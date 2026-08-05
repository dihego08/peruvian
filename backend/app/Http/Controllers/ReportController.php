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
                    'y' => (float)$item->total
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
                    'y' => (float)$item->total
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

    public function anularSale($codigo)
    {
        // El sistema legacy ponía estado_anulado = 1, o id_estado_entrega = 4 (anulado).
        // Actualizaremos id_estado_entrega a 4 que es el código legacy para anulado, y estado_anulado a 1
        $updated = DB::table('ventas_cabecera')
            ->where('codigo_venta', $codigo)
            ->update([
                'id_estado_entrega' => 4,
                'estado_anulado' => 1
            ]);

        return response()->json(['Result' => 'OK']);
    }
}
