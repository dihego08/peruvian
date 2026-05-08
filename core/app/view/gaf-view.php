<section class="content-header">
    <div class="row" style="display: flex;">
        <div class="col-md-6">
            <h1>
                Gastos Administrativos y Financieros
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <button onclick="history.back()" class="btn btn-danger">Volver</button>
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <th>Concepto</th>
                                    <th>Monto</th>
                                    <th>Día/Mes</th>
                                    <th>Horas/Día</th>
                                    <th>Acciones</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" id="moi_concepto">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="moi_sueldo_mes">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="moi_dia_mes">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="moi_horas_dia">
                                        </td>
                                        <td>
                                            <button style="margin-bottom: 0.5rem;" class="btn-xs btn btn-success" id="btn_guardar" onclick="guardar_gaf();">Agregar</button>
                                            <button class="btn-xs btn btn-info" onclick="limpiar();">Limpiar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr style="display: block; width: 100%;">
                        <div class="col-md-12">
                            <table class="table" id="lista_moi">
                                <thead>
                                    <th>Concepto</th>
                                    <th>Monto Mes</th>
                                    <th>Día/Mes</th>
                                    <th>Horas/Día</th>
                                    <th>Monto Mes</th>
                                    <th>Monto Día</th>
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
</section>
<script>
    extraer_gaf();

    function guardar_gaf() {
        if (
            $("#moi_concepto").val() == "" ||
            $("#moi_sueldo_mes").val() == "" ||
            $("#moi_n_trabajador").val() == "" ||
            $("#moi_dia_mes").val() == "" ||
            $("#moi_horas_dia").val() == ""
        ) {
            bootbox.alert({
                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                    '<strong>Se estan dejando campos en blanco.</strong>' +
                    '</div>'
            });
        } else {
            var sueldo_mes = $("#moi_sueldo_mes").val();
            var sueldo_dia = sueldo_mes / $("#moi_dia_mes").val();

            $.post("core/app/view/costos.php?parAccion=guardar_gaf", {
                concepto: $("#moi_concepto").val(),
                monto: $("#moi_sueldo_mes").val(),
                dias_mes: $("#moi_dia_mes").val(),
                horas_dia: $("#moi_horas_dia").val(),
                monto_mes: sueldo_mes,
                monto_dia: sueldo_dia,
            }, function(response) {
                var obj = JSON.parse(response);
                if (obj.Result == "OK") {
                    bootbox.alert({
                        message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>Registrado Correctamente.</strong>' +
                            '</div>'
                    });
                    limpiar();
                    extraer_gaf();
                } else {
                    bootbox.alert({
                        message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>Algo ha salido terriblemente mal.</strong>' +
                            '</div>'
                    });
                }
            });
        }
    }

    function actualizar_moi(id) {
        if (
            $("#moi_concepto").val() == "" ||
            $("#moi_sueldo_mes").val() == "" ||
            $("#moi_n_trabajador").val() == "" ||
            $("#moi_dia_mes").val() == "" ||
            $("#moi_horas_dia").val() == ""
        ) {
            bootbox.alert({
                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                    '<strong>Se estan dejando campos en blanco.</strong>' +
                    '</div>'
            });
        } else {
            var sueldo_mes = $("#moi_sueldo_mes").val() * $("#moi_n_trabajador").val();
            var sueldo_dia = sueldo_mes / $("#moi_dia_mes").val();

            $.post("core/app/view/costos.php?parAccion=actualizar_MOI", {
                moi_concepto: $("#moi_concepto").val(),
                moi_sueldo_mes: $("#moi_sueldo_mes").val(),
                moi_n_trabajador: $("#moi_n_trabajador").val(),
                moi_dia_mes: $("#moi_dia_mes").val(),
                moi_horas_dia: $("#moi_horas_dia").val(),
                sueldo_mes: sueldo_mes,
                sueldo_dia: sueldo_dia,
                id: id
            }, function(response) {
                var obj = JSON.parse(response);
                if (obj.Result == "OK") {
                    bootbox.alert({
                        message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>Actualizado Correctamente.</strong>' +
                            '</div>'
                    });
                    limpiar();
                    extraer_gaf();
                } else {
                    bootbox.alert({
                        message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>Algo ha salido terriblemente mal.</strong>' +
                            '</div>'
                    });
                }
            });
        }
    }

    function eliminar_moi(id) {
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
                    $.post('core/app/view/costos.php?parAccion=eliminar_MOI', {
                        id: id
                    }, function(data) {
                        var obj = JSON.parse(data);
                        if (obj.Result == 'OK') {
                            limpiar();
                            extraer_gaf();
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

    function limpiar() {
        $("#moi_concepto").val("");
        $("#moi_sueldo_mes").val("");
        $("#moi_n_trabajador").val("");
        $("#moi_dia_mes").val("");
        $("#moi_horas_dia").val("");
        $("#btn_guardar").text("Agregar");
        $("#btn_guardar").attr("onclick", "guardar_gaf();");
    }

    function editar_moi(id) {
        $.post("core/app/view/costos.php?parAccion=editar_MOI", {
            id: id
        }, function(response) {
            var obj = JSON.parse(response);

            $("#moi_concepto").val(obj.moi_concepto);
            $("#moi_sueldo_mes").val(obj.moi_sueldo_mes);
            $("#moi_n_trabajador").val(obj.moi_n_trabajador);
            $("#moi_dia_mes").val(obj.moi_dia_mes);
            $("#moi_horas_dia").val(obj.moi_horas_dia);
            $("#moi_concepto").focus();

            $("#btn_guardar").text("Actualizar");
            $("#btn_guardar").attr("onclick", "actualizar_moi(" + id + ");");
        });
    }

    function extraer_gaf() {
        $("#lista_moi").find("tbody").empty();
        $.post("core/app/view/costos.php?parAccion=extraer_gaf", function(response) {
            var obj = JSON.parse(response);
            var total_mes = 0;
            var total_dia = 0;
            $.each(obj, function(index, val) {
                total_mes += parseFloat(val.monto_mes);
                total_dia += parseFloat(val.monto_dia);
                $("#lista_moi").find("tbody").append(`
                    <tr>
                        <td>${val.concepto}</td>
                        <td>${val.monto}</td>
                        <td>${val.dias_mes }</td>
                        <td>${val.horas_dia }</td>
                        <td>S/ ${val.monto_mes }</td>
                        <td>S/ ${val.monto_dia }</td>
                        <td>
                            <!--<span class="btn btn-xs btn-warning" onclick="editar_moi(${val.id});"><i class="fa fa-pencil"></i></span>
                            <span class="btn btn-xs btn-danger" onclick="eliminar_moi(${val.id});"><i class="fa fa-trash"></i></span>-->
                        </td>
                    </tr>
                `);
            });
            $("#lista_moi").find("tbody").append(`
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="border: solid 1px; font-weight: bold;">TOTAL</td>
                        <td style="border: solid 1px; font-weight: bold;">S/ ${total_mes.toFixed(2) }</td>
                        <td style="border: solid 1px; font-weight: bold;">S/ ${total_dia.toFixed(2) }</td>
                        <td></td>
                    </tr>
                `);
        });
    }
</script>