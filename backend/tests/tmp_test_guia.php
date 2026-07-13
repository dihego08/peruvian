<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Services/GuiaRemisionService.php';

use App\Services\GuiaRemisionService;

$service = new GuiaRemisionService();

$g = (object)[
    'num_guia' => 'G001-1',
    'fecha_emision' => '2026-07-13',
    'ruc_destinatario' => '20123456789',
    'total_neto' => 0,
    'total_bruto' => 10,
    'motivo_traslado' => '01',
    'modalidad_trasnporte' => '01',
    'fecha_traslado' => '2026-07-13',
    'ubigeo_destino' => '040109',
    'destino' => 'AREQUIPA',
    'ubigeo' => '040100',
    'origen' => 'AREQUIPA',
    'placa' => 'ABC123'
];
$det = [(object)['cantidad' => 2, 'unidad' => 'NIU', 'descripcion_producto' => 'Prod', 'code' => 'P1']];
$dest = (object)['name' => 'Cliente S.A.'];

try {
    $ref = new ReflectionClass(GuiaRemisionService::class);
    $method = $ref->getMethod('construirDespatch');
    $method->setAccessible(true);
    $despatch = $method->invoke($service, (array)$g, $det, (array)$dest, null, null);
    echo "Despatch constructed: ";
    echo get_class($despatch) . "\n";
} catch (Throwable $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}
