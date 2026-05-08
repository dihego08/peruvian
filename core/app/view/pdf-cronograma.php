<?php
/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/
include('env.php');
$f_desde = $_GET['fecha_desde'];
$f_hasta = $_GET['fecha_hasta'];
$tipo = $_GET['tipo'];

$codigo_producto = date("Y");
$mes = $_GET['mes'];
$mes = $mes - 1;
if ($tipo == 0) {
	$query = $mbd->prepare("SELECT distinct cr.*, t.tipo_cronograma from capacitacion_registro as cr inner join capacitacion_registro_fecha crf on cr.id = crf.id_capacitacion_registro left join tipo_cronogramas as t on cr.id_tipo = t.id where crf.dia >= " . date("d", strtotime($f_desde)) . " and crf.dia <= " . date("d", strtotime($f_hasta)) . " and (crf.mes + 1) >= " . date("m", strtotime($f_desde)) . " and (crf.mes+1) <= " . date("m", strtotime($f_hasta)) . " and cr.anio = " . date("Y", strtotime($f_desde)) . " ORDER BY crf.dia ASC;");
	$query->execute();
} else {
	$query = $mbd->prepare("SELECT distinct cr.*, t.tipo_cronograma from capacitacion_registro as cr inner join capacitacion_registro_fecha crf on cr.id = crf.id_capacitacion_registro left join tipo_cronogramas as t on cr.id_tipo = t.id where crf.dia >= " . date("d", strtotime($f_desde)) . " and crf.dia <= " . date("d", strtotime($f_hasta)) . " and (crf.mes + 1) >= " . date("m", strtotime($f_desde)) . " and (crf.mes+1) <= " . date("m", strtotime($f_hasta)) . " and cr.anio = " . date("Y", strtotime($f_desde)) . " AND cr.id_tipo = " . $tipo . " ORDER BY crf.dia ASC;");
	$query->execute();
}

$anio = $_GET['anio'];

$query->execute();

$table = "";
$auxiliar = 0;

$meses = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic.'];
$meses_valor = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
$meses_aux = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];


while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
	$fechas = array();
	$table .= '<tr>
  			<td style="padding: 5px; width: 11%; font-size: 11px;"><strong>' . $res['curso'] . '</strong></td>
			  <td style="padding: 5px; width: 11%; font-size: 11px;">' . $res['areas'] . '</td>
			  <td style="padding: 5px; width: 11%; font-size: 11px;">' . $res['responsable'] . '</td>
			  <td style="padding: 5px; width: 11%; font-size: 11px;">' . $res['tipo_cronograma'] . '</td>
			  <td style="padding: 5px; width: 11%; font-size: 9px; ">' . $res['eficacia'] . '</td>';

	$meses_ = explode(",", $res['mes']);
	$ejecutado = 0;
	$programado = 0;

	for ($i = date("m", strtotime($f_desde)) - 1; $i <= date("m", strtotime($f_hasta)) - 1; $i++) {
		$table .= '<td style="padding: 5px; text-align: center;">';

		$q = $mbd->prepare("SELECT * FROM capacitacion_registro_fecha WHERE id_capacitacion_registro = :id AND (mes + 1) >= " . date("m", strtotime($f_desde)) . " and (mes+1) <= " . date("m", strtotime($f_hasta)) . " ORDER BY dia ASC");
		$q->bindParam(":id", $res['id']);
		$q->execute();

		while ($r = $q->fetch(PDO::FETCH_ASSOC)) {

			$meses_valor[$r['mes']] = $meses_valor[$r['mes']] + 1;

			$programado += 1;
			if ($r['estado'] == 1) {
				$ejecutado += 1;

				$meses_aux[$r['mes']] = $meses_aux[$r['mes']] + 1;
			}
			if ($i == $r['mes']) {
				$table .= "<strong style='border-bottom: solid 1px #666; margin-bottom: 0.5rem;'>" . $r['dia'] . "<br></strong>";
			} else {
			}
		}

		$table .= '</td>';
	}
	$table .= '<td style="text-align: center;">' . number_format(($ejecutado * 100) / $programado, 0) . '%</td>';
	$auxiliar++;
	$table .= '</tr>';
}

$cabecera = '<tr>
	<th rowspan="2" style="text-align: center; width: 7%;">
		CURSO
	</th>
	<th style="text-align: center; width: 7%;" rowspan="2">
		AREAS
	</th>
	<th style="text-align: center; width: 7%;" rowspan="2">
		RESPONSABLE
	</th>
	<th style="text-align: center; width: 7%;" rowspan="2">
		T. PROGRAMA
	</th>
	<th style="text-align: center; width: 7%;" rowspan="2">
		VER. EFICACIA
	</th>';
// echo date("m", strtotime($f_desde))." ".date("m", strtotime($f_hasta));
for ($i = date("m", strtotime($f_desde)) - 1; $i <= date("m", strtotime($f_hasta)) - 1; $i++) {
	$cabecera .= '<th style="text-align: center;">' . $meses[$i] . '</th>';
}
$cabecera .= '<th></th></tr><tr>';

for ($i = date("m", strtotime($f_desde)) - 1; $i <= date("m", strtotime($f_hasta)) - 1; $i++) {
	if ($meses_valor[$i] == 0) {
		$cabecera .= '<th style="text-align: center;" id="th_' . $i . '">0%</th>';
	} else {
		$cabecera .= '<th style="text-align: center;" id="th_' . $i . '">' . number_format(($meses_aux[$i] * 100) / $meses_valor[$i], 0) . '%</th>';
	}
}
$cabecera .= '<th width="5%" style="white-space: break-spaces; text-align: center;">CUMPLIMIENTO <br> ANUAL</th>';
$cabecera .= '</tr>';


$pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
	<page_header >
		
	</page_header>
	<page_footer>
	</page_footer>";
$header = '';
switch ($tipo) {
	case 1:
		$header .= '<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<h5 style="font-weight: bold; text-align: center;">Programa de Capacitaciones Año: ' . date("Y", strtotime($f_desde)) . '</h5>
			</td>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<table class="table table-xs table-bordered" border="1" style="width: 100%;">
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Código: </td>
									<td>PD-FOR-004</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Versión: </td>
									<td>002</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>F. Aprob.: </td>
									<td>11/04/2022</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>';
		break;
	case 2:
		$header .= '<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<h5 style="font-weight: bold; text-align: center;">PROGRAMA ANUAL DE MANTENIMIENTO</h5>
			</td>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<table class="table table-xs table-bordered" border="1" style="width: 100%;">
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Código: </td>
									<td>PD-FOR-024</td>
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
									<td>11/04/2022</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>';
		break;
	case 3:
		$header .= '<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<h5 style="font-weight: bold; text-align: center;">PROGRAMA ANUAL DE CALIBRACIONES Año: ' . date("Y", strtotime($f_desde)) . '</h5>
			</td>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<table class="table table-xs table-bordered" border="1" style="width: 100%;">
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Código: </td>
									<td>PD-FOR-025</td>
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
									<td>11/04/2022</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>';
		break;
	case 4:
		$header .= '<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<h5 style="font-weight: bold; text-align: center;">PROGRAMA DE AUDITORAS INTERNAS Año: ' . date("Y", strtotime($f_desde)) . '</h5>
			</td>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<table class="table table-xs table-bordered" border="1" style="width: 100%;">
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Código: </td>
									<td>PD-FOR-017</td>
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
									<td>01/12/2021</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>';
		break;
	case 5:
		$header .= '<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<h5 style="font-weight: bold; text-align: center;">PROGRAMA ANUAL DE SALUD OCUPACIONAL Año: ' . date("Y", strtotime($f_desde)) . '</h5>
			</td>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<table class="table table-xs table-bordered" border="1" style="width: 100%;">
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Código: </td>
									<td>PD-FOR-038</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Versión: </td>
									<td>002</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>F. Aprob.: </td>
									<td>29/08/2022</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>';
		break;
	case 6:
		$header .= '<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<h5 style="font-weight: bold; text-align: center;">PROGRAMA VARIOS Año: ' . date("Y", strtotime($f_desde)) . '</h5>
			</td>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<table class="table table-xs table-bordered" border="1" style="width: 100%;">
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Código: </td>
									<td>PD-FOR-004</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>Versión: </td>
									<td>002</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding: 5px; width: 100%;">
							<table>
								<tr>
									<td>F. Aprob.: </td>
									<td>11/04/2022</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>';
		break;

	default:
		$header .= '<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
			<h5 style="font-weight: bold; text-align: center;">PROGRAMA GENERAL</h5>
		</td>
		<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
		</td>';
		break;
}
$pipi .= '<table class="table" style="border: none; width: 100%; margin-bottom: 1rem; ">
		<tr>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%; text-align: center;">
				<img src="' . $_SERVER['DOCUMENT_ROOT'] . '/img/logo-3.png" style="width:200px;">
			</td>
			' . $header . '
		</tr>
	</table>';

$pipi .= "<table cellpadding='4' cellspacing='0' border='1' style=\"width: 100%;\">
		" . $cabecera . "
		" . $table . "
	</table>
</page>";
// echo $pipi;
header("Content-Disposition: attachment; filename=ficha_tecnica-" . date("Y", strtotime($f_desde)) . ".pdf");

require __DIR__ . '/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

ob_start();

$html2pdf = new Html2Pdf('L', 'A4', 'es', 'false', 'UTF-8', array(0, 0, 0, 0));

$html2pdf->writeHTML($pipi);

$html2pdf->Output("Cronograma-capacitaciones-" . date("Y", strtotime($f_desde)) . ".pdf", 'D');
// $html2pdf->Output("Cronograma-capacitaciones-" . date("Y", strtotime($f_desde)) . ".pdf");
