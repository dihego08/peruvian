<?php
include('clsFichaTecnica.php');
$ficha = new clsFichaTecnica;
$accion = $_GET['parAccion'];
if ($accion == 'get_ficha') {
	echo $ficha->get_ficha($_POST['num_modelo']);
} elseif ($accion == "update_ficha") {
	echo $ficha->update_ficha($_POST);
} elseif ($accion == "save_ficha") {
	echo $ficha->save_ficha($_POST);
} elseif ($accion == "get_instruccion") {
	echo $ficha->get_instruccion($_POST['num_modelo']);
} elseif ($accion == "save_instruccion") {
	echo $ficha->save_instruccion($_POST);
} elseif ($accion == "eliminar_paso") {
	echo $ficha->eliminar_paso($_POST);
} elseif ($accion == "guardar_maquina") {
	echo $ficha->guardar_maquina($_POST);
} elseif ($accion == "eliminar_maquina") {
	echo $ficha->eliminar_maquina($_POST);
} elseif ($accion == "get_medidas") {
	echo $ficha->get_medidas($_POST['num_modelo']);
} elseif ($accion == "guardar_medidas") {
	echo $ficha->guardar_medidas($_POST);
} elseif ($accion == "edit_medida") {
	echo $ficha->edit_medida($_POST);
} elseif ($accion == "delete_medida") {
	echo $ficha->delete_medida($_POST['id']);
} elseif ($accion == "edit_instruccion") {
	echo $ficha->edit_instruccion($_POST);
} elseif ($accion == "get_complementos") {
	echo $ficha->get_complementos($_POST['num_modelo']);
} elseif ($accion == "edit_complemento") {
	echo $ficha->edit_complemento($_POST);
} elseif ($accion == "delete_complemento") {
	echo $ficha->delete_complemento($_POST['id']);
} elseif ($accion == "guardar_complemento") {
	echo $ficha->guardar_complemento($_POST);
} elseif ($accion == "get_identificacion") {
	echo $ficha->get_identificacion($_POST['num_modelo']);
} elseif ($accion == "edit_identificacion") {
	echo $ficha->edit_identificacion($_POST);
} elseif ($accion == "delete_identificacion") {
	echo $ficha->delete_identificacion($_POST['id']);
} elseif ($accion == "guardar_identificacion") {
	echo $ficha->guardar_identificacion($_POST);
} elseif ($accion == "get_modificacion") {
	echo $ficha->get_modificacion($_POST['num_modelo']);
} elseif ($accion == "edit_modificacion") {
	echo $ficha->edit_modificacion($_POST);
} elseif ($accion == "delete_modificacion") {
	echo $ficha->delete_modificacion($_POST['id']);
} elseif ($accion == "guardar_modificacion") {
	echo $ficha->guardar_modificacion($_POST);
} elseif ($accion == "guardar_adjunto") {
	$_POST['archivo'] = "";
	$fileName = $_FILES["archivo_adjunto"]["name"];
	$fileTmpLoc = $_FILES["archivo_adjunto"]["tmp_name"];
	$fileType = $_FILES["archivo_adjunto"]["type"];
	$fileSize = $_FILES["archivo_adjunto"]["size"];
	$fileErrorMsg = $_FILES["archivo_adjunto"]["error"];
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "img-colaboradores/$fileName")) {
		$_POST['archivo'] = $fileName;
	} else {
	}
	echo $ficha->guardar_adjunto($_POST);
}elseif ($accion == 'get_archivo_adjunto') {
	echo $ficha->get_archivo_adjunto($_POST['num_modelo']);
}elseif($accion == "get_observaciones"){
	echo $ficha->get_observaciones($_POST['num_modelo']);
}elseif($accion == "guardar_observacion"){
	echo $ficha->guardar_observacion($_POST);
}elseif($accion == "edit_observacion"){
echo $ficha->edit_observacion($_POST);
}elseif($accion == "eliminar_observacion"){
echo $ficha->eliminar_observacion($_POST['id']);
}