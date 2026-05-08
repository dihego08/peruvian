<style>
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
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Directorio de Proveedores</h3>
			<div class="w-100 d-block text-right">
				<a href="index.php?view=newprovider" class="btn btn-outline-dark rounded-pill"><i class='fa fa-truck'></i> Nuevo Proveedor</a>
				<div class="btn-group">
					<button type="button" class="btn btn-outline-dark rounded-pill dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-download"></i> Descargar <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="report/providers-word.php">Word 2007 (.docx)</a></li>
						<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a>
					</ul>
				</div>
			</div>
			<div class="row" style="margin-bottom: 5rem;">
				<form class="form-row">
					<div class="col-md-4">
						<label for="">Desde</label>
						<input type="text" class="form-control rounded-pill clsDatePicker" id="desde" name="desde" value="<?php echo isset($_GET['desde']) ? $_GET['desde'] : '' ?>">
						<input type="hidden" name="view" value="providers">
					</div>
					<div class="col-md-4">
						<label for="">Hasta</label>
						<input type="text" class="form-control rounded-pill clsDatePicker" id="hasta" name="hasta" value="<?php echo isset($_GET['hasta']) ? $_GET['hasta'] : '' ?>">
					</div>
					<div class="col-md-4">
						<label for="">Mayor a</label>
						<input type="text" class="form-control rounded-pill" id="mayor_a" name="mayor_a" value="<?php echo isset($_GET['mayor_a']) ? $_GET['mayor_a'] : '' ?>">
					</div>
					<div class="col-md-12 text-center">
						<input type="submit" value="Filtrar" class="btn btn-success rounded-pill mt-1">
					</div>
				</form>
			</div>
			<?php
			if (isset($_GET['desde']) && isset($_GET['hasta']) && isset($_GET['mayor_a'])) {
				$users = PersonData::getProviders_filtro($_GET['desde'], $_GET['hasta'], $_GET['mayor_a']);
			} else {
				$users = PersonData::getProviders();
			}

			if (count($users) > 0) {
				// si hay usuarios
			?>
				<div class="box box-primary">
					<div class="box-body table-responsive">
						<table class="table table-bordered datatable table-hover" style="font-size: 12px;">
							<thead>
								<th>COD.</th>
								<th>Insumo/Material</th>
								<th>DNI/RUC</th>
								<th>Nombre / Razón social</th>
								<th>Direccion</th>
								<th>Banco</th>
								<th>Nro. de Cuenta</th>
								<th>Email</th>
								<th>Telefono</th>
								<th>WSP</th>
								<th>Forma Envío</th>
								<th># Compras</th>
								<th></th>
							</thead>
							<?php
							foreach ($users as $user) {
								$insumo = InsumosData::getById($user->id_insumo);
							?>
								<tr>
									<td><?php echo $user->id; ?></td>
									<td><?php echo $insumo->insumo; ?></td>
									<td><?php echo $user->no; ?></td>
									<td><?php echo $user->name . " " . $user->lastname; ?></td>
									<td>
										<span style="width: 120px; white-space: normal; display: block;">
											<?php echo $user->address1; ?>
										</span>
									</td>
									<td><?php echo $user->banco; ?></td>
									<td><?php echo $user->nro_cuenta; ?></td>
									<td>
										<span>
											<?php echo $user->email1; ?>
										</span>
									</td>
									<td><?php echo $user->phone1; ?></td>
									<td><?php echo $user->wsp; ?></td>
									<td><?php echo $user->forma_envio; ?></td>
									<td>
										<?php echo $user->num_compras; ?>
									</td>
									<td style="width:130px;">
										<a href="index.php?view=editprovider&id=<?php echo $user->id; ?>" class="btn btn-outline-warning d-block mt-1 btn-sm rounded-pill"><i class="fa fa-pencil"></i></a>
										<a href="index.php?view=delprovider&id=<?php echo $user->id; ?>" class="btn btn-outline-danger d-block mt-1 btn-sm rounded-pill"><i class="fa fa-trash"></i></a>

									</td>
								</tr>
							<?php

							}
							?>
						</table>
					</div>
				</div>
			<?php
			} else {
				echo "<p class='alert alert-danger'>No hay proveedores</p>";
			}
			?>
		</div>
	</div>
</section>



<script type="text/javascript">
	$(document).ready(function() {
		$("#desde").datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});
		$("#hasta").datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			altField: "#fecha_nacimiento_hidden",
			altFormat: "yy-mm-dd"
		});
	});

	function thePDF() {
		var doc = new jsPDF('landscape', 'pt');
		doc.setFontSize(26);
		doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val; ?>", 40, 65);
		doc.setFontSize(18);
		doc.text("DIRECTORIO DE PROVEEDORES", 40, 80);
		doc.setFontSize(12);
		doc.text("Usuario: <?php echo Core::$user->name . " " . Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s"); ?> ", 40, 90);
		var columns = [{
				title: "Id",
				dataKey: "id"
			},
			{
				title: "Insumo/Material",
				dataKey: "insumo"
			},
			{
				title: "DNI/RUC",
				dataKey: "no"
			},
			{
				title: "Nombre completo",
				dataKey: "name"
			},
			{
				title: "Direccion",
				dataKey: "address"
			},
			{
				title: "Banco",
				dataKey: "banco"
			},
			{
				title: "Nro. de Cuenta",
				dataKey: "nro_cuenta"
			},
			{
				title: "Email",
				dataKey: "email"
			},
			{
				title: "Telefono",
				dataKey: "phone"
			},
			{
				title: "WSP",
				dataKey: "wsp"
			},
			{
				title: "Forma Envío",
				dataKey: "forma_envio"
			},
		];
		var rows = [
			<?php foreach ($users as $product) {
				$insumo = InsumosData::getById($user->id_insumo);
			?> {
					"id": "<?php echo $product->id; ?>",
					"insumo": "<?php echo $insumo->insumo; ?>",
					"no": "<?php echo $product->no; ?>",
					"name": "<?php echo $product->name . " " . $product->lastname; ?>",
					"address": "<?php echo $product->address1; ?>",
					"banco": "<?php echo $product->banco; ?>",
					"nro_cuenta": "<?php echo $product->nro_cuenta; ?>",
					"email": "<?php echo $product->email1; ?>",
					"phone": "<?php echo $product->phone1; ?>",
					"wsp": "<?php echo $product->wsp; ?>",
					"forma_envio": "<?php echo $product->forma_envio; ?>",
				},
			<?php } ?>
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
				doc.save('providers-<?php echo date("d-m-Y h:i:s", time()); ?>.pdf');
			}
		<?php else : ?>
			doc.save('providers-<?php echo date("d-m-Y h:i:s", time()); ?>.pdf');
		<?php endif; ?>
	}
</script>