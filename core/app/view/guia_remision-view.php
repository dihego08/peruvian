<style>
    .clsDatePicker {
        border-top-left-radius: 50rem !important;
        border-bottom-left-radius: 50rem !important;
    }

    .mt-2 {
        margin-top: 1rem !important;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h3 style="margin-bottom: 3rem; margin-top: 0px !important;">Guía de Remisión </h3>

            <div class="row">
                <div class="form-group col-md-3" style="border-right: solid 1px #eee;">
                    <div class="col-md-12">
                        <span id="codigo_venta" style="font-weight: bold;">Núm. Doc.: &nbsp;</span>
                    </div>
                </div>
                <div class="form-group col-md-3" style="border-right: solid 1px #eee;">
                    <div class="col-md-12">
                        <span id="codigo_venta" style="font-weight: bold;">Fecha Emisión: &nbsp;</span>
                    </div>
                </div>
                <div class="form-group col-md-3" style="border-right: solid 1px #eee;">
                    <div class="col-md-12">
                        <span id="codigo_venta" style="font-weight: bold;">Fecha Inicio Traslado: &nbsp;</span>
                    </div>
                </div>
                <div class="form-group col-md-3" style="border-right: solid 1px #eee;">
                    <div class="col-md-12">
                        <span id="codigo_venta" style="font-weight: bold;">Motivo Traslado: &nbsp;</span>
                    </div>
                </div>

                <div class="form-group col-md-3" style="border-right: solid 1px #eee;">
                    <div class="col-md-12" style="padding-left: 0; text-align: left;">
                        <input type="text" readonly name="txt_cod_venta" id="txt_cod_venta"
                            class="form-control rounded-pill">
                    </div>
                </div>
                <div class="form-group col-md-3" style="border-right: solid 1px #eee;">
                    <div class="col-md-12" style="padding-left: 0; text-align: left;">
                        <input type="text" name="fecha_emision" id="fecha_emision" class="form-control rounded-pill"
                            placeholder="Año-Mes-día" value="<?php echo date(" Y-m-d", strtotime(date("Y-m-d"))) ?>">
                    </div>
                </div>
                <div class="form-group col-md-3" style="border-right: solid 1px #eee;">
                    <div class="col-md-12" style="padding-left: 0; text-align: left;">
                        <div class="input-group">
                            <input type="text" name="fecha_traslado" id="fecha_traslado" readonly="readonly"
                                class="form-control clsDatePicker" value="<?php echo date(" Y-m-d",
                                strtotime(date("Y-m-d"))) ?>">
                            <span class="input-group-addon">
                                <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3" style="border-right: solid 1px #eee;">
                    <div class="col-md-12" style="padding-left: 0; text-align: left;">
                        <select name="" id="motivo_traslado" class="form-control rounded-pill">
                            <option value="01" selected>VTA|Venta</option>
                            <option value="02">CMP|Compra</option>
                            <option value="03">VET|Venta con entrega a terceros</option>
                            <option value="04">TEE|Traslado entre establecimientos de la misma empresa</option>
                            <option value="05">CON|Consignación</option>
                            <option value="06">DEV|Devolución</option>
                            <option value="07">RBT|Recojo de bienes transformados</option>
                            <option value="08">IMP|Importación</option>
                            <option value="09">EXP|Exportación</option>
                            <option value="14">VSC|Venta sujeta a confirmación del comprador</option>
                            <option value="17">TPT|Traslado de bienes para transformación</option>
                            <option value="18">EMI|Traslado emisor itinerante de comprobantes de pago</option>
                            <option value="19">TME|Traslado de mercancía extranjera</option>
                            <option value="13">OTR|Otros (no especificados en los anteriores)</option>
                        </select>
                        <input type="text" class="form-control rounded-pill mt-2" placeholder="Ingrese Motivo"
                            id="descripcion_motivo" style="display: none;">
                    </div>
                </div>
                <hr class="w-100">

                <div class="col-md-12 form-group">
                    <div class="box box-primary" style="overflow: hidden; padding: 5px;">
                        <span style="font-weight: bold;">Origen</span>
                        <hr class="w-100" style="margin: 0.5rem !important;">
                        <div class="col-md-12">
                            <label for="origen">Dirección:</label>
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control rounded-pill"
                                id="origen">CAL.BELEN MZA. B LOTE. 8 JERUSALEN - MARIANO - MELGAR - AREQUIPA - AREQUIPA</textarea>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="origen">Ubigeo:</label>
                        </div>
                        <div class="col-md-4">
                            <select name="" id="departamento_origen" class="form-control rounded-pill">
                                <option value="0">--DEPARTAMENTO--</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="" id="provincia_origen" class="form-control rounded-pill">
                                <option value="0">--PROVINCIA--</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="" id="distrito_origen" class="form-control rounded-pill">
                                <option value="0">--DISTRITO--</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 form-group">
                    <div class="box box-primary" style="overflow: hidden; padding: 5px;">
                        <span style="font-weight: bold;">Destino</span>
                        <hr class="w-100" style="margin: 0.5rem !important;">
                        <div class="form-group col-md-6">
                            <label>RUC Destinatario</label>
                            <div class="input-group mb-3" style="display: flex;">
                                <input type="text" class="form-control rounded-pill-left" placeholder="RUC ..."
                                    id="ruc_destinatario" aria-label="Recipient's username"
                                    aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-dark" type="button" onclick="buscar_ruc();"><i
                                            class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="">Resultado Búsqueda</label>
                            <div id="resultado_ruc">

                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="col-md-12">
                                <label for="destino">Dirección:</label>
                            </div>
                            <div class="col-md-12">
                                <textarea class="form-control rounded-pill" id="destino"></textarea>
                                <input type="hidden" name="" id="ubigeo">
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label>Ubigeo:</label>
                        </div>
                        <div class="col-md-4">
                            <select name="" id="departamento_destino" class="form-control rounded-pill">
                                <option value="0">--DEPARTAMENTO--</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="" id="provincia_destino" class="form-control rounded-pill">
                                <option value="0">--PROVINCIA--</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="" id="distrito_destino" class="form-control rounded-pill">
                                <option value="0">--DISTRITO--</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 form-group">
                    <div class="box box-primary" style="overflow: hidden; padding: 5px;">
                        <div class="col-md-12 mb-1">
                            <label for="modalidad_trasnporte">Modalidad de Traslado</label>
                            <select name="" id="modalidad_trasnporte" class="form-control rounded-pill">
                                <option value="01">Público</option>
                                <option value="02">Privado</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label>Datos Transportista</label>

                            <div class="input-group mb-3" style="display: flex;">
                                <input type="text" class="form-control rounded-pill-left"
                                    placeholder="Datos Transportista" id="ruc_transportista"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-dark" type="button"
                                        onclick="buscar_transportista();">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="resultado_transportista">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label>Datos Conductor</label>
                            <div class="input-group mb-3" style="display: flex;">
                                <input type="text" class="form-control rounded-pill-left" placeholder="Datos Conductor"
                                    id="ruc_conductor" aria-label="Recipient's username"
                                    aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-dark" type="button" onclick="buscar_conductor();">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="resultado_conductor">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label style="font-weight: bold;">Placa Vehículo: &nbsp;</label>
                            <input type="text" name="placa" id="placa" class="form-control rounded-pill"
                                placeholder="Ex. F5Z200">

                            <label style="font-weight: bold;">N° Licencia: &nbsp;</label>
                            <input type="text" name="licencia" id="licencia" class="form-control rounded-pill"
                                placeholder="Ex. 0012012">
                        </div>

                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="box box-primary" style="overflow: hidden; padding: 5px;">
                        <div class="col-md-12 form-group">
                            <div class="col-md-12">
                                <label for="comentario">Comentario:</label>
                            </div>
                            <div class="col-md-12">
                                <textarea name="comentario" id="comentario" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p>
                <b>Buscar producto por nombre o por codigo:</b>
            </p>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" id="product_name" name="product_name" class="form-control rounded-pill"
                        placeholder="Nombre del Producto">
                </div>
                <div class="col-md-5">
                    <input type="text" id="product_code" name="product_code" class="form-control rounded-pill"
                        placeholder="Modelo">
                </div>
                <div class="col-md-1 text-center">
                    <button type="submit" class="w-100 btn btn-success rounded-pill" onclick="buscar_productos();"><i
                            class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>

            <hr class="w-100">

            <div class="row">
                <div id="resultado_busqueda_order">
                    <div class="col-md-9">
                        <h2>Cantidad por tallas</h2>
                        <div class="box box-success">
                            <table class="table table-bordered" id="tabla-tallas">
                                <thead>
                                    <th><input placeholder="2" style="padding:3px 6px;" type="text" class="form-control"
                                            name="TN1" id="TN1"></th>
                                    <th><input placeholder="4" style="padding:3px 6px;" type="text" class="form-control"
                                            name="TN2" id="TN2"></th>
                                    <th><input placeholder="6" style="padding:3px 6px;" type="text" class="form-control"
                                            name="TN3" id="TN3"></th>
                                    <th><input placeholder="8" style="padding:3px 6px;" type="text" class="form-control"
                                            name="TN4" id="TN4"></th>
                                    <th><input placeholder="10" style="padding:3px 6px;" type="text"
                                            class="form-control" name="TN5" id="TN5"></th>
                                    <th><input placeholder="12" style="padding:3px 6px;" type="text"
                                            class="form-control" name="TN6" id="TN6"></th>
                                    <th><input placeholder="14" style="padding:3px 6px;" type="text"
                                            class="form-control" name="TN7" id="TN7"></th>
                                    <th><input placeholder="XS" style="padding:3px 6px;" type="text"
                                            class="form-control" name="TN8" id="TN8"></th>
                                    <th><input placeholder="S" style="padding:3px 6px;" type="text" class="form-control"
                                            name="TN9" id="TN9"></th>
                                    <th><input placeholder="M" style="padding:3px 6px;" type="text" class="form-control"
                                            name="TN10" id="TN10"></th>
                                    <th><input placeholder="L" style="padding:3px 6px;" type="text" class="form-control"
                                            name="TN11" id="TN11"></th>
                                    <th><input placeholder="XL" style="padding:3px 6px;" type="text"
                                            class="form-control" name="TN12" id="TN12"></th>
                                    <th><input placeholder="XXL" style="padding:3px 6px;" type="text"
                                            class="form-control" name="TN13" id="TN13"></th>
                                </thead>
                                <tbody>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N1"
                                            id="N1"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N2"
                                            id="N2"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N3"
                                            id="N3"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N4"
                                            id="N4"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N5"
                                            id="N5"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N6"
                                            id="N6"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N7"
                                            id="N7"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N8"
                                            id="N8"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N9"
                                            id="N9"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N10"
                                            id="N10"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N11"
                                            id="N11"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N12"
                                            id="N12"></th>
                                    <th><input style="padding:3px 6px;" type="text" class="form-control" name="N13"
                                            id="N13"></th>
                                </tbody>
                            </table>
                        </div>
                        <h2>Resultado de Búsqueda</h2>
                        <div class="box box-primary">
                            <table class="table table-bordered table-hover" id="detalle_busqueda_order">
                                <thead>
                                    <tr>
                                        <th>Unidad</th>
                                        <th style="width: 10%;">Pedido</th>
                                        <th style="width: 10%;">Código</th>
                                        <th style="width: 40%;">Descripción</th>
                                        <th>KG. Neto</th>
                                        <th>KG. Bruto</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div id="div_lista_ventas" hidden>
                            <h2>Contenido</h2>
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover" id="tabla_lista_venta" hidden>
                                    <thead>
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Pedido</th>
                                            <th>Unidad</th>
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>KG. Neto</th>
                                            <th>KG. Bruto</th>
                                            <th>Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="box box-primary">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row">
                                                Total Peso Bruto:
                                            </th>
                                            <td id="">
                                                <input type="text" name="" id="total_peso_bruto"
                                                    class="form-control rounded-pill">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                Total Peso Neto:
                                            </th>
                                            <td id="">
                                                <input type="text" name="" id="total_peso_neto"
                                                    class="form-control rounded-pill">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button class="btn btn-danger rounded-pill" onclick="cancelar_venta();">Cancelar</button>
                            <button class="btn btn-success rounded-pill" id="guardar_venta">Guardar Guía</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var _subtotal = 0;
    var ids_venta = 0;
    var total_brutos = 0;
    var total_netos = 0;
    var obj_destinatario = null;
    var obj_transportista = null;
    var obj_conductor = null;
    var contador_borrar = 0;

    function llenar_departamentos_destino() {
        $.post("core/app/view/venta.php?parAccion=llenar_departamentos", function (response) {
            var obj = JSON.parse(response);
            $.each(obj, function (index, val) {
                $("#departamento_destino").append(`<option value="${val.codigo}">${val.departamento}</option>`);
            });
        });
    }

    function llenar_provincias_destino(departamento) {
        $.post("core/app/view/venta.php?parAccion=llenar_provincias", {
            departamento: departamento
        }, function (response) {
            var obj = JSON.parse(response);
            $("#provincia_destino").empty();;
            $("#provincia_destino").append(`<option value="0">--PROVINCIA--</option>`);
            $.each(obj, function (index, val) {
                $("#provincia_destino").append(`<option value="${val.codigo}">${val.provincia}</option>`);
            });
        });
    }

    function llenar_distritos_destino(provincia) {
        $.post("core/app/view/venta.php?parAccion=llenar_distritos", {
            provincia: provincia
        }, function (response) {
            var obj = JSON.parse(response);
            $("#distrito_destino").empty();
            $("#distrito_destino").append(`<option value="0">--DISTRITO--</option>`);
            $.each(obj, function (index, val) {
                $("#distrito_destino").append(`<option value="${val.codigo}">${val.distrito}</option>`);
            });
        });
    }

    function llenar_departamentos() {
        $.post("core/app/view/venta.php?parAccion=llenar_departamentos", function (response) {
            var obj = JSON.parse(response);
            $.each(obj, function (index, val) {
                $("#departamento_origen").append(`<option value="${val.codigo}">${val.departamento}</option>`);
            });
        });
    }

    function llenar_provincias(departamento) {
        $.post("core/app/view/venta.php?parAccion=llenar_provincias", {
            departamento: departamento
        }, function (response) {
            var obj = JSON.parse(response);
            $("#provincia_origen").empty();;
            $("#provincia_origen").append(`<option value="0">--PROVINCIA--</option>`);
            $.each(obj, function (index, val) {
                $("#provincia_origen").append(`<option value="${val.codigo}">${val.provincia}</option>`);
            });
        });
    }

    function llenar_distritos(provincia) {
        $.post("core/app/view/venta.php?parAccion=llenar_distritos", {
            provincia: provincia
        }, function (response) {
            var obj = JSON.parse(response);
            $("#distrito_origen").empty();
            $("#distrito_origen").append(`<option value="0">--DISTRITO--</option>`);
            $.each(obj, function (index, val) {
                $("#distrito_origen").append(`<option value="${val.codigo}">${val.distrito}</option>`);
            });
        });
    }

    function get_aux() {
        $.post("core/app/view/venta.php?parAccion=get_aux_guia", function (data) {
            var obj = JSON.parse(data);
            $("#txt_cod_venta").val("T001-" + (parseInt(obj.Records[0].id) + parseInt(1)))
        });
    }
    let unidades_opciones = '';
    function get_unidades() {
        // cbo_unidad
        $.post("core/app/view/venta.php?parAccion=get_unidades", function (data) {
            var obj = JSON.parse(data);
            $.each(obj, function (index, val) {
                if (val.codigo == 'NIU') {
                    unidades_opciones += `<option value="${val.codigo}" selected>${val.unidad}</option>`;
                } else {
                    unidades_opciones += `<option value="${val.codigo}">${val.unidad}</option>`;
                }

            });
        });
    }
    $(document).ready(function () {
        get_unidades();
        get_aux();
        $('#fecha_traslado').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            altField: "#fecha_nacimiento_hidden",
            altFormat: "yy-mm-dd"
        });

        llenar_departamentos();
        llenar_departamentos_destino();
        $("#departamento_origen").on("change", function () {
            if ($(this).val() > 0) {
                llenar_provincias($(this).val());
            } else {
                $("#provincia_origen").empty();
                $("#provincia_origen").append(`<option value="0">--PROVINCIA--</option>`);
                $("#distrito_origen").empty();
                $("#distrito_origen").append(`<option value="0">--DISTRITO--</option>`);
            }
        });
        $("#provincia_origen").on("change", function () {
            if ($(this).val() > 0) {
                llenar_distritos($(this).val());
            } else {
                $("#distrito_origen").empty();
                $("#distrito_origen").append(`<option value="0">--DISTRITO--</option>`);
            }
        });

        $("#motivo_traslado").on("change", function () {
            if ($(this).val() == 13) {
                $("#descripcion_motivo").show();
            } else {
                $("#descripcion_motivo").hide();
            }
        });

        $("#departamento_destino").on("change", function () {
            if ($(this).val() > 0) {
                llenar_provincias_destino($(this).val());
            } else {
                $("#provincia_destino").empty();
                $("#provincia_destino").append(`<option value="0">--PROVINCIA--</option>`);
                $("#distrito_destino").empty();
                $("#distrito_destino").append(`<option value="0">--DISTRITO--</option>`);
            }
        });
        $("#provincia_destino").on("change", function () {
            if ($(this).val() > 0) {
                llenar_distritos_destino($(this).val());
            } else {
                $("#distrito_destino").empty();
                $("#distrito_destino").append(`<option value="0">--DISTRITO--</option>`);
            }
        });
        $("#guardar_venta").click(function () {
            if ($("#lista_clientes").val() == 0 && $("#nuevo_ruc").val() == "") {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Seleccionar un cliente.</strong>' +
                        '</div>'
                });
            } else {
                if ($("#txt_cod_venta").val() == "") {
                    bootbox.alert({
                        message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>Ingresar Codigo de Venta.</strong>' +
                            '</div>'
                    });
                } else {
                    var ids = 0;
                    var precios = 0;
                    var unidades = 0;
                    var unidades_r = 0;
                    var cantidades = 0;
                    var pedidos = 0;
                    var descripciones = 0;
                    var p_b_s = 0;
                    var n_pedidos = 0;
                    var ttotal_brutos = 0;
                    var ttotal_netos = 0;

                    $(".id_producto").parent("tr").find(".id_producto").each(function () {
                        ids = ids + ',' + $(this).html();
                    });
                    $(".id_producto").parent("tr").find(".tot_brutos").each(function () {
                        ttotal_brutos = ttotal_brutos + ',' + $(this).html();
                    });
                    $(".id_producto").parent("tr").find(".tot_netos").each(function () {
                        ttotal_netos = ttotal_netos + ',' + $(this).html();
                    });

                    $(".id_producto").parent("tr").find(".unidades_producto").each(function () {
                        unidades = unidades + ',' + $(this).html();
                    });

                    $(".id_producto").parent("tr").find(".unidades_producto_r").each(function () {
                        unidades_r = unidades_r + ',' + $(this).html();
                    });

                    $(".id_producto").parent("tr").find(".cantidad_producto").each(function () {
                        cantidades = cantidades + ',' + $(this).html();
                    });
                    $(".id_producto").parent("tr").find(".pedido_producto").each(function () {
                        pedidos = pedidos + ',' + $(this).html();
                    });
                    $(".id_producto").parent("tr").find(".descripcion_producto").each(function () {
                        descripciones = descripciones + '||' + $(this).html();
                    });
                    console.log(ids);
                    console.log(ttotal_brutos);
                    console.log(ttotal_netos);
                    console.log(unidades);
                    console.log(unidades_r);
                    console.log(cantidades);
                    console.log(descripciones);
                    //return;
                    $.post('core/app/view/venta.php?parAccion=insertar_guia', {
                        ids: ids,
                        ttotal_brutos: ttotal_brutos,
                        ttotal_netos: ttotal_netos,
                        unidades: unidades,
                        unidades_r: unidades_r,
                        cantidades: cantidades,
                        pedidos: pedidos,
                        descripciones: descripciones,
                        num_guia: $("#txt_cod_venta").val(),
                        fecha_emision: $("#fecha_emision").val(),
                        fecha_traslado: $("#fecha_traslado").val(),
                        total_netos: $("#total_peso_neto").val(),
                        total_brutos: $("#total_peso_bruto").val(),
                        ruc_destinatario: $("#ruc_destinatario").val(),
                        destino: $("#destino").val(),
                        placa: $("#placa").val(),
                        ruc_transportista: $("#ruc_transportista").val(),
                        ruc_conductor: $("#ruc_conductor").val(),
                        origen: $("#origen").val(),
                        obj_destinatario: obj_destinatario,
                        obj_transportista: obj_transportista,
                        obj_conductor: obj_conductor,
                        ubigeo: $("#distrito_origen").val(),
                        ubigeo_destino: $("#distrito_destino").val(),
                        modalidad_trasnporte: $("#modalidad_trasnporte").val(),
                        motivo_traslado: $("#motivo_traslado").val(),
                        licencia: $("#licencia").val(),
                        descripcion_motivo: $("#descripcion_motivo").val()
                    }, function (data_) {
                        var obj_ = JSON.parse(data_);
                        if (obj_.Result == 'OK') {
                            bootbox.alert({
                                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Guía registrada correctamente.</strong>' +
                                    '</div>'
                            });
                            window.location.href = "./?view=guias";
                        } else {
                            bootbox.alert({
                                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>No se pudo guardar esta venta.</strong>' +
                                    '</div>'
                            });
                        }
                    });
                }
            }
        });
    });

    function buscar_productos() {
        $("#detalle_busqueda_order").find('tbody').empty();
        var total = 0;
        var subtotal = 0;

        var igv = 0;
        code = $("#product_code").val();
        name = $("#product_name").val();

        if (name != "") {
            if ($("#category_id").val() == 0) {
                $.get('core/app/view/cotizacion.php', {
                    parAccion: 'busqueda_productos',
                    producto: name
                }, function (data) {
                    var obj = JSON.parse(data);
                    if (obj.Records.length > 0) {
                        $("#resultado_busqueda_order").removeAttr('hidden');
                        $.each(obj.Records, function (index, val) {
                            $("#detalle_busqueda_order").find('tbody').append('<tr id="td_' + val.id + '">' +
                                `<td id="s_p_id_` + val.id + `">
                                    <select class="form-control rounded-pill" id="s_p_` + val.id + `">
                                        <option value="0">Producto</option>
                                        <option value="1">Servicio</option>
                                    </select>
                                </td>` +
                                '<td><input type="text" class="form-control rounded-pill pedido_producto" id="pedido_' + val.id + '" value=""/></td>' +
                                '<td id="id_' + val.id + '">' + val.id + '</td>' +
                                '<td><input type="text" class="form-control rounded-pill" name="canti_' + val.id + '" id="canti_' + val.id + '" value="1"><span hidden class="cantidad_producto">' + val.id + '</span></td>' +
                                `<td>
                                    <input type="text" name="txt_pedido_${val.id}" id="txt_pedido_${val.id}" class="form-control rounded-pill">
                                </td>` +
                                '<td>' + val.name + '</td>' +
                                '<td><select class="form-control cbo_unidad" id="unit_' + val.id + '">' + unidades_opciones + '</select></td>' +
                                '<td><b>S/ <span id="precio_' + val.id + '" class="precio_producto">' + parseFloat(val.price_in).toFixed(4) + '</span></b></td>' +
                                '<td></td>' +
                                '<td><button class="btn btn-outline-success btn-sm rounded-pill" onclick="agregar_producto_lista(' + val.id + ')"><i class="glyphicon glyphicon-plus"></i></button></td>' +
                                '</tr>');
                        });
                    } else {
                        bootbox.alert({
                            message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                '<strong>Ningún producto encontrado.</strong>' +
                                '</div>'
                        });
                    }
                });
            } else {
                $.get('core/app/view/cotizacion.php', {
                    parAccion: 'busqueda_productos_2',
                    producto: name
                }, function (data) {
                    $("#resultado_busqueda_order").removeAttr('hidden');
                    var obj = JSON.parse(data);
                    if (obj.Records.length > 0) {
                        var html = "";
                        $.each(obj.Records, function (index, val) {
                            html = html + '<tr id="td_' + val.id + '">' +
                                '<td><input type="text" class="form-control rounded-pill unidades_producto" id="unit_' + val.id + '" value="' + val.unit + '"/></td>' +
                                '<td><input type="text" class="form-control rounded-pill pedido_producto" id="pedido_' + val.id + '" value=""/></td>' +
                                '<td id="id_' + val.id + '">' + val.code + '</td>' +
                                '<td><textarea class="form-control rounded-pill" id="nombre_pro_id_' + val.id + '">' + val.name + '</textarea></td>' +

                                '<td><input type="text" class="form-control rounded-pill" id="peso_neto_' + val.id + '" value="' + 0 + '" /></td>' +
                                '<td><input type="text" class="form-control rounded-pill" id="peso_' + val.id + '" value="' + 0 + '" /></td>' +

                                '<td><button class="btn btn-outline-success btn-sm rounded-pill" onclick="agregar_producto_lista(' + val.id + ')"><i class="glyphicon glyphicon-plus"></i></button></td>' +
                                '</tr>';
                        });
                        $("#detalle_busqueda_order").find('tbody').append(html);
                    } else {
                        bootbox.alert({
                            message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                '<strong>Ningún producto encontrado.</strong>' +
                                '</div>'
                        });
                    }
                });
            }
        } else if (code != "") {
            $.get('core/app/view/cotizacion.php', {
                parAccion: 'busqueda_productos_barcode',
                barcode: code
            }, function (data) {
                $("#resultado_busqueda_order").removeAttr('hidden');
                var obj = JSON.parse(data);
                if (obj.Records.length > 0) {
                    var html = "";
                    $.each(obj.Records, function (index, val) {
                        html = html + '<tr id="td_' + val.id + '">' +
                            '<td><select class="form-control cbo_unidad" id="unit_' + val.id + '">' + unidades_opciones + '</select></td>' +
                            '<td><input type="text" class="form-control rounded-pill pedido_producto" id="pedido_' + val.id + '" value=""/></td>' +
                            '<td id="id_' + val.id + '">' + val.code + '</td>' +
                            '<td><textarea class="form-control rounded-pill" id="nombre_pro_id_' + val.id + '">' + val.name + '</textarea></td>' +

                            '<td><input type="text" class="form-control rounded-pill" id="peso_neto_' + val.id + '" value="' + 0 + '" /></td>' +
                            '<td><input type="text" class="form-control rounded-pill" id="peso_' + val.id + '" value="' + 0 + '" /></td>' +

                            '<td><button class="btn btn-outline-success rounded-pill btn-sm" onclick="agregar_producto_lista(' + val.id + ')"><i class="glyphicon glyphicon-plus"></i></button></td>' +
                            '</tr>';
                    });
                    $("#detalle_busqueda_order").find('tbody').append(html);
                } else {
                    bootbox.alert({
                        message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>Ningún producto encontrado.</strong>' +
                            '</div>'
                    });
                }
            });
        } else {
            bootbox.alert({
                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                    '<strong>Elegir algún criterio de búsqueda.</strong>' +
                    '</div>'
            });
        }

        $("#product_code").val('');
        $("#product_name").val('');
        $("#order_pedido").val('');

        $("#subtotal_detalle_td").text(subtotal.toFixed(2));
        $("#igv_detalle_td").text(igv.toFixed(2));
        $("#total_detalle_td").text((parseFloat(subtotal) + parseFloat(igv)).toFixed(2));
    };

    function agregar_producto_lista(id) {

        var descripcion = "";
        if ($("#s_p_" + id).val() == 1) {
            descripcion = "SERVICIO DE CONFECCION ";
        }

        let cantidad_tallas = 0;
        cantidad_tallas =
            parseFloat($("#N1").val() || 0) +
            parseFloat($("#N2").val() || 0) +
            parseFloat($("#N3").val() || 0) +
            parseFloat($("#N4").val() || 0) +
            parseFloat($("#N5").val() || 0) +
            parseFloat($("#N6").val() || 0) +
            parseFloat($("#N7").val() || 0) +
            parseFloat($("#N8").val() || 0) +
            parseFloat($("#N9").val() || 0) +
            parseFloat($("#N10").val() || 0) +
            parseFloat($("#N11").val() || 0) +
            parseFloat($("#N12").val() || 0) +
            parseFloat($("#N13").val() || 0);
        let texto_tallas = '<table border=1>';
        let texto_contenido = '<tr>';
        let texto_cabecera = '<tr>';
        let aux_c = 1;
        $("#tabla-tallas tbody tr").each(function () {
            let values = $(this).find("input").map(function () {
                if ($(this).val() == "" || $(this).val() == 0) { } else {
                    texto_contenido += "<td style='padding:5px;'>" + $(this).val() + "</td>";
                    texto_cabecera += "<td style='padding:5px;'>" + $("#TN" + aux_c).val() + "</td>";
                }
                aux_c += 1;
            }).get();
        });
        texto_cabecera += '</tr>';
        texto_contenido += '</tr>';
        texto_tallas += texto_cabecera + texto_contenido + '</table>';
        console.log(texto_tallas);
        let val_tem_neto = 0;
        let val_tem_bruto = 0;
        if ($("#peso_" + id).val() == 0 || $("#peso_" + id).val() == "") {
            val_tem_neto = parseFloat(cantidad_tallas * parseFloat($("#peso_neto_" + id).val()));
        } else {
            val_tem_bruto = parseFloat($("#peso_" + id).val()).toFixed(2);
        }

        total_brutos = parseFloat(total_brutos) + parseFloat($("#peso_" + id).val());
        total_netos = parseFloat(total_netos) + parseFloat(cantidad_tallas * parseFloat($("#peso_neto_" + id).val()));
        contador_borrar += 1;
        $.get('core/app/view/order.php', {
            parAccion: 'detalle_producto',
            codigo: id
        }, function (data) {
            var obj = JSON.parse(data);
            $("#tabla_lista_venta").removeAttr('hidden');
            $("#div_lista_ventas").removeAttr('hidden');
            $("#tabla_lista_venta > tbody").append('<tr id="ocul_' + id + '">' +
                '<td class="id_producto" id="pro_' + id + '" hidden>' + obj.Records.id + '</td>' +
                '<td class="cantidad_producto">' + cantidad_tallas + '</td>' +
                '<td class="pedido_producto">' + $("#pedido_" + id).val() + '</td>' +
                '<td class="unidades_producto_r">' + $("#unit_" + id).val() + '</td>' +
                '<td class="unidades_producto">' + obj.Records.code + '</td>' +
                '<td class="descripcion_producto">' + descripcion + $("#nombre_pro_id_" + id).val() + '<br> TALLAS ' + texto_tallas + '</td>' +
                '<td scope="row"><span id="spn_total_netos_' + id + '_' + contador_borrar + '" class="tot_netos">' + val_tem_neto.toFixed(2) + '</span></td>' +
                '<td scope="row"><span id="spn_total_brutos_' + id + '_' + contador_borrar + '" class="tot_brutos">' + parseFloat(val_tem_bruto).toFixed(2) + '</span></td>' +
                '<td><a href="javascript:eliminar_producto_venta(' + id + ', ' + contador_borrar + ');" class="btn btn-xs btn-danger borrar" id="btn_borrar_' + id + '_' + contador_borrar + '"><i class="fa fa-trash"></i></a></td>' +
                '</tr>');
        });

        $("#total_peso_neto").val(total_netos.toFixed(2));
        $("#total_peso_bruto").val(total_brutos.toFixed(2));

        $("#N1").val('');
        $("#N2").val('');
        $("#N3").val('');
        $("#N4").val('');
        $("#N5").val('');
        $("#N6").val('');
        $("#N7").val('');
        $("#N8").val('');
        $("#N9").val('');
        $("#N10").val('');
        $("#N11").val('');
        $("#N12").val('');
        $("#N13").val('');

        $("#TN1").val('');
        $("#TN2").val('');
        $("#TN3").val('');
        $("#TN4").val('');
        $("#TN5").val('');
        $("#TN6").val('');
        $("#TN7").val('');
        $("#TN8").val('');
        $("#TN9").val('');
        $("#TN10").val('');
        $("#TN11").val('');
        $("#TN12").val('');
        $("#TN13").val('');
    }

    function eliminar_producto_venta(id, c) {
        total_brutos = total_brutos - $("#spn_total_brutos_" + id + '_' + c).text();
        total_netos = total_netos - $("#spn_total_netos_" + id + '_' + c).text();

        $("#total_peso_neto").val(total_netos.toFixed(2));
        $("#total_peso_bruto").val(total_brutos.toFixed(2));

        $("#btn_borrar_" + id + "_" + c).closest('tr').remove();
    }

    function tipos_pagos() {
        $.get('core/app/view/venta.php', {
            parAccion: 'tipos_pago'
        }, function (data) {
            $("#tipos_pago").empty();
            var obj = JSON.parse(data);
            $.each(obj.Records, function (index, val) {
                if (val.id == 4) {
                    $("#tipos_pago").append('<option value="' + val.id + '" selected>' + val.name + '</option>');
                } else {
                    $("#tipos_pago").append('<option value="' + val.id + '">' + val.name + '</option>');
                }
            });
        });
    }

    function tipos_entregas() {
        $.get('core/app/view/venta.php', {
            parAccion: 'tipos_entrega'
        }, function (data) {
            $("#tipos_entrega").empty();
            var obj = JSON.parse(data);
            $.each(obj.Records, function (index, val) {
                $("#tipos_entrega").append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        });
    }

    function tipos_documentos() {
        $.get('core/app/view/venta.php', {
            parAccion: 'tipos_documento'
        }, function (data) {
            $("#tipos_documento").empty();
            var obj = JSON.parse(data);
            $.each(obj.Records, function (index, val) {
                $("#tipos_documento").append('<option value="' + val.id + '">' + val.tipo_documento + '</option>');
            });

            $("#tipos_documento").val("2");
            $("#tipos_documento").change();
        });
    }

    function formas_pagos() {
        $.get('core/app/view/venta.php', {
            parAccion: 'forma_pago'
        }, function (data) {
            $("#forma_pago").empty();
            var obj = JSON.parse(data);
            $.each(obj.Records, function (index, val) {
                if (val.id == 2) {
                    $("#forma_pago").append('<option value="' + val.id + '" selected>' + val.name + '</option>');
                } else {
                    $("#forma_pago").append('<option value="' + val.id + '">' + val.name + '</option>');
                }

            });
        });
    }

    function lista_clientes() {
        $.get('core/app/view/order.php', {
            parAccion: 'lista_clientes'
        }, function (data) {
            $("#lista_clientes").empty();
            $("#lista_clientes").append('<option value="0">SELECCIONE ...</option>');
            var obj = JSON.parse(data);
            $.each(obj.Records, function (index, val) {
                $("#lista_clientes").append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        });
    }

    function buscar_ruc() {
        $("#resultado_ruc").empty();
        $("#resultado_ruc").append('<span class="badge"  style="background-color: #ffc107; color: #212529;">Buscando</span>');

        $.get("https://dbusinessaqp.com/api_ruc/api.php", {
            ruc: $("#ruc_destinatario").val()
        }, function (response) {
            //var obj = JSON.parse(response);
            var obj = response;
            $("#resultado_ruc").empty();
            if (obj.error === undefined) {
                $("#resultado_ruc").append('<span class="badge" style="background-color: #28a745; color: #ffffff;">' + $("#ruc_destinatario").val() + ' - ' + obj.nombre + '</span>');
                obj_destinatario = obj;
                $("#destino").val(obj.direccion + " - " + obj.distrito + " - " + obj.provincia + " - " + obj.departamento);
                $("#ubigeo").val(obj.ubigeo);
            } else {
                $("#resultado_ruc").append('<span class="badge" style="background-color: #dc3545; color: #ffffff;">' + $("#ruc_destinatario").val() + ' - ' + obj.error + '</span>');
            }

        });
    }

    function buscar_transportista() {
        $("#resultado_transportista").empty();
        $("#resultado_transportista").append('<span class="badge"  style="background-color: #ffc107; color: #212529;">Buscando</span>');
        if ($("#ruc_transportista").val().length > 8) {
            $.get("https://dbusinessaqp.com/api_ruc/api.php", {
                ruc: $("#ruc_transportista").val()
            }, function (response) {
                var obj = response;
                $("#resultado_transportista").empty();
                if (obj.error === undefined) {
                    $("#resultado_transportista").append('<span class="badge" style="background-color: #007bff; color: #ffffff;">' + $("#ruc_transportista").val() + ' - ' + obj.nombre + '</span>');
                    obj_transportista = obj;
                } else {
                    $("#resultado_transportista").append('<span class="badge" style="background-color: #dc3545; color: #ffffff;">' + $("#ruc_transportista").val() + ' - ' + obj.error + '</span>');
                }

            });
        } else {
            $.get("https://dbusinessaqp.com/api_ruc/api.php", {
                dni: $("#ruc_transportista").val()
            }, function (response) {
                var obj = response;
                $("#resultado_transportista").empty();
                if (obj.error === undefined) {
                    $("#resultado_transportista").append('<span class="badge" style="background-color: #007bff; color: #ffffff;">' + $("#ruc_transportista").val() + ' - ' + obj.nombre + '</span>');
                    obj_transportista = obj;
                } else {
                    $("#resultado_transportista").append('<span class="badge" style="background-color: #dc3545; color: #ffffff;">' + $("#ruc_transportista").val() + ' - ' + obj.error + '</span>');
                }

            });
        }
    }

    function buscar_conductor() {
        $("#resultado_conductor").empty();
        $("#resultado_conductor").append('<span class="badge"  style="background-color: #ffc107; color: #212529;">Buscando</span>');
        if ($("#ruc_conductor").val().length > 8) {
            $.get("https://dbusinessaqp.com/api_ruc/api.php", {
                ruc: $("#ruc_conductor").val()
            }, function (response) {
                var obj = response;
                $("#resultado_conductor").empty();
                if (obj.error === undefined) {
                    $("#resultado_conductor").append('<span class="badge" style="background-color: #17a2b8; color: #ffffff;">' + $("#ruc_conductor").val() + ' - ' + obj.nombre + '</span>');
                    obj_conductor = obj;
                } else {
                    $("#resultado_conductor").append('<span class="badge" style="background-color: #dc3545; color: #ffffff;">' + $("#ruc_conductor").val() + ' - ' + obj.error + '</span>');
                }

            });
        } else {
            $.get("https://dbusinessaqp.com/api_ruc/api.php", {
                dni: $("#ruc_conductor").val()
            }, function (response) {
                var obj = response;
                $("#resultado_conductor").empty();
                if (obj.error === undefined) {
                    $("#resultado_conductor").append('<span class="badge" style="background-color: #17a2b8; color: #ffffff;">' + $("#ruc_conductor").val() + ' - ' + obj.nombre + '</span>');
                    obj_conductor = obj;
                } else {
                    $("#resultado_conductor").append('<span class="badge" style="background-color: #dc3545; color: #ffffff;">' + $("#ruc_conductor").val() + ' - ' + obj.error + '</span>');
                }

            });
        }

    }
</script>