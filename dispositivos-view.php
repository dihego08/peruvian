<?php

$person = PersonData::getAll();

$k = Core::$user->kind;

$maquinas = DispositivosData::getAll();
?>
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
		z-index: 2001;
		height: 100%;
	}

	.thumbnail {
		margin-left: auto;
		margin-right: auto;
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

	@media (max-width: 600px) {
		select {
			width: 105px !important;
		}
	}

	.w-100 {
		width: 100%;
	}

	.mt-1 {
		margin-top: .5rem;
	}

	.mt-2 {
		margin-top: 1rem;
	}

	.cursor {
		cursor: pointer;
	}

	.block {
		display: block;
	}

	.bold {
		font-weight: bold;
	}

	.text-center {
		text-align: center;
	}

	.br-4 {
		border-radius: 4px !important;
	}

	table.entries {
		width: 100%;
		border-spacing: 0px;
		margin: 0;
	}

	table.entries thead.fixed {
		position: fixed;
		top: 0;
	}
</style>
<style type="text/css">
	.selected {
		background-color: #666;
		color: #fff;
	}
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h3>
		Dispotivos y Accesorios
	</h3>
</section>

<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="w-100 text-right" style="margin-bottom: 2rem;">
				<a href="index.php?view=newdispositivo" class="btn btn-success rounded-pill">Agregar Dispositivo/Accesorio</a>
				<a href="?view=maquinas" class="btn btn-danger rounded-pill" style="margin-left: 1rem;">Volver</a>
			</div>
			<?php


			if (count($maquinas) > 0) {
			?>
				<div class="box box-primary">
					<div class="box-body no-padding">
						<div class="box-body table-responsive" style="padding: 0;">
							<table id="example" class="table  table-bordered table-hover">
								<thead>
									<th>codigo</th>
									<th>Descripcion</th>
									<th>Fecha</th>
									<th>Responsable</th>
									<th>Valorizacion</th>
									<th>Imagen</th>
									<th>Cantidad</th>
									<th>Observaciones</th>
									<th></th>
								</thead>
								<tbody>
									<?php foreach ($maquinas as $maquina) { ?>
										<tr>
											<td><?php echo $maquina->codigo; ?></td>
											<td><?php echo $maquina->descripcion; ?></td>
											<td><?php echo DispositivosData::get_max("fecha_entrega", $maquina->id)/*$maquina->fecha*/; ?></td>
											<td><?php echo DispositivosData::get_max("recibido_por", $maquina->id)/*$maquina->responsable*/; ?></td>
											<td><?php echo DispositivosData::get_max("cantidad", $maquina->id)/*$maquina->valorizacion*/; ?></td>
											<td>
												<?php if ($maquina->imagen != "") : ?>
													<img src="storage/dispositivos/<?php echo $maquina->imagen; ?>" style="width:94px; cursor: pointer;" onclick="abrir_imagen('storage/dispositivos/<?php echo $maquina->imagen; ?>');">
												<?php endif; ?>
											</td>
											<td><?php echo $maquina->cantidad; ?></td>
											<td><?php echo $maquina->observaciones; ?></td>
											<td style="text-align: center;">
												<a href="index.php?view=editdispositivo&mid=<?php echo $maquina->id; ?>" class="btn btn-sm btn-outline-warning rounded-pill" <?php echo $cls; ?>><i class="glyphicon glyphicon-pencil"></i></a>
												<span class="btn btn-outline-danger btn-sm rounded-pill" title="Eliminar Dispositivo/Accesorio" onclick="eliminar_dispositivo(<?php echo $maquina->id; ?>);"><i class="glyphicon glyphicon-trash"></i></span>
												<a href="?view=registro_dispositivo&mid=<?php echo $maquina->id; ?>" style="display: block; margin-top: 0.5rem;" class="btn btn-outline-primary btn-sm rounded-pill">Ver Registo</a>
											</td>
										</tr>
								<?php }
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>
	<div id="popup_editar" style="display: none; overflow-y: scroll;">
		<div class="content-popup">
			<div class="close"><a href="#" id="close_editar">X</a></div>
			<div>
				<div class="box box-primary table-responsive">
					<img src="" id="imagen_grande" class="thumbnail" style="max-width: 450px;">
					<span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
				</div>
			</div>
		</div>
	</div>
	<div class="popup-overlay"></div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		$("#example").DataTable({
			dom: 'Bfrtip',
			buttons: [{
					extend: 'excelHtml5'
				},
				{
					extend: 'pdfHtml5',
					orientation: 'landscape'
				}
			],
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
	$('#close_editar').on('click', function() {
		//limpiar_formulario();
		$('#popup_editar').fadeOut('slow');
		$('.popup-overlay').fadeOut('slow');
		return false;
	});

	function abrir_imagen(codigo) {
		$("#imagen_grande").attr('src', codigo);
		$('#popup_editar').fadeIn('slow');
		$('.popup-overlay').fadeIn('slow');
		$('.popup-overlay').height($(window).height());
		return false;
	}

	function cerrar_editar() {
		$('#close_editar').click();
	}

	function eliminar_dispositivo(id) {
		bootbox.confirm({
			message: "¿Seguro de Eliminar este registro? Esta acción es irreversible.",
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
					$.post('core/app/view/maquinas.php?parAccion=eliminar_dispositivo', {
						id: id
					}, function(data) {
						var obj = JSON.parse(data);
						if (obj.Result == "OK") {
							bootbox.alert({
								message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Eliminado correctamente.</strong>' +
									'</div>'
							});
							location.reload();
						} else {
							bootbox.alert({
								message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
									'<strong>Algo ha salido mal.</strong>' +
									'</div>'
							});
						}
					});
				} else {}
			}
		});
	}
</script>