<?php
	ini_set('max_execution_time', 300);
	set_time_limit(300);
	$filtro = $_GET['filtro'];
	$tabla = $_GET['tabla'];
	if($filtro == 'fecha' && $tabla == 'compras'){
		$desde = $_GET['desde'];
		$hasta = $_GET['hasta'];
		
		include('env.php');
		$query = $mbd->prepare("SELECT id_proveedor as person_id, proveedor as cliente, total, codigo, DATE(fecha_creacion) as fecha_creacion FROM compras WHERE DATE(fecha_creacion) BETWEEN :desde AND :hasta ORDER BY fecha_creacion DESC");
		$query->bindParam(':desde', $desde);
		$query->bindParam(':hasta', $hasta);
		$query->execute();
		$arrayName = array();
		$cliente = "";
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$id = $res['person_id'];
			if($id == 0){
				$cliente = $res['cliente'];
			}else{
				$pp = $mbd->prepare("SELECT * FROM person WHERE id = :id");
				
				$pp->bindParam(':id', $id);
				$pp->execute();
				$p = $pp->fetch(PDO::FETCH_ASSOC);
				$cliente = $p['name'];
			}
			$values[] = array(
				"Codigo" => $res['codigo'],
				"Fecha de Compra" => $res['fecha_creacion'],
				"Proveedor" => $cliente,
				"Total" => $res['total']
			);
		}
		if(!empty($values)) {
			$filename = "reporte_" . date('d-m-Y') . ".xls";
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
	}elseif ($filtro == 'ninguno' && $tabla == 'compras') {
				
		include('env.php');
		$query = $mbd->prepare("SELECT id_proveedor as person_id, proveedor as cliente, total, codigo, DATE(fecha_creacion) as fecha_creacion FROM compras ORDER BY fecha_creacion DESC");
		$query->execute();
		$values = array();
		$cliente = "";
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$id = $res['person_id'];
			if($id == 0){
				$cliente = $res['cliente'];
			}else{
				$pp = $mbd->prepare("SELECT * FROM person WHERE id = :id");
				
				$pp->bindParam(':id', $id);
				$pp->execute();
				$p = $pp->fetch(PDO::FETCH_ASSOC);
				$cliente = $p['name'];
			}

			$values[] = array(
				"Codigo" => $res['codigo'],
				"Fecha de Compra" => $res['fecha_creacion'],
				"Proveedor" => $cliente,
				"Total" => $res['total']
			);
		}

		/*while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

			$values[] = array(
				"Código" => $res['codigo_venta'],
				"Fecha" => $res['fecha_creacion'],
				"Tipo" => $res['tipo_documento'],
				"Pago" => $res['pago'],
				"Entrega" => $res['entrega'],
				"Total" => 'S/. '.($res['total'] - $res['detraccion_p']),
				"Cliente" => $res['person'],
				"Adeuda" => 'S/. '.$res['a_cuenta']
			);
		}*/

		if(!empty($values)) {
			$filename = "reporte_" . date('d-m-Y') . ".xls";
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
	}elseif ($filtro == 'fecha' && $tabla == 'ventas') {
		//echo "GASD";
		$desde = $_GET['desde'];
		$hasta = $_GET['hasta'];

		$tipos_pago = $_GET['tipos_pago'];
		$tipos_documento = $_GET['tipos_documento'];
		$combo_cliente = $_GET['combo_cliente'];

		include('env.php');

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


		$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id ".$where." ORDER BY fecha_creacion DESC");

		//echo "SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id ".$where." ORDER BY fecha_creacion DESC";

		/*$query->bindParam(':desde', $desde);
		$query->bindParam(':hasta', $hasta);*/
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

			$values[] = array(
				"Codigo" => $res['codigo_venta'],
				"Fecha" => $res['fecha_creacion'],
				"Tipo" => $res['tipo_documento'],
				"Pago" => $res['pago'],
				"Entrega" => $res['entrega'],
				"Total" => 'S/. '.($res['total'] - $res['detraccion_p']),
				"Cliente" => $res['person'],
				"Adeuda" => 'S/. '.$res['a_cuenta']
			);
		}

		if(!empty($values)) {
			$filename = "reporte_" . date('d-m-Y') . ".xls";
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
	}elseif ($filtro == 'extra' && $tabla == 'ventas') {
		if($_GET['extra'] == 'ninguno'){
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id  ORDER BY vc.fecha_creacion DESC");
			$query->execute();
			$values = array();

			$mostrar_columnas = false;

			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

				$values[] = array(
					"Codigo" => $res['codigo_venta'],
					"Fecha" => $res['fecha_creacion'],
					"Tipo" => $res['tipo_documento'],
					"Pago" => $res['pago'],
					"Entrega" => $res['entrega'],
					"Total" => 'S/. '.($res['total'] - $res['detraccion_p']),
					"Cliente" => $res['person'],
					"Adeuda" => 'S/. '.$res['a_cuenta']
				);
			}

			if(!empty($values)) {
				$filename = "reporte_" . date('d-m-Y') . ".xls";
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
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_creacion) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND p.id = :codigo ORDER BY vc.fecha_creacion DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

				$values[] = array(
					"Codigo" => $res['codigo_venta'],
					"Fecha" => $res['fecha_creacion'],
					"Tipo" => $res['tipo_documento'],
					"Pago" => $res['pago'],
					"Entrega" => $res['entrega'],
					"Total" => 'S/. '.($res['total'] - $res['detraccion_p']),
					"Cliente" => $res['person'],
					"Adeuda" => 'S/. '.$res['a_cuenta']
				);
			}

			if(!empty($values)) {
				$filename = "reporte_" . date('d-m-Y') . ".xls";
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
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_creacion) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND d.id = :codigo ORDER BY vc.fecha_creacion DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

				$values[] = array(
					"Codigo" => $res['codigo_venta'],
					"Fecha" => $res['fecha_creacion'],
					"Tipo" => $res['tipo_documento'],
					"Pago" => $res['pago'],
					"Entrega" => $res['entrega'],
					"Total" => 'S/. '.($res['total'] - $res['detraccion_p']),
					"Cliente" => $res['person'],
					"Adeuda" => 'S/. '.$res['a_cuenta']
				);
			}

			if(!empty($values)) {
				$filename = "reporte_" . date('d-m-Y') . ".xls";
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
		}elseif ($_GET['extra'] == 'cliente') {
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_creacion) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo ORDER BY vc.fecha_creacion DESC");
			$query->bindParam(':codigo', $_GET['codigo']);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

				$values[] = array(
					"Codigo" => $res['codigo_venta'],
					"Fecha" => $res['fecha_creacion'],
					"Tipo" => $res['tipo_documento'],
					"Pago" => $res['pago'],
					"Entrega" => $res['entrega'],
					"Total" => 'S/. '.($res['total'] - $res['detraccion_p']),
					"Cliente" => $res['person'],
					"Adeuda" => 'S/. '.$res['a_cuenta']
				);
			}

			if(!empty($values)) {
				$filename = "reporte_" . date('d-m-Y') . ".xls";
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
		}
	}

?>