<section class="content">
<?php 



?>
<div class="row">
	<div class="col-md-12">
	<h1>
    Pagar
  </h1>
	<br>
  <div class="box box-primary">
  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" id="procesarpago" action="index.php?action=procesarpago" role="form">

      <input type="hidden" name="id" id="id" value="<?php echo $_GET['id'] ?>">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Fecha de pago:*</label>
    <div class="col-md-6">
      <input type="date" name="fecpago" required class="form-control" id="fecpago" >
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Entidad:*</label>
    <div class="col-md-6">
      <input type="text" name="entidad" required class="form-control" id="entidad" >
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Fecha detracción:*</label>
    <div class="col-md-6">
      <input type="date" name="fecdet" required class="form-control" id="fecdet" >
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Pagar</button>
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