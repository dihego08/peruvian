<?php
date_default_timezone_set('America/Lima');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);*/
class clsVenta
{
	function tipos_documento()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM kind_doc where modulo = 1 ORDER BY id DESC");
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
	function llenar_departamentos()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM departamento");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		return json_encode($values);
	}
	function llenar_provincias($departamento)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM provincia WHERE departamento = :departamento");
		$query->bindParam(":departamento", $departamento);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		return json_encode($values);
	}
	function llenar_distritos($provincia)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM distrito WHERE provincia = :provincia");
		$query->bindParam(":provincia", $provincia);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		return json_encode($values);
	}
	function tipos_documentos_compras()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM kind_doc where modulo IN (1, 3) ORDER BY id DESC");
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
	function tipos_pago()
	{
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
	function tipos_entrega()
	{
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
	function forma_pago()
	{
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
	function existe_transportista($ruc)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT count(*) as cant FROM transportistas where ruc = :ruc");
		$query->bindParam(":ruc", $ruc);
		$query->execute();
		$res = $query->fetch(PDO::FETCH_ASSOC);
		return $res['cant'];
	}
	function existe_conductor($ndoc)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT count(*) as cant FROM conductores where ruc = :ruc");
		$query->bindParam(":ruc", $ndoc);
		$query->execute();
		$res = $query->fetch(PDO::FETCH_ASSOC);
		return $res['cant'];
	}
	function insertar_guia($POST)
	{
		include('env.php');

		$ids = explode(",", $POST['ids']);
		$ttotal_brutos = explode(",", $POST['ttotal_brutos']);
		$ttotal_netos = explode(",", $POST['ttotal_netos']);
		$cantidades = explode(",", $POST['cantidades']);
		$unidades = explode(",", $POST['unidades_r']);
		$descripciones = explode("||", $POST['descripciones']);
		$pedidos = explode(",", $POST['pedidos']);


		try {
			$cuenta_cliente = $mbd->prepare("SELECT count(*) as cant FROM person WHERE no = :nuevo_ruc");
			$cuenta_cliente->bindParam(":nuevo_ruc", $POST['ruc_destinatario']);
			$cuenta_cliente->execute();
			$cant = $cuenta_cliente->fetch(PDO::FETCH_ASSOC);

			if ($cant['cant'] == 0) {
				$result = file_get_contents('https://dbusinessaqp.com/api_ruc/api.php?ruc=' . $POST['ruc_destinatario'], false);
				$obj = json_decode($result);

				$query_insertar_cliente = $mbd->prepare("INSERT INTO person(no, name, address1, kind) VALUES (:no, :name, :address1, 1)");
				$query_insertar_cliente->bindParam(":no", $POST['ruc_destinatario']);
				$query_insertar_cliente->bindParam(":name", $obj->nombre);
				$query_insertar_cliente->bindParam(":address1", $obj->direccion);
				$query_insertar_cliente->execute();

				$lastInsertId = $mbd->lastInsertId();
				$lista_clientes = $lastInsertId;
			}
		} catch (Exception $ex) {
		}

		try {

			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();
			if (empty($_POST['ruc_transportista']) || is_null($_POST['ruc_transportista'])) {
			} else {
				$existe_transportista = $this->existe_transportista($_POST['obj_transportista']['numeroDocumento']);
				if ($existe_transportista == 0) {
					$query_transportista = $mbd->prepare("INSERT INTO transportistas(razon_social, ruc, direccion, ubigeo, numero, tipoDocumento) VALUES (:razon_social, :ruc, :direccion, :ubigeo, :numero, :tipoDocumento)");
					$query_transportista->bindParam(':razon_social', $_POST['obj_transportista']['nombre']);
					$query_transportista->bindParam(':ruc', $_POST['obj_transportista']['numeroDocumento']);
					$query_transportista->bindParam(':direccion', $_POST['obj_transportista']['direccion']);
					$query_transportista->bindParam(':ubigeo', $_POST['obj_transportista']['ubigeo']);
					$query_transportista->bindParam(':numero', $_POST['obj_transportista']['numero']);
					$query_transportista->bindParam(':tipoDocumento', $_POST['obj_transportista']['tipoDocumento']);
					$query_transportista->execute();
				}
			}
			if (empty($_POST['ruc_conductor']) || is_null($_POST['ruc_conductor'])) {
			} else {
				$existe_conductor = $this->existe_conductor($_POST['obj_conductor']['numeroDocumento']);
				if ($existe_conductor == 0) {
					$query_conductor = $mbd->prepare("INSERT INTO conductores(razon_social, ruc, direccion, ubigeo, numero, tipoDocumento, licencia, nombres, apellidos) VALUES (:razon_social, :ruc, :direccion, :ubigeo, :numero, :tipoDocumento, :licencia, :nombres, :apellidos)");
					$query_conductor->bindParam(':razon_social', $_POST['obj_conductor']['nombre']);
					$query_conductor->bindParam(':ruc', $_POST['obj_conductor']['numeroDocumento']);
					$query_conductor->bindParam(':direccion', $_POST['obj_conductor']['direccion']);
					$query_conductor->bindParam(':ubigeo', $_POST['obj_conductor']['ubigeo']);
					$query_conductor->bindParam(':numero', $_POST['obj_conductor']['numero']);
					$query_conductor->bindParam(':tipoDocumento', $_POST['obj_conductor']['tipoDocumento']);
					$query_conductor->bindParam(':nombres', $_POST['obj_conductor']['nombres']);
					$_POST['obj_conductor']['apellidos'] = $_POST['obj_conductor']['apellidoPaterno'] . " " . $_POST['obj_conductor']['apellidoMaterno'];
					$query_conductor->bindParam(':apellidos', $_POST['obj_conductor']['apellidos']);
					$query_conductor->bindParam(':licencia', $_POST['licencia']);
					$query_conductor->execute();
				}
			}
			$query = $mbd->prepare("INSERT INTO guia_cabecera(num_guia, fecha_emision, fecha_traslado, ruc_destinatario, destino, ruc_transportista, ruc_conductor, placa, comentario, total_bruto, total_neto, estado, origen, ubigeo, ubigeo_destino, modalidad_trasnporte, motivo_traslado, descripcion_motivo) VALUES (:num_guia, :fecha_emision, :fecha_traslado, :ruc_destinatario, :destino, :ruc_transportista, :ruc_conductor, :placa, :comentario, :total_bruto, :total_neto, 0, :origen, :ubigeo, :ubigeo_destino, :modalidad_trasnporte, :motivo_traslado, :descripcion_motivo)");

			$query->bindParam(":num_guia", $POST['num_guia']);
			$query->bindParam(":fecha_emision", $POST['fecha_emision']);
			$query->bindParam(":fecha_traslado", $POST['fecha_traslado']);
			$query->bindParam(":ruc_destinatario", $POST['ruc_destinatario']);
			$query->bindParam(":destino", $POST['destino']);
			$query->bindParam(":ruc_transportista", $POST['ruc_transportista']);
			$query->bindParam(":ruc_conductor", $POST['ruc_conductor']);
			$query->bindParam(":placa", $POST['placa']);
			$query->bindParam(":comentario", $POST['comentario']);
			$query->bindParam(":total_bruto", $POST['total_netos']);
			$query->bindParam(":total_neto", $POST['total_brutos']);
			$query->bindParam(":origen", $POST['origen']);
			$query->bindParam(":ubigeo", $POST['ubigeo']);
			$query->bindParam(":ubigeo_destino", $POST['ubigeo_destino']);
			$POST['modalidad_trasnporte'] = str_pad($POST['modalidad_trasnporte'], 2, "0", STR_PAD_LEFT);
			$query->bindParam(":modalidad_trasnporte", $POST['modalidad_trasnporte']);
			$POST['motivo_traslado'] = str_pad($POST['motivo_traslado'], 2, "0", STR_PAD_LEFT);
			$query->bindParam(":motivo_traslado", $POST['motivo_traslado']);
			$query->bindParam(":descripcion_motivo", $POST['descripcion_motivo']);
			$query->execute();

			$lastInsertId = $mbd->lastInsertId();

			for ($i = 1; $i < count($ids); $i++) {
				$query_p = $mbd->prepare("INSERT INTO guia_detalle(id_guia, id_producto, cantidad, t_bruto, t_neto, unidad, descripcion_producto, pedido) VALUES (:id_guia, :id_producto, :cantidad, :t_bruto, :t_neto, :unidad, :descripcion_producto, :pedido)");
				$query_p->bindParam(":id_guia", $lastInsertId);
				$query_p->bindParam(":id_producto", $ids[$i]);
				$query_p->bindParam(":cantidad", $cantidades[$i]);
				$query_p->bindParam(":t_bruto", $ttotal_brutos[$i]);
				$query_p->bindParam(":t_neto", $ttotal_netos[$i]);
				$query_p->bindParam(":unidad", $unidades[$i]);
				$query_p->bindParam(":descripcion_producto", $descripciones[$i]);
				$query_p->bindParam(":pedido", $pedidos[$i]);
				$query_p->execute();
			}
			$query_upd = $mbd->prepare("UPDATE aux SET id = id + 1 WHERE i = 12");
			$query_upd->execute();

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
	function insertar_venta($tipo_documento, $almacen, $lista_clientes, $tipos_pago, $tipos_entrega, $forma_pago, $descuento, $subtotal, $desc_descuento, $igv, $total, $detraccion, $ids, $precios, $pagado, $a_cuenta, $unidades, $unidades_r, $cantidades, $n_pedidos, $cod_venta, $guia, $fecha_emision, $detraccion_p, $igv_p, $p_b, $pedido_cod, $descripciones, $nuevo_ruc, $fecha_vencimiento, $incluye_igv)
	{
		/*echo "tipo_documento ".$tipo_documento."<br>";
		echo "almacen ". $almacen."<br>";
		echo "lista_clientes ". $lista_clientes."<br>";
		echo "tipos_pago ". $tipos_pago."<br>";
		echo "tipos_entrega ". $tipos_entrega."<br>";
		echo "forma_pago ". $forma_pago."<br>";
		echo "descuento ". $descuento."<br>";
		echo "subtotal ". $subtotal."<br>";
		echo "descripcion ". $descripcion."<br>";
		echo "igv ". $igv."<br>";
		echo "total ". $total."<br>";
		echo "detraccion ". $detraccion."<br>";
		echo "ids ". $ids."<br>";
		echo "precios ". $precios."<br>";
		echo "pagado ". $pagado."<br>";
		echo "a_cuenta ". $a_cuenta."<br>";
		echo "unidades ". $unidades."<br>";
		echo "unidades_r ". $unidades_r."<br>";
		echo "cantidades ". $cantidades."<br>";
		echo "n_pedidos ". $n_pedidos."<br>";
		echo "cod_venta ". $cod_venta."<br>";
		echo "guia ". $guia."<br>";
		echo "fecha_emision ". $fecha_emision."<br>";
		echo "detraccion_p ". $detraccion_p."<br>";
		echo "igv_p ". $igv_p."<br>";
		echo "p_b ". $p_b."<br>";
		echo "pedido_cod ". $pedido_cod."<br>";
		echo "descripciones ". $descripciones."<br>";
		echo "nuevo_ruc ". $nuevo_ruc."<br>";
		echo "fecha_vencimiento ". $fecha_vencimiento."<br>";
		echo "incluye_igv ". $incluye_igv."<br>";*/
		if (empty($igv_p) || is_null($igv_p)) {
			$igv_p = 0;
		}
		if (empty($detraccion_p) || is_null($detraccion_p)) {
			$detraccion_p = 0;
		}
		//$now = new DateTime();
		include('env.php');
		if ($lista_clientes == 0) {
			try {
				$cuenta_cliente = $mbd->prepare("SELECT count(*) as cant FROM person WHERE no = :nuevo_ruc");
				$cuenta_cliente->bindParam(":nuevo_ruc", $nuevo_ruc);
				$cuenta_cliente->execute();
				$cant = $cuenta_cliente->fetch(PDO::FETCH_ASSOC);

				if ($cant['cant'] > 0) {
					$cuenta_cliente_ = $mbd->prepare("SELECT * FROM person WHERE no = :nuevo_ruc");
					$cuenta_cliente_->bindParam(":nuevo_ruc", $nuevo_ruc);
					$cuenta_cliente_->execute();

					$lastInsertId_ = $cuenta_cliente_->fetch(PDO::FETCH_ASSOC);
					$lista_clientes = $lastInsertId_['id'];
				} else {
					$result = file_get_contents('https://dbusinessaqp.com/api_ruc/api.php?ruc=' . $nuevo_ruc, false);
					$obj = json_decode($result);

					$query_insertar_cliente = $mbd->prepare("INSERT INTO person(no, name, address1, kind) VALUES (:no, :name, :address1, 1)");
					$query_insertar_cliente->bindParam(":no", $nuevo_ruc);
					$query_insertar_cliente->bindParam(":name", $obj->nombre);
					$query_insertar_cliente->bindParam(":address1", $obj->direccion);
					$query_insertar_cliente->execute();

					$lastInsertId = $mbd->lastInsertId();
					$lista_clientes = $lastInsertId;
				}
			} catch (Exception $ex) {
			}
		} else {
		}
		$codigo_venta = "";
		$ids = explode(',', $ids);
		$precios = explode(',', $precios);
		$unidades = explode(',', $unidades);
		$unidades_r = explode(',', $unidades_r);
		$cantidades = explode(',', $cantidades);
		$descripciones = explode('--', $descripciones);
		$n_pedidos_anteriores = $n_pedidos;
		$n_pedidos = explode('--', $n_pedidos);
		$p_b = explode(',', $p_b);

		$valor_pagar = $total - round($detraccion_p);

		//calculamos el monto pagado para credito y contado
		switch ($tipos_pago) {
			case 2:
				$pagado = $valor_pagar;
				break;
			case 3:
				$pagado = 0;
				break;
			case 4:
				$pagado = 0;
				break;
		}

		$a_cuenta = $valor_pagar - $pagado;


		try {

			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			switch ($tipo_documento) {
				case 1:
					$codigo_venta = $cod_venta; //'B001-'.str_pad($cod_venta, 9, "0", STR_PAD_LEFT);
					break;
				case 2:
					$query_upd = $mbd->prepare("UPDATE aux SET id = id + 1 WHERE i = 5");
					$query_upd->execute();
					$codigo_venta = $cod_venta; //'F001-'.str_pad($cod_venta, 9, "0", STR_PAD_LEFT);
					break;
				case 3:
					$codigo_venta = $cod_venta; //'NP001-'.str_pad($cod_venta, 9, "0", STR_PAD_LEFT);
					break;
			}
			if ($fecha_emision == "") {
				$fecha_emision = date('Y-m-d');
			}

			$query = $mbd->prepare("INSERT INTO ventas_cabecera(`codigo_venta`, `tipo_documento`, `id_person`, `id_forma_pago`, `id_estado_pago`, `id_estado_entrega`, `almacen`, `descuento`, `desc_descuento`, `detraccion`, `total`, `igv`, `subtotal`,`valor_pagar`, `pagado`, `a_cuenta`, `guia`, `fecha_emision`, `detraccion_p`, `igv_p`,`pedido_cod`, `ruc_add`, `subtotal_2`, `igv_2`, `total_2`, `fecha_vencimiento`, `incluye_igv`) VALUES(:codigo_venta, :tipo_documento, :lista_clientes, :forma_pago, :tipos_pago, :tipos_entrega, :almacen, :descuento, :desc_descuento, :detraccion, :total, :igv, :subtotal,:valor_pagar, :pagado, :a_cuenta, :guia, :fecha_emision, round(:detraccion_p), :igv_p, :pedido_cod, :ruc_add, :subtotal_2, :igv_2, :total_2, :fecha_vencimiento, :incluye_igv)");
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
			$query->bindParam(':desc_descuento', $desc_descuento);
			$query->bindParam(':detraccion', $detraccion);
			$query->bindParam(':total', $total);
			$query->bindParam(':igv', $igv);
			$query->bindParam(':subtotal', $subtotal);
			$query->bindParam(':subtotal_2', $subtotal);
			$query->bindParam(':igv_2', $igv);
			$query->bindParam(':total_2', $total);
			$query->bindParam(':valor_pagar', $valor_pagar);
			$query->bindParam(':pagado', $pagado);
			$query->bindParam(':a_cuenta', $a_cuenta);
			$query->bindParam(':guia', $guia);
			$query->bindParam(':fecha_emision', $fecha_emision);
			$query->bindParam(':pedido_cod', $n_pedidos_anteriores);
			//fecha_vencimiento
			$query->bindParam(':fecha_vencimiento', $fecha_vencimiento);

			$query->bindParam(':ruc_add', $nuevo_ruc);
			$query->bindParam(':incluye_igv', $incluye_igv);
			$query->execute();

			if ($tipos_pago == 2) {
				$pago = new clsVenta;
				$pago->insertar_pago($codigo_venta, $lista_clientes, $valor_pagar, $pagado, $a_cuenta, $fecha_emision);
			}
			for ($i = 1; $i < count($ids); $i++) {
				if ($incluye_igv == 1) {
					$query2 = $mbd->prepare("INSERT INTO ventas_detalle(codigo_venta_cabecera, id_producto, cantidad, pedido_cod, codigo_unidad, precio_unitario, precio_bordado, unidad, tipo) VALUES(:codigo_venta_cabecera, :id_producto, :cantidad, :pedido_cod, :codigo_unidad, :precio_unitario, :p_b, :unidad, :tipo)");
					$query2->bindParam(':codigo_venta_cabecera', $codigo_venta);
					$query2->bindParam(':id_producto', $ids[$i]);
					$query2->bindParam(':p_b', $p_b[$i]);
					$query2->bindParam(':cantidad', $cantidades[$i]);
					$query2->bindParam(':pedido_cod', $n_pedidos[$i]);
					$query2->bindParam(':codigo_unidad', $unidades[$i]);
					$query2->bindParam(':unidad', $unidades_r[$i]);
					$prr = number_format($precios[$i] / 1.18, 6, ".", "");
					$query2->bindParam(':precio_unitario', $prr);
					$query2->bindParam(':tipo', $descripciones[$i]);
					$query2->execute();
				} else {
					$query2 = $mbd->prepare("INSERT INTO ventas_detalle(codigo_venta_cabecera, id_producto, cantidad, pedido_cod, codigo_unidad, precio_unitario, precio_bordado, unidad, tipo) VALUES(:codigo_venta_cabecera, :id_producto, :cantidad, :pedido_cod, :codigo_unidad, :precio_unitario, :p_b, :unidad, :tipo)");
					$query2->bindParam(':codigo_venta_cabecera', $codigo_venta);
					$query2->bindParam(':id_producto', $ids[$i]);
					$query2->bindParam(':p_b', $p_b[$i]);
					$query2->bindParam(':cantidad', $cantidades[$i]);
					$query2->bindParam(':pedido_cod', $n_pedidos[$i]);
					$query2->bindParam(':codigo_unidad', $unidades[$i]);
					$query2->bindParam(':unidad', $unidades_r[$i]);
					$query2->bindParam(':precio_unitario', $precios[$i]);
					$query2->bindParam(':tipo', $descripciones[$i]);
					$query2->execute();
				}
			}
			/*switch ($tipo_documento) {
				case 1:
					$codigo_venta = $cod_venta; //'B001-'.str_pad($cod_venta, 9, "0", STR_PAD_LEFT);
					break;
				case 2:
					$query_upd = $mbd->prepare("UPDATE aux SET id = id + 1 WHERE tabla = 'factura'");
					$query_upd->execute();
					$codigo_venta = $cod_venta; //'F001-'.str_pad($cod_venta, 9, "0", STR_PAD_LEFT);
					break;
				case 3:
					$codigo_venta = $cod_venta; //'NP001-'.str_pad($cod_venta, 9, "0", STR_PAD_LEFT);
					break;
			}*/
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
	function insertar_pago($codigo_venta, $id_person, $total, $pago, $adeuda, $fecha_emision, $banco = "", $concepto = "")
	{
		//echo $codigo_venta." - ". $id_person." - ".  $total." - ".  $pago." - ".  $adeuda;
		if ($total === $pago) {
			//echo "IS TrU";
			$adeuda = 0;
		} else {
			//echo "IS NO";
		}
		//echo $adeuda;
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query_ = $mbd->prepare("UPDATE ventas_cabecera SET a_cuenta = :adeuda, pagado = pagado + :pago WHERE codigo_venta = :codigo_venta");
			$query_->bindParam(":adeuda", $adeuda);
			$query_->bindParam(":pago", $pago);
			$query_->bindParam(":codigo_venta", $codigo_venta);
			$query_->execute();

			$query = $mbd->prepare("INSERT INTO pagos(codigo_venta, id_person, total, pago, deuda, fecha_creacion, banco, concepto) VALUES(:codigo_venta, :id_person, :total, :pago, :adeuda, :fecha_creacion, :banco, :concepto)");
			$query->bindParam(':codigo_venta', $codigo_venta);
			$query->bindParam(':id_person', $id_person);
			$query->bindParam(':total', $total);
			$query->bindParam(':pago', $pago);
			$query->bindParam(':adeuda', $adeuda);
			$query->bindParam(':fecha_creacion', $fecha_emision);
			$query->bindParam(':banco', $banco);
			$query->bindParam(':concepto', $concepto);
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
	function actualizar_pagos($codigo_venta, $pago, $deuda)
	{
		include('env.php');
		if ($total === $pago) {
			//echo "IS TrU";
			$adeuda = 0;
		} else {
			//echo "IS NO";
		}
		$query = $mbd->prepare("UPDATE pagos SET pago = :pago, deuda = :deuda WHERE codigo_venta = :codigo_venta");
		$query->bindParam(':pago', $pago);
		$query->bindParam(':deuda', $deuda);
		$query->bindParam(':codigo_venta', $codigo_venta);
		$query->execute();
	}
	function lista_ventas_s($filtro, $codigo)
	{

		include('env.php');
		if ($filtro == 'ninguno') {
			$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_emision DESC");
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
		} else {
			if ($filtro == 'pago') {
				$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND p.id = :codigo AND vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_emision DESC");
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
			} else {
				if ($filtro == 'entrega') {
					$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND d.id = :codigo AND vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_emision DESC");
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
				} elseif ($filtro == 'cliente') {
					$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo AND vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_emision DESC");
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
	function sortFunction($a, $b)
	{
		return  strtotime($b["fecha_creacion"]) - strtotime($a["fecha_creacion"]);
	}
	function lista_ventas_fe($filtro, $codigo, $fecha)
	{
		$anio = "";
		$mes = "";
		if ($fecha == "") {
			$fecha_ = date("Y-m-d");
		} else {
			$fecha = explode('-', $fecha);
			$anio = $fecha[1];
			$mes = $fecha[0];
		}
		include('env.php');
		if ($filtro == 'ninguno' && $fecha == "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id UNION SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 ORDER BY fc DESC");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}

			//usort($values, "sortFunction");
			//var_dump($values);
			$result = array(
				'Result' => 'OK',
				'Records' => $values
			);
			return json_encode($result);
		} elseif ($filtro == "ninguno" && $fecha != "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio UNION SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, vc.fecha_creacion as fc, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio ORDER BY fc DESC");
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
		} elseif ($filtro == 'pago' && $fecha == "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND p.id = :codigo  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
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
		} elseif ($filtro == 'entrega') {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND d.id = :codigo  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
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
		} elseif ($filtro == 'cliente' && $fecha == "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
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
		} elseif ($filtro == 'cliente' && $fecha != "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio  ORDER BY vc.fecha_creacion DESC, vc.codigo_venta DESC");
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
	function lista_guias($filtro, $codigo, $fecha)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT DISTINCT g.*, p.name FROM guia_cabecera AS g join person p on p.no = g.ruc_destinatario order by g.id DESC");
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
	function lista_ventas($filtro, $codigo, $fecha)
	{
		$anio = "";
		$mes = "";
		if ($fecha == "") {
			$fecha_ = date("Y-m-d");
		} else {
			$fecha = explode('-', $fecha);
			$anio = $fecha[1];
			$mes = $fecha[0];
		}
		include('env.php');
		if ($filtro == 'ninguno' && $fecha == "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id UNION SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 ORDER BY fc DESC");

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
		} elseif ($filtro == "ninguno" && $fecha != "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio  ORDER BY vc.fecha_creacion DESC");
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
		} elseif ($filtro == 'pago' && $fecha == "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id UNION SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 AND p.id = :codigo ORDER BY fc DESC");
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
		} elseif ($filtro == 'entrega') {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND d.id = :codigo  ORDER BY vc.fecha_creacion DESC");
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
		} elseif ($filtro == 'cliente' && $fecha == "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo  ORDER BY vc.fecha_creacion DESC");
			$query->bindParam(':codigo', $codigo);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$res = str_replace("null", "", $res);
				$values[] = $res;
			}
			$result = array(
				'Result' => 'OK',
				'Records' => $values
			);
			return json_encode($result);
		} elseif ($filtro == 'cliente' && $fecha != "") {
			$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = :codigo AND MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio  ORDER BY vc.fecha_creacion DESC");
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
	function lista_detalle_guia($id){
		include('env.php');
		$mbd->exec("set names utf8");
		$query = $mbd->prepare("SELECT p.name, vd.* FROM guia_detalle as vd, product as p WHERE p.id = vd.id_producto AND vd.id_guia = :codigo");
		$query->bindParam(':codigo', $id);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			//print_r($res);

			$values[] = $res;
		}
		$query = $mbd->prepare("SELECT * FROM guia_cabecera WHERE id = :codigo");
		$query->bindParam(':codigo', $id);
		$query->execute();
		$venta = $query->fetch(PDO::FETCH_ASSOC);
		$result = array(
			'Result' => 'OK',
			'Records' => $values,
			'venta' => $venta
		);
		return json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	function lista_detalle($codigo)
	{
		include('env.php');
		$mbd->exec("set names utf8");
		$query = $mbd->prepare("SELECT p.name, vd.* FROM ventas_detalle as vd, product as p WHERE p.id = vd.id_producto AND vd.codigo_venta_cabecera = :codigo");
		$query->bindParam(':codigo', $codigo);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			//print_r($res);

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
		return json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	function eliminar_venta($codigo)
	{
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
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function actualizar_pago($codigo, $monto_pagado, $monto_adeuda, $fecha, $banco, $concepto)
	{
		// IMPORTANTE: Incluir env.php ANTES de iniciar la transacción
		include('env.php');
		
		// Verificar que la conexión está activa
		try {
			$mbd->getAttribute(PDO::ATTR_SERVER_INFO);
		} catch (PDOException $e) {
			// Reconectar si la conexión se perdió
			include('env.php');
		}
		
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// Establecer timeout más largo para la transacción
			$mbd->setAttribute(PDO::ATTR_TIMEOUT, 60);
			
			$mbd->beginTransaction();

			// 1. Actualizar ventas_cabecera
			$query = $mbd->prepare("UPDATE ventas_cabecera 
									SET pagado = pagado + :monto_pagado, 
										a_cuenta = :monto_adeuda 
									WHERE codigo_venta = :codigo");
			$query->bindParam(':monto_pagado', $monto_pagado);
			$query->bindParam(':monto_adeuda', $monto_adeuda);
			$query->bindParam(':codigo', $codigo);
			$query->execute();

			// 2. Obtener datos de la venta
			$p = $mbd->prepare("SELECT id_person, valor_pagar 
								FROM ventas_cabecera 
								WHERE codigo_venta = :codigo");
			$p->bindParam(':codigo', $codigo);
			$p->execute();
			$pagos = $p->fetch(PDO::FETCH_ASSOC);

			// 3. Insertar pago (inline, sin llamar a otra función)
			$query_pago = $mbd->prepare("INSERT INTO pagos(
											codigo_venta, 
											id_person, 
											total, 
											pago, 
											deuda, 
											fecha_creacion, 
											banco, 
											concepto
										) VALUES(
											:codigo_venta, 
											:id_person, 
											:total, 
											:pago, 
											:adeuda, 
											:fecha_creacion, 
											:banco, 
											:concepto
										)");
			
			$query_pago->bindParam(':codigo_venta', $codigo);
			$query_pago->bindParam(':id_person', $pagos['id_person']);
			$query_pago->bindParam(':total', $pagos['valor_pagar']);
			$query_pago->bindParam(':pago', $monto_pagado);
			$query_pago->bindParam(':adeuda', $monto_adeuda);
			$query_pago->bindParam(':fecha_creacion', $fecha);
			$query_pago->bindParam(':banco', $banco);
			$query_pago->bindParam(':concepto', $concepto);
			$query_pago->execute();

			$mbd->commit();
			
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
			
		} catch (PDOException $e) {
			// Verificar si la conexión sigue activa antes de rollback
			try {
				if ($mbd->inTransaction()) {
					$mbd->rollBack();
				}
			} catch (PDOException $rollback_error) {
				// Si el rollback falla, la conexión ya se perdió
				error_log("Error en rollback: " . $rollback_error->getMessage());
			}
			
			$result = array(
				'Result' => 'ERROR: ' . $e->getMessage()
			);
			return json_encode($result);
		}
	}
	/*function actualizar_pago($codigo, $monto_pagado, $monto_adeuda, $fecha, $banco, $concepto)
	{
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
			$pago->insertar_pago($codigo, $pagos['id_person'], $pagos['valor_pagar'], $monto_pagado, $monto_adeuda, $fecha, $banco, $concepto);

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
	}*/

	function actualizar_pago_historial($codigo, $pago, $clienteId, $fecha, $banco, $concepto)
	{
		include('env.php');
		try {
			$query = $mbd->prepare("UPDATE pagos SET pago = :pago, fecha_creacion = :fecha, banco = :banco, concepto = :concepto WHERE id = :codigo");
			$query->bindParam(':pago', $pago);
			$query->bindParam(':fecha', $fecha);
			$query->bindParam(':codigo', $codigo);
			$query->bindParam(':banco', $banco);
			$query->bindParam(':concepto', $concepto);
			$query->execute();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}

	function eliminar_pago_historial($codigo)
	{
		include('env.php');
		try {
			$query = $mbd->prepare("delete from pagos WHERE id = :codigo");
			$query->bindParam(':codigo', $codigo);
			$query->execute();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}

	function recalcular_pago_historial($id_person, $codigo_venta)
	{
		include('env.php');
		$query2 = $mbd->prepare("SELECT id,total,pago,deuda FROM pagos");
		//$query->bindParam(':codigo_venta', $codigo_venta);
		//$query->bindParam(':id_person', $id_person);
		//WHERE codigo_venta = :codigo_venta AND id_person = :id_person ORDER BY fc ASC"
		$query2->execute();
		/*
			$cont = 1;
			$saldo = 0;
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				if($cont == 1){
					$saldo = $res->total;
				}
				$saldo = $saldo - $res->pago;
				$query = $mbd->prepare("UPDATE pagos set deuda = :deuda where id = :id");
				$query->bindParam(':deuda', $saldo);
				$query->bindParam(':id', $res->id);
				$query->execute();
				$cont++;
			}
			*/
	}

	function actualizar_venta_pagos($codigo, $monto_pagado, $monto_adeuda, $fecha)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE ventas_cabecera SET pagado = pagado + :monto_pagado, a_cuenta = :monto_adeuda WHERE codigo_venta = :codigo");
			$query->bindParam(':monto_pagado', $monto_pagado);
			$query->bindParam(':monto_adeuda', $monto_adeuda);
			$query->bindParam(':codigo', $codigo);
			$query->execute();

			/*$p = $mbd->prepare("SELECT * FROM ventas_cabecera WHERE codigo_venta = :codigo");
			  	$p->bindParam(':codigo', $codigo);
			  	$p->execute();
			  	$pagos = $p->fetch(PDO::FETCH_ASSOC);*/

			$pago = new clsVenta;
			$pago->insertar_pago($codigo, $pagos['id_person'], $pagos['total'], $monto_pagado, $monto_adeuda, $fecha);



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
	function historial_pago($id_person, $codigo_venta)
	{
		include('env.php');
		if ($id_person == 0) {
			$query = $mbd->prepare("SELECT id, codigo_venta, id_person, banco, concepto, total, pago, deuda, fecha_creacion as fc, DATE(fecha_creacion) as fecha_creacion FROM pagos WHERE codigo_venta = :codigo_venta ORDER BY fc ASC");
			$query->bindParam(':codigo_venta', $codigo_venta);
		} else {
			$query = $mbd->prepare("SELECT id, codigo_venta, id_person, banco, concepto, total, pago, deuda, fecha_creacion as fc, DATE(fecha_creacion) as fecha_creacion FROM pagos WHERE codigo_venta = :codigo_venta AND id_person = :id_person ORDER BY fc ASC");
			$query->bindParam(':codigo_venta', $codigo_venta);
			$query->bindParam(':id_person', $id_person);
		}
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
	function buscar_por_fecha($desde, $hasta, $tipos_pago, $tipos_documento, $combo_cliente)
	{
		include('env.php');

		$where = "";

		if ($tipos_pago > 0) {
			$where .= ' AND p.id = ' . $tipos_pago;
		} elseif ($tipos_pago == "-1") {
			//val.a_cuenta
			$where .= ' AND vc.a_cuenta > 0';
		}

		if ($tipos_documento != 0) {
			$where .= ' AND k.id = ' . $tipos_documento;
		}

		if ($combo_cliente != 0) {
			$where .= ' AND pe.id = ' . $combo_cliente;
		}

		if (empty($desde) || is_null($hasta)) {
		} else {
			$where .= " AND fecha_creacion BETWEEN '" . $desde . "' AND '" . $hasta . "'";
		}

		/*$query = $mbd->prepare("
			SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
			FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k 
			WHERE fecha_creacion BETWEEN :desde AND :hasta AND vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id 
			UNION 
			SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
			FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k 
			WHERE fecha_creacion BETWEEN :desde AND :hasta AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 ORDER BY fc DESC");*/

		$query = $mbd->prepare("
			SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, DATE_FORMAT(vc.fecha_creacion, '%d-%m-%Y') as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
			FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k 
			WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id " . $where . " ORDER BY vc.fecha_creacion DESC");

		/*echo "
			SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, DATE_FORMAT(vc.fecha_creacion, '%d-%m-%Y') as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
			FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k 
			WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id ".$where." ORDER BY vc.fecha_creacion DESC";*/

		/*echo "
			SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, DATE_FORMAT(vc.fecha_creacion, '%d-%m-%Y') as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
			FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k 
			WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id ".$where." ORDER BY DATE(fc) DESC";*/

		/*echo "
			SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, DATE_FORMAT(vc.fecha_creacion, '%d-%m-%Y') as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
			FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k 
			WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id ".$where." ORDER BY DATE(fc) DESC";*/

		/*$query->bindParam(':desde', $desde);
		$query->bindParam(':hasta', $hasta);*/
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			//'Records' => array_reverse($values)
			'Records' => $values
		);
		return json_encode($result);
	}
	function buscar_por_fecha_fe($desde, $hasta, $id_cliente)
	{
		//$stop_date = date('Y-m-d', strtotime($hasta . ' +1 day'));
		$stop_date = date('Y-m-d', strtotime($hasta));
		//echo $stop_date;
		include('env.php');
		if ($id_cliente == "" || $id_cliente == null || is_null($id_cliente) || $id_cliente == 0) {
			$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND DATE(vc.fecha_emision) BETWEEN :desde AND :hasta ORDER BY vc.fecha_emision DESC");
			/*$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
						FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k 
						WHERE vc.fecha_emision >= :desde AND vc.fecha_emision <= :hasta AND vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id 
					UNION SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento 
						FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k 
						WHERE vc.fecha_emision >= :desde AND vc.fecha_emision <= :hasta AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 ORDER BY fc DESC");*/
			$query->bindParam(':desde', $desde);
			$query->bindParam(':hasta', $stop_date);
		} else {
			$query = $mbd->prepare("SELECT vc.*, date(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND DATE(vc.fecha_emision) BETWEEN :desde AND :hasta AND vc.id_person = :id_cliente ORDER BY vc.fecha_emision DESC");
			//$query = $mbd->prepare("SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE fecha_creacion BETWEEN :desde AND :hasta AND vc.tipo_documento = 2 AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id UNION SELECT vc.*, DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion, vc.fecha_creacion as fc, vc.ruc_add as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, p as p, d as d, f as f, kind_doc as k WHERE fecha_creacion BETWEEN :desde AND :hasta AND vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = 0 ORDER BY fc DESC");
			$query->bindParam(':desde', $desde);
			$query->bindParam(':hasta', $stop_date);
			$query->bindParam(':id_cliente', $id_cliente);
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
	function buscar_por_fecha_s($desde, $hasta)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND DATE(vc.fecha_emision) BETWEEN :desde AND :hasta AND  vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_emision DESC");
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
	function tipo_usuario()
	{
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
	function anular($codigo)
	{
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
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function actualizar($cod_n, $guia, $codigo, $fecha_pago, $entidad, $fecha_det)
	{
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
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}

	function lista_rep_ventas_cliente($fecha_ini, $fecha_fin)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT p.name as cliente, vc.fecha_emision as fecha, vd.cantidad as cantidad,m.code as modelo,(vd.precio_unitario * vd.cantidad) as subtotal FROM
									ventas_detalle vd
									INNER JOIN ventas_cabecera vc ON vd.codigo_venta_cabecera = vc.codigo_venta
									INNER JOIN product m ON vd.id_producto = m.id
									inner join person p ON vc.id_person = p.id where vc.fecha_emision between :fecha_ini and :fecha_fin AND vc.estado_anulado  IS NULL ORDER BY vc.fecha_emision DESC");
		$query->bindParam(':fecha_ini', $fecha_ini);
		$query->bindParam(':fecha_fin', $fecha_fin);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$res['fff'] = $res['fecha'];
			$res['fecha'] = date("d-m-Y", strtotime($res['fecha']));
			$values[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
	}

	function lista_gra_ventas_cliente($fecha_ini, $fecha_fin)
	{
		include('env.php');
		$query = $mbd->prepare("select p.name as cliente,sum((vd.precio_unitario * vd.cantidad)) as total FROM ventas_detalle vd
			INNER JOIN ventas_cabecera vc ON vd.codigo_venta_cabecera = vc.codigo_venta
			inner join person p ON vc.id_person = p.id where vc.fecha_emision between :fecha_ini and :fecha_fin AND vc.estado_anulado  IS NULL group by p.name");
		$query->bindParam(':fecha_ini', $fecha_ini);
		$query->bindParam(':fecha_fin', $fecha_fin);
		$query->execute();
		$labels = array();
		$values = array();
		$result_2 = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$labels[] = $res['cliente'];
			$values[] = $res['total'];
			$result_2[] = array(
				'label' => $res['cliente'],
				'y' => $res['total']
			);
		}
		$result = array(
			'clientes' => $labels,
			'totales' => $values,
			'extra' => $result_2
		);
		/*$dataPoints = array( 
				array("label"=>"Chrome", "y"=>64.02),
				array("label"=>"Firefox", "y"=>12.55),
				array("label"=>"IE", "y"=>8.47),
				array("label"=>"Safari", "y"=>6.08),
				array("label"=>"Edge", "y"=>4.29),
				array("label"=>"Others", "y"=>4.59)
			)*/
		//return json_encode($result);

		return json_encode($result, JSON_NUMERIC_CHECK);
	}

	function lista_gra_ventas_producto($fecha_ini, $fecha_fin)
	{
		include('env.php');
		$query = $mbd->prepare("select m.code as modelo,sum((vd.precio_unitario * vd.cantidad)) as total FROM ventas_detalle vd INNER JOIN ventas_cabecera vc ON vd.codigo_venta_cabecera = vc.codigo_venta INNER JOIN product m ON vd.id_producto = m.id where vc.fecha_emision between :fecha_ini and :fecha_fin AND vc.estado_anulado  IS NULL group by m.code ");
		$query->bindParam(':fecha_ini', $fecha_ini);
		$query->bindParam(':fecha_fin', $fecha_fin);
		$query->execute();
		$labels = array();
		$values = array();

		$result_2 = array();

		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$labels[] = $res['modelo'];
			$values[] = $res['total'];

			$result_2[] = array(
				"y" => $res['total'],
				"label" => $res['modelo']
			);
		}
		$result = array(
			'modelos' => $labels,
			'totales' => $values,
			'extra' => $result_2
		);
		return json_encode($result);
	}

	function lista_gra_ventas_mes($desde, $hasta)
	{
		$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		$valores = array('0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');

		include('env.php');
		if (empty($desde) || empty($hasta)) {
			$anio_actual = date("Y");
			$query = $mbd->prepare("SELECT MONTH(fecha_emision)as mes, SUM(subtotal) as total FROM ventas_cabecera where year(fecha_emision) = :anio_actual group by MONTH(fecha_emision) order by MONTH(fecha_emision) asc");
			$query->bindParam(":anio_actual", $anio_actual);
			$query->execute();
			$labels = array();
			$values = array();
			//setlocale(LC_MONETARY,"es_PE");
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$labels[] = $meses[$res['mes'] - 1];
				//$values[] = money_format("%.2n", $res['total']);
				$values[] = $res['total'];
				$valores[$res['mes'] - 1] = $res['total'];
			}
			$result = array(
				'meses' => $meses,
				'totales' => $valores
			);
			return json_encode($result);
		} else {
			$query = $mbd->prepare("SELECT MONTH(fecha_emision)as mes, SUM(subtotal) as total FROM ventas_cabecera where fecha_emision BETWEEN :desde AND :hasta group by MONTH(fecha_emision) order by MONTH(fecha_emision) asc");
			$query->bindParam(":desde", $desde);
			$query->bindParam(":hasta", $hasta);
			$query->execute();
			$labels = array();
			$values = array();
			//setlocale(LC_MONETARY,"es_PE");
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$labels[] = $meses[$res['mes'] - 1];
				//$values[] = money_format("%.2n", $res['total']);
				$values[] = $res['total'];
			}
			$result = array(
				'meses' => $labels,
				'totales' => $values
			);
			return json_encode($result);
		}
	}

	function lista_gra_ventas_mes_fechas($fecha_ini, $fecha_fin)
	{
		include('env.php');
		$query = $mbd->prepare("select YEAR(fecha_emision) as a,MONTH(fecha_emision)as mes,SUM(subtotal) as total from ventas_cabecera where fecha_emision between :fecha_ini and :fecha_fin group by MONTH(fecha_emision) order by YEAR(fecha_emision),MONTH(fecha_emision) asc");
		$query->bindParam(':fecha_ini', $fecha_ini);
		$query->bindParam(':fecha_fin', $fecha_fin);
		$query->execute();
		$labels = array();
		$values = array();
		//setlocale(LC_MONETARY,"es_PE");
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$labels[] = $res['a'] . '-' . $res['mes'];
			//$values[] = money_format("%.2n", $res['total']);
			$values[] = $res['total'];
		}
		$result = array(
			'meses' => $labels,
			'totales' => $values
		);
		return json_encode($result);
	}

	function lista_rep_ventas_mes()
	{
		include('env.php');

		$query_ = $mbd->prepare("SELECT distinct YEAR(fecha_emision) as anio from ventas_cabecera ORDER BY anio ASC");
		$query_->execute();

		$anios = array();

		$aux = 0;
		$values = array();
		while ($res = $query_->fetch(PDO::FETCH_ASSOC)) {
			$anios[] = $res;
			$query = $mbd->prepare("SELECT MONTH(fecha_emision)as mes, SUM(subtotal) as total from ventas_cabecera WHERE YEAR(fecha_emision) = :anio_ group by MONTH(fecha_emision) order by MONTH(fecha_emision) asc");
			$query->bindParam(":anio_", $res['anio']);
			$query->execute();

			$add_anio = array();
			while ($res_ = $query->fetch(PDO::FETCH_ASSOC)) {
				/*for($i = 1; $i <= 12; $i++){
					if($)
				}*/
				$add_anio[] = $res_;
			}
			$values[$res['anio']] = $add_anio;
			$aux++;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values,
			'anios' => $anios
		);
		return json_encode($result);
	}

	function lista_rep_ventas_guia_pedido($fecha_ini, $fecha_fin)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha,vc.codigo_venta as venta,vc.guia as guia,vc.pedido_cod as pedido FROM ventas_cabecera vc where vc.fecha_emision between :fecha_ini and :fecha_fin order by vc.codigo_venta desc");
		$query->bindParam(':fecha_ini', $fecha_ini);
		$query->bindParam(':fecha_fin', $fecha_fin);
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
	function get_unidades()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM codigos_sunat");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		return json_encode($values);
	}
	function get_aux_guia()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM aux WHERE tabla = 'guia'");
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
	function get_aux()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM aux WHERE tabla = 'factura'");
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
	public function get_correlativo_nc()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM aux WHERE tabla = 'nota_credito'");
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
	public function guardar_pago_detraccion($codigo_venta, $paga)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE ventas_cabecera SET detraccion_paga = :paga WHERE codigo_venta = :codigo_venta");
			$query->bindParam(':paga', $paga);
			$query->bindParam(':codigo_venta', $codigo_venta);
			/*$query->bindParam(':codigo', $codigo);
			$query->bindParam(':fecha_pago', $fecha_pago);
			$query->bindParam(':entidad', $entidad);
			$query->bindParam(':fecha_detraccion', $fecha_det);*/
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
}
