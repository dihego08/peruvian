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
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12 form-row">
			<table class="table">
				<tr>
					<td colspan="4" class="text-center">COSTEO DE PRENDAS</td>
				</tr>
				<tr>
					<td class="bold">Prenda</td>
					<td id="nombre_producto">MADIL CHEF</td>
					<td class="bold">Fecha:</td>
					<td><?php echo date("d-m-Y"); ?></td>
				</tr>
			</table>
			<h4 class="bold">A: MATERIALES</h4>


			<fieldset>
				<legend>A.1. MATERIALES DIRECTOS</legend>
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
					<table class="table table-bordered table-hover table-striped" id="tabla_directos">
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
				<legend>A.2. MATERIALES EXTRAS INCORPORADOS</legend>
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
					<table class="table table-bordered table-hover table-striped" id="tabla_extras">
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
				<legend>A.3. MATERIALES EXTRAS EMPAQUE</legend>
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
					<table class="table table-bordered table-hover table-striped" id="tabla_empaques">
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

			<h4 class="bold">B: MANO OBRA DIRECTA</h4>

			<fieldset>
				<legend></legend>
				<div class="col-md-5">
					<label for="">
						PROCESO
					</label>
					<input type="text" name="" id="proceso" class="form-control">
				</div>
				<div class="col-md-3">
					<label for="">
						TIEMPO PRODUCCIÓN (Minutos)
					</label>
					<input type="text" name="" id="tiempo_produccion" class="form-control">
				</div>
				<div class="col-md-3">
					<label for="">
						COSTO MINUTO S/.
					</label>
					<input type="text" name="" id="costo_minuto" class="form-control">
				</div>
				<div class="col-md-1">
					<!--<label for="">
					COSTO UNITARIO
				</label>-->
					<button class="btn btn-success" id="btn_mano_directa" style="height: 100%;" onclick="guardar_mano_directa();">
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-md-12 mt-2">
					<table class="table table-bordered table-hover table-striped" id="tabla_mano_directa">
						<thead>
							<th>PROCESO</th>
							<th>TIEMPO PRODUCCIÓN (Minutos)</th>
							<th>COSTO MINUTO S/.</th>
							<th>VALOR M.O.D. por Prenda</th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</fieldset>


			<h4 class="bold">C. Servicio Externo:</h4>

			<fieldset>
				<legend></legend>
				<div class="col-md-11">
					<label for="">
						BORDADO
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
					<table class="table table-bordered table-hover table-striped" id="tabla_bordado">
						<thead>
							<th>Bordado</th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</fieldset>

			<h4 class="bold">D. Tarifa Uso del Taller (S/. Minuto)</h4>
			<fieldset>
				<legend></legend>
				<div class="col-md-5">
					<label for="">
						Costo Minuto
					</label>
					<input type="text" name="" id="costo_minuto_taller" class="form-control">
				</div>
				<div class="col-md-6">
					<label for="">
						Tiempo de Produccion
					</label>
					<input type="text" name="" id="tiempo_produccion_taller" class="form-control">
				</div>
				<div class="col-md-1">
					<!--<label for="">
					COSTO UNITARIO
				</label>-->
					<button class="btn btn-success" id="btn_uso_taller" style="height: 100%;" onclick="guardar_uso_taller();">
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-md-12 mt-2">
					<table class="table table-bordered table-hover table-striped" id="tabla_uso_taller">
						<thead>
							<th>Costo Minuto</th>
							<th>Tiempo Producion</th>
							<th>Total</th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</fieldset>


			<div class="col-md-12" style="margin-top: 2rem;">
				<table id="tabla_totales" class="table table-bordered">
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
		function data_producto(id_producto) {
			$.post('core/app/view/costos.php?parAccion=data_producto', {
				id_producto: id_producto
			}, function(data) {
				var obj = JSON.parse(data);

				$("#nombre_producto").text(obj.name);
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
								lista_directos();
								get_totales_2(0);
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
								get_totales_2(0);
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
						<td>${parseFloat(val.costo_total).toFixed(2)}</td>
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
					<td>SUBTOTAL</td>
					<td>${parseFloat(subtotal_directos).toFixed(2)}</td>
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
					$("#tabla_extras").find('tbody').append(`
					<tr>
						<td>${val.insumo}</td>
						<td>${val.unidad}</td>
						<td>${val.consumo_teorico}</td>
						<td>${val.merma} %</td>
						<td>${val.consumo_real}</td>
						<td>${val.costo_unitario}</td>
						<td>${parseFloat(val.costo_total).toFixed(2)}</td>
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
						<td>${parseFloat(val.costo_total).toFixed(2)}</td>
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

		function lista_bordado() {
			$.post('core/app/view/costos.php?parAccion=lista_bordado', {
				id_producto: <?php echo $_GET['id_producto']; ?>
			}, function(data) {
				var obj = JSON.parse(data);
				$("#tabla_bordado").find('tbody').empty();

				$.each(obj, function(index, val) {
					$("#tabla_bordado").find('tbody').append(`
					<tr>
						<td>${parseFloat(val.bordado).toFixed(2)}</td>
						<td>
							<button class="btn btn-warning btn-xs" style="display: block;" onclick="editar_bordado(${val.id});"><i class="fa fa-pencil"></i></button>
							<button class="btn btn-danger btn-xs" style="display: block; margin-top: 0.5rem;" onclick="eliminar_bordado(${val.id});"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				`);
				});
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
					$("#tabla_mano_directa").find('tbody').append(`
					<tr>
						<td>${val.proceso}</td>
						<td>${val.tiempo_produccion}</td>
						<td>${val.costo_minuto}</td>
						<td>${parseFloat(val.valor_prenda).toFixed(2)}</td>
						<td>
							<button class="btn btn-warning btn-xs" style="display: block;" onclick="editar_mano_directa(${val.id});"><i class="fa fa-pencil"></i></button>
							<button class="btn btn-danger btn-xs" style="display: block; margin-top: 0.5rem;" onclick="eliminar_mano_directa(${val.id});"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				`);
				});
				$("#tabla_mano_directa").find('tbody').append(`
				<tr>
					<td></td>
					<td></td>
					<td>SUBTOTAL</td>
					<td>${parseFloat(subtotal_mano_directa).toFixed(2)}</td>
					<td></td>
				</tr>
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
					$("#tabla_uso_taller").find('tbody').append(`
					<tr>
						<td>${val.costo_minuto}</td>
						<td>${val.tiempo_produccion}</td>
						<td>${parseFloat(val.total).toFixed(2)}</td>
						<td>
							<button class="btn btn-warning btn-xs" style="display: block;" onclick="editar_uso_taller(${val.id});"><i class="fa fa-pencil"></i></button>
							<button class="btn btn-danger btn-xs" style="display: block; margin-top: 0.5rem;" onclick="eliminar_uso_taller(${val.id});"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
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
					$("#btn_mano_directa").append(`<i class="fa fa-plus"></i>`);
					$("#btn_mano_directa").attr('onclick', 'guardar_mano_directa();');
				} else {
					alert("ERROR");
				}
			});
		}

		function guardar_bordado() {
			$.post('core/app/view/costos.php?parAccion=guardar_bordado', {
				bordado: $("#bordado").val(),
				id_producto: <?php echo $_GET['id_producto']; ?>,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_bordado();
					get_totales_2(0);
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
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == "OK") {
					lista_bordado();
					get_totales_2(0);
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
					get_totales_2(0);
				} else {
					alert("ERROR");
				}
			});
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
					get_totales_2(0);

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
					//lista_empaques();
					get_totales_2(0);

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
					get_totales_2(0);
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
					get_totales_2(0);
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
					get_totales_2(0);

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
					$("#total_costo_prenda").text(parseFloat(obj.costo_prenda).toFixed(2));
					$("#total_utilidad").text(parseFloat(obj.utilidad).toFixed(2));
					$("#total_valor_venta_2").val(parseFloat(obj.valor_venta).toFixed(2));
					$("#total_igv").text(parseFloat(obj.igv).toFixed(2));
					$("#total_renta").text(parseFloat(obj.renta).toFixed(2));
					$("#total_precio_venta").text(parseFloat(obj.precio_venta).toFixed(2));
				}


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