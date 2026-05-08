<script type="text/javascript">
	$(function() {
		$("#banco").change(function() {
			if ($(this).val() == 'SIN_BANCO') {
				$('.nro_cuenta_pnl').hide();
				$('.tipo_cuenta_pnl').hide();
				$('.tipo_moneda_pnl').hide();
			} else {
				$('.nro_cuenta_pnl').show();
				$('.tipo_cuenta_pnl').show();
				$('.tipo_moneda_pnl').show();
			}
		});
	});
</script>
<div class="row">
	<div class="col-md-12">
		<h3>Nuevo Proveedor</h3>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=addprovider" role="form">
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Material/Insumo</label>
				<div class="col-md-6">
					<select class="form-control rounded-pill js-example-basic-single" id="id_insumo" name="id_insumo"></select>
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">DNI / RUC*</label>
				<div class="col-md-6">
					<div class="input-group mb-3" style="display: flex;">
						<input type="text" name="no" class="form-control rounded-pill-left" placeholder="RUC ..." id="no" aria-label="Recipient's username" aria-describedby="basic-addon2">
						<div class="input-group-append">
							<button class="btn btn-outline-dark" type="button" onclick="buscar_ruc();"><i class="fa fa-search"></i></button>
						</div>
					</div>
					<div id="resultado_ruc"></div>
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Nombre / Razón social*</label>
				<div class="col-md-6">
					<input type="text" name="name" class="form-control rounded-pill" id="name" placeholder="Nombre / Razón social">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Direccion*</label>
				<div class="col-md-6">
					<input type="text" name="address1" class="form-control rounded-pill" required id="address1" placeholder="Direccion">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Banco*</label>
				<div class="col-md-6">
					<select class="form-control rounded-pill" name="banco" id="banco">
						<option value="BCP">BCP</option>
						<option value="INTERBANK">INTERBANK</option>
						<option value="SCOTIABANK">SCOTIABANK</option>
						<option value="BBVA_CONTINENTAL">BBVA_CONTINENTAL</option>
						<option value="BANCO_DE_CREDITO">BANCO DE CREDITO</option>
						<option value="MiBanco">MiBanco</option>
						<option value="SIN_BANCO">SIN BANCO</option>
					</select>
				</div>
			</div>
			<div class="form-group nro_cuenta_pnl">
				<label for="inputEmail1" class="col-lg-2 control-label">Nro de Cuenta</label>
				<div class="col-md-6">
					<input type="text" name="nro_cuenta" class="form-control rounded-pill" id="nro_cuenta" placeholder="Número de Cuenta">
				</div>
			</div>
			<div class="form-group tipo_cuenta_pnl">
				<label for="inputEmail1" class="col-lg-2 control-label">Tipo de Cuenta</label>
				<div class="col-md-6">
					<select class="form-control rounded-pill" name="tipo_cuenta" id="tipo_cuenta">
						<option>- Elegir opción -</option>
						<option value="corriente">Cuenta Corriente</option>
						<option value="ahorros">Cuenta de Ahorros</option>
					</select>

				</div>
			</div>
			<div class="form-group tipo_moneda_pnl">
				<label for="inputEmail1" class="col-lg-2 control-label">Tipo de Moneda</label>
				<div class="col-md-6">
					<select class="form-control rounded-pill" name="tipo_moneda" id="tipo_moneda">
						<option>- Elegir opción -</option>
						<option value="SOL">Soles</option>
						<option value="DOL">Dólares</option>
					</select>

				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Forma de Envío</label>
				<div class="col-md-6">
					<input type="text" name="forma_envio" class="form-control rounded-pill" id="forma_envio" placeholder="Forma de Envío">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Email</label>
				<div class="col-md-6">
					<input type="text" name="email1" class="form-control rounded-pill" id="email1" placeholder="Email">
				</div>
			</div>

			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Telefono</label>
				<div class="col-md-6">
					<input type="text" name="phone1" class="form-control rounded-pill" id="phone1" placeholder="Telefono">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">WhatsApp</label>
				<div class="col-md-6">
					<input type="text" name="wsp" class="form-control rounded-pill" id="inputEmail1" placeholder="WhatsApp">
				</div>
			</div>
			<p class="alert alert-info">* Campos obligatorios</p>

			<div class="form-group">
				<div class="col-lg-offset-2 col-lg-10">
					<button type="submit" class="btn btn-success rounded-pill">Agregar Proveedor</button>
				</div>
			</div>
		</form>
	</div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
	function llenar_insumos() {
		$.post("core/app/view/insumos.php?parAccion=lista_insumos", function(response) {
			var obj = JSON.parse(response);

			$.each(obj.Records, function(index, val) {
				$("#id_insumo").append(`<option value="${val.id}">${val.insumo}</option>`);
			});
		});
	}
	$(document).ready(function() {
		llenar_insumos();
		$('.js-example-basic-single').select2();
	});

	function buscar_ruc() {
		$("#resultado_ruc").empty();
		$("#resultado_ruc").append('<span class="badge badge-warning">Buscando</span>');


		$.get("https://diegoaranibar.com/api_ruc/api.php", {
			ruc: $("#no").val()
		}, function(response) {
			//var obj = JSON.parse(response);
			var obj = response;
			$("#resultado_ruc").empty();
			if (obj.error === undefined) {
				$("#resultado_ruc").append('<span class="badge badge-success">' + $("#no").val() + ' - ' + obj.nombre + '</span>');

				$("#name").val(obj.nombre);
				$("#address1").val(obj.direccion);
			} else {
				$("#resultado_ruc").append('<span class="badge badge-danger">' + $("#no").val() + ' - ' + obj.error + '</span>');
			}

		});
	}
</script>