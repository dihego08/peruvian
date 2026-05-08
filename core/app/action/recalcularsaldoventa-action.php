<?php
    $conn = mysqli_connect("localhost","peruvian_sivec","Ow93BFv5zK9v","peruvian_sivecsol");
    //mysqli_select_db($conn);

    $ventaId = $_GET['vid'];
    $clienteId = $_GET['cid'];

    $sql  = "SELECT * FROM pagos WHERE codigo_venta = '$ventaId' AND id_person = '$clienteId' ORDER BY fecha_creacion ASC";
    $query = mysqli_query($conn,$sql);

    $sqlVenta = "Select vc.* from ventas_cabecera vc where vc.codigo_venta = '$ventaId'";
    $queryVenta = mysqli_query($conn,$sqlVenta);

    while($venta = mysqli_fetch_assoc($queryVenta)){
	    $valor_pagar = $venta['valor_pagar'];
    }

    $cont = 1;
	$saldo = $valor_pagar;
	$pagado = 0;
	while ($pagos = mysqli_fetch_assoc($query)) {
		if($cont == 1){
			$saldo = $pagos['total'];
		}
		$pagado = $pagado + $pagos['pago'];
		$saldo = $saldo - $pagos['pago'];
		$pagoId = $pagos['id'];
		$sql2 = "UPDATE pagos set deuda = $saldo where id = $pagoId";
		$query2 = mysqli_query($conn,$sql2);
		//mysqli_free_result($query2);
		//echo "id : " .$pagoId;
		//$query3 = $mbd->prepare("UPDATE pagos set deuda = :v1 where id = :v2");
		//$query3->bindValue(':v1', $saldo);
		//$query3->bindValue(':v2', $pagoId);
		//$query3->execute();
		$cont++;
    }

    $sql3 = "UPDATE ventas_cabecera SET pagado = $pagado, a_cuenta = $saldo WHERE codigo_venta = '$ventaId'";
    //echo($sql3);
    mysqli_query($conn,$sql3);
    //mysqli_free_result($query);
    /* cerrar la conexión */
    mysqli_close($conn);
    //header("Location:https://www.peruviandress.com/sistema/index.php?view=detalle_pago&cid=$clienteId&vid=$ventaId")
    echo '<script>
        window.history.back();
    </script>';
?>