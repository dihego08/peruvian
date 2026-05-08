<style type="text/css">
	#popup_editar {
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 1001;
        }
        .content-popup {
            margin:0px auto;
            margin-top:10%;
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
<?php
$cajaId = $_GET['cid'];

$clsKardex = new SellData();
$rsKardex = $clsKardex->caja_lista_kardex($cajaId);
?>
<section class="content"> 
<div class="row">
	<div class="col-md-12">
		<h1><i class='fa fa-square-o'></i> KARDEX</h1>
		<div class="clearfix"></div>
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
                        <button class="btn btn-success" onclick="filtrar_kardex(<?php echo $cajaId; ?>);" style="width: 100%;">Filtrar</button>
                    </div>
                </div>
        </fieldset>
		<br>
		<div class="box box-primary">
			<div class="box-body table-responsive" style="padding: 0;">
				<table id="tabla_kardex" class="table  datatable table-bordered  table-hover">
	                <thead>
	                    <th colspan = "4">ABONOS</th>
	                    <th colspan = "6">CARGOS</th>
	                </thead>
					<thead>
	                    <th>CODIGO</th>
						<th>BANCO</th>
						<th>PERIODO</th>
						<th>FECHA</th>
						<th>MONTO</th>
						<th>FECHA</th>
						<th>CONCEPTO</th>
	                    <th>PERIODO</th>
	                    <th>MONTO</th>
	                    <th>SALDO</th>
						<th>Acciones</th>
					</thead>
					<tbody>
						<?php foreach($rsKardex as $kardex){?>
						<tr><td><?php echo $kardex->caja_mov_id; ?></td>
							<td><?php echo $kardex->abono_banco; ?></td>
							<td><?php echo $kardex->abono_periodo; ?></td>
							<td><?php echo $kardex->abono_fecha; ?></td>
							<td>S/. <?php echo $kardex->abono_monto; ?></td>
							<td><?php echo $kardex->cargo_fecha; ?></td>
							<td><?php echo $kardex->cargo_concepto; ?></td>
							<td><?php echo $kardex->cargo_periodo; ?></td>
							<td><?php echo $kardex->cargo_monto; ?></td>
							<td><?php echo $kardex->cargo_saldo; ?></td>
							<td><span class="btn-xs btn-danger" onclick="eliminar(<?php echo $kardex->caja_mov_id; ?>,<?php echo $kardex->kardex_tipo; ?>,<?php echo $kardex->cargo_abono_id; ?>);" style="cursor: pointer;"><i class="fa fa-trash"></i></span></td>
						</tr>
						<?php }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</section>
<script type="text/javascript">

            function eliminar(id,tipo,abono_id){
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
                        $.get('core/app/view/caja.php', {
                            parAccion: 'eliminar_kardex',
                            kardex_id: id,
                            kardex_tipo: tipo,
                            abono_id: abono_id
                        }, function(data) {
                            var obj = JSON.parse(data);
                            if (obj.Result == 'OK') {
                                lista_kardex(<?php echo $_GET['cid'] ?>);
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

            function filtrar_kardex(cajaId){
                $("#tabla_kardex").find('tbody').empty();
                $.get('core/app/view/caja.php', {
                    parAccion: 'filtrar_kardex',
                    caja_id: cajaId,
                    desde: $("#fecha_desde").val(),
                    hasta: $("#fecha_hasta").val()
                }, function(data) {
                    var obj = JSON.parse(data);

                    $.each(obj.Records, function(index, val) {
                    	var fecha_abono = val.abono_fecha;
                    	if(val.kardex_tipo == 2){
                    		fecha_abono = '';
                    	}
                        $("#tabla_kardex").find('tbody').append('<tr><td>'+val.caja_mov_id+'</td><td><!--<input type="checkbox" value="'+val.id+'" name="kardex[]" />-->'+val. abono_banco+'</td><td>'+val.abono_periodo+'</td><td>'+fecha_abono+'</td><td>S/. '+val.abono_monto+'</td><td>'+val.cargo_fecha+'</td><td>'+val.cargo_concepto+'</td><td>'+val.cargo_periodo+'</td><td>'+val.cargo_monto+'</td><td>'+val.cargo_saldo+'</td><td><span class="btn-xs btn-danger" onclick="eliminar('+val.caja_mov_id+','+val.kardex_tipo+','+val.cargo_abono_id+');" style="cursor: pointer;"><i class="fa fa-trash"></i></span></td></tr>');
                    });
                });
            }


            function lista_kardex(cajaId){
                $("#tabla_kardex").find('tbody').empty();
                $.get('core/app/view/caja.php', {
                    parAccion: 'lista_kardex',
                    caja_id: cajaId
                }, function(data) {
                    var obj = JSON.parse(data);
                    $.each(obj.Records, function(index, val) {
                        
                        /*
                        $("#tabla_kardex").find("tbody").append("<tr><td>"+val. abono_banco+"</td><td>"+val.abono_periodo+"</td><td>"+val.abono_fecha+"</td><td>S/. "+val.abono_monto+"</td><td>"+val.cargo_fecha+"</td><td>"+val.cargo_concepto+"</td><td>"+val.cargo_periodo+"</td><td>"+val.cargo_monto+"</td><td>"+val.cargo_saldo+"</td><td><span class='btn-xs btn-danger' onclick='eliminar('"+val.caja_mov_id+"','"+val.tipo+"');' style='cursor: pointer;'><i class='fa fa-trash'></i></span></td></tr>");*/

                        $("#tabla_kardex").find('tbody').append('<tr><td>'+val.caja_mov_id+'</td><td><!--<input type="checkbox" value="'+val.id+'" name="kardex[]" />-->'+val. abono_banco+'</td><td>'+val.abono_periodo+'</td><td>'+val.abono_fecha+'</td><td>S/. '+val.abono_monto+'</td><td>'+val.cargo_fecha+'</td><td>'+val.cargo_concepto+'</td><td>'+val.cargo_periodo+'</td><td>'+val.cargo_monto+'</td><td>'+val.cargo_saldo+'</td><td><span class="btn-xs btn-danger" onclick="eliminar('+val.caja_mov_id+','+val.kardex_tipo+','+val.cargo_abono_id+');" style="cursor: pointer;"><i class="fa fa-trash"></i></span></td></tr>');
                    });
                });
            }

             $(document).ready(function() {
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
                //lista_kardex(<?php echo $_GET['cid'] ?>);
                

            });
    </script>