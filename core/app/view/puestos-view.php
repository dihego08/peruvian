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
			<h3><i class="glyphicon glyphicon-stats"></i> Registro de Puestos</h3>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-body">
					<div class="row" id="div_formulario" style="border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
						<div class="col-md-12">
							<label for="">Seleccionar Area</label>
							<select id="id_area" class="form-control rounded-pill js-example-basic-single">
								<option value="0">Seleccionar...</option>
							</select>
						</div>
						<div class="col-md-12" style="margin-top: 1rem;">
							<label>Nombre del Puesto</label>
							<textarea class="form-control rounded-pill" id="puesto"></textarea>
						</div>
						<div class="col-md-12" style="text-align: center; margin-top: 15px;">
							<button id="btn_rehusar" class="btn btn-success rounded-pill" onclick="guardar();">Guardar</button>
							<button class="btn btn-danger rounded-pill" id="btn_cancelar" hidden onclick="cancelar();">Cancelar</button>
						</div>
					</div>
					<h5 style="font-weight: bold;">Lista de Puestos Registrados</h5>
					<div class="form-row" id="div_puestos" style="margin-top: 15px;">

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		get_all_areas();
		$(".js-example-basic-single").select2();

		get_puestos($("#id_area").val());
	});

	function get_all_areas() {
		$("#tabla_colaboradores").find('tbody').empty();
		$.post('core/app/view/colaborador.php?parAccion=get_all_areas', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_area").append(`<option value="` + val.id + `">` + val.area + `</option>`)
			});
		});
	}

	function cancelar() {
		$("#puesto").val("");
		$("#btn_rehusar").attr('onclick', 'guardar();');
		$("#btn_cancelar").attr('hidden', true);
	}

	function get_puestos(id) {
		$("#div_puestos").empty();
		$("#div_formulario").removeAttr("hidden");

		$.post('core/app/view/colaborador.php?parAccion=get_puestos', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#div_puestos").append(`
					<div class="card col-md-12" style="padding: 0px !important; margin-bottom: 5px;">
  						<div class="card-header"  style="font-weight: bold;">
    						` + val.puesto + `
    						<span class="btn btn-outline-warning rounded-pill btn-sm btn_accion" style="right: 40px;" onclick="editar(` + val.id + `);"><i class="glyphicon glyphicon-pencil"></i></span>
    						<span class="btn btn-outline-danger rounded-pill btn-sm btn_accion" onclick="eliminar(` + val.id + `);"><i class="fa fa-trash"></i></span>
  						</div>
						<div class="card-body">
    						<h5 style="font-weight: bold;" class="card-title">` + val.area + `</h5>
  						</div>
					</div>
				`);
			});
		});
	}

	function editar(id) {
		$.post('core/app/view/colaborador.php?parAccion=editar_puesto', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$("#puesto").val(obj.puesto);

			$("#puesto").focus();

			$("#id_area").select2("val", obj.id_area);

			$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
			$("#btn_cancelar").removeAttr('hidden');
			$("#btn_rehusar").text("Actualizar");
		});
	}

	function actualizar(id) {
		$.post('core/app/view/colaborador.php?parAccion=actualizar_puesto', {
			id_area: $("#id_area").val(),
			puesto: $("#puesto").val(),
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_all_areas();
				limpiar_formulario();
				cancelar();
			}
		});
	}

	function eliminar(id) {
		$.post('core/app/view/colaborador.php?parAccion=eliminar_puesto', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Eliminado correctamente.</strong>' +
						'</div>'
				});
				get_puestos();
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
		$("#puesto").val("");
		$("#btn_rehusar").attr('onclick', 'guardar();');
		$("#btn_rehusar").text("Guardar");
	}

	function guardar() {
		$.post('core/app/view/colaborador.php?parAccion=guardar_puesto', {
			id_area: $("#id_area").val(),
			puesto: $("#puesto").val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_puestos($("#id_area").val());
				limpiar_formulario();
			}
		});
	}
</script>