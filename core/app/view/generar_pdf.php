<?php
	ini_set('max_execution_time', 300);
	set_time_limit(300);
	$filtro = $_GET['filtro'];
	$tabla = $_GET['tabla'];
	if($filtro == 'fecha' && $tabla == 'compras'){
		$desde = $_GET['desde'];
		$hasta = $_GET['hasta'];
		
		include("clsInsumos.php");
	    $compra = new ClsInsumos;
	    $compras = json_decode($compra->lista_compras_2($_GET));
		$html = '<!DOCTYPE html>
		<html lang="es">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<title>Document</title>
			
			<link href="'.$_SERVER['DOCUMENT_ROOT'].'/sistema/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
			<style>.page_break { page-break-before: always; }</style>
		</head>
		<body>
			<img src="/home/peruvian/public_html/sistema/img/logo.png">
			<h3 style="font-weight: bold; text-align: center;">Reporte de Compras</h3>
			<table class="table table-bordered" style="font-size: 9px;"><tr>
                <th>Com</th>
				<th>Tipo</th>
				<th>Serie</th>
				<th>Numero</th>
				<th>Fecha Emision</th>
				<th>Apellidos y Nombres y/o Razon Social</th>
				<th>RUC</th>
				<th>Insumos</th>
				<th>Otros no Gravados</th>
				<th>Adquisi no Gravadas</th>
				<th>Adquisio Gravadas</th>
				<th>IGV</th>
				<th>Importe Total</th>
				<th>Constan Fecha</th>
				<th>Detraccion Numero</th>
				<th>T/C</th>
				<th>Fecha</th>
				<th>Serie</th>
				<th>Documento</th>
          </tr>';
		$aux = 0;
		foreach($compras->Records as $key => $value){
			$detalle_compra = json_decode($compra->lista_detalle($value->id));
			$insumos_ = "";
			$aux_2 = 0;
			foreach ($detalle_compra->Records as $k => $v) {
				if($aux_2 == 0){
					$insumos_ = $v->insumo;
				}else{
					$insumos_ .= " <b>|</b> ".$v->insumo;
				}
				$aux_2++;
			}

		    $aux++;
		    $ppp = "";
			if ($value->proveedor == 'null' || $value->proveedor == "") {
				$ppp = "";
			} else {
				$ppp = $value->proveedor;
			}
		    $html = $html . '<tr style="font-size: 9px;">
				<th scope="row">'.$aux.'</th>'.
				'<th scope="row">' . $value->tipo_documento . '</th>' .
				'<td>' . $value->serie . '</td>' .
				'<td>' . $value->numeracion . '</td>' .
				'<td>' . $value->fecha_creacion . '</td>' .
				'<td style="width: 60px;">' . $ppp . '</td>' .
				'<td>' . $value->no . '</td>' .
				'<td>' . $insumos_ . '</td>' .
				'<td>S/ ' . $value->otros_no_gravado . '</td>' .
				'<td>S/ ' . $value->exonerado . '</td>' .
				'<td>S/ ' . $value->gravado . '</td>' .
				'<td>S/ ' . $value->igv . '</td>' .
				'<td>S/ ' . $value->total . '</td>' .
				'<td>' . $value->fecha_detraccion . '</td>' .
				'<td>' . $value->numero_detraccion . '</td>' .
				'<td>' . $value->tipo_cambio . '</td>' .
				'<td>' . $value->fecha_comprobante . '</td>' .
				'<td>' . $value->serie_comprobante . '</td>' .
				'<td>' . $value->documento_comprobante . '</td>' .
			'</tr>';
		}
		
		$html = $html.'
                    </table>
                </body>
		</html>';
	}elseif ($filtro == 'ninguno' && $tabla == 'compras') {
	    include("clsInsumos.php");
	    $compra = new ClsInsumos;
	    $compras = json_decode($compra->lista_compras());
		$html = '<!DOCTYPE html>
		<html lang="es">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<title>Document</title>
			
			<link href="'.$_SERVER['DOCUMENT_ROOT'].'/sistema/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
			<style>.page_break { page-break-before: always; }</style>
		</head>
		<body>
			<img src="/home/peruvian/public_html/sistema/img/logo.png">
			<h3 style="font-weight: bold; text-align: center;">Reporte de Compras</h3>
			<table class="table table-bordered" style="font-size: 9px;"><tr>
                <th>Com</th>
				<th>Tipo</th>
				<th>Serie</th>
				<th>Numero</th>
				<th>Fecha Emision</th>
				<th>Apellidos y Nombres y/o Razon Social</th>
				<th>RUC</th>
				<th>Insumos</th>
				<th>Otros no Gravados</th>
				<th>Adquisi no Gravadas</th>
				<th>Adquisio Gravadas</th>
				<th>IGV</th>
				<th>Importe Total</th>
				<th>Constan Fecha</th>
				<th>Detraccion Numero</th>
				<th>T/C</th>
				<th>Fecha</th>
				<th>Serie</th>
				<th>Documento</th>
          </tr>';
		$aux = 0;
		foreach($compras->Records as $key => $value){
			$detalle_compra = json_decode($compra->lista_detalle($value->id));
			$insumos_ = "";
			$aux_2 = 0;
			foreach ($detalle_compra->Records as $k => $v) {
				if($aux_2 == 0){
					$insumos_ = $v->insumo;
				}else{
					$insumos_ .= " <b>|</b> ".$v->insumo;
				}
				$aux_2++;
			}
		    $aux++;
		    $ppp = "";
			if ($value->proveedor == 'null' || $value->proveedor == "") {
				$ppp = "";
			} else {
				$ppp = $value->proveedor;
			}
		    $html = $html . '<tr style="font-size: 9px;">
				<th scope="row">'.$aux.'</th>'.
				'<th scope="row">' . $value->tipo_documento . '</th>' .
				'<td>' . $value->serie . '</td>' .
				'<td>' . $value->numeracion . '</td>' .
				'<td>' . $value->fecha_creacion . '</td>' .
				'<td style="width: 60px;">' . $ppp . '</td>' .
				'<td>' . $value->no . '</td>' .
				'<td>' . $insumos_ . '</td>' .
				'<td>S/ ' . $value->otros_no_gravado . '</td>' .
				'<td>S/ ' . $value->exonerado . '</td>' .
				'<td>S/ ' . $value->gravado . '</td>' .
				'<td>S/ ' . $value->igv . '</td>' .
				'<td>S/ ' . $value->total . '</td>' .
				'<td>' . $value->fecha_detraccion . '</td>' .
				'<td>' . $value->numero_detraccion . '</td>' .
				'<td>' . $value->tipo_cambio . '</td>' .
				'<td>' . $value->fecha_comprobante . '</td>' .
				'<td>' . $value->serie_comprobante . '</td>' .
				'<td>' . $value->documento_comprobante . '</td>' .
			'</tr>';
		}
		
		$html = $html.'
                    </table>
                </body>
		</html>';
	}elseif ($filtro == 'fecha' && $tabla == 'ventas') {
		/*$desde = $_GET['desde'];
		$hasta = $_GET['hasta'];*/

		$tipos_pago = $_GET['tipos_pago'];
		$tipos_documento = $_GET['tipos_documento'];
		$combo_cliente = $_GET['combo_cliente'];

		//include('env.php');

		$where = "";

		if ($tipos_pago > 0) {
			$where .= ' AND p.id = '.$tipos_pago;
		}elseif ($tipos_pago == "-1") {
			//val.a_cuenta
			$where .= ' AND vc.a_cuenta > 0';
		}

		if ($tipos_documento != 0) {
			$where .= ' AND k.id = '.$tipos_documento;
		}

		if ($combo_cliente != 0) {
			$where .= ' AND pe.id = '.$combo_cliente;
		}

		if (empty($desde) || is_null($hasta)) {
		}else{
			$where .= " AND fecha_creacion BETWEEN '".$desde."' AND '".$hasta."'";
		}

		$html = '<!DOCTYPE html>
		<html lang="es">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<title>Document</title>
			
			<link href="https://www.softluttion.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
			<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
		</head>
		<body>
			<img src="/home/peruvian/public_html/sivecsol/img/logo.png">
			<h3 style="font-weight: bold; text-align: center;">Reporte de Ventas del '.$desde.' hasta '.$hasta.'</h3>
			<table class="table table-bordered">
				<tr>
					<th>Código</th>	
					<th>Fecha</th>
					<th>Tipo</th>
					<th>Pago</th>
					<th>Entrega</th>
					<th>Total</th>
					<th>Cliente</th>
					<th>Adeuda</th>
	          	</tr>';
		include('env.php');
		/*$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_creacion) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND DATE(vc.fecha_creacion) BETWEEN :desde AND :hasta ORDER BY fecha_creacion DESC");*/

		$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id ".$where." ORDER BY fecha_creacion DESC");

		/*$query->bindParam(':desde', $desde);
		$query->bindParam(':hasta', $hasta);*/
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			//$values[] = $res;
			if ($res['a_cuenta'] > floatval('0.00')) {
				$html = $html . '<tr class="danger">
				<td>'.$res['codigo_venta'].'</td>
				<td>'.$res['fecha_creacion'].'</td>
				<td>'.$res['tipo_documento'].'</td>
				<td>'.$res['pago'].'</td>
				<td>'.$res['entrega'].'</td>
				<td>S/. '.($res['total'] - $res['detraccion_p']).'</td>
				<td>'.$res['person'].'</td>
				<td>S/. '.$res['a_cuenta'].'</td></tr>';
			}else{
				$html = $html . '<tr>
				<td>'.$res['codigo_venta'].'</td>
				<td>'.$res['fecha_creacion'].'</td>
				<td>'.$res['tipo_documento'].'</td>
				<td>'.$res['pago'].'</td>
				<td>'.$res['entrega'].'</td>
				<td>S/. '.($res['total'] - $res['detraccion_p']).'</td>
				<td>'.$res['person'].'</td>
				<td>S/. '.$res['a_cuenta'].'</td></tr>';
			}
		}
		$html = $html.'
                    </table>
                </body>
		</html>';
	}elseif ($filtro == 'extra' && $tabla == 'ventas') {
		if($_GET['extra'] == 'ninguno'){
			$html = '<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title>Document</title>
				
				<link href="https://www.softluttion.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
			</head>
			<body>
				<img src="'.$_SERVER['DOCUMENT_ROOT'].'/sivecsol/img/logo.png" width="75">
				<h3 style="font-weight: bold; text-align: center;">Reporte de Ventas</h3>
				<table class="table table-bordered">
					<tr>
						<th>Código</th>	
						<th>Fecha</th>
						<th>Tipo</th>
						<th>Pago</th>
						<th>Entrega</th>
						<th>Total</th>
						<th>Cliente</th>
						<th>Adeuda</th>
		          	</tr>';
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id  ORDER BY vc.fecha_creacion DESC");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				//$values[] = $res;
				if ($res['a_cuenta'] > floatval('0.00')) {
					$html = $html . '<tr class="danger">
					<td>'.$res['codigo_venta'].'</td>
					<td>'.$res['fecha_creacion'].'</td>
					<td>'.$res['tipo_documento'].'</td>
					<td>'.$res['pago'].'</td>
					<td>'.$res['entrega'].'</td>
					<td>S/. '.($res['total'] - $res['detraccion_p']).'</td>
					<td>'.$res['person'].'</td>
					<td>S/. '.$res['a_cuenta'].'</td></tr>';
				}else{
					$html = $html . '<tr>
					<td>'.$res['codigo_venta'].'</td>
					<td>'.$res['fecha_creacion'].'</td>
					<td>'.$res['tipo_documento'].'</td>
					<td>'.$res['pago'].'</td>
					<td>'.$res['entrega'].'</td>
					<td>S/. '.($res['total'] - $res['detraccion_p']).'</td>
					<td>'.$res['person'].'</td>
					<td>S/. '.$res['a_cuenta'].'</td></tr>';
				}
			}
			$html = $html.'
	                    </table>
	                </body>
			</html>';
		}elseif ($_GET['extra'] == 'pago') {
			$dd = "";
			switch ($_GET['codigo']) {
				case 2:
					$pp = "Canceladas";
					break;
				case 3:
					$pp = "de Pago a Cuenta";
					break;
				case 4:
					$pp = "Al Crédito";
					break;
			}
			$html = '<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title>Document</title>
				
				<link href="https://www.softluttion.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
			</head>
			<body>
				<img src="'.$_SERVER['DOCUMENT_ROOT'].'/sivecsol/img/logo.png">
				<h3 style="font-weight: bold; text-align: center;">Reporte de Ventas '.$pp.'</h3>
				<table class="table table-bordered" border="1">
					<tr>
						<th>Código</th>	
						<th>Fecha</th>
						<th>Tipo</th>
						<th>Pago</th>
						<th>Entrega</th>
						<th>Total</th>
						<th>Cliente</th>
						<th>Adeuda</th>
		          	</tr>';
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_creacion) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND p.id = :codigo ORDER BY vc.fecha_creacion DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				//$values[] = $res;
				if ($res['a_cuenta'] > floatval('0.00')) {
					$html = $html . '<tr class="danger">
					<td>'.$res['codigo_venta'].'</td>
					<td>'.$res['fecha_creacion'].'</td>
					<td>'.$res['tipo_documento'].'</td>
					<td>'.$res['pago'].'</td>
					<td>'.$res['entrega'].'</td>
					<td>S/. '.$res['total'].'</td>
					<td>'.$res['person'].'</td>
					<td>S/. '.$res['a_cuenta'].'</td></tr>';
				}else{
					$html = $html . '<tr>
					<td>'.$res['codigo_venta'].'</td>
					<td>'.$res['fecha_creacion'].'</td>
					<td>'.$res['tipo_documento'].'</td>
					<td>'.$res['pago'].'</td>
					<td>'.$res['entrega'].'</td>
					<td>S/. '.$res['total'].'</td>
					<td>'.$res['person'].'</td>
					<td>S/. '.$res['a_cuenta'].'</td></tr>';
				}
			}
			$html = $html.'
	                    </table>
	                </body>
			</html>';
		}elseif ($_GET['extra'] == 'entrega') {
			$dd = "";
			switch ($_GET['codigo']) {
				case 2:
					$pp = "Pendientes";
					break;
				case 1:
					$pp = "Entregadas";
					break;
			}
			$html = '<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title>Document</title>
				
				<link href="https://www.softluttion.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
			</head>
			<body>
				<img src="'.$_SERVER['DOCUMENT_ROOT'].'/sivecsol/img/logo.png">
				<h3 style="font-weight: bold; text-align: center;">Reporte de Ventas '.$pp.'</h3>
				<table class="table table-bordered">
					<tr>
						<th>Código</th>	
						<th>Fecha</th>
						<th>Tipo</th>
						<th>Pago</th>
						<th>Entrega</th>
						<th>Total</th>
						<th>Cliente</th>
						<th>Adeuda</th>
		          	</tr>';
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_creacion) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND d.id = :codigo ORDER BY vc.fecha_creacion DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				//$values[] = $res;
				if ($res['a_cuenta'] > floatval('0.00')) {
					$html = $html . '<tr class="danger">
					<td>'.$res['codigo_venta'].'</td>
					<td>'.$res['fecha_creacion'].'</td>
					<td>'.$res['tipo_documento'].'</td>
					<td>'.$res['pago'].'</td>
					<td>'.$res['entrega'].'</td>
					<td>S/. '.$res['total'].'</td>
					<td>'.$res['person'].'</td>
					<td>S/. '.$res['a_cuenta'].'</td></tr>';
				}else{
					$html = $html . '<tr>
					<td>'.$res['codigo_venta'].'</td>
					<td>'.$res['fecha_creacion'].'</td>
					<td>'.$res['tipo_documento'].'</td>
					<td>'.$res['pago'].'</td>
					<td>'.$res['entrega'].'</td>
					<td>S/. '.$res['total'].'</td>
					<td>'.$res['person'].'</td>
					<td>S/. '.$res['a_cuenta'].'</td></tr>';
				}
			}
			$html = $html.'
	                    </table>
	                </body>
			</html>';
		}elseif ($_GET['extra'] == 'cliente') {
			$html = '<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title>Document</title>
				
				<link href="https://www.softluttion.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
			</head>
			<body>
				<img src="/home/peruvian/public_html/sivecsol/img/logo.png">
				<h3 style="font-weight: bold; text-align: center;">Reporte de Ventas según Cliente</h3>
				<table class="table table-bordered">
					<tr>
						<th>Código</th>	
						<th>Fecha</th>
						<th>Tipo</th>
						<th>Pago</th>
						<th>Entrega</th>
						<th>Total</th>
						<th>Cliente</th>
						<th>Adeuda</th>
		          	</tr>';
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_creacion) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo ORDER BY vc.fecha_creacion DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				//$values[] = $res;
				if ($res['a_cuenta'] > floatval('0.00')) {
					$html = $html . '<tr class="danger">
					<td>'.$res['codigo_venta'].'</td>
					<td>'.$res['fecha_creacion'].'</td>
					<td>'.$res['tipo_documento'].'</td>
					<td>'.$res['pago'].'</td>
					<td>'.$res['entrega'].'</td>
					<td>S/. '.$res['total'].'</td>
					<td>'.$res['person'].'</td>
					<td>S/. '.$res['a_cuenta'].'</td></tr>';
				}else{
					$html = $html . '<tr>
					<td>'.$res['codigo_venta'].'</td>
					<td>'.$res['fecha_creacion'].'</td>
					<td>'.$res['tipo_documento'].'</td>
					<td>'.$res['pago'].'</td>
					<td>'.$res['entrega'].'</td>
					<td>S/. '.$res['total'].'</td>
					<td>'.$res['person'].'</td>
					<td>S/. '.$res['a_cuenta'].'</td></tr>';
				}
			}
			$html = $html.'
	                    </table>
	                </body>
			</html>';
		}
	}

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
