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

	.note {
		width: 100%;
		box-sizing: border-box;
		display: block;
		max-width: 100%;
		line-height: 1.5;
		padding: 15px 15px 30px;
		border-radius: 3px;
		/*border:1px solid #F7E98D;*/
		transition: box-shadow 0.5s ease;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		font-smoothing: subpixel-antialiased;
		/*background:linear-gradient(#F9EFAF, #F7E98D);
		background:-o-linear-gradient(#F9EFAF, #F7E98D);
		background:-ms-linear-gradient(#F9EFAF, #F7E98D);
		background:-moz-linear-gradient(#F9EFAF, #F7E98D);
		background:-webkit-linear-gradient(#F9EFAF, #F7E98D);*/
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h1><i class="glyphicon glyphicon-stats"></i> Habilidades Laborales</h1>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">
					<h4>Habilidades Laborales</h4>
				</div>
				<div class="box-body">
					<h4 id="nombre_colaborador"></h4>
					<!--<h4>Seleccionar Colaborador</h4>
		  			<select id="id_colaborador" class="form-control js-example-basic-single">
		  				<option value="0">Seleccionar...</option>
		  			</select>-->

					<div class="row" hidden id="div_formulario" style="margin-top: 15px; background: wheat; border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
						<div class="col-md-6 form-row">
							<h4>Habilidades Técnicas</h4>
							<hr>
							<div class="col-md-12">
								<label>Nombre de la Habilidad</label>
								<input type="text" id="elemento_tecnica" name="elemento_tecnica" class="form-control">
							</div>
							<div class="col-md-12">
								<label>Descripción</label>
								<textarea style="resize: none;" class="form-control" id="habilidad_tecnica"></textarea>
							</div>
							<div class="col-md-12" style="margin-top: 1rem;">
								<button class="btn btn-success" id="btn_rehusar_tecnica" onclick="guardar(1);">Guardar</button>
								<button class="btn btn-danger" id="btn_cancelar_tecnica" hidden onclick="cancelar(0);">Cancelar</button>
							</div>
						</div>
						<div class="col-md-6 form-row">
							<h4>Habilidades Blandas</h4>
							<hr>
							<div class="col-md-12">
								<label>Nombre de la Habilidad</label>
								<input type="text" id="elemento_blanda" name="elemento_blanda" class="form-control">
							</div>
							<div class="col-md-12">
								<label>Descripción</label>
								<textarea style="resize: none;" class="form-control" id="habilidad_blanda"></textarea>
							</div>
							<div class="col-md-12" style="margin-top: 1rem;">
								<button class="btn btn-success" id="btn_rehusar_blanda" onclick="guardar(0);">Guardar</button>
								<button class="btn btn-danger" id="btn_cancelar_blanda" hidden onclick="cancelar(0);">Cancelar</button>
							</div>
						</div>

						<div class="col-md-12" style="text-align: center; margin-top: 15px;">
							<a class="btn btn-info" href="http://192.99.55.83/sistema/core/app/view/pdf-utiles.php?parAccion=habilidades&id_col=<?php echo $_GET['id_colaborador']; ?>">Imprimir</a>

							<a class="btn btn-primary" href="http://192.99.55.83/sistema/?view=colaborador2&id_col=<?php echo $_GET['id_colaborador']; ?>">Volver</a>
						</div>
					</div>
					<h5 style="font-weight: bold;">Habilidades del Colaborador</h5>
					<div class="form-row" id="div_experiencia" style="margin-top: 15px;">

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script type="text/javascript" src="res/js/autosize.js"></script>
<script type="text/javascript">
	var id_colaborador = <?php echo isset($_GET['id_colaborador']) ? $_GET['id_colaborador'] : 0; ?>;
	$(document).ready(function() {
		get_all_colaboradores();
		$(".js-example-basic-single").select2();

		if (id_colaborador != 0) {
			get_habilidad(id_colaborador);
		} else {}

		$("#id_colaborador").on("change", function() {
			get_habilidad($("#id_colaborador").val());
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

	function get_all_colaboradores() {
		$("#tabla_colaboradores").find('tbody').empty();
		$.post('core/app/view/colaborador.php?parAccion=get_all_colaboradores', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_colaborador").append(`<option value="` + val.id + `">` + val.nombres + " " + val.apellido_paterno + " " + val.apellido_materno + `</option>`)
			});
		});
	}

	function goBack() {
		window.history.back();
	}

	function get_habilidad(id) {
		$("#div_experiencia").empty();
		$("#div_formulario").removeAttr("hidden");

		$.post('core/app/view/colaborador.php?parAccion=get_habilidad', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				var tipo_habilidad = '';
				if (val.tipo == 0) {
					tipo_habilidad = `<p class="badge badge-primary">BLANDA</p>`;
				} else if (val.tipo == 1) {
					tipo_habilidad = `<p class="badge badge-success">TECNICA</p>`;
				}
				$("#div_experiencia").append(`
					<div class="card col-md-12" style="padding: 0px !important; margin-top: 10px;">
  						<div class="card-header"  style="font-weight: bold;">
    						` + val.elemento + `
    						<span class="btn btn-warning btn-sm btn_accion" style="right: 40px;" onclick="editar(` + val.id + `, ${val.tipo});"><i class="glyphicon glyphicon-pencil"></i></span>
    						<span class="btn btn-danger btn-sm btn_accion" onclick="eliminar(` + val.id + `);"><i class="fa fa-trash"></i></span>
  						</div>
						<div class="card-body">
    						<span style="resize: none; height: auto;" class="form-control note">${val.habilidad}</span>
							${tipo_habilidad}
  						</div>
					</div>
				`);
			});
			//autosize($(".note"));
			autosize(document.getElementsByClassName("note"));
		});
	}

	function cancelar(tipo) {
		limpiar_formulario();
		if (tipo == 0) {
			$("#btn_rehusar_blanda").attr('onclick', 'guardar(0);');
			$("#btn_cancelar_blanda").attr('hidden', true);
		} else if (tipo == 1) {
			$("#btn_rehusar_tecnica").attr('onclick', 'guardar(1);');
			$("#btn_cancelar_tecnica").attr('hidden', true);
		}
	}

	function editar(id, tipo) {
		$.post('core/app/view/colaborador.php?parAccion=editar_habilidad', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (tipo == 0) {
				$("#elemento_blanda").val(obj.elemento);
				$("#habilidad_blanda").val(obj.habilidad.replace(/<br\s*[\/]?>/gi, ""));
				$("#elemento_blanda").focus();
			} else if (tipo == 1) {
				$("#elemento_tecnica").val(obj.elemento);
				$("#habilidad_tecnica").val(obj.habilidad.replace(/<br\s*[\/]?>/gi, ""));
				$("#elemento_tecnica").focus();
			}

			if (obj.tipo == 0) {
				$("#btn_rehusar_blanda").attr('onclick', 'actualizar(' + obj.id + ', 0);');
				$("#btn_cancelar_blanda").removeAttr('hidden');
			} else if (obj.tipo == 1) {
				$("#btn_rehusar_tecnica").attr('onclick', 'actualizar(' + obj.id + ', 1);');
				$("#btn_cancelar_tecnica").removeAttr('hidden');
			}
		});
	}

	function actualizar(id, tipo) {
		if (tipo == 0) {
			var elemento = $("#elemento_blanda").val();
			var habilidad = $("#habilidad_blanda").val();
		} else if (tipo == 1) {
			var elemento = $("#elemento_tecnica").val();
			var habilidad = $("#habilidad_tecnica").val();
		}
		$.post('core/app/view/colaborador.php?parAccion=actualizar_habilidad', {
			id_colaborador: id_colaborador,
			elemento: elemento,
			habilidad: habilidad,
			id: id,
			tipo: tipo,
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				if (obj.Result == "OK") {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Actualizado correctamente.</strong>' +
							'</div>'
					});
					get_habilidad(id_colaborador);
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
		$.post('core/app/view/colaborador.php?parAccion=eliminar_habilidad', {
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
		$("#elemento_blanda").val("");
		$("#habilidad_blanda").val("");
		$("#elemento_tecnica").val("");
		$("#habilidad_tecnica").val("");
	}

	function guardar(tipo) {
		if (tipo == 0) {
			var elemento = $("#elemento_blanda").val();
			var habilidad = $("#habilidad_blanda").val();
		} else if (tipo == 1) {
			var elemento = $("#elemento_tecnica").val();
			var habilidad = $("#habilidad_tecnica").val();
		}

		$.post('core/app/view/colaborador.php?parAccion=guardar_habilidad', {
			id_colaborador: id_colaborador,
			elemento: elemento,
			habilidad: habilidad,
			tipo: tipo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_habilidad(id_colaborador);
				limpiar_formulario();
			}
		});
	}
</script>