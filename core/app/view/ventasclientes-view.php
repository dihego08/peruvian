<style type="text/css">
	.ct-label {
		font-size: 15px;
		color: black;
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
			<!-- Single button -->
			<h3><i class="glyphicon glyphicon-stats"></i> Ventas x Cliente</h3>
			<!--<a onclick="thePDF()" class="btn btn-default">Descargar PDF</a><br><br>-->
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">
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
				</div><!-- /.box-header -->
				<div class="box-body">
					<table class="table table-bordered table-hover" id="tabla_lista-reporte">
						<thead>
							<tr>
								<th hidden></th>
								<th>Cliente</th>
								<th>Fecha</th>
								<th>Cantidad</th>
								<th>Modelo</th>
								<th>Subtotal</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h2><i class="glyphicon glyphicon-stats"></i> Graficos</h2>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">

				</div><!-- /.box-header -->
				<div class="box-body">
					<table align="center" style="width: 100%;">
						<tr>
							<!--<td><div id="chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div></td>-->
							<td id="graph-container">
								<!--<canvas id="pie-chart" width="500" height="500"></canvas>-->
								<div id="chartContainer" style="height: 500px; width: 100%;"></div>
							</td>
						</tr>
						<tr>
							<td id="graph-container2">
								<!--<canvas id="pie-chart2" width="500" height="500"></canvas>-->
								<div id="chartContainer2" style="height: 500px; width: 100%;"></div>
							</td>
						</tr>
					</table>
				</div><!-- /.box-body -->
			</div>
		</div>
	</div>
</section>

<!--<script src="core/app/view/charts/canvasjs.min.js"></script>-->

<!--<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>-->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script type="text/javascript">
	function buscar_por_fecha() {
		lista_datos();
		grafico_ventas_clientes();
		grafico_ventas_producto();
	}

	function lista_datos() {
		$("#tabla_lista-reporte").find('tbody').empty();
		//$("#reportar").attr('href', 'http://peruviandress.com/sivecsol/core/app/view/generar_pdf.php?filtro=ninguno&tabla=compras');
		$.get('core/app/view/venta.php', {
			parAccion: 'lista_rep_ventas_cliente',
			desde: $("#fecha_desde").val(),
			hasta: $("#fecha_hasta").val()
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {

				$("#tabla_lista-reporte").find('tbody').append('<tr>' +
					'<th scope="row" hidden>' + val.fff + '</th>' +
					'<th scope="row">' + val.cliente + '</th>' +
					'<th scope="row">' + val.fecha + '</th>' +
					'<td>' + val.cantidad + '</td>' +
					'<td>' + val.modelo + '</td>' +
					'<td>S/. ' + parseFloat(val.subtotal).toFixed(2) + '</td>' +
					'</tr>');
			});
			$("#tabla_lista-reporte").DataTable().destroy();
			$("#tabla_lista-reporte").DataTable({
				dom: 'Bfrtip',
				buttons: [{
						extend: 'excelHtml5',
					},
					{
						extend: 'pdfHtml5',
						orientation: 'portrait',
					},
				],
				order: ['0', 'desc'],
				"language": {
					"sProcessing": "Procesando...",
					"sLengthMenu": "Mostrar _MENU_ registros",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
					"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix": "",
					"sSearch": "Buscar:",
					"sUrl": "",
					"sInfoThousands": ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst": "Primero",
						"sLast": "Último",
						"sNext": "Siguiente",
						"sPrevious": "Anterior"
					},
					"oAria": {
						"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				}
			});
		});
	}


	function grafico_ventas_clientes() {
		Chart.pluginService.register({
			beforeRender: function(chart) {
				if (chart.config.options.showAllTooltips) {
					// create an array of tooltips
					// we can't use the chart tooltip because there is only one tooltip per chart
					chart.pluginTooltips = [];
					chart.config.data.datasets.forEach(function(dataset, i) {
						chart.getDatasetMeta(i).data.forEach(function(sector, j) {
							chart.pluginTooltips.push(new Chart.Tooltip({
								_chart: chart.chart,
								_chartInstance: chart,
								_data: chart.data,
								_options: chart.options.tooltips,
								_active: [sector]
							}, chart));
						});
					});

					// turn off normal tooltips
					chart.options.tooltips.enabled = false;
				}
			},
			afterDraw: function(chart, easing) {
				if (chart.config.options.showAllTooltips) {
					// we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
					if (!chart.allTooltipsOnce) {
						if (easing !== 1)
							return;
						chart.allTooltipsOnce = true;
					}

					// turn on tooltips
					chart.options.tooltips.enabled = true;
					Chart.helpers.each(chart.pluginTooltips, function(tooltip) {
						tooltip.initialize();
						tooltip.update();
						// we don't actually need this since we are not animating tooltips
						tooltip.pivot();
						tooltip.transition(easing).draw();
					});
					chart.options.tooltips.enabled = false;
				}
			}
		});

		$.get('core/app/view/venta.php', {
			parAccion: 'lista_gra_ventas_cliente',
			desde: $("#fecha_desde").val(),
			hasta: $("#fecha_hasta").val()
		}, function(response) {
			var obj = JSON.parse(response);

			var chart = new CanvasJS.Chart("chartContainer", {
				animationEnabled: true,
				title: {
					text: "Ventas x Cliente"
				},
				data: [{
					type: "pie",
					startAngle: 240,
					yValueFormatString: "##0.00\"\"",
					indexLabel: "{label} {y}",
					dataPoints: obj.extra
				}]
			});
			chart.render();

		});
	}

	function grafico_ventas_producto() {
		$.get('core/app/view/venta.php', {
			parAccion: 'lista_gra_ventas_producto',
			desde: $("#fecha_desde").val(),
			hasta: $("#fecha_hasta").val()
		}, function(data) {
			var obj = JSON.parse(data);

			var chart = new CanvasJS.Chart("chartContainer2", {
				animationEnabled: true,
				title: {
					text: "Ventas x Modelo"
				},
				data: [{
					type: "pie",
					startAngle: 240,
					yValueFormatString: "##0.00\"\"",
					indexLabel: "{label} {y}",
					dataPoints: obj.extra
				}]
			});
			chart.render();

		});
	}

	$(document).ready(function() {
		var d = new Date();
		d.setMonth(d.getMonth() - 1);
		$('#fecha_desde').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		}).datepicker("setDate", d);
		$('#fecha_hasta').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		}).datepicker("setDate", new Date());


		//lista_datos();
		//grafico();
		//grafico_clientes();
		//grafico_ventas_clientes();
		//grafico_ventas_producto();

	});
</script>