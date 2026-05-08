<?php
	include('clsOpiniones.php');
	$opinion = new clsOpiniones;
	$accion = $_GET['parAccion'];
	if($accion == 'get_all_opiniones'){
		echo $opinion->get_all_opiniones();
	}elseif ($accion == "get_mi_opiniones") {
		echo $opinion->get_mi_opiniones($_POST['id_cliente']);
	}elseif ($accion == "save_mi_opinion") {
		echo $opinion->save_mi_opinion($_POST);
	}elseif ($accion == "fill_cliente") {
		echo $opinion->fill_cliente();
	}
?>