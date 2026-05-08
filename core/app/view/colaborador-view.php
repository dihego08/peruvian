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
 </style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h1><i class="glyphicon glyphicon-stats"></i> Lista de Colaboradores</h1>
			<div class="clearfix"></div>
			<div class="box">
		  		<div class="box-header">
		  			<h4>Colaboradores</h4>
		  			<span class="btn btn-primary pull-right" onclick="abrir_formulario();" data-toggle="modal" data-target="#formulario">
		  				<i class="glyphicon glyphicon-plus"></i>
		  			</span>
		  		</div>
		  		<div class="box-body">
		  			<table class="table table-bordered table-hover" id="tabla_colaboradores">
						<thead>
							<tr>
								<th>ID</th>
								<th>Foto</th>
								<th>DNI</th>
								<th>Nombres</th>
								<th>Apellidos</th>
								<th>Celular</th>
								<th>Correo</th>
								<th>Dirección</th>
								<th>Ent. Pensión</th>
								<th>Asegurado</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
		  		</div>
			</div>	
		</div>
	</div>
	<!----------------------------------------------------------------------->
    <div class="modal fade" id="formulario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"></h3>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="width: 100%;">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label>DNI</label>
                                <input id="dni" class="form-control" name="dni" type="text"/>
                            </div>
                            <div class="col-md-8">
                                <label>Nombres</label>
                                <input id="nombres" class="form-control" name="nombres" type="text"/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-8">
                                <label>Apellidos</label>
                                <input id="apellidos" class="form-control" name="apellidos" type="text"/>
                            </div>
                            <div class="col-md-4">
                                <label>Fec. Nacimiento</label>
                                <input id="fecha_nacimiento" class="form-control datepicker" name="fecha_nacimiento" type="text"/>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="col-md-6">
                                <label>Lugar de Nacimiento</label>
                                <input id="lugar_nacimiento" class="form-control" name="lugar_nacimiento" type="text"/>
                            </div>
                            <div class="col-md-6">
                                <label>Estado Civil</label>
                                <select name="id_estado_civil" id="id_estado_civil" class="form-control">
                                	
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4">
                                <label>Celular</label>
                                <input id="celular" class="form-control" name="celular" type="text"/>
                            </div>
                            <div class="col-md-4">
                                <label>Dirección</label>
                                <input id="direccion" class="form-control" name="direccion" type="text"/>
                            </div>
                            <div class="col-md-4">
                                <label>Correo</label>
                                <input id="correo" class="form-control" name="correo" type="text"/>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="col-md-6">
                                <label>Brevette</label>
                                <input type="text" class="form-control" id="brevette" name="brevette">
                            </div>
                            <div class="col-md-6">
                                <label>Teléfono de Emergencia</label>
                                <input type="text" class="form-control" id="telefono_emergencia" name="telefono_emergencia">
                            </div>
                        </div>

						<div class="form-row">
                            <div class="col-md-6">
                                <label>Sistema de Pensiones</label>
                                <select name="sistema_pension" id="sistema_pension" class="form-control">
                                	<option value="0">SELECCIONA...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Entidad de Pensiones</label>
                                <select name="id_entidad_pension" id="id_entidad_pension" class="form-control">
                                	<option value="0">SELECCIONA...</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-1">
                            	<label>Código</label>
                            	<input type="text" id="codigo" name="codigo" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mt-1">
                            	<label>Asegurado</label><br>
                            	<input type="checkbox" id="asegurado" name="asegurado">
                            </div>
                        </div>

						<div class="col-md-12">
                          	<label>Foto</label>
                          	<input type="file" name="file1" id="file1" class="form-control">
                          	<div class="w-100" style="padding: 10px; text-align: center;">
                          		<img src="" width="100" height="100" id="imagen_usuario" style="border-radius: 4px; box-shadow: 0px 2px 2px #333;">
                          	</div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <progress id="progressBar" class="mt-2" value="0" max="100" style="width:100%;"></progress>
                            <p id="status"></p>
                            <p id="loaded_n_total"></p>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                	<button type="submit" class="btn btn-success pull-right ml-2" id="btn_finalizar">Guardar</button>
                    <span class="btn btn-danger" type="button" data-dismiss="modal" id="cerrar_formulario_docente">
                        Cancelar
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!----------------------------------------------------------------------->
</section>
<script type="text/javascript">
	$(document).ready(function() {
		get_all_colaboradores();
		llenar_estado_civil();
		llenar_sistema_pension();

		$("#sistema_pension").on("change", function(){
			llenar_entidades_pension($("#sistema_pension").val(), 0);
		});

		function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#imagen_usuario").attr("src", e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#file1").change(function(){
            readURL(this);
        });
	});
	function get_all_colaboradores(){
		$("#tabla_colaboradores").find('tbody').empty();
		$.post('core/app/view/colaborador.php?parAccion=get_all_colaboradores', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				var asegurado = "";
				if(val.asegurado == 0){
					asegurado = `<i class="glyphicon glyphicon-remove"></i>`;
				}else{
					asegurado = `<i class="glyphicon glyphicon-ok"></i>`;
				}
				$("#tabla_colaboradores").find('tbody').append(`
					<tr>
						<td>`+val.id+`</td>
						<td>
							<img style="border-radius: 4px; box-shadow: 0px 2px 2px #333;" src="core/app/view/img-colaboradores/`+val.foto+`" width="50" height="50" alt="" />
						</td>
						<td>`+val.dni+`</td>
						<td>`+val.nombres+`</td>
						<td>`+val.apellidos+`</td>
						<td>`+val.celular+`</td>
						<td>`+val.correo+`</td>
						<td>`+val.direccion+`</td>
						<td>`+val.afp+`</td>
						<td style="text-align: center;">`+asegurado+`</td>
						<td>
							<span role="button" data-toggle="modal" data-target="#formulario" class="w-100 mb-1 btn btn-sm btn-warning" onclick="editar(`+val.id+`);">
								<i class="glyphicon glyphicon-pencil"></i>
							</span>
							<span role="button" class="w-100 btn btn-sm btn-danger" onclick="eliminar(`+val.id+`);">
								<i class="fa fa-trash"></i>
							</span>
						</td>
					</tr>
				`);
			});
		});
	}
	function abrir_formulario() {
		limpiar_formulario();
		$("#exampleModalLabel").text("Nuevo Colaborador");
		$("#btn_finalizar").attr("onclick", "guardar();");
	}
	function limpiar_formulario(){
		$("#dni").val("");
		$("#nombres").val("");
		$("#apellidos").val("");
		$("#fecha_nacimiento").val("");
		$("#lugar_nacimiento").val("");
		$("#id_estado_civil").val("");
		$("#celular").val("");
		$("#direccion").val("");
		$("#correo").val("");
		$("#brevette").val("");
		$("#telefono_emergencia").val("");
		$("#id_entidad_pension").val("");
		$("#codigo").val("");
		
		$("#asegurado").attr('checked', false);
	}
	function editar(id){
		$.post('core/app/view/colaborador.php?parAccion=editar', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$("#exampleModalLabel").text("Editar Colaborador");

			$("#dni").val(obj.dni);
			$("#nombres").val(obj.nombres);
			$("#apellidos").val(obj.apellidos);
			$("#fecha_nacimiento").val(obj.fecha_nacimiento);
			$("#lugar_nacimiento").val(obj.lugar_nacimiento);
			$("#id_estado_civil").val(obj.id_estado_civil);
			$("#celular").val(obj.celular);
			$("#direccion").val(obj.direccion);
			$("#correo").val(obj.correo);
			$("#brevette").val(obj.brevette);
			$("#telefono_emergencia").val(obj.telefono_emergencia);
			
			$("#sistema_pension option[value="+obj.id_sistema_pension+"]").attr('selected','selected');
			
			$("#sistema_pension").change(llenar_entidades_pension(obj.id_sistema_pension, obj.id_entidad_pension));

			//$("#id_entidad_pension option[value="+obj.id_entidad_pension+"]").attr('selected','selected');
			$("#codigo").val(obj.codigo);
			
			if(obj.asegurado == 1){
				$("#asegurado").attr('checked', true);
			}else{
				$("#asegurado").attr('checked', false);
			}

			$("#imagen_usuario").attr("src", "core/app/view/img-colaboradores/"+obj.foto);

			$("#btn_finalizar").attr("onclick", "actualizar("+id+");");
		});
	}
	function llenar_estado_civil(){
		$.post('core/app/view/colaborador.php?parAccion=llenar_estado_civil', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_estado_civil").append(`
					<option value="`+val.id+`">`+val.estado_civil+`</option>
				`);
			});
		});
	}
	function llenar_sistema_pension(){
		$.post('core/app/view/colaborador.php?parAccion=llenar_sistema_pension', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#sistema_pension").append(`
					<option value="`+val.id+`">`+val.sistema_pension+`</option>
				`);
			});
		});
	}
	function llenar_entidades_pension(id, id_) {
		$("#id_entidad_pension").empty();
		$.post('core/app/view/colaborador.php?parAccion=llenar_entidades_pension', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				if(id_ == val.id){
					$("#id_entidad_pension").append(`
						<option value="`+val.id+`" selected>`+val.afp+`</option>
					`);
				}else{
					$("#id_entidad_pension").append(`
						<option value="`+val.id+`">`+val.afp+`</option>
					`);
				}
			});
		});
	}
	function actualizar(id){
		var file = _("file1").files[0];
        var formdata = new FormData();
        formdata.append("file1", file);
        
        formdata.append("dni", $("#dni").val());
		formdata.append("nombres", $("#nombres").val());
		formdata.append("apellidos", $("#apellidos").val());
		formdata.append("fecha_nacimiento", $("#fecha_nacimiento").val());
		formdata.append("lugar_nacimiento", $("#lugar_nacimiento").val());
		formdata.append("id_estado_civil", $("#id_estado_civil").val());
		formdata.append("celular", $("#celular").val());
		formdata.append("direccion", $("#direccion").val());
		formdata.append("correo", $("#correo").val());
		formdata.append("brevette", $("#brevette").val());
		formdata.append("telefono_emergencia", $("#telefono_emergencia").val());
		formdata.append("id_entidad_pension", $("#id_entidad_pension").val());
		formdata.append("codigo", $("#codigo").val());
		formdata.append("asegurado", $("#asegurado").val());
		formdata.append("id_sistema_pension", $("#sistema_pension").val());
		formdata.append("id", id);

        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.addEventListener("abort", abortHandler, false);
        ajax.open("POST", "core/app/view/colaborador.php?parAccion=actualizar");
        ajax.send(formdata);
	}
	function eliminar(id){
		$.post('core/app/view/colaborador.php?parAccion=eliminar', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if(obj.Result == "OK"){
			}else{
			}
		});
	}
	function guardar(){
		var file = _("file1").files[0];
        var formdata = new FormData();
        formdata.append("file1", file);
        
        formdata.append("dni", $("#dni").val());
		formdata.append("nombres", $("#nombres").val());
		formdata.append("apellidos", $("#apellidos").val());
		formdata.append("fecha_nacimiento", $("#fecha_nacimiento").val());
		formdata.append("lugar_nacimiento", $("#lugar_nacimiento").val());
		formdata.append("id_estado_civil", $("#id_estado_civil").val());
		formdata.append("celular", $("#celular").val());
		formdata.append("direccion", $("#direccion").val());
		formdata.append("correo", $("#correo").val());
		formdata.append("brevette", $("#brevette").val());
		formdata.append("telefono_emergencia", $("#telefono_emergencia").val());
		formdata.append("id_entidad_pension", $("#id_entidad_pension").val());
		formdata.append("id_sistema_pension", $("#sistema_pension").val());
		formdata.append("codigo", $("#codigo").val());
		formdata.append("asegurado", $("#asegurado").val());

        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.addEventListener("abort", abortHandler, false);
        ajax.open("POST", "core/app/view/colaborador.php?parAccion=guardar");
        ajax.send(formdata);
	}

	function _(el){
        return document.getElementById(el);
    }
    function uploadFile(){
        var file = _("file1").files[0];
        var formdata = new FormData();
        formdata.append("file1", file);
        formdata.append("id_curso", $("#id_curso").val());
        formdata.append("id_tema", $("#id_tema").val());
        formdata.append("tarea", $("#tarea").val());
        formdata.append("fecha_entrega", $("#fecha_entrega").val());
        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.addEventListener("abort", abortHandler, false);
        ajax.open("POST", "../php/tarea.php?parAccion=guardar_tarea");
        ajax.send(formdata);
    }
    function progressHandler(event){
        _("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
        var percent = (event.loaded / event.total) * 100;
        _("progressBar").value = Math.round(percent);
    }
    function completeHandler(event){
        table = $(".datatable").DataTable();
        table.ajax.reload();
        limpiar_formulario();
        _("progressBar").value = 0;
        $("#cerrar_formulario_docente").click();
        get_all_colaboradores();
    }
    function errorHandler(event){
        _("status").innerHTML = "Upload Failed";
    }
    function abortHandler(event){
        _("status").innerHTML = "Upload Aborted";
    }
</script>