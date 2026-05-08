<?php
include('clsOrder.php');
$order = new clsOrder;
$accion = $_GET['parAccion'];
if ($accion == 'producto_autocomplete') {
	echo $order->producto_autocomplete($_GET['term']);
} elseif ($accion == 'guardar_order') {
	echo $order->guardar_order($_GET['cliente'], $_GET['productos'], $_GET['cantidad'], $_GET['tiempo'], $GET['usuario']);
} elseif ($accion == 'lista_clientes') {
	echo $order->lista_clientes();
} elseif ($accion == 'lista_ordenes') {
	echo $order->lista_ordenes($_GET['id_cliente']);
} elseif ($accion == "lista_ordenes_fecha") {
	echo $order->lista_ordenes_fecha($_GET);
} elseif ($accion == 'lista_detalle') {
	echo $order->lista_detalle_2($_GET['codigo']);
} elseif ($accion == 'eliminar_order') {
	echo $order->eliminar_order($_GET['codigo']);
} elseif ($accion == 'detalle_producto') {
	echo $order->detalle_producto($_GET['codigo']);
} elseif ($accion == 'actualizar_estado') {
	echo $order->actualizar_estado($_GET['codigo'], $_GET['estado']);
} elseif ($accion == 'buscar_por_orden') {
	echo $order->buscar_por_orden($_GET['codigo_order']);
} elseif ($accion == 'nuevo_order') {
	if (isset($_FILES["nueva_foto"])) {
		$file = $_FILES["nueva_foto"];
		$nombre = $file["name"];
		$tipo = $file["type"];
		$ruta_provisional = $file["tmp_name"];
		$carpeta = "../../../storage/products/";
		if (isset($_GET['img'])) {
			if ($_GET['img'] == 'SI') {
				if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
					//echo "Error, el archivo no es una imagen"; 
					$_POST['imagen_alt'] = $_POST['img_m'];
				} else {
					$src = $carpeta . $nombre;
					move_uploaded_file($ruta_provisional, $src);
					$_POST['imagen_alt'] = $nombre;
				}
			} else {
				$_POST['imagen_alt'] = $_POST['img_m'];
			}
		} else {
			if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
				//echo "Error, el archivo no es una imagen"; 
				$_POST['imagen_alt'] = $_POST['img_m'];
			} else {
				$src = $carpeta . $nombre;
				move_uploaded_file($ruta_provisional, $src);
				//echo $nombre;
				$_POST['imagen_alt'] = $nombre;
			}
		}
	} else {
		//echo "NO IMAGEN WERITO";
		$_POST['imagen_alt'] = $_POST['img_m'];
	}
	echo $order->nuevo_order($_POST, $_GET['cant']);
} elseif ($accion == 'lista_ordenes_2') {
	echo $order->lista_ordenes_2($_GET['id_cliente'], $_GET['codigo'], $_GET['modelo'], $_GET['contrato']);
} elseif ($accion == 'eliminar_imagen') {
	echo $order->eliminar_imagen($_GET['id']);
} elseif ($accion == 'eliminar_bordado') {
	echo $order->eliminar_bordado($_GET['id']);
} elseif ($accion == 'lista_detalle_produccion') {
	echo $order->lista_detalle_produccion($_GET['codigo']);
} elseif ($accion == 'actualizar_order_produccion') {
	echo $order->actualizar_order_produccion($_POST, $_GET['cant'], $_GET['codigo']);
} elseif ($accion == 'update_guia') {
	echo $order->update_guia($_POST);
} elseif ($accion == 'get_cliente') {
	echo $order->get_cliente($_GET['id']);
} elseif ($accion == "buscar_modelo") {
	echo $order->buscar_modelo($_POST['product_name']);
} elseif ($accion == "solo_ruc") {
	echo $order->solo_ruc();
} elseif ($accion == "lista_ventas_ruc") {
	echo $order->lista_ventas_ruc($_GET['ruc_add'], $_GET['fecha']);
} elseif ($accion == "para_cabecera") {
	echo $order->para_cabecera($_POST['codigo_cabecera']);
} elseif ($accion == "get_orden") {
	echo $order->get_orden($_POST['codigo']);
} elseif ($accion == 'edit_order') {
	if (isset($_FILES["nueva_foto"])) {
		$file = $_FILES["nueva_foto"];
		$nombre = $file["name"];
		$tipo = $file["type"];
		$ruta_provisional = $file["tmp_name"];
		$carpeta = "../../../storage/products/";
		if (isset($_GET['img'])) {
			if ($_GET['img'] == 'SI') {
				if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
					//echo "Error, el archivo no es una imagen"; 
					$_POST['imagen_alt'] = $_POST['img_m'];
				} else {
					$src = $carpeta . $nombre;
					move_uploaded_file($ruta_provisional, $src);
					$_POST['imagen_alt'] = $nombre;
				}
			} else {
				$_POST['imagen_alt'] = $_POST['img_m'];
			}
		} else {
			if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
				//echo "Error, el archivo no es una imagen"; 
				$_POST['imagen_alt'] = $_POST['img_m'];
			} else {
				$src = $carpeta . $nombre;
				move_uploaded_file($ruta_provisional, $src);
				//echo $nombre;
				$_POST['imagen_alt'] = $nombre;
			}
		}
	} else {
		//echo "NO IMAGEN WERITO";
		$_POST['imagen_alt'] = $_POST['img_m'];
	}
	echo $order->edit_order($_POST, $_GET['cant']);
}elseif ($accion == 'eliminar_order_detalle') {
	echo $order->eliminar_order_detalle($_POST['id']);
}elseif($accion == "editar_order_detalle"){
	echo $order->editar_order_detalle($_POST);
}
