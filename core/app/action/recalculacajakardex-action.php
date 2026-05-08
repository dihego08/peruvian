<?php
$conn = mysqli_connect("localhost","peruvian_sivec","Ow93BFv5zK9v","peruvian_sivecsol");
//mysqli_select_db($conn);

$cajaId = $_GET['cid'];


$sqldel  = "delete from caja_kardex";
$query = mysqli_query($conn,$sqldel);


$sql  = "SELECT * FROM caja_mov WHERE caja_id = '$cajaId' and tipo = 'abono' ORDER BY fecha_pago ASC";
$query = mysqli_query($conn,$sql);

/*
$sqlCajaMov = "Select vc.* from ventas_cabecera vc where vc.codigo_venta = '$ventaId'";
$queryCajaMov = mysqli_query($conn,$sqlCajaMov);

while($venta = mysqli_fetch_assoc($queryCajaMov)){
	$valor_pagar = $venta['valor_pagar'];
}
*/

$cont = 1;
					$saldo = 0.00;
					$tipo = 1;
					while ($pagos = mysqli_fetch_assoc($query)) {

						
							$saldo = $saldo + $pagos['monto'];
							$sql2 = "INSERT INTO caja_kardex(caja_id,kardex_tipo, caja_mov_id, abono_banco, abono_periodo, abono_fecha, abono_monto,cargo_saldo) VALUES ('".$pagos['caja_id']."','1','".$pagos['id']."','".$pagos['concepto']."','".$pagos['periodo']."','".$pagos['fecha_pago']."','".$pagos['monto']."','".$saldo."')";
							$query2 = mysqli_query($conn,$sql2);

							//verificamos los cargos de esos abonos
								$sql3  = "SELECT c.*,m.tipo as mtipo,m.caja_id as mcaja_id,m.id as mid,m.concepto as mconcepto,m.periodo as mperiodo,m.monto as mmonto,m.fecha_pago as mfecha FROM caja_abono_mov c INNER JOIN caja_mov m ON c.caja_retiro_id = m.id WHERE c.caja_mov_id = '".$pagos['id']."' ORDER BY fecha ASC";
								//echo($sql3);
								$query3 = mysqli_query($conn,$sql3);
								while($cargos = mysqli_fetch_assoc($query3))
								{
									$saldo = $saldo - $cargos['monto'];

									$sql4 = "INSERT INTO caja_kardex(caja_id,kardex_tipo, caja_mov_id,cargo_fecha, cargo_concepto, cargo_periodo, cargo_monto, cargo_saldo,cargo_abono_id,abono_fecha) VALUES ('".$cargos['mcaja_id']."','2','".$cargos['mid']."','".$cargos['mfecha']."','".$cargos['mconcepto']."','".$cargos['mperiodo']."','".$cargos['monto']."',round(".$saldo.",2),'".$pagos['id']."','".$cargos['mfecha']."')";
									//echo($sql4);
									$query4 = mysqli_query($conn,$sql4);
								}
						
						$cont++;
					}

//$sql3 = "UPDATE ventas_cabecera SET pagado = $pagado, a_cuenta = $saldo WHERE codigo_venta = '$ventaId'";
//echo($sql3);
//mysqli_query($conn,$sql3);
mysqli_close($conn);
//echo "realizado";
header("Location:http://www.peruviandress.com/sivecsol/index.php?view=cajakardex&cid=".$cajaId);
?>