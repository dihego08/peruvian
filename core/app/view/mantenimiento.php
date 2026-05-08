<?php
	include('clsMantenimiento.php');
	$accion = $_GET['parAccion'];
	$mtto = new clsMantenimiento;
	if ($accion == 'agregar_mtto') {
		echo $mtto->agregar_mtto($_GET['tipo'],$_GET['fecha'], $_GET['responsable'], $_GET['observacion'], $_GET['maquina_id'], $_GET['costo'], $_GET['tipo_mantenimiento']);
	}elseif($accion == 'lista_mttos'){
		echo $mtto->lista_mttos($_GET['maquina_id']);
	}elseif($accion == 'eliminar'){
		echo $mtto->eliminar($_GET['id']);
	}elseif ($accion == "editar_mantenimiento") {
		echo $mtto->editar_mantenimiento($_POST['id']);
	}elseif ($accion == "actualizar_mtto") {
		echo $mtto->actualizar_mtto($_GET);
	}elseif ($accion == "agregar_registro") {
		echo $mtto->agregar_registro($_POST);
	}elseif ($accion == "lista_registros") {
		echo $mtto->lista_registros($_GET['id_dispositivo']);
	}elseif ($accion == "actualizar_registro") {
		echo $mtto->actualizar_registro($_POST);
	}elseif ($accion == "editar_registro") {
		echo $mtto->editar_registro($_POST['id']);
	}elseif ($accion = "eliminar_registro") {
		echo $mtto->eliminar_registro($_GET['id']);
	}
?>