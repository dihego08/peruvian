<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class CotizationPdfController extends Controller
{
    private const LEGACY_IMAGE_BASE = 'https://peruvian.peruviandress.com/storage/products/';

    public function downloadCotizationPdf($codigo)
    {
        $cabecera = DB::table('cotizacion as c')
            ->leftJoin('person as pe', 'pe.id', '=', 'c.person_id')
            ->where('c.codigo', $codigo)
            ->select([
                'c.*',
                'pe.name as person_name',
                'pe.lastname as person_lastname',
                'pe.address1 as direccion',
            ])
            ->first();

        if (!$cabecera) {
            return response('Cotización no encontrada', 404);
        }

        $detalle = DB::table('cotizacion_detalle as cd')
            ->leftJoin('product as pr', 'pr.id', '=', 'cd.id_producto')
            ->where('cd.codigo_cotizacion', $codigo)
            ->select([
                'cd.*',
                'pr.name as producto_nombre',
            ])
            ->get();

        $cliente = $cabecera->person_id == 0
            ? ($cabecera->cliente ?? '')
            : trim(($cabecera->person_name ?? '') . ' ' . ($cabecera->person_lastname ?? ''));
        $direccion = $cabecera->direccion ?: 'Arequipa';

        $logoPath = public_path('img/logo.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : 'https://peruvian.dbusinessaqp.com/assets/logo_2-DmLP2iC3.png';

        // Logos de certificaciones ISO (opcionales): se muestran los que existan en backend/public/img
        $certImgs = '';
        foreach (['iso9.png', 'iso45.png'] as $cert) {
            $certPath = public_path('img/' . $cert);
            if (file_exists($certPath)) {
                $certImgs .= '<img src="data:image/png;base64,' . base64_encode(file_get_contents($certPath)) . '" style="width: 85px; margin-left: 8px;" />';
            }
        }

        $sinIgv = (int) $cabecera->igv_incluye !== 1;

        $detalleRows = '';
        foreach ($detalle as $i => $item) {
            $nombre = $item->nombre_producto ?: ($item->producto_nombre ?? '');

            // Imagen de modelo y de bordado lado a lado (cada una solo si existe),
            // recortadas al mismo tamaño cuadrado
            $box = 140;
            $celdas = [];
            foreach (['MODELO' => $item->imagen, 'BORDADO/ESTAMPADO' => $item->imagen_2] as $label => $img) {
                $src = $this->resolveImage($img);
                if ($src) {
                    $celdas[] = '<td style="border: none; padding: 2px 4px; text-align: center; vertical-align: top;">'
                        . '<img src="' . $src . '" style="width: ' . $box . 'px; height: ' . $box . 'px;" />'
                        . '<div style="font-size: 8px; color: #6b7280; margin-top: 2px;">' . $label . '</div>'
                        . '</td>';
                }
            }
            $imagenes = $celdas
                ? '<table style="width: auto; margin: 0 auto;"><tr>' . implode('', $celdas) . '</tr></table>'
                : '';

            $detalleRows .= '<tr>'
                . '<td style="width: 6%; text-align: center; vertical-align: middle; font-weight: bold;">' . ($i + 1) . '</td>'
                . '<td style="width: 38%; vertical-align: middle;">'
                . '<p style="font-weight: bold; margin: 0 0 12px 0;">' . e(mb_strtoupper($nombre)) . '</p>'
                . '<div>' . $this->cleanHtml($item->descripcion) . '</div>'
                . '</td>'
                . '<td style="width: 44%; text-align: center; vertical-align: middle;">' . $imagenes . '</td>'
                . '<td style="width: 12%; text-align: center; vertical-align: middle; font-weight: bold;">S/. ' . $this->formatPrecio($item->costo)
                . ($sinIgv ? '<div style="font-size: 8px; font-weight: normal; margin-top: 4px;">SIN IGV</div>' : '')
                . '</td>'
                . '</tr>';
        }

        $celulares = implode(' | ', array_filter([$cabecera->asesor_celular, '958133948']));

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8" /><style>'
            . '@page{margin: 40px 50px;}'
            . 'body{font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 0;}'
            . 'p{margin: 0 0 10px 0;}'
            . 'table{border-collapse: collapse; width: 100%;}'
            . '#tabla_detalles th, #tabla_detalles td{border: 1px solid #9ca3af; padding: 8px;}'
            . '#tabla_detalles th{font-weight: bold; text-align: center;}'
            . '.condiciones p{margin: 0 0 12px 0;}'
            . '</style></head><body>'
            . '<div style="width: 100%;">'
            . '<img src="' . $logoDataUri . '" style="width: 200px;" />'
            . '<h2 style="text-align: center; margin: 25px 0 30px 0; font-size: 18px;">Cotización: ' . e($cabecera->codigo) . '</h2>'
            . '<p><strong>Fecha:</strong> Arequipa, ' . $this->formatFecha($cabecera->fecha_creacion) . '</p>'
            . '<p><strong>Señor(es):</strong> ' . e(mb_strtoupper($cliente)) . '</p>'
            . '<p><strong>Direccion:</strong> ' . e($direccion) . '</p>'
            . '<p style="margin-top: 18px;">Estimado Señor(a):</p>'
            . '<p>Nos es grato saludarlo por medio de la presente y hacerle llegar la siguiente cotizacion.</p>'
            . '<table id="tabla_detalles" style="margin-top: 20px;">'
            . '<tr><th>ITEM</th><th>DESCRIPCION</th><th>IMAGEN</th><th>PRECIO</th></tr>'
            . $detalleRows
            . '</table>'
            . ($sinIgv ? '<p style="margin-top: 8px; font-weight: bold;">* LOS PRECIOS NO INCLUYEN IGV.</p>' : '')
            . '<div class="condiciones" style="margin-top: 30px;">'
            . '<p><strong>SERVICIOS:</strong> ' . e(mb_strtoupper($cabecera->servicios ?? '')) . '</p>'
            . '<p><strong>TIEMPO DE ENTREGA:</strong> ' . e(mb_strtoupper($cabecera->tiempo_entrega ?? '')) . '</p>'
            . '<p><strong>VALIDEZ DE LA OFERTA:</strong> ' . e(mb_strtoupper($cabecera->validez ?? '')) . '.</p>'
            . '<p><strong>FORMA DE PAGO:</strong> ' . e(mb_strtoupper($cabecera->forma_pago ?? '')) . '.</p>'
            . '<p><strong>TALLAS ESPECIALES:</strong> ' . e(mb_strtoupper($cabecera->tallas_especiales ?? '')) . '.</p>'
            . '<p><strong>OBSERVACIONES:</strong> ' . e(mb_strtoupper($cabecera->obervacion ?? '')) . '</p>'
            . '</div>'
            . '<p style="margin-top: 25px;">Sin otro particular, nos despedimos de Ud. a la espera de sus gratas indicaciones.</p>'
            . '<table style="margin-top: 20px;"><tr>'
            . '<td style="width: 65%; vertical-align: bottom;">'
            . '<p>Gustoso de atenderte...</p>'
            . '<p>Asesor Comercial</p>'
            . '<p><strong>' . e(mb_strtoupper($cabecera->asesor_comercial ?? '')) . '</strong></p>'
            . '<p>Celular: ' . e($celulares) . '</p>'
            . '<p style="margin: 0;">Correo: <span style="color: #1d4ed8;">ventas@peruviandress.com</span> | <span style="color: #1d4ed8;">omendoza@peruviandress.com</span></p>'
            . '</td>'
            . '<td style="width: 35%; text-align: right; vertical-align: bottom;">'
            . $certImgs
            . '</td>'
            . '</tr></table>'
            . '</div>'
            . '</body></html>';

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('DejaVu Sans', 'italic');
        $canvas->page_text($canvas->get_width() - 110, $canvas->get_height() - 30, 'Página {PAGE_NUM}/{PAGE_COUNT}', $font, 8, [0, 0, 0]);

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="cotizacion-' . $codigo . '.pdf"',
        ]);
    }

    /**
     * Las imágenes nuevas están en public/storage/products de esta API; las antiguas siguen en el legacy.
     */
    private function resolveImage($filename)
    {
        if (empty($filename)) {
            return null;
        }

        $localPath = public_path('storage/products/' . $filename);
        if (file_exists($localPath)) {
            $data = file_get_contents($localPath);
        } else {
            $context = stream_context_create(['http' => ['timeout' => 10], 'https' => ['timeout' => 10]]);
            $data = @file_get_contents(self::LEGACY_IMAGE_BASE . rawurlencode($filename), false, $context);
        }

        $info = $data ? @getimagesizefromstring($data) : false;
        if (!$info) {
            return null;
        }

        return $this->squareCrop($data) ?: 'data:' . $info['mime'] . ';base64,' . base64_encode($data);
    }

    /**
     * Recorta el centro de la imagen a un cuadrado y la reduce a $size px, para que todas
     * las imágenes del PDF tengan exactamente el mismo tamaño (equivalente a object-fit: cover,
     * que Dompdf no soporta). También aligera el PDF cuando las fotos son muy grandes.
     */
    private function squareCrop($data, $size = 400)
    {
        $src = @imagecreatefromstring($data);
        if (!$src) {
            return null;
        }

        $w = imagesx($src);
        $h = imagesy($src);
        $lado = min($w, $h);

        $dst = imagecreatetruecolor($size, $size);
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255)); // fondo blanco para PNG transparentes
        imagecopyresampled($dst, $src, 0, 0, (int) (($w - $lado) / 2), (int) (($h - $lado) / 2), $size, $size, $lado, $lado);

        ob_start();
        imagejpeg($dst, null, 85);
        $jpg = ob_get_clean();
        unset($src, $dst); // libera la memoria de las imágenes antes de procesar la siguiente

        return 'data:image/jpeg;base64,' . base64_encode($jpg);
    }

    /**
     * Las cotizaciones del legacy guardan la descripción como HTML (se conservan solo etiquetas de formato);
     * las nuevas la guardan como texto plano desde un textarea, así que se respetan sus saltos de línea.
     */
    private function cleanHtml($html)
    {
        $html = (string) $html;
        if (!preg_match('#</?(p|br|div|span|b|strong|i|em|u|ul|ol|li|h[1-6]|table|tr|td)\b[^>]*>#i', $html)) {
            return nl2br(e(trim($html)));
        }

        $html = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $html);
        return strip_tags($html, '<p><br><b><strong><i><em><u><ul><ol><li><span>');
    }

    private function formatPrecio($costo)
    {
        $valor = (float) $costo;
        return fmod($valor, 1.0) == 0.0 ? number_format($valor, 0) : number_format($valor, 2);
    }

    private function formatFecha($fecha)
    {
        if (empty($fecha)) {
            return '';
        }
        $meses = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Set.', 'Oct.', 'Nov.', 'Dic.'];
        $ts = strtotime($fecha);
        return date('j', $ts) . ' de ' . $meses[(int) date('n', $ts) - 1] . ' del ' . date('Y', $ts) . ' ' . date('H:i:s', $ts);
    }
}
