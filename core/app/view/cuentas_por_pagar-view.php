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
		<button class="btn btn-success pull-right" onclick="formulario();">Agregar Ingreso</button>
		<div class="col-md-12">
			<h1>Cuentas por Pagar </h1>
  			<h3><b>Agregar:</b></h3>
			<div class="row">
				<div class="form-group col-md-6">
					<label>Concepto</label>
					<input type="text" id="concepto" name="concepto" class="form-control" placeholder="Concepto...">
				</div>
				<div class="form-group col-md-6">
					<label>Monto</label>
					<input type="text" id="monto" name="monto" class="form-control" placeholder="Monto...">
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
					<button class="btn btn-success" id="guardar_cuenta" style="width: 100%;" onclick="guardar_cuenta();">Guardar Cuenta</button>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div id="lista_order">
				<h3>Listado de de Cuentas por Pagar</h3>
				<h3 class="pull-right" style="z-index: 1000001; position: relative;"><span class="label label-success" id="saldo_t"> </span></h3>
				<div class="box box-primary">
					<table class="table table-bordered table-hover" id="tabla_lista">
						<thead>
							<tr>
								<th>Concepto</th>
								<th>Monto</th>
								<th>Fecha de Vencimiento</th>
								<th>Estado</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
				<a href="#" class="btn btn-info pull-right" id="reportar">Exportar</a>
			</div>
		</div>
		<div class="col-md-4">
			<h3></h3>
			<div class="box box-primary">
				<table class="table table-bordered table-hover" id="">
					<thead>
						<tr>
							<th>Color</th>
							<th>Concepto</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="danger"></td>
							<td>Inmediato</td>
						</tr>
						<tr>
							<td class="warning"></td>
							<td>Urgente</td>
						</tr>
						<tr>
							<td class="success"></td>
							<td>Importante</td>
						</tr>
						<tr>
							<td class="primary"></td>
							<td>Por Reactivar</td>
						</tr>
						<tr>
							<td class="info"></td>
							<td>Pagado</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div id="popup_editar" style="display: none;">
	        <div class="content-popup">
	            <div class="close"><a href="#" id="close_editar"><img src="../css/images/close.png"/></a></div>
	            <div>
	                <h2 id="titulo_detalle">Agregar Retiro</h2>
	  					<div class="box box-primary">
	  						<div class="form-row col-md-4">
	  							<label for="">Tipo</label>
	  							<input type="text" name="" class="form-control" id="tipo">
	  						</div>
	  						<div class="form-row col-md-4">
	  							<label for="">Concepto</label>
	  							<input type="text" name="" class="form-control" id="detalle">
	  						</div>
	  						<div class="form-row col-md-4">
	  							<label for="">Monto</label>
	  							<input type="text" name="" class="form-control" id="monto_">
	  						</div>
	  						<div class="form-row col-md-4">
	  							<label for="fecha_">Fecha:</label>
			                    <div class="input-group">
			                        <input type="text" name="fecha_" id="fecha_" readonly="readonly" class="form-control clsDatePicker"> 
			                        <span class="input-group-addon">
			                            <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
			                        </span>
			                    </div>
	  						</div>
	  						<div class="form-row col-md-12" style="margin-top: 10px;">
	  							<button class="btn btn-info" style="width: 100%;" onclick="guardar_retiro();">Guardar</button>
	  						</div>

							<div class="form-row col-md-12">
								<fieldset>
									<legend>Filtro por Mes</legend>
									<div class="form-group col-md-12">
										<div class="col-md-12">
							              	<label for="fecha_desde">Mes y Año</label>
							            </div>
							              <div class="col-md-6">
							              		<select class="form-control" id="mes_">
							              			<option value="1">Enero</option>
							              			<option value="2">Febrero</option>
							              			<option value="3">Marzo</option>
							              			<option value="4">Abril</option>
							              			<option value="5">Mayo</option>
							              			<option value="6">Junio</option>
							              			<option value="7">Julio</option>
							              			<option value="8">Agosto</option>
							              			<option value="9">Septiembre</option>
							              			<option value="10">Octubre</option>
							              			<option value="11">Noviembre</option>
							              			<option value="12">Diciembre</option>
							              		</select>
							              </div>
						              
						             	<div class="col-md-6">
						              		<input type="text" id="anio_" class="form-control" name="" placeholder="Ejemplo: 2019">
						              	</div>
						              	<div class="col-md-12" style="margin-top: 10px;">
						              		<button class="btn btn-success" style="width: 100%;" onclick="buscar_mes();">Filtrar</button>
						              	</div>
						          </div>
							</fieldset>
							</div>
							<div id="div_robar">
								<table class="table table-bordered table-hover" id="tabla_retiros">
									<thead>
										<tr>
											<th>Tipo</th>
											<th>Concepto</th>
											<th>Monto</th>
											<th>Fecha</th>
											<th style="text-align: center;">Pagos</th>
										</tr>
									</thead>
									<tbody id="tabla_retiros_t">
									</tbody>
								</table>
							</div>
	  					</div>
	  					<span class="btn btn-danger" onclick="cerrar_editar()">Cerrar</span>
	  					<a class="btn btn-info pull-right" id="reportar_2" target="_blanck">Exportar</a>
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
		var htmll = '<!DOCTYPE html>'+
			'<html lang="es">'+
			'<head>'+
				'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'+
				'<title>Document</title>'+
				'<link href="http://www.peruviandress.com/sivecsol/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">'+
				'<style>.page_break { page-break-before: always; } body{font-size: 11px;}</style>'+
			'</head>'+
			'<body>'+
				'<img src="/home/peruvian/public_html/sivecsol/img/logo.png">'+
				'<h3 style="font-weight: bold; text-align: center;">Reporte de Retiros</h3>'+
				'<!--<table class="table table-bordered table-hover" id="tabla_retiros">'+
					'<tr>'+
						'<th>Tipo</th>'+
						'<th>Concepto</th>'+
						'<th>Monto</th>'+
						'<th>Fecha</th>'+
						'<th style="text-align: center;">Pagos</th>'+
					'</tr>-->';
		function buscar_mes(){
			var htth = htmll;
			$("#tabla_retiros_t").empty();
			$.get('core/app/view/cuentas_pagar.php', {
				parAccion: 'buscar_mes',
				mes: $("#mes_").val(),
				anio: $("#anio_").val()
			}, function(data) {
				var obj = JSON.parse(data);
  				$.each(obj.Records, function(index, val) {
					$("#tabla_retiros_t").append('<tr>'+
										'<td>'+
											val.tipo+
										'</td>'+
										'<td>'+
											val.concepto+
										'</td>'+
										'<td>'+
											val.monto+
										'</td>'+
										'<td>'+
											val.fecha+
										'</td>'+
										'<td>'+
											'<table class="table table-bordered table-hover" id="tabla_pagos_'+val.id+'">'+
												'<thead>'+
													'<th>Concepto</th>'+
													'<th>Fecha</th>'+
													'<th>Monto</th>'+
												'</thead>'+
												'<tbody>'+
												'</tbody>'+
											'</table>'+
										'</td>'+
									'</tr>');
					
					$.get('core/app/view/cuentas_pagar.php', {
						parAccion: 'lista_pagos',
						id: val.id
					}, function(responseText) {
						z = "";
						var p = JSON.parse(responseText);
						$.each(p.Records, function(i, v) {
							z = z + '<tr>'+
														'<td>'+v.concepto+'</td>'+
														'<td><i class="fa fa-calendar"></i> '+v.fecha_pago+'</td>'+
														'<td>S/. '+v.monto+'</td>'+
													'</tr>';

						});
						$("#tabla_pagos_"+val.id).find('tbody').append(z);
					});
  					
  				});
  				htth = htth + $("#div_robar").html();
		        htth = htth + '</body></html>';
		        console.log(htth);
		        docu_2(htth);
  				$("#reportar_2").attr('href', 'http://peruviandress.com/sivecsol/core/app/view/pdf-cuentas.php?tipo=retiros&extra=extra');
			});
		}
		var fecha = new Date();
  		var mes = fecha.getMonth();
  		function guardar_retiro(){
  			var concepto = $("#detalle").val();
  			var tipo = $("#tipo").val();
  			var monto = $("#monto_").val();
  			var fecha = $("#fecha_").val();
  			$.get('core/app/view/cuentas_pagar.php', {
  				parAccion: 'guardar_retiro',
  				concepto: concepto,
				monto: monto,
				fecha: fecha,
				tipo: tipo
  			}, function(data) {
  				var obj = JSON.parse(data);
  				if (obj.Result == 'OK') {
					bootbox.alert({
		                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Retiro Agregado Correctamente.</strong>'+
		                    '</div>'
		            });
		            //lista_cuentas(mes + 1);
		            formulario();
		            suma_saldo();
				}else{
					bootbox.alert({
		                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Ago ha salido mal.</strong>'+
		                    '</div>'
		            });
				}
  			});
  		}
  		function cerrar_editar(){
			$('#close_editar').click();
		}
		function cerrar_editar_2(){
			$('#close_editar_2').click();
		}
  		function formulario(){
  			var htth = htmll;
  			var html = "";
  			var z = "";
  			$("#tabla_retiros").find('tbody').empty();
  			$.get('core/app/view/cuentas_pagar.php', {
  				parAccion: 'lista_retiros'
  			}, function(data) {
  				var obj = JSON.parse(data);
  				$.each(obj.Records, function(index, val) {
					$("#tabla_retiros_t").append('<tr>'+
										'<td>'+
											val.tipo+
										'</td>'+
										'<td>'+
											val.concepto+
										'</td>'+
										'<td>'+
											val.monto+
										'</td>'+
										'<td>'+
											val.fecha+
										'</td>'+
										'<td>'+
											'<table class="table table-bordered table-hover" id="tabla_pagos_'+val.id+'">'+
												'<thead>'+
													'<th>Concepto</th>'+
													'<th>Fecha</th>'+
													'<th>Monto</th>'+
												'</thead>'+
												'<tbody>'+
												'</tbody>'+
											'</table>'+
										'</td>'+
									'</tr>');
					
					$.get('core/app/view/cuentas_pagar.php', {
						parAccion: 'lista_pagos',
						id: val.id
					}, function(responseText) {
						z = "";
						var p = JSON.parse(responseText);
						$.each(p.Records, function(i, v) {
							z = z + '<tr>'+
														'<td>'+v.concepto+'</td>'+
														'<td><i class="fa fa-calendar"></i> '+v.fecha_pago+'</td>'+
														'<td>S/. '+v.monto+'</td>'+
													'</tr>';

						});
						$("#tabla_pagos_"+val.id).find('tbody').append(z);
					});
  					
  				});
  				htth = htth + $("#div_robar").html();
		        htth = htth + '</body></html>';
		        console.log(htth);
		        docu(htth);
  				$("#reportar_2").attr('href', 'http://peruviandress.com/sivecsol/core/app/view/pdf-cuentas.php?tipo=retiros');
  			});
  			$('#popup_editar').fadeIn('slow');
	        $('.popup-overlay').fadeIn('slow');
	        $('.popup-overlay').height($(window).height());
	        return false;
	        
  		}
  		function docu(ht){
  			$.post('core/app/view/crear_html.php', {html: ht}, function(data) {
  				/*optional stuff to do after success */
  			});
  		}
  		function docu_2(ht){
  			$.post('core/app/view/crear_html_2.php', {html: ht}, function(data) {
  				/*optional stuff to do after success */
  			});
  		}
  		function suma_saldo(){
  			var sum = 0;
  			$.get('core/app/view/cuentas_pagar.php', {
  				parAccion: 'lista_retiros_2'
  			}, function(data) {
  				var obj = JSON.parse(data);
  				$.each(obj.Records, function(index, val) {
  					sum = parseFloat(sum) + parseFloat(val.saldo);
  				});
  				$("#saldo_t").text("S/. " + parseFloat(sum).toFixed(2));
  			});
  		}
  		function abrir_pagar(id) {
  			$("#lista_retiros").empty();
  			$("#btn_pagar_cuenta").attr('onclick', 'pagar('+id+');');
  			$.get('core/app/view/cuentas_pagar.php', {
  				parAccion: 'lista_retiros_2'
  			}, function(data) {
  				var obj = JSON.parse(data);
  				$.each(obj.Records, function(index, val) {
  					$("#lista_retiros").append('<label class="radio-inline"><input type="radio" name="optradio" value="'+val.id+'">'+val.concepto+' - S/.'+val.saldo+'</label>');	
  				});
  			});
  			$('#popup_editar_2').fadeIn('slow');
	        $('.popup-overlay').fadeIn('slow');
	        $('.popup-overlay').height($(window).height());
	        return false;
  		}
  		function pagar(id){
  			var retiro = $('input:radio[name=optradio]:checked').val();  			
  			$.get('core/app/view/cuentas_pagar.php', {
  				parAccion: 'pagar_cuenta',
  				id: id,
  				retiro: retiro
  			}, function(data) {
  				var obj = JSON.parse(data);
  				if (obj.Result == 'OK') {
					bootbox.alert({
		                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Cuenta Pagada Correctamente.</strong>'+
		                    '</div>'
		            });
		            lista_cuentas();
		            suma_saldo();
				}else{
					bootbox.alert({
		                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Ago ha salido mal.</strong>'+
		                    '</div>'
		            });
				}
  			});
  		}
		function guardar_cuenta(){
			var concepto = $("#concepto").val();
			var fecha_vencimiento = $("#fecha_desde").val();
			var prioridad = $("#prioridad").val();
			var monto = $("#monto").val();
			var estado = $("#estado").val();
			$.get('core/app/view/cuentas_pagar.php',{
				parAccion: 'guardar_cuenta',
				concepto: concepto,
				fecha_vencimiento: fecha_vencimiento,
				prioridad: prioridad,
				monto: monto,
				estado: estado
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Result == 'OK') {
					bootbox.alert({
		                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Cuenta Registrada Correctamente.</strong>'+
		                    '</div>'
		            });
		            //lista_cuentas(mes + 1);
		            lista_cuentas();
				}else{
					bootbox.alert({
		                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">'+
		                        '<strong>Ago ha salido mal.</strong>'+
		                    '</div>'
		            });
				}
			});
		}
		function lista_cuentas(){
			$("#tabla_lista").find('tbody').empty();
			$.get('core/app/view/cuentas_pagar.php', {
				parAccion: 'lista_cuentas'
			}, function(data) {
				var obj = JSON.parse(data);
				if (obj.Records.length > 0) {
					$.each(obj.Records, function(index, val) {
						var cls = "";
						var e = "";
						var r = "";
						if (val.prioridad == 1) {
							cls = "danger";
						} else {
							if (val.prioridad == 2) {
								cls = "warning";
							} else {
								if (val.prioridad == 3) {
									cls = "success";
								} else {
									if (val.prioridad == 4) {
										cls = "primary";
									} else {
										if (val.prioridad == 5) {
											cls = "info";
										}
									}
								}
							}
						}
						if (val.estado == 1) {
							e = "Pagado";
							r = "hidden";
						} else {
							if (val.estado == 0) {
								e = "Debe";
							}
						}
						$("#tabla_lista").find('tbody').append('<tr class="'+cls+'"><td>'+val.concepto+'</td><td>S/. '+val.monto+'</td><td>'+val.fecha_vencimiento+'</td><td>'+e+'</td><td><a href="#" onclick="abrir_pagar('+val.id+');" class="btn-xs btn-success" '+r+'><i class="fa fa-check"></i></a></td></tr>');
					});
					$("#reportar").removeAttr('disabled');
					$("#reportar").attr('href', 'http://peruviandress.com/sivecsol/core/app/view/pdf-cuentas.php?tipo=cuentas');
				}else{
					$("#tabla_lista").find('tbody').append('<tr class="danger" style="text-align: center;"><td colspan="5">Sin Registros</td></tr>');
					$("#reportar").attr('disabled', 'disabled');
				}
				
			});
		}
		$(document).ready(function() {
  			lista_cuentas();
  			suma_saldo();
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
