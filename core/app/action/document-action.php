<?php
	$accion = $_GET['opt'];
	if($accion == 'tipo_documento'){
		$doc = DocumentoData::getById($_GET['id']);
	}
?>