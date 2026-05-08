<?php
	class clsMantenimiento{
		
		function agregar_mtto($tipo, $fecha, $responsable, $observacion, $maquina_id, $costo, $tipo_mantenimiento){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("INSERT INTO tbl_maq_mtto(maq_mtto_tipo, maq_mtto_fecha, maq_mtto_reponsable, maq_mtto_observacion, maquina_id, maq_mtto_costo, tipo_mantenimiento) VALUES (:tipo,:fecha,:responsable,:observacion,:maquina_id, :costo, :tipo_mantenimiento);");
				$query->bindParam(':tipo', $tipo);
				$query->bindParam(':fecha', $fecha);
				$query->bindParam(':responsable', $responsable);
				$query->bindParam(':observacion', $observacion);
				$query->bindParam(':maquina_id', $maquina_id);
				$query->bindParam(':costo', $costo);
				$query->bindParam(':tipo_mantenimiento', $tipo_mantenimiento);
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
		function agregar_registro($POST)
		{
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("INSERT INTO registro_dispositivo(id_dispositivo, fecha_entrega, recibido_por, cantidad, observaciones, responsable) VALUES (:id_dispositivo, :fecha_entrega, :recibido_por, :cantidad, :observaciones, :responsable);");
				$query->bindParam(':fecha_entrega', $POST['fecha_entrega']);
				$query->bindParam(':recibido_por', $POST['recibido_por']);
				$query->bindParam(':cantidad', $POST['cantidad']);
				$query->bindParam(':observaciones', $POST['observaciones']);
				$query->bindParam(':responsable', $POST['responsable']);

				$query->bindParam(':id_dispositivo', $POST['id_dispositivo']);
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
		function actualizar_mtto($GET)
		{
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("UPDATE tbl_maq_mtto SET maq_mtto_tipo = :tipo, maq_mtto_fecha = :fecha, maq_mtto_reponsable = :responsable, maq_mtto_observacion = :observacion, maq_mtto_costo = :costo, tipo_mantenimiento = :tipo_mantenimiento WHERE maq_mtto_id = :id;");
				$query->bindParam(':tipo', $GET['tipo']);
				$query->bindParam(':fecha', $GET['fecha']);
				$query->bindParam(':responsable', $GET['responsable']);
				$query->bindParam(':observacion', $GET['observacion']);
				$query->bindParam(':id', $GET['id']);
				$query->bindParam(':costo', $GET['costo']);
				$query->bindParam(':tipo_mantenimiento', $GET['tipo_mantenimiento']);
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
		function actualizar_registro($POST)
		{
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("UPDATE registro_dispositivo SET fecha_entrega = :fecha_entrega,recibido_por = :recibido_por,cantidad = :cantidad,observaciones = :observaciones,responsable = :responsable WHERE id = :id");
				$query->bindParam(':fecha_entrega', $POST['fecha_entrega']);
				$query->bindParam(':recibido_por', $POST['recibido_por']);
				$query->bindParam(':cantidad', $POST['cantidad']);
				$query->bindParam(':observaciones', $POST['observaciones']);
				$query->bindParam(':id', $POST['id']);
				$query->bindParam(':responsable', $POST['responsable']);
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
		function eliminar($id){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("DELETE from tbl_maq_mtto where maq_mtto_id = :id;");
				$query->bindParam(':id', $id);
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
		function eliminar_registro($id){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("DELETE from registro_dispositivo where id = :id;");
				$query->bindParam(':id', $id);
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

		function lista_mttos($maquina_id){
			include('env.php');
			$query = $mbd->prepare("SELECT maq_mtto_id, DATE_FORMAT(maq_mtto_fecha, '%d-%m-%Y') as maq_mtto_fecha, maq_mtto_reponsable, maq_mtto_tipo, maq_mtto_observacion, COALESCE(maq_mtto_costo, '0.00') as costo, maquina_id, tipo_mantenimiento FROM tbl_maq_mtto WHERE maquina_id = :maquina_id");
			$query->bindParam(':maquina_id', $maquina_id);
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
		function lista_registros($id_dispositivo)
		{
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM registro_dispositivo WHERE id_dispositivo = :id_dispositivo");
			$query->bindParam(':id_dispositivo', $id_dispositivo);
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
		function editar_mantenimiento($id){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM tbl_maq_mtto WHERE maq_mtto_id = :id");
			$query->bindParam(':id', $id);
			$query->execute();
			
			$res = $query->fetch(PDO::FETCH_ASSOC);
			return json_encode($res);
		}
		function editar_registro($id){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM registro_dispositivo WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->execute();
			
			$res = $query->fetch(PDO::FETCH_ASSOC);
			return json_encode($res);
		}
	}
?>