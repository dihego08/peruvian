<?php
class clsFichaTecnica
{
	function get_ficha($codigo_producto)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$pro = $mbd->prepare("SELECT description, image FROM product WHERE code = :codigo_producto");
			$pro->bindParam(":codigo_producto", $codigo_producto);
			$pro->execute();

			$producto = $pro->fetch(PDO::FETCH_ASSOC);

			$query = $mbd->prepare("SELECT * FROM ficha_tecnica WHERE code_producto = :codigo_producto");
			$query->bindParam(":codigo_producto", $codigo_producto);
			$query->execute();

			$values = $query->fetch(PDO::FETCH_ASSOC);
			$values['descripcion'] = $producto['description'];
			$values['image'] = $producto['image'];
			$mbd->commit();
			$result = array(
				'Result' => 'OK',
				'Records' => $values
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
	function update_ficha($POST)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$count = $mbd->prepare("SELECT COUNT(*) as cant FROM ficha_tecnica WHERE code_producto = :code_producto");
			$count->bindParam(":code_producto", $POST['code_producto']);
			$count->execute();

			$cant = $count->fetch(PDO::FETCH_ASSOC);

			if ($cant['cant'] > 0) {
				$query = $mbd->prepare("UPDATE ficha_tecnica SET elaborado_por = :elaborado_por, revisado_por = :revisado_por, aprobado_por = :aprobado_por, u_modificacion = :u_modificacion WHERE code_producto = :code_producto");
				//$POST['u_modificacion'] = date("d-m-Y");
				$query->execute($POST);
			} else {
				$query = $mbd->prepare("INSERT INTO ficha_tecnica(code_producto, elaborado_por, revisado_por, aprobado_por, u_modificacion) VALUES(:code_producto, :elaborado_por, :revisado_por, :aprobado_por, :u_modificacion);");
				//$POST['u_modificacion'] = date("d-m-Y");
				$query->execute($POST);
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
	function save_ficha($POST)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO ficha_tecnica(tejido, cinta, etiqueta, estampado, code_producto) VALUES(:tejido, :cinta, :etiqueta, :estampado, :code_producto);");
			$query->execute($POST);

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
	function get_instruccion($codigo_producto)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$ma = $mbd->prepare("SELECT * FROM maquinas WHERE code_producto = :code_producto");
			$ma->bindParam(":code_producto", $codigo_producto);
			$ma->execute();

			$maquinas = array();

			while ($m = $ma->fetch(PDO::FETCH_ASSOC)) {
				$maquinas[] = $m;
			}

			$ins = $mbd->prepare("SELECT * FROM etapas");
			$ins->execute();

			$ins_ = array();

			while ($res = $ins->fetch(PDO::FETCH_ASSOC)) {
				$query = $mbd->prepare("SELECT * FROM pasos WHERE code_producto = :code_producto AND id_etapa = :id_etapa ORDER BY orden");
				$query->bindParam(":code_producto", $codigo_producto);
				$query->bindParam(":id_etapa", $res['id']);
				$query->execute();

				$values = array();

				$ins_['id'] = $res['id'];
				$ins_['etapa'] = $res['etapa'];
				while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
					$values[] = $res;
				}
				$ins_['pasos'] = $values;
				$result_[] = $ins_;
			}

			$mbd->commit();
			$result = array(
				'Result' => 'OK',
				'Records' => $result_,
				'Maquinas' => $maquinas
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
	function save_instruccion($POST)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO pasos(id_etapa, paso, instruccion, code_producto, orden) VALUES(:id_etapa, :paso, :instruccion, :code_producto, :orden);");
			$POST['instruccion'] = nl2br($POST['instruccion']);
			$query->execute($POST);

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
	function edit_instruccion($POST)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE pasos SET paso = :paso, instruccion = :instruccion, orden = :orden WHERE id = :id;");
			$query->execute($POST);

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
	function edit_observacion($POST){
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE observaciones SET observacion = :observacion, detalle = :detalle WHERE id = :id;");
			$query->execute($POST);

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
	function eliminar_paso($POST)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("DELETE FROM pasos WHERE id = :id;");
			$query->execute($POST);

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
	function eliminar_observacion($id){
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("DELETE FROM observaciones WHERE id = :id;");
			$query->bindParam(":id", $id);
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
	function guardar_maquina($POST)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO maquinas(maquina, code_producto) VALUES(:maquina, :code_producto);");
			$query->execute($POST);

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
	function eliminar_maquina($POST)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("DELETE FROM maquinas WHERE id = :id;");
			$query->execute($POST);

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
	function get_medidas($codigo_producto)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("SELECT * FROM medidas WHERE code_producto = :code_producto");
			$query->bindParam(":code_producto", $codigo_producto);
			$query->execute();

			$values = array();

			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}

			$mbd->commit();
			$result = array(
				'Result' => 'OK',
				'Records' => $values
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
	function get_complementos($codigo_producto)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("SELECT * FROM complementos WHERE code_producto = :code_producto");
			$query->bindParam(":code_producto", $codigo_producto);
			$query->execute();

			$values = array();

			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}

			$mbd->commit();
			$result = array(
				'Result' => 'OK',
				'Records' => $values
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
	function get_identificacion($codigo_producto)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("SELECT * FROM identificacion WHERE code_producto = :code_producto");
			$query->bindParam(":code_producto", $codigo_producto);
			$query->execute();

			$values = array();

			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}

			$mbd->commit();
			$result = array(
				'Result' => 'OK',
				'Records' => $values
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
	function guardar_medidas($POST)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO medidas(descripcion, t_2, t_4, t_6, t_8, t_10, t_12, t_14, t_16, s, m, l, code_producto, xl, xxl, xxxl) VALUES(:descripcion, :t_2, :t_4, :t_6, :t_8, :t_10, :t_12, :t_14, :t_16, :s, :m, :l, :num_modelo, :xl, :xxl, :xxxl);");
			$query->execute($POST);

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
	function edit_medida($POST)
	{
		include("env.php");
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE medidas SET descripcion = :descripcion, t_2 = :t_2, t_4 = :t_4, t_6 = :t_6, t_8 = :t_8, t_10 = :t_10, t_12 = :t_12, t_14 = :t_14, t_16 = :t_16, s = :s, m = :m, l = :l, xl = :xl, xxl = :xxl, xxxl = :xxxl WHERE id = :id;");
			$query->execute($POST);

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
	function delete_medida($id)
	{
		include("env.php");
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("DELETE FROM medidas WHERE id = :id;");
			$query->bindParam(":id", $id);
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
	function edit_complemento($POST)
	{
		include("env.php");
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE complementos SET titulo = :titulo, complemento = :complemento WHERE id = :id;");
			$query->execute($POST);

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
	function delete_complemento($id)
	{
		include("env.php");
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("DELETE FROM complementos WHERE id = :id;");
			$query->bindParam(":id", $id);
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
	function guardar_complemento($POST)
	{

		include("env.php");
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO complementos(titulo, complemento, code_producto) VALUES (:titulo, :complemento, :code_producto)");
			$query->execute($POST);

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
	/****************************************/
	function edit_identificacion($POST)
	{
		include("env.php");
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE identificacion SET titulo = :titulo, complemento = :complemento WHERE id = :id;");
			$query->execute($POST);

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
	function delete_identificacion($id)
	{
		include("env.php");
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("DELETE FROM identificacion WHERE id = :id;");
			$query->bindParam(":id", $id);
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
	function guardar_identificacion($POST)
	{

		include("env.php");
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO identificacion(titulo, complemento, code_producto) VALUES (:titulo, :complemento, :code_producto)");
			$query->execute($POST);

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
	/****************************************/
	function edit_modificacion($POST)
	{
		include("env.php");
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE modificaciones SET titulo = :titulo, aprobado_por = :aprobado_por, ultima_modificacion = :ultima_modificacion WHERE id = :id;");
			$query->execute($POST);

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
	function delete_modificacion($id)
	{
		include("env.php");
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("DELETE FROM modificaciones WHERE id = :id;");
			$query->bindParam(":id", $id);
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
	function guardar_modificacion($POST)
	{

		include("env.php");
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO modificaciones(titulo, aprobado_por, ultima_modificacion, code_producto) VALUES (:titulo, :aprobado_por, :ultima_modificacion, :code_producto)");
			$query->execute($POST);

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
	function guardar_observacion($POST){
		include("env.php");
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("INSERT INTO observaciones(observacion, detalle, code_producto) VALUES (:titulo, :detalle, :code_producto)");
			$query->execute($POST);

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
	function get_modificacion($codigo_producto)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("SELECT * FROM modificaciones WHERE code_producto = :code_producto");
			$query->bindParam(":code_producto", $codigo_producto);
			$query->execute();

			$values = array();

			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}

			$mbd->commit();
			$result = array(
				'Result' => 'OK',
				'Records' => $values
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
	function guardar_adjunto($POST)
	{
		include('env.php');
		$result_ = array();
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query_c = $mbd->prepare("SELECT COUNT(*) as cant FROM fecha_tecnica_archivo WHERE id_producto = :id_producto");
			$query_c->bindParam(":id_producto", $POST['num_modelo']);
			$query_c->execute();

			$cant = $query_c->fetch(PDO::FETCH_ASSOC);

			if ($cant['cant'] > 0) {
				$query = $mbd->prepare("UPDATE fecha_tecnica_archivo SET archivo = :archivo WHERE id_producto = :code_producto");
				$query->bindParam(":code_producto", $POST['num_modelo']);
				$query->bindParam(":archivo", $POST['archivo']);
				$query->execute();
			} else {
				$query = $mbd->prepare("INSERT INTO fecha_tecnica_archivo(id_producto, archivo) VALUES (:id_producto, :archivo)");
				$query->bindParam(":id_producto", $POST['num_modelo']);
				$query->bindParam(":archivo", $POST['archivo']);
				$query->execute();
			}



			$mbd->commit();
			$result = array(
				'Result' => 'OK',
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
	function get_archivo_adjunto($num_modelo)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query_c = $mbd->prepare("SELECT * FROM fecha_tecnica_archivo WHERE id_producto = :id_producto");
			$query_c->bindParam(":id_producto", $num_modelo);
			$query_c->execute();
			$array = $query_c->fetch(PDO::FETCH_ASSOC);

			$mbd->commit();

			return json_encode($array);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function get_observaciones($code_producto) {
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("SELECT * FROM observaciones WHERE code_producto = :code_producto");
			$query->bindParam(":code_producto", $code_producto);
			$query->execute();
			// $array = $query_c->fetch(PDO::FETCH_ASSOC);

			// $mbd->commit();

			$values = array();

			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}

			$mbd->commit();
			$result = array(
				'Result' => 'OK',
				'Records' => $values
			);
			return json_encode($result);

			// return json_encode($array);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
}
