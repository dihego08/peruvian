<?php
/*date_default_timezone_set('America/Lima');

use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;

require __DIR__ . '/vendor/autoload.php';
$see = require __DIR__ . '/config.php';
require __DIR__ . '/env/env.php';

$num_factura = $_GET['cod_factura'];
$motivo = $_GET['motivo'];
$cod_motivo = $_GET['cod_motivo'];
$correlativo = $_GET['correlativo'];

$query_factura = $mbd->prepare("SELECT * FROM ventas_cabecera WHERE codigo_venta = :num_factura");
$query_factura->bindParam(":num_factura", $num_factura);
$query_factura->execute();

$factura = $query_factura->fetch(PDO::FETCH_ASSOC);

$client = new Client();
$email = "";
$phone = "";
$address = "";

$query = $mbd->prepare("SELECT no, email1, phone1, address1, name from person WHERE id = :id");
$query->bindParam(":id", $factura['id_person']);
$query->execute();
$ruc_db = $query->fetch(PDO::FETCH_ASSOC);

$client = (new Client())
    ->setTipoDoc('6')
    ->setNumDoc(str_replace(" ", "", $ruc_db['no']))
    ->setRznSocial($ruc_db['name']);

$see->setCertificate(file_get_contents(__DIR__.'/certificate_pv_2024.pem'));
$see->setClaveSOL('20455175781', 'PERUVI11', 'Omcipier11');

$address = (new Address())
    ->setUbigueo('040109')
    ->setDepartamento('AREQUIPA')
    ->setProvincia('AREQUIPA')
    ->setDistrito('MARIANO MELGAR')
    ->setUrbanizacion('-')
    ->setDireccion('CAL.BELEN MZA. B LOTE. 8 AREQUIPA - AREQUIPA - MARIANO MELGAR')
    ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

    $company = (new Company())
    ->setRuc('20455175781')
    ->setRazonSocial('PERUVIAN DRESS TPX S.A.C.')
    ->setNombreComercial('PERUVIAN DRESS TPX S.A.C.')
    ->setAddress($address);

//$util = Util::getInstance();

$note = new Note();
$note
    ->setUblVersion('2.1')
    ->setTipDocAfectado('01')
    ->setNumDocfectado($num_factura)
    ->setCodMotivo($cod_motivo)
    ->setDesMotivo($motivo)
    ->setTipoDoc('07')
    ->setSerie('FF01')
    ->setFechaEmision(new DateTime())
    ->setCorrelativo($correlativo)
    ->setTipoMoneda('PEN')
    ->setCompany($company)
    ->setClient($client)
    ->setMtoOperGravadas($factura['subtotal'])
    ->setMtoIGV($factura['igv'])
    ->setTotalImpuestos($factura['igv'])
    ->setMtoImpVenta($factura['total']);


$query_detalle = $mbd->prepare("SELECT * FROM ventas_detalle WHERE codigo_venta_cabecera = :codigo_venta");
$query_detalle->bindParam(":codigo_venta", $_GET['cod_factura']);
$query_detalle->execute();

$items = [];

while ($res = $query_detalle->fetch(PDO::FETCH_ASSOC)) {
    $pr = $mbd->prepare("SELECT * FROM product WHERE id = :id");
    $pr->bindParam(":id", $res['id_producto']);
    $pr->execute();

    $producto = $pr->fetch(PDO::FETCH_ASSOC);

    $unidad = "";

    $unidad = "UNI";

    $item = new SaleDetail();

    $monto_valor_venta = number_format($res['precio_unitario'] * $res['cantidad'], 2, ".", "");
    if(empty($res['precio_bordado']) || is_null($res['precio_bordado'])){
        $res['precio_bordado'] = 0;
    }

    $item->setCodProducto($res['id_producto'])
        //->setUnidad($unidades_r_[$i])
        ->setUnidad('NIU')
        ->setDescripcion($res['tipo'])
        ->setCantidad($res['cantidad'])
        ->setMtoValorUnitario($res['precio_unitario'])
        ->setMtoValorVenta($monto_valor_venta + $res['precio_bordado'])
        ->setMtoBaseIgv(($monto_valor_venta + $res['precio_bordado']))
        ->setPorcentajeIgv(18)
        ->setIgv(($monto_valor_venta + $res['precio_bordado']) * 0.18)
        ->setTipAfeIgv('10')
        ->setTotalImpuestos(($monto_valor_venta + $res['precio_bordado']) * 0.18)
        ->setMtoPrecioUnitario((($monto_valor_venta + $res['precio_bordado']) * 0.18) + ($monto_valor_venta + $res['precio_bordado']));

    $items[] = $item;
}

$total_letras = json_decode(file_get_contents('https://dbusinessaqp.com/numero_2_letras/conversor.php?total=' . $factura['total']));

$note->setDetails($items)
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue($total_letras->letras)
    ]);

    //print_r($items);

$result = $see->send($note);

file_put_contents(
    $note->getName() . '.xml',
    $see->getFactory()->getLastXml()
);

if (!$result->isSuccess()) {
    print_r($result);
    echo json_encode(array("Result" => "ERROR"));
}

$cdr = $result->getCdrResponse();
file_put_contents('R-' . $note->getName() . '.zip', $result->getCdrZip());

$code = (int)$cdr->getCode();

$query_anular = $mbd->prepare("UPDATE ventas_cabecera SET estado_anulado = 1, motivo = :motivo, correlativo_nc = :correlativo, fecha_anulacion = :fecha_anulacion, total = 0, igv = 0, detraccion_p = 0, igv_p = 0, subtotal = 0, valor_pagar = 0, a_cuenta = 0 WHERE codigo_venta = :codigo_venta;");
$fecha_anulacion = date("Y-m-d");
$query_anular->bindParam(":codigo_venta", $factura['codigo_venta']);
$query_anular->bindParam(":motivo", $motivo);
$query_anular->bindParam(":correlativo", $correlativo);
$query_anular->bindParam(":fecha_anulacion", $fecha_anulacion);
$query_anular->execute();

$query_aux = $mbd->prepare("UPDATE aux set id = id + 1 WHERE tabla = :id");
$id = 'nota_credito';
$query_aux->bindParam(":id", $id);
$query_aux->execute();

echo json_encode(array("Result" => "OK"));
*/

date_default_timezone_set('America/Lima');

use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;

require __DIR__ . '/vendor/autoload.php';
$see = require __DIR__ . '/config.php';
require __DIR__ . '/env/env.php';

// Función para registrar logs
function logError($message, $data = []) {
    $logFile = __DIR__ . '/logs/nota_credito_' . date('Y-m-d') . '.log';
    $logMessage = date('Y-m-d H:i:s') . " - " . $message . "\n";
    if (!empty($data)) {
        $logMessage .= "Datos: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

try {
    // Validar parámetros requeridos
    $num_factura = $_GET['cod_factura'] ?? null;
    $motivo = $_GET['motivo'] ?? null;
    $cod_motivo = $_GET['cod_motivo'] ?? null;
    $correlativo = $_GET['correlativo'] ?? null;

    if (!$num_factura || !$motivo || !$cod_motivo || !$correlativo) {
        throw new Exception("Faltan parámetros requeridos");
    }

    // Consultar factura
    $query_factura = $mbd->prepare("SELECT * FROM ventas_cabecera WHERE codigo_venta = :num_factura");
    $query_factura->bindParam(":num_factura", $num_factura);
    $query_factura->execute();
    $factura = $query_factura->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        throw new Exception("Factura no encontrada: " . $num_factura);
    }

    // Verificar si ya está anulada
    if ($factura['estado_anulado'] == 1) {
        throw new Exception("La factura ya está anulada");
    }

    // Consultar datos del cliente
    $query = $mbd->prepare("SELECT no, email1, phone1, address1, name FROM person WHERE id = :id");
    $query->bindParam(":id", $factura['id_person']);
    $query->execute();
    $ruc_db = $query->fetch(PDO::FETCH_ASSOC);

    if (!$ruc_db) {
        throw new Exception("Cliente no encontrado");
    }

    $client = (new Client())
        ->setTipoDoc('6')
        ->setNumDoc(str_replace(" ", "", $ruc_db['no']))
        ->setRznSocial($ruc_db['name']);

    $see->setCertificate(file_get_contents(__DIR__.'/certificate_pv_2024.pem'));
    $see->setClaveSOL('20455175781', 'PERUVI11', 'Omcipier11');

    $address = (new Address())
        ->setUbigueo('040109')
        ->setDepartamento('AREQUIPA')
        ->setProvincia('AREQUIPA')
        ->setDistrito('MARIANO MELGAR')
        ->setUrbanizacion('-')
        ->setDireccion('CAL.BELEN MZA. B LOTE. 8 AREQUIPA - AREQUIPA - MARIANO MELGAR')
        ->setCodLocal('0000');

    $company = (new Company())
        ->setRuc('20455175781')
        ->setRazonSocial('PERUVIAN DRESS TPX S.A.C.')
        ->setNombreComercial('PERUVIAN DRESS TPX S.A.C.')
        ->setAddress($address);

    // Crear nota de crédito
    $note = new Note();
    $note
        ->setUblVersion('2.1')
        ->setTipDocAfectado('01')
        ->setNumDocfectado($num_factura)
        ->setCodMotivo($cod_motivo)
        ->setDesMotivo($motivo)
        ->setTipoDoc('07')
        ->setSerie('FF01')
        ->setFechaEmision(new DateTime())
        ->setCorrelativo($correlativo)
        ->setTipoMoneda('PEN')
        ->setCompany($company)
        ->setClient($client)
        ->setMtoOperGravadas($factura['subtotal'])
        ->setMtoIGV($factura['igv'])
        ->setTotalImpuestos($factura['igv'])
        ->setMtoImpVenta($factura['total']);

    // Consultar detalle
    $query_detalle = $mbd->prepare("SELECT * FROM ventas_detalle WHERE codigo_venta_cabecera = :codigo_venta");
    $query_detalle->bindParam(":codigo_venta", $num_factura);
    $query_detalle->execute();

    $items = [];
    while ($res = $query_detalle->fetch(PDO::FETCH_ASSOC)) {
        $pr = $mbd->prepare("SELECT * FROM product WHERE id = :id");
        $pr->bindParam(":id", $res['id_producto']);
        $pr->execute();
        $producto = $pr->fetch(PDO::FETCH_ASSOC);

        $item = new SaleDetail();
        $monto_valor_venta = number_format($res['precio_unitario'] * $res['cantidad'], 2, ".", "");
        $precio_bordado = $res['precio_bordado'] ?? 0;
        $monto_total = $monto_valor_venta + $precio_bordado;

        $item->setCodProducto($res['id_producto'])
            ->setUnidad('NIU')
            ->setDescripcion($res['tipo'])
            ->setCantidad($res['cantidad'])
            ->setMtoValorUnitario($res['precio_unitario'])
            ->setMtoValorVenta($monto_total)
            ->setMtoBaseIgv($monto_total)
            ->setPorcentajeIgv(18)
            ->setIgv($monto_total * 0.18)
            ->setTipAfeIgv('10')
            ->setTotalImpuestos($monto_total * 0.18)
            ->setMtoPrecioUnitario(($monto_total * 0.18) + $monto_total);

        $items[] = $item;
    }

    if (empty($items)) {
        throw new Exception("No se encontraron items para la nota de crédito");
    }

    // Convertir total a letras
    $total_letras = json_decode(file_get_contents('https://dbusinessaqp.com/numero_2_letras/conversor.php?total=' . $factura['total']));

    $note->setDetails($items)
        ->setLegends([
            (new Legend())
                ->setCode('1000')
                ->setValue($total_letras->letras)
        ]);

    // Guardar XML antes de enviar
    file_put_contents(
        __DIR__ . '/xml/' . $note->getName() . '.xml',
        $see->getFactory()->getLastXml()
    );

    // ENVIAR A SUNAT
    $result = $see->send($note);

    // VALIDACIÓN CRÍTICA: Verificar respuesta
    if (!$result->isSuccess()) {
        $error = $result->getError();
        logError("Error al enviar nota de crédito", [
            'factura' => $num_factura,
            'error_code' => $error->getCode(),
            'error_message' => $error->getMessage()
        ]);
        
        echo json_encode([
            "Result" => "ERROR",
            "Message" => "Error al enviar a SUNAT: " . $error->getMessage(),
            "Code" => $error->getCode()
        ]);
        exit;
    }

    // Obtener CDR (Constancia de Recepción)
    $cdr = $result->getCdrResponse();
    
    if (!$cdr) {
        logError("No se recibió CDR de SUNAT", ['factura' => $num_factura]);
        echo json_encode([
            "Result" => "ERROR",
            "Message" => "No se recibió respuesta de SUNAT"
        ]);
        exit;
    }

    // Guardar CDR
    file_put_contents(
        __DIR__ . '/cdr/R-' . $note->getName() . '.zip',
        $result->getCdrZip()
    );

    $code = (int)$cdr->getCode();
    $description = $cdr->getDescription();
    
    // Log de respuesta de SUNAT
    logError("Respuesta de SUNAT", [
        'factura' => $num_factura,
        'code' => $code,
        'description' => $description,
        'notes' => $cdr->getNotes()
    ]);

    // VALIDACIÓN ESTRICTA: Solo códigos 0 o con observaciones aceptadas
    // Códigos aceptados por SUNAT:
    // 0 = Aceptado
    // 0001-0999 = Observaciones pero aceptado
    // 2000+ = Rechazado
    // 4000 = Rechazado
    
    $aceptado = false;
    
    if ($code === 0) {
        $aceptado = true;
    } elseif ($code >= 1 && $code < 2000) {
        // Observaciones pero aceptado
        $aceptado = true;
    } else {
        // Rechazado
        $aceptado = false;
    }

    if (!$aceptado) {
        logError("Nota de crédito RECHAZADA por SUNAT", [
            'factura' => $num_factura,
            'code' => $code,
            'description' => $description
        ]);
        
        echo json_encode([
            "Result" => "RECHAZADO",
            "Message" => "SUNAT rechazó la nota de crédito: " . $description,
            "Code" => $code
        ]);
        exit;
    }

    // SOLO SI FUE ACEPTADO: Actualizar base de datos
    $mbd->beginTransaction();

    try {
        $fecha_anulacion = date("Y-m-d H:i:s");
        
        $query_anular = $mbd->prepare("UPDATE ventas_cabecera 
            SET estado_anulado = 1, 
                motivo = :motivo, 
                correlativo_nc = :correlativo, 
                fecha_anulacion = :fecha_anulacion,
                codigo_sunat_nc = :codigo_sunat,
                descripcion_sunat_nc = :descripcion_sunat,
                total = 0, 
                igv = 0, 
                detraccion_p = 0, 
                igv_p = 0, 
                subtotal = 0, 
                valor_pagar = 0, 
                a_cuenta = 0 
            WHERE codigo_venta = :codigo_venta");
        
        $query_anular->bindParam(":codigo_venta", $factura['codigo_venta']);
        $query_anular->bindParam(":motivo", $motivo);
        $query_anular->bindParam(":correlativo", $correlativo);
        $query_anular->bindParam(":fecha_anulacion", $fecha_anulacion);
        $query_anular->bindParam(":codigo_sunat", $code);
        $query_anular->bindParam(":descripcion_sunat", $description);
        $query_anular->execute();

        $query_aux = $mbd->prepare("UPDATE aux SET id = id + 1 WHERE tabla = :id");
        $id = 'nota_credito';
        $query_aux->bindParam(":id", $id);
        $query_aux->execute();

        $mbd->commit();

        logError("Nota de crédito ACEPTADA y registrada", [
            'factura' => $num_factura,
            'correlativo' => $correlativo,
            'code' => $code
        ]);

        echo json_encode([
            "Result" => "OK",
            "Message" => "Nota de crédito aceptada por SUNAT",
            "Code" => $code,
            "Description" => $description,
            "NotaCredito" => $note->getName()
        ]);

    } catch (Exception $e) {
        $mbd->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    logError("Excepción en proceso de nota de crédito", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    
    echo json_encode([
        "Result" => "ERROR",
        "Message" => $e->getMessage()
    ]);
}