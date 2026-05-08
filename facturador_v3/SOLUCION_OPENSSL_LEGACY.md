# SOLUCIÓN: Error OpenSSL 3.x - digital envelope routines::unsupported

## ERROR
```
Fatal error: Uncaught Exception: error:0308010C:digital envelope routines::unsupported
in X509Certificate.php:172
```

## CAUSA
OpenSSL 3.x deshabilitó algoritmos legacy (RC2, RC4, etc.) que aún usan los certificados .pfx de SUNAT.

---

## SOLUCIÓN 1: Habilitar Legacy Provider (RECOMENDADO)

### Opción A: php.ini (Global)

Editar `/etc/php/8.x/apache2/php.ini` o `/etc/php/8.x/cli/php.ini`:

```ini
[openssl]
openssl.conf = /etc/ssl/openssl.cnf
```

Luego editar `/etc/ssl/openssl.cnf`:

```ini
# Al inicio del archivo (antes de [default_conf])
openssl_conf = openssl_init

# Al final del archivo
[openssl_init]
providers = provider_sect

[provider_sect]
default = default_sect
legacy = legacy_sect

[default_sect]
activate = 1

[legacy_sect]
activate = 1
```

**Reiniciar Apache:**
```bash
sudo systemctl restart apache2
# o
sudo service apache2 restart
```

---

### Opción B: En el código PHP (Local)

Crear archivo `openssl_legacy.cnf`:

```ini
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
```

Cargar antes de usar el certificado:

```php
<?php
// En convertir_certificado.php

// ANTES de cargar el certificado
putenv('OPENSSL_CONF=' . __DIR__ . '/openssl_legacy.cnf');

// Ahora sí cargar el certificado
$pfx = file_get_contents('certificado.pfx');
$certificate = new X509Certificate($pfx, 'PASSWORD');
```

---

## SOLUCIÓN 2: Convertir certificado .pfx a .pem (PERMANENTE)

Los certificados .pem no tienen este problema.

### Convertir una sola vez:

```bash
# Extraer clave privada
openssl pkcs12 -in certificado.pfx -nocerts -out key.pem -nodes -legacy

# Extraer certificado
openssl pkcs12 -in certificado.pfx -clcerts -nokeys -out cert.pem -legacy

# Verificar
openssl x509 -in cert.pem -text -noout
```

### Usar en PHP:

```php
<?php
// En lugar de usar .pfx
$certificate = new X509Certificate(
    file_get_contents('certificado.pfx'), 
    'PASSWORD'
);

// Usar .pem
$certificate = new X509ContentCertificate(
    file_get_contents('cert.pem')
);

// Y para la clave privada
$privateKey = file_get_contents('key.pem');
```

---

## SOLUCIÓN 3: Modificar X509Certificate.php (TEMPORAL)

**ADVERTENCIA:** Solo si no tienes acceso al servidor.

En `X509Certificate.php` línea ~40:

```php
// ANTES (línea 40):
$this->parsePfx($content, $password);

// DESPUÉS:
putenv('OPENSSL_CONF=/ruta/a/openssl_legacy.cnf');
$this->parsePfx($content, $password);
```

---

## SOLUCIÓN 4: Actualizar Greenter (si está desactualizado)

```bash
composer update greenter/xmldsig
composer update greenter/greenter
```

Las versiones más recientes pueden tener compatibilidad mejorada.

---

## SOLUCIÓN 5: Wrapper con manejo de error

Crear archivo `CertificadoHelper.php`:

```php
<?php
class CertificadoHelper
{
    private static $opensslConfigured = false;
    
    public static function loadCertificate($pfxPath, $password)
    {
        // Configurar OpenSSL para legacy una sola vez
        if (!self::$opensslConfigured) {
            self::configureOpenSSL();
            self::$opensslConfigured = true;
        }
        
        try {
            $pfx = file_get_contents($pfxPath);
            return new \Greenter\XMLSecLibs\Certificate\X509Certificate($pfx, $password);
        } catch (Exception $e) {
            // Si falla, intentar con conversión a PEM
            return self::convertAndLoad($pfxPath, $password);
        }
    }
    
    private static function configureOpenSSL()
    {
        // Crear archivo de configuración temporal
        $config = <<<EOT
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
        
        $configPath = sys_get_temp_dir() . '/openssl_legacy.cnf';
        file_put_contents($configPath, $config);
        putenv('OPENSSL_CONF=' . $configPath);
    }
    
    private static function convertAndLoad($pfxPath, $password)
    {
        // Convertir PFX a PEM automáticamente
        $certPath = sys_get_temp_dir() . '/cert_' . md5($pfxPath) . '.pem';
        $keyPath = sys_get_temp_dir() . '/key_' . md5($pfxPath) . '.pem';
        
        if (!file_exists($certPath)) {
            // Extraer certificado
            exec("openssl pkcs12 -in {$pfxPath} -clcerts -nokeys -out {$certPath} -password pass:{$password} -legacy 2>&1", $output, $return);
            
            if ($return !== 0) {
                throw new Exception("No se pudo convertir el certificado: " . implode("\n", $output));
            }
        }
        
        if (!file_exists($keyPath)) {
            // Extraer clave privada
            exec("openssl pkcs12 -in {$pfxPath} -nocerts -out {$keyPath} -nodes -password pass:{$password} -legacy 2>&1", $output, $return);
            
            if ($return !== 0) {
                throw new Exception("No se pudo extraer la clave privada: " . implode("\n", $output));
            }
        }
        
        return new \Greenter\XMLSecLibs\Certificate\X509ContentCertificate(
            file_get_contents($certPath)
        );
    }
}

// USO:
try {
    $certificate = CertificadoHelper::loadCertificate('certificado.pfx', 'PASSWORD');
    echo "Certificado cargado correctamente\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

---

## SOLUCIÓN RÁPIDA PARA HOSTINGER/CPANEL

Si no tienes acceso SSH completo:

### 1. Crear `.htaccess` en el directorio:

```apache
# .htaccess
php_value openssl.conf /home/usuario/openssl_legacy.cnf
```

### 2. Crear `openssl_legacy.cnf`:

```ini
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
```

### 3. En PHP (antes de usar certificado):

```php
<?php
putenv('OPENSSL_CONF=' . __DIR__ . '/openssl_legacy.cnf');

// Ahora usar certificado normalmente
$pfx = file_get_contents('certificado.pfx');
$certificate = new X509Certificate($pfx, 'PASSWORD');
```

---

## DIAGNÓSTICO: Verificar versión OpenSSL

```php
<?php
echo "OpenSSL Version: " . OPENSSL_VERSION_TEXT . "\n";
echo "OpenSSL Version Number: " . OPENSSL_VERSION_NUMBER . "\n";

// Si es 3.0.0 o superior, necesitas habilitar legacy provider
```

```bash
# Desde terminal
openssl version
```

---

## TESTING: Verificar que funciona

```php
<?php
require 'vendor/autoload.php';

use Greenter\XMLSecLibs\Certificate\X509Certificate;

// Configurar legacy
putenv('OPENSSL_CONF=' . __DIR__ . '/openssl_legacy.cnf');

try {
    $pfx = file_get_contents('certificado.pfx');
    $certificate = new X509Certificate($pfx, 'TU_PASSWORD');
    
    echo "✅ Certificado cargado correctamente\n";
    echo "Emisor: " . $certificate->getIssuer() . "\n";
    echo "Válido desde: " . $certificate->getValidFrom() . "\n";
    echo "Válido hasta: " . $certificate->getValidTo() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
```

---

## RECOMENDACIÓN FINAL

**Para producción:** Usar **SOLUCIÓN 1 Opción A** (editar openssl.cnf del sistema)

**Para desarrollo/pruebas:** Usar **SOLUCIÓN 1 Opción B** (putenv en código)

**Para portabilidad:** Usar **SOLUCIÓN 2** (convertir a .pem una sola vez)

---

## CONVERSIÓN PERMANENTE (Recomendado)

Script de conversión `convertir_pfx_a_pem.php`:

```php
<?php
$pfxFile = 'certificado.pfx';
$password = 'TU_PASSWORD';
$certFile = 'certificado.pem';
$keyFile = 'key.pem';

// Configurar OpenSSL legacy
putenv('OPENSSL_CONF=' . __DIR__ . '/openssl_legacy.cnf');

// Extraer certificado
$cmd1 = "openssl pkcs12 -in {$pfxFile} -clcerts -nokeys -out {$certFile} -password pass:{$password} -legacy";
exec($cmd1, $output1, $return1);

if ($return1 === 0) {
    echo "✅ Certificado extraído: {$certFile}\n";
} else {
    die("❌ Error extrayendo certificado\n");
}

// Extraer clave privada
$cmd2 = "openssl pkcs12 -in {$pfxFile} -nocerts -out {$keyFile} -nodes -password pass:{$password} -legacy";
exec($cmd2, $output2, $return2);

if ($return2 === 0) {
    echo "✅ Clave privada extraída: {$keyFile}\n";
    echo "\n🎉 Conversión completada. Ahora usa .pem en lugar de .pfx\n";
} else {
    die("❌ Error extrayendo clave privada\n");
}
?>
```

Ejecutar una sola vez:
```bash
php convertir_pfx_a_pem.php
```

Luego en tu código usar los .pem generados.
