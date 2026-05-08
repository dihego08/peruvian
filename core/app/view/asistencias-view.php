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
		.btn_accion{
			border-radius: 50%;
			opacity: .8;
		}
	</style>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<h1><i class="glyphicon glyphicon-stats"></i> Asistencias Realizadas</h1>
				<div class="clearfix"></div>
				<div class="box">
			  		<div class="box-header">
			  		</div>
			  		<div class="box-body row">
			  		    
			  			<div class="col-md-4" id="div_formulario" style="margin-top: 15px; background: wheat; border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
			  			    <h4>Adjuntar Asistencia</h4>
			  				<div class="col-md-12">
			  					<label>Año</label>
			  					<select class="form-control" id="anio">
			  						<option value="2017">2017</option>
			  						<option value="2018">2018</option>
			  						<option value="2019">2019</option>
			  						<option value="2020" selected="selected">2020</option>
			  						<option value="2021">2021</option>
			  						<option value="2022">2022</option>
			  						<option value="2023">2023</option>
			  					</select>
			  				</div>
			  				<div class="col-md-12">
			  					<label>Curso</label>
			  					<select class="form-control" id="id_curso"></select>
			  				</div>
			  				<div class="col-md-12">
			  					<label>Fecha</label>
			  					<input type="text" class="form-control datepicker"  name="" id="fecha">
			  				</div>

			  				<div class="col-md-12">
			  					<label>Horas de Capacitación</label>
			  					<input type="text" class="form-control"  name="" id="horas_capacitacion">
			  				</div>
			  				<div class="col-md-12">
			  					<label>Capacitador</label>
			  					<input type="text" class="form-control"  name="" id="capacitador">
			  				</div>

			  				<div class="form-row mt-2">
                                <div class="col-md-12">
                                    <label>Archivo</label>
                                    <input type="file" name="file1" id="file1" class="form-control">
                                    <small id="archivo_file"></small>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-12">
                                    <label>Lista de Asistentes</label>
                                    <input type="file" name="file1_2" id="file1_2" class="form-control">
                                    <small id="asistentes_file"></small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <progress id="progressBar" class="mt-2" value="0" max="100" style="width:100%;"></progress>
                                <p id="status"></p>
                                <p id="loaded_n_total"></p>
                            </div>
			  				<div class="col-md-12" style="text-align: center; margin-top: 15px;">
			  					<button class="btn btn-success" id="btn_rehusar" >Guardar</button>
			  					<button class="btn btn-danger" id="btn_cancelar" onclick="limpiar_formulario();">Cancelar</button>
			  				</div>
			  			</div>
			  			<div class="col-md-8">
			  			    <h4>Registro de Asistencias</h4>
			  			    <table class="table table-hover table-bordered table-striped" id="tabla_asistencias" style="font-size: 12px;">
    			  				<thead style="font-weight: bold; ">
    			  					<th>Fecha</th>
    			  					<th>Curso</th>
    			  					<th>Horas de Capacitación</th>
    			  					<th>Capacitador</th>
    			  					<th>Plan</th>
    			  					<th>Asistencias</th>
    			  					<th></th>
    			  				</thead>
    			  				<tbody></tbody>
    			  			</table>
			  			</div>
			  		</div>
				</div>
			</div>
		</div>
	</section>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.full.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet"/>
	<script>
        function _(el){
            return document.getElementById(el);
        }
        function uploadFile(){
            var file = _("file1").files[0];
            var file1_2 = _("file1_2").files[0];
            var formdata = new FormData();
            
            formdata.append("file1", file);
            formdata.append("file1_2", file1_2);
            formdata.append("id_curso", $("#id_curso").val());
            formdata.append("fecha", $("#fecha").val());
            formdata.append("horas_capacitacion", $("#horas_capacitacion").val());
			formdata.append("capacitador", $("#capacitador").val());

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "core/app/view/colaborador.php?parAccion=guardar_asistencia");
            ajax.send(formdata);
        }
        function update_data(id_asistencia){
            var file = _("file1").files[0];
            var file1_2 = _("file1_2").files[0];
            var formdata = new FormData();
            
            formdata.append("file1", file);
            formdata.append("file1_2", file1_2);
            formdata.append("id_curso", $("#id_curso").val());
            formdata.append("fecha", $("#fecha").val());
            formdata.append("horas_capacitacion", $("#horas_capacitacion").val());
			formdata.append("capacitador", $("#capacitador").val());
			
			formdata.append("id", id_asistencia);

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "core/app/view/colaborador.php?parAccion=actualizar_asistencia");
            ajax.send(formdata);
        }
        function progressHandler(event){
            _("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
            var percent = (event.loaded / event.total) * 100;
            _("progressBar").value = Math.round(percent);
        }
        function completeHandler(event){
        	get_asistencias();
        	limpiar_formulario();
            _("progressBar").value = 0;
            $("#cerrar_formulario").click();
            $("#cerrar_mas_imagenes").click();
        }
        function errorHandler(event){
            _("status").innerHTML = "Upload Failed";
        }
        function abortHandler(event){
            _("status").innerHTML = "Upload Aborted";
        }
    </script>
	<script type="text/javascript">
		var anio = <?php echo date('Y'); ?>;
		function get_asistencias(){
			$("#tabla_asistencias").find('tbody').empty();
			$.post('core/app/view/colaborador.php?parAccion=get_asistencias', function(data) {
				
				var obj = JSON.parse(data);
				$.each(obj, function(index, val) {
				    var foto = "";
				    var asistentes = "";
				    
				    if(val.foto == "" || val.foto == null){
				    }else{
				        foto = `<a href="core/app/view/asistencias/${val.foto}" target="_blank"><i class="glyphicon glyphicon-file"></i> ${val.foto}</a>`;
				    }
				    
				    if(val.asistentes == "" || val.asistentes == null){
				    }else{
				        asistentes = `<a href="core/app/view/asistencias/${val.asistentes}" target="_blank"><i class="glyphicon glyphicon-file"></i> ${val.asistentes}</a>`;
				    }
					$("#tabla_asistencias").find('tbody').append(`
						<tr>
							<td>${val.fecha_registro}</td>
							<td>${val.curso}</td>
							<td>${val.horas_capacitacion}</td>
							<td>${val.capacitador}</td>
							<td>${foto}</td>
							<td>${asistentes}</td>
							<td class="text-center">
								<span class="btn_accion" onclick="editar(${val.id});"><i class="fa fa-pencil" style="cursor: pointer; color: #dd4b39; font-size: 14px;"></i></span>
								<span class="btn_accion" onclick="eliminar(${val.id});"><i class="fa fa-trash" style="cursor: pointer; color: #d73925; font-size: 14px;"></i></span>
							</td>
						</tr>
					`);
				});
			});
		}
		function eliminar(id){
		    bootbox.confirm({
                message: "¿Esta seguro de querer eliminar este registro?",
                buttons: {
                    confirm: {
                        label: 'Si',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result){
                        $.post('core/app/view/colaborador.php?parAccion=eliminar_asistencia', {
                			id: id
                		}, function(data) {
                			var obj = JSON.parse(data);
                			if(obj.Result == "OK"){
                				bootbox.alert({
                                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">'+
                                            '<strong>Eliminado correctamente.</strong>'+
                                        '</div>'
                                });
                                get_asistencias();
                			}else{
                				bootbox.alert({
                                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
                                            '<strong>Algo ha salido mal.</strong>'+
                                        '</div>'
                                });
                			}
                		});
                    }else{
                        
                    }
                }
            });
    	}
    	function editar(id_asistencia){
    	    $.post('core/app/view/colaborador.php?parAccion=editar_asistencia', {
    	        id: id_asistencia
    	    }, function(response){
    	        var obj = JSON.parse(response);
    	        
    	        $("#fecha").val(obj.fecha_registro);
                $("#horas_capacitacion").val(obj.horas_capacitacion);
                $("#capacitador").val(obj.capacitador);
                
                $("#archivo_file").html(`<a href="core/app/view/asistencias/${obj.foto}">${obj.foto}</a>`);
                $("#asistentes_file").html(`<a href="core/app/view/asistencias/${obj.asistentes}">${obj.asistentes}</a>`);
                
                $("#fecha").focus();
                
                $("#btn_rehusar").attr("onclick", "update_data("+id_asistencia+");");
                $("#btn_rehusar").text("Actualizar");
    	    });
    	}
		function get_cursos(anio){
			$("#id_curso").empty();
			$.post('core/app/view/colaborador.php?parAccion=get_cronograma', {
				anuo: anio
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj, function(index, val) {
					$("#id_curso").append(`<option value="${val.id}">${val.curso}</option>`);
				});
			});
		}
		
    	function limpiar_formulario(){
            $("#fecha").val("");
            $("#horas_capacitacion").val("");
            $("#capacitador").val("");
            $("#file1").val("");
            $("#file1_2").val("");
            
            $("#archivo_file").html(``);
            $("#asistentes_file").html(``);
            
            $("#btn_rehusar").attr("onclick", "uploadFile();");
            $("#btn_rehusar").text("Guardar");
    	}
		$(document).ready(function() {
			$(".datepicker").datetimepicker({
                format: "d-m-Y",
                timepicker:false
            });
            $.datetimepicker.setLocale('es');
			    get_asistencias();
			    get_cursos(anio);
			    $("#anio").on("change", function(){
				get_cursos($("#anio").val());
			});
			
			limpiar_formulario();
		});
		
	</script>