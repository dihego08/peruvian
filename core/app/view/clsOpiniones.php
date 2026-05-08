<?php
	class clsOpiniones{
		function get_all_opiniones(){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

			  	$query = $mbd->prepare("SELECT o.*, p.name FROM opiniones as o, user as p WHERE o.id_cliente = p.id");
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
			}catch (Exception $e) {
			  	$mbd->rollBack();
			  	$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}
		}
		function get_mi_opiniones($id_cliente){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

			  	$query = $mbd->prepare("SELECT o.*, p.name FROM opiniones as o, user as p WHERE o.id_cliente = p.id AND o.id_cliente = :id_cliente");
			  	$query->bindParam(":id_cliente", $id_cliente);
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
			}catch (Exception $e) {
			  	$mbd->rollBack();
			  	$result = array(
	            	'Result' => $e->getMessage()
	            );
	            return json_encode($result);
			}
		}
		function save_mi_opinion($POST){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

			  	$query = $mbd->prepare("INSERT INTO opiniones(id_cliente, pedido, opinion, estado) VALUES(:id_cliente, :pedido, :opinion, :estado);");
			  	$query->execute($POST);

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
		function fill_cliente(){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

			  	$query = $mbd->prepare("SELECT * FROM user WHERE id NOT IN(1, 2)");
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