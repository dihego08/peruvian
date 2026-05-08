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
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if (Core::$user->kind == 1 || Core::$user->kind == 12) { ?><a href="./?view=newcotization" class="btn btn-outline-dark rounded-pill pull-right"><i class="fa fa-asterisk"></i> Nueva cotizacion</a><?php } ?>
            <h3>
                <i class='fa fa-square-o'></i> Cotizaciones
            </h3>
            <div class="clearfix"></div>
            <br>
            <div class="box box-primary">
                <table class="table table-bordered table-hover" id="lista_cotizaciones">
                    <thead>
                        <th></th>
                        <th>Codigo</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th></th>
                        <th>Subtotal</th>
                        <th>IGV</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div id="popup_editar" style="display: none;">
            <div class="content-popup">
                <div class="close"><a href="#" id="close_editar">X</a></div>
                <div>
                    <h4 id="titulo_detalle">Detalle De Cotización</h4>
                    <div class="box box-primary">
                        <table class="table table-bordered table-hover" id="tabla_detalle">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Costo</th>
                                    <th>Cantidad</th>
                                    <th>Descripción</th>
                                    <th>Modelo</th>
                                    <th>Bordado</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <span class="btn btn-danger rounded-pill" onclick="cerrar_editar()">Cerrar</span>
                    <!--<button type="submit" class="btn btn-success" style="float: right;" id="btn_formulario">Actualizar</button>-->
                </div>
            </div>
        </div>
        <div class="popup-overlay"></div>
    </div>
</section>
<!----------------------------------------------------------------------->
<div class="modal fade" id="formulario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <!--<h3 class="modal-title" id="exampleModalLabel">Nuevo Alumno</h3>-->
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group" style="width: 100%;">
                    <img src="" id="la_imagen" style="width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!----------------------------------------------------------------------->
<script type="text/javascript">
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

    function cerrar_editar() {
        $('#close_editar').click();
    }

    function detalle_cotizacion(codigo) {
        $("#tabla_detalle").find('tbody').empty();
        $.get('core/app/view/cotizacion.php', {
            parAccion: 'detalle_cotizacion',
            codigo: codigo
        }, function(data) {
            var obj = JSON.parse(data);
            $.each(obj.Records, function(index, val) {
                $("#tabla_detalle").find('tbody').append('<tr><th scope="row">' + val.name + '</th><td>' + val.costo + '</td><td>' + val.cantidad + '</td><td>' + val.descripcion + '</td><td style="width: 50px;"><img src="storage/products/' + val.imagen + '" class="thumbnail" style="width: 100px;"></td><td style="width: 50px;"><img alt="No Imagen" src="storage/products/' + val.imagen_2 + '" class="thumbnail" style="width: 100px;"></td></tr>');
            });
        });
        $('#popup_editar').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height($(window).height());
        return false;
    }

    function generar_pdf(codigo) {
        $.get('core/app/view/cotizacion.php', {
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

    function eliminar_cotizacion(codigo) {
        bootbox.confirm({
            message: "¿Seguro de Eliminar esta Cotización?",
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
                    $.get('core/app/view/cotizacion.php', {
                        parAccion: 'eliminar_cotizacion',
                        codigo: codigo
                    }, function(data) {
                        var obj = JSON.parse(data);
                        if (obj.Result == 'OK') {
                            lista_cotizaciones();
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

    function lista_cotizaciones() {
        $("#lista_cotizaciones").find('tbody').empty();
        $.get('core/app/view/cotizacion.php', {
            parAccion: 'lista_cotizaciones'
        }, function(data) {
            var obj = JSON.parse(data);
            $.each(obj.Records, function(index, val) {
                $("#lista_cotizaciones").find('tbody').append('<tr><td style="width:30px;">' +
                    '<a href="#" onclick="detalle_cotizacion(\'' + val.codigo + '\');" class="btn btn-sm btn-outline-dark rounded-pill"><i class="glyphicon glyphicon-eye-open"></i></a></td><td>' + val.codigo + '</td><td>' + val.fecha_creacion + '</td><td>' + val.name + '</td><td style="width: 50px;"><img src="storage/products/' + val.imagen + '" class="thumbnail" data-toggle="modal" data-target="#formulario" onclick="ver_imagen(\'storage/products/' + val.imagen + '\');" style="width: 100px; cursor: pointer;" title="Ver Imagen"></td><td> S/. ' + val.sub_total + '</td><td> S/. ' + val.igv + '</td><td> S/. ' + parseFloat(val.total).toFixed(2) + '</td><td><a href="#" onclick="eliminar_cotizacion(\'' + val.codigo + '\');" class="btn btn-sm btn-outline-danger d-block mt-1 rounded-pill"><i class="fa fa-trash"></i></a><a class="btn btn-sm btn-outline-info d-block mt-1 rounded-pill" href="core/app/view/pdf-cotizacion.php?codigo=' + val.codigo + '"><i class="fa fa-file-pdf-o"></i></a></td></tr>');

            });
        });
    }

    function ver_imagen(url_imagen) {
        $("#la_imagen").prop('src', url_imagen);
    }

    function lista_cotizaciones_cliente(cli) {
        $("#lista_cotizaciones").find('tbody').empty();
        $.get('core/app/view/cotizacion.php', {
            parAccion: 'lista_cotizaciones_cliente',
            cli: cli
        }, function(data) {
            var obj = JSON.parse(data);
            $.each(obj.Records, function(index, val) {

                $("#lista_cotizaciones").find('tbody').append('<tr><td style="width:30px;">' +
                    '<a href="#" onclick="detalle_cotizacion(\'' + val.codigo + '\');" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td><td>' + val.codigo + '</td><td>' + val.fecha_creacion + '</td><td>' + val.name + '</td><td style="width: 50px;"><img src="storage/products/' + val.imagen + '" class="thumbnail" data-toggle="modal" data-target="#formulario" onclick="ver_imagen(\'storage/products/' + val.imagen + '\');" style="width: 100px; cursor: pointer;" title="Ver Imagen"></td><td> S/. ' + val.sub_total + '</td><td> S/. ' + val.igv + '</td><td> S/. ' + parseFloat(val.total).toFixed(2) + '</td><td></a></td><td><a class="btn btn-xs btn-info" href="core/app/view/pdf-cotizacion.php?codigo=' + val.codigo + '"><i class="fa fa-file-pdf-o"></i></a></td></tr>');

            });
        });
    }
    $(document).ready(function() {
        if (k == 1 || k == 12) {
            lista_cotizaciones();
        } else {
            lista_cotizaciones_cliente(kk);
        }
        $('#close_editar').on('click', function() {
            //limpiar_formulario();
            $('#popup_editar').fadeOut('slow');
            $('.popup-overlay').fadeOut('slow');
            return false;
            flag = false;
        });
    });
</script>