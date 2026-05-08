<?php
class clsInsumos
{
	function actualizar_compra($POST)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE compras SET fecha_creacion = :fecha_creacion, id_proveedor = :id_proveedor, serie = :serie, numeracion = :numeracion, igv = :igv, gravado = :gravado, exonerado = :exonerado, total = :total, tipo_documento = :tipo_documento, id_forma_pago = :id_forma_pago WHERE id = :id_compra");

			$query->bindParam(':tipo_documento', $POST['tipo_documento']);
			$query->bindParam(':id_forma_pago', $POST['id_forma_pago']);
			$query->bindParam(':serie', $POST['serie']);
			$query->bindParam(':numeracion', $POST['numeracion']);
			$query->bindParam(':total', $POST['total']);
			$query->bindParam(':igv', $POST['igv']);
			$query->bindParam(':gravado', $POST['gravado']);
			$query->bindParam(':exonerado', $POST['exonerado']);
			$query->bindParam(':id_compra', $POST['id_compra']);
			$query->bindParam(':fecha_creacion', date("Y-m-d", strtotime($POST['fecha_creacion'])));
			$query->bindParam(':id_proveedor', $POST['id_proveedor']);
			$query->execute();

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
	function actualizar_detalle($POST)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE compras_detalle SET precio = :precio, cantidad = :cantidad, total= :total WHERE id = :id");
			$query->bindParam(':precio', $POST['precio']);
			$query->bindParam(':cantidad', $POST['cantidad']);
			$query->bindParam(':total', $POST['total']);
			$query->bindParam(':id', $POST['id']);
			$query->execute();

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
	function lista_forma_pago()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM f;");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		return json_encode($values);
	}
	function get_order_compra_detalle($id) {
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM orden_compra_detalle WHERE id_orden_compra = :id;");
		$query->bindParam(":id", $id);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		return json_encode($values);
	}
	function get_order_compra($id)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM orden_compra WHERE id = :id");
		$query->bindParam(":id", $id);
		$query->execute();
		
		return json_encode($query->fetch(PDO::FETCH_ASSOC));
	}
	function get_data_compra($id_compra)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT *, DATE(fecha_creacion) as fecha_creacion FROM compras WHERE id = :id_compra");
		$query->bindParam(":id_compra", $id_compra);
		$query->execute();

		return json_encode($query->fetch(PDO::FETCH_ASSOC));
	}
	function get_body_compra($id_compra)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT CONCAT(i.familia, i.clase, i.subclase, i.codigo) as codigo, i.insumo, c_d.* FROM compras_detalle as c_d, insumos_2 as i WHERE c_d.id_compra = :id_compra AND i.id = c_d.id_insumo");
		$query->bindParam(":id_compra", $id_compra);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($values);
	}
	function agregar_proveedor($POST)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO person(kind, id_insumo, no, name, address1, banco, nro_cuenta, tipo_cuenta, tipo_moneda, forma_envio, email1, phone1, wsp) VALUES(2, :id_insumo, :no, :name, :address1, :banco, :nro_cuenta, :tipo_cuenta, :tipo_moneda, :forma_envio, :email1, :phone1, :wsp)");
			$query->bindParam(':id_insumo', $POST['id_insumo']);
			$query->bindParam(':no', $POST['no']);
			$query->bindParam(':name', $POST['name']);
			$query->bindParam(':address1', $POST['address1']);
			$query->bindParam(':banco', $POST['banco']);
			$query->bindParam(':nro_cuenta', $POST['nro_cuenta']);
			$query->bindParam(':tipo_cuenta', $POST['tipo_cuenta']);
			$query->bindParam(':tipo_moneda', $POST['tipo_moneda']);
			$query->bindParam(':forma_envio', $POST['forma_envio']);
			$query->bindParam(':email1', $POST['email1']);
			$query->bindParam(':phone1', $POST['phone1']);
			$query->bindParam(':wsp', $POST['wsp']);
			$query->execute();

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
	function lista_insumos()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM insumos_2");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$query_stock = $mbd->prepare("SELECT * FROM insumo_stock WHERE id_insumo = :id_insumo");
			$query_stock->bindParam(":id_insumo", $res['id']);
			$query_stock->execute();

			$suma_unidades = 0;
			$suma_precio = 0;
			while ($r = $query_stock->fetch(PDO::FETCH_ASSOC)) {
				$suma_unidades += $r['stock'];
				$suma_precio += $r['precio'];
			}

			$res['precio_total'] = $suma_precio;
			$res['stock_total'] = $suma_unidades;
			$res['total_to'] = $suma_unidades * $suma_precio;
			$values[] = $res;
			/*$codigo = "";
				if(empty($res['codigo']) || is_null($res['codigo'])){
					$codigo = "-";
				}else{
					$codigo = $res['codigo'];
				}
				$values[] = array(
					"id" => $res['id'],
					"codigo" => $codigo,
					"insumo" => $res['insumo']
				);*/
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
		//return json_encode($values,JSON_PRETTY_PRINT);
	}
	function detalle_insumo($id)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM insumos_2 WHERE id = :id");
		$query->bindParam(':id', $id);
		$query->execute();
		$values = $query->fetch(PDO::FETCH_ASSOC);
		return json_encode($values);
	}
	function combo_unidades($id)
	{
		include('env.php');
		if ($id == 0) {
			$query = $mbd->prepare("SELECT * FROM unidades");
		} else {
			$query = $mbd->prepare("SELECT u.* FROM unidades as u, insumo_stock as si WHERE si.codigo_unidad = u.codigo AND si.id_insumo = :id");
			$query->bindParam(":id", $id);
		}

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
	function actualizar_insumo($subclase, $insumo, $clase, $familia, $id, $codigo)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE insumos_2 SET subclase = :subclase, insumo = :insumo, clase = :clase, familia = :familia, codigo = :codigo WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->bindParam(':insumo', $insumo);
			$query->bindParam(':familia', $familia);
			$query->bindParam(':clase', $clase);
			$query->bindParam(':subclase', $subclase);
			$query->bindParam(':codigo', $codigo);
			$query->execute();

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
	function aux()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM aux WHERE tabla = 'insumos_2'");
		$query->execute();
		$id = $query->fetch(PDO::FETCH_ASSOC);
		return json_encode($id);
	}
	function actualizar_aux()
	{
		include('env.php');
		$query = $mbd->prepare("UPDATE aux SET id = id + 1 WHERE tabla = 'insumos_2'");
		$query->execute();
	}
	function guardar_insumo($subclase, $insumo, $clase, $familia, $id)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$aux = new clsInsumos;
			$id_ = json_decode($aux->aux());
			$codigo = str_pad($id_->id, 3, "0", STR_PAD_LEFT) . '-' . $id;

			$query = $mbd->prepare("INSERT INTO insumos_2(insumo, familia, clase, subclase, codigo) VALUES(:insumo, :familia, :clase, :subclase, :id)");
			$query->bindParam(':insumo', $insumo);
			$query->bindParam(':id', $id);
			$query->bindParam(':familia', $familia);
			$query->bindParam(':clase', $clase);
			$query->bindParam(':subclase', $subclase);
			$query->execute();

			$aux->actualizar_aux();

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
	function eliminar_insumo($id)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("DELETE FROM insumos_2 WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->execute();

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
	function insumo_autocomplete($term)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM insumos_2 WHERE insumo LIKE '%" . $term . "%'");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = array(
				'id' => $res['id'],
				'cod' => $res['familia'] . $res['clase'] . $res['subclase'] . $res['codigo'],
				'value' => $res['insumo'] . " " . $res['codigo'],
				'unidad' => $res['unidad'],
				'codigo' => $res['codigo'],
			);
		}
		return json_encode($values);
	}
	function lista_compras()
	{
		include('env.php');
		/*$query = $mbd->prepare("SELECT id_proveedor as person_id, proveedor as cliente, total, codigo, DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fecha_creacion FROM compras ORDER BY ID DESC");*/
		$query = $mbd->prepare("SELECT *, DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fecha_creacion, DATE_FORMAT(fproceso, '%d-%m-%Y') as fproceso FROM compras ORDER BY id DESC");
		$query->execute();
		$arrayName = array();
		$cliente = "";
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			if (empty($res['id_proveedor']) || is_null($res['id_proveedor'])) {
				$res['proveedor'] = "Sin Proveedor";
			} else {
				$pp = $mbd->prepare("SELECT * FROM person WHERE id = :id");
				$pp->bindParam(':id', $res['id_proveedor']);
				$pp->execute();

				$p = $pp->fetch(PDO::FETCH_ASSOC);

				$res['proveedor'] = $p['name'];
				$res['no'] = $p['no'];
			}

			$tipo_documento = $mbd->prepare("SELECT * FROM kind_doc where modulo IN (1, 3) AND id = :tipo_documento");
			$tipo_documento->bindParam(":tipo_documento", $res['tipo_documento']);
			$tipo_documento->execute();
			$tipo_doc = $tipo_documento->fetch(PDO::FETCH_ASSOC);

			$p = $mbd->prepare("SELECT * FROM p WHERE id = :id");
			$p->bindParam(":id", $res['id_forma_pago']);
			$p->execute();
			$pago = $p->fetch(PDO::FETCH_ASSOC);

			$res['tipo_documento'] = $tipo_doc['tipo_documento'];
			$res['tipo_pago'] = $pago['name'];
			$arrayName[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $arrayName
		);
		return json_encode($result);
	}
	function lista_proveedores()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM person WHERE kind = 2");
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
	function guardar_compra($ids, $POST)
	{
		$ids = explode(',', $ids);
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$codigo = $POST['cliente'] . '-' . $POST['codigo_compra'];

			$query = $mbd->prepare("INSERT INTO compras(codigo, serie, numeracion, id_proveedor, proveedor, igv, gravado, exonerado, otros_no_gravado, total, tipo_documento, id_forma_pago, fecha_creacion, fecha_detraccion, numero_detraccion, tipo_cambio, fecha_comprobante, serie_comprobante, documento_comprobante) VALUES (:codigo, :serie, :numeracion, :id_proveedor, :proveedor, :igv, :gravado, :exonerado, :otros_no_gravado, :total, :tipo_documento, :id_forma_pago, :fecha_creacion, :fecha_detraccion, :numero_detraccion, :tipo_cambio, :fecha_comprobante, :serie_comprobante, :documento_comprobante)");
			$query->bindParam(":codigo", $codigo);
			$query->bindParam(":proveedor", $POST['n_proveedor']);
			$query->bindParam(":id_proveedor", $POST['cliente']);

			$query->bindParam(":serie", $POST['serie']);
			$query->bindParam(":numeracion", $POST['numeracion']);
			$query->bindParam(":igv", $POST['igv']);
			$query->bindParam(":gravado", $POST['gravado']);
			$query->bindParam(":exonerado", $POST['exonerado']);
			$query->bindParam(":otros_no_gravado", $POST['otros_no_gravado']);
			$query->bindParam(":total", $POST['total']);
			$query->bindParam(":tipo_documento", $POST['tipo_documento']);
			$query->bindParam(":id_forma_pago", $POST['id_forma_pago']);
			$query->bindParam(":fecha_creacion", $POST['fecha_creacion']);
			$query->bindParam(":fecha_detraccion", $POST['fecha_detraccion']);
			$query->bindParam(":numero_detraccion", $POST['numero_detraccion']);
			if (empty($POST['tipo_cambio']) || is_null($POST['tipo_cambio'])) {
				$POST['tipo_cambio'] = 0;
			}
			$query->bindParam(":tipo_cambio", $POST['tipo_cambio']);
			$query->bindParam(":fecha_comprobante", $POST['fecha_comprobante']);
			$query->bindParam(":serie_comprobante", $POST['serie_comprobante']);
			$query->bindParam(":documento_comprobante", $POST['documento_comprobante']);
			$query->execute();
			$lid = $mbd->lastInsertId();
			$total = 0;
			for ($i = 1; $i < count($ids); $i++) {
				$total = $total + $POST['total_' . $ids[$i]];
				$query_i = $mbd->prepare("INSERT INTO compras_detalle(id_compra, codigo_compra, id_insumo, precio, cantidad, total, unidad) VALUES(:id_compra, :codigo_compra, :id_insumo, :precio, :cantidad, :total, :unidad)");
				$query_i->bindParam(':codigo_compra', $codigo);
				$query_i->bindParam(':id_insumo', $ids[$i]);
				$query_i->bindParam(':unidad', $POST['t_unidad_' . $ids[$i]]);
				$query_i->bindParam(':precio', $POST['precio_' . $ids[$i]]);
				$query_i->bindParam(':cantidad', $POST['canti_' . $ids[$i]]);
				$query_i->bindParam(':total', $POST['total_' . $ids[$i]]);
				$query_i->bindParam(':id_compra', $lid);
				$query_i->execute();

				$query_2 = $mbd->prepare("UPDATE insumos_2 SET stock = stock + :cantidad WHERE id = :id");
				$query_2->bindParam(':cantidad', $POST['canti_' . $ids[$i]]);
				$query_2->bindParam(':id', $ids[$i]);
				$query_2->execute();


				$query_cuenta = $mbd->prepare("SELECT COUNT(*) as cant FROM insumo_stock WHERE id_insumo = :id_insumo AND codigo_unidad = :unidad");
				$query_cuenta->bindParam(':id_insumo', $ids[$i]);
				$query_cuenta->bindParam(':unidad', $POST['t_unidad_' . $ids[$i]]);
				$query_cuenta->execute();
				$cantidad_ = $query_cuenta->fetch(PDO::FETCH_ASSOC);

				if ($cantidad_['cant'] == 0) {
					$query_stock = $mbd->prepare("INSERT INTO insumo_stock(id_insumo, codigo_unidad, stock, precio) VALUES (:id_insumo, :unidad, :cantidad, :precio)");
					$query_stock->bindParam(':id_insumo', $ids[$i]);
					$query_stock->bindParam(':unidad', $POST['t_unidad_' . $ids[$i]]);
					$query_stock->bindParam(':cantidad', $POST['canti_' . $ids[$i]]);
					$query_stock->bindParam(':precio', $POST['precio_' . $ids[$i]]);
					$query_stock->execute();
				} else {
					$query_stock = $mbd->prepare("UPDATE insumo_stock SET stock = stock + :cantidad WHERE id_insumo = :id_insumo AND codigo_unidad = :unidad");
					$query_stock->bindParam(':id_insumo', $ids[$i]);
					$query_stock->bindParam(':unidad', $POST['t_unidad_' . $ids[$i]]);
					$query_stock->bindParam(':cantidad', $POST['canti_' . $ids[$i]]);
					$query_stock->execute();
				}
			}

			/*$query_u = $mbd->prepare("UPDATE compras SET total = :total WHERE codigo = :codigo");
				$query_u->bindParam(':total', $total);
				$query_u->bindParam(':codigo', $codigo);
				$query_u->execute();*/

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
	function lista_detalle($id)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT cd.*, i.insumo,CONCAT(i.familia,i.clase,i.subclase,i.codigo) as insumocod FROM compras_detalle as cd, insumos_2 as i WHERE cd.id_insumo = i.id AND cd.id_compra = :id");
		$query->bindParam(':id', $id);
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
	function lista_compras_2($POST)
	{
		include('env.php');

		$cadena_query = "SELECT *, DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fecha_creacion, DATE_FORMAT(fproceso, '%d-%m-%Y') as fproceso FROM compras WHERE 1 = 1 ";

		$fecha = "";
		if ($POST['desde'] != "" && $POST['hasta'] != "") {
			$fecha = "AND DATE(fecha_creacion) BETWEEN '" . $POST['desde'] . "' AND '" . $POST['hasta'] . "'";
		}
		$proveedor = "";
		if ($POST['id_proveedor'] != 0) {
			$proveedor = " AND id_proveedor = " . $POST['id_proveedor'];
		}
		$tipo_documento = "";
		if ($POST['tipo_documento'] != -1) {
			$tipo_documento = " AND tipo_documento = " . $POST['tipo_documento'];
		}
		$tipo_pago = "";
		if ($POST['tipo_pago'] != -1) {
			$tipo_pago = " AND id_forma_pago = " . $POST['tipo_pago'];
		}
		if (empty($POST['fproceso_desde'] || is_null($POST['fproceso_desde']))) {
		} else {
			$fproceso = " AND DATE(fproceso) BETWEEN '" . $POST['fproceso_desde'] . "' AND '" . $POST['fproceso_hasta'] . "'";
		}
		$order = " ORDER BY id DESC";
		//mayor_a
		$f_mayor_a = "";
		if (empty($POST['mayor_a'] || is_null($POST['mayor_a']))) {
		} else {
			/*$q = $mbd->prepare("SELECT id_proveedor from (SELECT id_proveedor, COUNT(id) as cant FROM compras GROUP BY id_proveedor HAVING cant >= :mayor_a) as cuenta");
			$q->bindParam(":mayor_a", $POST['mayor_a']);
			$q->execute();*/

			//$fproceso = " AND (IF SELECT SUM(serie) FROM compras WHERE id_proveedor =  >= ".$POST['mayor_a'].")";
			$f_mayor_a = " AND id_proveedor IN (SELECT id_proveedor from (SELECT id_proveedor, COUNT(id) as cant FROM compras GROUP BY id_proveedor HAVING cant >= " . $POST['mayor_a'] . ") as cuenta)";
			$order = " ORDER BY id_proveedor, id DESC";
		}


		//echo $cadena_query . $fecha . $proveedor . $tipo_documento . $tipo_pago . $fproceso . $f_mayor_a . $order;

		$query = $mbd->prepare($cadena_query . $fecha . $proveedor . $tipo_documento . $tipo_pago . $fproceso . $f_mayor_a . $order);
		$query->execute();
		$arrayName = array();
		$cliente = "";
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			if (empty($res['id_proveedor']) || is_null($res['id_proveedor'])) {
				$res['proveedor'] = "Sin Proveedor";
			} else {
				$pp = $mbd->prepare("SELECT * FROM person WHERE id = :id");
				$pp->bindParam(':id', $res['id_proveedor']);
				$pp->execute();

				$p = $pp->fetch(PDO::FETCH_ASSOC);

				$res['proveedor'] = $p['name'];
				$res['no'] = $p['no'];
			}

			$tipo_documento = $mbd->prepare("SELECT * FROM kind_doc where modulo IN (1, 3) AND id = :tipo_documento");
			$tipo_documento->bindParam(":tipo_documento", $res['tipo_documento']);
			$tipo_documento->execute();
			$tipo_doc = $tipo_documento->fetch(PDO::FETCH_ASSOC);

			$p = $mbd->prepare("SELECT * FROM p WHERE id = :id");
			$p->bindParam(":id", $res['id_forma_pago']);
			$p->execute();
			$pago = $p->fetch(PDO::FETCH_ASSOC);

			$res['tipo_documento'] = $tipo_doc['tipo_documento'];
			$res['tipo_pago'] = $pago['name'];
			$arrayName[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $arrayName
		);
		return json_encode($result);
	}
	function filtro_compras($id, $tipo)
	{
		include('env.php');
		if ($tipo == "tipo_documento") {
			$query = $mbd->prepare("SELECT *, DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fecha_creacion FROM compras WHERE tipo_documento = :id ORDER BY id DESC");
			$query->bindParam(':id', $id);
		} elseif ($tipo == "tipo_pago") {
			$query = $mbd->prepare("SELECT *, DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fecha_creacion FROM compras WHERE id_forma_pago = :id ORDER BY id DESC");
			$query->bindParam(':id', $id);
		}
		$query->execute();
		$arrayName = array();
		$cliente = "";
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			if (empty($res['id_proveedor']) || is_null($res['id_proveedor'])) {
				$res['proveedor'] = "Sin Proveedor";
			} else {
				$pp = $mbd->prepare("SELECT * FROM person WHERE id = :id");
				$pp->bindParam(':id', $res['id_proveedor']);
				$pp->execute();

				$p = $pp->fetch(PDO::FETCH_ASSOC);

				$res['proveedor'] = $p['name'];
				$res['no'] = $p['no'];
			}

			$tipo_documento = $mbd->prepare("SELECT * FROM kind_doc where modulo IN (1, 3) AND id = :tipo_documento");
			$tipo_documento->bindParam(":tipo_documento", $res['tipo_documento']);
			$tipo_documento->execute();
			$tipo_doc = $tipo_documento->fetch(PDO::FETCH_ASSOC);

			$res['tipo_documento'] = $tipo_doc['tipo_documento'];
			$arrayName[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $arrayName
		);
		return json_encode($result);
	}
	function filtro_proveedor($id_proveedor)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT *, DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fecha_creacion FROM compras WHERE id_proveedor = :id_proveedor ORDER BY id DESC");
		$query->bindParam(':id_proveedor', $id_proveedor);
		$query->execute();
		$arrayName = array();
		$cliente = "";
		/*echo "ID ".$id_proveedor."<br>";
		echo "Antes del For<br>";*/
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			//echo "En el For<br>";
			if (empty($res['id_proveedor']) || is_null($res['id_proveedor'])) {
				$res['proveedor'] = "Sin Proveedor";
			} else {
				$pp = $mbd->prepare("SELECT * FROM person WHERE id = :id");
				$pp->bindParam(':id', $res['id_proveedor']);
				$pp->execute();

				$p = $pp->fetch(PDO::FETCH_ASSOC);

				$res['proveedor'] = $p['name'];
				$res['no'] = $p['no'];
			}

			$tipo_documento = $mbd->prepare("SELECT * FROM kind_doc where modulo IN (1, 3) AND id = :tipo_documento");
			$tipo_documento->bindParam(":tipo_documento", $res['tipo_documento']);
			$tipo_documento->execute();
			$tipo_doc = $tipo_documento->fetch(PDO::FETCH_ASSOC);

			$res['tipo_documento'] = $tipo_doc['tipo_documento'];
			$arrayName[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $arrayName
		);
		return json_encode($result);
	}
	function combo_familia()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM familias");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array('Result' => 'OK', 'Records' => $values);
		return json_encode($result);
	}
	function combo_clase()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM clases");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array('Result' => 'OK', 'Records' => $values);
		return json_encode($result);
	}
	function combo_subclase()
	{
		include('env.php');

		/*$query_clase = $mbd->prepare("SELECT * FROM clases WHERE codigo = :id");
			$query_clase->bindParam(":id", $id);
			$query_clase->execute();
			
			$clase = $query_clase->fetch(PDO::FETCH_ASSOC);*/

		$query = $mbd->prepare("SELECT * FROM subclases");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array('Result' => 'OK', 'Records' => $values);
		return json_encode($result);
	}
	function combo_subclase_2()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM subclases");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array('Result' => 'OK', 'Records' => $values);
		return json_encode($result);
	}
	function eliminar_compra($codigo)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query_c = $mbd->prepare("SELECT * FROM compras_detalle WHERE id_compra	= :codigo");
			$query_c->bindParam(':codigo', $codigo);
			$query_c->execute();

			while ($res = $query_c->fetch(PDO::FETCH_ASSOC)) {
				$query_u = $mbd->prepare("UPDATE insumos_2 SET stock = stock - :cantidad WHERE id = :id_insumo");
				$query_u->bindParam(':cantidad', $res['cantidad']);
				$query_u->bindParam(':id_insumo', $res['id_insumo']);
				$query_u->execute();




				$query_stock = $mbd->prepare("UPDATE insumo_stock SET stock = stock - :cantidad WHERE id_insumo = :id_insumo AND codigo_unidad = :unidad");
				$query_stock->bindParam(':id_insumo', $res['id_insumo']);
				$query_stock->bindParam(':unidad', $res['unidad']);
				$query_stock->bindParam(':cantidad', $res['cantidad']);
				$query_stock->execute();
			}

			$query = $mbd->prepare("DELETE FROM compras_detalle WHERE id_compra = :codigo");
			$query->bindParam(':codigo', $codigo);
			$query->execute();

			$query2 = $mbd->prepare("DELETE FROM compras WHERE id = :codigo");
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
	function editar_stock($id)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM insumo_stock WHERE id = :id_stock");
		$query->bindParam(":id_stock", $id);
		$query->execute();

		echo json_encode($query->fetch(PDO::FETCH_ASSOC));
	}
	function ver_stock($id_insumo)
	{
		include('env.php');

		$query_insumo = $mbd->prepare("SELECT * FROM insumos_2 WHERE id = :id_insumo");
		$query_insumo->bindParam(":id_insumo", $id_insumo);
		$query_insumo->execute();

		$insumo = $query_insumo->fetch(PDO::FETCH_ASSOC);

		$query = $mbd->prepare("SELECT * FROM insumo_stock WHERE id_insumo = :id_insumo");
		$query->bindParam(":id_insumo", $id_insumo);
		$query->execute();

		$result = array();
		$values = array();

		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$query_proveedor = $mbd->prepare("SELECT * FROM person WHERE id = :id_proveedor");
			$query_proveedor->bindParam(":id_proveedor", $res['id_proveedor']);
			$query_proveedor->execute();


			$proveedor = $query_proveedor->fetch(PDO::FETCH_ASSOC);

			if (empty($proveedor)) {
				$res['proveedor'] = "SIN PROVEEDOR";
			} else {
				$res['proveedor'] = $proveedor['name'];
			}


			$values[] = $res;
		}

		$result = array(
			'insumo' => $insumo['insumo'] . " " . $insumo['codigo'],
			'stock' => $values
		);
		return json_encode($result);
	}
	function guardar_stock($POST)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query_c = $mbd->prepare("INSERT INTO insumo_stock(id_insumo, codigo_unidad, stock, precio, id_proveedor, descripcion, fecha) VALUES (:id_insumo, :codigo_unidad, :stock, :precio, :id_proveedor, :descripcion, :fecha)");
			$query_c->bindParam(':id_insumo', $POST['id_insumo']);
			$query_c->bindParam(':codigo_unidad', $POST['codigo_unidad']);
			$query_c->bindParam(':stock', $POST['stock']);
			$query_c->bindParam(':precio', $POST['precio']);
			$query_c->bindParam(':id_proveedor', $POST['id_proveedor']);
			$query_c->bindParam(':descripcion', $POST['descripcion']);
			$query_c->bindParam(':fecha', $POST['fecha']);
			$query_c->execute();

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
	function actualizar_stock($POST)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query_c = $mbd->prepare("UPDATE insumo_stock SET codigo_unidad = :codigo_unidad, stock = :stock, precio = :precio, id_proveedor = :id_proveedor, descripcion = :descripcion, fecha = :fecha WHERE id = :id");
			$query_c->bindParam(':id', $POST['id']);
			$query_c->bindParam(':codigo_unidad', $POST['codigo_unidad']);
			$query_c->bindParam(':stock', $POST['stock']);
			$query_c->bindParam(':precio', $POST['precio']);
			$query_c->bindParam(':id_proveedor', $POST['id_proveedor']);
			$query_c->bindParam(':descripcion', $POST['descripcion']);
			$query_c->bindParam(':fecha', $POST['fecha']);
			$query_c->execute();

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
	function eliminar_stock($id)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query2 = $mbd->prepare("DELETE FROM insumo_stock WHERE id = :id");
			$query2->bindParam(':id', $id);
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
	function eliminar_produccion($id)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("DELETE FROM produccion WHERE id = :id");
			$query->bindParam(":id", $id);
			$query->execute();

			$query_detalle = $mbd->prepare("SELECT * FROM produccion_detalle WHERE id_produccion = :id");
			$query_detalle->bindParam(":id", $id);
			$query_detalle->execute();

			while ($res = $query_detalle->fetch(PDO::FETCH_ASSOC)) {
				$query_stock = $mbd->prepare("UPDATE insumo_stock SET stock = stock + :cantidad WHERE id_insumo = :id_insumo AND codigo_unidad = :unidad");
				$query_stock->bindParam(':id_insumo', $res['id_insumo']);
				$query_stock->bindParam(':unidad', $res['unidad']);
				$query_stock->bindParam(':cantidad', $res['cantidad']);
				$query_stock->execute();
			}

			$query_eliminar = $mbd->prepare("DELETE FROM produccion_detalle WHERE id_produccion = :id");
			$query_eliminar->bindParam(":id", $id);
			$query_eliminar->execute();

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
	function lista_produccion()
	{
		include("env.php");

		$query = $mbd->prepare("SELECT * FROM produccion");
		$query->execute();

		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$insumos = "";
			$query_insumo = $mbd->prepare("SELECT i.*, pd.cantidad, pd.unidad FROM insumos_2 as i, produccion_detalle as pd WHERE i.id = pd.id_insumo AND pd.id_produccion = :id_produccion");
			$query_insumo->bindParam(":id_produccion", $res['id']);
			$query_insumo->execute();

			while ($r = $query_insumo->fetch(PDO::FETCH_ASSOC)) {
				$insumos .= $r['insumo'] . " " . $r['codigo'] . ' <strong>' . $r['cantidad'] . ' ' . $r['unidad'] . '</strong><br>';
			}
			$res['insumos'] = $insumos;
			$values[] = $res;
		}

		return json_encode($values);
	}
	function guardar_produccion($POST)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query_1 = $mbd->prepare("SELECT COUNT(*) as cant FROM produccion WHERE orden = :orden");
			$query_1->bindParam(":orden", $POST['orden']);
			$query_1->execute();

			$cant = $query_1->fetch(PDO::FETCH_ASSOC);

			if ($cant['cant'] == 0) {
				$query = $mbd->prepare("INSERT INTO produccion(orden) VALUES(:orden)");
				$query->bindParam(':orden', $POST['orden']);
				$query->execute();

				$lid = $mbd->lastInsertId();
			} else {
				$query_2 = $mbd->prepare("SELECT * FROM produccion WHERE orden = :orden");
				$query_2->bindParam(":orden", $POST['orden']);
				$query_2->execute();

				$produccion = $query_2->fetch(PDO::FETCH_ASSOC);

				$lid = $produccion['id'];
			}



			$query_2 = $mbd->prepare("INSERT INTO produccion_detalle(id_produccion, id_insumo, unidad, cantidad) VALUES(:id_produccion, :id_insumo, :unidad, :cantidad)");
			$query_2->bindParam(':id_produccion', $lid);
			$query_2->bindParam(':id_insumo', $POST['id_insumo']);
			$query_2->bindParam(':unidad', $POST['unidad']);
			$query_2->bindParam(':cantidad', $POST['cantidad']);
			$query_2->execute();

			$query_stock = $mbd->prepare("UPDATE insumo_stock SET stock = stock - :cantidad WHERE id_insumo = :id_insumo AND codigo_unidad = :unidad");
			$query_stock->bindParam(':id_insumo', $POST['id_insumo']);
			$query_stock->bindParam(':unidad', $POST['unidad']);
			$query_stock->bindParam(':cantidad', $POST['cantidad']);
			$query_stock->execute();

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
}
