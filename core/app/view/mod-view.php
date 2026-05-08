<section class="content-header">
    <div class="row" style="display: flex;">
        <div class="col-md-6">
            <h1>
                MOD
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
                        <div class="col-md-7">
                            <table class="table">
                                <tr>
                                    <td>Cantidad de Operarios</td>
                                    <td><input type="text" class="form-control" id="mod_mod"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Sueldo/Mes</td>
                                    <td><input type="text" class="form-control" id="mod_sueldo_mes"></td>
                                    <td>S/</td>
                                </tr>
                                <tr>
                                    <td>Dia/mes</td>
                                    <td><input type="text" class="form-control" id="mod_dia_mes"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Hrs/dia</td>
                                    <td><input type="text" class="form-control" id="mod_horas_dia"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Factor</td>
                                    <td><input type="text" class="form-control" id="mod_factor"></td>
                                    <td></td>
                                </tr>
                            </table>
                            <div class="row">
                                <div class="col-md-12" style="text-align: center;">
                                    <span class="btn btn-success" onclick="calcular_mod();">Calcular</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <table class="table">
                                <tr>
                                    <td>Sueldo/Mes</td>
                                    <td><input type="text" class="form-control" readonly id="sueldo_mes"></td>
                                    <td>S/</td>
                                </tr>
                                <tr>
                                    <td>Sueldo/Dia</td>
                                    <td><input type="text" class="form-control" readonly id="sueldo_dia"></td>
                                    <td>S/</td>
                                </tr>
                                <tr>
                                    <td>Sueldo/Hr</td>
                                    <td><input type="text" class="form-control" readonly id="sueldo_hora"></td>
                                    <td>S/</td>
                                </tr>
                                <tr>
                                    <td>Sueldo/min</td>
                                    <td><input type="text" class="form-control" readonly id="sueldo_minuto"></td>
                                    <td>S/</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function calcular_mod() {
        if (
            $("#mod_mod").val() == "" ||
            $("#mod_sueldo_mes").val() == "" ||
            $("#mod_dia_mes").val() == "" ||
            $("#mod_horas_dia").val() == "" ||
            $("#mod_factor").val() == ""
        ) {
            bootbox.alert({
                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                    '<strong>Se estan dejando campos en blanco.</strong>' +
                    '</div>'
            });
        } else {
            var sueldo_mes = ($("#mod_sueldo_mes").val() * $("#mod_factor").val()).toFixed(2);
            var sueldo_dia = (sueldo_mes / $("#mod_dia_mes").val()).toFixed(2);
            var sueldo_hora = (sueldo_dia / $("#mod_horas_dia").val()).toFixed(2);
            var sueldo_minuto = (sueldo_hora / 60).toFixed(2);
            $("#sueldo_mes").val(sueldo_mes);
            $("#sueldo_dia").val(sueldo_dia);
            $("#sueldo_hora").val(sueldo_hora);
            $("#sueldo_minuto").val(sueldo_minuto);

            $.post("core/app/view/costos.php?parAccion=guardar_MOD", {
                mod_mod: $("#mod_mod").val(),
                mod_sueldo_mes: $("#mod_sueldo_mes").val(),
                mod_dia_mes: $("#mod_dia_mes").val(),
                mod_horas_dia: $("#mod_horas_dia").val(),
                mod_factor: $("#mod_factor").val(),
                sueldo_mes: sueldo_mes,
                sueldo_dia: sueldo_dia,
                sueldo_hora: sueldo_hora,
                sueldo_minuto: sueldo_minuto,
                id_producto: <?php echo $_GET['id_producto']; ?>
            }, function(response) {
                var obj = JSON.parse(response);
                if (obj.Result == "OK") {
                    bootbox.alert({
                        message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>Registrado Correctamente.</strong>' +
                            '</div>'
                    });
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

    function get_mod() {
        $.post("core/app/view/costos.php?parAccion=get_MOD", {
            id_producto: <?php echo $_GET['id_producto']; ?>
        }, function(response) {
            var obj = JSON.parse(response);
            $("#mod_mod").val(obj.mod_mod);
            $("#mod_sueldo_mes").val(obj.mod_sueldo_mes);
            $("#mod_dia_mes").val(obj.mod_dia_mes);
            $("#mod_horas_dia").val(obj.mod_horas_dia);
            $("#mod_factor").val(obj.mod_factor);
            $("#sueldo_mes").val(obj.sueldo_mes);
            $("#sueldo_dia").val(obj.sueldo_dia);
            $("#sueldo_hora").val(obj.sueldo_hora);
            $("#sueldo_minuto").val(obj.sueldo_minuto);
        });
    }

    function get_data_ingreso() {
        $.post("core/app/view/costos.php?parAccion=get_data_ingreso", {
            id_producto: <?php echo $_GET['id_producto']; ?>
        }, function(response) {
            var obj = JSON.parse(response);
            data_inicial = obj;

            console.log(Object.keys(obj.data_ingreso).length);
            if (Object.keys(obj.data_ingreso).length > 0) {
                $("#mod_mod").val(parseInt(obj.data_ingreso.di_nro_operarios) + " Operarios");
            }
        });
    }
    $(document).ready(function() {
        get_mod();
        get_data_ingreso();
        //di_nro_operarios
    });
</script>