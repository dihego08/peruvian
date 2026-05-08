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
include('clsCompra.php');
$compra = new clsCompra;
$oc = json_decode($compra->orden_compra($_GET['id']));

$total_letras = json_decode(file_get_contents('https://dbusinessaqp.com/numero_2_letras/conversor.php?total=' . $oc->total));

$query_proveedor = $mbd->prepare("SELECT * FROM person WHERE id = :id");
$query_proveedor->bindParam(":id", $oc->id_proveedor);
$query_proveedor->execute();

$proveedor = $query_proveedor->fetch(PDO::FETCH_ASSOC);

$detalle = json_decode($compra->lista_detalle($_GET['id']));

$table = "<table id=\"tabla_detalles\" style=\"border: solid 1px #eeeeee; text-align: center; margin-top: 10px; width: 780px; font-size: 11px;\"><tr style=\"border-bottom: solid 1px;\">
                        <th>ITEM</th>
                        <th>CANTIDAD</th>
                        <th>TIPO</th>
                        <th>DESCRIPCIÓN</th>
                        <th>PRECIO UNIT.</th>
                        <th>PRECIO TOTAL</th>
                    </tr>";
$aux = 0;
foreach ($detalle->Records as $key => $value) {
	$aux++;
	$tipo = "";
	if ($value->tipo == 1) {
		$tipo = "PRODUCTO";
	} else {
		$tipo = "SERVICIO";
	}
	$table .= '<tr>
        <td style="width: 30px; vertical-align: middle; padding: 5px;">' . $aux . '</td>
        <td style="width: 30px; vertical-align: middle; padding: 5px;">' . $value->cantidad . '</td>
        <td style="width: 32px; vertical-align: middle; padding: 5px;">' . $tipo . '</td>
        <td style="vertical-align: middle; width: 320px; padding: 5px;">' . $value->descripcion . '</td>	 	
        <td style="width: 82px; vertical-align: middle; padding: 5px;">S/ ' . number_format($value->precio_unitario, 2) . '</td>
        <td style="width: 82px; vertical-align: middle; padding: 5px;">S/ ' . number_format($value->precio_total, 2) . '</td>
    </tr>';
}

$table .= '<tr>
        <td style="vertical-align: middle; padding: 5px;"></td>
        <td style="vertical-align: middle; padding: 5px;"></td>
        <td style="vertical-align: middle; padding: 5px;"></td>
        <td style="vertical-align: middle; width: 316px; padding: 5px;"></td>	 	
        <td style="vertical-align: middle; padding: 5px; font-weight: bold;" scope="row">TOTAL</td>
        <td style="vertical-align: middle; padding: 5px; font-weight: bold;">S/ ' . number_format($oc->total, 2) . '</td>
    </tr>';

$table .= '</table>';

$pipi .= "<page pageset='new' backbottom='10mm' backright='10mm' backleft='10mm' footer='page'>
<page_header>
	
</page_header>
<page_footer>
</page_footer>" . '
<table class="table" style="border: none; width: 100%; margin-bottom: 1rem; ">
		<tr>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%; text-align: center;">
				<img src="' . $_SERVER['DOCUMENT_ROOT'] . '/img/logo-3.png" style="width:200px;">
			</td>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<h5 style="font-weight: bold; text-align: center;">ORDEN DE COMPRA ' . str_pad($oc->id, 3, "0", STR_PAD_LEFT) . '</h5>
			</td>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<table class="table table-xs table-bordered" border="1" style="width: 100%; font-size: 10px;">
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Código: </td>
									<td>PD-FOR-022</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Versión: </td>
									<td>001</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>F. Aprob.: </td>
									<td>07/04/2022</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>'
	. "
	<div style=\"padding: 10px; width: 680px;\">
		<table style=\"width: 680px; font-size: 11px;\">
				<tr>
					<td style=\"text-align: left;\">
						<strong>Razón Social: </strong> PERUVIAN DRESS TPX S.A.C.
					</td>
				</tr>
				<tr>
					<td style=\"text-align: left;\">
						<strong>Dirección: </strong> " . 'CAL.BELEN MZA. B LOTE. 8 JERUSALEN - MARIANO - MELGAR - AREQUIPA - AREQUIPA' . "
					</td>
				</tr>
				<tr>
					<td style=\"text-align: left;\">
						<strong>RUC.: </strong>20455175781
					</td>
				</tr>
				<tr>
					<td style=\"text-align: left;\">
						<strong>Celular.: </strong>958133948
					</td>
				</tr>
				<tr>
				    <td style=\"text-align: left;\">
				        <strong>Correo : </strong>omendoza@peruviandress.com
				    </td>
				</tr>
		</table>
    </div>
	<div style=\"border: solid 1px; border-radius: 10px; padding: 10px; width: 680px;\">
		<table style=\"width: 680px; font-size: 11px;\">
			<tr>
				<td style=\"width: 380px; white-space: break-spaces;\" >
					<table style=\"width: 380px;\">
						<tr>
							<td style=\"padding: 5px; white-space: break-spaces;\">
								<strong>Proveedor: </strong>
                				<span style='white-space: break-spaces;'>" . wordwrap($proveedor['name'], 30, '<br />', true) . "</span>
							</td>
						</tr>
						<tr>
							<td style=\"width: 285px; padding: 5px;\">
								<strong>Dirección: </strong>
                				" . $proveedor['address1'] . "
							</td>
						</tr>
						<tr>
							<td style=\"padding: 5px;\">
								<strong>Fecha: </strong>
                				" . $oc->fecha . "
							</td>
						</tr>
					</table>
				</td>
				<td style=\"width: 285px;\">
					<table style=\"width: 285px;\">
						<tr>
							<td style=\"width: 285px; padding: 5px;\">
								<strong>RUC. O ID: </strong>
                				" . $proveedor['no'] . "
							</td>
						</tr>
						<tr>
							<td style=\"width: 285px; padding: 5px;\">
								<strong>TEL/CEL: </strong>
                				" . $proveedor['phone1'] . "
							</td>
						</tr>
						<tr>
							<td style=\"width: 285px; padding: 5px;\">
								<strong>CONTACTO: </strong>
                				" . $proveedor['email1'] . "
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
    </div>
	<p style='margin-bottom: 0px; margin-top: 0.5rem; font-size: 12px;'>Mucho Agradecemos se sirvan atendernos el siguiente requerimiento:</p>
	" . $table .
	"<table style=\"margin-top: 20px; width: 680px; font-size: 11px;\">
			<tr>
				<td style=\"width: 565px;\">
					<strong>Son: " . ucwords($total_letras->letras) . "</strong>
		            <p class=\"mt-3\" style=\"border-bottom: solid 1px #333333;\">
		                <strong>Información Adicional</strong>
		            </p>
		            <table class=\"table\">
		                <tr>
		                    <td><strong>PRECIO EXPRESADO: </strong></td>
		                    <td><span style=\"border-bottom: solid 1px;\">SOLES</span></td>
		                </tr>
		                <tr>
		                    <td><strong>FORMA DE PAGO: </strong></td>
		                    <td><span style=\"border-bottom: solid 1px;\">" . $oc->forma_pago . "</span></td>
		                </tr>
		                <tr>
		                    <td><strong>FECHA DE ENTREGA: </strong></td>
		                    <td><span style=\"border-bottom: solid 1px;\">" . $oc->fecha_entrega . "</span></td>
		                </tr>
		                <tr>
		                    <td><strong>LUGAR DE ENTREGA: </strong></td>
		                    <td><span style=\"border-bottom: solid 1px;\">" . $oc->lugar_entrega . "</span></td>
		                </tr>
		                <tr>
		                    <td><strong>CUENTA CORRIENTE: </strong></td>
		                    <td>" . $proveedor['nro_cuenta'] . "</td>
		                </tr>
		            </table>
				</td>
			</tr>
		</table>
		<table style=\"margin-top: 20px; width: 680px; font-size: 11px;\">
			<tr>
				<td style='width: 100px;'>
				</td>
				<td style='width: 240px;'>
					<table >
						<tr>
							<td colspan=\"2\" style=\"text-align: center; height: 90px; vertical-align: bottom; font-weight: bold; border: solid 1px;\">Aprobado por:</td>
						</tr>
						<tr>
							<td>Nombre</td>
							<td>____________________</td>
						</tr>
						<tr>
							<td>Fecha</td>
							<td>____________________</td>
						</tr>
					</table>
				</td>
				<td style='width: 100px;'>
				</td>
				<td style='width: 240px;'>
					<table >
						<tr>
							<td colspan=\"2\" style=\"text-align: center; height: 90px; vertical-align: bottom; font-weight: bold; border: solid 1px;\">Recibi Conforme:</td>
						</tr>
						<tr>
							<td>Nombre</td>
							<td>____________________</td>
						</tr>
						<tr>
							<td>Fecha</td>
							<td>____________________</td>
						</tr>
					</table>
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
