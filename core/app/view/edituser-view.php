<div class="content">
  <?php $user = UserData::getById($_GET["id"]); ?>
  <div class="row">
    <div class="col-md-12">
      <h3>Editar Usuario</h3>
      <br>
      <form class="form-horizontal" method="post" id="addproduct" enctype="multipart/form-data" action="index.php?view=updateuser" role="form">

        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Imagen (160x160)</label>
          <div class="col-md-6">
            <?php
            if ($user->image != "") {
              $url = "storage/profiles/" . $user->image;
              if (file_exists($url)) {
                echo "<img src='$url' style='width:80px;'>";
              }
            }
            ?>
            <br><br>
            <input type="file" name="image" id="image" placeholder="">
          </div>
        </div>
        <script>
          var id_tipo_usuario = <?php echo $user->kind; ?>
        </script>
        <div class="form-group">
          <div class="col-md-12">
            <label>Tipo de Usuario</label>
          </div>
          <div class="col-md-12">
            <select class="form-control rounded-pill" name="tipo_usuario" id="tipo_usuario">
              <option value="0">SELECCIONA ...</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
          <div class="col-md-6">
            <input type="text" name="name" value="<?php echo $user->name; ?>" class="form-control rounded-pill" id="name" placeholder="Nombre">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Apellido*</label>
          <div class="col-md-6">
            <input type="text" name="lastname" value="<?php echo $user->lastname; ?>" class="form-control rounded-pill" id="lastname" placeholder="Apellido">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Nombre de usuario*</label>
          <div class="col-md-6">
            <input type="text" name="username" value="<?php echo $user->username; ?>" class="form-control rounded-pill" id="username" placeholder="Nombre de usuario">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Email*</label>
          <div class="col-md-6">
            <input type="text" name="email" value="<?php echo $user->email; ?>" class="form-control rounded-pill" id="email" placeholder="Email">
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-lg-2 control-label">Celular</label>
          <div class="col-md-6">
            <input type="text" name="celular" value="<?php echo $user->celular; ?>" class="form-control rounded-pill" id="celular" placeholder="# de Celular">
          </div>
        </div>
        <?php if ($user->kind == 1 || $user->kind == 3) : ?>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Comision de ventas(%)</label>
            <div class="col-md-6">
              <input type="text" value="<?php echo $user->comision; ?>" name="comision" class="form-control rounded-pill" id="inputEmail1" placeholder="Comision de ventas(%)">
            </div>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Contrase&ntilde;a</label>
          <div class="col-md-6">
            <input type="password" name="password" class="form-control rounded-pill" id="inputEmail1" placeholder="Contrase&ntilde;a">
            <p class="help-block">La contrase&ntilde;a solo se modificara si escribes algo, en caso contrario no se modifica.</p>
          </div>
        </div>
        <?php if ($user->kind == 2 || $user->kind == 3) : ?>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Almacen</label>
            <div class="col-md-6">
              <?php
              $clients = StockData::getAll();
              ?>
              <select name="stock_id" class="form-control rounded-pill" required>
                <option value="">-- NINGUNO --</option>
                <?php foreach ($clients as $client) : ?>
                  <option value="<?php echo $client->id; ?>" <?php if ($client->id == $user->stock_id) {
                                                                echo "selected";
                                                              } ?>><?php echo $client->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Esta activo</label>
          <div class="col-md-6">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="status" <?php if ($user->status) {
                                                        echo "checked";
                                                      } ?>>
              </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-offset-2 col-lg-10">
            <input type="hidden" name="kind" value="<?php echo $user->kind; ?>" id="kind">
            <p class="text-info">* Campos obligatorios</p>
            <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
            <button type="submit" class="btn btn-success rounded-pill">Actualizar Usuario</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $.get('core/app/view/venta.php', {
      parAccion: 'tipo_usuario'
    }, function(data) {
      var obj = JSON.parse(data);
      $.each(obj.Records, function(index, val) {
        if (id_tipo_usuario == val.id) {
          $("#tipo_usuario").append('<option value="' + val.id + '" selected>' + val.cargo + '</option>');
        } else {
          $("#tipo_usuario").append('<option value="' + val.id + '">' + val.cargo + '</option>');
        }

      });
    });
    $("#tipo_usuario").on('change', function() {
      var k = $("#tipo_usuario").val();
      $("#kind").val(k);
      console.log($("#kind").val() + " " + k);
    });
  });
</script>