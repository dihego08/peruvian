<script type="text/javascript" src="https://cdn.datatables.net/tabletools/2.2.4/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>

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

	.header_fijo {
		table-layout: fixed;
		border-collapse: collapse;
	}

	.header_fijo thead tr {
		display: block;
		position: relative;
	}

	.header_fijo tbody {
		display: block;
		overflow: auto;
		width: 100%;
		height: 300px;
	}

	.header_fijo td {
		width: 6%;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">

			<?php
			if (isset($_SESSION["client_id"])) : ?>
				<h3><i class='glyphicon glyphicon-shopping-cart'></i> Mis Compras</h3>
			<?php else : ?>
				<h3><i class='glyphicon glyphicon-shopping-cart'></i> Ventas</h3>
			<?php endif; ?>
			<div class="clearfix"></div>

			<?php
			$products = null;
			// print_r(Core::$user);
			if (isset($_SESSION["user_id"])) {
				if (Core::$user->kind == 3) {
					$products = SellData::getAllBySQL(" where user_id=" . Core::$user->id . " and operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 order by created_at desc");
				} else if (Core::$user->kind == 2) {
					$products = SellData::getAllBySQL(" where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and stock_to_id=" . Core::$user->stock_id . " order by created_at desc");
				} else {
					$products = SellData::getSells();
				}
			} else if (isset($_SESSION["client_id"])) {
				$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 order by created_at desc");
			}
			?>

			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Lista General de Ventas</h3>
				</div>
				<div class="box-body">
					<fieldset>
						<legend>Filtros de Búsqueda</legend>
						<div class="form-group col-md-4">
							<label>Por Pago</label>
							<select class="form-control rounded-pill" name="tipos_pago" id="tipos_pago">
								<option value="0">SELECCIONA ...</option>
							</select>
						</div>
						<div class="form-group col-md-4">
							<label>Por Entrega</label>
							<select class="form-control rounded-pill" name="tipos_entrega" id="tipos_entrega">
								<option value="0">SELECCIONA ...</option>
							</select>
						</div>
						<div class="form-group col-md-4">
							<label>Por Cliente</label>
							<select class="form-control rounded-pill" name="combo_cliente" id="combo_cliente">
								<option value="0">SELECCIONA ...</option>
							</select>
						</div>
					</fieldset>
					<fieldset>
						<legend>Filtro por Fecha</legend>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="fecha_desde">Desde:</label>
								<div class="input-group">
									<input type="text" name="fecha_desde" id="fecha_desde" readonly="readonly" class="form-control clsDatePicker">
									<span class="input-group-addon">
										<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
									</span>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="fecha_hasta">Hasta:</label>
								<div class="input-group">
									<input type="text" name="fecha_hasta" id="fecha_hasta" readonly="readonly" class="form-control clsDatePicker">
									<span class="input-group-addon">
										<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
									</span>
								</div>
							</div>
							<div class="form-group col-md-12 text-center">
								<button class="btn btn-success rounded-pill" onclick="buscar_por_fecha();">Buscar por Fecha</button>
							</div>
						</div>
					</fieldset>
					<div class="box box-primary table-responsive">
						<tr>
							<td colspan="15"></td>
						<tr>
							<table class="table table-bordered table-hover table-responsive header_fijo" id="tabla_ventas" style="font-size: 11px">
								<thead>
									<tr>
										<td>Cliente</td>
										<td>N° Documento</td>
										<td>Fecha</td>
										<td>V. Venta</td>
										<td>P. Venta</td>
										<td>IGV</td>
										<td>Detrac.</td>
										<td>IGV x Pagar</td>
										<td>Renta 3ra Cat.</td>
										<td>Valor a Pagar</td>
										<td>Fecha Pago</td>
										<td>Entidad</td>
										<td>Fecha Detraccion</td>
										<td>Guia</td>
										<td>Actualizar</td>
										<td>Anular</td>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
							<a href="" class="pull-right btn btn-info rounded-pill" id="reportar" target="_blanck">Exportar</a>
					</div>
				</div>
			</div>
			<div id="popup_editar" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar"><img src="../css/images/close.png" /></a></div>
					<div>
						<h2 id="titulo_detalle">Detalle de Venta</h2>
						<div class="box box-primary">
							<table class="table table-bordered table-hover" id="tabla_detalle">
								<thead>
									<tr>
										<th>Producto</th>
										<th>Cantidad</th>
										<th>Unidad</th>
										<th>Precio Unitario</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<div class="box box-secundary" id="div_pagado" style="overflow: hidden;">
							<div class="form-group col-md-6">
								<label>Pagado (S/.)</label>
								<input type="text" name="pagado" id="pagado" class="form-control">
							</div>
							<div class="form-group col-md-6">
								<label>A Deuda (S/.)</label>
								<input type="text" name="a_deuda" id="a_deuda" class="form-control" readonly>
							</div>
							<button class="btn btn-primary" style="width: 100%;" id="boton_actualizar_pago">Actualizar Pago</button>
						</div>
						<div class="box box-secundary">
							<table class="table table-bordered">
								<tbody>
									<tr>
										<th scope="row">
											Subtotal:
										</th>
										<td id="">
											<span id="subtotal_detalle_span">S/. </span>
											<input type="hidden" name="" readonly id="subtotal_detalle_td" class="form-control">
										</td>
									</tr>
									<tr id="igv_detalle">
										<th scope="row">
											IGV:
										</th>
										<td id="">
											<span id="igv_detalle_span">S/. </span>
											<input type="hidden" name="" readonly id="igv_detalle_td" class="form-control">
										</td>
									</tr>
									<tr>
										<th scope="row">
											Total:
										</th>
										<td id="">
											<span id="total_detalle_span">S/. </span>
											<input type="hidden" name="" readonly id="total_detalle_td" class="form-control">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
						<!--<button type="submit" class="btn btn-success" style="float: right;" id="btn_formulario">Actualizar</button>-->
					</div>
				</div>
			</div>
			<div class="popup-overlay"></div>

			<div class="clearfix"></div>


		</div>
	</div>

</section>
<script type="text/javascript">
	function cerrar_editar() {
		$('#close_editar').click();
	}

	function exportar() {

	}

	function buscar_por_fecha() {
		var desde = $("#fecha_desde").val();
		var hasta = $("#fecha_hasta").val();
		$("#reportar").attr('href', 'report/sells-sunat-xlsx.php?desde=' + desde + '&hasta=' + hasta);
		//$("#reportar").attr('href', 'http://peruviandress.com/sivecsol/core/app/view/generar_pdf.php?filtro=fecha&tabla=ventas&desde='+desde+'&hasta='+hasta);
		$.get('core/app/view/venta.php', {
			parAccion: 'buscar_por_fecha_s',
			desde: $("#fecha_desde").val(),
			hasta: $("#fecha_hasta").val()
		}, function(data) {
			var obj = JSON.parse(data);
			$("#tabla_ventas").find('tbody').empty();
			$.each(obj.Records, function(index, val) {
				var cl = "";
				var hd = "";
				if (val.id_estado_entrega == 4) {
					cl = 'class="danger"';
					hd = "hidden";
				} else {
					cl = 'class=""';
				}
				$("#tabla_ventas").find('tbody').append('<tr ' + cl + '>' +
					'<td>' + val.person + '</td>' +
					'<td><input class="form-control" style="padding: 1px; font-size: 11px;" id="cod_' + val.codigo_venta + '" value="' + val.codigo_venta + '"></td>' +
					'<td>' + val.fecha_creacion + '</td>' +
					'<td>' + val.subtotal + '</td>' +
					'<td>' + val.total + '</td>' +
					'<td>' + val.igv + '</td>' +
					'<td>' + val.detraccion_p + '</td>' +
					'<td>' + val.igv_p + '</td>' +
					'<td>' + (parseFloat(val.subtotal) * parseFloat('0.02')).toFixed(2) + '</td>' +
					'<td>' + (parseFloat(val.total) - parseFloat(val.detraccion_p)).toFixed(2) + '</td>' +
					'<td><input class="form-control" style="padding: 1px; font-size: 11px;" id="fecha_pago_' + val.codigo_venta + '" value="' + val.fecha_pago + '"></td>' +
					'<td><input class="form-control" style="padding: 1px; font-size: 11px;" id="entidad_' + val.codigo_venta + '" value="' + val.entidad + '"></td>' +
					'<td><input class="form-control" style="padding: 1px; font-size: 11px;" id="fecha_det_' + val.codigo_venta + '" value="' + val.fecha_detraccion + '"></td>' +
					'<td><input class="form-control" style="padding: 1px; font-size: 11px;" id="guia_' + val.codigo_venta + '" value="' + val.guia + '"></td>' +
					'<td><span class="btn btn-sm btn-outline-success rounded-pill" onclick="actualizar(\'' + val.codigo_venta + '\');"><i class="fa fa-plus"></i></span></td>' +
					'<td><span ' + hd + ' class="btn btn-sm btn-outline-danger rounded-pill" onclick="anular(\'' + val.codigo_venta + '\');"><i class="fa fa-close"></i></span></td>' +
					'</tr>');


			});

		});
	}

	function actualizar_pago(codigo) {
		$.get('core/app/view/venta.php', {
			parAccion: 'actualizar_pago',
			codigo: codigo,
			monto_pagado: $("#pagado").val(),
			monto_adeuda: $("#a_deuda").val()
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == 'OK') {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Pago Actualizado Correctamente.</strong>' +
						'</div>'
				});
				lista_ventas('ninguno', 0);
				detalle_venta(codigo);
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Ago ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}

	function detalle_venta(codigo) {
		$("#tabla_detalle").find('tbody').empty();
		$("#pagado").val('');
		$.get('core/app/view/venta.php', {
			parAccion: 'lista_detalle',
			codigo: codigo
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#tabla_detalle").find('tbody').append('<tr><th scope="row">' + val.name + '</th><td>' + val.cantidad + '</td><td>' + val.codigo_unidad + '</td><td>' + val.precio_unitario + '</td></tr>');
			});

			$("#subtotal_detalle_span").text('S/. ' + obj.venta.subtotal);
			$("#igv_detalle_span").text('S/. ' + obj.venta.igv);
			$("#total_detalle_span").text('S/. ' + obj.venta.total);

			if (obj.venta.pagado == obj.venta.total) {
				$("#div_pagado").attr('hidden', true);
				$("#a_deuda").val(obj.venta.a_cuenta);
			} else {
				$("#a_deuda").val(obj.venta.a_cuenta);
			}
			$("#boton_actualizar_pago").attr('onclick', 'actualizar_pago(\'' + codigo + '\')');
		});

		$('#popup_editar').fadeIn('slow');
		$('.popup-overlay').fadeIn('slow');
		$('.popup-overlay').height($(window).height());
		return false;
	}

	function eliminar_venta(codigo) {
		bootbox.confirm({
			message: "¿Seguro de Eliminar esta Venta?",
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
							bootbox.alert({
								message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Eliminado Correctamente.</strong>' +
									'</div>'
							});
							lista_ventas('ninguno', 0);
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

	function lista_ventas(filtro, codigo) {
		$("#reportar").attr('href', 'report/sells-sunat-xlsx.php');
		//$("#reportar").attr('href', 'http://peruviandress.com/sivecsol/core/app/view/generar_pdf.php?filtro=extra&tabla=ventas');
		$.get('core/app/view/venta.php', {
			parAccion: 'lista_ventas_s',
			filtro: filtro,
			codigo: codigo
		}, function(data) {
			var obj = JSON.parse(data);
			$("#tabla_ventas").find('tbody').empty();
			$.each(obj.Records, function(index, val) {
				var cl = "";
				var hd = "";
				if (val.id_estado_entrega == 4) {
					cl = 'class="danger"';
					hd = "hidden";
				} else {
					cl = 'class=""';
				}
				$("#tabla_ventas").find('tbody').append('<tr ' + cl + '>' +
					'<td>' + val.person + '</td>' +
					'<td><input class="form-control rounded-pill" style="padding: 1px; font-size: 11px;" id="cod_' + val.codigo_venta + '" value="' + val.codigo_venta + '"></td>' +
					'<td>' + val.fecha_creacion + '</td>' +
					'<td>' + val.subtotal + '</td>' +
					'<td>' + val.total + '</td>' +
					'<td>' + val.igv + '</td>' +
					'<td>' + val.detraccion_p + '</td>' +
					'<td>' + val.igv_p + '</td>' +
					'<td>' + (parseFloat(val.subtotal) * parseFloat('0.02')).toFixed(2) + '</td>' +
					'<td>' + (parseFloat(val.total) - parseFloat(val.detraccion_p)).toFixed(2) + '</td>' +
					'<td><input class="form-control rounded-pill" style="padding: 1px; font-size: 11px;" id="fecha_pago_' + val.codigo_venta + '" value="' + val.fecha_pago + '"></td>' +
					'<td><input class="form-control rounded-pill" style="padding: 1px; font-size: 11px;" id="entidad_' + val.codigo_venta + '" value="' + val.entidad + '"></td>' +
					'<td><input class="form-control rounded-pill" style="padding: 1px; font-size: 11px;" id="fecha_det_' + val.codigo_venta + '" value="' + val.fecha_detraccion + '"></td>' +
					'<td><input class="form-control rounded-pill" style="padding: 1px; font-size: 11px;" id="guia_' + val.codigo_venta + '" value="' + val.guia + '"></td>' +
					'<td><span class="btn-sm btn btn-outline-success rounded-pill" onclick="actualizar(\'' + val.codigo_venta + '\');"><i class="fa fa-plus"></i></span></td>' +
					'<td><span ' + hd + ' class="btn-sm btn btn-outline-danger rounded-pill" onclick="anular(\'' + val.codigo_venta + '\');"><i class="fa fa-close"></i></span></td>' +
					'</tr>');

			});
		});
	}

	function anular(codigo) {
		$.get('core/app/view/venta.php', {
			parAccion: 'anular',
			codigo: codigo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == 'OK') {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Anulado Correctamente.</strong>' +
						'</div>'
				});
				lista_ventas('ninguno', 0);
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Ningún producto encontrado.</strong>' +
						'</div>'
				});
			}
		});
	}

	function actualizar(codigo) {
		var cod_n = $("#cod_" + codigo).val();
		var guia = $("#guia_" + codigo).val();
		var fecha_pago = $("#fecha_pago_" + codigo).val();
		var entidad = $("#entidad_" + codigo).val();
		var fecha_det = $("#fecha_det_" + codigo).val();

		$.get('core/app/view/venta.php', {
			parAccion: 'actualizar',
			cod_n: cod_n,
			codigo: codigo,
			guia: guia,
			fecha_pago: fecha_pago,
			entidad: entidad,
			fecha_det: fecha_det
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == 'OK') {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Actualizado Correctamente.</strong>' +
						'</div>'
				});
				lista_ventas('ninguno', 0);
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Ningún producto encontrado.</strong>' +
						'</div>'
				});
			}
		});
	}

	function calcular_deuda(pago_) {
		console.log(pago_);
		var deuda = parseFloat($("#a_deuda").val());
		var pago = parseFloat(pago_);
		var n_deuda = deuda - pago;
		console.log(n_deuda);
		$("#a_deuda").val(n_deuda.toFixed(2));
	}
	$(document).ready(function() {
		$('#fecha_desde').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});
		$('#fecha_hasta').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});
		$.get('core/app/view/venta.php', {
			parAccion: 'tipos_pago'
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#tipos_pago").append('<option value="' + val.id + '">' + val.name + '</option>');
			});
		});
		$.get('core/app/view/venta.php', {
			parAccion: 'tipos_entrega'
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#tipos_entrega").append('<option value="' + val.id + '">' + val.name + '</option>');
			});
		});


		$.get('core/app/view/order.php', {
			parAccion: 'lista_clientes'
		}, function(data) {
			$("#lista_clientes").empty();
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#combo_cliente").append('<option value="' + val.id + '">' + val.name + '</option>');
			});
		});


		$("#tipos_pago").on('change', function() {
			if ($("#tipos_pago").val() == 0) {
				lista_ventas('ninguno', 0);
			} else {
				lista_ventas('pago', $("#tipos_pago").val());
			}
		});

		$("#combo_cliente").on('change', function() {
			if ($("#combo_cliente").val() == 0) {
				lista_ventas('ninguno', 0);
			} else {
				lista_ventas('cliente', $("#combo_cliente").val());
			}
		});


		$("#tipos_entrega").on('change', function() {
			if ($("#tipos_entrega").val() == 0) {
				lista_ventas('ninguno', 0);
			} else {
				lista_ventas('entrega', $("#tipos_entrega").val());
			}
		});


		//lista_ventas(filtro, codigo);
		$("#pagado").on('input', function() {
			//$("#a_deuda").val(parseFloat($("#a_deuda").val()) - parseFloat($("#pagado").val()));
			//calcular_deuda($("#pagado").val());
		}).on('change', function() {
			//$("#a_deuda").val(parseFloat($("#a_deuda").val()) - parseFloat($("#pagado").val()));
			calcular_deuda($("#pagado").val());
		});

		lista_ventas('ninguno', 0);

		$('#close_editar').on('click', function() {
			//limpiar_formulario();
			$('#popup_editar').fadeOut('slow');
			$('.popup-overlay').fadeOut('slow');
			return false;
			flag = false;
		});
	});
</script>

<script type="text/javascript">
	function thePDF() {
		var doc = new jsPDF('p', 'pt');
		doc.setFontSize(26);
		doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val; ?>", 40, 65);
		doc.setFontSize(18);
		doc.text("VENTAS CANCELADAS", 40, 80);
		doc.setFontSize(12);
		doc.text("Usuario: <?php echo Core::$user->name . " " . Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s"); ?> ", 40, 90);
		var columns = [{
				title: "Id",
				dataKey: "id"
			},
			{
				title: "Cliente",
				dataKey: "client"
			},
			{
				title: "Total",
				dataKey: "total"
			},
			{
				title: "Estado de pago",
				dataKey: "p"
			},
			{
				title: "Estado de entrega",
				dataKey: "d"
			},
			{
				title: "Almacen",
				dataKey: "stock"
			},
			{
				title: "Fecha",
				dataKey: "created_at"
			},
		];
		var rows = [
			<?php foreach ($products as $sell) :
			?> {
					"id": "<?php echo $sell->id; ?>",
					"client": "<?php if ($sell->person_id != null) {
									$c = $sell->getPerson();
									echo $c->name . " " . $c->lastname;
								} ?>",
					"total": "<?php
								$total = $sell->total - $sell->discount;
								echo Core::$symbol . " " . number_format($total, 2, ".", ",");
								?>	",
					"p": "<?php echo $sell->getP()->name; ?>",
					"d": "<?php echo $sell->getD()->name; ?>",
					"stock": "<?php echo $sell->getStockTo()->name; ?>",
					"created_at": "<?php echo $sell->created_at; ?>",
				},
			<?php endforeach; ?>
		];
		doc.autoTable(columns, rows, {
			theme: 'grid',
			overflow: 'linebreak',
			styles: {
				fillColor: <?php echo Core::$pdf_table_fillcolor; ?>
			},
			columnStyles: {
				id: {
					fillColor: <?php echo Core::$pdf_table_column_fillcolor; ?>
				}
			},
			margin: {
				top: 100
			},
			afterPageContent: function(data) {}
		});
		doc.setFontSize(12);
		doc.text("<?php echo Core::$pdf_footer; ?>", 40, doc.autoTableEndPosY() + 25);
		<?php
		$con = ConfigurationData::getByPreffix("report_image");
		if ($con != null && $con->val != "") :
		?>
			var img = new Image();
			img.src = "storage/configuration/<?php echo $con->val; ?>";
			img.onload = function() {
				doc.addImage(img, 'PNG', 495, 20, 60, 60, 'mon');
				doc.save('sells-<?php echo date("d-m-Y h:i:s", time()); ?>.pdf');
			}
		<?php else : ?>
			doc.save('sells-<?php echo date("d-m-Y h:i:s", time()); ?>.pdf');
		<?php endif; ?>
	}
</script>