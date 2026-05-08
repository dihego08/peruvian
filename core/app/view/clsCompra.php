<?php
/*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ini_set('soap.wsdl_cache_enabled',0);
    ini_set('soap.wsdl_cache_ttl',0);*/
class clsCompra
{
	function lista_ordenes_compra()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT oc.*, p.name, f.name as forma_pago FROM orden_compra as oc JOIN person as p ON p.id = oc.id_proveedor LEFT JOIN f ON oc.id_forma_pago = f.id ORDER BY oc.fecha DESC");
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
	public function actualizar_orden_compra($POST)
	{
		include('env.php');

		try {

			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			// $query = $mbd->prepare("INSERT INTO orden_compra(id_proveedor, fecha, total, estado, lugar_entrega, fecha_entrega, id_forma_pago) VALUES (:id_proveedor, :fecha, :total, 0, :lugar_entrega, :fecha_entrega, :id_forma_pago)");

			$query = $mbd->prepare("UPDATE orden_compra SET  id_proveedor = :id_proveedor, fecha = :fecha, total = :total, lugar_entrega = :lugar_entrega, fecha_entrega = :fecha_entrega, id_forma_pago = :id_forma_pago WHERE id = :id");

			$query->bindParam(':id_proveedor', $POST['id_proveedor']);
			$query->bindParam(':fecha', $POST['fecha']);
			$query->bindParam(':total', $POST['total']);
			$query->bindParam(':lugar_entrega', $POST['lugar_entrega']);
			$query->bindParam(':fecha_entrega', $POST['fecha_entrega']);
			$query->bindParam(':id_forma_pago', $POST['id_forma_pago']);
			$query->bindParam(':id', $POST['id']);
			$query->execute();

			$query_delete = $mbd->prepare("DELETE FROM orden_compra_detalle WHERE id_orden_compra = :id");
			$query_delete->bindParam(":id", $POST['id']);
			$query_delete->execute();

			$descripciones = explode("--", $POST['descripciones']);

			$tipos_productos = explode("--", $POST['tipos_productos']);
			$cantidades = explode(",", $POST['cantidades']);
			$id_unidad = explode(",", $POST['unidades']);
			$precios = explode(",", $POST['precios']);

			for ($i = 1; $i < count($descripciones); $i++) {
				$query2 = $mbd->prepare("INSERT INTO orden_compra_detalle(id_orden_compra, descripcion, tipo, cantidad, precio_unitario, precio_total, id_unidad) VALUES (:id_orden_compra, :descripcion, :tipo, :cantidad, :precio_unitario, :precio_total, :id_unidad)");
				$query2->bindParam(':id_orden_compra', $POST['id']);
				$query2->bindParam(':descripcion', $descripciones[$i]);
				$query2->bindParam(':tipo', $tipos_productos[$i]);
				$query2->bindParam(':cantidad', $cantidades[$i]);
				$query2->bindParam(':precio_unitario', $precios[$i]);
				$precio_total = $precios[$i] * $cantidades[$i];
				$query2->bindParam(':precio_total', $precio_total);
				$query2->bindParam(':id_unidad', $id_unidad[$i]);

				$query2->execute();
			}

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
	public function insertar_orden_compra($POST)
	{
		include('env.php');

		try {

			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO orden_compra(id_proveedor, fecha, total, estado, lugar_entrega, fecha_entrega, id_forma_pago) VALUES (:id_proveedor, :fecha, :total, 0, :lugar_entrega, :fecha_entrega, :id_forma_pago)");
			$query->bindParam(':id_proveedor', $POST['id_proveedor']);
			$query->bindParam(':fecha', $POST['fecha']);
			$query->bindParam(':total', $POST['total']);
			$query->bindParam(':lugar_entrega', $POST['lugar_entrega']);
			$query->bindParam(':fecha_entrega', $POST['fecha_entrega']);
			$query->bindParam(':id_forma_pago', $POST['id_forma_pago']);
			$query->execute();

			$LID = $mbd->lastInsertId();

			$descripciones = explode("--", $POST['descripciones']);

			$tipos_productos = explode("--", $POST['tipos_productos']);
			$cantidades = explode(",", $POST['cantidades']);
			$id_unidad = explode(",", $POST['unidades']);
			$precios = explode(",", $POST['precios']);

			for ($i = 1; $i < count($descripciones); $i++) {
				$query2 = $mbd->prepare("INSERT INTO orden_compra_detalle(id_orden_compra, descripcion, tipo, cantidad, precio_unitario, precio_total, id_unidad) VALUES (:id_orden_compra, :descripcion, :tipo, :cantidad, :precio_unitario, :precio_total, :id_unidad)");
				$query2->bindParam(':id_orden_compra', $LID);
				$query2->bindParam(':descripcion', $descripciones[$i]);
				$query2->bindParam(':tipo', $tipos_productos[$i]);
				$query2->bindParam(':cantidad', $cantidades[$i]);
				$query2->bindParam(':precio_unitario', $precios[$i]);
				$precio_total = $precios[$i] * $cantidades[$i];
				$query2->bindParam(':precio_total', $precio_total);
				$query2->bindParam(':id_unidad', $id_unidad[$i]);

				$query2->execute();
			}

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
	public function lista_proveedores()
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
	public function lista_detalle($id)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM orden_compra_detalle WHERE id_orden_compra = :id");
		$query->bindParam(":id", $id);
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
	public function orden_compra($id)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT oc.*, f.name forma_pago FROM orden_compra oc LEFT JOIN f ON oc.id_forma_pago = f.id WHERE oc.id = :id");
		$query->bindParam(":id", $id);
		$query->execute();

		return json_encode($query->fetch(PDO::FETCH_ASSOC));
	}
	public function eliminar_order_compra($id) {
		include('env.php');
		$query2 = $mbd->prepare("DELETE FROM orden_compra WHERE id = :id");
		$query2->bindParam(":id", $id);
		$query2->execute();
		$result = array(
			'Result' => 'OK'
		);
		return json_encode($result);
	}
	public function eliminar_order($id)
	{
		include('env.php');
		$query2 = $mbd->prepare("UPDATE orden_compra SET estado = 1 WHERE id = :id");
		$query2->bindParam(":id", $id);
		$query2->execute();
		$result = array(
			'Result' => 'OK'
		);
		return json_encode($result);
	}
	function buscar_por_fecha_fe($GET)
	{
		$stop_date = date('Y-m-d', strtotime($GET['hasta']));
		include('env.php');
		if ($GET['id_cliente'] == "" || $GET['id_cliente'] == null || is_null($GET['id_cliente']) || $GET['id_cliente'] == 0) {
			$query = $mbd->prepare("SELECT oc.*, p.name, f.name as forma_pago FROM orden_compra as oc JOIN person as p ON p.id = oc.id_proveedor LEFT JOIN f ON oc.id_forma_pago = f.id WHERE oc.fecha BETWEEN :desde AND :hasta ORDER BY oc.fecha DESC");
			$query->bindParam(':desde', $GET['desde']);
			$query->bindParam(':hasta', $stop_date);
		} elseif (is_null($GET['desde']) || empty($GET['desde']) || $GET['desde'] == "" || $GET['desde'] == null) {
			$query = $mbd->prepare("SELECT oc.*, p.name, f.name as forma_pago FROM orden_compra as oc JOIN person as p ON p.id = oc.id_proveedor LEFT JOIN f ON oc.id_forma_pago = f.id WHERE oc.id_proveedor = :id_cliente ORDER BY oc.fecha DESC");
			$query->bindParam(':id_cliente', $GET['id_cliente']);
		} else {
			$query = $mbd->prepare("SELECT oc.*, p.name, f.name as forma_pago FROM orden_compra as oc JOIN person as p ON p.id = oc.id_proveedor LEFT JOIN f ON oc.id_forma_pago = f.id WHERE oc.fecha BETWEEN :desde AND :hasta AND oc.id_proveedor = :id_cliente ORDER BY oc.fecha DESC");
			$query->bindParam(':desde', $GET['desde']);
			$query->bindParam(':hasta', $stop_date);
			$query->bindParam(':id_cliente', $GET['id_cliente']);
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
}
