<?php
	include('env.php');
	
	$codigo_producto = date("Y");

	$query = $mbd->prepare("SELECT * FROM capacitacion_registro WHERE anio = :anio");
    $anio = date("Y");
    $query->bindParam(":anio", $anio);
    $query->execute();

  	$table = "";
  	$auxiliar = 0;
  	while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
  		$table .= '<tr>
  			<td style="padding: 5px; width: 150px; font-size: 17px;"><strong>'.$res['curso'].'</strong></td>
          	<td style="padding: 5px; width: 100px; font-size: 17px;">'.$res['areas'].'</td>';
          	
      	$meses = explode(",", $res['mes']);

      	for ($i = 0; $i < 12; $i++) { 
      		$table .= '<td style="padding: 5px; text-align: center;">';
      		for ($j = 0; $j < count($meses); $j++) { 
	      		if($i == $meses[$j]){
	      			$table .= "<strong>X</strong>";
	      		}else{
	      			
	      		}
	      	}
	      	$table .= '</td>';
      	}
  		$auxiliar++;
  		$table .= '</tr>';
  	}
	

	$filename = "reporte_" . date('d-m-Y') . ".xls";
	header("Content-Type: application/vnd.ms-excel charset=UTF-8");
	header("Content-Disposition: attachment; filename=".$filename);
	$mostrar_columnas = false;
	foreach($values as $libro) {
		if(!$mostrar_columnas) {
			echo implode("\t", array_keys($libro)) . "\n";
			$mostrar_columnas = true;
		}
		echo implode("\t", array_values($libro)) . "\n";
	}
?>