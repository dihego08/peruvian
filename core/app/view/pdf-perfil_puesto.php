<?php
include("clsColaborador.php");
$colaborador = new clsColaborador;
$puesto = json_decode($colaborador->get_perfil_puesto($_GET['id']));

//print_r($puesto);

$pipi = "<page pageset='new' backleft='15mm' backtop='50mm' backbottom='10mm' backright='15mm' footer='page'>
		<page_header>
			<table id=\"encabezado\">
				<tr>
					<td style='width: 222px; border: solid 1px; text-align: center;'>
						<img src=\"" . $_SERVER['DOCUMENT_ROOT'] . "/img/logo-3.png\" align='left' border='0' style='width: 200px;' />
					</td>
					<td style='width: 222px; border: solid 1px; text-align: center;'>
						<h2>FICHA DE DESCRIPCIÓN DE PUESTOS</h2>
					</td>
					<td style='width: 222px; border: solid 1px; text-align: center; padding-left: 10px; padding-right: 10px;'>
						<table style='width: 100%; border: solid 1px;'>
							<tr>
								<td style='width: 50%;'>Código:</td>
								<td style='width: 50%;'>PD-FOR-039</td>
							</tr>
						</table>
						<table style='width: 100%; border: solid 1px;'>
							<tr>
								<td style='width: 50%;'>Versión:</td>
								<td style='width: 50%;'>001</td>
							</tr>
						</table>
						<table style='width: 100%; border: solid 1px;'>
							<tr>
								<td style='width: 50%;'>F. Aprob.:</td>
								<td style='width: 50%;'>25/07/2023</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</page_header>
		<page_footer>
			
		</page_footer>
		<div>
			
		</div>
		
		<table cellpadding='4' cellspacing='0' border='1' style=\"width: 100%;\">
			<tr>
				<td style='padding: 5px; background: #c0c0c0;'><strong>I. IDENTIFICACIÓN DEL PUESTO</strong></td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>TITULO DEL PUESTO: </strong>" . $puesto->puesto . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>AREA: </strong>" . $puesto->area . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>REPORTA A: </strong>" . $puesto->reporta_a . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>SUPERVISA A: </strong>" . $puesto->supervisa_a . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>INTERACTUA CON: </strong>" . $puesto->interactua_con . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>REEMPLAZADO POR: </strong>" . $puesto->reemplazado_por . "</td>
			</tr>
		</table>

		<table cellpadding='4' cellspacing='0' border='1' style=\"width: 100%; margin-top: 20px;\">
			<tr>
				<td style='padding: 5px; background: #c0c0c0;'><strong>II. CONTENIDO</strong></td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>OBJETIVO DEL PUESTO: </strong>" . $puesto->objetivo . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>FUNCIONES: </strong>" . $puesto->funciones . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>RESPONSABILIDADES: </strong>" . $puesto->responsabilidades . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>EQUIPO UTILIZADO: </strong>" . $puesto->equipo_utilizado . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>LUGAR DE TRABAJO: </strong>" . $puesto->lugar_trabajo . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>REQUERIMIENTOS FÍSICOS: </strong>" . $puesto->requerimientos_fisicos . "</td>
			</tr>
		</table>

		<table cellpadding='4' cellspacing='0' border='1' style=\"width: 100%; margin-top: 20px;\">
			<tr>
				<td style='padding: 5px; background: #c0c0c0; width: 595px;'><strong>III. CONOCIMIENTOS REQUERIDOS</strong></td>
			</tr>
			<tr>
				<td style='padding: 5px;'>
					<table style='width: 100%;'>
						<tr>
							<td style='width: 20%;'><strong>EDUCACIÓN BÁSICA: </strong></td>
							<td style='width: 20%;'><strong>Requerido </strong></td>
							<td style='width: 20%;'>" . $puesto->formacion_basica . "</td>
							<td style='width: 20%;'><strong>Óptimo: </strong></td>
							<td style='width: 20%;'>" . $puesto->formacion_basica_optima . "</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>CONOCIMIENTOS ESPECÍFICOS: </strong>" . $puesto->conocimientos_especificos . "</td>
			</tr>
			<tr>
				<td style='padding: 5px;'>
					<table style='width: 100%;'>
						<tr>
							<td style='width: 20%;'><strong>EXPERIENCIA O FORMACIÓN: </strong></td>
							<td style='width: 20%;'><strong>Requerido </strong></td>
							<td style='width: 20%;'>" . $puesto->experiencia_requerida . "</td>
							<td style='width: 20%;'><strong>Óptimo: </strong></td>
							<td style='width: 20%;'>" . $puesto->experiencia_requerida_optima . "</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style='padding: 5px;'><strong>IDIOMA: </strong>" . $puesto->idioma . "</td>
			</tr>
		</table>

		<table cellpadding='4' cellspacing='0' border='1' style=\"width: 100%; margin-top: 20px;\">
			<tr>
				<td style='padding: 5px; background: #c0c0c0;'><strong>IV. COMPETENCIA ESPECÍFICA DEL PUESTO</strong></td>
			</tr>
			<tr>
				<td style='padding: 5px;'>" . $puesto->competencia_especifica . "</td>
			</tr>
		</table>

		<table cellpadding='4' cellspacing='0' border='1' style=\"width: 100%; margin-top: 20px;\">
			<tr>
				<td style='padding: 5px; background: #c0c0c0;'><strong>V. COMPETENCIAS CARDINALES</strong></td>
			</tr>
			<tr>
				<td style='padding: 5px;'>" . $puesto->competencia_cardinal . "</td>
			</tr>
		</table>

		<table cellpadding='4' cellspacing='0' border='1' style=\"width: 100%; margin-top: 20px;\">
			<tr>
				<td style='padding: 5px; width: 182px;'><strong>Elaborado por: </strong>" . $puesto->elaborado_por . "</td>
				<td style='padding: 5px; width: 182px;'><strong>Aprobado por: </strong>" . $puesto->aprobado_por . "</td>
				<td style='padding: 5px; width: 182px;'><strong>Fecha de aprobación: </strong>" . $puesto->fecha_aprobacion . "</td>
			</tr>
		</table>
	</page>";

header("Content-Disposition: attachment; filename=ficha_tecnica-" . $_GET['id'] . ".pdf");

require __DIR__ . '/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

ob_start();

$html2pdf = new Html2Pdf('P', 'A4', 'es', 'false', 'UTF-8', array(10, 10, 10, 10));

$html2pdf->writeHTML($pipi);

// $html2pdf->Output("ficha_tecnica-".$_GET['id'].".pdf", 'D');  
$html2pdf->Output("ficha_tecnica-" . $_GET['id'] . ".pdf");
