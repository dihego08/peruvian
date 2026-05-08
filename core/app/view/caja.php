<?php
	include('clsCaja.php');
	$accion = $_GET['parAccion'];
	$caja = new clsCaja;
	if ($accion == 'guardar') {
		echo $caja->guardar_caja_mov($_GET['concepto'], $_GET['fecha_pago'], $_GET['periodo'], $_GET['monto'], $_GET['tipo'],$_GET['fecha_vencimiento'],$_GET['estado'],$_GET['prioridad'],$_GET['caja'],$_GET['cuenta']);
	}elseif($accion == 'lista_abonos'){
		echo $caja->lista_abonos($_GET['caja_id']);
	}elseif($accion == 'lista_cargos'){
		echo $caja->lista_cargos($_GET['caja_id']);		
	}elseif ($accion == 'eliminar') {
		echo $caja->eliminar($_GET['id']);
	}elseif ($accion == 'editar') {
		echo $caja->editar($_GET['id'], $_GET['tipo']);
	}elseif ($accion == 'saldo') {
		echo $caja->saldo($_GET['caja_id']);
	}elseif ($accion == 'combo_cajas') {
		echo $caja->combo_cajas();
	}elseif ($accion == 'combo_banco_cuentas') {
		echo $caja->combo_banco_cuentas_por_caja($_GET['caja_id']);
	}elseif ($accion == 'pagar_cargo') {
		echo $caja->pagar_cargo($_GET['id'],$_GET['abonos'],$_GET['montos']);
	}elseif ($accion == 'unir_saldo_abonos') {
		echo $caja->unir_saldo_abonos($_GET['abonos'],$_GET['montos']);
	}elseif($accion == 'lista_kardex'){
		echo $caja->lista_kardex($_GET['caja_id']);
	}elseif($accion == 'filtrar_kardex'){
		echo $caja->filtrar_kardex($_GET['caja_id'],$_GET['desde'],$_GET['hasta']);
	}elseif($accion == 'eliminar_kardex'){
		echo $caja->eliminar_kardex($_GET['kardex_id'],$_GET['kardex_tipo'],$_GET['abono_id']);
	}
?>