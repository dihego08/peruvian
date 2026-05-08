<?php
	include("clsCargos.php");
	$cargo = new clsCargos;
	$accion = $_GET['parAccion'];
	if($accion == "llenar_clientes"){
		echo $cargo->llenar_clientes();
	}elseif ($accion == "lista_cargos") {
		echo $cargo->lista_cargos();
	}elseif ($accion == "guardar_cargo") {
		echo $cargo->guardar_cargo($_GET);
	}elseif ($accion == "editar") {
		echo $cargo->editar($_GET['id']);
	}elseif ($accion == "actualizar_cargo") {
		echo $cargo->actualizar_cargo($_GET);
	}elseif ($accion == "eliminar") {
		echo $cargo->eliminar($_GET['id']);
	}
?>