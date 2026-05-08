<?php

if (count($_POST) > 0) {
	/*nro_cuenta
tipo_cuenta
tipo_moneda
forma_envio*/
	$user = new PersonData();
	$user->no = $_POST["no"];
	$user->name = $_POST["name"];
	$user->lastname = $_POST["lastname"];
	$user->address1 = $_POST["address1"];
	$user->email1 = $_POST["email1"];
	$user->phone1 = $_POST["phone1"];
	$user->wsp = $_POST["wsp"];

	$user->nro_cuenta = $_POST["nro_cuenta"];
	$user->tipo_cuenta = $_POST["tipo_cuenta"];
	$user->tipo_moneda = $_POST["tipo_moneda"];
	$user->forma_envio = $_POST["forma_envio"];
	$user->banco = $_POST["banco"];
	$user->id_insumo = $_POST["id_insumo"];
	$user->add_provider();

	print "<script>window.location='index.php?view=providers';</script>";
}
