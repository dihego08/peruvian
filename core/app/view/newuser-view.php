<section class="content">
  <div class="row">
    <div class="col-md-12">
      <h3>Agregar Usuario</h3>
      <div class="box box-primary">
        <div class="box-body">
          <form class="form-horizontal" enctype="multipart/form-data" method="post" id="addproduct" action="index.php?view=adduser" role="form">
            <div class="form-group">
              <div class="col-md-12">
                <label for="inputEmail1">Imagen (160x160)</label>
              </div>
              <div class="col-md-12">
                <input type="file" name="image" id="image" placeholder="">
              </div>
            </div>
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
              <div class="col-md-12">
                <label for="inputEmail1">Nombre*</label>
              </div>
              <div class="col-md-12">
                <input type="text" name="name" class="form-control rounded-pill" id="name" placeholder="Nombre">
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <label for="inputEmail1">Apellido*</label>
              </div>
              <div class="col-md-12">
                <input type="text" name="lastname" class="form-control rounded-pill" id="lastname" placeholder="Apellido">
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <label for="inputEmail1">Nombre de usuario*</label>
              </div>
              <div class="col-md-12">
                <input type="text" name="username" class="form-control rounded-pill" required id="username" placeholder="Nombre de usuario">
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-6">
                <label for="inputEmail1">Email*</label>
                <input type="text" name="email" class="form-control rounded-pill" id="email" placeholder="Email">
              </div>
              <div class="col-md-6">
                <label for="celular">Celular</label>
                <input type="text" name="celular" class="form-control rounded-pill" id="celular" placeholder="# de Celular">
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <label for="inputEmail1">Contrase&ntilde;a</label>
              </div>
              <div class="col-md-12">
                <input type="password" name="password" class="form-control rounded-pill" required id="inputEmail1" placeholder="Contrase&ntilde;a">
              </div>
            </div>
            <input type="hidden" name="kind" value="" id="kind">
            <p class="alert alert-info">* Campos obligatorios</p>
            <div class="form-group">
              <div class="col-lg-12 text-center">
                <button type="submit" class="btn btn-success rounded-pill">Agregar Usuario</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script type="text/javascript">
  $(document).ready(function() {
    $.get('core/app/view/venta.php', {
      parAccion: 'tipo_usuario'
    }, function(data) {
      var obj = JSON.parse(data);
      $.each(obj.Records, function(index, val) {
        $("#tipo_usuario").append('<option value="' + val.id + '">' + val.cargo + '</option>');
      });
    });
    $("#tipo_usuario").on('change', function() {
      var k = $("#tipo_usuario").val();
      $("#kind").val(k);
      console.log($("#kind").val() + " " + k);
    });
  });
</script>