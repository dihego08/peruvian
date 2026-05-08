<?php
/**
 * Script para convertir certificado .pfx a .pem
 * Soluciona el error de OpenSSL 3.x con algoritmos legacy
 */

// Configuración
$pfxFile = 'CT2602142172.pfx';  // Ruta a tu certificado .pfx
$password = 'OMCARCERTIFICADO2026'; // Tu contraseña
$certOutput = 'certificado_om_2026.pem';
$keyOutput = 'key.pem';

phpinfo();

echo "=== CONVERSOR DE CERTIFICADO PFX A PEM ===\n\n";

// Verificar que existe el archivo PFX
if (!file_exists($pfxFile)) {
    die("❌ Error: No se encontró el archivo {$pfxFile}\n");
}

// Crear configuración OpenSSL con legacy provider
$opensslConfig = <<<EOT
openssl_conf = openssl_init

[openssl_init]
providers = provider_sect

[provider_sect]
default = default_sect
legacy = legacy_sect

[default_sect]
activate = 1

[legacy_sect]
activate = 1
EOT;

$configPath = __DIR__ . '/openssl_legacy.cnf';
file_put_contents($configPath, $opensslConfig);
putenv('OPENSSL_CONF=' . $configPath);

echo "✓ Configuración OpenSSL legacy creada\n";

// Extraer certificado
echo "\n1. Extrayendo certificado...\n";
$cmd1 = sprintf(
    "openssl pkcs12 -in %s -clcerts -nokeys -out %s -password pass:%s -legacy 2>&1",
    escapeshellarg($pfxFile),
    escapeshellarg($certOutput),
    escapeshellarg($password)
);

exec($cmd1, $output1, $return1);

if ($return1 === 0 && file_exists($certOutput)) {
    echo "✅ Certificado extraído correctamente: {$certOutput}\n";
    
    // Verificar certificado
    $certInfo = openssl_x509_parse(file_get_contents($certOutput));
    if ($certInfo) {
        echo "   - Emisor: " . $certInfo['issuer']['CN'] . "\n";
        echo "   - Válido desde: " . date('Y-m-d H:i:s', $certInfo['validFrom_time_t']) . "\n";
        echo "   - Válido hasta: " . date('Y-m-d H:i:s', $certInfo['validTo_time_t']) . "\n";
    }
} else {
    echo "❌ Error extrayendo certificado:\n";
    echo implode("\n", $output1) . "\n";
    exit(1);
}

// Extraer clave privada
echo "\n2. Extrayendo clave privada...\n";
$cmd2 = sprintf(
    "openssl pkcs12 -in %s -nocerts -out %s -nodes -password pass:%s -legacy 2>&1",
    escapeshellarg($pfxFile),
    escapeshellarg($keyOutput),
    escapeshellarg($password)
);

exec($cmd2, $output2, $return2);

if ($return2 === 0 && file_exists($keyOutput)) {
    echo "✅ Clave privada extraída correctamente: {$keyOutput}\n";
} else {
    echo "❌ Error extrayendo clave privada:\n";
    echo implode("\n", $output2) . "\n";
    exit(1);
}

// Limpiar archivo de configuración temporal
@unlink($configPath);

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎉 CONVERSIÓN COMPLETADA EXITOSAMENTE\n";
echo str_repeat("=", 50) . "\n\n";

echo "Archivos generados:\n";
echo "  • {$certOutput} (Certificado público)\n";
echo "  • {$keyOutput} (Clave privada)\n\n";

echo "Ahora modifica tu código para usar estos archivos:\n\n";
echo "// ANTES:\n";
echo "\$pfx = file_get_contents('{$pfxFile}');\n";
echo "\$certificate = new X509Certificate(\$pfx, '{$password}');\n\n";

echo "// DESPUÉS:\n";
echo "\$certificate = new X509ContentCertificate(file_get_contents('{$certOutput}'));\n";
echo "\$privateKey = file_get_contents('{$keyOutput}');\n\n";

echo "⚠️ IMPORTANTE: Guarda estos archivos de forma segura.\n";
echo "⚠️ NO los subas a repositorios públicos.\n\n";

// Crear archivo .gitignore si no existe
if (!file_exists('.gitignore')) {
    file_put_contents('.gitignore', "*.pem\n*.pfx\n*.key\n");
    echo "✓ Archivo .gitignore creado\n";
}
?>