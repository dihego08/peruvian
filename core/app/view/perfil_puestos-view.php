<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

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

	.col-md-4 {
		margin-top: 1rem;
	}

	.col-md-8 {
		margin-top: 1rem;
	}
</style>
<!-- <script src="https://cdn.ckeditor.com/4.22.0/standard/ckeditor.js"></script> -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3><i class="glyphicon glyphicon-stats"></i> Perfil de Puesto</h3>
			<div class="clearfix"></div>
			<div class="box">
				<div class="box-body">
					<h4>Seleccionar Puesto</h4>
					<select id="id_puesto" class="form-control rounded-pill js-example-basic-single">
						<option value="0">Seleccionar...</option>
					</select>

					<div class="row" hidden id="div_formulario" style="margin-top: 15px; border-radius: 4px; box-shadow: 0px 2px 2px #333; padding: 10px;">
						<h3>I. IDENTIFICACIÓN DEL PUESTO</h3>

						<div class="col-md-4">
							<label>TITULO DEL PUESTO: </label>
						</div>
						<div class="col-md-8">
							<input type="text" id="puesto" name="puesto" class="form-control rounded-pill">
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<label>AREA: </label>
							</div>
							<div class="col-md-8">
								<input type="text" id="area" name="area" class="form-control rounded-pill">
							</div>
						</div>
						<div class="col-md-4">
							<label>REPORTA A: </label>
						</div>
						<div class="col-md-8">
							<input type="text" id="reporta_a" name="reporta_a" class="form-control rounded-pill">
						</div>
						<div class="col-md-4">
							<label>SUPERVISA A: </label>
						</div>
						<div class="col-md-8">
							<div name="supervisa_a" id="supervisa_a"></div>
							<script>
								//CKEDITOR.replace('supervisa_a');
							</script>
						</div>
						<div class="col-md-4">
							<label>INTERACTUA CON: </label>
						</div>
						<div class="col-md-8">
							<div name="interactua_con" id="interactua_con"></div>
							<script>
								//CKEDITOR.replace('interactua_con');
							</script>
						</div>
						<div class="col-md-4">
							<label>REEMPLAZADO POR: </label>
						</div>
						<div class="col-md-8">
							<input type="text" id="reemplazado_por" name="reemplazado_por" class="form-control rounded-pill">
						</div>

						<hr>
						<h3>II. CONTENIDO</h3>
						<div class="form-group row" style="margin-top: 1rem;">
							<div class="col-md-4">
								<label>OBJETIVO DEL PUESTO: </label>
							</div>
							<div class="col-md-8">
								<input type="text" id="objetivo" name="objetivo" class="form-control rounded-pill">
							</div>
						</div>
						<div class="form-group row" style="margin-top: 1rem;">
							<div class="col-md-4">
								<label>FUNCIONES: </label>
							</div>
							<div class="col-md-8">
								<div name="funciones" id="funciones"></div>
								<script>
									//CKEDITOR.replace('funciones');
								</script>
							</div>
						</div>
						<div class="form-group row" style="margin-top: 1rem;">
							<div class="col-md-4">
								<label>RESPONSABILIDADES: </label>
							</div>
							<div class="col-md-8">
								<div name="responsabilidades" id="responsabilidades"></div>
								<script>
									//CKEDITOR.replace('responsabilidades');
								</script>
							</div>
						</div>
						<div class="form-group row" style="margin-top: 1rem;">
							<div class="col-md-4">
								<label>EQUIPO UTILIZADO: </label>
							</div>
							<div class="col-md-8">
								<div name="equipo_utilizado" id="equipo_utilizado"></div>
								<script>
									//CKEDITOR.replace('equipo_utilizado');
								</script>
							</div>
						</div>
						<div class="form-group row" style="margin-top: 1rem;">
							<div class="col-md-4">
								<label>LUGAR DE TRABAJO: </label>
							</div>
							<div class="col-md-8">
								<input type="text" id="lugar_trabajo" name="lugar_trabajo" class="form-control rounded-pill">
							</div>
						</div>
						<div class="form-group row" style="margin-top: 1rem;">
							<div class="col-md-4">
								<label>REQUERIMIENTOS FÍSICOS: </label>
							</div>
							<div class="col-md-8">
								<input type="text" id="requerimientos_fisicos" name="requerimientos_fisicos" class="form-control rounded-pill">
							</div>
						</div>
						<hr>

						<h3>III. CONOCIMIENTOS REQUERIDOS</h3>
						<div class="form-group row" style="margin-top: 1rem; display: flex; align-items: center;">
							<div class="col-md-4">
								<label>EDUCACIÓN BÁSICA: </label>
							</div>
							<div class="col-md-8 row">
								<div class="col-md-6">
									<label for="">Requerido</label>
									<input type="text" id="formacion_basica" name="formacion_basica" class="form-control rounded-pill">
								</div>
								<div class="col-md-6">
									<label for="">Óptimo</label>
									<input type="text" id="formacion_basica_optima" name="formacion_basica_optima" class="form-control rounded-pill">
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-top: 1rem; display: flex; align-items: center;">
							<div class="col-md-4">
								<label>CONOCIMIENTOS ESPECÍFICOS: </label>
							</div>
							<div class="col-md-8">
								<div name="conocimientos_especificos" id="conocimientos_especificos"></div>
								<script>
									//CKEDITOR.replace('conocimientos_especificos');
								</script>
							</div>
						</div>
						<div class="form-group row" style="margin-top: 1rem; display: flex; align-items: center;">
							<div class="col-md-4">
								<label>EXPERIENCIA O FORMACIÓN: </label>
							</div>
							<div class="col-md-8 row">
								<div class="col-md-6">
									<label for="">Requerido</label>
									<input type="text" id="experiencia_requerida" name="experiencia_requerida" class="form-control rounded-pill">
								</div>
								<div class="col-md-6">
									<label for="">Óptimo</label>
									<input type="text" id="experiencia_requerida_optima" name="experiencia_requerida_optima" class="form-control rounded-pill">
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-top: 1rem; display: flex; align-items: center;">
							<div class="col-md-4">
								<label>IDIOMA : </label>
							</div>
							<div class="col-md-8">
								<input type="text" id="idioma" name="idioma" class="form-control rounded-pill">
							</div>
						</div>
						<hr>

						<h3>IV. COMPETENCIA ESPECÍFICA DEL PUESTO</h3>
						<div class="form-group row" style="margin-top: 1rem;">
							<div class="col-md-12">
								<div name="competencia_especifica" id="competencia_especifica"></div>
								<script>
									//CKEDITOR.replace('competencia_especifica');
								</script>
							</div>
						</div>

						<h3>V. COMPETENCIAS CARDINALES</h3>
						<div class="form-group row" style="margin-top: 1rem;">
							<div class="col-md-12">
								<div name="competencia_cardinal" id="competencia_cardinal"></div>
								<script>
									//CKEDITOR.replace('competencia_cardinal');
								</script>
							</div>
						</div>

						<div class="form-group row" style="margin-top: 1rem;">
							<div class="col-md-4">
								<label>Elaborado por: </label>
								<input type="text" id="elaborado_por" name="elaborado_por" class="form-control rounded-pill">
							</div>
							<div class="col-md-4">
								<label>Aprobado por: </label>
								<input type="text" id="aprobado_por" name="aprobado_por" class="form-control rounded-pill">
							</div>
							<div class="col-md-4">
								<label>Fecha de aprobación: </label>
								<input type="text" id="fecha_aprobacion" name="fecha_aprobacion" class="form-control rounded-pill">
							</div>
						</div>

						<div class="col-md-12" style="text-align: center; margin-top: 15px;">
							<a class="btn btn-primary rounded-pill" id="btn_pdf" target="_blank">Exportar PDF</a>
							<button class="btn btn-success rounded-pill" onclick="guardar();">Guardar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<script type="text/javascript">
	/*var objetos = [
		'supervisa_a',
		'interactua_con',
		'funciones',
		'responsabilidades',
		'equipo_utilizado',
		'conocimientos_especificos',
		'competencia_especifica',
		'competencia_cardinal'
	];
	var quill = [];
	for (let index = 0; index < objetos.length; index++) {
		//const element = array[index];
		quill[objetos[index]] = new Quill('#' + objetos[index], {
			theme: 'snow'
		});

	}*/
	const supervisa_a = new Quill('#supervisa_a', {
		theme: 'snow'
	});
	const interactua_con = new Quill('#interactua_con', {
		theme: 'snow'
	});
	const funciones = new Quill('#funciones', {
		theme: 'snow'
	});
	const responsabilidades = new Quill('#responsabilidades', {
		theme: 'snow'
	});
	const equipo_utilizado = new Quill('#equipo_utilizado', {
		theme: 'snow'
	});
	const conocimientos_especificos = new Quill('#conocimientos_especificos', {
		theme: 'snow'
	});
	const competencia_especifica = new Quill('#competencia_especifica', {
		theme: 'snow'
	});
	const competencia_cardinal = new Quill('#competencia_cardinal', {
		theme: 'snow'
	});
	$(document).ready(function() {
		get_all_puestos();
		$(".js-example-basic-single").select2();

		$("#id_puesto").on("change", function() {
			get_perfil_puesto($("#id_puesto").val());
		});

		/*tinyMCE.init({
			mode: "textareas",
			//...
		});*/









	});

	function get_all_puestos() {
		$("#tabla_colaboradores").find('tbody').empty();
		$.post('core/app/view/colaborador.php?parAccion=get_all_puestos', function(data) {
			var obj = JSON.parse(data);
			$.each(obj, function(index, val) {
				$("#id_puesto").append(`<option value="` + val.id + `">` + val.puesto + `</option>`)
			});
		});
	}

	function get_perfil_puesto(id) {
		$("#btn_pdf").attr('href', 'core/app/view/pdf-perfil_puesto.php?id=' + id);
		$("#div_experiencias").empty();
		$("#div_formulario").removeAttr("hidden");

		$.post('core/app/view/colaborador.php?parAccion=get_perfil_puesto', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);

			//CKEDITOR.instances.editor1.setData(data.nosotros);


			/*CKEDITOR.instances.supervisa_a.setData(obj.supervisa_a);
			CKEDITOR.instances.interactua_con.setData(obj.interactua_con);
			CKEDITOR.instances.funciones.setData(obj.funciones);
			CKEDITOR.instances.responsabilidades.setData(obj.responsabilidades);
			CKEDITOR.instances.equipo_utilizado.setData(obj.equipo_utilizado);
			CKEDITOR.instances.conocimientos_especificos.setData(obj.conocimientos_especificos);
			CKEDITOR.instances.competencia_especifica.setData(obj.competencia_especifica);
			CKEDITOR.instances.competencia_cardinal.setData(obj.competencia_cardinal);*/


			conocimientos_especificos.clipboard.dangerouslyPasteHTML(obj.conocimientos_especificos);
			supervisa_a.clipboard.dangerouslyPasteHTML(obj.supervisa_a);
			interactua_con.clipboard.dangerouslyPasteHTML(obj.interactua_con);
			funciones.clipboard.dangerouslyPasteHTML(obj.funciones);
			responsabilidades.clipboard.dangerouslyPasteHTML(obj.responsabilidades);
			equipo_utilizado.clipboard.dangerouslyPasteHTML(obj.equipo_utilizado);
			competencia_especifica.clipboard.dangerouslyPasteHTML(obj.competencia_especifica);
			competencia_cardinal.clipboard.dangerouslyPasteHTML(obj.competencia_cardinal);
			//console.log(obj.competencia_cardinal);
			//console.log($("#interactua_con").find(".ql-editor"));
			//$("#interactua_con .ql-editor").append(obj.interactua_con);
			//$("#interactua_con .ql-editor").innerHTML = (obj.interactua_con);


			$("#area").val(obj.area);
			$("#puesto").val(obj.puesto);

			$("#reporta_a").val(obj.reporta_a);
			$("#reemplazado_por").val(obj.reemplazado_por);
			$("#objetivo").val(obj.objetivo);
			$("#lugar_trabajo").val(obj.lugar_trabajo);
			$("#requerimientos_fisicos").val(obj.requerimientos_fisicos);
			$("#formacion_basica").val(obj.formacion_basica);
			$("#formacion_basica_optima").val(obj.formacion_basica_optima);
			$("#experiencia_requerida").val(obj.experiencia_requerida);
			$("#experiencia_requerida_optima").val(obj.experiencia_requerida_optima);
			$("#idioma").val(obj.idioma);
			$("#competencia_especifica").val(obj.competencia_especifica);
			$("#competencia_cardinal").val(obj.competencia_cardinal);
			$("#elaborado_por").val(obj.elaborado_por);
			$("#aprobado_por").val(obj.aprobado_por);
			$("#fecha_aprobacion").val(obj.fecha_aprobacion);
		});
	}

	function limpiar_formulario() {
		$("#empresa").val("");
		$("#cargo").val("");
		$("#responsabilidades").val("");
		$("#fecha_ingreso").val("");
		$("#fecha_termino").val("");
		$("#tiempo_servicio").val("");
	}

	function guardar() {
		//var data = CKEDITOR.instances.editor1.getData();
		var _supervisa_a = supervisa_a.root.innerHTML;
		var _interactua_con = interactua_con.root.innerHTML;
		var _funciones = funciones.root.innerHTML;
		var _responsabilidades = responsabilidades.root.innerHTML;
		var _equipo_utilizado = equipo_utilizado.root.innerHTML;
		var _conocimientos_especificos = conocimientos_especificos.root.innerHTML;
		var _competencia_especifica = competencia_especifica.root.innerHTML;
		var _competencia_cardinal = competencia_cardinal.root.innerHTML;

		//console.log(_competencia_cardinal);

		$.post('core/app/view/colaborador.php?parAccion=guardar_perfil', {
			id_puesto: $("#id_puesto").val(),

			reporta_a: $("#reporta_a").val(),
			reemplazado_por: $("#reemplazado_por").val(),
			objetivo: $("#objetivo").val(),
			lugar_trabajo: $("#lugar_trabajo").val(),
			requerimientos_fisicos: $("#requerimientos_fisicos").val(),
			formacion_basica: $("#formacion_basica").val(),
			formacion_basica_optima: $("#formacion_basica_optima").val(),
			experiencia_requerida: $("#experiencia_requerida").val(),
			experiencia_requerida_optima: $("#experiencia_requerida_optima").val(),
			idioma: $("#idioma").val(),
			competencia_especifica: $("#competencia_especifica").val(),
			competencia_cardinal: $("#competencia_cardinal").val(),
			elaborado_por: $("#elaborado_por").val(),
			aprobado_por: $("#aprobado_por").val(),
			fecha_aprobacion: $("#fecha_aprobacion").val(),
			supervisa_a: _supervisa_a,
			interactua_con: _interactua_con,
			funciones: _funciones,
			responsabilidades: _responsabilidades,
			equipo_utilizado: _equipo_utilizado,
			conocimientos_especificos: _conocimientos_especificos,
			competencia_especifica: _competencia_especifica,
			competencia_cardinal: _competencia_cardinal
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_perfil_puesto($("#id_puesto").val());

				bootbox.alert({
					message: `<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">
                        <strong>Guardado Correctamente.</strong>
                    </div>`
				});
			}
		});
	}
</script>