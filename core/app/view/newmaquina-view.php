<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>
				Nueva Maquina
			</h3>
			<div class="box box-primary">
				<table class="table">
					<tr>
						<td>
							<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=addmaquina">
								<div class="form-group">
									<label class="col-lg-3 control-label">Ubicacion</label>
									<div class="col-md-6">
										<select name="ubicacion" class="form-control rounded-pill" id="ubicacion">
											<option value="Makitex">Makitex</option>
											<option value="Jerusalen">Jerusalen</option>
											<option value="Linea 1">Línea 1</option>
											<option value="Linea 2">Línea 2</option>
											<option value="Otros">Otros</option>
										</select>

									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Tipo</label>
									<div class="col-md-6">
										<select name="tipo" class="form-control rounded-pill" id="tipo">
										</select>
									</div>
									<div class="col-3">
										<span class="btn btn-outline-success rounded-pill" data-toggle="modal" data-target="#formulario"><i class="fa fa-plus"></i></span>
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Imagen</label>
									<div class="col-md-6">
										<input type="file" name="image" id="image" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Codigo*</label>
									<div class="col-md-6">
										<input type="text" name="codigo" required id="codigo" class="form-control rounded-pill" id="barcode" placeholder="">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Descripcion*</label>
									<div class="col-md-6">
										<input type="text" name="descripcion" required id="descripcion" class="form-control rounded-pill" id="barcode" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Marca*</label>
									<div class="col-md-6">
										<input type="text" name="marca" required class="form-control rounded-pill" id="marca" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Modelo</label>
									<div class="col-md-6">
										<input type="text" name="modelo" class="form-control rounded-pill" id="modelo" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Serie</label>
									<div class="col-md-6">
										<input type="text" name="serie" class="form-control rounded-pill" id="serie" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Marca Motor</label>
									<div class="col-md-6">
										<input type="text" name="marca_motor" class="form-control rounded-pill" id="marca_motor" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Serie Motor</label>
									<div class="col-md-6">
										<input type="text" name="serie_motor" class="form-control rounded-pill" id="serie_motor" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Medidas para Espacio</label>
									<div class="col-md-6">
										<input type="text" name="exigencias" class="form-control rounded-pill" id="exigencias" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Voltaje</label>
									<div class="col-md-6">
										<input type="text" name="voltaje" class="form-control rounded-pill" id="voltaje" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Tipo de Corriente</label>
									<div class="col-md-6">
										<input type="text" name="tipo_corriente" class="form-control rounded-pill" id="tipo_corriente" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Año de Compra</label>
									<div class="col-md-6">
										<input type="text" name="anio_compra" class="form-control rounded-pill" id="anio_compra" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Vida Util</label>
									<div class="col-md-6">
										<input type="text" name="vida_util" class="form-control rounded-pill" id="vida_util" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Estado</label>
									<div class="col-md-6">
										<select name="estado" class="form-control rounded-pill" id="estado">
											<option value="1">Activo</option>
											<option value="0">Baja</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Factura Compra</label>
									<div class="col-md-6">
										<input type="file" name="image_factura" id="image_factura" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Precio de Compra</label>
									<div class="col-md-6">
										<input type="text" name="precio_compra" class="form-control rounded-pill" id="precio_compra" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Proveedor</label>
									<div class="col-md-6">
										<input type="text" name="proveedor" class="form-control rounded-pill" id="proveedor" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" class="btn btn-primary rounded-pill">Agregar Maquina</button>
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
					<span class="btn btn-danger rounded-pill" type="button" data-dismiss="modal">
						Cerrar
					</span>
					<span class="btn btn-success rounded-pill" onclick="guardar();">Guardar</span>
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