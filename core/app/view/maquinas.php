<?php
include("clsMaquinas.php");
$accion = $_GET['parAccion'];
$maquina = new clsMaquinas;
//echo $accion;
if ($accion == "llenar_maquinas") {
	echo $maquina->llenar_maquinas();
} elseif ($accion == "get_cronograma") {
	echo $maquina->get_cronograma($_POST['anio'], $_POST['mes']);
} elseif ($accion == "editar_capacitacion_registro") {
	echo $maquina->editar_capacitacion_registro($_POST['id']);
} elseif ($accion == "actualizar_registro_capacitacion") {
	echo $maquina->actualizar_registro_capacitacion($_POST);
} elseif ($accion == "eliminar_registro_capacitacion") {
	echo $maquina->eliminar_registro_capacitacion($_POST['id']);
} elseif ($accion == "guardar_registro_capacitacion") {
	echo $maquina->guardar_registro_capacitacion($_POST);
} elseif ($accion == "hecho") {
	echo $maquina->hecho($_POST['id']);
} elseif ($accion == "no_hecho") {
	echo $maquina->no_hecho($_POST['id']);
} elseif ($accion == 'delete_elemento_from_form') {
	echo $maquina->delete_elemento_from_form($_POST['id']);
} elseif ($accion == "hecho") {
	echo $maquina->hecho($_POST['id']);
} elseif ($accion == "no_hecho") {
	echo $maquina->no_hecho($_POST['id']);
} elseif ($accion == 'guardar_cambio_estado') {
	echo $maquina->guardar_cambio_estado($_POST['id'], $_POST['estado']);
} elseif ($accion == 'get_tipos_maquinas') {
	echo $maquina->get_tipos_maquinas();
} elseif ($accion == "guardar_tipos_maquinas") {
	echo $maquina->guardar_tipos_maquinas($_POST['tipo_maquina']);
} elseif ($accion == "eliminar_maquina") {
	echo $maquina->eliminar_maquina($_POST['id']);
} elseif ($accion == "eliminar_dispositivo") {
	echo $maquina->eliminar_dispositivo($_POST['id']);
}
