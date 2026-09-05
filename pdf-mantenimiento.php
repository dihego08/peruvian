<?php 
	include('env.php');

	include('clsMantenimiento.php');
	$mtto = new clsMantenimiento;

	$los_mantenimientos = json_decode($mtto->lista_mttos($_GET['mid']));

	$tabla_mantenimientos = "";
$tt = 0;
	foreach($los_mantenimientos->Records as $key => $value){
		$tabla_mantenimientos .= '<tr>
			<td>' . $value->maq_mtto_tipo . '</td>
			<td>' . $value->maq_mtto_fecha . '</td>
			<td>' . $value->maq_mtto_reponsable . '</td>
			<td>S/ ' . $value->costo . '</td>
			<td>' . $value->maq_mtto_observacion . '</td>
		</tr>';
		$tt += $value->costo;
	}
	$tabla_mantenimientos .= '<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>S/ ' . number_format($tt, 2) . '</td>
			<td></td>
		</tr>';
	
	$data_maquina = $mbd->prepare("SELECT * FROM tbl_maquina WHERE maquina_id = ".$_GET['mid']);
	$data_maquina->execute();
	
	$data_maquina_ = $data_maquina->fetch(PDO::FETCH_ASSOC);
	

	$pipi = '<page pageset=\'new\' backleft=\'15mm\' backtop=\'10mm\' backright=\'15mm\' footer=\'page\'>
	<page_header >
		
	</page_header>
	<page_footer>
	</page_footer>
	<div style=\'width: 100%; text-align: center;\'>
		<img src="'.$_SERVER['DOCUMENT_ROOT'].'/img/logo-2.png" align=\'left\' border=\'0\' style=\'width: 150px; margin-left: auto; margin-right: auto;\' />
	</div>
	<p style="text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;"> FICHA DE MAQUINA </p>
	<style>
	    td, th{
	        padding: 10px;
	    }
	</style>
	
	<table class="table  table-bordered table-hover" style="width: 100%;" border="1">
		<tr style="width: 100%;">
			<td style="width: 50%;">
				<table border="1">
					<tr>
						<td style="font-weight: bold; width: 50%;">Codigo</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_tipo'].'-'.$data_maquina_['maquina_codigo'].'</td>
					</tr>
					<tr>
						<td style="font-weight: bold; width: 50%;">Descripcion</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_descripcion'].'</td>
					</tr>
					<tr>
						<td style="font-weight: bold; width: 50%;">Marca de la maquina</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_marca'].'</td>
					</tr>
					<tr>
						<td style="font-weight: bold; width: 50%;">Modelo</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_modelo'].'</td>
					</tr>
					<tr>
						<td style="font-weight: bold; width: 50%;">Nro de Serie</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_serie'].'</td>
					</tr>
					<tr>
						<td style="font-weight: bold; width: 50%;">Marca de Motor</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_marca_motor'].'</td>
					</tr>
					<tr>
						<td style="font-weight: bold; width: 50%;">Nro Serie de Motor</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_serie_motor'].'</td>
					</tr>
					<tr>
						<td style="font-weight: bold; width: 50%;">Medidas para Espacio</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_exigencias'].'</td>
					</tr>
					<tr>
						<td style="font-weight: bold; width: 50%;">Voltaje</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_voltaje'].'</td>
					</tr>
					<tr>
						<td style="font-weight: bold; width: 50%;">Tipo Corriente</td>
						<td style="width: 50%;">'.$data_maquina_['maquina_tipo_corriente'].'</td>
					</tr>
				</table>
			</td>
			<td style="width: 50%;">
				<img src="'.$_SERVER['DOCUMENT_ROOT'].'/storage/maquinas/'.$data_maquina_['maquina_imagen'].'" style="width:294px;" />
			</td>
		</tr>
	</table>
	<p style="text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;">Lista de Mantenimientos</p>
	<table id="tabla_mtto" class="table  table-bordered  table-hover" border="1" style="width: 100%;">
		<tr>
			<th style="width: 20%;">Mantenimiento Realizado</th>
			<th style="width: 10%;">Fecha</th>
			<th style="width: 20%;">Responsable</th>
			<th style="width: 10%;">Costo</th>
			<th style="width: 20%;">Observaciones</th>
		</tr>
		'.$tabla_mantenimientos.'
	</table>
</page>';

	header("Content-Disposition: attachment; filename=Datos-Mantenimiento.pdf");

	require __DIR__.'/html2pdf/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	ob_start();

	$html2pdf = new Html2Pdf('P','A4','es','false','UTF-8',array(0,0,0,0));

	$html2pdf->writeHTML($pipi);

	$html2pdf->Output("Datos-Mantenimiento.pdf", 'D');  
?>