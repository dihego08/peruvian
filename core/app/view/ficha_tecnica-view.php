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
<?php

//$currency = ConfigurationData::getByPreffix("currency")->val;
//$categories = CategoryData::getAll();
$person = PersonData::getAll();

$accion = $_GET['act'];

$k = Core::$user->kind;

if ($accion != "") {
	switch ($accion) {
		case 'filtrar':
			$clienteId = $_POST['cmbCliente'];
			$modelo = $_POST['txtModelo'];
			$nombre = $_POST['txtNombre'];
			$estado = $_POST['cmbEstado'];
			$products = ProductData::getAllByFilter($clienteId, $modelo, $nombre, $estado);
			break;
	}
} else {
	$currency = ConfigurationData::getByPreffix("currency")->val;

	if (Core::$user->kind == 1 || Core::$user->kind == 12 || Core::$user->kind == 4) {
		$products = ProductData::getAll();
	} elseif (Core::$user->kind == 8) {
		$cls = 'style="display: none;"';
		$products = ProductData::getAllByClienteId('2,3');
	} else {
		include("env.php");
		$query = $mbd->prepare("SELECT id_referencia FROM cargos WHERE id = :id");
		$query->bindParam(":id", Core::$user->kind);
		$query->execute();
		$values = $query->fetch(PDO::FETCH_ASSOC);
		$id_referencia = 0;
		if (empty($values['id_referencia']) || $values['id_referencia'] == null) {
		} else {
			$cls = 'style="display: none;"';
			$products = ProductData::getAllByClienteId($values['id_referencia']);
		}
	}
}

?>

<!-- Content Header (Page header) -->
<section class="content-header">
	<h3>
		Productos
	</h3>
</section>

<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-12">
			<?php

			if ($k == 1) {
			?>
				<div class="box box-primary">
					<div class="box-body">
						<form class="form-horizontal" method="post" id="filter" action="index.php?view=ficha_tecnica&act=filtrar" role="form">
							<fieldset>
								<legend>Filtros de Búsqueda</legend>
								<div class="col-md-3">
									<label>Cliente</label>
									<select name="cmbCliente" class="form-control rounded-pill">
										<option value="">-- NINGUNA --</option>
										<?php foreach ($person as $clientes) : ?>
											<option value="<?php echo $clientes->id; ?>" <?php if ($_GET['cid'] != "" && $clientes->id == $_GET['cid']) {
																								echo "selected";
																							} ?>>
												<?php echo $clientes->name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-md-3">
									<label>Modelo</label>
									<input type="text" name="txtModelo" id="txtModelo" class="form-control rounded-pill">
								</div>
								<div class="col-md-3">
									<label>Nombre</label>
									<input type="text" name="txtNombre" id="txtNombre" class="form-control rounded-pill">
								</div>
								<div class="col-md-3">
									<label>Estado</label>
									<select class="form-control rounded-pill" name="cmbEstado" id="cmbEstado">
										<option value="1">ACTIVO</option>
										<option value="0">INACTIVO</option>

									</select>
								</div>

							</fieldset>
							<div class="w-100 mt-1 text-center">
								<button type="submit" class="btn btn-primary rounded-pill"><i class="fa fa-search"></i> Filtrar</button>
							</div>
						</form>
					</div>
				</div>
			<?php
			} else {
			}
			?>
			<div class="w-100 text-right">
				<?php
				if ($k == 1) {
				?>
					<a href="index.php?view=newproduct" class="btn btn-outline-dark rounded-pill">Agregar Producto</a>
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-outline-dark rounded-pill dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-download"></i> Descargar <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="report/products-word.php?cli=<?= $clienteId; ?>&mod=<?= $modelo; ?>&nom=<?= $nombre; ?>&est=<?= $estado; ?>">Word 2007 (.docx)</a></li>
							<li><a href="report/products-xlsx.php?cli=<?= $clienteId; ?>&mod=<?= $modelo; ?>&nom=<?= $nombre; ?>&est=<?= $estado; ?>">Excel (.xlsx)</a></li>
							<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a>

						</ul>
					</div>
				<?php
				} else {
				}
				?>
			</div>
			<hr class="w-100 d-block">
			<?php


			if (count($products) > 0) {
			?>
				<div class="box box-primary">
					<div class="box-body no-padding">
						<div class="box-body table-responsive" style="padding: 0;">
							<table id="example" class="table  table-bordered datatable table-hover entries">


								<?php
								if (Core::$user->kind == 7) {
								?>
									<thead>
										<th>Modelo</th>
										<th <?php echo $cls; ?>>Descripción</th>
										<th <?php echo $cls; ?>>Fec. act.</th>
										<th>Imagen</th>
										<th <?php echo $cls; ?>>Prec. Bordado</th>
										<th <?php echo $cls; ?>>Prec. Bordado salida</th>
										<th <?php echo $cls; ?>>Bordado</th>
									</thead>
									<?php foreach ($products as $product) : ?>
										<tr>
											<td><?php echo $product->code; ?></td>
											<td <?php echo $cls; ?>><?php echo $product->name; ?></td>
											<td <?php echo $cls; ?>><?php echo date("d-m-Y", strtotime($product->fecact)); ?></td>
											<td>
												<?php if ($product->image != "") : ?>
													<img src="storage/products/<?php echo $product->image; ?>" style="width:64px;" onclick="abrir_imagen('storage/products/<?php echo $product->image; ?>');">
												<?php endif; ?>
											</td>
											<td <?php echo $cls; ?>><?php echo $product->prebor_in; ?></td>
											<td <?php echo $cls; ?>><?php echo $product->prebor_out; ?></td>
											<td>
												<?php if ($product->imgbordado != "") : ?>
													<img src="storage/products/<?php echo $product->imgbordado; ?>" style="width:64px;" onclick="abrir_imagen('storage/products/<?php echo $product->imgbordado; ?>');">
												<?php endif; ?>
											</td>
											<td <?php echo $cls; ?>>
												<span class='label label-primary w-100 mt-1 block cursor'>Ficha</span>

												<?php
												if ($product->secuencia == "" || is_null($product->secuencia)) {
												} else {
													echo "<a target='_blank' href='storage/secuencias/" . $product->secuencia . "' class='label label-warning w-100 mt-1 block'>Secuencia</a>";
												}
												?>
											</td>
											<td <?php echo $cls; ?>><?php if ($product->is_active) : ?><i class="fa fa-check"></i><?php endif; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php
								} else {
								?>
									<thead>
										<th>Modelo</th>
										<th>Cliente</th>
										<th>Descripción</th>
										<th>Fec. act.</th>
										<th>Imagen</th>
										<th>Bordado</th>
										<th></th>
										<th></th>
									</thead>
									<?php foreach ($products as $product) : ?>
										<tr>
											<td><?php echo $product->code; ?></td>
											<td><?php echo $product->cliente; ?></td>
											<td><?php echo $product->name; ?></td>


											<td><?php echo date("d-m-Y", strtotime($product->fecact)); ?></td>
											<td>
												<?php if ($product->image != "") : ?>
													<a href="#" onclick="abrir_imagen('storage/products/<?php echo $product->image; ?>');" class="btn btn-xs btn-default">
														<img src="storage/products/<?php echo $product->image; ?>" style="width:64px;">
													</a>

												<?php endif; ?>
											</td>
											<td>
												<?php if ($product->imgbordado != "") : ?>
													<a href="#" onclick="abrir_imagen('storage/products/<?php echo $product->imgbordado; ?>');" class="btn btn-xs btn-default">
														<img src="storage/products/<?php echo $product->imgbordado; ?>" style="width:64px;">
													</a>
												<?php endif; ?>
											</td>
											<td>
												<span class='label label-primary w-100 mt-1 block cursor ficha_tecnica' role="button" data-toggle="modal" data-target="#formulario" onclick="ficha_tecnica('<?php echo $product->code; ?>');">Ficha</span>

												<?php
												if ($product->secuencia == "" || is_null($product->secuencia)) {
												} else {
													echo "<a target='_blank' href='storage/secuencias/" . $product->secuencia . "' class='label label-warning w-100 mt-1 block'>Secuencia</a>";
												}
												?>
											</td>
											<td><?php if ($product->is_active) : ?><i class="fa fa-check"></i><?php endif; ?></td>
										</tr>
									<?php endforeach; ?>
								<?php
								}
								?>
							</table>
						</div>
					</div>
				</div>

			<?php
			} else {
			?>
				<div class="alert alert-info">
					<h2>No hay productos</h2>
					<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</b>.</p>
				</div>
			<?php
			}

			?>
		</div>

		<div id="popup_editar" style="display: none;">
			<div class="content-popup">
				<div class="close"><a href="#" id="close_editar"><img src="../css/images/close.png" /></a></div>
				<div>
					<div class="box box-primary table-responsive">
						<img src="" id="imagen_grande" class="thumbnail">
						<span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
					</div>
				</div>
			</div>
		</div>
		<div class="popup-overlay"></div>
	</div>
	<div class="modal fade" id="formulario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document" style="width: 90%;">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<h3 class="modal-title" id="exampleModalLabel">Ficha</h3>
				</div>
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#home">Ficha Técnica</a></li>
						<li><a data-toggle="tab" href="#menu1">Manual de Operaciones</a></li>
						<li><a data-toggle="tab" href="#menu2">Medidas</a></li>
						<li><a data-toggle="tab" href="#adjunto">Archivo Adjunto</a></li>
						<li><a data-toggle="tab" href="#observaciones">Observaciones</a></li>
					</ul>
					<div class="tab-content">
						<div id="home" class="tab-pane fade in active">
							<h3>Ficha Técnica</h3>
							<div class="row">
								<div class="col-md-4">
									<img src="img/logo.png" width="100">
								</div>
								<div class="col-md-3">
									<h4 style="border-bottom: solid 1px rgba(0,0,0,.1);" class="bold text-center">Modelo</h4>
								</div>
								<div class="col-md-2">
									<h4 style="border-bottom: solid 1px rgba(0,0,0,.1);" class="bold text-center">Nº</h4>
								</div>
								<div class="col-md-3">
									<h4 style="border-bottom: solid 1px rgba(0,0,0,.1);" class="bold text-center" id="num_modelo"></h4>
								</div>
								<div class="col-md-12">
									<h4 class="bold">A. IDENTIFICACIÓN DEL PRODUCTO</h4>
								</div>
								<div class="col-md-12 row">
									<table class="table table-bordered table-hover" id="div_identificacion">

									</table>
									<div class="col-md-12">
										<h4 class="bold">MATERIALES y COMPLEMENTOS</h4>
									</div>
									<div class="form-row">
										<table class="table table-bordered table-hover" id="div_complementos">

										</table>
									</div>
								</div>

								<div class="col-md-12">
									<h4 class="bold">B. DESCRIPCIÓN DEL ESTILO</h4>
								</div>
								<div class="col-md-12 text-center">
									<img id="img_producto" class="w-100">
								</div>

								<div class="col-md-12">
									<h4 class="bold">C. MODIFICACIONES</h4>
								</div>
								<div class="col-md-12 row">
									<div class="col-md-6">
										<label for="">
											Modificación:
										</label>
										<input type="text" class="form-control rounded-pill" id="modificacion_txt">
									</div>
									<div class="col-md-3">
										<label for="">
											Elaborado por:
										</label>
										<input type="text" class="form-control rounded-pill" id="elaborado_por">
									</div>
									<div class="col-md-2">
										<label for="">
											Ultima Modificacion:
										</label>

										<input autocomplete="false" type="date" id="u_modificacion" name="u_modificacion" class="form-control rounded-pill" placeholder="Fecha de Compra">
									</div>
									<div class="col-md-1" id="div_guardar_modificacion">
									</div>
									<table class="table table-bordered table-hover" id="div_modificacion">

									</table>
								</div>

								<div class="col-md-12 mt-2 text-right">
									<a class="btn btn-outline-dark rounded-pill" id="para_pdf_ficha_1">Exportar PDF</a>
									<table class="table table-bordered table-hover" id="div_observaciones">

									</table>
								</div>
							</div>
						</div>
						<div id="menu1" class="tab-pane fade">
							<h3>Manual de Operaciones</h3>
							<p>Instrucciones de Producción</p>
							<div class="panel-group" id="accordion">

							</div>
							<a class="btn btn-outline-dark rounded-pill" id="para_pdf_manual">Exportar PDF</a>
						</div>
						<div id="menu2" class="tab-pane fade">
							<h3>Medidas</h3>
							<p>MATRIZ DE TALLAS</p>
							<table class="table table-bordered table-hover" id="tabla_medidas">
								<thead>
									<tr>
										<th>Descripción</th>
										<th>2</th>
										<th>4</th>
										<th>6</th>
										<th>8</th>
										<th>10</th>
										<th>12</th>
										<th>14</th>
										<th>16</th>
										<th>S</th>
										<th>M</th>
										<th>L</th>
										<th>XL</th>
										<th>2XL</th>
										<th>3XL</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<div class="row">
							</div>
							<a class="btn btn-outline-dark rounded-pill" id="para_pdf">Exportar PDF</a>
						</div>
						<div id="adjunto" class="tab-pane fade">
							<h3>Archivo Adjunto</h3>
							<p>Si ya hay un archivo y se sube otro, el anterior será reemplazado</p>
							<div class="row">
								<div class="form-row">
									<div class="col-md-12">
										<label for="archivo_adjunto">Adjuntar Archivo <i class="fa fa-camera"></i></label>
										<input type="file" name="archivo_adjunto" id="archivo_adjunto" style="display: none;">
									</div>
									<div class="col-md-12" style="margin-top: 1rem;">
										<div id="div_archivo_adjunto"></div>
										<div id="nombre_ruta_archivo" class="alert alert-primary"></div>
									</div>
									<div class="col-md-12" style="margin-top: 1rem; text-align: center;">
										<span class="btn btn-success rounded-pill" id="btn_guardar_adjunto">Guardar Adjunto</span>
									</div>
								</div>
							</div>
						</div>
						<div id="observaciones" class="tab-pane fade">
							<h3>Observaciones</h3>
							<table class="table table-bordered table-hover" id="div_observacion_obs">

							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer txt-left">
					<span class="btn btn-danger rounded-pill" type="button" data-dismiss="modal" id="cerrar_formulario">
						Cerrar
					</span>
				</div>
			</div>
		</div>
	</div>
</section><!-- /.content -->


<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js"></script>
<script type="text/javascript">
	var modelo_ = "";

	function thePDF() {
		var doc = new jsPDF('p', 'pt');
		//doc.setFontSize(26);
		//doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val; ?>", 40, 65);
		doc.setFontSize(18);
		doc.text("Reporte de productos", 40, 80);
		//doc.setFontSize(12);
		//doc.text("Usuario: <?php echo Core::$user->name . " " . Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s"); ?> ", 40, 90);
		var columns = [{
				title: "Modelo",
				dataKey: "id"
			},
			{
				title: "Nombre del Producto",
				dataKey: "name"
			},
			{
				title: "Precio Minimo",
				dataKey: "price_in"
			},
			{
				title: "Precio Maximo",
				dataKey: "price_out"
			},
			{
				title: "Unidad",
				dataKey: "unidad"
			},
			{
				title: "Presentacion",
				dataKey: "presentacion"
			},
			{
				title: "Cliente",
				dataKey: "cliente"
			},
			{
				title: "Minima en Inv.",
				dataKey: "inv_min"
			},
			{
				title: "Activo",
				dataKey: "activo"
			},
		];
		var rows = [
			<?php foreach ($products as $product) : ?> {
					"id": "<?php echo $product->code; ?>",
					"name": "<?php echo $product->name; ?>",
					"price_in": "S/. <?php echo number_format($product->price_in, 2, '.', ','); ?>",
					"price_out": "S/. <?php echo number_format($product->price_in_2, 2, '.', ','); ?>",
					"unidad": "<?php echo $product->unit; ?>",
					"presentacion": "<?php echo $product->presentation; ?>",
					"cliente": "<?php if ($product->cliente_id != null) {
									echo $product->getCliente()->name;
								} else {
									echo "----";
								}  ?>",
					"inv_min": "<?php echo $product->inventary_min; ?>",
					"activo": "<?php if ($product->is_active == 1) {
									echo 'Si';
								} else {
									echo "No";
								} ?>",

				},
			<?php endforeach; ?>
		];
		doc.autoTable(columns, rows, {
			theme: 'grid',
			overflow: 'linebreak',
			styles: {
				fillColor: <?php echo Core::$pdf_table_fillcolor; ?>
			},
			columnStyles: {
				id: {
					fillColor: <?php echo Core::$pdf_table_column_fillcolor; ?>
				}
			},
			margin: {
				top: 100
			},
			afterPageContent: function(data) {}
		});
		doc.setFontSize(12);
		doc.text("<?php echo Core::$pdf_footer; ?>", 40, doc.autoTableEndPosY() + 25);
		<?php
		$con = ConfigurationData::getByPreffix("report_image");
		if ($con != null && $con->val != "") :
		?>
			var img = new Image();
			img.src = "storage/configuration/<?php echo $con->val; ?>";
			img.onload = function() {
				doc.addImage(img, 'PNG', 495, 20, 60, 60, 'mon');
				doc.save('products-<?php echo date("d-m-Y h:i:s", time()); ?>.pdf');
			}
		<?php else : ?>
			doc.save('products-<?php echo date("d-m-Y h:i:s", time()); ?>.pdf');
		<?php endif; ?>
	}

	function cerrar_editar() {
		$('#close_editar').click();
	}

	function abrir_imagen(codigo) {
		$("#imagen_grande").attr('src', codigo);
		$('#popup_editar').fadeIn('slow');
		$('.popup-overlay').fadeIn('slow');
		$('.popup-overlay').height($(window).height());
		return false;
	}

	function get_complementos(num_modelo) {
		$("#div_complementos").empty();
		$.post('core/app/view/fichaTecnica.php?parAccion=get_complementos', {
			num_modelo: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#div_complementos").append(`
                <tr>
                  <td style="padding: .5rem;">
                    <div class="text-right">
                      <input class="form-control rounded-pill br-4" style="font-weight: bold; text-align: right;" type="text" id="txt_titulo_` + val.id + `" value="` + val.titulo + `">
                    </div>
                  </td>
                  <td style="padding: .5rem;">
                    <div>
                      <input class="form-control rounded-pill br-4" type="text" id="txt_complemento_` + val.id + `" value="` + val.complemento + `">
                    </div>
                  </td>
                  <td style="padding: .5rem;">
                    <span class="cursor w-100 btn btn-outline-warning btn-sm rounded-pill" onclick="edit_complemento(` + val.id + `);"><i class="fa fa-pencil"></i></span>
                    <span class="cursor w-100 mt-1 btn btn-outline-danger btn-sm rounded-pill" onclick="delete_complemento(` + val.id + `);"><i class="fa fa-trash"></i></span>
                  </td>
                </tr>
            `);
			});


			$("#div_complementos").append(`<tr>
            <td><input type="text" class="form-control rounded-pill" id="txt_titulo"></td>
            <td><input type="text" class="form-control rounded-pill" id="txt_complemento"></td>
            <td><span class="w-100 cursor btn btn-sm rounded-pill btn-success" onclick="guardar_complemento('` + num_modelo + `');"><i class="fa fa-plus"></i></span></td>
        </tr>`);
		});
	}

	function get_identificacion(num_modelo) {
		$("#div_identificacion").empty();
		$.post('core/app/view/fichaTecnica.php?parAccion=get_identificacion', {
			num_modelo: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#div_identificacion").append(`
                <tr>
                  <td style="padding: .5rem;">
                    <div class="text-right">
                      <input class="form-control rounded-pill br-4" style="font-weight: bold; text-align: right;" type="text" id="txt_titulo_i_` + val.id + `" value="` + val.titulo + `">
                    </div>
                  </td>
                  <td style="padding: .5rem;">
                    <div>
                      <input class="form-control rounded-pill br-4" type="text" id="txt_complemento_i_` + val.id + `" value="` + val.complemento + `">
                    </div>
                  </td>
                  <td style="padding: .5rem;">
                    <span class="cursor w-100 btn btn-outline-warning btn-sm rounded-pill" onclick="edit_identificacion(` + val.id + `);"><i class="fa fa-pencil"></i></span>
                    <span class="cursor w-100 mt-1 btn btn-outline-danger btn-sm rounded-pill" onclick="delete_identificacion(` + val.id + `);"><i class="fa fa-trash"></i></span>
                  </td>
                </tr>
            `);
			});


			$("#div_identificacion").append(`<tr>
				<td><input type="text" class="form-control rounded-pill" id="txt_titulo_i"></td>
				<td><input type="text" class="form-control rounded-pill" id="txt_complemento_i"></td>
				<td><span class="w-100 cursor btn btn-sm rounded-pill btn-success" onclick="guardar_identificacion('` + num_modelo + `');"><i class="fa fa-plus"></i></span></td>
			</tr>`);
		});
	}

	function get_archivo_adjunto(num_modelo) {
		$("#div_archivo_adjunto").empty();
		$.post("core/app/view/fichaTecnica.php?parAccion=get_archivo_adjunto", {
			num_modelo: num_modelo
		}, function(response) {
			if (response == "false" || !response) {
				$("#div_archivo_adjunto").append(`<p class="alert alert-danger">NADA CARGADO...</p>`);
			} else {
				var obj = JSON.parse(response);
				$("#div_archivo_adjunto").append(`<a href="core/app/view/img-colaboradores/${obj.archivo}" target="_blank">${obj.archivo}</a>`);
			}
		});
	}

	function get_observaciones(num_modelo) {
		$("#div_observacion_obs").empty();
		$.post('core/app/view/fichaTecnica.php?parAccion=get_observaciones', {
			num_modelo: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#div_observacion_obs").append(`
                <tr>
                  <td style="padding: .5rem;">
                    <div class="text-right">
                      <input class="form-control rounded-pill br-4" style="font-weight: bold; text-align: right;" type="text" id="txt_titulo_obs_` + val.id + `" value="` + val.observacion + `">
                    </div>
                  </td>
                  <td style="padding: .5rem;">
                    <div>
                      <input class="form-control rounded-pill br-4" type="text" id="txt_detalle_obs_` + val.id + `" value="` + val.detalle + `">
                    </div>
                  </td>
                  <td style="padding: .5rem;">
                    <span class="cursor w-100 btn btn-outline-warning btn-sm rounded-pill" onclick="edit_observacion(` + val.id + `);"><i class="fa fa-pencil"></i></span>
                    <span class="cursor w-100 mt-1 btn btn-outline-danger btn-sm rounded-pill" onclick="delete_observacion(` + val.id + `);"><i class="fa fa-trash"></i></span>
                  </td>
                </tr>
            `);
			});


			$("#div_observacion_obs").append(`<tr>
				<td><input type="text" class="form-control rounded-pill" id="txt_titulo_obs" placeholder="Observacion"></td>
				<td><input type="text" class="form-control rounded-pill" id="txt_detalle_obs" placeholder="Detalle"></td>
				<td><span class="w-100 cursor btn btn-sm rounded-pill btn-success" onclick="guardar_observacion('` + num_modelo + `');"><i class="fa fa-plus"></i></span></td>
			</tr>`);
		});
	}

	function get_modificacion(num_modelo) {
		$("#div_modificacion").empty();
		$.post('core/app/view/fichaTecnica.php?parAccion=get_modificacion', {
			num_modelo: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			$.each(obj.Records, function(index, val) {
				$("#div_modificacion").append(`
                <tr>
                  <td style="padding: .5rem;">
                    <div class="text-right">
                      <input class="form-control rounded-pill br-4" style="font-weight: bold; text-align: right;" type="text" id="txt_titulo_m_` + val.id + `" value="` + val.titulo + `">
                    </div>
                  </td>
                  <td style="padding: .5rem;">
                    <div>
                      <input class="form-control rounded-pill br-4" type="text" id="txt_complemento_m_` + val.id + `" value="` + val.aprobado_por + `">
                    </div>
                  </td>
                  <td style="padding: .5rem;">
                    <div>
                      <input class="form-control rounded-pill br-4" type="text" id="txt_fecha_m_` + val.id + `" value="` + val.ultima_modificacion + `">
                    </div>
                  </td>
                  <td style="padding: .5rem;">
                    <span class="cursor w-100 btn btn-outline-warning btn-sm rounded-pill" onclick="edit_modificacion(` + val.id + `);"><i class="fa fa-pencil"></i></span>
                    <span class="cursor w-100 mt-1 btn btn-outline-danger btn-sm rounded-pill" onclick="delete_modificacion(` + val.id + `);"><i class="fa fa-trash"></i></span>
                  </td>
                </tr>
            `);
			});

			$("#div_guardar_modificacion").empty();
			$("#div_guardar_modificacion").append(`
                <span class="w-100 cursor btn btn-sm rounded-pill btn-success" onclick="guardar_modificacion('` + num_modelo + `');"><i class="fa fa-plus"></i></span>
            `);
		});
	}

	function ficha_tecnica(num_modelo) {
		$("#modificacion_txt").val('');
		$("#para_pdf").attr('href', 'core/app/view/pdf-ficha_tecnica.php?num_modelo=' + num_modelo);
		$("#para_pdf_manual").attr('href', 'core/app/view/pdf-ficha_tecnica_manual.php?num_modelo=' + num_modelo);
		$("#para_pdf_ficha_1").attr('href', 'core/app/view/pdf-ficha_tecnica_producto.php?num_modelo=' + num_modelo);
		$("#btn_guardar_adjunto").attr("onclick", "guardar_adjunto('" + num_modelo + "');");
		modelo_ = num_modelo;
		$("#num_modelo").text(num_modelo);
		$.post('core/app/view/fichaTecnica.php?parAccion=get_ficha', {
			num_modelo: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				var ficha = obj.Records;

				$("#elaborado_por").val(ficha.elaborado_por);
				$("#revisado_por").val(ficha.revisado_por);
				$("#aprobado_por").val(ficha.aprobado_por);
				$("#u_modificacion").val(ficha.u_modificacion);

				$("#btn_ficha_tecnica").text("Actualizar");
				$("#btn_ficha_tecnica").attr('onclick', 'update_ficha("' + num_modelo + '");');

				if (ficha.descripcion == "" || ficha.descripcion == null) {
					$("#desc_prenda").text("-");
				} else {
					$("#desc_prenda").text(ficha.descripcion);
				}

				$("#img_producto").attr('src', 'storage/products/' + ficha.image);

				get_complementos(num_modelo);
				get_identificacion(num_modelo);
				get_modificacion(num_modelo);
				get_archivo_adjunto(num_modelo);
				get_observaciones(num_modelo);

			} else {}
		});

		get_instrucciones(num_modelo);
		get_medidas(num_modelo);
	}

	function guardar_instruccion(id_etapa, num_modelo) {
		$.post('core/app/view/fichaTecnica.php?parAccion=save_instruccion', {
			id_etapa: id_etapa,
			paso: $("#paso_" + id_etapa).val(),
			instruccion: $("#instruccion_" + id_etapa).val(),
			orden: $("#orden_" + id_etapa).val(),
			code_producto: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_instrucciones(num_modelo);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function guardar_observacion(num_modelo) {
		$.post('core/app/view/fichaTecnica.php?parAccion=guardar_observacion', {
			titulo: $("#txt_titulo_obs").val(),
			detalle: $("#txt_detalle_obs").val(),
			code_producto: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_observaciones(num_modelo);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function guardar_medidas(num_modelo) {
		$.post('core/app/view/fichaTecnica.php?parAccion=guardar_medidas', {
			num_modelo: num_modelo,
			descripcion: $("#descripcion").val(),
			t_2: $("#t_2").val(),
			t_4: $("#t_4").val(),
			t_6: $("#t_6").val(),
			t_8: $("#t_8").val(),
			t_10: $("#t_10").val(),
			t_12: $("#t_12").val(),
			t_14: $("#t_14").val(),
			t_16: $("#t_16").val(),
			s: $("#s").val(),
			m: $("#m").val(),
			l: $("#l").val(),
			xl: $("#xl").val(),
			xxl: $("#xxl").val(),
			xxxl: $("#xxxl").val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_medidas(num_modelo);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}


	function edit_complemento(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=edit_complemento', {
			id: id,
			titulo: $("#txt_titulo_" + id).val(),
			complemento: $("#txt_complemento_" + id).val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				ficha_tecnica(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function edit_observacion(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=edit_observacion', {
			id: id,
			observacion: $("#txt_titulo_obs_" + id).val(),
			detalle: $("#txt_detalle_obs_" + id).val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_observaciones(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function delete_complemento(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=delete_complemento', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				ficha_tecnica(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function guardar_complemento(num_modelo) {
		$.post('core/app/view/fichaTecnica.php?parAccion=guardar_complemento', {
			titulo: $("#txt_titulo").val(),
			complemento: $("#txt_complemento").val(),
			code_producto: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				ficha_tecnica(num_modelo);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}





	function edit_identificacion(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=edit_identificacion', {
			id: id,
			titulo: $("#txt_titulo_i_" + id).val(),
			complemento: $("#txt_complemento_i_" + id).val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				ficha_tecnica(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function delete_identificacion(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=delete_identificacion', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				ficha_tecnica(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function guardar_identificacion(num_modelo) {
		$.post('core/app/view/fichaTecnica.php?parAccion=guardar_identificacion', {
			titulo: $("#txt_titulo_i").val(),
			complemento: $("#txt_complemento_i").val(),
			code_producto: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				ficha_tecnica(num_modelo);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	/***************************************************************/
	function edit_modificacion(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=edit_modificacion', {
			id: id,
			/*titulo: $("#txt_titulo_m_" + id).val(),
			complemento: $("#txt_complemento_m_" + id).val(),
			fecha: $("#txt_fecha_m_" + id).val(),*/
			titulo: $("#txt_titulo_m_" + id).val(),
			aprobado_por: $("#txt_complemento_m_" + id).val(),
			ultima_modificacion: $("#txt_fecha_m_" + id).val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				ficha_tecnica(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function delete_modificacion(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=delete_modificacion', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				ficha_tecnica(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function guardar_modificacion(num_modelo) {
		$.post('core/app/view/fichaTecnica.php?parAccion=guardar_modificacion', {
			/*titulo: $("#txt_titulo_m").val(),
			complemento: $("#txt_complemento_m").val(),
			fecha: $("#txt_fecha_m").val(),*/
			titulo: $("#modificacion_txt").val(),
			aprobado_por: $("#elaborado_por").val(),
			ultima_modificacion: $("#u_modificacion").val(),
			code_producto: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				ficha_tecnica(num_modelo);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}








	function edit_medida(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=edit_medida', {
			id: id,
			descripcion: $("#descripcion_" + id).val(),
			t_2: $("#t_2_" + id).val(),
			t_4: $("#t_4_" + id).val(),
			t_6: $("#t_6_" + id).val(),
			t_8: $("#t_8_" + id).val(),
			t_10: $("#t_10_" + id).val(),
			t_12: $("#t_12_" + id).val(),
			t_14: $("#t_14_" + id).val(),
			t_16: $("#t_16_" + id).val(),
			s: $("#s_" + id).val(),
			m: $("#m_" + id).val(),
			l: $("#l_" + id).val(),
			xl: $("#xl_" + id).val(),
			xxl: $("#xxl_" + id).val(),
			xxxl: $("#xxxl_" + id).val(),
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_medidas(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function delete_medida(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=delete_medida', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_medidas(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function get_medidas(num_modelo) {
		//$("#btn_medidas").attr('onclick', 'guardar_medidas('+num_modelo+');');
		$.post('core/app/view/fichaTecnica.php?parAccion=get_medidas', {
			num_modelo: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			$("#tabla_medidas").find('tbody').empty();
			$.each(obj.Records, function(index, val) {
				$("#tabla_medidas").find('tbody').append(`<tr>
    				<th><textarea class="form-control" style="width: 260px; resize: none;" type="text" value="` + val.descripcion + `" id="descripcion_` + val.id + `">` + val.descripcion + `</textarea></th>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.t_2 + `" id="t_2_` + val.id + `"></td>      
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.t_4 + `" id="t_4_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.t_6 + `" id="t_6_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.t_8 + `" id="t_8_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.t_10 + `" id="t_10_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.t_12 + `" id="t_12_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.t_14 + `" id="t_14_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.t_16 + `" id="t_16_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.s + `" id="s_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.m + `" id="m_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.l + `" id="l_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.xl + `" id="xl_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.xxl + `" id="xxl_` + val.id + `"></td>
	              	<td><input class="form-control rounded-pill" type="text" value="` + val.xxxl + `" id="xxxl_` + val.id + `"></td>
	              	<td>
	              		<span class="cursor w-100 btn btn-outline-warning btn-sm rounded-pill" onclick="edit_medida(` + val.id + `);"><i class="fa fa-pencil"></i></span>
	              		<span class="cursor w-100 mt-1 btn btn-outline-danger btn-sm rounded-pill" onclick="delete_medida(` + val.id + `);"><i class="fa fa-trash"></i></span>
	              	</td>
	            </tr>`);
			});
			$("#tabla_medidas").find('tbody').append(`<tr>
    				<td><textarea class="form-control" id="descripcion"></textarea></td>
	              	<th><input type="text" class="form-control rounded-pill" id="t_2"></th>      
	              	<td><input type="text" class="form-control rounded-pill" id="t_4"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="t_6"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="t_8"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="t_10"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="t_12"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="t_14"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="t_16"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="s"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="m"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="l"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="xl"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="xxl"></td>
	              	<td><input type="text" class="form-control rounded-pill" id="xxxl"></td>
	              	<td><span class="w-100 cursor btn btn-sm rounded-pill btn-success" onclick="guardar_medidas('` + num_modelo + `');"><i class="fa fa-plus"></i></span></td>
	            </tr>`);
		});
	}

	function guardar_maquina(num_modelo) {
		$.post('core/app/view/fichaTecnica.php?parAccion=guardar_maquina', {
			maquina: $("#maquina").val(),
			code_producto: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_instrucciones(num_modelo);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function get_instrucciones(num_modelo) {
		$("#accordion").empty();
		$.post('core/app/view/fichaTecnica.php?parAccion=get_instruccion', {
			num_modelo: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				var instrucciones = obj.Records;

				var cant = obj.Maquinas;

				var html = `<div class="panel panel-primary">
                    			<div class="panel-heading">
                      				<h4 class="panel-title">
                        				<a data-toggle="collapse" data-parent="#accordion" href="#collapse0">
                          					MAQUINAS
                        				</a>
                      				</h4>
                    			</div>
                    			<div id="collapse0" class="panel-collapse collapse in">
                      				<div class="panel-body">
                        				<table class="table table-bordered table-hover" id="tabla_maquinas">
                          					<tr>
                            					<th rowspan="` + parseInt(cant.length + 1) + `">
                              						PUNTADAS POR PULGADA:      
                            					</th>
                          					</tr>`;
				$.each(cant, function(index, val) {
					html += `<tr>
	                            					<td>
	                              						` + val.maquina + `
	                        						</td>
	                        						<td>
		                        						<span title="Eliminar Maquina" class="cursor bold" onclick="eliminar_maquina(` + val.id + `);"><i class="fa fa-trash" style="color: #d73925;"></i> Eliminar</span>
		                        					</td>
	                          					</tr>`;
				});
				html += `<tr>
		                        					<td colspan="2"><input type="text" id="maquina" placeholder="Maquina" class="form-control rounded-pill"></td>
		                        				</tr>
		                        				<tr>
		                        					<td></td>
		                        					<td colspan="2">
		                        						<button class="btn btn-success rounded-pill" id="btn_instrucciones" onclick="guardar_maquina('` + num_modelo + `');">Guardar</button>
		                        					</td>
		                        				</tr>
                        				</table>
                      				</div>
                    			</div>
                  			</div>`;

				if (instrucciones == false || instrucciones == "false" || instrucciones == 'false') {

				} else {

					$.each(instrucciones, function(index, val) {
						html += `<div class="panel panel-primary">
                			<div class="panel-heading">
                  				<h4 class="panel-title">
                    				<a data-toggle="collapse" data-parent="#accordion" href="#collapse` + val.id + `">
                      					` + val.etapa + `
                    				</a>
                  				</h4>
                			</div>
                			<div id="collapse` + val.id + `" class="panel-collapse collapse">
                  				<div class="panel-body">
                    				<table class="table table-bordered table-hover" id="tabla_` + val.id + `">`;

						$.each(val.pasos, function(i, v) {
							//console.log(val.pasos.length);
							html += `<tr>
                            				<td style="width: 200px;">
                                      <input class="form-control rounded-pill" id="paso_` + v.id + `" value="` + v.paso + `" style="font-weight: bold; font-size: 1.5rem;">
                                    </td>
                            				<td>
                                      <textarea class="form-control" id="instruccion_` + v.id + `" style="resize: none;">` + v.instruccion.replace(/<[^>]*>?/gm, '') + `</textarea></td>
                            				<td style="width: 50px;">
                                      <input class="form-control rounded-pill" id="orden_` + v.id + `" value="` + v.orden + `"></td>
                            				<td style="width: 50px;">
                            					<span class="cursor w-100 btn btn-outline-warning btn-sm rounded-pill" onclick="edit_instruccion(` + v.id + `);"><i class="fa fa-pencil"></i></span>
	              								<span class="cursor w-100 mt-1 btn btn-outline-danger btn-sm rounded-pill" onclick="eliminar_paso(` + v.id + `);"><i class="fa fa-trash"></i></span>
                        					</td>
                          				</tr>`;
						});
						html += `
                        				<tr>
                        					<td><input type="text" id="paso_` + val.id + `" placeholder="Paso" class="form-control rounded-pill"></td>
                        					<td><textarea type="text" id="instruccion_` + val.id + `" placeholder="Instruccion" class="form-control"></textarea></td>
                        					<td><input class="form-control rounded-pill" id="orden_` + val.id + `"></td>
                        					<td>
                        						<button class="btn btn-success rounded-pill" id="btn_instrucciones" onclick="guardar_instruccion(` + val.id + `,'` + num_modelo + `');"><i class="fa fa-plus"></i></button>
                        					</td>
                        				</tr>
                        			</table>
                  				</div>
                			</div>
              			</div>`;
						/*$("#current-files").sortable({
						    connectWith: "#selected-files"
						});*/
						//$("#tabla_"+val.id).sortable();
						$("#tabla_" + val.id).sortable({
							items: 'tr:not(tr:first-child)',
							cursor: 'pointer',
							axis: 'y',
							dropOnEmpty: false,
							start: function(e, ui) {
								ui.item.addClass("selected");
							},
							stop: function(e, ui) {
								ui.item.removeClass("selected");
								$(this).find("tr").each(function(index) {
									if (index > 0) {
										$(this).find("td").eq(2).html(index);
									}
								});
							}
						});
					});
					$("#accordion").append(html);
				}
			}
		});
	}

	function save_ficha(num_modelo) {
		$.post('core/app/view/fichaTecnica.php?parAccion=save_ficha', {
			tejido: $("#txt_tejido").val(),
			cinta: $("#txt_cinta").val(),
			etiqueta: $("#txt_etiqueta").val(),
			estampado: $("#txt_estampado").val(),
			code_producto: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: `<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">
                        <strong>Realizado correctamente.</strong>
                    </div>`
				});
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function guardar_adjunto(num_modelo) {
		var file = _("archivo_adjunto").files[0];
		var formdata = new FormData();
		formdata.append("archivo_adjunto", file);

		formdata.append("num_modelo", num_modelo);

		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", progressHandler, false);
		ajax.addEventListener("load", completeHandler, false);
		ajax.addEventListener("error", errorHandler, false);
		ajax.addEventListener("abort", abortHandler, false);
		ajax.open("POST", "core/app/view/fichaTecnica.php?parAccion=guardar_adjunto");
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
		//_("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
		var percent = (event.loaded / event.total) * 100;
		//_("progressBar").value = Math.round(percent);
	}

	function completeHandler(event) {
		bootbox.alert({
			message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
				'<strong>Realizado Correctamente.</strong>' +
				'</div>'
		});
		$("#archivo_adjunto").val('');
		$("#nombre_ruta_archivo").text('');
		get_archivo_adjunto(modelo_);
		// table = $(".datatable").DataTable();
		// table.ajax.reload();
		//limpiar_formulario();
		//_("progressBar").value = 0;
		//$("#cerrar_formulario_docente").click();
		//get_all_colaboradores();
	}

	function errorHandler(event) {
		_("status").innerHTML = "Upload Failed";
	}

	function abortHandler(event) {
		_("status").innerHTML = "Upload Aborted";
	}

	function eliminar_paso(id_paso) {
		$.post('core/app/view/fichaTecnica.php?parAccion=eliminar_paso', {
			id: id_paso
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_instrucciones(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function delete_observacion(id) {
		$.post('core/app/view/fichaTecnica.php?parAccion=eliminar_observacion', {
			id: id
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_observaciones(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function edit_instruccion(id_paso) {
		$.post('core/app/view/fichaTecnica.php?parAccion=edit_instruccion', {
			id: id_paso,
			paso: $("#paso_" + id_paso).val(),
			instruccion: $("#instruccion_" + id_paso).val(),
			orden: $("#orden_" + id_paso).val()
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_instrucciones(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function eliminar_maquina(id_maquina) {
		$.post('core/app/view/fichaTecnica.php?parAccion=eliminar_maquina', {
			id: id_maquina
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				get_instrucciones(modelo_);
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}

	function update_ficha(num_modelo) {
		$.post('core/app/view/fichaTecnica.php?parAccion=update_ficha', {
			tejido: $("#txt_tejido").val(),
			cinta: $("#txt_cinta").val(),
			etiqueta: $("#txt_etiqueta").val(),
			estampado: $("#txt_estampado").val(),
			elaborado_por: $("#elaborado_por").val(),
			revisado_por: $("#revisado_por").val(),
			aprobado_por: $("#aprobado_por").val(),
			u_modificacion: $("#u_modificacion").val(),
			code_producto: num_modelo
		}, function(data) {
			var obj = JSON.parse(data);
			if (obj.Result == "OK") {
				bootbox.alert({
					message: `<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">
                        <strong>Realizado correctamente.</strong>
                    </div>`
				});
			} else {
				bootbox.alert({
					message: `<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">+
                        <strong>Algo ha salido mal.</strong>
                    </div>`
				});
			}
		});
	}
	$(document).ready(function() {


		$('#close_editar').on('click', function() {
			//limpiar_formulario();
			$('#popup_editar').fadeOut('slow');
			$('.popup-overlay').fadeOut('slow');
			return false;
			flag = false;
		});
		console.log(<?php echo Core::$user->kind; ?>);
		if (<?php echo Core::$user->kind; ?> == 5 || <?php echo Core::$user->kind; ?> == 1 || <?php echo Core::$user->kind; ?> == 4 || <?php echo Core::$user->kind; ?> == 12) {

		} else {
			$(".ficha_tecnica").removeAttr('onclick');
			$(".ficha_tecnica").removeAttr('data-toggle')
			$(".ficha_tecnica").attr('disabled', true);
		}


		$(".datepicker").datetimepicker({
			format: "Y-m-d",
			timepicker: false,
			scrollInput: false
		});

		$("#archivo_adjunto").on("change", function() {
			$("#nombre_ruta_archivo").text("Archivo a Cargar: " + $(this).val().replace(/C:\\fakepath\\/i, ''));
		});
	});


	TableThing = function(params) {
		settings = {
			table: $('#example'),
			thead: []
		};

		this.fixThead = function() {
			settings.thead = [];
			$('tbody tr:eq(1) td', settings.table).each(function(i, v) {
				settings.thead.push($(v).width());
			});
			for (i = 0; i < settings.thead.length; i++) {
				$('thead th:eq(' + i + ')', settings.table).width(settings.thead[i]);
			}

			$(window).scroll(function() {
				var windowTop = $(window).scrollTop();

				if (windowTop > settings.table.offset().top) {
					$("thead", settings.table).addClass("fixed");
				} else {
					$("thead", settings.table).removeClass("fixed");
				}
			});
		}
	}
	$(function() {
		var table = new TableThing();
		table.fixThead();
		$(window).resize(function() {
			table.fixThead();
		});
	});
</script>