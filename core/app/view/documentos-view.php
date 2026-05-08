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
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h3>Gestión Documentaria</h3>
            <div class="row" style="padding: 1.5rem;">
                <h4 class="w-100">Crear Carpeta</h4>
                <div class="col-md-10 col-xs-12" style="margin-bottom: 0.5rem;">
                    <input type="text" class="form-control rounded-pill h-100" id="nombre_carpeta" placeholder="Nombre de la Carpeta">
                </div>
                <div class="col-md-1 col-xs-6" style="margin-bottom: 0.5rem; text-align: center;">
                    <button class="btn btn-success rounded-pill" onclick="crear_carpeta();">Crear</button>
                </div>
                <div class="col-md-1 col-xs-6" style="margin-bottom: 0.5rem; text-align: center;">
                    <a class="btn btn-primary rounded-pill" href="javascript:history.back()">Volver</a>
                </div>
            </div>
            <div class="row">
                <div style="margin-top: 1.5rem; margin-bottom: 1.5rem;" id="" class="col-md-12">
                    <ul class="nav nav-pills mb-3" role="tablist" id="accesos_rapidos">
                    </ul>
                </div>
                <div class="col-md-12 mt-2 h-100" style="margin-top: 1rem; height: 100%;">
                    <h4>Lista de Carpetas</h4>
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <input type="text" class="form-control rounded-pill" id="buscador" placeholder="Buscador..">
                            <span id="n_resultado"></span>
                        </div>
                    </div>
                    <div class="w-100 h-100 row mt-1" id="lista_carpetas" style="background: #313131; color: #f9f9f9; padding: 10px; border-radius: 8px; width: 100%; height: 100%; min-height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Context-menu -->
    <div class='context-menu'>
        <ul>
            <li><span class=''></span>&nbsp;<span>Editar (PROXIMAMENTE) </span></li>
            <li><span class=''></span>&nbsp;<span>Eliminar</span></li>
        </ul>
    </div>

    <input type='hidden' value='' id='txt_id'>
</section>
<script>
    function crear_carpeta() {
        $.post("core/app/view/sig.php?accion=crear_carpeta", {
            nombre_carpeta: $("#nombre_carpeta").val()
        }, function(data) {
            var obj = JSON.parse(data);

            if (obj.Result == "OK") {
                lista_carpetas();
            } else {
                alert(obj.Message);
            }
        });
    }

    function ir_archivo(nombre_archivo) {
        var _url_ = "/sistema/BIBLIOTECA/" + nombre_archivo;
        window.open(_url_, '_blank').focus();
    }

    async function buscarPorNombre(texto, signal) {
        console.log("EN FUNCION " + texto);
        $("#n_resultado").empty();
        $("#lista_carpetas").empty();

        try {
            const response = await fetch("core/app/view/sig.php?accion=lista_contenido_por_nombre", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    texto: texto
                }),
                signal // Pasamos la señal para poder abortar la solicitu
            });

            const data = await response.text();
            const obj = JSON.parse(data);

            $("#n_resultado").text(`${obj.length} Resultados.`);

            const filteredResults = obj.filter(val => val.archivo.toUpperCase().includes(texto.toUpperCase()));
            console.log(filteredResults);

            filteredResults.forEach(val => {
                $("#lista_carpetas").append(`
                <div class="row" ondblclick="ir_archivo('${val.archivo}');">
                    <div class="col-md-10">
                        <a class="btn btn-outline-warning rounded-pill btn-xs" href="?view=biblioteca_contenido&id_carpeta=${val.id_carpeta}" target="_blank">
                            <i class="fa fa-arrow-up"></i>
                        </a>
                        <span style="font-weight: bold;">${val.archivo} ${texto}</span>
                        <span class="small d-block mb-1" style="font-size: 12px;">${val.ruta}</span>
                    </div>
                    <div class="col-md-2">
                        <span>${val.fecha}</span>
                    </div>
                </div>
                <hr class="d-block mt-1 mb-1">
            `);
            });
        } catch (error) {
            console.error("Error al realizar la solicitud:", error);
        }
    }

    function lista_carpetas() {
        $("#lista_carpetas").empty();
        $.post("core/app/view/sig.php?accion=lista_carpetas", {
            nombre_carpeta: $("#nombre_carpeta").val()
        }, function(data) {
            var obj = JSON.parse(data);

            $.each(obj, function(index, val) {
                $("#lista_carpetas").append(`
                    <div class="col-md-2">
                        <div class="text-center">
                            <span class="span_carpeta" id="${val.id}" ondblclick="ir_carpeta(${val.id});"><i class="fa fa-folder" style="font-size: 50px; color: #ffa726;"></i></span><br>
                            <span style="font-weight: bold;">${val.nombre_carpeta}</span>
                        </div>
                    </div>
                `);
            });
            $(".span_carpeta").bind('contextmenu', function(e) {
                var id = this.id;
                $("#txt_id").val(id);

                var top = e.pageY + 5;
                var left = e.pageX;

                // Show contextmenu
                $(".context-menu").toggle(100).css({
                    top: top + "px",
                    left: left + "px"
                });

                // disable default context menu
                return false;
            });
        });
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

    function ir_carpeta(id_carpeta) {
        window.location.href = "?view=biblioteca_contenido&id_carpeta=" + id_carpeta;
    }
</script>
<script>
    function _(el) {
        return document.getElementById(el);
    }

    function uploadFile() {
        var file = _("file1").files[0];
        var formdata = new FormData();
        formdata.append("parAccion", "guardar_material_curso");
        formdata.append("id_curso", $("#curso").val());
        formdata.append("nombre", $("#nombre").val());
        formdata.append("universidad", $("#universidad").val());
        formdata.append("file1", file);


        //universidad: $("#universidad").val()
        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.addEventListener("abort", abortHandler, false);
        ajax.open("POST", "../php/material.php");
        ajax.send(formdata);
    }

    function progressHandler(event) {
        _("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
        var percent = (event.loaded / event.total) * 100;
        _("progressBar").value = Math.round(percent);
        all_materiales();
    }

    function completeHandler(event) {
        all_materiales();
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
    function verificar_archivos() {
        $.post("core/app/view/sig.php?accion=verificar_archivos", function(response) {
            console.log(response);
        });
    }
    $(document).ready(function() {
        verificar_archivos();
        lista_carpetas();
        accesos_rapidos();

        // Clicked context-menu item
        $('.context-menu li').click(function() {
            eliminar($("#txt_id").val());
        });
        // Hide context menu
        $(document).bind('contextmenu click', function() {
            $(".context-menu").hide();
        });

        let abortController;

        $("#buscador").on('input', async function() {
            const texto = $(this).val();

            if (texto.length > 0) {
                // Si hay una búsqueda anterior, cancélala
                if (abortController) {
                    abortController.abort();
                }

                // Crear un nuevo AbortController para la nueva solicitud
                abortController = new AbortController();

                $("#lista_carpetas").empty();
                $("#n_resultado").empty();
                console.log("NO EN FUNCION " + texto);

                try {
                    await buscarPorNombre(texto, abortController.signal);
                } catch (err) {
                    if (err.name === 'AbortError') {
                        console.log("Solicitud abortada");
                    } else {
                        console.error("Error en la búsqueda:", err);
                    }
                }
            } else {
                $("#lista_carpetas").empty();
                $("#n_resultado").empty();
            }
        });
    });

    function eliminar(id) {
        $.ajax({
            url: "core/app/view/sig.php?accion=eliminar",
            type: "POST",
            dataType: "html",
            data: {
                "id": id
            },
            success: function(data) {
                lista_carpetas();
            }
        });
    }
</script>