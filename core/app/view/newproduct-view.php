<section class="content">
	<?php

	$currency = ConfigurationData::getByPreffix("currency")->val;
	$categories = CategoryData::getAll();
	$person = PersonData::getAll();

	?>
	<div class="row">
		<div class="col-md-12">
			<h3>
				Nuevo Producto
			</h3>
			<br>
			<div class="box box-primary">
				<table class="table">
					<tr>
						<td>
							<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=addproduct" role="form">
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Tipo</label>
									<div class="col-md-6">
										<select name="kind" class="form-control rounded-pill" id="kind">
											<option value="1">Producto</option>
											<option value="2">Servicio</option>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Cliente</label>
									<div class="col-md-6">
										<select name="cliente_id" class="form-control rounded-pill">
											<option value="">-- NINGUNA --</option>
											<?php foreach ($person as $clientes) : ?>
												<option value="<?php echo $clientes->id; ?>" <?php if ($_GET['cid'] != "" && $clientes->id == $_GET['cid']) {
																									echo "selected";
																								} ?>>
													<?php echo $clientes->name; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Imagen</label>
									<div class="col-md-6">
										<input type="file" name="image" id="image" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Modelo*</label>
									<div class="col-md-6">
										<input type="text" name="code" id="product_code" class="form-control rounded-pill" id="barcode" placeholder="Modelo">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Codigo de Barras*</label>
									<div class="col-md-6">
										<input type="text" name="barcode" id="product_code" class="form-control rounded-pill" id="barcode" placeholder="Codigo de Barras del Producto">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Nombre*</label>
									<div class="col-md-6">
										<input type="text" name="name" required class="form-control rounded-pill" id="name" placeholder="Nombre del Producto">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Marca</label>
									<div class="col-md-6">
										<select name="brand_id" class="form-control rounded-pill">
											<option value="">-- NINGUNA --</option>
											<?php foreach (BrandData::getAll() as $category) : ?>
												<option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Descripcion</label>
									<div class="col-md-6">
										<textarea name="description" class="form-control" id="description" placeholder="Descripcion del Producto"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Precio de confección Mínimo (<?php echo $currency; ?>)*</label>
									<div class="col-md-6">
										<input type="text" name="price_in" required class="form-control rounded-pill" id="price_in" placeholder="Precio de entrada">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Precio de confección Máximo (<?php echo $currency; ?>)*</label>
									<div class="col-md-6">
										<input type="text" name="price_in_2" required class="form-control rounded-pill" id="price_in_2" placeholder="Precio de entrada">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Unidad*</label>
									<div class="col-md-6">
										<input type="text" name="unit" class="form-control rounded-pill" id="unit" placeholder="Unidad del Producto">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Presentacion</label>
									<div class="col-md-6">
										<input type="text" name="presentation" class="form-control rounded-pill" id="inputEmail1" placeholder="Presentacion del Producto">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label"></label>

									<div class="col-md-2">
										<label class="control-label">Largo*</label>
										<input type="text" name="large" value="<?php echo $product->large; ?>" class="form-control rounded-pill" placeholder="Largo">
									</div>
									<div class="col-md-2">
										<label class="control-label">Anchura*</label>
										<input type="text" name="width" class="form-control rounded-pill" placeholder="Ancho">
									</div>
									<div class="col-md-2">
										<label class="control-label">Altura*</label>
										<input type="text" name="height" class="form-control rounded-pill" placeholder="Altura">
									</div>
									<div class="col-md-2">
										<label class="control-label">Peso*</label>
										<input type="text" name="weight" class="form-control rounded-pill" placeholder="Peso">
									</div>

								</div>

								<div class="form-group" id="div_stock">
									<label for="inputEmail1" class="col-lg-3 control-label">Stock Mínimo:</label>
									<div class="col-md-6">
										<input type="text" name="inventary_min" class="form-control rounded-pill" id="inputEmail1" placeholder="Stock Mínimo (Default 10)">
									</div>
								</div>


								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Inventario inicial:</label>
									<div class="col-md-6">
										<input type="text" name="q" class="form-control rounded-pill" id="inputEmail1" placeholder="Inventario inicial">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Imagen bordado</label>
									<div class="col-md-6">
										<input type="file" name="imgBordado" id="imgBordado" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Precio de bordado (<?php echo $currency; ?>)*</label>
									<div class="col-md-6">
										<input type="text" name="pre_bor_in" required class="form-control rounded-pill" id="pre_bor_in" placeholder="Precio de bordado">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Precio de bordado salida (<?php echo $currency; ?>)*</label>
									<div class="col-md-6">
										<input type="text" name="pre_bor_out" required class="form-control rounded-pill" id="pre_bor_out" placeholder="Precio de bordado salida">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Fecha de actualización</label>
									<div class="col-md-6">
										<input type="date" name="fecact" required class="form-control rounded-pill" id="fecact" placeholder="Fecha de actualizacion">
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail1" class="col-lg-3 control-label">Hoja de Secuencia</label>
									<div class="col-md-6">
										<input type="file" name="secuencia" id="secuencia" placeholder="">
									</div>
								</div>

								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" class="btn btn-success  rounded-pill">Agregar Producto</button>
									</div>
								</div>
							</form>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$("#product_code").keydown(function(e) {
				if (e.which == 17 || e.which == 74) {
					e.preventDefault();
				} else {
					console.log(e.which);
				}
			});
			$("#kind").on('change', function() {
				if ($("#kind").val() == 2) {
					$("#div_stock").attr('hidden', true);
				} else {
					$("#div_stock").attr('hidden', false);
				}
			});
		});
	</script>
</section>