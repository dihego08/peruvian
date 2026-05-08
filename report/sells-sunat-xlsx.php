<?php
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
/** Error reporting */
	include('../core/app/view/env.php');

	//$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_creacion DESC");

	$query = $mbd->prepare("SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name as person, p.name as pago, d.name as entrega, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND DATE(vc.fecha_emision) BETWEEN :desde AND :hasta AND  vc.tipo_documento IN (1, 2) ORDER BY vc.fecha_emision DESC");
	$query->bindParam(':desde', $desde);
	$query->bindParam(':hasta', $hasta);
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
	$sheet->setCellValue('A1', 'Reporte de Ventas')
	->setCellValue('A2', 'Cliente')
	->setCellValue('B2', 'Nro Documento')
	->setCellValue('C2', 'Fecha')
	->setCellValue('D2', 'V. Venta')
	->setCellValue('E2', 'P. Venta')
	->setCellValue('F2', 'IGV')
	->setCellValue('G2', 'Detrac')
	->setCellValue('H2', 'IGV Por Pagar')
	->setCellValue('I2', 'Renta 3era Cat')
	->setCellValue('J2', 'Valor a Pagar');

	$start = 3;
	
	while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
		
		$sheet->setCellValue('A'.$start, $res['person'])
		->setCellValue('B'.$start, $res['codigo_venta'])
		->setCellValue('C'.$start, $res['fecha_creacion'])
		->setCellValue('D'.$start, $res['subtotal'])
		->setCellValue('E'.$start, $res['total'])
		->setCellValue('F'.$start, $res['igv'])
		->setCellValue('G'.$start, $res['detraccion_p'])
		->setCellValue('H'.$start, $res['igv_p'])
		->setCellValue('I'.$start, $res['subtotal'] * 0.02)
		->setCellValue('J'.$start, $res['total'] - $res['detraccion_p']);
		$start++;
		//$start =  $start + 1;
	}

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="ventas-'.time().'.xlsx"');
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
