<?php
	include('clsFam_class.php');
	$accion = $_GET['parAccion'];
	$fam_class = new clsFam_class;
	if ($accion == 'lista_clases') {
		echo $fam_class->lista_clases();
	}elseif ($accion == 'lista_familias') {
		echo $fam_class->lista_familias();
	}elseif($accion == 'agregar_clase'){
		echo $fam_class->agregar_clase($_GET['codigo'],$_GET['descripcion']);
	}elseif ($accion == 'agregar_familia') {
		echo $fam_class->agregar_familia($_GET['codigo'],$_GET['descripcion']);
	}elseif ($accion == 'detalle_clase') {
		echo $fam_class->detalle_clase($_GET['id']);
	}elseif ($accion == 'detalle_familia') {
		echo $fam_class->detalle_familia($_GET['id']);
	}elseif ($accion == 'actualizar_clase') {
		echo $fam_class->actualizar_clase($_GET['id'],$_GET['codigo'],$_GET['descripcion']);
	}elseif ($accion == 'actualizar_familia') {
		echo $fam_class->actualizar_familia($_GET['id'], $_GET['descripcion'],$_GET['aid']);
	}elseif ($accion == 'eliminar_clase') {
		echo $fam_class->eliminar_clase($_GET['id'],$_GET['codigo']);
	}elseif ($accion == 'eliminar_familia') {
		echo $fam_class->eliminar_familia($_GET['id']);
	}elseif ($accion == 'lista_subclases') {
		echo $fam_class->lista_subclases();
	}elseif ($accion == 'agregar_subclase') {
		echo $fam_class->agregar_subclase($_GET['codigo'], $_GET['descripcion']);
	}elseif ($accion == 'detalle_subclase') {
		echo $fam_class->detalle_subclase($_GET['id']);
	}elseif ($accion == 'actualizar_subclase') {
		echo $fam_class->actualizar_subclase($_GET['id'], $_GET['codigo'], $_GET['descripcion']);
	}elseif ($accion == 'eliminar_subclase') {
		echo $fam_class->eliminar_subclase($_GET['id']);
	}

	
	
	
?>