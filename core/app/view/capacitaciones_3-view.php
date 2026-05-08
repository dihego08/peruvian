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

	.m-0 {
		margin: 0 !important;
	}

	.mx-1 {
		margin-left: 0.5rem;
		margin-right: 0.5rem;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3><i class="glyphicon glyphicon-stats"></i> Capacitaciones</h3>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-body">
					<div class="row" id="div_formulario" style="margin-top: 15px; background: wheat; border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
						<div class="col-md-12">
							<label>Curso</label>
							<input type="text" id="curso" name="curso" class="form-control rounded-pill">
						</div>
						<div class="col-md-6">
							<label>Fecha</label>
							<input type="text" class="form-control rounded-pill datepicker" id="fecha">
						</div>
						<div class="col-md-6">
							<label>Cantidad de Horas</label>
							<input type="text" class="form-control rounded-pill" id="horas">
						</div>
						<div class="col-md-6">
							<label>Lugar</label>
							<input type="text" class="form-control rounded-pill" id="lugar">
						</div>
						<div class="col-md-6">
							<label>Capacitador</label>
							<input type="text" class="form-control rounded-pill" id="capacitador">
						</div>
						<hr style="width:100%">
						<div class="col-md-12">
							<label for="">Marcar Asistencia</label>
							<div id="lista_colaboradores" class="form-row"></div>
							<!-- asistentes_capacitacion -->
						</div>
						<div class="col-md-12" style="text-align: center; margin-top: 15px;">
							<button class="btn btn-success rounded-pill" id="btn_rehusar" onclick="guardar();">Guardar</button>
							<button class="btn btn-danger rounded-pill" id="btn_cancelar" hidden onclick="cancelar();">Cancelar</button>
						</div>
					</div>
					<hr style="width: 100%;">
					<div class="form-row">
						<h5 style="font-weight: bold;">Capacitaciones</h5>
						<div class="form-row" id="div_experiencia" style="margin-top: 15px;">
						</div>
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
						<label for="file1" class="btn btn-outline-danger rounded-pill"><i class="glyphicon glyphicon-camera"></i> Seleccionar Archivo</label>
						<input type="file" name="file1" id="file1" style="display: none;">
					</div>
					<div class="col-md-12" style='margin-top: 1rem !important;'>
						<span id='fileList'></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success rounded-pill" id="btn-subir-archivo">Subir Archivo</button>
				<button type="button" class="btn btn-danger rounded-pill" data-dismiss="modal">Cancelar</button>
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
		$(".datepicker").datetimepicker({
			format: "d-m-Y",
			timepicker: false
		});
		$.datetimepicker.setLocale('es');
		get_all_colaboradores();
		//$(".js-example-basic-single").select2();

		var fileList = document.getElementById("file1");
		fileList.addEventListener("change", function(e) {
			var list = "";
			for (var i = 0; i < this.files.length; i++) {
				list += "<div class='col-md-12'><span class='badge badge-info' style='font-size: 13px;'>" + this.files[i].name + "</span></div>"
			}

			$("#fileList").append(list);
		}, false);
		get_capacitacion();
	});

	function get_all_colaboradores() {
		$("#tabla_colaboradores").find('tbody').empty();
		$.post('core/app/view/colaborador.php?parAccion=get_all_colaboradores', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#lista_colaboradores").append(`<div class="form-check col-md-4">
                    <input class="form-check-input" type="checkbox" name="colaborador_asistente" id="flexCheckDefault" value="${val.id}">
                    <label class="form-check-label" for="flexCheckDefault">
                        ${val.nombres} ${val.apellido_paterno} ${val.apellido_materno}
                    </label>
                </div>`);
			});
		});
	}

	function goBack() {
		window.history.back();
	}

	function get_capacitacion() {
		$("#div_experiencia").empty();
		$("#div_formulario").removeAttr("hidden");

		$.post('core/app/view/colaborador.php?parAccion=get_capacitacion2', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				var asistentes = '';
				$.each(val.asistentes, function(i, v) {
					asistentes += `<div class="col-md-3 mx-1"><span class="label label-primary">${v}</span></div>`;
				});
				$("#div_experiencia").append(`
					<div class="card col-md-12" style="padding: 0px !important;">
  						<div class="card-header"  style="font-weight: bold;">
    						` + val.curso + `
							<span class="btn btn-outline-info rounded-pill btn-sm btn_accion" style="right: 83px;" onclick="preparar_subir_archivo(${val.id});" data-toggle="modal" data-target="#exampleModal"><i class="glyphicon glyphicon-open-file"></i></span>
    						<span class="btn btn-outline-warning rounded-pill btn-sm btn_accion" style="right: 40px;" onclick="editar(` + val.id + `);"><i class="glyphicon glyphicon-pencil"></i></span>
    						<span class="btn btn-outline-danger rounded-pill btn-sm btn_accion" onclick="eliminar(` + val.id + `);"><i class="fa fa-trash"></i></span>
  						</div>
						<div class="card-body">
    						<p>` + val.capacitador + `</p>
    						<p style="font-weight: bold; margin-bottom: 0px;"><strong>Lugar: </strong>` + val.lugar + `</p>
    						<p>` + val.fecha + ` - ` + val.horas + ` horas.</p>
							<p><a class="" href="core/app/view/capacitaciones/${val.archivo}" target="_blank">${$.trim(val.archivo)}</a></p>

							<div class="accordion" id="accordionExample">
								<div class="card">
									<div class="card-header" id="headingOne">
									<h2 class="m-0">
										<button class="btn btn-outline-success rounded-pill collapsed" type="button" data-toggle="collapse" data-target="#collapseOne${val.id}" aria-expanded="false" aria-controls="collapseOne${val.id}">
											Asistentes
										</button>
									</h2>
									</div>

									<div id="collapseOne${val.id}" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
										<div class="card-body">
										${asistentes}
										</div>
									</div>
								</div>
							</div>
  						</div>
					</div>
				`);
			});
		});
	}

	function cancelar() {
		limpiar_formulario();
		$("#btn_rehusar").attr('onclick', 'guardar();');
		$("#btn_cancelar").attr('hidden', true);
	}

	function editar(id) {
		$.post('core/app/view/colaborador.php?parAccion=editar_capacitacion2', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$("#curso").val(obj.curso);
			$("#capacitador").val(obj.capacitador);
			$("#lugar").val(obj.lugar);
			$("#fecha").val(obj.fecha);
			$("#horas").val(obj.horas);
			$("#curso").focus();

			$.each(obj.asistentes, function(index, val) {
				$('input.form-check-input[value="' + val.id_colaborador + '"]').prop('checked', true);
			});

			$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
			$("#btn_cancelar").removeAttr('hidden');
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
					url: "core/app/view/colaborador.php?parAccion=cargar_archivo_capacitaciones2",
					type: "POST",
					data: formData,
					dataType: "json",
					processData: false,
					contentType: false,
					success: function(data) {
						dialog.modal('hide');
						get_capacitacion(id_colaborador);
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

	function actualizar(id) {
		var asistentes = [];
		$('input[name="colaborador_asistente"]').each(function() {
			if ($(this).is(":checked")) {
				asistentes.push($(this).val());
			}
		});

		$.post('core/app/view/colaborador.php?parAccion=actualizar_capacitacion2', {
			id_colaborador: id_colaborador,
			curso: $("#curso").val(),
			capacitador: $("#capacitador").val(),
			lugar: $("#lugar").val(),
			fecha: $("#fecha").val(),
			horas: $("#horas").val(),
			id: id,
			asistentes: asistentes
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				if (obj.Result == "OK") {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Actualizado correctamente.</strong>' +
							'</div>'
					});
					get_capacitacion(id_colaborador);
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
		$.post('core/app/view/colaborador.php?parAccion=eliminar_capacitacion2', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Eliminado correctamente.</strong>' +
						'</div>'
				});
				get_capacitacion();
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
		$("#curso").val("");
		$("#capacitador").val("");
		$("#lugar").val("");
		$("#fecha").val("");
		$("#horas").val("");
		$('input.form-check-input').prop('checked', false);
	}

	function guardar() {
		var asistentes = [];
		$('input[name="colaborador_asistente"]').each(function() {
			if ($(this).is(":checked")) {
				asistentes.push($(this).val());
			}
		});
		// console.log(asistentes);
		//return;
		$.post('core/app/view/colaborador.php?parAccion=guardar_capacitacion_2', {
			id_colaborador: id_colaborador,
			curso: $("#curso").val(),
			capacitador: $("#capacitador").val(),
			lugar: $("#lugar").val(),
			fecha: $("#fecha").val(),
			horas: $("#horas").val(),
			asistentes: asistentes
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Guardado correctamente.</strong>' +
						'</div>'
				});
				get_capacitacion();
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