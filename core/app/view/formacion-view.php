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
			<h1><i class="glyphicon glyphicon-stats"></i> Formación Académica</h1>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">
					<h4>Formación Académica</h4>
				</div>
				<div class="box-body">
					<h4 id="nombre_colaborador"></h4>

					<div class="row" hidden id="div_formulario" style="margin-top: 15px; background: wheat; border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
						<div class="col-md-6">
							<label>Formación</label>
							<input type="text" id="formacion" name="formacion" class="form-control">
						</div>
						<div class="col-md-6">
							<label>Lugar</label>
							<input type="text" id="lugar" name="lugar" class="form-control">
						</div>
						<div class="col-md-12" style="text-align: center; margin-top: 15px;">
							<a class="btn btn-info" href="http://192.99.55.83/sistema/core/app/view/pdf-utiles.php?parAccion=formacion&id_col=<?php echo $_GET['id_colaborador']; ?>">Imprimir</a>
							<button class="btn btn-success" id="btn_rehusar" onclick="guardar();">Guardar</button>
							<button class="btn btn-danger" id="btn_cancelar" hidden onclick="cancelar();">Cancelar</button>
							<a class="btn btn-primary" href="http://192.99.55.83/sistema/?view=colaborador2&id_col=<?php echo $_GET['id_colaborador']; ?>">Volver</a>
						</div>
					</div>
					<h5 style="font-weight: bold;">Formación del Colaborador</h5>
					<div class="form-row" id="div_formacion" style="margin-top: 15px;">

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
<script type="text/javascript">
	var id_colaborador = <?php echo isset($_GET['id_colaborador']) ? $_GET['id_colaborador'] : 0; ?>;
	$(document).ready(function() {
		get_all_colaboradores();
		$(".js-example-basic-single").select2();

		if (id_colaborador != 0) {
			get_formacion(id_colaborador);
		} else {}

		$("#id_colaborador").on("change", function() {
			get_formacion($("#id_colaborador").val());
		});
		get_colaborador();

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

	function goBack() {
		window.history.back();
	}

	function get_all_colaboradores() {
		$("#tabla_colaboradores").find('tbody').empty();
		$.post('core/app/view/colaborador.php?parAccion=get_all_colaboradores', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_colaborador").append(`<option value="` + val.id + `">` + val.nombres + " " + val.apellidos + `</option>`)
			});
		});
	}

	function get_formacion(id) {
		$("#div_formacion").empty();
		$("#div_formulario").removeAttr("hidden");

		$.post('core/app/view/colaborador.php?parAccion=get_formacion', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#div_formacion").append(`
					<div class="card col-md-12 mb-1" style="padding: 0px !important;">
  						<div class="card-header"  style="font-weight: bold;">
    						` + val.formacion + `
    						<span class="btn btn-info btn-sm btn_accion" style="right: 83px;" onclick="preparar_subir_archivo(${val.id});" data-toggle="modal" data-target="#exampleModal"><i class="glyphicon glyphicon-open-file"></i></span>
							<span class="btn btn-warning btn-sm btn_accion" style="right: 40px;" onclick="editar(` + val.id + `);"><i class="glyphicon glyphicon-pencil"></i></span>
    						<span class="btn btn-danger btn-sm btn_accion" onclick="eliminar(` + val.id + `);"><i class="fa fa-trash"></i></span>
							
  						</div>
						<div class="card-body">
    						<p>` + val.lugar + `</p>
							<p><a class="" href="core/app/view/formacion/${val.archivo}" target="_blank">${$.trim(val.archivo)}</a></p>
  						</div>
					</div>
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
					url: "core/app/view/colaborador.php?parAccion=cargar_archivo",
					type: "POST",
					data: formData,
					dataType: "json",
					processData: false,
					contentType: false,
					success: function(data) {
						dialog.modal('hide');
						get_formacion(id_colaborador);
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

	function limpiar_formulario() {
		$("#formacion").val("");
		$("#lugar").val("");
	}

	function guardar() {
		$.post('core/app/view/colaborador.php?parAccion=guardar_formacion', {
			id_colaborador: id_colaborador,
			formacion: $("#formacion").val(),
			lugar: $("#lugar").val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_formacion(id_colaborador);
				limpiar_formulario();
			}
		});
	}

	function editar(id) {
		$.post('core/app/view/colaborador.php?parAccion=editar_formacion', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$("#formacion").val(obj.formacion);
			$("#lugar").val(obj.lugar);

			$("#formacion").focus();

			$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
			$("#btn_rehusar").text('Actualizar');
			$("#btn_cancelar").removeAttr('hidden');
		});
	}

	function actualizar(id) {
		$.post('core/app/view/colaborador.php?parAccion=actualizar_formacion', {
			id_colaborador: id_colaborador,
			formacion: $("#formacion").val(),
			lugar: $("#lugar").val(),
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
					get_formacion(id_colaborador);
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
		$.post('core/app/view/colaborador.php?parAccion=eliminar_formacion', {
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
</script>