<?php 
	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/
	include('env.php');
	$codigo_producto = $_GET['num_modelo'];

  	$ma = $mbd->prepare("SELECT * FROM maquinas WHERE code_producto = :code_producto");
  	$ma->bindParam(":code_producto", $codigo_producto);
  	$ma->execute();

  	$maquinas = array();

  	$maquinas = '<div style="padding: 5px; color: white; background: #337ab7; display: block; width: 100%;">
  	<h4 style="margin: 0px;">
		MAQUINAS
	</h4>
	</div>
	<table class="table table-bordered table-hover" id="tabla_maquinas" style="margin-top: 10px;">
		<tr>
		<th rowspan="'.($ma->rowCount() + 1).'" style="padding: 5px;">
			PUNTADAS POR PULGADA:      
		</th>
	</tr>';
	//echo $ma->rowCount()."<br>";
  	while ($m = $ma->fetch(PDO::FETCH_ASSOC)) {
  		//echo "MAQ ".$m['maquina']."<br>";
  		$maquinas .= '<tr>
			<td style="padding: 5px; width: 500px;">
				'.$m['maquina'].'
			</td>
		</tr>';
  	}

  	$maquinas .= '</table>';

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

	  	$maquinas .= '<div style="padding: 5px; color: white; background: #337ab7; display: block; width: 100%; margin-top: px;">
	  		<h4 style="margin: 0px;">'.$res['etapa'].'</h4>
		</div>
	  	<table border=1>';

	  	while ($r = $query_2->fetch(PDO::FETCH_ASSOC)) {
	  		//$values[] = $res;
	  		$maquinas .= '<tr>
	  			<th style="padding: 5px; width: 150px;">'.$r['paso'].'</th>
	  			<td style="padding: 5px; width: 500px;">'.nl2br($r['instruccion']).'</td>
	  			<td style="padding: 5px;">'.$r['orden'].'</td>
	  		</tr>';
	  	}
	  	$maquinas .= '</table>';
	  	$ins_['pasos'] = $values;
	  	$result_[] = $ins_;
  	}

  	$table = "";	

	$pipi = "<page pageset='new' backleft='10mm' backtop='10mm' backright='10mm' footer='page'>
	<page_header >
		
	</page_header>
	<page_footer>

		<!--<div id=\"watermark\" style=\"width: 100%; position: relative; height: 75px;\">
			<img src=\"".$_SERVER['DOCUMENT_ROOT']."/core/app/view/img/fondo.png\" height=\"100%\" width=\"100%\" style=\"height: 75px;\">
		</div>-->
	</page_footer>
	<div style='width: 100%;'>
		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo-2.png\" align='left' border='0' style='width: 150px;' />
	</div>
	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Manual de Operaciones Modelo: ".$codigo_producto."</p>
	
	".$maquinas."
</page>";

	header("Content-Disposition: attachment; filename=Manual de Operaciones-".$codigo_producto.".pdf");

	require __DIR__.'/html2pdf/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	ob_start();

	//$html2pdf = new Html2Pdf('L','A4','es','false','UTF-8',array(0,0,0,0));
	//$html2pdf = new HTML2PDF('L', 'A4', 'en');
	$html2pdf = new Html2Pdf('P','A4','es','false','UTF-8',array(0,0,0,0));

	$html2pdf->writeHTML($pipi);

	//$html2pdf->output();
	//$html2pdf->Output("Manual de Operaciones-".$codigo_producto.".pdf", 'D');  
	$html2pdf->Output("Manual de Operaciones-".$codigo_producto.".pdf", 'D');  
?>