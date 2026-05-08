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

	.mt-2 {
		margin-top: 1rem !important;
	}

	.mt-3 {
		margin-top: 1.5rem !important;
	}

	.mb-3 {
		margin-bottom: 1rem !important;
	}

	.mb-1 {
		margin-bottom: .5rem !important;
	}

	.w-100 {
		width: 100% !important;
	}

	.mt-3 {
		margin-top: 1rem !important;
	}

	.mr-1 {
		margin-right: .5rem !important;
	}

	.ml-1 {
		margin-left: .5rem !important;
	}

	.ml-2 {
		margin-left: 1rem !important;
	}

	/*.form-row{
		margin-top: 1rem !important;
	}*/
	.border-danger {
		border-color: #dc3545 !important;
	}

	.card {
		position: relative;
		display: -ms-flexbox;
		display: flex;
		-ms-flex-direction: column;
		flex-direction: column;
		min-width: 0;
		word-wrap: break-word;
		background-color: #fff;
		background-clip: border-box;
		border: 1px solid rgba(0, 0, 0, .125);
		border-top-color: rgba(0, 0, 0, 0.125);
		border-right-color: rgba(0, 0, 0, 0.125);
		border-bottom-color: rgba(0, 0, 0, 0.125);
		border-left-color: rgba(0, 0, 0, 0.125);
		border-radius: .25rem;
	}

	.card-header:first-child {
		border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
	}

	.card-header {
		padding: .75rem 1.25rem;
		margin-bottom: 0;
		background-color: rgba(0, 0, 0, .03);
		border-bottom: 1px solid rgba(0, 0, 0, .125);
	}

	.text-danger {
		color: #dc3545 !important;
	}

	.card-body {
		-ms-flex: 1 1 auto;
		flex: 1 1 auto;
		padding: 1.25rem;
	}

	.card-title {
		margin-bottom: .75rem;
	}

	.card-text:last-child {
		margin-bottom: 0;
	}

	.border-warning {
		border-color: #ffc107 !important;
	}

	.text-warning {
		color: #ffc107 !important;
	}

	.border-success {
		border-color: #28a745 !important;
	}

	.text-success {
		color: #28a745 !important;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3><i class="glyphicon glyphicon-stats"></i> Datos del Personal</h3>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-header">
					<h4>Colaboradores</h4>
					<div>
						<div class="col-md-4">
							<select class="form-control br-4 rounded-pill" id="id_mes_cumpleanos">
								<option value="0">--TODOS--</option>
								<option value="1">Enero</option>
								<option value="2">Febrero</option>
								<option value="3">Marzo</option>
								<option value="4">Abril</option>
								<option value="5">Mayo</option>
								<option value="6">Junio</option>
								<option value="7">Julio</option>
								<option value="8">Agosto</option>
								<option value="9">Septiembre</option>
								<option value="10">Octubre</option>
								<option value="11">Noviembre</option>
								<option value="12">Diciembre</option>
							</select>
						</div>
						<div class="col-md-4">
							<select class="form-control br-4 rounded-pill" id="_linea_">
								<option value="0">TODAS</option>
								<option value="1">Línea 1</option>
								<option value="2">Línea 2</option>
								<option value="3">Línea 3</option>
								<option value="4">Línea 4</option>
								<option value="5">Línea 5</option>
								<option value="6">Línea 6</option>
								<option value="7">Línea 7</option>
								<option value="8">Línea 8</option>
								<option value="9">Línea 9</option>
								<option value="10">Inactivo</option>
							</select>
						</div>
						<div class="col-md-4">
							<a id="enlace_descargar_cumpleanos" class="btn btn-primary pull-left rounded-pill" href="core/app/view/pdf-cumpleaños.php">
								<i class="glyphicon glyphicon-gift"></i> Descargar Cumpleaños
							</a>
						</div>
					</div>

				</div>
				<div class="box-body">
					<div class="col-md-10">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#home">DATOS PERSONALES</a></li>
							<li><a data-toggle="tab" href="#menu1">DATOS</a></li>
						</ul>
						<div class="tab-content">
							<div id="home" class="tab-pane fade in active">
								<h3 class="text-center">PERUVIAN DRESS TPX S.A.C.</h3>
								<div class="row">
									<div class="col-md-12">
										<h4 class="bold">A. IDENTIFICACIÓN DEL PRODUCTO</h4>
									</div>
									<div class="row">
										<div class="col-md-6 row">
											<div class="row">
												<div class="col-md-6 text-right">
													<h5 class="">DNI: </h5>
												</div>
												<div class="col-md-6">
													<input type="hidden" id="id_oculto">
													<div class="form-group">
														<div class="input-group">
															<input class="form-control br-4 rounded-pill-left" type="text" id="dni" name="" placeholder="DNI">
															<div class="input-group-addon">
																<span id="btn_dni" class="btn btn-success btn-xs" title="Adjuntar DNI" data-toggle="modal" data-target="#modalDNI" style="padding: 0px 6px 0px 5px;">
																	<i class="glyphicon glyphicon-credit-card"></i>
																</span>
															</div>
														</div>
														<div id="archivo_dni"></div>
													</div>
												</div>
											</div>
											<div class="row mt-2">
												<div class="col-md-6 text-right">
													<h5 class="">Nombres: </h5>
												</div>
												<div class="col-md-6">
													<input class="form-control br-4 rounded-pill" type="text" id="nombres" name="" placeholder="Nombres">
												</div>
											</div>
											<div class="row mt-2">
												<div class="col-md-6 text-right">
													<h5 class="">Apellido Paterno: </h5>
												</div>
												<div class="col-md-6">
													<input class="form-control br-4 rounded-pill" type="text" id="apellido_paterno" name="" placeholder="Apellido Paterno">
												</div>
											</div>
											<div class="row mt-2">
												<div class="col-md-6 text-right">
													<h5 class="">Apellido Materno: </h5>
												</div>
												<div class="col-md-6">
													<input class="form-control br-4 rounded-pill" type="text" id="apellido_materno" name="" placeholder="Apellido Materno">
												</div>
											</div>

										</div>
										<div class="col-md-6 text-center">
											<label class="btn btn-sm btn-outline-danger rounded-pill" style="cursor: pointer;" for="file1">
												<input type="file" id="file1" name="file1" style="display: none;">
												<span>Cargar foto</span>
											</label>
											<img src="" id="imagen_usuario" style="border-radius: 4px; box-shadow: 0px 2px 2px #333; width: 30%;">
										</div>
									</div>
									<div class="col-md-12 row">
										<div class="col-md-6">
											<div class="row mt-2">
												<div class="col-md-6 text-right">
													<h5 class="">Puesto: </h5>
												</div>
												<div class="col-md-6">
													<select class="form-control br-4 rounded-pill" id="id_cargo">
														<option value="0">SELECCIONA...</option>
													</select>
												</div>
											</div>
											<div class="row mt-2">
												<div class="col-md-6 text-right">
													<h5 class="">Certificado Medico: </h5>
												</div>
												<div class="col-md-6">
													<div class="w-100" id="div_certificado_medico"></div>
												</div>
											</div>

											<div class="row mt-2">
												<div class="col-md-6 text-right">
													<h5 class="">Contrato: </h5>
												</div>
												<div class="col-md-6">
													<div class="w-100" id="div_contratos"></div>
												</div>
											</div>

											<div class="row mt-2">
												<div class="col-md-6 text-right">
													<h5 class="">Recomendaciones SST: </h5>
												</div>
												<div class="col-md-6">
													<div class="w-100" id="div_sst"></div>
												</div>
											</div>

											<div class="row mt-2">
												<div class="col-md-6 text-right">
													<h5 class="">Lista Verificación de Competencias: </h5>
												</div>
												<div class="col-md-6">
													<div class="w-100" id="div_competencias"></div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row mt-2">
												<div class="col-md-6 text-right">
													<h5 class="">Línea: </h5>
												</div>
												<div class="col-md-6">
													<select class="form-control br-4 rounded-pill" id="linea">
														<option value="1">Línea 1</option>
														<option value="2">Línea 2</option>
														<option value="3">Línea 3</option>
														<option value="4">Línea 4</option>
														<option value="5">Línea 5</option>
														<option value="6">Línea 6</option>
														<option value="7">Línea 7</option>
														<option value="8">Línea 8</option>
														<option value="9">Línea 9</option>
														<option value="10">Inactivo</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="menu1" class="tab-pane fade">
								<div class="row" style="margin-top: 1.5rem;">
									<div class="col-md-4">
										<div class="form-group">
											<div class="col-md-12">
												<label>Celular</label>
												<input id="celular" class="form-control rounded-pill" name="celular" type="text" />
											</div>
											<div class="col-md-12">
												<label>Fec. Nacimiento</label>
												<input id="fecha_nacimiento" class="form-control datepicker rounded-pill" name="fecha_nacimiento" type="text" />
											</div>
											<div class="col-md-12">
												<label>Lugar de Nacimiento</label>
												<input id="lugar_nacimiento" class="form-control rounded-pill" name="lugar_nacimiento" type="text" />
											</div>
											<div class="col-md-12">
												<label>Estado Civil</label>
												<select name="id_estado_civil" id="id_estado_civil" class="form-control rounded-pill">

												</select>
											</div>
											<div class="col-md-12">
												<label>Brevette</label>
												<input type="text" class="form-control rounded-pill" id="brevette" name="brevette">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class="col-md-12">
												<label>Area</label>
												<select name="id_proceso" id="id_proceso" class="form-control rounded-pill">
													<!--<option value="1">Administración</option>
				                                	<option value="2">Logística</option>
				                                	<option value="3">Almacen</option>
				                                	<option value="4">Confección</option>
				                                	<option value="5">Corte</option>
				                                	<option value="6">Habilitado</option>
				                                	<option value="7">Bordado</option>
				                                	<option value="8">Mantenimiento</option>
				                                	<option value="9">Vigilancia</option>-->
												</select>
											</div>
											<div class="col-md-12">
												<label>Teléfono de Emergencia</label>
												<input type="text" class="form-control rounded-pill" id="telefono_emergencia" name="telefono_emergencia">
											</div>
											<div class="col-md-12">
												<label>Sueldo</label>
												<input id="sueldo" class="form-control rounded-pill" name="sueldo" type="text" />
											</div>
											<div class="col-md-12">
												<label>Sistema de Pensiones</label>
												<select name="sistema_pension" id="sistema_pension" class="form-control rounded-pill">
													<option value="0">SELECCIONA...</option>
												</select>
											</div>
											<div class="col-md-12">
												<label>Entidad de Pensiones</label>
												<select name="id_entidad_pension" id="id_entidad_pension" class="form-control rounded-pill">
													<option value="0">SELECCIONA...</option>
												</select>
											</div>
											<div class="col-md-12 mt-1">
												<label>Código</label>
												<input type="text" id="codigo" name="codigo" class="form-control rounded-pill">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class="col-md-12">
												<label>Género</label>
												<select name="genero" id="genero" class="form-control rounded-pill">
													<option value="M">Masculino</option>
													<option value="F">Femenino</option>
												</select>
											</div>
											<div class="col-md-12">
												<label>Estado Laboral</label>
												<select name="estado_laboral" id="estado_laboral" class="form-control rounded-pill">
													<option value="1">Contratado</option>
													<option value="2">Labora s/Contrado</option>
													<option value="3">Practicante</option>
													<option value="4">Contrato Vencido</option>
													<option value="5">Renuncia</option>
													<option value="6">Despido</option>
												</select>
											</div>
											<div class="col-md-12">
												<label>Fecha Ingreso</label>
												<input id="fecha_ingreso" class="form-control rounded-pill datepicker" name="fecha_ingreso" type="text" />
											</div>
											<div class="col-md-12">
												<label>Fecha Salida</label>
												<input id="fecha_salida" class="form-control rounded-pill datepicker" name="fecha_salida" type="text" />
											</div>
											<div class="col-md-6 mt-1">
												<label>Asegurado</label><br>
												<input type="checkbox" id="asegurado" name="asegurado">
											</div>
											<div class="col-md-6 mt-1">
												<label>Activo</label><br>
												<input type="checkbox" id="estado" name="estado">
											</div>
											<div class="col-md-12">
												<label>Correo</label>
												<input id="correo" class="form-control rounded-pill" name="correo" type="text" />
											</div>
										</div>
									</div>
									<div class="col-12">
										<div class="col-md-12">
											<label>Dirección</label>
											<input id="direccion" class="form-control rounded-pill" name="direccion" type="text" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="w-100" style="margin-top: 10px;">
							<button class="btn btn-warning w-100 rounded-pill" data-toggle="modal" data-target="#formulario">Buscar Registro</button>
						</div>
						<div class="w-100 text-center" style="margin-top: 10px;">
							<div>
								<button style="margin-left: 5px" class="btn btn-outline-info rounded-pill" onclick="previo();"><i class="glyphicon glyphicon-triangle-left"></i></button>
								<button class="btn btn-outline-info rounded-pill" onclick="siguiente();"><i class="glyphicon glyphicon-triangle-right"></i></button>
							</div>
						</div>
						<div class="w-100" style="margin-top: 10px;">
							<button class="btn btn-primary w-100 rounded-pill" onclick="guardar();" id="btn_rehusar">Guardar Registro</button>
						</div>
						<div class="w-100" style="margin-top: 10px;">
							<button class="btn btn-success w-100 rounded-pill" onclick="limpiar_formulario();">Agregar Registro</button>
						</div>
						<div class="w-100" style="margin-top: 10px;">
							<button class="btn btn-danger w-100 rounded-pill" id="btn_eliminar_">Eliminar Registro</button>
						</div>
						<div class="w-100" style="margin-top: 10px;">
							<a class="btn btn-warning w-100 rounded-pill" id="btn_imprimir">Imprimir Registro</a>
						</div>
						<div class="w-100" style="margin-top: 10px; text-align: center;">
							<span id="span_current"></span> <span id="span_total"></span>
						</div>
					</div>
					<div class="col-md-12 mt-3">
						<a class="btn btn-outline-secondary rounded-pill" id="btn_familiares">Familiares</a>
						<a href="" class="btn btn-outline-secondary rounded-pill" id="btn_formacion">Formación Académica</a>
						<a href="" class="btn btn-outline-secondary rounded-pill" id="btn_experiencia">Experiencia Laboral</a>
						<a href="" class="btn btn-outline-secondary rounded-pill" id="btn_habilidad">Habilidades</a>
						<a href="" class="btn btn-outline-secondary rounded-pill" id="btn_capacitacion">Capacitaciones</a>
						<a href="" class="btn btn-outline-secondary rounded-pill" id="btn_vacaciones">Vacaciones</a>
						<a href="" class="btn btn-outline-secondary rounded-pill" id="btn_certificado_medico">Certificado Medico</a>
						<a href="" class="btn btn-outline-secondary rounded-pill" id="btn_contrato">Contrato</a>
						<a href="" class="btn btn-outline-secondary rounded-pill" id="btn_sst">Recomendaciones SST</a>
						<a href="" class="btn btn-outline-secondary rounded-pill" id="btn_competencias">Verificación Competencias</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!----------------------------------------------------------------------->
	<div class="modal fade" id="formulario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document" style="width: 40%;">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="exampleModalLabel">Buscar Colaborador</h3>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group" style="width: 100%;">
						<label>Dni:</label>
						<input type="text" id="dni_buscar" class="form-control">
					</div>
					<div class="form-group" style="width: 100%;">
						<label>Nombre:</label>
						<input type="text" id="nombre_buscar" class="form-control">
					</div>
					<div class="form-group" style="width: 100%;">
						<label>Apellido:</label>
						<input type="text" id="apellido_buscar" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success pull-right ml-2" id="btn_buscar" onclick="buscar_dni();">Buscar</button>
					<span class="btn btn-danger" type="button" data-dismiss="modal" id="cerrar_formulario_docente">
						Cancelar
					</span>
				</div>
			</div>
		</div>
	</div>
	<!----------------------------------------------------------------------->
</section>
<div class="modal" tabindex="-1" role="dialog" id="modalDNI">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cargar Archivo DNI
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label for="file1_dni" class="btn bg-maroon rounded-pill"><i class="glyphicon glyphicon-camera"></i> Seleccionar Archivo</label>
						<input type="file" name="file1_dni" id="file1_dni" style="display: none;">
					</div>
					<div class="col-md-12" style='margin-top: 1rem !important;'>
						<span id='fileDNI'></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success rounded-pill" id="btn-subir-archivo-dni">Subir Archivo</button>
				<button type="button" class="btn btn-danger rounded-pill" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="exampleModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cargar Archivo
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label for="file1_1" class="btn bg-maroon rounded-pill"><i class="glyphicon glyphicon-camera"></i> Seleccionar Archivo</label>
						<input type="file" name="file1_1" id="file1_1" style="display: none;">
					</div>
					<div class="col-md-12" style='margin-top: 1rem !important;'>
						<span id='fileList'></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success rounded-pill" id="btn-subir-archivo">Subir Archivo</button>
				<button type="button" class="btn btn-danger rounded-pill" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="exampleModal2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cargar Contrato
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label for="file1_2" class="btn bg-maroon rounded-pill"><i class="glyphicon glyphicon-camera"></i> Seleccionar Archivo</label>
						<input type="file" name="file1_2" id="file1_2" style="display: none;">
					</div>
					<div class="col-md-12" style='margin-top: 1rem !important;'>
						<span id='fileList2'></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success rounded-pill" id="btn-subir-contrato">Subir Archivo</button>
				<button type="button" class="btn btn-danger rounded-pill" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="exampleModal3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cargar Recomendación SST
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label for="file1_3" class="btn bg-maroon rounded-pill"><i class="glyphicon glyphicon-camera"></i> Seleccionar Archivo</label>
						<input type="file" name="file1_3" id="file1_3" style="display: none;">
					</div>
					<div class="col-md-12" style='margin-top: 1rem !important;'>
						<span id='fileList3'></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success rounded-pill" id="btn-subir-sst">Subir Archivo</button>
				<button type="button" class="btn btn-danger rounded-pill" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="exampleModal4">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Lista de Verificación de Competencias
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label for="file1_4" class="btn bg-maroon rounded-pill"><i class="glyphicon glyphicon-camera"></i> Seleccionar Archivo</label>
						<input type="file" name="file1_4" id="file1_4" style="display: none;">
					</div>
					<div class="col-md-12" style='margin-top: 1rem !important;'>
						<span id='fileList4'></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success rounded-pill" id="btn-subir-competencias">Subir Archivo</button>
				<button type="button" class="btn btn-danger rounded-pill" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var current = -1;
	var id_vigente = 0;
	$(document).ready(function() {
		$("#id_mes_cumpleanos").on("change", function() {
			if ($("#id_mes_cumpleanos").val() == 0 && $("#_linea_").val() == 0) {
				$("#enlace_descargar_cumpleanos").attr("href", "core/app/view/pdf-cumpleaños.php");
			} else {
				if ($("#_linea_").val() == 0) {
					$("#enlace_descargar_cumpleanos").attr("href", "core/app/view/pdf-cumpleaños.php?mes=" + $("#id_mes_cumpleanos").val());
				} else {
					$("#enlace_descargar_cumpleanos").attr("href", "core/app/view/pdf-cumpleaños.php?mes=" + $("#id_mes_cumpleanos").val() + "&linea=" + $("#_linea_").val());
				}
			}
		});

		$("#_linea_").on("change", function() {
			if ($("#id_mes_cumpleanos").val() == 0 && $("#_linea_").val() == 0) {
				$("#enlace_descargar_cumpleanos").attr("href", "core/app/view/pdf-cumpleaños.php");
			} else {
				if ($("#_linea_").val() == 0) {
					$("#enlace_descargar_cumpleanos").attr("href", "core/app/view/pdf-cumpleaños.php?mes=" + $("#id_mes_cumpleanos").val());
				} else {
					$("#enlace_descargar_cumpleanos").attr("href", "core/app/view/pdf-cumpleaños.php?mes=" + $("#id_mes_cumpleanos").val() + "&linea=" + $("#_linea_").val());
				}
			}
		});

		get_total();
		get_all_colaboradores();
		llenar_estado_civil();
		llenar_sistema_pension();
		llenar_cargos();
		llenar_areas();


		<?php
		if (isset($_GET['id_col'])) {
			echo "traer_el_colaborador(" . $_GET['id_col'] . ");";
		} else {
			echo 'siguiente();';
		}
		?>

		$("#sistema_pension").on("change", function() {
			llenar_entidades_pension($("#sistema_pension").val(), 0);
		});

		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#imagen_usuario").attr("src", e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		$("#file1").change(function() {
			readURL(this);
		});
		var fileList = document.getElementById("file1_1");
		fileList.addEventListener("change", function(e) {
			var list = "";
			for (var i = 0; i < this.files.length; i++) {
				list += "<div class='col-md-12'><span class='badge badge-info' style='font-size: 13px;'>" + this.files[i].name + "</span></div>"
			}

			$("#fileList").append(list);
		}, false);

		var fileList = document.getElementById("file1_2");
		fileList.addEventListener("change", function(e) {
			var list = "";
			for (var i = 0; i < this.files.length; i++) {
				list += "<div class='col-md-12'><span class='badge badge-info' style='font-size: 13px;'>" + this.files[i].name + "</span></div>"
			}

			$("#fileList2").append(list);
		}, false);

		var fileList = document.getElementById("file1_3");
		fileList.addEventListener("change", function(e) {
			var list = "";
			for (var i = 0; i < this.files.length; i++) {
				list += "<div class='col-md-12'><span class='badge badge-info' style='font-size: 13px;'>" + this.files[i].name + "</span></div>"
			}

			$("#fileList3").append(list);
		}, false);

		var fileList = document.getElementById("file1_4");
		fileList.addEventListener("change", function(e) {
			var list = "";
			for (var i = 0; i < this.files.length; i++) {
				list += "<div class='col-md-12'><span class='badge badge-info' style='font-size: 13px;'>" + this.files[i].name + "</span></div>"
			}

			$("#fileList4").append(list);
		}, false);

		var fileDni = document.getElementById("file1_dni");
		fileDni.addEventListener("change", function(e) {
			var list = "";
			for (var i = 0; i < this.files.length; i++) {
				list += "<div class='col-md-12'><span class='badge badge-info' style='font-size: 13px;'>" + this.files[i].name + "</span></div>"
			}

			$("#fileDNI").append(list);
		}, false);
	});

	function preparar_subir_archivo(id) {
		$("#btn-subir-archivo").attr("onclick", "cargar_archivo(" + id + ");");
		$("#file1_1").val('');
		$("#fileList").empty();
	}

	function preparar_subir_dni(id) {
		$("#btn-subir-archivo-dni").attr("onclick", "cargar_archivo_dni(" + id + ");");
		$("#file1_dni").val('');
		$("#fileDNI").empty();
	}

	function preparar_subir_contrato(id) {
		$("#btn-subir-contrato").attr("onclick", "cargar_contrato(" + id + ");");
		$("#file1_2").val('');
		$("#fileList2").empty();
	}

	function preparar_subir_sst(id) {
		$("#btn-subir-sst").attr("onclick", "cargar_sst(" + id + ");");
		$("#file1_3").val('');
		$("#fileList3").empty();
	}

	function preparar_subir_competencias(id) {
		$("#btn-subir-competencias").attr("onclick", "cargar_competencias(" + id + ");");
		$("#file1_4").val('');
		$("#fileList4").empty();
	}

	function cargar_archivo(id) {
		var formData = new FormData();
		var aux = 0;
		var archivo = $('input[name="file1_1"]')[0].files;
		if ($('input[name="file1_1"]').val() !== '') {
			if (archivo.length > 0) {
				let dialog = bootbox.dialog({
					message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Cargando y Procesando Archivo, Espere Por Favor...</p>',
					closeButton: false
				});
				formData.append('archivo', archivo[0]);
				formData.append('id', id);
				$.ajax({
					url: "core/app/view/colaborador.php?parAccion=cargar_archivo_certificado_medico",
					type: "POST",
					data: formData,
					dataType: "json",
					processData: false,
					contentType: false,
					success: function(data) {
						dialog.modal('hide');
						traer_el_colaborador(id);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {

					}
				});
			} else {
				alertas('error', 'Debe seleccionar un archivo excel.', '');
			}
		} else {
			alertas('error', 'Debe seleccionar un archivo excel.', '');
		}
	}

	function cargar_archivo_dni(id) {
		var formData = new FormData();
		var aux = 0;
		var archivo = $('input[name="file1_dni"]')[0].files;
		if ($('input[name="file1_dni"]').val() !== '') {
			if (archivo.length > 0) {
				let dialog = bootbox.dialog({
					message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Cargando y Procesando Archivo, Espere Por Favor...</p>',
					closeButton: false
				});
				formData.append('archivo', archivo[0]);
				formData.append('id', id);
				$.ajax({
					url: "core/app/view/colaborador.php?parAccion=cargar_archivo_dni",
					type: "POST",
					data: formData,
					dataType: "json",
					processData: false,
					contentType: false,
					success: function(data) {
						dialog.modal('hide');
						traer_el_colaborador(id);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {

					}
				});
			} else {
				alertas('error', 'Debe seleccionar un archivo excel.', '');
			}
		} else {
			alertas('error', 'Debe seleccionar un archivo excel.', '');
		}
	}

	function cargar_contrato(id) {
		var formData = new FormData();
		var aux = 0;
		var archivo = $('input[name="file1_2"]')[0].files;
		if ($('input[name="file1_2"]').val() !== '') {
			if (archivo.length > 0) {
				let dialog = bootbox.dialog({
					message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Cargando y Procesando Archivo, Espere Por Favor...</p>',
					closeButton: false
				});
				formData.append('archivo', archivo[0]);
				formData.append('id', id);
				$.ajax({
					url: "core/app/view/colaborador.php?parAccion=cargar_contrato",
					type: "POST",
					data: formData,
					dataType: "json",
					processData: false,
					contentType: false,
					success: function(data) {
						dialog.modal('hide');
						traer_el_colaborador(id);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {

					}
				});
			} else {
				alertas('error', 'Debe seleccionar un archivo excel.', '');
			}
		} else {
			alertas('error', 'Debe seleccionar un archivo excel.', '');
		}
	}

	function cargar_sst(id) {
		var formData = new FormData();
		var aux = 0;
		var archivo = $('input[name="file1_3"]')[0].files;
		if ($('input[name="file1_3"]').val() !== '') {
			if (archivo.length > 0) {
				let dialog = bootbox.dialog({
					message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Cargando y Procesando Archivo, Espere Por Favor...</p>',
					closeButton: false
				});
				formData.append('archivo', archivo[0]);
				formData.append('id', id);
				$.ajax({
					url: "core/app/view/colaborador.php?parAccion=cargar_sst",
					type: "POST",
					data: formData,
					dataType: "json",
					processData: false,
					contentType: false,
					success: function(data) {
						dialog.modal('hide');
						traer_el_colaborador(id);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {

					}
				});
			} else {
				alertas('error', 'Debe seleccionar un archivo excel.', '');
			}
		} else {
			alertas('error', 'Debe seleccionar un archivo excel.', '');
		}
	}

	function cargar_competencias(id) {
		var formData = new FormData();
		var aux = 0;
		var archivo = $('input[name="file1_4"]')[0].files;
		if ($('input[name="file1_4"]').val() !== '') {
			if (archivo.length > 0) {
				let dialog = bootbox.dialog({
					message: '<p class="text-center mb-0"><i class="fas fa-spin fa-cog"></i> Cargando y Procesando Archivo, Espere Por Favor...</p>',
					closeButton: false
				});
				formData.append('archivo', archivo[0]);
				formData.append('id', id);
				$.ajax({
					url: "core/app/view/colaborador.php?parAccion=cargar_competencias",
					type: "POST",
					data: formData,
					dataType: "json",
					processData: false,
					contentType: false,
					success: function(data) {
						dialog.modal('hide');
						traer_el_colaborador(id);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {

					}
				});
			} else {
				alertas('error', 'Debe seleccionar un archivo excel.', '');
			}
		} else {
			alertas('error', 'Debe seleccionar un archivo excel.', '');
		}
	}

	function traer_el_colaborador(id_col) {
		$.post('core/app/view/colaborador.php?parAccion=siguiente_especifico', {
			id: id_col
		}, function(data) {
			var obj = JSON.parse(data);

			id_vigente = obj.id;
			$("#id_oculto").val(obj.id);
			$("#span_current").text(parseInt(current + 1) + " de ");
			$("#btn_familiares").attr("href", "?view=familiares&id_colaborador=" + obj.id);
			$("#btn_formacion").attr("href", "?view=formacion&id_colaborador=" + obj.id);
			$("#btn_experiencia").attr("href", "?view=colaborador_experiencia&id_colaborador=" + obj.id);
			$("#btn_habilidad").attr("href", "?view=habilidades&id_colaborador=" + obj.id);
			$("#btn_capacitacion").attr("href", "?view=capacitaciones&id_colaborador=" + obj.id);
			$("#btn_vacaciones").attr("href", "?view=vacaciones&id_colaborador=" + obj.id);
			$("#btn_certificado_medico").attr("href", "?view=certificado-medico&id_colaborador=" + obj.id);
			$("#btn_dni").attr("onclick", "preparar_subir_dni(" + obj.id + ");");


			$("#btn_contrato").attr("href", "?view=contratos&id_colaborador=" + obj.id);
			$("#btn_sst").attr("href", "?view=recomendaciones_sst&id_colaborador=" + obj.id);
			$("#btn_competencias").attr("href", "?view=verificacion_competencias&id_colaborador=" + obj.id);


			$("#dni").val(obj.dni);
			$("#nombres").val(obj.nombres);
			$("#fecha_nacimiento").val(formato_fecha(obj.fecha_nacimiento));
			$("#lugar_nacimiento").val(obj.lugar_nacimiento);
			$("#id_estado_civil").val(obj.id_estado_civil);
			$("#celular").val(obj.celular);
			$("#direccion").val(obj.direccion);
			$("#correo").val(obj.correo);
			$("#brevette").val(obj.brevette);
			$("#telefono_emergencia").val(obj.telefono_emergencia);

			$("#sistema_pension option[value=" + obj.id_sistema_pension + "]").attr('selected', 'selected');

			$("#sistema_pension").change(llenar_entidades_pension(obj.id_sistema_pension, obj.id_entidad_pension));

			$("#codigo").val(obj.codigo);

			if (obj.asegurado == 1) {
				$("#asegurado").prop('checked', true);
			} else {
				$("#asegurado").prop('checked', false);
			}

			if (obj.estado == 1) {
				$("#estado").attr("checked", true);
			} else {
				$("#estado").attr("checked", false);
			}

			$("#apellido_paterno").val(obj.apellido_paterno);
			$("#apellido_materno").val(obj.apellido_materno);
			$("#archivo_dni").empty();
			if (!$.trim(obj.dni_archivo) == "") {
				$("#archivo_dni").append(`<a href="core/app/view/dni/${obj.dni_archivo}" target="_blank">${$.trim(obj.dni_archivo)}</a>`);
			}

			$("#sueldo").val(obj.sueldo);

			$("#id_proceso").val(obj.proceso).change();

			$("#genero").val(obj.genero).change();

			$("#estado_laboral").val(obj.estado_laboral).change();

			$("#id_cargo option[value=" + obj.id_cargo + "]").prop('selected', 'selected');
			$("#div_certificado_medico").empty();
			$("#div_certificado_medico").append(`<a href="core/app/view/certificado_medico/${obj.certificado_medico}" target="_blank">${$.trim(obj.certificado_medico)}</a>`);

			$("#div_contratos").empty();
			$("#div_contratos").append(`<a href="core/app/view/contratos/${obj.contrato}" target="_blank">${$.trim(obj.contrato)}</a>`);
			$("#div_sst").empty();
			$("#div_sst").append(`<a href="core/app/view/sst/${obj.recomendacion_sst}" target="_blank">${$.trim(obj.recomendacion_sst)}</a>`);

			$("#div_competencias").empty();
			$("#div_competencias").append(`<a href="core/app/view/competencias/${obj.verificacion_competencias}" target="_blank">${$.trim(obj.verificacion_competencias)}</a>`);

			$("#linea").val(obj.linea).change();

			$("#fecha_ingreso").val(formato_fecha(obj.fecha_ingreso));
			$("#fecha_salida").val(obj.fecha_salida);

			$("#imagen_usuario").attr("src", "core/app/view/img-colaboradores/" + obj.foto);

			$("#cerrar_formulario_docente").click();

			$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
			$("#btn_eliminar_").attr('onclick', 'eliminar(' + obj.id + ');');

			$("#btn_imprimir").attr('href', 'core/app/view/pdf-colaborador-data.php?id_c=' + obj.id);
		});
	}

	function adicional() {

		current++;
		$.post('core/app/view/colaborador.php?parAccion=siguiente', {
			current: current
		}, function(data) {
			var obj = JSON.parse(data);

			id_vigente = obj.id;
			$("#span_current").text(parseInt(current + 1) + " de ");
			$("#btn_familiares").attr("href", "?view=familiares&id_colaborador=" + obj.id);
			$("#btn_formacion").attr("href", "?view=formacion&id_colaborador=" + obj.id);
			$("#btn_experiencia").attr("href", "?view=colaborador_experiencia&id_colaborador=" + obj.id);
			$("#btn_habilidad").attr("href", "?view=habilidades&id_colaborador=" + obj.id);
			$("#btn_capacitacion").attr("href", "?view=capacitaciones&id_colaborador=" + obj.id);
			$("#btn_vacaciones").attr("href", "?view=vacaciones&id_colaborador=" + obj.id);
			$("#btn_certificado_medico").attr("href", "?view=certificado-medico&id_colaborador=" + obj.id);
			$("#btn_dni").attr("onclick", "preparar_subir_dni(" + obj.id + ");");

			$("#btn_contrato").attr("href", "?view=contratos&id_colaborador=" + obj.id);
			$("#btn_sst").attr("href", "?view=recomendaciones_sst&id_colaborador=" + obj.id);
			$("#btn_competencias").attr("href", "?view=verificacion_competencias&id_colaborador=" + obj.id);

			$("#dni").val(obj.dni);
			$("#nombres").val(obj.nombres);
			$("#fecha_nacimiento").val(formato_fecha(obj.fecha_nacimiento));
			$("#lugar_nacimiento").val(obj.lugar_nacimiento);
			$("#id_estado_civil").val(obj.id_estado_civil);
			$("#celular").val(obj.celular);
			$("#direccion").val(obj.direccion);
			$("#correo").val(obj.correo);
			$("#brevette").val(obj.brevette);
			$("#telefono_emergencia").val(obj.telefono_emergencia);

			$("#sistema_pension option[value=" + obj.id_sistema_pension + "]").attr('selected', 'selected');

			$("#sistema_pension").change(llenar_entidades_pension(obj.id_sistema_pension, obj.id_entidad_pension));

			$("#codigo").val(obj.codigo);

			if (obj.asegurado == 1) {
				$("#asegurado").prop('checked', true);
			} else {
				$("#asegurado").prop('checked', false);
			}

			if (obj.estado == 1) {
				$("#estado").prop('checked', true);
			} else {
				$("#estado").prop('checked', false);
			}

			$("#apellido_paterno").val(obj.apellido_paterno);
			$("#apellido_materno").val(obj.apellido_materno);
			$("#archivo_dni").empty();
			if (!$.trim(obj.dni_archivo) == "") {
				$("#archivo_dni").append(`<a href="core/app/view/dni/${obj.dni_archivo}" target="_blank">${$.trim(obj.dni_archivo)}</a>`);
			}
			$("#sueldo").val(obj.sueldo);

			$("#id_proceso").val(obj.proceso).change();

			$("#genero").val(obj.genero).change();

			$("#estado_laboral").val(obj.estado_laboral).change();

			$("#id_cargo option[value=" + obj.id_cargo + "]").prop('selected', 'selected');
			$("#div_certificado_medico").empty();
			$("#div_certificado_medico").append(`<a href="core/app/view/certificado_medico/${obj.certificado_medico}" target="_blank">${$.trim(obj.certificado_medico)}</a>`);

			$("#div_contratos").empty();
			$("#div_contratos").append(`<a href="core/app/view/contratos/${obj.contrato}" target="_blank">${$.trim(obj.contrato)}</a>`);
			$("#div_sst").empty();
			$("#div_sst").append(`<a href="core/app/view/sst/${obj.recomendacion_sst}" target="_blank">${$.trim(obj.recomendacion_sst)}</a>`);

			$("#div_competencias").empty();
			$("#div_competencias").append(`<a href="core/app/view/competencias/${obj.verificacion_competencias}" target="_blank">${$.trim(obj.verificacion_competencias)}</a>`);

			$("#linea").val(obj.linea).change();

			$("#fecha_ingreso").val(formato_fecha(obj.fecha_ingreso));
			$("#fecha_salida").val(obj.fecha_salida);

			$("#imagen_usuario").attr("src", "core/app/view/img-colaboradores/" + obj.foto);

			$("#cerrar_formulario_docente").click();

			$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
			$("#btn_eliminar_").attr('onclick', 'eliminar(' + obj.id + ');');

			$("#btn_imprimir").attr('href', 'core/app/view/pdf-colaborador-data.php?id_c=' + obj.id);
		});
	}

	function get_total() {
		$.post('core/app/view/colaborador.php?parAccion=get_total', function(data) {
			var obj = JSON.parse(data);
			$("#span_total").text(obj.total + " Registros.");
		});
	}

	function siguiente() {
		$("#btn_familiares").attr("disabled", true);
		$("#btn_formacion").attr("disabled", true);
		$("#btn_experiencia").attr("disabled", true);
		$("#btn_habilidad").attr("disabled", true);
		$("#btn_capacitacion").attr("disabled", true);
		$("#btn_vacaciones").attr("disabled", true);

		current++;
		$.post('core/app/view/colaborador.php?parAccion=siguiente', {
			current: current
		}, function(data) {
			var obj = JSON.parse(data);

			if (obj == "false" || !obj) {
				current--;
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>no hay mas registros.</strong>' +
						'</div>'
				});
			} else {
				$("#btn_familiares").attr("disabled", false);
				$("#btn_formacion").attr("disabled", false);
				$("#btn_experiencia").attr("disabled", false);
				$("#btn_habilidad").attr("disabled", false);
				$("#btn_capacitacion").attr("disabled", false);
				$("#btn_vacaciones").attr("disabled", false);

				$("#span_current").text(parseInt(current + 1) + " de ");
				$("#btn_familiares").attr("href", "?view=familiares&id_colaborador=" + obj.id);
				$("#btn_formacion").attr("href", "?view=formacion&id_colaborador=" + obj.id);
				$("#btn_experiencia").attr("href", "?view=colaborador_experiencia&id_colaborador=" + obj.id);
				$("#btn_habilidad").attr("href", "?view=habilidades&id_colaborador=" + obj.id);
				$("#btn_capacitacion").attr("href", "?view=capacitaciones&id_colaborador=" + obj.id);
				$("#btn_vacaciones").attr("href", "?view=vacaciones&id_colaborador=" + obj.id);
				$("#btn_certificado_medico").attr("href", "?view=certificado-medico&id_colaborador=" + obj.id);
				$("#btn_dni").attr("onclick", "preparar_subir_dni(" + obj.id + ");");

				$("#btn_contrato").attr("href", "?view=contratos&id_colaborador=" + obj.id);
				$("#btn_sst").attr("href", "?view=recomendaciones_sst&id_colaborador=" + obj.id);
				$("#btn_competencias").attr("href", "?view=verificacion_competencias&id_colaborador=" + obj.id);


				$("#dni").val(obj.dni);
				$("#nombres").val(obj.nombres);
				$("#fecha_nacimiento").val(formato_fecha(obj.fecha_nacimiento));
				$("#lugar_nacimiento").val(obj.lugar_nacimiento);
				$("#id_estado_civil").val(obj.id_estado_civil);
				$("#celular").val(obj.celular);
				$("#direccion").val(obj.direccion);
				$("#correo").val(obj.correo);
				$("#brevette").val(obj.brevette);
				$("#telefono_emergencia").val(obj.telefono_emergencia);

				$("#sistema_pension option[value=" + obj.id_sistema_pension + "]").attr('selected', 'selected');

				$("#sistema_pension").change(llenar_entidades_pension(obj.id_sistema_pension, obj.id_entidad_pension));

				$("#codigo").val(obj.codigo);

				if (obj.asegurado == 1) {
					$("#asegurado").prop('checked', true);
				} else {
					$("#asegurado").prop('checked', false);
				}
				if (obj.estado == 1) {
					$("#estado").prop('checked', true);
				} else {
					$("#estado").prop('checked', false);
				}

				$("#apellido_paterno").val(obj.apellido_paterno);
				$("#apellido_materno").val(obj.apellido_materno);
				$("#archivo_dni").empty();
				if (!$.trim(obj.dni_archivo) == "") {
					$("#archivo_dni").append(`<a href="core/app/view/dni/${obj.dni_archivo}" target="_blank">${$.trim(obj.dni_archivo)}</a>`);
				}
				$("#sueldo").val(obj.sueldo);

				$("#id_proceso").val(obj.proceso).change();

				$("#genero").val(obj.genero).change();

				$("#estado_laboral").val(obj.estado_laboral).change();

				$("#id_cargo option[value=" + obj.id_cargo + "]").prop('selected', 'selected');
				$("#div_certificado_medico").empty();
				$("#div_certificado_medico").append(`<a href="core/app/view/certificado_medico/${obj.certificado_medico}" target="_blank">${$.trim(obj.certificado_medico)}</a>`);

				$("#div_contratos").empty();
				$("#div_contratos").append(`<a href="core/app/view/contratos/${obj.contrato}" target="_blank">${$.trim(obj.contrato)}</a>`);
				$("#div_sst").empty();
				$("#div_sst").append(`<a href="core/app/view/sst/${obj.recomendacion_sst}" target="_blank">${$.trim(obj.recomendacion_sst)}</a>`);

				$("#div_competencias").empty();
				$("#div_competencias").append(`<a href="core/app/view/competencias/${obj.verificacion_competencias}" target="_blank">${$.trim(obj.verificacion_competencias)}</a>`);

				$("#linea").val(obj.linea).change();
				if (!$.trim(obj.fecha_ingreso) == '') {
					$("#fecha_ingreso").val(formato_fecha(obj.fecha_ingreso));
				}
				if (!$.trim(obj.fecha_salida) == '') {
					$("#fecha_salida").val(obj.fecha_salida);
				} else {
					$("#fecha_salida").val("");
				}

				$("#imagen_usuario").attr("src", "core/app/view/img-colaboradores/" + obj.foto);

				$("#cerrar_formulario_docente").click();

				$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
				$("#btn_eliminar_").attr('onclick', 'eliminar(' + obj.id + ');');

				$("#btn_imprimir").attr('href', 'core/app/view/pdf-colaborador-data.php?id_c=' + obj.id);
			}
		});
	}

	function formato_fecha(fecha) {
		if (fecha == null || fecha == "null" || fecha == "") {
			return "";
		} else {
			nueva_fecha = fecha.split("-");
			return nueva_fecha[2] + "-" + nueva_fecha[1] + "-" + nueva_fecha[0];
		}
	}

	function previo() {
		$("#btn_familiares").attr("disabled", true);
		$("#btn_formacion").attr("disabled", true);
		$("#btn_experiencia").attr("disabled", true);
		$("#btn_habilidad").attr("disabled", true);
		$("#btn_capacitacion").attr("disabled", true);
		$("#btn_vacaciones").attr("disabled", true);

		current--;
		$.post('core/app/view/colaborador.php?parAccion=siguiente', {
			current: current
		}, function(data) {
			var obj = JSON.parse(data);

			if (obj == "false" || !obj) {
				current++;
				bootbox.alert({
					message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>no hay mas registros.</strong>' +
						'</div>'
				});
			} else {
				$("#btn_familiares").attr("disabled", false);
				$("#btn_formacion").attr("disabled", false);
				$("#btn_experiencia").attr("disabled", false);
				$("#btn_habilidad").attr("disabled", false);
				$("#btn_capacitacion").attr("disabled", false);
				$("#btn_vacaciones").attr("disabled", false);

				$("#span_current").text(parseInt(current + 1) + " de ");

				$("#btn_familiares").attr("href", "?view=familiares&id_colaborador=" + obj.id);
				$("#btn_formacion").attr("href", "?view=formacion&id_colaborador=" + obj.id);
				$("#btn_experiencia").attr("href", "?view=colaborador_experiencia&id_colaborador=" + obj.id);
				$("#btn_habilidad").attr("href", "?view=habilidades&id_colaborador=" + obj.id);
				$("#btn_capacitacion").attr("href", "?view=capacitaciones&id_colaborador=" + obj.id);
				$("#btn_vacaciones").attr("href", "?view=vacaciones&id_colaborador=" + obj.id);
				$("#btn_certificado_medico").attr("href", "?view=certificado-medico&id_colaborador=" + obj.id);
				$("#btn_dni").attr("onclick", "preparar_subir_dni(" + obj.id + ");");

				$("#btn_contrato").attr("href", "?view=contratos&id_colaborador=" + obj.id);
				$("#btn_sst").attr("href", "?view=recomendaciones_sst&id_colaborador=" + obj.id);
				$("#btn_competencias").attr("href", "?view=verificacion_competencias&id_colaborador=" + obj.id);

				$("#dni").val(obj.dni);
				$("#nombres").val(obj.nombres);
				$("#fecha_nacimiento").val(formato_fecha(obj.fecha_nacimiento));
				$("#lugar_nacimiento").val(obj.lugar_nacimiento);
				$("#id_estado_civil").val(obj.id_estado_civil);
				$("#celular").val(obj.celular);
				$("#direccion").val(obj.direccion);
				$("#correo").val(obj.correo);
				$("#brevette").val(obj.brevette);
				$("#telefono_emergencia").val(obj.telefono_emergencia);

				$("#sistema_pension option[value=" + obj.id_sistema_pension + "]").attr('selected', 'selected');

				$("#sistema_pension").change(llenar_entidades_pension(obj.id_sistema_pension, obj.id_entidad_pension));

				$("#codigo").val(obj.codigo);

				if (obj.asegurado == 1) {
					$("#asegurado").prop('checked', true);
				} else {
					$("#asegurado").prop('checked', false);
				}

				if (obj.estado == 1) {
					$("#estado").prop('checked', true);
				} else {
					$("#estado").prop('checked', false);
				}

				$("#apellido_paterno").val(obj.apellido_paterno);
				$("#apellido_materno").val(obj.apellido_materno);
				$("#archivo_dni").empty();
				if (!$.trim(obj.dni_archivo) == "") {
					$("#archivo_dni").append(`<a href="core/app/view/dni/${obj.dni_archivo}" target="_blank">${$.trim(obj.dni_archivo)}</a>`);
				}
				$("#sueldo").val(obj.sueldo);

				$("#id_proceso").val(obj.proceso).change();

				$("#genero").val(obj.genero).change();

				$("#estado_laboral").val(obj.estado_laboral).change();

				$("#id_cargo").val(obj.id_cargo).change();
				$("#div_certificado_medico").empty();
				$("#div_certificado_medico").append(`<a href="core/app/view/certificado_medico/${obj.certificado_medico}" target="_blank">${$.trim(obj.certificado_medico)}</a>`);

				$("#div_contratos").empty();
				$("#div_contratos").append(`<a href="core/app/view/contratos/${obj.contrato}" target="_blank">${$.trim(obj.contrato)}</a>`);
				$("#div_sst").empty();
				$("#div_sst").append(`<a href="core/app/view/sst/${obj.recomendacion_sst}" target="_blank">${$.trim(obj.recomendacion_sst)}</a>`);

				$("#div_competencias").empty();
				$("#div_competencias").append(`<a href="core/app/view/competencias/${obj.verificacion_competencias}" target="_blank">${$.trim(obj.verificacion_competencias)}</a>`);

				$("#linea").val(obj.linea).change();

				$("#fecha_ingreso").val(formato_fecha(obj.fecha_ingreso));
				$("#fecha_salida").val(obj.fecha_salida);

				$("#imagen_usuario").attr("src", "core/app/view/img-colaboradores/" + obj.foto);

				$("#cerrar_formulario_docente").click();

				$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
				$("#btn_eliminar_").attr('onclick', 'eliminar(' + obj.id + ');');
				$("#btn_imprimir").attr('href', 'core/app/view/pdf-colaborador-data.php?id_c=' + obj.id);
			}
		});
	}

	function buscar_dni() {
		$.post('core/app/view/colaborador.php?parAccion=buscar_dni', {
			dni: $("#dni_buscar").val(),
			nombres: $("#nombre_buscar").val(),
			apellido: $("#apellido_buscar").val(),
		}, function(data) {
			var obj = JSON.parse(data);
			$("#dni_buscar").val("");
			$("#nombre_buscar").val("");
			$("#apellido_buscar").val("");

			$("#exampleModalLabel").text("Buscar Colaborador");

			$("#dni").val(obj.dni);
			$("#nombres").val(obj.nombres);
			$("#fecha_nacimiento").val(formato_fecha(obj.fecha_nacimiento));
			$("#lugar_nacimiento").val(obj.lugar_nacimiento);
			$("#id_estado_civil").val(obj.id_estado_civil);
			$("#celular").val(obj.celular);
			$("#direccion").val(obj.direccion);
			$("#correo").val(obj.correo);
			$("#brevette").val(obj.brevette);
			$("#telefono_emergencia").val(obj.telefono_emergencia);

			$("#sistema_pension option[value=" + obj.id_sistema_pension + "]").attr('selected', 'selected');

			$("#sistema_pension").change(llenar_entidades_pension(obj.id_sistema_pension, obj.id_entidad_pension));

			$("#codigo").val(obj.codigo);

			if (obj.asegurado == 1) {
				$("#asegurado").prop('checked', true);
			} else {
				$("#asegurado").prop('checked', false);
			}

			if (obj.estado == 1) {
				$("#estado").prop('checked', true);
			} else {
				$("#estado").prop('checked', false);
			}

			$("#apellido_paterno").val(obj.apellido_paterno);
			$("#apellido_materno").val(obj.apellido_materno);
			$("#archivo_dni").empty();
			if (!$.trim(obj.dni_archivo) == "") {
				$("#archivo_dni").append(`<a href="core/app/view/dni/${obj.dni_archivo}" target="_blank">${$.trim(obj.dni_archivo)}</a>`);
			}
			$("#sueldo").val(obj.sueldo);

			$("#id_proceso option[value=" + obj.proceso + "]").attr('selected', 'selected');

			$("#genero option[value=" + obj.genero + "]").attr('selected', 'selected');

			$("#estado_laboral option[value=" + obj.estado_laboral + "]").attr('selected', 'selected');

			$("#id_cargo option[value=" + obj.id_cargo + "]").attr('selected', 'selected');
			$("#div_certificado_medico").empty();
			$("#div_certificado_medico").append(`<a href="core/app/view/certificado_medico/${obj.certificado_medico}" target="_blank">${$.trim(obj.certificado_medico)}</a>`);

			$("#div_contratos").empty();
			$("#div_contratos").append(`<a href="core/app/view/contratos/${obj.contrato}" target="_blank">${$.trim(obj.contrato)}</a>`);
			$("#div_sst").empty();
			$("#div_sst").append(`<a href="core/app/view/sst/${obj.recomendacion_sst}" target="_blank">${$.trim(obj.recomendacion_sst)}</a>`);

			$("#div_competencias").empty();
			$("#div_competencias").append(`<a href="core/app/view/competencias/${obj.verificacion_competencias}" target="_blank">${$.trim(obj.verificacion_competencias)}</a>`);

			$("#linea option[value=" + obj.linea + "]").attr('selected', 'selected');

			$("#btn_familiares").attr("href", "?view=familiares&id_colaborador=" + obj.id);
			$("#btn_formacion").attr("href", "?view=formacion&id_colaborador=" + obj.id);
			$("#btn_experiencia").attr("href", "?view=colaborador_experiencia&id_colaborador=" + obj.id);
			$("#btn_habilidad").attr("href", "?view=habilidades&id_colaborador=" + obj.id);
			$("#btn_capacitacion").attr("href", "?view=capacitaciones&id_colaborador=" + obj.id);
			$("#btn_vacaciones").attr("href", "?view=vacaciones&id_colaborador=" + obj.id);
			$("#btn_certificado_medico").attr("href", "?view=certificado-medico&id_colaborador=" + obj.id);
			$("#btn_dni").attr("onclick", "preparar_subir_dni(" + obj.id + ");");

			$("#btn_contrato").attr("href", "?view=contratos&id_colaborador=" + obj.id);
			$("#btn_sst").attr("href", "?view=recomendaciones_sst&id_colaborador=" + obj.id);
			$("#btn_competencias").attr("href", "?view=verificacion_competencias&id_colaborador=" + obj.id);

			$("#fecha_ingreso").val(formato_fecha(obj.fecha_ingreso));
			$("#fecha_salida").val(obj.fecha_salida);

			$("#imagen_usuario").attr("src", "core/app/view/img-colaboradores/" + obj.foto);

			$("#cerrar_formulario_docente").click();

			$("#btn_rehusar").removeAttr('onclick')
			$("#btn_rehusar").attr('onclick', 'actualizar(' + obj.id + ');');
			$("#btn_eliminar_").attr('onclick', 'eliminar(' + obj.id + ');');
			$("#btn_imprimir").attr('href', 'core/app/view/pdf-colaborador-data.php?id_c=' + obj.id);
		});
	}

	function get_all_colaboradores() {
		$("#tabla_colaboradores").find('tbody').empty();
		$.post('core/app/view/colaborador.php?parAccion=get_all_colaboradores', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				var asegurado = "";
				if (val.asegurado == 0) {
					asegurado = `<i class="glyphicon glyphicon-remove"></i>`;
				} else {
					asegurado = `<i class="glyphicon glyphicon-ok"></i>`;
				}

				var estado = "";
				if (val.estado == 0) {
					estado = `<i class="glyphicon glyphicon-remove"></i>`;
				} else {
					estado = `<i class="glyphicon glyphicon-ok"></i>`;
				}
				$("#tabla_colaboradores").find('tbody').append(`
					<tr>
						<td>` + val.id + `</td>
						<td>
							<img style="border-radius: 4px; box-shadow: 0px 2px 2px #333;" src="core/app/view/img-colaboradores/` + val.foto + `" width="50" height="50" alt="" />
						</td>
						<td>` + val.dni + `</td>
						<td>` + val.nombres + `</td>
						<td>` + val.apellidos + `</td>
						<td>` + val.celular + `</td>
						<td>` + val.correo + `</td>
						<td>` + val.direccion + `</td>
						<td>` + val.afp + `</td>
						<td style="text-align: center;">` + asegurado + `</td>
						<td>
							<span role="button" data-toggle="modal" data-target="#formulario" class="w-100 mb-1 btn btn-sm btn-warning" onclick="editar(` + val.id + `);">
								<i class="glyphicon glyphicon-pencil"></i>
							</span>
							<span role="button" class="w-100 btn btn-sm btn-danger" onclick="eliminar(` + val.id + `);">
								<i class="fa fa-trash"></i>
							</span>
						</td>
					</tr>
				`);
			});
		});
	}

	function abrir_formulario() {
		limpiar_formulario();
		$("#exampleModalLabel").text("Nuevo Colaborador");
		$("#btn_finalizar").attr("onclick", "guardar();");
	}

	function limpiar_formulario() {
		$("#dni").val("");
		$("#nombres").val("");

		$("#apellido_paterno").val("");
		$("#apellido_materno").val("");
		$("#archivo_dni").empty();

		$("#fecha_nacimiento").val("");
		$("#lugar_nacimiento").val("");
		$("#id_estado_civil").val("");
		$("#celular").val("");
		$("#direccion").val("");
		$("#correo").val("");
		$("#brevette").val("");
		$("#telefono_emergencia").val("");
		$("#id_entidad_pension").val("");
		$("#sistema_pension").val("");
		$("#codigo").val("");
		$("#asegurado").val("");
		$("#estado").val("");

		$("#id_proceso").val("");
		$("#genero").val("");
		$("#estado_laboral").val("");
		$("#fecha_ingreso").val("");
		$("#fecha_salida").val("");

		$("#id_cargo").val("");

		$("#div_certificado_medico").empty();

		$("#div_contratos").empty();

		$("#div_sst").empty();
		$("#div_competencias").empty();
		$("#linea").val("");

		$("#asegurado").prop('checked', false);
		$("#estado").prop('checked', false);

		$("#imagen_usuario").attr("src", "");

		$("#btn_rehusar").attr('onclick', 'guardar();');

		$("#btn_dni").css("display", "none");
	}

	function editar(id) {
		$.post('core/app/view/colaborador.php?parAccion=editar', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$("#exampleModalLabel").text("Editar Colaborador");

			$("#dni").val(obj.dni);
			$("#nombres").val(obj.nombres);
			$("#apellidos").val(obj.apellidos);
			$("#fecha_nacimiento").val(obj.fecha_nacimiento);
			$("#lugar_nacimiento").val(obj.lugar_nacimiento);
			$("#id_estado_civil").val(obj.id_estado_civil);
			$("#celular").val(obj.celular);
			$("#direccion").val(obj.direccion);
			$("#correo").val(obj.correo);
			$("#brevette").val(obj.brevette);
			$("#telefono_emergencia").val(obj.telefono_emergencia);

			$("#sistema_pension option[value=" + obj.id_sistema_pension + "]").attr('selected', 'selected');

			$("#sistema_pension").change(llenar_entidades_pension(obj.id_sistema_pension, obj.id_entidad_pension));

			$("#id_cargo option[value=" + obj.id_cargo + "]").attr('selected', 'selected');
			$("#div_certificado_medico").empty();
			$("#div_certificado_medico").append(`<a href="core/app/view/certificado_medico/${obj.certificado_medico}" target="_blank">${$.trim(obj.certificado_medico)}</a>`);

			$("#div_contratos").empty();
			$("#div_contratos").append(`<a href="core/app/view/contratos/${obj.contrato}" target="_blank">${$.trim(obj.contrato)}</a>`);
			$("#div_sst").empty();
			$("#div_sst").append(`<a href="core/app/view/sst/${obj.recomendacion_sst}" target="_blank">${$.trim(obj.recomendacion_sst)}</a>`);
			$("#id_proceso option[value=" + obj.proceso + "]").attr('selected', 'selected');

			$("#codigo").val(obj.codigo);

			if (obj.asegurado == 1) {
				$("#asegurado").prop('checked', true);
			} else {
				$("#asegurado").prop('checked', false);
			}

			if (obj.estado == 1) {
				$("#estado").prop('checked', true);
			} else {
				$("#estado").prop('checked', false);
			}

			$("#imagen_usuario").attr("src", "core/app/view/img-colaboradores/" + obj.foto);

			$("#btn_finalizar").attr("onclick", "actualizar(" + id + ");");
		});
	}

	function llenar_estado_civil() {
		$.post('core/app/view/colaborador.php?parAccion=llenar_estado_civil', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_estado_civil").append(`
					<option value="` + val.id + `">` + val.estado_civil + `</option>
				`);
			});
		});
	}

	function llenar_cargos() {
		$.post('core/app/view/colaborador.php?parAccion=get_puestos', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_cargo").append(`
					<option value="` + val.id + `">` + val.puesto + `</option>
				`);
			});
		});
	}

	function llenar_areas() {
		$.post('core/app/view/colaborador.php?parAccion=get_all_areas', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_proceso").append(`
					<option value="` + val.id + `">` + val.area + `</option>
				`);
			});
		});
	}

	function llenar_sistema_pension() {
		$.post('core/app/view/colaborador.php?parAccion=llenar_sistema_pension', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#sistema_pension").append(`
					<option value="` + val.id + `">` + val.sistema_pension + `</option>
				`);
			});
		});
	}

	function llenar_entidades_pension(id, id_) {
		$("#id_entidad_pension").empty();
		$.post('core/app/view/colaborador.php?parAccion=llenar_entidades_pension', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				if (id_ == val.id) {
					$("#id_entidad_pension").append(`
						<option value="` + val.id + `" selected>` + val.afp + `</option>
					`);
				} else {
					$("#id_entidad_pension").append(`
						<option value="` + val.id + `">` + val.afp + `</option>
					`);
				}
			});
		});
	}

	function actualizar(id) {
		var file = _("file1").files[0];
		var formdata = new FormData();
		formdata.append("file1", file);

		formdata.append("dni", $("#dni").val());
		formdata.append("nombres", $("#nombres").val());

		formdata.append("apellido_paterno", $("#apellido_paterno").val());
		formdata.append("apellido_materno", $("#apellido_materno").val());

		formdata.append("fecha_nacimiento", $("#fecha_nacimiento").val());
		formdata.append("lugar_nacimiento", $("#lugar_nacimiento").val());
		formdata.append("id_estado_civil", $("#id_estado_civil").val());
		formdata.append("celular", $("#celular").val());
		formdata.append("direccion", $("#direccion").val());
		formdata.append("correo", $("#correo").val());
		formdata.append("brevette", $("#brevette").val());
		formdata.append("telefono_emergencia", $("#telefono_emergencia").val());
		formdata.append("id_entidad_pension", $("#id_entidad_pension").val());
		formdata.append("id_sistema_pension", $("#sistema_pension").val());
		formdata.append("codigo", $("#codigo").val());
		formdata.append("asegurado", $("#asegurado").val());
		formdata.append("sueldo", $("#sueldo").val());
		if ($("#estado").is(':checked')) {
			formdata.append("estado", 1);
		} else {
			formdata.append("estado", 0);
		}

		formdata.append("id_proceso", $("#id_proceso").val());
		formdata.append("genero", $("#genero").val());
		formdata.append("estado_laboral", $("#estado_laboral").val());
		formdata.append("fecha_ingreso", $("#fecha_ingreso").val());
		formdata.append("fecha_salida", $("#fecha_salida").val());

		formdata.append("id_cargo", $("#id_cargo").val());
		formdata.append("linea", $("#linea").val());

		formdata.append("id", id);

		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", progressHandler, false);
		ajax.addEventListener("load", completeHandler, false);
		ajax.addEventListener("error", errorHandler, false);
		ajax.addEventListener("abort", abortHandler, false);
		ajax.open("POST", "core/app/view/colaborador.php?parAccion=actualizar");
		ajax.send(formdata);
	}

	function eliminar(id) {
		$.post('core/app/view/colaborador.php?parAccion=eliminar', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
						'<strong>Eliminado Correctamente.</strong>' +
						'</div>'
				});
				previo();
			} else {}
		});
	}

	function guardar() {
		var file = _("file1").files[0];
		var formdata = new FormData();
		formdata.append("file1", file);

		formdata.append("dni", $("#dni").val());
		formdata.append("nombres", $("#nombres").val());

		formdata.append("apellido_paterno", $("#apellido_paterno").val());
		formdata.append("apellido_materno", $("#apellido_materno").val());

		formdata.append("fecha_nacimiento", $("#fecha_nacimiento").val());
		formdata.append("lugar_nacimiento", $("#lugar_nacimiento").val());
		formdata.append("id_estado_civil", $("#id_estado_civil").val());
		formdata.append("celular", $("#celular").val());
		formdata.append("direccion", $("#direccion").val());
		formdata.append("correo", $("#correo").val());
		formdata.append("brevette", $("#brevette").val());
		formdata.append("telefono_emergencia", $("#telefono_emergencia").val());
		formdata.append("id_entidad_pension", $("#id_entidad_pension").val());
		formdata.append("id_sistema_pension", $("#sistema_pension").val());
		formdata.append("codigo", $("#codigo").val());
		formdata.append("asegurado", $("#asegurado").val());
		formdata.append("sueldo", $("#sueldo").val());
		if ($("#estado").is(':checked')) {
			formdata.append("estado", 1);
		} else {
			formdata.append("estado", 0);
		}

		formdata.append("id_proceso", $("#id_proceso").val());
		formdata.append("genero", $("#genero").val());
		formdata.append("estado_laboral", $("#estado_laboral").val());
		formdata.append("fecha_ingreso", $("#fecha_ingreso").val());
		formdata.append("fecha_salida", $("#fecha_salida").val());

		formdata.append("id_cargo", $("#id_cargo").val());
		formdata.append("linea", $("#linea").val());

		var lid_ = 0;

		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", progressHandler, false);
		ajax.addEventListener("load", completeHandler, false);
		ajax.addEventListener("error", errorHandler, false);
		ajax.addEventListener("abort", abortHandler, false);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == XMLHttpRequest.DONE) {
				var obj = JSON.parse(ajax.responseText);
			}
		}
		ajax.open("POST", "core/app/view/colaborador.php?parAccion=guardar");
		ajax.send(formdata);


	}

	function _(el) {
		return document.getElementById(el);
	}

	function uploadFile() {
		var file = _("file1").files[0];
		var formdata = new FormData();
		formdata.append("file1", file);
		formdata.append("id_curso", $("#id_curso").val());
		formdata.append("id_tema", $("#id_tema").val());
		formdata.append("tarea", $("#tarea").val());
		formdata.append("fecha_entrega", $("#fecha_entrega").val());
		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", progressHandler, false);
		ajax.addEventListener("load", completeHandler, false);
		ajax.addEventListener("error", errorHandler, false);
		ajax.addEventListener("abort", abortHandler, false);


		ajax.open("POST", "../php/tarea.php?parAccion=guardar_tarea");
		ajax.send(formdata);

	}

	function progressHandler(event) {
		var percent = (event.loaded / event.total) * 100;
	}

	function completeHandler(event) {
		bootbox.alert({
			message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
				'<strong>Realizado Correctamente.</strong>' +
				'</div>'
		});
		location.reload();
	}

	function errorHandler(event) {
		_("status").innerHTML = "Upload Failed";
	}

	function abortHandler(event) {
		_("status").innerHTML = "Upload Aborted";
	}
</script>