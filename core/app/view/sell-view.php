<?php
date_default_timezone_set('America/Lima');

$cli = "";
if ((isset($_GET["cli"]) && $_GET["cli"] != "")) {
	$cli = $_GET['cli'];
	$_SESSION['cliname'] = $cli;
}

?>
<style>
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

	.seleccion_venta {
		background-color: #dff0d8;
	}
</style>
<style type="text/css">
	#popup_editar {
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

	.clsDatePicker {
		position: absolute;
		cursor: default;
		z-index: 1001 !important
	}

	.ui-datepicker-month {
		color: #313131;
	}

	.ui-datepicker-year {
		color: #313131;
	}

	.badge {
		cursor: pointer;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Venta </h3>
			<?php
			if ($cli != "") {
			?>
				<h2>CLIENTE : <?php echo ($cli); ?> </h2>
			<?php
			}
			?>
			<p><b>Buscar producto por nombre o por codigo:</b></p>
			<form id="searchp">
				<div class="row">
					<div class="col-md-4">
						<input type="hidden" name="view" value="sell">
						<input type="text" id="product_name" name="product_name" class="form-control rounded-pill" placeholder="Nombre del Producto">
					</div>
					<div class="col-md-4">
						<input type="hidden" name="view" value="sell">
						<input type="text" id="product_code" name="product_code" class="form-control rounded-pill" placeholder="Modelo">
					</div>
					<div class="col-md-4" hidden>
						<input type="hidden" name="view" value="sell">
						<input type="text" id="order_pedido" name="order_pedido" class="form-control rounded-pill" placeholder="Orden de Pedido">
					</div>
					<div class="col-md-8 text-center" style="margin-top: 5px;">
						<button type="submit" class="btn btn-success rounded-pill"><i class="glyphicon glyphicon-search"></i> Buscar</button>
					</div>
				</div>
			</form>

			<div class="row">
				<div id="resultado_busqueda_order" hidden>
					<div class="col-md-8">
						<h2>Resultado de Búsqueda</h2>
						<div class="box box-primary">
							<table class="table table-bordered table-hover" id="detalle_busqueda_order">
								<thead>
									<tr>
										<th>Cant.</th>
										<th>Unidad</th>
										<th>Pedido</th>
										<th style="width: 10%;">Modelo</th>
										<th style="width: 12%;">Tipo</th>
										<th style="width: 20%;">Producto</th>
										<th>P. Unit.</th>
										<th>P. Bord.</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<div id="div_lista_ventas" hidden>
							<h2>Lista de Venta</h2>
							<div class="box box-primary">
								<table class="table table-bordered table-hover" id="tabla_lista_venta" hidden>
									<thead>
										<tr>
											<!--<th>Codigo</th>-->
											<th>Cantidad</th>
											<th>Unidad</th>
											<th>Modelo</th>
											<th>Pedido</th>
											<th>Producto</th>
											<th>P. Unitario</th>
											<th>P. Bordado</th>
											<th>P. Total</th>
											<th>Quitar</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<h2>Resumen de Venta</h2>
						<div class="row">
							<div class="box box-primary" style="overflow: hidden;">
								<div class="form-group col-md-12">
									<label>¿Venta incluye IGV?</label>
									<select class="form-control rounded-pill" id="incluye_igv" name="incluye_igv">
										<option value="0">NO</option>
										<option value="1">Si</option>
									</select>
								</div>
								<div class="form-group col-md-12">
									<label>Tipo de Documento</label>
									<select class="form-control rounded-pill" id="tipos_documento" name="tipos_documento">

									</select>
								</div>
								<div class="form-group col-md-12" id="div_codigo_venta">
									<div class="col-md-6" style="padding-right: 0; text-align: right; margin-top: 5px;">
										<span id="codigo_venta" style="font-weight: bold;">Número de Documento: &nbsp;</span>
									</div>
									<div class="col-md-6" style="padding-left: 0; text-align: left;">
										<input type="text" name="txt_cod_venta" id="txt_cod_venta" class="form-control rounded-pill">
									</div>
								</div>
								<div class="form-group col-md-12" id="div_codigo_venta">
									<div class="col-md-6" style="padding-right: 0; text-align: right; margin-top: 5px;">
										<span id="codigo_venta" style="font-weight: bold;">Guía: &nbsp;</span>
									</div>
									<div class="col-md-6" style="padding-left: 0; text-align: left;">
										<input type="text" name="txt_guia" id="txt_guia" class="form-control rounded-pill">
									</div>
								</div>
								<!--<div class="form-group col-md-12" id="div_codigo_venta">
									<div class="col-md-6" style="padding-right: 0; text-align: right; margin-top: 5px;">
										<span id="codigo_venta" style="font-weight: bold;">Nro Pedido: &nbsp;</span>
									</div>
									<div class="col-md-6" style="padding-left: 0; text-align: left;">
										<input type="text" name="txt_pedido" id="txt_pedido" class="form-control rounded-pill">
									</div>
								</div>-->
								<div class="form-group col-md-12" id="div_codigo_venta">
									<div class="col-md-6" style="padding-right: 0; text-align: right; margin-top: 5px;">
										<span id="codigo_venta" style="font-weight: bold;">Fecha Emisión: &nbsp;</span>
									</div>
									<div class="col-md-6" style="padding-left: 0; text-align: left;">
										<input type="text" name="fecha_emision" id="fecha_emision" class="form-control rounded-pill" placeholder="Año-Mes-día" value="<?php echo date("Y-m-d", strtotime(date("Y-m-d"))) ?>">
									</div>
								</div>
								<div class="form-group col-md-12" id="div_codigo_venta">
									<div class="col-md-6" style="padding-right: 0; text-align: right; margin-top: 5px;">
										<span id="codigo_venta" style="font-weight: bold;">Fecha Vencimiento: &nbsp;</span>
									</div>
									<div class="col-md-6" style="padding-left: 0; text-align: left;">


										<div class="input-group">
											<input type="text" name="fecha_vencimiento" id="fecha_vencimiento" readonly="readonly" class="form-control rounded-pill clsDatePicker" value="<?php echo date("Y-m-d", strtotime(date("Y-m-d"))) ?>">
											<span class="input-group-addon">
												<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group col-md-6">
									<label>Almacen</label>
									<p>Principal</p>
								</div>
								<div class="form-group col-md-6">
									<label>Cliente</label>
									<select class="form-control rounded-pill" id="lista_clientes" , name="lista_clientes">
										<option value="0">SELECCIONE ...</option>
									</select>
								</div>

								<div class="form-group col-md-12">
									<label>RUC Cliente</label>
									<div class="input-group mb-3" style="display: flex;">
										<input type="text" class="form-control rounded-pill-left" placeholder="RUC ..." id="nuevo_ruc" aria-label="Recipient's username" aria-describedby="basic-addon2">
										<div class="input-group-append">
											<button class="btn btn-outline-dark" type="button" onclick="buscar_ruc();"><i class="fa fa-search"></i></button>
										</div>
									</div>
								</div>

								<div class="col-md-12 form-group">
									<div id="resultado_ruc">

									</div>
								</div>

								<div class="form-group col-md-6">
									<label>Pago</label>
									<select class="form-control rounded-pill" id="tipos_pago" name="tipos_pago">

									</select>
								</div>
								<div class="form-group col-md-6">
									<label>Entrega</label>
									<select class="form-control rounded-pill" id="tipos_entrega" , name="tipos_entrega">

									</select>
								</div>
								<div class="form-group col-md-12" id="abono" hidden>
									<label>Abono</label>
									<input type="text" name="monto_abono" id="monto_abono" class="form-control rounded-pill" value="0">
								</div>
								<div class="form-group col-md-12">
									<label>Forma de Pago</label>
									<select class="form-control rounded-pill" id="forma_pago" name="forma_pago">

									</select>
								</div>
								<div class="form-group col-md-6">
									<label>Descuento (S/)</label>
									<input type="text" name="descuento" id="descuento" class="form-control rounded-pill" value="0">
								</div>
								<div class="form-group col-md-6">
									<label>Subtotal (S/)</label>
									<input type="text" name="subtotal" id="subtotal" class="form-control rounded-pill">
								</div>
								<div class="form-group col-md-12">
									<label>Detalle Descuento</label>
									<textarea class="form-control rounded-pill" id="desc_descuento" name="desc_descuento"></textarea>
								</div>
								<div class="form-group col-md-12">
									<fieldset>
										<legend>Aplicar Detracción</legend>
										<label class="radio-inline"><input type="radio" name="detraccion" value="no" id="detraccion_no" checked>No</label>
										<label class="radio-inline"><input type="radio" name="detraccion" id="detraccion_yes" value="yes">Sí</label>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="box box-primary">
								<table class="table table-bordered">
									<tbody>
										<tr>
											<th scope="row">
												Subtotal:
											</th>
											<td id="">
												<span id="subtotal_detalle_span">S/ </span>
												<input type="hidden" name="" readonly id="subtotal_detalle_td" class="form-control rounded-pill">
											</td>
										</tr>

										<tr id="igv_detalle" hidden>
											<th scope="row">
												IGV:
											</th>
											<td id="">
												<span id="igv_detalle_span">S/ </span>
												<input type="hidden" name="" readonly id="igv_detalle_td" class="form-control rounded-pill">
											</td>
										</tr>
										<tr id="detra" hidden>
											<th scope="row">
												Detraccion:
											</th>
											<td id="">
												<span id="detraccion_span">S/ </span>
												<input type="hidden" name="" readonly id="detraccion_td" class="form-control rounded-pill">
											</td>
										</tr>
										<tr id="igv_pa" hidden>
											<th scope="row">
												IGV a Pagar:
											</th>
											<td id="">
												<span id="igv_p_span">S/ </span>
												<input type="hidden" name="" readonly id="igv_p_td" class="form-control rounded-pill">
											</td>
										</tr>
										<tr>
											<th scope="row">
												Total:
											</th>
											<td id="">
												<span id="total_detalle_span">S/ </span>
												<input type="hidden" name="" readonly id="total_detalle_td" class="form-control rounded-pill">
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<button class="btn btn-danger rounded-pill" onclick="cancelar_venta();">Cancelar</button>
							<button class="btn btn-success rounded-pill" id="guardar_venta">Guardar Venta</button>
						</div>
					</div>
				</div>
			</div>

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

			<script>
				var _subtotal = 0;
				var ids_venta = 0;

				function buscar_ruc() {
					$("#resultado_ruc").empty();
					$("#resultado_ruc").append('<span class="badge badge-warning">Buscando</span>');

					$.get("https://dbusinessaqp.com/api_ruc/api.php", {
						ruc: $("#nuevo_ruc").val()
					}, function(response) {
						//var obj = JSON.parse(response);
						var obj = response;
						$("#resultado_ruc").empty();
						if (obj.error === undefined) {
							$("#resultado_ruc").append('<span class="badge badge-success">' + $("#nuevo_ruc").val() + ' - ' + obj.nombre + '</span>');
						} else {
							$("#resultado_ruc").append('<span class="badge badge-danger">' + $("#nuevo_ruc").val() + ' - ' + obj.error + '</span>');
						}

					});
				}

				function cancelar_venta() {
					$("#resultado_busqueda_order").attr('hidden', 'true');
					$("#tabla_lista_venta").find('tbody').empty();
					$("#subtotal").val("");
					$("#subtotal_detalle_span").val("");
					$("#subtotal_detalle_td").val("");
					$("#total_detalle_span").val("");
					$("#total_detalle_td").val("");
					$("#igv_p_td").val("");
					$("#igv_p_span").text("");
					$("#txt_guia").text("");
					$("#txt_guia").val("");
					$("#txt_pedido").text("");

					$("#detraccion_td").val("");
					$("#detraccion_span").text("");

					$("#igv_detalle_td").val("");
					$("#igv_detalle_span").text("");

					$("#subtotal_detalle_td").val("");
					$("#subtotal_detalle_span").text("");

					$("#total_detalle_span").text("");
					$("#total_detalle_td").val("");
					_subtotal = 0;
					//$("#radio_1").
					$("#detraccion_no").prop("checked", true);
					//$("input:radio[name=edad]").change();
					$("#detra").attr('hidden', true);
					$("#igv_pa").attr('hidden', true);
					$("#detraccion_td").val('');
					$("#detraccion_span").text($("#detraccion_td").val());
					//$("#igv_detalle_td").val('');
					$("#igv_p_span").text($("#igv_detalle_td").val());
				}

				function eliminar_producto_venta(id) {
					$("#ocul_" + id).attr('hidden', true);
					$("#pro_" + id).removeClass('id_producto');

					$("#subtotal").val((parseFloat($("#subtotal").val()) - parseFloat($("#tot_" + id).html())).toFixed(2));
					console.log($("#subtotal").val());
					console.log("MONTO A RESTAR" + $("#tot_" + id).text());

					_subtotal = $("#subtotal").val();
					if ($("#tipos_documento").val() == 2) {
						calcular_montos($("#subtotal").val(), $("#descuento").val(), true);
					} else {
						calcular_montos($("#subtotal").val(), $("#descuento").val(), false);
					}
					$("input[name=detraccion]").click();
					$("#btn_borrar_" + id).closest('tr').remove();
				}

				function agregar_producto_lista(id) {

					var descripcion = "";
					if ($("#s_p_" + id).val() == 1) {
						descripcion = "SERVICIO DE CONFECCION ";
					}

					var p_b = 0;
					p_b = parseFloat($("#canti_" + id).val() * parseFloat($("#pre_bor_" + id).val()));
					_subtotal = parseFloat(_subtotal) + parseFloat($("#canti_" + id).val() * parseFloat($("#precio_" + id).val())) + parseFloat(p_b);

					$.get('core/app/view/order.php', {
						parAccion: 'detalle_producto',
						codigo: id
					}, function(data) {
						var obj = JSON.parse(data);
						$("#tabla_lista_venta").removeAttr('hidden');
						$("#div_lista_ventas").removeAttr('hidden');
						$("#tabla_lista_venta").find('tbody').append('<tr id="ocul_' + id + '">' +
							'<td class="id_producto" id="pro_' + id + '" hidden>' + obj.Records.id + '</td>' +
							'<td class="cantidad_producto">' + $("#canti_" + id).val() + '</td>' +
							'<td class="unidades_producto_r">' + $("#unit_" + id).val() + '</td>' +
							'<td class="unidades_producto">' + obj.Records.code + '</td>' +
							'<td class="pedido_producto">' + $("#txt_pedido_" + id).val() + '</td>' +
							'<td class="descripcion_producto">' + descripcion + $("#nombre_pro_id_" + id).val() + '</td>' +
							'<td>S/ <span class="precio_producto">' + $("#precio_" + id).val() + '</span></td>' +
							'<td>S/ <span class="precio_bordado">' + p_b.toFixed(2) + '</span></td>' +
							'<td scope="row"> S/ <span id="tot_' + id + '">' + ($("#canti_" + id).val() * parseFloat($("#precio_" + id).val()) + parseFloat(p_b)).toFixed(2) + '</span></td>' +
							'<td><a href="javascript:eliminar_producto_venta(' + id + ');" class="btn btn-xs btn-danger borrar" id="btn_borrar_' + id + '"><i class="fa fa-trash"></i></a></td>' +
							'</tr>');
					});
					//$("#td_"+id).addClass('seleccion_venta');
					console.log('A SUMAR ' + _subtotal);
					console.log('A SUMAR 2 ' + _subtotal.toFixed(2));
					$("#subtotal").val(parseFloat(_subtotal).toFixed(2));
					if ($("#tipos_documento").val() == 2) {
						calcular_montos($("#subtotal").val(), $("#descuento").val(), true);
					} else {
						calcular_montos($("#subtotal").val(), $("#descuento").val(), false);
					}
					$("input[name=detraccion]").click();
				}

				function calcular_montos(subtotal, descuento, igv) {
					var subtotal_ = 0;
					var igv_ = 0;
					var total_ = 0;
					if (igv) {
						if ($("#incluye_igv").val() == 1) {
							total_ = parseFloat($("#subtotal").val()) - parseFloat(descuento);
							subtotal_ = total_ / parseFloat(1.18);
							console.log("IGV _ : " + igv_);
							igv_ = total_ - subtotal_;

							$("#subtotal_detalle_td").val('' + ((subtotal_)).toFixed(2));
							$("#igv_detalle_td").val(parseFloat(igv_).toFixed(2));
							$("#total_detalle_td").val('' + (total_).toFixed(2));
							$("#subtotal_detalle_span").text('S/ ' + ((subtotal_)).toFixed(2));
							$("#igv_detalle_span").text('S/ ' + (igv_).toFixed(2));
							$("#total_detalle_span").text('S/ ' + (total_).toFixed(2));
						} else {
							subtotal_ = parseFloat($("#subtotal").val()) - parseFloat(descuento);
							igv_ = subtotal_ * parseFloat(0.18);
							console.log("IGV _ : " + igv_);
							total_ = subtotal_ + igv_;
							$("#subtotal_detalle_td").val('' + ((subtotal_)).toFixed(2));
							$("#igv_detalle_td").val(parseFloat(igv_).toFixed(2));
							$("#total_detalle_td").val('' + (total_).toFixed(2));
							$("#subtotal_detalle_span").text('S/ ' + ((subtotal_)).toFixed(2));
							$("#igv_detalle_span").text('S/ ' + (igv_).toFixed(2));
							$("#total_detalle_span").text('S/ ' + (total_).toFixed(2));
						}

					} else {
						subtotal_ = parseFloat($("#subtotal").val()) - parseFloat(descuento);
						total_ = subtotal_ + igv_;
						$("#subtotal_detalle_td").val('' + ((subtotal_)).toFixed(2));
						$("#igv_detalle_td").val('' + (igv_).toFixed(2));
						$("#total_detalle_td").val('' + (total_).toFixed(2));
						$("#subtotal_detalle_span").text('S/ ' + ((subtotal_)).toFixed(2));
						$("#igv_detalle_span").text('S/ ' + (igv_).toFixed(2));
						$("#total_detalle_span").text('S/ ' + (total_).toFixed(2));
					}
					if (total_ >= parseFloat(700)) {
						$("#detraccion_yes").attr('checked');
					} else {
						$("#detraccion_no").attr('checked');
					}
				}
				$(document).ready(function() {
					$('#fecha_vencimiento').datepicker({
						dateFormat: 'yy-mm-dd',
						changeMonth: true,
						changeYear: true,
						altField: "#fecha_nacimiento_hidden",
						altFormat: "yy-mm-dd"
					});
					$("#descuento").on('input', function() {
						if ($("#tipos_documento").val() == 2) {
							calcular_montos($("#subtotal").val(), $(this).val(), true);
						} else {
							calcular_montos($("#subtotal").val(), $(this).val(), false);
						}
					}).on('change', function() {
						if ($("#tipos_documento").val() == 2) {
							calcular_montos($("#subtotal").val(), $(this).val(), true);
						} else {
							calcular_montos($("#subtotal").val(), $(this).val(), false);
						}
					});
					var flag = false;
					$("#tipos_documento").on('change', function() {
						var code = $("#tipos_documento").val();
						if (code == 2) {
							$("#igv_detalle").removeAttr('hidden');
							flag = true;
							calcular_montos($("#subtotal").val(), $("#descuento").val(), flag);
						} else {
							$("#igv_detalle").attr('hidden', true);
							flag = false;
							calcular_montos($("#subtotal").val(), $("#descuento").val(), flag);
						}
					});
					$("#tipos_pago").on('change', function() {
						var code = $("#tipos_pago").val();
						//para colocar el abono
						if (code == 4) {
							$("#abono").removeAttr('hidden');
						} else {
							$("#abono").attr('hidden', true);
						}
					});
					$("#searchp").on("submit", function(e) {
						$("#detalle_busqueda_order").find('tbody').empty();
						var total = 0;
						var subtotal = 0;
						var igv = 0;
						e.preventDefault();
						code = $("#product_code").val();
						name = $("#product_name").val();
						order = $("#order_pedido").val();

						$.post("core/app/view/venta.php?parAccion=get_aux", function(data) {
							var obj = JSON.parse(data);
							$("#txt_cod_venta").val("F001-" + (parseInt(obj.Records[0].id) + parseInt(1)))
						});

						if (order != "") {
							$.get('core/app/view/order.php', {
								parAccion: 'lista_detalle',
								codigo: order
							}, function(data) {
								$("#resultado_busqueda_order").removeAttr('hidden');
								var obj = JSON.parse(data);
								$.each(obj.Records, function(index, val) {
									$("#detalle_busqueda_order").find('tbody').append('<tr><td class="id_producto">' + val.id + '</td><td class="cantidad_producto">' + val.cantidad + '</td><td><input type="text" class="form-control rounded-pill unidades_producto" id="unit_' + val.id + '" value="' + val.unit + '"/></td><td>' + val.name + '</td><td>S/ <span class="precio_producto">' + val.price_in + '</span></td><td scope="row"> S/ ' + val.total + '</td></tr>');
									subtotal = parseFloat(subtotal) + parseFloat(val.total);
								});
								$("#subtotal").val(subtotal.toFixed(2));
								calcular_montos($("#subtotal").val(), $("#descuento").val(), flag);
							});
						} else {
							if (name != "") {
								if ($("#category_id").val() == 0) {
									$.get('core/app/view/cotizacion.php', {
										parAccion: 'busqueda_productos',
										producto: name
									}, function(data) {
										var obj = JSON.parse(data);
										if (obj.Records.length > 0) {
											$("#resultado_busqueda_order").removeAttr('hidden');
											$.each(obj.Records, function(index, val) {
												$("#detalle_busqueda_order").find('tbody').append('<tr id="td_' + val.id + '">' +
													`<td id="s_p_id_` + val.id + `">
							                        	<select class="form-control rounded-pill" id="s_p_` + val.id + `">
							                        		<option value="0">Producto</option>
							                        		<option value="1">Servicio</option>
							                        	</select>
							                        </td>` +
													'<td id="id_' + val.id + '">' + val.id + '</td>' +
													'<td><input type="text" class="form-control rounded-pill" name="canti_' + val.id + '" id="canti_' + val.id + '" value="1"><span hidden class="cantidad_producto">' + val.id + '</span></td>' +
													`<td>
														<input type="text" name="txt_pedido_${val.id}" id="txt_pedido_${val.id}" class="form-control rounded-pill">
													</td>` +
													'<td>' + val.name + '</td>' +
													'<td><input type="text" class="form-control rounded-pill unidades_producto" id="unit_' + val.id + '" value="' + val.unit + '"/></td>' +
													'<td><b>S/ <span id="precio_' + val.id + '" class="precio_producto">' + parseFloat(val.price_in).toFixed(4) + '</span></b></td>' +
													'<td></td>' +
													'<td><button class="btn btn-outline-success btn-sm rounded-pill" onclick="agregar_producto_lista(' + val.id + ')"><i class="glyphicon glyphicon-plus"></i></button></td>' +
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
								} else {
									$.get('core/app/view/cotizacion.php', {
										parAccion: 'busqueda_productos_2',
										producto: name
									}, function(data) {
										$("#resultado_busqueda_order").removeAttr('hidden');
										var obj = JSON.parse(data);
										if (obj.Records.length > 0) {
											var html = "";
											$.each(obj.Records, function(index, val) {
												html = html + '<tr id="td_' + val.id + '">' +
													'<td><input type="text" class="form-control rounded-pill" name="canti_' + val.id + '" id="canti_' + val.id + '" value="1" style="width: 50px;"><span hidden class="cantidad_producto">' + val.id + '</span></td>' +
													'<td><input type="text" class="form-control rounded-pill unidades_producto" id="unit_' + val.id + '" value="' + val.unit + '"/></td>' +
													`<td>
														<input type="text" name="txt_pedido_${val.id}" id="txt_pedido_${val.id}" class="form-control rounded-pill">
													</td>` +
													'<td id="id_' + val.id + '">' + val.code + '</td>' +
													`<td id="s_p_id_` + val.id + `">
							                        	<select class="form-control rounded-pill" id="s_p_` + val.id + `">
							                        		<option value="0">Producto</option>
							                        		<option value="1">Servicio</option>
							                        	</select>
							                        </td>` +
													'<td><textarea class="form-control rounded-pill" id="nombre_pro_id_' + val.id + '">' + val.name + '</textarea></td>' +
													'<td><input type="text" class="form-control rounded-pill" id="precio_' + val.id + '" value="' + parseFloat(val.price_in).toFixed(4) + '" /><!--<select class="form-control rounded-pill" name="" id="precio_' + val.id + '">';
												$.each(val.precios, function(i, v) {
													console.log(v);
													html = html + '<option value="' + v + '">' + v + '</option>';
												});
												html = html + '</select>--></td>' +
													'<td><input class="form-control rounded-pill" id="pre_bor_' + val.id + '" value="' + val.prebor_out + '" ></td>' +
													'<td><button class="btn btn-outline-success btn-sm rounded-pill" onclick="agregar_producto_lista(' + val.id + ')"><i class="glyphicon glyphicon-plus"></i></button></td>' +
													'</tr>';
											});
											$("#detalle_busqueda_order").find('tbody').append(html);
										} else {
											bootbox.alert({
												message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
													'<strong>Ningún producto encontrado.</strong>' +
													'</div>'
											});
										}
									});
								}
							} else if (code != "") {
								$.get('core/app/view/cotizacion.php', {
									parAccion: 'busqueda_productos_barcode',
									barcode: code
								}, function(data) {
									$("#resultado_busqueda_order").removeAttr('hidden');
									var obj = JSON.parse(data);
									if (obj.Records.length > 0) {
										var html = "";
										$.each(obj.Records, function(index, val) {
											html = html + '<tr id="td_' + val.id + '">' +
												'<td><input type="text" class="form-control rounded-pill" name="canti_' + val.id + '" id="canti_' + val.id + '" value="1" style="width: 50px;"><span hidden class="cantidad_producto">' + val.id + '</span></td>' +
												'<td><input type="text" class="form-control rounded-pill unidades_producto" id="unit_' + val.id + '" value="' + val.unit + '"/></td>' +
												`<td>
													<input type="text" name="txt_pedido_${val.id}" id="txt_pedido_${val.id}" class="form-control rounded-pill">
												</td>` +
												'<td id="id_' + val.id + '">' + val.code + '</td>' +
												`<td id="s_p_id_` + val.id + `">
						                        	<select class="form-control rounded-pill" id="s_p_` + val.id + `">
						                        		<option value="0">Producto</option>
						                        		<option value="1">Servicio</option>
						                        	</select>
						                        </td>` +
												'<td><textarea class="form-control rounded-pill" id="nombre_pro_id_' + val.id + '">' + val.name + '</textarea></td>' +
												'<td><input type="text" class="form-control rounded-pill" id="precio_' + val.id + '" value="' + parseFloat(val.price_in).toFixed(4) + '" /><!--<select class="form-control rounded-pill" name="" id="precio_' + val.id + '">';
											$.each(val.precios, function(i, v) {
												console.log(v);
												html = html + '<option value="' + v + '">' + v + '</option>';
											});
											html = html + '</select>--></td>' +
												'<td><input class="form-control rounded-pill" id="pre_bor_' + val.id + '" value="' + val.prebor_out + '" ></td>' +
												'<td><button class="btn btn-outline-success rounded-pill btn-sm" onclick="agregar_producto_lista(' + val.id + ')"><i class="glyphicon glyphicon-plus"></i></button></td>' +
												'</tr>';
										});
										$("#detalle_busqueda_order").find('tbody').append(html);
									} else {
										bootbox.alert({
											message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
												'<strong>Ningún producto encontrado.</strong>' +
												'</div>'
										});
									}
								});
							} else {
								bootbox.alert({
									message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Elegir algún criterio de búsqueda.</strong>' +
										'</div>'
								});
							}
						}
						$("#product_code").val('');
						$("#product_name").val('');
						$("#order_pedido").val('');
						$.get('core/app/view/order.php', {
							parAccion: 'lista_clientes'
						}, function(data) {
							$("#lista_clientes").empty();
							$("#lista_clientes").append('<option value="0">SELECCIONE ...</option>');
							var obj = JSON.parse(data);
							$.each(obj.Records, function(index, val) {
								$("#lista_clientes").append('<option value="' + val.id + '">' + val.name + '</option>');
							});
						});
						$.get('core/app/view/venta.php', {
							parAccion: 'tipos_pago'
						}, function(data) {
							$("#tipos_pago").empty();
							var obj = JSON.parse(data);
							$.each(obj.Records, function(index, val) {
								if (val.id == 4) {
									$("#tipos_pago").append('<option value="' + val.id + '" selected>' + val.name + '</option>');
								} else {
									$("#tipos_pago").append('<option value="' + val.id + '">' + val.name + '</option>');
								}
							});
						});
						$.get('core/app/view/venta.php', {
							parAccion: 'tipos_entrega'
						}, function(data) {
							$("#tipos_entrega").empty();
							var obj = JSON.parse(data);
							$.each(obj.Records, function(index, val) {
								$("#tipos_entrega").append('<option value="' + val.id + '">' + val.name + '</option>');
							});
						});
						$.get('core/app/view/venta.php', {
							parAccion: 'tipos_documento'
						}, function(data) {
							$("#tipos_documento").empty();
							var obj = JSON.parse(data);
							$.each(obj.Records, function(index, val) {
								$("#tipos_documento").append('<option value="' + val.id + '">' + val.tipo_documento + '</option>');
							});

							$("#tipos_documento").val("2");
							$("#tipos_documento").change();
						});
						$.get('core/app/view/venta.php', {
							parAccion: 'forma_pago'
						}, function(data) {
							$("#forma_pago").empty();
							var obj = JSON.parse(data);
							$.each(obj.Records, function(index, val) {
								if (val.id == 2) {
									$("#forma_pago").append('<option value="' + val.id + '" selected>' + val.name + '</option>');
								} else {
									$("#forma_pago").append('<option value="' + val.id + '">' + val.name + '</option>');
								}

							});
						});

						$("#subtotal_detalle_td").text(subtotal.toFixed(2));
						$("#igv_detalle_td").text(igv.toFixed(2));
						$("#total_detalle_td").text((parseFloat(subtotal) + parseFloat(igv)).toFixed(2));
					});
				});

				var dett = 'no';
				$(document).ready(function() {
					$("input[name=detraccion]").click(function() {
						if ($(this).val() == 'yes') {
							dett = 'yes';
							var tt = $("#total_detalle_td").val();
							var gb = $("#igv_detalle_td").val();

							var dt = Math.round(parseFloat(tt) * parseFloat(10 / 100));

							console.log("TOTAL: " + tt);
							console.log("IGV: " + gb);
							console.log("DETRACCION: " + dt);
							console.log("IGV PAGAR: " + parseFloat(gb) - parseFloat(dt));

							$("#detraccion_td").val(parseFloat(dt).toFixed(2));
							$("#detraccion_span").text("S/ " + $("#detraccion_td").val());

							$("#igv_p_td").val(parseFloat(gb) - parseFloat(dt));

							$("#igv_p_span").text("S/ " + parseFloat($("#igv_p_td").val()).toFixed(2));
							$("#detra").removeAttr('hidden');
							$("#igv_pa").removeAttr('hidden');
						} else {
							dett = 'no';
							$("#detra").attr('hidden', true);
							$("#igv_pa").attr('hidden', true);
							$("#detraccion_td").val('');
							$("#detraccion_span").text($("#detraccion_td").val());
							//$("#igv_detalle_td").val('');
							$("#igv_p_span").text($("#igv_detalle_td").val());
						}
					});
					$("#guardar_venta").click(function() {
						if ($("#lista_clientes").val() == 0 && $("#nuevo_ruc").val() == "") {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Seleccionar un cliente.</strong>' +
									'</div>'
							});
						} else {
							if ($("#txt_cod_venta").val() == "") {
								bootbox.alert({
									message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Ingresar Codigo de Venta.</strong>' +
										'</div>'
								});
							} else {
								var ids = 0;
								var precios = 0;
								var unidades = 0;
								var unidades_r = 0;
								var cantidades = 0;
								var descripciones = 0;
								var p_b_s = 0;
								var n_pedidos = 0;

								$(".id_producto").parent("tr").find(".id_producto").each(function() {
									ids = ids + ',' + $(this).html();
								});
								$(".id_producto").parent("tr").find(".precio_bordado").each(function() {
									p_b_s = p_b_s + ',' + $(this).html();
								});
								$(".id_producto").parent("tr").find(".precio_producto").each(function() {
									precios = precios + ',' + $(this).html();
								});

								$(".id_producto").parent("tr").find(".unidades_producto").each(function() {
									unidades = unidades + ',' + $(this).html();
								});

								$(".id_producto").parent("tr").find(".unidades_producto_r").each(function() {
									unidades_r = unidades_r + ',' + $(this).html();
								});

								$(".id_producto").parent("tr").find(".cantidad_producto").each(function() {
									cantidades = cantidades + ',' + $(this).html();
								});

								$(".id_producto").parent("tr").find(".pedido_producto").each(function() {
									n_pedidos = n_pedidos + '--' + $(this).html();
								});
								$(".id_producto").parent("tr").find(".descripcion_producto").each(function() {
									descripciones = descripciones + '--' + $(this).html();
								});

								$.get('core/app/view/venta.php', {
									parAccion: 'insertar_venta',
									tipos_documento: $("#tipos_documento").val(),
									almacen: 'principal',
									lista_clientes: $("#lista_clientes").val(),
									tipos_pago: $("#tipos_pago").val(),
									tipos_entrega: $("#tipos_entrega").val(),
									forma_pago: $("#forma_pago").val(),
									descuento: $("#descuento").val(),
									subtotal: $("#subtotal_detalle_td").val(),

									pagado: $("#monto_abono").val(),
									a_cuenta: parseFloat($("#total_detalle_td").val()) - parseFloat($("#monto_abono").val()),
									igv: $("#igv_detalle_td").val(),
									total: $("#total_detalle_td").val(),
									detraccion: $('input:radio[name=detraccion]:checked').val(),
									ids: ids,
									precios: precios,
									unidades: unidades,
									unidades_r: unidades_r,
									cantidades: cantidades,
									n_pedidos: n_pedidos,
									cod_venta: $("#txt_cod_venta").val(),
									guia: $("#txt_guia").val(),
									fecha_emision: $("#fecha_emision").val(),
									detraccion_p: $("#detraccion_td").val(),
									igv_p: $("#igv_p_td").val(),
									p_b: p_b_s,
									pedido: $("#txt_pedido").val(),
									descripciones: descripciones,
									nuevo_ruc: $("#nuevo_ruc").val(),
									fecha_vencimiento: $("#fecha_vencimiento").val(),
									incluye_igv: $("#incluye_igv").val(),
									desc_descuento: $("#desc_descuento").val()
								}, function(data_) {
									var obj_ = JSON.parse(data_);
									if (obj_.Result == 'OK') {
										bootbox.alert({
											message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
												'<strong>Venta registrada correctamente.</strong>' +
												'</div>'
										});
										cancelar_venta();
									} else {
										bootbox.alert({
											message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
												'<strong>No se pudo guardar esta venta.</strong>' +
												'</div>'
										});
									}
								});
							}
						}
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

			<div id="cartofsell"></div>
		</div>
</section>
<script>
	$(document).ready(function() {
		$.get("./?action=cartofsell", null, function(data) {
			$("#cartofsell").html(data);
		});
	});
</script>
