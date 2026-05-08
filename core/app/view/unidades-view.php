<style type="text/css">
	#popup_editar {
		left: 0;
		position: absolute;
		/*top: 0;*/
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
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Lista de Unidades</h3>
			<div class="w-100 d-block text-right mb-1">
				<span class="btn btn-outline-dark rounded-pill" onclick="formulario();"><i class="fa fa-plus"></i> Agregar Unidad</span>
			</div>
			<div class="row mt-1">
				<div class="col-md-12">
					<div class="box box-primary table-responsive">
						<table class="table table-bordered table-hover" id="tabla_lista">
							<thead>
								<th>Codigo</th>
								<th>Unidad</th>
								<th></th>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="popup_editar" style="display: none;">
			<div class="content-popup">
				<div class="close"><a href="#" id="close_editar">X</a></div>
				<div>
					<h4 id="titulo_detalle">Nueva Unidad</h4>
					<div class="box box-primary">
						<div class="row">
							<div class="col-md-6">
								<label for="">Codigo</label>
								<input type="text" class="form-control rounded-pill" id="codigo" name="">
							</div>
							<div class="col-md-6">
								<label for="">Unidad</label>
								<input type="text" class="form-control rounded-pill" id="unidad" name="">
							</div>

							<div class="col-md-12 mt-1 mb-1 text-center">
								<span class="btn btn-danger rounded-pill" onclick="cerrar_editar()" style="margin-top: 5px;">Cerrar</span>
								<button class="btn btn-success rounded-pill" style="margin-top: 5px;" id="btn_formulario" onclick="agregar_unidad();">Agregar</button>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function actualizar_unidad(codigo_) {
			var codigo = $("#codigo").val();
			var unidad = $("#unidad").val();
			$.post('core/app/view/unidades.php?parAccion=actualizar_unidad', {
				codigo: codigo,
				unidad: unidad,
				codigo_: codigo_,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Unidad actualizada correctamente.</strong>' +
							'</div>'
					});
					lista_unidades();
					limpiar_formulario();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function agregar_unidad() {
			var codigo = $("#codigo").val();
			var unidad = $("#unidad").val();
			$.get('core/app/view/unidades.php', {
				parAccion: 'agregar_unidad',
				codigo: codigo,
				unidad: unidad
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Unidad guardada correctamente.</strong>' +
							'</div>'
					});
					lista_unidades();
					limpiar_formulario();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function eliminar(codigo) {
			$.get('core/app/view/unidades.php', {
				parAccion: 'eliminar',
				codigo: codigo
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Unidad guardada correctamente.</strong>' +
							'</div>'
					});
					lista_unidades();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function limpiar_formulario() {
			$("#codigo").val('');
			$("#unidad").val('');

			$("#titulo_detalle").text("Nueva Unidad");
			$("#btn_formulario").text("Guardar");
			$("#btn_formulario").attr('onclick', 'agregar_unidad();');
		}

		function editar(codigo) {
			formulario();

			$.post('core/app/view/unidades.php?parAccion=editar', {
				codigo: codigo
			}, function(data) {
				var obj = JSON.parse(data);

				$("#codigo").val(obj.codigo);
				$("#unidad").val(obj.unidad);

				$("#titulo_detalle").text("Modificar Unidad");
				$("#btn_formulario").text("Actualizar")
				$("#btn_formulario").attr('onclick', 'actualizar_unidad("' + codigo + '");');
			});
		}

		function formulario() {
			$('#popup_editar').fadeIn('slow');
			$('.popup-overlay').fadeIn('slow');
			$('.popup-overlay').height($(window).height());
			return false;
		}

		function cerrar_editar() {
			$('#close_editar').click();
			limpiar_formulario();
		}

		function lista_unidades() {
			$("#tabla_lista").find('tbody').empty();
			$.get('core/app/view/unidades.php', {
				parAccion: 'lista_unidades'
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					$("#tabla_lista").find('tbody').append('<tr><td>' + val.codigo + '</td><td>' + val.unidad + '</td><td><span class="btn btn-sm btn-outline-warning d-block mt-1 rounded-pill" style="cursor: pointer;" onclick="editar(\'' + val.codigo + '\');"><i class="fa fa-pencil"></i></span> <span class="btn btn-sm btn-outline-danger d-block mt-1 rounded-pill" style="cursor: pointer;" onclick="eliminar(\'' + val.codigo + '\');"><i class="fa fa-trash"></i></span></td></tr>');
				});
			});
		}
		$(document).ready(function() {
			lista_unidades();
			$('#close_editar').on('click', function() {
				//limpiar_formulario();
				$('#popup_editar').fadeOut('slow');
				$('.popup-overlay').fadeOut('slow');
				return false;
				flag = false;
			});
		});
	</script>
</section>