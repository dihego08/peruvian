<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
date_default_timezone_set('America/Lima');

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Charge;

use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Prepayment;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

require __DIR__ . '/vendor/autoload.php';

$see = require __DIR__ . '/config.php';

require __DIR__ . '/env/env.php';

$num_factura = $_POST['codigo_venta'];

$query_factura = $mbd->prepare("SELECT * FROM ventas_cabecera WHERE codigo_venta = :num_factura");
$query_factura->bindParam(":num_factura", $num_factura);
$query_factura->execute();

$factura = $query_factura->fetch(PDO::FETCH_ASSOC);

$ruc_cliente_ = "";

$query = $mbd->prepare("SELECT no, email1, phone1, address1, name from person WHERE id = :id");
$query->bindParam(":id", $factura['id_person']);
$query->execute();

$ruc_db = $query->fetch(PDO::FETCH_ASSOC);

$num_factura = explode("-", $num_factura);

// try {

//     $postdata = http_build_query(
//         array(
//             'action' => 'getnumero',
//             'numero' => str_replace(" ", "", $ruc_db['no'])
//         )
//     );

//     $opts = array(
//         'http' =>
//         array(
//             'method'  => 'POST',
//             'header'  => 'Content-Type: application/x-www-form-urlencoded',
//             'content' => $postdata
//         )
//     );

//     $context  = stream_context_create($opts);

//     $result = file_get_contents('https://incared.com/api/apirest', false, $context);
//     $obj = json_decode($result);
// } catch (Exception $ex) {
// }

$client = new Client();
$email = "";
$phone = "";
$address = "";


if (empty($ruc_db['email1']) || $ruc_db['email1'] == null || !isset($ruc_db['email1'])) {
    $email = "-";
} else {
    $email = $ruc_db['email1'];
}
if (empty($ruc_db['phone1']) || $ruc_db['phone1'] == null || !isset($ruc_db['phone1'])) {
    $phone = "-";
} else {
    $phone = $ruc_db['phone1'];
}
if (empty($ruc_db['address1']) || $ruc_db['address1'] == null || !isset($ruc_db['address1'])) {
    $address = ""; //$obj->direccion_string;
} else {
    $address = $ruc_db['address1'];
}

// Cliente
$client = (new Client())
    ->setTipoDoc('6')
    ->setNumDoc(str_replace(" ", "", $ruc_db['no']))
    ->setRznSocial($ruc_db['name']);

// Emisor
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

$vencimiento = $factura['fecha_vencimiento'];

$query_detalle = $mbd->prepare("SELECT * FROM ventas_detalle WHERE codigo_venta_cabecera = :codigo_venta");
$query_detalle->bindParam(":codigo_venta", $_POST['codigo_venta']);
$query_detalle->execute();

$items = [];

$total___ = 0;

while ($res = $query_detalle->fetch(PDO::FETCH_ASSOC)) {
    $pr = $mbd->prepare("SELECT name, unit, prebor_out FROM product WHERE id = :id");
    $pr->bindParam(":id", $res['id_producto']);
    $pr->execute();

    $producto = $pr->fetch(PDO::FETCH_ASSOC);

    $unidad = "";
    if ($producto['unit'] == "" || empty($producto['unit']) || $producto['unit'] == null) {
        $unidad = "UNI";
    } else {
        $unidad = $producto['unit'];
    }

    $item = new SaleDetail();

    $monto_valor_venta = number_format($res['precio_unitario'] * $res['cantidad'], 2, ".", "");
    if (empty($res['precio_bordado']) || is_null($res['precio_bordado'])) {
        $res['precio_bordado'] = 0;
    }

    /*echo $res['cantidad']."\n";
    echo $res['precio_unitario']."\n";
    echo $monto_valor_venta + $res['precio_bordado']."\n";
    echo ($monto_valor_venta + $res['precio_bordado'])."\n";
    echo number_format(($monto_valor_venta + $res['precio_bordado']) * 0.18, 2, ".", "")."\n";
    echo number_format(($monto_valor_venta + $res['precio_bordado']) * 0.18, 2, ".", "")."\n";
    echo number_format((($monto_valor_venta + $res['precio_bordado']) * 0.18) + ($monto_valor_venta + $res['precio_bordado']), 2, ".", "")."\n";*/

    $item->setCodProducto($res['id_producto'])
        //->setUnidad($unidades_r_[$i])
        ->setUnidad('NIU')
        ->setDescripcion($res['tipo'])
        ->setCantidad($res['cantidad'])
        ->setMtoValorUnitario($res['precio_unitario'])
        ->setMtoValorVenta($monto_valor_venta + $res['precio_bordado'])
        ->setMtoBaseIgv(($monto_valor_venta + $res['precio_bordado']))
        ->setPorcentajeIgv(18)
        ->setIgv(number_format(($monto_valor_venta + $res['precio_bordado']) * 0.18, 2, ".", ""))
        ->setTipAfeIgv('10')
        ->setTotalImpuestos(number_format(($monto_valor_venta + $res['precio_bordado']) * 0.18, 2, ".", ""))
        ->setMtoPrecioUnitario(number_format((($monto_valor_venta + $res['precio_bordado']) * 0.18) + ($monto_valor_venta + $res['precio_bordado']), 2, ".", ""));

    $items[] = $item;
}
//echo "IGV ".$factura['igv']."<br>";

//$otr = $factura['subtotal'] - 84823.45;

if ($factura['descuento'] > 0) {



    $invoice = (new Invoice())
        ->setUblVersion('2.1')
        ->setFecVencimiento(new DateTime($vencimiento))
        ->setTipoOperacion('0101')
        ->setTipoDoc('01')
        ->setSerie($num_factura[0])
        ->setCorrelativo($num_factura[1])
        ->setFechaEmision(new DateTime($factura['fecha_creacion']))
        ->setTipoMoneda('PEN')
        ->setCompany($company)
        ->setClient($client)
        ->setMtoOperExoneradas(0)

        ->setMtoIGV($factura['igv'])
        ->setMtoOperGravadas($factura['subtotal'])
        ->setTotalImpuestos($factura['igv'])
        ->setValorVenta($factura['subtotal'])
        ->setSubTotal($factura['total'])
        ->setMtoImpVenta($factura['total'])
        ->setFormaPago(new FormaPagoContado())


        ->setDescuentos([
            (new Charge())
                ->setCodTipo('02') // Catalog. 53
                ->setMontoBase($factura['descuento'])
                ->setFactor(1)
                ->setMonto($factura['descuento'])
        ]);
} else {
    $invoice = (new Invoice())
        ->setUblVersion('2.1')
        ->setFecVencimiento(new DateTime($vencimiento))
        ->setTipoOperacion('0101')
        ->setTipoDoc('01')
        ->setSerie($num_factura[0])
        ->setCorrelativo($num_factura[1])
        ->setFechaEmision(new DateTime($factura['fecha_creacion']))
        ->setTipoMoneda('PEN')
        ->setCompany($company)
        ->setClient($client)
        ->setMtoOperExoneradas(0)

        ->setMtoIGV($factura['igv'])
        ->setMtoOperGravadas($factura['subtotal'])
        ->setTotalImpuestos($factura['igv'])
        ->setValorVenta($factura['subtotal'])
        ->setSubTotal($factura['total'])
        ->setMtoImpVenta($factura['total'])
        ->setFormaPago(new FormaPagoContado());
}
$total_letras = json_decode(file_get_contents('https://dbusinessaqp.com/numero_2_letras/conversor.php?total=' . $factura['total']));

$invoice->setDetails($items)
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue($total_letras->letras)
    ]);

//print_r($invoice);

$result = $see->send($invoice);

// Guardar XML firmado digitalmente.
file_put_contents(
    $invoice->getName() . '.xml',
    $see->getFactory()->getLastXml()
);

// Verificamos que la conexión con SUNAT fue exitosa.
if (!$result->isSuccess()) {
    // Mostrar error al conectarse a SUNAT.
    echo 'Codigo Error: ' . $result->getError()->getCode();
    echo 'Mensaje Error: ' . $result->getError()->getMessage();
    echo json_encode(array("Result" => "ERROR"));
    //exit();
}

// Guardamos el CDR
file_put_contents('R-' . $invoice->getName() . '.zip', $result->getCdrZip());

$cdr = $result->getCdrResponse();

$code = (int)$cdr->getCode();

if ($code === 0) {
    $query_upd = $mbd->prepare("UPDATE ventas_cabecera SET envio_sunat = 1 WHERE codigo_venta = '" . $_POST['codigo_venta'] . "'");
    $query_upd->execute();
    echo json_encode(array("Result" => "SUCCESS"));
} else if ($code >= 2000 && $code <= 3999) {
    //echo 'ESTADO: RECHAZADA'.PHP_EOL;
    echo json_encode(array("Result" => "ERROR"));
} else {
    echo json_encode(array("Result" => "ERROR"));
}
