<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderPdfController extends Controller
{
    public function downloadOrderPdf($codigo)
    {
        $q = DB::table('order_cabecera as oc')
            ->join('person as p', 'p.id', '=', 'oc.person_id')
            ->where('oc.codigo', $codigo)
            ->select([
                'oc.nombre_modelo',
                'oc.comentario',
                'oc.imagen_alt',
                'oc.codigo',
                DB::raw('DATE(oc.fecha_creacion) as fecha_creacion'),
                'oc.tiempo_entrega',
                'oc.fecha_entrega',
                'oc.estado',
                'p.name',
                DB::raw('DATEDIFF(oc.fecha_entrega, CURDATE()) as trans'),
                'oc.total',
                DB::raw('(SELECT p.image FROM order_detalle_2 d INNER JOIN product p ON d.modelo = p.code WHERE d.codigo_cabecera = oc.codigo ORDER BY d.id ASC LIMIT 1) as imagen'),
                DB::raw('(SELECT sum(d.ptotal) FROM order_detalle_2 d WHERE d.codigo_cabecera = oc.codigo) as totalp')
            ])
            ->first();

        if (!$q) {
            return response('Pedido no encontrado', 404);
        }

        $fecha = $q->fecha_creacion;
        $fecha_entrega = $q->fecha_entrega;
        $entrega = $q->tiempo_entrega;
        $cliente = $q->name;
        $comentarios = $q->comentario;
        $imagen = (!empty($q->imagen_alt)) ? $q->imagen_alt : $q->imagen;
        $totalp = $q->totalp;
        $nombre_modelo = (!empty($q->nombre_modelo)) ? $q->nombre_modelo : "";

        $detalles = DB::table('order_detalle_2')->where('codigo_cabecera', $codigo)->get();

        $logoDataUri = 'https://omcar.dbusinessaqp.com/assets/logo_2-DmLP2iC3.png';

        $imageDataUri = !empty($imagen) ? 'https://omcar.peruviandress.com/storage/products/' . rawurlencode($imagen) : '';

        $html = '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title>Pedido</title>
            <style>
                body{font-family: DejaVu Sans, sans-serif; font-size: 11px;}
                .table {width: 100%; border-collapse: collapse; margin-bottom: 20px;}
                .table-bordered th, .table-bordered td {border: 1px solid #000; padding: 5px;}
                .text-center {text-align: center;}
            </style>
        </head>
        <body>
            <table style="width: 100%; border: none; margin-bottom: 20px;">
                <tr>
                    <td style="vertical-align: middle; width: 30%;">
                        ' . ($logoDataUri ? '<img src="' . $logoDataUri . '" style="width:200px;">' : '') . '
                    </td>
                    <td style="vertical-align: middle; text-align: center; width: 40%;">
                        <h3 style="font-weight: bold; margin: 0;">REQUERIMIENTO DE PEDIDO</h3>
                    </td>
                    <td style="vertical-align: middle; width: 30%;">
                        <table class="table-bordered" style="width: 100%; font-size: 10px;">
                            <tr><td style="padding: 2px 5px;"><strong>Código:</strong> PD-FOR-011</td></tr>
                            <tr><td style="padding: 2px 5px;"><strong>Versión:</strong> 001</td></tr>
                            <tr><td style="padding: 2px 5px;"><strong>F. Aprob.:</strong> 10/01/2022</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
            <h3 style="font-weight: bold; text-align: center; margin-top: 0;">PEDIDO Nro : ' . $codigo . '</h3>
            
            <table class="table table-bordered">
                <tr><td style="width: 25%; font-weight: bold;">Fecha de entrega:</td><td>' . $fecha_entrega . '</td></tr>
                <tr><td style="font-weight: bold;">Cliente:</td><td>' . $cliente . '</td></tr>
                <tr><td style="font-weight: bold;">Tiempo de Entrega:</td><td>' . $entrega . '</td></tr>
                <tr><td style="font-weight: bold;">' . e($nombre_modelo) . '</td><td>' . ($imageDataUri ? '<img src="' . $imageDataUri . '" style="max-height: 120px; width: auto;">' : '') . '</td></tr>
            </table>
            
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle;">Modelo</th>
                        <th rowspan="2" style="vertical-align: middle;">Color</th>
                        <th colspan="13">Cantidades por Talla</th>
                        <th rowspan="2" style="vertical-align: middle;">Total</th>
                    </tr>
                    <tr>';

        $cabecera_html = "";
        $table_2 = "";
        $total = 0;

        foreach ($detalles as $idx => $res) {
            $subtotal = $res->total;
            if ($idx === 0) {
                $cabecera_html = '<th>' . e($res->n1 ?? '') . '</th>
                <th>' . e($res->n2 ?? '') . '</th>
                <th>' . e($res->n3 ?? '') . '</th>
                <th>' . e($res->n4 ?? '') . '</th>
                <th>' . e($res->n5 ?? '') . '</th>
                <th>' . e($res->n6 ?? '') . '</th>
                <th>' . e($res->n7 ?? '') . '</th>
                <th>' . e($res->n8 ?? '') . '</th>
                <th>' . e($res->n9 ?? '') . '</th>
                <th>' . e($res->n10 ?? '') . '</th>
                <th>' . e($res->n11 ?? '') . '</th>
                <th>' . e($res->n12 ?? '') . '</th>
                <th>' . e($res->n13 ?? '') . '</th>';
            }

            $total += $subtotal;
            $table_2 .= '<tr><td>' . e($res->modelo) . '</td>' .
                '<td>' . e($res->color) . '</td>' .
                '<td>' . e($res->_2 ?? ' ') . '</td>' .
                '<td>' . e($res->_4 ?? ' ') . '</td>' .
                '<td>' . e($res->_6 ?? ' ') . '</td>' .
                '<td>' . e($res->_8 ?? ' ') . '</td>' .
                '<td>' . e($res->_10 ?? ' ') . '</td>' .
                '<td>' . e($res->_12 ?? ' ') . '</td>' .
                '<td>' . e($res->_14 ?? ' ') . '</td>' .
                '<td>' . e($res->_16 ?? ' ') . '</td>' .
                '<td>' . e($res->s ?? ' ') . '</td>' .
                '<td>' . e($res->m ?? ' ') . '</td>' .
                '<td>' . e($res->l ?? ' ') . '</td>' .
                '<td>' . e($res->xl ?? ' ') . '</td>' .
                '<td>' . e($res->xxl ?? ' ') . '</td>' .
                '<td>' . e($subtotal) . '</td></tr>';

            $table_2 .= '<tr style="background-color: #f9fafb;"><td colspan="2" style="font-weight:bold; font-size:10px;">PRODUCIDOS</td>' .
                '<td>' . e($res->p2) . '</td>' .
                '<td>' . e($res->p4) . '</td>' .
                '<td>' . e($res->p6) . '</td>' .
                '<td>' . e($res->p8) . '</td>' .
                '<td>' . e($res->p10) . '</td>' .
                '<td>' . e($res->p12) . '</td>' .
                '<td>' . e($res->p14) . '</td>' .
                '<td>' . e($res->p16) . '</td>' .
                '<td>' . e($res->ps) . '</td>' .
                '<td>' . e($res->pm) . '</td>' .
                '<td>' . e($res->pl) . '</td>' .
                '<td>' . e($res->pxl) . '</td>' .
                '<td>' . e($res->pxxl) . '</td>' .
                '<td>' . e($res->ptotal) . '</td></tr>';
        }

        if (empty($cabecera_html)) {
            $cabecera_html = str_repeat('<th></th>', 13);
        }

        $html .= $cabecera_html . '</tr></thead><tbody>' . $table_2;
        $html .= '<tr style="font-weight: bold;"><td colspan="15" style="text-align: right;">Total Producidos:</td><td>' . $totalp . '</td></tr>';
        $html .= '</tbody></table>';

        $html .= '<p style="margin-top: 10px;"><strong>Comentarios:</strong> ' . nl2br(e($comentarios)) . '</p>';
        $html .= '</body></html>';

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Pedido-' . $codigo . '.pdf"',
        ]);
    }
}
