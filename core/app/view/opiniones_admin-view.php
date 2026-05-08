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

	.mb-3 {
		margin-bottom: 1rem !important;
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
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3><i class="glyphicon glyphicon-stats"></i> Opinion de nuestros clientes</h3>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">
					<fieldset>
						<legend>Filtro Rapido</legend>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="modelo">Cliente</label>
								<select class="form-control rounded-pill" id="id_cliente" name="id_cliente">
									<option value="0">SELECCIONA...</option>
								</select>
							</div>
							<div class="form-group col-md-12 text-center">
								<button class="btn btn-success rounded-pill" onclick="buscar_por_modelo();"> Filtrar
								</button>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="box-body">
					<div class="form-row" id="div_opiniones">

					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {
		get_all_opiniones();
		fill_cliente();

		$("#id_cliente").on('change', function() {
			get_opiniones($("#id_cliente").val());
		});
	});

	function fill_cliente() {
		$.post('core/app/view/opiniones.php?parAccion=fill_cliente', function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#id_cliente").append(`<option value="` + val.id + `">` + val.name + `</option>`);
			});
		});
	}

	function get_opiniones(id_cliente) {
		$("#div_opiniones").empty();
		$.post('core/app/view/opiniones.php?parAccion=get_mi_opiniones', {
			id_cliente: id_cliente
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				var border = "";
				var text = "";
				var clase = "";

				if (val.estado == 1) {
					border = "border-success";
					text = "text-success";
					clase = "success";
				} else if (val.estado == 2) {
					border = "border-warning";
					text = "text-warning";
					clase = "warning";
				} else if (val.estado == 3) {
					border = "border-danger";
					text = "text-danger";
					clase = "danger";
				}

				$("#div_opiniones").append(`<div class="card border-` + clase + ` mb-3 col-md-4 ml-1 mr-1" style="max-width: 20rem;">
  					<div class="card-header">` + val.name + `<small style="display: block; width: 100%;">` + val.fecha + `</small></div>
  						<div class="card-body text-` + clase + `">
								<h4 class="card-title">` + val.pedido + `</h4>
    						<p class="card-text">` + val.opinion + `</p>
  						</div>
					</div>`);
			});
		});
	}

	function get_all_opiniones() {
		$.post('core/app/view/opiniones.php?parAccion=get_all_opiniones', function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				var border = "";
				var text = "";
				var clase = "";

				if (val.estado == 1) {
					border = "border-success";
					text = "text-success";
					clase = "success";
				} else if (val.estado == 2) {
					border = "border-warning";
					text = "text-warning";
					clase = "warning";
				} else if (val.estado == 3) {
					border = "border-danger";
					text = "text-danger";
					clase = "danger";
				}

				$("#div_opiniones").append(`<div class="card border-` + clase + ` mb-3 col-md-4 ml-1 mr-1" style="max-width: 20rem;">
  					<div class="card-header">` + val.name + `<small style="display: block; width: 100%;">` + val.fecha + `</small></div>
  						<div class="card-body text-` + clase + `">
								<h4 class="card-title">` + val.pedido + `</h4>
    						<p class="card-text">` + val.opinion + `</p>
  						</div>
					</div>`);
			});
		});
	}
</script>