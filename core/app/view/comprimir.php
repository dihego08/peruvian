<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$filename = $_GET['factura'];
$zipFile = sys_get_temp_dir() . '/' . $filename . '.zip'; // Ruta temporal segura

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("No se pudo crear el archivo ZIP");
}

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/facturador_v2/files/20455175781-01-' . $filename . '.xml';
$path2 = $_SERVER['DOCUMENT_ROOT'] . '/facturador_v3/20455175781-01-' . $filename . '.xml';

if (file_exists($path1)) {
    $zip->addFile($path1, basename($path1));
} elseif (file_exists($path2)) {
    $zip->addFile($path2, basename($path2));
} else {
    die("No se encontró el archivo XML.");
}

$zip->close();

// Limpiamos buffer antes de enviar
if (ob_get_length()) ob_end_clean();

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . basename($zipFile) . '"');
header('Content-Length: ' . filesize($zipFile));
header('Pragma: no-cache');
header('Expires: 0');

readfile($zipFile);

// Eliminamos archivo temporal si quieres
unlink($zipFile);
exit;
