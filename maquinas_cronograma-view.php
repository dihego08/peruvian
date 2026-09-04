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

	.badge-danger {
		color: #fff;
		background-color: #dc3545;
	}

	.badge-success {
		color: #fff;
		background-color: #28a745;
	}

	.context-menu ul li:hover {
		cursor: pointer;
		background: #bbb;
	}

	.nav-pills .nav-link.active,
	.nav-pills .show>.nav-link {
		color: #fff;
		background-color: #007bff;
		font-size: 12px;
	}

	.nav-pills .nav-link {
		border-radius: .25rem;
	}

	.nav-link {
		display: block;
		padding: 5px 10px !important;
	}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3><i class="glyphicon glyphicon-stats"></i> Programa General</h3>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">
					<div class="form-row">
						<ul class="nav nav-pills mb-3" role="tablist" id="div-cronogramas">
						</ul>
					</div>
				</div>
				<div class="box-body">
					<div class="panel-group" id="accordion">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapse0" aria-expanded="false" class="collapsed" id="a_acordion">
										Agregar Ítem
									</a>
								</h4>
							</div>
							<div id="collapse0" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
								<div class="panel-body">
									<div class="row" id="div_formulario" style="background: wheat; border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
										<div class="col-md-12">
											<label style="display: block;">Ítem</label>
											<!-- <input type="text" id="curso" name="curso" class="form-control rounded-pill"> -->
											<select name="curso" id="curso" class="form-control rounded-pill w-100" style="width: 100%;"></select>
										</div>
										<div class="col-md-12">
											<label>Áreas</label>
											<textarea name="" style="resize: none;" id="areas" class="form-control rounded-pill"></textarea>
										</div>
										<div class="col-md-12">
											<label>Responsable</label>
											<input type="text" class="form-control rounded-pill" id="responsable">
										</div>
										<div class="col-md-12">
											<label>Tipo de Programa</label>
											<select name="" id="id_tipo" class="form-control rounded-pill"></select>
										</div>
										<div class="col-md-6">
											<label>Verificador de Eficacia</label>
											<input type="text" class="form-control rounded-pill" id="eficacia">
										</div>
										<div class="col-md-6">
											<label>Año</label>
											<input type="text" class="form-control rounded-pill" id="_anio_">
										</div>
										<div id="div_add" class="form-row">
											<div class="col-md-5">
												<label>Mes</label>
												<!--<input type="text" class="form-control rounded-pill" id="mes" name="mes[]">-->
												<select name="mes[]" id="mes" class="form-control rounded-pill">
													<option value="">--SELECCIONA--</option>
													<option value="0">Enero</option>
													<option value="1">Febrero</option>
													<option value="2">Marzo</option>
													<option value="3">Abril</option>
													<option value="4">Mayo</option>
													<option value="5">Junio</option>
													<option value="6">Julio</option>
													<option value="7">Agosto</option>
													<option value="8">Septiembre</option>
													<option value="9">Octubre</option>
													<option value="10">Noviembre</option>
													<option value="11">Diciembre</option>
												</select>
											</div>
											<div class="col-md-5">
												<label>Día</label>
												<input type="text" class="form-control rounded-pill" id="dia" name="dia[]">
											</div>
											<div class="col-md-2" style="text-align: center;">
												<label style="display: block;">Añadir</label>
												<span class="btn btn-outline-success rounded-pill" onclick="add_elemento_to_form();">
													<i class="fa fa-plus"></i>
												</span>
											</div>
										</div>
										<div class="col-md-12" style="text-align: center; margin-top: 15px;">
											<button class="btn btn-success rounded-pill" id="btn_rehusar" onclick="guardar();">Guardar</button>
											<button class="btn btn-danger rounded-pill" id="btn_cancelar" hidden onclick="cancelar();">Cancelar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box">
				<div class="box-header">
					<h4>Programa</h4>
				</div>
				<div class="box-body">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="fecha_desde">Desde:</label>
							<div class="input-group">
								<input type="text" name="fecha_desde" id="fecha_desde" readonly="readonly" class="form-control rounded-pill">
								<span class="input-group-addon">
									<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
								</span>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="fecha_hasta">Hasta:</label>
							<div class="input-group">
								<input type="text" name="fecha_hasta" id="fecha_hasta" readonly="readonly" class="form-control rounded-pill">
								<span class="input-group-addon">
									<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-12 text-center" style="width: 100%; text-align: center; margin-top: 0.5rem; margin-bottom: 1.5rem;">
						<button onclick="get_cronograma();" class="btn btn-success rounded-pill">Ver</button>
					</div>
					<div id="customers">
						<div class="table-responsive" style="width: 100%;">
							<table class="table table-hover table-bordered table-striped  table-responsive" id="tabla_cronograma">
								<thead>

								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
					<div style="width: 100%;">
						<a href="core/app/view/pdf-cronograma.php" id="btn_pdf_cronograma" class="btn btn-info">PDF</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="modal_cambiar_estado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="width: 45%;">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="exampleModalLabel">Cambiar Estado</h4>
			</div>
			<div class="modal-body">
				<h4>Seleccionar Estado</h4>
				<div class="form row">
					<div class="col-md-12">
						<label for="">Estado1</label>
						<select name="opt_estado" id="opt_estado" class="form-control rounded-pill">
							<option value="">--Selecciona--</option>
							<option value="0">PLANIFICADO</option>
							<option value="1">EJECUTADO</option>
							<option value="2">REPROGRAMADO</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer txt-left">
				<span class="btn btn-danger rounded-pill" type="button" data-dismiss="modal" id="cerrar_formulario">
					Guardar
				</span>
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
	var tipo_cronograma = 2;

	function get_tipos_cronograma() {
		$.post('core/app/view/colaborador.php?parAccion=get_tipos_cronograma', {
			id: tipo_cronograma
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#div-cronogramas").append(`
					<li class="nav-item" style="margin-bottom: 0.5rem; cursor: pointer;">
                        <a class="nav-link active" aria-selected="true" onclick="get_cronograma(${val.id});">${val.tipo_cronograma}</a>
                    </li>
				`);
				$("#id_tipo").append(`<option value="${val.id}">${val.tipo_cronograma}</option>`);
			});
		});
	}
	$(document).ready(function() {
		$("#anio").val(<?php echo date("Y"); ?>);
		$(".datepicker").datetimepicker({
			format: "Y-m-d",
			timepicker: false
		});
		$.datetimepicker.setLocale('es');
		get_maquinas();
		get_tipos_cronograma();
		get_cronograma(tipo_cronograma);
		$('#fecha_desde').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});
		$('#fecha_hasta').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});
	});
	var aux_form = 0;

	function get_maquinas() {
		$.post("core/app/view/maquinas.php?parAccion=llenar_maquinas", function(response) {
			var obj = JSON.parse(response);
			$.each(obj, function(index, val) {
				$("#curso").append(`<option value="${val.maquina_tipo} - ${val.maquina_codigo} - ${val.maquina_modelo} - ${val.maquina_descripcion}">${val.maquina_tipo} - ${val.maquina_codigo} - ${val.maquina_modelo} - ${val.maquina_descripcion}</option>`);
			});
			$("#curso").select2();
		});
	}

	function add_elemento_to_form() {
		aux_form++;
		$("#div_add").append(`
			<div class="col-md-5 form_${aux_form} form_delete" style="margin-top: 0.5rem;">
				<label>Mes</label>
				<select name="mes[]" id="mes" class="form-control rounded-pill">
					<option value="">--SELECCIONA--</option>
					<option value="0">Enero</option>
					<option value="1">Febrero</option>
					<option value="2">Marzo</option>
					<option value="3">Abril</option>
					<option value="4">Mayo</option>
					<option value="5">Junio</option>
					<option value="6">Julio</option>
					<option value="7">Agosto</option>
					<option value="8">Septiembre</option>
					<option value="9">Octubre</option>
					<option value="10">Noviembre</option>
					<option value="11">Diciembre</option>
				</select>
			</div>
			<div class="col-md-5 form_${aux_form} form_delete" style="margin-top: 0.5rem;">
				<label>Día</label>
				<input type="text" class="form-control rounded-pill datepicker" id="dia" name="dia[]">
			</div>
			<div class="col-md-2 form_${aux_form} form_delete" style="text-align: center; margin-top: 0.5rem;">
				<label style="display: block;">Eliminar</label>
				<span class="btn btn-outline-danger rounded-pill" onclick="remove_elemento_from_form(${aux_form});">
					<i class="fa fa-trash"></i>
				</span>
			</div>
		`);
	}

	function remove_elemento_from_form(aux) {
		$(".form_" + aux).remove();
	}

	function hecho(id) {
		$.post('core/app/view/colaborador.php?parAccion=hecho', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Actualizado correctamente.</strong>' +
						'</div>'
				});
				get_cronograma(tipo_cronograma);
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Algo ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}

	function no_hecho(id) {
		$.post('core/app/view/colaborador.php?parAccion=no_hecho', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Actualizado correctamente.</strong>' +
						'</div>'
				});
				get_cronograma(tipo_cronograma);
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Algo ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}
	var el_anio = 0;

	function get_cronograma(id = tipo_cronograma) {
		var ff = new Date();
		var $anio = ff.getFullYear();
		$("#tabla_cronograma").find('thead').empty();
		$("#tabla_cronograma").find('tbody').empty();
		var meses = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic.'];

		var cabecera = `<tr>
			<th rowspan="2"></th>
			<th rowspan="2" style="text-align: center;">
				Ítem
			</th>
			<th style="text-align: center;" rowspan="2">
				AREAS
			</th>
			<th style="text-align: center;" rowspan="2">
				RESPONSABLE
			</th>
			<th style="text-align: center;" rowspan="2">
				T. PROGRAMA
			</th>
			<th style="text-align: center;" rowspan="2">
				VER. EFICACIA
			</th>`;
		if ($("#fecha_desde").val() == "" && $("#fecha_hasta").val() == "") {
			for (var i = 0; i < meses.length; i++) {
				cabecera += `<th style="text-align: center;">${meses[i]} <br /> ${$anio}</th>`;
			}
			var $fecha_desde = '<?php echo date("Y-01-01"); ?>';
			var $fecha_hasta = '<?php echo date("Y-12-31"); ?>';
			el_anio = $anio;
		} else {
			var dt0 = new Date($("#fecha_desde").val() + "T23:00:00");
			var dt = new Date($("#fecha_hasta").val() + "T23:00:00");
			for (var i = dt0.getMonth(); i <= dt.getMonth(); i++) {
				cabecera += `<th style="text-align: center;">${meses[i]} <br /> ${dt0.getFullYear()}</th>`;
			}
			var $fecha_desde = $("#fecha_desde").val();
			var $fecha_hasta = $("#fecha_hasta").val();
			el_anio = dt0.getFullYear();
		}

		cabecera += '<th></th></tr><tr>';

		var r0 = new Date($fecha_desde + "T23:00:00");
		var r = new Date($fecha_hasta + "T23:00:00");
		for (var i = r0.getMonth(); i <= r.getMonth(); i++) {
			cabecera += `<th style="text-align: center;" id="th_${i}">0%</th>`;
		}
		cabecera += '<th width="5%">CUMPLIMIENTO ANUAL</th>';
		cabecera += '</tr>';

		$("#tabla_cronograma").find('thead').append(cabecera);

		$("#btn_pdf_cronograma").attr("href", "core/app/view/pdf-cronograma.php?fecha_desde=" + $fecha_desde + "&fecha_hasta=" + $fecha_hasta + "&tipo=" + id);

		$.post('core/app/view/colaborador.php?parAccion=get_cronograma', {
			anuo: $anio,
			mes: $("#_mes_").val(),
			tipo: id,
			fecha_desde: $fecha_desde,
			fecha_hasta: $fecha_hasta,
		}, function(data) {
			var obj = JSON.parse(data);

			var meses_valor = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
			var meses_aux = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

			$.each(obj, function(index, val) {

				var cuerpo = "";

				cuerpo += `<tr>
					<td style="width: 86px;">
						<span class="btn-sm btn-outline-warning rounded-pill btn " style="display: block; text-align: center;" onclick="editar(` + val.id + `);"><i class="glyphicon glyphicon-pencil"></i></span>
    					<span class="btn-sm btn-outline-danger rounded-pill btn " style="margin-top: 0.5rem; display: block; text-align: center;" onclick="eliminar(` + val.id + `);"><i class="fa fa-trash"></i></span>
					</td>
					<td style="text-align: center; width: 150px;">${val.curso}</td>
					<td style="text-align: center; width: 75px;">${val.areas}</td>
					<td style="text-align: center; width: 75px;">${val.responsable}</td>
					<td style="text-align: center; width: 75px;"><span class="badge badge-primary" style="white-space: break-spaces;">${$.trim(val.tipo_cronograma)}</span></td>
					<td style="text-align: center; width: 75px;">${$.trim(val.eficacia)}</td>`;

				for (var i = r0.getMonth(); i <= r.getMonth(); i++) {
					cuerpo += `<td id="td_${i}_${val.id}" style="text-align: center; vertical-align: middle;" >
					</td>`;
				}
				cuerpo += `<td id="td_anuo_${val.id}" style="text-align: center;"></td></tr>`;

				var el_dia = "0";
				if (val.dia == null || val.dia == "" || val.dia == undefined) {

				} else {
					el_dia = val.dia;
				}
				$("#tabla_cronograma").find('tbody').append(cuerpo);
				var programado = 0;
				var ejecutado = 0;

				$.each(val.fechas, function(index, va) {
					var estado = "";
					var cl = "";
					if (va.estado == 0 || va.estado == null) {
						estado = `<span title="Hacer clic para cambiar estado." class="badge badge-danger" style="cursor: pointer;" onclick="hecho(${va.id});">No Realizado</span>`;
						cl = "bg-danger";
					} else if (va.estado == 1) {
						estado = `<span title="Hacer clic para cambiar estado." class="badge badge-success" style="cursor: pointer;" onclick="no_hecho(${va.id});">Realizado</span>`;
						cl = "bg-success";
					} else if (va.estado == 2) {
						estado = `<span title="Hacer clic para cambiar estado." class="badge badge-success" style="cursor: pointer;" onclick="no_hecho(${va.id});">Reprogramado</span>`;
						cl = "bg-warning";
					}

					meses_valor[va.mes] = meses_valor[va.mes] + 1;
					programado += 1;
					if (va.estado == 1) {
						ejecutado += 1;
						meses_aux[va.mes] = meses_aux[va.mes] + 1;
					}
					$("#td_" + va.mes + "_" + val.id).append(`
					    <div class="${cl} d-block p-2" style="cursor: pointer; padding: 1rem; display: block; margin-bottom: 0.5rem;" onclick="preparar_cambio_estado(${va.id}, ${va.estado});" title="Clic para cambiar estado" data-toggle="modal" data-target="#modal_cambiar_estado">
					        <strong style="font-weight: 900; font-size: 20px;">${va.dia.padStart(2, "0")}</strong>
					    </div>
				    `);
				});

				$("#td_anuo_" + val.id).append(((ejecutado * 100) / programado).toFixed(0) + "%");
			});
			for (var j = 0; j < meses_valor.length; j++) {
				if (meses_valor[j] == 0) {} else {
					$("#th_" + j).text(((meses_aux[j] * 100) / meses_valor[j]).toFixed(0) + "%");
				}
			}
		});
	}

	function preparar_cambio_estado(id, estado) {
		$("#cerrar_formulario").attr('onclick', 'guardar_cambio_estado(' + id + ');');
		$("#opt_estado").val(estado);
	}

	function guardar_cambio_estado(id) {
		var dialog = bootbox.dialog({
			message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Cambiando estado, espere por favor.</p>',
			closeButton: false
		});

		$.post('core/app/view/colaborador.php?parAccion=guardar_cambio_estado', {
			id: id,
			estado: $("#opt_estado").val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				dialog.modal('hide');
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Actualizado correctamente.</strong>' +
						'</div>'
				});
				get_cronograma(tipo_cronograma);
			} else {
				dialog.modal('hide');
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Algo ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}

	function get_capacitacion(id) {
		$("#div_experiencia").empty();
		$("#div_formulario").removeAttr("hidden");

		$.post('core/app/view/colaborador.php?parAccion=get_capacitacion', {
			id: id
		}, function(data) {

			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				if (val.estado == 0 || val.estado == null) {
					estado = `<span class="badge badge-danger" style="cursor: pointer;">No Realizado</span>`;
				} else {
					estado = `<span class="badge badge-success" style="cursor: pointer;">Realizado</span>`;
				}
				$("#div_experiencia").append(`
					<div class="card col-md-12" style="padding: 0px !important;">
  						<div class="card-header"  style="font-weight: bold;">
    						` + val.curso + `
    						<span class="btn btn-warning btn-sm btn_accion" style="right: 40px;" onclick="editar(` + val.id + `);"><i class="glyphicon glyphicon-pencil"></i></span>
    						<span class="btn btn-danger btn-sm btn_accion" onclick="eliminar(` + val.id + `);"><i class="fa fa-trash"></i></span>
  						</div>
						<div class="card-body">
    						<p>` + val.capacitador + `</p>
    						<p style="font-weight: bold; margin-bottom: 0px;"><strong>Lugar: </strong>` + val.lugar + `</p>
    						<p>` + val.fecha + ` - ` + val.horas + `</p>
  						</div>
					</div>
				`);
			});
		});
	}

	function cancelar() {
		limpiar_formulario();
		$("#btn_rehusar").attr('onclick', 'guardar();');
		$("#btn_rehusar").text('Guardar');
		$("#btn_cancelar").attr('hidden', true);
	}

	function editar(id) {
		if ($("#a_acordion").attr('class') == "collapsed") {
			console.log("YES");
			$("#a_acordion").click();
		} else {
			console.log("NO");
		}

		$("#curso").focus();
		$.post('core/app/view/colaborador.php?parAccion=editar_capacitacion_registro', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$("#curso").val(obj.curso);
			$("#responsable").val(obj.responsable);
			$("#eficacia").val(obj.eficacia);
			$("#id_tipo").val(obj.id_tipo);
			$("#_anio_").val(obj.anio);
			$("#areas").val(obj.areas.replace(/<[^>]*>?/gm, ''));

			//$("#fecha").val(obj.fecha);

			$("#curso").focus();

			//$("#div_add")
			$.each(obj.fechas, function(index, val) {
				if (index == 0) {
					$("#mes").val(val.mes);
					$("#dia").val(val.dia);
				} else {
					$("#div_add").append(`
						<div class="col-md-5 form_${index} form_delete" style="margin-top: 0.5rem;">
							<label>Mes</label>
							<select name="mes[]" id="mes${index}" class="form-control rounded-pill">
								<option value="">--SELECCIONA--</option>
								<option value="0">Enero</option>
								<option value="1">Febrero</option>
								<option value="2">Marzo</option>
								<option value="3">Abril</option>
								<option value="4">Mayo</option>
								<option value="5">Junio</option>
								<option value="6">Julio</option>
								<option value="7">Agosto</option>
								<option value="8">Septiembre</option>
								<option value="9">Octubre</option>
								<option value="10">Noviembre</option>
								<option value="11">Diciembre</option>
							</select>
						</div>
						<div class="col-md-5 form_${index} form_delete" style="margin-top: 0.5rem;">
							<label>Día</label>
							<input type="text" class="form-control rounded-pill datepicker" id="dia${index}" name="dia[]">
						</div>
						<div class="col-md-2 form_${index} form_delete" style="text-align: center; margin-top: 0.5rem;">
							<label style="display: block;">Eliminar</label>
							<span class="btn btn-danger" onclick="delete_elemento_from_form(${index});">
								<i class="fa fa-trash"></i>
							</span>
						</div>
					`);
					$("#mes" + index).val(val.mes);
					$("#dia" + index).val(val.dia);
				}

			});

			$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
			$("#btn_rehusar").text('Actualizar');
			$("#btn_cancelar").removeAttr('hidden');
		});
	}

	function delete_elemento_from_form(id) {
		bootbox.confirm({
			message: "¿Seguro de Eliminar este registro? Esta acción es irreversible.",
			buttons: {
				confirm: {
					label: 'Sí',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function(result) {
				if (result) {
					$.post('core/app/view/colaborador.php?parAccion=delete_elemento_from_form', {
						id: id
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == "OK") {
							bootbox.alert({
								message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Eliminado correctamente.</strong>' +
									'</div>'
							});
							//location.reload();
							$(".form_" + id).remove();
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Algo ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				} else {}
			}
		});
	}

	function actualizar(id) {
		var _meses_ = $("select[name='mes[]']").map(function() {
			return $(this).val();
		}).get();
		var _dias_ = $("input[name='dia[]']").map(function() {
			return $(this).val();
		}).get();

		if ($("#curso").val() == "" || $("#curso").val() == undefined) {
			bootbox.alert({
				message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
					'<strong>Se debe ingresar un curso.</strong>' +
					'</div>'
			});
		} else {
			$.post('core/app/view/colaborador.php?parAccion=actualizar_registro_capacitacion', {
				curso: $("#curso").val(),
				areas: $("#areas").val(),
				//fecha: $("#fecha").val(),
				meses: _meses_,
				dias: _dias_,
				responsable: $("#responsable").val(),
				eficacia: $("#eficacia").val(),
				id_tipo: $("#id_tipo").val(),
				id: id,
				anio: $("#_anio_").val(),
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Actualizado correctamente.</strong>' +
							'</div>'
					});
					get_cronograma(tipo_cronograma);
					limpiar_formulario();
					$("#btn_rehusar").attr('onclick', 'guardar();');
					$("#btn_rehusar").text('Guardar');
					$("#btn_cancelar").attr('hidden', true);
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}
	}

	function eliminar(id) {
		bootbox.confirm({
			message: "¿Seguro de Eliminar este registro?",
			buttons: {
				confirm: {
					label: 'Sí',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function(result) {
				if (result) {
					$.post('core/app/view/colaborador.php?parAccion=eliminar_registro_capacitacion', {
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
				} else {}
			}
		});

	}

	function limpiar_formulario() {
		$("#curso").val("");
		$("#areas").val("");
		$("#mes").val("");
		$("#responsable").val("")
		$(".form_delete").remove();
		$("#dia").val("");
	}

	function guardar() {
		var _meses_ = $("select[name='mes[]']").map(function() {
			return $(this).val();
		}).get();
		var _dias_ = $("input[name='dia[]']").map(function() {
			return $(this).val();
		}).get();

		if ($("#curso").val() == "" || $("#curso").val() == undefined) {
			bootbox.alert({
				message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
					'<strong>Se debe ingresar un curso.</strong>' +
					'</div>'
			});
		} else {

			$.post('core/app/view/colaborador.php?parAccion=guardar_registro_capacitacion', {
				curso: $("#curso").val(),
				areas: $("#areas").val(),
				//fecha: $("#fecha").val(),
				meses: _meses_,
				dias: _dias_,
				responsable: $("#responsable").val(),
				eficacia: $("#eficacia").val(),
				id_tipo: $("#id_tipo").val(),
				anio: $("#_anio_").val(),
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Guardado correctamente.</strong>' +
							'</div>'
					});
					get_cronograma(tipo_cronograma);
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
	}




	function demoFromHTML() {
		var pdf = new jsPDF('l', 'pt', 'a4');
		// source can be HTML-formatted string, or a reference
		// to an actual DOM element from which the text will be scraped.
		source = $('#customers')[0];

		// we support special element handlers. Register them with jQuery-style 
		// ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
		// There is no support for any other type of selectors 
		// (class, of compound) at this time.
		specialElementHandlers = {
			// element with id of "bypass" - jQuery style selector
			'#bypassme': function(element, renderer) {
				// true = "handled elsewhere, bypass text extraction"
				return true
			}
		};
		margins = {
			top: 80,
			bottom: 60,
			left: 40,
			width: 522
		};
		// all coords and widths are in jsPDF instance's declared units
		// 'inches' in this case
		pdf.fromHTML(
			source, // HTML string or DOM elem ref.
			margins.left, // x coord
			margins.top, { // y coord
				'width': margins.width, // max width of content on PDF
				'elementHandlers': specialElementHandlers
			},
			function(dispose) {
				// dispose: object with X, Y of the last line add to the PDF 
				//          this allow the insertion of new lines after html
				pdf.save('Test.pdf');
			}, margins);
	}
</script>