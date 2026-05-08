<?php

if (count($_POST) > 0) {
	$maquina = new MaquinaData();
	$maquina->maquina_codigo = $_POST["codigo"];
	$maquina->maquina_descripcion  = $_POST["descripcion"];
	$maquina->maquina_marca = $_POST["marca"];
	$maquina->maquina_modelo = $_POST["modelo"];
	$maquina->maquina_serie  = $_POST["serie"];
	$maquina->maquina_marca_motor = $_POST["marca_motor"];
	$maquina->maquina_serie_motor = $_POST["serie_motor"];
	$maquina->maquina_exigencias = $_POST["exigencias"];
	$maquina->maquina_voltaje = $_POST["voltaje"];
	$maquina->maquina_tipo_corriente = $_POST["tipo_corriente"];

	$maquina->maquina_anio_compra = $_POST["anio_compra"];
	$maquina->maquina_vida_util = $_POST["vida_util"];
	$maquina->maquina_tipo = $_POST["tipo"];
	$maquina->maquina_ubicacion = $_POST["ubicacion"];
	$maquina->maquina_estado = $_POST["estado"];
	if(empty($_POST["precio_compra"]) || is_null($_POST["precio_compra"])){
		$_POST["precio_compra"] = 0;
	}
	$maquina->precio_compra = $_POST["precio_compra"];
	$maquina->proveedor = $_POST["proveedor"];
	$maquina->maquina_id = $_POST['id'];
	$maquina->update();

	if ($_POST['img_oculto'] == null || is_null($_POST['img_oculto']) || empty($_POST['img_oculto'])) {
		$maquina->maquina_imagen = null;
		$maquina->update_image();
	} else {
	}

	if ($_POST['img_oculto_factura'] == null || is_null($_POST['img_oculto_factura']) || empty($_POST['img_oculto_factura'])) {
		$maquina->factura_compra = null;
		$maquina->update_image_factura();
	} else {
	}

	if (isset($_FILES["image"])) {
		$image = new Upload($_FILES["image"]);
		if ($image->uploaded) {
			$image->Process("storage/maquinas/");
			if ($image->processed) {
				$maquina->maquina_imagen = $image->file_dst_name;
				$maquina->update_image();
			}
		}
	}


	if (isset($_FILES["image_factura"])) {
		$image_factura = new Upload($_FILES["image_factura"]);
		if ($image_factura->uploaded) {
			$image_factura->Process("storage/maquinas/");
			if ($image_factura->processed) {
				$maquina->factura_compra = $image_factura->file_dst_name;
				$maquina->update_image_factura();
			}
		}
	}



	//echo "ingreso exitoso";

	print "<script>window.location='index.php?view=maquinas';</script>";
}
