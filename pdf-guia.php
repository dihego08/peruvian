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

$id = $_GET['id'];

$query = $mbd->prepare("SELECT g.*, m.motivo_traslado as desc_motivo_traslado FROM guia_cabecera AS g join motivos_traslado AS m ON m.codigo = g.motivo_traslado WHERE g.id = :id");
$query->bindParam(":id", $id);
$query->execute();

$guia = $query->fetch(PDO::FETCH_ASSOC);

$query_conductor = $mbd->prepare("SELECT * FROM conductores WHERE ruc = :id_person");
$query_conductor->bindParam(":id_person", $guia['ruc_conductor']);
$query_conductor->execute();

$conductor = $query_conductor->fetch(PDO::FETCH_ASSOC);
$data_transportista = '';
if (empty($guia['ruc_transportista']) || is_null($guia['ruc_transportista'])) {
} else {
    $query_transportista = $mbd->prepare("SELECT * FROM transportistas WHERE ruc = :id_person");
    $query_transportista->bindParam(":id_person", $guia['ruc_transportista']);
    $query_transportista->execute();

    $transportista = $query_transportista->fetch(PDO::FETCH_ASSOC);

    $data_transportista = "<tr>
					<td style=\"width: 680px;\" >
						<table style=\"width: 680px;\">
							<tr>
								<td style=\"padding: 5px; width: 70%;\">
									<strong>Razón Social: </strong>
                    				<span>" . $transportista['razon_social'] . "</span>
								</td>
                                <td style=\"padding: 5px; width: 30%;\">
                                    <strong>RUC: </strong>
                                    " . $transportista['ruc'] . "
								</td>
							</tr>
						</table>
					</td>
				</tr>";
}

$query_person = $mbd->prepare("SELECT * FROM person WHERE no = :id_person");
$query_person->bindParam(":id_person", $guia['ruc_destinatario']);
$query_person->execute();

$person = $query_person->fetch(PDO::FETCH_ASSOC);

$cliente['direccion'] = $person['address1'];
$cliente['razon_social'] = $person['name'];
$cliente['ruc'] = $person['no'];

//$query_2 = $mbd->prepare("SELECT p.name, vd.*, p.code FROM guia_detalle as vd, product as p WHERE p.id = vd.id_producto AND vd.id_guia = :codigo");
$query_2 = $mbd->prepare("SELECT cs.unidad as unidad_descripcion, p.name, vd.*, p.code FROM guia_detalle as vd join product as p on p.id = vd.id_producto left join codigos_sunat cs on cs.codigo = vd.unidad WHERE vd.id_guia = :codigo");
$query_2->bindParam(':codigo', $id);
$query_2->execute();
$table = "<table id=\"tabla_detalles\" style=\"border: solid 1px #eeeeee; text-align: center; margin-top: 10px; width: 780px; font-size: 10px;\"><tr style=\"border-bottom: solid 1px;\">
	<th>Cantidad</th>
	<th>Pedido</th>
	<th>Modelo</th>
	<th>Descripción</th>
	<th>Peso (Kg)</th>
</tr>";
$total_peso_items = 0;
$total_cantidades_items = 0;
while ($res = $query_2->fetch(PDO::FETCH_ASSOC)) {
	$desc = "";
	if (empty($res['descripcion_producto']) || is_null($res['descripcion_producto'])) {
		$desc = $res['name'];
	} else {
		$desc = $res['descripcion_producto'];
	}
	$peso_t = 0;
	if ($res['t_bruto'] == 0) {
		$peso_t = $res['t_neto'];
	} else {
		$peso_t = $res['t_bruto'];
	}
	$table .= '<tr>
		<td style="width: 58px; padding: 5px;"><p>' . $res['cantidad'] . ' ' . $res['unidad_descripcion'] . '</p></td>
		<td style="width: 58px; padding: 5px;">' . $res['pedido'] . '</td>
		<td style="width: 116px; padding: 5px;">' . $res['code'] . '</td>
		<td style="width: 252px; padding: 5px;">' . $desc . '</td>	 	
		<td style="width: 100px; padding: 5px;">' . $peso_t . '</td>
	</tr>';
	$total_peso_items += $peso_t;
	$total_cantidades_items += $res['cantidad'];
}
$table .= '<tr>
		<th style="width: 58px; padding: 5px;"><p>Total Items: ' . $total_cantidades_items . '</p></th>
		<th style="width: 58px; padding: 5px;"></th>
		<th style="width: 116px; padding: 5px;"></th>
		<th style="width: 252px; padding: 5px;"></th>	 	
		<th style="width: 100px; padding: 5px;">Total Peso: ' . round($total_peso_items, 2) . '</th>
	</tr>';
$desc = "";
$table .= '</table>';


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
$contenido = "https://peruvian.peruviandress.com/core/app/view/pdf-guia.php?id=" . $_GET['id']; //Texto

//Enviamos los parametros a la Función para generar código QR 
QRcode::png($contenido, $filename, $level, $tamaño, $framSize);

//Mostramos la imagen generada
//echo '<img src="'.$dir.basename($filename).'" /><hr/>';

$tabla_credito = "";

if ($guia['modalidad_trasnporte'] == '01' || $guia['modalidad_trasnporte'] == 1) {
    $modalidad_trasnporte = 'Transporte público';
} else {
    $modalidad_trasnporte = 'Transporte privado';
}
if ($guia['motivo_traslado'] == 13) {
	$motivo_traslado = "OTROS " . $guia['descripcion_motivo'];
} else {
	$motivo_traslado = $guia['desc_motivo_traslado'];
}
//$guia['total_bruto'] || $guia['total_neto']
if (is_null($guia['total_bruto']) || empty($guia['total_bruto']) || $guia['total_bruto'] == 0) {
	$peso_total = $guia['total_neto'];
} else {
	$peso_total = $guia['total_bruto'];
}
$pipi = "
	<page pageset='new' backbottom='10mm' backright='10mm' backleft='10mm' footer='page'>
		<page_header>
			
		</page_header>
		<page_footer>
		</page_footer>

			<table id=\"encabezado\" style=\"margin-bottom: 10px; font-size: 11px;\">
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
						</table>
					</td>
					<td style='width: 44px; '>
					</td>
					<td style='padding: 5px; border-radius: 4px; width: 222px; text-align: center;'>
						<table style=\"width: 222px;\">
							<tr>
								<td style=\"text-align: center; width: 222px;\">
									<span style=\"font-weight: bold; font-size: 20px;\">Guía de Remisión Electrónica</span>
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px; font-weight: bold;\">
									R.U.C.: 20455175781
								</td>
							</tr>
							<tr>
								<td style=\"text-align: center; width: 222px;\">
									Nro. " . $guia['num_guia'] . "
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<div style=\"border: solid 1px; border-radius: 10px; padding: 10px; width: 680px; margin-bottom: 10px;\">
			<h5 style='margin-bottom: 0px !important; margin-top: 0px !important;'>Datos del Traslado</h5>
			<table style=\"width: 680px; font-size: 11px;\">
				<tr>
					<td style=\"width: 680px;\" >
						<table style=\"width: 680px;\">
							<tr>
								<td style=\"padding: 5px; width: 33%;\">
									<strong>Fecha Emisión: </strong>
                    				<span>" . $guia['fecha_emision'] . "</span>
								</td>
                                <td style=\"padding: 5px; width: 33%;\">
                                    <strong>Fecha Inicio Traslado: </strong>
                                    " . $guia['fecha_traslado'] . "
								</td>
                                <td style=\"padding: 5px; width: 33%;\">
                                    <strong>Motivo de Traslado: </strong>
                                    " . $motivo_traslado . "
								</td>
							</tr>
						</table>
					</td>
				</tr>
                <tr>
                    <td>
                        <table style=\"width: 680px;\">
							<tr>
								<td style=\"padding: 5px; width: 50%;\">
									<strong>Modalidad de Transporte: </strong>
                    				<span>" . $modalidad_trasnporte . "</span>
								</td>
                                <td style=\"padding: 5px; width: 50%;\">
                                    <strong>Peso Bruto Total (KG): </strong>
                                    " . $peso_total . "
								</td>
							</tr>
						</table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style=\"width: 680px;\">
							<tr>
								<td style=\"padding: 5px; width: 100%\">
									<strong>Observaciones: </strong>
                    				<span>" . $guia['comentario'] . "</span>
								</td>
							</tr>
						</table>
                    </td>
                </tr>
			</table>
        </div>


        <div style=\"border: solid 1px; border-radius: 10px; padding: 10px; width: 680px; margin-bottom: 10px;\">
			<h5 style='margin-bottom: 0px !important; margin-top: 0px !important;'>Datos del Destinatario</h5>
			<table style=\"width: 680px; font-size: 11px;\">
				<tr>
					<td style=\"width: 680px;\" >
						<table style=\"width: 680px;\">
							<tr>
								<td style=\"padding: 5px; width: 70%;\">
									<strong>Razón Social: </strong>
                    				<span>" . $cliente['razon_social'] . "</span>
								</td>
                                <td style=\"padding: 5px; width: 30%;\">
                                    <strong>RUC: </strong>
                                    " . $cliente['ruc'] . "
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
        </div>

        <div style=\"border: solid 1px; border-radius: 10px; padding: 10px; width: 680px; margin-bottom: 10px;\">
			<h5 style='margin-bottom: 0px !important; margin-top: 0px !important;'>Datos del punto de partida y llegada</h5>
			<table style=\"width: 680px; font-size: 11px;\">
				<tr>
					<td style=\"width: 680px; \" >
						<table style=\"width: 680px;\">
							<tr>
								<td style=\"padding: 5px;  width: 100%\">
									<strong>Punto de Partida: </strong>
                    				<span>" . $guia['origen'] . "</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
                <tr>
					<td style=\"width: 680px; \" >
						<table style=\"width: 680px;\">
							<tr>
								<td style=\"padding: 5px;  width: 100%\">
									<strong>Punto de Llegada: </strong>
                    				<span>" . $guia['destino'] . "</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
        </div>

        <div style=\"border: solid 1px; border-radius: 10px; padding: 10px; width: 680px; margin-bottom: 10px;\">
			<h5 style='margin-bottom: 0px !important; margin-top: 0px !important;'>Datos del Transporte</h5>
			<table style=\"width: 680px; font-size: 11px;\">
            " . $data_transportista . "
				<tr>
					<td style=\"width: 680px; white-space: break-spaces;\" >
						<table style=\"width: 680px;\">
							<tr>
								<td style=\"padding: 5px;\">
									<strong>Nro. Placa: </strong>
								</td>
                                <td style=\"padding: 5px;\">
                    				<span>" . $guia['placa'] . "</span>
								</td>
                                <td style=\"padding: 5px;\">
                                    <strong>Documento Conductor: </strong>
								</td>
                                <td style=\"padding: 5px;\">
                                    " . $guia['ruc_conductor'] . "
								</td>
                                <td style=\"padding: 5px;\">
                                    <strong>Nombres Conductor: </strong>
								</td>
                                <td style=\"padding: 5px;\">
                                    " . $conductor['razon_social'] . "
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
        </div>


        " . $table . "


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
								Representación Impresa de la GUÍA DE REMISIÓN ELECTRÓNICA.
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
//echo $pipi;
//return;    
require __DIR__ . '/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

ob_start();

$html2pdf = new Html2Pdf('P', 'A4', 'es', 'false', 'UTF-8', array(0, 10, 10, 0));

$html2pdf->writeHTML($pipi);

$html2pdf->Output("Guia-" . $guia['num_guia'] . ".pdf");
