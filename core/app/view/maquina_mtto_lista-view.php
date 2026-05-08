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
				<h3 id="titulo_maquina" style="width: 100%;">
					Mantenimiento Realizado <?php echo $maquina->maquina_tipo; ?>-<?php echo $maquina->maquina_codigo; ?>
				</h3>
				<div style="width: 100%; text-align: right; margin-bottom: 1rem;">
					<span onclick="goBack();" style="cursor: pointer;" class="btn btn-outline-danger rounded-pill">Volver</a>
				</div>
				<div class="box box-primary">
					<table class="table  table-bordered table-hover">
						<tr>
							<td colspan="2" class="text-center">Tipo de Mantenimiento</td>
							<td>Mantenimiento Realizado</td>
							<td>Fecha</td>
							<td>Responsable</td>
							<td>Costo</td>
							<td>Observaciones</td>
							<td></td>
						</tr>
						<tr>
							<td>
								<div class="form-check text-center">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="tipo_mantenimiento" value="1"> Preventivo
									</label>
								</div>
							</td>
							<td>
								<div class="form-check text-center">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="tipo_mantenimiento" value="2"> Correctivo
									</label>
								</div>
							</td>
							<td><input type="text" id="mtto_tipo" class="form-control rounded-pill" value=""></td>
							<td><input type="text" id="mtto_fecha" readonly="readonly" class="form-control rounded-pill clsDatePicker"></td>
							<td><input type="text" id="mtto_reponsable" class="form-control rounded-pill" value=""></td>
							<td><input type="text" id="mtto_costo" class="form-control rounded-pill" value=""></td>
							<td><input type="text" id="mtto_observacion" class="form-control rounded-pill" value=""></td>
							<td><button class="btn btn-success rounded-pill" id="f" style="width: 100%;" onclick="agregar_mtto(<?php echo $maquina_id; ?>);">Agregar</button></td>
						</tr>
					</table>
					<table id="tabla_mtto" class="table  table-bordered  table-hover">
						<thead>
							<th colspan="2" class="text-center">Tipo de Mantenimiento</th>
							<th>Mantenimiento Realizado</th>
							<th>Fecha</th>
							<th>Responsable</th>
							<th>Costo</th>
							<th>Observaciones</th>
							<th></th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php
	}
	?>
</section>
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
			tipo_mantenimiento: $('input:radio[name=tipo_mantenimiento]:checked').val()
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == 'OK') {
				lista_mttos(maquina_id);
				limpiar_formulario();
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Ago ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}

	function limpiar_formulario() {
		$("#mtto_tipo").val("");
		$("#mtto_fecha").val("");
		$("#mtto_reponsable").val("");
		$("#mtto_observacion").val("");
		$("#mtto_costo").val("");

		$("#f").text("Guardar");
		$("#f").attr('onclick', 'agregar_mtto(<?php echo $maquina_id; ?>);');
	}

	function actualizar_info(id) {
		var tipo = $("#mtto_tipo").val();
		var fecha = $("#mtto_fecha").val();
		var responsable = $("#mtto_reponsable").val();
		var observacion = $("#mtto_observacion").val();

		$.get('core/app/view/mantenimiento.php', {
			parAccion: 'actualizar_mtto',
			tipo: tipo,
			fecha: fecha,
			responsable: responsable,
			observacion: observacion,
			id: id,
			costo: $("#mtto_costo").val(),
			tipo_mantenimiento: $('input:radio[name=tipo_mantenimiento]:checked').val()
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == 'OK') {
				lista_mttos(<?php echo $maquina_id; ?>);
				limpiar_formulario();
			} else {
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Ago ha salido mal.</strong>' +
						'</div>'
				});
			}
		});
	}

	function goBack() {
		window.history.back();
	}

	function lista_mttos(maquina_id) {
		$("#tabla_mtto").find('tbody').empty();
		$.get('core/app/view/mantenimiento.php', {
			parAccion: 'lista_mttos',
			maquina_id: maquina_id
		}, function(data) {
			var obj = JSON.parse(data);
			var total_total = 0;
			$.each(obj.Records, function(index, val) {
				total_total += parseFloat(val.costo);
				var $check_preventivo = "";
				var $badge_preventivo = "";
				var $check_correctivo = "";
				var $badge_correctivo = "";
				switch (val.tipo_mantenimiento) {
					case '1':
						$check_preventivo = 'checked="true"';
						$badge_preventivo = 'badge';
						break;
					case '2':
						$check_correctivo = 'checked="true"';
						$badge_correctivo = 'badge';
						break;
				}
				$("#tabla_mtto").find('tbody').append('<tr>' +
					`<td>
						<div class="form-check text-center">
							<label class="form-check-label ${$badge_preventivo}">
								<input type="radio" readonly disabled class="form-check-input" ${$check_preventivo}> Preventivo
							</label>
						</div>
					</td>
					<td>
						<div class="form-check text-center">
							<label class="form-check-label ${$badge_correctivo}">
								<input type="radio" readonly disabled class="form-check-input" ${$check_correctivo}> Correctivo
							</label>
						</div>
					</td>` +
					'<td>' + val.maq_mtto_tipo + '</td><td>' + val.maq_mtto_fecha + '</td><td>' + val.maq_mtto_reponsable + '</td><td>S/ ' + val.costo + '</td><td>' + val.maq_mtto_observacion + '</td><td><span class="btn-sm btn btn-outline-warning rounded-pill" onclick="editar(' + val.maq_mtto_id + ');" style="cursor: pointer; text-align: center; display: block; width: 100%;"><i class="fa fa-pencil"></i></span> <span class="btn-sm btn btn-outline-danger rounded-pill" onclick="eliminar(' + val.maq_mtto_id + ');" style="margin-top: 0.5rem; cursor: pointer; text-align: center; display: block; width: 100%;"><i class="fa fa-trash"></i></span></td></tr>');

			});
			$("#tabla_mtto").find('tbody').append(`
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="font-weight: bold;">S/ ${total_total.toFixed(2)}</td>
					<td></td>
					<td></td>
				</tr>
			`);
			//$("#abono_opciones").append('<span class="btn-xs btn-success" onclick="unir_saldos_abonos();" style="cursor: pointer;">UNIR SALDOS</span>');
		});
	}

	function editar(id) {
		$.post('core/app/view/mantenimiento.php?parAccion=editar_mantenimiento', {
			id: id
		}, function(response) {
			var obj = JSON.parse(response);

			$("#mtto_tipo").val(obj.maq_mtto_tipo);
			$("#mtto_fecha").val(obj.maq_mtto_fecha);
			$("#mtto_reponsable").val(obj.maq_mtto_reponsable);
			$("#mtto_costo").val(obj.maq_mtto_costo);
			$("#mtto_observacion").val(obj.maq_mtto_observacion);

			$('input:radio[name=tipo_mantenimiento]').val([obj.tipo_mantenimiento]);

			$("#f").text("Actualizar");
			$("#f").attr('onclick', 'actualizar_info(' + id + ');');
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

		lista_mttos(<?php echo $maquina_id; ?>);

		limpiar_formulario();
	});
</script>