<style>
    /* Context menu */
    .context-menu {
        display: none;
        position: absolute;
        border: 1px solid black;
        border-radius: 3px;
        width: 200px;
        background: white;
    }

    .context-menu ul {
        list-style: none;
        padding: 2px;
    }

    .disabled {
        color: gray;
        pointer-events: none;
        cursor: not-allowed;
    }

    .context-menu ul li {
        padding: 5px 2px;
        margin-bottom: 3px;
        color: #313131;
        font-weight: bold;
    }

    .context-menu ul li:hover {
        cursor: pointer;
        background: #bbb;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: #fff;
        background-color: #007bff;
        font-size: 12px;
    }

    .nav-pills .nav-link {
        border-radius: .25rem;
    }

    .nav-link {
        display: block;
        padding: 5px 10px !important;
    }
</style>
<style type="text/css">
    .uploadArea {
        min-height: 300px;
        height: auto;
        border: 1px dotted #ccc;
        padding: 10px;
        cursor: move;
        margin-bottom: 10px;
        position: relative;
    }

    .select2-container {
        width: 100% !important;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="row" style="padding: 1.5rem;">
                <div class="col-xs-12">
                    <h3><i class="fa fa-folder-open"></i> <span id="nombre_carpeta"></span>
                    </h3>
                </div>
                <div class="col-xs-12 mt-2 form-row h-100" style="margin-top: 1rem; height: 100%;">
                    <div class="col-md-6 form-row">
                        <h4 class="w-100" style="width: 100%;">Cargar Archivo</h4>
                        <div class="col-md-10">
                            <input type="file" name="file1" id="file1" class="form-control rounded-pill h-100">
                        </div>
                        <div class="col-md-2">
                            <button onclick="uploadFile()" class="btn btn-success rounded-pill"><i
                                    class="fa fa-check"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 form-row">
                        <h4 class="w-100" style="width: 100%;">Crear Carpeta</h4>
                        <div class="col-xs-8">
                            <input type="text" class="form-control rounded-pill h-100" id="nombre_carpeta_txt"
                                placeholder="Nombre de la Carpeta">
                        </div>
                        <div class="col-xs-2">
                            <button class="btn btn-success rounded-pill" onclick="crear_carpeta();">Crear</button>
                        </div>

                        <div class="col-xs-2">
                            <a class="btn btn-primary rounded-pill" href="javascript:history.back()">Volver</a>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <progress id="progressBar" class="mt-2" value="0" max="100" style="width:100%;"></progress>
                        <p id="status"></p>
                        <p id="loaded_n_total"></p>
                    </div>

                    <div style="margin-top: 1.5rem; margin-bottom: 1.5rem;" id="" class="col-md-12">
                        <ul class="nav nav-pills mb-3" role="tablist" id="accesos_rapidos">
                        </ul>
                    </div>
                    <h4>Contenido</h4>
                    <div class="w-100 h-100 row" id="lista_contenido"
                        style="background: #313131; color: #f9f9f9; padding: 10px; border-radius: 8px; width: 100%; height: 100%;min-height: 500px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Context-menu -->
    <div class='context-menu'
        style="position: absolute; display: none; background-color: #fff; border: 1px solid #ccc;">
        <ul>
            <li id="1" data-id="ED"><span class=''></span>&nbsp;<span>Editar</span></li>
            <li id="3" data-id="MO"><span class=''></span>&nbsp;<span>Mover</span></li>
            <li id="2" data-id="EL"><span class=''></span>&nbsp;<span>Eliminar</span></li>
        </ul>
    </div>

    <input type='hidden' value='' id='txt_id'>
    <input type='hidden' value='' id='txt_tipo'>
</section>

<div class="modal fade" id="modal_lista_carpetas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Nuevo Alumno</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 id="h5_nueva_ruta" style="color:green;"></h5>
                <input type="hidden" name="id_archivo_mover" id="id_archivo_mover">
                <div class="row" id="div_lista_carpetas"></div>
            </div>
            <div class="modal-footer">
                <div class="form-row text-right">
                    <span class="btn btn-success rounded-pill hidden" onclick="ejecutar_mover();"
                        id="btn_ejecutar_mover">
                        <i class="fa fa-truck"></i> Mover
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function data_carpeta(id_carpeta, flag = 1) {
        $.post("core/app/view/sig.php?accion=data_carpeta", {
            id_carpeta: id_carpeta
        }, function(data) {
            var obj = JSON.parse(data);
            if (flag == 1) {
                $("#nombre_carpeta").text(obj.nombre_carpeta);
            } else {
                $("#h5_nueva_ruta").text("Nuevo: " + obj.nombre_carpeta);
            }
        });
    }

    function crear_carpeta() {
        $.post("core/app/view/sig.php?accion=crear_carpeta", {
            nombre_carpeta: $("#nombre_carpeta_txt").val(),
            id_padre: id_carpeta,
            nombre_carpeta_padre: $("#nombre_carpeta").text()
        }, function(data) {
            var obj = JSON.parse(data);

            if (obj.Result == "OK") {
                lista_contenido(id_carpeta);
            } else {
                alert(obj.Message);
            }
        });
    }

    function lista_contenido(id_carpeta) {
        $("#lista_contenido").empty();
        $.post("core/app/view/sig.php?accion=lista_contenido", {
            id_carpeta: id_carpeta
        }, function(data) {
            var obj = JSON.parse(data);

            $.each(obj, function(index, val) {
                if (val.type == "A") {
                    $("#lista_contenido").append(`<div class="col-md-2">
                        <div class="text-center">
                            <span class="span_archivo" data-id="A" id="${val.id}" ondblclick="ir_archivo(\'${val.archivo}\');"><i class="fa fa-file" style="font-size: 50px; color: #f9f9f9;"></i></span><br>
                            <span style="font-weight: bold;">${val.archivo}</span>
                        </div>
                    </div>`);
                    $(".span_archivo").on('contextmenu', function(event) {
                        event.preventDefault(); // Evita el menú contextual por defecto del navegador
                        var id = this.id;
                        $("#txt_id").val(id);
                        $("#txt_tipo").val($(this).attr("data-id"));

                        // Lógica para deshabilitar una opción específica
                        if ($(this).attr("data-id") == "C") {
                            $('#3').addClass('disabled'); // Deshabilitar la opción 2
                        } else {
                            $('#3').removeClass('disabled'); // Habilitar la opción si no cumple la condición
                        }

                        // Show contextmenu
                        $(".context-menu").css({
                            top: event.pageY + 'px',
                            left: event.pageX + 'px',
                            display: 'block'
                        });
                        return false;
                    });
                } else {
                    $("#lista_contenido").append(`<div class="col-md-2">
                        <div class="text-center">
                            <span class="span_carpeta" data-id="C" id="${val.id}" ondblclick="ir_carpeta(${val.id});"><i class="fa fa-folder" style="font-size: 50px; color: #ffa726;"></i></span><br>
                            <span style="font-weight: bold;">${val.nombre_carpeta}</span>
                        </div>
                    </div>`);
                    $(".span_carpeta").on('contextmenu', function(event) {
                        event.preventDefault(); // Evita el menú contextual por defecto del navegador
                        var id = this.id;
                        $("#txt_id").val(id);
                        $("#txt_tipo").val($(this).attr("data-id"));

                        // Lógica para deshabilitar una opción específica
                        if ($(this).attr("data-id") == "C") {
                            $('#3').addClass('disabled'); // Deshabilitar la opción 2
                        } else {
                            $('#3').removeClass('disabled'); // Habilitar la opción si no cumple la condición
                        }

                        // Show contextmenu
                        $(".context-menu").css({
                            top: event.pageY + 'px',
                            left: event.pageX + 'px',
                            display: 'block'
                        });
                        // disable default context menu
                        return false;
                    });
                }

            });
        });
    }
    document.addEventListener('contextmenu', function(event) {
        event.preventDefault();
        // Lógica para mostrar el menú personalizado
    });

    function ir_archivo(nombre_archivo) {
        var _url_ = "/BIBLIOTECA/" + nombre_archivo;
        //window.open(_url_, '_blank').focus();

        // Obtener la extensión del archivo
        var extension = nombre_archivo.split('.').pop().toLowerCase();

        // Archivos que se pueden visualizar en el navegador
        var visualizables = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt', 'html'];

        // Archivos que deben descargarse
        var descargables = ['xlsx', 'xls', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'rar'];

        if (descargables.includes(extension)) {
            // Forzar descarga
            var link = document.createElement('a');
            link.href = _url_;
            link.download = nombre_archivo;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else if (visualizables.includes(extension)) {
            // Abrir en nueva pestaña para visualizar
            window.open(_url_, '_blank');
        } else {
            // Por defecto, descargar
            var link = document.createElement('a');
            link.href = _url_;
            link.download = nombre_archivo;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    function ir_carpeta(id_carpeta) {
        window.location.href = "?view=biblioteca_contenido&id_carpeta=" + id_carpeta;
    }

    function accesos_rapidos() {
        $("#accesos_rapidos").empty();
        $.post("core/app/view/sig.php?accion=accesos_rapidos", {
            nombre_carpeta: $("#nombre_carpeta").val()
        }, function(data) {
            var obj = JSON.parse(data);

            $.each(obj, function(index, val) {
                $("#accesos_rapidos").append(`
                    <li class="nav-item" style="margin-bottom: 0.5rem; cursor: pointer;">
                        <a class="nav-link active"  aria-selected="true" onclick="ir_carpeta(${val.id});">${val.nombre_carpeta}</a>
                    </li>
                `);
            });
        });
    }
</script>
<script>
    function _(el) {
        return document.getElementById(el);
    }

    function uploadFile() {
        var file = _("file1").files[0];
        var formdata = new FormData();
        formdata.append("id_carpeta", id_carpeta);
        formdata.append("file1", file);
        formdata.append("nombre_carpeta", $("#nombre_carpeta").text());

        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.addEventListener("abort", abortHandler, false);
        ajax.open("POST", "core/app/view/sig.php?accion=guardar_material_permanente");
        ajax.send(formdata);
    }

    function progressHandler(event) {
        _("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
        var percent = (event.loaded / event.total) * 100;
        _("progressBar").value = Math.round(percent);
        //lista_contenido(id_carpeta);
    }

    function completeHandler(event) {
        var obj = JSON.parse(event.target.responseText);
        if (obj.Result == "OK") {
            lista_contenido(id_carpeta);
        } else {
            bootbox.alert({
                message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                    '<strong>Algo ha salido mal.</strong>' +
                    '</div>'
            });
        }
        _("progressBar").value = 0;
    }

    function errorHandler(event) {
        _("status").innerHTML = "Upload Failed";
    }

    function abortHandler(event) {
        _("status").innerHTML = "Upload Aborted";
    }
</script>
<script>
    var id_carpeta = 0;
    $(document).ready(function() {
        id_carpeta = <?php echo $_GET['id_carpeta']; ?>;

        data_carpeta(id_carpeta, 1);
        lista_contenido(id_carpeta);
        accesos_rapidos();

        // Clicked context-menu item
        $('.context-menu li').click(function() {
            if ($(this).attr("data-id") == "EL") {
                eliminar($("#txt_id").val());
            } else if ($(this).attr("data-id") == "ED") {
                editar($("#txt_id").val());
            } else {
                mover($("#txt_id").val());
            }

        });
        // Hide context menu
        $(document).bind('contextmenu click', function() {
            $(".context-menu").hide();
        });
    });

    function lista_accesos_rapidos() {
        console.log("AÑADE");
        $("#btn_ejecutar_mover").addClass("hidden");
        $.post("core/app/view/sig.php?accion=accesos_rapidos", function(response) {
            var obj = JSON.parse(response);
            $("#div_lista_carpetas").empty();
            $.each(obj, function(index, val) {
                $("#div_lista_carpetas").append(`
                    <div style="margin-bottom: 0.5rem; cursor: pointer;">
                        <span class="nav-link active"  aria-selected="true" ondblclick="mostrar_ir_carpeta(${val.id}, 1);">
                            <i class="fa fa-folder" style="font-size: 50px; color: #ffa726;"></i> ${val.nombre_carpeta}
                        </span>
                    </div>
                `);
            });
        });
    }

    function ejecutar_mover() {
        console.log($("#id_archivo_mover").val());
        console.log(a_padres[a_padres.length - 1]);
        $.post("core/app/view/sig.php?accion=ejecutar_mover", {
            id: $("#id_archivo_mover").val(),
            id_padre: a_padres[a_padres.length - 1]
        }, function(response) {
            var obj = JSON.parse(response)
            if (obj.Result == "OK") {
                bootbox.alert({
                    message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>' + obj.Message + '</strong>' +
                        '</div>'
                });
            } else {
                bootbox.alert({
                    message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                        '<strong>' + obj.Message + '</strong>' +
                        '</div>'
                });
            }
            lista_contenido(id_carpeta);
        });
    }

    function mover(_id) {
        $("#id_archivo_mover").val(_id);
        $("#exampleModalLabel").text("Actual: " + $("#nombre_carpeta").text());
        lista_accesos_rapidos();
        $("#modal_lista_carpetas").modal("show");
    }
    var a_padres = [];

    function mostrar_ir_carpeta(_id_carpeta, flag = 1) {
        console.log("REMUEVE");
        $("#btn_ejecutar_mover").removeClass("hidden");
        if (flag == 1) {
            a_padres.push(_id_carpeta);
        } else {
            a_padres.pop(_id_carpeta);
        }
        $.post("core/app/view/sig.php?accion=lista_contenido", {
            id_carpeta: _id_carpeta
        }, function(data) {
            var obj = JSON.parse(data);
            $("#div_lista_carpetas").empty();
            data_carpeta(_id_carpeta, 0);
            if (a_padres[a_padres.length - 2] === undefined) {
                $("#div_lista_carpetas").append(`<div style="margin-bottom: 0.5rem; cursor: pointer;">
                    <span class="nav-link active"  aria-selected="true" ondblclick="lista_accesos_rapidos();">
                        <i class="fa fa-folder" style="font-size: 50px; color: #ffa726;"></i> ..Volver..
                    </span>
                </div>`);
                $("#h5_nueva_ruta").text("");
            } else {
                $("#div_lista_carpetas").append(`<div style="margin-bottom: 0.5rem; cursor: pointer;">
                <span class="nav-link active"  aria-selected="true" ondblclick="mostrar_ir_carpeta(${a_padres[a_padres.length - 2]}, 0);">
                    <i class="fa fa-folder" style="font-size: 50px; color: #ffa726;"></i> ..Volver..
                </span>
            </div>`);
            }
            $.each(obj, function(index, val) {
                if (val.type == "A") {
                    $("#div_lista_carpetas").append(`<div style="margin-bottom: 0.5rem; cursor: pointer;">
                            <span class="nav-link active">
                                <i class="fa fa-file" style="font-size: 50px; color: #666;"></i> ${val.archivo}
                            </span>
                    </div>`);
                } else {
                    $("#div_lista_carpetas").append(`<div style="margin-bottom: 0.5rem; cursor: pointer;">
                        <span class="nav-link active"  aria-selected="true" ondblclick="mostrar_ir_carpeta(${val.id}, 1);">
                            <i class="fa fa-folder" style="font-size: 50px; color: #ffa726;"></i> ${val.nombre_carpeta}
                        </span>
                    </div>`);
                }
            });
        });
    }

    function editar(id) {

        bootbox.prompt({
            title: "Ingresar Nuevo Nombre (Si es un archivo, no olvidar la extensión del archivo)",
            centerVertical: true,
            callback: function(result) {
                if (result == "" || result == '' || result == null) {} else {
                    $.post("core/app/view/sig.php?accion=editar", {
                        nuevo_nombre: result,
                        id: id,
                        ruta: $("#nombre_carpeta").text(),
                        tipo: $("#txt_tipo").val()
                    }, function(response) {
                        lista_contenido(id_carpeta);
                    });
                }
            }
        });
    }

    function eliminar(id) {
        $.ajax({
            url: "core/app/view/sig.php?accion=eliminar",
            type: "POST",
            dataType: "html",
            data: {
                "id": id,
                ruta: $("#nombre_carpeta").text(),
                tipo: $("#txt_tipo").val()
            },
            success: function(data) {
                var obj = JSON.parse(data);
                if (obj.Result == "OK") {
                    bootbox.alert({
                        message: '<div class="alert alert-success fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>' + obj.Message + '</strong>' +
                            '</div>'
                    });
                } else {
                    bootbox.alert({
                        message: '<div class="alert alert-danger fade in" style="margin-top: 5%; margin-bottom: 0;">' +
                            '<strong>' + obj.Message + '</strong>' +
                            '</div>'
                    });
                }
                lista_contenido(id_carpeta);
            }
        });
    }
</script>