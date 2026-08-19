<?php
include('clsVenta.php');
$venta = new clsVenta;
$accion = $_GET['parAccion'];
if ($accion == 'tipos_documento') {
	echo $venta->tipos_documento();
} elseif ($accion == 'tipos_pago') {
	echo $venta->tipos_pago();
} elseif ($accion == 'tipos_entrega') {
	echo $venta->tipos_entrega();
} elseif ($accion == 'forma_pago') {
	echo $venta->forma_pago();
} elseif ($accion == 'insertar_venta') {
	echo $venta->insertar_venta($_GET['tipos_documento'], $_GET['almacen'], $_GET['lista_clientes'], $_GET['tipos_pago'], $_GET['tipos_entrega'], $_GET['forma_pago'], $_GET['descuento'], $_GET['subtotal'], $_GET['desc_descuento'], $_GET['igv'], $_GET['total'], $_GET['detraccion'], $_GET['ids'], $_GET['precios'], $_GET['pagado'], $_GET['a_cuenta'], $_GET['unidades'], $_GET['unidades_r'], $_GET['cantidades'], $_GET['n_pedidos'], $_GET['cod_venta'], $_GET['guia'], $_GET['fecha_emision'], $_GET['detraccion_p'], $_GET['igv_p'], $_GET['p_b'], $_GET['pedido'], $_GET['descripciones'], $_GET['nuevo_ruc'], $_GET['fecha_vencimiento'], $_GET['incluye_igv']);
} elseif ($accion == "insertar_guia") {
	echo $venta->insertar_guia($_POST);
} elseif ($accion == 'lista_ventas') {
	echo $venta->lista_ventas($_GET['filtro'], $_GET['codigo'], $_GET['fecha']);
} elseif ($accion == "lista_guias") {
	echo $venta->lista_guias($_GET['filtro'], $_GET['codigo'], $_GET['fecha']);
} elseif ($accion == 'lista_ventas_fe') {
	echo $venta->lista_ventas_fe($_GET['filtro'], $_GET['codigo'], $_GET['fecha']);
} elseif ($accion == 'lista_detalle') {
	echo $venta->lista_detalle($_GET['codigo']);
} elseif ($accion == "lista_detalle_guia") {
	echo $venta->lista_detalle_guia($_GET['codigo']);
} elseif ($accion == 'eliminar_venta') {
	echo $venta->eliminar_venta($_GET['codigo']);
} elseif ($accion == 'actualizar_pago') {
	echo $venta->actualizar_pago($_GET['codigo'], $_GET['monto_pagado'], $_GET['monto_adeuda'], $_GET['fecha'], $_GET['banco'], $_GET['concepto']);
} elseif ($accion == 'actualizar_pago_historial') {
	//echo ($_GET['pago_cod'].'-'. $_GET['monto_pagado'].'-'.$_GET['fecha']);
	echo $venta->actualizar_pago_historial($_GET['pago_cod'], $_GET['monto_pagado'], $_GET['cli_id'], $_GET['fecha'], $_GET['banco'], $_GET['concepto']);
} elseif ($accion == 'eliminar_pago_historial') {
	//echo ($_GET['pago_cod'].'-'. $_GET['monto_pagado'].'-'.$_GET['fecha']);
	echo $venta->eliminar_pago_historial($_GET['pago_cod']);
} elseif ($accion == 'historial_pago') {
	echo $venta->historial_pago($_GET['id_person'], $_GET['codigo_venta']);
} elseif ($accion == 'buscar_por_fecha') {
	echo $venta->buscar_por_fecha($_GET['desde'], $_GET['hasta'], $_GET['tipos_pago'], $_GET['tipos_documento'], $_GET['combo_cliente']);
} elseif ($accion == 'tipo_usuario') {
	echo $venta->tipo_usuario();
} elseif ($accion == 'lista_ventas_s') {
	echo $venta->lista_ventas_s($_GET['filtro'], $_GET['codigo']);
} elseif ($accion == 'buscar_por_fecha_s') {
	echo $venta->buscar_por_fecha_s($_GET['desde'], $_GET['hasta']);
} elseif ($accion == 'actualizar') {
	echo $venta->actualizar($_GET['cod_n'], $_GET['guia'], $_GET['codigo'], $_GET['fecha_pago'], $_GET['entidad'], $_GET['fecha_det']);
} elseif ($accion == 'anular') {
	echo $venta->anular($_GET['codigo']);
} elseif ($accion == 'lista_rep_ventas_cliente') {
	echo $venta->lista_rep_ventas_cliente($_GET['desde'], $_GET['hasta']);
} elseif ($accion == 'lista_gra_ventas_cliente') {
	echo $venta->lista_gra_ventas_cliente($_GET['desde'], $_GET['hasta']);
} elseif ($accion == 'lista_gra_ventas_producto') {
	echo $venta->lista_gra_ventas_producto($_GET['desde'], $_GET['hasta']);
} elseif ($accion == 'lista_gra_ventas_mes') {
	echo $venta->lista_gra_ventas_mes($_GET['desde'], $_GET['hasta']);
} elseif ($accion == 'lista_gra_ventas_mes_fechas') {
	echo $venta->lista_gra_ventas_mes_fechas($_GET['desde'], $_GET['hasta']);
} elseif ($accion == 'lista_rep_ventas_guia_pedido') {
	echo $venta->lista_rep_ventas_guia_pedido($_GET['desde'], $_GET['hasta']);
} elseif ($accion == 'lista_rep_ventas_mes') {
	echo $venta->lista_rep_ventas_mes();
} elseif ($accion == "buscar_por_fecha_fe") {
	echo $venta->buscar_por_fecha_fe($_GET['desde'], $_GET['hasta'], $_GET['id_cliente']);
} elseif ($accion == "get_aux") {
	echo $venta->get_aux();
} elseif ($accion == "get_aux_guia") {
	echo $venta->get_aux_guia();
} elseif ($accion == "add_pago") {
	//$codigo_venta, $id_person, $total, $pago, $adeuda, $fecha_emision
	$_POST['adeuda'] = $_POST['adeuda'] - $_POST['monto_pago'];
	echo $venta->insertar_pago($_POST['vid'], $_POST['cli_id'], $_POST['total'], $_POST['monto_pago'], $_POST['adeuda'], $_POST['fecha_pago'], $_POST['banco'], $_POST['concepto']);
} elseif ($accion == "tipos_documentos_compras") {
	echo $venta->tipos_documentos_compras();
} elseif ($accion == "get_correlativo_nc") {
	echo $venta->get_correlativo_nc();
} elseif ($accion == "guardar_pago_detraccion") {
	echo $venta->guardar_pago_detraccion($_POST['codigo_venta'], $_POST['paga']);
} elseif ($accion == "llenar_departamentos") {
	echo $venta->llenar_departamentos();
} elseif ($accion == "llenar_provincias") {
	echo $venta->llenar_provincias($_POST['departamento']);
} elseif ($accion == "llenar_distritos") {
	echo $venta->llenar_distritos($_POST['provincia']);
}elseif ($accion == "get_unidades") {
	echo $venta->get_unidades();
}
