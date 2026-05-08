<?php
	include('clsImpuestos.php');
	$accion = $_GET['parAccion'];
	$impuesto = new clsImpuestos;
	if ($accion == 'guardar_cuenta') {
		echo $impuesto->guardar_cuenta($_GET['concepto'], $_GET['fecha'], $_GET['periodo'], $_GET['monto'], $_GET['tipo']);
	}elseif($accion == 'lista_abonos'){
		echo $impuesto->lista_abonos();
	}elseif($accion == 'lista_cargos'){
		echo $impuesto->lista_cargos();		
	}elseif ($accion == 'eliminar') {
		echo $impuesto->eliminar($_GET['id']);
	}elseif ($accion == 'editar') {
		echo $impuesto->editar($_GET['id'], $_GET['tipo']);
	}elseif ($accion == 'saldo') {
		echo $impuesto->saldo();
	}
?>