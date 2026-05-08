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

	.oculta {
		display: none;
	}

	.resaltar {
		background-color: yellow;
		/*display: table-row;*/
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Familias y Clases</h3>
			<div class="col-md-6">
				<div class="box box-primary table-responsive">
					<h4 style="text-align: center;">Familia de artículos</h4>
					<div class="form-row mb-1">
						<div class="col-md-6">
							<input type="text" id="filtro_rapido_familia" name="filtro_rapido_familia" class="form-control rounded-pill" placeholder="Filtro Rápido">
						</div>
						<div class="col-md-6 text-right">
							<button class="btn btn-outline-dark rounded-pill btn-sm" onclick="formulario('familia');" style="margin-right: 0.5rem;"><i class="fa fa-plus"></i> Nueva Familia</button>
						</div>
					</div>
					<div class="form-row">

						<table class="table table-bordered table-hover mt-1" id="table_familias">
							<thead>
								<th>Código</th>
								<th>Descripción</th>
								<th></th>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>

				<div class="box box-primary table-responsive">
					<h4 style="text-align: center;">Subclase de artículos</h4>
					<div class="form-row mb-1">
						<div class="col-md-6">
							<input type="text" id="filtro_rapido_subclase" name="filtro_rapido_subclase" class="form-control rounded-pill" placeholder="Filtro Rápido">
						</div>
						<div class="col-md-6 text-right">
							<button class="btn btn-outline-dark btn-sm rounded-pill" onclick="formulario('subclase');" style="margin-right: 0.5rem;"><i class="fa fa-plus"></i> Nueva Subclase</button>
						</div>
					</div>
					<table class="table table-bordered table-hover mt-1" id="table_subclases">
						<thead>
							<th>Código</th>
							<th>Descripción</th>
							<!--<th>Clase</th>-->
							<th></th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="box box-primary table-responsive">
					<h4 style="text-align: center;">Clases de artículos</h4>
					<div class="form-row">
						<div class="col-md-6">
							<input type="text" id="filtro_rapido_clase" name="filtro_rapido_clase" class="form-control rounded-pill" placeholder="Filtro Rápido">
						</div>
						<div class="col-md-6 text-right">
							<button class="btn btn-outline-dark btn-sm rounded-pill" onclick="formulario('clase');" style="margin-right: 0.5rem;"><i class="fa fa-plus"></i> Nueva Clase</button>
						</div>
					</div>
					<table class="table table-bordered table-hover mt-1" id="table_clases">
						<thead>
							<th>Código</th>
							<th>Descripción</th>
							<th></th>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="popup_editar" style="display: none;">
			<div class="content-popup">
				<div class="close"><a href="#" id="close_editar">X</a></div>
				<div>
					<h4 id="titulo_detalle">Detalle de Venta</h4>
					<div class="box box-primary" style="margin-bottom: 10px;">
						<div class="form-row" style="overflow: hidden;">
							<div class="col-md-6">
								<label>Codigo</label>
								<input type="text" id="codigo" class="form-control rounded-pill" name="">
							</div>
							<div class="col-md-4" hidden id="formu2">
								<label>Clase</label>
								<select id="clase" class="form-control rounded-pill">

								</select>
							</div>
							<div class="col-md-6">
								<label>Descripción</label>
								<input type="text" class="form-control rounded-pill" id="descripcion" name="">
							</div>
							<div class="col-md-12 text-center">
								<span class="btn btn-danger rounded-pill" onclick="cerrar_editar()" style="margin-top: 10px; margin-bottom: 10px;">Cerrar</span>
								<button type="submit" class="btn btn-success rounded-pill" id="btn_formulario" style="margin-top: 10px; margin-bottom: 10px;">Actualizar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="popup-overlay"></div>
	</div>
	<script type="text/javascript">
		function cerrar_editar() {
			$('#close_editar').click();
		}

		function agregar_clase() {
			var descripcion = $("#descripcion").val();
			$.get('core/app/view/fam_class.php', {
				parAccion: 'agregar_clase',
				codigo: $("#codigo").val(),
				descripcion: descripcion
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Agregado Correctamente.</strong>' +
							'</div>'
					});
					lista_clases();
					cerrar_editar();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function agregar_subclase() {
			var descripcion = $("#descripcion").val();
			var codigo = $("#codigo").val();
			var clase = $("#clase").val();
			$.get('core/app/view/fam_class.php', {
				parAccion: 'agregar_subclase',
				descripcion: descripcion,
				codigo: codigo,
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Agregado Correctamente.</strong>' +
							'</div>'
					});
					lista_subclases();
					cerrar_editar();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function agregar_familia() {
			var codigo = $("#codigo").val();
			var descripcion = $("#descripcion").val();
			$.get('core/app/view/fam_class.php', {
				parAccion: 'agregar_familia',
				codigo: codigo,
				descripcion: descripcion
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					cerrar_editar();
					lista_familias();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function formulario(tipo) {
			$("#descripcion").val("");
			$("#codigo").val("");
			$("#btn_formulario").text('Agregar');
			if (tipo == 'clase') {
				$("#formu2").attr('hidden', true);
				$("#titulo_detalle").text('Agregar Clase');
				$("#btn_formulario").attr('onclick', 'agregar_clase();');
			} else {
				if (tipo == 'familia') {
					$("#formu2").attr('hidden', true);

					$("#titulo_detalle").text('Agregar Familia');
					$("#btn_formulario").attr('onclick', 'agregar_familia();');
				} else {
					if (tipo == 'subclase') {
						//$("#formu2").removeAttr('hidden');
						$("#titulo_detalle").text('Agregar Subclase');
						cbo_clase(0);
						$("#btn_formulario").attr('onclick', 'agregar_subclase();');
					}
				}
			}
			$('#popup_editar').fadeIn('slow');
			$('.popup-overlay').fadeIn('slow');
			$('.popup-overlay').height($(window).height());
			return false;
		}

		function actualizar_clase(id) {
			$.get('core/app/view/fam_class.php', {
				parAccion: 'actualizar_clase',
				id: id,
				codigo: $("#codigo").val(),
				descripcion: $("#descripcion").val()
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Modificado Correctamente.</strong>' +
							'</div>'
					});
					cerrar_editar();
					lista_clases();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function actualizar_subclase(id) {
			$.get('core/app/view/fam_class.php', {
				parAccion: 'actualizar_subclase',
				id: id,
				descripcion: $("#descripcion").val(),
				codigo: $("#codigo").val(),
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Modificado Correctamente.</strong>' +
							'</div>'
					});
					lista_subclases();
					cerrar_editar();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function editar_clase(id) {
			//$("#formu").attr('hidden', true);
			$("#formu").removeAttr('hidden');
			$("#descripcion").val("");
			$("#titulo_detalle").text('Editar Clase');
			$("#btn_formulario").attr('onclick', 'actualizar_clase(' + id + ');');
			$.get('core/app/view/fam_class.php', {
				parAccion: 'detalle_clase',
				id: id
			}, function(data) {
				var obj = JSON.parse(data);
				$("#codigo").val(obj.codigo);
				$("#descripcion").val(obj.descripcion);
			});
			$("#popup_editar").fadeIn('slow');
			$(".popup-overlay").fadeIn('slow');
			$('.popup-overlay').height($(window).height());
			return false;
		}

		function editar_subclase(id) {
			$("#descripcion").val("");
			$("#titulo_detalle").text('Editar Subclase');
			$("#formu").removeAttr('hidden');
			$("#btn_formulario").attr('onclick', 'actualizar_subclase(' + id + ');');
			$.get('core/app/view/fam_class.php', {
				parAccion: 'detalle_subclase',
				id: id
			}, function(data) {
				var obj = JSON.parse(data);
				$("#descripcion").val(obj.descripcion);
				$("#codigo").val(obj.codigo);
				cbo_clase(obj.id_clase);
			});
			$("#popup_editar").fadeIn('slow');
			$(".popup-overlay").fadeIn('slow');
			$('.popup-overlay').height($(window).height());
			return false;
		}

		function actualizar_familia(id) {
			$.get('core/app/view/fam_class.php', {
				parAccion: 'actualizar_familia',
				id: $("#codigo").val(),
				descripcion: $("#descripcion").val(),
				aid: id
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
						message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Modificado Correctamente.</strong>' +
							'</div>'
					});
					cerrar_editar();
					lista_familias();
				} else {
					bootbox.alert({
						message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
							'<strong>Algo ha salido mal.</strong>' +
							'</div>'
					});
				}
			});
		}

		function editar_familia(id) {
			$("#formu").attr('hidden', true);
			$("#codigo").val("");
			$("#descripcion").val("");
			$("#titulo_detalle").text('Editar Familia');
			var famcod = "'" + id + "'";
			$("#btn_formulario").attr('onclick', 'actualizar_familia(' + famcod + ');');
			$.get('core/app/view/fam_class.php', {
				parAccion: 'detalle_familia',
				id: id
			}, function(data) {
				var obj = JSON.parse(data);
				$("#codigo").val(obj.codigo);
				$("#descripcion").val(obj.descripcion);
			});
			$("#popup_editar").fadeIn('slow');
			$(".popup-overlay").fadeIn('slow');
			$('.popup-overlay').height($(window).height());
			return false;
		}

		function cbo_clase(id) {
			$("#clase").empty();
			$.get('core/app/view/insumos.php', {
				parAccion: 'combo_clase'
			}, function(data) {
				var obj = JSON.parse(data);
				$("#clase").append('<option value="0">SELECCIONA ...</option>');
				$.each(obj.Records, function(index, val) {
					if (val.id == id) {
						$("#clase").append('<option value="' + val.id + '" selected>' + val.descripcion + '</option>')
					} else {
						$("#clase").append('<option value="' + val.id + '">' + val.descripcion + '</option>')
					}

				});
			});
		}

		function eliminar_clase(id, codigo) {
			bootbox.confirm({
				message: "¿Seguro de Eliminar esta Clase?",
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
						//alert("YES");
						$.get('core/app/view/fam_class.php', {
							parAccion: 'eliminar_clase',
							id: id,
							codigo: codigo
						}, function(data) {
							var obj = JSON.parse(data);
							if (obj.Result == 'OK') {
								lista_clases();
								cerrar_editar();
							} else {
								if (obj.Result == 'ERROR') {
									bootbox.alert({
										message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
											'<strong>Esta Clase esta siendo usada.</strong>' +
											'</div>'
									});
								} else {
									bootbox.alert({
										message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
											'<strong>Ago ha salido mal.</strong>' +
											'</div>'
									});
								}
							}
						});
					} else {}
				}
			});
		}

		function eliminar_subclase(id) {
			bootbox.confirm({
				message: "¿Seguro de Eliminar esta Subclase?",
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
						//alert("YES");
						$.get('core/app/view/fam_class.php', {
							parAccion: 'eliminar_subclase',
							id: id
						}, function(data) {
							var obj = JSON.parse(data);
							if (obj.Result == 'OK') {
								lista_subclases();
								cerrar_editar();
							} else {
								if (obj.Result == 'ERROR') {
									bootbox.alert({
										message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
											'<strong>Esta Subclase esta siendo usada.</strong>' +
											'</div>'
									});
								} else {
									bootbox.alert({
										message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
											'<strong>Ago ha salido mal.</strong>' +
											'</div>'
									});
								}
							}
						});
					} else {}
				}
			});
		}

		function eliminar_familia(id) {
			bootbox.confirm({
				message: "¿Seguro de Eliminar esta Familia?",
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
						//alert("YES");
						$.get('core/app/view/fam_class.php', {
							parAccion: 'eliminar_familia',
							id: id
						}, function(data) {
							var obj = JSON.parse(data);
							if (obj.Result == 'OK') {
								cerrar_editar();
								lista_familias();
							} else {
								if (obj.Result == 'ERROR') {
									bootbox.alert({
										message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
											'<strong>Esta Familia esta siendo usada.</strong>' +
											'</div>'
									});
								} else {
									bootbox.alert({
										message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
											'<strong>Ago ha salido mal.</strong>' +
											'</div>'
									});
								}
							}
						});
					} else {}
				}
			});
		}

		function lista_familias() {
			$("#table_familias").find('tbody').empty();
			$.get('core/app/view/fam_class.php', {
				parAccion: 'lista_familias'
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					var famcod = "'" + val.codigo + "'";
					$("#table_familias").find('tbody').append('<tr><td>' + val.codigo + '</td><td>' + val.descripcion + '</td><td><span class="btn-sm btn btn-outline-warning rounded-pill" onclick="editar_familia(' + famcod + ');"><i class="fa fa-pencil"></i></span> <span class="btn-sm btn btn-outline-danger rounded-pill" onclick="eliminar_familia(' + famcod + ');"><i class="fa fa-trash"></i></span></td></tr>');
				});
			});
		}

		function lista_subclases() {
			$("#table_subclases").find('tbody').empty();
			$.get('core/app/view/fam_class.php', {
				parAccion: 'lista_subclases'
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					$("#table_subclases").find('tbody').append('<tr><td>' + val.codigo + '</td><td>' + val.descripcion + '</td><td><span class="btn-sm btn btn-outline-warning rounded-pill" onclick="editar_subclase(' + val.id + ');"><i class="fa fa-pencil"></i></span> <span class="btn-sm btn btn-outline-danger rounded-pill" onclick="eliminar_subclase(' + val.id + ');"><i class="fa fa-trash"></i></span></td></tr>');
				});
			});
		}

		function lista_clases() {
			$("#table_clases").find('tbody').empty();
			$.get('core/app/view/fam_class.php', {
				parAccion: 'lista_clases'
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					var clasecod = "'" + val.codigo + "'";
					$("#table_clases").find('tbody').append('<tr><td>' + val.codigo + '</td><td>' + val.descripcion + '</td><td><span class="btn-sm btn btn-outline-warning rounded-pill" onclick="editar_clase(' + val.id + ');"><i class="fa fa-pencil"></i></span> <span class="btn-sm btn btn-outline-danger rounded-pill" onclick="eliminar_clase(' + val.id + ',' + clasecod + ');"><i class="fa fa-trash"></i></span></td></tr>');
				});
			});
		}
		$(document).ready(function() {
			lista_familias();
			lista_clases();
			lista_subclases();
			$('#close_editar').on('click', function() {
				//limpiar_formulario();
				$('#popup_editar').fadeOut('slow');
				$('.popup-overlay').fadeOut('slow');
				return false;
				flag = false;
			});


			var contenido_fila;
			var coincidencias;
			var exp;
			var codigoAscci;
			$("#filtro_rapido_familia").keyup(function(event) {
				if (!checkTeclaDel(event, "table_familias", "filtro_rapido_familia")) {
					if ($(this).val().length >= 2) {
						filtrar($(this).val(), "table_familias");
					}
				}
			});



			$("#filtro_rapido_subclase").keyup(function(event) {
				if (!checkTeclaDel(event, "table_subclases", "filtro_rapido_subclase")) {
					if ($(this).val().length >= 2) {
						filtrar($(this).val(), "table_subclases");
					}
				}
			});

			$("#filtro_rapido_clase").keyup(function(event) {
				if (!checkTeclaDel(event, "table_clases", "filtro_rapido_clase")) {
					if ($(this).val().length >= 2) {
						filtrar($(this).val(), "table_clases");
					}
				}
			});

			function filtrar(cadena, table_name) {
				$("#" + table_name + " tbody tr").each(function() {
					$(this).removeClass('oculta');
					contenido_fila = $(this).find('td:eq(1)').html();
					exp = new RegExp(cadena, 'gi');
					coincidencias = contenido_fila.match(exp);
					if (coincidencias != null) {
						$(this).addClass('resaltar');
					} else {
						$(this).addClass('oculta');
						$(this).removeClass('resaltar');
					}
				});
			}

			function mostrarFilas(table_name) {
				$("#" + table_name + " tbody tr").each(function() {
					$(this).removeClass('oculta resaltar');
				});
			}

			function checkTeclaDel(e, table_name, input_name) {
				codigoAscci = (e.keyCode ? e.keyCode : e.which);
				if (codigoAscci == 8) {
					if ($("#" + input_name).val().length >= 2) {
						filtrar($("#" + input_name).val(), table_name);
					} else {
						mostrarFilas(table_name);
					}
					return true;
				} else {
					return false;
				}
			}
		});
	</script>
</section>