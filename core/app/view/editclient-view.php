<style type="text/css">
  .hdr {
    display: none;
  }
</style>
<section class="content">
  <?php $user = PersonData::getById($_GET["id"]);
  ?>
  <div class="row">
    <div class="col-md-12">
      <h3>Editar Cliente</h3>
      <br>
      <form class="form-horizontal" method="post" id="addproduct" action="index.php?view=updateclient" role="form">
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Nombre Comercial*</label>
          <div class="col-md-6">
            <input type="text" name="name" value="<?php echo $user->name; ?>" class="form-control rounded-pill" id="name" placeholder="Nombre">
          </div>
        </div>

        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Razon Social*</label>
          <div class="col-md-6">
            <input type="text" name="company" value="<?php echo $user->company; ?>" class="form-control rounded-pill" id="company" placeholder="Razon Social">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">DNI/RUC</label>
          <div class="col-md-6">
            <input type="text" name="no" value="<?php echo $user->no; ?>" class="form-control rounded-pill" id="no" placeholder="RFC/RUT">
          </div>
        </div>

        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Direccion</label>
          <div class="col-md-6">
            <input type="text" name="address1" value="<?php echo $user->address1; ?>" class="form-control rounded-pill" id="username" placeholder="Direccion">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Tipo de Pago</label>
          <div class="col-md-6">
            <select class="form-control rounded-pill" name="tipo_pago" id="tipo_pago">
              <?php
              if ($user->tipo_pago == 0) {
                echo '<option value="0" selected>Efectivo</option>
              <option value="1">Bancarizado</option>';
              } elseif ($user->tipo_pago == 1) {
                echo '<option value="0">Efectivo</option>
              <option value="1" selected>Bancarizado</option>';
              } else {
                echo '<option value="0">Efectivo</option>
              <option value="1">Bancarizado</option>';
              }
              ?>

            </select>
          </div>
        </div>
        <div class="form-group hdr" id="div_1">
          <label for="inputEmail1" class="col-lg-2 control-label">Banco*</label>
          <div class="col-md-6">
            <select class="form-control rounded-pill" name="banco" id="banco">
              <?php
              switch ($user->banco) {
                case 'BCP':
                  echo "<option value=\"BCP\" selected>BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>";

                  break;
                case 'INTERBANK':
                  echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\" selected>INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>";
                  break;
                case 'SCOTIABANK':
                  echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\" selected>SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>";
                  break;
                case 'BBVA_CONTINENTAL':
                  echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\" selected>BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>";
                  break;
                case 'BANCO_DE_CREDITO':
                  echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\" selected>BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>";
                  break;
                case 'MiBanco':
                  echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\" selected>MiBanco</option>";
                  break;
                default:
                  echo "<option value=\"BCP\">BCP</option>
                  <option value=\"INTERBANK\">INTERBANK</option>
                  <option value=\"SCOTIABANK\">SCOTIABANK</option>
                  <option value=\"BBVA_CONTINENTAL\">BBVA_CONTINENTAL</option>
                  <option value=\"BANCO_DE_CREDITO\">BANCO DE CREDITO</option>
                  <option value=\"MiBanco\">MiBanco</option>";
                  break;
              }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group nro_cuenta_pnl hdr" id="div_2">
          <label for="inputEmail1" class="col-lg-2 control-label">Nro de Cuenta</label>
          <div class="col-md-6">
            <input type="text" name="nro_cuenta" class="form-control rounded-pill" id="nro_cuenta" value="<?php echo $user->nro_cuenta; ?>" placeholder="Número de Cuenta">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Email</label>
          <div class="col-md-6">
            <input type="text" name="email1" value="<?php echo $user->email1; ?>" class="form-control rounded-pill" id="email" placeholder="Email">
          </div>
        </div>

        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Telefono</label>
          <div class="col-md-6">
            <input type="text" name="phone1" value="<?php echo $user->phone1; ?>" class="form-control rounded-pill" id="inputEmail1" placeholder="Telefono">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">WhatsApp</label>
          <div class="col-md-6">
            <input type="text" name="wsp" value="<?php echo $user->wsp; ?>" class="form-control rounded-pill" id="inputEmail1" placeholder="WhatsApp">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Activar Credito</label>
          <div class="col-md-6">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="has_credit" <?php if ($user->has_credit) {
                                                            echo "checked";
                                                          } ?>>
              </label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Credito</label>
          <div class="col-md-6">
            <input type="text" name="credit_limit" value="<?php echo $user->credit_limit; ?>" class="form-control rounded-pill" id="inputEmail1" placeholder="Credito">
          </div>
        </div>


        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Activar Acceso</label>
          <div class="col-md-6">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="is_active_access" <?php if ($user->is_active_access) {
                                                                  echo "checked";
                                                                } ?>>
              </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Password</label>
          <div class="col-md-6">
            <input type="password" name="password" class="form-control rounded-pill" id="phone1" placeholder="Password">
          </div>
        </div>
        <p class="alert alert-info">* Campos obligatorios</p>
        <div class="form-group">
          <div class="col-lg-offset-2 col-lg-10">
            <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
            <button type="submit" class="btn btn-success rounded-pill">Actualizar Cliente</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
      var val = <?php echo $user->tipo_pago; ?>;
      if (val == 0) {

      } else {
        if (val == 1) {
          $("#div_1").removeClass('hdr');
          $("#div_2").removeClass('hdr');
        }
      }
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
  </script>
</section>