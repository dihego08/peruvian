<?php
date_default_timezone_set('America/Lima');
class clsCotizacion
{
	function busqueda_productos($cadena)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM product WHERE name LIKE '%" . $cadena . "%' OR description LIKE '%" . $cadena . "%' OR code = :cadena");
		$query->bindParam(':cadena', $cadena);
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
	function busqueda_productos_2($cadena)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM product WHERE name LIKE '%" . $cadena . "%' OR description LIKE '%" . $cadena . "%' OR code = :cadena");
		$query->bindParam(':cadena', $cadena);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$precios = array();
			$precios = array('precio_min' => $res['price_in'], 'precio_max' => $res['price_in_2']);
			$values[] = array(
				'id' => $res['id'],
				'code' => $res['code'],
				'name' => $res['name'],
				'unit' => $res['unit'],
				'price_in' => $res['price_in'],
				'precios' => $precios,
				'prebor_out' => $res['prebor_out']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
	}
	function detalle_para_cotizacion($codigos)
	{
		$codigos = implode(",", $codigos);
		include('env.php');
		$sql = "SELECT * FROM product WHERE id IN ($codigos)";
		$query = $mbd->prepare($sql);
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
	function insertar_cotizacion($POST, $codigos, $entrega, $imagenes, $imagenes_b, $correlativo)
	{
		//echo $imagenes;
		$now = new DateTime();
		$imagenes = explode(',', $imagenes);
		$imagenes_b = explode(',', $imagenes_b);
		//echo $now->format('Y-m-d H:i:s');    // MySQL datetime format
		//echo $now->getTimestamp();  
		$codigo = $correlativo;

		include('env.php');
		$query_test = $mbd->query("SELECT NOW() as fecha_ahora, @@session.time_zone as tz");
		$test = $query_test->fetch(PDO::FETCH_ASSOC);
		//echo "DEBUG - Fecha MySQL: " . $test['fecha_ahora'] . " | TZ: " . $test['tz'];
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();
			$query = $mbd->prepare("INSERT INTO cotizacion(codigo, tiempo_entrega, obervacion, person_id, cliente, validez, forma_pago, tallas_especiales, servicios, asesor_comercial, asesor_celular, fecha_creacion) VALUES(:codigo, :entrega, :obervacion, :person_id, :cliente, :validez, :forma_pago, :tallas_especiales, :servicios, :asesor_comercial, :asesor_celular, now())");
			$query->bindParam(':codigo', $codigo);
			$query->bindParam(':entrega', $entrega);
			$query->bindParam(':obervacion', nl2br($POST['txt_observacion']));
			$query->bindParam(':person_id', $POST['c_cliente']);
			$query->bindParam(':cliente', $POST['x_cliente']);
			$query->bindParam(':validez', $POST['validez']);
			$query->bindParam(':forma_pago', $POST['forma_pago']);
			$query->bindParam(':tallas_especiales', $POST['tallas_especiales']);
			$query->bindParam(':servicios', nl2br($POST['servicios']));
			$query->bindParam(':asesor_comercial', $POST['asesor_comercial']);
			$query->bindParam(':asesor_celular', $POST['asesor_celular']);
			//$query->bindParam(':fecha_creacion', $now->format('Y-m-d H:i:s'));
			$query->execute();
			$total = 0;
			$subtotal = 0;
			$igv = 0;
			for ($i = 0; $i < count($codigos); $i++) {
				$query2 = $mbd->prepare("INSERT INTO cotizacion_detalle(codigo_cotizacion, id_producto, cantidad, imagen, costo, descripcion, imagen_2, nombre_producto) VALUES(:codigo_cotizacion, :id_producto, :cantidad, :imagen, :costo, :descripcion, :imagen_2, :nombre_producto)");
				if (empty($POST['cantidad_' . $codigos[$i]]) || is_null($POST['cantidad_' . $codigos[$i]])) {
					$POST['cantidad_' . $codigos[$i]] = 0;
				}
				$query2->bindParam(':codigo_cotizacion', $codigo);
				$query2->bindParam(':id_producto', $codigos[$i]);
				$query2->bindParam(':imagen', $imagenes[$i + 1]);
				$query2->bindParam(':imagen_2', $imagenes_b[$i + 1]);
				$query2->bindParam(':costo', $POST['costo_' . $codigos[$i]]);
				$query2->bindParam(':cantidad', $POST['cantidad_' . $codigos[$i]]);
				$query2->bindParam(':descripcion', nl2br($POST['descripcion_' . $codigos[$i]]));
				$query2->bindParam(':nombre_producto', $POST['producto_' . $codigos[$i]]);

				$query2->execute();
				$total = $total + ($POST['cantidad_' . $codigos[$i]] * $POST['costo_' . $codigos[$i]]);
			}
			if ($POST['igv'] == 'yes') {
				$igv = $total * 0.18;
				$subtotal = $total;
				$total = $total + $igv;
				$igv_incluye = 1;
			} else {
				$subtotal = $total;
				$igv_incluye = 0;
			}
			$query_u = $mbd->prepare("UPDATE cotizacion SET sub_total = :subtotal, total = :total, igv = :igv, igv_incluye = :igv_incluye WHERE codigo = :codigo");
			$query_u->bindParam(':codigo', $codigo);
			$query_u->bindParam(':igv', $igv);
			$query_u->bindParam(':total', $total);
			$query_u->bindParam(':subtotal', $subtotal);
			$query_u->bindParam(':igv_incluye', $igv_incluye);
			$query_u->execute();

			$query_correlativo = $mbd->prepare("UPDATE kind_doc SET numero = (numero + 1) WHERE id = 4");
			$query_correlativo->execute();

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);

			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function lista_cotizaciones()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT codigo, fecha_creacion, DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fec_cre, person_id, cliente, sub_total, igv, total FROM cotizacion ORDER BY fecha_creacion DESC");
		$query->execute();
		$arrayName = array();
		$cliente = "";
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$id = $res['person_id'];
			if ($id == 0) {
				$cliente = $res['cliente'];
			} else {
				$pp = $mbd->prepare("SELECT * FROM person WHERE id = :id");

				$pp->bindParam(':id', $id);
				$pp->execute();
				$p = $pp->fetch(PDO::FETCH_ASSOC);
				$cliente = $p['name'];
			}

			$img = $mbd->prepare("SELECT * FROM cotizacion_detalle WHERE codigo_cotizacion = :codigo_cotizacion");
			$img->bindParam(":codigo_cotizacion", $res['codigo']);
			$img->execute();

			$imagen = $img->fetch(PDO::FETCH_ASSOC);

			$arrayName[] = array(
				'codigo' => $res['codigo'],
				'fecha_creacion' => $res['fec_cre'],
				'name' => $cliente,
				'imagen' => $imagen['imagen'],
				'sub_total' => $res['sub_total'],
				'igv' => $res['igv'],
				'total' => $res['total']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $arrayName
		);
		return json_encode($result);
	}
	function lista_cotizaciones_cliente($cli)
	{
		/*include('env.php');
			$query = $mbd->prepare("SELECT codigo, DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fecha_creacion, person_id, cliente, sub_total, igv, total FROM cotizacion where person_id = :cliente_id  ORDER BY CAST(codigo as UNSIGNED) DESC");
			$query->bindParam(':cliente_id', $cli);
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
				$arrayName[] = array(
					'codigo' => $res['codigo'],
					'fecha_creacion' => $res['fecha_creacion'],
					'name' => $cliente,
					'sub_total' => $res['sub_total'],
					'igv' => $res['igv'],
					'total' => $res['total']
				);
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $arrayName
			);
			return json_encode($result);*/

		include('env.php');
		$query = $mbd->prepare("SELECT codigo, fecha_creacion, DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fec_cre, person_id, cliente, sub_total, igv, total FROM cotizacion where person_id = :cliente_id ORDER BY fecha_creacion DESC");
		$query->bindParam(':cliente_id', $cli);
		$query->execute();
		$arrayName = array();
		$cliente = "";
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$id = $res['person_id'];
			if ($id == 0) {
				$cliente = $res['cliente'];
			} else {
				$pp = $mbd->prepare("SELECT * FROM person WHERE id = :id");

				$pp->bindParam(':id', $id);
				$pp->execute();
				$p = $pp->fetch(PDO::FETCH_ASSOC);
				$cliente = $p['name'];
			}

			$img = $mbd->prepare("SELECT * FROM cotizacion_detalle WHERE codigo_cotizacion = :codigo_cotizacion");
			$img->bindParam(":codigo_cotizacion", $res['codigo']);
			$img->execute();

			$imagen = $img->fetch(PDO::FETCH_ASSOC);

			$arrayName[] = array(
				'codigo' => $res['codigo'],
				'fecha_creacion' => $res['fec_cre'],
				'name' => $cliente,
				'imagen' => $imagen['imagen'],
				'sub_total' => $res['sub_total'],
				'igv' => $res['igv'],
				'total' => $res['total']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $arrayName
		);
		return json_encode($result);
	}
	function detalle_cotizacion($codigo)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT p.name, cd.* FROM product as p, cotizacion_detalle as cd WHERE cd.id_producto = p.id AND cd.codigo_cotizacion = :codigo");
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
	function eliminar_cotizacion($codigo)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();
			$query = $mbd->prepare("DELETE FROM cotizacion_detalle WHERE codigo_cotizacion = :codigo");
			$query->bindParam(':codigo', $codigo);
			$query->execute();

			$query2 = $mbd->prepare("DELETE FROM cotizacion WHERE codigo = :codigo");
			$query2->bindParam(':codigo', $codigo);
			$query2->execute();

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function busqueda_productos_barcode($barcode)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM product WHERE code = :barcode");
		$query->bindParam(':barcode', $barcode);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$precios = array();
			$precios = array('precio_min' => $res['price_in'], 'precio_max' => $res['price_in_2']);
			$values[] = array(
				'id' => $res['id'],
				'code' => $res['code'],
				'name' => $res['name'],
				'unit' => $res['unit'],
				'price_in' => $res['price_in'],
				'precios' => $precios,
				'prebor_out' => $res['prebor_out']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
	}
	function pdf_cotizacion($codigo) {}

	function correlativo()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT max(numero) as nro FROM kind_doc where id = 4");
		$query->execute();
		$value = $query->fetch(PDO::FETCH_ASSOC);
		return $value['nro'];
	}

	function actualizar_correlativo($correlativo)
	{
		include('env.php');
		$query = $mbd->prepare("UPDATE kind_doc SET numero = :numero WHERE id = 4");
		$query->bindParam(':numero', $correlativo);
		$query->execute();
	}
}
