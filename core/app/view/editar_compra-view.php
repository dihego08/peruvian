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
		z-index: 1001;
	}

	#popup_editar_2 {
		left: 0;
		position: absolute;
		top: 0;
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
			<h1>Editar Compra </h1>
			<?php
			if ($cli != "") {
			?>
				<h2>CLIENTE : <?php echo ($cli); ?> </h2>
			<?php
			}
			?>
			<p><b>Editar Cabecera de Compra:</b></p>
			<!--<form id="searchp">-->
			<div class="row">
				<!--<form id="formulario" style="overflow: hidden;">-->
				<div class="col-md-12 form-row" id="div_entrega">
					<!--<label>Codigo Compra</label>
						<input type="text" id="codigo_compra" name="codigo_compra" class="form-control" placeholder="Codigo de Compra" style="margin-bottom: 10px;">-->
					<div class="col-md-3">
						<label for="">Tipo de Documento</label>
						<select name="tipo_documento" id="tipo_documento" class="form-control">
							<option value="-1">--Seleccionar--</option>
						</select>
					</div>
					<div class="col-md-3">
						<label for="">Pago</label>
						<select name="id_forma_pago" id="id_forma_pago" class="form-control"></select>
					</div>
					<div class="col-md-3">
						<label>Proveedor</label>
						<select class="form-control js-example-basic-single" id="cliente" name="cliente">
							<option value="0">SELECCIONA ...</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Fecha</label>
						<input type="text" id="fecha_creacion" name="fecha_creacion" class="form-control datepicker" placeholder="Fecha de Compra">
					</div>
					<div class="col-md-6">
						<label for="">Serie</label>
						<input type="text" class="form-control" id="serie" name="serie">
					</div>
					<div class="col-md-6">
						<label for="">Numeracion</label>
						<input type="text" class="form-control" id="numeracion" name="numeracion">
					</div>
					<div class="col-md-3">
						<label for="">Total</label>
						<input type="text" id="total" name="total" class="form-control" value="0">
					</div>
					<div class="col-md-3">
						<label for="">IGV</label>
						<input type="text" id="igv" name="igv" class="form-control" value="0">
					</div>
					<div class="col-md-3">
						<label for="">Gravado</label>
						<input type="text" id="gravado" name="gravado" class="form-control" value="0">
					</div>
					<div class="col-md-3">
						<label for="">Exonerado</label>
						<input type="text" id="exonerado" class="form-control" name="exonerado" value="0">
					</div>
					<div class="col-md-12" style="margin-top: 1rem;">
						<!--<a class="btn btn-danger" onclick="cancel_order();">Cancelar</a>-->
						<button class="btn btn-success" id="guardar_compra" onclick="actualizar_compra();">Actualizar Compra</button>
					</div>
				</div>
				<!--</form>-->
			</div>

			<p><b>Editar Cuerpo de Compra:</b></p>
			<div id="resultado" style="padding: 25px 25px 0 25px; margin-top: 25px; margin-bottom: 0px;">
				<h3>Lista de productos</h3>
				<div class="box box-primary table-responsive">
					<table class="table table-bordered table-hover" id="tabla_resultado">
						<thead>
							<tr>
								<th>Codigo</th>
								<th>Insumo</th>
								<th>Precio</th>
								<th>Cantidad</th>
								<th></th>
								<th>Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>

			<div id="popup_editar" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar"><img src="../css/images/close.png" /></a></div>
					<div>
						<h2 id="titulo_detalle">Detalle Orden de Pedido</h2>
						<div class="box box-primary table-responsive">
							<table class="table table-bordered table-hover" id="tabla_detalle">
								<thead>
									<tr>
										<th>Insumo</th>
										<th>Cantidad</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
						<!--<button type="submit" class="btn btn-success" style="float: right;" id="btn_formulario">Actualizar</button>-->
					</div>
				</div>
			</div>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.full.min.js"></script>
			<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet" />

			<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
			<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
			<script>
				function get_data(id_compra) {
					$.post("core/app/view/insumos.php?parAccion=get_data_compra", {
						id_compra: id_compra
					}, function(response) {
						var obj = JSON.parse(response);

						//$("#tipo_documento").val(obj.tipo_documento);
						$("#id_forma_pago").val(obj.id_forma_pago);
						$("#serie").val(obj.serie);
						$("#numeracion").val(obj.numeracion);
						$("#total").val(obj.total);
						$("#igv").val(obj.igv);
						$("#gravado").val(obj.gravado);
						$("#exonerado").val(obj.exonerado);

						$.get('core/app/view/venta.php', {
							parAccion: 'tipos_documentos_compras'
						}, function(data) {
							//$("#tipo_documento").empty();
							var ob = JSON.parse(data);
							$.each(ob.Records, function(ind, v) {
								if(v.id == obj.tipo_documento){
									$("#tipo_documento").append('<option value="' + v.id + '" selected>' + v.tipo_documento + '</option>');
								}else{
									$("#tipo_documento").append('<option value="' + v.id + '">' + v.tipo_documento + '</option>');
								}
								
							});
						});

						$("#fecha_creacion").val(obj.fecha_creacion);
						// $("#cliente").val(obj.id_proveedor);
						// $("#cliente").trigger('change');
						//$("#cliente").select2("val", obj.id_proveedor);

						lista_clientes(obj.id_proveedor);

						get_body(id_compra);
					});
				}

				function get_body(id_compra) {
					$("#tabla_resultado").find('tbody').empty();
					$.post("core/app/view/insumos.php?parAccion=get_body_compra", {
						id_compra: id_compra
					}, function(response) {
						var obj = JSON.parse(response);

						$.each(obj, function(index, val) {
							$("#tabla_resultado").find('tbody')
								.append('<tr>' +
									'<th scope="row">' + val.codigo + '</th>' +
									'<td>' + val.insumo + '</td>' +
									'<td>' +
									'<div class="input-group col-md-12">' +
									'<input type="" class="form-control" required id="precio_' + val.id + '" name="precio_' + val.id + '" placeholder="Precio ..." value="' + val.precio + '">' +
									'</div>' +
									'</td>' +
									'<td>' +
									'<div class="input-group col-md-12">' +
									'<input type="" class="form-control" required id="canti_' + val.id + '" name="canti_' + val.id + '" placeholder="Cantidad ..."value="' + val.cantidad + '">' +
									'</div>' +
									'</td>' +
									'<td>' +
									'<span class="btn-xs btn-success" onclick="calcular_total(' + val.id + ');"><i class="fa fa-forward"></i></span>' +
									'</td>' +
									'<td>' +
									'<div class="input-group col-md-12">' +
									'<input type="" class="form-control" required id="total_' + val.id + '" name="total_' + val.id + '" placeholder="Total ..."value="' + val.total + '">' +
									'</div>' +
									'</td>' +
									`<td>
									    <span style="cursor: pointer;" class="btn-xs btn-primary" onclick="actualizar(${val.id});" title="Actualizar"><i class="fa fa-check"></i></span>
									</td>` +
									'</tr>');
							//combo_cliente(ui.item.id);
						});
					});
				}

				function actualizar(id_compra_detalle) {
					$.post("core/app/view/insumos.php?parAccion=actualizar_detalle", {
						precio: $("#precio_" + id_compra_detalle).val(),
						cantidad: $("#canti_" + id_compra_detalle).val(),
						total: $("#total_" + id_compra_detalle).val(),
						id: id_compra_detalle
					}, function(response) {
						var obj = JSON.parse(response);

						if (obj.Result == 'OK') {
							get_body(<?php echo $_GET['id_compra']; ?>);
							bootbox.alert({
								message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Realizado Correctamente.</strong>' +
									'</div>'
							});
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Ago ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				}

				function actualizar_compra() {
					$.post("core/app/view/insumos.php?parAccion=actualizar_compra", {
						tipo_documento: $("#tipo_documento").val(),
						id_forma_pago: $("#id_forma_pago").val(),
						serie: $("#serie").val(),
						numeracion: $("#numeracion").val(),
						total: $("#total").val(),
						igv: $("#igv").val(),
						gravado: $("#gravado").val(),
						exonerado: $("#exonerado").val(),
						fecha_creacion: $("#fecha_creacion").val(),
						id_proveedor: $("#cliente").val(),
						id_compra: <?php echo $_GET['id_compra']; ?>
					}, function(response) {
						var obj = JSON.parse(response);


						if (obj.Result == 'OK') {
							window.location = "index.php?view=res";
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Ago ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				}

				function agregar_proveedor() {
					var proveedor = $("#proveedor").val();
					var dni = $("#dni").val();
					var direccion = $("#direccion").val();
					$.get('core/app/view/insumos.php', {
						parAccion: 'agregar_proveedor',
						proveedor: proveedor,
						dni: dni,
						direccion: direccion
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == 'OK') {
							lista_clientes();
							cerrar_editar_2();
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Ago ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				}
				var id_producto = 0;

				function detalle_order(codigo) {
					$("#tabla_detalle").find('tbody').empty();
					$.get('core/app/view/insumos.php', {
						parAccion: 'lista_detalle',
						codigo: codigo
					}, function(data) {
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							$("#tabla_detalle").find('tbody').append('<tr><th scope="row">' + val.insumo + '</th><td>' + val.cantidad + '</td><td>S/. ' + val.total + '</td></tr>');
						});
					});
					$('#popup_editar').fadeIn('slow');
					$('.popup-overlay').fadeIn('slow');
					$('.popup-overlay').height($(window).height());
					return false;
				}

				function nuevo_proveedor() {

					$('#popup_editar_2').fadeIn('slow');
					$('.popup-overlay').fadeIn('slow');
					$('.popup-overlay').height($(window).height());
					return false;
				}

				function eliminar_order(codigo) {
					bootbox.confirm({
						message: "¿Seguro de Eliminar esta Compra?",
						buttons: {
							confirm: {
								label: 'Sí',
								className: 'btn-success'
							},
							cancel: {
								label: 'No',
								className: 'btn-danger'
							}
						},
						callback: function(result) {
							if (result) {
								//alert("YES");
								$.get('core/app/view/venta.php', {
									parAccion: 'eliminar_venta',
									codigo: codigo
								}, function(data) {
									var obj = JSON.parse(data);
									if (obj.Result == 'OK') {
										//lista_ordenes();
									} else {
										bootbox.alert({
											message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
												'<strong>Ago ha salido mal.</strong>' +
												'</div>'
										});
									}
								});
							} else {}
						}
					});
				}

				function lista_ordenes() {
					$("#tabla_lista").find('tbody').empty();
					$.get('core/app/view/insumos.php', {
						parAccion: 'lista_compras'
					}, function(data) {
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							var ppp = "";
							if (val.proveedor == 'null' || val.proveedor == "") {
								ppp = "";
							} else {
								ppp = val.proveedor;
							}
							$("#tabla_lista").find('tbody').append('<tr>' +
								'<th scope="row">' + val.codigo + '</th>' +
								'<th scope="row">' + val.fecha_creacion + '</th>' +
								'<!--<td>' + val.name + '</td>-->' +
								'<td>' + ppp + '</td>' +
								'<td>S/. ' + val.total + '</td>' +
								'<td>' + '<a href="#" onclick="eliminar_order(\'' + val.codigo + '\');" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>' + '</td>' +
								'<td>' + '<a href="#" onclick="detalle_order(\'' + val.codigo + '\');" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>' + '</td>' +
								'</tr>');
						});
					});
				}

				function cancel_order() {
					$("#resultado").attr('hidden', true);
					$("#div_entrega").attr('hidden', true);
					$("#tabla_resultado").find('tbody').empty();
				}

				function lista_clientes(i) {
					$("#cliente").empty();
					$.get('core/app/view/insumos.php', {
						parAccion: 'lista_proveedores'
					}, function(data) {
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							if (val.id == i) {
								$("#cliente").append('<option value="' + val.id + '" selected>' + val.id + '-' + val.name + '</option>');
							} else {
								$("#cliente").append('<option value="' + val.id + '">' + val.id + '-' + val.name + '</option>');
							}
						});
						$('.js-example-basic-single').select2();
					});
				}

				function cerrar_editar() {
					$('#close_editar').click();
				}

				function cerrar_editar_2() {
					$('#close_editar_2').click();
				}

				function combo_cliente(id) {
					$.get('core/app/view/insumos.php', {
						parAccion: 'combo_unidades'
					}, function(data) {
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							$("#t_unidad_" + id).append('<option value="' + val.id + '">' + val.unidad + '</option>')
						});
					});
				}

				function calcular_total(id) {
					var precio = $("#precio_" + id).val();
					var cantidad = $("#canti_" + id).val();
					var total = parseFloat(precio) * parseFloat(cantidad);
					$("#total_" + id).val(parseFloat(total).toFixed(2));

					var total_total = parseFloat($("#total").val());
					//console.log(total_total);
					total_total += parseFloat(total);
					//console.log(total_total);
					$("#total").val(parseFloat(total_total).toFixed(2));


					var base = total_total / 1.18;
					$("#igv").val(parseFloat(total_total - base).toFixed(2));
					$("#gravado").val(parseFloat(base).toFixed(2));
				}
				$(document).ready(function() {
					//lista_clientes();


					$("#fecha_creacion").datetimepicker({
						format: "d-m-Y",
						timepicker: false
					});
					$.datetimepicker.setLocale('es');

					//lista_ordenes();
					$('#product_name').autocomplete({
						source: 'core/app/view/insumos.php?parAccion=insumo_autocomplete',
						minLength: 2,
						focus: true,
						select: function(event, ui) {
							id_producto = id_producto + ',' + ui.item.id;
							$("#resultado").removeAttr('hidden');
							$("#div_entrega").removeAttr('hidden');
							$("#tabla_resultado").find('tbody')
								.append('<tr>' +
									'<th scope="row">' + ui.item.cod + '</th>' +
									'<td>' + ui.item.value + '</td>' +

									'<td>' +
									'<div class="input-group col-md-12">' +
									'<input type="" class="form-control" required id="precio_' + ui.item.id + '" name="precio_' + ui.item.id + '" placeholder="Precio ...">' +
									'</div>' +
									'</td>' +
									'<td>' +
									'<div class="input-group col-md-12">' +
									'<input type="" class="form-control" required id="canti_' + ui.item.id + '" name="canti_' + ui.item.id + '" placeholder="Cantidad ...">' +
									'</div>' +
									'</td>' +
									'<td>' +
									'<span class="btn-xs btn-success" onclick="calcular_total(' + ui.item.id + ');"><i class="fa fa-forward"></i></span>' +
									'</td>' +
									'<td>' +
									'<div class="input-group col-md-12">' +
									'<input type="" class="form-control" required id="total_' + ui.item.id + '" name="total_' + ui.item.id + '" placeholder="Total ...">' +
									'</div>' +
									'</td>' +
									'<td><select class="form-control" name="t_unidad_' + ui.item.id + '" id="t_unidad_' + ui.item.id + '"></select></td>' +
									'</tr>');
							combo_cliente(ui.item.id);

						}
					});

					$("#formulario").submit(function(event) {
						event.preventDefault();
						//var form = $(this);
						$.ajax({
								url: 'core/app/view/insumos.php?parAccion=nuevo_compra&cant=' + id_producto,
								type: 'POST',
								//dataType: 'html',
								data: $(this).serialize(),
							})
							.done(function() {

								//lista_ordenes();
								id_producto = 0;
								window.location = "index.php?view=res";

							})
							.fail(function() {
								bootbox.alert({
									message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Algo ha salido mal.</strong>' +
										'</div>'
								});
							})
							.always(function() {
								console.log("complete");
							});

					});
					$('#close_editar').on('click', function() {
						//limpiar_formulario();
						$('#popup_editar').fadeOut('slow');
						$('.popup-overlay').fadeOut('slow');
						return false;
						flag = false;
					});
					$('#close_editar_2').on('click', function() {
						//limpiar_formulario();
						$('#popup_editar_2').fadeOut('slow');
						$('.popup-overlay').fadeOut('slow');
						return false;
						flag = false;
					});

					$("#product_code").keydown(function(e) {
						if (e.which == 17 || e.which == 74) {
							e.preventDefault();
						} else {
							console.log(e.which);
						}
					})




					$.get('core/app/view/venta.php', {
						parAccion: 'tipos_pago'
					}, function(data) {
						$("#id_forma_pago").empty();
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							$("#id_forma_pago").append('<option value="' + val.id + '">' + val.name + '</option>');
						});
					});

					get_data(<?php echo $_GET['id_compra']; ?>);
				});
			</script>

</section>