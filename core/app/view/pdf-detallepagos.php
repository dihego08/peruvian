<?php 
	include('env.php');
	$ventaId = $_GET['vid'];
	$clienteId = $_GET['cid'];
	$sql1 = "SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as clientedesc, p.name, d.name, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :clienteId AND vc.codigo_venta = :ventaId ORDER BY vc.fecha_creacion DESC";
	$query = $mbd->prepare($sql1);
	//$query = $mbd->prepare("SELECT * FROM cotizacion_detalle WHERE codigo_cotizacion = :codigo");
	$query->bindParam(':clienteId', $clienteId);
	$query->bindParam(':ventaId', $ventaId);
	$query->execute();

	while ($q = $query->fetch(PDO::FETCH_ASSOC)) {
		$fecha = $q['fecha_creacion'];
		$tipo_pago = $q['tipo_pago'];
		$pedido_cod = $q['pedido_cod'];
		
		$cliente = $q['clientedesc'];
		$valor_pagar = $q['valor_pagar'];
		$pagado = $q['pagado'];
		$a_cuenta = $q['a_cuenta'];
		
	}

	$query2 = $mbd->prepare("SELECT id, codigo_venta, id_person, total, pago, deuda, fecha_creacion as fc, DATE(fecha_creacion) as fecha_creacion FROM pagos WHERE codigo_venta = :codigo_venta AND id_person = :id_person ORDER BY fc ASC");
			$query2->bindParam(':codigo_venta', $ventaId);
			$query2->bindParam(':id_person', $clienteId);
	$query2->execute();

	

	$html = '<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title>Estado de Cuenta</title>
				
				<link href="https://www.softluttion.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
			</head>
			<body>
				<img src="'.$_SERVER['DOCUMENT_ROOT'].'/sivecsol/img/logo-2.png" style="width:98px;">
				<h2 style="font-weight: bold; text-align: center;">Datos de Venta</h2>
				<table class="table table-bordered" border="1">
				<tr>
				<td>Nro Documento:</td><td>'.$ventaId.'</td>
				<td>Fecha:</td><td>'.$fecha.'</td>
				<td>Nro Pedido:</td><td>'.$pedido_cod.'</td>
				<td>Cliente:</td><td>'.$cliente.'</td>
				</tr>
				<tr>
				<td>Total a Pagar:</td><td>'.$valor_pagar.'</td>
				<td>Total Pagado:</td><td>'.$pagado.'</td>
				<td>Saldo Final:</td><td>'.$a_cuenta.'</td>
				</tr>
				</table>
				<br><br>
				<h2 style="font-weight: bold; text-align: center;">Historial de Pagos</h2>
				<br>
				<table class="table table-bordered table-hover" id="detalle_pagos">
<tbody>
				              <tr>

				                <th>Fecha de Pago</th>

				                <th>Monto</th>

				                <th>Adeuda</th>
                                

				              </tr>

				';

			$total = 0;
			while ($res = $query2->fetch(PDO::FETCH_ASSOC)) {
				$html = $html . '<tr>

				                <td>'.$res['fecha_creacion'].'</td>

				                <td>'.$res['pago'].'</td>

				                <td>'.$res['deuda'].'</td>
                                

				              </tr>';
			}

	$html = $html . '		
			</tbody>

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