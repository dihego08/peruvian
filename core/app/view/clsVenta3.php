<?php 
	class clsVenta{
		function tipos_documento(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM kind_doc ORDER BY id DESC");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $values 
			);
			return json_encode($result);
		}
		function tipos_pago(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM p");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $values 
			);
			return json_encode($result);
		}
		function tipos_entrega(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM d");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $values 
			);
			return json_encode($result);
		}
		function forma_pago(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM f");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $values 
			);
			return json_encode($result);
		}
		function insertar_venta($tipo_documento, $almacen, $lista_clientes, $tipos_pago, $tipos_entrega, $forma_pago, $descuento, $subtotal, $descripcion, $igv, $total, $detraccion, $ids, $precios, $pagado, $a_cuenta, $unidades, $cantidades, $cod_venta, $guia, $fecha_emision, $detraccion_p, $igv_p, $p_b){
			//$now = new DateTime();
			$codigo_venta = "";
			$ids = explode(',', $ids);
			$precios = explode(',', $precios);
			$unidades = explode(',', $unidades);
			$cantidades = explode(',', $cantidades);
			$p_b = explode(',', $p_b);
			include('env.php');
			if($pagado == floatval('0.00')){
				$pagado = $total;
			}else{

			}
			switch ($tipo_documento) {
				case 1:
					$codigo_venta = $cod_venta; //'B001-'.str_pad($cod_venta, 9, "0", STR_PAD_LEFT);
					break;
				case 2:
					$codigo_venta = $cod_venta; //'F001-'.str_pad($cod_venta, 9, "0", STR_PAD_LEFT);
					break;
				case 3:
					$codigo_venta = $cod_venta; //'NP001-'.str_pad($cod_venta, 9, "0", STR_PAD_LEFT);
					break;
			}
			if ($fecha_emision == "") {
				$fecha_emision = date('Y-m-d');
			}
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				$query = $mbd->prepare("INSERT INTO ventas_cabecera(`codigo_venta`, `tipo_documento`, `id_person`, `id_forma_pago`, `id_estado_pago`, `id_estado_entrega`, `almacen`, `descuento`, `descripcion`, `detraccion`, `total`, `igv`, `subtotal`, `pagado`, `a_cuenta`, `guia`, `fecha_emision`, `detraccion_p`, `igv_p`) VALUES(:codigo_venta, :tipo_documento, :lista_clientes, :forma_pago, :tipos_pago, :tipos_entrega, :almacen, :descuento, :descripcion, :detraccion, :total, :igv, :subtotal, :pagado, :a_cuenta, :guia, :fecha_emision, :detraccion_p, :igv_p)");
				$query->bindParam(':codigo_venta', $codigo_venta);
				$query->bindParam(':tipo_documento', $tipo_documento);
				$query->bindParam(':lista_clientes', $lista_clientes);
				$query->bindParam(':forma_pago', $forma_pago);
				$query->bindParam(':tipos_pago', $tipos_pago);
				$query->bindParam(':tipos_entrega', $tipos_entrega);

				$query->bindParam(':detraccion_p', $detraccion_p);
				$query->bindParam(':igv_p', $igv_p);

				$query->bindParam(':almacen', $almacen);
				$query->bindParam(':descuento', $descuento);
				$query->bindParam(':descripcion', $descripcion);
				$query->bindParam(':detraccion', $detraccion);
				$query->bindParam(':total', $total);
				$query->bindParam(':igv', $igv);
				$query->bindParam(':subtotal', $subtotal);
				$query->bindParam(':pagado', $pagado);
				$query->bindParam(':a_cuenta', $a_cuenta);
				$query->bindParam(':guia', $guia);
				$query->bindParam(':fecha_emision', $fecha_emision);
				$query->execute();

				$pago = new clsVenta;
				$pago->insertar_pago($codigo_venta, $lista_clientes, $total, $pagado, $a_cuenta, $fecha_emision);

				for ($i = 1; $i < count($ids); $i++) { 
					$query2 = $mbd->prepare("INSERT INTO ventas_detalle(codigo_venta_cabecera, id_producto, cantidad, codigo_unidad, precio_unitario, precio_bordado) VALUES(:codigo_venta_cabecera, :id_producto, :cantidad, :codigo_unidad, :precio_unitario, :p_b)");
					$query2->bindParam(':codigo_venta_cabecera', $codigo_venta); 
					$query2->bindParam(':id_producto', $ids[$i]);
					$query2->bindParam(':p_b', $p_b[$i]);
					$query2->bindParam(':cantidad', $cantidades[$i]);
					$query2->bindParam(':codigo_unidad', $unidades[$i]);
					$query2->bindParam(':precio_unitario', $precios[$i]);
					$query2->execute();
				}

				$mbd->commit();
				$result = array(
	            	'Result' => 'OK'
	            );
	            return json_encode($result);
			}catch (Exception $e) {
			  	$mbd->rollBack();
			  	$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}
		}
		function insertar_pago($codigo_venta, $id_person, $total, $pago, $adeuda, $fecha_emision){
			//echo $codigo_venta." - ". $id_person." - ".  $total." - ".  $pago." - ".  $adeuda;
			if($total === $pago){
				//echo "IS TrU";
				$adeuda = 0;
			}else{
				//echo "IS NO";
			}
			//echo $adeuda;
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				$query = $mbd->prepare("INSERT INTO pagos(codigo_venta, id_person, total, pago, deuda, fecha_creacion) VALUES(:codigo_venta, :id_person, :total, :pago, :adeuda, :fecha_creacion)");
				$query->bindParam(':codigo_venta', $codigo_venta);
				$query->bindParam(':id_person', $id_person);
				$query->bindParam(':total', $total);
				$query->bindParam(':pago', $pago);
				$query->bindParam(':adeuda', $adeuda);
				$query->bindParam(':fecha_creacion', $fecha_emision);
				$query->execute();
				$mbd->commit();
				$result = array(
	            	'Result' => 'OK'
	            );
	            return json_encode($result);
			}catch (Exception $e) {
			  	$mbd->rollBack();
			  	$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}
		}
		function actualizar_pagos($codigo_venta, $pago, $deuda){
			include('env.php');
			if($total === $pago){
				//echo "IS TrU";
				$adeuda = 0;
			}else{
				//echo "IS NO";
			}
			$query = $mbd->prepare("UPDATE pagos SET pago = :pago, deuda = :deuda WHERE codigo_venta = :codigo_venta");
			$query->bindParam(':pago', $pago);
			$query->bindParam(':deuda', $deuda);
			$query->bindParam(':codigo_venta', $codigo_venta);
			$query->execute();
		}
		function lista_ventas_s($filtro, $codigo){

			include('env.php');
			if($filtro == 'ninguno'){
				$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_creacion DESC");
				$query->execute();
				$values = array();
				while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
					$values[] = $res;
				}
				$result = array(
					'Result' => 'OK',
					'Records' => $values 
				);
				return json_encode($result);
			}else{
				if ($filtro == 'pago') {
					$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND p.id = :codigo AND vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_creacion DESC");
					$query->bindParam(':codigo', $codigo);
					$query->execute();
					$values = array();
					while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
						$values[] = $res;
					}
					$result = array(
						'Result' => 'OK',
						'Records' => $values 
					);
					return json_encode($result);
				}else{
					if ($filtro == 'entrega') {
						$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND d.id = :codigo AND vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_creacion DESC");
						$query->bindParam(':codigo', $codigo);
						$query->execute();
						$values = array();
						while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
							$values[] = $res;
						}
						$result = array(
							'Result' => 'OK',
							'Records' => $values 
						);
						return json_encode($result);
					}elseif ($filtro == 'cliente') {
						$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo AND vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_creacion DESC");
						$query->bindParam(':codigo', $codigo);
						$query->execute();
						$values = array();
						while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
							$values[] = $res;
						}
						$result = array(
							'Result' => 'OK',
							'Records' => $values 
						);
						return json_encode($result);
					}
				}
			}			
		}
		function lista_ventas($filtro, $codigo, $fecha){
			$anio = "";
			$mes = "";
			if($fecha == ""){
				$fecha_ = date("Y-m-d");
			}else{
				$fecha = explode('-', $fecha);
				$anio = $fecha[1];
				$mes = $fecha[0];
			}
			include('env.php');
			if($filtro == 'ninguno' && $fecha == ""){
				$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id  ORDER BY vc.fecha_creacion DESC");
				$query->execute();
				$values = array();
				while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
					$values[] = $res;
				}
				$result = array(
					'Result' => 'OK',
					'Records' => $values 
				);
				return json_encode($result);
			}elseif ($filtro == "ninguno" && $fecha != "") {
				$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio  ORDER BY vc.fecha_creacion DESC");
				$query->bindParam(':mes', $mes);
				$query->bindParam(':anio', $anio);
				$query->execute();
				$values = array();
				while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
					$values[] = $res;
				}
				$result = array(
					'Result' => 'OK',
					'Records' => $values 
				);
				return json_encode($result);
			}elseif ($filtro == 'pago' && $fecha == "") {
				$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND p.id = :codigo  ORDER BY vc.fecha_creacion DESC");
				$query->bindParam(':codigo', $codigo);
				$query->execute();
				$values = array();
				while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
					$values[] = $res;
				}
				$result = array(
					'Result' => 'OK',
					'Records' => $values 
				);
				return json_encode($result);
			}elseif ($filtro == 'entrega') {
				$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND d.id = :codigo  ORDER BY vc.fecha_creacion DESC");
				$query->bindParam(':codigo', $codigo);
				$query->execute();
				$values = array();
				while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
					$values[] = $res;
				}
				$result = array(
					'Result' => 'OK',
					'Records' => $values 
				);
				return json_encode($result);
			}elseif ($filtro == 'cliente' && $fecha == "") {
				$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo  ORDER BY vc.fecha_creacion DESC");
				$query->bindParam(':codigo', $codigo);
				$query->execute();
				$values = array();
				while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
					$values[] = $res;
				}
				$result = array(
					'Result' => 'OK',
					'Records' => $values 
				);
				return json_encode($result);
			}elseif ($filtro == 'cliente' && $fecha != "") {
				$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio  ORDER BY vc.fecha_creacion DESC");
				$query->bindParam(':codigo', $codigo);
				$query->bindParam(':mes', $mes);
				$query->bindParam(':anio', $anio);
				$query->execute();
				$values = array();
				while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
					$values[] = $res;
				}
				$result = array(
					'Result' => 'OK',
					'Records' => $values 
				);
				return json_encode($result);
			}		
		}
		function lista_detalle($codigo){
			include('env.php');
			$query = $mbd->prepare("SELECT p.name, vd.* FROM ventas_detalle as vd, product as p WHERE p.id = vd.id_producto AND vd.codigo_venta_cabecera = :codigo");
			$query->bindParam(':codigo', $codigo);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$query = $mbd->prepare("SELECT * FROM ventas_cabecera WHERE codigo_venta = :codigo");
			$query->bindParam(':codigo', $codigo);
			$query->execute();
			$venta = $query->fetch(PDO::FETCH_ASSOC);
			$result = array(
				'Result' => 'OK',
				'Records' => $values,
				'venta' => $venta
			);
			return json_encode($result);
		}
		function eliminar_venta($codigo){
			//echo $codigo;
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("DELETE FROM ventas_detalle WHERE codigo_venta_cabecera = :codigo");
				$query->bindParam(':codigo', $codigo);
				$query->execute();

				$query = $mbd->prepare("DELETE FROM pagos WHERE codigo_venta = :codigo");
				$query->bindParam(':codigo', $codigo);
				$query->execute();

				$query2 = $mbd->prepare("DELETE FROM ventas_cabecera WHERE codigo_venta = :codigo");
				$query2->bindParam(':codigo', $codigo);
				$query2->execute();

				$mbd->commit();
				$result = array(
	            	'Result' => 'OK'
	            );
	            return json_encode($result);
			}catch (Exception $e) {
			  	$mbd->rollBack();
			  	$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}
		}
		function actualizar_pago($codigo, $monto_pagado, $monto_adeuda){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

			  	$query = $mbd->prepare("UPDATE ventas_cabecera SET pagado = pagado + :monto_pagado, a_cuenta = :monto_adeuda WHERE codigo_venta = :codigo");
			  	$query->bindParam(':monto_pagado', $monto_pagado);
			  	$query->bindParam(':monto_adeuda', $monto_adeuda);
			  	$query->bindParam(':codigo', $codigo);
			  	$query->execute();

			  	$p = $mbd->prepare("SELECT * FROM ventas_cabecera WHERE codigo_venta = :codigo");
			  	$p->bindParam(':codigo', $codigo);
			  	$p->execute();
			  	$pagos = $p->fetch(PDO::FETCH_ASSOC);

			  	$pago = new clsVenta;
				$pago->insertar_pago($codigo, $pagos['id_person'], $pagos['total'], $monto_pagado, $monto_adeuda);

			  	$mbd->commit();
				$result = array(
	            	'Result' => 'OK'
	            );
	            return json_encode($result);
			}catch (Exception $e) {
			  	$mbd->rollBack();
			  	$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}
		}
		function historial_pago($id_person, $codigo_venta){
			include('env.php');
			$query = $mbd->prepare("SELECT id, codigo_venta, id_person, total, pago, deuda, fecha_creacion as fc, DATE(fecha_creacion) as fecha_creacion FROM pagos WHERE codigo_venta = :codigo_venta AND id_person = :id_person ORDER BY fc ASC");
			$query->bindParam(':codigo_venta', $codigo_venta);
			$query->bindParam(':id_person', $id_person);
			$query->execute();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $values 
			);
			return json_encode($result);
		}
		function buscar_por_fecha($desde, $hasta){
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND DATE(vc.fecha_emision) BETWEEN :desde AND :hasta ORDER BY fecha_creacion DESC");
			$query->bindParam(':desde', $desde);
			$query->bindParam(':hasta', $hasta);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $values 
			);
			return json_encode($result);
		}
		function buscar_por_fecha_s($desde, $hasta){
			include('env.php');
			$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND DATE(vc.fecha_emision) BETWEEN :desde AND :hasta AND  vc.tipo_documento IN (1, 2) ORDER BY fecha_creacion DESC");
			$query->bindParam(':desde', $desde);
			$query->bindParam(':hasta', $hasta);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $values 
			);
			return json_encode($result);
		}
		function tipo_usuario(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM cargos");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $values 
			);
			return json_encode($result);
		}
		function anular($codigo){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

			  	$query_u = $mbd->prepare("DELETE FROM pagos WHERE codigo_venta = :codigo");
			  	$query_u->bindParam(':codigo', $codigo);
				$query_u->execute();

			  	$query = $mbd->prepare("UPDATE ventas_cabecera SET id_estado_entrega = 4, descuento = 0, detraccion_p = 0, igv_p = 0, subtotal = 0, pagado = 0, a_cuenta = 0, tercera = 0, val_pagar = 0, total = 0, igv = 0 WHERE codigo_venta = :codigo");
				$query->bindParam(':codigo', $codigo);
				$query->execute();

			  	$mbd->commit();
				$result = array(
	            	'Result' => 'OK'
	            );
	            return json_encode($result);
			}catch (Exception $e) {
			  	$mbd->rollBack();
			  	$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}
		}
		function actualizar($cod_n, $guia, $codigo, $fecha_pago, $entidad, $fecha_det){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

			  	$query = $mbd->prepare("UPDATE ventas_cabecera SET guia = :guia, fecha_pago = :fecha_pago, entidad = :entidad, fecha_detraccion = :fecha_detraccion, codigo_venta = :cod_n WHERE codigo_venta = :codigo");
				$query->bindParam(':guia', $guia);
				$query->bindParam(':cod_n', $cod_n);
				$query->bindParam(':codigo', $codigo);
				$query->bindParam(':fecha_pago', $fecha_pago);
				$query->bindParam(':entidad', $entidad);
				$query->bindParam(':fecha_detraccion', $fecha_det);
				$query->execute();

			  	$mbd->commit();
				$result = array(
	            	'Result' => 'OK'
	            );
	            return json_encode($result);
			}catch (Exception $e) {
			  	$mbd->rollBack();
			  	$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}			
		}
	}
?>