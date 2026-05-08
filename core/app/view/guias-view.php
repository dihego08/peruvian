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
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">Lista General de Guías de Remisión</h4>
                    <div class="col-md-12 text-right">
                        <a href="./?view=guia_remision" class="btn btn-success rounded-pill"><i class="fa fa-plus"></i> Nueva Guía</a>
                    </div>
                </div>
                <div class="box-body" style="margin-bottom: 2rem; height: 100vh; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabla_ventas" style="font-size: 11px !important;">
                            <thead>
                                <th>#</th>
                                <th></th>
                                <th>Num Guía</th>
                                <th>Fecha</th>
                                <th>Fecha Traslado</th>
                                <th>Origen</th>
                                <th>Razón Social</th>
                                <th>Destinatario</th>
                                <th>Destino</th>
                                <th>Transportista</th>
                                <th>Placa</th>
                                <th>Conductor</th>
                                <th>Total Brutos</th>
                                <th>Total Netos</th>
                                <th></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
                <a href="" class="pull-right btn btn-info" id="reportar" target="_blanck">Exportar PDF</a>
                <a href="" class="pull-right btn btn-primary" style="margin-right: 2rem;" id="reportar_excel" target="_blanck">Exportar Excel</a>
            </div>
            <div id="popup_editar" style="display: none;">
                <div class="content-popup">
                    <div class="close"><a href="#" id="close_editar"><strong>X</strong></a></div>
                    <div>
                        <h2 id="titulo_detalle">Detalle de Guía de Remisión</h2>
                        <div class="box box-primary">
                            <table class="table table-bordered table-hover" id="tabla_detalle">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Pedido</th>
                                        <th>Cantidad</th>
                                        <th>Unidad</th>
                                        <th>Peso Neto</th>
                                        <th>Peso Bruto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="w-100 text-right">
                            <span class="btn btn-danger rounded-pill" onclick="cerrar_editar()">Cerrar</span>
                        </div> <!--<button type="submit" class="btn btn-success" style="float: right;" id="btn_formulario">Actualizar</button>-->
                    </div>
                </div>
            </div>
            <div class="popup-overlay"></div>

            <div class="clearfix"></div>


        </div>
    </div>
</section>
<script type="text/javascript">
    function cerrar_editar() {
        $('#close_editar').click();
    }

    function buscar_por_fecha() {
        var desde = $("#fecha_desde").val();
        var hasta = $("#fecha_hasta").val();
        $("#reportar").attr('href', 'core/app/view/generar_pdf.php?filtro=fecha&tabla=ventas&desde=' + desde + '&hasta=' + hasta + '&tipos_pago=' + $("#tipos_pago").val() + '&tipos_documento=' + $("#tipos_documento").val() + '&combo_cliente=' + $("#combo_cliente").val());

        $("#reportar_excel").attr('href', 'core/app/view/generar_excel.php?filtro=fecha&tabla=ventas&desde=' + desde + '&hasta=' + hasta + '&tipos_pago=' + $("#tipos_pago").val() + '&tipos_documento=' + $("#tipos_documento").val() + '&combo_cliente=' + $("#combo_cliente").val());

        $.get('core/app/view/venta.php', {
            parAccion: 'buscar_por_fecha',
            desde: $("#fecha_desde").val(),
            hasta: $("#fecha_hasta").val(),
            tipos_pago: $("#tipos_pago").val(),
            tipos_documento: $("#tipos_documento").val(),
            combo_cliente: $("#combo_cliente").val(),
        }, function(data) {
            var obj = JSON.parse(data);
            $("#tabla_ventas").find('tbody').empty();

            var t_adeuda = 0;
            var t_total = 0;

            $.each(obj.Records, function(index, val) {

                t_adeuda += parseFloat(val.a_cuenta);
                t_total += parseFloat(val.valor_pagar);

                var pa_pagar = "";
                if (val.detraccion == "yes") {
                    if (val.detraccion_p > 0 && val.detraccion_paga == 0) {
                        console.log(val.codigo_venta + " si cumple");
                        pa_pagar = `<select class="form-control-sm" style="display: block; margin-bottom: 0.5rem;" id="${val.codigo_venta}">
							<option value="0">PENDIENTE</option>
							<option value="1">PAGADO</option>
						</select>
						<span class="btn btn-sm btn-outline-success rounded-pill " onclick="guardar_pago_detraccion('${val.codigo_venta}');"><i class="fa fa-check"></i></span>`;
                    }
                }

                if (val.a_cuenta > parseFloat('0.00')) {
                    $("#tabla_ventas").find('tbody').append('<tr class="danger" id="' + val.codigo_venta + '_"><td>' + parseInt(index + 1) + '</td><td>' + '<a href="#" onclick="detalle_venta(\'' + val.codigo_venta + '\');" class="btn btn-sm btn-outline-dark rounded-pill"><i class="glyphicon glyphicon-eye-open"></i></a>' + '</td><td>' + val.codigo_venta + '</td><td>' + val.fc + '</td><td>' + val.tipo_documento + '</td><td>' + val.pago + '</td><td>' + val.entrega + '</td><td>S/. ' + val.valor_pagar + '</td><td>' + val.person + '</td><td class="text-center">' + val.detraccion_p + `
						${pa_pagar}
						` + '</td><td>S/. ' + val.a_cuenta + '</td><td>' + '<a href="#" onclick="eliminar_venta(\'' + val.codigo_venta + '\');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa fa-trash"></i></a>' + '</td>' +
                        '</tr>');
                } else {
                    $("#tabla_ventas").find('tbody').append('<tr id="' + val.codigo_venta + '_"><td>' + parseInt(index + 1) + '</td><td>' + '<a href="#" onclick="detalle_venta(\'' + val.codigo_venta + '\');" class="btn btn-sm btn-outline-dark rounded-pill"><i class="glyphicon glyphicon-eye-open"></i></a>' + '</td><td>' + val.codigo_venta + '</td><td>' + val.fc + '</td><td>' + val.tipo_documento + '</td><td>' + val.pago + '</td><td>' + val.entrega + '</td><td>S/. ' + val.valor_pagar + '</td><td>' + val.person + '</td><td class="text-center">' + val.detraccion_p + `
						${pa_pagar}
						` + '</td><td>S/. ' + val.a_cuenta + '</td><td>' + '<a href="#" onclick="eliminar_venta(\'' + val.codigo_venta + '\');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa fa-trash"></i></a>' + '</td>' +
                        '</tr>');
                }
            });

            $("#tabla_ventas").find('tbody').append(`<tr style="font-weight: bold;">
				<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>S/ ` + t_total.toFixed(2) + `</td>
                <td></td>
                <td>S/ ` + t_adeuda.toFixed(2) + `</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>`);
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
        return bancos;
    }

    function carga_historial_pago(id_person, codigo_venta) {
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
                $("#detalle_pagos").find('tbody').append("<tr><td scope='row'>" + val.fecha_creacion + "</td><td>S/. " + val.pago + "</td><td>" + concepto + "</td><td>" + val.banco + "</td><td>S/. " + val.deuda + "</td></tr>");
            });

            $("#fecha_p").datetimepicker({
                format: "Y-m-d",
                timepicker: false
            });
        });
    }

    function detalle_venta(codigo, num_guia) {
        $("#tabla_detalle").find('tbody').empty();
        $("#titulo_detalle").empty();
        $("#titulo_detalle").append('Detalle de Guía de Remisión: <a href="#' + codigo + '_" onclick="cerrar_editar();">' + num_guia + '</a>');
        $("#pagado").val('');
        $.get('core/app/view/venta.php', {
            parAccion: 'lista_detalle_guia',
            codigo: codigo
        }, function(data) {
            var obj = JSON.parse(data);

            $.each(obj.Records, function(index, val) {
                subtotal = parseFloat(val.precio_unitario) + parseFloat(val.precio_bordado / val.cantidad);
                $("#tabla_detalle > tbody").append(`<tr>
                    <th scope="row">${val.descripcion_producto}</th>
                    <td>${$.trim(val.pedido)}</td>
                    <td>${val.cantidad}</td>
                    <td>${val.unidad}</td>
                    <td>${val.t_neto}</td>
                    <td>${val.t_bruto}</td>
                </tr>`);
            });

        });
        
        $('#popup_editar').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height($(window).height());
        return false;
    }

    function eliminar_venta(codigo) {
        bootbox.confirm({
            message: "¿Seguro de Eliminar esta Venta?",
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
                    $.get('core/app/view/venta.php', {
                        parAccion: 'eliminar_venta',
                        codigo: codigo
                    }, function(data) {
                        var obj = JSON.parse(data);
                        if (obj.Result == 'OK') {
                            bootbox.alert({
                                message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                                    '<strong>Eliminado Correctamente.</strong>' +
                                    '</div>'
                            });
                            lista_guias('ninguno', 0);
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

    function lista_guias(filtro, codigo) {
        /*if ($.fn.DataTable.isDataTable('#tabla_ventas')) {
        	$('#tabla_ventas').DataTable().destroy();
        }*/
        $("#tabla_ventas").find('tbody').empty();
        $("#reportar").attr('href', 'https://softluttion.com/sivecsol/core/app/view/generar_pdf.php?filtro=extra&tabla=ventas&extra=' + filtro + '&codigo=' + codigo);

        $("#reportar_excel").attr('href', 'https://softluttion.com/sivecsol/core/app/view/generar_excel.php?filtro=extra&tabla=ventas&extra=' + filtro + '&codigo=' + codigo);

        $.get('core/app/view/venta.php', {
            parAccion: 'lista_guias',
            filtro: filtro,
            codigo: codigo
        }, function(data) {
            var obj = JSON.parse(data);

            var t_adeuda = 0;
            var t_total = 0;
            $.each(obj.Records, function(index, val) {

                var los_botones = '';
                if (val.estado == 1) {
                    //los_botones = `<a href="core/app/view/comprimir.php?factura=` + val.codigo_venta + `" class="badge badge-danger">XML</a>
                    los_botones = `<a href="core/app/view/pdf-guia.php?id=` + val.id + `" target="_blank" class="badge badge-danger">PDF</a>`;
                } else {
                    los_botones = `<button onclick="enviar_sunat_guia('${val.id}');" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-share"></i> EMITIR</button>`;
                }

                $("#tabla_ventas").find('tbody').append(`<tr id="${val.id}_">
                    <td>${parseInt(index + 1)}</td>
                    <td>
                        <span onclick="detalle_venta('${val.id}', '${val.num_guia}');" class="btn btn-sm rounded-pill btn-outline-dark"><i class="glyphicon glyphicon-eye-open"></i></span>
                    </td>
                    <td>${val.num_guia}</td>
                    <td>${val.fecha_emision}</td>
                    <td>${val.fecha_traslado}</td>
                    <td>${val.origen}</td>
                    <td>${val.name}</td>
                    <td>${val.ruc_destinatario}</td>
                    <td>${val.destino}</td>
                    <td>${val.ruc_transportista}</td>
                    <td>${val.placa}</td>
                    <td>${val.ruc_conductor}</td>
                    <td>${val.total_bruto}</td>
                    <td>${val.total_neto}</td>
                    <td>
                        ${los_botones}
                    </td>
                </tr>`);

            });

            /*$("#tabla_ventas").find('tbody').append(`<tr style="font-weight: bold;">
				<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>S/ ` + t_total.toFixed(2) + `</td>
                <td></td>
                <td></td>
                <td>S/ ` + t_adeuda.toFixed(2) + `</td>
                <td></td>
                <td></td>
            </tr>`);*/
        });
    }

    function enviar_sunat_guia(id) {
        var dialog = bootbox.dialog({
            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Enviando data a SUNAT</p>',
            closeButton: false
        });


        // do something in the background

        $.get("guias/examples/guia-remision.php", {
            id: id
        }, function(response) {
            //var obj = JSON.parse(response);
            var obj = response;
            if (obj.Result == "OK") {
                dialog.modal('hide');

                bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>Guía Enviada Correctamente.</strong>' +
                        '</div>'
                });
                lista_guias('ninguno', 0);
            } else {
                dialog.modal('hide');
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>'+obj.Message+'</strong>' +
                        '</div>'
                });

                //alert("ERROR");
            }
        });
    }

    function calcular_deuda(pago_) {
        console.log(pago_);
        var deuda = parseFloat($("#a_deuda").val());
        var pago = parseFloat(pago_);
        var n_deuda = deuda - pago;
        console.log(n_deuda);
        $("#a_deuda").val(n_deuda.toFixed(2));
    }
    $(document).ready(function() {
        $('#fecha_desde').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            altField: "#fecha_nacimiento_hidden",
            altFormat: "yy-mm-dd"
        });
        $('#fecha_hasta').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            altField: "#fecha_nacimiento_hidden",
            altFormat: "yy-mm-dd"
        });
        $("#fecha_p").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            altField: "#fecha_nacimiento_hidden",
            altFormat: "yy-mm-dd"
        });
        $.get('core/app/view/venta.php', {
            parAccion: 'tipos_pago'
        }, function(data) {
            var obj = JSON.parse(data);
            $.each(obj.Records, function(index, val) {
                $("#tipos_pago").append('<option value="' + val.id + '">' + val.name + '</option>');
            });

            $("#tipos_pago").append('<option value="-1">Pendiente de Pago</option>');
        });
        /*$.get('core/app/view/venta.php', {
        	parAccion: 'tipos_entrega'
        }, function(data) {
        	var obj = JSON.parse(data);
        	$.each(obj.Records, function(index, val) {
        		$("#tipos_entrega").append('<option value="' + val.id + '">' + val.name + '</option>');
        	});
        });*/


        $.get('core/app/view/venta.php', {
            parAccion: 'tipos_documento'
        }, function(data) {
            $("#tipos_documento").empty();
            var obj = JSON.parse(data);

            $("#tipos_documento").append('<option value="0">SELECCIONA ...</option>');
            $.each(obj.Records, function(index, val) {
                $("#tipos_documento").append('<option value="' + val.id + '">' + val.tipo_documento + '</option>');
            });
        });

        $.get('core/app/view/order.php', {
            parAccion: 'lista_clientes'
        }, function(data) {
            $("#lista_clientes").empty();
            var obj = JSON.parse(data);
            $.each(obj.Records, function(index, val) {
                $("#combo_cliente").append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        });


        /*$("#tipos_pago").on('change', function() {
        	if ($("#tipos_pago").val() == 0) {
        		lista_guias('ninguno', 0);
        	} else {
        		lista_guias('pago', $("#tipos_pago").val());
        	}
        });*/

        /*$("#combo_cliente").on('change', function() {
        	if ($("#combo_cliente").val() == 0) {
        		lista_guias('ninguno', 0);
        	} else {
        		lista_guias('cliente', $("#combo_cliente").val());
        	}
        });


        $("#tipos_entrega").on('change', function() {
        	if ($("#tipos_entrega").val() == 0) {
        		lista_guias('ninguno', 0);
        	} else {
        		lista_guias('entrega', $("#tipos_entrega").val());
        	}
        });*/


        //lista_guias(filtro, codigo);
        $("#pagado").on('input', function() {
            //$("#a_deuda").val(parseFloat($("#a_deuda").val()) - parseFloat($("#pagado").val()));
            //calcular_deuda($("#pagado").val());
        }).on('change', function() {
            //$("#a_deuda").val(parseFloat($("#a_deuda").val()) - parseFloat($("#pagado").val()));
            calcular_deuda($("#pagado").val());
        });

        lista_guias('ninguno', 0);

        $('#close_editar').on('click', function() {
            //limpiar_formulario();
            $('#popup_editar').fadeOut('slow');
            $('.popup-overlay').fadeOut('slow');
            return false;
            flag = false;
        });
    });

    function guardar_pago_detraccion(codigo_venta) {
        $.post('core/app/view/venta.php?parAccion=guardar_pago_detraccion', {
            codigo_venta: codigo_venta,
            paga: $("#" + codigo_venta).val(),
        }, function(data) {
            var obj = JSON.parse(data);

            if (obj.Result == "OK") {
                lista_guias('ninguno', 0);
            } else {
                alert("ERROR");
            }
        });
    }
</script>