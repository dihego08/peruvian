<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Anular Venta</h3>
                </div>
                <div class="box-body" style="margin-bottom: 2rem;">
                    <!-- Alert de mensajes -->
                    <div id="alert-container" style="display: none; margin-bottom: 20px;">
                        <div class="alert alert-dismissible" id="alert-message" role="alert">
                            <button type="button" class="close" onclick="cerrarAlerta()" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong id="alert-title"></strong>
                            <p id="alert-text" style="margin: 5px 0 0 0;"></p>
                        </div>
                    </div>

                    <fieldset>
                        <legend>Seleccionar Motivo</legend>
                        <div class="form-group col-md-12">
                            <label for="cod_motivo">Motivo de Anulación <span style="color: red;">*</span></label>
                            <select class="form-control" name="cod_motivo" id="cod_motivo" required>
                                <option value="-1">SELECCIONA UN MOTIVO...</option>
                                <option value="01">01 - Anulación de la operación</option>
                                <option value="02">02 - Anulación por error en el RUC</option>
                                <option value="03">03 - Corrección por error en la descripción</option>
                                <option value="04">04 - Descuento global</option>
                                <option value="05">05 - Descuento por ítem</option>
                                <option value="06">06 - Devolución total</option>
                                <option value="07">07 - Devolución por ítem</option>
                                <option value="08">08 - Bonificación</option>
                                <option value="09">09 - Disminución en el valor</option>
                            </select>
                            <small class="form-text text-muted">
                                Selecciona el motivo según normativa SUNAT
                            </small>
                        </div>
                    </fieldset>

                    <fieldset style="margin-top: 20px;">
                        <legend>Datos de la Factura</legend>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cod_factura">Número de Factura</label>
                                <input type="text" 
                                       name="cod_factura" 
                                       id="cod_factura" 
                                       readonly 
                                       class="form-control" 
                                       value="<?php echo htmlspecialchars($_GET['factura'] ?? ''); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="correlativo">Correlativo Nota de Crédito <span style="color: red;">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="correlativo" 
                                       readonly
                                       placeholder="Cargando...">
                                <small class="form-text text-muted">
                                    Se genera automáticamente
                                </small>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-group col-md-12" style="margin-top: 20px;">
                        <button class="btn btn-success btn-lg" 
                                id="btn-anular" 
                                onclick="anular();" 
                                style="width: 100%;">
                            <i class="fa fa-ban"></i> Anular Factura
                        </button>
                        <button class="btn btn-default btn-lg" 
                                onclick="window.location.href='?view=fesunat'" 
                                style="width: 100%; margin-top: 10px;">
                            <i class="fa fa-arrow-left"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de confirmación -->
<div class="modal fade" id="modalConfirmar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-exclamation-triangle"></i> Confirmar Anulación
                </h4>
            </div>
            <div class="modal-body">
                <p><strong>¿Está seguro que desea anular esta factura?</strong></p>
                <p>Esta acción generará una nota de crédito en SUNAT y no se puede revertir.</p>
                <div style="background-color: #f5f5f5; padding: 10px; border-radius: 5px; margin-top: 15px;">
                    <p style="margin: 5px 0;"><strong>Factura:</strong> <span id="confirm-factura"></span></p>
                    <p style="margin: 5px 0;"><strong>Motivo:</strong> <span id="confirm-motivo"></span></p>
                    <p style="margin: 5px 0;"><strong>Correlativo NC:</strong> <span id="confirm-correlativo"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmarAnulacion()">
                    <i class="fa fa-check"></i> Sí, Anular Factura
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de progreso -->
<div class="modal fade" id="modalProgreso" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-spinner fa-spin fa-3x fa-fw" style="color: #3c8dbc;"></i>
                <p style="margin-top: 15px;"><strong>Procesando...</strong></p>
                <p id="progreso-texto">Enviando nota de crédito a SUNAT</p>
            </div>
        </div>
    </div>
</div>

<style>
    .alert {
        border-radius: 4px;
        padding: 15px;
    }
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
    }
    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }
    fieldset {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    legend {
        width: auto;
        padding: 0 10px;
        border: none;
        margin-bottom: 0;
        font-size: 16px;
        font-weight: bold;
    }
    .form-control[readonly] {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }
    #btn-anular:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        // Cargar correlativo al iniciar
        get_cor();
        
        // Validar que exista el parámetro factura
        if (!$("#cod_factura").val()) {
            mostrarAlerta('danger', 'Error', 'No se especificó una factura válida');
            $("#btn-anular").prop('disabled', true);
        }
    });

    /**
     * Obtiene el correlativo de la nota de crédito
     */
    function get_cor() {
        $.ajax({
            url: "core/app/view/venta.php?parAccion=get_correlativo_nc",
            type: "POST",
            dataType: "json",
            timeout: 10000,
            beforeSend: function() {
                $("#correlativo").val("Cargando...");
            },
            success: function(response) {
                try {
                    if (response.Records && response.Records[0]) {
                        var nuevoCorrelativo = parseInt(response.Records[0].id) + 1;
                        $("#correlativo").val(nuevoCorrelativo);
                    } else {
                        throw new Error("Formato de respuesta inválido");
                    }
                } catch (e) {
                    mostrarAlerta('danger', 'Error', 'Error al obtener el correlativo: ' + e.message);
                    $("#correlativo").val("ERROR");
                    $("#btn-anular").prop('disabled', true);
                }
            },
            error: function(xhr, status, error) {
                var mensajeError = "Error al obtener correlativo";
                if (status === "timeout") {
                    mensajeError = "Tiempo de espera agotado al obtener correlativo";
                } else if (xhr.responseText) {
                    mensajeError += ": " + xhr.responseText;
                }
                mostrarAlerta('danger', 'Error', mensajeError);
                $("#correlativo").val("ERROR");
                $("#btn-anular").prop('disabled', true);
            }
        });
    }

    /**
     * Valida y muestra modal de confirmación
     */
    function anular() {
        // Validar motivo
        if ($("#cod_motivo").val() == "-1") {
            mostrarAlerta('warning', 'Atención', 'Debes seleccionar un motivo de anulación');
            $("#cod_motivo").focus();
            return false;
        }

        // Validar correlativo
        if (!$("#correlativo").val() || $("#correlativo").val() === "ERROR" || $("#correlativo").val() === "Cargando...") {
            mostrarAlerta('danger', 'Error', 'El correlativo no está disponible. Recarga la página e intenta nuevamente.');
            return false;
        }

        // Validar factura
        if (!$("#cod_factura").val()) {
            mostrarAlerta('danger', 'Error', 'No se ha especificado una factura válida');
            return false;
        }

        // Mostrar datos en el modal de confirmación
        $("#confirm-factura").text($("#cod_factura").val());
        $("#confirm-motivo").text($("#cod_motivo option:selected").text());
        $("#confirm-correlativo").text($("#correlativo").val());
        
        // Abrir modal
        $("#modalConfirmar").modal('show');
    }

    /**
     * Procesa la anulación después de confirmar
     */
    function confirmarAnulacion() {
        // Cerrar modal de confirmación
        $("#modalConfirmar").modal('hide');
        
        // Mostrar modal de progreso
        $("#modalProgreso").modal('show');
        
        // Deshabilitar botón
        $("#btn-anular").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');

        $.ajax({
            url: 'facturador_v3/nota-credito.php',
            type: 'GET',
            data: {
                cod_factura: $("#cod_factura").val(),
                motivo: $("#cod_motivo option:selected").text(),
                cod_motivo: $("#cod_motivo").val(),
                correlativo: $("#correlativo").val()
            },
            dataType: 'json',
            timeout: 30000, // 30 segundos
            success: function(data) {
                $("#modalProgreso").modal('hide');
                
                if (data.Result === "OK") {
                    // Éxito
                    mostrarAlerta('success', '¡Éxito!', 
                        data.Message || 'Nota de crédito generada correctamente. ' +
                        'Código SUNAT: ' + (data.Code || 'N/A') + '<br>' +
                        'Nota de Crédito: ' + (data.NotaCredito || ''));
                    
                    // Redirigir después de 3 segundos
                    setTimeout(function() {
                        window.location.href = "?view=fesunat";
                    }, 3000);
                    
                } else if (data.Result === "RECHAZADO") {
                    // Rechazado por SUNAT
                    mostrarAlerta('danger', 'Rechazado por SUNAT', 
                        data.Message + '<br><strong>Código:</strong> ' + data.Code);
                    $("#btn-anular").prop('disabled', false).html('<i class="fa fa-ban"></i> Anular Factura');
                    
                } else {
                    // Error genérico
                    mostrarAlerta('danger', 'Error', 
                        data.Message || 'Ha ocurrido un error al procesar la nota de crédito');
                    $("#btn-anular").prop('disabled', false).html('<i class="fa fa-ban"></i> Anular Factura');
                }
            },
            error: function(xhr, status, error) {
                $("#modalProgreso").modal('hide');
                $("#btn-anular").prop('disabled', false).html('<i class="fa fa-ban"></i> Anular Factura');
                
                var mensajeError = "Error al comunicarse con el servidor";
                
                if (status === "timeout") {
                    mensajeError = "Tiempo de espera agotado. La operación puede haberse completado, verifica en SUNAT.";
                } else if (xhr.status === 500) {
                    mensajeError = "Error interno del servidor. Contacta al administrador.";
                } else if (xhr.status === 404) {
                    mensajeError = "No se encontró el servicio de anulación.";
                } else if (xhr.responseText) {
                    try {
                        var errorData = JSON.parse(xhr.responseText);
                        mensajeError = errorData.Message || mensajeError;
                    } catch (e) {
                        mensajeError += "<br>Detalles: " + xhr.responseText.substring(0, 200);
                    }
                }
                
                mostrarAlerta('danger', 'Error de Comunicación', mensajeError);
            }
        });
    }

    /**
     * Muestra alertas con diferentes tipos
     * @param {string} tipo - success, danger, warning, info
     * @param {string} titulo - Título de la alerta
     * @param {string} mensaje - Mensaje de la alerta
     */
    function mostrarAlerta(tipo, titulo, mensaje) {
        var alertClass = 'alert-' + tipo;
        var iconClass = '';
        
        switch(tipo) {
            case 'success':
                iconClass = 'fa-check-circle';
                break;
            case 'danger':
                iconClass = 'fa-times-circle';
                break;
            case 'warning':
                iconClass = 'fa-exclamation-triangle';
                break;
            case 'info':
                iconClass = 'fa-info-circle';
                break;
        }
        
        $("#alert-message").removeClass().addClass('alert alert-dismissible ' + alertClass);
        $("#alert-title").html('<i class="fa ' + iconClass + '"></i> ' + titulo);
        $("#alert-text").html(mensaje);
        $("#alert-container").slideDown('fast');
        
        // Scroll hacia arriba para ver la alerta
        $('html, body').animate({
            scrollTop: $("#alert-container").offset().top - 100
        }, 500);
        
        // Auto-cerrar alertas de éxito después de 8 segundos
        if (tipo === 'success') {
            setTimeout(function() {
                cerrarAlerta();
            }, 8000);
        }
    }

    /**
     * Cierra la alerta
     */
    function cerrarAlerta() {
        $("#alert-container").slideUp('fast');
    }

    // Prevenir envío accidental con Enter
    $(document).on('keypress', function(e) {
        if (e.which === 13 && e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            return false;
        }
    });
</script>