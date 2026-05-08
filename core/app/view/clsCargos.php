<?php
	class clsCargos{
		function llenar_clientes(){
			include("env.php");
			$query = $mbd->prepare("SELECT id, name FROM person WHERE kind = 1");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}
			$result = array(
				'Result' => "OK",
				'Records' => $values 
			);
			return json_encode($result);
		}
		function lista_cargos(){
			$cargos = array();
			include("env.php");
			$query = $mbd->prepare("SELECT * FROM cargos");
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$cliente = $mbd->prepare("SELECT name FROM person WHERE id = :id");
				$cliente->execute(array('id' => $res['id_referencia']));
				$cliente_ = $cliente->fetch(PDO::FETCH_ASSOC);

				$referencia = "";
				if($cliente_['name'] == "" || empty($cliente_['name']) || $cliente_['name'] == null){
					$referencia = "";
				}else{
					$referencia = $cliente_['name'];
				}

				$values[] = array(
					'id' => $res['id'],
					'cargo' => $res['cargo'],
					'cliente' => $referencia,
				);
			}
			$result = array(
				'Result' => "OK",
				'Records' => $values 
			);
			return json_encode($result);
		}
		function guardar_cargo($GET){
			include("env.php");
			
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				
				$query = $mbd->prepare("INSERT INTO cargos(cargo, id_referencia) VALUES(:cargo, :id_referencia);");
				$query->bindParam(":cargo", $GET['cargo']);
				$query->bindParam(":id_referencia", $GET['id_referencia']);
				$query->execute();

				$mbd->commit();
				$result = array(
	            	'Result' => "OK"
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
		function actualizar_cargo($GET){
			include("env.php");
			
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				
				$query = $mbd->prepare("UPDATE cargos SET cargo = :cargo, id_referencia = :id_referencia WHERE id = :id;");
				$query->bindParam(":cargo", $GET['cargo']);
				$query->bindParam(":id_referencia", $GET['id_referencia']);
				$query->bindParam(":id", $GET['id']);
				$query->execute();

				$mbd->commit();
				$result = array(
	            	'Result' => "OK"
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
		function editar($id){
			include("env.php");
			$query = $mbd->prepare("SELECT * FROM cargos WHERE id = :id");
			$query->bindParam(":id", $id);
			$query->execute();
			return json_encode($query->fetch(PDO::FETCH_ASSOC));
		}
		function eliminar($id){

			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				
				$query = $mbd->prepare("DELETE FROM cargos WHERE id = :id;");
				$query->bindParam(":id", $id);
				$query->execute();

				$mbd->commit();
				$result = array(
	            	'Result' => "OK"
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