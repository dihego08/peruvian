<?php
    $accion = $_GET['parAccion'];
    include("env.php");
    include("clsColaborador.php");
	$colaborador = new clsColaborador;
	
	$titulo = "";
	if($accion == "familiares"){
	    $titulo = "Familiares-pdf";
	    $pipi = "";
	    
	    $familiares = json_decode($colaborador->get_familiares($_GET['id_col']));
	    $data_colaborador = json_decode($colaborador->editar($_GET['id_col']));
	    
	    $html = "";
	    foreach($familiares as $key => $value){
	        $html .= '
	        <div class="card col-md-12" style="padding: 10px; margin-bottom: 5px;">
  				<div class="card-header" style="font-weight: bold;">
					'.$value->nombre.' '.$value->apellidos.'
  				</div>
				<div class="card-body">
					<h5 style="font-weight: bold;" class="card-title">'.$value->parentesco.'</h5>
					<p class="card-text" style="margin-bottom: 0px;">'.$value->fecha_nacimiento.' '.$value->lugar_nacimiento.'</p>
					<p style="font-weight: bold; margin-bottom: 0px;"><strong>Teléfono: </strong>'.$value->telefono.'</p>
					<p><strong>DNI: </strong>'.$value->dni.'</p>
  				</div>
			</div>
	        ';
	    }
	    
	    $pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
        	<page_header >
        		
        	</page_header>
        	<page_footer>
        	</page_footer>
        	<div style='width: 100%; text-align: center;'>
        		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo.png\" align='left' border='0' style='width: 150px; margin-left: auto; margin-right: auto;' />
        	</div>
        	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Lista de Familiares ".$data_colaborador->nombres." ".$data_colaborador->apellido_paterno." ".$data_colaborador->apellido_materno."</p>
        	<style>
        	    td{
        	        padding: 5px;
        	    }
        	    .card {
                	position: relative;
                	display: -ms-flexbox;
                	display: flex;
                	-ms-flex-direction: column;
                	flex-direction: column;
                	min-width: 0;
                	word-wrap: break-word;
                	background-color: #fff;
                	background-clip: border-box;
                	border: 1px solid rgba(0,0,0,.125);
                	border-top-color: rgba(0, 0, 0, 0.125);
                	border-right-color: rgba(0, 0, 0, 0.125);
                	border-bottom-color: rgba(0, 0, 0, 0.125);
                	border-left-color: rgba(0, 0, 0, 0.125);
                	border-radius: .25rem;
                }
                .card-header:first-child {
                	border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
                }
                .card-header {
                	padding: .75rem 1.25rem;
                	margin-bottom: 0;
                	border-bottom: 1px solid rgba(0,0,0,.125);
                }
                .card-body {
                	-ms-flex: 1 1 auto;
                	flex: 1 1 auto;
                	padding: 1.25rem;
                }
                .card-title {
                	margin-bottom: .75rem;
                }
        	</style>
        	".$html."
        </page>";
	}elseif($accion == "formacion"){
	    $titulo = "Formacion-pdf";
	    $pipi = "";
	    
	    $formacion = json_decode($colaborador->get_formacion($_GET['id_col']));
	    $data_colaborador = json_decode($colaborador->editar($_GET['id_col']));
	    
	    $html = "";
	    foreach($formacion as $key => $value){
	        $html .= '
	        <div class="card col-md-12" style="padding: 10px; margin-bottom: 5px;">
  				<div class="card-header" style="font-weight: bold;">
					'.$value->formacion.'
  				</div>
				<div class="card-body">
					<p>'.$value->lugar.'</p>
  				</div>
			</div>
	        ';
	    }
	    
	    $pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
        	<page_header >
        		
        	</page_header>
        	<page_footer>
        	</page_footer>
        	<div style='width: 100%; text-align: center;'>
        		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo.png\" align='left' border='0' style='width: 150px; margin-left: auto; margin-right: auto;' />
        	</div>
        	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Formacion ".$data_colaborador->nombres." ".$data_colaborador->apellido_paterno." ".$data_colaborador->apellido_materno."</p>
        	<style>
        	    td{
        	        padding: 5px;
        	    }
        	    .card {
                	position: relative;
                	display: -ms-flexbox;
                	display: flex;
                	-ms-flex-direction: column;
                	flex-direction: column;
                	min-width: 0;
                	word-wrap: break-word;
                	background-color: #fff;
                	background-clip: border-box;
                	border: 1px solid rgba(0,0,0,.125);
                	border-top-color: rgba(0, 0, 0, 0.125);
                	border-right-color: rgba(0, 0, 0, 0.125);
                	border-bottom-color: rgba(0, 0, 0, 0.125);
                	border-left-color: rgba(0, 0, 0, 0.125);
                	border-radius: .25rem;
                }
                .card-header:first-child {
                	border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
                }
                .card-header {
                	padding: .75rem 1.25rem;
                	margin-bottom: 0;
                	border-bottom: 1px solid rgba(0,0,0,.125);
                }
                .card-body {
                	-ms-flex: 1 1 auto;
                	flex: 1 1 auto;
                	padding: 1.25rem;
                }
                .card-title {
                	margin-bottom: .75rem;
                }
        	</style>
        	".$html."
        </page>";
	}elseif($accion == "experiencia"){
	    $titulo = "Experiencia-pdf";
	    $pipi = "";
	    
	    $experiencia = json_decode($colaborador->get_experiencia($_GET['id_col']));
	    $data_colaborador = json_decode($colaborador->editar($_GET['id_col']));
	    
	    $html = "";
	    foreach($experiencia as $key => $value){
	        $html .= '
	        <div class="card col-md-12" style="padding: 10px; margin-bottom: 5px;">
  				<div class="card-header" style="font-weight: bold;">
					'.$value->empresa.'
  				</div>
				<div class="card-body">
					<h5 style="font-weight: bold; margin-bottom: 0px;" class="card-title">'.$value->cargo.'</h5>
					<p class="card-text" style="margin-bottom: 0px;">'.$value->responsabilidades.' </p>
					<p style="font-weight: bold; margin-bottom: 0px;">'.$value->fecha_ingreso.' '.$value->fecha_termino.'</p>
					<p>'.$value->tiempo_servicio.'</p>
  				</div>
			</div>
	        ';
	    }
	    
	    $pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
        	<page_header >
        		
        	</page_header>
        	<page_footer>
        	</page_footer>
        	<div style='width: 100%; text-align: center;'>
        		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo.png\" align='left' border='0' style='width: 150px; margin-left: auto; margin-right: auto;' />
        	</div>
        	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Experiencia ".$data_colaborador->nombres." ".$data_colaborador->apellido_paterno." ".$data_colaborador->apellido_materno."</p>
        	<style>
        	    td{
        	        padding: 5px;
        	    }
        	    .card {
                	position: relative;
                	display: -ms-flexbox;
                	display: flex;
                	-ms-flex-direction: column;
                	flex-direction: column;
                	min-width: 0;
                	word-wrap: break-word;
                	background-color: #fff;
                	background-clip: border-box;
                	border: 1px solid rgba(0,0,0,.125);
                	border-top-color: rgba(0, 0, 0, 0.125);
                	border-right-color: rgba(0, 0, 0, 0.125);
                	border-bottom-color: rgba(0, 0, 0, 0.125);
                	border-left-color: rgba(0, 0, 0, 0.125);
                	border-radius: .25rem;
                }
                .card-header:first-child {
                	border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
                }
                .card-header {
                	padding: .75rem 1.25rem;
                	margin-bottom: 0;
                	border-bottom: 1px solid rgba(0,0,0,.125);
                }
                .card-body {
                	-ms-flex: 1 1 auto;
                	flex: 1 1 auto;
                	padding: 1.25rem;
                }
                .card-title {
                	margin-bottom: .75rem;
                }
        	</style>
        	".$html."
        </page>";
	}elseif($accion == "habilidades"){
	    $titulo = "Experiencia-pdf";
	    $pipi = "";
	    
	    $habilidades = json_decode($colaborador->get_habilidad($_GET['id_col']));
	    $data_colaborador = json_decode($colaborador->editar($_GET['id_col']));
	    
	    $html = "";
	    foreach($habilidades as $key => $value){
	        $html .= '
	        <div class="card col-md-12" style="padding: 10px; margin-bottom: 5px;">
  				<div class="card-header" style="font-weight: bold;">
					'.$value->elemento.'
  				</div>
				<div class="card-body">
					<p>'.$value->habilidad.'</p>
  				</div>
			</div>
	        ';
	    }
	    
	    $pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
        	<page_header >
        		
        	</page_header>
        	<page_footer>
        	</page_footer>
        	<div style='width: 100%; text-align: center;'>
        		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo.png\" align='left' border='0' style='width: 150px; margin-left: auto; margin-right: auto;' />
        	</div>
        	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Habilidades ".$data_colaborador->nombres." ".$data_colaborador->apellido_paterno." ".$data_colaborador->apellido_materno."</p>
        	<style>
        	    td{
        	        padding: 5px;
        	    }
        	    .card {
                	position: relative;
                	display: -ms-flexbox;
                	display: flex;
                	-ms-flex-direction: column;
                	flex-direction: column;
                	min-width: 0;
                	word-wrap: break-word;
                	background-color: #fff;
                	background-clip: border-box;
                	border: 1px solid rgba(0,0,0,.125);
                	border-top-color: rgba(0, 0, 0, 0.125);
                	border-right-color: rgba(0, 0, 0, 0.125);
                	border-bottom-color: rgba(0, 0, 0, 0.125);
                	border-left-color: rgba(0, 0, 0, 0.125);
                	border-radius: .25rem;
                }
                .card-header:first-child {
                	border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
                }
                .card-header {
                	padding: .75rem 1.25rem;
                	margin-bottom: 0;
                	border-bottom: 1px solid rgba(0,0,0,.125);
                }
                .card-body {
                	-ms-flex: 1 1 auto;
                	flex: 1 1 auto;
                	padding: 1.25rem;
                }
                .card-title {
                	margin-bottom: .75rem;
                }
        	</style>
        	".$html."
        </page>";
	}elseif($accion == "capacitaciones"){
	    $titulo = "Capacitaciones-pdf";
	    $pipi = "";
	    
	    $capacita = json_decode($colaborador->get_capacitacion($_GET['id_col']));
	    $data_colaborador = json_decode($colaborador->editar($_GET['id_col']));
	    
	    //print_r($capacitaciones);
	    
	    $html = "";
	    foreach($capacita as $key => $value){
	        $html .= '
	        <div class="card col-md-12" style="padding: 10px; margin-bottom: 5px;">
  				<div class="card-header" style="font-weight: bold;">
					'.$value->curso.'
  				</div>
				<div class="card-body">
					<p>'.$value->capacitador.'</p>
					<p style="font-weight: bold; margin-bottom: 0px;"><strong>Lugar: </strong>'.$value->lugar.'</p>
					<p>'.$value->fecha.' - '.$value->horas.'</p>
  				</div>
			</div>
	        ';
	    }
	    
	    $pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
        	<page_header >
        		
        	</page_header>
        	<page_footer>
        	</page_footer>
        	<div style='width: 100%; text-align: center;'>
        		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo.png\" align='left' border='0' style='width: 150px; margin-left: auto; margin-right: auto;' />
        	</div>
        	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Capacitacion ".$data_colaborador->nombres." ".$data_colaborador->apellido_paterno." ".$data_colaborador->apellido_materno."</p>
        	<style>
        	    td{
        	        padding: 5px;
        	    }
        	    .card {
                	position: relative;
                	display: -ms-flexbox;
                	display: flex;
                	-ms-flex-direction: column;
                	flex-direction: column;
                	min-width: 0;
                	word-wrap: break-word;
                	background-color: #fff;
                	background-clip: border-box;
                	border: 1px solid rgba(0,0,0,.125);
                	border-top-color: rgba(0, 0, 0, 0.125);
                	border-right-color: rgba(0, 0, 0, 0.125);
                	border-bottom-color: rgba(0, 0, 0, 0.125);
                	border-left-color: rgba(0, 0, 0, 0.125);
                	border-radius: .25rem;
                }
                .card-header:first-child {
                	border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
                }
                .card-header {
                	padding: .75rem 1.25rem;
                	margin-bottom: 0;
                	border-bottom: 1px solid rgba(0,0,0,.125);
                }
                .card-body {
                	-ms-flex: 1 1 auto;
                	flex: 1 1 auto;
                	padding: 1.25rem;
                }
                .card-title {
                	margin-bottom: .75rem;
                }
        	</style>
        	".$html."
        </page>";
	}elseif($accion == "vacaciones"){
		//echo "asdasas";
	    $titulo = "vacaciones-pdf";
	    $pipi = "";
	    
	    $capacita = json_decode($colaborador->get_vacaciones($_GET['id_col']));
	    $data_colaborador = json_decode($colaborador->editar($_GET['id_col']));
	    
	    //print_r($capacita);
	    
	    $html = "";
	    foreach($capacita as $key => $value){

	    	//print_r($value);

	        $html .= '
	        <div class="card col-md-12" style="padding: 10px; margin-bottom: 5px;">
  				<div class="card-header" style="font-weight: bold;">
					'.$value->periodo.'
  				</div>
				<div class="card-body">
					<p>'.$value->fecha_salida.' al '.$value->fecha_retorno.'</p>
					<p>'.$value->dias.' días.</p>
					<p>'.$value->observaciones.'</p>
  				</div>
			</div>
	        ';
	    }
	    
	    $pipi = "<page pageset='new' backleft='15mm' backtop='10mm' backright='15mm' footer='page'>
        	<page_header >
        		
        	</page_header>
        	<page_footer>
        	</page_footer>
        	<div style='width: 100%; text-align: center;'>
        		<img src=\"".$_SERVER['DOCUMENT_ROOT']."/img/logo.png\" align='left' border='0' style='width: 150px; margin-left: auto; margin-right: auto;' />
        	</div>
        	<p style='text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;'>Vacaciones ".$data_colaborador->nombres." ".$data_colaborador->apellido_paterno." ".$data_colaborador->apellido_materno."</p>
        	<style>
        	    td{
        	        padding: 5px;
        	    }
        	    .card {
                	position: relative;
                	display: -ms-flexbox;
                	display: flex;
                	-ms-flex-direction: column;
                	flex-direction: column;
                	min-width: 0;
                	word-wrap: break-word;
                	background-color: #fff;
                	background-clip: border-box;
                	border: 1px solid rgba(0,0,0,.125);
                	border-top-color: rgba(0, 0, 0, 0.125);
                	border-right-color: rgba(0, 0, 0, 0.125);
                	border-bottom-color: rgba(0, 0, 0, 0.125);
                	border-left-color: rgba(0, 0, 0, 0.125);
                	border-radius: .25rem;
                }
                .card-header:first-child {
                	border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
                }
                .card-header {
                	padding: .75rem 1.25rem;
                	margin-bottom: 0;
                	border-bottom: 1px solid rgba(0,0,0,.125);
                }
                .card-body {
                	-ms-flex: 1 1 auto;
                	flex: 1 1 auto;
                	padding: 1.25rem;
                }
                .card-title {
                	margin-bottom: .75rem;
                }
        	</style>
        	".$html."
        </page>";
	}
	
	header("Content-Disposition: attachment; filename=".$titulo.".pdf");

	require __DIR__.'/html2pdf/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	ob_start();

	$html2pdf = new Html2Pdf('P','A4','es','false','UTF-8',array(0,0,0,0));

	$html2pdf->writeHTML($pipi);

	$html2pdf->Output($titulo.".pdf", 'D');  
?>