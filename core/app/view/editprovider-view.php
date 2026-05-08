<?php $user = PersonData::getById($_GET["id"]); ?>


<script type="text/javascript">
	$(function() {

		if ($("#banco").val() == 'SIN_BANCO') {

			$('.nro_cuenta_pnl').hide();
			$('.tipo_cuenta_pnl').hide();
			$('.tipo_moneda_pnl').hide();

		} else {

			$('.nro_cuenta_pnl').show();
			$('.tipo_cuenta_pnl').show();
			$('.tipo_moneda_pnl').show();

		}


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
		<h1>Editar Proveedor</h1>
		<br>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=updateprovider" role="form">
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Material/Insumo</label>
				<div class="col-md-6">
					<select class="form-control js-example-basic-single" id="id_insumo" name="id_insumo"></select>
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">DNI/RUC*</label>
				<div class="col-md-6">
					<!--<input type="text" name="no" value="<?php echo $user->no; ?>" class="form-control" id="no" placeholder="DNI/RUC">-->


					<div class="input-group mb-3" style="display: flex;">
						<input type="text" name="no" value="<?php echo $user->no; ?>" class="form-control" placeholder="RUC ..." id="no" aria-label="Recipient's username" aria-describedby="basic-addon2">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary" type="button" onclick="buscar_ruc();"><i class="fa fa-search"></i></button>
						</div>
					</div>

					<div id="resultado_ruc"></div>
				</div>
			</div>

			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Nombre / Razón social*</label>
				<div class="col-md-6">
					<input type="text" name="name" value="<?php echo $user->name; ?>" class="form-control" id="name" placeholder="Nombre / Razón social">
				</div>
			</div>
			<!--   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Apellido*</label>
    <div class="col-md-6">
      <input type="text" name="lastname" value="<?php echo $user->lastname; ?>" required class="form-control" id="lastname" placeholder="Apellido">
    </div>
  </div> -->
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Direccion*</label>
				<div class="col-md-6">
					<input type="text" name="address1" value="<?php echo $user->address1; ?>" class="form-control" required id="address1" placeholder="Direccion">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Banco*</label>
				<div class="col-md-6">
					<select class="form-control" name="banco" id="banco">
						<?php
						switch ($user->banco) {
							case 'BCP':
								echo "<option value=\"BCP\" selected>BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>
                  <option value=\"SIN_BANCO\">SIN_BANCO</option>";

								break;
							case 'INTERBANK':
								echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\" selected>INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>
                  <option value=\"SIN_BANCO\">SIN_BANCO</option>";
								break;
							case 'SCOTIABANK':
								echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\" selected>SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>
                  <option value=\"SIN_BANCO\">SIN_BANCO</option>";
								break;
							case 'BBVA_CONTINENTAL':
								echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\" selected>BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>
                  <option value=\"SIN_BANCO\">SIN_BANCO</option>";
								break;
							case 'BANCO_DE_CREDITO':
								echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\" selected>BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>
                  <option value=\"SIN_BANCO\">SIN_BANCO</option>";
								break;
							case 'MiBanco':
								echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\" selected>MiBanco</option>
                  <option value=\"SIN_BANCO\">SIN_BANCO</option>";
								break;
							case 'SIN_BANCO':
								echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>
                  <option value=\"SIN_BANCO\" selected>SIN_BANCO</option>";
								break;
							default:
								echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>
                  <option value=\"SIN_BANCO\">SIN_BANCO</option>";
								break;
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group nro_cuenta_pnl">
				<label for="inputEmail1" class="col-lg-2 control-label">Nro de Cuenta*</label>
				<div class="col-md-6">
					<input type="text" name="nro_cuenta" class="form-control" value="<?php echo $user->nro_cuenta; ?>" id="nro_cuenta" placeholder="Número de Cuenta">
				</div>
			</div>
			<div class="form-group tipo_cuenta_pnl">
				<label for="inputEmail1" class="col-lg-2 control-label">Tipo de Cuenta*</label>
				<div class="col-md-6">
					<select class="form-control" name="tipo_cuenta" id="tipo_cuenta">
						<?php
						if ($user->tipo_cuenta == 'corriente') {
							echo "<option>- Elegir opción -</option>";
							echo "<option value=\"corriente\" selected>Cuenta Corriente</option>";
							echo "<option value=\"ahorros\">Cuenta de Ahorros</option>";
						} elseif ($user->tipo_cuenta == 'ahorros') {
							echo "<option>- Elegir opción -</option>";
							echo "<option value=\"corriente\">Cuenta Corriente</option>";
							echo "<option value=\"ahorros\" selected>Cuenta de Ahorros</option>";
						} else {
							echo "<option selected>- Elegir opción -</option>";
							echo "<option value=\"corriente\">Cuenta Corriente</option>";
							echo "<option value=\"ahorros\">Cuenta de Ahorros</option>";
						}
						?>

					</select>

				</div>
			</div>
			<div class="form-group tipo_moneda_pnl">
				<label for="inputEmail1" class="col-lg-2 control-label">Tipo de Moneda*</label>
				<div class="col-md-6">
					<select class="form-control" name="tipo_moneda" id="tipo_moneda">
						<?php
						if ($user->tipo_moneda == 'SOL') {
							echo "<option>- Elegir opción -</option>";
							echo "<option value=\"SOL\" selected>Soles</option>";
							echo "<option value=\"DOL\">Dólares</option>";
						} elseif ($user->tipo_moneda == 'DOL') {
							echo "<option>- Elegir opción -</option>";
							echo "<option value=\"SOL\">Soles</option>";
							echo "<option value=\"DOL\" selected>Dólares</option>";
						} else {
							echo "<option selected>- Elegir opción -</option>";
							echo "<option value=\"SOL\">Soles</option>";
							echo "<option value=\"DOL\">Dólares</option>";
						}
						?>
					</select>

				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Forma de Envio</label>
				<div class="col-md-6">
					<input type="text" name="forma_envio" class="form-control" value="<?php echo $user->forma_envio; ?>" id="forma_envio" placeholder="Direccion">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Email</label>
				<div class="col-md-6">
					<input type="text" name="email1" value="<?php echo $user->email1; ?>" class="form-control" id="email" placeholder="Email">
				</div>
			</div>

			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">Telefono</label>
				<div class="col-md-6">
					<input type="text" name="phone1" value="<?php echo $user->phone1; ?>" class="form-control" id="inputEmail1" placeholder="Telefono">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail1" class="col-lg-2 control-label">WhatsApp</label>
				<div class="col-md-6">
					<input type="text" name="wsp" value="<?php echo $user->wsp; ?>" class="form-control" id="inputEmail1" placeholder="WhatsApp">
				</div>
			</div>


			<p class="alert alert-info">* Campos obligatorios</p>

			<div class="form-group">
				<div class="col-lg-offset-2 col-lg-10">
					<input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
					<button type="submit" class="btn btn-primary">Actualizar Proveedor</button>
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
				if(val.id == <?php echo $user->no; ?>){
					$("#id_insumo").append(`<option value="${val.id}" selected>${val.insumo}</option>`);
				}else{
					$("#id_insumo").append(`<option value="${val.id}">${val.insumo}</option>`);
				}
				
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
		$.get('https://softluttion.com/jossmp/sunatphp/example/consulta.php', {
			nruc: $("#no").val()
		}, function(data) {
			var obj = JSON.parse(data);
			console.log(obj);
			$("#resultado_ruc").empty();
			if (obj.success == "false") {
				$("#resultado_ruc").append('<span class="badge badge-success">' + $("#no").val() + ' - ' + obj.msg + '</span>');
			} else {
				$("#resultado_ruc").append('<span class="badge badge-success">' + $("#no").val() + ' - ' + obj.result.RazonSocial + '</span>');
			}

			$("#name").val(obj.result.RazonSocial);
			$("#address1").val(obj.result.Direccion);
		});
	}
</script>