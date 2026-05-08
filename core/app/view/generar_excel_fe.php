<?php
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
			//$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio UNION SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, vc.fecha_creacion as fc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio ORDER BY fc DESC");
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio ORDER BY fc DESC");
			$query->bindParam(':mes', $mes);
			$query->bindParam(':anio', $anio);
			$query->execute();
		}elseif ($_GET['filtro'] == 'pago' && $_GET['fecha'] == "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND p.id = :codigo  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
		}elseif ($_GET['filtro'] == 'entrega') {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, pe.no as ruc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND d.id = :codigo  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
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
  	
  	//$values = array();
	$t_subtotal = 0;
	$t_igv = 0;
	$t_detraccion = 0;
	$t_igv_pagar = 0;
	$t_total = 0;
	
	$values = array();
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
		
		$values[] = array(
		    "Factura" => $res['codigo_venta'],
			"Fecha" => $res['fecha_creacion'],
			"Tipo" => $res['tipo_documento'],
			"RUC" => $res['ruc'],
			"Cliente" => $res['person'],
			"Subtotal" => 'S/ '.$res['subtotal'],
			"IGV" => 'S/ '.$res['igv'],
			"Detraccion" => 'S/ '.$res['detraccion_p'],
			"IGV Pagar" => 'S/ '.$igv_pagar,
			"Total" => 'S/ '.$res['total'],
	    );
		
		/*$html = $html . '<tr>
			<td>'.$res['codigo_venta'].'</td>
			<td>'.$res['fecha_creacion'].'</td>
			<td>'.$res['tipo_documento'].'</td>
			<td>S/ '.$res['subtotal'].'</td>
			<td>S/ '.$res['igv'].'</td>
			<td>S/ '.($res['detraccion_p']).'</td>
			<td>S/ '.$igv_pagar.'</td>
			<td>S/ '.$res['total'].'</td>
			<td>'.$res['person'].'</td>
		</tr>';*/
	}
	
	$values[] = array(
	    "Factura" => '',
		"Fecha" => '',
		"Tipo" => '',
		"RUC" => '',
		"Cliente" => '',
		"Subtotal" => 'S/ '.number_format($t_subtotal, 2),
		"IGV" => 'S/ '.number_format($t_igv, 2),
		"Detraccion" => 'S/ '.number_format($t_detraccion, 2),
		"IGV Pagar" => 'S/ '.number_format($t_igv_pagar, 2),
		"Total" => 'S/ '.number_format($t_total, 2),
    );
	
	if(!empty($values)) {
		$filename = "reporte_fe" . date('d-m-Y') . ".xls";
		header("Content-Type: application/vnd.ms-excel charset=UTF-8");
		header("Content-Disposition: attachment; filename=".$filename);
		$mostrar_columnas = false;
		foreach($values as $libro) {
			if(!$mostrar_columnas) {
				echo implode("\t", array_keys($libro)) . "\n";
				$mostrar_columnas = true;
			}
			echo implode("\t", array_values($libro)) . "\n";
		}
	}else{
		echo 'No hay datos a exportar';

	}
?>