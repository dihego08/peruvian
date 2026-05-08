<?php 
	include('env.php');
	include("clsColaborador.php");
	$colaborador = new clsColaborador;
	$puesto = json_decode($colaborador->editar($_GET['id_c']));

	$puesto_2 = json_decode($colaborador->editar_puesto($puesto->id_cargo));
	
	$sistema_pension = json_decode($colaborador->get_sistema_pension($puesto->id_sistema_pension));
	$entidad_pension = json_decode($colaborador->get_entidad_pension($puesto->id_entidad_pension));
	$estado_civil = json_decode($colaborador->get_estado_civil($puesto->id_estado_civil));
	
	$sexo = "";
	if($puesto->genero == "M"){
	    $sexo = "MASCULINO";
	}else{
	    $sexo = "FEMENINO";
	}
	$estado_laboral = "";
	switch($puesto->estado_laboral){
	    case 1:
	        $estado_laboral = "Contratado";
	        break;
        case 2:
            $estado_laboral = "Labora s/Contrado";
	        break;
        case 3:
            $estado_laboral = "Practicante";
	        break;
        case 4:
            $estado_laboral = "Contrato Vencido";
	        break;
        case 5:
            $estado_laboral = "Renuncia";
	        break;
        case 6:
            $estado_laboral = "Despido";
	        break;
	}
	$asegurado = "";
	if($puesto->asegurado == 1){
	    $asegurado = "SI";
	}else{
	    $asegurado = "NO";
	}

	$la_fotito = "";
	if(is_null($puesto->foto) || empty($puesto->foto)){
		$la_fotito = "";
	}else{
		$la_fotito = "<img src=\"".$_SERVER['DOCUMENT_ROOT']."/core/app/view/img-colaboradores/".$puesto->foto."\" style=\"width: 150px; border-radius: 4px;\">";
	}

	$pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
	<page_header >
		
	</page_header>
	<page_footer>
	</page_footer>
	<div style='width: 100%; text-align: center;'>
		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo.png\" align='left' border='0' style='width: 150px; margin-left: auto; margin-right: auto;' />
	</div>
	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Datos del Colaborador</p>
	<style>
	    td{
	        padding: 5px;
	    }
	</style>
	<table  style='width: 100%;' border=1>
	    <tr style='width: 50%;'>
	        <td>
	            <div>
	                <table >
	                    <tr>
	                        <td>
	                            <p style='padding-bottom: 15px;'><strong>DNI:</strong> ".$puesto->dni."</p>
	                        </td>
	                    </tr>
	                    <tr>
	                        <td>
	                            <strong>Nombres:</strong> ".$puesto->nombres."
	                        </td>
	                    </tr>
	                    <tr>
	                        <td>
	                            <strong>Apellido Paterno:</strong> ".$puesto->apellido_paterno."
	                        </td>
	                    </tr>
	                    <tr>
	                        <td>
	                            <strong>Apellido Materno:</strong> ".$puesto->apellido_materno."
	                        </td>
	                    </tr>
	                    <tr>
	                        <td>
	                            <strong>Puesto:</strong> ".$puesto_2->puesto."
	                        </td>
	                    </tr>
	                    <tr>
	                        <td>
	                            <strong>Línea:</strong> ".$puesto->linea."
	                        </td>
	                    </tr>
	                </table>
	            </div>
	        </td>
	        <td colspan=2>
				".$la_fotito."
	        </td>
	    </tr>
	    <tr>
	        <td>
	            <strong>Celular:</strong> <p>".$puesto->celular."</p>
	        </td>
	        <td>
	            <strong>Area:</strong> <p>".$puesto->area."</p>
	        </td>
	        <td>
	            <strong>Género:</strong> <p>".$sexo."</p>
	        </td>
	    </tr>
	    <tr>
	        <td>
	            <strong>Fec. Nacimiento:</strong> <p>".$puesto->fecha_nacimiento."</p>
	        </td>
	        <td>
	            <strong>Teléfono de Emergencia:</strong> <p>".$puesto->telefono_emergencia."</p>
	        </td>
	        <td>
	            <strong>Estado Laboral:</strong> <p>".$estado_laboral."</p>
	        </td>
	    </tr>
	    <tr>
	        <td>
	            <strong>Lugar de Nacimiento:</strong> <p>".$puesto->lugar_nacimiento."</p>
	        </td>
	        <td>
	            <strong>Sueldo:</strong> <p>".$puesto->sueldo."</p>
	        </td>
	        <td>
	            <strong>Fecha Ingreso:</strong> <p>".$puesto->fecha_ingreso."</p>
	        </td>
	    </tr>
	    <tr>
	        <td>
	            <strong>Estado Civil:</strong> <p>".$estado_civil->estado_civil."</p>
	        </td>
	        <td>
	            <strong>Sistema de Pensiones:</strong> <p>".$sistema_pension->sistema_pension."</p>
	        </td>
	        <td>
	            <strong>Fecha Salida:</strong> <p>".$puesto->fecha_salida."</p>
	        </td>
	    </tr>
	    <tr>
	        <td>
	            <strong>Brevette:</strong> <p>".$puesto->brevette."</p>
	        </td>
	        <td>
	            <strong>Entidad de Pensiones:</strong> <p>".$entidad_pension->entidad_pension."</p>
	        </td>
	        <td>
	            <strong>Asegurado:</strong> <p>".$asegurado."</p>
	        </td>
	    </tr>
	    <tr>
	        <td>
	            <strong>Correo:</strong> <p>".$puesto->correo."</p>
	        </td>
	        <td colspan=2>
	            <strong>Código:</strong> <p>".$puesto->codigo."</p>
	        </td>
	    </tr>
	    <tr>
	        <td colspan=3>
	            <strong>Dirección:</strong> <p>".$puesto->direccion."</p>
	        </td>
	    </tr>
	</table>
</page>";

	//echo $pipi;

	header("Content-Disposition: attachment; filename=Datos-Colaborador.pdf");

	require __DIR__.'/html2pdf/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	ob_start();

	$html2pdf = new Html2Pdf('P','A4','es','false','UTF-8',array(0,0,0,0));

	$html2pdf->writeHTML($pipi);

	$html2pdf->Output("Datos-Colaborador.pdf", 'D');  
?>