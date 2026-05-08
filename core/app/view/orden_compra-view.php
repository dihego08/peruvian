<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
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
			<div class="row">
				<div class="col-md-8">
					<h3><i class='glyphicon glyphicon-shopping-cart'></i>ORDEN DE COMPRA</h3>
				</div>
				<div class="col-md-4 text-right">
					<a class="btn btn-outline-dark rounded-pill" href="?view=nuevo_orden_compra"><i class="fa fa-plus"></i> AGREGAR</a>
				</div>
			</div>
			<div class="clearfix"></div>

			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Lista General de Ordenes de Compra</h3>
				</div>
				<div class="box-body" style="margin-bottom: 2rem;">
					<fieldset>
						<legend>Filtros de Búsqueda</legend>
						<div class="form-group col-md-12">
							<label>Por Proveedor</label>
							<select class="form-control" name="combo_proveedor" id="combo_proveedor">
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
								<button class="btn btn-success rounded-pill" onclick="buscar_por_fecha();"><i class="fa fa-search"></i> Buscar por Fecha</button>
							</div>
						</div>
					</fieldset>

					<div class="form-row bg-primary" id="div_total_pedido" style="padding: 1rem; margin-bottom: 1rem;">

					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-responsive" id="tabla_ventas">
							<thead>
								<th></th>
								<th>O.C.</th>
								<th>Fecha</th>
								<th>Proveedor</th>
								<th>Total</th>
								<th>Forma de Pago</th>
								<th width="12%"></th>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
				<a href="" class="pull-right btn btn-info rounded-pill" id="reportar" target="_blanck">Exportar PDF</a>
				<a href="" class="pull-right btn btn-primary rounded-pill" style="margin-right: 2rem;" id="reportar_excel" target="_blanck">Exportar Excel</a>
			</div>
			<div id="popup_editar" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar">X</a></div>
					<div>
						<h2 id="titulo_detalle">Detalle de Venta</h2>
						<div class="box box-primary">
							<table class="table table-bordered table-hover" id="tabla_detalle">
								<thead>
									<tr>
										<th>Producto</th>
										<th>Tipo</th>
										<th>Cantidad</th>
										<th>Precio Unitario</th>
										<th>Precio Total</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<span class="btn btn-danger rounded-pill" onclick="cerrar_editar()">Cerrar</span>
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

	function descargar(url) {
		$.fileDownload(url)
			.done(function() {
				alert('File download a success!');
			})
			.fail(function() {
				alert('File download failed!');
			});
	}

	function buscar_por_fecha() {
		var desde = $("#fecha_desde").val();
		var hasta = $("#fecha_hasta").val();
		$("#reportar").attr('href', 'core/app/view/generar_pdf_fe.php?orden=buscar_por_fecha&desde=' + desde + '&hasta=' + hasta + "&id_cliente=" + $("#combo_proveedor").val());

		$("#reportar_excel").attr('href', 'core/app/view/generar_excel_fe.php?orden=buscar_por_fecha&desde=' + desde + '&hasta=' + hasta + "&id_cliente=" + $("#combo_proveedor").val());

		$.get('core/app/view/compra.php', {
			parAccion: 'buscar_por_fecha_fe',
			desde: $("#fecha_desde").val(),
			hasta: $("#fecha_hasta").val(),
			id_cliente: $("#combo_proveedor").val()
		}, function(data) {
			var obj = JSON.parse(data);
			$("#tabla_ventas").find('tbody').empty();
			var t_subtotal = 0;
			var t_igv = 0;
			var t_detraccion = 0;
			var t_igv_pagar = 0;
			var t_total = 0;

			$("#div_total_pedido").empty();
			$("#div_total_pedido").append(`
							<h4>Total Pedidos: ${obj.Records.length}</h4>
						`);
			$.each(obj.Records, function(index, val) {
				var estado = "";
				var botones = "";
				if (val.estado == 1) {
					estado = 'class="bg-danger"';
					botones = `<span class="badge bg-maroon">ANULADO</span> <a href="core/app/view/pdf-oc.php?id=` + val.id + `" target="_blank" class="badge badge-danger">PDF</a>`;
				} else {
					botones = `<a href="core/app/view/pdf-oc.php?id=` + val.id + `" target="_blank" class="badge badge-danger">PDF</a>
						<a href="?view=re" target="_blank"><span class="badge badge-success">RGC</span></a>
						<span onclick="eliminar_order(${val.id});" class="btn btn-sm btn-outline-danger rounded-pill" title="Anular O.C."><i class="fa fa-times"></i></span>`;
				}
				$("#tabla_ventas").find('tbody').append(`<tr ${estado}>
					<td>
						<a href="#" onclick="detalle_orden_compra('` + val.id + `');" class="btn btn-sm btn-outline-dark rounded-pill">
							<i class="glyphicon glyphicon-eye-open"></i>
						</a>
					</td>
					<td>OC-` + val.id + `</td>
					<td>` + val.fecha + `</td>
					<td>` + val.name + `</td>
					<td style="/*text-align: right;*/">S/ ` + val.total + `</td>
					<td>` + $.trim(val.forma_pago) + `</td>
					<td>
						${botones}
					</td>
				</tr>`);
			});
		});
	}

	function actualizar_pago(codigo) {
		$.get('core/app/view/venta.php', {
			parAccion: 'actualizar_pago',
			codigo: codigo,
			monto_pagado: $("#pagado").val(),
			monto_adeuda: $("#a_deuda").val(),
			fecha: $("#fecha_p").val()
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == 'OK') {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Pago Actualizado Correctamente.</strong>' +
						'</div>'
				});
				lista_ordenes_compra('ninguno', 0);
				detalle_orden_compra(codigo);
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Ago ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}

	function detalle_orden_compra(codigo) {
		$("#tabla_detalle").find('tbody').empty();

		$.get('core/app/view/compra.php', {
			parAccion: 'lista_detalle',
			id: codigo
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#tabla_detalle").find('tbody').append(`<tr>
					<th scope="row">${val.descripcion}</th>
					<td>${(val.tipo == 1)? 'Producto':'Servicio'}</td>
					<td>${val.cantidad}</td>
					<td>${val.precio_unitario}</td>
					<td>${val.precio_total}</td>
				</tr>`);
			});

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
							lista_ordenes_compra('ninguno', 0);
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

	function lista_ordenes_compra(filtro, codigo) {
		$("#reportar").attr('href', 'core/app/view/generar_pdf_fe.php?orden=lista_ordenes_compra&filtro=' + filtro + '&codigo=' + codigo + '&fecha=');

		$("#reportar_excel").attr('href', 'core/app/view/generar_excel_fe.php?orden=lista_ordenes_compra&filtro=' + filtro + '&codigo=' + codigo + '&fecha=');

		$.get('core/app/view/compra.php', {
			parAccion: 'lista_ordenes_compra',
			filtro: filtro,
			codigo: codigo
		}, function(data) {
			var obj = JSON.parse(data);
			$("#tabla_ventas").find('tbody').empty();
			var t_subtotal = 0;
			var t_igv = 0;
			var t_detraccion = 0;
			var t_igv_pagar = 0;
			var t_total = 0;

			$("#div_total_pedido").empty();
			$("#div_total_pedido").append(`
							<h4>Total Pedidos: ${obj.Records.length}</h4>
						`);
			$.each(obj.Records, function(index, val) {
				var estado = "";
				var botones = "";
				if (val.estado == 1) {
					estado = 'class="bg-danger"';
					botones = `<span class="badge bg-maroon">ANULADO</span> <a href="core/app/view/pdf-oc.php?id=` + val.id + `" target="_blank" class="badge badge-danger">PDF</a>`;
				} else {
					botones = `<a href="core/app/view/pdf-oc.php?id=` + val.id + `" target="_blank" class="badge badge-danger mb-1">PDF</a>
						<a href="?view=re" target="_blank" class=" mb-1"><span class="badge badge-success">RGC</span></a>
						<span onclick="eliminar_order(${val.id});" class="btn btn-sm btn-outline-danger rounded-pill" title="Anular O.C."><i class="fa fa-times"></i></span>`;
				}
				$("#tabla_ventas").find('tbody').append(`<tr ${estado}>
					<td>
						<a href="#" onclick="detalle_orden_compra('` + val.id + `');" class="btn btn-sm btn-outline-dark rounded-pill">
							<i class="glyphicon glyphicon-eye-open"></i>
						</a>
					</td>
					<td>OC-` + val.id + `</td>
					<td>` + val.fecha + `</td>
					<td>` + val.name + `</td>
					<td style="/*text-align: right;*/">S/ ` + val.total + `</td>
					<td>` + $.trim(val.forma_pago) + `</td>
					<td class="text-center">
						${botones}
						<a class="btn btn-sm btn-outline-warning rounded-pill" href="?view=editar_orden_compra&id=${val.id}">
							<i class="fa fa-pencil"></i>
						</a>
						<span class="btn btn-sm btn-outline-danger rounded-pill" onclick="eliminar_orden_compra(${val.id});">
							<i class="fa fa-trash"></i>
						</span>
					</td>
				</tr>`);
			});
		});
	}

	function eliminar_orden_compra(id) {
		bootbox.confirm({
			message: "¿Seguro de Eliminar esta Orden de Compra?",
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
					$.get('core/app/view/compra.php', {
						parAccion: 'eliminar_order_compra',
						codigo: codigo
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == 'OK') {
							lista_ordenes_compra('ninguno', 0);
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Ago ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				} else {

				}
			}
		});
	}

	function eliminar_order(codigo) {
		bootbox.confirm({
			message: "¿Seguro de Anular esta Orden de Compra?",
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
					$.get('core/app/view/compra.php', {
						parAccion: 'eliminar_order',
						codigo: codigo
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == 'OK') {
							lista_ordenes_compra('ninguno', 0);
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Ago ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				} else {

				}
			}
		});
	}
	$(document).ready(function() {
		$("#combo_proveedor").select2();
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
		$("#fecha_p").datepicker({
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


		$.get('core/app/view/compra.php', {
			parAccion: 'lista_proveedores'
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#combo_proveedor").append('<option value="' + val.id + '">' + val.name + '</option>');
			});
		});


		$("#tipos_pago").on('change', function() {
			if ($("#tipos_pago").val() == 0) {
				lista_ordenes_compra('ninguno', 0);
			} else {
				lista_ordenes_compra('pago', $("#tipos_pago").val());
			}
		});

		$("#combo_proveedor").on('change', function() {
			if ($("#combo_proveedor").val() == 0) {
				lista_ordenes_compra('ninguno', 0);
			} else {
				lista_ordenes_compra('cliente', $("#combo_proveedor").val());
			}
		});


		$("#tipos_entrega").on('change', function() {
			if ($("#tipos_entrega").val() == 0) {
				lista_ordenes_compra('ninguno', 0);
			} else {
				lista_ordenes_compra('entrega', $("#tipos_entrega").val());
			}
		});


		//lista_ordenes_compra(filtro, codigo);
		$("#pagado").on('input', function() {
			//$("#a_deuda").val(parseFloat($("#a_deuda").val()) - parseFloat($("#pagado").val()));
			//calcular_deuda($("#pagado").val());
		}).on('change', function() {
			//$("#a_deuda").val(parseFloat($("#a_deuda").val()) - parseFloat($("#pagado").val()));
			calcular_deuda($("#pagado").val());
		});

		lista_ordenes_compra('ninguno', 0);

		$('#close_editar').on('click', function() {
			//limpiar_formulario();
			$('#popup_editar').fadeOut('slow');
			$('.popup-overlay').fadeOut('slow');
			return false;
			flag = false;
		});
	});
</script>