<style type="text/css">
	.ui-datepicker-month {
		color: #313131;
	}

	.ui-datepicker-year {
		color: #313131;
	}
</style>
<section class="content">
	<?php
	$maquina_id = $_GET['mid'];
	$maquina = MaquinaData::getById($maquina_id);

	if ($maquina != null) {
	?>
		<div class="row">
			<div class="col-md-12">
				<h3>
					FICHA DE MAQUINA
				</h3>
				<div class="box box-primary">
					<div style="width: 100%; padding: 10px; text-align: right;">
						<?php
						if (empty($maquina->factura_compra) || is_null($maquina->factura_compra)) {
						} else {
							echo '<a class="btn btn-outline-info rounded-pill" href="storage/maquinas/' . $maquina->factura_compra . '" target="_blank">Factura</a>';
						}

						?>
						<a class="btn btn-outline-primary rounded-pill" href="core/app/view/pdf-mantenimiento.php?mid=<?php echo $_GET['mid']; ?>">Ficha</a>
						<a href="?view=maquina_mtto_lista&mid=<?php echo $_GET['mid']; ?>" class="btn btn-outline-danger rounded-pill">Mantenimiento Realizado</a>
					</div>
					<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=addmaquina" role="form">
						<table class="table  table-bordered table-hover">
							<tr>
								<td>
									<table>
										<tr>
											<td>Codigo</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->maquina_tipo; ?>-<?php echo $maquina->maquina_codigo; ?>"></td>
										</tr>
										<tr>
											<td>Descripcion</td>
											<td><input type="text" class="form-control rounded-pill mt-1 mb-1" size="50" value="<?php echo $maquina->maquina_descripcion; ?>"></td>
										</tr>
										<tr>
											<td>Marca de la maquina</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->maquina_marca; ?>"></td>
										</tr>
										<tr>
											<td>Modelo</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->maquina_modelo; ?>"></td>
										</tr>
										<tr>
											<td>Nro de Serie</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->maquina_serie; ?>"></td>
										</tr>
										<tr>
											<td>Marca de Motor</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->maquina_marca_motor; ?>"></td>
										</tr>
										<tr>
											<td>Nro Serie de Motor</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->maquina_serie_motor; ?>"></td>
										</tr>
										<tr>
											<td>Medidas para Espacio</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->maquina_exigencias; ?>"></td>
										</tr>
										<tr>
											<td>Voltaje</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->maquina_voltaje; ?>"></td>
										</tr>
										<tr>
											<td>Tipo Corriente</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->maquina_tipo_corriente; ?>"></td>
										</tr>
										<tr>
											<td>Año de compra</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1 datepicker" value="<?php echo $maquina->maquina_anio_compra; ?>"></td>
										</tr>
										<tr>
											<td>Vida Util</td>
											<td><input type="text" name="codigo" id="codigo" class="form-control rounded-pill mt-1 mb-1 datepicker" value="<?php echo $maquina->maquina_vida_util; ?>"></td>
										</tr>
										<tr>
											<td>Precio de Compra</td>
											<td><input type="text" name="precio_compra" id="precio_compra" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->precio_compra; ?>"></td>
										</tr>
										<tr>
											<td>Proveedor</td>
											<td><input type="text" name="proveedor" id="proveedor" class="form-control rounded-pill mt-1 mb-1" value="<?php echo $maquina->proveedor; ?>"></td>
										</tr>
									</table>
								</td>
								<td>
									<img src="storage/maquinas/<?php echo $maquina->maquina_imagen; ?>" style="width:494px;" />
								</td>
							</tr>
						</table>
					</form>

				</div>
			</div>
		</div>
	<?php
	}
	?>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.full.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet" />

<script type="text/javascript">
	function agregar_mtto(maquina_id) {
		var tipo = $("#mtto_tipo").val();
		var fecha = $("#mtto_fecha").val();
		var responsable = $("#mtto_reponsable").val();
		var observacion = $("#mtto_observacion").val();

		$.get('core/app/view/mantenimiento.php', {
			parAccion: 'agregar_mtto',
			tipo: tipo,
			fecha: fecha,
			responsable: responsable,
			observacion: observacion,
			maquina_id: maquina_id,
			costo: $("#mtto_costo").val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == 'OK') {
				lista_mttos(maquina_id);
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Ago ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}

	function lista_mttos(maquina_id) {
		$("#tabla_mtto").find('tbody').empty();
		$.get('core/app/view/mantenimiento.php', {
			parAccion: 'lista_mttos',
			maquina_id: maquina_id
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#tabla_mtto").find('tbody').append('<tr><td>' + val.maq_mtto_tipo + '</td><td>' + val.maq_mtto_fecha + '</td><td>' + val.maq_mtto_reponsable + '</td><td>' + val.costo + '</td><td>' + val.maq_mtto_observacion + '</td><td><span class="btn-xs btn-danger" onclick="eliminar(' + val.maq_mtto_id + ');" style="cursor: pointer;"><i class="fa fa-trash"></i></span></td></tr>');

			});
			//$("#abono_opciones").append('<span class="btn-xs btn-success" onclick="unir_saldos_abonos();" style="cursor: pointer;">UNIR SALDOS</span>');
		});
	}

	function eliminar(id) {
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
					//alert(id);
					$.get('core/app/view/mantenimiento.php', {
						parAccion: 'eliminar',
						id: id
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == 'OK') {

							lista_mttos(<?php echo $maquina_id; ?>);

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

	$(document).ready(function() {
		$('#mtto_fecha').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});

		$(".datepicker").datetimepicker({
			format: "d-m-Y",
			timepicker: false
		});
		$.datetimepicker.setLocale('es');
		//lista_mttos(<?php echo $maquina_id; ?>);
	});
</script>