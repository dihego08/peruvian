<style type="text/css">
	.hdr {
		display: none;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h3>Nuevo Cliente</h3>
			<div class="box box-primary"><br>
				<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=addclient" role="form">
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Nombre/Razon Social*</label>
						<div class="col-md-6">
							<input type="text" name="name" class="form-control rounded-pill" required id="name" placeholder="Nombre">
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">DNI/RUC</label>
						<div class="col-md-6">


							<div class="input-group mb-3" style="display: flex;">
								<input type="text" name="no" class="form-control rounded-pill-left" placeholder="RUC ..." id="no" aria-label="Recipient's username" aria-describedby="basic-addon2">
								<div class="input-group-append">
									<button class="btn btn-outline-secondary" type="button" onclick="buscar_ruc();"><i class="fa fa-search"></i></button>
								</div>
							</div>

							<div id="resultado_ruc"></div>
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Direccion</label>
						<div class="col-md-6">
							<input type="text" name="address1" class="form-control rounded-pill" id="address1" placeholder="Direccion">
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Tipo de Pago</label>
						<div class="col-md-6">
							<select class="form-control rounded-pill" name="tipo_pago" id="tipo_pago">
								<option value="0">Efectivo</option>
								<option value="1">Bancarizado</option>
							</select>
						</div>
					</div>
					<div class="form-group hdr" id="div_1">
						<label for="inputEmail1" class="col-lg-2 control-label">Banco*</label>
						<div class="col-md-6">
							<select class="form-control rounded-pill" name="banco" id="banco">
								<option value="BCP">BCP</option>
								<option value="INTERBANK">INTERBANK</option>
								<option value="SCOTIABANK">SCOTIABANK</option>
								<option value="BBVA_CONTINENTAL">BBVA_CONTINENTAL</option>
								<option value="BANCO_DE_CREDITO">BANCO DE CREDITO</option>
								<option value="MiBanco">MiBanco</option>
							</select>
						</div>
					</div>
					<div class="form-group nro_cuenta_pnl hdr" id="div_2">
						<label for="inputEmail1" class="col-lg-2 control-label">Nro de Cuenta</label>
						<div class="col-md-6">
							<input type="text" name="nro_cuenta" class="form-control rounded-pill" id="nro_cuenta" placeholder="Número de Cuenta">
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

					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Activar Credito</label>
						<div class="col-md-6">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="has_credit">
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Limite de credito</label>
						<div class="col-md-6">
							<input type="text" name="credit_limit" class="form-control rounded-pill" id="" placeholder="Limite de credito">
						</div>
					</div>

					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Activar Acceso </label>
						<div class="col-md-6">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="is_active_access">
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-2 control-label">Password</label>
						<div class="col-md-6">
							<input type="password" name="password" class="form-control rounded-pill" id="phone1" placeholder="Password">
							<p class="text-muted">Acceso en (http://localhost/inventio-max/?view=clientaccess) con Email, Password y Acceso Activado</p>
						</div>
					</div>

					<p class="alert alert-info">* Campos obligatorios</p>

					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<button type="submit" class="btn btn-success rounded-pill">Agregar Cliente</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#tipo_pago").on('change', function() {
				if ($("#tipo_pago").val() == 0) {
					$("#div_1").addClass('hdr');
					$("#div_2").addClass('hdr');
				} else {
					$("#div_1").removeClass('hdr');
					$("#div_2").removeClass('hdr');
				}
			});
		});

		function buscar_ruc() {
			$("#resultado_ruc").empty();
			$("#resultado_ruc").append('<span class="badge badge-warning">Buscando</span>');


			/*$.post("https://incared.com/api/apirest", {
				action: 'getnumero',
				numero: $("#no").val()
			}, function(response) {
				var obj = JSON.parse(response);
				$("#resultado_ruc").empty();
				$("#resultado_ruc").append('<span class="badge badge-success">' + $("#no").val() + ' - ' + obj.rs + '</span>');


				$("#name").val(obj.rs);
				$("#address1").val(obj.direccion_string);
			});*/

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
</section>