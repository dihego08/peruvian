<?php 
    //header("Content-Type: text/html;charset=utf-8"); 
	try{
		$mbd = new PDO('mysql:host=localhost; dbname=u622044135_peruvian', 'u622044135_peruvian', '2bGMm^n/4gZ:', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
		//echo "CONECTADO";
	}catch(PDOException $e){
		echo "Fallo en la conexion ".$e->getMessage();
	}
?>
