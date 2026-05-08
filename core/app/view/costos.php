<?php
/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/
include("clsCostos.php");
$costo = new clsCostos;
$accion = $_GET['parAccion'];

if ($accion == 'data_producto') {
	echo $costo->data_producto($_POST['id_producto']);
} elseif ($accion == "lista_directos") {
	echo $costo->lista_directos($_POST['id_producto']);
} elseif ($accion == "lista_extras") {
	echo $costo->lista_extras($_POST['id_producto']);
} elseif ($accion == "lista_empaques") {
	echo $costo->lista_empaques($_POST['id_producto']);
} elseif ($accion == "guardar_directo") {
	echo $costo->guardar_directo($_POST);
} elseif ($accion == "guardar_extra") {
	echo $costo->guardar_extra($_POST);
} elseif ($accion == "guardar_empaque") {
	echo $costo->guardar_empaque($_POST);
} elseif ($accion == "get_totales") {
	echo $costo->get_totales($_POST['id_producto']);
} elseif ($accion == "lista_mano_directa") {
	echo $costo->lista_mano_directa($_POST['id_producto']);
} elseif ($accion == "lista_uso_taller") {
	echo $costo->lista_uso_taller($_POST['id_producto']);
} elseif ($accion == "lista_bordado") {
	echo $costo->lista_bordado($_POST['id_producto']);
} elseif ($accion == "guardar_mano_directa") {
	echo $costo->guardar_mano_directa($_POST);
} elseif ($accion == "guardar_bordado") {
	echo $costo->guardar_bordado($_POST);
} elseif ($accion == "guardar_uso_taller") {
	echo $costo->guardar_uso_taller($_POST);
} elseif ($accion == "get_totales_2") {
	echo $costo->get_totales_2($_POST['id_producto'], $_POST['status']);
} elseif ($accion == "set_costos") {
	echo $costo->set_costos($_POST);
} elseif ($accion == "editar_directo") {
	echo $costo->editar_directo($_POST['id']);
} elseif ($accion == "actualizar_directo") {
	echo $costo->actualizar_directo($_POST);
} elseif ($accion == "eliminar_directo") {
	echo $costo->eliminar_directo($_POST['id']);
} elseif ($accion == "eliminar_uso_taller") {
	echo $costo->eliminar_uso_taller($_POST['id']);
} elseif ($accion == "editar_mano_directa") {
	echo $costo->editar_mano_directa($_POST['id']);
} elseif ($accion == "actualizar_mano_directa") {
	echo $costo->actualizar_mano_directa($_POST);
} elseif ($accion == "editar_bordado") {
	echo $costo->editar_bordado($_POST['id']);
} elseif ($accion == "actualizar_bordado") {
	echo $costo->actualizar_bordado($_POST);
} elseif ($accion == "editar_uso_taller") {
	echo $costo->editar_uso_taller($_POST['id']);
} elseif ($accion == "actualizar_uso_taller") {
	echo $costo->actualizar_uso_taller($_POST);
} elseif ($accion == "get_precio_insumo") {
	echo $costo->get_precio_insumo($_POST['id']);
} elseif ($accion == "guardar_MOD") {
	echo $costo->guardar_MOD($_POST);
} elseif ($accion == "guardar_MOI") {
	echo $costo->guardar_MOI($_POST);
} elseif ($accion == "guardar_costos_fijos") {
	echo $costo->guardar_costos_fijos($_POST);
} elseif ($accion == "guardar_gaf") {
	echo $costo->guardar_gaf($_POST);
} elseif ($accion == "guardar_gvm") {
	echo $costo->guardar_gvm($_POST);
} elseif ($accion == "extraer_MOI") {
	echo $costo->extraer_MOI();
} elseif ($accion == "extraer_costos_fijos") {
	echo $costo->extraer_costos_fijos();
} elseif ($accion == "extraer_gvm") {
	echo $costo->extraer_gvm();
} elseif ($accion == "extraer_gaf") {
	echo $costo->extraer_gaf();
} elseif ($accion == "guardar_CIF") {
	echo $costo->guardar_CIF($_POST);
} elseif ($accion == "extraer_CIF") {
	echo $costo->extraer_CIF();
} elseif ($accion == "get_data_ingreso") {
	echo $costo->get_data_ingreso($_POST['id_producto']);
} elseif ($accion == "set_ingreso") {
	echo $costo->set_ingreso($_POST);
} elseif ($accion == "get_MOD") {
	echo $costo->get_MOD($_POST['id_producto']);
} elseif ($accion == "editar_MOI") {
	echo $costo->editar_MOI($_POST['id']);
} elseif ($accion == "actualizar_MOI") {
	echo $costo->actualizar_MOI($_POST);
} elseif ($accion == "eliminar_MOI") {
	echo $costo->eliminar_MOI($_POST['id']);
} elseif ($accion == "editar_CIF") {
	echo $costo->editar_CIF($_POST['id']);
} elseif ($accion == "actualizar_CIF") {
	echo $costo->actualizar_CIF($_POST);
} elseif ($accion == "eliminar_CIF") {
	echo $costo->eliminar_CIF($_POST['id']);
} elseif ($accion == "eliminar_bordado") {
	echo $costo->eliminar_bordado($_POST['id']);
}
