<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h3><i class="glyphicon glyphicon-stats"></i> Accesos</h3>
            <div class="clearfix"></div>
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-1">
                            <label for="inputUsuario">Usuario</label>
                        </div>
                        <div class="col-xs-4">
                            <select id="cboUsuarios" NAME="cboUsuarios" class="form-control  rounded-pill">
                                <option VALUE="0" selected>--Seleccione--</option>
                            </select>
                        </div>
                        <div class="col-xs-6">
                        </div>
                    </div>
                    <hr>
                    <div id="jstree"></div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-8">
                        </div>
                        <div class="col-xs-4">
                            <button type="button" class="btn btn-success  rounded-pill btn-block" id="botonAceptar">
                                <span class="glyphicon glyphicon-ok"></span> <span id="spanBotonAceptar">Guardar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var objPermiso;
    $(document).ready(function() {
        $('#cboUsuarios').change(function() {
            $('#divMensaje').hide();
            getPermisos();
        });
        $.get('core/app/view/entidades.php', {
            parAccion: 'getListaUsuarios'
        }, function(responseText) {
            var obj = JSON.parse(responseText);
            $.each(obj, function(idx, obj) {
                $('#cboUsuarios').append('<option value="' + obj.value + '" >' + obj.text + '</option>');
            });
        });
    });
    $('#botonAceptar').click(function() {
        /*$("#jstree").bind("select_node.jstree", function(evt, data){
    		console.log("SINENTRA");

             var i, j, r = [], ids=[];
                for(i = 0, j = data.selected.length; i < j; i++) {
                  r.push(data.instance.get_node(data.selected[i]).text);
                }
                alert('Selected: ' + r.join(', '));
           }
		);*/

        $('#divMensaje').hide();
        var arrayPermisos = $("#jstree").jstree('get_checked', true);


        var selectedElmsIds = [];
        //var selectedElms = $('#jstree').jstree("get_selected", true);
        $.each(arrayPermisos, function() {
            selectedElmsIds.push(this.id);
        });
        console.log(selectedElmsIds);


        if (arrayPermisos.length == 0)
            arrayPermisos = [""];
        if ($('#cboUsuarios').val() != "0") {
            $.post('core/app/view/entidades.php?parAccion=funGuardarPermisos', {
                varPostUsuCodigo: $('#cboUsuarios').val(),
                //arrayData: arrayPermisos
                arrayData: selectedElmsIds
            }, function(responseText) {
                var result = JSON.parse(responseText);
                if (result.Result == "OK") {
                    /*$('#divMensaje').html('<div  class="alert alert-success fade in" ><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');
                    $('#divMensaje div').append('<strong>Operación Exitosa!</strong>');
                    $('#divMensaje div').append(' El permiso ha sido guardado.');
                    $('#divMensaje').show();*/

                    bootbox.alert({
                        message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>Guardado correctamente.</strong>' +
                            '</div>'
                    });
                } else {
                    $('#divMensaje').html('<div  class="alert alert-danger fade in" ><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');
                    $('#divMensaje div').append('<strong>Error!</strong>');
                    $('#divMensaje div').append('<label>El permiso no ha sido guardado.</label>');
                    $('#divMensaje').show();
                }
            });
        }

    });

    function getPermisos() {
        if ($('#cboUsuarios').val() != "0") {
            $('#botonAceptar').prop('disabled', false);
            $.get('core/app/view/tree_data_P.php?idUsuario=' + $('#cboUsuarios').val(), function(responseText) {
                $('#jstree').jstree("destroy").empty();
                var obj = JSON.parse(responseText);
                $('#jstree').jstree({
                    core: {
                        data: obj
                    },
                    "state": {
                        "key": "jstree_state"
                    },
                    checkbox: {
                        "whole_node": false,
                        "keep_selected_style": false,
                        "three_state": true,
                        "tie_selection": false
                    },
                    plugins: ['checkbox', "types"],
                    "types": {
                        "1": {
                            "icon": "../imagenes/cube.png"
                        },
                        "2": {
                            "icon": "../imagenes/view.png"
                        }
                    }
                });
            });
        } else {
            $('.check-permiso, #chkTodos, #botonAceptar').prop('disabled', true);
            $('#chkTodos').prop('checked', false);
            $('#chkVisualizar').prop('checked', false);
            $('#chkSubir').prop('checked', false);
            $('#chkDescargar').prop('checked', false);
            $('#chkEliminar').prop('checked', false);
            $('#chkEliminarCarpeta').prop('checked', false);
        }
    }
</script>