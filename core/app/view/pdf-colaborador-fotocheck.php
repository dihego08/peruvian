<?php 
	include("clsColaborador.php");
	$colaborador = new clsColaborador;
	$puesto = json_decode($colaborador->editar($_GET['id_c']));

	$puesto_2 = json_decode($colaborador->editar_puesto($puesto->id_cargo));

	$pipi = "
	<page pageset='new' backleft='1mm' backright='1mm' >
		<page_header>
			
		</page_header>
		<page_footer>
			
		</page_footer>
		
		<div style=\"text-align: center; margin-top: 5px; margin-bottom: 0px;\">
			<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo.png\" align='left' border='0' style='width: 30px; margin-bottom: 0px;' />
		</div>
		<div style=\"text-align: center;\">
			<p style=\"font-size: 10px; font-weight: bold;  margin-top: 0px;\">PERUVIAN DRESS TPX S.A.C.</p>
		</div>
		
		<div style=\"text-align: center; margin-top: 5px;\">
			<img src=\"".$_SERVER['DOCUMENT_ROOT']."/core/app/view/img-colaboradores/".$puesto->foto."\" style=\"width: 55px; border-radius: 4px;\">
		</div>

		<table cellpadding='4' style=\"font-size: 8px; margin-top: 5px;\">
			<tr>
				<td style='width: 165px;'>
					<table>
						<tr>
							<td style=\"text-align: right; width: 75px;\">
								<strong>DNI: </strong>
							</td>
							<td style=\"width: 95px;\">	
								".$puesto->dni."
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style='width: 165px;'>
					<table>
						<tr>
							<td style=\"text-align: right; width: 75px;\">
								<strong>Nombres: </strong>
							</td>
							<td style=\"width: 95px;\">	
								".$puesto->nombres."
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style='width: 165px;'>
					<table>
						<tr>
							<td style=\"text-align: right; width: 75px;\">
								<strong>Apellido Paterno: </strong>
							</td>
							<td style=\"width: 95px;\">	
								".$puesto->apellido_paterno."
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style='width: 165px;'>
					<table>
						<tr>
							<td style=\"text-align: right; width: 75px;\">
								<strong>Apellido Materno: </strong>
							</td>
							<td style=\"width: 95px;\">	
								".$puesto->apellido_materno."
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style='width: 165px;'>
					<table>
						<tr>
							<td style=\"text-align: right; width: 75px;\">
								<strong>Puesto: </strong>
							</td>
							<td style=\"width: 95px;\">	
								".$puesto_2->puesto."
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style='width: 165px;'>
					<table>
						<tr>
							<td style=\"text-align: right; width: 75px;\">
								<strong>Linea: </strong>
							</td>
							<td style=\"width: 95px;\">	
								".$puesto->linea."
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</page>";

	header("Content-Disposition: attachment; filename=fotocheck-".$_GET['id_c'].".pdf");

	require __DIR__.'/html2pdf/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	ob_start();

	//$html2pdf = new Html2Pdf('L','A4','es','false','UTF-8',array(0,0,0,0));
	//$html2pdf = new HTML2PDF('L', 'A4', 'en');
	$html2pdf = new Html2Pdf('P', array(54, 70),'es','false','UTF-8',array(1,1,1,1));

	$html2pdf->writeHTML($pipi);

	//$html2pdf->output();
	//$html2pdf->Output("ficha_tecnica-".$_GET['id'].".pdf");  
	$html2pdf->Output("fotocheck-".$_GET['id_c'].".pdf", 'D');  

?>