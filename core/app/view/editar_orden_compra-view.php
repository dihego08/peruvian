<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<?php
date_default_timezone_set('America/Lima');

$proveedores = PersonData::getProviders();
?>
<style>
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

    .seleccion_venta {
        background-color: #dff0d8;
    }

    .w-90 {
        width: 90% !important;
    }
</style>
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

    .badge {
        cursor: pointer;
    }
</style>
<section class="content">

    <div class="row">
        <div class="col-md-12">
            <h3><i class='glyphicon glyphicon-shopping-cart'></i>ORDEN DE COMPRA</h3>
            <hr>
            <div class="form-row">
                <div class="col-md-6">
                    <label for="">Seleccionar Proveedor</label>
                    <select name="id_proveedor" id="id_proveedor" class="form-control rounded-pill">
                        <option value="-1">--SELECCIONA--</option>
                        <?php
                        foreach ($proveedores as $key) {
                            echo '<option value="' . $key->id . '">' . $key->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Seleccionar Fecha</label>
                    <input type="text" class="form-control rounded-pill rounded-pill" id="fecha">
                </div>
                <div class="col-md-4">
                    <label for="">Lugar de Entrega</label>
                    <input type="text" class="form-control rounded-pill rounded-pill" id="lugar_entrega">
                </div>
                <div class="col-md-4">
                    <label for="fecha_entrega">Fecha de Entrega</label>
                    <input type="text" class="form-control rounded-pill rounded-pill" id="fecha_entrega">
                </div>
                <div class="col-md-4">
                    <label for="fecha_entrega">Forma de Pago</label>
                    <select name="" id="id_forma_pago" class="form-control">
                        <option value="0">--SELECCIONE--</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div id="resultado_busqueda_order">
                    <div class="col-md-12" style="margin-top: 1rem;">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>Contenido O.C.</h2>
                            </div>
                            <div class="col-md-4" style="text-align: right;">
                                <h2><button class="btn btn-success rounded-pill" onclick="add_item();"><i class="fa fa-plus"></i></button></h2>
                            </div>
                        </div>
                        <div class="box box-primary">
                            <table class="table table-bordered table-hover" id="detalle_busqueda_order">
                                <thead>
                                    <tr>
                                        <th>Ítem</th>
                                        <th>Cant.</th>
                                        <th>Tipo</th>
                                        <th>Und.</th>
                                        <th style="width: 40%;">Descripción</th>
                                        <th>P. Unitario</th>
                                        <th>P. Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h2>Total</h2>
                        <div class="box box-primary">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            Total:
                                        </th>
                                        <td id="">
                                            <span id="total_detalle_span">S/ </span>
                                            <input type="hidden" name="" readonly id="total_detalle_td" class="form-control rounded-pill">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row w-100 text-right">
                            <button class="btn btn-danger rounded-pill" onclick="cancelar_venta();">Cancelar</button>
                            <button class="btn btn-success rounded-pill" id="guardar_venta">Actualizar O.C.</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                var _subtotal = 0;
                var ids_venta = 0;
                var contador = 0;

                function combo_unidades(id, id_unidad) {
                    $.get('core/app/view/insumos.php', {
                        parAccion: 'combo_unidades',
                        id: '0'
                    }, function(data) {
                        var obj = JSON.parse(data);
                        $.each(obj.Records, function(index, val) {
                            if (val.codigo == id_unidad) {
                                $("#t_unidad_" + id).append('<option value="' + val.codigo + '" selected>' + val.unidad + '</option>')
                            } else {
                                $("#t_unidad_" + id).append('<option value="' + val.codigo + '">' + val.unidad + '</option>')
                            }
                        });
                    });
                }

                function add_item() {
                    contador = parseInt(contador) + parseInt(1);
                    $("#detalle_busqueda_order").append(`
						<tr id="tr_${contador}">
							<td style="text-align: center;" class="item_producto">${contador}</td>
							<td class="cantidad_producto">
								<input step="any" type="number" class="form-control rounded-pill cantidades_producto w-90" id="unit_${contador}" value="1"/>
							</td>
							<td class="tipo_producto">
								<select name="" id="tipo_${contador}" class="form-control rounded-pill tipos_producto w-90">
									<option value="1">Producto</option>
									<option value="2">Servicio</option>
								</select>
							</td>
							<td class="id_unidad">
								<select class="form-control rounded-pill unidades_producto" name="t_unidad_${contador}" id="t_unidad_${contador}"></select>
							</td>
							<td class="descripcion_producto">
								<textarea name="" id="descripcion_${contador}" class="form-control rounded-pill w-90 descripciones_producto"></textarea>
							</td>
							<td class="precio_producto">
								<input data-id="${contador}" type="text" class="form-control rounded-pill w-90 precios_producto" id="precio_${contador}" value="1"/>
							</td>
							<td scope="row" id="precio_total_${contador}" style="text-align: center;" class="totales_">0</td>
							<td style="text-align: center;"><span class="btn btn-sm btn-outline-danger rounded-pill" onclick="remove_row(${contador});"><i class="fa fa-trash"></i></span></td>
						</tr>
					`);

                    $(".precios_producto").keyup(function() {
                        var id = $(this).attr("data-id");
                        $("#precio_total_" + id).text(($(this).val() * $("#unit_" + id).val()).toFixed(2));
                        var To = 0;
                        $(".totales_").each(function(index, el) {
                            To += parseFloat($(this).text());
                        });
                        $("#total_detalle_span").text(To.toFixed(2));
                        $("#total_detalle_td").val(To.toFixed(2));
                    });

                    combo_unidades(contador);
                }

                function remove_row(contador) {
                    $("#total_detalle_td").val($("#total_detalle_td").val() - $("#precio_total_" + contador).text());
                    $("#total_detalle_span").text($("#total_detalle_td").val());

                    $("#tr_" + contador).remove();
                }

                function cancelar_venta() {
                    $("#resultado_busqueda_order").attr('hidden', 'true');
                    $("#tabla_lista_venta").find('tbody').empty();
                    $("#subtotal").val("");
                    $("#subtotal_detalle_span").val("");
                    $("#subtotal_detalle_td").val("");
                    $("#total_detalle_span").val("");
                    $("#total_detalle_td").val("");
                    $("#igv_p_td").val("");
                    $("#igv_p_span").text("");
                    $("#txt_guia").text("");
                    $("#txt_guia").val("");
                    $("#txt_pedido").text("");

                    $("#detraccion_td").val("");
                    $("#detraccion_span").text("");

                    $("#igv_detalle_td").val("");
                    $("#igv_detalle_span").text("");

                    $("#subtotal_detalle_td").val("");
                    $("#subtotal_detalle_span").text("");

                    $("#total_detalle_span").text("");
                    $("#total_detalle_td").val("");
                    _subtotal = 0;
                    $("#detraccion_no").prop("checked", true);
                    $("#detra").attr('hidden', true);
                    $("#igv_pa").attr('hidden', true);
                    $("#detraccion_td").val('');
                    $("#detraccion_span").text($("#detraccion_td").val());
                    $("#igv_p_span").text($("#igv_detalle_td").val());
                }

                function eliminar_producto_venta(id) {
                    $("#ocul_" + id).attr('hidden', true);
                    $("#pro_" + id).removeClass('id_producto');

                    $("#subtotal").val((parseFloat($("#subtotal").val()) - parseFloat($("#tot_" + id).html())).toFixed(2));
                    console.log($("#subtotal").val());
                    console.log("MONTO A RESTAR" + $("#tot_" + id).text());

                    _subtotal = $("#subtotal").val();
                    if ($("#tipos_documento").val() == 2) {
                        calcular_montos($("#subtotal").val(), $("#descuento").val(), true);
                    } else {
                        calcular_montos($("#subtotal").val(), $("#descuento").val(), false);
                    }
                    $("#btn_borrar_" + id).closest('tr').remove();
                }

                function lista_forma_pago() {
                    $.post("core/app/view/insumos.php?parAccion=lista_forma_pago", function(response) {
                        var obj = JSON.parse(response);
                        $.each(obj, function(index, val) {
                            $("#id_forma_pago").append(`<option value="${val.id}">${val.name}</option>`);
                        });
                    });
                }

                function get_order_compra() {
                    $.post("core/app/view/insumos.php?parAccion=get_order_compra", {
                        id: <?php echo $_GET['id']; ?>
                    }, function(response) {
                        var obj = JSON.parse(response);
                        $("#id_proveedor").val(obj.id_proveedor).trigger("change");
                        $("#fecha").val(obj.fecha);
                        $("#lugar_entrega").val(obj.lugar_entrega);
                        $("#fecha_entrega").val(obj.fecha_entrega);
                        $("#id_forma_pago").val(obj.id_forma_pago);
                        $("#total_detalle_td").val(obj.total);
                        $("#total_detalle_span").text('S/ ' + obj.total);
                    });
                }
                var contador = 0;

                function get_order_compra_detalle() {
                    $.post("core/app/view/insumos.php?parAccion=get_order_compra_detalle", {
                        id: <?php echo $_GET['id']; ?>
                    }, function(response) {
                        var obj = JSON.parse(response);
                        $.each(obj, function(index, val) {
                            contador = parseInt(contador) + parseInt(1);
                            $("#detalle_busqueda_order").find("tbody").append(`<tr id="tr_${contador}">
                                <td style="text-align: center;" class="item_producto">${contador}</td>
                                <td class="cantidad_producto">
                                    <input step="any" type="number" class="form-control rounded-pill cantidades_producto w-90" id="unit_${contador}" value="${val.cantidad}"/>
                                </td>
                                <td class="tipo_producto">
                                    <select name="" id="tipo_${contador}" class="form-control rounded-pill tipos_producto w-90">
                                        <option value="1">Producto</option>
                                        <option value="2">Servicio</option>
                                    </select>
                                </td>
                                <td class="id_unidad">
                                    <select class="form-control rounded-pill unidades_producto" name="t_unidad_${contador}" id="t_unidad_${contador}"></select>
                                </td>
                                <td class="descripcion_producto">
                                    <textarea name="" id="descripcion_${contador}" class="form-control rounded-pill w-90 descripciones_producto">${val.descripcion}</textarea>
                                </td>
                                <td class="precio_producto">
                                    <input data-id="${contador}" type="text" class="form-control rounded-pill w-90 precios_producto" id="precio_${contador}" value="${val.precio_unitario}"/>
                                </td>
                                <td scope="row" id="precio_total_${contador}" style="text-align: center;" class="totales_">${val.precio_total}</td>
                                <td style="text-align: center;"><span class="btn btn-sm btn-outline-danger rounded-pill" onclick="remove_row(${contador});"><i class="fa fa-trash"></i></span></td>
                            </tr>`);
                            combo_unidades(contador, val.id_unidad);
                            //$("#t_unidad_" + contador).val(val.id_unidad);
                            $("#tipo_" + contador).val(val.tipo);
                        });

                        $(".precios_producto").keyup(function() {
                            var id = $(this).attr("data-id");
                            $("#precio_total_" + id).text(($(this).val() * $("#unit_" + id).val()).toFixed(2));
                            var To = 0;
                            $(".totales_").each(function(index, el) {
                                To += parseFloat($(this).text());
                            });
                            $("#total_detalle_span").text(To.toFixed(2));
                            $("#total_detalle_td").val(To.toFixed(2));
                        });
                    });
                }
                $(document).ready(function() {
                    lista_forma_pago();
                    get_order_compra();
                    get_order_compra_detalle();
                    $("#id_proveedor").select2();
                    $('#fecha').datepicker({
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true,
                        altFormat: "yy-mm-dd"
                    });

                    $('#fecha_entrega').datepicker({
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true,
                        altFormat: "yy-mm-dd"
                    });
                });

                var dett = 'no';
                $(document).ready(function() {
                    $("#guardar_venta").click(function() {
                        if ($("#id_proveedor").val() == 0 && $("#id_proveedor").val() == "" && $("#id_proveedor").val() == "-1") {
                            bootbox.alert({
                                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Seleccionar un proveedor.</strong>' +
                                    '</div>'
                            });
                        } else {
                            if (contador == 0) {
                                bootbox.alert({
                                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                        '<strong>No se ha Ingresado Detalle de Orden de Compra.</strong>' +
                                        '</div>'
                                });
                            } else {
                                var ids = 0;
                                var precios = 0;
                                var unidades = 0;
                                // var unidades_r = 0;
                                var cantidades = 0;
                                var descripciones = 0;
                                var p_b_s = 0;
                                var n_pedidos = 0;
                                var tipos_productos = 0;

                                $(".item_producto").parent("tr").find(".precios_producto").each(function() {
                                    precios = precios + ',' + $(this).val();
                                });

                                $(".item_producto").parent("tr").find(".cantidades_producto").each(function() {
                                    cantidades = cantidades + ',' + $(this).val();
                                });

                                $(".item_producto").parent("tr").find(".unidades_producto").each(function() {
                                    console.log("-------");
                                    console.log($(this).val());
                                    console.log("-------");
                                    unidades = unidades + ',' + $(this).val();
                                });

                                $(".item_producto").parent("tr").find(".descripciones_producto").each(function() {
                                    descripciones = descripciones + '--' + $(this).val();
                                });
                                $(".item_producto").parent("tr").find(".tipos_producto").each(function() {
                                    tipos_productos = tipos_productos + '--' + $(this).val();
                                });

                                $.get('core/app/view/compra.php', {
                                    parAccion: 'actualizar_orden_compra',
                                    total: $("#total_detalle_td").val(),
                                    tipos_productos: tipos_productos,
                                    descripciones: descripciones,
                                    cantidades: cantidades,
                                    precios: precios,
                                    fecha: $("#fecha").val(),
                                    id_proveedor: $("#id_proveedor").val(),
                                    lugar_entrega: $("#lugar_entrega").val(),
                                    fecha_entrega: $("#fecha_entrega").val(),
                                    id_forma_pago: $("#id_forma_pago").val(),
                                    unidades: unidades,
                                    id: <?php echo $_GET['id']; ?>
                                }, function(data_) {
                                    var obj_ = JSON.parse(data_);
                                    if (obj_.Result == 'OK') {
                                        bootbox.alert({
                                            message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                                '<strong>Venta registrada correctamente.</strong>' +
                                                '</div>'
                                        });
                                        //cancelar_venta();
                                        window.location.href = "?view=orden_compra";
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
                $(document).ready(function() {
                    $("#product_code").keydown(function(e) {
                        if (e.which == 17 || e.which == 74) {
                            e.preventDefault();
                        } else {
                            console.log(e.which);
                        }
                    })
                });
            </script>

            <div id="cartofsell"></div>
        </div>
</section>
<script>
    $(document).ready(function() {
        $.get("./?action=cartofsell", null, function(data) {
            $("#cartofsell").html(data);
        });
    });
</script>