<?php
/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	ini_set('max_execution_time', 120);
	ini_set('max_input_time', 120);
	ini_set('memory_limit', '256M');
	ini_set('post_max_size', '25M');
	ini_set('default_socket_timeout', 240);
	error_reporting(E_ALL);*/
include("env.php");

$cod_venta = $_GET['codigo_venta'];

$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_creacion) as fecha_creacion, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.codigo_venta = :cod_venta");
$query->bindParam(":cod_venta", $cod_venta);
$query->execute();

$venta = $query->fetch(PDO::FETCH_ASSOC);

$query_person = $mbd->prepare("SELECT * FROM person WHERE id = :id_person");
$query_person->bindParam(":id_person", $venta['id_person']);
$query_person->execute();

$person = $query_person->fetch(PDO::FETCH_ASSOC);

$cliente['direccion'] = $person['address1'];
$cliente['razon_social'] = $person['name'];
$cliente['ruc'] = $person['no'];

$query_2 = $mbd->prepare("SELECT p.name, vd.* FROM ventas_detalle as vd, product as p WHERE p.id = vd.id_producto AND vd.codigo_venta_cabecera = :codigo");
$query_2->bindParam(':codigo', $cod_venta);
$query_2->execute();
$table = "<table id=\"tabla_detalles\" style=\"border: solid 1px #eeeeee; text-align: center; margin-top: 10px; width: 780px; font-size: 10px;\"><tr style=\"border-bottom: solid 1px;\">
                        <th>Cantidad</th>
                        <th>Pedido</th>
                        <th>Modelo</th>
                        <th>Descripción</th>
                        <th>Valor Unitario</th>
                        <th>Valor Total</th>
                    </tr>";
while ($res = $query_2->fetch(PDO::FETCH_ASSOC)) {
	$desc = "";
	if (empty($res['tipo']) || is_null($res['tipo'])) {
		$desc = $res['name'];
	} else {
		$desc = $res['tipo'];
	}
	if (empty($res['pedido_cod']) || is_null($res['pedido_cod'])) {
		$res['pedido_cod'] = str_replace( "--", "<br>", substr($venta['pedido_cod'], 3, strlen($venta['pedido_cod'])));
	}
	$p_unitario = 0;
	if ($res['precio_unitario'] == 0) {
	} else {
		$p_unitario = number_format(($res['precio_unitario'] + $res['precio_bordado'] / $res['cantidad']), 2);
	}
	$table .= '<tr>
                        <td style="width: 66px; padding: 5px;"><p>' . $res['cantidad'] . ' ' . $res['unidad'] . '</p></td>
                        <td style="width: 66px; padding: 5px;">' . $res['pedido_cod'] . '</td>
                        <td style="width: 66px; padding: 5px;">' . $res['codigo_unidad'] . '</td>
                        <td style="width: 186px; padding: 5px;">' . $desc . '</td>	 	
                        <td style="width: 100px; padding: 5px;">S/ ' . $p_unitario . '</td>
                        <td style="width: 100px; padding: 5px;">S/ ' . number_format(($res['precio_unitario'] * $res['cantidad']) + $res['precio_bordado'], 2) . '</td>
                    </tr>';
}
$desc = "";
if ($venta['descuento'] > 0) {
	$table .= '<tr>
                        <td style="width: 66px; padding: 5px;"></td>
                        <td style="width: 66px; padding: 5px;"></td>
                        <td style="width: 66px; padding: 5px;"></td>
                        <td style="width: 186px; padding: 5px;">' . strtoupper($venta['desc_descuento']) . '</td>	 	
                        <td style="width: 100px; padding: 5px;">S/ ' . $venta['descuento'] . '</td>
                        <td style="width: 100px; padding: 5px;">S/ ' . $venta['descuento'] . '</td>
                    </tr>';
}
$table .= '</table>';

$total_letras = json_decode(file_get_contents('https://dbusinessaqp.com/numero_2_letras/conversor.php?total=' . $venta['total']));


//Agregamos la libreria para genera códigos QR
require "phpqrcode/qrlib.php";

//Declaramos una carpeta temporal para guardar la imagenes generadas
$dir = 'temp_/';

//Si no existe la carpeta la creamos
if (!file_exists($dir))
	mkdir($dir);

//Declaramos la ruta y nombre del archivo a generar
$filename = $dir . 'test.png';

//Parametros de Condiguración

$tamaño = 10; //Tamaño de Pixel
$level = 'L'; //Precisión Baja
$framSize = 3; //Tamaño en blanco
$contenido = "https://peruvian.peruviandress.com/core/app/view/pdf-venta.php?codigo_venta=" . $_GET['codigo_venta']; //Texto

//Enviamos los parametros a la Función para generar código QR 
QRcode::png($contenido, $filename, $level, $tamaño, $framSize);

//Mostramos la imagen generada
//echo '<img src="'.$dir.basename($filename).'" /><hr/>';

$tabla_credito = "";
if ($venta['id_estado_pago'] == 4) {
	$tabla_credito = "<div style=\"border: solid 1px; border-radius: 10px; padding: 5px 10px; width: 680px; font-size: 9px;\">
		    <h5 style=\"margin-bottom: 0; width: 100%; display: block;\">Información del Crédito</h5>
			<table style=\"width: 680px;\">
				<tr>
					<td style=\"width: 340px;\">
						<table>
							<tr>
								<td style=\"padding: 5px; white-space: break-spaces;\">
									<strong style=\"font-size: 12px;\">Monto neto pendiente de pago</strong>
                    				
								</td>
							</tr>
							<tr>
								<td style=\"padding: 5px;\">
									<strong style=\"font-size: 12px;\">Total de cuotas</strong>
                    				
								</td>
							</tr>
							
						</table>
					</td>
					<td style=\"width: 340px;\">
						<table style=\"width: 100%;\">
							<tr>
								<td style=\"width: 100%; padding: 5px;\">
                    				: <span>S/ " . $venta['a_cuenta'] . "</span>
								</td>
							</tr>
							<tr>
								<td style=\"width: 100%; padding: 5px;\">
									: " . $venta['n_cuotas'] . "
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
				    <td colspan=\"2\">
				        <table style=\"width: 680px; border-top: solid 1px;\">
				            <tr>
								<td style=\"font-weight: bold; width: 33.33%;\">
								    Número de Cuota
								</td>
								<td style=\"font-weight: bold; width: 33.33%;\">
								    Fecha Vencimiento
								</td>
								<td style=\"font-weight: bold; width: 33.33%;\">
								    Monto
								</td>
							</tr>
							<tr>
								<td>1</td>
								<td>
								    " ./*date("d-m-Y",strtotime($venta['fecha_creacion']."+ 30 days"))*/ $venta['fecha_vencimiento'] . "
								</td>
								<td>" . $venta['a_cuenta'] . "</td>
							</tr>
				        </table>
				    </td>
				</tr>
			</table>
        </div>";
}

if ($venta['descuento'] > 0) {
	$ds = "<tr style=\"width: 200px;\">
	<td style=\"text-align: right;\"><strong>Descuento: </strong></td>
	<td style=\"padding: 10px; border-bottom: solid 1px;\">S/ " . $venta['descuento'] . "</td>
</tr>";
}

$pipi = "
	<page pageset='new' backbottom='10mm' backright='10mm' backleft='10mm' footer='page'>
		<page_header>
			
		</page_header>
		<page_footer>
		</page_footer>

			<table id=\"encabezado\" style=\"margin-bottom: 25px; font-size: 11px;\">
				<tr>
					<td style='padding: 5px; border-radius: 4px; width: 222px; text-align: left;'>
						<table style=\"width: 222px;\">
							<tr>
								<td style=\"text-align: center; width: 400px;\">
									<img src=\"" . $_SERVER['DOCUMENT_ROOT'] . "/img/logo-3.png\" align='left' border='0' style='width: 150px;' />
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px; font-weight: bold; font-size: 20px;\">
									PERUVIAN DRESS TPX S.A.C.
								</td>
							</tr>
							<tr>
								<td style=\"text-align: left; width: 222px;\">
									<strong>Dirección: </strong>" . "CAL.BELEN MZA. B LOTE. 8 JERUSALEN - MARIANO - MELGAR - AREQUIPA - AREQUIPA" . "
								</td>
							</tr>
							<tr>
								<td style=\"text-align: left; width: 222px;\">
									<strong>Celular.: </strong>958133948
								</td>
							</tr>
							<tr>
							    <td style=\"text-align: left; width: 222px;\">
							        <strong>Correo : </strong>omendoza@peruviandress.com
							    </td>
							</tr>
						</table>
					</td>
					<td style='width: 44px; '>
						
					</td>
					<td style='padding: 5px; border-radius: 4px; width: 222px; text-align: center;'>
						
						
						<table style=\"width: 222px;\">
							<tr>
								<td style=\"text-align: center; width: 222px;\">
									<span style=\"font-weight: bold; font-size: 20px;\">Factura Electrónica</span>
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px; font-weight: bold;\">
									R.U.C.: 20455175781
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px;\">
									Nro. " . $venta['codigo_venta'] . "
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px;\">
									Nro. R.I. Emisor: 212321
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px;\">
									<strong>Guía de Remisión:</strong>
                    				" . $venta['guia'] . "
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<div style=\"border: solid 1px; border-radius: 10px; padding: 10px; width: 680px;\">
			
			<table style=\"width: 680px; font-size: 11px;\">
				<tr>
					<td style=\"width: 380px; white-space: break-spaces;\" >
						<table style=\"width: 380px;\">
							<tr>
								<td style=\"padding: 5px; white-space: break-spaces;\">
									<strong>Razón Social: </strong>
                    				<span style='white-space: break-spaces;'>" . wordwrap($cliente['razon_social'], 30, '<br />', true) . "</span>
								</td>
							</tr>
							<tr>
								<td style=\"padding: 5px;\">
									<strong>Fecha Emisión: </strong>
                    				" . $venta['fecha_emision'] . "
								</td>
							</tr>
							<tr>
								<td style=\"padding: 5px;\">
									<strong>Tipo Moneda: </strong>
                    				SOLES
								</td>
							</tr>
							
						</table>
					</td>
					<td style=\"width: 285px;\">
						<table style=\"width: 285px;\">
							<tr>
								<td style=\"width: 285px; padding: 5px;\">
									<strong>RUC: </strong>
                    				" . $cliente['ruc'] . "
								</td>
							</tr>
							<tr>
								<td style=\"width: 285px; padding: 5px;\">
									<strong>Dirección: </strong>
                    				" . $cliente['direccion'] . "
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
        </div>
        " . $table . "

		<table style=\"margin-top: 20px; width: 680px; font-size: 11px;\">
			<tr>
				<td style=\"width: 565px;\">
					<strong>Son: " . ucwords($total_letras->letras) . "</strong>
		            <p class=\"mt-3\" style=\"border-bottom: solid 1px #333333;\">
		                <strong>Información Adicional</strong>
		            </p>
		            <table class=\"table\">
		                <tr>
		                    <td><strong>Tipo de Transación: </strong></td>
		                    <td style=\"border-bottom: solid 1px;\">" . $venta['pago'] . "</td>
		                </tr>
		                <tr>
		                    <td><strong>Condición de Pago: </strong></td>
		                    <td style=\"border-bottom: solid 1px;\">" . $venta['tipo_pago'] . "</td>
		                </tr>
		                <tr>
		                    <td><strong>Fecha de Vencimiento: </strong></td>
		                    <td style=\"border-bottom: solid 1px;\">" . $venta['fecha_vencimiento'] . "</td>
		                </tr>
		            </table>
				</td>
				<td style=\"width: 200px;\">
					<table class=\"table\"style=\"width: 200px;\">
		            " . $ds . "
					<tr style=\"width: 200px;\">
		                    <td style=\"text-align: right;\"><strong>Subtotal: </strong></td>
		                    <td style=\"padding: 10px; border-bottom: solid 1px;\">S/ " . $venta['subtotal'] . "</td>
		                </tr>
		                <tr style=\"width: 200px;\">
		                    <td style=\"text-align: right;\"><strong>I.G.V.: </strong></td>
		                    <td style=\"padding: 10px; border-bottom: solid 1px;\">S/ " . $venta['igv'] . "</td>
		                </tr>
		                <tr style=\"width: 200px;\">
		                    <td style=\"text-align: right;\"><strong>Total: </strong></td>
		                    <td style=\"padding: 10px; border-bottom: solid 1px;\">S/ " . $venta['total'] . "</td>
		                </tr>
		            </table>
				</td>
			</tr>
		</table>
		" . $tabla_credito . "

<div style=\"border: solid 1px; border-radius: 10px; padding: 10px; width: 690px; margin-top: 20px; font-size: 11px;\">


<p><strong>DATOS BANCARIOS (M.N. SOLES):</strong></p>

      <div style='width: 690px; margin-top: 5px;'><span style='display: block;'><strong>CTA. CTE BANCO DE CREDITO DEL PERÚ: </strong> 2152497862044  /  C.C.I: 00221500249786204421</span></div>
      <div style='width: 690px; margin-top: 5px;'><span style='display: block;'><strong>CTA. AHORROS BANCO CONTINENTAL: </strong> 0011-0222-0200690076  /  C.C.I.: 011-222-000200690076-71</span></div>
      <div style='width: 690px; margin-top: 5px;'><span style='display: block;'><strong>CTA. CORRIENTE DETRACCIONES BANCO DE LA NACIÓN: </strong> 00-101-180476.</span> Detracción (10.00%): S/".$venta["detraccion_p"]."</div>
</div>


		<hr style=\"margin-top: 30px; width: 780px;\">
		<table>
			<tr>
				<td style=\"width: 565px; border: solid 1px #aaaaaa;\">
					<table>
						<tr>
							<td>
								consulte en www.peruviandress.com (https://www.peruviandress.com/fe)
							</td>
						</tr>
						<tr>
							<td>
								<!--<strong>Resumen:</strong> zH/NGT77JvsyTi8MeZrqX0sY1pY=-->
							</td>
						</tr>
						<tr>
							<td>
								Representación Impresa de la FACTURA ELECTRÓNICA.
							</td>
						</tr>
					</table>
				</td>
				<td style=\"width: 200px; \">
					<img src=\"" . $dir . basename($filename) . "\" align='left' border='0' style='width: 120px;' />
				</td>
			</tr>
		</table>
	</page>";

require __DIR__ . '/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

ob_start();

$html2pdf = new Html2Pdf('P', 'A4', 'es', 'false', 'UTF-8', array(0, 10, 10, 0));

$html2pdf->writeHTML($pipi);

$html2pdf->Output("venta-" . $cod_venta . ".pdf");
