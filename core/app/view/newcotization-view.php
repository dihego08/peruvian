<?php
// $symbol = ConfigurationData::getByPreffix("currency")->val;
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;

?>
<style type="text/css">
	#popup_editar {
		left: 110px;
		position: absolute;
		/*top: 0;*/
		width: 100%;
		z-index: 1001;
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
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Nueva Cotizacion</h3>
			<p><b>Buscar producto por nombre o por codigo:</b></p>
			<!--<form id="searchp">-->
			<div class="row">
				<div class="col-md-6">
					<input type="hidden" name="view" value="newcotization">
					<input type="text" id="product_code" name="product" class="form-control rounded-pill">
				</div>
				<div class="col-md-3">
					<button class="btn btn-primary rounded-pill" onclick="buscar_productos();"><i class="glyphicon glyphicon-search"></i> Buscar</button>
				</div>
			</div>
			<!--</form>-->
			<div id="show_search_results" hidden>
				<h3>Resultados de la Busqueda</h3>
				<div class="box box-primary">
					<table class="table table-bordered table-hover" id="tabla_resultado_busqueda">
						<thead>
							<th colspan="2" style="text-align: center;">Codigo</th>
							<th>Nombre</th>
							<th>Unidad</th>
							<th>Precio unitario</th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
				<!--<p class='alert alert-warning'>Se omitieron <b>7 productos</b> que no tienen existencias en el inventario. <a href='index.php?view=inventary&stock=1'>Ir al Inventario</a></p>-->
				<button class="btn btn-danger rounded-pill">Cancelar</button>
				<button class="btn btn-success rounded-pill" onclick="continuar_cotizacion();">Continuar</button>
			</div>

			<div id="detalle_cotizacion" hidden>
				<h3>Detalle de la Cotización</h3>
				<div class="box box-primary" style="padding: 10px; overflow: hidden;">
					<form id="div_detalle_cotizacion" action="" method="post" enctype="multipart/form-data">

					</form>
				</div>
			</div>

			<script>
				function insertar_cotizacion(event, codigos) {

					event.preventDefault();
					var entrega_total = $("#entrega_total").val();
					var formData = new FormData($("#div_detalle_cotizacion")[0]);
					var codigos = codigos;
					var ruta = "core/app/view/cotizacion.php?parAccion=insertar_cotizacion&codigos=" + codigos + "&entrega=" + entrega_total;
					$.ajax({
						url: ruta,
						type: "POST",
						data: formData,
						contentType: false,
						processData: false,
						success: function(datos) {
							var obj = JSON.parse(datos);
							if (obj.Result == 'OK') {
								//limpiar_formulario();
								bootbox.alert({
									message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Agregado correctamente.</strong>' +
										'</div>'
								});
								//lista_empresas();
							} else {
								bootbox.alert({
									message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Algo ha salido mal.</strong>' +
										'</div>'
								});
							}
						}
					});
				}

				function continuar_cotizacion() {
					let valoresCheck = [];
					$("input[type=checkbox]:checked").each(function() {
						valoresCheck.push(this.value);
					});
					console.log(valoresCheck);
					$.get('core/app/view/cotizacion.php', {
						parAccion: 'detalle_para_cotizacion',
						codigos: valoresCheck
					}, function(data) {
						$("#detalle_cotizacion").removeAttr('hidden');
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							$("#div_detalle_cotizacion").append('<div class="box box-primary" style="overflow: hidden;"><h4>Producto ' + (index + 1) + '</h4>' +
								'<div class="form-group col-md-4">' +
								'<label>Producto</label>' +
								'<input type="text" name="producto_' + val.id + '" id="producto_' + val.id + '" class="form-control rounded-pill" value="' + val.name + '">' +
								'</div>' +
								'<div class="form-group col-md-4">' +
								'<label>Costo</label>' +
								'<input type="text" name="costo_' + val.id + '" id="costo_' + val.id + '" class="form-control rounded-pill" value="' + val.price_in + '">' +
								'</div>' +
								'<div class="form-group col-md-4">' +
								'<label>Cantidad</label>' +
								'<input type="text" name="cantidad_' + val.id + '" id="cantidad_' + val.id + '" class="form-control rounded-pill" >' +
								'</div>' +
								'<div class="form-group col-md-4"><div class="form-group" style="text-align: left;">' +
								'<label for="ruta_imagen">Cargar Imágenes</label>' +
								'<div class="input-group">' +
								'<input type="file" name="imagen_' + val.id + '" id="imagen_' + val.id + '">' +
								'</div>' +
								'</div><div class="form-group" style="text-align: left;">' +
								'<div class="input-group">' +
								'<input type="file" name="imagen_b_' + val.id + '" id="imagen_b_' + val.id + '">' +
								'</div>' +
								'</div></div>' +

								'<div class="form-group col-md-4" style="text-align: center;">' +
								'<label>Imagen Modelo</label>' +
								'<img src="storage/products/' + val.image + '" id="image_muestra_' + val.id + '" class="thumbnail center-block" style="width: 150px;">' +
								'<input type="hidden" id="img_m_' + val.id + '" name="img_m_' + val.id + '" value="' + val.image + '">' +
								`<span class="btn btn-danger" onclick="quitar_foto(` + val.id + `);" style="cursor: pointer; position: absolute; top: 30px; right: 110px;"><i class="fa fa-trash"></i></span>` +
								'</div>' +
								'<div class="form-group col-md-4" style="text-align: center;">' +
								'<label>Imagen Bordado</label>' +
								'<img src="storage/products/' + val.imgbordado + '" id="image_muestra_bordado_' + val.id + '" class="thumbnail center-block" style="width: 150px;">' +
								'<input type="hidden" id="img_b_' + val.id + '" name="img_b_' + val.id + '" value="' + val.imgbordado + '">' +
								`<span class="btn btn-danger" onclick="quitar_foto_bordado(` + val.id + `);" style="cursor: pointer; position: absolute; top: 30px; right: 110px;"><i class="fa fa-trash"></i></span>` +
								'</div>' +
								'<!--<div style="width: 100%; text-align: center;">' +
								'<img id=i2mg-upload" style="width: 50%;" />' +
								'</div>-->' +
								'<div class="form-group col-md-12">' +
								'<label>Descripción</label>' +
								'<textarea class="form-control rounded-pill" name="descripcion_' + val.id + '">' + val.description + '</textarea>' +
								'</div>');
							$("#imagen_b_" + val.id).change(function() {
								readURL(this, val.id);
							});
							$("#imagen_" + val.id).change(function() {
								readURL_(this, val.id);
							});
						});
						$("#div_detalle_cotizacion").append('<div class="form-group col-md-6">' +
							'<label>Tiempo de Entrega</label>' +
							'<input type="text" name="entrega_total" id="entrega_total" class="form-control rounded-pill">' +
							'</div>' +
							'<div class="form-group col-md-6">' +
							'<label>¿Aplicar IGV?</label>' +
							'<div>' +
							'<input type="radio" id="no" name="igv" value="no" checked>' +
							' <label for="huey"> No</label>' +
							'</div>' +
							'<div>' +
							'<input type="radio" id="yes" name="igv" value="yes">' +
							' <label for="dewey"> Sí</label>' +
							'</div>' +
							'</div>' +
							'<div class="form-group col-md-6">' +
							'<label>Cliente</label>' +
							'<select class="form-control rounded-pill" id="c_cliente" name="c_cliente"></select>' +
							'</div>' +
							'<div class="form-group col-md-6">' +
							'<label>Cliente +</label>' +
							'<input type="text" id="x_cliente" name="x_cliente" class="form-control rounded-pill" />' +
							'</div>' +
							'<div class="form-group col-md-6">' +
							'<label>Obervación</label>' +
							'<textarea class="form-control rounded-pill" id="txt_observacion" name="txt_observacion"></textarea>' +
							'</div>' +
							'<div class="form-group col-md-6">' +
							'<label>Servicios</label>' +
							'<textarea class="form-control rounded-pill" name="servicios" id="servicios"></textarea>' +
							'</div></div>' +
							`<div class="col-md-4">
						<label for="">Validez de la Oferta</label>
						<input type="text" id="validez" name="validez" class="form-control rounded-pill" />
					</div>
					<div class="col-md-4">
						<label for="">Forma de Pago</label>
						<input type="text" id="forma_pago" name="forma_pago" class="form-control rounded-pill" />
					</div>
					<div class="col-md-4">
						<label for="">Tallas Especiales</label>
						<input type="text" id="tallas_especiales" name="tallas_especiales" class="form-control rounded-pill" />
					</div>
					<div class="col-md-6">
						<label for="">Asesor Comercial</label>
						<input type="text" id="asesor_comercial" name="asesor_comercial" class="form-control rounded-pill" />
					</div>
					<div class="col-md-6">
						<label for="">Celular</label>
						<input type="text" id="asesor_celular" name="asesor_celular" class="form-control rounded-pill" />
					</div>`);
						$("#div_detalle_cotizacion").append('<hr class="d-block w-100"><button type="submit" class="btn btn-success rounded-pill" style="float: right;" id="btn_formulario" onclick="insertar_cotizacion(event, \'' + valoresCheck + '\');">Guardar</button>');
						$.get('core/app/view/order.php', {
							parAccion: 'lista_clientes'
						}, function(data) {
							$("#c_cliente").append('<option value="0">SELECCIONE ...</option>');
							var ooo = JSON.parse(data);
							$.each(ooo.Records, function(index, val) {
								$("#c_cliente").append('<option value="' + val.id + '">' + val.name + '</option>')
							});
						});
					});
					$('#popup_editar').fadeIn('slow');
					$('.popup-overlay').fadeIn('slow');
					$('.popup-overlay').height($(window).height());



					return false;
				}

				function readURL(input, _id) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();
						reader.onload = function(e) {
							$("#image_muestra_bordado_" + _id).attr("src", e.target.result);
						}
						reader.readAsDataURL(input.files[0]);
					}
				}

				function readURL_(input, _id) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();
						reader.onload = function(e) {
							$("#image_muestra_" + _id).attr("src", e.target.result);
						}
						reader.readAsDataURL(input.files[0]);
					}
				}


				function quitar_foto(id) {
					$("#image_muestra_" + id).attr('src', '');
					$("#img_m_" + id).val("");
				}

				function quitar_foto_bordado(id) {
					$("#image_muestra_bordado_" + id).attr('src', '');
					$("#img_b_" + id).val("");
				}

				function buscar_productos() {
					$("#tabla_resultado_busqueda").find('tbody').empty();
					$("#show_search_results").removeAttr('hidden');
					producto = $("#product_code").val();
					$.get('core/app/view/cotizacion.php', {
						parAccion: 'busqueda_productos',
						producto: producto
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Records.length > 0) {
							$.each(obj.Records, function(index, val) {
								$("#tabla_resultado_busqueda").find('tbody').append('<tr class="">' +
									'<td><input type="checkbox" class="form-check-input" id="chk_' + val.id + '" name="chk[]" value="' + val.id + '"></td>' +
									'<td>' + val.id + '</td>' +
									'<td>' + val.name + '</td>' +
									'<td>' + val.unit + '</td>' +
									'<td><b>S/ ' + val.price_in + '</b></td>' +
									'</tr>');
							});
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Ningún producto encontrado.</strong>' +
									'</div>'
							});
						}
					});
				}

				function cerrar_editar() {
					$('#close_editar').click();
				}
				$(document).ready(function() {
					$('#close_editar').on('click', function() {
						//limpiar_formulario();
						$('#popup_editar').fadeOut('slow');
						$('.popup-overlay').fadeOut('slow');
						return false;
						flag = false;
					});
				});

				$(document).ready(function() {
					$("#product_code").keydown(function(e) {
						if (e.which == 17 || e.which == 74) {
							e.preventDefault();
						} else {
							console.log(e.which);
						}
					})
				});
			</script>

			<?php if (isset($_SESSION["errors"])) : ?>
				<h2>Errores</h2>
				<p></p>
				<table class="table table-bordered table-hover">
					<tr class="danger">
						<th>Codigo</th>
						<th>Producto</th>
						<th>Mensaje</th>
					</tr>
					<?php foreach ($_SESSION["errors"]  as $error) :
						$product = ProductData::getById($error["product_id"]);
					?>
						<tr class="danger">
							<td><?php echo $product->id; ?></td>
							<td><?php echo $product->name; ?></td>
							<td><b><?php echo $error["message"]; ?></b></td>
						</tr>

					<?php endforeach; ?>
				</table>
			<?php
				unset($_SESSION["errors"]);
			endif; ?>


			<!--- Carrito de compras :) -->
			<?php if (isset($_SESSION["cotization"])) :
				$total = 0;
			?>
				<h2>Lista de venta</h2>
				<div class="box box-primary">
					<table class="table table-bordered table-hover">
						<thead>
							<th style="width:30px;">Codigo</th>
							<th style="width:30px;">Cantidad</th>
							<th style="width:30px;">Unidad</th>
							<th>Producto</th>
							<th style="width:30px;">Precio Unitario</th>
							<th style="width:30px;">Precio Total</th>
							<th></th>
						</thead>
						<?php foreach ($_SESSION["cotization"] as $p) :
							$product = ProductData::getById($p["product_id"]);
						?>
							<tr>
								<td><?php echo $product->id; ?></td>
								<td><?php echo $p["q"]; ?></td>
								<td><?php echo $product->unit; ?></td>
								<td><?php echo $product->name; ?></td>
								<td><b><?php echo Core::$symbol; ?> <?php echo number_format($product->price_out, 2, ".", ","); ?></b></td>
								<td><b><?php echo Core::$symbol; ?> <?php $pt = $product->price_out * $p["q"];
																	$total += $pt;
																	echo number_format($pt, 2, ".", ","); ?></b></td>
								<td style="width:30px;"><a href="index.php?view=clearcart&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
							</tr>

						<?php endforeach; ?>
					</table>
				</div>
				<form method="post" class="form-horizontal" id="processsell" action="index.php?action=savecotization">
					<h2>Resumen</h2>

					<input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
					<div class="clearfix"></div>
					<br>
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<div class="box box-primary">
								<table class="table table-bordered">
									<tr>
										<td>
											<p>Subtotal</p>
										</td>
										<td>
											<p><b><?php echo Core::$symbol; ?> <?php echo number_format($total * (1 - ($iva_val / 100)), 2, '.', ','); ?></b></p>
										</td>
									</tr>
									<tr>
										<td>
											<p><?php echo $iva_name . " (" . $iva_val . "%) "; ?></p>
										</td>
										<td>
											<p><b><?php echo Core::$symbol; ?> <?php echo number_format($total * ($iva_val / 100), 2, '.', ','); ?></b></p>
										</td>
									</tr>
									<tr>
										<td>
											<p>Total</p>
										</td>
										<td>
											<p><b><?php echo Core::$symbol; ?> <?php echo number_format($total, 2, '.', ','); ?></b></p>
										</td>
									</tr>

								</table>
							</div>
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<div class="checkbox">
										<label>
											<input name="is_oficial" type="hidden" value="1">
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<div class="checkbox">
										<label>
											<a href="index.php?view=clearcart" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
											<button class="btn btn-success"><i class="glyphicon glyphicon-send"></i> Guardar Cotizacion</button>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
		</div>
	</div>

<?php endif; ?>

</div>

</section>