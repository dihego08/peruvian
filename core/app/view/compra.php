<?php
include('clsCompra.php');
$compra = new clsCompra;
$accion = $_GET['parAccion'];
if ($accion == 'lista_ordenes_compra') {
	echo $compra->lista_ordenes_compra();
} elseif ($accion == 'insertar_orden_compra') {
	echo $compra->insertar_orden_compra($_GET);
} elseif ($accion == 'lista_proveedores') {
	echo $compra->lista_proveedores();
} elseif ($accion == 'lista_detalle') {
	echo $compra->lista_detalle($_GET['id']);
} elseif ($accion == 'eliminar_order') {
	echo $compra->eliminar_order($_GET['codigo']);
} elseif ($accion == "buscar_por_fecha_fe") {
	echo $compra->buscar_por_fecha_fe($_GET);
} elseif ($accion == "actualizar_orden_compra") {
	echo $compra->actualizar_orden_compra($_GET);
} elseif ($accion == "eliminar_order_compra") {
	echo $compra->eliminar_order_compra($_GET['codigo']);
}
