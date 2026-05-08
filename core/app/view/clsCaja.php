<?php
	class clsCaja{
		function listar_caja(){
			include('env.php');
		}
		function guardar_caja_mov($concepto, $fecha_pago, $periodo, $monto, $tipo,$fecha_vencimiento,$estado,$prioridad,$caja_id,$banco_cuenta_id){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();

				$query = $mbd->prepare("INSERT INTO caja_mov(concepto, periodo, tipo, fecha_registro, monto,estado,prioridad,fecha_vencimiento,fecha_pago,caja_id,banco_cuenta_id,saldo) VALUES (:concepto, :periodo, :tipo, now(), :monto,:estado,:prioridad,:fecha_vencimiento,:fecha_pago,:caja_id,:banco_cuenta_id,:saldo)");
				$query->bindParam(':concepto', $concepto);
				$query->bindParam(':periodo', $periodo);
				$query->bindParam(':tipo', $tipo);
				$query->bindParam(':monto', $monto);
				$query->bindParam(':estado', $estado);
				$query->bindParam(':prioridad', $prioridad);
				$query->bindParam(':fecha_vencimiento', $fecha_vencimiento);
				$query->bindParam(':fecha_pago', $fecha_pago);
				$query->bindParam(':caja_id', $caja_id);
				$query->bindParam(':banco_cuenta_id', $banco_cuenta_id);
				$query->bindParam(':saldo', $monto);

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
		function lista_abonos($caja_id){
			include('env.php');
			$query = $mbd->prepare("SELECT id, concepto, periodo, tipo, DATE_FORMAT(fecha_registro, '%d-%m-%Y') as fecha_registro, monto, estado, prioridad, id_retiro, monto_retiro, fecha_vencimiento,  DATE_FORMAT(fecha_pago, '%d-%m-%Y') as fecha_pago, caja_id, banco_cuenta_id, saldo FROM caja_mov WHERE tipo = 'abono' and (isnull(id_retiro) or saldo > 0) and caja_id = :caja_id");
			$query->bindParam(':caja_id', $caja_id);
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

		function lista_kardex($caja_id){
			include('env.php');
			$query = $mbd->prepare("SELECT id, caja_id, kardex_tipo, caja_mov_id, abono_banco, abono_periodo, DATE_FORMAT(abono_fecha, '%d-%m-%Y') as abono_fecha, abono_monto, DATE_FORMAT(cargo_fecha, '%d-%m-%Y') as cargo_fecha, cargo_concepto, cargo_periodo, cargo_monto, cargo_saldo, cargo_abono_id FROM caja_kardex WHERE caja_id = :caja_id");
			$query->bindParam(':caja_id', $caja_id);
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

		function filtrar_kardex($caja_id,$desde,$hasta){
			include('env.php');
			$query = $mbd->prepare("SELECT id, caja_id, kardex_tipo, caja_mov_id, abono_banco, abono_periodo, DATE_FORMAT(abono_fecha, '%d-%m-%Y') as abono_fecha, abono_monto, DATE_FORMAT(cargo_fecha, '%d-%m-%Y') as cargo_fecha, cargo_concepto, cargo_periodo, cargo_monto, cargo_saldo, cargo_abono_id FROM caja_kardex WHERE caja_id = :caja_id and abono_fecha between :desde and :hasta");
			$query->bindParam(':caja_id', $caja_id);
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

		function lista_cargos($caja_id){
			include('env.php');
			$query = $mbd->prepare("SELECT id, concepto, periodo, tipo, DATE_FORMAT(fecha_registro, '%d-%m-%Y') as fecha_registro, monto, estado, prioridad, id_retiro, monto_retiro, fecha_vencimiento, DATE_FORMAT(fecha_pago, '%d-%m-%Y') as fecha_pago, caja_id, banco_cuenta_id, saldo FROM caja_mov WHERE tipo = 'cargo' and estado = '0' and caja_id = :caja_id");
			$query->bindParam(':caja_id', $caja_id);
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
				
				$query = $mbd->prepare("DELETE FROM caja_mov WHERE id = :id");
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
		function eliminar_kardex($id,$tipo,$abonoId){
			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				
				$query = $mbd->prepare("DELETE FROM caja_kardex WHERE caja_mov_id = :id");
				$query->bindParam(':id', $id);
				$query->execute();

				if($tipo == 1)
				{
					$queryAbono = $mbd->prepare("UPDATE caja_mov set id_retiro = null,monto_retiro = 0,saldo = monto  WHERE id = :id");
					$queryAbono->bindParam(':id', $id);
					$queryAbono->execute();

					$queryAbono2 = $mbd->prepare("DELETE from caja_abono_mov  WHERE caja_mov_id = :id");
					$queryAbono2->bindParam(':id', $id);
					$queryAbono2->execute();
				}
				elseif($tipo == 2)
				{
					$queryCargo = $mbd->prepare("UPDATE caja_mov set estado = '0'  WHERE id = :id");
					$queryCargo->bindParam(':id', $id);
					$queryCargo->execute();

					$queryCargo2 = $mbd->prepare("DELETE from caja_abono_mov  WHERE caja_retiro_id = :id");
					$queryCargo2->bindParam(':id', $id);
					$queryCargo2->execute();

					$queryCargo3 = $mbd->prepare("UPDATE caja_mov  m set m.monto_retiro = (select sum(a.monto) from caja_abono_mov a where a.caja_mov_id = m.id) where id = '". $abonoId ."'");
					$queryCargo3->bindParam(':id', $id);
					$queryCargo3->execute();

					$queryCargo4 = $mbd->prepare("UPDATE caja_mov  set saldo = round(monto - monto_retiro,2) where id = '". $abonoId ."'");
					$queryCargo4->bindParam(':id', $id);
					$queryCargo4->execute();
				}

				

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
				
				$query = $mbd->prepare("UPDATE caja_mov SET tipo = :tipo WHERE id = :id");
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


		function pagar_cargo($id,$abonos,$montos){

			$abonos = explode(',',$abonos);
			$montos = explode(',',$montos);

			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				
				$query = $mbd->prepare("UPDATE caja_mov SET estado = '1' WHERE id = :id");
				$query->bindParam(':id', $id);
				$query->execute();

				for ($i = 1; $i <= count($abonos) ; $i++) {

					if($abonos[$i] != '')
					{

						//TODO: QUE JALE EL SALDO ACTUAL CON UNA CONSUTLA Y DEALLI LO ACTUALIZE, COO ESTA PRESNETA RPORBELMAS

						$query2 = $mbd->prepare("UPDATE caja_mov SET id_retiro = :id,monto_retiro = round((monto_retiro +:monto),2),saldo = round(saldo - :monto,2) where id = :abono_id");
						$query2->bindParam(':id', $id); 
						$query2->bindParam(':monto', $montos[$i]);
						$query2->bindParam(':abono_id', $abonos[$i]);
						$query2->execute();

						$query3 = $mbd->prepare("INSERT INTO caja_abono_mov(caja_mov_id,caja_retiro_id,monto) VALUES(:abono_id,:id,:monto)");
						$query3->bindParam(':id', $id); 
						$query3->bindParam(':monto', $montos[$i]);
						$query3->bindParam(':abono_id', $abonos[$i]);
						$query3->execute();
					}
				}



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

		function unir_saldo_abonos($abonos,$montos){

			$abonos = explode(',',$abonos);
			$montos = explode(',',$montos);

			include('env.php');
			try {  
				$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  	$mbd->beginTransaction();
				
				$saldo = 0;

				for ($i = 1; $i <= count($abonos) ; $i++) {

					//if($abonos[$i] != '')
					//{
						$saldo = $saldo + $montos[$i];
						if($i == (count($abonos) - 1))
						{
							$query2 = $mbd->prepare("UPDATE caja_mov SET monto_retiro = 0,saldo = :saldo where id = :abono_id");
							$query2->bindParam(':saldo', $saldo);
							$query2->bindParam(':abono_id', $abonos[$i]);
							$query2->execute();
						}
						else
						{
							$query2 = $mbd->prepare("UPDATE caja_mov SET monto_retiro = :monto,saldo = 0 where id = :abono_id");
							$query2->bindParam(':monto', $montos[$i]);
							$query2->bindParam(':abono_id', $abonos[$i]);
							$query2->execute();
						}
						
					//}
				}



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

		function saldo($caja_id){
			include('env.php');
			$query = $mbd->prepare("SELECT id, concepto, periodo, tipo, DATE_FORMAT(fecha_registro, '%d-%m-%Y') as fecha_registro, monto, estado, prioridad, id_retiro, monto_retiro, fecha_vencimiento, DATE_FORMAT(fecha_pago, '%d-%m-%Y') as fecha_pago, caja_id, banco_cuenta_id, saldo FROM caja_mov where estado = '1' and caja_id = :caja_id");
			$query->bindParam(':caja_id', $caja_id);
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

		function combo_cajas(){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM caja");
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

		function combo_banco_cuentas_por_caja($caja_id){
			include('env.php');
			$query = $mbd->prepare("SELECT * FROM banco_cuenta");
			$query->bindParam(':caja_id', $caja_id);
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