<?php
include('clsCotizacion.php');
$cotizacion = new ClsCotizacion;
$accion = $_GET['parAccion'];
if ($accion == 'busqueda_productos') {
	echo $cotizacion->busqueda_productos($_GET['producto']);
} elseif ($accion == 'detalle_para_cotizacion') {
	echo $cotizacion->detalle_para_cotizacion($_GET['codigos']);
} elseif ($accion == 'insertar_cotizacion') {
	$correlativo = $cotizacion->correlativo();
	//$cotizacion->actualizar_correlativo($correlativo);
	$correlativo = $correlativo + 1;
	$codigos = explode(",", $_GET['codigos']);
	$aux_n = 0;
	$aux_b = 0;
	for ($i = 0; $i < count($codigos); $i++) {
		if (isset($_FILES["imagen_" . $codigos[$i]])) {
			$file = $_FILES["imagen_" . $codigos[$i]];
			$nombre = $file["name"];
			$tipo = $file["type"];
			$ruta_provisional = $file["tmp_name"];
			$carpeta = "../../../storage/products/";
			if (isset($_GET['img'])) {
				if ($_GET['img'] == 'SI') {
					if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
						echo "Error, el archivo no es una imagen";
					} else {
						$src = $carpeta . $nombre;
						move_uploaded_file($ruta_provisional, $src);
						//echo $nombre;
					}
				} else {
					$nombre = $_POST['img_m_' . $codigos[$i]];
				}
			} else {
				if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
					//echo "Error, el archivo no es una imagen"; 
					$nombre = $_POST['img_m_' . $codigos[$i]];
				} else {
					$src = $carpeta . $nombre;
					move_uploaded_file($ruta_provisional, $src);
				}
			}
		} else {
			//echo "NO IMAGEN WERITO";
			$nombre = $_POST['img_m_' . $codigos[$i]];
		}
		$aux_n = $aux_n . ',' . $nombre;

		if (isset($_FILES["imagen_b_" . $codigos[$i]])) {
			$file = $_FILES["imagen_b_" . $codigos[$i]];
			$nombre = $file["name"];
			$tipo = $file["type"];
			$ruta_provisional = $file["tmp_name"];
			$carpeta = "../../../storage/products/";
			if (isset($_GET['img'])) {
				if ($_GET['img'] == 'SI') {
					if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
						echo "Error, el archivo no es una imagen";
					} else {
						$src = $carpeta . $nombre;
						move_uploaded_file($ruta_provisional, $src);
						//echo $nombre;
					}
				} else {
					$nombre = $_POST['img_b_' . $codigos[$i]];
				}
			} else {
				if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
					//echo "Error, el archivo no es una imagen"; 
					$nombre = $_POST['img_b_' . $codigos[$i]];
				} else {
					$src = $carpeta . $nombre;
					move_uploaded_file($ruta_provisional, $src);
				}
			}
		} else {
			//echo "NO IMAGEN WERITO";
			$nombre = $_POST['img_b_' . $codigos[$i]];
		}
		$aux_b = $aux_b . ',' . $nombre;
	}
	//echo $_GET['imgs'];
	echo $cotizacion->insertar_cotizacion($_POST, $codigos, $_GET['entrega'], $aux_n, $aux_b, $correlativo);
} elseif ($accion == 'lista_cotizaciones') {
	echo $cotizacion->lista_cotizaciones();
} elseif ($accion == 'lista_cotizaciones_cliente') {
	echo $cotizacion->lista_cotizaciones_cliente($_GET['cli']);
} elseif ($accion == 'detalle_cotizacion') {
	echo $cotizacion->detalle_cotizacion($_GET['codigo']);
} elseif ($accion == 'eliminar_cotizacion') {
	echo $cotizacion->eliminar_cotizacion($_GET['codigo']);
} elseif ($accion == 'busqueda_productos_2') {
	echo $cotizacion->busqueda_productos_2($_GET['producto']);
} elseif ($accion == 'busqueda_productos_barcode') {
	echo $cotizacion->busqueda_productos_barcode($_GET['barcode']);
} elseif ($accion == 'pdf_cotizacion') {
	//echo $cotizacion->pdf_cotizacion($_GET['codigo']);
}
