<?php 
	include('clsEntidades.php');
	$accion = $_GET['parAccion'];
	$entidad = new Entidad;
	if($accion == 'funGuardarPermisos'){
		echo $entidad->cambiarPermisos($_POST['varPostUsuCodigo'], $_POST['arrayData']);
	}elseif ($accion == 'getListaUsuarios') {
		echo $entidad->comboEntidades();
	}elseif($accion == 'list'){
		echo $entidad->listarEntidades();
	}elseif ($accion == 'create') {
		foreach ($_POST as $clave=>$valor){
			if (empty($valor)) {
				$_POST[$clave] = NULL;
			}else{
			}
		}
		echo $entidad->insertarEntidades($_POST['nombres'], $_POST['apellidoPaterno'], $_POST['apellidoMaterno'], $_POST['fechaNacimiento'], $_POST['dni'], $_POST['direccion'], $_POST['telefono'], $_POST['idSede'], $_POST['idCargo'], $_POST['rol'], $_POST['usuario'], $_POST['pass']);
	}elseif ($accion == 'update') {
		foreach ($_POST as $clave=>$valor){
			if (empty($valor)) {
				$_POST[$clave] = NULL;
			}else{
			}
		}
		echo $entidad->editarEntidades($_POST['id'], $_POST['nombres'], $_POST['apellidoPaterno'], $_POST['apellidoMaterno'], $_POST['fechaNacimiento'], $_POST['dni'], $_POST['direccion'], $_POST['telefono'], $_POST['idSede'], $_POST['idEstadoCivil'], $_POST['idNivelAcademico'], $_POST['idCargo'], $_POST['rol'], $_POST['usuario'], $_POST['pass']);
	}elseif ($accion == 'delete') {
		echo $entidad->eliminarEntidades($_POST['id']);
	}elseif ($accion == 'comboSedes') {
		//echo "HERE";
		echo $entidad->comboSedes();
	}elseif ($accion == 'comboNivelAcademico') {
		echo $entidad->comboNivelAcademico();
	}elseif ($accion == 'comboCargo') {
		echo $entidad->comboCargo();
	}elseif ($accion == 'cambiarContrasena') {
		echo $entidad -> cambiarContrasena($_POST['passwordAct'], $_POST['passwordNue']);
	}elseif ($accion == 'iniciarSesion') {
		echo $entidad->iniciarSesion($_GET['usuario'], $_GET['pass']);
	}elseif ($accion == 'cerrarSesion') {
		echo $entidad->cerrarSesion();
	}
?>