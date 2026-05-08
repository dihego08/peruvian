<?php $user = UserData::getById($_SESSION["user_id"]); ?>
<style>
    .ui-autocomplete {
        position: absolute;
        cursor: default;
        z-index: 1001 !important
    }

    #v {
        width: 320px;
        height: 240px;
    }

    #qr-canvas {
        display: none;
    }

    #qrfile {
        width: 320px;
        height: 240px;
    }

    #mp1 {
        text-align: center;
        font-size: 35px;
    }

    #imghelp {
        position: relative;
        left: 0px;
        top: -160px;
        z-index: 100;
        font: 18px arial, sans-serif;
        background: #f0f0f0;
        margin-left: 35px;
        margin-right: 35px;
        padding-top: 10px;
        padding-bottom: 10px;
        border-radius: 20px;
    }

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

    @media (max-width: 600px) {
        .tdd {
            padding: 1px !important;
        }
    }

    .form-control {
        font-size: 10px !important;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h3>Orden de Pedido </h3>
            <?php
            if ($cli != "") {
            ?>
                <h2>CLIENTE : <?php echo ($cli); ?> </h2>
            <?php
            }
            ?>
            <p><b>Agregar una nueva Orden de Pedido:</b></p>
            <div class="row">
                <div class="col-md-12">
                    <label>Modelo</label>
                    <div class="input-group mb-3" style="display: flex;">
                        <input style="font-size:14px !important;" type="text" id="product_name" name="product_name" class="form-control ui-autocomplete-input rounded-pill-left" placeholder="Modelo ...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="buscar_modelo();"><i class="glyphicon glyphicon-zoom-in"></i></button>
                        </div>
                    </div>
                </div>

                <div id="resultado" style="padding: 25px 25px 0 25px; margin-top: 25px; margin-bottom: 0px;">
                    <div class="box box-primary table-responsive" style="margin-top: 20px;">
                        <table class="table table-bordered table-hover" id="tabla_resultado">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle; text-align: center; width: 140px;">Color</th>
                                    <th colspan="13" style="text-align: center;">Cantidades por Talla</th>
                                    <th rowspan="2" style="vertical-align: middle; text-align: center;"></th>
                                </tr>
                                <tr id="la_cabecera_de_datos">

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <form id="formulario" enctype="multipart/form-data">
                    <input type="hidden" name="codigo" id="codigo" value="<?php echo $_GET['codigo']; ?>">
                    <div class="col-md-12" id="div_entrega">
                        <div class="box box-primary table-responsive" style="margin-top: 20px;">
                            <table class="table table-bordered table-hover" id="tabla_resultado_2">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle; text-align: center;">Modelo</th>
                                        <th style="vertical-align: middle; text-align: center;">Color</th>
                                        <th colspan="14" style="text-align: center;">Cantidades por Talla</th>
                                        <th style="vertical-align: middle; text-align: center;">Eliminar</th>
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
                    <div class="col-md-3">
                        <label>Núm. Contrato</label>
                        <input type="text" id="num_contrato" nam style="font-size:14px !important;" name="num_contrato" class="form-control rounded-pill" placeholder="Número de Contrato" style="margin-bottom: 10px;">
                    </div>
                    <div class="col-md-3">
                        <label>Tiempo de Entrega (días)</label>
                        <input type="text" id="tiempo_entrega" name="tiempo_entrega" style="font-size:14px !important;" class="form-control rounded-pill" placeholder="Tiempo de Entrega" style="margin-bottom: 10px;">
                    </div>
                    <div class="col-md-3">
                        <label>Cliente</label>
                        <select style="font-size:14px !important;" class="form-control rounded-pill" name="s_cliente" id="s_cliente">
                            <option value="0">SELECCIONE ...</option>
                        </select>
                    </div>
                    <div class="col-md-3" style="margin-bottom: 5px;">
                        <label for="fecha_desde">Fecha:</label>
                        <div class="input-group">
                            <input type="text" name="fecha_desde" id="fecha_desde" readonly="readonly" style="font-size:14px !important;" class="form-control clsDatePicker">
                            <span class="input-group-addon">
                                <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 5px;">
                        <label>Comentario</label>
                        <textarea style="font-size:14px !important;" class="form-control" name="comentario" id="comentario"></textarea>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 5px;">
                        <label>Imagen</label>
                        <div style="position: relative;">
                            <img src="" id="image_muestra" class="thumbnail center-block" style="width: 150px;">
                            <input type="hidden" id="img_m" name="img_m" value="">
                            <span class="btn btn-outline-danger rounded-pill" onclick="quitar_foto();" style="cursor: pointer; position: absolute; top: 10%; right: 35%;">
                                <i class="fa fa-trash"></i>
                            </span>
                            <label class="btn btn-outline-primary rounded-pill" style="cursor: pointer; position: absolute; top: 10%; left: 35%;">
                                <i class="glyphicon glyphicon-picture"></i>
                                <input type="file" name="nueva_foto" id="nueva_foto" style="display: none;">
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label>Producto</label>
                        <input type="text" style="font-size:14px !important;" class="form-control rounded-pill" id="nombre_producto" name="nombre_producto">
                    </div>

                    <div class="col-md-12">
                        <a href="#" class="btn btn-danger rounded-pill" onclick="cancel_order();">Cancelar</a>
                        <button class="btn btn-success rounded-pill">Modificar Orden</button>
                    </div>
                    <input type="hidden" name="usuario" value="<?php echo ($user->name); ?>" id="usuario" />
                </form>
            </div>
            <div id="popup_editar" style="display: none;">
                <div class="content-popup">
                    <div class="close"><a href="#" id="close_editar"><img src="../css/images/close.png" /></a></div>
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
                    </div>
                </div>
            </div>
            <div class="popup-overlay"></div>

            <script>
                function data(codigo) {
                    $.post('core/app/view/order.php?parAccion=get_orden', {
                        codigo: codigo
                    }, function(response) {
                        var obj = JSON.parse(response);
                        $("#num_contrato").val(obj.cabecera.num_contrato);
                        $("#tiempo_entrega").val(obj.cabecera.tiempo_entrega);
                        //$("#s_cliente").val(6);
                        console.log(obj.cabecera.person_id);
                        $("#s_cliente option[value='" + obj.cabecera.person_id.toString() + "']").prop('selected', true);

                        $("#fecha_desde").val(obj.cabecera.fecha_entrega);
                        $("#comentario").val(obj.cabecera.comentario);
                        $("#image_muestra").attr("src", "storage/products/" + obj.cabecera.imagen_alt);
                        $("#nombre_producto").val(obj.cabecera.nombre_modelo);
                        $("#img_m").val(obj.cabecera.imagen_alt);

                        agregar_listado(obj.detalle);
                    });
                }

                function buscar_modelo() {
                    $.post('core/app/view/order.php?parAccion=buscar_modelo', {
                        product_name: $("#product_name").val()
                    }, function(data) {
                        var obj = JSON.parse(data);
                        $("#image_muestra").attr("src", "storage/products/" + obj.image);
                        $("#img_m").val(obj.image);

                        $("#nombre_producto").val(obj.name);
                    });
                }

                function quitar_foto() {
                    $("#image_muestra").attr('src', '');
                    $("#img_m").val("");
                }
                var zux = 0;
                var k = <?php echo Core::$user->kind; ?>;
                var kk = 0;

                switch (k) {
                    case 1:
                        kk = 0;
                        break;
                    case 8:
                        kk = 2;
                        break;
                    case 7:
                        kk = 3;
                        break;
                    case 6:
                        kk = 5;
                        break;
                    case 9:
                        kk = 38;
                        break;
                    case 10:
                        kk = 39;
                        break;
                }

                function llenar() {
                    $("#tabla_resultado").find('tbody').empty();
                    $("#tabla_resultado").find('tbody').append('<tr>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_1" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_2" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_3" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_4" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_5" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_6" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_7" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_8" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_9" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_10" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_11" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_12" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_13" name=""></td>' +
                        '<td class="tdd"><input type="text" class="form-control rounded-pill tdd" id="c_14" name=""></td>' +
                        '<td><button class="btn-sm d-block btn-outline-success rounded-pill" onclick="agregar_listado_2();"><i class="fa fa-plus"></i></button></td>' +
                        '</tr>');
                }

                function cancel_order() {
                    zux = 0;
                    $("#tabla_resultado_2").find('tbody').empty();
                    $("#formulario")[0].reset();
                }
                var to = 0;

                function agregar_listado_2() {
                    var h = '';
                    $("#name_N1").text($("#N1").val() == "" ? $("#name_N1").text() : $("#N1").val());
                    $("#name_N2").text($("#N2").val() == "" ? $("#name_N2").text() : $("#N2").val());
                    $("#name_N3").text($("#N3").val() == "" ? $("#name_N3").text() : $("#N3").val());
                    $("#name_N4").text($("#N4").val() == "" ? $("#name_N4").text() : $("#N4").val());
                    $("#name_N5").text($("#N5").val() == "" ? $("#name_N5").text() : $("#N5").val());
                    $("#name_N6").text($("#N6").val() == "" ? $("#name_N6").text() : $("#N6").val());
                    $("#name_N7").text($("#N7").val() == "" ? $("#name_N7").text() : $("#N7").val());
                    $("#name_N8").text($("#N8").val() == "" ? $("#name_N8").text() : $("#N8").val());
                    $("#name_N9").text($("#N9").val() == "" ? $("#name_N9").text() : $("#N9").val());
                    $("#name_N10").text($("#N10").val() == "" ? $("#name_N10").text() : $("#N10").val());
                    $("#name_N11").text($("#N11").val() == "" ? $("#name_N11").text() : $("#N11").val());
                    $("#name_N12").text($("#N12").val() == "" ? $("#name_N12").text() : $("#N12").val());
                    $("#name_N13").text($("#N13").val() == "" ? $("#name_N13").text() : $("#N13").val());

                    var sto = 0;
                    sto = parseInt(sto) + ($("#c_2").val() ? parseInt($("#c_2").val()) : 0);
                    sto = parseInt(sto) + ($("#c_3").val() ? parseInt($("#c_3").val()) : 0);
                    sto = parseInt(sto) + ($("#c_4").val() ? parseInt($("#c_4").val()) : 0);
                    sto = parseInt(sto) + ($("#c_5").val() ? parseInt($("#c_5").val()) : 0);
                    sto = parseInt(sto) + ($("#c_6").val() ? parseInt($("#c_6").val()) : 0);
                    sto = parseInt(sto) + ($("#c_7").val() ? parseInt($("#c_7").val()) : 0);
                    sto = parseInt(sto) + ($("#c_8").val() ? parseInt($("#c_8").val()) : 0);
                    sto = parseInt(sto) + ($("#c_9").val() ? parseInt($("#c_9").val()) : 0);
                    sto = parseInt(sto) + ($("#c_10").val() ? parseInt($("#c_10").val()) : 0);
                    sto = parseInt(sto) + ($("#c_11").val() ? parseInt($("#c_11").val()) : 0);
                    sto = parseInt(sto) + ($("#c_12").val() ? parseInt($("#c_12").val()) : 0);
                    sto = parseInt(sto) + ($("#c_13").val() ? parseInt($("#c_13").val()) : 0);
                    sto = parseInt(sto) + ($("#c_14").val() ? parseInt($("#c_14").val()) : 0);
                    zux++;
                    // $("#tabla_resultado_2").find('tbody').append('<tr>' +
                    //     '<td>' + $("#product_name").val() + '<input type="hidden" value="' + $("#product_name").val() + '" name="nn_0_' + zux + '"></td>' +
                    //     '<td>' + $("#c_1").val() + '<input type="hidden" value="' + $("#c_1").val() + '" name="nn_1_' + zux + '" id="nn_1_' + zux + '"></td>' +
                    //     '<td>' + $("#c_2").val() + '<input type="hidden" value="' + $("#c_2").val() + '" name="nn_2_' + zux + '" id="nn_2_' + zux + '">  <input type="hidden" value="' + $("#N1").val() + '" name="celda_1_' + zux + '" id="celda_1_' + zux + '"></td>' +
                    //     '<td>' + $("#c_3").val() + '<input type="hidden" value="' + $("#c_3").val() + '" name="nn_3_' + zux + '" id="nn_3_' + zux + '">  <input type="hidden" value="' + $("#N2").val() + '" name="celda_2_' + zux + '" id="celda_2_' + zux + '"></td>' +
                    //     '<td>' + $("#c_4").val() + '<input type="hidden" value="' + $("#c_4").val() + '" name="nn_4_' + zux + '" id="nn_4_' + zux + '">  <input type="hidden" value="' + $("#N3").val() + '" name="celda_3_' + zux + '" id="celda_3_' + zux + '"></td>' +
                    //     '<td>' + $("#c_5").val() + '<input type="hidden" value="' + $("#c_5").val() + '" name="nn_5_' + zux + '" id="nn_5_' + zux + '">  <input type="hidden" value="' + $("#N4").val() + '" name="celda_4_' + zux + '" id="celda_4_' + zux + '"></td>' +
                    //     '<td>' + $("#c_6").val() + '<input type="hidden" value="' + $("#c_6").val() + '" name="nn_6_' + zux + '" id="nn_6_' + zux + '">  <input type="hidden" value="' + $("#N5").val() + '" name="celda_5_' + zux + '" id="celda_5_' + zux + '"></td>' +
                    //     '<td>' + $("#c_7").val() + '<input type="hidden" value="' + $("#c_7").val() + '" name="nn_7_' + zux + '" id="nn_7_' + zux + '">  <input type="hidden" value="' + $("#N6").val() + '" name="celda_6_' + zux + '" id="celda_6_' + zux + '"></td>' +
                    //     '<td>' + $("#c_8").val() + '<input type="hidden" value="' + $("#c_8").val() + '" name="nn_8_' + zux + '" id="nn_8_' + zux + '">  <input type="hidden" value="' + $("#N7").val() + '" name="celda_7_' + zux + '" id="celda_7_' + zux + '"></td>' +
                    //     '<td>' + $("#c_9").val() + '<input type="hidden" value="' + $("#c_9").val() + '" name="nn_9_' + zux + '" id="nn_9_' + zux + '">  <input type="hidden" value="' + $("#N8").val() + '" name="celda_8_' + zux + '" id="celda_8_' + zux + '"></td>' +
                    //     '<td>' + $("#c_10").val() + '<input type="hidden" value="' + $("#c_10").val() + '" name="nn_10_' + zux + '" id="nn_10_' + zux + '">  <input type="hidden" value="' + $("#N9").val() + '" name="celda_9_' + zux + '" id="celda_9_' + zux + '"></td>' +
                    //     '<td>' + $("#c_11").val() + '<input type="hidden" value="' + $("#c_11").val() + '" name="nn_11_' + zux + '" id="nn_11_' + zux + '">  <input type="hidden" value="' + $("#N10").val() + '" name="celda_10_' + zux + '" id="celda_10_' + zux + '"></td>' +
                    //     '<td>' + $("#c_12").val() + '<input type="hidden" value="' + $("#c_12").val() + '" name="nn_12_' + zux + '" id="nn_12_' + zux + '">  <input type="hidden" value="' + $("#N11").val() + '" name="celda_11_' + zux + '" id="celda_11_' + zux + '"></td>' +
                    //     '<td>' + $("#c_13").val() + '<input type="hidden" value="' + $("#c_13").val() + '" name="nn_13_' + zux + '" id="nn_13_' + zux + '">  <input type="hidden" value="' + $("#N12").val() + '" name="celda_12_' + zux + '" id="celda_12_' + zux + '"></td>' +
                    //     '<td>' + $("#c_14").val() + '<input type="hidden" value="' + $("#c_14").val() + '" name="nn_14_' + zux + '" id="nn_14_' + zux + '">  <input type="hidden" value="' + $("#N13").val() + '" name="celda_13_' + zux + '" id="celda_13_' + zux + '"></td>' +
                    //     '<td>' + sto + '<input type="hidden" name="tot_' + zux + '" id="tot_' + zux + '" value="' + sto + '"></td>' +
                    //     '<td><button class="borrar btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>');

                    h += '<tr class="tr_' + zux + '_n">' +
                        '<td rowspan="2" style="vertical-align: middle;">' + $("#product_name").val() + '<input type="hidden" value="' + $("#product_name").val() + '" name="nn_0_' + zux + '"></td>' +
                        '<td rowspan="2" style="vertical-align: middle;">' + $.trim($("#c_1").val()) + '<input type="hidden" value="' + $("#c_1").val() + '" name="nn_1_' + zux + '" id="nn_1_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N1").val() + '" name="celda_1_' + zux + '" id="celda_1_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N2").val() + '" name="celda_2_' + zux + '" id="celda_2_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N3").val() + '" name="celda_3_' + zux + '" id="celda_3_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N4").val() + '" name="celda_4_' + zux + '" id="celda_4_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N5").val() + '" name="celda_5_' + zux + '" id="celda_5_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N6").val() + '" name="celda_6_' + zux + '" id="celda_6_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N7").val() + '" name="celda_7_' + zux + '" id="celda_7_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N8").val() + '" name="celda_8_' + zux + '" id="celda_8_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N9").val() + '" name="celda_9_' + zux + '" id="celda_9_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N10").val() + '" name="celda_10_' + zux + '" id="celda_10_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N11").val() + '" name="celda_11_' + zux + '" id="celda_11_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N12").val() + '" name="celda_12_' + zux + '" id="celda_12_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" style="background: skyblue; text-align: center; font-weight: bold;" value="' + $("#N13").val() + '" name="celda_13_' + zux + '" id="celda_13_' + zux + '"></td>' +
                        '<td rowspan="2" style="vertical-align: middle;"><input type="text" class="form-control rounded-pill" readonly name="tot_' + zux + '" id="tot_' + zux + '" value="' + sto + '"></td>' +
                        '<td rowspan="2" style="vertical-align: middle; text-align: center;">' +
                        '<span class="borrar btn-xs btn-danger btn" id="' + zux + '"><i class="fa fa-trash"></i></span>' +
                        '</td></tr>' +

                        '<tr class="tr_' + zux + '_n">' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_2").val() + '" name="nn_2_' + zux + '" id="nn_2_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_3").val() + '" name="nn_3_' + zux + '" id="nn_3_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_4").val() + '" name="nn_4_' + zux + '" id="nn_4_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_5").val() + '" name="nn_5_' + zux + '" id="nn_5_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_6").val() + '" name="nn_6_' + zux + '" id="nn_6_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_7").val() + '" name="nn_7_' + zux + '" id="nn_7_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_8").val() + '" name="nn_8_' + zux + '" id="nn_8_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_9").val() + '" name="nn_9_' + zux + '" id="nn_9_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_10").val() + '" name="nn_10_' + zux + '" id="nn_10_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_11").val() + '" name="nn_11_' + zux + '" id="nn_11_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_12").val() + '" name="nn_12_' + zux + '" id="nn_12_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_13").val() + '" name="nn_13_' + zux + '" id="nn_13_' + zux + '"></td>' +
                        '<td><input type="text" class="form-control rounded-pill" value="' + $("#c_14").val() + '" name="nn_14_' + zux + '" id="nn_14_' + zux + '"></td>' +
                        '</tr>';

                    to = parseInt(to) + parseInt(sto);
                    /*$("#tabla_total_").find('tbody').empty();
                    $("#tabla_total_").find('tbody').append('<tr><td>'+to+'</td></tr>');*/
                    llenar();
                    $("#tabla_resultado_2").find('tbody').append(h);
                    calcular_tot(zux);

                }

                function agregar_listado(detalle) {
                    var h = "";
                    $.each(detalle, function(index, val) {

                        $("#name_N1").text(val.n1 == "" ? $("#name_N1").text() : val.n1);
                        $("#name_N2").text(val.n2 == "" ? $("#name_N2").text() : val.n2);
                        $("#name_N3").text(val.n3 == "" ? $("#name_N3").text() : val.n3);
                        $("#name_N4").text(val.n4 == "" ? $("#name_N4").text() : val.n4);
                        $("#name_N5").text(val.n5 == "" ? $("#name_N5").text() : val.n5);
                        $("#name_N6").text(val.n6 == "" ? $("#name_N6").text() : val.n6);
                        $("#name_N7").text(val.n7 == "" ? $("#name_N7").text() : val.n7);
                        $("#name_N8").text(val.n8 == "" ? $("#name_N8").text() : val.n8);
                        $("#name_N9").text(val.n9 == "" ? $("#name_N9").text() : val.n9);
                        $("#name_N10").text(val.n10 == "" ? $("#name_N10").text() : val.n10);
                        $("#name_N11").text(val.n11 == "" ? $("#name_N11").text() : val.n11);
                        $("#name_N12").text(val.n12 == "" ? $("#name_N12").text() : val.n12);
                        $("#name_N13").text(val.n13 == "" ? $("#name_N13").text() : val.n13);

                        var sto = 0;
                        sto = parseInt(sto) + (val._2 ? parseInt(val._2) : 0);
                        sto = parseInt(sto) + (val._4 ? parseInt(val._4) : 0);
                        sto = parseInt(sto) + (val._6 ? parseInt(val._6) : 0);
                        sto = parseInt(sto) + (val._8 ? parseInt(val._8) : 0);
                        sto = parseInt(sto) + (val._10 ? parseInt(val._10) : 0);
                        sto = parseInt(sto) + (val._12 ? parseInt(val._12) : 0);
                        sto = parseInt(sto) + (val._14 ? parseInt(val._14) : 0);
                        sto = parseInt(sto) + (val._16 ? parseInt(val._16) : 0);
                        sto = parseInt(sto) + (val.s ? parseInt(val.s) : 0);
                        sto = parseInt(sto) + (val.m ? parseInt(val.m) : 0);
                        sto = parseInt(sto) + (val.l ? parseInt(val.l) : 0);
                        sto = parseInt(sto) + (val.xl ? parseInt(val.xl) : 0);
                        sto = parseInt(sto) + (val.xxl ? parseInt(val.xxl) : 0);
                        zux++;
                        h = '<tr class="tr_' + val.id + '_id">' +
                            '<td rowspan="2" style="vertical-align: middle;">' + val.modelo + '<input type="hidden" value="' + val.modelo + '" name="nn_0_' + zux + '"></td>' +
                            '<td rowspan="2" style="vertical-align: middle;">' + $.trim(val.color) + '<input type="hidden" value="' + val.color + '" name="nn_1_' + zux + '" id="nn_1_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n1) + '" name="celda_1_' + zux + '" id="celda_1_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n2) + '" name="celda_2_' + zux + '" id="celda_2_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n3) + '" name="celda_3_' + zux + '" id="celda_3_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n4) + '" name="celda_4_' + zux + '" id="celda_4_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n5) + '" name="celda_5_' + zux + '" id="celda_5_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n6) + '" name="celda_6_' + zux + '" id="celda_6_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n7) + '" name="celda_7_' + zux + '" id="celda_7_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n8) + '" name="celda_8_' + zux + '" id="celda_8_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n9) + '" name="celda_9_' + zux + '" id="celda_9_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n10) + '" name="celda_10_' + zux + '" id="celda_10_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n11) + '" name="celda_11_' + zux + '" id="celda_11_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n12) + '" name="celda_12_' + zux + '" id="celda_12_' + zux + '"></td>' +
                            '<td><input type="text" style="background: skyblue; text-align: center; font-weight: bold;" class="form-control rounded-pill" value="' + $.trim(val.n13) + '" name="celda_13_' + zux + '" id="celda_13_' + zux + '"></td>' +
                            '<td rowspan="2" style="vertical-align: middle;"><input type="text" class="form-control rounded-pill" readonly name="tot_' + zux + '" id="tot_' + zux + '" value="' + sto + '"></td>' +
                            '<td rowspan="2" style="vertical-align: middle; text-align: center;">' +
                            '<span class="btn-sm btn-outline-warning btn rounded-pill d-block mt-1" onclick="modificar(' + val.id + ', ' + zux + ');"><i class="fa fa-pencil"></i></span>' +
                            '<span class="btn-sm btn-outline-danger btn rounded-pill d-block mt-1" onclick="eliminar_bd(' + val.id + ');"><i class="fa fa-trash"></i></span>' +
                            '</td></tr>' +

                            '<tr class="tr_' + val.id + '_id">' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val._2) + '" name="nn_2_' + zux + '" id="nn_2_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val._4) + '" name="nn_3_' + zux + '" id="nn_3_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val._6) + '" name="nn_4_' + zux + '" id="nn_4_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val._8) + '" name="nn_5_' + zux + '" id="nn_5_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val._10) + '" name="nn_6_' + zux + '" id="nn_6_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val._12) + '" name="nn_7_' + zux + '" id="nn_7_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val._14) + '" name="nn_8_' + zux + '" id="nn_8_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val._16) + '" name="nn_9_' + zux + '" id="nn_9_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val.s) + '" name="nn_10_' + zux + '" id="nn_10_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val.m) + '" name="nn_11_' + zux + '" id="nn_11_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val.l) + '" name="nn_12_' + zux + '" id="nn_12_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val.xl) + '" name="nn_13_' + zux + '" id="nn_13_' + zux + '"></td>' +
                            '<td><input type="text" class="form-control rounded-pill" value="' + $.trim(val.xxl) + '" name="nn_14_' + zux + '" id="nn_14_' + zux + '"></td>' +
                            '</tr>';
                        to = parseInt(to) + parseInt(sto);
                        llenar();
                        $("#tabla_resultado_2").find('tbody').append(h);
                        calcular_tot(zux);
                        $("#product_name").val(val.modelo);
                    });

                }

                function calcular_tot(z) {

                    var fd = 0;
                    for (var i = 1; i <= z; i++) {
                        console.log("#tot_" + i + " => " + $("#tot_" + i).val());
                        fd = parseInt(fd) + ($("#tot_" + i).val() ? parseInt($("#tot_" + i).val()) : 0); // parseInt($("#tot_"+i).val());
                    }
                    $("#tabla_total_").find('tbody').empty();
                    $("#tabla_total_").find('tbody').append('<tr><td>' + fd + '</td></tr>');
                }

                function lista_clientes() {
                    $.get('core/app/view/order.php', {
                        parAccion: 'lista_clientes'
                    }, function(data) {
                        var obj = JSON.parse(data);
                        if (kk == 0) {
                            $.each(obj.Records, function(index, val) {
                                $("#s_cliente").append('<option value="' + val.id + '">' + val.name + '</option>');
                            });
                        } else {
                            $.each(obj.Records, function(index, val) {
                                if (val.id == kk) {
                                    $("#s_cliente").append('<option value="' + val.id + '" selected>' + val.name + '</option>');
                                } else {
                                    //$("#cliente").append('<option value="'+val.id+'" disabled>'+val.name+'</option>');	
                                }

                            });
                        }

                    });
                }
                $(function() {
                    $(document).on('click', '.borrar', function(event) {
                        event.preventDefault();
                        var ifd = $(this).attr("id");
                        $(".tr_" + ifd + "_n").remove();
                        //zux = zux - 1;
                        calcular_tot(zux);
                    });
                });

                function eliminar_bd(id) {
                    bootbox.confirm({
                        message: "¿Seguro de Eliminar este registro?",
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
                        callback: function(result) {
                            if (result) {
                                $.post('core/app/view/order.php?parAccion=eliminar_order_detalle', {
                                    id: id
                                }, function(data) {
                                    var obj = JSON.parse(data);
                                    if (obj.Result == "OK") {
                                        bootbox.alert({
                                            message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                                '<strong>Eliminado correctamente.</strong>' +
                                                '</div>'
                                        });
                                        $(".tr_" + id + "_id").remove();
                                    } else {
                                        bootbox.alert({
                                            message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                                '<strong>Algo ha salido mal.</strong>' +
                                                '</div>'
                                        });
                                    }
                                });
                            } else {}
                        }
                    });

                }

                function pintar_cabecera() {
                    $("#la_cabecera_de_datos").append(`
						<th><input type="text" class="form-control" name="N1" id="N1" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N2" id="N2" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N3" id="N3" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N4" id="N4" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N5" id="N5" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N6" id="N6" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N7" id="N7" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N8" id="N8" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N9" id="N9" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N10" id="N10" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N11" id="N11" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N12" id="N12" style="background: skyblue;"></th>
						<th><input type="text" class="form-control" name="N13" id="N13" style="background: skyblue;"></th>
					`);
                }
                $(document).ready(function() {
                    data(<?php echo $_GET['codigo']; ?>)
                    pintar_cabecera();

                    function readURL(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $("#image_muestra").attr("src", e.target.result);
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                    $("#nueva_foto").change(function() {
                        readURL(this);
                    });
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
                        var nFilas = $("#tabla_resultado_2").find('tbody tr').length;

                        if (nFilas > 0) {
                            var formData = new FormData($(this)[0]);
                            var ruta = 'core/app/view/order.php?parAccion=edit_order&cant=' + zux;
                            $.ajax({
                                url: ruta,
                                type: "POST",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(datos) {
                                    var obj = JSON.parse(datos);
                                    if (obj.Result == 'OK') {
                                        bootbox.alert({
                                            message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                                '<strong>Orden Modificada Correctamente.</strong>' +
                                                '</div>'
                                        });
                                        window.location.reload();
                                    } else {
                                        bootbox.alert({
                                            message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                                '<strong>Algo ha salido mal.</strong>' +
                                                '</div>'
                                        });
                                    }
                                }
                            });
                        } else {
                            bootbox.alert({
                                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>No has Agregado nada al listado.</strong>' +
                                    '</div>'
                            });
                        }
                    });
                });

                function modificar(id, n) {
                    var total_anterior = $("#tot_" + n).val();
                    console.log(parseInt(($("#nn_11_" + n).val() == "" ||
                        $("#nn_11_" + n).val().length == 0) ? 0 : $("#nn_11_" + n).val()));
                    var tt = parseInt(($("#nn_2_" + n).val() == "" || $("#nn_2_" + n).val().length == 0) ? 0 : $("#nn_2_" + n).val()) +
                        parseInt(($("#nn_3_" + n).val() == "" || $("#nn_3_" + n).val().length == 0) ? 0 : $("#nn_3_" + n).val()) +
                        parseInt(($("#nn_4_" + n).val() == "" || $("#nn_4_" + n).val().length == 0) ? 0 : $("#nn_4_" + n).val()) +
                        parseInt(($("#nn_5_" + n).val() == "" || $("#nn_5_" + n).val().length == 0) ? 0 : $("#nn_5_" + n).val()) +
                        parseInt(($("#nn_6_" + n).val() == "" || $("#nn_6_" + n).val().length == 0) ? 0 : $("#nn_6_" + n).val()) +
                        parseInt(($("#nn_7_" + n).val() == "" || $("#nn_7_" + n).val().length == 0) ? 0 : $("#nn_7_" + n).val()) +
                        parseInt(($("#nn_8_" + n).val() == "" || $("#nn_8_" + n).val().length == 0) ? 0 : $("#nn_8_" + n).val()) +
                        parseInt(($("#nn_9_" + n).val() == "" || $("#nn_9_" + n).val().length == 0) ? 0 : $("#nn_9_" + n).val()) +
                        parseInt(($("#nn_10_" + n).val() == "" || $("#nn_10_" + n).val().length == 0) ? 0 : $("#nn_10_" + n).val()) +
                        parseInt(($("#nn_11_" + n).val() == "" || $("#nn_11_" + n).val().length == 0) ? 0 : $("#nn_11_" + n).val()) +
                        parseInt(($("#nn_12_" + n).val() == "" || $("#nn_12_" + n).val().length == 0) ? 0 : $("#nn_12_" + n).val()) +
                        parseInt(($("#nn_13_" + n).val() == "" || $("#nn_13_" + n).val().length == 0) ? 0 : $("#nn_13_" + n).val()) +
                        parseInt(($("#nn_14_" + n).val() == "" || $("#nn_14_" + n).val().length == 0) ? 0 : $("#nn_14_" + n).val());
                    console.log(tt);
                    $("#tot_" + n).val(tt);
                    $.post("core/app/view/order.php?parAccion=editar_order_detalle", {
                        id: id,
                        n1: $("#celda_1_" + n).val(),
                        n2: $("#celda_2_" + n).val(),
                        n3: $("#celda_3_" + n).val(),
                        n4: $("#celda_4_" + n).val(),
                        n5: $("#celda_5_" + n).val(),
                        n6: $("#celda_6_" + n).val(),
                        n7: $("#celda_7_" + n).val(),
                        n8: $("#celda_8_" + n).val(),
                        n9: $("#celda_9_" + n).val(),
                        n10: $("#celda_10_" + n).val(),
                        n11: $("#celda_11_" + n).val(),
                        n12: $("#celda_12_" + n).val(),
                        n13: $("#celda_13_" + n).val(),
                        _2: $("#nn_2_" + n).val(),
                        _4: $("#nn_3_" + n).val(),
                        _6: $("#nn_4_" + n).val(),
                        _8: $("#nn_5_" + n).val(),
                        _10: $("#nn_6_" + n).val(),
                        _12: $("#nn_7_" + n).val(),
                        _14: $("#nn_8_" + n).val(),
                        _16: $("#nn_9_" + n).val(),
                        _s: $("#nn_10_" + n).val(),
                        _m: $("#nn_11_" + n).val(),
                        _l: $("#nn_12_" + n).val(),
                        _xl: $("#nn_13_" + n).val(),
                        _xxl: $("#nn_14_" + n).val(),
                        _color: $("#nn_1_" + n).val(),
                        _total: $("#tot_" + n).val(),
                        total_anterior: total_anterior
                    }, function(response) {
                        var obj = JSON.parse(response);
                        if (obj.Result == "OK") {
                            bootbox.alert({
                                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Cantidades Modificadas Correctamente.</strong>' +
                                    '</div>'
                            });
                        } else {
                            bootbox.alert({
                                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Algo ha salido mal.</strong>' +
                                    '</div>'
                            });
                        }
                    });
                }
            </script>

</section>