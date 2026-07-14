<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

$renderer = new ImageRenderer(
    new RendererStyle(120),
    new SvgImageBackEnd()
);
$writer = new Writer($renderer);
$qrSvg = $writer->writeString('Hello World');
$qrDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

$html = '<html><body><img src="' . $qrDataUri . '"></body></html>';

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');

try {
    $dompdf->render();
    echo "Dompdf SVG works\n";
} catch (\Exception $e) {
    echo "Dompdf SVG Failed: " . $e->getMessage() . "\n";
}
