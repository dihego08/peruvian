<?php
require 'vendor/autoload.php';
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

$renderer = new ImageRenderer(
    new RendererStyle(100),
    new SvgImageBackEnd()
);
$writer = new Writer($renderer);
$svgCode = $writer->writeString('Hello World');
echo "SVG works\n";
