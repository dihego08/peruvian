<?php
	require "../core/app/view/dompdf/autoload.inc.php";
	use Dompdf\Dompdf;

	define("DOMPDF_ENABLE_REMOTE", true);
    $dompdf = new Dompdf();
    $html = "<h2 style=\"color: red;\">Prueba pdf</h2>";
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4');
	$dompdf->render();
	$dompdf->stream("factura_n_.pdf");
?>