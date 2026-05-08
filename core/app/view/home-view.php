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
</style>
<section class="content-header">
	<h1>PERUVIAN DRESS TPX SAC </h1>
	<h4>Sucursal: <?php echo StockData::getPrincipal()->name;  ?></h4>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<a href="./?view=newcotization" class="btn btn-default">Nueva Cotización</a>
			<a href="./?view=new_order_pedido" class="btn btn-default">Nuevo Pedido</a>
			<a href="./?view=sell" class="btn btn-default">Nueva Venta</a>
			<!--  <a href="./?view=messages&opt=all" class="btn btn-default">Mensajes</a> -->
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
					<div class="form-group col-md-12">
						<button class="btn btn-success" onclick="buscar_por_fecha();" style="width: 100%;">Buscar por Fecha</button>
					</div>
				</div>
			</fieldset>
		</div>
	</div>

	<br>
	<div class="row">
	    <?php
	        if(Core::$user->kind != 1){
	            
        ?>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-glass"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Productos</span>
					<span class="info-box-number"><?php echo count(ProductData::getAll()); ?><small></small></span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>
		<?php
		}else{
	            
	    ?>
		<!-- /.col -->
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-male"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Clientes</span>
					<span class="info-box-number"><?php echo count(PersonData::getClients()); ?></span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>
		<?php
		    }
	    ?>
		<!-- /.col -->

		<!-- fix for small devices only -->
		<div class="clearfix visible-sm-block">
		</div>


	</div>
	<!-- /.row -->

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<!-- /.box-header -->
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<canvas id="pie-chart" width="900" height="500"></canvas>










							<!-- /.chart-responsive -->
						</div>
						<!-- /.col -->

						<!-- /.col -->
					</div>
					<!-- /.row -->
				</div>


				<!-- /.box-footer -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->







</section>
<script type="text/javascript">
	function grafico_ventas_mes() {

		$.get('core/app/view/venta.php', {
			parAccion: 'lista_gra_ventas_mes'
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
					}
				}
			});

		});
	}

	function buscar_por_fecha() {
		var desde = $("#fecha_desde").val();
		var hasta = $("#fecha_hasta").val();

		$.get('core/app/view/venta.php', {
			parAccion: 'lista_gra_ventas_mes_fechas',
			desde: $("#fecha_desde").val(),
			hasta: $("#fecha_hasta").val()
		}, function(data) {
			var obj = JSON.parse(data);

			//var datagra = {"labels":obj.meses, "series":[obj.totales]};
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
					labels: meses,
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
						}
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
					}
				}
			});

		});
	}

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
		$("#fecha_p").datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});



		<?php
		if (Core::$user->kind == 1) {
			echo 'grafico_ventas_mes();';
		} elseif(Core::$user->kind == 5) {
			echo "window.location='index.php?view=fesunat';";
		}elseif(Core::$user->kind == 12) {
			echo "window.location='index.php?view=ficha_tecnica';";
		}else{
		    echo "window.location='index.php?view=products';";
		}
		?>


	});
</script>