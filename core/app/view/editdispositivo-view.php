<section class="content">
	<?php
	$maquina_id = $_GET['mid'];
	$maquina = DispositivosData::getById($maquina_id);
	?>
	<div class="row">
		<div class="col-md-12">
			<h3>
				Editar Dispositivo/Accesorio
			</h3>
			<div class="box box-primary">
				<table class="table">
					<tr>
						<td>
							<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=updatedispositivo">
								<input type="hidden" name="id" value="<?php echo $maquina->id; ?>" />

								<div class="form-row" style="margin-bottom: 20px; display: flex;">
									<div class="col-md-6">
										<label for="inputEmail1" class="control-label">Imagen</label>
										<div class="col-md-12">
											<input type="file" name="image" id="image">
										</div>
									</div>
									<div class="col-md-6">
										<div style="position: relative;">
											<img id="image_muestra" src="storage/dispositivos/<?php echo $maquina->imagen; ?>" style="width:94px;" />
											<input type="hidden" name="img_oculto" id="img_oculto" value="<?php echo $maquina->imagen; ?>">
											<span onclick="quitar_foto();" role="button" class="btn btn-danger btn-xs" style="position: absolute; top: 5px; left: 5px;"><i class="fa fa-trash"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Codigo*</label>
									<div class="col-md-6">
										<input type="text" name="codigo" required id="codigo" class="form-control rounded-pill" id="barcode" value="<?php echo $maquina->codigo; ?>">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Descripcion*</label>
									<div class="col-md-6">
										<input type="text" name="descripcion" required id="descripcion" class="form-control rounded-pill" id="barcode" value="<?php echo $maquina->descripcion; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Responsable</label>
									<div class="col-md-6">
										<input type="text" name="responsable" id="responsable" class="form-control rounded-pill" value="<?php echo $maquina->responsable; ?>">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Fecha</label>
									<div class="col-md-6">
										<input type="date" name="fecha" id="fecha" class="form-control rounded-pill" value="<?php echo $maquina->fecha; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
									<div class="col-md-6">
										<input type="text" name="cantidad" class="form-control rounded-pill" id="cantidad" value="<?php echo $maquina->cantidad; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Observaciones</label>
									<div class="col-md-6">
										<input type="text" name="observaciones" class="form-control rounded-pill" id="observaciones" value="<?php echo $maquina->observaciones; ?>">
									</div>
								</div>

								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" class="btn btn-primary rounded-pill">Actualizar</button>
									</div>
								</div>
							</form>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.full.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet" />
<script>
	function quitar_foto() {
		$("#image_muestra").attr('src', '');
		$("#img_oculto").val("");
	}

	$(document).ready(function() {
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#image_muestra").attr("src", e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}

		$("#image").change(function() {
			readURL(this);
		});

		$(".datepicker").datetimepicker({
			format: "d-m-Y",
			timepicker: false
		});
		$.datetimepicker.setLocale('es');
	});
</script>