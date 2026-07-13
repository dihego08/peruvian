<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellPaymentController extends Controller
{
    /**
     * Lista ventas con filtros (fecha, pago, documento, cliente)
     * Compatible con la lógica de clsVenta::lista_ventas() / buscar_por_fecha()
     */
    public function index(Request $request)
    {
        $query = DB::table('ventas_cabecera as vc')
            ->leftJoin('person as pe', 'pe.id', '=', 'vc.id_person')
            ->leftJoin('p', 'p.id', '=', 'vc.id_estado_pago')
            ->leftJoin('d', 'd.id', '=', 'vc.id_estado_entrega')
            ->leftJoin('kind_doc as k', 'k.id', '=', 'vc.tipo_documento')
            ->select([
                'vc.codigo_venta',
                'vc.tipo_documento',
                'vc.id_person',
                'vc.detraccion',
                'vc.detraccion_p',
                'vc.detraccion_paga',
                'vc.valor_pagar',
                'vc.pagado',
                'vc.a_cuenta',
                'vc.subtotal',
                'vc.igv',
                'vc.total',
                'vc.id_estado_pago',
                DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion"),
                DB::raw("COALESCE(pe.name, vc.ruc_add) as person"),
                'p.name as pago',
                'd.name as entrega',
                'k.tipo_documento as tipo_documento_nombre',
            ])
            ->orderBy('vc.fecha_emision', 'desc');

        // Filtro por rango de fechas
        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('vc.fecha_emision', [$request->desde, $request->hasta]);
        }

        // Filtro por tipo de pago
        if ($request->filled('tipos_pago')) {
            if ($request->tipos_pago == -1) {
                // Pendiente de pago = tiene deuda
                $query->where('vc.a_cuenta', '>', 0);
            } else {
                $query->where('vc.id_estado_pago', $request->tipos_pago);
            }
        }

        // Filtro por tipo de documento
        if ($request->filled('tipos_documento') && $request->tipos_documento != 0) {
            $query->where('vc.tipo_documento', $request->tipos_documento);
        }

        // Filtro por cliente
        if ($request->filled('combo_cliente') && $request->combo_cliente != 0) {
            $query->where('vc.id_person', $request->combo_cliente);
        }

        $records = $query->limit(500)->get();

        return response()->json([
            'Result'  => 'OK',
            'Records' => $records,
            'totales' => [
                'total_general' => $records->sum('valor_pagar'),
                'total_adeuda'  => $records->sum('a_cuenta'),
            ]
        ]);
    }

    /**
     * Historial de pagos de una venta específica (tabla pagos)
     * Equivale a clsVenta::historial_pago()
     */
    public function paymentHistory($codigo_venta)
    {
        $pagos = DB::table('pagos')
            ->where('codigo_venta', $codigo_venta)
            ->orderBy('fecha_creacion', 'asc')
            ->select([
                'id',
                'codigo_venta',
                'id_person',
                'banco',
                'concepto',
                'total',
                'pago',
                'deuda',
                DB::raw("DATE(fecha_creacion) as fecha_creacion"),
            ])
            ->get();

        return response()->json([
            'Result'  => 'OK',
            'Records' => $pagos,
        ]);
    }

    /**
     * Registrar un pago contra una venta
     * Equivale a clsVenta::actualizar_pago()
     */
    public function storePayment(Request $request, $codigo_venta)
    {
        $request->validate([
            'monto_pagado' => 'required|numeric|min:0.01',
            'fecha'        => 'required|date',
            'banco'        => 'nullable|string|max:100',
            'concepto'     => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Obtener cabecera para calcular nueva deuda
            $venta = DB::table('ventas_cabecera')
                ->where('codigo_venta', $codigo_venta)
                ->first();

            if (!$venta) {
                return response()->json(['Result' => 'ERROR', 'message' => 'Venta no encontrada'], 404);
            }

            $montoPagado = (float) $request->monto_pagado;
            $adeudaActual = (float) $venta->a_cuenta;
            $nuevaDeuda   = max(0, $adeudaActual - $montoPagado);

            // Insertar registro en tabla pagos
            DB::table('pagos')->insert([
                'codigo_venta'   => $codigo_venta,
                'id_person'      => $venta->id_person,
                'total'          => $venta->valor_pagar,
                'pago'           => $montoPagado,
                'deuda'          => $nuevaDeuda,
                'banco'          => $request->banco ?? '',
                'concepto'       => $request->concepto ?? '',
                'fecha_creacion' => $request->fecha,
            ]);

            // Actualizar a_cuenta y pagado en ventas_cabecera
            $nuevoPagado = (float) $venta->pagado + $montoPagado;
            DB::table('ventas_cabecera')
                ->where('codigo_venta', $codigo_venta)
                ->update([
                    'pagado'  => $nuevoPagado,
                    'a_cuenta' => $nuevaDeuda,
                ]);

            DB::commit();

            return response()->json([
                'Result'     => 'OK',
                'nueva_deuda' => $nuevaDeuda,
                'pagado'     => $nuevoPagado,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar un registro individual del historial de pagos
     */
    public function deletePayment(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $pago = DB::table('pagos')->where('id', $id)->first();
            if (!$pago) {
                return response()->json(['Result' => 'ERROR', 'message' => 'Pago no encontrado'], 404);
            }

            // Revertir a_cuenta y pagado
            DB::table('ventas_cabecera')
                ->where('codigo_venta', $pago->codigo_venta)
                ->update([
                    'pagado'   => DB::raw("GREATEST(0, pagado - {$pago->pago})"),
                    'a_cuenta' => DB::raw("a_cuenta + {$pago->pago}"),
                ]);

            DB::table('pagos')->where('id', $id)->delete();

            DB::commit();
            return response()->json(['Result' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Tipos de documento (kind_doc) para el filtro
     */
    public function tiposDocumento()
    {
        $tipos = DB::table('kind_doc')->select('id', 'tipo_documento')->get();
        return response()->json(['Result' => 'OK', 'Records' => $tipos]);
    }
}
