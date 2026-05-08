<?php
include('clsInsumos.php');
$insumo = new clsInsumos;
$accion = $_GET['parAccion'];
if ($accion == 'lista_insumos') {
	echo $insumo->lista_insumos();
} elseif ($accion == 'detalle_insumo') {
	echo $insumo->detalle_insumo($_GET['id']);
} elseif ($accion == 'combo_unidades') {
	echo $insumo->combo_unidades($_GET['id']);
} elseif ($accion == 'actualizar_insumo') {
	echo $insumo->actualizar_insumo($_GET['subclase'], $_GET['insumo'], $_GET['clase'], $_GET['familia'], $_GET['id'],  $_GET['codigo']);
} elseif ($accion == 'guardar_insumo') {
	echo $insumo->guardar_insumo($_GET['subclase'], $_GET['insumo'], $_GET['clase'], $_GET['familia'], $_GET['id']);
} elseif ($accion == 'eliminar_insumo') {
	echo $insumo->eliminar_insumo($_GET['id']);
} elseif ($accion == 'insumo_autocomplete') {
	echo $insumo->insumo_autocomplete($_GET['term']);
} elseif ($accion == 'lista_compras') {
	echo $insumo->lista_compras();
} elseif ($accion == 'lista_proveedores') {
	echo $insumo->lista_proveedores();
} elseif ($accion == 'nuevo_compra') {
	echo $insumo->guardar_compra($_GET['cant'], $_POST);
} elseif ($accion == 'lista_compras_2') {
	echo $insumo->lista_compras_2($_POST);
} elseif ($accion == 'lista_detalle') {
	echo $insumo->lista_detalle($_GET['id']);
} elseif ($accion == 'agregar_proveedor') {
	echo $insumo->agregar_proveedor($_POST);
} elseif ($accion == 'combo_familia') {
	echo $insumo->combo_familia();
} elseif ($accion == 'combo_clase') {
	echo $insumo->combo_clase();
} elseif ($accion == 'combo_subclase') {
	echo $insumo->combo_subclase();
} elseif ($accion == 'combo_subclase_2') {
	echo $insumo->combo_subclase_2();
} elseif ($accion == 'eliminar_compra') {
	echo $insumo->eliminar_compra($_GET['id']);
} elseif ($accion == "filtro_compras") {
	echo $insumo->filtro_compras($_POST['id'], $_POST['tipo']);
} elseif ($accion == "get_data_compra") {
	echo $insumo->get_data_compra($_POST['id_compra']);
} elseif ($accion == "get_body_compra") {
	echo $insumo->get_body_compra($_POST['id_compra']);
} elseif ($accion == "actualizar_detalle") {
	echo $insumo->actualizar_detalle($_POST);
} elseif ($accion == "actualizar_compra") {
	echo $insumo->actualizar_compra($_POST);
} elseif ($accion == "ver_stock") {
	echo $insumo->ver_stock($_POST['id_insumo']);
} elseif ($accion == "guardar_stock") {
	echo $insumo->guardar_stock($_POST);
} elseif ($accion == "eliminar_stock") {
	echo $insumo->eliminar_stock($_GET['id']);
} elseif ($accion == "editar_stock") {
	echo $insumo->editar_stock($_POST['id_stock']);
} elseif ($accion == "actualizar_stock") {
	echo $insumo->actualizar_stock($_POST);
} elseif ($accion == "lista_produccion") {
	echo $insumo->lista_produccion();
} elseif ($accion == "guardar_produccion") {
	echo $insumo->guardar_produccion($_POST);
} elseif ($accion == "eliminar_produccion") {
	echo $insumo->eliminar_produccion($_GET['id']);
} elseif ($accion == "filtro_proveedor") {
	echo $insumo->filtro_proveedor($_POST['id_proveedor']);
} elseif ($accion == "lista_forma_pago") {
	echo $insumo->lista_forma_pago();
} elseif ($accion == "get_order_compra") {
	echo $insumo->get_order_compra($_POST["id"]);
} elseif ($accion == "get_order_compra_detalle") {
	echo $insumo->get_order_compra_detalle($_POST['id']);
}
