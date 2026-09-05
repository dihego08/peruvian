<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;

class TransactionPdfController extends Controller
{
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

        $qrText = url('/api/transactions/sells/' . urlencode($codigo_venta) . '/pdf');
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(120),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrSvg = $writer->writeString($qrText);
        $qrDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        $logoDataUri = 'https://omcar.dbusinessaqp.com/assets/logo_2-DmLP2iC3.png';
        $ds = '';
        if ($cabecera->descuento > 0) {
            $ds = '<tr style="width: 200px;">'
                . '<td style="text-align: right;"><strong>Descuento: </strong></td>'
                . '<td style="padding: 10px; border-bottom: solid 1px;">S/ ' . $cabecera->descuento . '</td>'
                . '</tr>';
        }

        $tablaCredito = '';
        if ($cabecera->id_estado_pago == 4) {
            $tablaCredito = '<div style="border: solid 1px; border-radius: 10px; padding: 5px 10px; width: 100%; font-size: 9px; margin-top: 10px; box-sizing: border-box;">'
                . '<h5 style="margin-bottom: 0; width: 100%; display: block;">Información del Crédito</h5>'
                . '<table style="width: 100%; border-collapse: collapse;">'
                . '<tr><td style="width: 50%;"><table style="width: 100%;">'
                . '<tr><td style="padding: 5px; white-space: pre-wrap;"><strong style="font-size: 12px;">Monto neto pendiente de pago</strong></td></tr>'
                . '<tr><td style="padding: 5px;"><strong style="font-size: 12px;">Total de cuotas</strong></td></tr>'
                . '</table></td>'
                . '<td style="width: 50%;"><table style="width: 100%;">'
                . '<tr><td style="width: 100%; padding: 5px;">: <span>S/ ' . $cabecera->a_cuenta . '</span></td></tr>'
                . '<tr><td style="width: 100%; padding: 5px;">: ' . $cabecera->n_cuotas . '</td></tr>'
                . '</table></td></tr>'
                . '<tr><td colspan="2"><table style="width: 100%; border-top: solid 1px; border-collapse: collapse;">'
                . '<tr><td style="font-weight: bold; width: 33.33%;">Número de Cuota</td><td style="font-weight: bold; width: 33.33%;">Fecha Vencimiento</td><td style="font-weight: bold; width: 33.33%;">Monto</td></tr>'
                . '<tr><td>1</td><td>' . $cabecera->fecha_vencimiento . '</td><td>' . $cabecera->a_cuenta . '</td></tr>'
                . '</table></td></tr>'
                . '</table></div>';
        }

        $detalleTable = '<table id="tabla_detalles" style="border: solid 1px #eeeeee; text-align: center; margin-top: 10px; width: 100%; font-size: 10px; border-collapse: collapse;">'
            . '<tr style="border-bottom: solid 1px; background-color: #f7fafc;">'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Cantidad</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Pedido</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Modelo</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Descripción</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Valor Unitario</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Valor Total</th>'
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
            . '<div style="width: 100%; margin: 0 auto;">'
            . '<table id="encabezado" style="margin-bottom: 25px; font-size: 11px; width: 100%;">'
            . '<tr>'
            . '<td style="padding: 5px; border-radius: 4px; width: 222px; text-align: left;">'
            . '<table style="width: 222px;">'
            . '<tr><td style="text-align: center; width: 400px;">' . ($logoDataUri ? '<img src="' . $logoDataUri . '" align="left" border="0" style="width: 200px;" />' : '') . '</td></tr>'
            . '<tr><td style="opacity: 0 !important; text-align: center; width: 222px; font-weight: bold; font-size: 20px;">OMCAR SOLUTIONS E.I.R.L.</td></tr>'
            . '<tr><td style="text-align: left; width: 222px;"><strong>Dirección: </strong>CAL.BELEN NRO. 319 URB. JERUSALEN AREQUIPA - AREQUIPA - MARIANO MELGAR</td></tr>'
            . '<tr><td style="text-align: left; width: 222px;"><strong>Celular.: </strong>958133948</td></tr>'
            . '<tr><td style="text-align: left; width: 222px;"><strong>Correo : </strong>ventas.omcar@gmail.com</td></tr>'
            . '</table></td>'
            . '<td style="width: 44px;"></td>'
            . '<td style="padding: 5px; border-radius: 4px; width: 222px; text-align: center;">'
            . '<table style="width: 222px;">'
            . '<tr><td style="text-align: center; width: 222px;"><span style="font-weight: bold; font-size: 20px;">Factura Electrónica</span></td></tr>'
            . '<tr><td style="text-align: center; width: 222px; font-weight: bold;">R.U.C.: 20611081651</td></tr>'
            . '<tr><td style="text-align: center; width: 222px;">Nro. ' . $cabecera->codigo_venta . '</td></tr>'
            . '<tr><td style="text-align: center; width: 222px;">Nro. R.I. Emisor: 212321</td></tr>'
            . '<tr><td style="text-align: center; width: 222px;"><strong>Guía de Remisión:</strong> ' . $cabecera->guia . '</td></tr>'
            . '</table></td></tr></table>'
            . '<div style="border: solid 1px; border-radius: 10px; padding: 5px; width: 100%; margin-bottom: 12px; box-sizing: border-box;">'
            . '<table style="width: 100%; font-size: 11px;">'
            . '<tr><td style="width: 60%; white-space: pre-wrap;">'
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
            . '<table style="margin-top: 20px; width: 100%; font-size: 11px; border-collapse: collapse;">'
            . '<tr><td style="width: 70%; vertical-align: top;">'
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
            . '<div style="border: solid 1px; border-radius: 10px; padding: 10px; width: 100%; margin-top: 20px; font-size: 11px; box-sizing: border-box;">'
            . '<p><strong>DATOS BANCARIOS (M.N. SOLES):</strong></p>'
            . '<div style="width: 100%; margin-top: 5px;"><span style="display: block;"><strong>CTA. CTE BANCO DE CREDITO DEL PERÚ: </strong> 2152497862044  /  C.C.I: 00221500249786204421</span></div>'
            . '<div style="width: 100%; margin-top: 5px;"><span style="display: block;"><strong>CTA. AHORROS BANCO CONTINENTAL: </strong> 0011-0222-0200690076  /  C.C.I.: 011-222-000200690076-71</span></div>'
            . '<div style="width: 100%; margin-top: 5px;"><span style="display: block;"><strong>CTA. CORRIENTE DETRACCIONES BANCO DE LA NACIÓN: </strong> 00-101-180476.</span> Detracción (10.00%): S/' . $cabecera->detraccion_p . '</div>'
            . '</div>'
            . '<hr style="margin-top: 30px; width: 100%; border: 1px solid #d1d5db;" />'
            . '<table style="width: 100%; font-size: 11px; margin-top: 20px; border-collapse: collapse;">'
            . '<tr><td style="width: 70%; border: solid 1px #aaaaaa; padding: 8px;">'
            . '<p style="margin: 0;">consulte en www.omcar.com (https://www.omcar.com/fe)</p>'
            . '</td><td style="width: 200px; vertical-align: top; padding-top: 8px;">'
            . '<img src="' . $qrDataUri . '" align="left" border="0" style="width: 120px;" />'
            . '</td></tr></table>'
            . '</div></body></html>';

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="venta-' . $codigo_venta . '.pdf"',
        ]);
    }

    public function downloadNotaCreditoPdf($codigo_venta)
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
                DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion"),
                DB::raw("DATE_FORMAT(vc.fecha_anulacion, '%Y-%m-%d %H:%i:%s') as fecha_anulacion_fmt"),
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

        $qrText = url('/api/transactions/sells/' . urlencode($codigo_venta) . '/pdf-nota');
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(120),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrSvg = $writer->writeString($qrText);
        $qrDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        $logoDataUri = 'https://omcar.dbusinessaqp.com/assets/logo_2-DmLP2iC3.png';

        $detalleTable = '<table id="tabla_detalles" style="border: solid 1px #eeeeee; text-align: center; margin-top: 10px; width: 100%; font-size: 10px; border-collapse: collapse;">'
            . '<tr style="border-bottom: solid 1px; background-color: #f7fafc;">'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Cantidad</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Pedido</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Modelo</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Descripción</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Valor Unitario</th>'
            . '<th style="padding: 4px; border: 1px solid #e5e7eb;">Valor Total</th>'
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
        $detalleTable .= '</table>';

        $totalLetras = null;
        try {
            $letras = @file_get_contents('https://dbusinessaqp.com/numero_2_letras/conversor.php?total=' . urlencode(number_format($cabecera->total_2, 2, '.', '')));
            $totalLetras = $letras ? json_decode($letras) : null;
        } catch (\Exception $e) {
            $totalLetras = null;
        }

        $totalLetrasText = $totalLetras->letras ?? '';
        
        $fechaEmision = $cabecera->fecha_anulacion ?? $cabecera->fecha_creacion;

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8" /><style>'
            . 'body{font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 0;}'
            . 'table{border-collapse: collapse; width: 100%;}'
            . 'td, th{padding: 5px; vertical-align: top;}'
            . '.header-table td{vertical-align: top;}'
            . '.small{font-size: 10px;}'
            . '</style></head><body>'
            . '<div style="width: 100%; margin: 0 auto;">'
            . '<table id="encabezado" style="margin-bottom: 25px; font-size: 11px; width: 100%;">'
            . '<tr>'
            . '<td style="padding: 5px; border-radius: 4px; width: 222px; text-align: left;">'
            . '<table style="width: 222px;">'
            . '<tr><td style="text-align: center; width: 400px;">' . ($logoDataUri ? '<img src="' . $logoDataUri . '" align="left" border="0" style="width: 200px;" />' : '') . '</td></tr>'
            . '<tr><td style="opacity: 0 !important; text-align: center; width: 222px; font-weight: bold; font-size: 20px;">PERUVIAN DRESS TPX S.A.C.</td></tr>'
            . '<tr><td style="text-align: left; width: 222px;"><strong>Dirección: </strong>CAL.BELEN MZA. B LOTE. 8 JERUSALEN - MARIANO - MELGAR - AREQUIPA - AREQUIPA</td></tr>'
            . '<tr><td style="text-align: left; width: 222px;"><strong>Celular.: </strong>958133948</td></tr>'
            . '<tr><td style="text-align: left; width: 222px;"><strong>Correo : </strong>omendoza@peruviandress.com</td></tr>'
            . '</table></td>'
            . '<td style="width: 44px;"></td>'
            . '<td style="padding: 5px; border-radius: 4px; width: 222px; text-align: center;">'
            . '<table style="width: 222px;">'
            . '<tr><td style="text-align: center; width: 222px;"><span style="font-weight: bold; font-size: 20px;">Nota de crédito</span></td></tr>'
            . '<tr><td style="text-align: center; width: 222px; font-weight: bold;">R.U.C.: 20455175781</td></tr>'
            . '<tr><td style="text-align: center; width: 222px;">Nro. FF01-' . $cabecera->correlativo_nc . '</td></tr>'
            . '<tr><td style="text-align: center; width: 222px;">Nro. R.I. Emisor: 212321</td></tr>'
            . '<tr><td style="text-align: center; width: 222px;"><strong>Guía de Remisión:</strong> ' . $cabecera->guia . '</td></tr>'
            . '</table></td></tr></table>'
            . '<p style="margin-bottom: 0px;"><b>Motivo de emisión: </b>' . e($cabecera->motivo) . '</p>'
            . '<p><b>Descripción del motivo: </b>' . e($cabecera->motivo) . '</p>'
            . '<div style="border: solid 1px; border-radius: 10px; padding: 5px; width: 100%; margin-bottom: 12px; box-sizing: border-box;">'
            . '<table style="width: 100%; font-size: 11px;">'
            . '<tr><td style="width: 60%; white-space: pre-wrap;">'
            . '<table style="width: 380px;">'
            . '<tr><td style="padding: 5px; white-space: pre-wrap;"><strong>Razón Social: </strong><span style="white-space: pre-wrap;">' . nl2br(e($cliente['razon_social'])) . '</span></td></tr>'
            . '<tr><td style="padding: 5px;"><strong>Fecha Emisión: </strong>' . $fechaEmision . '</td></tr>'
            . '<tr><td style="padding: 5px;"><strong>Tipo Moneda: </strong>SOLES</td></tr>'
            . '</table></td>'
            . '<td style="width: 285px;"><table style="width: 285px;">'
            . '<tr><td style="width: 285px; padding: 5px;"><strong>RUC: </strong>' . e($cliente['ruc']) . '</td></tr>'
            . '<tr><td style="width: 285px; padding: 5px;"><strong>Dirección: </strong>' . e($cliente['direccion']) . '</td></tr>'
            . '</table></td></tr>'
            . '</table></div>'
            . '<div style="margin-top: 0.5rem; border: solid 1px; border-radius: 10px; padding: 10px; width: 100%; box-sizing: border-box;">'
            . '<h4 style="margin-top: 0px; margin-bottom: 0px;">Datos del documento que modifica</h4>'
            . '<table style="width: 100%;"><tr><td><table style="width: 100%;">'
            . '<tr><td style="padding: 5px;"><strong>Tipo de documento: </strong>Factura</td></tr>'
            . '<tr><td style="padding: 5px;"><strong>Número de documento: </strong>' . e($cabecera->codigo_venta) . '</td></tr>'
            . '</table></td></tr></table></div>'
            . $detalleTable
            . '<table style="margin-top: 20px; width: 100%; font-size: 11px; border-collapse: collapse;">'
            . '<tr><td style="width: 70%; vertical-align: top;">'
            . '<strong>Son: ' . ucwords($totalLetrasText) . '</strong>'
            . '</td>'
            . '<td style="width: 200px; vertical-align: top;">'
            . '<table style="width: 200px; font-size: 11px; border-collapse: collapse;">'
            . '<tr><td style="text-align: right;"><strong>Subtotal: </strong></td><td style="padding: 10px; border-bottom: solid 1px;">S/ ' . number_format($cabecera->subtotal_2, 2) . '</td></tr>'
            . '<tr><td style="text-align: right;"><strong>I.G.V.: </strong></td><td style="padding: 10px; border-bottom: solid 1px;">S/ ' . number_format($cabecera->igv_2, 2) . '</td></tr>'
            . '<tr><td style="text-align: right;"><strong>Total: </strong></td><td style="padding: 10px; border-bottom: solid 1px;">S/ ' . number_format($cabecera->total_2, 2) . '</td></tr>'
            . '</table></td></tr></table>'
            . '<hr style="margin-top: 30px; width: 100%; border: 1px solid #d1d5db;" />'
            . '<table style="width: 100%; font-size: 11px; margin-top: 20px; border-collapse: collapse;">'
            . '<tr><td style="width: 70%; border: solid 1px #aaaaaa; padding: 8px;">'
            . '<p style="margin: 0;">consulte en www.peruviandress.com (https://www.peruviandress.com/fe)</p>'
            . '<p style="margin: 0;">Representación Impresa de la NOTA DE CRÉDITO.</p>'
            . '</td><td style="width: 200px; vertical-align: top; padding-top: 8px;">'
            . '<img src="' . $qrDataUri . '" align="left" border="0" style="width: 120px;" />'
            . '</td></tr></table>'
            . '</div></body></html>';

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="nota-credito-' . $codigo_venta . '.pdf"',
        ]);
    }
}
