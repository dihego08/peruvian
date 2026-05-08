<section class="content">
	<?php

	$maquina_id = $_GET['mid'];
	$maquina = MaquinaData::getById($maquina_id);
	?>
	<div class="row">
		<div class="col-md-12">
			<h3>
				Editar Maquina
			</h3>
			<div class="box box-primary">
				<table class="table">
					<tr>
						<td>
							<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=updatemaquina">
								<input type="hidden" name="id" value="<?php echo $maquina->maquina_id; ?>" />
								<div class="form-group">
									<label class="col-lg-3 control-label">Ubicacion</label>
									<div class="col-md-6">
										<select name="ubicacion" class="form-control rounded-pill" id="ubicacion">
											<option value="Makitex" <?php if ($maquina->maquina_ubicacion == "Makitex") echo "selected" ?>>Makitex</option>
											<option value="Jerusalen" <?php if ($maquina->maquina_ubicacion == "Jerusalen") echo "selected" ?>>Jerusalen</option>
											<option value="Linea 1" <?php if ($maquina->maquina_ubicacion == "Linea 1") echo "selected" ?>>Línea 1</option>
											<option value="Linea 2" <?php if ($maquina->maquina_ubicacion == "Linea 2") echo "selected" ?>>Línea 2</option>
											<option value="Otros" <?php if ($maquina->maquina_ubicacion == "Otros") echo "selected" ?>>Otros</option>
										</select>

									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Tipo</label>
									<div class="col-md-6">
										<select name="tipo" class="form-control rounded-pill" id="tipo">
											<option value="CR1" <?php if ($maquina->maquina_tipo == "CR1") echo "selected" ?>>CR1</option>
											<option value="RC3" <?php if ($maquina->maquina_tipo == "RC3") echo "selected" ?>>RC3</option>
											<option value="RM4" <?php if ($maquina->maquina_tipo == "RM4") echo "selected" ?>>RM4</option>
											<option value="RM5" <?php if ($maquina->maquina_tipo == "RM5") echo "selected" ?>>RM5</option>
											<option value="BAL" <?php if ($maquina->maquina_tipo == "BAL") echo "selected" ?>>BAL</option>
											<option value="BOT" <?php if ($maquina->maquina_tipo == "BOT") echo "selected" ?>>BOT</option>
											<option value="CC1" <?php if ($maquina->maquina_tipo == "CC1") echo "selected" ?>>CC1</option>
											<option value="COP" <?php if ($maquina->maquina_tipo == "COP") echo "selected" ?>>COP</option>
											<option value="CRT" <?php if ($maquina->maquina_tipo == "CRT") echo "selected" ?>>CRT</option>
											<option value="DC1" <?php if ($maquina->maquina_tipo == "DC1") echo "selected" ?>>DC1</option>
											<option value="EM1" <?php if ($maquina->maquina_tipo == "EM1") echo "selected" ?>>EM1</option>
											<option value="ETP" <?php if ($maquina->maquina_tipo == "ETP") echo "selected" ?>>ETP</option>
											<option value="FUS" <?php if ($maquina->maquina_tipo == "FUS") echo "selected" ?>>FUS</option>
											<option value="MTG" <?php if ($maquina->maquina_tipo == "MTG") echo "selected" ?>>MTG</option>
											<option value="OJAL" <?php if ($maquina->maquina_tipo == "OJAL") echo "selected" ?>>OJAL</option>
											<option value="PLV" <?php if ($maquina->maquina_tipo == "PLV") echo "selected" ?>>PLV</option>
											<option value="PLD" <?php if ($maquina->maquina_tipo == "PLD") echo "selected" ?>>PLD</option>
											<option value="TPT" <?php if ($maquina->maquina_tipo == "TPT") echo "selected" ?>>TPT</option>

										</select>
									</div>
								</div>

								<div class="form-row" style="margin-bottom: 20px; display: flex;">
									<div class="col-md-6">
										<label for="inputEmail1" class="control-label">Imagen</label>
										<div class="col-md-12">
											<input type="file" name="image" id="image" placeholder="">
										</div>
									</div>
									<div class="col-md-6">
										<div style="position: relative;">
											<img id="image_muestra" src="storage/maquinas/<?php echo $maquina->maquina_imagen; ?>" style="width:94px;" />
											<input type="hidden" name="img_oculto" id="img_oculto" value="<?php echo $maquina->maquina_imagen; ?>">
											<span onclick="quitar_foto();" role="button" class="btn btn-danger btn-xs" style="position: absolute; top: 5px; left: 5px;"><i class="fa fa-trash"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Codigo*</label>
									<div class="col-md-6">
										<input type="text" name="codigo" required id="codigo" class="form-control rounded-pill" id="barcode" placeholder="" value="<?php echo $maquina->maquina_codigo; ?>">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Descripcion*</label>
									<div class="col-md-6">
										<input type="text" name="descripcion" required id="descripcion" class="form-control rounded-pill" id="barcode" placeholder="" value="<?php echo $maquina->maquina_descripcion; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Marca*</label>
									<div class="col-md-6">
										<input type="text" name="marca" required class="form-control rounded-pill" id="marca" placeholder="" value="<?php echo $maquina->maquina_marca; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Modelo</label>
									<div class="col-md-6">
										<input type="text" name="modelo" class="form-control rounded-pill" id="modelo" placeholder="" value="<?php echo $maquina->maquina_modelo; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Serie</label>
									<div class="col-md-6">
										<input type="text" name="serie" class="form-control rounded-pill" id="serie" placeholder="" value="<?php echo $maquina->maquina_serie; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Marca Motor</label>
									<div class="col-md-6">
										<input type="text" name="marca_motor" class="form-control rounded-pill" id="marca_motor" placeholder="" value="<?php echo $maquina->maquina_marca_motor; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Serie Motor</label>
									<div class="col-md-6">
										<input type="text" name="serie_motor" class="form-control rounded-pill" id="serie_motor" placeholder="" value="<?php echo $maquina->maquina_serie_motor; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Medidas para Espacio</label>
									<div class="col-md-6">
										<input type="text" name="exigencias" class="form-control rounded-pill" id="exigencias" placeholder="" value="<?php echo $maquina->maquina_exigencias; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Voltaje</label>
									<div class="col-md-6">
										<input type="text" name="voltaje" class="form-control rounded-pill" id="voltaje" placeholder="" value="<?php echo $maquina->maquina_voltaje; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Tipo de Corriente</label>
									<div class="col-md-6">
										<input type="text" name="tipo_corriente" class="form-control rounded-pill" id="tipo_corriente" placeholder="" value="<?php echo $maquina->maquina_tipo_corriente; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Año de Compra</label>
									<div class="col-md-6">
										<input type="text" name="anio_compra" class="form-control rounded-pill datepicker" id="anio_compra" placeholder="" value="<?php echo $maquina->maquina_anio_compra; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Vida Util</label>
									<div class="col-md-6">
										<input type="text" name="vida_util" class="form-control rounded-pill datepicker" id="vida_util" placeholder="" value="<?php echo $maquina->maquina_vida_util; ?>">
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
									<label for="inputEmail1" class="col-lg-3 control-label">Precio de Compra</label>
									<div class="col-md-6">
										<input type="text" name="precio_compra" class="form-control rounded-pill" id="precio_compra" value="<?php echo $maquina->precio_compra; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Proveedor</label>
									<div class="col-md-6">
										<input type="text" name="proveedor" class="form-control rounded-pill" id="proveedor" value="<?php echo $maquina->proveedor; ?>">
									</div>
								</div>
								<div class="form-row" style="margin-bottom: 20px; display: flex;">
									<div class="col-md-6">
										<label for="inputEmail1" class="control-label">Factura Compra</label>
										<div class="col-md-12">
											<input type="file" name="image_factura" id="image_factura">
										</div>
									</div>
									<div class="col-md-6">
										<div style="position: relative;">
											<a href="storage/maquinas/<?php echo $maquina->factura_compra; ?>" target="_blank">
												<img id="image_muestra_factura" src="storage/maquinas/<?php echo $maquina->factura_compra; ?>" style="width:94px;" />
											</a>
											<input type="hidden" name="img_oculto_factura" id="img_oculto_factura" value="<?php echo $maquina->factura_compra; ?>">
											<span onclick="quitar_foto_factura();" role="button" class="btn btn-danger btn-xs" style="position: absolute; top: 5px; left: 5px;"><i class="fa fa-trash"></i></span>
										</div>
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

	function quitar_foto_factura() {
		$("#image_muestra_factura").attr('src', '');
		$("#img_oculto_factura").val("");
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

		function readURL_f(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#image_muestra_factura").attr("src", e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		$("#image").change(function() {
			readURL(this);
		});

		$("#image_factura").change(function() {
			readURL_f(this);
		});


		$(".datepicker").datetimepicker({
			format: "d-m-Y",
			timepicker: false
		});
		$.datetimepicker.setLocale('es');
	});
</script>