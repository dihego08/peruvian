<?php 
	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/
	include('env.php');
	$codigo_producto = $_GET['num_modelo'];
	
	$query = $mbd->prepare("SELECT * FROM complementos WHERE code_producto = :code_producto");
  	$query->bindParam(":code_producto", $codigo_producto);
  	$query->execute();


  	$query_1 = $mbd->prepare("SELECT * FROM medidas WHERE code_producto = :code_producto");
  	$query_1->bindParam(":code_producto", $codigo_producto);
  	$query_1->execute();




  	/*$ma = $mbd->prepare("SELECT * FROM maquinas WHERE code_producto = :code_producto");
  	$ma->bindParam(":code_producto", $codigo_producto);
  	$ma->execute();

  	$maquinas = array();

  	while ($m = $ma->fetch(PDO::FETCH_ASSOC)) {
  		$maquinas[] = $m;
  	}

  	$ins = $mbd->prepare("SELECT * FROM etapas");
  	$ins->execute();

  	$ins_ = array();

  	while ($res = $ins->fetch(PDO::FETCH_ASSOC)) {
  		$query_2 = $mbd->prepare("SELECT * FROM pasos WHERE code_producto = :code_producto AND id_etapa = :id_etapa ORDER BY orden");
	  	$query_2->bindParam(":code_producto", $codigo_producto);
	  	$query_2->bindParam(":id_etapa", $res['id']);
	  	$query_2->execute();

	  	$values = array();

	  	$ins_['id'] = $res['id'];
	  	$ins_['etapa'] = $res['etapa'];
	  	while ($res = $query_2->fetch(PDO::FETCH_ASSOC)) {
	  		$values[] = $res;
	  	}
	  	$ins_['pasos'] = $values;
	  	$result_[] = $ins_;
  	}*/

  	$table = "";
  	while ($res = $query_1->fetch(PDO::FETCH_ASSOC)) {
  		//echo $res['descripcion']."<br>";
  		$table .= '<tr>
  			<th style="padding: 5px; width: 200px; font-size: 10px;">'.$res['descripcion'].'</th>
          	<td style="padding: 5px; font-size: 10px;">'.$res['t_2'].'</td>      
          	<td style="padding: 5px; font-size: 10px;">'.$res['t_4'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['t_6'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['t_8'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['t_10'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['t_12'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['t_14'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['t_16'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['s'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['m'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['l'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['xl'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['xxl'].'</td>
          	<td style="padding: 5px; font-size: 10px;">'.$res['xxxl'].'</td>
  		</tr>';
  	}
	

	$pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
	<page_header >
		
	</page_header>
	<page_footer>

		<div id=\"watermark\" style=\"width: 100%; position: relative; height: 100px;\"><img src=\"".$_SERVER['DOCUMENT_ROOT']."/core/app/view/img/fondo.png\" height=\"100%\" width=\"100%\" style=\"height: 100px;\">
			
		</div>
	</page_footer>
	<div style='width: 100%;'>
		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo-2.png\" align='left' border='0' style='width: 150px;' />
	</div>
	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Tabla de Medidas Modelo: ".$codigo_producto."</p>
	
	<table cellpadding='4' cellspacing='0' border='1' style=\"width: 100%;\">
		<tr>
			<th style=\"padding: 5px;\">Descripción</th>
			<th style=\"padding: 5px;\">2</th>
			<th style=\"padding: 5px;\">4</th>
			<th style=\"padding: 5px;\">6</th>
			<th style=\"padding: 5px;\">8</th>
			<th style=\"padding: 5px;\">10</th>
			<th style=\"padding: 5px;\">12</th>
			<th style=\"padding: 5px;\">14</th>
			<th style=\"padding: 5px;\">16</th>
			<th style=\"padding: 5px;\">S</th>
			<th style=\"padding: 5px;\">M</th>
			<th style=\"padding: 5px;\">L</th>
			<th style=\"padding: 5px;\">XL</th>
			<th style=\"padding: 5px;\">2XL</th>
			<th style=\"padding: 5px;\">3XL</th>
		</tr>
		".$table."
	</table>
</page>";

	header("Content-Disposition: attachment; filename=ficha_tecnica-".$codigo_producto.".pdf");

	require __DIR__.'/html2pdf/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	ob_start();

	//$html2pdf = new Html2Pdf('L','A4','es','false','UTF-8',array(0,0,0,0));
	//$html2pdf = new HTML2PDF('L', 'A4', 'en');
	$html2pdf = new Html2Pdf('P','A4','es','false','UTF-8',array(0,0,0,0));

	$html2pdf->writeHTML($pipi);

	//$html2pdf->output();
	// $html2pdf->Output("ficha_tecnica-".$codigo_producto.".pdf", 'D');  
	$html2pdf->Output("ficha_tecnica-".$codigo_producto.".pdf");  
?>