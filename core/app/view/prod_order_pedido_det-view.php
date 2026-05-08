<?php //include('https://miempresa.softluttion.com/pages/porcentaje.php'); ?>
<?php $user = UserData::getById($_SESSION["user_id"]);?>
<style>
  	.ui-autocomplete {
        position:absolute;
        cursor:default;
        z-index:1001 !important
    }
#v{
    width:320px;
    height:240px;
}
#qr-canvas{
    display:none;
}
#qrfile{
    width:320px;
    height:240px;
}
#mp1{
    text-align:center;
    font-size:35px;
}
#imghelp{
    position:relative;
    left:0px;
    top:-160px;
    z-index:100;
    font:18px arial,sans-serif;
    background:#f0f0f0;
  margin-left:35px;
  margin-right:35px;
  padding-top:10px;
  padding-bottom:10px;
  border-radius:20px;
}

        #popup_editar {
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 1001;
        }
        .content-popup {
            margin:0px auto;
            margin-top:2%;
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
        .clsDatePicker {
            position:absolute;
            cursor:default;
            z-index:1001 !important
        }
        .ui-datepicker-month{
            color: #313131;
        }
        .ui-datepicker-year{
            color: #313131;
        }
	@media (max-width: 600px) {
  		.tdd {
    		padding: 1px !important;
  		}
	}
</style>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>USUARIO : <?php echo($user->name); ?> </h1>
	<h1>Orden de Pedido </h1>
  <?php
  if($cli != ""){
  ?>
    <h2>CLIENTE : <?php echo($cli); ?> </h2>
	<?php
  }
  ?>
  <p><b>Agregar una nueva Orden de Pedido:</b></p>
		<!--<form id="searchp">-->
			<div class="row">
				<div class="col-md-12">
					<label>Modelo</label>
					<input type="text" id="product_name" name="product_name" class="form-control ui-autocomplete-input" placeholder="Modelo ...">
				</div>
      			<div id="resultado" style="padding: 25px 25px 0 25px; margin-top: 25px; margin-bottom: 0px;">
					<div class="box box-primary table-responsive" style="margin-top: 20px;">
						<table class="table table-bordered table-hover" id="tabla_resultado">
							<thead>
								<tr>
									<th rowspan="2" style="vertical-align: middle; text-align: center;">Color</th>
									<th colspan="13" style="text-align: center;">Cantidades por Talla</th>
									<th rowspan="2" style="vertical-align: middle; text-align: center;">Agregar</th>
								</tr>
								<!--<tr>
									
								</tr>-->
								<tr>
									<th>2</th>
									<th>4</th>
									<th>6</th>
									<th>8</th>
									<th>10</th>
									<th>12</th>
									<th>14</th>
									<th>16</th>
									<th>S</th>
									<th>M</th>
									<th>L</th>
									<th>XL</th>
									<th>XXL</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
				<form id="formulario">
					<div class="col-md-12" id="div_entrega" >
						<div class="box box-primary table-responsive" style="margin-top: 20px;">
							<table class="table table-bordered table-hover" id="tabla_resultado_2">
								<thead>
									<tr>
										<th rowspan="2" style="vertical-align: middle; text-align: center;">Modelo</th>
										<th rowspan="2" style="vertical-align: middle; text-align: center;">Color</th>
										<th colspan="14" style="text-align: center;">Cantidades por Talla</th>
										<th rowspan="2" style="vertical-align: middle; text-align: center;">Eliminar</th>
									</tr>
									<tr>
										<th>2</th>
										<th>4</th>
										<th>6</th>
										<th>8</th>
										<th>10</th>
										<th>12</th>
										<th>14</th>
										<th>16</th>
										<th>S</th>
										<th>M</th>
										<th>L</th>
										<th>XL</th>
										<th>XXL</th>
										<th>Tot.</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
							<div class="pull-right">
								<table class="table table-bordered table-hover" id="tabla_total_">
									<thead>
										<tr>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<label>Tiempo de Entrega (días)</label>
						<input type="text" id="tiempo_entrega" name="tiempo_entrega" class="form-control" placeholder="Tiempo de Entrega" style="margin-bottom: 10px;">
					</div>
					<div class="col-md-4">
						<label>Cliente</label>
						<select class="form-control" name="s_cliente" id="s_cliente">
							<option value="0">SELECCIONE ...</option>
						</select>
					</div>
					<div class="col-md-4" style="margin-bottom: 5px;">
						<label for="fecha_desde">Fecha:</label>
	                    <div class="input-group">
	                        <input type="text" name="fecha_desde" id="fecha_desde" readonly="readonly" class="form-control clsDatePicker"> 
	                        <span class="input-group-addon">
	                            <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
	                        </span>
	                    </div>
					</div>
					<div class="col-md-12" style="margin-bottom: 5px;">
						<label>Comentario</label>
						<textarea class="form-control" name="comentario" id="comentario"></textarea>
					</div>
					
					<div class="col-md-12">
						<a href="#" class="btn btn-danger" onclick="cancel_order();">Cancelar</a>
						<button class="btn btn-success">Guardar Orden</button>
					</div>
					<input type="hidden" name="usuario" value="<?php echo($user->name); ?>" id="usuario"/>
				</form>
			</div>
	<div id="popup_editar" style="display: none;">
        <div class="content-popup">
            <div class="close"><a href="#" id="close_editar"><img src="../css/images/close.png"/></a></div>
            <div>
                <h2 id="titulo_detalle">Detalle Orden de Pedido</h2>
  					<div class="box box-primary">
						<table class="table table-bordered table-hover" id="tabla_detalle">
							<thead>
								<tr>
									<th>Producto</th>
									<th>Cantidad</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
  					</div>
  					<span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
  					<!--<button type="submit" class="btn btn-success" style="float: right;" id="btn_formulario">Actualizar</button>-->
            </div>
        </div>
    </div>
    <div class="popup-overlay"></div>

<script>
	var zux = 0;
	var k = <?php echo Core::$user->kind; ?>;
	var kk = 0;
	if(k == 1){
      kk = 0;
    }else{
      if(k == 8){
        kk = 2;
      }else{
        if (k == 7) {
          kk = 3;
        }else{
          if (k == 6) {
            kk = 5;
          }
        }
      }
    }
	function llenar(){
		$("#tabla_resultado").find('tbody').empty();
		$("#tabla_resultado").find('tbody').append('<tr>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_1" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_2" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_3" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_4" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_5" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_6" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_7" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_8" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_9" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_10" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_11" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_12" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_13" name=""></td>'+
			'<td class="tdd"><input type="text" class="form-control tdd" id="c_14" name=""></td>'+
			'<td><button class="btn-xs btn-success" onclick="agregar_listado();"><i class="fa fa-plus"></i></button></td>'+
		'</tr>');
	}
	function cancel_order(){
		zux = 0;
		$("#tabla_resultado_2").find('tbody').empty();
		$("#formulario")[0].reset();
	}
	var to = 0;
	function agregar_listado(){
		var sto = 0;
		sto = parseInt(sto) + ($("#c_2").val()? parseInt($("#c_2").val()) : 0);
		sto = parseInt(sto) + ($("#c_3").val()? parseInt($("#c_3").val()) : 0);
		sto = parseInt(sto) + ($("#c_4").val()? parseInt($("#c_4").val()) : 0);
		sto = parseInt(sto) + ($("#c_5").val()? parseInt($("#c_5").val()) : 0);
		sto = parseInt(sto) + ($("#c_6").val()? parseInt($("#c_6").val()) : 0);
		sto = parseInt(sto) + ($("#c_7").val()? parseInt($("#c_7").val()) : 0);
		sto = parseInt(sto) + ($("#c_8").val()? parseInt($("#c_8").val()) : 0);
		sto = parseInt(sto) + ($("#c_9").val()? parseInt($("#c_9").val()) : 0);
		sto = parseInt(sto) + ($("#c_10").val()? parseInt($("#c_10").val()) : 0);
		sto = parseInt(sto) + ($("#c_11").val()? parseInt($("#c_11").val()) : 0);
		sto = parseInt(sto) + ($("#c_12").val()? parseInt($("#c_12").val()) : 0);
		sto = parseInt(sto) + ($("#c_13").val()? parseInt($("#c_13").val()) : 0);
		sto = parseInt(sto) + ($("#c_14").val()? parseInt($("#c_14").val()) : 0);
		zux++;
		$("#tabla_resultado_2").find('tbody').append('<tr>'+
			'<td>'+$("#product_name").val()+'<input type="hidden" value="'+$("#product_name").val()+'" name="nn_0_'+zux+'"></td>'+
			'<td>'+$("#c_1").val()+'<input type="hidden" value="'+$("#c_1").val()+'" name="nn_1_'+zux+'" id="nn_1_'+zux+'"></td>'+
			'<td>'+$("#c_2").val()+'<input type="hidden" value="'+$("#c_2").val()+'" name="nn_2_'+zux+'" id="nn_2_'+zux+'"></td>'+
			'<td>'+$("#c_3").val()+'<input type="hidden" value="'+$("#c_3").val()+'" name="nn_3_'+zux+'" id="nn_3_'+zux+'"></td>'+
			'<td>'+$("#c_4").val()+'<input type="hidden" value="'+$("#c_4").val()+'" name="nn_4_'+zux+'" id="nn_4_'+zux+'"></td>'+
			'<td>'+$("#c_5").val()+'<input type="hidden" value="'+$("#c_5").val()+'" name="nn_5_'+zux+'" id="nn_5_'+zux+'"></td>'+
			'<td>'+$("#c_6").val()+'<input type="hidden" value="'+$("#c_6").val()+'" name="nn_6_'+zux+'" id="nn_6_'+zux+'"></td>'+
			'<td>'+$("#c_7").val()+'<input type="hidden" value="'+$("#c_7").val()+'" name="nn_7_'+zux+'" id="nn_7_'+zux+'"></td>'+
			'<td>'+$("#c_8").val()+'<input type="hidden" value="'+$("#c_8").val()+'" name="nn_8_'+zux+'" id="nn_8_'+zux+'"></td>'+
			'<td>'+$("#c_9").val()+'<input type="hidden" value="'+$("#c_9").val()+'" name="nn_9_'+zux+'" id="nn_9_'+zux+'"></td>'+
			'<td>'+$("#c_10").val()+'<input type="hidden" value="'+$("#c_10").val()+'" name="nn_10_'+zux+'" id="nn_10_'+zux+'"></td>'+
			'<td>'+$("#c_11").val()+'<input type="hidden" value="'+$("#c_11").val()+'" name="nn_11_'+zux+'" id="nn_11_'+zux+'"></td>'+
			'<td>'+$("#c_12").val()+'<input type="hidden" value="'+$("#c_12").val()+'" name="nn_12_'+zux+'" id="nn_12_'+zux+'"></td>'+
			'<td>'+$("#c_13").val()+'<input type="hidden" value="'+$("#c_13").val()+'" name="nn_13_'+zux+'" id="nn_13_'+zux+'"></td>'+
			'<td>'+$("#c_14").val()+'<input type="hidden" value="'+$("#c_14").val()+'" name="nn_14_'+zux+'" id="nn_14_'+zux+'"></td>'+
			'<td>'+sto+'<input type="hidden" name="tot_'+zux+'" id="tot_'+zux+'" value="'+sto+'"></td>'+
			'<td><button class="borrar btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>');
		to = parseInt(to) + parseInt(sto);
		/*$("#tabla_total_").find('tbody').empty();
		$("#tabla_total_").find('tbody').append('<tr><td>'+to+'</td></tr>');*/
		llenar();
		calcular_tot(zux);
	}
	function calcular_tot(z){
		console.log(z);
		var fd = 0;
		for (var i = 1; i <= z; i++) {
			console.log(i);
			fd = parseInt(fd) + ($("#tot_"+i).val()? parseInt($("#tot_"+i).val()) : 0);// parseInt($("#tot_"+i).val());
			console.log(fd);
		}
		$("#tabla_total_").find('tbody').empty();
		$("#tabla_total_").find('tbody').append('<tr><td>'+fd+'</td></tr>');
	}
	function lista_clientes(){
		$.get('core/app/view/order.php', {
			parAccion: 'lista_clientes'
		}, function(data) {
			var obj = JSON.parse(data);
			if(kk == 0){
				$.each(obj.Records, function(index, val) {
					$("#s_cliente").append('<option value="'+val.id+'">'+val.name+'</option>');
				});	
			}else{
				$.each(obj.Records, function(index, val) {
					if(val.id == kk){
						$("#s_cliente").append('<option value="'+val.id+'" selected>'+val.name+'</option>');
					}else{
						//$("#cliente").append('<option value="'+val.id+'" disabled>'+val.name+'</option>');	
					}
					
				});
		    }
			
		});
	}
	$(function () {
    	$(document).on('click', '.borrar', function (event) {
        	event.preventDefault();
        	$(this).closest('tr').remove();
        	//zux = zux - 1;
        	calcular_tot(zux);
    	});
	});
	$(document).ready(function() {
		$('#fecha_desde').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                altField: "#fecha_nacimiento_hidden",
                altFormat: "yy-mm-dd"
            });
		llenar();
		lista_clientes();
		$("#formulario").submit(function(event) {
			event.preventDefault();
			//var form = $(this);
			var nFilas = $("#tabla_resultado_2").find('tbody tr').length;
		    //var nColumnas = $("#mi-tabla tr:last td").length;
		    //var msg = "Filas: "+nFilas;
      		//alert(msg);
      		if (nFilas > 0) {
      			$.ajax({
					url: 'core/app/view/order.php?parAccion=nuevo_order&cant='+zux,
					type: 'POST',
					//dataType: 'html',
					data: $(this).serialize(),
				})
				.done(function() {
					bootbox.alert({
		                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Orden guardada correctamente.</strong>'+
		                    '</div>'
		            });				
		            llenar();
		            cancel_order();
		            to = 0;
		            //lista_ordenes(kk);
		            //cancel_order();
					zux = 0;
				})
				.fail(function() {
					bootbox.alert({
		                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Algo ha salido mal.</strong>'+
		                    '</div>'
		            });
				})
				.always(function() {
					console.log("complete");
				});
      		}else{
      			bootbox.alert({
	                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
	                        '<strong>No has Agregado nada al listado.</strong>'+
	                    '</div>'
	            });
      		}
			
			
		});
	});
</script>

</section>
