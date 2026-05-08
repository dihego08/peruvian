<?php
	include('clsUnidades.php');
	$unidad = new clsUnidades;
	$accion = $_GET['parAccion'];
	if ($accion == 'lista_unidades') {
		echo $unidad->lista_unidades();
	}elseif ($accion == 'agregar_unidad') {
		echo $unidad->agregar_unidad($_GET['codigo'], $_GET['unidad']);
	}elseif ($accion == 'eliminar') {
		echo $unidad->eliminar($_GET['codigo']);
	}elseif ($accion == "editar") {
		echo $unidad->editar($_POST['codigo']);
	}elseif ($accion == "actualizar_unidad") {
		echo $unidad->actualizar_unidad($_POST);
	}
?>