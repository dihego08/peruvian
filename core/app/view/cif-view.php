<section class="content-header">
    <div class="row" style="display: flex;">
        <div class="col-md-6">
            <h1>
                CIF
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
                                    <th>Mensual</th>
                                    <th>Asignación Planta</th>
                                    <th>Dias/Mes</th>
                                    <th>Hrs/Día</th>
                                    <th>Acciones</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" id="cif_concepto">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="cif_mensual">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="cif_asignacion_planta">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="cif_dia_mes">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="cif_horas_dia">
                                        </td>
                                        <td>
                                            <button style="margin-bottom: 0.5rem;" class="btn-xs btn btn-success" id="btn_guardar" onclick="guardar_cif();">Agregar</button>
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
                                    <th>Mensual</th>
                                    <th>Asignación Planta (%)</th>
                                    <th>Asignación Planta (S/)</th>
                                    <th>Día/Mes</th>
                                    <th>Horas/Día</th>
                                    <th>Consumo Día</th>
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
    extraer_cif();

    function guardar_cif() {
        if (
            $("#cif_concepto").val() == "" ||
            $("#cif_mensual").val() == "" ||
            $("#cif_asignacion_planta").val() == "" ||
            $("#cif_dia_mes").val() == "" ||
            $("#cif_horas_dia").val() == ""
        ) {
            bootbox.alert({
                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                    '<strong>Se estan dejando campos en blanco.</strong>' +
                    '</div>'
            });
        } else {
            var asignacion_planta_so = $("#cif_mensual").val() * ($("#cif_asignacion_planta").val() / 100);
            var consumo_dia = asignacion_planta_so / $("#cif_dia_mes").val();

            $.post("core/app/view/costos.php?parAccion=guardar_CIF", {
                cif_concepto: $("#cif_concepto").val(),
                cif_mensual: $("#cif_mensual").val(),
                cif_asignacion_planta: $("#cif_asignacion_planta").val(),
                cif_dia_mes: $("#cif_dia_mes").val(),
                cif_horas_dia: $("#cif_horas_dia").val(),
                asignacion_planta_so: asignacion_planta_so,
                consumo_dia: consumo_dia,
            }, function(response) {
                var obj = JSON.parse(response);
                if (obj.Result == "OK") {
                    bootbox.alert({
                        message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>Registrado Correctamente.</strong>' +
                            '</div>'
                    });
                    limpiar();
                    extraer_cif();
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

    function editar_cif(id) {
        $.post("core/app/view/costos.php?parAccion=editar_CIF", {
            id: id
        }, function(response) {
            var obj = JSON.parse(response);

            $("#cif_concepto").val(obj.cif_concepto);
            $("#cif_mensual").val(obj.cif_mensual);
            $("#cif_asignacion_planta").val(obj.cif_asignacion_planta);
            $("#cif_dia_mes").val(obj.cif_dia_mes);
            $("#cif_horas_dia").val(obj.cif_horas_dia);
            $("#cif_concepto").focus();

            $("#btn_guardar").text("Actualizar");
            $("#btn_guardar").attr("onclick", "actualizar_cif(" + id + ");");
        });
    }

    function actualizar_cif(id) {
        if (
            $("#cif_concepto").val() == "" ||
            $("#cif_mensual").val() == "" ||
            $("#cif_asignacion_planta").val() == "" ||
            $("#cif_dia_mes").val() == "" ||
            $("#cif_horas_dia").val() == ""
        ) {
            bootbox.alert({
                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                    '<strong>Se estan dejando campos en blanco.</strong>' +
                    '</div>'
            });
        } else {
            var asignacion_planta_so = $("#cif_mensual").val() * ($("#cif_asignacion_planta").val() / 100);
            var consumo_dia = asignacion_planta_so / $("#cif_dia_mes").val();

            $.post("core/app/view/costos.php?parAccion=actualizar_CIF", {
                cif_concepto: $("#cif_concepto").val(),
                cif_mensual: $("#cif_mensual").val(),
                cif_asignacion_planta: $("#cif_asignacion_planta").val(),
                cif_dia_mes: $("#cif_dia_mes").val(),
                cif_horas_dia: $("#cif_horas_dia").val(),
                asignacion_planta_so: asignacion_planta_so,
                consumo_dia: consumo_dia,
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
                    extraer_cif();
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

    function eliminar_cif(id) {
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
                    $.post('core/app/view/costos.php?parAccion=eliminar_CIF', {
                        id: id
                    }, function(data) {
                        var obj = JSON.parse(data);
                        if (obj.Result == 'OK') {
                            limpiar();
                            extraer_cif();
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
        $("#cif_concepto").val("");
        $("#cif_mensual").val("");
        $("#cif_asignacion_planta").val("");
        $("#cif_dia_mes").val("");
        $("#cif_horas_dia").val("");
        $("#btn_guardar").text("Agregar");
        $("#btn_guardar").attr("onclick", "guardar_cif();");
    }

    function extraer_cif() {
        $("#lista_moi").find("tbody").empty();
        $.post("core/app/view/costos.php?parAccion=extraer_CIF", function(response) {
            var obj = JSON.parse(response);
            var total_dia = 0;
            $.each(obj, function(index, val) {
                total_dia += parseFloat(val.consumo_dia);
                $("#lista_moi").find("tbody").append(`
                    <tr>
                     	 	 	 	 	 	 
                        <td>${val.cif_concepto}</td>
                        <td>${val.cif_mensual}</td>
                        <td>${val.cif_asignacion_planta }</td>
                        <td>S/ ${val.asignacion_planta_so }</td>
                        <td>${val.cif_dia_mes }</td>
                        <td>${val.cif_horas_dia }</td>
                        <td>S/ ${val.consumo_dia }</td>
                        <td>
                            <span class="btn btn-xs btn-warning" onclick="editar_cif(${val.id});"><i class="fa fa-pencil"></i></span>
                            <span class="btn btn-xs btn-danger" onclick="eliminar_cif(${val.id});"><i class="fa fa-trash"></i></span>
                        </td>
                    </tr>
                `);
            });
            $("#lista_moi").find("tbody").append(`
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="border: solid 1px; font-weight: bold;">TOTAL</td>
                        <td style="border: solid 1px; font-weight: bold;">S/ ${total_dia.toFixed(2) }</td>
                        <td></td>
                    </tr>
                `);
        });
    }
</script>