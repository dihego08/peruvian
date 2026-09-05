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

$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.codigo_venta = :cod_venta");
$query->bindParam(":cod_venta", $cod_venta);
$query->execute();

$venta = $query->fetch(PDO::FETCH_ASSOC);
//print_r($venta);


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
$table = "<table id=\"tabla_detalles\" style=\"font-size: 10px; border: solid 1px #eeeeee; text-align: center; margin-top: 10px; width: 780px;\"><tr style=\"border-bottom: solid 1px;\">
                        <th>Cantidad</th>
                        <th>Pedido</th>
                        <th>Modelo</th>
                        <th>Descripción</th>
                        <th>Valor Unitario</th>
                        <th>Valor Total</th>
                    </tr>";
$base = 0;
while ($res = $query_2->fetch(PDO::FETCH_ASSOC)) {
    $desc = "";
    if (empty($res['tipo']) || is_null($res['tipo'])) {
        $desc = $res['name'];
    } else {
        $desc = $res['tipo'];
    }
    if (empty($res['pedido_cod']) || is_null($res['pedido_cod'])) {
        $res['pedido_cod'] = $venta['pedido_cod'];
    }
    $table .= '<tr>
                        <td style="width: 66px; padding: 5px;"><p>' . $res['cantidad'] . ' ' . $res['unidad'] . '</p></td>
                        <td style="width: 66px; padding: 5px;">' . $res['pedido_cod'] . '</td>
                        <td style="width: 66px; padding: 5px;">' . $res['codigo_unidad'] . '</td>
                        <td style="width: 186px; padding: 5px;">' . $desc . '</td>	 	
                        <td style="width: 100px; padding: 5px;">S/ ' . number_format(($res['precio_unitario'] + $res['precio_bordado'] / $res['cantidad']), 2) . '</td>
                        <td style="width: 100px; padding: 5px;">S/ ' . number_format(($res['precio_unitario'] * $res['cantidad']) + $res['precio_bordado'], 2) . '</td>
                    </tr>';
    $base += number_format(($res['precio_unitario'] * $res['cantidad']) + $res['precio_bordado'], 2);
}
$table .= '</table>';

//echo number_format($base * 1.18, 2, '.', '');
$total_letras = json_decode(file_get_contents('https://dbusinessaqp.com/numero_2_letras/conversor.php?total=' . number_format($venta['total_2'], 2, '.', '')));

$pipi = "
	<page pageset='new' backbottom='10mm' backright='10mm' backleft='10mm' footer='page'>
		<page_header>
			
		</page_header>
		<page_footer>
		</page_footer>

			<table id=\"encabezado\" style=\"margin-bottom: 10px;\">
				<tr>
					<td style='padding: 5px; border-radius: 4px; width: 222px; text-align: left;'>
						<table style=\"width: 222px;\">
							<tr>
								<td style=\"text-align: center; width: 400px;\">
									<img src=\"" . $_SERVER['DOCUMENT_ROOT'] . "/img/logo-2.png\" align='left' border='0' style='width: 80px;' />
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px; font-weight: bold; font-size: 20px;\">
									PERUVIAN DRESS TPX S.A.C.
								</td>
							</tr>
							<tr>
								<td style=\"text-align: left; width: 222px;\">
									<strong>Dirección: </strong>" . "CAL.BELEN MZA. B LOTE. 8 AREQUIPA - AREQUIPA - MARIANO MELGAR"/*$peruvian->result->Direccion*/ . "
								</td>
							</tr>
							<tr>
								<td style=\"text-align: left; width: 222px;\">
									Cel. <strong>958133948</strong>
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
									<span style=\"font-weight: bold; font-size: 20px;\">Nota de crédito</span>
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px; font-weight: bold;\">
									R.U.C.: 20455175781
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px;\">
									Nro. FF01-" . $venta['correlativo_nc'] . "
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
        <p style='margin-bottom: 0px;'><b>Motivo de emisión: </b>" . $venta['motivo'] . "</p>
        <p><b>Descripción del motivo: </b>" . $venta['motivo'] . "</p>
		<div style=\"border: solid 1px; border-radius: 10px; padding: 10px; width: 680px;\">
			<table style=\"width: 680px;\">
				<tr>
					<td style=\"width: 380px;\">
						<table style=\"width: 380px;\">
							<tr>
								<td style=\"padding: 5px;\">
									<strong>Razón Social: </strong>
                    				<span style='white-space: break-spaces;'>" . wordwrap($cliente['razon_social'], 30, '<br />', true) . "</span>
								</td>
							</tr>
							<tr>
								<td style=\"padding: 5px;\">
									<strong>Fecha Emisión: </strong>
                    				" . $venta['fecha_anulacion'] . "
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

        <div style=\"margin-top: 0.5rem; border: solid 1px; border-radius: 10px; padding: 10px; width: 680px;\">
            <h4 style=\"margin-top: 0px; margin-bottom: 0px;\">Datos del documento que modifica</h4>
			<table style=\"width: 680px;\">
				<tr>
					<td style=\"width: 680px;\">
						<table style=\"width: 680px;\">
							<tr>
								<td style=\"padding: 5px;\">
									<strong>Tipo de documento: </strong>
                    				Factura
								</td>
							</tr>
							<tr>
								<td style=\"padding: 5px;\">
									<strong>Número de documento: </strong>
                    				" . $venta['codigo_venta'] . "
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
        </div>
        " . $table . "

		<table style=\"margin-top: 20px; width: 680px;\">
			<tr>
				<td style=\"width: 515px;\">
					<strong>Son: " . ucwords($total_letras->letras) . "</strong>
				</td>
				<td style=\"width: 250px;\">
					<table class=\"table\"style=\"width: 250px;\">
		                <tr style=\"width: 200px;\">
		                    <td style=\"text-align: right;\"><strong>Subtotal: </strong></td>
		                    <td style=\"padding: 10px; border-bottom: solid 1px;\">S/ " . number_format($venta['subtotal_2'], 2) . "</td>
		                </tr>
		                <tr style=\"width: 200px;\">
		                    <td style=\"text-align: right;\"><strong>I.G.V.: </strong></td>
		                    <td style=\"padding: 10px; border-bottom: solid 1px;\">S/ " . number_format($venta['igv_2'], 2) . "</td>
		                </tr>
		                <tr style=\"width: 200px;\">
		                    <td style=\"text-align: right;\"><strong>Total: </strong></td>
		                    <td style=\"padding: 10px; border-bottom: solid 1px;\">S/ " . number_format($venta['total_2'], 2) . "</td>
		                </tr>
		            </table>
				</td>
			</tr>
		</table>
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
								Representación Impresa de la NOTA DE CRÉDITO.
							</td>
						</tr>
					</table>
				</td>
				<td style=\"width: 200px; \">
					<img src=\"" . $_SERVER['DOCUMENT_ROOT'] . "/core/app/view/img/qr_img.png\" align='left' border='0' style='width: 120px;' />
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