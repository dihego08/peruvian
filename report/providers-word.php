<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/InsumosData.php";

//require_once '../core/controller/PhpWord/Autoloader.php';
include 'vendor/autoload.php';
/*use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();*/

$word = new \PhpOffice\PhpWord\PhpWord();
$clients = PersonData::getProviders();


$section1 = $word->AddSection(array('orientation' => 'landscape'));
$section1->addText("PROVEEDORES",array("size"=>22,"bold"=>true,"align"=>"center"));


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell(1600)->addText("Insumo/Material");
$table1->addCell(1400)->addText("DNI/RUC");
$table1->addCell(1800)->addText("Nombre completo");
$table1->addCell(2400)->addText("Direccion");
$table1->addCell(1500)->addText("Banco");
$table1->addCell(1500)->addText("Nro. de Cuenta");
$table1->addCell(1500)->addText("Email");
$table1->addCell(1100)->addText("Telefono");
$table1->addCell(1100)->addText("WSP");
$table1->addCell(1100)->addText("Forma Envío");

foreach($clients as $client){
	$insumo = InsumosData::getById($client->id_insumo);
$table1->addRow();
$table1->addCell(1600)->addText($insumo->insumo);
$table1->addCell(1400)->addText($client->no);
$table1->addCell(1800)->addText($client->name);
$table1->addCell(2400)->addText($client->address1);
$table1->addCell(1500)->addText($client->banco);
$table1->addCell(1500)->addText($client->nro_cuenta);
$table1->addCell(1500)->addText($client->email1);
$table1->addCell(1100)->addText($client->phone1);
$table1->addCell(1100)->addText($client->wsp);
$table1->addCell(1100)->addText($client->forma_envio);

}

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
/// datos bancarios

$filename = "providers-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename=$filename");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>