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
			<h3><i class="glyphicon glyphicon-stats"></i> Ventas x Periodo</h3>
			<!--<a onclick="thePDF()" class="btn btn-default">Descargar PDF</a><br><br>-->
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">

				</div><!-- /.box-header -->
				<div class="box-body">
					<table class="table table-bordered  table-hover" id="tabla_lista">
						<thead>
							<tr id="cabeza_tabla_">
								<th>Periodo</th>
								<!--<th>Total</th>-->
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
			<h3><i class="glyphicon glyphicon-stats"></i> Graficos</h3>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">
					<div class="row">
						<div class="col-md-6">
							<label>Desde</label>
							<input class="rounded-pill form-control datepicker" id="desde">
						</div>
						<div class="col-md-6">
							<label>Hasta</label>
							<input class="rounded-pill form-control datepicker" id="hasta">
						</div>
						<div class="col-md-12 text-center" style="margin-top: 1rem;">
							<button class="btn btn-success rounded-pill" onclick="grafico_ventas_mes();">Filtrar</button>
						</div>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
					<table align="center">
						<tr>
							<td><canvas id="pie-chart" width="900" height="500"></canvas></canvas></td>

						</tr>
					</table>
				</div><!-- /.box-body -->
			</div>
		</div>
	</div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.full.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet" />


<script type="text/javascript">
	function lista_datos() {
		$("#tabla_lista").find('tbody').empty();
		$.get('core/app/view/venta.php', {
			parAccion: 'lista_rep_ventas_mes'
		}, function(data) {
			var obj = JSON.parse(data);
			var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
			var html = "";

			for (var i = 0; i <= 11; i++) {
				html += `<tr>
							<th row="scope">` + meses[i] + `</th>`;
				$.each(obj.Records, function(index, val) {
					html += `<td id="` + i + `_` + index + `">S/ 0.00</td>`;
				});
				html += `</tr>`;
			}

			$("#tabla_lista").find('tbody').append(html);

			ar_tot = [];

			$.each(obj.Records, function(index, val) {
				$("#cabeza_tabla_").append(`
					<th>` + index + `</th>
				`);
				total_uno = 0;
				$.each(val, function(i, v) {
					$("#" + parseInt(v.mes - 1) + "_" + index).text("S/." + v.total);
					total_uno += parseFloat(v.total);
				});
				ar_tot.push(total_uno);
			});

			$totales = "<tr><td></td>";

			for (i = 0; i < Object.keys(obj.Records).length; i++) {
				$totales += `<td id="total_${i}"><b>S/ ${parseFloat(ar_tot[i]).toFixed(2)}</b></td>`;
			}
			$totales += "</tr>";

			$("#tabla_lista").find('tbody').append($totales);
		});
	}

	function grafico_ventas_mes() {

		$.get('core/app/view/venta.php', {
			parAccion: 'lista_gra_ventas_mes',
			desde: $("#desde").val(),
			hasta: $("#hasta").val(),
		}, function(data) {
			var obj = JSON.parse(data);

			//var datagra = {"labels":obj.labels, "series":[obj.series]};
			var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

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

			new Chart(document.getElementById("pie-chart"), {
				type: 'bar',
				data: {
					labels: obj.meses,
					datasets: [{
						label: ".",
						backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850", "#3e85cd", "#8e6ea2", "#3cba7f", "#e8c8b9", "#c45950", "#3e65cd", "#8e5ea2"],
						data: obj.totales
					}]
				},
				options: {
					showAllTooltips: true,
					tooltips: {
						callbacks: {
							label: function(tooltipItem, data) {
								return 'S/. ' + tooltipItem.yLabel;
							},
						},
						backgroundColor: '#eee',
						titleFontSize: 15,
						titleFontColor: '#0066ff',
						bodyFontColor: '#000',
						bodyFontSize: 14,
						displayColors: false,
						yAlign: "bottom",
						xAlign: "center",
						/*position: 'top'*/
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true,
								callback: function(value, index, values) {
									return 'S/. ' + value;
								}
							}
						}]
					},
				}
			});

		});
	}

	$(document).ready(function() {
		$(".datepicker").datetimepicker({
			format: "Y-m-d",
			timepicker: false
		});
		$.datetimepicker.setLocale('es');
		lista_datos();
		grafico_ventas_mes();
	});
</script>