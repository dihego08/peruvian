<?php
	require_once 'dompdf/autoload.inc.php';
	use Dompdf\Dompdf;
	$dompdf = new Dompdf();
	$tipo = $_GET['tipo'];
	if($tipo == 'cuentas'){
		$html = '<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title>Document</title>
				
				<link href="https://www.softluttion.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
				<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>
			</head>
			<body>
				<img src="'.$_SERVER['DOCUMENT_ROOT'].'/sivecsol/img/logo-2.png" style="width:98px;">
				<h3 style="font-weight: bold; text-align: center;">Reporte de Ventas '.$pp.'</h3>
				<table class="table table-bordered">
					<tr>
						<th>Concepto</th>
						<th>Monto</th>
						<th>Fecha de Vencimiento</th>
						<th>Estado</th>
					</tr>';
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM cuentas_pagar ORDER BY fecha_vencimiento DESC");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$cls = "";
			$e = "";
			$r = "";
			if ($res['prioridad'] == 1) {
				$cls = "danger";
			} else {
				if ($res['prioridad'] == 2) {
					$cls = "warning";
				} else {
					if ($res['prioridad'] == 3) {
						$cls = "success";
					} else {
						if ($res['prioridad'] == 4) {
							$cls = "primary";
						} else {
							if ($res['prioridad'] == 5) {
								$cls = "info";
							}
						}
					}
				}
			}
			if ($res['estado'] == 1) {
				$e = "Pagado";
				$r = "disabled";
			} else {
				if ($res['estado'] == 0) {
					$e = "Debe";
				}
			}
			$html = $html . '<tr class="'.$cls.'"><td>'.$res['concepto'].'</td><td>S/. '.$res['monto'].'</td><td>'.$res['fecha_vencimiento'].'</td><td>'.$e.'</td></tr>';
		}
		$html = $html.'
	                    </table>
	                </body>
			</html>';

		//echo $html;
		/*require_once 'dompdf/autoload.inc.php';
		use Dompdf\Dompdf;
		$dompdf = new Dompdf();*/
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream('my.pdf',array('Attachment'=>0));
	}elseif ($tipo == "retiros") {
		if (isset($_GET['extra'])) {
			$dompdf->load_html_file('dompdf/datos_2.html');
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$dompdf->stream('my.pdf',array('Attachment'=>0));
		}else{
			$dompdf->load_html_file('dompdf/datos.html');
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$dompdf->stream('my.pdf',array('Attachment'=>0));
		}
	}

?>