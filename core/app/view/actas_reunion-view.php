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
			opacity: .8;
		}
	</style>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<h3><i class="glyphicon glyphicon-stats"></i> Actas de Reunion</h3>
				<div class="clearfix"></div>
				<div class="box">
					<div class="box-header">
					</div>
					<div class="box-body row">

						<div class="col-md-4" id="div_formulario" style="margin-top: 15px; background: wheat; border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
							<h4>Adjuntar Acta de Reunion</h4>
							<div class="col-md-12">
								<label>Orden del dia</label>
								<textarea class="form-control rounded-pill" id="orden_dia" style="resize: none;"></textarea>
							</div>
							<div class="col-md-12">
								<label>Fecha</label>
								<input type="text" class="form-control rounded-pill datepicker" name="" id="fecha">
							</div>

							<div class="col-md-12">
								<label>Horas/reunión</label>
								<input type="text" class="form-control rounded-pill" name="" id="duracion">
							</div>
							<div class="col-md-12">
								<label>Convoca</label>
								<input type="text" class="form-control rounded-pill" name="" id="convoca">
							</div>

							<div class="form-row mt-2">
								<div class="col-md-12">
									<label>Acuerdos</label>
									<input type="file" name="file1_2" id="file1_2" class="form-control rounded-pill">
								</div>
							</div>
							<div class="form-row mt-2">
								<div class="col-md-12">
									<label>Lista de Asistentes</label>
									<input type="file" name="file1" id="file1" class="form-control rounded-pill">
								</div>
							</div>
							<div class="col-md-12 mt-3">
								<progress id="progressBar" class="mt-2" value="0" max="100" style="width:100%;"></progress>
								<p id="status"></p>
								<p id="loaded_n_total"></p>
							</div>
							<div class="col-md-12" style="text-align: center; margin-top: 15px;">
								<button class="btn btn-success rounded-pill" id="btn_rehusar" onclick="uploadFile();">Guardar</button>
								<button class="btn btn-danger rounded-pill" id="btn_cancelar" hidden onclick="cancelar();">Cancelar</button>
							</div>
						</div>
						<div class="col-md-8">
							<h4>Registro de Actas</h4>
							<table class="table table-hover table-bordered table-striped" id="tabla_asistencias" style="font-size: 12px;">
								<thead style="font-weight: bold; ">
									<th>Fecha</th>
									<th>Orden del Dia</th>
									<th>Horas/Reunion</th>
									<th>Convoca</th>
									<th>Acuerdos</th>
									<th>Asistencias</th>
									<th></th>
								</thead>
								<tbody></tbody>
							</table>
							<div style="width: 100%; text-align: right;">
								<a href="core/app/view/pdf-actas.php" target="_blank" class="btn btn-outline-dark rounded-pill">Exportar PDF</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.full.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet" />
	<script>
		function _(el) {
			return document.getElementById(el);
		}

		function uploadFile() {
			var file1_2 = _("file1_2").files[0];
			var file = _("file1").files[0];
			var formdata = new FormData();

			formdata.append("file1", file);
			formdata.append("file1_2", file1_2);
			formdata.append("orden_dia", $("#orden_dia").val());
			formdata.append("acuerdos", $("#acuerdos").val());
			formdata.append("fecha_registro", $("#fecha").val());
			formdata.append("duracion", $("#duracion").val());
			formdata.append("convoca", $("#convoca").val());

			var ajax = new XMLHttpRequest();
			ajax.upload.addEventListener("progress", progressHandler, false);
			ajax.addEventListener("load", completeHandler, false);
			ajax.addEventListener("error", errorHandler, false);
			ajax.addEventListener("abort", abortHandler, false);
			ajax.open("POST", "core/app/view/colaborador.php?parAccion=guardar_acta");
			ajax.send(formdata);
		}

		function progressHandler(event) {
			_("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
			var percent = (event.loaded / event.total) * 100;
			_("progressBar").value = Math.round(percent);
		}

		function completeHandler(event) {
			get_actas_reunion();
			limpiar_formulario();
			_("progressBar").value = 0;
			$("#cerrar_formulario").click();
			$("#cerrar_mas_imagenes").click();
		}

		function errorHandler(event) {
			_("status").innerHTML = "Upload Failed";
		}

		function abortHandler(event) {
			_("status").innerHTML = "Upload Aborted";
		}
	</script>
	<script type="text/javascript">
		var anio = <?php echo date('Y'); ?>;

		function get_actas_reunion() {
			$("#tabla_asistencias").find('tbody').empty();
			$.post('core/app/view/colaborador.php?parAccion=get_actas_reunion', function(data) {

				var obj = JSON.parse(data);
				$.each(obj, function(index, val) {
					var foto = "";
					var asistentes = "";

					if (val.asistentes == "" || val.asistentes == null) {} else {
						asistentes = `<a href="core/app/view/asistencias/${val.asistentes}" target="_blank"><i class="glyphicon glyphicon-file"></i> ${val.asistentes}</a>`;
					}

					var acuerdos = "";

					if (val.acuerdos == "" || val.acuerdos == null) {} else {
						acuerdos = `<a href="core/app/view/asistencias/${val.acuerdos}" target="_blank"><i class="glyphicon glyphicon-file"></i> ${val.acuerdos}</a>`;
					}
					$("#tabla_asistencias").find('tbody').append(`
						<tr>
							<td>${val.fecha_registro}</td>
							<td>${val.orden_dia}</td>
							<td>${val.duracion}</td>
							<td>${val.convoca}</td>
							<td>${acuerdos}</td>
							<td>${asistentes}</td>
							<td class="text-center">
								<span class="btn btn-sm btn-outline-danger rounded-pill btn_accion" onclick="eliminar(${val.id});"><i class="fa fa-trash" style="color: #d73925; font-size: 14px;"></i></span>
							</td>
						</tr>
					`);
				});
			});
		}

		function eliminar(id) {
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
				callback: function(result) {
					if (result) {
						$.post('core/app/view/colaborador.php?parAccion=eliminar_acta_reunion', {
							id: id
						}, function(data) {
							var obj = JSON.parse(data);
							if (obj.Result == "OK") {
								bootbox.alert({
									message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Eliminado correctamente.</strong>' +
										'</div>'
								});
								get_actas_reunion();
							} else {
								bootbox.alert({
									message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Algo ha salido mal.</strong>' +
										'</div>'
								});
							}
						});
					} else {

					}
				}
			});
		}

		function limpiar_formulario() {
			$("#file1").val("");
			$("#orden_dia").val("");
			$("#acuerdos").val("");
			$("#fecha_registro").val("");
			$("#duracion").val("");
			$("#convoca").val("");
		}
		$(document).ready(function() {
			$(".datepicker").datetimepicker({
				format: "d-m-Y",
				timepicker: false
			});
			$.datetimepicker.setLocale('es');
			get_actas_reunion();
		});
	</script>