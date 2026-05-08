<?php
	require "http://peruviandress.com/sivecsol/core/app/view/dompdf/vendor/autoload.php";
	use Dompdf\Dompdf;

	define("DOMPDF_ENABLE_REMOTE", true);
    $dompdf = new Dompdf();
    $html = "<style>h2{color: red;}</style><h2>Prueba pdf</h2>";
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4');
	$dompdf->render();
	$dompdf->stream("factura_n_.pdf");
?>