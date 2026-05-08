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

	#popup_editar {
		left: 0;
		position: absolute;
		top: 100px;
		width: 100%;
		z-index: 1001;
	}

	#popup_editar_2 {
		left: 0;
		position: absolute;
		top: 100px;
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

	.primary {
		background-color: wheat;
	}
</style>
<section class="content">
	<div class="row">
		<div class="box box-primary">
			<div class="box-body">
				<form class="form-horizontal" method="post" id="filter" action="index.php?view=products&act=filtrar" role="form">
					<fieldset>
						<legend>Filtros de Búsqueda</legend>
						<div class="form-group col-md-12">
							<label>Caja</label>
							<select class="form-control rounded-pill" id="combo_cajas">
							</select>
						</div>
					</fieldset>
					<div class="form-group col-md-2" id="kardex">
						<!--<a href="index.php?action=recalculacajakardex" class="btn btn-primary" style="width: 50%;" id="boton_kardex" >Generar Kardex</a>-->
						<!--<button type="submit" class="btn btn-primary">Filtrar</button>-->
					</div>
				</form>
			</div>
			<div class="w-100 text-right">
				<button class="btn btn-success rounded-pill" onclick="formulario();">Agregar Abono - Cargo</button>
			</div>

			<div class="col-md-12">
				<div id="lista_order">
					<h3>Listado de Abonos</h3>
					<div class="box box-primary">
						<form id="formid" action="#" method="post">
							<table class="table table-bordered table-hover" id="tabla_lista">
								<thead>
									<tr>
										<th>Codigo</th>
										<th>Banco</th>
										<th>Periodo</th>
										<th>F. Depósito</th>
										<th>Monto</th>
										<th>Saldo</th>
										<th>Retiro</th>
										<th colspan="2">Acciones</th>
									</tr>
								</thead>
								<tbody>

								</tbody>

							</table>
						</form>
						<div class="form-group col-md-2" id="abono_opciones">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<h3 class="pull-right" style="z-index: 1000001; position: relative;"><span class="label label-success" id="saldo_t"> </span></h3>
			</div>
			<div class="col-md-12">
				<div id="lista_order_2">
					<h3>Listado de Cargos</h3>
					<div class="box box-primary">
						<table class="table table-bordered table-hover" id="tabla_lista_2">
							<thead>
								<tr>
									<th>Codigo</th>
									<th>Concepto</th>
									<th>Periodo</th>
									<th>Fecha</th>
									<th>Monto</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div id="popup_editar" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar">X</a></div>
					<div>
						<h2 id="titulo_detalle">Agregar Abono - Cargo</h2>
						<div class="box box-primary">
							<div class="col-md-12">
								<h3><b>Agregar:</b></h3>
								<div class="row">
									<div class="form-group col-md-6">
										<label>Banco - Concepto</label>
										<input type="text" id="concepto" name="concepto" class="form-control" placeholder="Concepto...">
									</div>
									<div class="form-group col-md-6">
										<label>Monto</label>
										<input type="text" id="monto" name="monto" class="form-control" placeholder="Monto...">
									</div>
									<div class="form-group col-md-4">
										<label for="fecha_pago">Fecha</label>
										<div class="input-group">
											<input type="text" name="fecha_pago" id="fecha_pago" readonly="readonly" class="form-control clsDatePicker">
											<span class="input-group-addon">
												<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
											</span>
										</div>
									</div>
									<div class="form-group col-md-4">
										<label>Periodo</label>
										<input type="text" class="form-control" id="periodo" name="">
									</div>
									<div class="form-group col-md-4">
										<label>Tipo</label>
										<select class="form-control" id="combo_tipo">
											<option value="abono">Abono</option>
											<option value="cargo">Cargo</option>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label>Caja</label>
										<select class="form-control" id="combo_cajas_insert" name="combo_cajas_insert">
										</select>
									</div>
									<div class="form-group col-md-4">
										<label>Cuenta Corriente</label>
										<select class="form-control" id="combo_banco_cuentas" name="combo_banco_cuentas">
										</select>
									</div>
									<div class="form-group col-md-4">
										<label for="fecha_vencimiento">Fec. Vencimiento</label>
										<div class="input-group">
											<input type="text" name="fecha_vencimiento" id="fecha_vencimiento" readonly="readonly" class="form-control clsDatePicker">
											<span class="input-group-addon">
												<i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
											</span>
										</div>
									</div>
									<div class="form-group col-md-4">
										<label>Prioridad</label>
										<select class="form-control" id="prioridad">
											<option value="1">Inmediato</option>
											<option value="2">Urgente</option>
											<option value="3">Importante</option>
											<option value="4">Por Reactivar</option>
											<option value="5">Pagado</option>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label>Estado</label>
										<select class="form-control" id="combo_estado" disabled>
											<option value="0">Debe</option>
											<option value="1" selected>Pagado</option>
										</select>
									</div>

									<div class="form-group col-md-12">
										<!--<button class="btn btn-danger" onclick="cancel_order();">Cancelar</button>-->
										<button class="btn btn-success" id="f" style="width: 100%;" onclick="guardar_();">Guardar</button>
									</div>
								</div>
							</div>
						</div>
						<span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
						<!--<button type="submit" class="btn btn-success" style="float: right;" id="btn_formulario">Actualizar</button>-->
					</div>
				</div>
			</div>

			<div id="popup_editar_2" style="display: none;">
				<div class="content-popup">
					<div class="close"><a href="#" id="close_editar_2"><img src="../css/images/close.png" /></a></div>
					<div>
						<h2 id="titulo_detalle">Pagar Cuenta</h2>
						<div class="box box-primary" style="overflow: hidden;">
							<fieldset>
								<legend>Seleccionar:</legend>
								<div class="form-row col-md-12" id="lista_retiros">

								</div>
							</fieldset>
							<div class="form-row col-md-12" style="margin-top: 10px;">
								<span class="btn btn-danger" onclick="cerrar_editar_2()">Cerrar</span>
								<button class="btn btn-info" id="btn_pagar_cuenta">Pagar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="popup-overlay"></div>
		</div>
		<script type="text/javascript">
			var fecha = new Date();
			var mes = fecha.getMonth();

			function cerrar_editar() {
				$('#close_editar').click();
			}

			function cerrar_editar_2() {
				$('#close_editar_2').click();
			}

			function formulario() {
				$('#popup_editar').fadeIn('slow');
				$('.popup-overlay').fadeIn('slow');
				$('.popup-overlay').height($(window).height());
				combo_cajas_insert($("#combo_cajas").val());
				combo_banco_cuentas(0);
				return false;
			}

			function guardar_() {
				var concepto = $("#concepto").val();
				var fecha_vencimiento = $("#fecha_vencimiento").val();
				var fecha_pago = $("#fecha_pago").val();
				var periodo = $("#periodo").val();
				var monto = $("#monto").val();
				var estado = $("#combo_estado").val();
				var tipo = $("#combo_tipo").val();
				var prioridad = $("#prioridad").val();
				var caja = $("#combo_cajas_insert").val();
				var cuenta = $("#combo_banco_cuentas").val();

				$.get('core/app/view/caja.php', {
					parAccion: 'guardar',
					concepto: concepto,
					fecha_pago: fecha_pago,
					periodo: periodo,
					monto: monto,
					tipo: tipo,
					fecha_vencimiento: fecha_vencimiento,
					estado: estado,
					prioridad: prioridad,
					caja: caja,
					cuenta: cuenta
				}, function(data) {
					var obj = JSON.parse(data);
					if (obj.Result == 'OK') {
						bootbox.alert({
							message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
								'<strong>Registrado Correctamente.</strong>' +
								'</div>'
						});
						//lista_cuentas(mes + 1);
						lista_abonos(caja);
						lista_cargos(caja);
						saldo(caja);
						//$('#popup_editar').fadeOut('slow');
						//lista_cargos();
						//saldo();
					} else {
						bootbox.alert({
							message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
								'<strong>Ago ha salido mal.</strong>' +
								'</div>'
						});
					}
				});
			}

			function lista_abonos(cajaId) {
				$("#tabla_lista").find('tbody').empty();
				$("#abono_opciones").empty();
				$.get('core/app/view/caja.php', {
					parAccion: 'lista_abonos',
					caja_id: cajaId
				}, function(data) {
					var obj = JSON.parse(data);
					$.each(obj.Records, function(index, val) {

						$("#tabla_lista").find('tbody').append('<tr><td><input type="checkbox" value="' + val.id + '" name="abonos[]" />' + val.id + '</td><td>' + val.concepto + '</td><td>' + val.periodo + '</td><td>' + val.fecha_pago + '</td><td>S/. ' + val.monto + '</td><td>S./ ' + val.saldo + '</td><td><input type="text" name="retiro_' + val.id + '" class="form-control" id="retiro_' + val.id + '"/></td><td><span class="btn-xs btn-danger" onclick="eliminar(' + val.id + ');" style="cursor: pointer;"><i class="fa fa-trash"></i></span></td></tr>');

					});
					//$("#abono_opciones").append('<span class="btn-xs btn-success" onclick="unir_saldos_abonos();" style="cursor: pointer;">UNIR SALDOS</span>');
				});
			}

			function eliminar(id) {
				var cajaId = $("#combo_cajas").val();
				bootbox.confirm({
					message: "¿Seguro de Eliminar este elemento?",
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
							$.get('core/app/view/caja.php', {
								parAccion: 'eliminar',
								id: id
							}, function(data) {
								var obj = JSON.parse(data);
								if (obj.Result == 'OK') {

									//lista_cotizaciones();
									lista_abonos(cajaId);
									lista_cargos(cajaId);
									saldo(cajaId);
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

			function lista_cargos(cajaId) {
				$("#tabla_lista_2").find('tbody').empty();
				$.get('core/app/view/caja.php', {
					parAccion: 'lista_cargos',
					caja_id: cajaId
				}, function(data) {
					var obj = JSON.parse(data);
					$.each(obj.Records, function(index, val) {
						$("#tabla_lista_2").find('tbody').append('<tr><td>' + val.id + '</td><td>' + val.concepto + '</td><td>' + val.periodo + '</td><td>' + val.fecha_pago + '</td><td> S/. ' + val.monto + '</td><td><span class="btn-xs btn-success" onclick="pagar_cargo(' + val.id + ');" style="cursor: pointer;">PAGAR</span><span class="btn-xs btn-danger" onclick="eliminar(' + val.id + ');" style="cursor: pointer;"><i class="fa fa-trash"></i></span> </td></tr>');
					});
				});
			}

			function editar(id, tipo) {
				var tipo_ = $("#select_" + tipo + "_" + id).val();
				var cajaId = $("#combo_cajas").val();
				if (tipo_ == 0) {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Antes de Editar, Cambiar tipo.</strong>' +
							'</div>'
					});
				} else {
					console.log(tipo_);
					$.get('core/app/view/caja.php', {
						parAccion: 'editar',
						id: id,
						tipo: tipo_
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == 'OK') {
							//lista_cotizaciones();
							lista_abonos(cajaId);
							lista_cargos(cajaId);
							saldo(cajaId);
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

			function unir_saldos_abonos() {

				var selected = '0';
				var montos = '0';
				var tipo_ = 0;
				var cajaId = $("#combo_cajas").val();
				$('#formid input[type=checkbox]').each(function() {
					if (this.checked) {
						selected += ',' + $(this).val();
						montos += ',' + $('#retiro_' + $(this).val()).val();
					}
				});

				if (selected != '') {
					alert('Has seleccionado: ' + selected + " montos : " + montos);
					tipo_ = 1;
				} else {
					//alert('Debes seleccionar al menos una opción.');
					tipo_ = 0;
				}
				if (tipo_ == 0) {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Antes de Pagar, Debe seleccionar los abonos que deseas unir.</strong>' +
							'</div>'
					});
				} else {
					//console.log(tipo_);
					$.get('core/app/view/caja.php', {
						parAccion: 'unir_saldo_abonos',
						abonos: selected,
						montos: montos
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == 'OK') {
							bootbox.alert({
								message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Se cargo el saldo de todos los abonos seleccionados al ultimo abono registrado.</strong>' +
									'</div>'
							});
							//lista_cotizaciones();
							lista_abonos(cajaId);
							lista_cargos(cajaId);
							saldo(cajaId);
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

			function pagar_cargo(id) {

				var selected = '0';
				var montos = '0';
				var tipo_ = 0;
				var movId = id;
				var cajaId = $("#combo_cajas").val();
				$('#formid input[type=checkbox]').each(function() {
					if (this.checked) {
						selected += ',' + $(this).val();
						montos += ',' + $('#retiro_' + $(this).val()).val();
					}
				});

				if (selected != '') {
					//alert('Has seleccionado: '+selected+" montos : "+ montos);
					tipo_ = 1;
				} else {
					//alert('Debes seleccionar al menos una opción.');
					tipo_ = 0;
				}
				if (tipo_ == 0) {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Antes de Pagar, Debe seleccionar los abonos con los que pagara.</strong>' +
							'</div>'
					});
				} else {
					//console.log(tipo_);
					$.get('core/app/view/caja.php', {
						parAccion: 'pagar_cargo',
						id: movId,
						abonos: selected,
						montos: montos
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == 'OK') {
							bootbox.alert({
								message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Cargo Pagado Correctamente.</strong>' +
									'</div>'
							});
							//lista_cotizaciones();
							lista_abonos(cajaId);
							lista_cargos(cajaId);
							saldo(cajaId);
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

			function saldo(cajaId) {
				$.get('core/app/view/caja.php', {
					parAccion: 'saldo',
					caja_id: cajaId
				}, function(data) {
					var obj = JSON.parse(data);
					var suma = 0;
					$.each(obj.Records, function(index, val) {
						if (val.tipo == 'cargo') {
							suma = parseFloat(suma) - parseFloat(val.monto);
						} else {
							if (val.tipo == 'abono') {
								suma = parseFloat(suma) + parseFloat(val.monto);
							}
						}
					});
					$("#saldo_t").text("S/. " + suma.toFixed(2));
				});
			}

			function combo_cajas(id) {
				$("#combo_cajas").empty();
				$.get('core/app/view/caja.php', {
					parAccion: 'combo_cajas'
				}, function(data) {
					var obj = JSON.parse(data);
					$("#combo_cajas").append('<option value="0">SELECCIONA ...</option>');
					$.each(obj.Records, function(index, val) {
						if (val.id == id) {
							$("#combo_cajas").append('<option value="' + val.id + '" selected>' + val.descripcion + '</option>')
						} else {
							$("#combo_cajas").append('<option value="' + val.id + '">' + val.descripcion + '</option>')
						}

					});
				});
			}

			function combo_cajas_insert(id) {
				$("#combo_cajas_insert").empty();
				$.get('core/app/view/caja.php', {
					parAccion: 'combo_cajas'
				}, function(data) {
					var obj = JSON.parse(data);
					$("#combo_cajas_insert").append('<option value="0">SELECCIONA ...</option>');
					$.each(obj.Records, function(index, val) {
						if (val.id == id) {
							$("#combo_cajas_insert").append('<option value="' + val.id + '" selected>' + val.descripcion + '</option>')
						} else {
							$("#combo_cajas_insert").append('<option value="' + val.id + '">' + val.descripcion + '</option>')
						}

					});
				});
			}

			function combo_banco_cuentas(id) {
				//var cajaId = $("combo_cajas_insert").val();
				//alert("carganod cuentas de caja "+caja_id);
				$("#combo_banco_cuentas").empty();
				$.get('core/app/view/caja.php', {
					parAccion: 'combo_banco_cuentas',
					caja_id: $("combo_cajas_insert").val()
				}, function(data) {
					var obj = JSON.parse(data);
					$("#combo_banco_cuentas").append('<option value="0">SELECCIONA ...</option>');
					$.each(obj.Records, function(index, val) {
						if (val.id == id) {
							$("#combo_banco_cuentas").append('<option value="' + val.id + '" selected>' + val.descripcion + '</option>')
						} else {
							$("#combo_banco_cuentas").append('<option value="' + val.id + '">' + val.descripcion + '</option>')
						}

					});
				});
			}

			$(document).ready(function() {
				//lista_abonos();
				//lista_cargos();
				combo_cajas(0);
				//saldo();


				$('#fecha_pago').datepicker({
					dateFormat: 'yy-mm-dd',
					changeMonth: true,
					changeYear: true,
					altField: "#fecha_nacimiento_hidden",
					altFormat: "yy-mm-dd"
				});
				$('#fecha_vencimiento').datepicker({
					dateFormat: 'yy-mm-dd',
					changeMonth: true,
					changeYear: true,
					altField: "#fecha_nacimiento_hidden",
					altFormat: "yy-mm-dd"
				});
				$('#fecha_').datepicker({
					dateFormat: 'yy-mm-dd',
					changeMonth: true,
					changeYear: true,
					altField: "#fecha_nacimiento_hidden",
					altFormat: "yy-mm-dd"
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
				$("#combo_cajas").on('change', function() {
					if ($("#combo_cajas").val() == 0) {
						//lista_ventas('ninguno', 0);
					} else {
						//alert('seleecion de caja '+$("#combo_cajas").val());
						lista_abonos($("#combo_cajas").val());
						lista_cargos($("#combo_cajas").val());
						saldo($("#combo_cajas").val());
						$("#kardex").empty();
						$("#kardex").append('<a href="index.php?action=recalculacajakardex&cid=' + $("#combo_cajas").val() + '" class="btn btn-primary">Generar Kardex</a>');

					}
				});

				$("#combo_tipo").on('change', function() {
					if ($("#combo_tipo").val() == "abono") {
						//lista_ventas('ninguno', 0);
						$("#combo_estado").empty();
						$("#combo_estado").append('<option value="0">Debe</option><option value="1" selected >Pagado</option>');
					} else {

						$("#combo_estado").empty();
						$("#combo_estado").append('<option value="0" selected>Debe</option><option value="1">Pagado</option>');
					}

				});

				$('#enviar').click(function() {
					var selected = '';
					var montos = '';
					$('#formid input[type=checkbox]').each(function() {
						if (this.checked) {
							selected += $(this).val() + ', ';
							montos += $('#retiro_' + $(this).val()).val() + ',';
						}
					});

					if (selected != '')
						alert('Has seleccionado: ' + selected + " montos : " + montos);
					else
						alert('Debes seleccionar al menos una opción.');

					return false;
				});

			});
		</script>
</section>