<?php
/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/
include('env.php');

$codigo_producto = date("Y");
$mes = $_GET['mes'];
$mes = $mes - 1;

if($mes < 0){
    //$q = $mbd->prepare("SELECT * FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id");
    $query = $mbd->prepare("SELECT c.* FROM cronograma_registro as c WHERE c.anio = :anio");
}else{
    /*$q = $mbd->prepare("SELECT * FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id AND mes = :mes");
    $q->bindParam(":mes", $mes);*/
    $query = $mbd->prepare("SELECT c.* FROM cronograma_registro as c, cronograma_registro_fecha as crf WHERE c.anio = :anio AND crf.id_cronograma_registro = c.id AND crf.mes = :mes");
    $query->bindParam(":mes", $mes);
}



$anio = $_GET['anio'];

$query->bindParam(":anio", $anio);
$query->execute();



$table = "";
$auxiliar = 0;

$meses = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic.'];
$meses_valor = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
$meses_aux = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];


while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
   
    $fechas = array();
    
                
	$table .= '<tr>
  			<td style="padding: 5px; width: 250px; font-size: 11px;"><strong>' . $res['curso'] . '</strong></td>
			  <td style="padding: 5px; width: 100px; font-size: 11px;">' . $res['areas'] . '</td>
			  <td style="padding: 5px; width: 100px; font-size: 11px;">' . $res['responsable'] . '</td>';

	$meses_ = explode(",", $res['mes']);
	$ejecutado = 0;
	$programado = 0;

	for ($i = 0; $i < 12; $i++) {
		$table .= '<td style="padding: 5px; text-align: center;">';
		
		if($mes < 0){
            $q = $mbd->prepare("SELECT * FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id");
        }else{
            $q = $mbd->prepare("SELECT * FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id AND mes = :mes");
            $q->bindParam(":mes", $mes);
        }
		
        //$q = $mbd->prepare("SELECT * FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id");
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
				$table .= "<strong style='border-bottom: solid 1px #666; margin-bottom: 0.5rem;'>".$r['dia']."<br></strong>";
			} else {
			}
        }
		
		$table .= '</td>';
	}
	$table .= '<td style="text-align: center;">'.number_format(($ejecutado*100)/$programado, 0).'%</td>';
	$auxiliar++;
	$table .= '</tr>';
}

$cabecera = '<tr>
	<th rowspan="2" style="text-align: center;">
		Ítem
	</th>
	<th style="text-align: center;" rowspan="2">
		AREAS
	</th>
	<th style="text-align: center;" rowspan="2">
		RESPONSABLE
	</th>';

for ($i = 0; $i < count($meses); $i++) {
	$cabecera .= '<th style="text-align: center;">'.$meses[$i].'</th>';
}
$cabecera .= '<th></th></tr><tr>';

for ($i = 0; $i < count($meses); $i++) {
	if ($meses_valor[$i] == 0) {
		$cabecera .= '<th style="text-align: center;" id="th_'.$i.'">0%</th>';
	}else{
		$cabecera .= '<th style="text-align: center;" id="th_'.$i.'">'.number_format(($meses_aux[$i]*100)/$meses_valor[$i], 0).'%</th>';
	}
}
$cabecera .= '<th width="5%" style="white-space: break-spaces; text-align: center;">CUMPLIMIENTO <br> ANUAL</th>';
$cabecera .= '</tr>';


$pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
	<page_header >
		
	</page_header>
	<page_footer>
	</page_footer>";

	$pipi .= '<table class="table" style="border: none; width: 100%; margin-bottom: 1rem; ">
		<tr>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%; text-align: center;">
				<img src="'.$_SERVER['DOCUMENT_ROOT'].'sistema/img/logo-3.png" style="width:200px;">
			</td>
			<td style="vertical-align: middle; padding: 0px; width: 33.3333333%;">
				<h5 style="font-weight: bold; text-align: center;">Programa de Capacitaciones Año: '.$anio.'</h5>
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
			</td>
		</tr>
	</table>';
	
	$pipi .= "<table cellpadding='4' cellspacing='0' border='1' style=\"width: 100%;\">
		".$cabecera."
		" . $table . "
	</table>
</page>";

header("Content-Disposition: attachment; filename=ficha_tecnica-" . $anio . ".pdf");

require __DIR__ . '/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

ob_start();

$html2pdf = new Html2Pdf('L', 'A4', 'es', 'false', 'UTF-8', array(0, 0, 0, 0));

$html2pdf->writeHTML($pipi);

$html2pdf->Output("Cronograma-capacitaciones-" . $anio . ".pdf", 'D');
//$html2pdf->Output("Cronograma-capacitaciones-" . $anio . ".pdf");
