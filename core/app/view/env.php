<?php

date_default_timezone_set('America/Lima');
try {
	$mbd = new PDO('mysql:host=localhost; dbname=u622044135_peruvian', 'u622044135_peruvian', '2bGMm^n/4gZ:', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
	// ✅ CRÍTICO: Configurar timezone INMEDIATAMENTE después de conectar
    $mbd->exec("SET time_zone = '-05:00'");
    $mbd->exec("SET sql_mode = ''"); // Por si hay incompatibilidad
} catch (PDOException $e) {
	echo "Fallo en la conexion " . $e->getMessage();
}
