<?php
	$html = '<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title>Document</title>
				
				<link href="https://www.softluttion.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
			</head>
			<body>
				<img src="'.$_SERVER['DOCUMENT_ROOT'].'/sistema/img/logo.png" width="75">
				<h3 style="font-weight: bold; text-align: center;">Reporte de Ventas</h3>
				<table class="table table-bordered">
					<tr>
						<th>Factura</th>	
						<th>Fecha</th>
						<th>Tipo</th>
						<th>RUC</th>
						<th>Cliente</th>
						<th>Subtotal</th>
						<th>IGV</th>
						<th>Detraccion</th>
						<th>IGV * Pagar</th>
						<th>Total</th>
		          	</tr>';
  	include('env.php');
  	if ($_GET['orden'] == "buscar_por_fecha") {
  		$stop_date = date('Y-m-d', strtotime($_GET['hasta'] . ' +1 day'));
  		if($_GET['id_cliente'] == "" || $_GET['id_cliente'] == null || is_null($_GET['id_cliente']) || $_GET['id_cliente'] == 0){
			/*$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
					FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k 
					WHERE vc.fecha_emision >= :desde AND vc.fecha_emision <= :hasta AND vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id 
				UNION SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
					FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k 
					WHERE vc.fecha_emision >= :desde AND vc.fecha_emision <= :hasta AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 ORDER BY fc DESC");*/
			$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND DATE(vc.fecha_emision) BETWEEN :desde AND :hasta ORDER BY vc.fecha_emision DESC");
			$query->bindParam(':desde', $_GET['desde']);
			$query->bindParam(':hasta', $stop_date);
			$query->execute();
		}else{
			$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND DATE(vc.fecha_emision) BETWEEN :desde AND :hasta AND vc.id_person = :id_cliente ORDER BY vc.fecha_emision DESC");
			$query->bindParam(':desde', $_GET['desde']);
			$query->bindParam(':hasta', $stop_date);
			$query->bindParam(':id_cliente', $_GET['id_cliente']);
			$query->execute();
		}
  	}elseif($_GET['orden'] == "lista_ventas"){
  		if($_GET['filtro'] == 'ninguno' && $_GET['fecha'] == ""){
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id ORDER BY fc DESC");
			$query->execute();
		}elseif ($_GET['filtro'] == "ninguno" && $_GET['fecha'] != "") {
			//$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio UNION SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, vc.fecha_creacion as fc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio ORDER BY fc DESC");
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio ORDER BY fc DESC");
			$query->bindParam(':mes', $mes);
			$query->bindParam(':anio', $anio);
			$query->execute();
		}elseif ($_GET['filtro'] == 'pago' && $_GET['fecha'] == "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND p.id = :codigo  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
		}elseif ($_GET['filtro'] == 'entrega') {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion,  .name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND d.id = :codigo  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
		}elseif ($_GET['filtro'] == 'cliente' && $_GET['fecha'] == "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
		}elseif ($_GET['filtro'] == 'cliente' && $_GET['fecha'] != "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->bindParam(':mes', $mes);
			$query->bindParam(':anio', $anio);
			$query->execute();
		}
  	}
  	//$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id  ORDER BY vc.fecha_creacion DESC");
	//$query->execute();
	$values = array();
	$t_subtotal = 0;
	$t_igv = 0;
	$t_detraccion = 0;
	$t_igv_pagar = 0;
	$t_total = 0;
	while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
		//$values[] = $res;
		
		$igv_pagar = "0";
		if($res['igv_p'] == "0.00" || floatval($res['igv_p']) == floatval(0)){
            $igv_pagar = $res['igv'];
        }else{
            $igv_pagar = $res['igv_p'];
        }

		$t_subtotal += floatval($res['subtotal']);
        $t_igv += floatval($res['igv']);
        $t_detraccion += floatval($res['detraccion_p']);
        $t_igv_pagar += floatval($igv_pagar);
        $t_total += floatval($res['total']);
		
		if ($res['a_cuenta'] > floatval('0.00')) {
			$html = $html . '<tr class="danger">
				<td>'.$res['codigo_venta'].'</td>
				<td>'.$res['fecha_creacion'].'</td>
				<td>'.$res['tipo_documento'].'</td>
				<td>'.$res['ruc'].'</td>
				<td style="font-size: 12px;">'.$res['person'].'</td>
				<td>S/ '.$res['subtotal'].'</td>
				<td>S/ '.$res['igv'].'</td>
				<td>S/ '.($res['detraccion_p']).'</td>
				<td>S/ '.$igv_pagar.'</td>
				<td>S/ '.$res['total'].'</td>
			</tr>';
		}else{
			$html = $html . '<tr>
				<td>'.$res['codigo_venta'].'</td>
				<td>'.$res['fecha_creacion'].'</td>
				<td>'.$res['tipo_documento'].'</td>
				<td>'.$res['ruc'].'</td>
				<td style="font-size: 12px;">'.$res['person'].'</td>
				<td>S/ '.$res['subtotal'].'</td>
				<td>S/ '.$res['igv'].'</td>
				<td>S/ '.($res['detraccion_p']).'</td>
				<td>S/ '.$igv_pagar.'</td>
				<td>S/ '.$res['total'].'</td>
			</tr>';
		}

		
	}
	$html = $html . '<tr style="font-weight: bold;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="white-space: nowrap;">S/ '.number_format($t_subtotal, 2).'</td>
            <td style="white-space: nowrap;">S/ '.number_format($t_igv, 2).'</td>
            <td style="white-space: nowrap;">S/ '.number_format($t_detraccion, 2).'</td>
            <td style="white-space: nowrap;">S/ '.number_format($t_igv_pagar, 2).'</td>
            <td style="white-space: nowrap;">S/ '.number_format($t_total, 2).'</td>
        </tr>';
	$html = $html.'
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
	$dompdf->setPaper('A4', 'landscape');

	// Render the HTML as PDF
	$dompdf->render();

	// Output the generated PDF to Browser
	$dompdf->stream('my.pdf',array('Attachment'=>0));
?>