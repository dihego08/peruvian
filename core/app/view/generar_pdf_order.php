<?php
ini_set('memory_limit', '-1');
$html = '<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title>Document</title>
				
				<link href="https://www.softluttion.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
			</head>
			<body>
				<img src="' . $_SERVER['DOCUMENT_ROOT'] . '/sistema/img/logo.png" width="193">
				<h3 style="font-weight: bold; text-align: center;">Reporte de Orden de Pedido</h3>
				<table class="table table-bordered">
					<tr>
						<th>Pedido</th>
						<th>Fecha de Pedido</th>
						<th>Cliente</th>
						<th style="width: 50px;">Descripción</th>
						<th>Modelo</th>
						<th>Núm. Contrato</th>
						<th>Cant Pedido</th>
						<th>Cant Produccion</th>
						<th>Guía Remisión</th>
						<th>Documento</th>
						<th>Fec. Entrega</th>
						<th>Estado</th>
		          	</tr>';
include('env.php');
include('clsOrder.php');
if ($_GET['orden'] == "lista_ordenes") {
	if ($_GET['id_cliente'] == 0) {
		if (isset($_GET['fecha_desde'])) {
			$query = $mbd->prepare("SELECT oc.nombre_modelo, oc.fecha_entrega_real, oc.imagen_alt, oc.guia_remision, oc.num_contrato, oc.comentario, oc.codigo, DATE_FORMAT(oc.fecha_creacion, '%d-%m-%Y') as fecha_creacion, oc.tiempo_entrega, oc.fecha_entrega, oc.estado, p.name, DATEDIFF(fecha_entrega, CURDATE()) as trans, oc.total, (SELECT p.image FROM order_detalle_2 d inner join product p on d.modelo = p.code where d.codigo_cabecera = oc.codigo order by d.id asc limit 1) as imagen,(SELECT p.name FROM order_detalle_2 d inner join product p on d.modelo = p.code where d.codigo_cabecera = oc.codigo order by d.id asc limit 1) as producto, (SELECT sum(d.ptotal) FROM order_detalle_2 d where d.codigo_cabecera = oc.codigo) as totalp FROM order_cabecera as oc, person as p WHERE p.id = oc.person_id AND fecha_creacion BETWEEN :fecha_desde AND :fecha_hasta ORDER BY CAST(oc.codigo as UNSIGNED) DESC");
			//AND :fecha_hasta
			$query->bindParam(":fecha_desde", $_GET['fecha_desde']);
			$query->bindParam(":fecha_hasta", $_GET['fecha_hasta']);
		} else {
			$query = $mbd->prepare("SELECT oc.nombre_modelo, oc.fecha_entrega_real, oc.imagen_alt, oc.guia_remision, oc.num_contrato, oc.comentario, oc.codigo, DATE_FORMAT(oc.fecha_creacion, '%d-%m-%Y') as fecha_creacion, oc.tiempo_entrega, oc.fecha_entrega, oc.estado, p.name, DATEDIFF(fecha_entrega, CURDATE()) as trans, oc.total, (SELECT p.image FROM order_detalle_2 d inner join product p on d.modelo = p.code where d.codigo_cabecera = oc.codigo order by d.id asc limit 1) as imagen,(SELECT p.name FROM order_detalle_2 d inner join product p on d.modelo = p.code where d.codigo_cabecera = oc.codigo order by d.id asc limit 1) as producto, (SELECT sum(d.ptotal) FROM order_detalle_2 d where d.codigo_cabecera = oc.codigo) as totalp FROM order_cabecera as oc, person as p WHERE p.id = oc.person_id ORDER BY CAST(oc.codigo as UNSIGNED) DESC");
		}
	} else {
		if (isset($_GET['fecha_desde'])) {
			$query = $mbd->prepare("SELECT oc.nombre_modelo, oc.fecha_entrega_real, oc.imagen_alt, oc.guia_remision, oc.num_contrato, oc.comentario, oc.codigo, DATE_FORMAT(oc.fecha_creacion, '%d-%m-%Y') as fecha_creacion, oc.tiempo_entrega, oc.fecha_entrega, oc.estado, p.name, DATEDIFF(fecha_entrega, CURDATE()) as trans, oc.total,(SELECT p.image FROM order_detalle_2 d inner join product p on d.modelo = p.code where d.codigo_cabecera = oc.codigo order by d.id asc limit 1) as imagen,(SELECT p.name FROM order_detalle_2 d inner join product p on d.modelo = p.code where d.codigo_cabecera = oc.codigo order by d.id asc limit 1) as producto,(SELECT sum(d.ptotal) FROM order_detalle_2 d where d.codigo_cabecera = oc.codigo) as totalp FROM order_cabecera as oc, person as p WHERE p.id = oc.person_id AND fecha_creacion BETWEEN :fecha_desde AND :fecha_hasta AND p.id = :id_cliente ORDER BY CAST(oc.codigo as UNSIGNED) DESC");
			$query->bindParam(':id_cliente', $_GET['id_cliente']);
			$query->bindParam(":fecha_desde", $_GET['fecha_desde']);
			$query->bindParam(":fecha_hasta", $_GET['fecha_hasta']);
		} else {
			$query = $mbd->prepare("SELECT oc.nombre_modelo, oc.fecha_entrega_real, oc.imagen_alt, oc.guia_remision, oc.num_contrato, oc.comentario, oc.codigo, DATE_FORMAT(oc.fecha_creacion, '%d-%m-%Y') as fecha_creacion, oc.tiempo_entrega, oc.fecha_entrega, oc.estado, p.name, DATEDIFF(fecha_entrega, CURDATE()) as trans, oc.total,(SELECT p.image FROM order_detalle_2 d inner join product p on d.modelo = p.code where d.codigo_cabecera = oc.codigo order by d.id asc limit 1) as imagen,(SELECT p.name FROM order_detalle_2 d inner join product p on d.modelo = p.code where d.codigo_cabecera = oc.codigo order by d.id asc limit 1) as producto,(SELECT sum(d.ptotal) FROM order_detalle_2 d where d.codigo_cabecera = oc.codigo) as totalp FROM order_cabecera as oc, person as p WHERE p.id = oc.person_id AND p.id = :id_cliente ORDER BY CAST(oc.codigo as UNSIGNED) DESC");
			$query->bindParam(':id_cliente', $_GET['id_cliente']);
		}
	}
} elseif ($_GET['orden'] == "lista_ventas") {
}
$values = array();
$t_subtotal = 0;
$t_igv = 0;
$t_detraccion = 0;
$t_igv_pagar = 0;
$t_total = 0;
$query->execute();
while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

	$nombre_modelo = "";
	if ($res['nombre_modelo'] == "" || $res['nombre_modelo'] == null) {
		$nombre_modelo = $res['producto'];
	} else {
		$nombre_modelo = $res['nombre_modelo'];
	}
	$contrato = "";
	if ($res['num_contrato'] == null || $res['num_contrato'] == "null" || $res['num_contrato'] == "NULL") {
	} else {
		$contrato = $res['num_contrato'];
	}
	$guia = "";
	if ($res['guia_remision'] == null || $res['guia_remision'] == "null" || $res['guia_remision'] == "NULL") {
	} else {
		$guia = $res['guia_remision'];
	}
	$fecha_e_r = "";
	if ($res['fecha_entrega_real'] == null || $res['fecha_entrega_real'] == "null" || $res['fecha_entrega_real'] == "NULL") {
	} else {
		$fecha_e_r = $res['fecha_entrega_real'];
	}
	$img_ = "";
	if ($res['imagen_alt'] == "" || $res['imagen_alt'] == null) {
		$img_ = $res['imagen'];
	} else {
		$img_ = $res['imagen_alt'];
	}

	$query_venta = $mbd->prepare("SELECT codigo_venta FROM ventas_cabecera WHERE pedido_cod LIKE '%" . $res['codigo'] . "%'");
	$query_venta->execute();
	$venta = $query_venta->fetch(PDO::FETCH_ASSOC);

	$res['codigo_venta'] = is_null($venta['codigo_venta']) ? "" : $venta['codigo_venta'];
	$estado = "";
	if ($res['estado'] == 0) {
		$estado = 'Pendiente';
	} else {
		if ($res['estado'] == 1) {
			$estado = 'Entregado';
		} else {
			$estado = 'Cancelado';
		}
	}

	$html .= '<tr><th scope="row">' . $res['codigo'] . '</th>
					<th>' . $res['fecha_creacion'] . '</th>
					<td>' . $res['name'] . '</td>
					<td>' . $nombre_modelo . '</td>
					<td><img src="' . $_SERVER['DOCUMENT_ROOT'] . '/sistema/storage/products/' . $img_ . '" style="width:64px;"></td>
					<td>' . $contrato . '</td>
					<td>' . $res['total'] . '</td>
					<td>' . $res['totalp'] . '</td>
					<td>' . $guia . '</td>
					<td>' . $res['codigo_venta'] . '</td>
					<td>' . $fecha_e_r . '</td>
					<td>' . $estado . '</td></tr>';
}
$html = $html . '
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
$dompdf->stream('my.pdf', array('Attachment' => 0));
