<?php 
	include('env.php');
	include("clsColaborador.php");
	$colaborador = new clsColaborador;
	$cronograma = json_decode($colaborador->get_actas_reunion());

	$tabla = "";
	foreach ($cronograma as $key => $value) {
		$tabla .= '<tr>
			<td>'.$value->fecha_registro.'</td>
			<td>'.$value->orden_dia.'</td>
			<td>'.$value->duracion.'</td>
			<td>'.$value->convoca.'</td>
			<td>'.$value->acuerdos.'</td>
		</tr>';
	}

	$pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
	<page_header >
		
	</page_header>
	<page_footer>
	</page_footer>
	<div style='width: 100%; text-align: center;'>
		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo.png\" align='left' border='0' style='width: 150px; margin-left: auto; margin-right: auto;' />
	</div>
	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Registro de Actas de Reunion</p>
	<style>
	    td, th{
	        padding: 5px;
	    }
	    td{
	    	width: 20%;
	    }
	</style>
	<table style='width: 100%;' border=1>
	    <tr style='width: 100%;'>
	        <th>Fecha</th>
			<th>Orden del Dia</th>
			<th>Horas/Reunion</th>
			<th>Convoca</th>
			<th>Acuerdos</th>
	    </tr>
	    ".$tabla."
	</table>
</page>";

	//echo $pipi;

	header("Content-Disposition: attachment; filename=actas-reunion.pdf");

	require __DIR__.'/html2pdf/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	ob_start();

	$html2pdf = new Html2Pdf('P','A4','es','false','UTF-8',array(0,0,0,0));

	$html2pdf->writeHTML($pipi);

	$html2pdf->Output("actas-reunion.pdf", 'D');  
?>