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
        margin-top: 10%;
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

    #ui-datepicker-div {
        z-index: 1000001 !important;
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

    .oculta {
        display: none;
    }

    .resaltar {
        background-color: yellow;
        /*display: table-row;*/
    }

    .select2-container {
        width: 100% !important;
    }
</style>
<script type="text/javascript" src="res/js/jquery.quicksearch.js"></script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h3><i class='fa fa-square-o'></i> Insumos</h3>
            <div class="w-100 d-block text-right">
                <a href="#" class="btn btn-outline-dark rounded-pill" onclick="nuevo_insumo();"><i class="fa fa-asterisk"></i> Nuevo Insumo</a>
            </div>
            <hr class="dblock w-100">
            <div class="box box-primary">
                <div class="w-100 d-block text-center mb-1 mt-1">
                    <input type="text" id="filtro_rapido" name="filtro_rapido" class="form-control rounded-pill mb-1 mt-1" placeholder="Filtro Rápido" style="margin-left: auto; margin-right: auto; width: 50%;">
                </div>
                <table class="table table-bordered table-hover mt-1" id="lista_cotizaciones">
                    <thead>
                        <th>#</th>
                        <th>Codigo</th>
                        <th>Insumo</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Total</th>
                        <th colspan="2" style="text-align: center;">Acciones</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div id="paginacion_div" style="text-align: right;"></div>
            </div>
        </div>
        <div id="popup_editar" style="display: none;">
            <div class="content-popup">
                <div class="close">
                    <a href="#" id="close_editar">
                        <span aria-hidden="true">×</span>
                    </a>
                </div>
                <div>
                    <h2 id="titulo_detalle">Insumo</h2>
                    <div class="box box-primary">
                        <div class="form-row form-group">
                            <div class="col-md-4">
                                <label for="">Familia</label>
                                <select class="form-control select2" id="combo_familia">

                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="">Clase</label>
                                <select class="form-control select2" id="combo_clase">

                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="">Subclase</label>
                                <select class="form-control select2" id="combo_subclase">

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Generado</label><br>
                                <span id="codigo_completar" style="border-bottom: solid 1px;"></span>
                                <span onclick="limpiar_codigo();" class="btn-sm btn btn-outline-danger rounded-pill"><i class="fa fa-trash"></i></span>
                            </div>
                            <div class="col-md-2">
                                <label for="">Codigo</label>
                                <input type="text" name="id" id="id" class="form-control rounded-pill">
                            </div>
                            <div class="col-md-8">
                                <label for="">Insumo</label>
                                <input type="text" name="insumo" id="insumo" class="form-control rounded-pill">
                            </div>
                            <div class="col-md-12 text-center mt-1 mb-1">
                                <span class="btn btn-danger rounded-pill" onclick="cerrar_editar()">Cerrar</span>
                                <button type="submit" class="btn btn-success rounded-pill" id="btn_formulario">Actualizar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!----------------------------------------------------------------------->
        <div class="modal fade" id="modal_images" tabindex="-1" role="dialog" aria-labelledby="modal_images_tittle" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%; width: 80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="modal_images_tittle">Stock Insumo</h3>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 20px; font-weight: bold;">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row w-100" id="div_imagenes">
                            <div class="col-md-6">
                                <label for="">Proveedor</label>
                                <select class="form-control js-example-basic-single" id="id_proveedor" style="width: 100%;"></select>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha">Fecha</label>
                                <div class="input-group">
                                    <input type="text" name="fecha" id="fecha" readonly="readonly" class="form-control clsDatePicker">
                                    <span class="input-group-addon">
                                        <i id="calIconTourDateDetails" class="glyphicon glyphicon-th"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="">Descripción</label>
                                <input class="form-control rounded-pill" id="descripcion" style="width: 100%;">
                            </div>
                            <div class="col-md-2">
                                <label for="">Unidad</label>
                                <select class="form-control rounded-pill" id="unidad"></select>
                            </div>
                            <div class="col-md-2">
                                <label for="">Stock</label>
                                <input type="text" class="form-control rounded-pill" id="stock" name="">
                            </div>
                            <div class="col-md-2">
                                <label for="">Precio</label>
                                <input type="text" class="form-control rounded-pill" id="precio" name="">
                            </div>
                            <div class="col-md-12 text-center">
                                <button style="margin: 1rem;" class="btn-outline-success rounded-pill btn" onclick="guardar_stock();" id="boton_guardar_stock"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                        <h4>Lista de Stocks Registrados</h4>
                        <table class="table table-hover table-bordered" id="tabla_stocks">
                            <thead>
                                <th>Proveedor</th>
                                <th>Descripción</th>
                                <th>Unidad</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th>Fecha</th>
                                <th></th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="modal-footer form-row" style="margin-top: 1rem;">
                        <div class="col-md-12 mt-2 text-right">
                            <button class="btn-sm btn-danger rounded-pill" type="button" data-dismiss="modal" id="cerrar_mas_imagenes" style="margin-left: 10px">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!----------------------------------------------------------------------->
    </div>
</section>

<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript">
    function cerrar_editar() {
        $('#close_editar').click();
    }

    function cerrar_stock() {
        $('#close_stock').click();
    }

    function actualizar_insumo(id) {
        var clase = $("#combo_clase").val();
        var familia = $("#combo_familia").val();
        var subclase = $("#combo_subclase").val();
        var insumo = $("#insumo").val();
        $.get('core/app/view/insumos.php', {
            parAccion: 'actualizar_insumo',
            id: id,
            subclase: subclase,
            clase: clase,
            familia: familia,
            insumo: insumo,
            codigo: $("#id").val(),
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.Result == 'OK') {
                bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Actualizado Correctamente.</strong>' +
                        '</div>'
                });
                cerrar_editar();
                lista_insumos();
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function combo_familia(id) {
        $("#combo_familia").empty();
        $.get('core/app/view/insumos.php', {
            parAccion: 'combo_familia'
        }, function(data) {
            var obj = JSON.parse(data);
            $("#combo_familia").append('<option value="0">SELECCIONA ...</option>');
            $.each(obj.Records, function(index, val) {
                if (val.codigo == id) {
                    $("#combo_familia").append('<option value="' + val.codigo + '" selected>' + val.descripcion + '</option>')
                } else {
                    $("#combo_familia").append('<option value="' + val.codigo + '">' + val.descripcion + '</option>')
                }

            });
        });
    }

    function combo_clase(id) {
        $("#combo_clase").empty();
        $.get('core/app/view/insumos.php', {
            parAccion: 'combo_clase'
        }, function(data) {
            var obj = JSON.parse(data);
            $("#combo_clase").append('<option value="0">SELECCIONA ...</option>');
            $.each(obj.Records, function(index, val) {
                if (val.codigo.padStart(2, '0') == id) {
                    $("#combo_clase").append('<option value="' + val.codigo + '" selected>' + val.descripcion + '</option>')
                } else {
                    $("#combo_clase").append('<option value="' + val.codigo + '">' + val.descripcion + '</option>')
                }

            });
        });
    }

    function editar_insumo(id) {
        $("#unidad").empty();
        $.get('core/app/view/insumos.php', {
            parAccion: 'detalle_insumo',
            id: id
        }, function(data) {
            var obj = JSON.parse(data);
            $("#btn_formulario").attr('onclick', 'actualizar_insumo(' + obj.id + ');');
            //$("#id").val(obj.subclase);
            $("#codigo_completar").text(obj.familia + "" + "" + obj.clase + "" + obj.subclase);
            $("#insumo").val(obj.insumo);
            $("#stock").val(obj.stock);
            $("#id").val(obj.codigo);

            combo_familia(obj.familia);
            combo_clase(obj.clase);
            combo_subclase(obj.subclase);

            $.get('core/app/view/insumos.php', {
                parAccion: 'combo_unidades',
                id: 0
            }, function(responseText) {
                var uni = JSON.parse(responseText);
                $.each(uni.Records, function(index, val) {
                    if (val.codigo == obj.unidad) {
                        $("#unidad").append('<option value="' + val.codigo + '" selected>' + val.unidad + '</option>');
                    } else {
                        $("#unidad").append('<option value="' + val.codigo + '">' + val.unidad + '</option>');
                    }
                });
            });
        });
        $('#popup_editar').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height($(window).height());
        return false;
    }

    function guardar_insumo() {
        var subclase = $("#combo_subclase").val();
        var insumo = $("#insumo").val();
        var id = $("#id").val();
        var clase = $("#combo_clase").val();
        //var subclase = $();
        var familia = $("#combo_familia").val();
        $.get('core/app/view/insumos.php', {
            parAccion: 'guardar_insumo',
            id: id,
            familia: familia,
            clase: clase,
            subclase: subclase,
            insumo: insumo,
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.Result == 'OK') {
                bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Guardaro Correctamente.</strong>' +
                        '</div>'
                });
                cerrar_editar();
                lista_insumos();
                limpiar_codigo();
                combo_clase();
                combo_familia();
                combo_subclase_2();
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function nuevo_insumo() {
        limpiar_formulario();
        $("#unidad").empty();
        $("#btn_formulario").attr('onclick', 'guardar_insumo();');
        $("#btn_formulario").text("Agregar");
        $.get('core/app/view/insumos.php', {
            parAccion: 'combo_unidades',
            id: 0
        }, function(responseText) {
            var uni = JSON.parse(responseText);
            $.each(uni.Records, function(index, val) {
                $("#unidad").append('<option value="' + val.codigo + '">' + val.unidad + '</option>');
            });
        });

        $('#popup_editar').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height($(window).height());
        return false;
    }

    function limpiar_formulario() {
        $("#id").val('');
        $("#insumo").val('');
        $("#stock").val('');
    }

    function generar_pdf(codigo) {
        $.get('core/app/view/insumos.php', {
            parAccion: 'pdf_cotizacion',
            codigo: codigo
        }, function(data) {
            var obj = JSON.parse(data);
            if (obj.Result == 'OK') {
                bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>PDF generado correctamente.</strong>' +
                        '</div>'
                });
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function eliminar_insumo(id) {
        bootbox.confirm({
            message: "¿Seguro de Eliminar este Insumo?",
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
                    //alert("YES");
                    $.get('core/app/view/insumos.php', {
                        parAccion: 'eliminar_insumo',
                        id: id
                    }, function(data) {
                        var obj = JSON.parse(data);
                        if (obj.Result == 'OK') {
                            lista_insumos();
                            bootbox.alert({
                                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Eliminado Correctamente.</strong>' +
                                    '</div>'
                            });
                        } else {
                            bootbox.alert({
                                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Ago ha salido mal.</strong>' +
                                    '</div>'
                            });
                        }
                    });
                } else {}
            }
        });
    }
    var por_pagina = 10;

    function ver_page(page) {
        $(".btn_page").removeClass('btn-info');
        $(".btn_page").addClass('btn-default');
        $("#page_" + page).addClass('btn-info');

        $(".la_page").css('display', 'none');
        $(".page_" + parseInt(page - 1)).css('display', 'table-row');
    }

    function lista_insumos() {
        $("#lista_cotizaciones").find('tbody').empty();
        $.get('core/app/view/insumos.php', {
            parAccion: 'lista_insumos'
        }, function(data) {
            var obj = JSON.parse(data);
            $("#paginacion_div").empty();
            for (var i = 0; i < (obj.Records.length) / por_pagina; i++) {
                if (i == 0) {
                    $("#paginacion_div").append(`<button class="btn btn-xs btn-info btn_page" id="page_${parseInt(i + 1)}" style="margin: 0.5rem;" onclick="ver_page(${parseInt(i + 1)});">${parseInt(i + 1)}</button>`);
                } else {
                    $("#paginacion_div").append(`<button class="btn btn-xs btn-default btn_page" id="page_${parseInt(i + 1)}" style="margin: 0.5rem;" onclick="ver_page(${parseInt(i + 1)});">${parseInt(i + 1)}</button>`);
                }

            }

            var auz = 0;
            var auz_2 = 0;
            var stock_total = 0;
            var precio_total = 0;
            $.each(obj.Records, function(index, val) {
                stock_total += parseFloat(val.total_to);
                precio_total += parseInt(val.precio_total);
                if (index >= por_pagina) {
                    if (auz_2 == por_pagina) {
                        auz++;
                        auz_2 = 0;
                    } else {

                    }
                    $("#lista_cotizaciones").find('tbody').append('<tr class="la_page page_' + auz + '" style="display: none;"><td>' + parseInt(index + 1) + '</td><td scope="row">' + val.familia + val.clase + val.subclase + val.codigo + '</td><td>' + val.insumo + " " + val.codigo + '</td><td>S/ ' + parseFloat(val.precio_total).toFixed(2) + '</td><td><span data-toggle="modal" data-target="#modal_images" class="btn-sm btn btn-outline-info fa fa-search-plus" onclick="ver_stock(' + val.id + ');"></span></td><td>S/ ' + parseFloat(val.total_to).toFixed(2) + '</td><td><a class="btn btn-sm btn-outline-warning rounded-pill" href="#" onclick="editar_insumo(\'' + val.id + '\')"><i class="fa fa-pencil"></i></a></td><td><a href="#" onclick="eliminar_insumo(\'' + val.id + '\');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa fa-trash"></i></a></td></tr>');
                } else {
                    if (auz_2 == por_pagina) {
                        auz++;
                        auz_2 = 0;
                    } else {

                    }
                    $("#lista_cotizaciones").find('tbody').append('<tr class="la_page page_' + auz + '"><td>' + parseInt(index + 1) + '</td><td scope="row">' + val.familia + val.clase + val.subclase + val.codigo + '</td><td>' + val.insumo + " " + val.codigo + '</td><td>S/ ' + parseFloat(val.precio_total).toFixed(2) + '</td><td><span data-toggle="modal" data-target="#modal_images" class="btn-sm btn btn-outline-info rounded-pill fa fa-search-plus" onclick="ver_stock(' + val.id + ');"></span></td><td>S/ ' + parseFloat(val.total_to).toFixed(2) + '</td><td><a class="btn btn-sm btn-outline-warning rounded-pill" href="#" onclick="editar_insumo(\'' + val.id + '\')"><i class="fa fa-pencil"></i></a></td><td><a href="#" onclick="eliminar_insumo(\'' + val.id + '\');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa fa-trash"></i></a></td></tr>');
                }

                auz_2++;
            });
            $("#lista_cotizaciones").find('tbody').append('<tr><td></td><td scope="row"></td><td></td><td></td><td></td><td>S/ ' + parseFloat(stock_total).toFixed(2) + '</td><td></td><td></td></tr>');
        });

    }

    function guardar_stock(id_insumo) {
        $.post('core/app/view/insumos.php?parAccion=guardar_stock', {
            id_insumo: id_insumo,
            stock: $("#stock").val(),
            codigo_unidad: $("#unidad").val(),
            precio: $("#precio").val(),
            id_proveedor: $("#id_proveedor").val(),
            descripcion: $("#descripcion").val(),
            fecha: $("#fecha").val()
        }, function(data) {
            var obj = JSON.parse(data);

            if (obj.Result == 'OK') {
                bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Guardado Correctamente.</strong>' +
                        '</div>'
                });
                ver_stock(id_insumo);
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function actualizar_stock(id) {
        $.post('core/app/view/insumos.php?parAccion=actualizar_stock', {
            stock: $("#stock").val(),
            codigo_unidad: $("#unidad").val(),
            precio: $("#precio").val(),
            id_proveedor: $("#id_proveedor").val(),
            descripcion: $("#descripcion").val(),
            fecha: $("#fecha").val(),
            id: id
        }, function(data) {
            var obj = JSON.parse(data);

            if (obj.Result == 'OK') {
                bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Actualizado Correctamente.</strong>' +
                        '</div>'
                });
                $("#cerrar_mas_imagenes").click();
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function eliminar_stock(id_stock) {
        bootbox.confirm({
            message: "¿Seguro de Eliminar este Registro?",
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
                    //alert("YES");
                    $.get('core/app/view/insumos.php', {
                        parAccion: 'eliminar_stock',
                        id: id_stock
                    }, function(data) {
                        var obj = JSON.parse(data);
                        if (obj.Result == 'OK') {
                            $("#cerrar_mas_imagenes").click();
                            bootbox.alert({
                                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Eliminado Correctamente.</strong>' +
                                    '</div>'
                            });
                        } else {
                            bootbox.alert({
                                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Ago ha salido mal.</strong>' +
                                    '</div>'
                            });
                        }
                    });
                } else {}
            }
        });
    }

    function editar_stock(id_stock) {
        $.post('core/app/view/insumos.php?parAccion=editar_stock', {
            id_stock: id_stock
        }, function(data) {
            var obj = JSON.parse(data);

            $("#id_proveedor").val(obj.id_proveedor);
            $("#unidad").val(obj.codigo_unidad);
            $("#stock").val(obj.stock);
            $("#precio").val(obj.precio);
            $("#descripcion").val(obj.descripcion);
            $("#fecha").val(obj.fecha);

            $("#boton_guardar_stock").text("Actualizar");
            $("#boton_guardar_stock").attr("onclick", "actualizar_stock(" + id_stock + ");")
        });
    }

    function ver_stock(id_insumo) {
        $('.js-example-basic-single').select2({
            dropdownParent: "#modal_images"
        });
        $("#id_proveedor").val("");
        $("#unidad").val("");
        $("#stock").val("");
        $("#precio").val("");

        $("#boton_guardar_stock").text("Guardar");
        $("#boton_guardar_stock").attr("onclick", "guardar_stock();")


        $.get('core/app/view/insumos.php', {
            parAccion: 'combo_unidades',
            id: 0
        }, function(responseText) {
            var uni = JSON.parse(responseText);
            $.each(uni.Records, function(index, val) {
                $("#unidad").append('<option value="' + val.codigo + '">' + val.unidad + '</option>');
            });
        });

        $("#tabla_stocks").find('tbody').empty();
        $("#boton_guardar_stock").attr('onclick', 'guardar_stock(' + id_insumo + ');');
        $.post('core/app/view/insumos.php?parAccion=ver_stock', {
            id_insumo: id_insumo
        }, function(data) {
            var obj = JSON.parse(data);
            $("#modal_images_tittle").text(obj.insumo);

            $.each(obj.stock, function(index, val) {
                $("#tabla_stocks").find('tbody').append(`
    				<tr>
                        <td>${val.proveedor}</td>
                        <td>${$.trim(val.descripcion)}</td>
    					<td>${val.codigo_unidad}</td>
    					<td>${val.stock}</td>
    					<td>S/ ${val.precio}</td>
                        <td>${$.trim(val.fecha)}</td>
    					<td>
                            <span class="btn btn-outline-warning btn-sm rounded-pill" onclick="editar_stock(${val.id});"><i class="fa fa-pencil"></i></span>
    						<span class="btn btn-outline-danger btn-sm rounded-pill" onclick="eliminar_stock(${val.id});"><i class="fa fa-trash" ></i></span>
    					</td>
    				</tr>
				`);
            });
        });
        $('#popup_stock').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height('100%');

        return false;
    }

    function combo_subclase(id) {
        $("#combo_subclase").empty();
        $.get('core/app/view/insumos.php', {
            parAccion: 'combo_subclase'
        }, function(data) {
            var obj = JSON.parse(data);
            $("#combo_subclase").append('<option value="0">SELECCIONA ...</option>');
            $.each(obj.Records, function(index, val) {
                if (val.codigo == id) {
                    $("#combo_subclase").append('<option value="' + val.codigo + '" selected>' + val.descripcion + '</option>');
                } else {
                    $("#combo_subclase").append('<option value="' + val.codigo + '">' + val.descripcion + '</option>');
                }
            });
        });
    }

    function combo_subclase_2(id) {
        $("#combo_subclase").empty();
        $.get('core/app/view/insumos.php', {
            parAccion: 'combo_subclase_2'
        }, function(data) {
            var obj = JSON.parse(data);
            $("#combo_subclase").append('<option value="0">SELECCIONA ...</option>');
            $.each(obj.Records, function(index, val) {
                if (val.id.padStart(2, '0') == id) {
                    $("#combo_subclase").append('<option value="' + val.codigo + '" selected>' + val.descripcion + '</option>')
                } else {
                    $("#combo_subclase").append('<option value="' + val.codigo + '">' + val.descripcion + '</option>')
                }

            });
        });
    }

    function limpiar_codigo() {
        $("#codigo_completar").text("");
    }
    var familia_valor = "";
    var clase_valor = "";
    var subclase_valor = "";

    var familia_codigo = "";
    var clase_codigo = "";
    var subclase_codigo = "";

    function llenar_proveedores() {
        $.post('core/app/view/insumos.php?parAccion=lista_proveedores', function(data) {
            var obj = JSON.parse(data);

            $.each(obj.Records, function(index, val) {
                $("#id_proveedor").append(`<option value="${val.id}">${val.name}</option>`);
            });
        });
    }
    $(document).ready(function() {
        $(".select2").select2();

        $("#fecha").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            altField: "#fecha_nacimiento_hidden",
            altFormat: "yy-mm-dd"
        });
        combo_familia(0);
        combo_clase(0);
        combo_subclase(0);
        lista_insumos();
        llenar_proveedores();
        $("#combo_clase").on('change', function() {
            clase_codigo = $("#combo_clase").val();
            $("#codigo_completar").text(familia_codigo + clase_codigo + subclase_codigo);

            clase_valor = $("#combo_clase option:selected").text();
            $("#insumo").val(familia_valor + " " + clase_valor + " " + subclase_valor);

        });
        $("#combo_familia").on('change', function() {
            familia_valor = $("#combo_familia option:selected").text();
            $("#insumo").val(familia_valor + " " + clase_valor + " " + subclase_valor);

            familia_codigo = $("#combo_familia").val();
            $("#codigo_completar").text(familia_codigo + clase_codigo + subclase_codigo);
        });
        $("#combo_subclase").on('change', function() {
            subclase_valor = $("#combo_subclase option:selected").text();
            $("#insumo").val(familia_valor + " " + clase_valor + " " + subclase_valor);

            subclase_codigo = $("#combo_subclase").val();
            $("#codigo_completar").text(familia_codigo + clase_codigo + subclase_codigo);
        });
        $('#close_editar').on('click', function() {
            //limpiar_formulario();
            $('#popup_editar').fadeOut('slow');
            $('.popup-overlay').fadeOut('slow');
            return false;
            flag = false;
        });


        //$('#search').quicksearch('#lista_cotizaciones tbody tr');	


        var contenido_fila;
        var coincidencias;
        var exp;
        var codigoAscci;
        $("#filtro_rapido").keyup(function(event) {
            //console.log(event);
            if (!checkTeclaDel(event)) {
                if ($(this).val().length >= 2) {
                    filtrar($(this).val());
                }
            }
            /*$("#lista_cotizaciones tr td").filter(function() {
		        return $(this).text() == $(this).val();
		    }).parent('tr').css('color','red');*/

            /*var tableRow = $("td").filter(function() {
			    return $(this).text() == $(this).val();
			}).closest("tr");

			console.log(tableRow);*/
        });

        function filtrar(cadena) {
            console.log(cadena);
            $("#lista_cotizaciones tbody tr").each(function() {
                $(this).removeClass('oculta');
                contenido_fila = $(this).find('td:eq(2)').html();
                exp = new RegExp(cadena, 'gi');
                coincidencias = contenido_fila.match(exp);
                if (coincidencias != null) {
                    $(this).addClass('resaltar');
                    $(this).css('display', 'table-row');
                } else {
                    $(this).css('display', 'none');
                    $(this).addClass('oculta');
                    $(this).removeClass('resaltar');
                }
            });
        }

        function mostrarFilas() {
            $("#lista_cotizaciones tbody tr").each(function() {
                $(this).removeClass('oculta resaltar');
            });
        }

        function checkTeclaDel(e) {
            codigoAscci = (e.keyCode ? e.keyCode : e.which);
            //console.log(codigoAscci);
            if (codigoAscci == 8) {
                if ($("#filtro_rapido").val().length >= 2) {
                    filtrar($("#filtro_rapido").val());
                } else {
                    mostrarFilas();
                    $("#page_1").click();
                }
                return true;
            } else {
                return false;
            }
        }
    });
</script>