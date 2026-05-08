<?php if (isset($_GET["opt"]) && $_GET["opt"] == "all") : ?>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h3>
      Marcas
    </h3>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="w-100 text-right">
      <a href="index.php?view=brands&opt=new" class="btn btn-outline-dark rounded-pill"><i class='fa fa-th-list'></i> Nueva Marca</a>
    </div>

    <div class="row">
      <div class="col-md-12">
        <br>
        <?php

        $users = BrandData::getAll();
        if (count($users) > 0) {
          // si hay usuarios
        ?>
          <div class="box">
            <div class="box-body">

              <table class="table table-bordered datatable table-hover">
                <thead>
                  <th>Nombre</th>
                  <th></th>
                </thead>
                <?php
                foreach ($users as $user) {
                ?>
                  <tr>
                    <td><?php echo $user->name . " " . $user->lastname; ?></td>
                    <td style="width:130px;">
                      <a href="index.php?view=brands&opt=edit&id=<?php echo $user->id; ?>" class="btn btn-outline-warning btn-sm mt-1 rounded-pill"><i class="fa fa-edit"></i></a>
                      <a href="index.php?action=brands&opt=del&id=<?php echo $user->id; ?>" class="btn btn-outline-danger btn-sm mt-1 rounded-pill"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                <?php

                }

                ?>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->

        <?php


        } else {
          echo "<p class='alert alert-danger'>No hay Marcas</p>";
        }


        ?>


      </div>
    </div>
  </section><!-- /.content -->
<?php elseif (isset($_GET["opt"]) && $_GET["opt"] == "new") : ?>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <h1>Nueva Marca</h1>
        <br>
        <div class="box box-primary">
          <table class="table">
            <tr>
              <td>
                <form class="form-horizontal" method="post" id="addcategory" action="index.php?action=brands&opt=add" role="form">
                  <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
                    <div class="col-md-6">
                      <input type="text" name="name" required class="form-control rounded-pill" id="name" placeholder="Nombre">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                      <button type="submit" class="btn btn-success rounded-pill">Agregar Marca</button>
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
<?php elseif (isset($_GET["opt"]) && $_GET["opt"] == "edit") : ?>
  <section class="content">
    <?php $user = BrandData::getById($_GET["id"]); ?>
    <div class="row">
      <div class="col-md-12">
        <h1>Editar Marca</h1>
        <br>
        <div class="box box-primary">
          <table class="table">
            <tr>
              <td>
                <form class="form-horizontal" method="post" id="addproduct" action="index.php?action=brands&opt=upd" role="form">


                  <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
                    <div class="col-md-6">
                      <input type="text" name="name" value="<?php echo $user->name; ?>" class="form-control rounded-pill" id="name" placeholder="Nombre">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                      <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                      <button type="submit" class="btn btn-success  rounded-pill">Actualizar Marca</button>
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
<?php endif; ?>