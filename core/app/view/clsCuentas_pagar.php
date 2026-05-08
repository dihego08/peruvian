<?php
	class clsCuentas_pagar{
		function lista_cuentas(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM cuentas_pagar ORDER BY fecha_vencimiento DESC");
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
		function guardar_cuenta($concepto, $fecha_vencimiento, $prioridad, $monto, $estado){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				$query = $mbd->prepare("INSERT INTO cuentas_pagar(concepto, monto, estado, prioridad, fecha_vencimiento) VALUES(:concepto, :monto, :estado, :prioridad, :fecha_vencimiento)");
				$query->bindParam(':concepto', $concepto);
				$query->bindParam(':monto', $monto);
				$query->bindParam(':estado', $estado);
				$query->bindParam(':prioridad', $prioridad);
				$query->bindParam(':fecha_vencimiento', $fecha_vencimiento);
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
		function pagar_cuenta($id, $retiro){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				$query = $mbd->prepare("UPDATE cuentas_pagar SET estado = 1, id_retiro = :retiro, fecha_pago = CURDATE() WHERE id = :id");
				$query->bindParam(':id', $id);
				$query->bindParam(':retiro', $retiro);
				$query->execute();

				$query_m = $mbd->prepare("SELECT monto FROM cuentas_pagar WHERE id = :id");
				$query_m->bindParam(':id', $id);
				$query_m->execute();
				$m = $query_m->fetch(PDO::FETCH_ASSOC);

				$query_m_s = $mbd->prepare("SELECT * FROM retiro WHERE saldo > 0 ORDER BY id DESC");
				$query_m->bindParam(':retiro', $retiro);
				$query_m_s->execute();
				$aux = $m['monto'];

				while ($res = $query_m_s->fetch(PDO::FETCH_ASSOC)) {
					/*echo "AUX: ".$aux."<br>";
					echo "Saldo: ".$res['saldo']."<br>";*/
					if($aux > $res['saldo']){
						$aux = $aux - $res['saldo'];

						//echo "NUEVO :".$aux."<br/>";

						$query_u = $mbd->prepare("UPDATE retiro SET saldo = :monto WHERE id = :retiro");
						$i = 0;
						$query_u->bindParam(':monto', $i);
						$query_u->bindParam(':retiro', $res['id']);
						$query_u->execute();
					}else{
						//echo "NUEVO 2 :".$aux."<br/>";
						$query_u = $mbd->prepare("UPDATE retiro SET saldo = saldo - :monto WHERE id = :retiro");
						$query_u->bindParam(':monto', $aux);
						$query_u->bindParam(':retiro', $res['id']);
						$query_u->execute();
					}
				}
				
				$query_s = $mbd->prepare("UPDATE s SET saldo = saldo - :monto");
				$query_s->bindParam(':monto', $m['monto']);
				$query_s->execute();

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
		function guardar_retiro($concepto, $monto, $fecha, $tipo){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				$query = $mbd->prepare("INSERT INTO retiro(concepto, monto, fecha, saldo, tipo) VALUES(:concepto, :monto, :fecha, :monto, :tipo)");
				$query->bindParam(':concepto', $concepto);
				$query->bindParam(':monto', $monto);
				$query->bindParam(':tipo', $tipo);
				$query->bindParam(':fecha', $fecha);
				$query->execute();

				$query_s = $mbd->prepare("UPDATE s SET saldo = saldo + :monto");
				$query_s->bindParam(':monto', $monto);
				$query_s->execute();

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
		function lista_retiros_2(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM retiro WHERE saldo > 0 ");
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
		function lista_retiros(){
			$mes = date("m");
			$fec = date("Y-m-d");
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM retiro WHERE MONTH(fecha) BETWEEN :menos AND :mas ORDER BY fecha DESC");
			$auu = strtotime(($fec."- 2 month"));
			$menos = date("m", strtotime($auu));
			$query->bindParam(':menos', $menos);
			$query->bindParam(':mas', $mes);
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
		function lista_pagos($id){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM cuentas_pagar where id_retiro = :id");
			$query->bindParam(':id', $id);
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
		function buscar_mes($mes, $anio){
			include('env.php');
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM retiro WHERE MONTH(fecha) = :mes AND YEAR(fecha) = :anio ORDER BY fecha DESC");
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
?>