<?php
date_default_timezone_set('America/Lima');
class Entidad
{
	function comboEntidades()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * from user");
		$query->execute();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = array(
				'value' => $res['id'],
				'text' => $res['name']
			);
		}
		$JSON = json_encode($values);
		echo $JSON;
	}
	function listarEntidades()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM entidades WHERE fechaEliminacion ='0000-00-00 00:00:00' and id != 1");
		$query->execute();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = array(
				'id' => $res['id'],
				'dni' => $res['dni'],
				'nombres' => $res['nombres'],
				'apellidoPaterno' => $res['apellidoPaterno'],
				'apellidoMaterno' => $res['apellidoMaterno'],
				'fechaNacimiento' => $res['fechaNacimiento'],
				'direccion' => $res['direccion'],
				'telefono' => $res['telefono'],
				'idCargo' => $res['idCargo'],
				'idSede' => $res['idSede'],
				'rol' => $res['rol'],
				'usuario' => $res['usuario'],
				'pass' => $res['password']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
	}
	function comboEstadoCivil()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * from estadocivil");
		$query->execute();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = array(
				'DisplayText' => $res['estado'],
				'Value' => $res['id']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Options' => $values
		);
		$JSON = json_encode($result);
		echo $JSON;
	}
	function comboNivelAcademico()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * from nivelacademico");
		$query->execute();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = array(
				'DisplayText' => $res['nivelAcademico'],
				'Value' => $res['id']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Options' => $values
		);
		$JSON = json_encode($result);
		echo $JSON;
	}
	function comboCargo()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * from cargo");
		$query->execute();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = array(
				'DisplayText' => $res['cargo'],
				'Value' => $res['id']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Options' => $values
		);
		$JSON = json_encode($result);
		echo $JSON;
	}
	function comboSedes()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * from sedes");
		$query->execute();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = array(
				'DisplayText' => $res['sede'],
				'Value' => $res['id']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Options' => $values
		);
		$JSON = json_encode($result);
		echo $JSON;
	}
	function insertarEntidades($nombres, $apellidoPaterno, $apellidoMaterno, $fechaNacimiento, $dni, $direccion, $telefono, $idSede, $idCargo, $rol, $usuario, $pass)
	{
		include('env.php');
		try {
			$query = $mbd->prepare("INSERT INTO entidades(nombres, apellidoPaterno, apellidoMaterno, fechaNacimiento, dni, direccion, telefono, idEstadoCivil, idNivelAcademico, idCargo, idUsuarioCreacion, idEmpresa, rol, usuario, password) VALUES(:nombres, :apellidoPaterno, :apellidoMaterno, :fechaNacimiento, :dni, :direccion, :telefono, :idSede, :idNivelAcademico, :idCargo, :idUsuarioCreacion, :idEmpresa, :rol, :usuario, :pass)");
			$query->bindParam(':nombres', $nombres);
			$query->bindParam(':apellidoPaterno', $apellidoPaterno);
			$query->bindParam(':apellidoMaterno', $apellidoMaterno);
			$query->bindParam(':fechaNacimiento', $fechaNacimiento);
			$query->bindParam(':dni', $dni);
			$query->bindParam(':rol', $rol);
			$query->bindParam(':usuario', $usuario);
			$query->bindParam(':pass', $pass);
			$query->bindParam(':direccion', $direccion);
			$query->bindParam(':telefono', $telefono);
			$query->bindParam(':idSede', $idSede);
			$query->bindParam(':idNivelAcademico', $idNivelAcademico);
			$query->bindParam(':idCargo', $idCargo);
			$idEmpresa = 1;
			$query->bindParam(':idEmpresa', $idEmpresa);

			$usuario = $_SESSION['usuario'];
			$query->bindParam(':idUsuarioCreacion', $usuario);
			$query->execute();
			$values = array(
				'nombres' => $nombres,
				'apellidoPaterno' => $apellidoPaterno,
				'apellidoMaterno' => $apellidoMaterno,
				'fechaNacimiento' => $fechaNacimiento,
				'dni' => $dni,
				'direccion' => $direccion,
				'telefono' => $telefono,
				'idEstadoCivil' => $idEstadoCivil,
				'idNivelAcademico' => $idNivelAcademico,
				'idCargo' => $idCargo,
				'rol' => $rol
			);
			$result = array(
				'Result' => 'OK',
				'Record' => $values
			);
			return json_encode($result);
		} catch (PDOException $e) {
			$result = array(
				'Result' => 'ERROR'
			);
		}
	}
	function editarEntidades($id, $nombres, $apellidoPaterno, $apellidoMaterno, $fechaNacimiento, $dni, $direccion, $telefono, $idSede, $idEstadoCivil, $idNivelAcademico, $idCargo, $rol, $usuarioA, $pass)
	{
		$auxApellidos = explode(" ", $apellidos);
		include('env.php');
		try {
			$query = $mbd->prepare("UPDATE entidades SET nombres = :nombres, apellidoPaterno = :apellidoPaterno, apellidoMaterno = :apellidoMaterno, fechaNacimiento = :fechaNacimiento, dni = :dni, direccion = :direccion, telefono = :telefono, idEstadoCivil = :idEstadoCivil, idNivelAcademico = :idNivelAcademico, idSede = :idSede, idCargo = :idCargo, idUsuarioModificacion = :idUsuarioModificacion, fechaModificacion = :fechaModificacion, rol = :rol, usuario = :usuario, password = :pass WHERE id = :id");
			$query->bindParam(':nombres', $nombres);
			$query->bindParam(':apellidoPaterno', $apellidoPaterno);
			$query->bindParam(':apellidoMaterno', $apellidoMaterno);
			$query->bindParam(':fechaNacimiento', $fechaNacimiento);
			$query->bindParam(':dni', $dni);
			$query->bindParam(':rol', $rol);
			$query->bindParam(':usuario', $usuarioA);
			$query->bindParam(':pass', $pass);
			$query->bindParam(':idSede', $idSede);
			$query->bindParam(':direccion', $direccion);
			$query->bindParam(':telefono', $telefono);
			$query->bindParam(':idEstadoCivil', $idEstadoCivil);
			$query->bindParam(':idNivelAcademico', $idNivelAcademico);
			$query->bindParam(':idCargo', $idCargo);
			$usuario = $_SESSION['usuario'];
			$dateTimeVariable = date("Y-m-d H:i:s");
			$query->bindParam(':idUsuarioModificacion', $usuario);
			$query->bindParam(':fechaModificacion', $dateTimeVariable);
			$query->bindParam(':id', $id);
			$query->execute();
			$cuenta = $query->rowCount();
			if ($cuenta > 0) {
				$result = array(
					'Result' => 'OK'
				);
			} else {
				$result = array(
					'Result' => 'ERROR'
				);
			}
			return json_encode($result);
		} catch (PDOException $e) {
			$result = array(
				'Result' => 'ERROR'
			);
		}
	}
	function eliminarEntidades($id)
	{
		include('env.php');
		$fechaEliminacion = date("Y-m-d H:i:s");
		$query = $mbd->prepare("UPDATE entidades SET fechaEliminacion = :fechaEliminacion, idUsuarioEliminacion = :idUsuarioEliminacion WHERE id = :id");
		$query->bindParam(':id', $id);
		$query->bindParam(':fechaEliminacion', $fechaEliminacion);
		$usuario = $_SESSION['usuario'];
		$query->bindParam(':idUsuarioEliminacion', $usuario);
		$query->execute();
		$cuenta = $query->rowCount();
		if ($cuenta > 0) {
			$result = array(
				'Result' => 'OK'
			);
		} else {
			$result = array(
				'Result' => 'ERROR'
			);
		}
		return json_encode($result);
	}
	function cambiarPermisos($idUsuario, $arrayData)
	{

		//print_r($arrayData);
		include('env.php');
		try {
			$query2 = $mbd->prepare("DELETE FROM menus_entidades WHERE idMenu NOT IN (1, 15) AND idUsuario = :idUsuario");
			$query2->bindParam(':idUsuario', $idUsuario);
			$query2->execute();
			for ($j = 0; $j < count($arrayData); $j++) {

				$query_padre = $mbd->prepare("SELECT parent_id FROM menus where id = :id_");
				$query_padre->bindParam(":id_", $arrayData[$j]);
				$query_padre->execute();

				$padre_id = $query_padre->fetch(PDO::FETCH_ASSOC);

				if (in_array($padre_id['parent_id'], $marks)) {
				} else {
					if ($padre_id['parent_id'] == 0) {
					} else {
						$query = $mbd->prepare("INSERT INTO menus_entidades(idMenu, idUsuario, idUsuarioCreacion) values(:idMenu, :idUsuario, :idUsuarioCreacion)");
						$query->bindParam(':idMenu', $padre_id['parent_id']);
						$query->bindParam(':idUsuario', $idUsuario);
						$idUsuarioCreacion = $_SESSION['usuario'];
						$query->bindParam(':idUsuarioCreacion', $idUsuarioCreacion);
						$query->execute();
					}
				}

				/*$r = $mbd->prepare("SELECT count(*) as cant from menus_entidades WHERE idMenu = :idMenu AND idUsuario = :idUsuario");
				$r->bindParam(':idMenu', $arrayData[$j]['parent']);
				$r->bindParam(':idUsuario', $idUsuario);
				$r->execute();*/

				//$t = $r->fetch(PDO::FETCH_ASSOC);
				//if($t['cant'] == 0){

				$query = $mbd->prepare("INSERT INTO menus_entidades(idMenu, idUsuario, idUsuarioCreacion) values(:idMenu, :idUsuario, :idUsuarioCreacion)");
				$query->bindParam(':idMenu', $arrayData[$j]);
				$query->bindParam(':idUsuario', $idUsuario);
				$idUsuarioCreacion = $_SESSION['usuario'];
				$query->bindParam(':idUsuarioCreacion', $idUsuarioCreacion);
				$query->execute();
			}

			$dup = $mbd->prepare("DELETE t1 FROM menus_entidades t1
			INNER  JOIN menus_entidades t2
			WHERE
				t1.id < t2.id AND
				t1.idMenu = t2.idMenu AND
				t1.idUsuario = t2.idUsuario;");
			$dup->execute();

			$values = array('Result' => 'OK');
			echo json_encode($values);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	function cambiarContrasena($passwordAct, $password)
	{
		include('env.php');
		$query = $mbd->prepare("UPDATE entidades set password = :password WHERE id = :id AND password = :passwordAct");
		$query->bindParam(':password', $password);
		$query->bindParam(':passwordAct', $passwordAct);
		$id = 1;
		$query->bindParam(':id', $id);
		$query->execute();
		$cuenta = $query->rowCount();
		if ($cuenta > 0) {
			$result = array(
				'Result' => 'OK'
			);
		} else {
			$result = array(
				'Result' => 'ERROR'
			);
		}
		return json_encode($result);
	}
	function iniciarSesion($usuario, $pass)
	{
		include('env.php');

		$_SESSION["usuario"] = "";
		$query = $mbd->prepare("SELECT COUNT(*) as canti FROM entidades where usuario = :usuario AND password = :pass");
		$query->bindParam(':usuario', $usuario);
		$query->bindParam(':pass', $pass);
		$query->execute();
		$canti = 0;
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$canti = $res['canti'];
		}
		if ($canti == 0) {
			$result = array(
				'Result' => 'ERROR'
			);
		} else {
			$query2 = $mbd->prepare("SELECT * FROM entidades WHERE usuario = :usuario AND password = :pass");
			$query2->bindParam(':usuario', $usuario);
			$query2->bindParam(':pass', $pass);
			$query2->execute();
			while ($res = $query2->fetch(PDO::FETCH_ASSOC)) {
				$_SESSION["usuario"] = $res['id'];
				$_SESSION["rol"] = $res['rol'];
				$_SESSION["idSede"] = $res['idSede'];
			}
			$result = array(
				'Result' => 'OK'
			);
		}
		return json_encode($result);
	}
	function cerrarSesion()
	{
		include('env.php');
		session_destroy();
		$result = array(
			'Result' => 'OK'
		);
		return json_encode($result);
	}
}
