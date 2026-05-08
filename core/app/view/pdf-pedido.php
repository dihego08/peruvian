<?php 
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ini_set('soap.wsdl_cache_enabled',0);
    ini_set('soap.wsdl_cache_ttl',0);
	include('env.php');
	$codigo = $_GET['codigo'];
	$sql1 = "SELECT oc.nombre_modelo, oc.comentario, oc.imagen_alt, oc.codigo, date(oc.fecha_creacion) as fecha_creacion, oc.tiempo_entrega, oc.fecha_entrega, oc.estado, p.name, DATEDIFF(fecha_entrega, CURDATE()) as trans, oc.total,(SELECT p.image FROM order_detalle_2 d inner join product p on d.modelo = p.code where d.codigo_cabecera = oc.codigo order by d.id asc limit 1) as imagen, (SELECT sum(d.ptotal) FROM order_detalle_2 d where d.codigo_cabecera = oc.codigo) as totalp FROM order_cabecera as oc, person as p WHERE p.id = oc.person_id and oc.codigo = :codigo";
	$query = $mbd->prepare($sql1);
	//$query = $mbd->prepare("SELECT * FROM cotizacion_detalle WHERE codigo_cotizacion = :codigo");
	$query->bindParam(':codigo', $codigo);
	$query->execute();

	while ($q = $query->fetch(PDO::FETCH_ASSOC)) {
		//print_r($q)."<br>";
		$fecha = $q['fecha_creacion'];
		$fecha_entrega = $q['fecha_entrega'];
		$entrega = $q['tiempo_entrega'];
		$cliente = $q['name'];
		$comentarios = $q['comentario'];
		if($q['imagen_alt'] == "" || is_null($q['imagen_alt']) || empty($q['imagen_alt'])){
			$imagen = $q['imagen'];
		}else{
			$imagen = $q['imagen_alt'];
		}
		$totalp = $q['totalp'];

		if($q['nombre_modelo'] == null || empty($q['nombre_modelo'])){
			$nombre_modelo = "";
		}else{
			$nombre_modelo = $q['nombre_modelo'];
		}
		
	}

	$query2 = $mbd->prepare("SELECT `id`, `codigo_cabecera`, `modelo`, `color`, COALESCE(`_2`, ' ') _2, COALESCE(`_4`, ' ') _4, COALESCE(`_6`, ' ') _6, COALESCE(`_8`, ' ') _8, COALESCE(`_10`, ' ') _10, COALESCE(`_12`, ' ') _12, COALESCE(`_14`, ' ') _14, COALESCE(`_16`, ' ') _16, COALESCE(`s`, ' ') s, COALESCE(`m`, ' ') m, COALESCE(`l`, ' ') l, COALESCE(`xl`, ' ') xl, COALESCE(`xxl`, ' ') xxl, total,p2,p4,p6,p8,p10,p12,p14,p16,ps,pm,pl,pxl,pxxl,ptotal, n1, n2, n3, n4, n5, n6, n7, n8, n9, n10, n11, n12, n13 FROM order_detalle_2 WHERE codigo_cabecera = :codigo");
	$query2->bindParam(':codigo', $codigo);
	$query2->execute();
	

	

	$html = '<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title>Pedido</title>
				
				<link href="https://peruvian.peruviandress.com/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
			</head>
			<body>
				
				<table class="table" style="border: none;">
				    <tr>
				        <td style="vertical-align: middle; padding: 0px;">
				            <img src="'.$_SERVER['DOCUMENT_ROOT'].'/img/logo-3.png" style="width:200px;">
				        </td>
				        <td style="vertical-align: middle; padding: 0px;">
				            <h5 style="font-weight: bold; text-align: center;">REQUERIMIENTO DE PEDIDO</h5>
				        </td>
				        <td style="vertical-align: middle; padding: 0px;">
				            <table class="table table-xs table-bordered" border="1">
				                <tr>
				                    <td style="padding: 5px;">
				                        <table>
				                            <tr>
				                                <td>Código: </td>
				                                <td>PD-FOR-011</td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				                <tr>
				                    <td style="padding: 5px;">
				                        <table>
				                            <tr>
				                                <td>Versión: </td>
				                                <td>001</td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				                <tr>
				                    <td style="padding: 5px;">
				                        <table>
				                            <tr>
				                                <td>F. Aprob.: </td>
				                                <td>10/01/2022</td>
				                            </tr>
				                        </table>
				                    </td>
				                </tr>
				            </table>
				        </td>
				    </tr>
				</table>
				<h3 style="font-weight: bold; text-align: center;">PEDIDO Nro : '.$codigo.'</h3>
				<table class="table table-bordered" border="1">
				<tr><td>Fecha de entrega:</td><td>'.$fecha_entrega.'</td></tr>
				<tr><td>Cliente:</td><td>'.$cliente.'</td></tr>
				<tr><td>Tiempo de Entrega:</td><td>'.$entrega.'</td></tr>
				<tr><td>'.$nombre_modelo.'</td><td><img src="'.$_SERVER['DOCUMENT_ROOT'].'/storage/products/'.$imagen.'" style="width:128px;"></td></tr>
				<table>
				<table class="table table-bordered" border="1">
				<thead>
								<tr>
									<th rowspan="2" style="vertical-align: middle; text-align: center;">Modelo</th>
									<th rowspan="2" style="vertical-align: middle; text-align: center;">Color</th>
									<th colspan="14" style="text-align: center;">Cantidades por Talla</th>
								</tr>
								<tr>';
								$cabecera = "";
									

			$total = 0;
			$table_2 = "";
			while ($res = $query2->fetch(PDO::FETCH_ASSOC)) {
				$subtotal = $res['total'];

				$cabecera = '<th>'.$res['n1'].'</th>
				<th>'.$res['n2'].'</th>
				<th>'.$res['n3'].'</th>
				<th>'.$res['n4'].'</th>
				<th>'.$res['n5'].'</th>
				<th>'.$res['n6'].'</th>
				<th>'.$res['n7'].'</th>
				<th>'.$res['n8'].'</th>
				<th>'.$res['n9'].'</th>
				<th>'.$res['n10'].'</th>
				<th>'.$res['n11'].'</th>
				<th>'.$res['n12'].'</th>
				<th>'.$res['n13'].'</th>';

				$total += $subtotal;
				$table_2 = $table_2 . '<tr><td>'.$res['modelo'].'</td>'.
				'<td>'.$res['color'].'</td>'.
				'<td>'.$res['_2'].'</td>'.
				'<td>'.$res['_4'].'</td>'.
				'<td>'.$res['_6'].'</td>'.
				'<td>'.$res['_8'].'</td>'.
				'<td>'.$res['_10'].'</td>'.
				'<td>'.$res['_12'].'</td>'.
				'<td>'.$res['_14'].'</td>'.
				'<td>'.$res['_16'].'</td>'.
				'<td>'.$res['s'].'</td>'.
				'<td>'.$res['m'].'</td>'.
				'<td>'.$res['l'].'</td>'.
				'<td>'.$res['xl'].'</td>'.
				'<td>'.$res['xxl'].'</td>
				<td>'.$subtotal.'</td></tr>';

				$table_2 = $table_2 . '<tr><td colspan="2">PRODUCIDOS</td>'.
				'<td>'.$res['p2'].'</td>'.
				'<td>'.$res['p4'].'</td>'.
				'<td>'.$res['p6'].'</td>'.
				'<td>'.$res['p8'].'</td>'.
				'<td>'.$res['p10'].'</td>'.
				'<td>'.$res['p12'].'</td>'.
				'<td>'.$res['p14'].'</td>'.
				'<td>'.$res['p16'].'</td>'.
				'<td>'.$res['ps'].'</td>'.
				'<td>'.$res['pm'].'</td>'.
				'<td>'.$res['pl'].'</td>'.
				'<td>'.$res['pxl'].'</td>'.
				'<td>'.$res['pxxl'].'</td>
				<td>'.$res['ptotal'].'</td></tr>';
			}

			$html .= $cabecera .'
									<th>Total</th>
								</tr>
							</thead>
				'.$table_2;

	$html = $html . '		
			<tr style="font-weight: bold;"><td colspan="15" style="text-align: right;">Total:</td><td>'.$totalp.'</td></tr>
			<tr><td colspan="16" style="text-align: left;">Comentarios: '.$comentarios.'</td></tr>
			</table>
		</body>
		</html>';
	//echo $html;
	require_once 'dompdf/autoload.inc.php';

	// reference the Dompdf namespace
	use Dompdf\Dompdf;

	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');

	// Render the HTML as PDF
	$dompdf->render();

	// Output the generated PDF to Browser
	$dompdf->stream();
?>