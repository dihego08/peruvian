<style>
	.ui-autocomplete {
		position: absolute;
		cursor: default;
		z-index: 1001 !important
	}

	#v {
		width: 320px;
		height: 240px;
	}

	#qr-canvas {
		display: none;
	}

	#qrfile {
		width: 320px;
		height: 240px;
	}

	#mp1 {
		text-align: center;
		font-size: 35px;
	}

	#imghelp {
		position: relative;
		left: 0px;
		top: -160px;
		z-index: 100;
		font: 18px arial, sans-serif;
		background: #f0f0f0;
		margin-left: 35px;
		margin-right: 35px;
		padding-top: 10px;
		padding-bottom: 10px;
		border-radius: 20px;
	}

	#popup_editar {
		left: 0;
		position: absolute;
		top: 0;
		width: 100%;
		z-index: 2001;
	}

	.content-popup {
		margin: 0px auto;
		margin-top: 2%;
		position: relative;
		padding: 10px;
		width: 75%;
		/*min-height:250px;*/
		border-radius: 4px;
		background-color: #FFFFFF;
		box-shadow: 0 2px 5px #666666;
	}

	.content-popup h2 {
		color: #48484B;
		border-bottom: 1px solid #48484B;
		margin-top: 0;
		padding-bottom: 4px;
	}

	.popup-overlay {
		left: 0;
		position: absolute;
		top: 0;
		width: 100%;
		z-index: 999;
		display: none;
		background-color: #777777;
		cursor: pointer;
		opacity: 0.7;
	}

	.close {
		position: absolute;
		right: 15px;
	}

	@media (max-width: 600px) {
		select {
			width: 105px !important;
		}
	}

	.w-100 {
		width: 100% !important;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Cargos</h3>
			<div class="row">
				<div class="form-group col-md-6">
					<label for="pedido_orden">Cargo</label>
					<input type="text" name="cargo" id="cargo" class="form-control rounded-pill">
				</div>
				<div class="col-md-6">
					<label>Cliente Referencia</label>
					<select class="form-control rounded-pill" id="cliente" name="cliente">
						<option value="0">SELECCIONA ...</option>
					</select>
				</div>
				<div class="col-md-12 mt-3 text-center">
					<button class="btn btn-success rounded-pill" onclick="guardar_cargo();" id="btn_finalizar">Guardar Cargo</button>
				</div>
				<div id="resultado" style="padding: 25px 25px 0 25px;">
					<hr class="d-block w-100">
					<h3>Lista de Cargos Registrados</h3>
					<div class="box box-primary table-responsive">
						<table class="table table-bordered table-hover" id="tabla_resultado">
							<thead>
								<tr>
									<th>Codigo</th>
									<th>Cargo</th>
									<th>Cliente de Referencia</th>
									<th></th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			llenar_clientes();
			lista_cargos();
		});

		function lista_cargos() {
			$("#tabla_resultado").find('tbody').empty();
			$.get('core/app/view/cargos.php', {
				parAccion: "lista_cargos"
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					$("#tabla_resultado").find('tbody').append(`
					<tr>
						<td>` + val.id + `</td>
						<td>` + val.cargo + `</td>
						<td>` + val.cliente + `</td>
						<td>
							<span onclick="eliminar(` + val.id + `);" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa fa-trash"></i></span>
							<span onclick="editar(` + val.id + `);" class="btn btn-sm btn-outline-warning rounded-pill"><i class="fa fa-pencil"></i></span>
						</td>
					</tr>
				`);
				});
			});
		}

		function llenar_clientes() {
			$.get('core/app/view/cargos.php', {
				parAccion: "llenar_clientes"
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					$("#cliente").append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
			});
		}

		function guardar_cargo() {
			$.get('core/app/view/cargos.php', {
				parAccion: "guardar_cargo",
				cargo: $("#cargo").val(),
				id_referencia: $("#cliente").val()
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Cargo guardado correctamente.</strong>' +
							'</div>'
					});
					limpiar_formulario();
					lista_cargos();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function editar(id) {
			$.get('core/app/view/cargos.php', {
				parAccion: "editar",
				id: id
			}, function(data) {
				var obj = JSON.parse(data);
				$("#cargo").val(obj.cargo);
				$("#cliente option[value=" + obj.id_referencia + "]").prop('selected', true);

				$("#btn_finalizar").attr('onclick', 'actualizar_cargo(' + id + ')');
				$("#btn_finalizar").text('Actualizar Cargo');
				$("#cargo").focus();
			});
		}

		function limpiar_formulario() {
			$("#btn_finalizar").attr('onclick', 'guardar_cargo()');
			$("#cargo").val("");
			$("#cliente option[value=0]").prop('selected', true);
			$("#btn_finalizar").text('Guardar Cargo');
		}

		function actualizar_cargo(id) {
			$.get('core/app/view/cargos.php', {
				parAccion: "actualizar_cargo",
				cargo: $("#cargo").val(),
				id_referencia: $("#cliente").val(),
				id: id
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Cargo actualizado correctamente.</strong>' +
							'</div>'
					});
					limpiar_formulario();
					lista_cargos();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function eliminar(id) {
			$.get('core/app/view/cargos.php', {
				parAccion: "eliminar",
				id: id
			}, function(data) {
				if (obj.Result == "OK") {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Cargo eliminado correctamente.</strong>' +
							'</div>'
					});
					lista_cargos();
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

</section>