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
	$maquina = DispositivosData::getById($maquina_id);

	if ($maquina != null) {
	?>
		<div class="row">
			<div class="col-md-12">
				<h3 id="titulo_maquina" style="width: 100%;">
					<?php echo $maquina->codigo; ?>-<?php echo $maquina->descripcion; ?>
				</h3>
				<div style="width: 100%; text-align: right; margin-bottom: 1rem;">
					<span onclick="goBack();" style="cursor: pointer;" class="btn btn-outline-danger rounded-pill">Volver</a>
				</div>
				<div class="box box-primary">
					<table class="table  table-bordered table-hover">
						<tr>
							<!--<th>Descripción</th>-->
							<th>Fecha Entrega</th>
							<th>Recibido Por</th>
							<th>Valoración</th>
							<th>Responsable</th>
							<th>Observación</th>
							<th></th>
						</tr>
						<tr>
							<!--<td><input type="text" id="mtto_tipo" class="form-control" value=""></td>-->
							<td><input type="date" id="fecha_entrega" class="form-control rounded-pill"></td>
							<td><input type="text" id="recibido_por" class="form-control rounded-pill" value=""></td>
							<td><input type="text" id="cantidad" class="form-control rounded-pill" value=""></td>
							<td><input type="text" id="responsable" class="form-control rounded-pill" value=""></td>
							<td><input type="text" id="observaciones" class="form-control rounded-pill" value=""></td>
							<td><button class="btn btn-success rounded-pill" id="f" style="width: 100%;" onclick="agregar_mtto(<?php echo $maquina_id; ?>);">Agregar</button></td>
						</tr>
					</table>
					<table id="tabla_mtto" class="table  table-bordered  table-hover">
						<thead>
							<th>Fecha Entrega</th>
							<th>Recibido Por</th>
							<th>Valoración</th>
							<th>Responsable</th>
							<th>Observación</th>
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
		//var tipo = $("#mtto_tipo").val();
		var fecha_entrega = $("#fecha_entrega").val();
		var recibido_por = $("#recibido_por").val();
		var cantidad = $("#cantidad").val();
		var observaciones = $("#observaciones").val();
		var responsable = $("#responsable").val();

		$.post('core/app/view/mantenimiento.php?parAccion=agregar_registro', {
			fecha_entrega: fecha_entrega,
			recibido_por: recibido_por,
			cantidad: cantidad,
			observaciones: observaciones,
			responsable: responsable,
			id_dispositivo: maquina_id,
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
		$("#fecha_entrega").val("");
		$("#recibido_por").val("");
		$("#cantidad").val("");
		$("#observaciones").val("");
		$("#responsable").val("");

		$("#f").text("Guardar");
		$("#f").attr('onclick', 'agregar_mtto(<?php echo $maquina_id; ?>);');
	}

	function actualizar_info(id) {
		var fecha_entrega = $("#fecha_entrega").val();
		var recibido_por = $("#recibido_por").val();
		var cantidad = $("#cantidad").val();
		var observaciones = $("#observaciones").val();
		var responsable = $("#responsable").val();

		$.post('core/app/view/mantenimiento.php?parAccion=actualizar_registro', {
			id: id,


			fecha_entrega: fecha_entrega,
			recibido_por: recibido_por,
			cantidad: cantidad,
			observaciones: observaciones,
			responsable: responsable,
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
			parAccion: 'lista_registros',
			id_dispositivo: maquina_id
		}, function(data) {
			var obj = JSON.parse(data);
			var total_total = 0;
			$.each(obj.Records, function(index, val) {
				total_total += parseFloat(val.cantidad);
				$("#tabla_mtto").find('tbody').append('<tr><td>' + val.fecha_entrega + '</td><td>' + val.recibido_por + '</td><td>' + val.cantidad + '</td><td>' + val.responsable + '</td><td>' + val.observaciones + '</td><td><span class="btn-sm btn btn-outline-warning rounded-pill" onclick="editar(' + val.id + ');" style="cursor: pointer; text-align: center; display: block; width: 100%;"><i class="fa fa-pencil"></i></span> <span class="btn-sm btn btn-outline-danger rounded-pill" onclick="eliminar(' + val.id + ');" style="margin-top: 0.5rem; cursor: pointer; text-align: center; display: block; width: 100%;"><i class="fa fa-trash"></i></span></td></tr>');

			});
			$("#tabla_mtto").find('tbody').append('<tr><td></td><td></td><td>' + total_total + '</td><td></td><td></td><td></td></tr>');
		});
	}

	function editar(id) {
		$.post('core/app/view/mantenimiento.php?parAccion=editar_registro', {
			id: id
		}, function(response) {
			var obj = JSON.parse(response);

			$("#fecha_entrega").val(obj.fecha_entrega);
			$("#recibido_por").val(obj.recibido_por);
			$("#cantidad").val(obj.cantidad);
			$("#observaciones").val(obj.observaciones);
			$("#responsable").val(obj.responsable);

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
						parAccion: 'eliminar_registro',
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