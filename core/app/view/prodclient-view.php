<?php $cliente = PersonData::getById($_GET["id"]);?>
<section class="content">
<div class="row">
	<div class="col-md-12">
		<h1>Lista de productos - cliente(<b><i><?php echo $cliente->name;?></b></i>)</h1>
	<a href="index.php?view=newproduct&cid=<?php echo $_GET["id"];?>" class="btn btn-default"><i class='fa fa-smile-o'></i> Agregar nuevo producto</a>

<br><br>




<?php
$currency = ConfigurationData::getByPreffix("currency")->val;

$products = ProductData::getAllByClienteId($_GET["id"]);
if(count($products)>0){
?>
<div class="box box-primary">
  <div class="box-header">
    <h3 class="box-title">Productos</h3>

  </div><!-- /.box-header -->
  <div class="box-body no-padding">
<div class="box-body table-responsive">
<table class="table  table-bordered datatable table-hover">
<!-- 	<thead>
		<th>Codigo</th>
		<th>Imagen</th>
		<th>Nombre</th>
		<th>Precio Entrada</th>
		<th>Precio Salida</th>
		<th>Categoria</th>
		<th>Minima</th>
    <th>Tipo</th>
		<th>Activo</th>
		<th></th>
	</thead> -->

  <thead>
    <th>Cliente</th>
    <th>Descripción</th>
    <th>Prec. de confección</th>
    <th>Fec. act.</th>
    <th>Modelo</th>
    <th>Prec. Bordado</th>
    <th>Prec. Bordado salida</th>
    <th>Bordado</th>
    <th></th>
  </thead>

	<?php foreach($products as $product):?>
	<tr>
    <td><?php if($product->cliente_id!=null){echo $product->getCliente()->name;}else{ echo "<center>----</center>"; }  ?></td>
    <td><?php echo $product->name; ?></td>
    <td><?php echo $currency; ?> <?php echo number_format($product->price_in,2,'.',','); ?></td>
    <td><?php echo $product->fecact; ?></td>
		<td>
			<?php if($product->image!=""):?>
				<img src="storage/products/<?php echo $product->image;?>" style="width:64px;">
			<?php endif;?>
		</td>
		<td><?php echo $product->prebor_in; ?></td>
		<td><?php echo $product->prebor_out; ?></td>
    <td>
      <?php if($product->imgbordado!=""):?>
        <img src="storage/products/<?php echo $product->imgbordado;?>" style="width:64px;">
      <?php endif;?>
    </td>
<td>
  <?php
if($product->kind==1){
  echo "<span class='label label-info'>Producto</span>";
}else if($product->kind==2){
  echo "<span class='label label-success'>Servicio</span>";

}
  ?>


</td>
		<td><?php if($product->is_active): ?><i class="fa fa-check"></i><?php endif;?></td>
		

		<td style="width:90px;">
		<a target="_blank" href="index.php?action=productqr&id=<?php echo $product->id; ?>" class="btn btn-xs btn-default"><i class="fa fa-qrcode"></i></a>
		<a href="index.php?view=editproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
		<a href="index.php?view=delproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
		</td>
	</tr>
	<?php endforeach;?>
</table>
</div>
  </div><!-- /.box-body -->
</div><!-- /.box -->


	<?php
}else{
	?>
	<div class="alert alert-info">
		<h2>No hay productos</h2>
		<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</b>.</p>
	</div>
	<?php
}

?>
















</section>

