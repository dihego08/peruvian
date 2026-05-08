<?php
$person = PersonData::getAll();

$accion = $_GET['act'];

$k = Core::$user->kind;

switch ($accion) {
	case 'bajas':
		$maquinas = MaquinaData::getBajas();
		break;

	default:
		$maquinas = MaquinaData::getAll();
		break;
}
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
		MAQUINAS
	</h3>
</section>

<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-12">

			<div class="btn-group">

				<?php

				if ($k == 1) {
				?>
					<a href="index.php?view=newmaquina" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Agregar Maquina</a>
					<a href="index.php?view=maquinas" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Ver Activos</a>
					<a href="index.php?view=maquinas&act=bajas" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Ver Bajas</a>
					<a href="index.php?view=maquinas_cronograma" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Programa de Mantenimiento</a>
					<a href="index.php?view=dispositivos" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Dispositivos y accesorios</a>
				<?php
				} else {
				?>
					<a href="index.php?view=newmaquina" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Agregar Maquina</a>
					<a href="index.php?view=maquinas" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Ver Activos</a>
					<a href="index.php?view=maquinas&act=bajas" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Ver Bajas</a>
					<a href="index.php?view=maquinas_cronograma" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Programa de Mantenimiento</a>
					<a href="index.php?view=dispositivos" class="btn btn-outline-dark rounded-pill" style="margin-right: 0.5rem;">Dispositivos y accesorios</a>
				<?php }
				?>

			</div>
			<br><br>

			<?php


			if (count($maquinas) > 0) {
			?>
				<div class="box box-primary">
					<div class="box-body no-padding">
						<div class="box-body table-responsive" style="padding: 0;">
							<table id="example" class="table  table-bordered datatable table-hover">
								<thead>
									<tr>
										<td colspan="5"></td>
										<td colspan="3">Cabezal</td>
										<td colspan="2">Motor</td>
										<td></td>

									</tr>
									<tr>
										<td>...</td>
										<td>Ubicacion</td>
										<td>Codigo</td>
										<td>Descripcion</td>
										<td>Imagen</td>
										<td>Marca</td>
										<td>Modelo</td>
										<td>Serie</td>
										<td>Marca</td>
										<td>Serie</td>
										<td>Activo</td>

									</tr>
								</thead>
								<?php foreach ($maquinas as $maquina) { ?>
									<tr>
										<td><a href="index.php?view=maquina_mtto&mid=<?php echo $maquina->maquina_id; ?>" class="btn btn-outline-success rounded-pill">Mantenimiento</a></td>
										<td><?php echo $maquina->maquina_ubicacion; ?></td>
										<td><?php echo $maquina->maquina_tipo; ?>-<?php echo $maquina->maquina_codigo; ?></td>
										<td><?php echo $maquina->maquina_descripcion; ?></td>
										<td>
											<?php if ($maquina->maquina_imagen != "") : ?>
												<img src="storage/maquinas/<?php echo $maquina->maquina_imagen; ?>" style="width:94px; cursor: pointer;" onclick="abrir_imagen('storage/maquinas/<?php echo $maquina->maquina_imagen; ?>');">
											<?php endif; ?>
										</td>
										<td><?php echo $maquina->maquina_marca; ?></td>
										<td><?php echo $maquina->maquina_modelo; ?></td>
										<td><?php echo $maquina->maquina_serie; ?></td>
										<td><?php echo $maquina->maquina_marca_motor; ?></td>
										<td><?php echo $maquina->maquina_serie_motor; ?></td>
										<td>
											<?php if ($maquina->maquina_estado == "1") : ?><span class="btn btn-sm btn-outline-success rounded-pill" style="margin-bottom: 0.5rem;"><i class="fa fa-check"></i></span><?php endif; ?>
											<a href="index.php?view=editmaquina&mid=<?php echo $maquina->maquina_id; ?>" class="btn btn-sm btn-outline-warning rounded-pill" <?php echo $cls; ?> style="margin-bottom: 0.5rem;"><i class="glyphicon glyphicon-pencil"></i></a>
											<span class="btn btn-outline-danger rounded-pill btn-sm" title="Eliminar Maquina" onclick="eliminar_maquina(<?php echo $maquina->maquina_id; ?>);"><i class="glyphicon glyphicon-trash"></i></span>
										</td>
									</tr>
							<?php }
							}
							?>
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

	function eliminar_maquina(id) {
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
					$.post('core/app/view/maquinas.php?parAccion=eliminar_maquina', {
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