<?php
include("clsCostos.php");
$costo = new clsCostos;
$data_ingreso = json_decode($costo->get_data_ingreso($_GET['id_producto']));
$lista_directos = json_decode($costo->lista_directos($_GET['id_producto']));
$lista_extras = json_decode($costo->lista_empaques($_GET['id_producto']));
$lista_empaques = json_decode($costo->lista_extras($_GET['id_producto']));
$lista_bordado = json_decode($costo->lista_bordado($_GET['id_producto']));
$totales = json_decode($costo->get_totales_2($_GET['id_producto'], 0));
$data_producto = json_decode($costo->data_producto($_GET['id_producto']));

$MOD = $data_ingreso->mod;
$di_hor_laboradas = $data_ingreso->data_ingreso->di_hor_laboradas;
$di_por_capacidad = $data_ingreso->data_ingreso->di_por_capacidad;
$di_nro_operarios = $data_ingreso->data_ingreso->di_nro_operarios;
$di_tie_confeccion = $data_ingreso->data_ingreso->di_tie_confeccion;
$cuota_produccion = intval((($di_hor_laboradas * 60) * ($di_por_capacidad / 100) * $di_nro_operarios) / $di_tie_confeccion);
$MOI = number_format(($data_ingreso->moi / $cuota_produccion * ($di_por_capacidad / 100)), 2);
$CIF = number_format(($data_ingreso->cif / $cuota_produccion), 2);

$costos_fijos = number_format($data_ingreso->costos_fijos / $cuota_produccion, 2);
$gaf = number_format($data_ingreso->gaf / $cuota_produccion, 2);
$gvm = number_format($data_ingreso->gvm / $cuota_produccion, 2);


$di_tal_estimar = $data_ingreso->data_ingreso->di_tal_estimar;
$tarifa_corte = $data_ingreso->data_ingreso->tarifa_corte;
$id_producto = $data_ingreso->data_ingreso->id_producto;
$di_total_confeccion = $data_ingreso->data_ingreso->di_total_confeccion;
$di_confeccion_margen = $data_ingreso->data_ingreso->di_confeccion_margen;
$di_margen = $data_ingreso->data_ingreso->di_margen;

$costo_prenda = number_format($totales->costo_prenda, 2);
$utilidad = number_format($totales->utilidad, 2);
$valor_venta = number_format($totales->valor_venta, 2);
$igv = number_format($totales->igv, 2);
$renta = number_format($totales->renta, 2);
$precio_venta = number_format($totales->precio_venta, 2);


$tabla_directos = '';
foreach ($lista_directos as $key) {
    $tabla_directos .= '<tr>
        <td>' . $key->insumo . '</td>
        <td>' . $key->unidad . '</td>
        <td>' . $key->consumo_teorico . '</td>
        <td>' . $key->merma . '</td>
        <td>' . $key->consumo_real . '</td>
        <td>' . $key->costo_unitario . '</td>
        <td>' . $key->costo_total . '</td>
    </tr>';
}

$tabla_extras = '';
foreach ($lista_extras as $key) {
    $tabla_extras .= '<tr>
        <td>' . $key->insumo . '</td>
        <td>' . $key->unidad . '</td>
        <td>' . $key->consumo_teorico . '</td>
        <td>' . $key->merma . '</td>
        <td>' . $key->consumo_real . '</td>
        <td>' . $key->costo_unitario . '</td>
        <td>' . $key->costo_total . '</td>
    </tr>';
}

$tabla_empaques = '';
foreach ($lista_empaques as $key) {
    $tabla_empaques .= '<tr>
        <td>' . $key->insumo . '</td>
        <td>' . $key->unidad . '</td>
        <td>' . $key->consumo_teorico . '</td>
        <td>' . $key->merma . '</td>
        <td>' . $key->consumo_real . '</td>
        <td>' . $key->costo_unitario . '</td>
        <td>' . $key->costo_total . '</td>
    </tr>';
}

$tabla_bordado = '';
foreach ($lista_bordado as $key) {
    $tabla_bordado .= '<tr>
        <td>' . $key->concepto . '</td>
        <td>' . number_format($key->bordado, 2) . '</td>
    </tr>';
}


$html = '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        td{
            padding: 5px;
        }
        h5{
            margin-top: 0;
            margin-bottom: 1rem;
        }
        h4{
            margin-top: 1rem;
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <table class="table" style="width: 100%;">
        <tr>
            <td style="text-align: center;">
                <img src="' . $_SERVER['DOCUMENT_ROOT'] . '/img/logo-3.png" style="width: 150px;" />
            </td>
            <td colspan="3" style="text-align: left;">
                <h4><strong>COSTEO DE PRENDAS</strong></h4>
            </td>
        </tr>
        <tr>
            <th class="bold">Cod. Modelo</th>
            <td id="codigo_producto">' . $data_producto->code . '</td>
            <th class="bold">Fecha:</th>
            <td>' . date("d-m-Y") . '</td>
        </tr>
        <tr>
            <th class="bold">Descripción</th>
            <td id="nombre_producto" colspan="3">' . $data_producto->name . '</td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 100%;">
                <h4>
                    <strong>
                        A: DATOS DE INGRESO
                    </strong>
                </h4>
            </td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%;">
                <table border=1>
                    <tr>
                        <th>% de Capacidad</th>
                        <td>' . $di_por_capacidad . '</td>
                    </tr>
                    <tr>
                        <th>Nro. de Operarios</th>
                        <td>' . $di_nro_operarios . '</td>
                    </tr>
                    <tr>
                        <th>Timpo estimado en confeccionar 1 prenda</th>
                        <td>' . $di_tie_confeccion . '</td>
                    </tr>
                    <tr>
                        <th>Horas Laboradas</th>
                        <td>' . $di_hor_laboradas . '</td>
                    </tr>
                    <tr>
                        <th>Talla a Estimar</th>
                        <td>' . $di_tal_estimar . '</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <table border=1>
                    <tr>
                        <th>Cuota/Producción</th>
                        <td>' . $cuota_produccion . ' Prendas/día</td>
                    </tr>
                    <tr>
                        <th>S/</th>
                        <td>' . $MOD . ' MOD</td>
                    </tr>
                    <tr>
                        <th>S/</th>
                        <td>' . $MOI . ' MOI</td>
                    </tr>
                    <tr>
                        <th>S/</th>
                        <td>' . $CIF . ' CIF</td>
                    </tr>
                    <tr>
                        <th>S/</th>
                        <td>' . $costos_fijos . ' Costos Fijos</td>
                    </tr>
                    <tr>
                        <th>S/</th>
                        <td>' . $gaf . ' GAF</td>
                    </tr>
                    <tr>
                        <th>S/</th>
                        <td>' . $gvm . ' GVM</td>
                    </tr>
                    <tr>
                        <th>S/</th>
                        <td>' . $tarifa_corte . ' TARIFA CORTE</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 33.33333%;"></td>
            <td style="width: 33.33333%;">
                <table border=1>
                    <tr>
                        <th>Total Confección</th>
                        <td>' . $di_total_confeccion . '</td>
                    </tr>
                    <tr>
                        <th>Margen</th>
                        <td>' . $di_margen . '</td>
                    </tr>
                    <tr>
                        <th>Confección + Margen</th>
                        <td>' . $di_confeccion_margen . '</td>
                    </tr>
                </table>
            </td>
            <td style="width: 33.33333%;"></td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 100%;">
                <h4>
                    <strong>
                        B: MATERIALES
                    </strong>
                </h4>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;">
                <h5>
                    <strong>
                        B.1. MATERIALES DIRECTOS
                    </strong>
                </h5>
            </td>
        </tr>
    </table>
    <table border=1>
        <tr>
            <th>Descripción</th>
            <th>Unidad Medida</th>
            <th>Consumo Teórico</th>
            <th>% Merma</th>
            <th>Consumo Real</th>
            <th>Costo Unitario</th>
            <th>Costo Total</th>
        </tr>
        ' . $tabla_directos . '
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 100%;">
                <h5>
                    <strong>
                        B.2. MATERIALES EXTRAS INCORPORADOS
                    </strong>
                </h5>
            </td>
        </tr>
    </table>
    <table border=1>
        <tr>
            <th>Descripción</th>
            <th>Unidad Medida</th>
            <th>Consumo Teórico</th>
            <th>% Merma</th>
            <th>Consumo Real</th>
            <th>Costo Unitario</th>
            <th>Costo Total</th>
        </tr>
        ' . $tabla_empaques . '
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 100%;">
                <h5>
                    <strong>
                        B.3. MATERIALES EXTRAS EMPAQUE
                    </strong>
                </h5>
            </td>
        </tr>
    </table>
    <table border=1>
        <tr>
            <th>Descripción</th>
            <th>Unidad Medida</th>
            <th>Consumo Teórico</th>
            <th>% Merma</th>
            <th>Consumo Real</th>
            <th>Costo Unitario</th>
            <th>Costo Total</th>
        </tr>
        ' . $tabla_extras . '
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 100%;">
                <h4>
                    <strong>
                        C: SERVICIO EXTERNO
                    </strong>
                </h4>
            </td>
        </tr>
    </table>
    <table border=1 style="width: 100%;">
        <tr>
            <th>Concepto</th>
            <th>Costo</th>
        </tr>
        ' . $tabla_bordado . '
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 100%;">
                <h4>
                    <strong>
                        D: TARIFA USO DEL TALLER
                    </strong>
                </h4>
            </td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 33.333333%;"></td>
            <td style="width: 33.333333%;">
                <table border=1>
                    <tr>
                        <th>Costo Total Prenda</th>
                        <td>' . $costo_prenda . '</td>
                    </tr>
                    <tr>
                        <th>Utilidad</th>
                        <td>' . $utilidad . '</td>
                    </tr>
                    <tr>
                        <th>Valor de Venta</th>
                        <td>' . $valor_venta . '</td>
                    </tr>
                    <tr>
                        <th>Impuesto General de Ventas</th>
                        <td>' . $igv . '</td>
                    </tr>
                    <tr>
                        <th>Impuesto a la Renta</th>
                        <td>' . $renta . '</td>
                    </tr>
                    <tr>
                        <th>Precio Venta</th>
                        <td>' . $precio_venta . '</td>
                    </tr>
                </table>
            </td>
            <td style="width: 33.333333%;"></td>
        </tr>
    </table>
</body>

</html>';
//echo $html;
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('my.pdf', array('Attachment' => 0));
