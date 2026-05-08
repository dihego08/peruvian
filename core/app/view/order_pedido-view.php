<?php
if (Core::$user->kind == 5) {
	header("Location: ?view=fesunat");
} elseif (Core::$user->kind == 13) {
	header("Location: ?view=maquinas");
} elseif (Core::$user->kind == 4 && Core::$user->id != 22) {
	header("Location: ?view=documentos");
}
?>
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
		z-index: 2001;
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

	@media (max-width: 600px) {
		select {
			width: 105px !important;
		}
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
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Orden de Pedido </h3>
			<div class="w-100 text-right">
				<a href="index.php?view=new_order_pedido" class="btn btn-outline-dark rounded-pill"><i class="fa fa-plus"></i> NUEVA ORDEN</a>
			</div>
			<?php
			if ($cli != "") {
			?>
				<h2>CLIENTE : <?php echo ($cli); ?> </h2>
			<?php
			}
			?>
			<div class="row">
				<div class="form-group col-md-3">
					<label for="pedido_orden">Orden</label>
					<div class="input-group">
						<input type="text" name="pedido_orden" id="pedido_orden" class="form-control rounded-pill-left ui-autocomplete-input">
						<span class="input-group-addon btn btn-primary" onclick="buscar_por_orden();">
							<i id="calIconTourDateDetails" class="fa fa-search-plus"></i>
						</span>
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="pedido_modelo">Modelo</label>
					<div class="input-group">
						<input type="text" name="pedido_modelo" id="pedido_modelo" class="form-control rounded-pill-left ui-autocomplete-input">
						<span class="input-group-addon btn btn-primary" onclick="buscar_por_modelo();">
							<i id="calIconTourDateDetails" class="fa fa-search-plus"></i>
						</span>
					</div>
				</div>
				<div class="form-group col-md-3">
					<label for="contrato">Contrato</label>
					<div class="input-group">
						<input type="text" name="contrato" id="contrato" class="form-control rounded-pill-left ui-autocomplete-input">
						<span class="input-group-addon btn btn-primary" onclick="buscar_por_contrato();">
							<i id="calIconTourDateDetails" class="fa fa-search-plus"></i>
						</span>
					</div>
				</div>
				<div class="col-md-3">
					<label>Cliente</label>
					<select class="form-control rounded-pill" id="cliente" name="cliente">
						<option value="0">SELECCIONA ...</option>
					</select>
				</div>

				<fieldset class="d-block w-100 px-3" style="width: 100%; padding-left: 1.5rem; padding-right: 1.5rem;">
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

				<div id="resultado" hidden style="padding: 25px 25px 0 25px; margin-top: 25px; margin-bottom: 0px;">
					<h3>Lista de productos</h3>
					<div class="box box-primary table-responsive">
						<table class="table table-bordered table-hover" id="tabla_resultado">
							<thead>
								<tr>
									<th>Codigo</th>
									<th>Nombre</th>
									<th>Unidad</th>
									<th>Tipo</th>
									<th>Precio unitario</th>
									<th>Cantidad</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-12" id="div_entrega" hidden>
					<label>Tiempo de Entrega (días)</label>
					<input type="text" id="tiempo_entrega" name="tiempo_entrega" class="form-control" placeholder="Tiempo de Entrega" style="margin-bottom: 10px;">
					<button class="btn btn-danger rounded-pill" onclick="cancel_order();">Cancelar</button>
					<button class="btn btn-success rounded-pill" onclick="guardar_order();">Guardar Orden</button>
				</div>
			</div>
			<div id="lista_order">
				<h3>Listado de Órdenes de Pedido</h3>
				<div class="row" style="width: 100%; margin-bottom: 1rem;">
					<div class="col-md-6" style="text-align: center;">
						<a id="a_pdf" href="" target="_blank" class="btn btn-success rounded-pill">Descargar PDF</a>
					</div>
					<div class="col-md-6" style="text-align: center;">
						<a id="a_excel" href="" class="btn btn-info rounded-pill">Descargar EXCEL</a>
					</div>
				</div>
				<div class="row bg-primary" id="div_total_pedido" style="padding: 1rem; margin-bottom: 1rem;">
					<div class="col-md-4">
						<p>Total Pedidos: <span id="total_pedidos"></span></p>
					</div>
					<div class="col-md-4">
						<p>Pedidos entregados a tiempo: <span id="pedidos_tiempo"></span></p>
					</div>
					<div class="col-md-4">
						<p>Pedidos entregados fuera de tiempo: <span id="pedidos_fuera"></span></p>
					</div>
				</div>
				<div class="box box-primary table-responsive">
					<table class="table table-bordered table-hover" id="tabla_lista">
						<thead>
							<tr>
								<th colspan="2">Pedido</th>
								<th>Fecha de Pedido</th>
								<th>Cliente</th>
								<th style="width: 50px;">Descripción</th>
								<th>Cod. Modelo</th>
								<th>Modelo</th>
								<th>Núm. Contrato</th>
								<th>Cant Pedido</th>
								<th>Cant Produccion</th>
								<th>Guía Remisión</th>
								<th>Documento</th>
								<th>Fec. Est. Entrega</th>
								<th>Fec. Entrega</th>
								<th class="depende">Acciones</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
						</tfoot>
					</table>
				</div>
			</div>
			<div id="popup_editar" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar"><strong>X</strong></a></div>
					<div>
						<h3 id="titulo_detalle"></h3>
						<div class="box box-primary table-responsive">
							<table class="table table-bordered table-hover" id="tabla_detalle">
								<thead>
									<tr>
										<th rowspan="2" style="vertical-align: middle; text-align: center;">Modelo</th>
										<th rowspan="2" style="vertical-align: middle; text-align: center;">Color</th>
										<th colspan="14" style="text-align: center;">Cantidades por Talla</th>
									</tr>
									<tr>
										<th><span id="name_N1"></span></th>
										<th><span id="name_N2"></span></th>
										<th><span id="name_N3"></span></th>
										<th><span id="name_N4"></span></th>
										<th><span id="name_N5"></span></th>
										<th><span id="name_N6"></span></th>
										<th><span id="name_N7"></span></th>
										<th><span id="name_N8"></span></th>
										<th><span id="name_N9"></span></th>
										<th><span id="name_N10"></span></th>
										<th><span id="name_N11"></span></th>
										<th><span id="name_N12"></span></th>
										<th><span id="name_N13"></span></th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
							<div class="form-row col-md-12" style="background: auto;border-radius: 8px;box-shadow: 0px 2px 2px;margin-bottom: 5px; border: solid 1px #aaa;">
								<h3 id="comentario"></h3>
								<span class="btn btn-danger rounded-pill" style="float: right;" onclick="cerrar_editar()"><i class="fa fa-times"></i> Cerrar</span>
							</div>
						</div>
					</div>
					<div>
						<h3 id="titulo_detalle_produccion"></h3>
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


			<!----------------------------------------------------------------------->
			<div class="modal fade" id="formulario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document" style="max-width: 80%;">
					<div class="modal-content">
						<div class="modal-header">
							<!--<h3 class="modal-title" id="exampleModalLabel">Nuevo Alumno</h3>-->
							<button class="close" type="button" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group" style="width: 100%;">
								<img src="" id="la_imagen" style="width: 100%;">
							</div>
						</div>
						<div class="modal-footer">
						</div>
					</div>
				</div>
			</div>
			<!----------------------------------------------------------------------->
			<script>
				var k = <?php echo Core::$user->kind; ?>;
				<?php
				include("env.php");
				$query = $mbd->prepare("SELECT id_referencia FROM cargos WHERE id = :id");
				$query->bindParam(":id", Core::$user->kind);
				$query->execute();
				$values = $query->fetch(PDO::FETCH_ASSOC);
				$id_referencia = 0;
				if (empty($values['id_referencia']) || $values['id_referencia'] == null) {
				} else {
					$id_referencia = $values['id_referencia'];
				}

				echo "var kk = " . $id_referencia . ";"
				?>

				var id_producto = 0;

				function buscar_por_orden() {
					lista_ordenes_2($("#cliente").val(), $("#pedido_orden").val());
				}

				function buscar_por_modelo() {
					lista_ordenes_2($("#cliente").val(), $("#pedido_orden").val(), $("#pedido_modelo").val());
				}

				function buscar_por_contrato() {
					lista_ordenes_2($("#cliente").val(), $("#pedido_orden").val(), $("#pedido_modelo").val(), $("#contrato").val());
				}

				function get_id_cliente() {
					$.get('core/app/view/order.php', {
						parAccion: "get_cliente",
						id: k
					}, function(data) {
						var obj = JSON.parse(data);
						kk = obj.id_referencia;
					});
				}

				function detalle_order(codigo, comentario, total, cliente) {
					$("#tabla_detalle").find('tbody').empty();
					$.get('core/app/view/order.php', {
						parAccion: 'lista_detalle',
						codigo: codigo
					}, function(data) {
						$("#titulo_detalle").text("Nro Pedido: " + codigo + "  | Cliente : " + cliente);
						if (comentario == 'null') {
							$("#comentario").text("Sin Comentario");
						} else {
							$("#comentario").text(comentario);
						}
						var obj = JSON.parse(data);
						var cc = 0;
						var totalprod = 0;
						$.each(obj.Records, function(index, val) {
							$("#name_N1").text(val.n1);
							$("#name_N2").text(val.n2);
							$("#name_N3").text(val.n3);
							$("#name_N4").text(val.n4);
							$("#name_N5").text(val.n5);
							$("#name_N6").text(val.n6);
							$("#name_N7").text(val.n7);
							$("#name_N8").text(val.n8);
							$("#name_N9").text(val.n9);
							$("#name_N10").text(val.n10);
							$("#name_N11").text(val.n11);
							$("#name_N12").text(val.n12);
							$("#name_N13").text(val.n13);

							$("#tabla_detalle").find('tbody').append('<tr><td>' + val.modelo + '</td>' +
								'<td>' + val.color + '</td>' +
								'<td>' + val._2 + '</td>' +
								'<td>' + val._4 + '</td>' +
								'<td>' + val._6 + '</td>' +
								'<td>' + val._8 + '</td>' +
								'<td>' + val._10 + '</td>' +
								'<td>' + val._12 + '</td>' +
								'<td>' + val._14 + '</td>' +
								'<td>' + val._16 + '</td>' +
								'<td>' + val.s + '</td>' +
								'<td>' + val.m + '</td>' +
								'<td>' + val.l + '</td>' +
								'<td>' + val.xl + '</td>' +
								'<td>' + val.xxl + '</td><td>' + val.total + '</td></tr><tr><td colspan="2">PRODUCIDOS</td>' +
								'<td>' + val.p2 + '</td>' +
								'<td>' + val.p4 + '</td>' +
								'<td>' + val.p6 + '</td>' +
								'<td>' + val.p8 + '</td>' +
								'<td>' + val.p10 + '</td>' +
								'<td>' + val.p12 + '</td>' +
								'<td>' + val.p14 + '</td>' +
								'<td>' + val.p16 + '</td>' +
								'<td>' + val.ps + '</td>' +
								'<td>' + val.pm + '</td>' +
								'<td>' + val.pl + '</td>' +
								'<td>' + val.pxl + '</td>' +
								'<td>' + val.pxxl + '</td><td>' + val.ptotal + '</td></tr>');

							totalprod = totalprod + parseFloat(val.ptotal);
						});
						$("#tabla_detalle").find('tbody').append('<tr style="font-weight: bold;"><td colspan="15" style="text-align: right;">Total:</td><td>' + totalprod + '</td></tr>');
					});
					$('#popup_editar').fadeIn('slow');
					$('.popup-overlay').fadeIn('slow');
					$('.popup-overlay').height($(window).height());
					return false;
				}

				function eliminar_order(codigo) {
					bootbox.confirm({
						message: "¿Seguro de Eliminar esta Orden de Pedido?",
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
								$.get('core/app/view/order.php', {
									parAccion: 'eliminar_order',
									codigo: codigo
								}, function(data) {
									var obj = JSON.parse(data);
									if (obj.Result == 'OK') {
										lista_ordenes(kk);
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
					});
				}

				function actualizar_estado(codigo) {
					var estado = $("#estado_" + codigo).val();
					$.get('core/app/view/order.php', {
						parAccion: 'actualizar_estado',
						codigo: codigo,
						estado: estado
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == 'OK') {
							bootbox.alert({
								message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Orden guardada correctamente.</strong>' +
									'</div>'
							});
							lista_ordenes(kk);
							cancel_order();
							id_producto = 0;
							cantidades = 0;
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Algo ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				}

				function lista_ordenes_2(id_cliente, codigo, modelo = -1, contrato = -1) {
					$total_tiempo = 0;
					$total_fuera = 0;
					$("#tabla_lista").find('tbody').empty();
					$("#tabla_lista").find('tfoot').empty();
					$.get('core/app/view/order.php', {
						parAccion: 'lista_ordenes_2',
						codigo: codigo,
						id_cliente: id_cliente,
						modelo: modelo,
						contrato: contrato
					}, function(data) {
						var obj = JSON.parse(data);
						var html = "";

						var cls2 = "";
						var p1 = 50,
							p2 = 15;
						var total_pedido = 0;
						var total_producido = 0;

						$("#total_pedidos").text(obj.Records.length);

						$("#a_pdf").attr('href', 'core/app/view/generar_pdf_order.php?orden=lista_ordenes_2&id_cliente=' + id_cliente + '&codigo=' + codigo + '&modelo=' + modelo + '&contrato=' + contrato);
						$("#a_excel").attr('href', 'core/app/view/generar_excel_order.php?orden=lista_ordenes_2&id_cliente=' + id_cliente + '&codigo=' + codigo + '&modelo=' + modelo + '&contrato=' + contrato);

						$.each(obj.Records, function(index, val) {
							var cls = "";

							var r = /\d+/;

							var trans = val.trans.match(r); // val.trans;
							if (val.tiempo_entrega == null || val.tiempo_entrega == undefined) {
								var ent = 0;
							} else {
								var ent = val.tiempo_entrega.match(r);
							}

							var por = (trans * 100 / ent[0]);

							if ((val.codigo_venta == null || val.codigo_venta == "null" || val.codigo_venta == "NULL" || val.codigo_venta == "")) {
								cls = 'class="danger"';
							} else {
								if (parseFloat(val.total) > parseFloat(val.totalp)) {

									cls = 'class="danger"';
								}
							}

							var contrato = "";
							if (val.num_contrato == null || val.num_contrato == "null" || val.num_contrato == "NULL") {

							} else {
								contrato = val.num_contrato;
							}
							var guia = "";
							if (val.guia_remision == null || val.guia_remision == "null" || val.guia_remision == "NULL") {

							} else {
								guia = val.guia_remision;
							}
							var fecha_e_r = "";
							if (val.fecha_entrega_real == null || val.fecha_entrega_real == "null" || val.fecha_entrega_real == "NULL") {

							} else {
								fecha_e_r = val.fecha_entrega_real;
							}

							var img_ = "";
							if (val.imagen_alt == "" || val.imagen_alt == null) {
								img_ = val.imagen;
							} else {
								img_ = val.imagen_alt;
							}

							var nombre_modelo = "";
							if (val.nombre_modelo == "" || val.nombre_modelo == null) {
								nombre_modelo = val.producto;
							} else {
								nombre_modelo = val.nombre_modelo;
							}

							total_pedido += parseFloat(val.total);
							total_producido += parseFloat(val.totalp);

							html = html + '<tr ' + cls + ' id="' + val.codigo + '">' +
								`<td><a class="btn btn-sm btn-outline-info rounded-pill d-block mt-1" href="core/app/view/pdf-pedido.php?codigo=${val.codigo}"><i class="fa fa-file-pdf-o"></i></a><a href="#" onclick="detalle_order('${val.codigo}','${val.comentario}', '${val.total}', '${val.name}');" class="btn btn-sm btn-outline-dark rounded-pill d-block mt-1"><i class="glyphicon glyphicon-eye-open"></i></a></td>` +
								'<th scope="row">' + val.codigo + '</th>' +
								'<th>' + val.fecha_creacion + '</th>' +
								'<td>' + val.name + '</td>' +
								'<td>' + nombre_modelo + '</td>' +
								'<td>' + $.trim(val.codigo_unitario) + '</td>' +
								'<td><img src="storage/products/' + img_ + '" style="width:64px; cursor: pointer;" title="Ver Imagen" data-toggle="modal" data-target="#formulario" onclick="ver_imagen(\'storage/products/' + img_ + '\');"></td>' +
								'<td>' + contrato + '</td>' +
								'<td>' + val.total + '</td>' +
								'<td>' + val.totalp + '</td>' +
								'<td>' + guia + '</td>' +
								'<td>' + val.codigo_venta + '</td>' +
								`<td>${val.fecha_entrega}</td>` +
								'<td>' + fecha_e_r + '</td>';

							var $di = diferencias_dias(val.fecha_entrega, fecha_e_r);
							html = html + '<td class="depende">' + $di + '<a href="index.php?view=new_produccion_order_pedido&pcod=' + val.codigo + '" class="btn-sm rounded-pill btn-outline-dark d-block mt-1 btn" ><i class="fa fa-plus"></i></a> <a href="#" onclick="eliminar_order(\'' + val.codigo + '\');" class="btn btn-sm rounded-pill btn-outline-danger d-block mt-1"><i class="fa fa-trash"></i></a>' +
								'<a href="index.php?view=edit_order_pedido&codigo=' + val.codigo + '" class="btn-sm rounded-pill btn-outline-warning d-block mt-1 btn" ><i class="fa fa-pencil"></i></a></td>' +
								'</tr>';
						});
						$("#tabla_lista").find('tbody').append(html);
						$("#tabla_lista").find('tfoot').append(`
							<tr clas="bg-dark" style="font-weight: bold;">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>${total_pedido}</td>
								<td>${total_producido}</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						`);
						$("#pedidos_tiempo").text($total_tiempo);
						$("#pedidos_fuera").text($total_fuera);
						if (k == 1 || k == 12) {
							cls = "";
						} else {
							$(".depende").attr('hidden', true);
							$(".ssll").attr('disabled', true);
						}
						var cur_url = window.location.href;
						var location = cur_url.split("#");
						if (location[1] == null || location[1] == undefined) {

						} else {
							$([document.documentElement, document.body]).animate({
								scrollTop: $("#" + location[1]).offset().top
							}, 1000);
						}
					});
				}

				function buscar_por_fecha() {
					$total_tiempo = 0;
					$total_fuera = 0;
					$("#tabla_lista").find('tbody').empty();
					$.get('core/app/view/order.php', {
						parAccion: 'lista_ordenes_fecha',
						id_cliente: $("#cliente").val(),
						fecha_desde: $("#fecha_desde").val(),
						fecha_hasta: $("#fecha_hasta").val(),
						num_modelo: $("#pedido_modelo").val(),
					}, function(data) {

						$("#a_pdf").attr('href', 'core/app/view/generar_pdf_order.php?orden=lista_ordenes&fecha_desde=' + $("#fecha_desde").val() + '&fecha_hasta=' + $("#fecha_hasta").val() + '&id_cliente=' + $("#cliente").val() + '&num_modelo=' + $("#pedido_modelo").val());
						$("#a_excel").attr('href', 'core/app/view/generar_excel_order.php?orden=lista_ordenes&fecha_desde=' + $("#fecha_desde").val() + '&fecha_hasta=' + $("#fecha_hasta").val() + '&id_cliente=' + $("#cliente").val() + '&num_modelo=' + $("#pedido_modelo").val());

						var obj = JSON.parse(data);
						var html = "";

						var cls2 = "";
						var p1 = 50,
							p2 = 15;

						var total_pedido = 0;
						var total_producido = 0;


						$("#total_pedidos").text(obj.Records.length);

						$.each(obj.Records, function(index, val) {
							var cls = "";
							var trans = val.trans;
							var ent = val.tiempo_entrega;
							var por = (trans * 100 / ent);

							if (val.codigo_venta == null || val.codigo_venta == "null" || val.codigo_venta == "NULL" || val.codigo_venta == "" || val.totalp < val.total) {
								cls = 'class="danger"';
							}

							var contrato = "";
							if (val.num_contrato == null || val.num_contrato == "null" || val.num_contrato == "NULL") {

							} else {
								contrato = val.num_contrato;
							}
							var guia = "";
							if (val.guia_remision == null || val.guia_remision == "null" || val.guia_remision == "NULL") {

							} else {
								guia = val.guia_remision;
							}
							var fecha_e_r = "";
							if (val.fecha_entrega_real == null || val.fecha_entrega_real == "null" || val.fecha_entrega_real == "NULL") {

							} else {
								fecha_e_r = val.fecha_entrega_real;
							}

							var img_ = "";
							if (val.imagen_alt == "" || val.imagen_alt == null) {
								img_ = val.imagen;
							} else {
								img_ = val.imagen_alt;
							}

							var nombre_modelo = "";
							if (val.nombre_modelo == "" || val.nombre_modelo == null) {
								nombre_modelo = val.producto;
							} else {
								nombre_modelo = val.nombre_modelo;
							}

							total_pedido += parseFloat(val.total);
							total_producido += parseFloat(val.totalp);

							html = html + '<tr ' + cls + ' id="' + val.codigo + '">' +
								`<td><a class="btn btn-sm btn-outline-info rounded-pill mt-1 d-block" href="core/app/view/pdf-pedido.php?codigo=${val.codigo}"><i class="fa fa-file-pdf-o"></i></a><a href="#" onclick="detalle_order('${val.codigo}','${val.comentario}', '${val.total}', '${val.name}');" class="btn btn-sm btn-outline-dark rounded-pill mt-1 d-block"><i class="glyphicon glyphicon-eye-open"></i></a></td>` +
								'<th scope="row">' + val.codigo + '</th>' +
								'<th>' + val.fecha_creacion + '</th>' +
								'<td>' + val.name + '</td>' +
								'<td>' + nombre_modelo + '</td>' +
								'<td>' + $.trim(val.codigo_unitario) + '</td>' +
								'<td><img src="storage/products/' + img_ + '" style="width:64px; cursor: pointer;" title="Ver Imagen" data-toggle="modal" data-target="#formulario" onclick="ver_imagen(\'storage/products/' + img_ + '\');"></td>' +
								'<td>' + contrato + '</td>' +
								'<td>' + val.total + '</td>' +
								'<td>' + val.totalp + '</td>' +
								'<td>' + guia + '</td>' +
								'<td>' + val.codigo_venta + '</td>' +
								`<td>${val.fecha_entrega}</td>` +
								'<td>' + fecha_e_r + '</td>';

							var $di = diferencias_dias(val.fecha_entrega, fecha_e_r);

							html = html + '<td class="depende">' + $di + '<a href="index.php?view=new_produccion_order_pedido&pcod=' + val.codigo + '" class="btn-sm btn-outline-dark rounded-pill d-block mt-1" ><i class="fa fa-plus"></i></a> <a href="#" onclick="eliminar_order(\'' + val.codigo + '\');" class="btn btn-sm btn-outline-danger rounded-pill d-block mt-1"><i class="fa fa-trash"></i></a></td>' +
								'</tr>';
						});
						$("#tabla_lista").find('tbody').append(html);
						$("#tabla_lista").find('tfoot').empty();


						$("#tabla_lista").find('tfoot').append(`
							<tr clas="bg-dark" style="font-weight: bold;">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>${total_pedido}</td>
								<td>${total_producido}</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						`);
						$("#pedidos_tiempo").text($total_tiempo);
						$("#pedidos_fuera").text($total_fuera);
						if (k == 1 || k == 12) {
							cls = "";
						} else {
							$(".depende").attr('hidden', true);
							$(".ssll").attr('disabled', true);
						}
						var cur_url = window.location.href;
						var location = cur_url.split("#");
						if (location[1] == null || location[1] == undefined) {

						} else {
							$([document.documentElement, document.body]).animate({
								scrollTop: $("#" + location[1]).offset().top
							}, 1000);
						}
					});
				}
				var $total_tiempo = 0;
				var $total_fuera = 0;

				function lista_ordenes(id) {
					$total_tiempo = 0;
					$total_fuera = 0;
					$("#tabla_lista").find('tbody').empty();
					$("#tabla_lista").find('tfoot').empty();
					$.get('core/app/view/order.php', {
						parAccion: 'lista_ordenes',
						id_cliente: id
					}, function(data) {

						$("#a_pdf").attr('href', 'core/app/view/generar_pdf_order.php?orden=lista_ordenes&id_cliente=' + id);
						$("#a_excel").attr('href', 'core/app/view/generar_excel_order.php?orden=lista_ordenes&id_cliente=' + id);

						var obj = JSON.parse(data);
						var html = "";

						var cls2 = "";
						var p1 = 50,
							p2 = 15;
						var total_pedido = 0;
						var total_producido = 0;


						$("#total_pedidos").text(obj.Records.length);

						$.each(obj.Records, function(index, val) {
							var cls = "";

							var r = /\d+/;

							var trans = val.trans.match(r); // val.trans;
							if (val.tiempo_entrega == null || val.tiempo_entrega == undefined) {
								var ent = 0;
							} else {
								var ent = val.tiempo_entrega.match(r);
							}

							var por = (trans * 100 / ent[0]);

							if ((val.codigo_venta == null || val.codigo_venta == "null" || val.codigo_venta == "NULL" || val.codigo_venta == "")) {
								cls = 'class="danger"';
							} else {
								if (parseFloat(val.total) > parseFloat(val.totalp)) {

									cls = 'class="danger"';
								}
							}

							var contrato = "";
							if (val.num_contrato == null || val.num_contrato == "null" || val.num_contrato == "NULL") {

							} else {
								contrato = val.num_contrato;
							}
							var guia = "";
							if (val.guia_remision == null || val.guia_remision == "null" || val.guia_remision == "NULL") {

							} else {
								guia = val.guia_remision;
							}
							var fecha_e_r = "";
							if (val.fecha_entrega_real == null || val.fecha_entrega_real == "null" || val.fecha_entrega_real == "NULL") {

							} else {
								fecha_e_r = val.fecha_entrega_real;
							}

							var img_ = "";
							if (val.imagen_alt == "" || val.imagen_alt == null) {
								img_ = val.imagen;
							} else {
								img_ = val.imagen_alt;
							}

							var nombre_modelo = "";
							if (val.nombre_modelo == "" || val.nombre_modelo == null) {
								nombre_modelo = val.producto;
							} else {
								nombre_modelo = val.nombre_modelo;
							}

							total_pedido += parseFloat(val.total);
							total_producido += parseFloat(val.totalp);

							html = html + '<tr ' + cls + ' id="' + val.codigo + '">' +
								`<td><a class="btn btn-sm btn-outline-info rounded-pill mt-1 d-block" href="core/app/view/pdf-pedido.php?codigo=${val.codigo}"><i class="fa fa-file-pdf-o"></i></a><a href="#" onclick="detalle_order('${val.codigo}','${val.comentario}', '${val.total}', '${val.name}');" class="btn btn-sm btn-outline-dark rounded-pill mt-1 d-block"><i class="glyphicon glyphicon-eye-open"></i></a></td>` +
								'<th scope="row">' + val.codigo + '</th>' +
								'<th>' + val.fecha_creacion + '</th>' +
								'<td>' + val.name + '</td>' +
								'<td>' + nombre_modelo + '</td>' +
								'<td>' + $.trim(val.codigo_unitario) + '</td>' +
								'<td><img src="storage/products/' + img_ + '" style="width:64px; cursor: pointer;" title="Ver Imagen" data-toggle="modal" data-target="#formulario" onclick="ver_imagen(\'storage/products/' + img_ + '\');"></td>' +
								'<td>' + contrato + '</td>' +
								'<td>' + val.total + '</td>' +
								'<td>' + val.totalp + '</td>' +
								'<td>' + guia + '</td>' +
								'<td>' + val.codigo_venta + '</td>' +
								`<td>${val.fecha_entrega}</td>` +
								'<td>' + fecha_e_r + '</td>';

							var $di = diferencias_dias(val.fecha_entrega, fecha_e_r);

							html = html + '<td class="depende">' + $di + '<a href="index.php?view=new_produccion_order_pedido&pcod=' + val.codigo + '" class="btn-sm btn-outline-dark mt-1 d-block rounded-pill btn" ><i class="fa fa-plus"></i></a> <a href="#" onclick="eliminar_order(\'' + val.codigo + '\');" class="btn btn-sm btn-outline-danger mt-1 d-block rounded-pill"><i class="fa fa-trash"></i></a>' +
								'<a href="index.php?view=edit_order_pedido&codigo=' + val.codigo + '" class="btn-sm btn-outline-warning mt-1 d-block rounded-pill btn" ><i class="fa fa-pencil"></i></a></td>' +
								'</tr>';
						});
						$("#tabla_lista").find('tbody').append(html);
						$("#tabla_lista").find('tfoot').append(`
							<tr clas="bg-dark" style="font-weight: bold;">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>${total_pedido}</td>
								<td>${total_producido}</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						`);

						$("#pedidos_tiempo").text($total_tiempo);
						$("#pedidos_fuera").text($total_fuera);

						if (k == 1 || k == 12) {
							cls = "";
						} else {
							$(".depende").attr('hidden', true);
							$(".ssll").attr('disabled', true);
						}
						var cur_url = window.location.href;
						var location = cur_url.split("#");
						if (location[1] == null || location[1] == undefined) {

						} else {
							$([document.documentElement, document.body]).animate({
								scrollTop: $("#" + location[1]).offset().top
							}, 1000);
						}
					});
				}

				function diferencias_dias(fecha_entrega, fecha_e_r) {
					// Define las dos fechas
					var fecha1 = new Date(fecha_entrega);
					var fecha2 = new Date(fecha_e_r);

					// Calcula la diferencia en milisegundos
					var diferenciaEnMilisegundos = fecha2 - fecha1;

					// Convierte la diferencia en días
					var diferenciaEnDias = Math.floor(diferenciaEnMilisegundos / (1000 * 60 * 60 * 24));

					if (diferenciaEnDias <= 0) {
						$total_tiempo += 1;
						return `<span class="btn-sm rounded-pill d-block btn-outline-success btn"><i class="glyphicon glyphicon-thumbs-up"></i></span>`;
					} else if (diferenciaEnDias > 0 && diferenciaEnDias <= 5) {
						$total_fuera += 1;
						return `<span class="btn-sm rounded-pill d-block btn-outline-warning btn"><i class=" glyphicon glyphicon-hand-right"></i></span>`;
					} else if (diferenciaEnDias > 5) {
						$total_fuera += 1;
						return `<span class="btn-sm rounded-pill d-block btn-outline-danger btn"><i class="glyphicon glyphicon-thumbs-down"></i></span>`;
					} else {
						return `<span></span>`;
					}
				}

				function ver_imagen(url_imagen) {
					$("#la_imagen").prop('src', url_imagen);
				}

				function allow_edit(codigo) {
					$("#n_c_" + codigo).removeAttr('disabled');
					$("#n_c_" + codigo).focus();
				}

				function save_co(codigo) {
					$.post('core/app/view/order.php?parAccion=update_guia', {
						codigo: codigo,
						num_contrato: $("#n_c_" + codigo).val()
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == "OK") {
							$("#n_c_" + codigo).attr('disabled', true);
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Los campos de CANTIDAD no pueden estar vacios.</strong>' +
									'</div>'
							});
						}
					});
				}

				function cancel_order() {
					$("#resultado").attr('hidden', true);
					$("#div_entrega").attr('hidden', true);
					$("#tabla_resultado").find('tbody').empty();
				}

				function guardar_order() {
					var cliente = $("#cliente").val();
					var cantidades = 0;
					var tiempo = $("#tiempo_entrega").val();
					var aux = id_producto.split(',');
					var cont = 0;
					for (var i = 1; i < aux.length; i++) {
						if ($("#canti_" + aux[i]).val() == 0 || $("#canti_" + aux[i]).val() == "") {
							cont++;
						} else {
							cantidades = cantidades + ',' + $("#canti_" + aux[i]).val();
						}
					}

					if ($("#cliente").val() == 0) {
						bootbox.alert({
							message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
								'<strong>Debe de elegir un cliente.</strong>' +
								'</div>'
						});
					} else {
						if (cont > 0) {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Los campos de CANTIDAD no pueden estar vacios.</strong>' +
									'</div>'
							});
						} else {
							$.get('core/app/view/order.php', {
								parAccion: 'guardar_order',
								cliente: cliente,
								productos: id_producto,
								cantidad: cantidades,
								tiempo: tiempo
							}, function(data) {
								var obj = JSON.parse(data);
								if (obj.Result == 'OK') {
									bootbox.alert({
										message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
											'<strong>Orden guardada correctamente.</strong>' +
											'</div>'
									});
									lista_ordenes(kk);
									cancel_order();
									id_producto = 0;
									cantidades = 0;
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

				function lista_clientes() {
					$.get('core/app/view/order.php', {
						parAccion: 'lista_clientes'
					}, function(data) {
						var obj = JSON.parse(data);
						if (kk == 0) {
							$.each(obj.Records, function(index, val) {
								$("#cliente").append('<option value="' + val.id + '">' + val.name + '</option>');
							});
						} else {
							$("#cliente").empty();
							$.each(obj.Records, function(index, val) {
								if (val.id == kk) {
									$("#cliente").append('<option value="' + val.id + '" selected>' + val.name + '</option>');
								}
							});
						}
					});
				}

				function lista_clientes_2() {
					$.get('core/app/view/order.php', {
						parAccion: 'lista_clientes'
					}, function(data) {
						var obj = JSON.parse(data);
						if (kk == 0) {
							$.each(obj.Records, function(index, val) {
								$("#f_cliente").append('<option value="' + val.id + '">' + val.name + '</option>');
							});
						} else {
							$.each(obj.Records, function(index, val) {
								if (val.id == kk) {
									$("#f_cliente").append('<option value="' + val.id + '" selected>' + val.name + '</option>');
								}
							});
						}

					});
				}

				function cerrar_editar() {
					$('#close_editar').click();
				}
				$(window).load(function() {
					var cur_url = window.location.href;
					var location = cur_url.split("#");
					if (location[1] == null || location[1] == undefined) {

					} else {
						location.reload = cur_url;
					}
				});
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

					var cur_url = window.location.href;
					var location = cur_url.split("#");
					if (location[1] == null || location[1] == undefined) {

					} else {
						location.reload = cur_url;
					}

					$("#cliente").on('change', function() {
						lista_ordenes($("#cliente").val());
					});
					lista_clientes_2();
					lista_clientes();
					lista_ordenes(kk);
					$("#f_cliente").on('change', function() {
						lista_ordenes($("#f_cliente").val());
					});
					$('#product_name').autocomplete({
						source: 'core/app/view/order.php?parAccion=producto_autocomplete',
						minLength: 2,
						focus: true,
						select: function(event, ui) {
							$("#tabla_resultado").find('tbody').empty();
							id_producto = id_producto + ',' + ui.item.id;
							$("#resultado").removeAttr('hidden');
							$("#div_entrega").removeAttr('hidden');
							$("#tabla_resultado").find('tbody')
								.append('<tr>' +
									'<th scope="row">' + ui.item.id + '</th>' +
									'<td>' + ui.item.value + '</td>' +
									'<td>' + ui.item.unidad + '</td>' +
									'<td><span class=\'label label-info\'>' + ui.item.tipo + '</span></td>' +
									'<td>S/. ' + ui.item.precio_unitario + '</td>' +
									'<td>' +
									'<div class="input-group col-md-12">' +
									'<input type="" class="form-control" required id="canti_' + ui.item.id + '" name="q" placeholder="Cantidad ...">' +
									'</div>' +
									'</td>' +
									'</tr>');
						}
					});

					if (k == 1 || k == 12) {
						$(".depende").removeAttr('hidden');
					} else {
						$(".depende").attr('hidden', true);
					}

					$('#close_editar').on('click', function() {
						$('#popup_editar').fadeOut('slow');
						$('.popup-overlay').fadeOut('slow');
						return false;
						flag = false;
					});

					$("#product_code").keydown(function(e) {
						if (e.which == 17 || e.which == 74) {
							e.preventDefault();
						} else {

						}
					})
				});
			</script>

</section>