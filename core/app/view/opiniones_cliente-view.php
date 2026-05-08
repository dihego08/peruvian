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
			<h3><i class="glyphicon glyphicon-stats"></i> Centro de Opiniones</h3>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">
					<fieldset>
						<legend>Registrar Opinion</legend>
						<div class="form-row">
							<div class="form-row">
								<div class="col-md-6">
									<label>Numero de Pedido</label>
									<input type="text" id="pedido" name="" class="form-control rounded-pill" placeholder="Numero de Pedido">
								</div>
								<div class="col-md-6">
									<label>Valoracion</label>
									<select class="form-control rounded-pill" id="estado">
										<option value="1">Buena</option>
										<option value="2">Intermedia</option>
										<option value="3">Mala</option>
									</select>
								</div>
								<div class="col-md-12" style="margin-top: 1rem;">
									<label>Opinion</label>
									<textarea class="form-control rounded-pill" id="opinion"></textarea>
								</div>
							</div>
							<div class="form-group col-md-12 text-center" style="margin-top: 1rem;">
								<button class="btn btn-success rounded-pill" onclick="enviar();"> Enviar
								</button>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="box-body">
					<h4>Mis opiniones anteriores</h4>
					<div class="form-row" id="div_opiniones">

					</div>
				</div>
			</div>
		</div>
	</div>

</section>
<script type="text/javascript">
	$(document).ready(function() {
		get_mi_opiniones();
	});

	function enviar() {
		$.post('core/app/view/opiniones.php?parAccion=save_mi_opinion', {
			id_cliente: <?php echo Core::$user->id; ?>,
			pedido: $("#pedido").val(),
			opinion: $("#opinion").val(),
			estado: $("#estado").val()
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: `<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">
                        <strong>Realizado correctamente.</strong>
                    </div>`
				});
				get_mi_opiniones();
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function get_mi_opiniones() {
		$("#div_opiniones").empty();
		$.post('core/app/view/opiniones.php?parAccion=get_mi_opiniones', {
			id_cliente: <?php echo Core::$user->id; ?>
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
</script>