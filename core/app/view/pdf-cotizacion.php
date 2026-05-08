<?php
/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);*/
include('env.php');
$codigo = $_GET['codigo'];
//$query = $mbd->prepare("SELECT c.*,p.name as cotcliente FROM cotizacion c inner join person p on c.person_id = p.id WHERE codigo = :codigo");
$query = $mbd->prepare("SELECT c.*, p.name as cotcliente FROM cotizacion c inner join person p on c.person_id = p.id WHERE codigo = :codigo UNION SELECT *, '' FROM cotizacion WHERE codigo = :codigo");
//$query = $mbd->prepare("SELECT * FROM cotizacion_detalle WHERE codigo_cotizacion = :codigo");
$query->bindParam(':codigo', $codigo);
$query->execute();
$cotizacion = $query->fetch(PDO::FETCH_ASSOC);

$new_fecha = explode(" ", $cotizacion['fecha_creacion']);
$meses = array(
	"Ene.",
	"Feb.",
	"Mar.",
	"Abr.",
	"May.",
	"Jun.",
	"Jul.",
	"Ago.",
	"Sep.",
	"Oct.",
	"Nov.",
	"Dic.",
);

$fecha_dias = explode("-", $new_fecha[0]);

$clientedesc = $cotizacion['cliente'];

$url_fondo = $_SERVER['DOCUMENT_ROOT'] . '/core/app/view/img/fondo.png';

if ($clientedesc == "") {
	$clientedesc = $cotizacion['cotcliente'];
}

$query2 = $mbd->prepare("SELECT cd.*, p.name FROM cotizacion_detalle as cd, product as p WHERE p.id = id_producto AND cd.codigo_cotizacion = :codigo");
$query2->bindParam(':codigo', $codigo);
$query2->execute();
$cuenta_col = $query2->rowCount();
$html = '<page>
			
			<style>
				#watermark { position: fixed; bottom: 0px; right: 0px; width: 100%; height: 200px; }
				table{
				    margin-bottom:5px;
				}
				table, tbody{
				    border-collapse: collapse;
				    text-align:center;
				}
			</style>
			<img src="' . $_SERVER['DOCUMENT_ROOT'] . '/img/logo-2.png" style="width:98px;">
			<span style="display: block; width: 100%; margin-top: 10px;"><strong>Fecha:</strong> ' . $cotizacion['fecha_creacion'] . '</span>
			<span style="display: block; width: 100%;"><strong>Señor(es):</strong> ' . $clientedesc . '</span>
			<span style="display: block; width: 100%; margin-bottom: 10px;"><strong>Direccion:</strong> Arequipa</span>

			<p>Estimado Señor:</p>
			<p>Nos es grato saludarlo por medio de la presente y hacerle llegar la siguiente cotizacion.</p>';

$html .= '<table border=1>
				<tr>
					<td style="text-align: center; font-weight: bold;">ITEM</td>
					<td style="text-align: center; font-weight: bold;">DESCRIPCION</td>
					<td style="text-align: center; font-weight: bold;">IMAGEN</td>
					<td style="text-align: center; font-weight: bold;">PRECIO</td>
				</tr>
			';

$aux = 0;
$table = "";
while ($res = $query2->fetch(PDO::FETCH_ASSOC)) {
	$imgs = "";
	$aux = $aux + 1;

	if ($res['imagen'] != "") {
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/storage/products/' . $res['imagen'])) {
			$imgs .= '<img src="' . $_SERVER['DOCUMENT_ROOT'] . '/storage/products/' . $res['imagen'] . '" align=\'left\' border=\'0\' style="margin-left: auto; margin-right: auto; width: 45%; border-radius: 8px;">';
		}
	}
	if ($res['imagen_2'] != "") {
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/storage/products/' . $res['imagen_2'])) {
			$imgs .= '<img src="' . $_SERVER['DOCUMENT_ROOT'] . '/storage/products/' . $res['imagen_2'] . '" align=\'left\' border=\'0\' style="margin-left: auto; margin-right: auto; width: 45%; border-radius: 8px;">';
		}
	}
	$igv_incluye = '';
	if ($cotizacion['igv_incluye'] == 1) {
		$igv_incluye = 'INCLUYE IGV';
	}
	$html .= '
					<tr>
						<td style="text-align: center; font-weight: bold;">
							' . $aux . '
						</td>
						<td style="padding-left: 5px; padding-right: 5px;">
							<p>' . $res['name'] . '</p>
							<p style="text-align: justify; width: 100%; white-space: break-spaces;">' . $res['descripcion'] . '</p>
						</td>
						<td style="text-align: center;">
							' . $imgs . '
						</td>
						<td style="text-align: center; font-weight: bold;">
							S/. ' . $res['costo'] . '
							<p><strong>' . $igv_incluye . '</strong></p>
						</td>
					</tr>
				';

	$table .= '
					<tr>
						<td style="text-align: center; font-weight: bold; width: 5%; border: solid 1px #666;">
							' . $aux . '
						</td>
						<td style="padding-left: 5px; padding-right: 5px; width: 38%; border: solid 1px #666;">
							<p style="font-weight: bold;">' . $res['nombre_producto'] . '</p>
							<p style="white-space: break-spaces;">' . $res['descripcion'] . '</p>
						</td>
						<td style="text-align: center; width: 50%; border: solid 1px #666; vertical-align: middle;">
							' . $imgs . '
						</td>
						<td style="text-align: center; font-weight: bold; width: 5%; border: solid 1px #666;">
							S/. ' . $res['costo'] . '
							<p><strong>' . $igv_incluye . '</strong></p>
						</td>
					</tr>
				';

	if ($aux < $cuenta_col) {
		$html = $html . '<div class="page_break"></div>';
	} else {
	}
}

$html .= '</table>';

$adicional = "";
$adicional = '<p style="margin-top: 10px; margin-bottom: 0px;"><strong>SERVICIOS:</strong> ' . $cotizacion['servicios'] . ' </p>
				<p style="margin-bottom: 0px;"><strong>TIEMPO DE ENTREGA:</strong> ' . $cotizacion['tiempo_entrega'] . '</p>
				<p style="margin-bottom: 0px;"><strong>VALIDEZ DE LA OFERTA:</strong> ' . $cotizacion['validez'] . '.</p>
				<p style="margin-bottom: 0px;"><strong>FORMA DE PAGO:</strong> ' . $cotizacion['forma_pago'] . '.</p>
				<p style="margin-bottom: 0px;"><strong>TALLAS ESPECIALES:</strong> ' . $cotizacion['tallas_especiales'] . '.</p>
			';
if ($cotizacion['obervacion'] != "") {
	$adicional = $adicional . '<p style="margin-bottom: 0px;"><strong>OBSERVACIONES:</strong> ' . $cotizacion['obervacion'] . '</p>';
} else {
}

$html = $html . '
				
				<div id="watermark" style="position: relative;"><img src="' . $_SERVER['DOCUMENT_ROOT'] . '/core/app/view/img/fondo.png" height="100%" width="100%">
					<div class="img-fondo" style="position: absolute;" >
						<p>Calle Belén N°319 - Jerusalén - Mariano Melgar - Arequipa - Perú</p>
						<h4>CONTACTO:</h4>
						<p>+51 - 958133948 / informes@softluttion.com | <img src="' . $_SERVER['DOCUMENT_ROOT'] . '/core/app/view/img/fb-logo.png" style="width: 5%; margin-top: 15px;"> /peruviandress</p>
					</div>
				</div>
		</page>';
//echo $html;
/**********************************************/
/*require_once 'dompdf/autoload.inc.php';

	// reference the Dompdf namespace
	use Dompdf\Dompdf;

	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);

	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');

	// Render the HTML as PDF
	$dompdf->render();

	// Output the generated PDF to Browser
	$dompdf->stream();*/
/**********************************************/

$pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
	<page_header >
		
	</page_header>
	<page_footer>
	</page_footer>
	<div style='width: 100%;'>
		<img src=\"" . $_SERVER['DOCUMENT_ROOT'] . "/img/logo-3.png\" align='left' border='0' style='width: 200px;' />
	</div>
	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Cotización: " . $codigo . "</p>
	<p style=\"margin-bottom: 0px; display: block; width: 100%; \"><strong>Fecha:</strong> Arequipa, " . $fecha_dias[2] . " de " . $meses[intval($fecha_dias[1] - 1)] . " del " . $fecha_dias[0] . " " . $new_fecha[1] . "</p>
	<p style=\"margin-bottom: 0px; display: block; width: 100%;\"><strong>Señor(es):</strong> " . $clientedesc . "</p>
	<p style=\"display: block; width: 100%; margin-bottom: 10px;\"><strong>Direccion:</strong> Arequipa</p>
	<p style=\"margin-bottom: 0px;\">Estimado Señor(a):</p>
	<p>Nos es grato saludarlo por medio de la presente y hacerle llegar la siguiente cotizacion.</p>
	<br />
	<table cellpadding='4' cellspacing='0' style=\"width: 100%; border: solid 1px #666; border-top-left-radius: 8px; border-top-left-radius: 8px;\">
		<tr>
			<td style=\"text-align: center; font-weight: bold; padding: 5px; border: solid 1px #666; border-top-left-radius: 8px;\">ITEM</td>
			<td style=\"text-align: center; font-weight: bold; padding: 5px; border: solid 1px #666;\">DESCRIPCION</td>
			<td style=\"text-align: center; font-weight: bold; padding: 5px; border: solid 1px #666;\">IMAGEN</td>
			<td style=\"text-align: center; font-weight: bold; padding: 5px; border: solid 1px #666; border-top-right-radius: 8px;\">PRECIO</td>
		</tr>
		" . $table . "
	</table>
	<br />
	<br />
	" . $adicional . "
	<br />
	<br />
	<p style='margin-top: 10px;'>Sin otro particular, nos despedimos de Ud. a la espera de sus gratas indicaciones.</p>
	
	<div id=\"watermark\" style=\"width: 100%; height: 100px;\">
		<div class=\"img-fondo\"  >
			<table style='width: 100%;'>
			<tr>
				<td style='width: 70%;'>
			<p style=\"margin-bottom: 5px; margin-top: 20px;\">Gustoso de atenderte...</p>
			<p style=\"margin-bottom: 5px;\">Asesor Comercial</p>
			<p style=\"font-weight: bold; display: block; margin-bottom: 0px; width: 100%;\">" . $cotizacion['asesor_comercial'] . "</p>
			<p style=\"display: block; width: 100%; margin-bottom: 0px;\"><img src=\"" . $_SERVER['DOCUMENT_ROOT'] . "/core/app/view/img/celular1.png\" style='width: 5%; display: block;'> Celular: 999045777 | " . $cotizacion['asesor_celular'] . "
			</p>
			<p>
			<img src=\"" . $_SERVER['DOCUMENT_ROOT'] . "/core/app/view/img/mensaje.png\" style='width: 5%; display: block;'> 
				Correo: <span style='color: blue;'>ventas@peruviandress.com</span> <span> | </span> <span style='color: blue;'>omendoza@peruviandress.com</span>
			</p>
			</td>
				<td style='width: 30%; vertical-align: bottom;'>
			<p style='width: 100%; text-align: right;'>

				<img src=\"" . $_SERVER['DOCUMENT_ROOT'] . "/core/app/view/img/iso9.png\" style='width: 35%; margin-left: auto; margin-right: auto;'>
				<span style='width: 10%; display: block;'></span>
				<img src=\"" . $_SERVER['DOCUMENT_ROOT'] . "/core/app/view/img/iso45.png\" style='width: 35%; margin-left: auto; margin-right: auto;'>

			</p>
			</td>
			</tr></table>
		</div>
	</div>
</page>";

//echo $pipi;


header("Content-Disposition: attachment; filename=cotizacion-" . $codigo . ".pdf");
require __DIR__ . '/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

ob_start();

$html2pdf = new Html2Pdf('P', 'A4', 'es', 'false', 'UTF-8', array(0, 0, 0, 0));

$html2pdf->writeHTML($pipi);

$html2pdf->output();
	// $html2pdf->Output("cotizacion-".$codigo.".pdf", 'D');  
