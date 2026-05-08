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
</style>
<script type="text/javascript" src="datatables/datatables.min.js"></script>
<script type="text/javascript" src="datatables/dat.js"></script>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Registros de Compra </h3>
			<div class="d-block w-100 text-right">
				<a href="?view=re" class="btn btn-outline-dark rounded-pill"><i class="fa fa-plus"></i> NUEVA COMPRA</a>
			</div>
			<div class="row">
				<div class="form-group col-md-12">
					<fieldset>
						<legend style="margin-bottom: 0px;">Filtros</legend>
						<div class="col-md-12">
							<label>Proveedor</label>
							<select class="form-control rounded-pill js-example-basic-single" name="combo_proveedor" id="combo_proveedor">
							</select>
						</div>
						<div class="col-md-12 form-row " style="margin-bottom: 1rem;">
							<h5 style="font-weight: bold; border-bottom: solid 1px #313131;">Fecha Creación</h5>
							<div class="col-md-6">
								<label for="fproceso_desde">Desde:</label>
								<div class="input-group">
									<input type="text" name="fproceso_desde" id="fproceso_desde" readonly="readonly" class="form-control clsDatePicker">
									<span class="input-group-addon">
										<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
									</span>
								</div>
							</div>
							<div class="col-md-6">
								<label for="fproceso_hasta">Hasta:</label>
								<div class="input-group">
									<input type="text" name="fproceso_hasta" id="fproceso_hasta" readonly="readonly" class="form-control clsDatePicker">
									<span class="input-group-addon">
										<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
									</span>
								</div>
							</div>
							<div class="col-md-4" hidden>
								<label for="mayor_a_1">Compras Mayores a:</label>
								<input type="text" name="mayor_a_1" id="mayor_a_1" class="form-control" value="0">
							</div>
						</div>
						<hr>
						<div class="col-md-6">
							<label for="fecha_desde">Fecha Desde:</label>
							<div class="input-group">
								<input type="text" name="fecha_desde" id="fecha_desde" readonly="readonly" class="form-control clsDatePicker">
								<span class="input-group-addon">
									<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
								</span>
							</div>
						</div>
						<div class="col-md-6">
							<label for="fecha_hasta">Fecha Hasta:</label>
							<div class="input-group">
								<input type="text" name="fecha_hasta" id="fecha_hasta" readonly="readonly" class="form-control clsDatePicker">
								<span class="input-group-addon">
									<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
								</span>
							</div>
						</div>
						<div class="col-md-6">
							<label for="fecha_desde">Tipo Documento: </label>
							<select name="" id="b_documento" class="form-control rounded-pill">
								<option value="-1">--Selecciona--</option>
							</select>
						</div>
						<div class="col-md-6">
							<label for="fecha_hasta">Estado Pago: </label>
							<select name="" id="b_pago" class="form-control rounded-pill">
								<option value="-1">--Selecciona--</option>
							</select>
						</div>
					</fieldset>
				</div>
				<div class="form-row col-md-12 text-center">
					<button class="btn btn-success rounded-pill" onclick="filtrar();"><i class="fa fa-search"></i> Buscar</button>
				</div>
				<div id="resultado" hidden style="padding: 25px 25px 0 25px; margin-top: 25px; margin-bottom: 0px;">
					<h3>Lista de productos</h3>
					<div class="box box-primary table-responsive">
						<table class="table table-bordered table-hover" id="tabla_resultado">
							<thead>
								<tr>
								<tr>
									<th>Codigo</th>
									<th>Fecha de Compra</th>
									<th>Proveedor</th>
									<th>Total</th>
									<th colspan="2" style="text-align: center;">Acciones</th>
								</tr>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-12" id="div_entrega" hidden>
					<label>Codigo Compra</label>
					<input type="text" id="codigo_compra" name="codigo_compra" class="form-control" placeholder="Tiempo de Entrega" style="margin-bottom: 10px;">
					<button class="btn btn-danger" onclick="cancel_order();">Cancelar</button>
					<button class="btn btn-success" id="guardar_compra">Guardar Compra</button>
				</div>
			</div>
			<div id="lista_order">
				<h3>Listado de Compras</h3>
				<div class="box box-primary table-responsive" id="div_contenido">
					<table class="table table-bordered table-hover" id="tabla_lista" style="font-size: 11px;">
						<thead>
							<tr>
								<th>Com</th>
								<th>Fec. Creación</th>
								<th>Tipo</th>
								<th>Serie</th>
								<th>Numero</th>
								<th>E. Pago</th>
								<th>Fecha Emision</th>
								<th>Apellidos y Nombres y/o Razon Social</th>
								<th>RUC</th>
								<th>Otros no Gravados</th>
								<th>Adquisi no Gravadas</th>
								<th>Adquisio Gravadas</th>
								<th>IGV</th>
								<th>Importe Total</th>
								<th>Constan Fecha</th>
								<th>Detraccion Numero</th>
								<th>T/C</th>
								<th>Fecha</th>
								<th>Serie</th>
								<th>Doc.</th>
								<th></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
				<div class="col-md-12 text-right">
					<a href="" class="btn btn-danger rounded-pill" id="reportar_excel" target="_blanck">Exportar EXCEL</a>
					<a href="" class="btn btn-info rounded-pill" id="reportar" target="_blanck">Exportar PDF</a>
				</div>
			</div>
			<div id="popup_editar" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar">X</a></div>
					<div>
						<h4 id="titulo_detalle">Detalle Orden de Pedido</h4>
						<div class="box box-primary table-responsive">
							<table class="table table-bordered table-hover" id="tabla_detalle">
								<thead>
									<tr>
										<th>Codigo</th>
										<th>Insumo</th>
										<th>Cantidad</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<div class="w-100 d-block text-right">
							<span class="btn btn-danger rounded-pill" onclick="cerrar_editar()">Cerrar</span>
						</div>
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
		</div>
	</div>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script>
		var html = '<!DOCTYPE html>' +
			'<html lang="es">' +
			'<head>' +
			'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' +
			'<title>Document</title>' +
			'<link href="http://www.peruviandress.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">' +
			'<style>.page_break { page-break-before: always; }</style>' +
			'</head>' +
			'<body>';
		//jQuery.noConflict();
		var id_producto = 0;

		function filtrar() {
			var desde = $("#fecha_desde").val();
			var hasta = $("#fecha_hasta").val();
			var id_proveedor = $("#combo_proveedor").val();
			var tipo_documento = $("#b_documento").val();
			var tipo_pago = $("#b_pago").val();
			var fproceso_desde = $("#fproceso_desde").val();
			var fproceso_hasta = $("#fproceso_hasta").val();
			var mayor_a = $("#mayor_a_1").val();

			$("#reportar").attr('href', 'core/app/view/generar_pdf.php?filtro=fecha&tabla=compras&desde=' + desde + '&hasta=' + hasta + '&id_proveedor=' + id_proveedor + '&tipo_documento=' + tipo_documento + '&tipo_pago=' + tipo_pago + '&fproceso_desde=' + fproceso_desde + '&fproceso_hasta=' + fproceso_hasta + '&mayor_a=' + mayor_a);
			$("#reportar_excel").attr('href', 'core/app/view/generar_excel_compras.php?filtro=fecha&tabla=compras&desde=' + desde + '&hasta=' + hasta + '&id_proveedor=' + id_proveedor + '&tipo_documento=' + tipo_documento + '&tipo_pago=' + tipo_pago + '&fproceso_desde=' + fproceso_desde + '&fproceso_hasta=' + fproceso_hasta + '&mayor_a=' + mayor_a);
			$("#tabla_lista").find('tbody').empty();

			$.post('core/app/view/insumos.php?parAccion=lista_compras_2', {
				desde: desde,
				hasta: hasta,
				id_proveedor: id_proveedor,
				tipo_documento: tipo_documento,
				tipo_pago: tipo_pago,
				fproceso_desde: fproceso_desde,
				fproceso_hasta: fproceso_hasta,
				mayor_a: mayor_a
			}, function(data) {
				var obj = JSON.parse(data);
				var total_exonerado = 0;
				var total_gravado = 0;
				var total_igv = 0;
				var total_total = 0;
				var total_otros_no_gravado = 0;
				$.each(obj.Records, function(index, val) {
					total_exonerado += parseFloat(val.exonerado);
					total_igv += parseFloat(val.igv);
					total_gravado += parseFloat(val.gravado);
					total_total += parseFloat(val.total);
					total_otros_no_gravado += parseFloat(val.otros_no_gravado);
					var ppp = "";
					if (val.proveedor == 'null' || val.proveedor == "") {
						ppp = "";
					} else {
						ppp = val.proveedor;
					}
					$("#tabla_lista").find('tbody').append('<tr>' +
						`<th scope="row">${(index + 1)}</th>` +
						'<th scope="row">' + val.fproceso + '</th>' +
						'<th scope="row">' + val.tipo_documento + '</th>' +
						'<td>' + val.serie + '</td>' +
						'<td>' + val.numeracion + '</td>' +
						'<td>' + val.tipo_pago + '</td>' +
						'<td>' + val.fecha_creacion + '</td>' +
						'<td>' + ppp + '</td>' +
						'<td>' + val.no + '</td>' +
						'<td>S/ ' + val.otros_no_gravado + '</td>' +
						'<td>S/ ' + val.exonerado + '</td>' +
						'<td>S/ ' + val.gravado + '</td>' +
						'<td>S/ ' + val.igv + '</td>' +
						'<td>S/ ' + val.total + '</td>' +
						'<td>' + val.fecha_detraccion + '</td>' +
						'<td>' + val.numero_detraccion + '</td>' +
						'<td>' + val.tipo_cambio + '</td>' +
						'<td>' + val.fecha_comprobante + '</td>' +
						'<td>' + val.serie_comprobante + '</td>' +
						'<td>' + val.documento_comprobante + '</td>' +
						'<td style="text-align: center;">' +
						`<a href="?view=editar_compra&id_compra=${val.id}" class="btn-sm btn-outline-warning rounded-pill btn" style="display: block; width: 100%;"><i class="fa fa-pencil"></i></a>` +
						'<a href="#" style="display: block; width: 100%; margin-top: 0.5rem;" onclick="eliminar_order(\'' + val.id + '\');" class="btn-sm btn-outline-danger rounded-pill btn"><i class="fa fa-trash"></i></a>' +
						'<a href="#" style="display: block; width: 100%; margin-top: 0.5rem;" onclick="detalle_order(\'' + val.id + '\');" class="btn-sm btn-outline-primary rounded-pill btn"><i class="fa fa-eye"></i></a>' +
						'</td>' +
						'</tr>');
				});
				$("#tabla_lista").find('tbody').append('<tr>' +
					`<th scope="row"></th>` +
					'<th scope="row"></th>' +
					'<th scope="row"></th>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<th scope="row">S/ ' + total_otros_no_gravado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_exonerado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_gravado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_igv.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_total.toFixed(2) + '</th>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'</tr>');
			});
		}

		function detalle_order(id) {
			$("#tabla_detalle").find('tbody').empty();
			$.get('core/app/view/insumos.php', {
				parAccion: 'lista_detalle',
				id: id
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					$("#tabla_detalle").find('tbody').append('<tr><th scope="row">' + val.insumocod + '</th><th scope="row">' + val.insumo + '</th><td>' + val.cantidad + '</td><td>S/. ' + val.total + '</td></tr>');
				});
			});
			$('#popup_editar').fadeIn('slow');
			$('.popup-overlay').fadeIn('slow');
			$('.popup-overlay').height($(window).height());
			return false;
		}

		function eliminar_order(id) {
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
						$.get('core/app/view/insumos.php', {
							parAccion: 'eliminar_compra',
							id: id
						}, function(data) {
							var obj = JSON.parse(data);
							if (obj.Result == 'OK') {
								lista_ordenes();
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



		function cancel_order() {
			$("#resultado").attr('hidden', true);
			$("#div_entrega").attr('hidden', true);
			$("#tabla_resultado").find('tbody').empty();
		}

		function guardar_order(id) {
			//alert(id_producto);
			var proveedor = $("#cliente").val();
			var cantidad = $("#canti_" + id).val();
			var precio = $("#precio_" + id).val();
			var codigo_compra = $("#codigo_compra").val();
			if (cantidad == 0 || cantidad == "" || precio == 0 || precio == "" || codigo_compra == 0 || codigo_compra == "") {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Estas dejando campos vacios.</strong>' +
						'</div>'
				});
			} else {
				$.get('core/app/view/insumos.php', {
					parAccion: 'guardar_compra',
					id: id,
					cantidad: cantidad,
					precio: precio,
					proveedor: proveedor,
					codigo_compra: codigo_compra
				}, function(data) {
					var obj = JSON.parse(data);
					if (obj.Result == 'OK') {
						lista_ordenes();
						bootbox.alert({
							message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
								'<strong>Guardado Correctamente.</strong>' +
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
		}

		function lista_clientes() {
			$.get('core/app/view/insumos.php', {
				parAccion: 'lista_proveedores'
			}, function(data) {
				var obj = JSON.parse(data);
				$("#combo_proveedor").append(`<option value="0">SELECCIONA ...</option>`);
				$.each(obj.Records, function(index, val) {
					$("#combo_proveedor").append('<option value="' + val.id + '">' + val.name + '</option>');
				});
			});
		}

		function cerrar_editar() {
			$('#close_editar').click();
		}

		function lista_ordenes() {
			$("#tabla_lista").find('tbody').empty();
			$("#reportar").attr('href', 'core/app/view/generar_pdf.php?filtro=ninguno&tabla=compras');
			$("#reportar_excel").attr('href', 'core/app/view/generar_excel_compras.php?filtro=ninguno&tabla=compras');
			$.get('core/app/view/insumos.php', {
				parAccion: 'lista_compras'
			}, function(data) {
				var obj = JSON.parse(data);
				var total_exonerado = 0;
				var total_gravado = 0;
				var total_igv = 0;
				var total_total = 0;
				var total_otros_no_gravado = 0;
				$.each(obj.Records, function(index, val) {
					total_exonerado += parseFloat(val.exonerado);
					total_igv += parseFloat(val.igv);
					total_gravado += parseFloat(val.gravado);
					total_total += parseFloat(val.total);
					total_otros_no_gravado += parseFloat(val.otros_no_gravado);
					var ppp = "";
					if (val.proveedor == 'null' || val.proveedor == "") {
						ppp = "";
					} else {
						ppp = val.proveedor;
					}
					$("#tabla_lista").find('tbody').append('<tr>' +
						`<th scope="row">${(index + 1)}</th>` +
						'<th scope="row">' + val.fproceso + '</th>' +
						'<th scope="row">' + val.tipo_documento + '</th>' +
						'<td>' + val.serie + '</td>' +
						'<td>' + val.numeracion + '</td>' +
						'<td>' + val.tipo_pago + '</td>' +
						'<td>' + val.fecha_creacion + '</td>' +
						'<td>' + ppp + '</td>' +
						'<td>' + val.no + '</td>' +
						'<td>S/ ' + val.otros_no_gravado + '</td>' +
						'<td>S/ ' + val.exonerado + '</td>' +
						'<td>S/ ' + val.gravado + '</td>' +
						'<td>S/ ' + val.igv + '</td>' +
						'<td>S/ ' + val.total + '</td>' +
						'<td>' + val.fecha_detraccion + '</td>' +
						'<td>' + val.numero_detraccion + '</td>' +
						'<td>' + val.tipo_cambio + '</td>' +
						'<td>' + val.fecha_comprobante + '</td>' +
						'<td>' + val.serie_comprobante + '</td>' +
						'<td>' + val.documento_comprobante + '</td>' +
						'<td style="text-align: center;">' +
						`<a href="?view=editar_compra&id_compra=${val.id}" class="btn-sm rounded-pill btn-outline-warning btn" style="display: block; width: 100%;"><i class="fa fa-pencil"></i></a>` +
						'<a href="#" style="display: block; width: 100%; margin-top: 0.5rem;" onclick="eliminar_order(\'' + val.id + '\');" class="btn-sm rounded-pill btn-outline-danger btn"><i class="fa fa-trash"></i></a>' +
						'<a href="#" style="display: block; width: 100%; margin-top: 0.5rem;" onclick="detalle_order(\'' + val.id + '\');" class="btn-sm rounded-pill btn-outline-primary btn"><i class="fa fa-eye"></i></a>' +
						'</td>' +
						'</tr>');
				});

				$("#tabla_lista").find('tbody').append('<tr>' +
					`<th scope="row"></th>` +
					'<th scope="row"></th>' +
					'<th scope="row"></th>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<th scope="row">S/ ' + total_otros_no_gravado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_exonerado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_gravado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_igv.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_total.toFixed(2) + '</th>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'</tr>');
			});
		}

		$(document).ready(function() {

			$('.js-example-basic-single').select2();
			$('#fecha_desde').datepicker({
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
				altField: "#fecha_nacimiento_hidden",
				altFormat: "yy-mm-dd"
			});
			$("#fproceso_desde").datepicker({
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
				altField: "#fecha_nacimiento_hidden",
				altFormat: "yy-mm-dd"
			});
			$("#fproceso_hasta").datepicker({
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
			lista_clientes();
			lista_ordenes();
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
							'<th scope="row">' + ui.item.id + '</th>' +
							'<td>' + ui.item.value + '</td>' +
							'<td>' + ui.item.unidad + '</td>' +
							'<td>' +
							'<div class="input-group col-md-12">' +
							'<input type="" class="form-control" required id="precio_' + ui.item.id + '" name="q" placeholder="Precio ...">' +
							'</div>' +
							'</td>' +
							'<td>' +
							'<div class="input-group col-md-12">' +
							'<input type="" class="form-control" required id="canti_' + ui.item.id + '" name="q" placeholder="Cantidad ...">' +
							'</div>' +
							'</td>' +
							'</tr>');
					$("#guardar_compra").attr('onclick', 'guardar_order(' + ui.item.id + ');');
				}
			});

			$('#close_editar').on('click', function() {
				//limpiar_formulario();
				$('#popup_editar').fadeOut('slow');
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
					$("#b_documento").append('<option value="' + val.id + '">' + val.tipo_documento + '</option>');
				});
			});

			$.get('core/app/view/venta.php', {
				parAccion: 'tipos_pago'
			}, function(data) {
				$("#id_forma_pago").empty();
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					$("#b_pago").append('<option value="' + val.id + '">' + val.name + '</option>');
				});
			});
		});

		function filtro_busqueda(id, tipo) {
			$("#tabla_lista").find('tbody').empty();
			//$("#reportar").attr('href', 'core/app/view/generar_pdf.php?filtro=ninguno&tabla=compras');
			$.post('core/app/view/insumos.php?parAccion=filtro_compras', {
				id: id,
				tipo: tipo
			}, function(data) {
				var obj = JSON.parse(data);
				var total_exonerado = 0;
				var total_gravado = 0;
				var total_igv = 0;
				var total_total = 0;
				var total_otros_no_gravado = 0;
				$.each(obj.Records, function(index, val) {
					total_exonerado += parseFloat(val.exonerado);
					total_igv += parseFloat(val.igv);
					total_gravado += parseFloat(val.gravado);
					total_total += parseFloat(val.total);
					total_otros_no_gravado += parseFloat(val.otros_no_gravado);
					var ppp = "";
					if (val.proveedor == 'null' || val.proveedor == "") {
						ppp = "";
					} else {
						ppp = val.proveedor;
					}
					$("#tabla_lista").find('tbody').append('<tr>' +
						`<th scope="row">${(index + 1)}</th>` +
						'<th scope="row">' + val.fproceso + '</th>' +
						'<th scope="row">' + val.tipo_documento + '</th>' +
						'<td>' + val.serie + '</td>' +
						'<td>' + val.numeracion + '</td>' +
						'<td>' + val.fecha_creacion + '</td>' +
						'<td>' + ppp + '</td>' +
						'<td>' + val.no + '</td>' +
						'<td>S/ ' + val.otros_no_gravado + '</td>' +
						'<td>S/ ' + val.exonerado + '</td>' +
						'<td>S/ ' + val.gravado + '</td>' +
						'<td>S/ ' + val.igv + '</td>' +
						'<td>S/ ' + val.total + '</td>' +
						'<td>' + val.fecha_detraccion + '</td>' +
						'<td>' + val.numero_detraccion + '</td>' +
						'<td>' + val.tipo_cambio + '</td>' +
						'<td>' + val.fecha_comprobante + '</td>' +
						'<td>' + val.serie_comprobante + '</td>' +
						'<td>' + val.documento_comprobante + '</td>' +
						'<td style="text-align: center;">' + `<a href="?view=editar_compra&id_compra=${val.id}" class="btn-xs btn-warning"><i class="fa fa-pencil"></i></a><br>` + '<a href="#" onclick="eliminar_order(\'' + val.id + '\');" class="btn-xs btn-danger"><i class="fa fa-trash"></i></a> <br>' +
						'<a href="#" onclick="detalle_order(\'' + val.id + '\');" class="btn-xs btn-primary"><i class="fa fa-eye"></i></a>' + '</td>' +
						'</tr>');
				});

				$("#tabla_lista").find('tbody').append('<tr>' +
					`<th scope="row"></th>` +
					'<th scope="row"></th>' +
					'<th scope="row"></th>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<th scope="row">S/ ' + total_otros_no_gravado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_exonerado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_gravado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_igv.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_total.toFixed(2) + '</th>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'</tr>');
			});
		}

		function filtro_proveedor(id_proveedor) {
			$("#tabla_lista").find('tbody').empty();
			$.post('core/app/view/insumos.php?parAccion=filtro_proveedor', {
				id_proveedor: id_proveedor,
			}, function(data) {

				var obj = JSON.parse(data);
				var total_exonerado = 0;
				var total_gravado = 0;
				var total_igv = 0;
				var total_total = 0;
				var total_otros_no_gravado = 0;
				$.each(obj.Records, function(index, val) {
					total_exonerado += parseFloat(val.exonerado);
					total_igv += parseFloat(val.igv);
					total_gravado += parseFloat(val.gravado);
					total_total += parseFloat(val.total);
					total_otros_no_gravado += parseFloat(val.otros_no_gravado);
					var ppp = "";
					if (val.proveedor == 'null' || val.proveedor == "") {
						ppp = "";
					} else {
						ppp = val.proveedor;
					}
					$("#tabla_lista").find('tbody').append('<tr>' +
						`<th scope="row">${(index + 1)}</th>` +
						'<th scope="row">' + val.fproceso + '</th>' +
						'<th scope="row">' + val.tipo_documento + '</th>' +
						'<td>' + val.serie + '</td>' +
						'<td>' + val.numeracion + '</td>' +
						'<td>' + val.fecha_creacion + '</td>' +
						'<td>' + ppp + '</td>' +
						'<td>' + val.no + '</td>' +
						'<td>S/ ' + val.otros_no_gravado + '</td>' +
						'<td>S/ ' + val.exonerado + '</td>' +
						'<td>S/ ' + val.gravado + '</td>' +
						'<td>S/ ' + val.igv + '</td>' +
						'<td>S/ ' + val.total + '</td>' +
						'<td>' + val.fecha_detraccion + '</td>' +
						'<td>' + val.numero_detraccion + '</td>' +
						'<td>' + val.tipo_cambio + '</td>' +
						'<td>' + val.fecha_comprobante + '</td>' +
						'<td>' + val.serie_comprobante + '</td>' +
						'<td>' + val.documento_comprobante + '</td>' +
						'<td style="text-align: center;">' + `<a href="?view=editar_compra&id_compra=${val.id}" class="btn-xs btn-warning"><i class="fa fa-pencil"></i></a><br>` + '<a href="#" onclick="eliminar_order(\'' + val.id + '\');" class="btn-xs btn-danger"><i class="fa fa-trash"></i></a> <br>' +
						'<a href="#" onclick="detalle_order(\'' + val.id + '\');" class="btn-xs btn-primary"><i class="fa fa-eye"></i></a>' + '</td>' +
						'</tr>');
				});

				$("#tabla_lista").find('tbody').append('<tr>' +
					`<th scope="row"></th>` +
					'<th scope="row"></th>' +
					'<th scope="row"></th>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<th scope="row">S/ ' + total_otros_no_gravado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_exonerado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_gravado.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_igv.toFixed(2) + '</th>' +
					'<th scope="row">S/ ' + total_total.toFixed(2) + '</th>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'<td></td>' +
					'</tr>');
			});
		}
	</script>
</section>