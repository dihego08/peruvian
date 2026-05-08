<?php
	include('clsCuentas_pagar.php');
	$accion = $_GET['parAccion'];
	$cuenta = new clsCuentas_pagar;
	if ($accion == 'lista_cuentas') {
		echo $cuenta->lista_cuentas();
	}elseif ($accion == 'guardar_cuenta') {
		echo $cuenta->guardar_cuenta($_GET['concepto'], $_GET['fecha_vencimiento'], $_GET['prioridad'], $_GET['monto'], $_GET['estado']);
	}elseif ($accion == 'pagar_cuenta') {
		echo $cuenta->pagar_cuenta($_GET['id'], $_GET['retiro']);
	}elseif ($accion == 'guardar_retiro') {
		echo $cuenta->guardar_retiro($_GET['concepto'], $_GET['monto'], $_GET['fecha'], $_GET['tipo']);
	}elseif ($accion == 'lista_retiros_2') {
		echo $cuenta->lista_retiros_2();
	}elseif ($accion == 'lista_retiros') {
		echo $cuenta->lista_retiros();
	}elseif ($accion == 'lista_pagos') {
		echo $cuenta->lista_pagos($_GET['id']);
	}elseif ($accion == 'buscar_mes') {
		echo $cuenta->buscar_mes($_GET['mes'], $_GET['anio']);
	}
?>