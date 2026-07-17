<?php
require 'vendor/autoload.php';
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

try {
    $renderer = new ImageRenderer(
        new RendererStyle(120),
        new ImagickImageBackEnd()
    );
    $writer = new Writer($renderer);
    $pngCode = $writer->writeString('Hello World');
    echo "PNG works\n";
} catch (\Exception $e) {
    echo "PNG Failed: " . $e->getMessage() . "\n";
}
