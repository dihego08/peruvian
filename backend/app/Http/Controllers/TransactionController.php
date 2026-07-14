<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\SunatService;
use Dompdf\Dompdf;

class TransactionController extends Controller
{
    // ====== VENTAS (SALES) ====== //

    public function getCorrelativo(Request $request)
    {
        $tipo = $request->query('tipo_documento');
        $prefix = '';
        $tabla = '';

        if ($tipo == '1') {
            $tabla = 'boleta';
            $prefix = 'B001-';
        } elseif ($tipo == '2') {
            $tabla = 'factura';
            $prefix = 'F001-';
        } elseif ($tipo == '3') {
            $tabla = 'nota_pedido';
            $prefix = 'NP001-';
        } else {
            return response()->json(['correlativo' => '']);
        }

        $aux = DB::table('aux')->where('tabla', $tabla)->first();
        $nextId = $aux ? ((int)$aux->id + 1) : 1;

        return response()->json([
            'correlativo' => $prefix . $nextId
        ]);
    }

    /**
     * Enviar factura/venta a SUNAT usando Greenter (integra archivo legacy facturador_v3)
     */
    public function sendToSunat($codigo, SunatService $sunatService)
    {
        try {
            $see = $sunatService->createSee();

            $cabecera = DB::table('ventas_cabecera as vc')
                ->leftJoin('person as pe', 'pe.id', '=', 'vc.id_person')
                ->leftJoin('p', 'p.id', '=', 'vc.id_estado_pago')
                ->leftJoin('d', 'd.id', '=', 'vc.id_estado_entrega')
                ->leftJoin('f', 'f.id', '=', 'vc.id_forma_pago')
                ->leftJoin('kind_doc as k', 'k.id', '=', 'vc.tipo_documento')
                ->where('vc.codigo_venta', $codigo)
                ->select([
                    'vc.*',
                    DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_emision_fmt"),
                    DB::raw("DATE_FORMAT(vc.fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento_fmt"),
                    DB::raw("COALESCE(pe.name, vc.ruc_add) as person"),
                    'pe.no as ruc',
                    'pe.address1 as direccion',
                    'p.name as pago',
                    'd.name as entrega',
                    'f.name as tipo_pago',
                    'k.tipo_documento as tipo_doc_nombre',
                ])
                ->first();

            if (!$cabecera) {
                return response()->json(['Result' => 'ERROR', 'message' => 'Venta no encontrada'], 404);
            }

            $detalle = DB::table('ventas_detalle as vd')
                ->leftJoin('product as pr', 'pr.id', '=', 'vd.id_producto')
                ->where('vd.codigo_venta_cabecera', $codigo)
                ->select([
                    'vd.*',
                    'pr.name as producto_nombre',
                    'pr.code as producto_codigo',
                    'pr.image as producto_imagen'
                ])
                ->get();

            $customerData = [
                'ruc' => $cabecera->ruc ?? $cabecera->ruc_add ?? '',
                'razon_social' => $cabecera->person ?? '-',
            ];

            $client = $sunatService->buildClient($customerData);
            $company = $sunatService->buildCompany();
            $items = $sunatService->buildItems($detalle->all());
            $invoice = $sunatService->buildInvoice((array)$cabecera, $items, $client, $company);

            $sendResult = $sunatService->sendInvoice($invoice, $see);

            if ($sendResult['success']) {
                $code = $sendResult['code'];
                if ($code === 0) {
                    DB::table('ventas_cabecera')->where('codigo_venta', $codigo)->update(['envio_sunat' => 1]);
                    return response()->json(['Result' => 'SUCCESS']);
                } else if ($code >= 2000 && $code <= 3999) {
                    return response()->json([
                        'Result' => 'ERROR',
                        'message' => 'Comprobante rechazado: ' . ($sendResult['message'] ?? 'Sin mensaje')
                    ]);
                }
            }

            return response()->json([
                'Result' => 'ERROR',
                'code' => $sendResult['code'] ?? null,
                'message' => $sendResult['message'] ?? 'Error de conexión'
            ]);

        } catch (\Exception $e) {
            Log::error('sendToSunat error: ' . $e->getMessage());
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }
    public function getSells()
    {
        // Consulta la tabla legacy ventas_cabecera con los mismos joins que clsVenta.php lista_ventas()
        $sells = DB::table('ventas_cabecera as vc')
            ->leftJoin('person as pe', 'pe.id', '=', 'vc.id_person')
            ->leftJoin('p', 'p.id', '=', 'vc.id_estado_pago')
            ->leftJoin('d', 'd.id', '=', 'vc.id_estado_entrega')
            ->leftJoin('f', 'f.id', '=', 'vc.id_forma_pago')
            ->leftJoin('kind_doc as k', 'k.id', '=', 'vc.tipo_documento')
            ->select([
                'vc.*',
                DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion"),
                DB::raw("COALESCE(pe.name, vc.ruc_add) as person"),
                'p.name as pago',
                'd.name as entrega',
                'f.name as tipo_pago',
                'k.tipo_documento as tipo_doc_nombre',
            ])
            ->orderBy('vc.fecha_emision', 'desc')
            ->limit(500)
            ->get();

        return response()->json([
            'Result' => 'OK',
            'Records' => $sells,
        ]);
    }

    public function getSellDetail($codigo_venta)
    {
        // Cabecera
        $cabecera = DB::table('ventas_cabecera as vc')
            ->leftJoin('person as pe', 'pe.id', '=', 'vc.id_person')
            ->leftJoin('p', 'p.id', '=', 'vc.id_estado_pago')
            ->leftJoin('d', 'd.id', '=', 'vc.id_estado_entrega')
            ->leftJoin('f', 'f.id', '=', 'vc.id_forma_pago')
            ->leftJoin('kind_doc as k', 'k.id', '=', 'vc.tipo_documento')
            ->where('vc.codigo_venta', $codigo_venta)
            ->select([
                'vc.*',
                DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_emision_fmt"),
                DB::raw("DATE_FORMAT(vc.fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento_fmt"),
                DB::raw("COALESCE(pe.name, vc.ruc_add) as person"),
                'pe.no as ruc',
                'pe.address1 as direccion',
                'p.name as pago',
                'd.name as entrega',
                'f.name as tipo_pago',
                'k.tipo_documento as tipo_doc_nombre',
            ])
            ->first();

        if (!$cabecera) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Venta no encontrada'], 404);
        }

        // Detalle de productos
        $detalle = DB::table('ventas_detalle as vd')
            ->leftJoin('product as pr', 'pr.id', '=', 'vd.id_producto')
            ->where('vd.codigo_venta_cabecera', $codigo_venta)
            ->select([
                'vd.*',
                'pr.name as producto_nombre',
                'pr.code as producto_codigo',
                'pr.image as producto_imagen',
                DB::raw('ROUND(vd.precio_unitario * vd.cantidad, 2) as subtotal_item'),
            ])
            ->get();

        return response()->json([
            'Result' => 'OK',
            'cabecera' => $cabecera,
            'detalle' => $detalle,
        ]);
    }

    public function downloadSellPdf($codigo_venta)
    {
        $cabecera = DB::table('ventas_cabecera as vc')
            ->leftJoin('person as pe', 'pe.id', '=', 'vc.id_person')
            ->leftJoin('p', 'p.id', '=', 'vc.id_estado_pago')
            ->leftJoin('d', 'd.id', '=', 'vc.id_estado_entrega')
            ->leftJoin('f', 'f.id', '=', 'vc.id_forma_pago')
            ->leftJoin('kind_doc as k', 'k.id', '=', 'vc.tipo_documento')
            ->where('vc.codigo_venta', $codigo_venta)
            ->select([
                'vc.*',
                DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_emision"),
                DB::raw("DATE_FORMAT(vc.fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento"),
                DB::raw("COALESCE(pe.name, vc.ruc_add) as person"),
                'pe.no as ruc',
                'pe.address1 as direccion',
                'p.name as pago',
                'd.name as entrega',
                'f.name as tipo_pago',
                'k.tipo_documento as tipo_doc_nombre',
            ])
            ->first();

        if (!$cabecera) {
            return response('Venta no encontrada', 404);
        }

        $detalle = DB::table('ventas_detalle as vd')
            ->leftJoin('product as pr', 'pr.id', '=', 'vd.id_producto')
            ->where('vd.codigo_venta_cabecera', $codigo_venta)
            ->select([
                'vd.*',
                'pr.name as producto_nombre',
                'pr.code as producto_codigo',
            ])
            ->get();

        $cliente = [
            'razon_social' => $cabecera->person ?? '-',
            'ruc' => $cabecera->ruc ?? '',
            'direccion' => $cabecera->direccion ?? '',
        ];

        $qrDir = storage_path('app/temp_qr');
        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0777, true);
        }

        require_once base_path('../core/app/view/phpqrcode/qrlib.php');
        $qrFile = $qrDir . '/qr_' . preg_replace('/[^A-Za-z0-9_-]/', '', $codigo_venta) . '.png';
        $qrText = url('/api/transactions/sells/' . urlencode($codigo_venta) . '/pdf');
        \QRcode::png($qrText, $qrFile, 'L', 10, 3);
        $qrDataUri = file_exists($qrFile) ? 'data:image/png;base64,' . base64_encode(file_get_contents($qrFile)) : '';

        $logoPath = base_path('../frontend/public/assets/logo.png');
        if (!file_exists($logoPath)) {
            $logoPath = base_path('../frontend/public/assets/logo_2.png');
        }
        $logoDataUri = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';

        $ds = '';
        if ($cabecera->descuento > 0) {
            $ds = '<tr style="width: 200px;">'
                . '<td style="text-align: right;"><strong>Descuento: </strong></td>'
                . '<td style="padding: 10px; border-bottom: solid 1px;">S/ ' . $cabecera->descuento . '</td>'
                . '</tr>';
        }

        $tablaCredito = '';
        if ($cabecera->id_estado_pago == 4) {
            $tablaCredito = '<div style="border: solid 1px; border-radius: 10px; padding: 5px 10px; width: 680px; font-size: 9px; margin-top: 10px;">'
                . '<h5 style="margin-bottom: 0; width: 100%; display: block;">Información del Crédito</h5>'
                . '<table style="width: 680px; border-collapse: collapse;">'
                . '<tr><td style="width: 340px;"><table style="width: 100%;">'
                . '<tr><td style="padding: 5px; white-space: pre-wrap;"><strong style="font-size: 12px;">Monto neto pendiente de pago</strong></td></tr>'
                . '<tr><td style="padding: 5px;"><strong style="font-size: 12px;">Total de cuotas</strong></td></tr>'
                . '</table></td>'
                . '<td style="width: 340px;"><table style="width: 100%;">'
                . '<tr><td style="width: 100%; padding: 5px;">: <span>S/ ' . $cabecera->a_cuenta . '</span></td></tr>'
                . '<tr><td style="width: 100%; padding: 5px;">: ' . $cabecera->n_cuotas . '</td></tr>'
                . '</table></td></tr>'
                . '<tr><td colspan="2"><table style="width: 680px; border-top: solid 1px; border-collapse: collapse;">'
                . '<tr><td style="font-weight: bold; width: 33.33%;">Número de Cuota</td><td style="font-weight: bold; width: 33.33%;">Fecha Vencimiento</td><td style="font-weight: bold; width: 33.33%;">Monto</td></tr>'
                . '<tr><td>1</td><td>' . $cabecera->fecha_vencimiento . '</td><td>' . $cabecera->a_cuenta . '</td></tr>'
                . '</table></td></tr>'
                . '</table></div>';
        }

        $detalleTable = '<table id="tabla_detalles" style="border: solid 1px #eeeeee; text-align: center; margin-top: 10px; width: 780px; font-size: 10px; border-collapse: collapse;">'
            . '<tr style="border-bottom: solid 1px; background-color: #f7fafc;">'
            . '<th style="padding: 6px; border: 1px solid #e5e7eb;">Cantidad</th>'
            . '<th style="padding: 6px; border: 1px solid #e5e7eb;">Pedido</th>'
            . '<th style="padding: 6px; border: 1px solid #e5e7eb;">Modelo</th>'
            . '<th style="padding: 6px; border: 1px solid #e5e7eb;">Descripción</th>'
            . '<th style="padding: 6px; border: 1px solid #e5e7eb;">Valor Unitario</th>'
            . '<th style="padding: 6px; border: 1px solid #e5e7eb;">Valor Total</th>'
            . '</tr>';

        foreach ($detalle as $item) {
            $desc = (!empty($item->tipo) && !is_null($item->tipo)) ? $item->tipo : $item->producto_nombre;
            $pedidoCod = $item->pedido_cod ?: $cabecera->pedido_cod;
            $pUnitario = 0;
            if ($item->precio_unitario != 0) {
                $pUnitario = number_format(($item->precio_unitario + $item->precio_bordado / max(1, $item->cantidad)), 2);
            }
            $detalleTable .= '<tr>'
                . '<td style="width: 66px; padding: 5px; border: 1px solid #e5e7eb;"><p>' . $item->cantidad . ' ' . $item->unidad . '</p></td>'
                . '<td style="width: 66px; padding: 5px; border: 1px solid #e5e7eb;">' . $pedidoCod . '</td>'
                . '<td style="width: 66px; padding: 5px; border: 1px solid #e5e7eb;">' . $item->codigo_unidad . '</td>'
                . '<td style="width: 186px; padding: 5px; border: 1px solid #e5e7eb; text-align: left;">' . $desc . '</td>'
                . '<td style="width: 100px; padding: 5px; border: 1px solid #e5e7eb;">S/ ' . $pUnitario . '</td>'
                . '<td style="width: 100px; padding: 5px; border: 1px solid #e5e7eb;">S/ ' . number_format(($item->precio_unitario * $item->cantidad) + $item->precio_bordado, 2) . '</td>'
                . '</tr>';
        }

        if ($cabecera->descuento > 0) {
            $detalleTable .= '<tr>'
                . '<td style="width: 66px; padding: 5px; border: 1px solid #e5e7eb;"></td>'
                . '<td style="width: 66px; padding: 5px; border: 1px solid #e5e7eb;"></td>'
                . '<td style="width: 66px; padding: 5px; border: 1px solid #e5e7eb;"></td>'
                . '<td style="width: 186px; padding: 5px; border: 1px solid #e5e7eb; text-align: left;">' . strtoupper($cabecera->desc_descuento) . '</td>'
                . '<td style="width: 100px; padding: 5px; border: 1px solid #e5e7eb;">S/ ' . $cabecera->descuento . '</td>'
                . '<td style="width: 100px; padding: 5px; border: 1px solid #e5e7eb;">S/ ' . $cabecera->descuento . '</td>'
                . '</tr>';
        }
        $detalleTable .= '</table>';

        $totalLetras = null;
        try {
            $letras = @file_get_contents('https://dbusinessaqp.com/numero_2_letras/conversor.php?total=' . urlencode($cabecera->total));
            $totalLetras = $letras ? json_decode($letras) : null;
        } catch (\Exception $e) {
            $totalLetras = null;
        }

        $totalLetrasText = $totalLetras->letras ?? '';

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8" /><style>'
            . 'body{font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 0;}'
            . 'table{border-collapse: collapse; width: 100%;}'
            . 'td, th{padding: 5px; vertical-align: top;}'
            . '.header-table td{vertical-align: top;}'
            . '.small{font-size: 10px;}'
            . '</style></head><body>'
            . '<div style="width: 780px; margin: 0 auto;">'
            . '<table id="encabezado" style="margin-bottom: 25px; font-size: 11px; width: 780px;">'
            . '<tr>'
            . '<td style="padding: 5px; border-radius: 4px; width: 222px; text-align: left;">'
            . '<table style="width: 222px;">'
            . '<tr><td style="text-align: center; width: 400px;">' . ($logoDataUri ? '<img src="' . $logoDataUri . '" align="left" border="0" style="width: 150px;" />' : '') . '</td></tr>'
            . '<tr><td style="text-align: center; width: 222px; font-weight: bold; font-size: 20px;">PERUVIAN DRESS TPX S.A.C.</td></tr>'
            . '<tr><td style="text-align: left; width: 222px;"><strong>Dirección: </strong>CAL.BELEN MZA. B LOTE. 8 JERUSALEN - MARIANO - MELGAR - AREQUIPA - AREQUIPA</td></tr>'
            . '<tr><td style="text-align: left; width: 222px;"><strong>Celular.: </strong>958133948</td></tr>'
            . '<tr><td style="text-align: left; width: 222px;"><strong>Correo : </strong>omendoza@peruviandress.com</td></tr>'
            . '</table></td>'
            . '<td style="width: 44px;"></td>'
            . '<td style="padding: 5px; border-radius: 4px; width: 222px; text-align: center;">'
            . '<table style="width: 222px;">'
            . '<tr><td style="text-align: center; width: 222px;"><span style="font-weight: bold; font-size: 20px;">Factura Electrónica</span></td></tr>'
            . '<tr><td style="text-align: center; width: 222px; font-weight: bold;">R.U.C.: 20455175781</td></tr>'
            . '<tr><td style="text-align: center; width: 222px;">Nro. ' . $cabecera->codigo_venta . '</td></tr>'
            . '<tr><td style="text-align: center; width: 222px;">Nro. R.I. Emisor: 212321</td></tr>'
            . '<tr><td style="text-align: center; width: 222px;"><strong>Guía de Remisión:</strong> ' . $cabecera->guia . '</td></tr>'
            . '</table></td></tr></table>'
            . '<div style="border: solid 1px; border-radius: 10px; padding: 10px; width: 680px; margin-bottom: 12px;">'
            . '<table style="width: 680px; font-size: 11px;">'
            . '<tr><td style="width: 380px; white-space: pre-wrap;">'
            . '<table style="width: 380px;">'
            . '<tr><td style="padding: 5px; white-space: pre-wrap;"><strong>Razón Social: </strong><span style="white-space: pre-wrap;">' . nl2br(e($cliente['razon_social'])) . '</span></td></tr>'
            . '<tr><td style="padding: 5px;"><strong>Fecha Emisión: </strong>' . $cabecera->fecha_emision . '</td></tr>'
            . '<tr><td style="padding: 5px;"><strong>Tipo Moneda: </strong>SOLES</td></tr>'
            . '</table></td>'
            . '<td style="width: 285px;"><table style="width: 285px;">'
            . '<tr><td style="width: 285px; padding: 5px;"><strong>RUC: </strong>' . e($cliente['ruc']) . '</td></tr>'
            . '<tr><td style="width: 285px; padding: 5px;"><strong>Dirección: </strong>' . e($cliente['direccion']) . '</td></tr>'
            . '</table></td></tr>'
            . '</table></div>'
            . $detalleTable
            . '<table style="margin-top: 20px; width: 680px; font-size: 11px; border-collapse: collapse;">'
            . '<tr><td style="width: 565px; vertical-align: top;">'
            . '<strong>Son: ' . ucwords($totalLetrasText) . '</strong>'
            . '<p style="margin-top: 12px; border-bottom: solid 1px #333333; padding-bottom: 4px;"><strong>Información Adicional</strong></p>'
            . '<table style="width: 100%; font-size: 11px; border-collapse: collapse;">'
            . '<tr><td><strong>Tipo de Transación: </strong></td><td style="border-bottom: solid 1px;">' . e($cabecera->pago) . '</td></tr>'
            . '<tr><td><strong>Condición de Pago: </strong></td><td style="border-bottom: solid 1px;">' . e($cabecera->tipo_pago) . '</td></tr>'
            . '<tr><td><strong>Fecha de Vencimiento: </strong></td><td style="border-bottom: solid 1px;">' . e($cabecera->fecha_vencimiento) . '</td></tr>'
            . '</table></td>'
            . '<td style="width: 200px; vertical-align: top;">'
            . '<table style="width: 200px; font-size: 11px; border-collapse: collapse;">'
            . $ds
            . '<tr><td style="text-align: right;"><strong>Subtotal: </strong></td><td style="padding: 10px; border-bottom: solid 1px;">S/ ' . $cabecera->subtotal . '</td></tr>'
            . '<tr><td style="text-align: right;"><strong>I.G.V.: </strong></td><td style="padding: 10px; border-bottom: solid 1px;">S/ ' . $cabecera->igv . '</td></tr>'
            . '<tr><td style="text-align: right;"><strong>Total: </strong></td><td style="padding: 10px; border-bottom: solid 1px;">S/ ' . $cabecera->total . '</td></tr>'
            . '</table></td></tr></table>'
            . $tablaCredito
            . '<div style="border: solid 1px; border-radius: 10px; padding: 10px; width: 690px; margin-top: 20px; font-size: 11px;">'
            . '<p><strong>DATOS BANCARIOS (M.N. SOLES):</strong></p>'
            . '<div style="width: 690px; margin-top: 5px;"><span style="display: block;"><strong>CTA. CTE BANCO DE CREDITO DEL PERÚ: </strong> 2152497862044  /  C.C.I: 00221500249786204421</span></div>'
            . '<div style="width: 690px; margin-top: 5px;"><span style="display: block;"><strong>CTA. AHORROS BANCO CONTINENTAL: </strong> 0011-0222-0200690076  /  C.C.I.: 011-222-000200690076-71</span></div>'
            . '<div style="width: 690px; margin-top: 5px;"><span style="display: block;"><strong>CTA. CORRIENTE DETRACCIONES BANCO DE LA NACIÓN: </strong> 00-101-180476.</span> Detracción (10.00%): S/' . $cabecera->detraccion_p . '</div>'
            . '</div>'
            . '<hr style="margin-top: 30px; width: 780px; border: 1px solid #d1d5db;" />'
            . '<table style="width: 780px; font-size: 11px; margin-top: 20px; border-collapse: collapse;">'
            . '<tr><td style="width: 565px; border: solid 1px #aaaaaa; padding: 8px;">'
            . '<p style="margin: 0;">consulte en www.peruviandress.com (https://www.peruviandress.com/fe)</p>'
            . '</td><td style="width: 200px; vertical-align: top; padding-top: 8px;">'
            . '<img src="' . $qrDataUri . '" align="left" border="0" style="width: 120px;" />'
            . '</td></tr></table>'
            . '</div></body></html>';

        $dompdfPath = base_path('../core/app/view/dompdf/autoload.inc.php');
        if (file_exists($dompdfPath)) {
            require_once $dompdfPath;
        }

        $dompdf = new Dompdf();
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="venta-' . $codigo_venta . '.pdf"',
        ]);
    }

    public function storeSell(Request $request)
    {
        $request->validate([
            'person_id' => 'nullable|integer',
            'invoice_code' => 'required|string',
            'subtotal' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'tipo_documento' => 'nullable|string',
            'operations' => 'required|array',
            'operations.*.product_id' => 'required|integer',
            'operations.*.q' => 'required|numeric',
            'operations.*.price_out' => 'required|numeric',
            'operations.*.pedido' => 'nullable|string',
            'operations.*.tipo' => 'nullable|string',
            'operations.*.codigo_producto' => 'nullable|string',
            'operations.*.unidad' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // ===== Resolver cliente: registrado o nuevo RUC =====
            $person_id = $request->input('person_id') ?: 0;
            $nuevo_ruc = $request->input('nuevo_ruc', '');

            if (!$person_id && $request->has('ruc_result') && $request->input('ruc_result.nombre')) {
                $rucData = $request->input('ruc_result');
                $existing = DB::table('person')->where('no', $nuevo_ruc)->first();
                if ($existing) {
                    $person_id = $existing->id;
                } else {
                    $person_id = DB::table('person')->insertGetId([
                        'no' => $nuevo_ruc,
                        'name' => $rucData['nombre'],
                        'address1' => $rucData['direccion'] ?? '',
                        'kind' => 1,
                    ]);
                }
                $nuevo_ruc = ''; // ya está registrado
            }

            // ===== Cálculos de pago (igual que clsVenta.php insertar_venta) =====
            $total = (float) $request->input('total');
            $detraccion_p = (float) $request->input('detraccion_p', 0);
            $tipos_pago = (int) $request->input('tipos_pago', 2);
            $valor_pagar = $total - round($detraccion_p);

            switch ($tipos_pago) {
                case 2:
                    $pagado = $valor_pagar;
                    break; // contado
                case 3:
                case 4:
                    $pagado = 0;
                    break; // crédito
                default:
                    $pagado = $valor_pagar;
            }
            $a_cuenta = $valor_pagar - $pagado;

            // ===== Insertar en ventas_cabecera =====
            // Recopilar todos los pedidos para la cabecera (legacy behaviour)
            $pedidos = [];
            foreach ($request->input('operations') as $op) {
                if (!empty($op['pedido'])) {
                    $pedidos[] = $op['pedido'];
                }
            }
            $pedido_cod_cabecera = !empty($pedidos) ? '0--' . implode('--', $pedidos) : '0';

            DB::table('ventas_cabecera')->insert([
                'codigo_venta' => $request->input('invoice_code'),
                'tipo_documento' => (int) $request->input('tipo_documento', 2),
                'id_person' => $person_id,
                'id_forma_pago' => (int) $request->input('forma_pago', 2),
                'id_estado_pago' => (int) $request->input('tipos_pago', 2),
                'id_estado_entrega' => (int) $request->input('tipos_entrega', 1),
                'almacen' => 1,
                'descuento' => (float) $request->input('discount', 0),
                'desc_descuento' => '',
                'detraccion' => 0,
                'detraccion_p' => round($detraccion_p),
                'igv_p' => (float) $request->input('igv', 0),
                'subtotal' => (float) $request->input('subtotal'),
                'subtotal_2' => (float) $request->input('subtotal'),
                'igv' => (float) $request->input('igv'),
                'igv_2' => (float) $request->input('igv'),
                'total' => $total,
                'total_2' => $total,
                'valor_pagar' => $valor_pagar,
                'pagado' => $pagado,
                'a_cuenta' => $a_cuenta,
                'guia' => $request->input('guia', ''),
                'fecha_emision' => $request->input('fecha_emision', now()->toDateString()),
                'fecha_vencimiento' => $request->input('fecha_vencimiento'),
                'pedido_cod' => $pedido_cod_cabecera,
                'ruc_add' => $nuevo_ruc,
                'incluye_igv' => (int) $request->input('incluye_igv', 1),
                'fecha_creacion' => now(),
            ]);

            // ===== Insertar en ventas_detalle =====
            $incluye_igv = (int) $request->input('incluye_igv', 1);
            $codigo_venta = $request->input('invoice_code');

            foreach ($request->input('operations') as $op) {
                $precio_unit = (float) $op['price_out'];
                // Si el precio ya incluye IGV, dividimos entre 1.18 para guardar el neto
                if ($incluye_igv === 1) {
                    $precio_unit = round($precio_unit / 1.18, 6);
                }

                DB::table('ventas_detalle')->insert([
                    'codigo_venta_cabecera' => $codigo_venta,
                    'id_producto' => (int) $op['product_id'],
                    'cantidad' => (float) $op['q'],
                    'pedido_cod' => $op['pedido'] ?? '',
                    'codigo_unidad' => $op['codigo_producto'] ?? '',
                    'unidad' => $op['unidad'] ?? '',
                    'precio_unitario' => $precio_unit,
                    'precio_bordado' => (float) ($op['price_bordado'] ?? 0),
                    'tipo' => $op['tipo'] ?? 'Producto',
                ]);
            }

            // ===== Incrementar correlativo =====
            $tipo = (int) $request->input('tipo_documento', 2);
            $tabla = '';
            if ($tipo == 1) $tabla = 'boleta';
            elseif ($tipo == 2) $tabla = 'factura';
            elseif ($tipo == 3) $tabla = 'nota_pedido';
            
            if ($tabla) {
                DB::table('aux')->where('tabla', $tabla)->increment('id');
            }

            DB::commit();

            return response()->json([
                'Result' => 'OK',
                'message' => 'Venta registrada correctamente',
                'codigo_venta' => $codigo_venta,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'Result' => 'ERROR',
                'message' => 'Error al guardar la venta',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
