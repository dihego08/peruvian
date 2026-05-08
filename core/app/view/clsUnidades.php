<?php
	class clsUnidades{
		function lista_unidades(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM unidades");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array('Result' => 'OK', 'Records' => $values);
			return json_encode($result);
		}
		function agregar_unidad($codigo, $unidad){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("INSERT INTO unidades(codigo, unidad) VALUES(:codigo, :unidad)");
				$query->bindParam(':codigo', $codigo);
				$query->bindParam(':unidad', $unidad);
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
		function eliminar($codigo){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("DELETE FROM unidades WHERE codigo = :codigo");
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
		function editar($codigo)
		{
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM unidades WHERE codigo = :codigo");
			$query->bindParam(":codigo", $codigo);
			$query->execute();

			return json_encode($query->fetch(PDO::FETCH_ASSOC));
		}
		function actualizar_unidad($POST)
		{
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("UPDATE unidades SET codigo = :codigo, unidad = :unidad WHERE codigo = :codigo_");
				$query->bindParam(':codigo_', $POST['codigo_']);
				$query->bindParam(':codigo', $POST['codigo']);
				$query->bindParam(':unidad', $POST['unidad']);
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