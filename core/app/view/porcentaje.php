<?php 
	function porcentaje($fechaI, $fechaF){
		$fechaInicio = new DateTime($fechaI);
		$fechaFinal = new DateTime($fechaF);
		$hoy = date("Y/m/d");
		$fechaActual = new DateTime($hoy);

		$totalDias = $fechaInicio->diff($fechaFinal);
		$diasTrans = $fechaInicio->diff($fechaActual);
		//echo "Actual ".$diasTrans->format('%R%a');
		
		$int = $totalDias->format('%R%a');
		//echo "Int 1: ".$int."<br>";

		$int2 = $diasTrans->format('%R%a');
		//echo "Int 2: ".$int2."<br>";
		//echo "Suma".($int - $int2."<br>");
		//echo $totalDias->format('%R%a días');

		$porcentaje = ($int2*100)/$int;
		//echo "<br><br> porcentaje: ".$porcentaje."<br>";

		return $porcentaje;
	}
?>