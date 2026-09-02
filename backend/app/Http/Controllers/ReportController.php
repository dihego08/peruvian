<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Equivalente a lista_ventas_s
     * Filtrado por rango de fechas, estado de pago, entrega o cliente.
     */
    public function getSellsSunat(Request $request)
    {
        $filtro = $request->query('filtro', 'ninguno'); // ninguno, pago, entrega, cliente
        $codigo = $request->query('codigo', 0);
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');

        $query = DB::table('ventas_cabecera as vc')
            ->join('person as pe', 'vc.id_person', '=', 'pe.id')
            ->join('p as p', 'vc.id_estado_pago', '=', 'p.id')
            ->join('d as d', 'vc.id_estado_entrega', '=', 'd.id')
            ->join('f as f', 'vc.id_forma_pago', '=', 'f.id')
            ->join('kind_doc as k', 'vc.tipo_documento', '=', 'k.id')
            ->select([
                'vc.*',
                DB::raw('DATE(vc.fecha_emision) as fecha_creacion'),
                'pe.name as person',
                'p.name as pago',
                'd.name as entrega',
                'f.name as tipo_pago',
                'k.tipo_documento as tipo_doc_nombre'
            ])
            ->whereIn('vc.tipo_documento', [1, 2]);

        if ($filtro === 'pago') {
            $query->where('p.id', $codigo);
        } elseif ($filtro === 'entrega') {
            $query->where('d.id', $codigo);
        } elseif ($filtro === 'cliente') {
            $query->where('pe.id', $codigo);
        }

        if ($desde && $hasta) {
            $query->whereBetween(DB::raw('DATE(vc.fecha_emision)'), [$desde, $hasta]);
        }

        $sells = $query->orderBy('vc.fecha_emision', 'desc')->get();

        return response()->json([
            'Result' => 'OK',
            'Records' => $sells
        ]);
    }

    /**
     * Equivalente a lista_rep_ventas_cliente, lista_gra_ventas_cliente, lista_gra_ventas_producto
     */
    public function getVentasCliente(Request $request)
    {
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');

        // Validar fechas
        if (!$desde || !$hasta) {
            $hasta = date('Y-m-d');
            $desde = date('Y-m-d', strtotime('-1 month'));
        }

        // 1. Lista de ventas por cliente
        $lista = DB::table('ventas_detalle as vd')
            ->join('ventas_cabecera as vc', 'vd.codigo_venta_cabecera', '=', 'vc.codigo_venta')
            ->join('product as m', 'vd.id_producto', '=', 'm.id')
            ->join('person as p', 'vc.id_person', '=', 'p.id')
            ->select([
                'p.name as cliente',
                'vc.fecha_emision as fff',
                DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha"),
                'vd.cantidad',
                'm.code as modelo',
                DB::raw('(vd.precio_unitario * vd.cantidad) as subtotal')
            ])
            ->whereBetween(DB::raw('DATE(vc.fecha_emision)'), [$desde, $hasta])
            ->whereNull('vc.estado_anulado')
            ->orderBy('vc.fecha_emision', 'desc')
            ->get();

        // 2. Gráfico: Ventas x Cliente
        $graficoClientes = DB::table('ventas_detalle as vd')
            ->join('ventas_cabecera as vc', 'vd.codigo_venta_cabecera', '=', 'vc.codigo_venta')
            ->join('person as p', 'vc.id_person', '=', 'p.id')
            ->select([
                'p.name as cliente',
                DB::raw('SUM(vd.precio_unitario * vd.cantidad) as total')
            ])
            ->whereBetween(DB::raw('DATE(vc.fecha_emision)'), [$desde, $hasta])
            ->whereNull('vc.estado_anulado')
            ->groupBy('p.name')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->cliente,
                    'y' => (float) $item->total
                ];
            });

        // 3. Gráfico: Ventas x Modelo
        $graficoModelos = DB::table('ventas_detalle as vd')
            ->join('ventas_cabecera as vc', 'vd.codigo_venta_cabecera', '=', 'vc.codigo_venta')
            ->join('product as m', 'vd.id_producto', '=', 'm.id')
            ->select([
                'm.code as modelo',
                DB::raw('SUM(vd.precio_unitario * vd.cantidad) as total')
            ])
            ->whereBetween(DB::raw('DATE(vc.fecha_emision)'), [$desde, $hasta])
            ->whereNull('vc.estado_anulado')
            ->groupBy('m.code')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->modelo,
                    'y' => (float) $item->total
                ];
            });

        return response()->json([
            'Result' => 'OK',
            'Records' => $lista,
            'graficoClientes' => $graficoClientes,
            'graficoModelos' => $graficoModelos
        ]);
    }

    /**
     * Equivalente a lista_rep_ventas_mes y lista_gra_ventas_mes
     */
    public function getVentasMensuales(Request $request)
    {
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        // Si se proveen fechas (para el gráfico)
        if ($desde && $hasta) {
            $queryGra = DB::table('ventas_cabecera')
                ->select([
                    DB::raw('MONTH(fecha_emision) as mes'),
                    DB::raw('SUM(subtotal) as total')
                ])
                ->whereBetween(DB::raw('DATE(fecha_emision)'), [$desde, $hasta]);

            $datosGrafico = $queryGra->groupBy(DB::raw('MONTH(fecha_emision)'))
                ->orderBy(DB::raw('MONTH(fecha_emision)'), 'asc')
                ->get();

            $valores = array_fill(0, 12, 0.00);
            foreach ($datosGrafico as $dg) {
                $valores[$dg->mes - 1] = (float) $dg->total;
            }

            return response()->json([
                'Result' => 'OK',
                'meses' => $meses,
                'totales' => $valores
            ]);
        }

        // Si no hay fechas, devolver la tabla sumarizada de todos los años
        $aniosRaw = DB::table('ventas_cabecera')
            ->select(DB::raw('YEAR(fecha_emision) as anio'))
            ->distinct()
            ->orderBy('anio', 'asc')
            ->get();

        $anios = $aniosRaw->pluck('anio');
        $values = [];

        foreach ($anios as $anio) {
            $mesesData = DB::table('ventas_cabecera')
                ->select([
                    DB::raw('MONTH(fecha_emision) as mes'),
                    DB::raw('SUM(subtotal) as total')
                ])
                ->whereYear('fecha_emision', $anio)
                ->groupBy(DB::raw('MONTH(fecha_emision)'))
                ->orderBy(DB::raw('MONTH(fecha_emision)'), 'asc')
                ->get();

            $values[$anio] = $mesesData;
        }

        return response()->json([
            'Result' => 'OK',
            'Records' => $values,
            'anios' => $aniosRaw
        ]);
    }

    /**
     * Equivalente a lista_rep_ventas_guia_pedido
     */
    public function getVentasCruzado(Request $request)
    {
        $desde = $request->query('desde', date('Y-m-d', strtotime('-1 month')));
        $hasta = $request->query('hasta', date('Y-m-d'));

        $records = DB::table('ventas_cabecera')
            ->select([
                DB::raw("DATE_FORMAT(fecha_emision, '%d-%m-%Y') as fecha"),
                'codigo_venta as venta',
                'guia',
                'pedido_cod as pedido'
            ])
            ->whereBetween(DB::raw('DATE(fecha_emision)'), [$desde, $hasta])
            ->orderBy('codigo_venta', 'desc')
            ->get();

        return response()->json([
            'Result' => 'OK',
            'Records' => $records
        ]);
    }

    public function updateSale(Request $request, $codigo)
    {
        $updated = DB::table('ventas_cabecera')
            ->where('codigo_venta', $codigo)
            ->update([
                'guia' => $request->input('guia'),
                'fecha_pago' => $request->input('fecha_pago') ?: null,
                'entidad' => $request->input('entidad'),
                'fecha_detraccion' => $request->input('fecha_det') ?: null
            ]);

        return response()->json(['Result' => 'OK']);
    }

    public function anularSale(Request $request, $codigo, \App\Services\SunatService $sunatService)
    {
        $motivo = $request->input('motivo', 'Anulación de la operación');
        $codMotivo = $request->input('cod_motivo', '01');

        $cabecera = DB::table('ventas_cabecera as vc')
            ->leftJoin('person as pe', 'pe.id', '=', 'vc.id_person')
            ->where('vc.codigo_venta', $codigo)
            ->select([
                'vc.*',
                'pe.name as person',
                'pe.no as ruc',
                'pe.address1 as direccion',
            ])
            ->first();

        if (!$cabecera) {
            return response()->json(['Result' => 'ERROR', 'Message' => 'Venta no encontrada'], 404);
        }

        if ($cabecera->estado_anulado == 1) {
            return response()->json(['Result' => 'ERROR', 'Message' => 'Venta ya está anulada'], 400);
        }

        // Fetch details
        $detalle = DB::table('ventas_detalle as vd')
            ->leftJoin('product as pr', 'pr.id', '=', 'vd.id_producto')
            ->where('vd.codigo_venta_cabecera', $codigo)
            ->select([
                'vd.*',
                'pr.name as producto_nombre',
                'pr.code as producto_codigo',
            ])
            ->get();

        if ($detalle->isEmpty()) {
            return response()->json(['Result' => 'ERROR', 'Message' => 'La venta no tiene items'], 400);
        }

        // Get correlative for NC
        $aux = DB::table('aux')->where('tabla', 'nota_credito')->first();
        if (!$aux) {
            return response()->json(['Result' => 'ERROR', 'Message' => 'Correlativo no encontrado'], 500);
        }
        $correlativoNc = $aux->id + 1;

        // Build objects
        $customerData = [
            'ruc' => $cabecera->ruc ?? $cabecera->ruc_add ?? '',
            'razon_social' => $cabecera->person ?? '-',
        ];

        $client = $sunatService->buildClient($customerData);
        $company = $sunatService->buildCompany();
        $items = $sunatService->buildItems($detalle->all());

        $see = $sunatService->createSee();
        $note = $sunatService->buildCreditNote((array) $cabecera, $items, $client, $company, $codMotivo, $motivo, $correlativoNc);

        $sendResult = $sunatService->sendCreditNote($note, $see);

        if (!$sendResult['success']) {
            return response()->json([
                'Result' => 'ERROR',
                'Message' => 'Error al enviar a SUNAT: ' . $sendResult['message'],
                'Code' => $sendResult['code']
            ], 500);
        }

        $code = $sendResult['code'];
        $aceptado = ($code === 0 || ($code >= 1 && $code < 2000));

        if (!$aceptado) {
            return response()->json([
                'Result' => 'RECHAZADO',
                'Message' => 'SUNAT rechazó la nota de crédito: ' . $sendResult['message'],
                'Code' => $code
            ], 400);
        }

        DB::beginTransaction();
        try {
            DB::table('ventas_cabecera')
                ->where('codigo_venta', $codigo)
                ->update([
                    'estado_anulado' => 1,
                    'id_estado_entrega' => 4, // 4 = Anulado en bd legacy
                    'motivo' => $motivo,
                    'correlativo_nc' => $correlativoNc,
                    'fecha_anulacion' => date("Y-m-d H:i:s"),
                    'codigo_sunat_nc' => $code,
                    'descripcion_sunat_nc' => $sendResult['message'],
                    'total' => 0,
                    'igv' => 0,
                    'detraccion_p' => 0,
                    'igv_p' => 0,
                    'subtotal' => 0,
                    'valor_pagar' => 0,
                    'a_cuenta' => 0
                ]);

            DB::table('aux')->where('tabla', 'nota_credito')->increment('id');
            DB::commit();

            return response()->json([
                'Result' => 'OK',
                'Message' => 'Nota de crédito aceptada por SUNAT',
                'Code' => $code,
                'NotaCredito' => $sendResult['notaName'] ?? null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'Message' => 'Error al actualizar base de datos: ' . $e->getMessage()], 500);
        }
    }
}
