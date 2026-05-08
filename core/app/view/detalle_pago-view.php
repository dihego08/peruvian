<?php
$act = $_GET['act'];
if ($act == "delpago") {
    $pagoId = $_GET['paid'];
    $pago = new SellData();
    $pago->delPagoById($pagoId);
} elseif ($act == "aped") {
    $ventaActId = $_GET['vid'];
    $txtPedido = $_POST['txtPedido'];
    $ventaAct = new SellData();
    $ventaAct->update_ventas_pedido($txtPedido, $ventaActId);
}
//$pagos = SellData::getDetallePago($_GET['cid'], $_GET['vid']);
$venta = SellData::getVentaSunatById($_GET['cid'], $_GET['vid']);
$cliente = PersonData::getById($_GET['cid']);
?>

<style type="text/css">
    #popup_editar {

        left: 0;

        position: absolute;

        top: 0;

        width: 100%;

        z-index: 1001;

    }

    .content-popup {

        margin: 0px auto;

        margin-top: 2%;

        position: relative;

        padding: 10px;

        width: 75%;

        /*min-height:250px;*/

        border-radius: 4px;

        background-color: #FFFFFF;

        box-shadow: 0 2px 5px #666666;

    }

    .content-popup h2 {

        color: #48484B;

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

        display: none;

        background-color: #777777;

        cursor: pointer;

        opacity: 0.7;

    }

    .close {

        position: absolute;

        right: 15px;

    }

    .clsDatePicker {
        position: absolute;
        cursor: default;
        z-index: 1001 !important
    }

    .ui-datepicker-month {

        color: #313131;

    }

    .ui-datepicker-year {
        color: #313131;
    }

    fieldset {
        background-color: #eeeeee;
        border: solid 1px #bbb;
        padding: 5px;
    }

    legend {
        background-color: gray;
        color: white;
        padding: 5px 10px;
        width: auto;
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h3>HISTORIAL DE PAGOS</h3>
            <div class="w-100 text-right">
                <a class="btn btn-outline-dark rounded-pill" href="core/app/view/pdf-detallepagos.php?cid=<?php echo $_GET['cid'] ?>&vid=<?php echo $_GET['vid'] ?>">Imprimir</a>
            </div>
            <div class="box box-primary" style="display: flex;">
                <div class="form-group col-md-12" id="div_venta">
                    <table class="table table-bordered table-hover" id="detalle_venta" border="1">
                        <tbody>
                            <tr>
                                <td><b>Fecha :</b></td>
                                <td><?php echo $venta->fecha_creacion; ?></td>
                                <td><b>Nro Documento :</b></td>
                                <td><?php echo $_GET['vid'] ?></td>
                                <td>
                                    <b>Nro Pedido: </b>
                                </td>
                                <td>
                                    <form name="frmActPedido" action="index.php?view=detalle_pago&act=aped&vid=<?php echo $_GET['vid'] ?>&cid=<?php echo $_GET['cid'] ?>" method="POST">
                                        <input type="text" class="form-control rounded-pill" name=" txtPedido" value="<?php echo $venta->pedido_cod; ?>" />
                                        <input type="submit" class="btn btn-outline-success rounded-pill" value="Actualizar" />
                                    </form>
                                </td>
                                <td><b>Cliente :</b></td>
                                <td><?php echo $cliente->name; ?></td>
                            </tr>
                            <tr>
                                <td><b>TOTAL A PAGAR:</b></td>
                                <td><?php echo $venta->valor_pagar; ?></td>
                                <td><b>TOTAL PAGADO:</b></td>
                                <td><?php echo $venta->pagado; ?></td>
                                <td><b>TOTAL ADEUDO:</b></td>
                                <td><?php echo $venta->a_cuenta; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="w-100 text-center">
                        <a href="index.php?action=recalcularsaldoventa&cid=<?php echo $_GET['cid'] ?>&vid=<?php echo $_GET['vid'] ?>" class="btn btn-outline-success rounded-pill" id="boton_recalcular_saldo">Recalcular Saldo</a>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <fieldset class="form-row">
                    <legend>Nuevo Pago:</legend>
                    <div class="col-md-2">
                        <label>Fecha de Pago</label>
                        <div class="input-group">
                            <input type="text" name="fecha_pago" id="fecha_pago" class="form-control clsDatePicker">
                            <span class="input-group-addon">
                                <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>Monto</label>
                        <input type="text" class="form-control rounded-pill" id="monto_pago">
                    </div>
                    <div class="col-md-4">
                        <label>Concepto</label>
                        <input type="text" class="form-control rounded-pill" id="concepto">
                    </div>
                    <div class="col-md-3">
                        <label>Banco</label>
                        <select name="" id="banco" class="form-control rounded-pill">
                            <option value="">--SELECCIONA--</option>
                            <option value="BCP">BCP</option>
                            <option value="SCOTIABANK">SCOTIABANK</option>
                            <option value="BBVA CONTINENTAL">BBVA CONTINENTAL</option>
                            <option value="INTERBANK">INTERBANK</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>Guardar</label><br>
                        <button class="btn btn-success rounded-pill" onclick="add_pago();"><i class="fa fa-plus"></i></button>
                    </div>
                </fieldset>
            </div>

            <div class="row">

                <div id="resultado_busqueda_order">
                    <div class="col-md-12">
                        <div>
                            <h2 id="titulo_detalle"></h2>
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover" id="detalle_pagos">
                                    <thead>
                                        <th>Fecha de Pago</th>
                                        <th>Monto</th>
                                        <th>Concepto</th>
                                        <th>Banco</th>
                                        <th>Adeuda</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.full.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet" />

<script type="text/javascript">
    function actualizar_pago_historial(pago_cod) {
        $.get('core/app/view/venta.php', {
            parAccion: 'actualizar_pago_historial',
            pago_cod: pago_cod,
            monto_pagado: $("#pagado").val(),
            cli_id: '<?= $_GET['cid']; ?>',
            fecha: $("#fecha_p").val(),
            banco: $("#banco_").val(),
            concepto: $("#concepto_edit").val()
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.Result == 'OK') {
                carga_historial_pago('<?= $_GET['cid']; ?>', '<?= $_GET['vid']; ?>', '0');
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function add_pago() {
        $.post('core/app/view/venta.php?parAccion=add_pago', {
            cli_id: '<?= $_GET['cid']; ?>',
            vid: '<?= $_GET['vid']; ?>',
            fecha_pago: $("#fecha_pago").val(),
            monto_pago: $("#monto_pago").val(),
            total: '<?= $venta->valor_pagar; ?>',
            adeuda: '<?= $venta->a_cuenta; ?>',
            banco: $("#banco").val(),
            concepto: $("#concepto").val(),
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.Result == "OK") {
                location.reload();
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function eliminar_pago_historial(pago_cod) {
        $.get('core/app/view/venta.php', {
            parAccion: 'eliminar_pago_historial',
            pago_cod: pago_cod
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.Result == 'OK') {
                bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Pago Eliminado Correctamente.</strong>' +
                        '</div>'
                });
                carga_historial_pago('<?= $_GET['cid']; ?>', '<?= $_GET['vid']; ?>', '0');
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function recalcular_saldo(id_person, codigo_venta) {
        $.get('core/app/view/venta.php', {
            parAccion: 'recalcular_saldo',
            codigo_venta: codigo_venta,
            cli_id: id_person
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.Result == 'OK') {
                bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Saldo Actualizado.</strong>' +
                        '</div>'
                });
                carga_historial_pago('<?= $_GET['cid']; ?>', '<?= $_GET['vid']; ?>', '0');
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function carga_historial_pago(id_person, codigo_venta, pago_editar) {
        console.log("carga historial de pagos de venta " + codigo_venta);
        $.get('core/app/view/venta.php', {
            parAccion: 'historial_pago',
            id_person: id_person,
            codigo_venta: codigo_venta
        }, function(data) {
            var obj = JSON.parse(data);
            //$("#titulo_detalle").append("CODIGO: "+codigo_venta);
            $("#detalle_pagos").find('tbody').empty();
            var los_bancos = "";
            $.each(obj.Records, function(index, val) {
                los_bancos = devolver_bancos(val.banco);
                var concepto = "";
                if (val.concepto == null) {

                } else {
                    concepto = val.concepto;
                }
                if (val.id == pago_editar) {
                    $("#detalle_pagos").find('tbody').append("<tr><td scope='row'><div class='input-group'><input type='text' size='10' name='fecha_p' id='fecha_p' class='form-control clsDatePicker' value='" + val.fecha_creacion + "'><span class='input-group-addon'><i id='calIconTourDateDetails' class='glyphicon glyphicon-th'></i></span></div></td><td><input type='text' name='pagado' id='pagado' class='form-control' value='" + val.pago + "''></td><td><input type='text' name='concepto_edit' id='concepto_edit' class='form-control' value='" + concepto + "''></td><td>" + los_bancos + "</td><td>S/. " + val.deuda + "</td><td><button class='btn btn-primary' id='boton_actualizar_pago' onclick=actualizar_pago_historial('" + val.id + "')>Actualizar</button></td></tr>");
                } else {
                    $("#detalle_pagos").find('tbody').append("<tr><td scope='row'>" + val.fecha_creacion + "</td><td>S/. " + val.pago + "</td><td>" + concepto + "</td><td>" + val.banco + "</td><td>S/. " + val.deuda + "</td><td><button class='btn btn-warning' style='display: block; margin-bottom: 0.5rem; width: 50%;' id='boton_editar_pago' onclick=carga_historial_pago('" + id_person + "','" + codigo_venta + "','" + val.id + "')>Editar</button><a href='index.php?view=detalle_pago&act=delpago&paid=" + val.id + "&vid=" + codigo_venta + "&cid=" + id_person + "' class='btn btn-danger' style='display: block; width: 50%;' id='boton_eliminar_pago'>Eliminar</button></td></tr>");
                }
            });

            $("#fecha_p").datetimepicker({
                format: "Y-m-d",
                timepicker: false
            });
        });
    }

    function devolver_bancos(banco) {
        var bancos = '<select name="" id="banco_" class="form-control">';
        var bancos_ = ["BCP", "SCOTIABANK", "BBVA CONTINENTAL", "INTERBANK"];
        bancos += '<option value="">--SELECCIONA--</option>';
        $.each(bancos_, function(index, val) {
            if (val == banco) {
                bancos += `<option value="${val}" selected>${val}</option>`;
            } else {
                bancos += `<option value="${val}">${val}</option>`;
            }

        });
        /*<option value=\"\">--SELECCIONA--</option><option value=\"SCOTIABANK\"></option><option value=\"\">BBVA CONTINENTAL</option><option value=\"\">INTERBANK</option></select>*/
        return bancos;
    }

    function editar_pago(id) {
        console.log("editar pago id " + id);
        $("#detalle_pagos").find('pago_' + id).empty();
        $("#detalle_pagos").find('pago_' + id).append('<td><input type="text" name="fecha_p" id="fecha_p" class="form-control clsDatePicker"> <span class="input-group-addon"><i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i></span></td></td><td><input type="text" name="pagado" id="pagado" class="form-control"></td><td><input type="text" name="a_deuda" id="a_deuda" class="form-control"></td><td><button class="btn btn-primary" style="width: 100%;" id="boton_actualizar_pago" onclick="actualizar_pago_historial()>Actualizar Pago</button></td>');
    }

    $(document).ready(function() {
        $(".clsDatePicker").datetimepicker({
            format: "Y-m-d",
            timepicker: false
        });
        $.datetimepicker.setLocale('es');
        carga_historial_pago('<?= $_GET['cid']; ?>', '<?= $_GET['vid']; ?>', '0');
    });
</script>