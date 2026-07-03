<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ====== PEDIDOS (ORDERS) ====== //

    public function getOrders()
    {
        $orders = DB::table('order_cabecera as oc')
            ->join('person as p', 'p.id', '=', 'oc.person_id')
            ->leftJoin(DB::raw('(
                SELECT
                    d.codigo_cabecera,
                    SUM(d.total) AS totalp,
                    SUBSTRING_INDEX(GROUP_CONCAT(d.modelo ORDER BY d.id ASC), \',\', 1) AS modelo,
                    SUBSTRING_INDEX(GROUP_CONCAT(prod.image ORDER BY d.id ASC), \',\', 1) AS imagen,
                    SUBSTRING_INDEX(GROUP_CONCAT(prod.name ORDER BY d.id ASC), \',\', 1) AS producto
                FROM order_detalle_2 d
                INNER JOIN product prod ON prod.code = d.modelo
                GROUP BY d.codigo_cabecera
            ) AS det'), 'det.codigo_cabecera', '=', 'oc.codigo')
            ->select([
                'oc.codigo',
                'oc.num_contrato',
                'oc.nombre_modelo',
                'oc.comentario',
                'oc.fecha_creacion',
                'oc.fecha_entrega',
                'oc.fecha_entrega_real',
                'oc.tiempo_entrega',
                'oc.estado',
                'oc.total',
                'oc.imagen_alt',
                'p.name',
                DB::raw('DATEDIFF(oc.fecha_entrega, CURDATE()) AS dias_restantes'),
                'det.modelo AS codigo_modelo',
                'det.producto',
                'det.imagen',
                'det.totalp',
                DB::raw('IFNULL((SELECT GROUP_CONCAT(DISTINCT g.num_guia SEPARATOR \' - \') FROM guia_detalle gd JOIN guia_cabecera g ON g.id = gd.id_guia WHERE gd.pedido LIKE CONCAT(\'%\', oc.codigo, \'%\')), oc.guia_remision) AS guia_remision'),
                DB::raw('IFNULL((SELECT GROUP_CONCAT(DISTINCT v.codigo_venta SEPARATOR \' - \') FROM ventas_cabecera v WHERE v.pedido_cod LIKE CONCAT(\'%\', oc.codigo, \'%\')), \'\') AS codigo_venta'),
            ])
            ->orderByRaw('CAST(oc.codigo AS UNSIGNED) DESC')
            ->limit(200)
            ->get();

        return response()->json([
            'Result' => 'OK',
            'Records' => $orders
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/products');
            $file->move($destinationPath, $filename);
            return response()->json([
                'Result' => 'OK',
                'filename' => $filename
            ]);
        }

        return response()->json([
            'Result' => 'ERROR',
            'message' => 'No se pudo cargar la imagen'
        ], 400);
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'person_id'       => 'required|integer',
            'fecha_desde'     => 'required|date',
            'tiempo_entrega'  => 'required|integer|min:1',
            'imagen_alt'      => 'nullable|string',
            'rows'            => 'required|array|min:1',
            'rows.*.modelo'   => 'required|string',
            'rows.*.color'    => 'nullable|string',
            'rows.*.sizes'    => 'required|array',
            'rows.*.headers'  => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // ===== Generar código de orden (lógica legacy usando tabla aux) =====
            $auxRow = DB::table('aux')->where('tabla', 'order')->first();
            $auxId  = $auxRow ? $auxRow->id : 1;
            $codigo = date('y') . str_pad($auxId, 2, '0', STR_PAD_LEFT);

            // Calcular fecha de entrega estimada (días hábiles, sin domingos)
            $fechaEntrega = $this->calcularFechaEntrega(
                $request->input('fecha_desde'),
                $request->input('tiempo_entrega')
            );

            // ===== Insertar cabecera =====
            $order = new Order();
            $order->codigo          = $codigo;
            $order->person_id       = $request->input('person_id');
            $order->fecha_creacion  = $request->input('fecha_desde');
            $order->tiempo_entrega  = $request->input('tiempo_entrega');
            $order->fecha_entrega   = $fechaEntrega;
            $order->estado          = 0;
            $order->num_contrato    = $request->input('num_contrato');
            $order->nombre_modelo   = $request->input('nombre_producto');
            $order->comentario      = $request->input('comentario');
            $order->imagen_alt      = $request->input('imagen_alt');
            $order->save();

            $totalGeneral = $this->insertOrderDetails($codigo, $request->input('rows'));

            // Actualizar total en cabecera
            DB::table('order_cabecera')
                ->where('codigo', $codigo)
                ->update(['total' => $totalGeneral]);

            // Incrementar contador en tabla aux
            DB::table('aux')->where('tabla', 'order')->increment('id');

            DB::commit();

            return response()->json([
                'Result'  => 'OK',
                'codigo'  => $codigo,
                'message' => 'Orden de pedido registrada correctamente'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'Result'  => 'ERROR',
                'message' => 'Error al guardar la orden',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getOrderDetail($codigo)
    {
        $cabecera = DB::table('order_cabecera as oc')
            ->join('person as p', 'p.id', '=', 'oc.person_id')
            ->where('oc.codigo', $codigo)
            ->select('oc.*', 'p.name as cliente')
            ->first();

        if (!$cabecera) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Orden no encontrada'], 404);
        }

        $detalles = DB::table('order_detalle_2')
            ->where('codigo_cabecera', $codigo)
            ->get();

        return response()->json([
            'Result'   => 'OK',
            'cabecera' => $cabecera,
            'detalles' => $detalles
        ]);
    }

    public function updateOrderStatus(Request $request, $codigo)
    {
        $request->validate(['estado' => 'required|integer']);
        DB::table('order_cabecera')
            ->where('codigo', $codigo)
            ->update(['estado' => $request->input('estado')]);
        return response()->json(['Result' => 'OK']);
    }

    public function deleteOrder($codigo)
    {
        DB::beginTransaction();
        try {
            DB::table('order_detalle_2')->where('codigo_cabecera', $codigo)->delete();
            DB::table('order_cabecera')->where('codigo', $codigo)->delete();
            // No decrementamos aux para no desincronizar con registros legacy
            DB::commit();
            return response()->json(['Result' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'error' => $e->getMessage()], 500);
        }
    }

    /** Editar pedido (legacy: edit_order_pedido / edit_order). */
    public function updateOrder(Request $request, $codigo)
    {
        $cabecera = DB::table('order_cabecera')->where('codigo', $codigo)->first();
        if (!$cabecera) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Orden no encontrada'], 404);
        }

        $request->validate([
            'person_id'       => 'required|integer',
            'fecha_desde'     => 'required|date',
            'tiempo_entrega'  => 'required|integer|min:1',
            'imagen_alt'      => 'nullable|string',
            'rows'            => 'required|array|min:1',
            'rows.*.modelo'   => 'required|string',
            'rows.*.color'    => 'nullable|string',
            'rows.*.sizes'    => 'required|array',
            'rows.*.headers'  => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $fechaEntrega = $this->calcularFechaEntrega(
                $request->input('fecha_desde'),
                (int) $request->input('tiempo_entrega')
            );

            DB::table('order_cabecera')->where('codigo', $codigo)->update([
                'num_contrato'   => $request->input('num_contrato'),
                'tiempo_entrega' => $request->input('tiempo_entrega'),
                'person_id'      => $request->input('person_id'),
                'fecha_creacion' => $request->input('fecha_desde'),
                'fecha_entrega'  => $fechaEntrega,
                'comentario'     => $request->input('comentario'),
                'imagen_alt'     => $request->input('imagen_alt'),
                'nombre_modelo'  => $request->input('nombre_producto'),
            ]);

            DB::table('order_detalle_2')->where('codigo_cabecera', $codigo)->delete();

            $totalGeneral = $this->insertOrderDetails($codigo, $request->input('rows'));

            DB::table('order_cabecera')->where('codigo', $codigo)->update(['total' => $totalGeneral]);
            DB::table('order_detalle_2')->whereNull('modelo')->delete();

            DB::commit();

            return response()->json([
                'Result'  => 'OK',
                'codigo'  => $codigo,
                'message' => 'Orden modificada correctamente',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'Result'  => 'ERROR',
                'message' => 'Error al modificar la orden',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /** Detalle para avance de producción (legacy: lista_detalle_produccion). */
    public function getProductionDetail($codigo)
    {
        $cabecera = DB::table('order_cabecera')->where('codigo', $codigo)->first();
        if (!$cabecera) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Orden no encontrada'], 404);
        }

        $detalles = DB::table('order_detalle_2')
            ->where('codigo_cabecera', $codigo)
            ->get();

        return response()->json([
            'Result'             => 'OK',
            'Records'            => $detalles,
            'fecha_entrega'      => $cabecera->fecha_entrega,
            'fecha_entrega_real' => $cabecera->fecha_entrega_real,
            'guia_remision'      => $cabecera->guia_remision,
            'num_contrato'       => $cabecera->num_contrato,
            'nombre_modelo'      => $cabecera->nombre_modelo,
            'fecha_creacion'     => $cabecera->fecha_creacion,
        ]);
    }

    /** Guardar avance de producción (legacy: new_produccion_order_pedido / actualizar_order_produccion). */
    public function updateProduction(Request $request, $codigo)
    {
        $cabecera = DB::table('order_cabecera')->where('codigo', $codigo)->first();
        if (!$cabecera) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Orden no encontrada'], 404);
        }

        $request->validate([
            'fecha_desde'    => 'nullable|date',
            'fecha_estimada' => 'nullable|date',
            'fecha_entrega'  => 'nullable|date',
            'n_contrato'     => 'nullable|string',
            'guia'           => 'nullable|string',
            'nombre_modelo'  => 'nullable|string',
            'rows'           => 'required|array|min:1',
            'rows.*.id'      => 'required|integer',
            'rows.*.produced' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            DB::table('order_cabecera')->where('codigo', $codigo)->update([
                'fecha_creacion'     => $request->input('fecha_desde'),
                'num_contrato'       => $request->input('n_contrato'),
                'fecha_entrega_real' => $request->input('fecha_entrega'),
                'fecha_entrega'      => $request->input('fecha_estimada'),
                'guia_remision'      => $request->input('guia'),
                'nombre_modelo'      => $request->input('nombre_modelo'),
            ]);

            $prodCols = ['p2','p4','p6','p8','p10','p12','p14','p16','ps','pm','pl','pxl','pxxl'];

            foreach ($request->input('rows') as $row) {
                $produced = $row['produced'];
                $ptotal = 0;
                $update = [];
                foreach ($prodCols as $i => $col) {
                    $val = isset($produced[$i]) && $produced[$i] !== '' ? (int) $produced[$i] : 0;
                    $update[$col] = $val;
                    $ptotal += $val;
                }
                $update['ptotal'] = $ptotal;

                DB::table('order_detalle_2')->where('id', $row['id'])->update($update);
            }

            DB::commit();

            return response()->json(['Result' => 'OK', 'message' => 'Avance de producción guardado']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'Result'  => 'ERROR',
                'message' => 'Error al guardar producción',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteOrderDetail($id)
    {
        DB::beginTransaction();
        try {
            $detail = DB::table('order_detalle_2')->where('id', $id)->first();
            if (!$detail) {
                return response()->json(['Result' => 'ERROR', 'message' => 'Detalle no encontrado'], 404);
            }

            DB::table('order_detalle_2')->where('id', $id)->delete();
            DB::table('order_cabecera')
                ->where('codigo', $detail->codigo_cabecera)
                ->decrement('total', (int) ($detail->total ?? 0));

            DB::commit();
            return response()->json(['Result' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'error' => $e->getMessage()], 500);
        }
    }

    private function insertOrderDetails(string $codigo, array $rows): int
    {
        $colNames = ['_2','_4','_6','_8','_10','_12','_14','_16','s','m','l','xl','xxl'];
        $totalGeneral = 0;

        foreach ($rows as $row) {
            $sizes   = $row['sizes'];
            $headers = $row['headers'];
            $rowTotal = array_sum(array_map(fn ($v) => (int) ($v ?: 0), $sizes));

            $detail = new OrderDetail();
            $detail->codigo_cabecera = $codigo;
            $detail->modelo  = $row['modelo'];
            $detail->color   = $row['color'] ?? null;
            $detail->total   = $rowTotal;

            for ($i = 0; $i < 13; $i++) {
                $col = $colNames[$i];
                $detail->$col = isset($sizes[$i]) && $sizes[$i] !== '' ? (int) $sizes[$i] : null;
            }

            for ($i = 1; $i <= 13; $i++) {
                $nkey = 'n' . $i;
                $detail->$nkey = $headers[$i - 1] ?? null;
            }

            $detail->save();
            $totalGeneral += $rowTotal;
        }

        return $totalGeneral;
    }

    // ===== Helper: calcular fecha de entrega ignorando domingos y feriados peruanos =====
    private function calcularFechaEntrega(string $fechaInicio, int $dias): string
    {
        $feriados = $this->feriadosPeru();
        $fecha    = new \DateTime($fechaInicio);

        $contados = 0;
        while ($contados < $dias) {
            $fecha->modify('+1 day');
            $dow     = (int)$fecha->format('N'); // 7 = domingo
            $fechaStr = $fecha->format('Y-m-d');
            if ($dow !== 7 && !in_array($fechaStr, $feriados)) {
                $contados++;
            }
        }

        return $fecha->format('Y-m-d');
    }

    private function feriadosPeru(): array
    {
        $anio = date('Y');
        return [
            "$anio-01-01", "$anio-05-01", "$anio-06-24", "$anio-06-29",
            "$anio-06-30", "$anio-07-28", "$anio-07-29", "$anio-08-15",
            "$anio-08-30", "$anio-10-08", "$anio-11-01", "$anio-12-25",
        ];
    }
}
