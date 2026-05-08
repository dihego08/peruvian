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
</style>
<script type="text/javascript" src="res/js/jquery.quicksearch.js"></script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <span data-toggle="modal" data-target="#modal_images" class="btn btn-success pull-right rounded-pill" onclick="nuevo_produccion();"><i class="fa fa-plus"></i> Registro Produccion</span>
            <h3><i class='fa fa-square-o'></i> Produccion</h3>
            <div class="clearfix"></div>
            <br>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Detalle de Produccion</h3>
                </div>
                <table class="table table-bordered table-hover" id="lista_produccion">
                    <thead>
                        <th>Pedido</th>
                        <th>Insumos</th>
                        <th></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

        <!----------------------------------------------------------------------->
        <div class="modal fade" id="modal_images" tabindex="-1" role="dialog" aria-labelledby="modal_images_tittle" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%; width: 80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="modal_images_tittle">Produccion</h3>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 20px; font-weight: bold;">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row w-100" id="div_imagenes">
                            <div class="col-md-3">
                                <label for="">N° Pedido</label>
                                <select class="form-control rounded-pill" id="orden"></select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Insumo</label>
                                <select class="form-control rounded-pill" id="id_insumo"></select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Cantidad</label>
                                <input type="text" class="form-control rounded-pill" id="cantidad" name="">
                            </div>
                            <div class="col-md-3">
                                <label for="">Unidad</label>
                                <select class="form-control rounded-pill" id="unidad"></select>
                            </div>
                            <div class="col-md-12 text-center">
                                <button style="margin: 1rem;" class="btn btn-outline-success rounded-pill" onclick="guardar_produccion();" id="boton_guardar_stock"><i class="fa fa-check"></i> Guardar</button>
                            </div>
                        </div>
                        <h4>Lista</h4>
                        <table class="table table-hover table-bordered" id="tabla_stocks">
                            <thead>
                                <th>Orden</th>
                                <th>Insumo</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="modal-footer form-row" style="margin-top: 1rem;">
                        <div class="col-md-12 mt-2 text-right">
                            <button class="btn btn-danger rounded-pill" type="button" data-dismiss="modal" id="cerrar_mas_imagenes" style="margin-left: 10px">
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

<script type="text/javascript">
    function llenar_pedidos() {
        $.post('core/app/view/order.php?parAccion=lista_ordenes&id_cliente=0', function(data) {
            var obj = JSON.parse(data);

            $.each(obj.Records, function(index, val) {
                $("#orden").append(`<option value="${val.codigo}">${val.codigo}</option>`)
            });
        });
    }

    function llenar_insumos() {
        $.post('core/app/view/insumos.php?parAccion=lista_insumos', function(data) {
            var obj = JSON.parse(data);

            $("#id_insumo").append(`<option value="-1">--SELECCIONA--</option>`);
            $.each(obj.Records, function(index, val) {
                $("#id_insumo").append(`<option value="${val.id}">${val.insumo} ${val.codigo}</option>`);
            });
        });
    }

    function combo_unidades(id) {
        $("#unidad").empty();
        $.get('core/app/view/insumos.php', {
            parAccion: 'combo_unidades',
            id: id
        }, function(data) {
            var obj = JSON.parse(data);
            $.each(obj.Records, function(index, val) {
                $("#unidad").append('<option value="' + val.codigo + '">' + val.unidad + '</option>')
            });
        });
    }

    function guardar_produccion() {
        $.post('core/app/view/insumos.php?parAccion=guardar_produccion', {
            orden: $("#orden").val(),
            id_insumo: $("#id_insumo").val(),
            cantidad: $("#cantidad").val(),
            unidad: $("#unidad").val(),
        }, function(data) {
            var obj = JSON.parse(data);

            if (obj.Result == 'OK') {
                /*bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Guardado Correctamente.</strong>' +
                        '</div>'
                });*/
                lista_produccion();

                add_to_table($("#orden").val(), $("#id_insumo option:selected").text(), $("#cantidad").val(), $("#unidad option:selected").text());
                $("#cantidad").val("");
                //$("#cerrar_mas_imagenes").click();
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Ago ha salido mal.</strong>' +
                        '</div>'
                });
            }
        });
    }

    function add_to_table(orden, insumo, cantidad, unidad) {
        $("#tabla_stocks").find('tbody').append(`
            <tr>
                <td>${orden}</td>
                <td>${insumo}</td>
                <td>${cantidad}</td>
                <td>${unidad}</td>
            </tr>
        `);
    }

    function nuevo_produccion() {
        $("#cantidad").val("");
        $("#tabla_stocks").find('tbody').empty();
    }

    function lista_produccion() {
        $("#lista_produccion").find('tbody').empty();
        $.post('core/app/view/insumos.php?parAccion=lista_produccion', function(data) {
            var obj = JSON.parse(data);
            if (obj.length == 0) {
                $("#lista_produccion").find('tbody').append(`
                    <tr>
                        <td colspan="2" style="text-align: center;">--NO HAY REGISTROS--</td>
                    </tr>
                `);
            } else {
                $.each(obj, function(index, val) {
                    $("#lista_produccion").find('tbody').append(`
                        <tr>
                            <td style="vertical-align: middle;">Pedido: <strong>${val.orden}</strong></td>
                            <td>${val.insumos}</td>
                            <td>
                                <!--<span class="btn-xs btn-warning" onclick="editar(${val.id});"><i class="fa fa-pencil"></i></span>-->
                                <span class="btn-sm btn btn-outline-danger rounded-pill" onclick="eliminar(${val.id});"><i class="fa fa-trash"></i></span>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    }

    function eliminar(id) {
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
                        parAccion: 'eliminar_produccion',
                        id: id
                    }, function(data) {
                        var obj = JSON.parse(data);
                        if (obj.Result == 'OK') {
                            bootbox.alert({
                                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Eliminado Correctamente.</strong>' +
                                    '</div>'
                            });
                            lista_produccion();
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
    $(document).ready(function() {
        lista_produccion();
        llenar_pedidos();
        llenar_insumos();

        $("#id_insumo").on('change', function() {
            combo_unidades($(this).val());
        });
    });
</script>