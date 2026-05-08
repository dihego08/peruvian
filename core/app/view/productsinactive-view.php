        <style type="text/css">
          #popup_editar {
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 1001;
        }
        .thumbnail{
          margin-left: auto;
          margin-right: auto;
        }
        .content-popup {
            margin:0px auto;
            /*margin-top:2%;*/
            position:relative;
            padding:10px;
            width:75%;
            /*min-height:250px;*/
            border-radius:4px;
            background-color:#FFFFFF;
            box-shadow: 0 2px 5px #666666;
        }
        .content-popup h2 {
            color:#48484B;
            border-bottom: 1px solid #48484B;
            margin-top: 0;
            padding-bottom: 4px;
        }
        .popup-overlay {
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 999;
            display:none;
            background-color: #777777;
            cursor: pointer;
            opacity: 0.7;
        }
        .close {
            position: absolute;
            right: 15px;
        }
        </style>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Productos
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

<div class="row">
	<div class="col-md-12">

<div class="btn-group">
  
  <?php 
    $k = Core::$user->kind;
    if ($k == 1) {
  ?>
    <a href="index.php?view=newproduct" class="btn btn-default">Agregar Producto</a>
    <div class="btn-group pull-right">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-download"></i> Descargar <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="report/products-word.php">Word 2007 (.docx)</a></li>
            <li><a href="report/products-xlsx.php">Excel (.xlsx)</a></li>
        <li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a>

          </ul>
        </div>
  <?php
    }else{

    }
  ?>
        
</div>
<br><br>

<?php
$currency = ConfigurationData::getByPreffix("currency")->val;
switch (Core::$user->kind) {
  case 8:
    $cls = 'style="display: none;"';
    $products = ProductData::getAllByClienteId('2,3');
    //$products = ProductData::getAllByClienteId(3);
    break;
  case 7:
    $cls = 'style="display: none;"';
    $products = ProductData::getAllByClienteId('3');
    break;
  case 6:
    $cls = 'style="display: none;"';
    $products = ProductData::getAllByClienteId('5');
    break;
  case 1:
    //$cls = "display: none;";
    $products = ProductData::getAll();
    break;
  case 5:
    //$cls = "display: none;";
    $products = ProductData::getAll();
    break;
  default:
    # code...
    break;
}
/*if(Core::$user->kind == 3 || Core::$user->kind == 7 || Core::$user->kind == 6){
  $products = ProductData::getAllByClienteId();
}else{
  $products = ProductData::getAll();
}*/
if(count($products)>0){
?>
<div class="box box-primary">
  <div class="box-body no-padding">
<div class="box-body table-responsive" style="padding: 0;">
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

  
    <?php
      if(Core::$user->kind == 7){
    ?>
      <thead>
        <th>Modelo</th>
        <th <?php echo $cls; ?>>Descripción</th>
        <!--<th>Modelo</th>-->

        <th <?php echo $cls; ?>>Prec. confec. Min</th>
        <th <?php echo $cls; ?>>Prec. confec. Max</th>
        <th <?php echo $cls; ?>>Fec. act.</th>
        <th>Imágen</th>
        <th <?php echo $cls; ?>>Prec. Bordado</th>
        <!--<th <?php echo $cls; ?>>Prec. Bordado salida</th>-->
        <th <?php echo $cls; ?>>Bordado</th>
        <th></th>
      </thead>
      <?php foreach($products as $product):?>
        <tr>
          <td><?php echo $product->code; ?></td>
          <!--<td><?php if($product->cliente_id!=null){echo $product->getCliente()->name;}else{ echo "<center>----</center>"; }  ?></td>-->
          <td <?php echo $cls; ?>><?php echo $product->name; ?></td>
          
          <?php
            /*if ($product->price_in_2 == 0) {
              echo "<td colspan=\"2\" style=\"text-align: center; display:none;\" ".$cls.">". $currency. number_format($product->price_in,2,'.',',')."</td>";    
            }else{*/
              echo "<td <?php echo $cls; ?>>".$currency.number_format($product->price_in,2,'.',',')."</td>";
              echo "<td <?php echo $cls; ?>>".$currency.number_format($product->price_in_2,2,'.',',')."</td>";
            //}
          ?>
          
          <td <?php echo $cls; ?>><?php echo $product->fecact; ?></td>
          <td>
            <?php if($product->image!=""):?>
              <img src="storage/products/<?php echo $product->image;?>" style="width:64px;" onclick="abrir_imagen('storage/products/<?php echo $product->image;?>');">
            <?php endif;?>
          </td>
          <td <?php echo $cls; ?>><?php echo $product->prebor_in; ?></td>
          <!--<td <?php echo $cls; ?>><?php echo $product->prebor_out; ?></td>-->
          <td>
            <?php if($product->imgbordado!=""):?>
              <img src="storage/products/<?php echo $product->imgbordado;?>" style="width:64px;" onclick="abrir_imagen('storage/products/<?php echo $product->imgbordado;?>');">
            <?php endif;?>
          </td>
      <td <?php echo $cls; ?>>
        <?php
      if($product->kind==1){
        echo "<span class='label label-info'>Producto</span>";
      }else if($product->kind==2){
        echo "<span class='label label-success'>Servicio</span>";

      }
        ?>


      </td>
          <td <?php echo $cls; ?>><?php if($product->is_active): ?><i class="fa fa-check"></i><?php endif;?></td>
          

          <td style="width:90px;">
          <a target="_blank" href="index.php?action=productqr&id=<?php echo $product->id; ?>" class="btn btn-xs btn-default" <?php echo $cls; ?>><i class="fa fa-qrcode"></i></a>
          <a href="index.php?view=editproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-warning" <?php echo $cls; ?>><i class="glyphicon glyphicon-pencil"></i></a>
          <a href="index.php?view=delproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-danger" <?php echo $cls; ?>><i class="fa fa-trash"></i></a>
          </td>
        </tr>
        <?php endforeach;?>
    <?php
      }else{
    ?>
      <thead>
        <th>Modelo</th>
        <th>Descripción</th>
        <th>Prec. Min</th>
        <th>Prec. Max</th>
        <th>Fec. act.</th>
        <th>Imágen</th>
        <th <?php echo $cls; ?>>Prec. Bordado</th>
        <th <?php echo $cls; ?>>Prec. Bordado salida</th>
        <th>Bordado</th>
        <th></th>
        <th></th>
        <th></th>
      </thead>
      <?php foreach($products as $product):?>
        <tr>
          <td><?php echo $product->code; ?></td>
          <td><?php echo $product->name; ?></td>
          <?php
            echo "<td>".$currency.number_format($product->price_in,2,'.',',')."</td>";
            echo "<td>".$currency.number_format($product->price_in_2,2,'.',',')."</td>";
          ?>
          
          <td><?php echo $product->fecact; ?></td>
          <td>
            <?php if($product->image!=""):?>
              <img src="storage/products/<?php echo $product->image;?>" style="width:64px;" onclick="abrir_imagen('storage/products/<?php echo $product->image;?>');">
            <?php endif;?>
          </td>
          <td <?php echo $cls; ?>><?php echo $product->prebor_in; ?></td>
          <td <?php echo $cls; ?>><?php echo $product->prebor_out; ?></td>
          <td>
            <?php if($product->imgbordado!=""):?>
              <img src="storage/products/<?php echo $product->imgbordado;?>" style="width:64px;" onclick="abrir_imagen('storage/products/<?php echo $product->imgbordado;?>');">
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
          <td <?php echo $cls; ?>><?php if($product->is_active): ?><i class="fa fa-check"></i><?php endif;?></td>
          

          <td style="width:90px;">
          <a target="_blank" href="index.php?action=productqr&id=<?php echo $product->id; ?>" class="btn btn-xs btn-default" <?php echo $cls; ?>><i class="fa fa-qrcode"></i></a>
          <a href="index.php?view=editproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-warning" <?php echo $cls; ?>><i class="glyphicon glyphicon-pencil"></i></a>
          <a href="index.php?view=delproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-danger" <?php echo $cls; ?>><i class="fa fa-trash"></i></a>
          </td>
        </tr>
        <?php endforeach;?>
    <?php
      }
    ?>
    <!--<th>Modelo</th>
    <th>Descripción</th>
    
    <th colspan="2">Prec. de confección</th>
    <th>Fec. act.</th>
    <th>Imágen</th>
    <th <?php echo $cls; ?>>Prec. Bordado</th>
    <th <?php echo $cls; ?>>Prec. Bordado salida</th>
    <th>Bordado</th>
    <th></th>-->
  

	
</table>
</div>

    


  </div><!-- /.box-body -->
</div><!-- /.box -->
<div id="popup_editar" style="display: none;">
        <div class="content-popup">
            <div class="close"><a href="#" id="close_editar"><img src="../css/images/close.png"/></a></div>
            <div>
              <img src="" id="imagen_grande" class="thumbnail">
              <span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
            </div>
        </div>
    </div>
    <div class="popup-overlay"></div>

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
	</div>
</div>
        </section><!-- /.content -->



<script type="text/javascript">
        function thePDF() {
			var doc = new jsPDF('p', 'pt');
        	doc.setFontSize(26);
        	doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        	doc.setFontSize(18);
        	doc.text("LISTADO DE PRODUCTOS", 40, 80);
        	doc.setFontSize(12);
        	doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
			var columns = [
    			{title: "Modelo", dataKey: "id"}, 
    			{title: "Nombre del Producto", dataKey: "name"}, 
    			{title: "Precio Minimo", dataKey: "price_in"}, 
    			{title: "Precio Maximo", dataKey: "price_out"}, 
          {title: "Unidad", dataKey: "unidad"}, 
          {title: "Presentacion", dataKey: "presentacion"}, 
          {title: "Cliente", dataKey: "cliente"}, 
          {title: "Minima en Inv.", dataKey: "inv_min"}, 
          {title: "Activo", dataKey: "activo"}, 
			];
			var rows = [
  				<?php foreach($products as $product):?>
    			{
      				"id": "<?php echo $product->id; ?>",
      				"name": "<?php echo $product->name; ?>",
      				"price_in": "S/. <?php echo number_format($product->price_in,2,'.',',');?>",
      				"price_out": "S/. <?php echo number_format($product->price_in_2,2,'.',',');?>",
              "unidad": "<?php echo $product->unit; ?>",
              "presentacion": "<?php echo $product->presentation; ?>",
              "cliente": "<?php if($product->cliente_id!=null){echo $product->getCliente()->name;}else{ echo "----"; }  ?>",
              "inv_min": "<?php echo $product->inventary_min; ?>",
              "activo": "<?php if($product->is_active==1){echo 'Si';}else{ echo "No"; } ?>",

      			},
 				<?php endforeach; ?>
			];
			doc.autoTable(columns, rows, {
    			theme: 'grid',
    			overflow:'linebreak',
    			styles: { 
        			fillColor: <?php echo Core::$pdf_table_fillcolor;?>
    			},
    			columnStyles: {
        			id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
    			},
    			margin: {top: 100},
    			afterPageContent: function(data) {
    			}
			});
			doc.setFontSize(12);
			doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+25);
			<?php 
				$con = ConfigurationData::getByPreffix("report_image");
				if($con!=null && $con->val!=""):
			?>
					var img = new Image();
					img.src= "storage/configuration/<?php echo $con->val;?>";
					img.onload = function(){
						doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
						doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
					}
				<?php else:?>
					doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
				<?php endif; ?>
		}
    function cerrar_editar(){
      $('#close_editar').click();
    }
    function abrir_imagen(codigo){
      $("#imagen_grande").attr('src', codigo);
      $('#popup_editar').fadeIn('slow');
          $('.popup-overlay').fadeIn('slow');
          $('.popup-overlay').height($(window).height());
          return false;
    }
    $(document).ready(function() {
      $('#close_editar').on('click', function(){
            //limpiar_formulario();
            $('#popup_editar').fadeOut('slow');
            $('.popup-overlay').fadeOut('slow');
            return false;
            flag = false;
        });
    });
</script>

