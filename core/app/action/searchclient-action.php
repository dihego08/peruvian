<?php
$currency = ConfigurationData::getByPreffix("currency")->val;

?>

<?php if((isset($_GET["client_name"]) && $_GET["client_name"]!="") || (isset($_GET["client_code"]) && $_GET["client_code"]!="") ):?>
<?php
$go = $_GET["go"];
$search  ="";
if($go=="code"){ $search=$_GET["client_code"]; }
else if($go=="name"){ $search=$_GET["client_name"]; }



$clients = array();

$clients = PersonData::getLike($search);



if(count($clients)>0){
	?>
<h3>Resultados de la Busqueda</h3>
<div class="box box-primary">
<table class="table table-bordered table-hover">
	<thead>
		<th>DNI/RUC</th>
		<th>Nombre/Razon Social</th>
		<th>Telefono</th>
		<th>Email</th>
		<th></th>
	</thead>
	<?php
	 foreach($clients as $client):
	?>	
	<tr>
		<td><?php echo $client->no; ?></td>
		<td><?php echo $client->name; ?></td>
		<td><?php echo $client->phone1; ?></td>
		<td><?php echo $client->email1; ?></td>
		<td><a href="index.php?view=sell&cli=<?php echo $client->name; ?>" class="btn btn-primary">Continuar</a></td>
	</tr>
	<?php endforeach;?>
</table>
</div>
<?php
}else{
	?>
<h4>Nuevo Cliente</h4>
	<div class="box box-primary">
	<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=addclientSell" role="form">
		<table class="table table-bordered table-hover">
			<thead>
				<th>DNI/RUC</th>
				<th>Nombre/Razon Social</th>
				<th>Telefono</th>
				<th>Email</th>
				<th></th>
			</thead>
			<tr>
				<td><input type="text" name="no" value="<?php echo($_GET["client_code"]); ?>"></td>
				<td><input type="text" name="name" value="<?php echo($_GET["client_name"]); ?>"></td>
				<td><input type="text" name="email1"></td>
				<td><input type="text" name="phone1"></td>
				<td><button type="submit" class="btn btn-primary">Crear y Continuar</button></td>
			</tr>
		</table>
		
	</form>
	</div>



	<?php
}
?>
<script>

</script>

<?php else:
?>
<?php endif; ?>