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
			<h3><i class="glyphicon glyphicon-stats"></i> Registro de Áreas</h3>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-body">
					<div class="row" id="div_formulario" style="border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
						<div class="col-md-12" style="margin-top: 1rem;">
							<label>Nombre del Área</label>
							<input type="text" class="form-control rounded-pill" id="area">
						</div>
						<div class="col-md-12" style="text-align: center; margin-top: 15px;">
							<button class="btn  rounded-pill btn-success" id="btn_rehusar" onclick="guardar();">Guardar</button>
							<button class="btn  rounded-pill btn-danger" id="btn_cancelar" hidden onclick="cancelar();">Cancelar</button>
						</div>
					</div>
					<h5 style="font-weight: bold;">Lista de Áreas Registrados</h5>
					<div class="form-row" id="div_areas" style="margin-top: 15px;">

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
	});

	function get_all_areas() {
		$("#div_areas").empty();
		$("#div_formulario").removeAttr("hidden");

		$.post('core/app/view/colaborador.php?parAccion=get_all_areas', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#div_areas").append(`
					<div class="card col-md-12" style="margin-top: 10px; padding: 0px !important;">
  						<div class="card-header"  style="font-weight: bold;">
    						` + val.area + `
    						<span class="btn btn-outline-warning btn-sm rounded-pill btn_accion" style="right: 40px;" onclick="editar(` + val.id + `);"><i class="glyphicon glyphicon-pencil"></i></span>
    						<span class="btn btn-outline-danger btn-sm rounded-pill btn_accion" onclick="eliminar(` + val.id + `);"><i class="fa fa-trash"></i></span>
  						</div>
					</div>
				`);
			});
		});
	}

	function editar(id) {
		$.post('core/app/view/colaborador.php?parAccion=editar_area', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$("#area").val(obj.area);

			$("#area").focus();

			$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
			$("#btn_cancelar").removeAttr('hidden');
		});
	}

	function actualizar(id) {
		$.post('core/app/view/colaborador.php?parAccion=actualizar_area', {
			area: $("#area").val(),
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

	function cancelar() {
		$("#area").val("");
		$("#btn_rehusar").attr('onclick', 'guardar();');
		$("#btn_cancelar").attr('hidden', true);
	}

	function eliminar(id) {
		$.post('core/app/view/colaborador.php?parAccion=eliminar_area', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Eliminado correctamente.</strong>' +
						'</div>'
				});
				get_all_areas();
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
		$("#area").val("");
	}

	function guardar() {
		$.post('core/app/view/colaborador.php?parAccion=guardar_area', {
			area: $("#area").val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_all_areas();
				limpiar_formulario();
			}
		});
	}
</script>