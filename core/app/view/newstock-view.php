<section class="content">
  <div class="row">
    <div class="col-md-12">
      <h3>Nueva Sucursal</h3>
      <br>
      <div class="box box-primary">
        <table class="table">
          <tr>
            <td>
              <form class="form-horizontal" method="post" id="addcategory" action="index.php?action=addstock" role="form">
                <div class="form-group">
                  <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
                  <div class="col-md-6">
                    <input type="text" name="name" required class="form-control rounded-pill" id="name" placeholder="Nombre">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail1" class="col-lg-2 control-label">Direccion*</label>
                  <div class="col-md-6">
                    <input type="text" name="address" class="form-control rounded-pill" id="name" placeholder="Direccion">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail1" class="col-lg-2 control-label">Telefono*</label>
                  <div class="col-md-6">
                    <input type="text" name="phone" class="form-control rounded-pill" id="name" placeholder="Telefono">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail1" class="col-lg-2 control-label">Email*</label>
                  <div class="col-md-6">
                    <input type="text" name="email" class="form-control rounded-pill" id="name" placeholder="Email">
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-success rounded-pill">Agregar Sucursal</button>
                  </div>
                </div>
              </form>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</section>