<style>
	.text-center {
		text-align: center !important;
	}

	.bold {
		font-weight: bold;
	}

	fieldset {
		background-color: #eeeeee;
		padding: 10px;
		margin-bottom: 2rem;
		border: solid 1px #999;
	}

	legend {
		background-color: gray;
		color: white;
		padding: 5px 10px;
		width: auto !important;
	}

	.mt-2 {
		margin-top: 1rem !important;
	}

	td {
		vertical-align: middle !important;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12 form-row">
			<table class="table">
				<tr>
					<td colspan="4" class="text-center">
						<h4><strong>COSTEO DE PRENDAS</strong></h4>
					</td>
				</tr>
				<tr>
					<td class="bold">Cod. Modelo</td>
					<td id="codigo_producto">MADIL CHEF</td>
					<td class="bold">Fecha:</td>
					<td><?php echo date("d-m-Y"); ?></td>
				</tr>
				<tr>
					<td class="bold">Descripción</td>
					<td id="nombre_producto">MADIL CHEF</td>
					<td class="bold"></td>
					<td>
						<a href="core/app/view/pdf-costeo.php?id_producto=<?php echo $_GET['id_producto'] ?>" id="btn-exportar" class="btn btn-primary" target="_blank">Exportar PDF</a>
					</td>
				</tr>
			</table>

			<h4 class="bold">A: DATOS DE INGRESO</h4>
			<div class="row">
				<div class="col-md-7">
					<fieldset>
						<table class="table" style="color: black; font-size: 17px;">
							<tr>
								<td>% de Capacidad</td>
								<td><input type="text" class="form-control" id="di_por_capacidad"></td>
								<td>%</td>
							</tr>
							<tr>
								<td>Nro. de Operarios</td>
								<td><input type="text" class="form-control" id="di_nro_operarios"></td>
								<td></td>
							</tr>
							<tr>
								<td>Timpo estimado es confeccionar 1 prenda</td>
								<td><input type="text" class="form-control" id="di_tie_confeccion"></td>
								<td>Min.</td>
							</tr>
							<tr>
								<td>Horas Laboradas</td>
								<td><input type="text" class="form-control" id="di_hor_laboradas"></td>
								<td>Hr</td>
							</tr>
							<tr>
								<td>Talla a Estimar</td>
								<td><input type="text" class="form-control" id="di_tal_estimar"></td>
								<td></td>
							</tr>
						</table>
						<div class="row">
							<div class="col-md-12" style="text-align: center;">
								<span class="btn btn-success" onclick="calcular_ingreso(1);">Calcular</span>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="col-md-5">
					<fieldset>
						<table class="table" style="color: black; font-size: 17px;">
							<tr>
								<td>Cuota/Producción</td>
								<td><input type="text" class="form-control" id="cuota_produccion" readonly></td>
								<td>Prendas/día</td>
							</tr>
							<tr>
								<td style="text-align: right;">S/</td>
								<td><input type="text" class="form-control" id="mod" readonly></td>
								<td>
									<a href="?view=mod&id_producto=<?php echo $_GET['id_producto']; ?>" style="width: 100%;" class="btn btn-success">MOD</a>
								</td>
							</tr>
							<tr>
								<td style="text-align: right;">S/</td>
								<td><input type="text" class="form-control" id="moi" readonly></td>
								<td>
									<span style="width: 100%; cursor: default;" class="btn btn-success">MOI</span>
								</td>
							</tr>
							<tr>
								<td style="text-align: right;">S/</td>
								<td><input type="text" class="form-control" id="cif" readonly></td>
								<td>
									<span style="width: 100%; cursor: default;" class="btn btn-success">CIF</span>
								</td>
							</tr>
							<tr>
								<td style="text-align: right;">S/</td>
								<td><input type="text" class="form-control" id="costos_fijos" readonly></td>
								<td>
									<span style="width: 100%; cursor: default;" class="btn btn-success">Costos Fijos</span>
								</td>
							</tr>
							<tr>
								<td style="text-align: right;">S/</td>
								<td><input type="text" class="form-control" id="gaf" readonly></td>
								<td>
									<span style="width: 100%; cursor: default;" class="btn btn-success">GAF</span>
								</td>
							</tr>
							<tr>
								<td style="text-align: right;">S/</td>
								<td><input type="text" class="form-control" id="gvm" readonly></td>
								<td>
									<span style="width: 100%; cursor: default;" class="btn btn-success">GVM</span>
								</td>
							</tr>
							<!--<tr>
								<td style="text-align: right;">S/</td>
								<td><input type="text" class="form-control" id="tarifa_corte"></td>
								<td style="white-space: nowrap;">TARIFA CORTE</td>
							</tr>-->
							<tr>
								<td style="white-space: nowrap; font-weight: bold; text-align: right;">SUBTOTAL (S/)</td>
								<td><input type="text" class="form-control" readonly id="subtotal-1"></td>
								<td style="text-align: left;">
									<span id="subtotal-1-porcentaje" style="font-weight: bold;"></span>
								</td>
							</tr>
						</table>
					</fieldset>
				</div>
				<div class="col-md-12">
					<fieldset>
						<table class="table" style="color: black; font-size: 17px;">
							<tr>
								<td style="text-align: right; font-weight: bold;">TOTAL CONFECCIÓN</td>
								<td><input type="text" class="form-control" id="di_total_confeccion" readonly></td>
							</tr>
							<tr>
								<td style="text-align: right; font-weight: bold;">MARGEN</td>
								<td><input type="text" class="form-control" id="di_margen" value="100"></td>
							</tr>
							<tr>
								<td style="text-align: right; font-weight: bold;">CONFECCION + MARGEN</td>
								<td><input type="text" class="form-control" id="di_confeccion_margen" readonly></td>
							</tr>
						</table>
					</fieldset>
				</div>
			</div>
			<h4 class="bold">B: MATERIALES</h4>

			<fieldset>
				<legend>B.1. MATERIALES DIRECTOS</legend>
				<div class="col-md-3">
					<label for="">DESCRIPCIÓN</label>
					<select name="" id="id_insumo_directo" class="form-control id_insumo"></select>
				</div>
				<div class="col-md-2">
					<label for="">
						Unid. Medida
					</label>
					<select name="" id="unidad_directo" class="form-control unidad"></select>
				</div>
				<div class="col-md-2">
					<label for="">
						CONSUMO TEÓRICO
					</label>
					<input type="text" name="" id="consumo_teorico_directo" class="form-control">
				</div>
				<div class="col-md-2">
					<label for="">
						CONSUMO REAL
					</label>
					<input type="text" name="" id="consumo_real_directo" class="form-control">
				</div>
				<div class="col-md-2">
					<label for="">
						COSTO UNITARIO
					</label>
					<input type="text" name="" id="costo_unitario_directo" class="form-control">
				</div>
				<div class="col-md-1">
					<!--<label for="">
					COSTO UNITARIO
				</label>-->
					<button class="btn btn-success" id="btn_directo" style="height: 100%;" onclick="guardar_directo();">
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-md-12 mt-2">
					<table class="table table-bordered table-hover table-striped" id="tabla_directos" style="color: black; font-size: 17px;">
						<thead>
							<th>DESCRIPCIÓN</th>
							<th>Unid. Medida</th>
							<th>CONSUMO TEÓRICO</th>
							<th>% MERMA</th>
							<th>CONSUMO REAL</th>
							<th>COSTO UNITARIO</th>
							<th>COSTO TOTAL</th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</fieldset>



			<fieldset>
				<legend>B.2. MATERIALES EXTRAS INCORPORADOS</legend>
				<div class="col-md-3">
					<label for="">DESCRIPCIÓN</label>
					<select name="" id="id_insumo_extra" class="form-control id_insumo"></select>
				</div>
				<div class="col-md-2">
					<label for="">
						Unid. Medida
					</label>
					<select name="" id="unidad_extra" class="form-control unidad"></select>
				</div>
				<div class="col-md-2">
					<label for="">
						CONSUMO TEÓRICO
					</label>
					<input type="text" name="" id="consumo_teorico_extra" class="form-control">
				</div>
				<div class="col-md-2">
					<label for="">
						CONSUMO REAL
					</label>
					<input type="text" name="" id="consumo_real_extra" class="form-control">
				</div>
				<div class="col-md-2">
					<label for="">
						COSTO UNITARIO
					</label>
					<input type="text" name="" id="costo_unitario_extra" class="form-control">
				</div>
				<div class="col-md-1">
					<!--<label for="">
					COSTO UNITARIO
				</label>-->
					<button class="btn btn-success" id="btn_extra" style="height: 100%;" onclick="guardar_extra();">
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-md-12 mt-2">
					<table class="table table-bordered table-hover table-striped" id="tabla_extras" style="color: black; font-size: 17px;">
						<thead>
							<th>DESCRIPCIÓN</th>
							<th>Unid. Medida</th>
							<th>CONSUMO TEÓRICO</th>
							<th>% MERMA</th>
							<th>CONSUMO REAL</th>
							<th>COSTO UNITARIO</th>
							<th>COSTO TOTAL</th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</fieldset>


			<fieldset>
				<legend>B.3. MATERIALES EXTRAS EMPAQUE</legend>
				<div class="col-md-3">
					<label for="">DESCRIPCIÓN</label>
					<select name="" id="id_insumo_empaque" class="form-control id_insumo"></select>
				</div>
				<div class="col-md-2">
					<label for="">
						Unid. Medida
					</label>
					<select name="" id="unidad_empaque" class="form-control unidad"></select>
				</div>
				<div class="col-md-2">
					<label for="">
						CONSUMO TEÓRICO
					</label>
					<input type="text" name="" id="consumo_teorico_empaque" class="form-control">
				</div>
				<div class="col-md-2">
					<label for="">
						CONSUMO REAL
					</label>
					<input type="text" name="" id="consumo_real_empaque" class="form-control">
				</div>
				<div class="col-md-2">
					<label for="">
						COSTO UNITARIO
					</label>
					<input type="text" name="" id="costo_unitario_empaque" class="form-control">
				</div>
				<div class="col-md-1">
					<!--<label for="">
					COSTO UNITARIO
				</label>-->
					<button class="btn btn-success" id="btn_empaque" style="height: 100%;" onclick="guardar_empaque();">
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-md-12 mt-2">
					<table class="table table-bordered table-hover table-striped" id="tabla_empaques" style="color: black; font-size: 17px;">
						<thead>
							<th>DESCRIPCIÓN</th>
							<th>Unid. Medida</th>
							<th>CONSUMO TEÓRICO</th>
							<th>% MERMA</th>
							<th>CONSUMO REAL</th>
							<th>COSTO UNITARIO</th>
							<th>COSTO TOTAL</th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</fieldset>

			<fieldset>
				<legend>Costo Materiales</legend>
				<div class="col-md-8">
					<h4 class="bold">Total</h4>
				</div>
				<div class="col-md-4">
					<h4 class="bold" id="total_materiales"></h4>
				</div>
			</fieldset>

			<h4 class="bold">C. Otros Procesos:</h4>

			<fieldset>
				<legend></legend>
				<div class="col-md-6">
					<label for="">
						CONCEPTO
					</label>
					<input type="text" name="" id="concepto_bordado" class="form-control">
				</div>
				<div class="col-md-5">
					<label for="">
						Costo
					</label>
					<input type="text" name="" id="bordado" class="form-control">
				</div>
				<div class="col-md-1">
					<!--<label for="">
					COSTO UNITARIO
				</label>-->
					<button class="btn btn-success" id="btn_bordado" style="height: 100%;" onclick="guardar_bordado();">
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-md-12 mt-2">
					<table class="table table-bordered table-hover table-striped" id="tabla_bordado" style="color: black; font-size: 17px;">
						<thead>
							<th>Concepto</th>
							<th>Monto</th>
							<th></th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</fieldset>


			<div class="col-md-12" style="margin-top: 2rem;">
				<table id="tabla_totales" class="table table-bordered" style="color: black; font-size: 20px;">
					<tr>
						<td class="bold">COSTO TOTAL PRENDA</td>
						<td id="total_costo_prenda"></td>
					</tr>
					<tr>
						<td class="bold">UTILIDAD</td>
						<td id="total_utilidad"></td>
					</tr>
					<tr>
						<td class="bold">VALOR DE VENTA</td>
						<td id="total_valor_venta_">
							<div class="form-row">
								<div class="col-md-11">
									<input type="text" class="form-control" id="total_valor_venta_2">
								</div>
								<div class="col-md-1">
									<button class="btn btn-success" onclick="calcular();">
										<i class="fa fa-check"></i>
									</button>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="bold">IMPUESTO GENERAL VENTAS</td>
						<td id="total_igv"></td>
					</tr>
					<tr>
						<td class="bold">Impuesto a la renta</td>
						<td id="total_renta"></td>
					</tr>
					<tr>
						<td class="bold">PRECIO VENTA</td>
						<td id="total_precio_venta"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script>
		function calcular_ingreso(insertar) {
			if (
				$("#di_por_capacidad").val() == "" ||
				$("#di_nro_operarios").val() == "" ||
				$("#di_tie_confeccion").val() == "" ||
				$("#di_hor_laboradas").val() == "" ||
				$("#di_tal_estimar").val() == ""
			) {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Se estan dejando campos en blanco.</strong>' +
						'</div>'
				});
			} else {
				var cuota_produccion = parseInt((($("#di_hor_laboradas").val() * 60) * ($("#di_por_capacidad").val() / 100) * $("#di_nro_operarios").val()) / $("#di_tie_confeccion").val());
				$("#cuota_produccion").val(cuota_produccion);

				//di_nro_operarios
				console.log("ESTAMOS EN ESTO " + data_inicial.sueldo_dia + " * " + $("#di_nro_operarios").val() + " / " + $("#cuota_produccion").val());

				$("#mod").val((data_inicial.sueldo_dia * $("#di_nro_operarios").val() / $("#cuota_produccion").val()).toFixed(2));
				$("#moi").val((data_inicial.moi / cuota_produccion * ($("#di_por_capacidad").val() / 100)).toFixed(2));
				$("#cif").val((data_inicial.cif / cuota_produccion).toFixed(2));
				$("#costos_fijos").val((data_inicial.costos_fijos / cuota_produccion).toFixed(2));
				$("#gaf").val((data_inicial.gaf / cuota_produccion).toFixed(2));
				$("#gvm").val((data_inicial.gvm / cuota_produccion).toFixed(2));

				if (insertar == 1) {
					console.log(parseFloat($("#di_total_confeccion").val()) + " * " + (parseFloat(1) + " + " + parseFloat($("#di_margen").val() + " / 100")));
					$("#di_confeccion_margen").val(parseFloat($("#di_total_confeccion").val()) * (parseFloat(1) + parseFloat($("#di_margen").val() / 100)));
					$("#di_total_confeccion").val(parseFloat(
						parseFloat($("#mod").val()) +
						parseFloat($("#moi").val()) +
						parseFloat($("#cif").val()) +

						parseFloat($("#costos_fijos").val()) +
						parseFloat($("#gaf").val()) +
						parseFloat($("#gvm").val())
					).toFixed(2));

					$.post("core/app/view/costos.php?parAccion=set_ingreso", {
						di_por_capacidad: $("#di_por_capacidad").val(),
						di_nro_operarios: $("#di_nro_operarios").val(),
						di_tie_confeccion: $("#di_tie_confeccion").val(),
						di_hor_laboradas: $("#di_hor_laboradas").val(),
						di_tal_estimar: $("#di_tal_estimar").val(),
						tarifa_corte: 0,
						id_producto: <?php echo $_GET['id_producto']; ?>,
						di_total_confeccion: $("#di_total_confeccion").val(),
						di_confeccion_margen: $("#di_confeccion_margen").val(),
						di_margen: $("#di_margen").val()
					}, function(response) {
						var obj = JSON.parse(response);
						if (obj.Result == "OK") {
							bootbox.alert({
								message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Data registrada correctamente.</strong>' +
									'</div>'
							});
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Algo ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				}
				$("#subtotal-1").val((
					parseFloat($("#mod").val()) +
					parseFloat($("#moi").val()) +
					parseFloat($("#cif").val()) +
					parseFloat($("#costos_fijos").val()) +
					parseFloat($("#gaf").val()) +
					parseFloat($("#gvm").val())).toFixed(2));
				calcular_totales();
			}
		}
		var data_inicial = "";

		function get_data_ingreso() {
			$.post("core/app/view/costos.php?parAccion=get_data_ingreso", {
				id_producto: <?php echo $_GET['id_producto']; ?>
			}, function(response) {
				var obj = JSON.parse(response);
				data_inicial = obj;

				console.log(Object.keys(obj.data_ingreso).length);
				if (Object.keys(obj.data_ingreso).length > 0) {
					$("#di_por_capacidad").val(obj.data_ingreso.di_por_capacidad);
					$("#di_nro_operarios").val(obj.data_ingreso.di_nro_operarios);
					$("#di_tie_confeccion").val(obj.data_ingreso.di_tie_confeccion);
					$("#di_hor_laboradas").val(obj.data_ingreso.di_hor_laboradas);
					$("#di_tal_estimar").val(obj.data_ingreso.di_tal_estimar);
					$("#di_total_confeccion").val(obj.data_ingreso.di_total_confeccion);
					$("#di_confeccion_margen").val(obj.data_ingreso.di_confeccion_margen);
					$("#di_margen").val(obj.data_ingreso.di_margen);
					calcular_ingreso(0);
				}
			});
		}

		function data_producto(id_producto) {
			$.post('core/app/view/costos.php?parAccion=data_producto', {
				id_producto: id_producto
			}, function(data) {
				var obj = JSON.parse(data);

				$("#nombre_producto").text(obj.name);
				$("#codigo_producto").text(obj.code);
			});
		}

		function editar_directo(id) {
			$.post('core/app/view/costos.php?parAccion=editar_directo', {
				id: id
			}, function(data) {
				var obj = JSON.parse(data);

				//$("#id_insumo_directo").val(obj.id_insumo);
				$("#id_insumo_directo").val(obj.id_insumo).trigger('change');
				$("#unidad_directo").val(obj.unidad);
				$("#consumo_teorico_directo").val(obj.consumo_teorico);
				$("#consumo_real_directo").val(obj.consumo_real);
				$("#costo_unitario_directo").val(obj.costo_unitario);

				$("#btn_directo").empty();
				$("#btn_directo").append(`<i class="fa fa-check"></i>`);
				$("#btn_directo").attr('onclick', 'actualizar_directo(' + id + ');');
				$("#consumo_teorico_directo").focus();
			});
		}

		function editar_extras(id) {
			$.post('core/app/view/costos.php?parAccion=editar_directo', {
				id: id
			}, function(data) {
				var obj = JSON.parse(data);

				//$("#id_insumo_directo").val(obj.id_insumo);
				$("#id_insumo_extra").val(obj.id_insumo).trigger('change');
				$("#unidad_extra").val(obj.unidad);
				$("#consumo_teorico_extra").val(obj.consumo_teorico);
				$("#consumo_real_extra").val(obj.consumo_real);
				$("#costo_unitario_extra").val(obj.costo_unitario);

				$("#btn_extra").empty();
				$("#btn_extra").append(`<i class="fa fa-check"></i>`);
				$("#btn_extra").attr('onclick', 'actualizar_extra(' + id + ');');
			});
		}

		function editar_empaque(id) {
			$.post('core/app/view/costos.php?parAccion=editar_directo', {
				id: id
			}, function(data) {
				var obj = JSON.parse(data);

				//$("#id_insumo_directo").val(obj.id_insumo);
				$("#id_insumo_empaque").val(obj.id_insumo).trigger('change');
				$("#unidad_empaque").val(obj.unidad);
				$("#consumo_teorico_empaque").val(obj.consumo_teorico);
				$("#consumo_real_empaque").val(obj.consumo_real);
				$("#costo_unitario_empaque").val(obj.costo_unitario);

				$("#btn_empaque").empty();
				$("#btn_empaque").append(`<i class="fa fa-check"></i>`);
				$("#btn_empaque").attr('onclick', 'actualizar_empaque(' + id + ');');
			});
		}

		function editar_mano_directa(id) {
			$.post('core/app/view/costos.php?parAccion=editar_mano_directa', {
				id: id
			}, function(data) {
				var obj = JSON.parse(data);

				$("#proceso").val(obj.proceso);
				$("#tiempo_produccion").val(obj.tiempo_produccion);
				$("#costo_minuto").val(obj.costo_minuto);

				$("#btn_mano_directa").empty();
				$("#btn_mano_directa").append(`<i class="fa fa-check"></i>`);
				$("#btn_mano_directa").attr('onclick', 'actualizar_mano_directa(' + id + ');');
			});
		}

		function editar_bordado(id) {
			$.post('core/app/view/costos.php?parAccion=editar_bordado', {
				id: id
			}, function(data) {
				var obj = JSON.parse(data);

				$("#bordado").val(obj.bordado);
				$("#concepto_bordado").val(obj.concepto),
					$("#bordado").focus();

				$("#btn_bordado").empty();
				$("#btn_bordado").append(`<i class="fa fa-check"></i>`);
				$("#btn_bordado").attr('onclick', 'actualizar_bordado(' + id + ');');
			});
		}

		function editar_uso_taller(id) {
			$.post('core/app/view/costos.php?parAccion=editar_uso_taller', {
				id: id
			}, function(data) {
				var obj = JSON.parse(data);

				//$("#bordado").val(obj.bordado);
				$("#costo_minuto_taller").val(obj.costo_minuto);
				$("#tiempo_produccion_taller").val(obj.tiempo_produccion);
				$("#costo_minuto_taller").focus();

				$("#btn_uso_taller").empty();
				$("#btn_uso_taller").append(`<i class="fa fa-check"></i>`);
				$("#btn_uso_taller").attr('onclick', 'actualizar_uso_taller(' + id + ');');
			});
		}

		function eliminar_directo(id) {
			bootbox.confirm({
				message: "¿Seguro de Eliminar este registro?",
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
						$.post('core/app/view/costos.php?parAccion=eliminar_directo', {
							id: id
						}, function(data) {
							var obj = JSON.parse(data);
							if (obj.Result == 'OK') {
								lista_extras();
								calcular_ingreso(1);

								get_totales_2(0);
								calcular_totales();
								bootbox.alert({
									message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Eliminado Correctamente.</strong>' +
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
					} else {}
				}
			});
		}

		function eliminar_bordado(id) {
			bootbox.confirm({
				message: "¿Seguro de Eliminar este registro?",
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
						$.post('core/app/view/costos.php?parAccion=eliminar_bordado', {
							id: id
						}, function(data) {
							var obj = JSON.parse(data);
							if (obj.Result == 'OK') {
								lista_bordado();
								calcular_ingreso(1);

								get_totales_2(0);
								calcular_totales();
								bootbox.alert({
									message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Eliminado Correctamente.</strong>' +
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
					} else {}
				}
			});
		}

		function eliminar_uso_taller(id) {
			bootbox.confirm({
				message: "¿Seguro de Eliminar este registro?",
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
						$.post('core/app/view/costos.php?parAccion=eliminar_uso_taller', {
							id: id
						}, function(data) {
							var obj = JSON.parse(data);
							if (obj.Result == 'OK') {
								lista_uso_taller();
								calcular_ingreso(1);

								get_totales_2(0);
								calcular_totales();
								bootbox.alert({
									message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
										'<strong>Eliminado Correctamente.</strong>' +
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
					} else {}
				}
			});
		}

		function lista_directos() {
			$.post('core/app/view/costos.php?parAccion=lista_directos', {
				id_producto: <?php echo $_GET['id_producto']; ?>
			}, function(data) {
				var obj = JSON.parse(data);
				$("#tabla_directos").find('tbody').empty();
				var subtotal_directos = 0;
				$.each(obj, function(index, val) {
					subtotal_directos += parseFloat(val.costo_total);
					$("#tabla_directos").find('tbody').append(`
					<tr>
						<td>${val.insumo}</td>
						<td>${val.unidad}</td>
						<td>${val.consumo_teorico}</td>
						<td>${val.merma} %</td>
						<td>${val.consumo_real}</td>
						<td>${val.costo_unitario}</td>
						<td>${parseFloat(val.costo_total).toFixed(2)} <span style="font-weight: bold;">(${(val.costo_total*100/$("#total_precio_venta").text()).toFixed(2)}%</span>)</td>
						<td>
							<button class="btn btn-warning btn-xs" style="display: block;" onclick="editar_directo(${val.id});"><i class="fa fa-pencil"></i></button>
							<button class="btn btn-danger btn-xs" style="display: block; margin-top: 0.5rem;" onclick="eliminar_directo(${val.id});"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				`);
				});
				$("#tabla_directos").find('tbody').append(`
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align: right; font-weight: bold;">SUBTOTAL</td>
					<td><span id="spn_subtotal_directos">${parseFloat(subtotal_directos).toFixed(2)}</span></td>
					<td></td>
				</tr>
			`);
			});
		}

		function lista_extras() {
			$.post('core/app/view/costos.php?parAccion=lista_extras', {
				id_producto: <?php echo $_GET['id_producto']; ?>
			}, function(data) {
				var obj = JSON.parse(data);
				$("#tabla_extras").find('tbody').empty();
				var subtotal_extras = 0;
				$.each(obj, function(index, val) {
					subtotal_extras += parseFloat(val.costo_total);
					console.log(val.costo_total+"*100/"+$("#total_precio_venta").text());
					$("#tabla_extras").find('tbody').append(`
					<tr>
						<td>${val.insumo}</td>
						<td>${val.unidad}</td>
						<td>${val.consumo_teorico}</td>
						<td>${val.merma} %</td>
						<td>${val.consumo_real}</td>
						<td>${val.costo_unitario}</td>
						<td>${parseFloat(val.costo_total).toFixed(2)} <span style="font-weight: bold;">(${(val.costo_total*100/$("#total_precio_venta").text()).toFixed(2)}%</span>)</td>
						<td>
							<button class="btn btn-warning btn-xs" style="display: block;" onclick="editar_extras(${val.id});"><i class="fa fa-pencil"></i></button>
							<button class="btn btn-danger btn-xs" style="display: block; margin-top: 0.5rem;" onclick="eliminar_directo(${val.id});"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				`);
				});
				$("#tabla_extras").find('tbody').append(`
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>SUBTOTAL</td>
					<td>${parseFloat(subtotal_extras).toFixed(2)}</td>
					<td></td>
				</tr>
			`);
			});
		}

		function lista_empaques() {
			$.post('core/app/view/costos.php?parAccion=lista_empaques', {
				id_producto: <?php echo $_GET['id_producto']; ?>
			}, function(data) {
				var obj = JSON.parse(data);
				$("#tabla_empaques").find('tbody').empty();
				var subtotal_empaques = 0;
				$.each(obj, function(index, val) {
					subtotal_empaques += parseFloat(val.costo_total);
					$("#tabla_empaques").find('tbody').append(`
					<tr>
						<td>${val.insumo}</td>
						<td>${val.unidad}</td>
						<td>${val.consumo_teorico}</td>
						<td>${val.merma} %</td>
						<td>${val.consumo_real}</td>
						<td>${val.costo_unitario}</td>
						<td>${parseFloat(val.costo_total).toFixed(2)} <span style="font-weight: bold;">(${(val.costo_total*100/$("#total_precio_venta").text()).toFixed(2)}%</span>)</td>
						<td>
							<button class="btn btn-warning btn-xs" style="display: block;" onclick="editar_empaque(${val.id});"><i class="fa fa-pencil"></i></button>
							<button class="btn btn-danger btn-xs" style="display: block; margin-top: 0.5rem;" onclick="eliminar_empaque(${val.id});"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				`);
				});
				$("#tabla_empaques").find('tbody').append(`
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>SUBTOTAL</td>
					<td>${parseFloat(subtotal_empaques).toFixed(2)}</td>
					<td></td>
				</tr>
			`);
			});
		}
		var total_bordado = parseFloat(0);

		function lista_bordado() {
			$.post('core/app/view/costos.php?parAccion=lista_bordado', {
				id_producto: <?php echo $_GET['id_producto']; ?>
			}, function(data) {
				var obj = JSON.parse(data);
				$("#tabla_bordado").find('tbody').empty();
				total_bordado = parseFloat(0);
				$.each(obj, function(index, val) {
					total_bordado = parseFloat(parseFloat(total_bordado) + parseFloat(val.bordado));
					$("#tabla_bordado").find('tbody').append(`
						<tr>
							<td>${val.concepto}</td>
							<td>${parseFloat(val.bordado).toFixed(2)} <span style="font-weight: bold;">(${(val.bordado*100/$("#total_precio_venta").text()).toFixed(2)}%</span>)</td>
							<td>
								<button class="btn btn-warning btn-xs" style="display: block;" onclick="editar_bordado(${val.id});"><i class="fa fa-pencil"></i></button>
								<button class="btn btn-danger btn-xs" style="display: block; margin-top: 0.5rem;" onclick="eliminar_bordado(${val.id});"><i class="fa fa-trash"></i></button>
							</td>
						</tr>
					`);
				});
				$("#tabla_bordado").find('tbody').append(`
					<tr>
						<td style="text-align: right;">SUBTOTAL</td>
						<td>${total_bordado.toFixed(2)}</td>
						<td>
						</td>
					</tr>
				`);
			});
		}

		function lista_mano_directa() {
			$.post('core/app/view/costos.php?parAccion=lista_mano_directa', {
				id_producto: <?php echo $_GET['id_producto']; ?>
			}, function(data) {
				var obj = JSON.parse(data);
				$("#tabla_mano_directa").find('tbody').empty();
				var subtotal_mano_directa = 0;
				$.each(obj, function(index, val) {
					subtotal_mano_directa += parseFloat(val.valor_prenda);
					$("#tabla_mano_directa").find('tbody').append(` <
					tr >
					<
					td > $ {
						val.proceso
					} < /td> <
					td > $ {
						val.tiempo_produccion
					} < /td> <
					td > $ {
						val.costo_minuto
					} < /td> <
					td > $ {
						parseFloat(val.valor_prenda).toFixed(2)
					} < /td> <
					td >
					<
					button class = "btn btn-warning btn-xs"
					style = "display: block;"
					onclick = "editar_mano_directa(${val.id});" > < i class = "fa fa-pencil" > < /i></button >
					<
					button class = "btn btn-danger btn-xs"
					style = "display: block; margin-top: 0.5rem;"
					onclick = "eliminar_mano_directa(${val.id});" > < i class = "fa fa-trash" > < /i></button >
					<
					/td> <
					/tr>
					`);
				});
				$("#tabla_mano_directa").find('tbody').append(` <
					tr >
					<
					td > < /td> <
					td > < /td> <
					td > SUBTOTAL < /td> <
					td > $ {
						parseFloat(subtotal_mano_directa).toFixed(2)
					} < /td> <
					td > < /td> <
					/tr>
					`);
			});
		}

		function lista_uso_taller() {
			$.post('core/app/view/costos.php?parAccion=lista_uso_taller', {
				id_producto: <?php echo $_GET['id_producto']; ?>
			}, function(data) {
				var obj = JSON.parse(data);
				$("#tabla_uso_taller").find('tbody').empty();

				$.each(obj, function(index, val) {
					$("#tabla_uso_taller").find('tbody').append(` <
					tr >
					<
					td > $ {
						val.costo_minuto
					} < /td> <
					td > $ {
						val.tiempo_produccion
					} < /td> <
					td > $ {
						parseFloat(val.total).toFixed(2)
					} < /td> <
					td >
					<
					button class = "btn btn-warning btn-xs"
					style = "display: block;"
					onclick = "editar_uso_taller(${val.id});" > < i class = "fa fa-pencil" > < /i></button >
					<
					button class = "btn btn-danger btn-xs"
					style = "display: block; margin-top: 0.5rem;"
					onclick = "eliminar_uso_taller(${val.id});" > < i class = "fa fa-trash" > < /i></button >
					<
					/td> <
					/tr>
					`);
				});
			});
		}

		function guardar_uso_taller() {
			$.post('core/app/view/costos.php?parAccion=guardar_uso_taller', {
				costo_minuto_taller: $("#costo_minuto_taller").val(),
				tiempo_produccion_taller: $("#tiempo_produccion_taller").val(),
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_uso_taller();
					get_totales_2(0);
				} else {
					alert("ERROR");
				}
			});
		}

		function actualizar_uso_taller(id) {
			$.post('core/app/view/costos.php?parAccion=actualizar_uso_taller', {
				costo_minuto_taller: $("#costo_minuto_taller").val(),
				tiempo_produccion_taller: $("#tiempo_produccion_taller").val(),
				id: id,
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_uso_taller();
					get_totales_2(0);
				} else {
					alert("ERROR");
				}
			});
		}

		function guardar_mano_directa() {
			$.post('core/app/view/costos.php?parAccion=guardar_mano_directa', {
				proceso: $("#proceso").val(),
				tiempo_produccion: $("#tiempo_produccion").val(),
				costo_minuto: $("#costo_minuto").val(),
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_mano_directa();
					get_totales_2(0);
				} else {
					alert("ERROR");
				}
			});
		}

		function actualizar_mano_directa(id) {
			$.post('core/app/view/costos.php?parAccion=actualizar_mano_directa', {
				proceso: $("#proceso").val(),
				tiempo_produccion: $("#tiempo_produccion").val(),
				costo_minuto: $("#costo_minuto").val(),
				id: id,
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_mano_directa();
					get_totales_2(0);

					$("#btn_mano_directa").empty();
					$("#btn_mano_directa").append(` < i class = "fa fa-plus" > < /i>`);
					$("#btn_mano_directa").attr('onclick', 'guardar_mano_directa();');
				} else {
					alert("ERROR");
				}
			});
		}

		function guardar_bordado() {
			$.post('core/app/view/costos.php?parAccion=guardar_bordado', {
				bordado: $("#bordado").val(),
				concepto: $("#concepto_bordado").val(),
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_bordado();
					calcular_ingreso(1);

					get_totales_2(0);
					calcular_totales();
				} else {
					alert("ERROR");
				}
			});
		}

		function actualizar_bordado(id) {
			$.post('core/app/view/costos.php?parAccion=actualizar_bordado', {
				bordado: $("#bordado").val(),
				id: id,
				id_producto: <?php echo $_GET['id_producto']; ?>,
				concepto: $("#concepto_bordado").val(),
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_bordado();
					calcular_ingreso(1);

					get_totales_2(0);
					calcular_totales();

					$("#btn_bordado").empty();
					$("#btn_bordado").append(`<i class="fa fa-plus"></i>`);
					$("#btn_bordado").attr('onclick', 'guardar_bordado();');

					$("#concepto_bordado").val('');
					$("#bordado").val('');
				} else {
					alert("ERROR");
				}
			});
		}

		function guardar_directo() {
			$.post('core/app/view/costos.php?parAccion=guardar_directo', {
				id_insumo_directo: $("#id_insumo_directo").val(),
				unidad_directo: $("#unidad_directo").val(),
				consumo_teorico_directo: $("#consumo_teorico_directo").val(),
				consumo_real_directo: $("#consumo_real_directo").val(),
				costo_unitario_directo: $("#costo_unitario_directo").val(),
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_directos();
					calcular_ingreso(1);
					get_totales_2(0);
					calcular_totales();
				} else {
					alert("ERROR");
				}
			});
		}

		function calcular_totales() {
			var _di_confeccion_margen = $("#di_confeccion_margen").val() || 0;
			var _total_materiales = $("#total_materiales").text() || 0;
			var $total = parseFloat(_di_confeccion_margen) + parseFloat(_total_materiales) + parseFloat(total_bordado || 0);
			console.log("TOTAL1 " + $("#di_total_confeccion").val());
			console.log("TOTAL2 " + $("#total_materiales").text());
			$("#total_costo_prenda").text($total.toFixed(2));
		}

		function actualizar_directo(id) {
			$.post('core/app/view/costos.php?parAccion=actualizar_directo', {
				id_insumo_directo: $("#id_insumo_directo").val(),
				unidad_directo: $("#unidad_directo").val(),
				consumo_teorico_directo: $("#consumo_teorico_directo").val(),
				consumo_real_directo: $("#consumo_real_directo").val(),
				costo_unitario_directo: $("#costo_unitario_directo").val(),
				id: id,
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_directos();
					calcular_ingreso(1);

					get_totales_2(0);
					calcular_totales();

					$("#btn_directo").empty();
					$("#btn_directo").append(`<i class="fa fa-plus"></i>`);
					$("#btn_directo").attr('onclick', 'guardar_directo();');
				} else {
					alert("ERROR");
				}
			});
		}

		function actualizar_extra(id) {
			$.post('core/app/view/costos.php?parAccion=actualizar_directo', {
				id_insumo_directo: $("#id_insumo_extra").val(),
				unidad_directo: $("#unidad_extra").val(),
				consumo_teorico_directo: $("#consumo_teorico_extra").val(),
				consumo_real_directo: $("#consumo_real_extra").val(),
				costo_unitario_directo: $("#costo_unitario_extra").val(),
				id: id,
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					//lista_directos();
					lista_extras();
					calcular_ingreso(1);
					//lista_empaques();

					get_totales_2(0);
					calcular_totales();

					$("#btn_extra").empty();
					$("#btn_extra").append(`<i class="fa fa-plus"></i>`);
					$("#btn_extra").attr('onclick', 'guardar_extra();');
				} else {
					alert("ERROR");
				}
			});
		}

		function guardar_extra() {
			$.post('core/app/view/costos.php?parAccion=guardar_extra', {
				id_insumo_extra: $("#id_insumo_extra").val(),
				unidad_extra: $("#unidad_extra").val(),
				consumo_teorico_extra: $("#consumo_teorico_extra").val(),
				consumo_real_extra: $("#consumo_real_extra").val(),
				costo_unitario_extra: $("#costo_unitario_extra").val(),
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_extras();
					calcular_ingreso(1);

					get_totales_2(0);
					calcular_totales();
				} else {
					alert("ERROR");
				}
			});
		}

		function guardar_empaque() {
			$.post('core/app/view/costos.php?parAccion=guardar_empaque', {
				id_insumo_empaque: $("#id_insumo_empaque").val(),
				unidad_empaque: $("#unidad_empaque").val(),
				consumo_teorico_empaque: $("#consumo_teorico_empaque").val(),
				consumo_real_empaque: $("#consumo_real_empaque").val(),
				costo_unitario_empaque: $("#costo_unitario_empaque").val(),
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_empaques();
					calcular_ingreso(1);

					get_totales_2(0);
					calcular_totales();
				} else {
					alert("ERROR");
				}
			});
		}

		function actualizar_empaque(id) {
			$.post('core/app/view/costos.php?parAccion=actualizar_directo', {
				id_insumo_directo: $("#id_insumo_empaque").val(),
				unidad_directo: $("#unidad_empaque").val(),
				consumo_teorico_directo: $("#consumo_teorico_empaque").val(),
				consumo_real_directo: $("#consumo_real_empaque").val(),
				costo_unitario_directo: $("#costo_unitario_empaque").val(),
				id: id,
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_empaques();
					calcular_ingreso(1);

					get_totales_2(0);
					calcular_totales();

					$("#btn_empaque").empty();
					$("#btn_empaque").append(`<i class="fa fa-plus"></i>`);
					$("#btn_empaque").attr('onclick', 'guardar_empaque();');
				} else {
					alert("ERROR");
				}
			});
		}

		function llenar_insumos() {
			$.post('core/app/view/insumos.php?parAccion=lista_insumos', function(data) {
				var obj = JSON.parse(data);

				$(".id_insumo").append(`<option value="-1">--SELECCIONA--</option>`);
				$.each(obj.Records, function(index, val) {
					$(".id_insumo").append(`<option value="${val.id}">${val.insumo}</option>`);
				});
			});
		}

		function llenar_unidades() {
			$.post('core/app/view/insumos.php?parAccion=combo_unidades&id=0', function(data) {
				var obj = JSON.parse(data);

				$.each(obj.Records, function(index, val) {
					$(".unidad").append(`<option value="${val.codigo}">${val.unidad}</option>`);
				});
			});
		}

		function get_totales() {
			$.post('core/app/view/costos.php?parAccion=get_totales', {
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);

				$("#total_materiales").text(obj.total_materiales);
			});
		}

		function get_totales_2(status) {
			$.post('core/app/view/costos.php?parAccion=get_totales_2', {
				id_producto: <?php echo $_GET['id_producto']; ?>,
				status: status
			}, function(data) {
				var obj = JSON.parse(data);

				if (obj == false || obj == "false") {
					$("#total_costo_prenda").text(parseFloat(0).toFixed(2));
					$("#total_utilidad").text(parseFloat(0).toFixed(2));
					$("#total_valor_venta_2").val(parseFloat(0).toFixed(2));
					$("#total_igv").text(parseFloat(0).toFixed(2));
					$("#total_renta").text(parseFloat(0).toFixed(2));
					$("#total_precio_venta").text(parseFloat(0).toFixed(2));
				} else {
					console.log("ALOJAKI");
					console.log(obj.costo_prenda);
					$("#total_costo_prenda").text(parseFloat(obj.costo_prenda).toFixed(2));
					var utilidad = obj.utilidad * 100 / obj.precio_venta;
					$("#total_utilidad").empty();
					$("#total_utilidad").append(parseFloat(obj.utilidad).toFixed(2) + "<span style='font-weight: bold;'> (" + utilidad.toFixed(2) + " %)</span>");
					$("#total_valor_venta_2").val(parseFloat(obj.valor_venta).toFixed(2));
					$("#total_igv").text(parseFloat(obj.igv).toFixed(2));
					$("#total_renta").text(parseFloat(obj.renta).toFixed(2));
					$("#total_precio_venta").text(parseFloat(obj.precio_venta).toFixed(2));
				}

				$("#subtotal-1-porcentaje").text(($("#subtotal-1").val() * 100 / $("#total_precio_venta").text()).toFixed(2) + " %");
			});
		}

		function calcular() {
			$("#total_valor_venta_2")
			$.post('core/app/view/costos.php?parAccion=set_costos', {
				costo_prenda: $("#total_costo_prenda").text(),
				valor_venta: $("#total_valor_venta_2").val(),
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);

				if (obj.Result == "OK") {
					get_totales_2(1);
				} else {
					alert("ERROR");
				}
			});
		}
		$(document).ready(function() {
			get_data_ingreso();
			$("#id_insumo_directo").on('change', function() {
				$.post('core/app/view/costos.php?parAccion=get_precio_insumo', {
					id: $(this).val()
				}, function(data) {
					var obj = JSON.parse(data);

					$("#costo_unitario_directo").val(obj.precio);
				});
			});

			$("#id_insumo_extra").on('change', function() {
				$.post('core/app/view/costos.php?parAccion=get_precio_insumo', {
					id: $(this).val()
				}, function(data) {
					var obj = JSON.parse(data);

					$("#costo_unitario_extra").val(obj.precio);
				});
			});

			$("#id_insumo_empaque").on('change', function() {
				$.post('core/app/view/costos.php?parAccion=get_precio_insumo', {
					id: $(this).val()
				}, function(data) {
					var obj = JSON.parse(data);

					$("#costo_unitario_empaque").val(obj.precio);
				});
			});

			get_totales_2(0);
			data_producto(<?php echo $_GET['id_producto']; ?>);
			llenar_insumos();
			llenar_unidades();

			lista_directos();
			lista_extras();
			lista_empaques();
			lista_mano_directa();
			lista_bordado();
			lista_uso_taller();
			get_totales();
			get_totales_2(0);

			$(".id_insumo").select2();
		});
	</script>

</section>