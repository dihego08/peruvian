<?php //include('https://miempresa.softluttion.com/pages/porcentaje.php'); 
?>
<?php $user = UserData::getById($_SESSION["user_id"]); ?>
<?php $pedidoCod = $_GET['pcod']; ?>
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

	@media (max-width: 600px) {
		.tdd {
			padding: 1px !important;
		}
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>PEDIDO : <a href="?view=order_pedido#<?php echo ($pedidoCod); ?>"><?php echo ($pedidoCod); ?> </a></h3>
			<div class="row">
				<div id="resultado" style="padding: 25px 25px 0 25px;">
					<div class="box box-primary table-responsive" style="margin-top: 20px;">
						<form id="formulario">
							<div class="form-row">
								<div class="col-md-3">
									<label>Número Contrato</label>
									<input type="text" id="n_contrato" name="n_contrato" class="form-control rounded-pill" placeholder="Número de Guía">
								</div>
								<div class="col-md-3">
									<label>Número de Guía</label>
									<input type="text" id="guia" name="guia" class="form-control rounded-pill" placeholder="Número de Guía">
								</div>
								<div class="col-md-3">
									<label>Fecha de Entrega Estimada</label>
									<input type="text" id="fecha_estimada" name="fecha_estimada" class="form-control rounded-pill" placeholder="Fecha de Entrega Estimada">
								</div>
								<div class="col-md-3">
									<label>Fecha de Entrega</label>
									<input type="text" id="fecha_entrega" name="fecha_entrega" class="form-control rounded-pill" placeholder="Fecha de Entrega">
								</div>
								<div class="col-md-3 mt-1" style="margin-top: 1rem;">
									<label>Fecha de Pedido</label>
									<input type="text" id="fecha_desde" name="fecha_desde" class="form-control rounded-pill" placeholder="Fecha de Creacion">
								</div>
								<div class="col-md-9 mt-1" style="margin-top: 1rem;">
									<label>Producto</label>
									<input type="text" id="nombre_modelo" name="nombre_modelo" class="form-control rounded-pill" placeholder="Fecha de Entrega">
								</div>
							</div>
							<hr class="d-block w-100">
							<table class="table table-bordered table-hover" id="tabla_resultado">
								<thead>
									<tr>
										<th rowspan="2" style="vertical-align: middle; text-align: center;">Modelo</th>
										<th rowspan="2" style="vertical-align: middle; text-align: center;">Color</th>
										<th colspan="14" style="text-align: center;">Cantidades por Talla</th>
									</tr>
									<tr id="la_cabecera_de_datos">
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
										<th>Tot.</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
							<div class="pull-right">
								<table class="table table-bordered table-hover" id="tabla_total_">
									<tbody>

									</tbody>
								</table>
							</div>
							<input type="submit" id="btnGuardar" value="Guardar Cambios" class="btn btn-success rounded-pill">
						</form>
					</div>
				</div>

				<div class="col-md-12" id="div_entrega">
				</div>
				<div id="popup_editar" style="display: none;">
					<div class="content-popup">
						<div class="close"><a href="#" id="close_editar">X</a></div>
						<div>
							<h2 id="titulo_detalle">Detalle Orden de Pedido</h2>
							<div class="box box-primary">
								<table class="table table-bordered table-hover" id="tabla_detalle">
									<thead>
										<tr>
											<th>Producto</th>
											<th>Cantidad</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
							<span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
						</div>
					</div>
				</div>
				<div class="popup-overlay"></div>
			</div>
		</div>
	</div>
</section>
<script>
	var codigo_cabecera = "";
	var zux = 0;
	var k = <?php echo Core::$user->kind; ?>;
	var kk = 0;
	if (k == 1) {
		kk = 0;
	} else {
		if (k == 8) {
			kk = 2;
		} else {
			if (k == 7) {
				kk = 3;
			} else {
				if (k == 6) {
					kk = 5;
				}
			}
		}
	}

	function lista_pedido(codigo) {
		$("#tabla_resultado").find('tbody').empty();
		$("div_entrega").empty();
		var totalPedido = 0;
		var totalProduccion = 0;
		$.get('core/app/view/order.php', {
			parAccion: 'lista_detalle_produccion',
			codigo: codigo
		}, function(data) {
			var obj = JSON.parse(data);
			$("#fecha_estimada").val(obj.fecha_entrega);
			$("#fecha_entrega").val(obj.fecha_entrega_real);
			$("#n_contrato").val(obj.num_contrato);
			$("#guia").val(obj.guia_remision);
			$("#nombre_modelo").val(obj.nombre_modelo);
			$("#fecha_desde").val(obj.fecha_creacion);

			$.each(obj.Records, function(index, val) {
				$("#name_N1").text($("#name_N1").text() == "" || $("#name_N1").text() == null ? val.n1 : $("#name_N1").text());
				$("#name_N2").text($("#name_N2").text() == "" || $("#name_N2").text() == null ? val.n2 : $("#name_N2").text());
				$("#name_N3").text($("#name_N3").text() == "" || $("#name_N3").text() == null ? val.n3 : $("#name_N3").text());
				$("#name_N4").text($("#name_N4").text() == "" || $("#name_N4").text() == null ? val.n4 : $("#name_N4").text());
				$("#name_N5").text($("#name_N5").text() == "" || $("#name_N5").text() == null ? val.n5 : $("#name_N5").text());
				$("#name_N6").text($("#name_N6").text() == "" || $("#name_N6").text() == null ? val.n6 : $("#name_N6").text());
				$("#name_N7").text($("#name_N7").text() == "" || $("#name_N7").text() == null ? val.n7 : $("#name_N7").text());
				$("#name_N8").text($("#name_N8").text() == "" || $("#name_N8").text() == null ? val.n8 : $("#name_N8").text());
				$("#name_N9").text($("#name_N9").text() == "" || $("#name_N9").text() == null ? val.n9 : $("#name_N9").text());
				$("#name_N10").text($("#name_N10").text() == "" || $("#name_N10").text() == null ? val.n10 : $("#name_N10").text());
				$("#name_N11").text($("#name_N11").text() == "" || $("#name_N11").text() == null ? val.n11 : $("#name_N11").text());
				$("#name_N12").text($("#name_N12").text() == "" || $("#name_N12").text() == null ? val.n12 : $("#name_N12").text());
				$("#name_N13").text($("#name_N13").text() == "" || $("#name_N13").text() == null ? val.n13 : $("#name_N13").text());

				codigo_cabecera = val.codigo_cabecera;
				zux++;
				totalPedido = totalPedido + parseInt(val.total);
				totalProduccion = totalProduccion + parseInt(val.ptotal);
				$("#tabla_resultado").find('tbody').append('<tr><td rowspan="2">' + val.modelo + '</td>' +
					'<td rowspan="2">' + val.color + '</td>' +
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
					'<td>' + val.xxl + '</td><td>' + val.total + '</td></tr>');
				$("#tabla_resultado").find('tbody').append('<tr>' +
					'<td class="tdd"><input type="hidden" name="pid_' + zux + '" value="' + val.id + '"><input type="text" class="form-control rounded-pill tdd" id="p2_' + zux + '" name="p2_' + zux + '" value="' + val.p2 + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="p4_' + zux + '" name="p4_' + zux + '" value="' + val.p4 + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="p6_' + zux + '" name="p6_' + zux + '" value="' + val.p6 + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="p8_' + zux + '" name="p8_' + zux + '" value="' + val.p8 + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="p10_' + zux + '" name="p10_' + zux + '" value="' + val.p10 + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="p12_' + zux + '" name="p12_' + zux + '" value="' + val.p12 + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="p14_' + zux + '" name="p14_' + zux + '" value="' + val.p14 + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="p16_' + zux + '" name="p16_' + zux + '" value="' + val.p16 + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="ps_' + zux + '" name="ps_' + zux + '" value="' + val.ps + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="pm_' + zux + '" name="pm_' + zux + '" value="' + val.pm + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="pl_' + zux + '" name="pl_' + zux + '" value="' + val.pl + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="pxl_' + zux + '" name="pxl_' + zux + '" value="' + val.pxl + '"></td>' +
					'<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="pxxl_' + zux + '" name="pxxl_' + zux + '" value="' + val.pxxl + '"></td>' +
					'<td class="tdd">' + val.ptotal + '</td>' +
					'</tr>');
			});
			$("#tabla_total_").find('tbody').empty();
			$("#tabla_total_").find('tbody').append('<tr class="danger"><td>TOTAL PEDIDO</td><td>' + totalPedido + '</td><td>TOTAL PRODUCIDO</td><td>' + totalProduccion + '</td></tr>');
		});
	}

	function cancel_order() {
		zux = 0;
		$("#tabla_resultado_2").find('tbody').empty();
		$("#formulario")[0].reset();
	}
	var to = 0;

	function agregar_listado() {
		var sto = 0;
		sto = parseInt(sto) + ($("#c_2").val() ? parseInt($("#c_2").val()) : 0);
		sto = parseInt(sto) + ($("#c_3").val() ? parseInt($("#c_3").val()) : 0);
		sto = parseInt(sto) + ($("#c_4").val() ? parseInt($("#c_4").val()) : 0);
		sto = parseInt(sto) + ($("#c_5").val() ? parseInt($("#c_5").val()) : 0);
		sto = parseInt(sto) + ($("#c_6").val() ? parseInt($("#c_6").val()) : 0);
		sto = parseInt(sto) + ($("#c_7").val() ? parseInt($("#c_7").val()) : 0);
		sto = parseInt(sto) + ($("#c_8").val() ? parseInt($("#c_8").val()) : 0);
		sto = parseInt(sto) + ($("#c_9").val() ? parseInt($("#c_9").val()) : 0);
		sto = parseInt(sto) + ($("#c_10").val() ? parseInt($("#c_10").val()) : 0);
		sto = parseInt(sto) + ($("#c_11").val() ? parseInt($("#c_11").val()) : 0);
		sto = parseInt(sto) + ($("#c_12").val() ? parseInt($("#c_12").val()) : 0);
		sto = parseInt(sto) + ($("#c_13").val() ? parseInt($("#c_13").val()) : 0);
		sto = parseInt(sto) + ($("#c_14").val() ? parseInt($("#c_14").val()) : 0);
		zux++;
		$("#tabla_resultado_2").find('tbody').append('<tr>' +
			'<td>' + $("#product_name").val() + '<input type="hidden" value="' + $("#product_name").val() + '" name="nn_0_' + zux + '"></td>' +
			'<td>' + $("#c_1").val() + '<input type="hidden" value="' + $("#c_1").val() + '" name="nn_1_' + zux + '" id="nn_1_' + zux + '"></td>' +
			'<td>' + $("#c_2").val() + '<input type="hidden" value="' + $("#c_2").val() + '" name="nn_2_' + zux + '" id="nn_2_' + zux + '"></td>' +
			'<td>' + $("#c_3").val() + '<input type="hidden" value="' + $("#c_3").val() + '" name="nn_3_' + zux + '" id="nn_3_' + zux + '"></td>' +
			'<td>' + $("#c_4").val() + '<input type="hidden" value="' + $("#c_4").val() + '" name="nn_4_' + zux + '" id="nn_4_' + zux + '"></td>' +
			'<td>' + $("#c_5").val() + '<input type="hidden" value="' + $("#c_5").val() + '" name="nn_5_' + zux + '" id="nn_5_' + zux + '"></td>' +
			'<td>' + $("#c_6").val() + '<input type="hidden" value="' + $("#c_6").val() + '" name="nn_6_' + zux + '" id="nn_6_' + zux + '"></td>' +
			'<td>' + $("#c_7").val() + '<input type="hidden" value="' + $("#c_7").val() + '" name="nn_7_' + zux + '" id="nn_7_' + zux + '"></td>' +
			'<td>' + $("#c_8").val() + '<input type="hidden" value="' + $("#c_8").val() + '" name="nn_8_' + zux + '" id="nn_8_' + zux + '"></td>' +
			'<td>' + $("#c_9").val() + '<input type="hidden" value="' + $("#c_9").val() + '" name="nn_9_' + zux + '" id="nn_9_' + zux + '"></td>' +
			'<td>' + $("#c_10").val() + '<input type="hidden" value="' + $("#c_10").val() + '" name="nn_10_' + zux + '" id="nn_10_' + zux + '"></td>' +
			'<td>' + $("#c_11").val() + '<input type="hidden" value="' + $("#c_11").val() + '" name="nn_11_' + zux + '" id="nn_11_' + zux + '"></td>' +
			'<td>' + $("#c_12").val() + '<input type="hidden" value="' + $("#c_12").val() + '" name="nn_12_' + zux + '" id="nn_12_' + zux + '"></td>' +
			'<td>' + $("#c_13").val() + '<input type="hidden" value="' + $("#c_13").val() + '" name="nn_13_' + zux + '" id="nn_13_' + zux + '"></td>' +
			'<td>' + $("#c_14").val() + '<input type="hidden" value="' + $("#c_14").val() + '" name="nn_14_' + zux + '" id="nn_14_' + zux + '"></td>' +
			'<td>' + sto + '<input type="hidden" name="tot_' + zux + '" id="tot_' + zux + '" value="' + sto + '"></td>' +
			'<td><button class="borrar btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>');
		to = parseInt(to) + parseInt(sto);
		llenar();
		calcular_tot(zux);
	}

	function calcular_tot(z) {
		var fd = 0;
		for (var i = 1; i <= z; i++) {
			fd = parseInt(fd) + ($("#tot_" + i).val() ? parseInt($("#tot_" + i).val()) : 0); // parseInt($("#tot_"+i).val());
		}
		$("#tabla_total_").find('tbody').empty();
		$("#tabla_total_").find('tbody').append('<tr><td>' + fd + '</td></tr>');
	}

	function lista_clientes() {
		$.get('core/app/view/order.php', {
			parAccion: 'lista_clientes'
		}, function(data) {
			var obj = JSON.parse(data);
			if (kk == 0) {
				$.each(obj.Records, function(index, val) {
					$("#s_cliente").append('<option value="' + val.id + '">' + val.name + '</option>');
				});
			} else {
				$.each(obj.Records, function(index, val) {
					if (val.id == kk) {
						$("#s_cliente").append('<option value="' + val.id + '" selected>' + val.name + '</option>');
					}
				});
			}
		});
	}
	$(function() {
		$(document).on('click', '.borrar', function(event) {
			event.preventDefault();
			$(this).closest('tr').remove();
			calcular_tot(zux);
		});
	});

	function pintar_cabecera(argument) {
		$.post('core/app/view/order.php?parAccion=para_cabecera', {
			codigo_cabecera: <?php echo ($pedidoCod); ?>
		}, function(data) {
			var obj = JSON.parse(data);

			$("#name_N1").val(obj.N1);
			$("#name_N2").val(obj.N2);
			$("#name_N3").val(obj.N3);
			$("#name_N4").val(obj.N4);
			$("#name_N5").val(obj.N5);
			$("#name_N6").val(obj.N6);
			$("#name_N7").val(obj.N7);
			$("#name_N8").val(obj.N8);
			$("#name_N9").val(obj.N9);
			$("#name_N10").val(obj.N10);
			$("#name_N11").val(obj.N11);
			$("#name_N12").val(obj.N12);
			$("#name_N13").val(obj.N13);
		});
	}
	$(document).ready(function() {

		$('#fecha_entrega').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});
		$('#fecha_estimada').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});
		//fecha_desde
		$('#fecha_desde').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});
		lista_pedido(<?php echo ($pedidoCod); ?>);
		$("#formulario").submit(function(event) {
			event.preventDefault();
			var nFilas = $("#tabla_resultado").find('tbody tr').length;
			if (nFilas > 0) {
				$.ajax({
						url: 'core/app/view/order.php?parAccion=actualizar_order_produccion&cant=' + zux + "&codigo=" + codigo_cabecera,
						type: 'POST',
						data: $(this).serialize(),
					})
					.done(function() {
						bootbox.alert({
							message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
								'<strong>Orden guardada correctamente.</strong>' +
								'</div>'
						});
						lista_pedido(<?php echo ($pedidoCod); ?>);
						to = 0;
						zux = 0;
					})
					.fail(function() {
						bootbox.alert({
							message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
								'<strong>Algo ha salido mal.</strong>' +
								'</div>'
						});
					})
					.always(function() {});
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>No has Agregado nada al listado.</strong>' +
						'</div>'
				});
			}
		});
	});
</script>