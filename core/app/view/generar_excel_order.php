<?php
$filtro = $_GET['filtro'];
$tabla = $_GET['tabla'];
if ($_GET['orden'] == 'lista_ordenes') {
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];

	include("clsOrder.php");
	$orden = new clsOrder;
	if (isset($_GET['fecha_desde'])) {
		$data = array(
			"id_cliente" => $_GET['id_cliente'],
			"fecha_desde" => $_GET['fecha_desde'],
			"fecha_hasta" => $_GET['fecha_hasta'],
			"num_modelo" => $_GET['num_modelo'],
		);
		$ordenes = json_decode($orden->lista_ordenes_fecha($data));
	} else {
		$ordenes = json_decode($orden->lista_ordenes($_GET['id_cliente']));
	}


	$aux = 0;
	foreach ($ordenes->Records as $key => $value) {

		$nombre_modelo = "";
		if ($value->nombre_modelo == "" || $value->nombre_modelo == null) {
			$nombre_modelo = $value->producto;
		} else {
			$nombre_modelo = $value->nombre_modelo;
		}
		$contrato = "";
		if ($value->num_contrato == null || $value->num_contrato == "null" || $value->num_contrato == "NULL") {
		} else {
			$contrato = $value->num_contrato;
		}
		$guia = "";
		if ($value->guia_remision == null || $value->guia_remision == "null" || $value->guia_remision == "NULL") {
		} else {
			$guia = $value->guia_remision;
		}
		$fecha_e_r = "";
		if ($value->fecha_entrega_real == null || $value->fecha_entrega_real == "null" || $value->fecha_entrega_real == "NULL") {
		} else {
			$fecha_e_r = $value->fecha_entrega_real;
		}
		$img_ = "";
		if ($value->imagen_alt == "" || $value->imagen_alt == null) {
			$img_ = $value->imagen;
		} else {
			$img_ = $value->imagen_alt;
		}

		$estado = "";
		if ($value->estado == 0) {
			$estado = 'Pendiente';
		} else {
			if ($value->estado == 1) {
				$estado = 'Entregado';
			} else {
				$estado = 'Cancelado';
			}
		}
		$values[] = array(
			"Pedido" => $value->codigo,
			"Fecha de Pedido" => $value->fecha_creacion,
			"Cliente" => $value->name,
			"Cod. Modelo" => $value->codigo_unitario,
			"Descripcion" => $nombre_modelo,
			"Num. Contrato" => $contrato,
			"Cant Pedido" => $value->total,
			"Cant Produccion" => $value->totalp,
			"Guia Remision" => $guia,
			"Documento" => $value->codigo_venta,
			"Fec. Entrega" => $fecha_e_r,
			"Estado" => $estado,
		);
	}
} elseif ($_GET['orden'] == "lista_ordenes_2") {
	include("clsOrder.php");
	$orden = new clsOrder;
	$ordenes = json_decode($orden->lista_ordenes_2($_GET['id_cliente'], $_GET['codigo'], $_GET['modelo'], $_GET['contrato']));
	$aux = 0;
	foreach ($ordenes->Records as $key => $value) {

		$nombre_modelo = "";
		if ($value->nombre_modelo == "" || $value->nombre_modelo == null) {
			$nombre_modelo = $value->producto;
		} else {
			$nombre_modelo = $value->nombre_modelo;
		}
		$contrato = "";
		if ($value->num_contrato == null || $value->num_contrato == "null" || $value->num_contrato == "NULL") {
		} else {
			$contrato = $value->num_contrato;
		}
		$guia = "";
		if ($value->guia_remision == null || $value->guia_remision == "null" || $value->guia_remision == "NULL") {
		} else {
			$guia = $value->guia_remision;
		}
		$fecha_e_r = "";
		if ($value->fecha_entrega_real == null || $value->fecha_entrega_real == "null" || $value->fecha_entrega_real == "NULL") {
		} else {
			$fecha_e_r = $value->fecha_entrega_real;
		}
		$img_ = "";
		if ($value->imagen_alt == "" || $value->imagen_alt == null) {
			$img_ = $value->imagen;
		} else {
			$img_ = $value->imagen_alt;
		}

		$estado = "";
		if ($value->estado == 0) {
			$estado = 'Pendiente';
		} else {
			if ($value->estado == 1) {
				$estado = 'Entregado';
			} else {
				$estado = 'Cancelado';
			}
		}
		$values[] = array(
			"Pedido" => $value->codigo,
			"Fecha de Pedido" => $value->fecha_creacion,
			"Cliente" => $value->name,
			"Cod. Modelo" => $value->codigo_unitario,
			"Descripcion" => $nombre_modelo,
			"Num. Contrato" => $contrato,
			"Cant Pedido" => $value->total,
			"Cant Produccion" => $value->totalp,
			"Guia Remision" => $guia,
			"Documento" => $value->codigo_venta,
			"Fec. Entrega" => $fecha_e_r,
			"Estado" => $estado,
		);
	}
}

if (!empty($values)) {
	$filename = "reporte_orden_pedido-" . date('d-m-Y') . ".xls";
	header("Content-type: text/html; charset=utf8");
	header("Content-Type: application/vnd.ms-excel charset=UTF-8");
	header("Content-Disposition: attachment; filename=" . $filename);
	$mostrar_columnas = false;
	foreach ($values as $libro) {
		if (!$mostrar_columnas) {
			echo implode("\t", array_keys($libro)) . "\n";
			$mostrar_columnas = true;
		}
		echo implode("\t", array_values($libro)) . "\n";
	}
} else {
	echo 'No hay datos a exportar';
}
