<?php 
	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/
	include('env.php');
	$codigo_producto = $_GET['num_modelo'];

	include("clsFichaTecnica.php");

	$ficha = new clsFichaTecnica;
	$identificacion = json_decode($ficha->get_identificacion($codigo_producto));

	$query_2 = $mbd->prepare("SELECT * FROM ficha_tecnica WHERE code_producto = :codigo_producto");
	$query_2->bindParam(":codigo_producto", $codigo_producto);
	$query_2->execute();

	$datos = $query_2->fetch(PDO::FETCH_ASSOC);
	
	$query = $mbd->prepare("SELECT * FROM product WHERE code = :codigo_producto");
	$query->bindParam(":codigo_producto", $codigo_producto);
	$query->execute();

	$producto = $query->fetch(PDO::FETCH_ASSOC);
	
	/*include('clsFichaTecnica.php');
	$ficha = new clsFichaTecnica;*/

	$complementos = json_decode($ficha->get_complementos($codigo_producto));
	$modificacion = json_decode($ficha->get_modificacion($codigo_producto));

	$table_1 = "";
	foreach ($identificacion->Records as $key => $value) {
		$table_1 .= '<tr>
			<td style="width: 160px; text-align: left;">
				<strong>'.$value->titulo.'</strong>
			</td>
			<td style="width: 465px;">
				'.$value->complemento.'
			</td>
		</tr>';
	}

	$table_3 = '';
	foreach ($modificacion->Records as $key => $value) {
		$table_3 .= '<tr>
			<td style="width: 160px; text-align: left;">
				'.$value->aprobado_por.'
			</td>
			<td style="width: 400px;">
				'.$value->titulo.'
			</td>
			<td style="width: 65px;">
				'.$value->ultima_modificacion.'
			</td>
		</tr>';
	}

	$table = "";
	//print_r($complementos);
	foreach ($complementos->Records as $key => $value) {
		$table .= '<tr>
			<td style="width: 160px; text-align: left;">
				<strong style="font-size: 13px;">'.$value->titulo.'</strong>
			</td>
			<td style="width: 465px;">
				'.$value->complemento.'
			</td>
		</tr>';
	}

	//625
  	$pipi = '<page pageset="new" backleft="10mm" backtop="10mm" backright="10mm" footer="page">
		<page_header>
			
		</page_header>
		<page_footer>
			
		</page_footer>
		<table >
			<tr>
				<td>
					<img src="'.$_SERVER['DOCUMENT_ROOT'].'/img/logo-2.png" align="left" border="0" style="width: 150px;" />
				</td>
				<td style="text-align: center; width: 475px;">
					<p style="font-weight: bold; font-size: 20px;">MODELO N° '.$codigo_producto.'</p>
				</td>
			</tr>
		</table>
		
		<h4 style="margin-bottom: 0px;">A. IDENTIFICACIÓN DEL PRODUCTO</h4>
		<div style="background: #da7339; height: 5px;"></div>
		<table >
			'.$table_1.'
		</table>
		<h4>MATERIALES y COMPLEMENTOS</h4>
		<table >
			'.$table.'
		</table>

		<h4 style="margin-bottom: 0px;">B. DESCRIPCIÓN DEL ESTILO</h4>
		<div style="background: #337ab7; height: 5px;"></div>
		<div style="text-align: center;">
			<img src="'.$_SERVER['DOCUMENT_ROOT'].'/storage/products/'.$producto['image'].'" align="left" border="0" style="width: 250px;" />
		</div>

		<h4 style="margin-bottom: 0px;">C. MODIFICACIONES</h4>
		<div style="background: #00ff00; height: 5px;"></div>
		<table style="border-bottom: solid;">
			<tr>
				<td style="width: 160px; text-align: left;">
					<strong>RESPONSABLE</strong>
				</td>
				<td style="width: 400px;">
					<strong>DESCRIPCION DE LA MODIFICACION</strong>
				</td>
				<td style="width: 65px;">
					<strong>FECHA</strong>
				</td>
			</tr>
		</table>
		<table style="margin-top: 10px;">
			'.$table_3.'
		</table>';

		/*<table cellpadding="4" cellspacing="0"  border="1" style="width: 100%; margin-top: 50px; display: none;">
			<tr>
				<td style="padding: 5px; width: 135px;""><strong>Elaborado por: </strong><br>'.$datos['elaborado_por'].'</td>
				<td style="padding: 5px; width: 135px;""><strong>Aprobado por: </strong><br>'.$datos['aprobado_por'].'</td>
				<td style="padding: 5px; width: 135px;""><strong>Revisado por: </strong><br>'.$datos['revisado_por'].'</td>
				<td style="padding: 5px; width: 135px;""><strong>Ultima Modificación: </strong><br>'.$datos['u_modificacion'].'</td>
			</tr>
		</table>*/
		$pipi .= '<div style="background: #6f6f6f; height: 5px;"></div>

	</page>';

	header("Content-Disposition: attachment; filename=Ficha-Tecnica-".$codigo_producto.".pdf");

	require __DIR__.'/html2pdf/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	ob_start();

	$html2pdf = new Html2Pdf('P', 'A4','es','false','UTF-8',array(10,0,10,0));

	$html2pdf->writeHTML($pipi);

	$html2pdf->Output("Ficha-Tecnica-".$codigo_producto.".pdf", 'D');  
?>