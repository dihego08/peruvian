<?php

/** Error reporting */
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

	$query = $mbd->prepare($sql);
	$query->execute();
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	include "../core/autoload.php";
	include "../core/app/model/ProductData.php";
	include "../core/app/model/CategoryData.php";

	/** Include PHPExcel */
	//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
	require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$products = ProductData::getAll();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Peruviandress")
								 ->setLastModifiedBy("Peruviandress")
								 ->setTitle("Productos")
								 ->setSubject("reporte")
								 ->setDescription("")
								 ->setKeywords("")
								 ->setCategory("");


	// Add some data
	$sheet = $objPHPExcel->setActiveSheetIndex(0);
/*
	$tabla->addCell()->addText("Modelo", $fuente);
	$tabla->addCell()->addText("Nombre del Producto", $fuente);
	$tabla->addCell()->addText("Precio Minimo", $fuente);
	$tabla->addCell()->addText("Precio Maximo", $fuente);
	$tabla->addCell()->addText("Presentacion", $fuente);
	$tabla->addCell()->addText("Cliente", $fuente);
	$tabla->addCell()->addText("Minima en Inv.", $fuente);
	$tabla->addCell()->addText("Activo", $fuente);
 */
	$sheet->setCellValue('A1', 'Reporte de Productos')
	->setCellValue('A2', 'Modelo')
	->setCellValue('B2', 'Nombre del Producto')
	->setCellValue('C2', 'Precio Minimo')
	->setCellValue('D2', 'Precio Maximo')
	->setCellValue('E2', 'Unidad')
	->setCellValue('F2', 'Presentacion')
	->setCellValue('G2', 'Cliente')
	->setCellValue('H2', 'Minima en Inv.')
	->setCellValue('I2', 'Activo');

	$start = 3;
	/*foreach($products as $product){
	$sheet->setCellValue('A'.$start, $product->barcode)
	->setCellValue('B'.$start, $product->id)
	->setCellValue('C'.$start, $product->name)
	->setCellValue('D'.$start, $product->price_in)
	->setCellValue('E'.$start, $product->price_in_2)
	->setCellValue('F'.$start, $product->unit)
	->setCellValue('G'.$start, $product->presentation)
	->setCellValue('H'.$start, $product->)
	->setCellValue('I'.$start, $product->category_id)
	->setCellValue('J'.$start, $product->is_active);
	$start++;
	}*/
	while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
		$vv = "";
		if ($res['is_active'] == 1) {
			$vv = "SI";
		}else{
			$vv = "NO";
		}
		$sheet->setCellValue('A'.$start, $res['code'])
		->setCellValue('B'.$start, $res['name'])
		->setCellValue('C'.$start, 'S/. '.$res['price_in'])
		->setCellValue('D'.$start, 'S/. '.$res['price_in_2'])
		->setCellValue('E'.$start, $res['unit'])
		->setCellValue('F'.$start, $res['presentation'])
		->setCellValue('G'.$start, $res['cliente'])
		->setCellValue('H'.$start, $res['inventary_min'])
		->setCellValue('I'.$start, $vv);
		$start++;
	}

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="products-'.time().'.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
