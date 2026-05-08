<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>
				Nuevo Dispositivo/Accesorio
			</h3>
			<div class="box box-primary">
				<table class="table">
					<tr>
						<td>
							<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=adddispositivo">

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Imagen</label>
									<div class="col-md-6">
										<input type="file" name="image" id="image">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Codigo*</label>
									<div class="col-md-6">
										<input type="text" name="codigo" id="codigo" class="form-control rounded-pill">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Descripcion*</label>
									<div class="col-md-6">
										<input type="text" name="descripcion" required id="descripcion" class="form-control rounded-pill">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Responsable</label>
									<div class="col-md-6">
										<input type="text" name="responsable" id="responsable" class="form-control rounded-pill">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Fecha</label>
									<div class="col-md-6">
										<input type="date" name="fecha" id="fecha" class="form-control rounded-pill">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
									<div class="col-md-6">
										<input type="text" name="cantidad" id="cantidad" class="form-control rounded-pill">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Observaciones</label>
									<div class="col-md-6">
										<textarea name="observaciones" id="observaciones" cols="30" rows="10" class="form-control"></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" class="btn btn-primary rounded-pill">Agregar Dispositivo/Accesorio</button>
									</div>
								</div>
							</form>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="modal fade" id="formulario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document" style="width: 30%;">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<h3 class="modal-title" id="exampleModalLabel">Añadir</h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label for="">Tipo de Máquina</label>
							<input type="text" class="form-control rounded-pill" id="tipo_maquina">
						</div>
					</div>
				</div>
				<div class="modal-footer txt-left">
					<span class="btn btn-danger" type="button" data-dismiss="modal">
						Cerrar
					</span>
					<span class="btn btn-success" onclick="guardar();">Guardar</span>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
	$(document).ready(function() {
		get_tipos_maquinas();
	});

	function get_tipos_maquinas() {
		$.post("core/app/view/maquinas.php?parAccion=get_tipos_maquinas", function(response) {
			var obj = JSON.parse(response);
			$("#tipo").empty();
			$.each(obj, function(index, val) {
				$("#tipo").append(`<option value="${val.tipo_maquina}">${val.tipo_maquina}</option>`);
			});
		});
	}

	function guardar() {
		$.post("core/app/view/maquinas.php?parAccion=guardar_tipos_maquinas", {
			tipo_maquina: $("#tipo_maquina").val()
		}, function(response) {
			var obj = JSON.parse(response);
			if (obj.Result == "OK") {
				$("#formulario").modal("hide");
				get_tipos_maquinas();
			}
		})
	}
</script>