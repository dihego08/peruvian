<?php
	//include "../core/autoload.php";
	/*include "../core/app/model/ProductData.php";
	include "../core/app/model/CategoryData.php";*/

	include('../core/app/view/env.php');

	$cliente = $_GET['cli'];
	$modelo = $_GET['mod'];
	$nombre = $_GET['nom'];
	$estado = $_GET['est'];
	
	$sql = "SELECT p.id,p.code, p.name, p.price_in, p.price_in_2, p.unit, p.presentation, c.name as cliente, p.inventary_min, p.is_active FROM product as p LEFT JOIN person as c ON c.id = p.cliente_id where p.is_active = '$estado'";
		if($cliente != "")
		{
			$sql .= " and p.cliente_id = '$cliente'";
		}
		if($modelo != "")
		{
			$sql .= " and p.code = '$modelo'";
		}
		if($nombre != "")
		{
			$sql .= " and p.name like '%$nombre%'";
		}
		$sql .= " order by p.created_at desc";

	//echo($sql);
	
	$query = $mbd->prepare($sql);
	$query->execute();


	require_once '../core/controller/PhpWord/Autoloader.php';
	use PhpOffice\PhpWord\Autoloader;
	use PhpOffice\PhpWord\SimpleType\Jc;
	use PhpOffice\PhpWord\Settings;

	Autoloader::register();

	$word = new  PhpOffice\PhpWord\PhpWord();

	$documento = new \PhpOffice\PhpWord\PhpWord();
	$propiedades = $documento->getDocInfo();
	$propiedades->setCreator("Luis Cabrera Benito");
	$propiedades->setTitle("Tablas");
	# Agregar texto...
	/*
	Todos los textos deben estar dentro de una sección
	 */
	$seccion = $documento->addSection();
	$estiloTabla = [
	    "borderColor" => "8bc34a",

	    "borderSize" => 1,
	];
	# Otra tabla
	$estiloTabla = [
	    "borderColor" => "000000",
	    "borderSize" => 3,
	    "cellMargin" => 80,
	];
	// Guardarlo para usarlo más tarde
	$documento->addTableStyle("estilo3", $estiloTabla);
	$tabla = $seccion->addTable("estilo3");
	$mascotas = [
	    [
	        "nombre" => "Maggie",
	        "edad" => 3,
	    ],
	    [
	        "nombre" => "Panqué",
	        "edad" => 1,
	    ],
	    [
	        "nombre" => "Guayaba",
	        "edad" => 2,
	    ],
	];
	# Encabezados
	$fuente = [
	    "name" => "Arial",
	    "size" => 12,
	    "color" => "fff",
	    "background" => "333333",
	];
	$tabla->addRow();
	$tabla->addCell()->addText("Modelo", $fuente);
	$tabla->addCell()->addText("Nombre del Producto", $fuente);
	$tabla->addCell()->addText("Precio Minimo", $fuente);
	$tabla->addCell()->addText("Precio Maximo", $fuente);
	$tabla->addCell()->addText("Unidad", $fuente);
	$tabla->addCell()->addText("Presentacion", $fuente);
	$tabla->addCell()->addText("Cliente", $fuente);
	$tabla->addCell()->addText("Minima en Inv.", $fuente);
	$tabla->addCell()->addText("Activo", $fuente);
	while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
		$tabla->addRow();
	    $tabla->addCell()->addText($res["code"]);
	    $tabla->addCell()->addText($res["name"]);
	    $tabla->addCell()->addText('S/. ' .$res["price_in"]);
	    $tabla->addCell()->addText('S/. ' .$res["price_in_2"]);
	    $tabla->addCell()->addText($res["unit"]);
	    $tabla->addCell()->addText($res["presentation"]);
	    $tabla->addCell()->addText($res["cliente"]);
	    $tabla->addCell()->addText($res["inventary_min"]);
	    if ($res['is_active'] == 1) {
	    	$tabla->addCell()->addText("SI");	
	    }else{
	    	$tabla->addCell()->addText("NO");
	    }
	    
	}
	# Para que no diga que se abre en modo de compatibilidad
	$documento->getCompatibility()->setOoxmlVersion(15);
	# Idioma español de México
	# Guardarlo
	$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, "Word2007");
	$filename = "products-".time().".docx";
	$objWriter->save($filename);
	header("Content-Disposition: attachment; filename=$filename");
	readfile($filename); // or echo file_get_contents($filename);
	unlink($filename);  // remove temp file
?>