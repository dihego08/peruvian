<?php
    $filtro = $_GET['filtro'];
	$tabla = $_GET['tabla'];
	if($filtro == 'fecha' && $tabla == 'compras'){
		$desde = $_GET['desde'];
		$hasta = $_GET['hasta'];
		
		include("clsInsumos.php");
	    $compra = new ClsInsumos;
	    $compras = json_decode($compra->lista_compras_2($_GET));
		
		$aux = 0;
		foreach($compras->Records as $key => $value){
			$detalle_compra = json_decode($compra->lista_detalle($value->id));
			$insumos_ = "";
			$aux_2 = 0;
			foreach ($detalle_compra->Records as $k => $v) {
				if($aux_2 == 0){
					$insumos_ = $v->insumo;
				}else{
					$insumos_ .= " | ".$v->insumo;
				}
				$aux_2++;
			}

		    $aux++;
		    $ppp = "";
			if ($value->proveedor == 'null' || $value->proveedor == "") {
				$ppp = "";
			} else {
				$ppp = $value->proveedor;
			}

		    $values[] = array(
				"Com" => $aux,
    			"Tipo" => $value->tipo_documento,
    			"Serie" => "'".strval($value->serie),
    			"Numero" => "'".strval($value->numeracion),
    			"Fecha Emision" => $value->fecha_creacion,
    			"Apellidos y Nombres y/o Razon Social" => $ppp,
    			"RUC" => $value->no,
    			"Insumos" => $insumos_,
    			"Otros no Gravados" => $value->otros_no_gravado,
    			"Adquisi no Gravadas" => $value->exonerado,
    			"Adquisio Gravadas" => $value->gravado,
    			"IGV" => $value->igv,
    			"Importe Total" => $value->total,
    			"Constan Fecha" => $value->fecha_detraccion,
    			"Detraccion Numero" => "'".strval($value->numero_detraccion),
    			"T/C" => $value->tipo_cambio,
    			"Fecha" => strval($value->fecha_comprobante),
    			"Serie C." => "'".strval($value->serie_comprobante),
    			"Doc." => "'".strval($value->documento_comprobante),
            );
		}
	}elseif ($filtro == 'ninguno' && $tabla == 'compras') {
	    include("clsInsumos.php");
	    $compra = new ClsInsumos;
	    $compras = json_decode($compra->lista_compras());
		
		$aux = 0;
		foreach($compras->Records as $key => $value){
			$detalle_compra = json_decode($compra->lista_detalle($value->id));
			$insumos_ = "";
			$aux_2 = 0;
			foreach ($detalle_compra->Records as $k => $v) {
				if($aux_2 == 0){
					$insumos_ = $v->insumo;
				}else{
					$insumos_ .= " | ".$v->insumo;
				}
				$aux_2++;
			}
		    $aux++;
		    $ppp = "";
			if ($value->proveedor == 'null' || $value->proveedor == "") {
				$ppp = "";
			} else {
				$ppp = $value->proveedor;
			}
			
			$values[] = array(
				"Com" => $aux,
    			"Tipo" => $value->tipo_documento,
    			"Serie" => "'".strval($value->serie),
    			"Numero" => "'".strval($value->numeracion),
    			"Fecha Emision" => $value->fecha_creacion,
    			"Apellidos y Nombres y/o Razon Social" => $ppp,
    			"RUC" => $value->no,
    			"Insumos" => $insumos_,
    			"Otros no Gravados" => $value->otros_no_gravado,
    			"Adquisi no Gravadas" => $value->exonerado,
    			"Adquisio Gravadas" => $value->gravado,
    			"IGV" => $value->igv,
    			"Importe Total" => $value->total,
    			"Constan Fecha" => $value->fecha_detraccion,
    			"Detraccion Numero" => "'".strval($value->numero_detraccion),
    			"T/C" => $value->tipo_cambio,
    			"Fecha" => strval($value->fecha_comprobante),
    			"Serie C." => "'".strval($value->serie_comprobante),
    			"Doc." => "'".strval($value->documento_comprobante),
            );
		}	
	}
	
	if(!empty($values)) {
		$filename = "reporte_compras-" . date('d-m-Y') . ".xls";
		header("Content-type: text/html; charset=utf8");
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
	}else{
		echo 'No hay datos a exportar';

	}
?>