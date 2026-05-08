<style type="text/css">
	.ct-label {
		font-size: 15px;
		color: black;
	}

	.clsDatePicker {
		position: absolute;
		cursor: default;
		z-index: 1001 !important
	}

	.ui-datepicker-month {
		color: #313131;
	}

	.ui-datepicker-year {
		color: #313131;
	}

	.mt-2 {
		margin-top: 1rem !important;
	}

	.mt-3 {
		margin-top: 1.5rem !important;
	}

	.mb-3 {
		margin-bottom: 1rem !important;
	}

	.mb-1 {
		margin-bottom: .5rem !important;
	}

	.w-100 {
		width: 100% !important;
	}

	.mt-3 {
		margin-top: 1rem !important;
	}

	.mr-1 {
		margin-right: .5rem !important;
	}

	.ml-1 {
		margin-left: .5rem !important;
	}

	.ml-2 {
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
		border: 1px solid rgba(0, 0, 0, .125);
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
		background-color: rgba(0, 0, 0, .03);
		border-bottom: 1px solid rgba(0, 0, 0, .125);
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

	.btn_accion {
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
			<h1><i class="glyphicon glyphicon-stats"></i> RECORD VACACIONAL</h1>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">
					<h4>Vacaciones</h4>
				</div>
				<div class="box-body">
					<h4 id="nombre_colaborador"></h4>
					<div class="row" hidden id="div_formulario" style="margin-top: 15px; background: wheat; border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
						<div class="col-md-6">
							<label>Periodo Vacacional</label>
							<input type="text" id="periodo" name="periodo" class="form-control">
						</div>
						<div class="col-md-6">
							<label>Fecha de Salida</label>
							<input type="date" class="form-control datepicker" id="fecha_salida">
						</div>
						<div class="col-md-6">
							<label>Fecha de Retorno</label>
							<input type="date" class="form-control datepicker" id="fecha_retorno">
						</div>
						<div class="col-md-6">
							<label>Días</label>
							<input type="text" class="form-control" id="dias">
						</div>
						<div class="col-md-12">
							<label>Observaciones</label>
							<input type="text" class="form-control" id="observaciones">
						</div>
						<div class="col-md-12" style="text-align: center; margin-top: 15px;">
							<a class="btn btn-info" href="http://192.99.55.83/sistema/core/app/view/pdf-utiles.php?parAccion=vacaciones&id_col=<?php echo $_GET['id_colaborador']; ?>">Imprimir</a>
							<button class="btn btn-success" id="btn_rehusar" onclick="guardar();">Guardar</button>
							<button class="btn btn-danger" id="btn_cancelar" hidden onclick="cancelar();">Cancelar</button>
							<a class="btn btn-primary" href="http://192.99.55.83/sistema/?view=colaborador2&id_col=<?php echo $_GET['id_colaborador']; ?>">Volver</a>
						</div>
					</div>
					<h5 style="font-weight: bold;">Vacaciones del Colaborador</h5>
					<div class="form-row" id="div_experiencia" style="margin-top: 15px;">
						<table class="table" id="tabla_vacaciones">
							<thead>
								<th>Periodo Vacacional</th>
								<th>Fecha de Salida</th>
								<th>Fecha de Retorno</th>
								<th>Dias</th>
								<th>Observaciones</th>
								<th>Archivo</th>
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
<div class="modal" tabindex="-1" role="dialog" id="exampleModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cargar Archivo
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label for="file1" class="btn bg-maroon btn-rounded"><i class="glyphicon glyphicon-camera"></i> Seleccionar Archivo</label>
						<input type="file" name="file1" id="file1" style="display: none;">
					</div>
					<div class="col-md-12" style='margin-top: 1rem !important;'>
						<span id='fileList'></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="btn-subir-archivo">Subir Archivo</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.full.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet" />
<script type="text/javascript">
	var id_colaborador = <?php echo isset($_GET['id_colaborador']) ? $_GET['id_colaborador'] : 0; ?>;
	$(document).ready(function() {
		// $(".datepicker").datetimepicker({
		//     format: "d/m / Y",
		//     timepicker:false
		// });
		$.datetimepicker.setLocale('es');
		get_all_colaboradores();
		$(".js-example-basic-single").select2();

		if (id_colaborador != 0) {
			get_vacaciones(id_colaborador);
		} else {}

		$("#id_colaborador").on("change", function() {
			get_vacaciones($("#id_colaborador").val());
		});
		get_colaborador();


		$("#fecha_retorno").on("change", function() {
			var day1 = new Date($("#fecha_salida").val());
			var day2 = new Date($("#fecha_retorno").val());

			var difference = (day2.getTime() - day1.getTime()) / (1000 * 3600 * 24);

			$("#dias").val(parseInt(difference) + parseInt(1));
		});

		var fileList = document.getElementById("file1");
		fileList.addEventListener("change", function(e) {
			var list = "";
			for (var i = 0; i < this.files.length; i++) {
				list += "<div class='col-md-12'><span class='badge badge-info' style='font-size: 13px;'>" + this.files[i].name + "</span></div>"
			}

			$("#fileList").append(list);
		}, false);
	});

	function get_colaborador() {
		$.post('core/app/view/colaborador.php?parAccion=editar', {
			id: id_colaborador
		}, function(data) {
			var obj = JSON.parse(data);
			$("#nombre_colaborador").text("Colaborador: " + obj.nombres + " " + obj.apellido_paterno + " " + obj.apellido_materno);
		});
	}

	function get_all_colaboradores() {
		$("#tabla_colaboradores").find('tbody').empty();
		$.post('core/app/view/colaborador.php?parAccion=get_all_colaboradores', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_colaborador").append(`<option value="` + val.id + `">` + val.nombres + " " + val.apellido_paterno + " " + val.apellido_materno + `</option>`);
			});
		});
	}

	function goBack() {
		window.history.back();
	}

	function get_vacaciones(id) {
		$("#tabla_vacaciones").find("tbody").empty();
		$("#div_formulario").removeAttr("hidden");

		$.post('core/app/view/colaborador.php?parAccion=get_vacaciones', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {

				$("#tabla_vacaciones").find("tbody").append(`
					<tr>
						<td>${val.periodo}</td>
						<td>${val.fecha_salida}</td>
						<td>${val.fecha_retorno}</td>
						<td>${val.dias}</td>
						<td>${val.observaciones}</td>
						<td>
							<a class="" href="core/app/view/vacaciones/${val.archivo}" target="_blank">${$.trim(val.archivo)}</a>
						</td>
						<td>
						<span class="btn btn-info btn-sm" style="right: 83px;" onclick="preparar_subir_archivo(${val.id});" data-toggle="modal" data-target="#exampleModal"><i class="glyphicon glyphicon-open-file"></i></span>	
						<span class="btn btn-warning btn-sm" onclick="editar(${val.id});"><i class="glyphicon glyphicon-pencil"></i></span>
    						<span class="btn btn-danger btn-sm" onclick="eliminar(${val.id});"><i class="fa fa-trash"></i></span>
						</td>
					</tr>
				`);
			});
		});
	}

	function preparar_subir_archivo(id) {
		$("#btn-subir-archivo").attr("onclick", "cargar_archivo(" + id + ");");
		$("#file1").val('');
		$("#fileList").empty();
	}

	function cargar_archivo(id) {
		var formData = new FormData();
		var aux = 0;
		var archivo = $('input[name="file1"]')[0].files;
		if ($('input[name="file1"]').val() !== '') {
			if (archivo.length > 0) {
				let dialog = bootbox.dialog({
					message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Cargando y Procesando Archivo, Espere Por Favor...</p>',
					closeButton: false
				});
				formData.append('archivo', archivo[0]);
				formData.append('id', id);
				$.ajax({
					url: "core/app/view/colaborador.php?parAccion=cargar_archivo_vacaciones",
					type: "POST",
					data: formData,
					dataType: "json",
					processData: false,
					contentType: false,
					success: function(data) {
						dialog.modal('hide');
						get_vacaciones(id_colaborador);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {

					}
				});
			} else {
				alertas('error', 'Debe seleccionar un archivo excel.', '');
			}
		} else {
			alertas('error', 'Debe seleccionar un archivo excel.', '');
		}
	}

	function cancelar() {
		limpiar_formulario();
		$("#btn_rehusar").attr('onclick', 'guardar();');
		$("#btn_cancelar").attr('hidden', true);
	}

	function editar(id) {
		$.post('core/app/view/colaborador.php?parAccion=editar_vacaciones', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$("#periodo").val(obj.periodo);
			$("#fecha_salida").val(obj.fecha_salida);
			$("#fecha_retorno").val(obj.fecha_retorno);
			$("#dias").val(obj.dias);
			$("#observaciones").val(obj.observaciones);

			$("#periodo").focus();

			$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
			$("#btn_cancelar").removeAttr('hidden');
		});
	}

	function actualizar(id) {
		$.post('core/app/view/colaborador.php?parAccion=actualizar_vacaciones', {
			id_colaborador: id_colaborador,
			periodo: $("#periodo").val(),
			fecha_salida: $("#fecha_salida").val(),
			fecha_retorno: $("#fecha_retorno").val(),
			dias: $("#dias").val(),
			observaciones: $("#observaciones").val(),
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				if (obj.Result == "OK") {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Actualizado correctamente.</strong>' +
							'</div>'
					});
					get_vacaciones(id_colaborador);
					limpiar_formulario();
					cancelar();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			}
		});
	}

	function eliminar(id) {
		$.post('core/app/view/colaborador.php?parAccion=eliminar_vacaciones', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Eliminado correctamente.</strong>' +
						'</div>'
				});
				location.reload();
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Algo ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}

	function limpiar_formulario() {
		$("#periodo").val("");
		$("#fecha_salida").val("");
		$("#fecha_retorno").val("");
		$("#dias").val("");
		$("#observaciones").val("");
	}

	function guardar() {
		$.post('core/app/view/colaborador.php?parAccion=guardar_vacaciones', {
			id_colaborador: id_colaborador,
			periodo: $("#periodo").val(),
			fecha_salida: $("#fecha_salida").val(),
			fecha_retorno: $("#fecha_retorno").val(),
			dias: $("#dias").val(),
			observaciones: $("#observaciones").val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Guardado correctamente.</strong>' +
						'</div>'
				});
				get_vacaciones(id_colaborador);
				limpiar_formulario();
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Algo ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}
</script>