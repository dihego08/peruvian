<?php
include('env.php');
/*if(isset($_GET['linea']) || $_GET['linea'] === 0){
	echo "Si esta hcho";
}*/
if ((isset($_GET['mes']) && $_GET['mes'] > 0) && (isset($_GET['linea']) || $_GET['linea'] === 0)) {
	//echo "if 1";
	$query = $mbd->prepare("SELECT c.*, a.area FROM colaboradores as c, puestos as p, areas as a WHERE c.id_cargo = p.id AND p.id_area = a.id AND MONTH(c.fecha_nacimiento) = " . $_GET['mes'] . " AND c.linea = " . $_GET['linea'] . " AND c.estado = 1 AND c.linea != 10 ORDER BY MONTH(c.fecha_nacimiento), DAY(c.fecha_nacimiento) ASC");
} else {
	//echo "if 2";
	if (isset($_GET['linea']) && $_GET['mes'] == 0) {
		//echo "if 3";
		$query = $mbd->prepare("SELECT c.*, a.area FROM colaboradores as c, puestos as p, areas as a WHERE c.id_cargo = p.id AND p.id_area = a.id AND c.estado = 1 AND c.linea = " . $_GET['linea'] . " ORDER BY MONTH(c.fecha_nacimiento), DAY(c.fecha_nacimiento) ASC");
	} elseif (isset($_GET['mes']) && $_GET['mes'] > 0) {
		//echo "if 4";
		$query = $mbd->prepare("SELECT c.*, a.area FROM colaboradores as c, puestos as p, areas as a WHERE c.id_cargo = p.id AND p.id_area = a.id AND c.estado = 1 AND MONTH(c.fecha_nacimiento) = " . $_GET['mes'] . " AND c.linea != 10 ORDER BY MONTH(c.fecha_nacimiento), DAY(c.fecha_nacimiento) ASC");
	} else {
		//echo "if 5";
		$query = $mbd->prepare("SELECT c.*, a.area FROM colaboradores as c, puestos as p, areas as a WHERE c.id_cargo = p.id AND p.id_area = a.id AND c.estado = 1 AND c.linea != 10 ORDER BY MONTH(c.fecha_nacimiento), DAY(c.fecha_nacimiento) ASC");
	}
}

$query->execute();

$aux = 1;
while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
	$tabla_mantenimientos .= '<tr>
	        <td>' . $aux . '</td>
			<td>' . $res['area'] . '</td>
			<td>' . $res['nombres'] . " " . $res['apellido_paterno'] . " " . $res['apellido_materno'] . '</td>
			<td>' . date("d-m", strtotime($res['fecha_nacimiento'])) . '</td>
			<td>Línea ' . $res['linea'] . '</td>
		</tr>';
	$aux++;
}


$pipi = '<page pageset=\'new\' backleft=\'15mm\' backtop=\'10mm\' backright=\'15mm\' footer=\'page\'>
	<page_header >
		
	</page_header>
	<page_footer>
	</page_footer>
	<div style=\'width: 100%; text-align: center;\'>
		<img src="' . $_SERVER['DOCUMENT_ROOT'] . '/img/logo-2.png" align=\'left\' border=\'0\' style=\'width: 150px; margin-left: auto; margin-right: auto;\' />
	</div>
	<p style="text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;"> Cumpleaños Colaboradores</p>
	<style>
	    td, th{
	        padding: 10px;
	    }
	</style>
	
	<table id="tabla_mtto" class="table  table-bordered  table-hover" border="1" style="width: 100%; font-size: 12px;">
		<tr>
		    <th>#</th>
			<th>Área</th>
			<th>Colaborador</th>
			<th>Cumpleaños</th>
			<th>Línea</th>
		</tr>
		' . $tabla_mantenimientos . '
	</table>
</page>';

header("Content-Disposition: attachment; filename=Datos-Mantenimiento.pdf");

require __DIR__ . '/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

ob_start();

$html2pdf = new Html2Pdf('P', 'A4', 'es', 'false', 'UTF-8', array(0, 0, 0, 0));

$html2pdf->writeHTML($pipi);

$html2pdf->Output("Datos-Cumpleaños.pdf", 'D');
//$html2pdf->Output("Datos-Cumpleaños.pdf");
