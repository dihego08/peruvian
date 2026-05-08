<?php

$cli = "";
if ((isset($_GET["cli"]) && $_GET["cli"] != "")) {
	$cli = $_GET['cli'];
	$_SESSION['cliname'] = $cli;
}

?>
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

	.ui-datepicker-calendar {
		display: none;
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
			<h3>Historial de Pagos </h3>
			<?php
			if ($cli != "") {
			?>
				<h4>CLIENTE : <?php echo ($cli); ?> </h4>
			<?php
			}
			?>
			<p><b>Seleccionar un Cliente:</b></p>
			<div class="row">
				<div class="col-md-6">
					<select class="form-control rounded-pill" name="combo_cliente" id="combo_cliente">
						<option value="0">SELECCIONA ...</option>
					</select>
				</div>
				<div class="col-md-6">
					<select class="form-control rounded-pill" name="combo_cliente_ruc" id="combo_cliente_ruc">
						<option value="0">SELECCIONA ...</option>
					</select>
				</div>
				<div class="form-group col-md-12">
					<label for="fecha_desde">Fecha</label>
					<div class="input-group">
						<input type="text" name="fecha_desde" id="fecha_desde" readonly="readonly" class="form-control rounded-pill-left date-picker">
						<span class="input-group-addon">
							<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
						</span>
					</div>
				</div>
				<div class="form-row col-md-12 text-center">
					<button class="btn btn-success rounded-pill" onclick="filtro_por_fecha();"><i class="fa fa-search"></i> Filtrar</button>
				</div>
			</div>
			<div class="row">
				<div id="resultado_busqueda_order" hidden>
					<div class="col-md-12">
						<h2>Resultado de Búsqueda</h2>
						<div class="box box-primary table-responsive">
							<table class="table table-bordered table-hover" id="detalle_busqueda_order">
								<thead>
									<tr>
										<th>Factura</th>
										<th>Guia</th>
										<th>Pedido</th>
										<th>Cliente</th>
										<th>Fecha</th>
										<th>Total</th>
										<th>Pagado</th>
										<th>Adeuda</th>
										<th></th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-12">
						<h2>Balance</h2>
						<div class="box box-primary table-responsive">
							<table class="table table-bordered table-hover" id="balance_table">
								<thead>
									<tr>
										<th>Total</th>
										<th>Pagado</th>
										<th>Adeuda</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div id="popup_editar" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar"><img src="../css/images/close.png" /></a></div>
					<div>
						<h2 id="titulo_detalle"></h2>
						<div class="box box-primary">
							<table class="table table-bordered table-hover" id="detalle_pagos">
								<thead>
									<th>Fecha de Pago</th>
									<th>Monto</th>
									<th>Adeuda</th>
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
			<div class="popup-overlay"></div>

		</div>
</section>
<script type="text/javascript">
	function filtro_por_fecha() {

		var fecha = $("#fecha_desde").val();
		var cli = $("#combo_cliente").val();
		if (fecha == "" && cli == 0) {
			bootbox.alert({
				message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
					'<strong>Elegir al menos un filtro.</strong>' +
					'</div>'
			});
		} else {
			lista_ventas('cliente', cli, fecha);
			$("#fecha_desde").val("");
		}

	}

	function cerrar_editar() {
		$('#close_editar').click();
	}

	function lista_ventas(filtro, codigo, fecha) {
		$.get('core/app/view/venta.php', {
			parAccion: 'lista_ventas',
			filtro: filtro,
			codigo: codigo,
			fecha: fecha
		}, function(data) {
			var obj = JSON.parse(data);
			var ttt = 0;
			var pp = 0;
			var add = 0;
			$("#resultado_busqueda_order").removeAttr('hidden');
			$("#detalle_busqueda_order").find('tbody').empty();
			$("#balance_table").find('tbody').empty();
			$.each(obj.Records, function(index, val) {
				ttt = parseFloat(ttt) + parseFloat(val.pagado);
				add = parseFloat(add) + parseFloat(val.a_cuenta);
				pp = parseFloat(pp) + parseFloat(val.total);
				if (val.a_cuenta > parseFloat('0.00')) {
					$("#detalle_busqueda_order").find('tbody').append('<tr class="danger"><td>' + val.codigo_venta + '</td><td>' + val.guia + '</td><td>' + val.pedido_cod + '</td><td>' + val.person + '</td><td>' + val.fecha_creacion + '</td><td>S/. ' + val.valor_pagar + '</td><td>S/. ' + val.pagado + '</td><td>S/. ' + val.a_cuenta + '</td><td>' + '<a href="index.php?view=detalle_pago&cid=' + val.id_person + '&vid=' + val.codigo_venta + '" class="btn btn-sm btn-outline-success rounded-pill"><i class="fa fa-search-plus"></i> Detalle Pagos</a>' + '</td></tr>');
				} else {
					$("#detalle_busqueda_order").find('tbody').append('<tr><td>' + val.codigo_venta + '</td><td>' + val.guia + '</td><td>' + val.pedido_cod + '</td><td>' + val.person + '</td><td>' + val.fecha_creacion + '</td><td>S/. ' + val.valor_pagar + '</td><td>S/. ' + val.pagado + '</td><td>S/. ' + val.a_cuenta + '</td><td><a href="index.php?view=detalle_pago&cid=' + val.id_person + '&vid=' + val.codigo_venta + '" class="btn btn-sm btn-outline-success rounded-pill"><i class="fa fa-search-plus"></i> Detalle Pagos</a>' + '</td></tr>');
				}
				// anterior enlace para detalle de pago :'+'<a href="#" onclick="historial_pago('+val.id_person+',\''+val.codigo_venta+'\')" class="btn btn-xs btn-info"><i class="fa fa-search-plus"></i> Ver Detalle</a>'+'
			});
			$("#balance_table").find('tbody').append('<tr><td>S/. ' + pp.toFixed(2) + '</td><td>S/. ' + ttt.toFixed(2) + '</td><td>S/. ' + add.toFixed(2) + '</td></tr>');
		});
	}

	function lista_ventas_ruc(ruc_add, fecha) {
		$.get('core/app/view/order.php', {
			parAccion: 'lista_ventas_ruc',
			ruc_add: ruc_add,
			fecha: fecha
		}, function(data) {
			var obj = JSON.parse(data);
			var ttt = 0;
			var pp = 0;
			var add = 0;
			$("#resultado_busqueda_order").removeAttr('hidden');
			$("#detalle_busqueda_order").find('tbody').empty();
			$("#balance_table").find('tbody').empty();
			$.each(obj, function(index, val) {
				ttt = parseFloat(ttt) + parseFloat(val.pagado);
				add = parseFloat(add) + parseFloat(val.a_cuenta);
				pp = parseFloat(pp) + parseFloat(val.total);
				if (val.a_cuenta > parseFloat('0.00')) {
					$("#detalle_busqueda_order").find('tbody').append('<tr class="danger"><td>' + val.codigo_venta + '</td><td>' + val.guia + '</td><td>' + val.pedido_cod + '</td><td>' + val.person + '</td><td>' + val.fecha_creacion + '</td><td>S/. ' + val.valor_pagar + '</td><td>S/. ' + val.pagado + '</td><td>S/. ' + val.a_cuenta + '</td><td>' + '<a href="index.php?view=detalle_pago&cid=' + val.id_person + '&vid=' + val.codigo_venta + '" class="btn btn-xs btn-success"><i class="fa fa-search-plus"></i>Detalle Pagos</a>' + '</td></tr>');
				} else {
					$("#detalle_busqueda_order").find('tbody').append('<tr><td>' + val.codigo_venta + '</td><td>' + val.guia + '</td><td>' + val.pedido_cod + '</td><td>' + val.person + '</td><td>' + val.fecha_creacion + '</td><td>S/. ' + val.valor_pagar + '</td><td>S/. ' + val.pagado + '</td><td>S/. ' + val.a_cuenta + '</td><td><a href="index.php?view=detalle_pago&cid=' + val.id_person + '&vid=' + val.codigo_venta + '" class="btn btn-xs btn-success"><i class="fa fa-search-plus"></i>Detalle Pagos</a>' + '</td></tr>');
				}
				// anterior enlace para detalle de pago :'+'<a href="#" onclick="historial_pago('+val.id_person+',\''+val.codigo_venta+'\')" class="btn btn-xs btn-info"><i class="fa fa-search-plus"></i> Ver Detalle</a>'+'
			});
			$("#balance_table").find('tbody').append('<tr><td>S/. ' + pp.toFixed(2) + '</td><td>S/. ' + ttt.toFixed(2) + '</td><td>S/. ' + add.toFixed(2) + '</td></tr>');
		});
	}

	function historial_pago(id_person, codigo_venta) {
		$.get('core/app/view/venta.php', {
			parAccion: 'historial_pago',
			id_person: id_person,
			codigo_venta: codigo_venta
		}, function(data) {
			var obj = JSON.parse(data);
			$("#titulo_detalle").append("CODIGO: " + codigo_venta);
			$("#detalle_pagos").find('tbody').empty();
			$.each(obj.Records, function(index, val) {
				$("#detalle_pagos").find('tbody').append('<tr><td scope="row">' + val.fecha_creacion + '</td><td>S/. ' + val.pago + '</td><td>S/. ' + val.deuda + '</td></tr>');
			});
		});
		$('#popup_editar').fadeIn('slow');
		$('.popup-overlay').fadeIn('slow');
		$('.popup-overlay').height($(window).height());
		return false;
	}
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '< Ant',
		nextText: 'Sig >',
		currentText: 'Hoy',
		monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
		dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
		dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$(document).ready(function() {
		$('.date-picker').datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'mm-yy',
			onClose: function(dateText, inst) {
				$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
			}
		});
		var k = <?php echo Core::$user->kind; ?>;
		//console.log("K " + k);
		if (k == 1) {
			$.get('core/app/view/order.php', {
				parAccion: 'lista_clientes'
			}, function(data) {
				$("#lista_clientes").empty();
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					$("#combo_cliente").append('<option value="' + val.id + '">' + val.name + '</option>');
				});
			});
			$.get('core/app/view/order.php', {
				parAccion: "solo_ruc"
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj, function(index, val) {
					$("#combo_cliente_ruc").append('<option value="' + val.ruc_add + '">' + val.ruc_add + '</option>');
				});
			});
		} else {
			if (k == 8) {
				lista_ventas('cliente', 2, $("#fecha_desde").val());
			} else {
				if (k == 7) {
					lista_ventas('cliente', 3, $("#fecha_desde").val());
				} else {
					if (k == 6) {
						lista_ventas('cliente', 5, $("#fecha_desde").val());
					}
				}
			}
		}

		$("#combo_cliente").on('change', function() {
			if ($("#combo_cliente").val() == 0) {
				//lista_ventas('ninguno', 0);
			} else {
				//alert('seleecion de cliente');
				lista_ventas('cliente', $("#combo_cliente").val(), $("#fecha_desde").val());
			}
		});
		$("#combo_cliente_ruc").on("change", function() {
			if ($("#combo_cliente_ruc").val() == 0) {} else {
				lista_ventas_ruc($("#combo_cliente_ruc").val(), $("#fecha_desde").val());
			}
		});
		$('#close_editar').on('click', function() {
			//limpiar_formulario();
			$('#popup_editar').fadeOut('slow');
			$('.popup-overlay').fadeOut('slow');
			return false;
			flag = false;
		});
	});
</script>