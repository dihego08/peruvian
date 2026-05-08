<style type="text/css">
	.ct-label {
	    font-size: 15px;
	    color:black;
	}
	.clsDatePicker {
        position:absolute;
        cursor:default;
        z-index:1001 !important
    }
    .ui-datepicker-month{
        color: #313131;
    }
    .ui-datepicker-year{
        color: #313131;
    }
    .mt-2{
    	margin-top: 1rem !important;
    }
    .mt-3{
    	margin-top: 1.5rem !important;
    }
    .mb-3 {
	    margin-bottom: 1rem !important;
	}
	.mb-1{
		margin-bottom: .5rem !important;
	}
	.w-100{
		width: 100% !important;
	}
	.mt-3{
		margin-top: 1rem !important;
	}
	.mr-1{
		margin-right: .5rem !important;
	}
	.ml-1{
		margin-left: .5rem !important;
	}
	.ml-2{
		margin-left: 1rem !important;
	}
	/*.form-row{
		margin-top: 1rem !important;
	}*/
	.border-danger {
	    border-color: #dc3545 !important;
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
	    background-color: rgba(0,0,0,.03);
	    border-bottom: 1px solid rgba(0,0,0,.125);
	}
	.text-danger {
	    color: #dc3545 !important;
	}
	.card-body {
	    -ms-flex: 1 1 auto;
	    flex: 1 1 auto;
	    padding: 1.25rem;
	}
	.card-title {
	    margin-bottom: .75rem;
	}
	.card-text:last-child {
	    margin-bottom: 0;
	}
	.border-warning {
	    border-color: #ffc107 !important;
	}
	.text-warning {
	    color: #ffc107 !important;
	}
	.border-success {
	    border-color: #28a745 !important;
	}
	.text-success {
	    color: #28a745 !important;
	}
	fieldset {
  		background-color: #eeeeee;
	}
	legend {
  		background-color: gray;
  		color: white;
  		padding: 5px 10px;
	}
	.btn_accion{
		border-radius: 50%;
		position: absolute;
		right: 0;
		top: 2px;
		opacity: .8;
	}
 </style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h1><i class="glyphicon glyphicon-stats"></i> Familiares</h1>
			<div class="clearfix"></div>
			<div class="box">
		  		<div class="box-header">
		  			<h4>Familiares</h4>
		  		</div>
		  		<div class="box-body">
		  			<h4 id="nombre_colaborador"></h4>
		  			<!--<select id="id_colaborador" class="form-control js-example-basic-single">
		  				<option value="0">Seleccionar...</option>
		  			</select>-->
					
		  			<div class="row" hidden id="div_formulario" style="margin-top: 15px; background: wheat; border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
		  				<h4>Nuevo Familiar</h4>
		  				<div class="col-md-6">
		  					<label>Dni</label>
		  					<input type="text" id="dni" name="dni" class="form-control">
		  				</div>
		  				<div class="col-md-6">
		  					<label>Nombres</label>
		  					<input type="text" id="nombre" name="nombre" class="form-control">
		  				</div>
		  				<div class="col-md-6">
		  					<label>Apellidos</label>
		  					<input type="text" id="apellidos" name="apellidos" class="form-control">
		  				</div>
		  				<div class="col-md-6">
		  					<label>Fecha de Nacimiento</label>
		  					<input type="text" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control">
		  				</div>
		  				<div class="col-md-4">
		  					<label>Lugar de Nacimiento</label>
		  					<input type="text" id="lugar_nacimiento" name="lugar_nacimiento" class="form-control">
		  				</div>
		  				<div class="col-md-4">
		  					<label>Teléfono</label>
		  					<input type="text" id="telefono" name="telefono" class="form-control">
		  				</div>
		  				<div class="col-md-4">
		  					<label>Parentesco</label>
		  					<input type="text" id="parentesco" name="parentesco" class="form-control">
		  				</div>
		  				<div class="col-md-12" style="text-align: center; margin-top: 15px;">
		  					<a class="btn btn-info" href="http://192.99.55.83/sistema/core/app/view/pdf-utiles.php?parAccion=familiares&id_col=<?php echo $_GET['id_colaborador']; ?>">Imprimir</a>
		  					<button class="btn btn-success" id="btn_rehusar" onclick="guardar();">Guardar</button>
		  					<button class="btn btn-danger" id="btn_cancelar" hidden onclick="cancelar();">Cancelar</button>
		  					<a class="btn btn-primary" href="http://192.99.55.83/sistema/?view=colaborador2&id_col=<?php echo $_GET['id_colaborador']; ?>">Volver</a>
		  				</div>
		  			</div>
		  			<h5 style="font-weight: bold;">Familiares del Colaborador</h5>
		  			<div class="form-row" id="div_familiares" style="margin-top: 15px;">
		  				<h3>Resultados de la búsqueda</h3>
		  			</div>
		  		</div>
			</div>	
		</div>
	</div>
</section>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script type="text/javascript">
	var id_colaborador = <?php echo isset($_GET['id_colaborador'])?$_GET['id_colaborador']:0; ?>;
	$(document).ready(function() {
		get_all_colaboradores();
		$(".js-example-basic-single").select2();

		<?php
			/*if(isset($_GET['id_colaborador'])){
				echo '$("#id_colaborador").val('.$_GET['id_colaborador'].');
                    $("#id_colaborador").select2().trigger(\'change\');';
			}else{

			}*/
		?>

		if(id_colaborador != 0){
			/*$("#id_colaborador").val(id_colaborador);
            $("#id_colaborador").select2().trigger('change');*/
            get_familiares(id_colaborador);
		}else{

		}

		$("#id_colaborador").on("change", function(){
			get_familiares($("#id_colaborador").val());
		});
		get_colaborador();
	});
	function get_colaborador() {
		$.post('core/app/view/colaborador.php?parAccion=editar', {
			id: id_colaborador
		}, function(data) {
			var obj = JSON.parse(data);
			$("#nombre_colaborador").text("Colaborador: " + obj.nombres + " " + obj.apellido_paterno + " " + obj.apellido_materno);
		});
	}
	function get_all_colaboradores(){
		$("#tabla_colaboradores").find('tbody').empty();
		$.post('core/app/view/colaborador.php?parAccion=get_all_colaboradores', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_colaborador").append(`<option value="`+val.id+`">`+val.nombres +" " + val.apellido_paterno +" " + val.apellido_materno +`</option>`)
			});
		});
	}
	function cancelar(){
		limpiar_formulario();
		$("#btn_rehusar").attr('onclick', 'guardar();');
		$("#btn_cancelar").attr('hidden', true);
	}
	function get_familiares(id) {
		$("#div_familiares").empty();
		$("#div_formulario").removeAttr("hidden");

		$.post('core/app/view/colaborador.php?parAccion=get_familiares', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#div_familiares").append(`
					<div class="card col-md-12" style="padding: 0px !important; margin-bottom: 5px;">
  						<div class="card-header"  style="font-weight: bold;">
    						`+val.nombre+` `+val.apellidos+`
    						<span class="btn btn-warning btn-sm btn_accion" style="right: 40px;" onclick="editar(`+val.id+`);"><i class="glyphicon glyphicon-pencil"></i></span>
    						<span class="btn btn-danger btn-sm btn_accion" onclick="eliminar(`+val.id+`);"><i class="fa fa-trash"></i></span>
  						</div>
						<div class="card-body">
    						<h5 style="font-weight: bold;" class="card-title">`+val.parentesco+`</h5>
    						<p class="card-text">`+val.fecha_nacimiento+` - ` + val.lugar_nacimiento + `</p>
    						<p style="font-weight: bold; margin-bottom: 0px;"><strong>Teléfono: </strong>`+val.telefono+`</p>
    						<p><strong>DNI: </strong>`+val.dni+`</p>
  						</div>
					</div>
				`);
			});
		});
	}
	function editar(id){
		$.post('core/app/view/colaborador.php?parAccion=editar_familiar', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$("#dni").val(obj.dni);
			$("#nombre").val(obj.nombre);
			$("#apellidos").val(obj.apellidos);
			$("#fecha_nacimiento").val(obj.fecha_nacimiento);
			$("#lugar_nacimiento").val(obj.lugar_nacimiento);
			$("#telefono").val(obj.telefono);
			$("#parentesco").val(obj.parentesco);

			$("#dni").focus();
			
			//$("#id_colaborador").select2("val", obj.id_colaborador);

			$("#btn_rehusar").attr('onclick', 'actualizar('+obj.id+');');
			$("#btn_cancelar").removeAttr('hidden');
		});
	}
	function goBack() {
 		window.history.back();
	}
	function actualizar(id){
		$.post('core/app/view/colaborador.php?parAccion=actualizar_familiar', {
			id_colaborador: id_colaborador,
			dni: $("#dni").val(),
			nombre: $("#nombre").val(),
			apellidos: $("#apellidos").val(),
			fecha_nacimiento: $("#fecha_nacimiento").val(),
			lugar_nacimiento: $("#lugar_nacimiento").val(),
			telefono: $("#telefono").val(),
			parentesco: $("#parentesco").val(),
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if(obj.Result == "OK"){
				if(obj.Result == "OK"){
					bootbox.alert({
	                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">'+
	                            '<strong>Actualizado correctamente.</strong>'+
	                        '</div>'
	                });
	                get_familiares(id_colaborador);
					limpiar_formulario();
					cancelar();
				}else{
					bootbox.alert({
	                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
	                            '<strong>Algo ha salido mal.</strong>'+
	                        '</div>'
	                });
				}
			}
		});
	}
	function eliminar(id){
		$.post('core/app/view/colaborador.php?parAccion=eliminar_familiar', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if(obj.Result == "OK"){
				bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">'+
                            '<strong>Eliminado correctamente.</strong>'+
                        '</div>'
                });
                location.reload();
			}else{
				bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
                            '<strong>Algo ha salido mal.</strong>'+
                        '</div>'
                });
			}
		});
	}
	function filtrar(){
		$.post('', {
			termino: $("#filtro_busqueda").val()
		}, function(data) {
			var obj = JSON.parse(data);
			if(obj.length > 0){
			}else{
				bootbox.alert({
			        message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
			            '<strong>Sin coincidencias.</strong>'+
			        '</div>'
			    });
			}
		});
	}
	function limpiar_formulario(){
		$("#dni").val("");
		$("#nombre").val("");
		$("#apellidos").val("");
		$("#fecha_nacimiento").val("");
		$("#lugar_nacimiento").val("");
		$("#telefono").val("");
		$("#parentesco").val("");
	}
	function guardar(){
		$.post('core/app/view/colaborador.php?parAccion=guardar_familiar', {
			id_colaborador: id_colaborador,
			dni: $("#dni").val(),
			nombre: $("#nombre").val(),
			apellidos: $("#apellidos").val(),
			fecha_nacimiento: $("#fecha_nacimiento").val(),
			lugar_nacimiento: $("#lugar_nacimiento").val(),
			telefono: $("#telefono").val(),
			parentesco: $("#parentesco").val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if(obj.Result == "OK"){
				bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">'+
                            '<strong>Guardado correctamente.</strong>'+
                        '</div>'
                });
                get_familiares(id_colaborador);
				limpiar_formulario();
			}else{
				bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
                            '<strong>Algo ha salido mal.</strong>'+
                        '</div>'
                });
			}
		});
	}
</script>