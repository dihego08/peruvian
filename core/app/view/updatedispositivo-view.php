<?php

if (count($_POST) > 0) {
	$maquina = new DispositivosData();
    $maquina->codigo = $_POST["codigo"];
    $maquina->descripcion  = $_POST["descripcion"];
    $maquina->cantidad = $_POST["cantidad"];
    $maquina->observaciones = $_POST["observaciones"];
    $maquina->fecha = $_POST["fecha"];
    $maquina->responsable = $_POST["responsable"];
	$maquina->id = $_POST['id'];

	$maquina->update();


	if (isset($_FILES["image"])) {
		$image = new Upload($_FILES["image"]);
		if ($image->uploaded) {
			$image->Process("storage/dispositivos/");
			if ($image->processed) {
				$maquina->imagen = $image->file_dst_name;
				$maquina->update_image();
			}
		}
	}

	print "<script>window.location='index.php?view=dispositivos';</script>";
}
