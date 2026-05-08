<script type="text/javascript">
	$(function() {
		$("#banco").change(function() {
			if ($(this).val() == 'SIN_BANCO') {
				$('.nro_cuenta_pnl').hide();
				$('.tipo_cuenta_pnl').hide();
				$('.tipo_moneda_pnl').hide();
			} else {
				$('.nro_cuenta_pnl').show();
				$('.tipo_cuenta_pnl').show();
				$('.tipo_moneda_pnl').show();
			}
		});
	});
</script>
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


	fieldset {
		background-color: #eeeeee;
	}

	legend {
		background-color: gray;
		color: white;
		padding: 5px 10px;
	}

	.select2-container {
		width: 100% !important;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Registro de Compra </h3>
			<?php
			if ($cli != "") {
			?>
				<h4>CLIENTE : <?php echo ($cli); ?> </h4>
			<?php
			}
			?>
			<div class="row">
				<form id="formulario" style="overflow: hidden;">
					<div class="col-md-2">
						<label>Nombre del Insumo</label>
						<input type="text" id="product_name" name="product_name" class="form-control rounded-pill ui-autocomplete-input" placeholder="Nombre del Producto">
					</div>
					<div class="col-md-4">
						<label>Proveedor</label>
						<select class="form-control rounded-pill js-example-basic-single" id="cliente" name="cliente">
							<option value="0">SELECCIONA ...</option>
						</select>
					</div>
					<div class="col-md-2">
						<label> + Proveedor</label><br>
						<a href="#" class="btn btn-success rounded-pill" onclick="nuevo_proveedor();"><i class="fa fa-plus"></i></a>
					</div>
					<div class="col-md-4">
						<label>Fecha</label>
						<input autocomplete="off" type="text" id="fecha_creacion" name="fecha_creacion" class="form-control rounded-pill datepicker" placeholder="Fecha de Compra">
					</div>

					<div id="resultado" hidden style="padding: 25px 25px 0 25px; margin-top: 25px; margin-bottom: 0px;">
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
										<th>Unidad</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-12 form-row" id="div_entrega" hidden>
						<div class="col-md-6">
							<label for="">Tipo de Documento</label>
							<select name="tipo_documento" id="tipo_documento" class="form-control rounded-pill">
								<option value="-1">--Seleccionar--</option>
							</select>
						</div>
						<div class="col-md-6">
							<label for="">Pago</label>
							<select name="id_forma_pago" id="id_forma_pago" class="form-control rounded-pill"></select>
						</div>
						<div class="col-md-6">
							<label for="">Serie</label>
							<input type="text" class="form-control rounded-pill" id="serie" name="serie">
						</div>
						<div class="col-md-6">
							<label for="">Numeracion</label>
							<input type="text" class="form-control rounded-pill" id="numeracion" name="numeracion">
						</div>
						<div class="col-md-6 form-row">
							<div class="col-md-6">
								<label for="">Total</label>
								<input type="text" id="total" name="total" class="form-control rounded-pill" value="0">
							</div>
							<div class="col-md-6">
								<label for="">IGV</label>
								<input type="text" id="igv" name="igv" class="form-control rounded-pill" value="0">
							</div>
						</div>
						<div class="col-md-6 form-row">
							<div class="col-md-4">
								<label for="">Gravado</label>
								<input type="text" id="gravado" name="gravado" class="form-control rounded-pill" value="0">
							</div>
							<div class="col-md-4">
								<label for="">Exonerado</label>
								<input type="text" id="exonerado" class="form-control rounded-pill" name="exonerado" value="0">
							</div>
							<div class="col-md-4">
								<label for="">Otros no Gravados</label>
								<input type="text" id="otros_no_gravado" class="form-control rounded-pill" name="otros_no_gravado" value="0">
							</div>
						</div>

						<div class="col-md-12" style="margin-top: 1rem;">
							<fieldset>
								<legend style="width: auto !important;">Detraccion:</legend>
								<div class="form-row">
									<div class="col-md-4">
										<label for="">Constancia Fecha</label>
										<input type="text" id="fecha_detraccion" name="fecha_detraccion" class="form-control rounded-pill">
									</div>
									<div class="col-md-4">
										<label for="">Detraccion Numero</label>
										<input type="text" id="numero_detraccion" name="numero_detraccion" class="form-control rounded-pill">
									</div>
									<div class="col-md-4">
										<label for="">Tipo de Cambio</label>
										<input type="text" id="tipo_cambio" name="tipo_cambio" class="form-control rounded-pill">
									</div>
								</div>
							</fieldset>
						</div>


						<div class="col-md-12" style="margin-top: 1rem;">
							<fieldset>
								<legend style="width: auto !important;">Ref. Comprobando de Pago:</legend>
								<div class="form-row">
									<div class="col-md-4">
										<label for="">Fecha</label>
										<input type="text" id="fecha_comprobante" name="fecha_comprobante" class="form-control rounded-pill">
									</div>
									<div class="col-md-4">
										<label for="">Serie</label>
										<input type="text" id="serie_comprobante" name="serie_comprobante" class="form-control rounded-pill">
									</div>
									<div class="col-md-4">
										<label for="">Documento:</label>
										<input type="text" id="documento_comprobante" name="documento_comprobante" class="form-control rounded-pill">
									</div>
								</div>
							</fieldset>
						</div>

						<div class="col-md-12 text-right" style="margin-top: 1rem;">
							<a class="btn btn-danger rounded-pill" onclick="cancel_order();">Cancelar</a>
							<button class="btn btn-success rounded-pill" id="guardar_compra">Guardar Compra</button>
						</div>
					</div>
				</form>
			</div>
			<div id="popup_editar" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar"><b>X</b></a></div>
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
			<div id="popup_editar_2" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar_2"><b>X</b></a></div>
					<div class="form-row">
						<h2 id="titulo_detalle">Nuevo Proveedor</h2>
						<div class="form-group">
							<div class="col-md-6">
								<label for="inputEmail1" class="control-label">Material/Insumo</label>
								<select class="form-control rounded-pill js-example-basic-single" id="id_insumo" name="id_insumo"></select>
							</div>
							<div class="col-md-6">
								<label for="inputEmail1" class="control-label">DNI / RUC*</label>
								<div class="input-group mb-3" style="display: flex;">
									<input type="text" name="no" class="form-control rounded-pill" placeholder="RUC ..." id="no" aria-label="Recipient's username" aria-describedby="basic-addon2">
									<div class="input-group-append">
										<button class="btn btn-outline-secondary" type="button" onclick="buscar_ruc();"><i class="fa fa-search"></i></button>
									</div>
								</div>

								<div id="resultado_ruc"></div>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail1" class="control-label">Nombre / Razón social*</label>
							<input type="text" name="name" class="form-control rounded-pill" id="name" placeholder="Nombre / Razón social">
						</div>
						<div class="form-group">
							<label for="inputEmail1" class="control-label">Direccion*</label>
							<input type="text" name="address1" class="form-control rounded-pill" required id="address1" placeholder="Direccion">
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<label for="inputEmail1" class="control-label">Banco*</label>
								<select class="form-control rounded-pill" name="banco" id="banco">
									<option value="BCP">BCP</option>
									<option value="INTERBANK">INTERBANK</option>
									<option value="SCOTIABANK">SCOTIABANK</option>
									<option value="BBVA_CONTINENTAL">BBVA_CONTINENTAL</option>
									<option value="BANCO_DE_CREDITO">BANCO DE CREDITO</option>
									<option value="MiBanco">MiBanco</option>
									<option value="SIN_BANCO">SIN BANCO</option>
								</select>
							</div>
							<div class="col-md-6 nro_cuenta_pnl">
								<label for="inputEmail1" class="control-label">Nro de Cuenta</label>
								<input type="text" name="nro_cuenta" class="form-control rounded-pill" id="nro_cuenta" placeholder="Número de Cuenta">
							</div>

						</div>
						<div class="col-md-6 tipo_cuenta_pnl">
							<label for="inputEmail1" class="control-label">Tipo de Cuenta</label>
							<select class="form-control rounded-pill" name="tipo_cuenta" id="tipo_cuenta">
								<option>- Elegir opción -</option>
								<option value="corriente">Cuenta Corriente</option>
								<option value="ahorros">Cuenta de Ahorros</option>
							</select>
						</div>
						<div class="col-md-6 tipo_moneda_pnl">
							<label for="inputEmail1" class="control-label">Tipo de Moneda</label>
							<select class="form-control rounded-pill" name="tipo_moneda" id="tipo_moneda">
								<option>- Elegir opción -</option>
								<option selected value="SOL">Soles</option>
								<option value="DOL">Dólares</option>
							</select>
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<label for="inputEmail1" class="control-label">Forma de Pago</label>
								<!--<input type="text" name="forma_envio" class="form-control rounded-pill" id="forma_envio" placeholder="Forma de Envío">-->
								<select name="forma_envio" id="forma_envio" class="form-control rounded-pill">
									<option value="Contado" selected>Contado</option>
									<option value="Pago a Cuenta">Pago a Cuenta</option>
									<option value="Credito">Credito</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="inputEmail1" class="control-label">Email</label>
								<input type="text" name="email1" class="form-control rounded-pill" id="email1" placeholder="Email">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6">
								<label for="inputEmail1" class="control-label">Telefono</label>
								<input type="text" name="phone1" class="form-control rounded-pill" id="phone1" placeholder="Telefono">
							</div>
							<div class="col-md-6">
								<label for="inputEmail1" class="control-label">WhatsApp</label>
								<input type="text" name="wsp" class="form-control rounded-pill" id="inputEmail1" placeholder="WhatsApp">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12" style="margin-top: 1rem;">
								<p class="alert alert-info">* Campos obligatorios</p>
							</div>
						</div>
						<span class="btn btn-danger" onclick="cerrar_editar_2()">Cerrar</span>
						<button type="submit" class="btn btn-success" style="float: right;" id="btn_formulario" onclick="agregar_proveedor();">Agregar</button>
					</div>
				</div>
			</div>
			<div class="popup-overlay"></div>
			<div style="display:none;" id="qrreader">
				<div id="mainbody">
					<a class="selector" id="webcamimg" onclick="setwebcam()" align="left">Camara</a>
					<a class="selector" id="qrimg" src="cam.png" onclick="setimg()" align="right">Imagen</a>
					<div id="outdiv">
					</div>
					<div id="result">-- Scaning --</div>
					<canvas id="qr-canvas" width="800" height="600"></canvas>
					<button onclick="captureToCanvas()">Capture</button><br>
				</div>
			</div>
			<div id="show_search_results"></div>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.full.min.js"></script>
			<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet" />

			<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
			<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
			<script>
				//jQuery.noConflict();
				function agregar_proveedor() {
					var proveedor = $("#proveedor").val();
					var dni = $("#dni").val();
					var direccion = $("#direccion").val();
					$.post('core/app/view/insumos.php?parAccion=agregar_proveedor', {
						/*proveedor: proveedor,
						dni: dni,
						direccion: direccion*/
						id_insumo: $("#id_insumo").val(),
						no: $("#no").val(),
						name: $("#name").val(),
						address1: $("#address1").val(),
						banco: $("#banco").val(),
						nro_cuenta: $("#nro_cuenta").val(),
						tipo_cuenta: $("#tipo_cuenta").val(),
						tipo_moneda: $("#tipo_moneda").val(),
						forma_envio: $("#forma_envio").val(),
						email1: $("#email1").val(),
						phone1: $("#phone1").val(),
						wsp: $("#inputEmail1").val(),
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

				function llenar_insumos() {
					$.post("core/app/view/insumos.php?parAccion=lista_insumos", function(response) {
						var obj = JSON.parse(response);

						$.each(obj.Records, function(index, val) {
							$("#id_insumo").append(`<option value="${val.id}">${val.insumo}</option>`);
						});
					});
				}

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

				function lista_clientes() {
					$("#cliente").empty();
					$.get('core/app/view/insumos.php', {
						parAccion: 'lista_proveedores'
					}, function(data) {
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							$("#cliente").append('<option value="' + val.id + '">' + val.id + '-' + val.name + '</option>');
						});
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
						parAccion: 'combo_unidades',
						id: '0'
					}, function(data) {
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							$("#t_unidad_" + id).append('<option value="' + val.codigo + '">' + val.unidad + '</option>')
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
					llenar_insumos();
					$('.js-example-basic-single').select2();
					$(".datepicker").datetimepicker({
						format: "Y-m-d",
						timepicker: false,
						scrollInput: false
					});
					$("#fecha_detraccion").datetimepicker({
						format: "d-m-Y",
						timepicker: false,
						scrollInput: false
					});
					$("#fecha_comprobante").datetimepicker({
						format: "d-m-Y",
						timepicker: false,
						scrollInput: false
					});
					$.datetimepicker.setLocale('es');
					lista_clientes();
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
									'<input type="" class="form-control rounded-pill" required id="precio_' + ui.item.id + '" name="precio_' + ui.item.id + '" placeholder="Precio ...">' +
									'</div>' +
									'</td>' +
									'<td>' +
									'<div class="input-group col-md-12">' +
									'<input type="" class="form-control rounded-pill" required id="canti_' + ui.item.id + '" name="canti_' + ui.item.id + '" placeholder="Cantidad ...">' +
									'</div>' +
									'</td>' +
									'<td>' +
									'<span class="btn-sm btn btn-outline-success rounded-pill" onclick="calcular_total(' + ui.item.id + ');"><i class="fa fa-forward"></i></span>' +
									'</td>' +
									'<td>' +
									'<div class="input-group col-md-12">' +
									'<input type="" class="form-control rounded-pill" required id="total_' + ui.item.id + '" name="total_' + ui.item.id + '" placeholder="Total ...">' +
									'</div>' +
									'</td>' +
									'<td><select class="form-control rounded-pill" name="t_unidad_' + ui.item.id + '" id="t_unidad_' + ui.item.id + '"></select></td>' +
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
						parAccion: 'tipos_documentos_compras'
					}, function(data) {
						//$("#tipo_documento").empty();
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							if (val.id == 2) {
								$("#tipo_documento").append('<option value="' + val.id + '" selected>' + val.tipo_documento + '</option>');
							} else {
								$("#tipo_documento").append('<option value="' + val.id + '">' + val.tipo_documento + '</option>');
							}

						});
					});

					$.get('core/app/view/venta.php', {
						parAccion: 'tipos_pago'
					}, function(data) {
						$("#id_forma_pago").empty();
						var obj = JSON.parse(data);
						$.each(obj.Records, function(index, val) {
							$("#id_forma_pago").append('<option value="' + val.id + '">' + val.name + '</option>');
						});
					});
				});

				function buscar_ruc() {
					$("#resultado_ruc").empty();
					$("#resultado_ruc").append('<span class="badge badge-warning">Buscando</span>');

					$.post("https://incared.com/api/apirest", {
						action: 'getnumero',
						numero: $("#no").val()
					}, function(response) {
						var obj = JSON.parse(response);
						$("#resultado_ruc").empty();
						$("#resultado_ruc").append('<span class="badge badge-success">' + $("#no").val() + ' - ' + obj.rs + '</span>');


						$("#name").val(obj.rs);
						$("#address1").val(obj.direccion_string);
					});


					/*$.get('https://softluttion.com/jossmp/sunatphp/example/consulta.php', {
						nruc: $("#no").val()
					}, function(data) {
						var obj = JSON.parse(data);
						console.log(obj);
						$("#resultado_ruc").empty();
						if (obj.success == "false") {
							$("#resultado_ruc").append('<span class="badge badge-success">' + $("#no").val() + ' - ' + obj.msg + '</span>');
						} else {
							$("#resultado_ruc").append('<span class="badge badge-success">' + $("#no").val() + ' - ' + obj.result.RazonSocial + '</span>');
						}

						$("#name").val(obj.result.RazonSocial);
						$("#address1").val(obj.result.Direccion);
					});*/
				}
			</script>

</section>