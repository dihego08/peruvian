<section class="content">
	<?php
	$product = ProductData::getById($_GET["id"]);
	$categories = CategoryData::getAll();
	$person = PersonData::getAll();

	if ($product != null) :
	?>
		<div class="row">
			<div class="col-md-12">
				<h3><?php echo $product->name ?> <small>Editar Producto</small></h3>
				<?php if (isset($_COOKIE["prdupd"])) : ?>
					<p class="alert alert-info">La informacion del producto se ha actualizado exitosamente.</p>
				<?php setcookie("prdupd", "", time() - 18600);
				endif; ?>
				<br>
				<div class="box box-primary">
					<table class="table">
						<tr>
							<td>
								<form class="form-horizontal" method="post" id="addproduct" enctype="multipart/form-data" action="index.php?view=updateproduct" role="form">

									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Cliente</label>
										<div class="col-md-6">
											<select name="cliente_id" class="form-control rounded-pill">
												<option value="">-- NINGUNA --</option>
												<?php foreach ($person as $cliente) : ?>
													<option value="<?php echo $cliente->id; ?>" <?php if ($product->cliente_id != null && $product->cliente_id == $cliente->id) {
																									echo "selected";
																								} ?>><?php echo $cliente->name; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>


									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Imagen*</label>
										<div class="col-md-6">
											<input type="file" name="image" id="name" placeholder="">
											<?php if ($product->image != "") : ?>
												<br>
												<a href="#" class="btn-xs btn-danger" onclick="eliminar_imagen(<?php echo $product->id; ?>, 'imagen');" style="float: right; width: 100%; margin-bottom: 5px;"><i class="fa fa-trash"></i> Eliminar</a>
												<img src="storage/products/<?php echo $product->image; ?>" class="img-responsive">
											<?php endif; ?>
										</div>
									</div>

									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Modelo*</label>
										<div class="col-md-6">
											<input type="text" name="code" class="form-control rounded-pill" id="code" value="<?php echo $product->code; ?>" placeholder="Codigo Interno del Producto">
										</div>
									</div>


									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Codigo de barras*</label>
										<div class="col-md-6">
											<input type="text" name="barcode" class="form-control rounded-pill" id="barcode" value="<?php echo $product->barcode; ?>" placeholder="Codigo de barras del Producto">
										</div>
									</div>
									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Nombre*</label>
										<div class="col-md-6">
											<input type="text" name="name" class="form-control rounded-pill" id="name" value="<?php echo $product->name; ?>" placeholder="Nombre del Producto">
										</div>
									</div>
									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Marca</label>
										<div class="col-md-6">
											<select name="brand_id" class="form-control rounded-pill">
												<option value="">-- NINGUNA --</option>
												<?php foreach (BrandData::getAll() as $category) : ?>
													<option value="<?php echo $category->id; ?>" <?php if ($product->brand_id != null && $product->brand_id == $category->id) {
																										echo "selected";
																									} ?>><?php echo $category->name; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Descripcion</label>
										<div class="col-md-6">
											<textarea name="description" class="form-control" id="description" placeholder="Descripcion del Producto"><?php echo $product->description; ?></textarea>
										</div>
									</div>

									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Precio de Confeccion Mínimo*</label>
										<div class="col-md-6">
											<input type="text" name="price_in" class="form-control rounded-pill" value="<?php echo $product->price_in; ?>" id="price_in" placeholder="Precio de entrada">
										</div>
									</div>
									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Precio de Confeccion Máximo</label>
										<div class="col-md-6">
											<input type="text" name="price_in_2" class="form-control rounded-pill" value="<?php echo $product->price_in_2; ?>" id="price_in_2" placeholder="Precio de entrada">
										</div>
									</div>
									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Unidad*</label>
										<div class="col-md-6">
											<input type="text" name="unit" class="form-control rounded-pill" id="unit" value="<?php echo $product->unit; ?>" placeholder="Unidad del Producto">
										</div>
									</div>
									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Presentacion</label>
										<div class="col-md-6">
											<input type="text" name="presentation" class="form-control rounded-pill" id="inputEmail1" value="<?php echo $product->presentation; ?>" placeholder="Presentacion del Producto">
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
											<input type="text" name="width" value="<?php echo $product->width; ?>" class="form-control rounded-pill" placeholder="Ancho">
										</div>
										<div class="col-md-2">
											<label class="control-label">Altura*</label>
											<input type="text" name="height" value="<?php echo $product->height; ?>" class="form-control rounded-pill" placeholder="Altura">
										</div>
										<div class="col-md-2">
											<label class="control-label">Peso*</label>
											<input type="text" name="weight" value="<?php echo $product->weight; ?>" class="form-control rounded-pill" placeholder="Peso">
										</div>
									</div>
									<?php
									if ($product->kind == 2) {
									} else {
										echo "<div class=\"form-group\">
                          <label for=\"inputEmail1\" class=\"col-lg-3 control-label\">Minima en inventario:</label>
                          <div class=\"col-md-6\">
                            <input type=\"text\" name=\"inventary_min\" class=\"form-control rounded-pill\" value=" . $product->inventary_min . " id=\"inputEmail1\" placeholder=\"Minima en Inventario (Default 10)\">
                          </div>
                          </div>";
									}
									?>
									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Imagen bordado*</label>
										<div class="col-md-6">
											<input type="file" name="imgBordado" id="imgBordado" placeholder="">
											<?php if ($product->imgbordado != "") : ?>
												<br>
												<a href="#" class="btn-xs btn-danger" onclick="eliminar_imagen(<?php echo $product->id; ?>, 'bordado');" style="float: right; width: 100%; margin-bottom: 5px;"><i class="fa fa-trash"></i> Eliminar</a>
												<img src="storage/products/<?php echo $product->imgbordado; ?>" class="img-responsive">
											<?php endif; ?>
										</div>
									</div>

									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Precio de bordado</label>
										<div class="col-md-6">
											<input type="text" name="pre_bor_in" class="form-control rounded-pill" id="pre_bor_in" value="<?php echo $product->prebor_in; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Precio de bordado salida</label>
										<div class="col-md-6">
											<input type="text" name="pre_bor_out" class="form-control rounded-pill" id="pre_bor_out" value="<?php echo $product->prebor_out; ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Fecha de actualización</label>
										<div class="col-md-6">
											<input type="date" name="fecact" class="form-control rounded-pill" id="fecact" value="<?php echo $product->fecact; ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Esta activo</label>
										<div class="col-md-6">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="is_active" <?php if ($product->is_active) {
																								echo "checked";
																							} ?>>
												</label>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="inputEmail1" class="col-lg-3 control-label">Hoja de Secuencia</label>
										<div class="col-md-6">
											<input type="file" name="secuencia" id="secuencia">
											<a href="storage/secuencias/<?php echo $product->secuencia; ?>" target="_blank"><?php echo $product->secuencia; ?></a>
										</div>
									</div>

									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-8">
											<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
											<button type="submit" class="btn btn-success rounded-pill">Actualizar Producto</button>
										</div>
									</div>
								</form>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<script type="text/javascript">
		function eliminar_imagen(id, tipo) {
			if (tipo == 'imagen') {
				$.get('core/app/view/order.php', {
					parAccion: 'eliminar_imagen',
					id: id
				}, function(data) {
					var obj = JSON.parse(data);
					if (obj.Result == 'OK') {
						bootbox.alert({
							message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
								'<strong>Imagen Borrada correctamente.</strong>' +
								'</div>'
						});
						location.reload();
					} else {
						bootbox.alert({
							message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
								'<strong>Algo ha salido mal.</strong>' +
								'</div>'
						});
					}
				});
			} else {
				if (tipo == 'bordado') {
					$.get('core/app/view/order.php', {
						parAccion: 'eliminar_bordado',
						id: id
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == 'OK') {
							bootbox.alert({
								message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Imagen Bordado Borrada correctamente.</strong>' +
									'</div>'
							});
							location.reload();
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Algo ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				}
			}
		}
	</script>
</section>