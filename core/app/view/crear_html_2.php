<?php
	$html = $_POST['html'];

	//$contenido = "<html><body><h1>Datos</h1><p>$nombre</p><p>$apellido</p></body></html>";

	//file_put_contents('dompdf/datos.html', $html);

	/*$fichero = 'dompdf/datos.html';
	// Abre el fichero para obtener el contenido existente
	$actual = file_get_contents($fichero);
	// Añade una nueva persona al fichero
	$actual = $html;
	// Escribe el contenido al fichero
	file_put_contents('dompdf/datos.html', $html);*/
	$file = "dompdf/datos_2.html";
	$texto = $html;
	$fp = fopen($file, "wa+");
	fwrite($fp, $texto);
	fclose($fp);
?>