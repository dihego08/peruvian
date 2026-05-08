<?php
	class clsImpuestos{
		function listar_impuestos(){
			include('env.php');
		}
		function guardar_cuenta($concepto, $fecha, $periodo, $monto, $tipo){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("INSERT INTO `sunat`(`concepto`, `periodo`, `tipo`, `fecha`, `monto`) VALUES (:concepto, :periodo, :tipo, :fecha, :monto)");
				$query->bindParam(':concepto', $concepto);
				$query->bindParam(':periodo', $periodo);
				$query->bindParam(':tipo', $tipo);
				$query->bindParam(':fecha', $fecha);
				$query->bindParam(':monto', $monto);
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
		function lista_abonos(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM sunat WHERE tipo = 'abono'");
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
		function lista_cargos(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM sunat WHERE tipo = 'cargo'");
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
		function eliminar($id){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				
				$query = $mbd->prepare("DELETE FROM sunat WHERE id = :id");
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
		function editar($id, $tipo){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				
				$query = $mbd->prepare("UPDATE sunat SET tipo = :tipo WHERE id = :id");
				$query->bindParam(':tipo', $tipo);
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
		function saldo(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM sunat");
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
?>