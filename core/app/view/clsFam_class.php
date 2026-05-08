<?php
	class clsFam_class{
		function lista_subclases(){
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
		function lista_clases(){
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
		function lista_familias(){
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
		function agregar_clase($codigo,$descripcion){
			include('env.php');
			$query = $mbd->prepare("INSERT INTO clases(codigo,descripcion) VALUES(:codigo,:descripcion)");
			$query->bindParam(':codigo', $codigo);
			$query->bindParam(':descripcion', $descripcion);
			$query->execute();
			$result = array('Result' => 'OK');
			return json_encode($result);
		}
		function agregar_subclase($codigo, $descripcion){
			include('env.php');
			
			
			try {
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$mbd->beginTransaction();

				$query = $mbd->prepare("INSERT INTO subclases(codigo, descripcion) VALUES(:codigo, :descripcion)");
    			$query->bindParam(':descripcion', $descripcion);
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
			
			//$result = array('Result' => 'OK');
			//return json_encode($result);
		}
		function agregar_familia($codigo,$descripcion){
			include('env.php');
			$query = $mbd->prepare("INSERT INTO familias(codigo,descripcion) VALUES(:codigo,:descripcion)");
			$query->bindParam(':codigo', $codigo);
			$query->bindParam(':descripcion', $descripcion);
			$query->execute();
			$result = array('Result' => 'OK');
			return json_encode($result);
		}
		function detalle_clase($id){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM clases WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->execute();
			return json_encode($query->fetch(PDO::FETCH_ASSOC));
		}
		function detalle_subclase($id){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM subclases WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->execute();
			return json_encode($query->fetch(PDO::FETCH_ASSOC));
		}
		function detalle_familia($id){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM familias WHERE codigo = :id");
			$query->bindParam(':id', $id);
			$query->execute();
			return json_encode($query->fetch(PDO::FETCH_ASSOC));
		}
		function actualizar_clase($id,$codigo,$descripcion){
			include('env.php');
			$query = $mbd->prepare("UPDATE clases SET descripcion = :descripcion,codigo = :codigo WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->bindParam(':descripcion', $descripcion);
			$query->bindParam(':codigo', $codigo);
			$query->execute();
			$result = array('Result' => 'OK');
			return json_encode($result);
		}
		function actualizar_subclase($id, $codigo, $descripcion){
			include('env.php');
			$query = $mbd->prepare("UPDATE subclases SET descripcion = :descripcion, codigo = :codigo WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->bindParam(':codigo', $codigo);
			$query->bindParam(':descripcion', $descripcion);
			$query->execute();
			$result = array('Result' => 'OK');
			return json_encode($result);
		}
		function actualizar_familia($id, $descripcion,$ant_id){
			include('env.php');
			$query = $mbd->prepare("UPDATE familias SET codigo = :id,descripcion = :descripcion WHERE codigo = :ant_id");
			$query->bindParam(':id', $id);
			$query->bindParam(':descripcion', $descripcion);
			$query->bindParam(':ant_id', $ant_id);
			$query->execute();
			$result = array('Result' => 'OK');
			return json_encode($result);
		}
		function eliminar_clase($id,$codigo){
			include('env.php');
			try {
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$mbd->beginTransaction();

				$query_c = $mbd->prepare("SELECT count(*) as c FROM insumos_2 WHERE clase = :codigo");
			  	$query_c->bindParam(':codigo', $codigo);
			  	$query_c->execute();
			  	$cuenta = $query_c->fetch(PDO::FETCH_ASSOC);

			  	if ($cuenta['c'] > 0) {
			  		$mbd->commit();
					$result = array(
		            	'Result' => 'ERROR'
		            );
		            return json_encode($result);
			  	}else{
			  		$query = $mbd->prepare("DELETE FROM clases WHERE id = :id");
					$query->bindParam(':id', $id);
					$query->execute();

					$mbd->commit();
					$result = array(
		            	'Result' => 'OK'
		            );
		            return json_encode($result);
			  	}

			} catch (Exception $e) {
				$mbd->rollBack();
				$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}
			
		}
		function aux_subclase($id){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM subclases WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->execute();
			$res = $query->fetch(PDO::FETCH_ASSOC);
			return $res['codigo'];
		}
		function eliminar_subclase($id){
			include('env.php');
			$zux = new clsFam_class;
			$cod = $zux->aux_subclase($id);
			try {
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$mbd->beginTransaction();

				$query_c = $mbd->prepare("SELECT count(*) as c FROM insumos_2 WHERE subclase = :cod");
			  	$query_c->bindParam(':cod', $cod);
			  	$query_c->execute();
			  	$cuenta = $query_c->fetch(PDO::FETCH_ASSOC);

			  	if ($cuenta['c'] > 0) {
			  		$mbd->commit();
					$result = array(
		            	'Result' => 'ERROR'
		            );
		            return json_encode($result);
			  	}else{
			  		$query = $mbd->prepare("DELETE FROM subclases WHERE id = :id");
					$query->bindParam(':id', $id);
					$query->execute();

					$mbd->commit();
					$result = array(
		            	'Result' => 'OK'
		            );
		            return json_encode($result);
			  	}

			} catch (Exception $e) {
				$mbd->rollBack();
				$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}
		}
		function eliminar_familia($id){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

			  	$query_c = $mbd->prepare("SELECT count(*) as c FROM insumos_2 WHERE familia = :id");
			  	$query_c->bindParam(':id', $id);
			  	$query_c->execute();
			  	$cuenta = $query_c->fetch(PDO::FETCH_ASSOC);

			  	if ($cuenta['c'] > 0) {
			  		$mbd->commit();
					$result = array(
		            	'Result' => 'ERROR'
		            );
		            return json_encode($result);
			  	}else{
			  		$query = $mbd->prepare("DELETE FROM familias WHERE codigo = :id");
					$query->bindParam(':id', $id);
					$query->execute();

					$mbd->commit();
					$result = array(
		            	'Result' => 'OK'
		            );
		            return json_encode($result);
			  	}
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