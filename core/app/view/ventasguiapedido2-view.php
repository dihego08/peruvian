<style type="text/css">
	.ct-label {
    font-size: 15px;
    color:black;
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
 </style>
<section class="content">
<div class="row">
	<div class="col-md-12">
		<!-- Single button -->
		<h1><i class="glyphicon glyphicon-stats"></i> Ventas Guias y Pedidos</h1>
		<!--<a onclick="thePDF()" class="btn btn-default">Descargar PDF</a><br><br>-->
		<div class="clearfix"></div>
		<div class="box">
		  <div class="box-header">
		  	<fieldset>
				<legend>Filtro por Fecha</legend>
				<div class="form-row">
	                <div class="form-group col-md-6">
	                    <label for="fecha_desde">Desde:</label>
	                    <div class="input-group">
	                        <input type="text" name="fecha_desde" id="fecha_desde" readonly="readonly" class="form-control clsDatePicker"> 
	                        <span class="input-group-addon">
	                            <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
	                        </span>
	                    </div>
	                </div>
	                <div class="form-group col-md-6">
	                    <label for="fecha_hasta">Hasta:</label>
	                    <div class="input-group">
	                        <input type="text" name="fecha_hasta" id="fecha_hasta" readonly="readonly" class="form-control clsDatePicker"> 
	                        <span class="input-group-addon">
	                            <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
	                        </span>
	                    </div>
	                </div>
	                <div class="form-group col-md-12">
	                	<button class="btn btn-success" onclick="buscar_por_fecha();" style="width: 100%;">Buscar por Fecha</button>
	                </div>
	            </div>
			</fieldset>
		  </div><!-- /.box-header -->
		  <div class="box-body">
		  	<table class="table table-bordered datatable table-hover" id="tabla_lista">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Nro Documento</th>
						<th>Nro Guia</th>
				    	<th>Nro Pedido</th>
			    	</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		  </div><!-- /.box-body -->
		</div><!-- /.box -->		
	</div>
</div>

</section>






<script type="text/javascript">
		function buscar_por_fecha(){
			lista_datos();
		
    	}

		function lista_datos(){
				$("#tabla_lista").find('tbody').empty();
				//$("#reportar").attr('href', 'http://peruviandress.com/sivecsol/core/app/view/generar_pdf.php?filtro=ninguno&tabla=compras');
				$.get('core/app/view/venta.php', {
					parAccion: 'lista_rep_ventas_guia_pedido',
					desde: $("#fecha_desde").val(),
					hasta: $("#fecha_hasta").val()
				}, function(data) {
					var obj = JSON.parse(data);
					$.each(obj.Records, function(index, val) {
						
						$("#tabla_lista").find('tbody').append('<tr>'+
								'<td>'+val.fecha+'</td>'+
								'<td>'+val.venta+'</td>'+
								'<td>'+val.guia+'</td>'+
								'<td>'+val.pedido+'</td>'+
							'</tr>');
					});
				});
		}


		
		
        $(document).ready(function(){
        	var d = new Date();
			d.setMonth(d.getMonth() - 1);
        	$('#fecha_desde').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                altField: "#fecha_nacimiento_hidden",
                altFormat: "yy-mm-dd"
            }).datepicker("setDate", d);
			$('#fecha_hasta').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                altField: "#fecha_nacimiento_hidden",
                altFormat: "yy-mm-dd"
            }).datepicker("setDate", new Date());
            
        	//lista_datos();
        	//grafico();
        	//grafico_clientes();
        	//grafico_ventas_clientes();
        	//grafico_ventas_producto();
        	
        });
    </script>