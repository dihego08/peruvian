<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("clsColaborador.php");
$colaborador = new clsColaborador;
$accion = $_GET['parAccion'];

if ($accion == 'get_all_colaboradores') {
	echo $colaborador->get_all_colaboradores();
} elseif ($accion == "editar") {
	echo $colaborador->editar($_POST['id']);
} elseif ($accion == "guardar") {
	if ($_POST['asegurado'] == "on") {
		$_POST['asegurado'] = 1;
	} else {
		$_POST['asegurado'] = 0;
	}

	$_POST['foto'] = "";
	$fileName = $_FILES["file1"]["name"];
	$fileTmpLoc = $_FILES["file1"]["tmp_name"];
	$fileType = $_FILES["file1"]["type"];
	$fileSize = $_FILES["file1"]["size"];
	$fileErrorMsg = $_FILES["file1"]["error"];
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "img-colaboradores/$fileName")) {
		$_POST['foto'] = $fileName;
	} else {
	}
	echo $colaborador->guardar($_POST);
} elseif ($accion == "llenar_estado_civil") {
	echo $colaborador->llenar_estado_civil();
} elseif ($accion == "llenar_sistema_pension") {
	echo $colaborador->llenar_sistema_pension();
} elseif ($accion == "llenar_entidades_pension") {
	echo $colaborador->llenar_entidades_pension($_POST['id']);
} elseif ($accion == "actualizar") {
	$colaborador_ = json_decode($colaborador->editar($_POST['id']));

	if ($_POST['asegurado'] == "on") {
		$_POST['asegurado'] = 1;
	} else {
		$_POST['asegurado'] = 0;
	}

	$_POST['foto'] = $colaborador_->foto;
	if (isset($_FILES["file1"])) {
		$fileName = $_FILES["file1"]["name"];
		$fileTmpLoc = $_FILES["file1"]["tmp_name"];
		$fileType = $_FILES["file1"]["type"];
		$fileSize = $_FILES["file1"]["size"];
		$fileErrorMsg = $_FILES["file1"]["error"];
		if (!$fileTmpLoc) {
		}
		if (move_uploaded_file($fileTmpLoc, "img-colaboradores/$fileName")) {
			$_POST['foto'] = $fileName;
		} else {
		}
	}

	echo $colaborador->actualizar($_POST);
} elseif ($accion == "eliminar") {
	echo $colaborador->eliminar($_POST['id']);
} elseif ($accion == "get_experiencia") {
	echo $colaborador->get_experiencia($_POST['id']);
} elseif ($accion == "guardar_experiencia") {
	echo $colaborador->guardar_experiencia($_POST);
} elseif ($accion == "get_familiares") {
	echo $colaborador->get_familiares($_POST['id']);
} elseif ($accion == "guardar_familiar") {
	echo $colaborador->guardar_familiar($_POST);
} elseif ($accion == "get_habilidad") {
	echo $colaborador->get_habilidad($_POST['id']);
} elseif ($accion == "guardar_habilidad") {
	echo $colaborador->guardar_habilidad($_POST);
} elseif ($accion == "get_formacion") {
	echo $colaborador->get_formacion($_POST['id']);
} elseif ($accion == "guardar_formacion") {
	echo $colaborador->guardar_formacion($_POST);
} elseif ($accion == 'get_all_areas') {
	echo $colaborador->get_all_areas();
} elseif ($accion == "guardar_puesto") {
	echo $colaborador->guardar_puesto($_POST);
} elseif ($accion == "get_puestos") {
	echo $colaborador->get_puestos();
} elseif ($accion == "get_all_puestos") {
	echo $colaborador->get_puestos();
} elseif ($accion == "get_perfil_puesto") {
	echo $colaborador->get_perfil_puesto($_POST['id']);
} elseif ($accion == "guardar_perfil") {
	echo $colaborador->guardar_perfil($_POST);
} elseif ($accion == "guardar_area") {
	echo $colaborador->guardar_area($_POST);
} elseif ($accion == "buscar_dni") {
	echo $colaborador->buscar_dni($_POST);
} elseif ($accion == "siguiente") {
	echo $colaborador->siguiente($_POST['current']);
} elseif ($accion == "eliminar_area") {
	echo $colaborador->eliminar_area($_POST['id']);
} elseif ($accion == "actualizar_area") {
	echo $colaborador->actualizar_area($_POST);
} elseif ($accion == "editar_area") {
	echo $colaborador->editar_area($_POST['id']);
} elseif ($accion == "eliminar_puesto") {
	echo $colaborador->eliminar_puesto($_POST['id']);
} elseif ($accion == "actualizar_puesto") {
	echo $colaborador->actualizar_puesto($_POST);
} elseif ($accion == "editar_puesto") {
	echo $colaborador->editar_puesto($_POST['id']);
} elseif ($accion == "get_total") {
	echo $colaborador->get_total();
} elseif ($accion == "eliminar_familiar") {
	echo $colaborador->eliminar_familiar($_POST['id']);
} elseif ($accion == "actualizar_familiar") {
	echo $colaborador->actualizar_familiar($_POST);
} elseif ($accion == "editar_familiar") {
	echo $colaborador->editar_familiar($_POST['id']);
} elseif ($accion == "guardar_capacitacion") {
	echo $colaborador->guardar_capacitacion($_POST);
} elseif ($accion == "guardar_capacitacion_2") {
	echo $colaborador->guardar_capacitacion_2($_POST);
} elseif ($accion == "get_capacitacion") {
	echo $colaborador->get_capacitacion($_POST['id']);
} elseif ($accion == "get_capacitacion2") {
	echo $colaborador->get_capacitacion2();
} elseif ($accion == "eliminar_capacitacion") {
	echo $colaborador->eliminar_capacitacion($_POST['id']);
} elseif ($accion == "eliminar_capacitacion2") {
	echo $colaborador->eliminar_capacitacion2($_POST['id']);
} elseif ($accion == "actualizar_capacitacion") {
	echo $colaborador->actualizar_capacitacion($_POST);
} elseif ($accion == "actualizar_capacitacion2") {
	echo $colaborador->actualizar_capacitacion2($_POST);
} elseif ($accion == "editar_capacitacion") {
	echo $colaborador->editar_capacitacion($_POST['id']);
} elseif ($accion == "editar_capacitacion2") {
	echo $colaborador->editar_capacitacion2($_POST['id']);
} elseif ($accion == "eliminar_habilidad") {
	echo $colaborador->eliminar_habilidad($_POST['id']);
} elseif ($accion == "actualizar_habilidad") {
	echo $colaborador->actualizar_habilidad($_POST);
} elseif ($accion == "editar_habilidad") {
	echo $colaborador->editar_habilidad($_POST['id']);
} elseif ($accion == "eliminar_experiencia") {
	echo $colaborador->eliminar_experiencia($_POST['id']);
} elseif ($accion == "actualizar_experiencia") {
	echo $colaborador->actualizar_experiencia($_POST);
} elseif ($accion == "editar_experiencia") {
	echo $colaborador->editar_experiencia($_POST['id']);
} elseif ($accion == "eliminar_formacion") {
	echo $colaborador->eliminar_formacion($_POST['id']);
} elseif ($accion == "actualizar_formacion") {
	echo $colaborador->actualizar_formacion($_POST);
} elseif ($accion == "editar_formacion") {
	echo $colaborador->editar_formacion($_POST['id']);
} elseif ($accion == "guardar_registro_capacitacion") {
	echo $colaborador->guardar_registro_capacitacion($_POST);
} elseif ($accion == "get_cronograma") {
	echo $colaborador->get_cronograma($_POST);
} elseif ($accion == "guardar_asistencia") {
	$_POST['foto'] = "";
	$_POST['asistentes'] = "";

	$fileName = $_FILES["file1"]["name"];
	$fileTmpLoc = $_FILES["file1"]["tmp_name"];
	$fileType = $_FILES["file1"]["type"];
	$fileSize = $_FILES["file1"]["size"];
	$fileErrorMsg = $_FILES["file1"]["error"];
	$_POST['foto'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "asistencias/$fileName")) {
		$_POST['foto'] = $fileName;
	} else {
	}


	$fileName_2 = $_FILES["file1_2"]["name"];
	$fileTmpLoc_2 = $_FILES["file1_2"]["tmp_name"];
	$fileType_2 = $_FILES["file1_2"]["type"];
	$fileSize_2 = $_FILES["file1_2"]["size"];
	$fileErrorMsg_2 = $_FILES["file1_2"]["error"];
	$_POST['asistentes'] = "";
	if (!$fileTmpLoc_2) {
	}
	if (move_uploaded_file($fileTmpLoc_2, "asistencias/$fileName_2")) {
		$_POST['asistentes'] = $fileName_2;
	} else {
	}

	echo $colaborador->guardar_asistencia($_POST);
} elseif ($accion == "actualizar_asistencia") {

	$data = json_decode($colaborador->editar_asistencia($_POST['id']));

	$_POST['foto'] = $data->foto;
	$_POST['asistentes'] = $data->asistentes;

	$fileName = $_FILES["file1"]["name"];
	$fileTmpLoc = $_FILES["file1"]["tmp_name"];
	$fileType = $_FILES["file1"]["type"];
	$fileSize = $_FILES["file1"]["size"];
	$fileErrorMsg = $_FILES["file1"]["error"];

	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "asistencias/$fileName")) {
		$_POST['foto'] = $fileName;
	} else {
	}


	$fileName_2 = $_FILES["file1_2"]["name"];
	$fileTmpLoc_2 = $_FILES["file1_2"]["tmp_name"];
	$fileType_2 = $_FILES["file1_2"]["type"];
	$fileSize_2 = $_FILES["file1_2"]["size"];
	$fileErrorMsg_2 = $_FILES["file1_2"]["error"];

	if (!$fileTmpLoc_2) {
	}
	if (move_uploaded_file($fileTmpLoc_2, "asistencias/$fileName_2")) {
		$_POST['asistentes'] = $fileName_2;
	} else {
	}

	echo $colaborador->actualizar_asistencia($_POST);
} elseif ($accion == "get_asistencias") {
	echo $colaborador->get_asistencias();
} elseif ($accion == "editar_capacitacion_registro") {
	echo $colaborador->editar_capacitacion_registro($_POST['id']);
} elseif ($accion == "actualizar_registro_capacitacion") {
	echo $colaborador->actualizar_registro_capacitacion($_POST);
} elseif ($accion == "eliminar_registro_capacitacion") {
	echo $colaborador->eliminar_registro_capacitacion($_POST['id']);
} elseif ($accion == "eliminar_asistencia") {
	echo $colaborador->eliminar_asistencia($_POST['id']);
} elseif ($accion == "hecho") {
	echo $colaborador->hecho($_POST['id']);
} elseif ($accion == "no_hecho") {
	echo $colaborador->no_hecho($_POST['id']);
} elseif ($accion == "siguiente_especifico") {
	echo $colaborador->siguiente_especifico($_POST['id']);
} elseif ($accion == "get_sistema_pension") {
	echo $colaborador->get_sistema_pension($_POST['id']);
} elseif ($accion == "get_entidad_pension") {
	echo $colaborador->get_entidad_pension($_POST['id']);
} elseif ($accion == "get_estado_civil") {
	echo $colaborador->get_estado_civil($_POST['id']);
} elseif ($accion == "get_actas_reunion") {
	echo $colaborador->get_actas_reunion();
} elseif ($accion == 'cargar_archivo') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "formacion/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == 'cargar_archivo_experiencia') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "experiencia/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_experiencia($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == 'cargar_archivo_vacaciones') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "vacaciones/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_vacaciones($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == "cargar_archivo_examen_medico") {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "certificado_medico/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_examen_medico($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == "cargar_archivo_recomendaciones") {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "sst/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_recomendaciones($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == "cargar_archivo_contrato") {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "contratos/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_contrato($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == "cargar_archivo_competencias") {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "competencias/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_competencias($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == 'cargar_archivo_capacitaciones') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "capacitaciones/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_capacitaciones($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == 'cargar_archivo_capacitaciones2') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "capacitaciones/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_capacitaciones2($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == 'cargar_archivo_certificado_medico') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "certificado_medico/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_certificado_medico($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == 'cargar_archivo_dni') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "dni/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_archivo_dni($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == 'cargar_contrato') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "contratos/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_contrato($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == 'cargar_sst') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "sst/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_sst($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == 'cargar_competencias') {
	$_POST['archivo'] = "";

	$fileName = $_FILES["archivo"]["name"];
	$fileTmpLoc = $_FILES["archivo"]["tmp_name"];
	$fileType = $_FILES["archivo"]["type"];
	$fileSize = $_FILES["archivo"]["size"];
	$fileErrorMsg = $_FILES["archivo"]["error"];
	$_POST['archivo'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "competencias/$fileName")) {
		$_POST['archivo'] = $fileName;
		echo $colaborador->cargar_competencias($_POST);
	} else {
		echo json_encode(array("Result" => "ERROR"));
	}
} elseif ($accion == "guardar_acta") {
	$_POST['asistentes'] = "";

	$fileName = $_FILES["file1"]["name"];
	$fileTmpLoc = $_FILES["file1"]["tmp_name"];
	$fileType = $_FILES["file1"]["type"];
	$fileSize = $_FILES["file1"]["size"];
	$fileErrorMsg = $_FILES["file1"]["error"];
	$_POST['asistentes'] = "";
	if (!$fileTmpLoc) {
	}
	if (move_uploaded_file($fileTmpLoc, "asistencias/$fileName")) {
		$_POST['asistentes'] = $fileName;
	} else {
	}

	$fileName_2 = $_FILES["file1_2"]["name"];
	$fileTmpLoc_2 = $_FILES["file1_2"]["tmp_name"];
	$fileType_2 = $_FILES["file1_2"]["type"];
	$fileSize_2 = $_FILES["file1_2"]["size"];
	$fileErrorMsg_2 = $_FILES["file1_2"]["error"];
	$_POST['acuerdos'] = "";
	if (!$fileTmpLoc_2) {
	}
	if (move_uploaded_file($fileTmpLoc_2, "asistencias/$fileName_2")) {
		$_POST['acuerdos'] = $fileName_2;
	} else {
	}

	echo $colaborador->guardar_acta($_POST);
} elseif ($accion == "eliminar_acta_reunion") {
	echo $colaborador->eliminar_acta_reunion($_POST['id']);
} elseif ($accion == "editar_asistencia") {
	echo $colaborador->editar_asistencia($_POST['id']);
} elseif ($accion == 'guardar_vacaciones') {
	echo $colaborador->guardar_vacaciones($_POST);
} elseif ($accion == "guardar_examen_medico") {
	echo $colaborador->guardar_examen_medico($_POST);
} elseif ($accion == "guardar_contrato") {
	echo $colaborador->guardar_contrato($_POST);
} elseif ($accion == "guardar_competencias") {
	echo $colaborador->guardar_competencias($_POST);
} elseif ($accion == "guardar_recomendaciones") {
	echo $colaborador->guardar_recomendaciones($_POST);
} elseif ($accion == 'get_vacaciones') {
	echo $colaborador->get_vacaciones($_POST['id']);
} elseif ($accion == "get_examenes_medicos") {
	echo $colaborador->get_examenes_medicos($_POST['id']);
} elseif ($accion == "get_contratos") {
	echo $colaborador->get_contratos($_POST['id']);
} elseif ($accion == "get_competencias") {
	echo $colaborador->get_competencias($_POST['id']);
} elseif ($accion == "get_recomendaciones") {
	echo $colaborador->get_recomendaciones($_POST['id']);
} elseif ($accion == 'editar_vacaciones') {
	echo $colaborador->editar_vacaciones($_POST['id']);
} elseif ($accion == "editar_examen_medico") {
	echo $colaborador->editar_examen_medico($_POST['id']);
} elseif ($accion == "editar_contrato") {
	echo $colaborador->editar_contrato($_POST['id']);
} elseif ($accion == "editar_competencias") {
	echo $colaborador->editar_competencias($_POST['id']);
} elseif ($accion == 'actualizar_vacaciones') {
	echo $colaborador->actualizar_vacaciones($_POST);
} elseif ($accion == "actualizar_examen_medico") {
	echo $colaborador->actualizar_examen_medico($_POST);
} elseif ($accion == "actualizar_contrato") {
	echo $colaborador->actualizar_contrato($_POST);
} elseif ($accion == "actualizar_competencias") {
	echo $colaborador->actualizar_competencias($_POST);
} elseif ($accion == "actualizar_recomendaciones") {
	echo $colaborador->actualizar_recomendaciones($_POST);
} elseif ($accion == 'eliminar_vacaciones') {
	echo $colaborador->eliminar_vacaciones($_POST['id']);
} elseif ($accion == "eliminar_examen_medico") {
	echo $colaborador->eliminar_examen_medico($_POST['id']);
} elseif ($accion == "eliminar_contrato") {
	echo $colaborador->eliminar_contrato($_POST['id']);
} elseif ($accion == "eliminar_competencias") {
	echo $colaborador->eliminar_competencias($_POST['id']);
} elseif ($accion == 'guardar_cambio_estado') {
	echo $colaborador->guardar_cambio_estado($_POST['id'], $_POST['estado']);
} elseif ($accion == 'delete_elemento_from_form') {
	echo $colaborador->delete_elemento_from_form($_POST['id']);
} elseif ($accion == "get_tipos_cronograma") {
	if (isset($_POST['id'])) {
		echo $colaborador->get_tipos_cronograma($_POST['id']);
	} else {
		echo $colaborador->get_tipos_cronograma(null);
	}
} elseif ($accion == "editar_recomendaciones") {
	echo $colaborador->editar_recomendaciones($_POST['id']);
} elseif ($accion == "eliminar_recomendaciones") {
	echo $colaborador->eliminar_recomendaciones($_POST['id']);
}
