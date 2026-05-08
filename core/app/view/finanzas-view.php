<style>
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
        top: 100px;
        width: 100%;
        z-index: 1001;
    }
    #popup_editar_2 {
        left: 0;
        position: absolute;
        top: 100px;
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
    .primary{
    	background-color: wheat;
    }
</style>
<section class="content">
	<div class="row">
		<div class="box box-primary">
        <div class="box-body">
        <form class="form-horizontal" method="post"  id="filter" action="index.php?view=products&act=filtrar" role="form">
            <fieldset>
                  <legend>Filtros de Búsqueda</legend>
                  <div class="form-group col-md-2">
                    <label>Caja</label>
                    
                        <select class="form-control" id="estado">
											<option value="abono">Impuestos</option>
											<option value="cargo">Pagos Varios</option>
										</select>

                  </div>
                  
                 
            </fieldset>
             <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
              </div>
        </form>
        </div>



		<button class="btn btn-success pull-right" onclick="formulario();">Agregar Abono - Cargo</button>
		
		<div class="col-md-12">
			<div id="lista_order">
				<h3>Listado de Abonos</h3>
				<div class="box box-primary">
					<table class="table table-bordered table-hover" id="tabla_lista">
						<thead>
							<tr>
								<th>Banco</th>
								<th>Periodo</th>
								<th>F. Depósito</th>
								<th>Monto</th>
								<th colspan="2">Acciones</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<h3 class="pull-right" style="z-index: 1000001; position: relative;"><span class="label label-success" id="saldo_t"> </span></h3>
		</div>
		<div class="col-md-12">
			<div id="lista_order_2">
				<h3>Listado de Cargos</h3>
				<div class="box box-primary">
					<table class="table table-bordered table-hover" id="tabla_lista_2">
						<thead>
							<tr>
								<th>Concepto</th>
								<th>Periodo</th>
								<th>Fecha</th>
								<th>Monto</th>
								<th colspan="2">Acciones</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="popup_editar" style="display: none;">
	        <div class="content-popup">
	            <div class="close"><a href="#" id="close_editar"><img src="../css/images/close.png"/></a></div>
	            <div>
	                <h2 id="titulo_detalle">Agregar Abono - Cargo</h2>
	  					<div class="box box-primary">
	  						<div class="col-md-12">
					  			<h3><b>Agregar:</b></h3>
								<div class="row">
									<div class="form-group col-md-6">
										<label>Banco - Concepto</label>
										<input type="text" id="concepto" name="concepto" class="form-control" placeholder="Concepto...">
									</div>
									<div class="form-group col-md-6">
										<label>Monto</label>
										<input type="text" id="monto" name="monto" class="form-control" placeholder="Monto...">
									</div>
									<div class="form-group col-md-4">
					                    <label for="fecha_desde">Fecha</label>
					                    <div class="input-group">
					                        <input type="text" name="fecha_desde" id="fecha_desde" readonly="readonly" class="form-control clsDatePicker"> 
					                        <span class="input-group-addon">
					                            <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
					                        </span>
					                    </div>
					                </div>
									<div class="form-group col-md-4">
										<label>Periodo</label>
										<input type="text" class="form-control" id="periodo" name="">
									</div>
									<div class="form-group col-md-4">
										<label>Tipo</label>
										<select class="form-control" id="estado">
											<option value="abono">Abono</option>
											<option value="cargo">Cargo</option>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label>Caja</label>
										<select class="form-control" id="estado">
											<option value="abono">Impuestos</option>
											<option value="cargo">Pagos Varios</option>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label>Cuenta Corriente</label>
										<select class="form-control" id="estado">
											<option value="abono">BCP</option>
											<option value="cargo">Scotibank</option>
										</select>
									</div>
									<div class="form-group col-md-4">
				                    <label for="fecha_desde">Fec. Vencimiento</label>
				                    <div class="input-group">
				                        <input type="text" name="fecha_desde" id="fecha_desde" readonly="readonly" class="form-control clsDatePicker"> 
				                        <span class="input-group-addon">
				                            <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
				                        </span>
				                    </div>
				                </div>
								<div class="form-group col-md-4">
									<label>Prioridad</label>
									<select class="form-control" id="prioridad">
										<option value="1">Inmediato</option>
										<option value="2">Urgente</option>
										<option value="3">Importante</option>
										<option value="4">Por Reactivar</option>
										<option value="5">Pagado</option>
									</select>
								</div>
								<div class="form-group col-md-4">
									<label>Estado</label>
									<select class="form-control" id="estado">
										<option value="0">Debe</option>
										<option value="1">Pagado</option>
									</select>
								</div>
								
									<div class="form-group col-md-12">
										<!--<button class="btn btn-danger" onclick="cancel_order();">Cancelar</button>-->
										<button class="btn btn-success" id="guardar_" style="width: 100%;" onclick="guardar_();">Guardar</button>
									</div>
								</div>
							</div>
	  					</div>
	  					<span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
	  					<!--<button type="submit" class="btn btn-success" style="float: right;" id="btn_formulario">Actualizar</button>-->
	            </div>
	        </div>
	    </div>

	    <div id="popup_editar_2" style="display: none;">
	        <div class="content-popup">
	            <div class="close"><a href="#" id="close_editar_2"><img src="../css/images/close.png"/></a></div>
	            <div>
	                <h2 id="titulo_detalle">Pagar Cuenta</h2>
	  					<div class="box box-primary" style="overflow: hidden;">
	  						<fieldset>
	  							<legend>Seleccionar:</legend>
	  							<div class="form-row col-md-12" id="lista_retiros">
	  								
	  							</div>
	  						</fieldset>
	  						<div class="form-row col-md-12" style="margin-top: 10px;">
	  							<span class="btn btn-danger" onclick="cerrar_editar_2()">Cerrar</span>
	  							<button class="btn btn-info" id="btn_pagar_cuenta">Pagar</button>
	  						</div>
	  					</div>
	            </div>
	        </div>
	    </div>
    	<div class="popup-overlay"></div>
	</div>
	<script type="text/javascript">
		var fecha = new Date();
  		var mes = fecha.getMonth();
  		function cerrar_editar(){
			$('#close_editar').click();
		}
		function cerrar_editar_2(){
			$('#close_editar_2').click();
		}
  		function formulario(){
  			$('#popup_editar').fadeIn('slow');
	        $('.popup-overlay').fadeIn('slow');
	        $('.popup-overlay').height($(window).height());
	        return false;
  		}
		function guardar_(){
			var concepto = $("#concepto").val();
			var fecha_vencimiento = $("#fecha_desde").val();
			var periodo = $("#periodo").val();
			var monto = $("#monto").val();
			var tipo = $("#estado").val();
			$.get('core/app/view/impuestos.php',{
				parAccion: 'guardar_cuenta',
				concepto: concepto,
				fecha: fecha_vencimiento,
				periodo: periodo,
				monto: monto,
				tipo: tipo
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
		                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Registrado Correctamente.</strong>'+
		                    '</div>'
		            });
		            //lista_cuentas(mes + 1);
		            lista_abonos();
					lista_cargos();
					saldo();
				}else{
					bootbox.alert({
		                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Ago ha salido mal.</strong>'+
		                    '</div>'
		            });
				}
			});
		}
		function lista_abonos(){
			$("#tabla_lista").find('tbody').empty();
			$.get('core/app/view/impuestos.php', {
				parAccion: 'lista_abonos'
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					
					$("#tabla_lista").find('tbody').append('<tr><td>'+val.concepto+'</td><td>'+val.periodo+'</td><td>'+val.fecha+'</td><td>S/. '+val.monto+'</td><td><select class="form-control" id="select_abono_'+val.id+'"><option value="0">EDITAR ...</option><option value="abono">Abono</option><option value="cargo">Cargo</option></select></td><td><span class="btn-xs btn-danger" onclick="eliminar('+val.id+');" style="cursor: pointer;"><i class="fa fa-trash"></i></span> <span class="btn-xs btn-info" onclick="editar('+val.id+', \'abono\');" style="cursor: pointer;"><i class="fa fa-check"></i></span></td></tr>');
				});
			});
		}
		function eliminar(id){
			bootbox.confirm({
    		message: "¿Seguro de Eliminar este elemento?",
    		buttons: {
        		confirm: {
            		label: 'Sí',
            		className: 'btn-success'
        		},
        		cancel: {
            		label: 'No',
            		className: 'btn-danger'
        		}
    		},
    		callback: function (result) {
        		if (result) {
        			//alert("YES");
        			$.get('core/app/view/impuestos.php', {
        				parAccion: 'eliminar',
        				id: id
        			}, function(data) {
        				var obj = JSON.parse(data);
        				if (obj.Result == 'OK') {
        					//lista_cotizaciones();
        					lista_abonos();
        					saldo();
							lista_cargos();
        				}else{
        					bootbox.alert({
				                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
				                        '<strong>Ago ha salido mal.</strong>'+
				                    '</div>'
				            });
        				}
        			});
        		}else{
        		}
    		}
		});
		}
		function lista_cargos(){
			$("#tabla_lista_2").find('tbody').empty();
			$.get('core/app/view/impuestos.php', {
				parAccion: 'lista_cargos'
			}, function(data) {
				var obj = JSON.parse(data);
				$.each(obj.Records, function(index, val) {
					$("#tabla_lista_2").find('tbody').append('<tr><td>'+val.concepto+'</td><td>'+val.periodo+'</td><td>'+val.fecha+'</td><td> S/. '+val.monto+'</td><td><select class="form-control" id="select_cargo_'+val.id+'"><option value="0">EDITAR ...</option><option value="abono">Abono</option><option value="cargo">Cargo</option></select></td><td><span class="btn-xs btn-danger" onclick="eliminar('+val.id+');" style="cursor: pointer;"><i class="fa fa-trash"></i></span> <span class="btn-xs btn-info" onclick="editar('+val.id+', \'cargo\');" style="cursor: pointer;"><i class="fa fa-check"></i></span></td></tr>');
				});
			});
		}
		function editar(id, tipo){
			var tipo_ = $("#select_"+tipo+"_"+id).val();
			if (tipo_ == 0) {
				bootbox.alert({
	                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
	                        '<strong>Antes de Editar, Cambiar tipo.</strong>'+
	                    '</div>'
	            });
			}else{
				console.log(tipo_);
				$.get('core/app/view/impuestos.php', {
					parAccion: 'editar',
					id: id,
					tipo: tipo_ 
				}, function(data) {
					var obj = JSON.parse(data);
					if (obj.Result == 'OK') {
						//lista_cotizaciones();
						lista_abonos();
						lista_cargos();
						saldo();
					}else{
						bootbox.alert({
			                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
			                        '<strong>Ago ha salido mal.</strong>'+
			                    '</div>'
			            });
					}
				});
			}
			
		}
		function saldo(){
			$.get('core/app/view/impuestos.php', {
				parAccion: 'saldo'
			}, function(data) {
				var obj = JSON.parse(data);
				var suma = 0;
				$.each(obj.Records, function(index, val) {
					if(val.tipo == 'cargo'){
						suma = parseFloat(suma) - parseFloat(val.monto);
					}else{
						if (val.tipo == 'abono') {
							suma = parseFloat(suma) + parseFloat(val.monto);
						}
					}
				});
				$("#saldo_t").text("S/. " + suma);
			});
		}
		$(document).ready(function() {
  			lista_abonos();
  			lista_cargos();
  			saldo();


  			$('#fecha_desde').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                altField: "#fecha_nacimiento_hidden",
                altFormat: "yy-mm-dd"
            });
            $('#fecha_').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                altField: "#fecha_nacimiento_hidden",
                altFormat: "yy-mm-dd"
            });

            $('#close_editar').on('click', function(){
	            //limpiar_formulario();
	            $('#popup_editar').fadeOut('slow');
	            $('.popup-overlay').fadeOut('slow');
	            return false;
	            flag = false;
	        });
	        $('#close_editar_2').on('click', function(){
	            //limpiar_formulario();
	            $('#popup_editar_2').fadeOut('slow');
	            $('.popup-overlay').fadeOut('slow');
	            return false;
	            flag = false;
	        });
		});
	</script>
</section>
