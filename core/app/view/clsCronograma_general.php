<?php
class clsCronograma_general
{
    function get_all_colaboradores()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM colaboradores");
        $query->execute();

        $values = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }

        return json_encode($values);
    }
    function editar_cronograma_registro($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM cronograma_registro WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $q = $mbd->prepare("SELECT * FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id");
        $q->bindParam(":id", $result['id']);
        $q->execute();
        $fechas = array();
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
            $fechas[] = $r;
        }
        $result['fechas'] = $fechas;

        echo json_encode($result);
    }
    function eliminar_registro_capacitacion($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM cronograma_registro WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();

            $query = $mbd->prepare("DELETE FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id");
            $query->bindParam(":id", $id);
            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function get_cronograma($anio, $mes = 0)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM cronograma_registro WHERE anio = :anio");
        //$anio = date("Y");
        $query->bindParam(":anio", $anio);
        $query->execute();

        $values = array();
        $mes = $mes - 1;

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

            if ($mes < 0) {
                $q = $mbd->prepare("SELECT * FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id");
            } else {
                //echo "SELECT * FROM cronograma_registro_fecha WHERE id_cronograma_registro = ".$res['id']." AND mes = ".$mes."||";
                $q = $mbd->prepare("SELECT * FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id AND mes = :mes");
                $q->bindParam(":mes", $mes);
            }

            $q->bindParam(":id", $res['id']);
            $q->execute();
            $fechas = array();
            while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
                $fechas[] = $r;
            }
            if (empty($fechas)) {
            } else {
                $res['fechas'] = $fechas;
                $values[] = $res;
            }
        }

        return json_encode($values);
    }
    function get_asistencias()
    {
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        include("env.php");
        $query = $mbd->prepare("SELECT a.*, c.curso FROM cronograma_registro as c, asistencias_cursos as a WHERE c.id = a.id_curso");
        $query->execute();

        $values = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $res['fecha_registro'] = iconv('ISO-8859-2', 'UTF-8', strftime("%d-%B-%Y", strtotime($res['fecha_registro']))); //date("d-B-Y", strtotime($res['fecha_registro']));
            $values[] = $res;
        }

        return json_encode($values);
    }
    function editar_asistencia($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM asistencias_cursos WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function get_actas_reunion()
    {
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM actas_reunion ORDER BY id DESC");
        $query->execute();

        $values = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $res['fecha_registro'] = iconv('ISO-8859-2', 'UTF-8', strftime("%d-%B-%Y", strtotime($res['fecha_registro'])));
            $values[] = $res;
        }

        return json_encode($values);
    }
    function eliminar_asistencia($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM asistencias_cursos WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function delete_elemento_from_form($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM cronograma_registro_fecha WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function eliminar_acta_reunion($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM actas_reunion WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function guardar_asistencia($POST)
    {
        include("env.php");

        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO asistencias_cursos(id_curso, foto, fecha_registro, asistentes, horas_capacitacion, capacitador) VALUES(:id_curso, :foto, :fecha, :asistentes, :horas_capacitacion, :capacitador);");
            $POST['fecha'] = date("Y-m-d", strtotime($POST['fecha']));
            $query->bindParam(":id_curso", $POST['id_curso']);
            $query->bindParam(":foto", $POST['foto']);
            $query->bindParam(":fecha", $POST['fecha']);
            $query->bindParam(":asistentes", $POST['asistentes']);
            $query->bindParam(":horas_capacitacion", $POST['horas_capacitacion']);
            $query->bindParam(":capacitador", $POST['capacitador']);
            $query->execute();

            $lid = $mbd->lastInsertId();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK',
                'LID' => $lid
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar_asistencia($POST)
    {
        include("env.php");

        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE asistencias_cursos SET id_curso = :id_curso, foto = :foto, fecha_registro = :fecha, asistentes = :asistentes, horas_capacitacion = :horas_capacitacion, capacitador = :capacitador WHERE id = :id");
            $POST['fecha'] = date("Y-m-d", strtotime($POST['fecha']));
            $query->bindParam(":id_curso", $POST['id_curso']);
            $query->bindParam(":foto", $POST['foto']);
            $query->bindParam(":fecha", $POST['fecha']);
            $query->bindParam(":asistentes", $POST['asistentes']);
            $query->bindParam(":horas_capacitacion", $POST['horas_capacitacion']);
            $query->bindParam(":capacitador", $POST['capacitador']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function guardar_acta($POST)
    {
        include("env.php");

        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO actas_reunion(orden_dia, acuerdos, asistentes, fecha_registro, duracion, convoca) VALUES (:orden_dia, :acuerdos, :asistentes, :fecha_registro, :duracion, :convoca)");
            $POST['fecha_registro'] = date("Y-m-d", strtotime($POST['fecha_registro']));
            $query->bindParam(":orden_dia", $POST['orden_dia']);
            $query->bindParam(":acuerdos", $POST['acuerdos']);
            $query->bindParam(":fecha_registro", $POST['fecha_registro']);
            $query->bindParam(":asistentes", $POST['asistentes']);
            $query->bindParam(":duracion", $POST['duracion']);
            $query->bindParam(":convoca", $POST['convoca']);
            $query->execute();

            $lid = $mbd->lastInsertId();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK',
                'LID' => $lid
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar_registro_capacitacion($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE cronograma_registro SET responsable = :responsable, curso = :curso, areas = :areas, anio = :anio WHERE id = :id;");
            $POST['areas'] = nl2br($POST['areas']);
            $query->bindParam(":curso", $POST['curso']);
            $query->bindParam(":areas", $POST['areas']);
            $query->bindParam(":anio", $POST['anio']);
            $query->bindParam(":responsable", $POST['responsable']);
            $query->bindParam(":id", $POST['id']);

            $query->execute();

            $query = $mbd->prepare("DELETE FROM cronograma_registro_fecha WHERE id_cronograma_registro = :id");
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            for ($i = 0; $i < count($POST['meses']); $i++) {
                $q = $mbd->prepare("INSERT INTO cronograma_registro_fecha(id_cronograma_registro, dia, mes, estado) VALUES (:id_cronograma_registro, :dia, :mes, 0)");
                $q->bindParam(":id_cronograma_registro", $POST['id']);
                $q->bindParam(":dia", $POST['dias'][$i]);
                $q->bindParam(":mes", $POST['meses'][$i]);
                $q->execute();
            }

            $lid = $mbd->lastInsertId();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK',
                'LID' => $lid
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function guardar_registro_capacitacion($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO cronograma_registro(curso, areas, anio, responsable) VALUES(:curso, :areas, :anio, :responsable);");
            $POST['areas'] = nl2br($POST['areas']);
            $query->bindParam(":curso", $POST['curso']);
            $query->bindParam(":areas", $POST['areas']);
            $query->bindParam(":anio", $POST['anio']);
            $query->bindParam(":responsable", $POST['responsable']);
            $query->execute();

            $lid = $mbd->lastInsertId();

            for ($i = 0; $i < count($POST['meses']); $i++) {
                $q = $mbd->prepare("INSERT INTO cronograma_registro_fecha(id_cronograma_registro, dia, mes, estado) VALUES (:id_cronograma_registro, :dia, :mes, 0)");
                $q->bindParam(":id_cronograma_registro", $lid);
                $q->bindParam(":dia", $POST['dias'][$i]);
                $q->bindParam(":mes", $POST['meses'][$i]);
                $q->execute();
            }

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK',
                'LID' => $lid
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function editar($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM colaboradores WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function guardar($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO colaboradores(dni, nombres, apellido_paterno, apellido_materno, foto, fecha_nacimiento, lugar_nacimiento, id_estado_civil, celular, correo, brevette, direccion, telefono_emergencia, id_sistema_pension, id_entidad_pension, 
                codigo, asegurado, proceso, sueldo, genero, estado_laboral, fecha_ingreso, fecha_salida, id_cargo, linea, estado) 
                VALUES(:dni, :nombres, :apellido_paterno, :apellido_materno, :foto, :fecha_nacimiento, :lugar_nacimiento, :id_estado_civil, :celular, :correo, :brevette, :direccion, :telefono_emergencia, :id_sistema_pension, :id_entidad_pension, 
                :codigo, :asegurado, :proceso, :sueldo, :genero, :estado_laboral, :fecha_ingreso, :fecha_salida, :id_cargo, :linea, :estado);");
            $query->bindParam(":dni", $POST['dni']);
            $query->bindParam(":nombres", $POST['nombres']);
            $query->bindParam(":apellido_paterno", $POST['apellido_paterno']);
            $query->bindParam(":apellido_materno", $POST['apellido_materno']);
            $query->bindParam(":foto", $POST['foto']);
            $fecha_nacimiento = date("Y-m-d", strtotime($POST['fecha_nacimiento']));
            $query->bindParam(":fecha_nacimiento", $fecha_nacimiento);
            $query->bindParam(":lugar_nacimiento", $POST['lugar_nacimiento']);
            $query->bindParam(":id_estado_civil", $POST['id_estado_civil']);
            $query->bindParam(":celular", $POST['celular']);
            $query->bindParam(":correo", $POST['correo']);
            $query->bindParam(":brevette", $POST['brevette']);
            $query->bindParam(":direccion", $POST['direccion']);
            $query->bindParam(":telefono_emergencia", $POST['telefono_emergencia']);
            $query->bindParam(":id_sistema_pension", $POST['id_sistema_pension']);
            $query->bindParam(":id_entidad_pension", $POST['id_entidad_pension']);
            $query->bindParam(":codigo", $POST['codigo']);
            $query->bindParam(":asegurado", $POST['asegurado']);
            $query->bindParam(":proceso", $POST['proceso']);
            $query->bindParam(":sueldo", $POST['sueldo']);
            $query->bindParam(":genero", $POST['genero']);
            $query->bindParam(":estado_laboral", $POST['estado_laboral']);
            $fecha_ingreso = date("Y-m-d", strtotime($POST['fecha_ingreso']));
            $query->bindParam(":fecha_ingreso", $fecha_ingreso);
            $fecha_salida = date("Y-m-d", strtotime($POST['fecha_salida']));
            $query->bindParam(":fecha_salida", $fecha_salida);
            $query->bindParam(":id_cargo", $POST['id_cargo']);
            $query->bindParam(":linea", $POST['linea']);
            $query->bindParam(":estado", $POST['estado']);
            $query->execute();

            $lid = $mbd->lastInsertId();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK',
                'LID' => $lid
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE colaboradores 
                	SET dni = :dni, 
                		nombres = :nombres, 
                		apellido_paterno = :apellido_paterno,
                		apellido_materno = :apellido_materno,
                		foto = :foto,
                		fecha_nacimiento = :fecha_nacimiento,
                		lugar_nacimiento = :lugar_nacimiento,
                		id_estado_civil = :id_estado_civil,
                		celular = :celular,
                		correo = :correo,
                		brevette = :brevette,
                		direccion = :direccion,
                		telefono_emergencia = :telefono_emergencia,
                		id_sistema_pension = :id_sistema_pension,
                		id_entidad_pension = :id_entidad_pension,
                		codigo = :codigo,
                		asegurado = :asegurado,
                		proceso = :proceso,
                		sueldo = :sueldo,
                		genero = :genero,
                		estado_laboral = :estado_laboral,
                		fecha_ingreso = :fecha_ingreso,
                		fecha_salida = :fecha_salida,
                		id_cargo = :id_cargo,
                		linea = :linea,
                        estado = :estado
            		WHERE id = :id");
            $query->bindParam(":dni", $POST['dni']);
            $query->bindParam(":nombres", $POST['nombres']);
            $query->bindParam(":apellido_paterno", $POST['apellido_paterno']);
            $query->bindParam(":apellido_materno", $POST['apellido_materno']);
            $query->bindParam(":foto", $POST['foto']);
            $fecha_nacimiento = date("Y-m-d", strtotime($POST['fecha_nacimiento']));

            //echo $POST['fecha_nacimiento'];

            $query->bindParam(":fecha_nacimiento", $fecha_nacimiento);
            $query->bindParam(":lugar_nacimiento", $POST['lugar_nacimiento']);
            $query->bindParam(":id_estado_civil", $POST['id_estado_civil']);
            $query->bindParam(":celular", $POST['celular']);
            $query->bindParam(":correo", $POST['correo']);
            $query->bindParam(":brevette", $POST['brevette']);
            $query->bindParam(":direccion", $POST['direccion']);
            $query->bindParam(":telefono_emergencia", $POST['telefono_emergencia']);
            $query->bindParam(":id_sistema_pension", $POST['id_sistema_pension']);
            $query->bindParam(":id_entidad_pension", $POST['id_entidad_pension']);
            $query->bindParam(":codigo", $POST['codigo']);
            $query->bindParam(":asegurado", $POST['asegurado']);
            $query->bindParam(":proceso", $POST['proceso']);
            $query->bindParam(":sueldo", $POST['sueldo']);
            $query->bindParam(":genero", $POST['genero']);
            $query->bindParam(":estado_laboral", $POST['estado_laboral']);
            $fecha_ingreso = date("Y-m-d", strtotime($POST['fecha_ingreso']));
            $query->bindParam(":fecha_ingreso", $fecha_ingreso);
            $fecha_salida = date("Y-m-d", strtotime($POST['fecha_salida']));
            $query->bindParam(":fecha_salida", $fecha_salida);
            $query->bindParam(":id_cargo", $POST['id_cargo']);
            $query->bindParam(":linea", $POST['linea']);
            $query->bindParam(":estado", $POST['estado']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function llenar_estado_civil()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM estado_civil");
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
    function llenar_sistema_pension()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM sistema_pensiones");
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
    function get_estado_civil($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM estado_civil WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function get_sistema_pension($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM sistema_pensiones WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function get_entidad_pension($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM afps WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function llenar_entidades_pension($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM afps WHERE id_sistema_pensiones = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
    function eliminar($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM colaboradores WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function get_experiencia($id)
    {
        include("env.php");

        $query = $mbd->prepare("SELECT * FROM experiencia_laboral WHERE id_colaborador = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }

        return json_encode($values);
    }
    function guardar_experiencia($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO experiencia_laboral(empresa, cargo, responsabilidades, fecha_ingreso, fecha_termino, tiempo_servicio, id_colaborador, motivo_cese) VALUES (:empresa, :cargo, :responsabilidades, :fecha_ingreso, :fecha_termino, :tiempo_servicio, :id_colaborador, :motivo_cese);");
            $query->bindParam(":empresa", $POST['empresa']);
            $query->bindParam(":cargo", $POST['cargo']);
            $query->bindParam(":responsabilidades", $POST['responsabilidades']);
            $query->bindParam(":fecha_ingreso", $POST['fecha_ingreso']);
            $query->bindParam(":fecha_termino", $POST['fecha_termino']);
            $query->bindParam(":tiempo_servicio", $POST['tiempo_servicio']);
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":motivo_cese", $POST['motivo_cese']);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function get_familiares($id)
    {
        include("env.php");

        $query = $mbd->prepare("SELECT * FROM familiares WHERE id_colaborador = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }

        return json_encode($values);
    }
    function guardar_familiar($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO familiares(dni, nombre, apellidos, fecha_nacimiento, lugar_nacimiento, telefono, parentesco, id_colaborador) VALUES (:dni, :nombre, :apellidos, :fecha_nacimiento, :lugar_nacimiento, :telefono, :parentesco, :id_colaborador);");
            $query->bindParam(":dni", $POST['dni']);
            $query->bindParam(":nombre", $POST['nombre']);
            $query->bindParam(":apellidos", $POST['apellidos']);
            $query->bindParam(":fecha_nacimiento", $POST['fecha_nacimiento']);
            $query->bindParam(":lugar_nacimiento", $POST['lugar_nacimiento']);
            $query->bindParam(":telefono", $POST['telefono']);
            $query->bindParam(":parentesco", $POST['parentesco']);
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function get_habilidad($id)
    {
        include("env.php");

        $query = $mbd->prepare("SELECT * FROM habilidades WHERE id_colaborador = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }

        return json_encode($values);
    }
    function guardar_habilidad($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO habilidades(elemento, habilidad, id_colaborador, tipo) VALUES (:elemento, :habilidad, :id_colaborador, :tipo);");
            $query->bindParam(":elemento", $POST['elemento']);
            $query->bindParam(":habilidad", nl2br($POST['habilidad']));
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":tipo", $POST['tipo']);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function cargar_archivo($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE formacion SET archivo = :archivo WHERE id = :id;");

            $query->bindParam(":id", $POST['id']);

            $query->bindParam(":archivo", $POST['archivo']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function cargar_archivo_experiencia($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE experiencia_laboral SET archivo = :archivo WHERE id = :id;");

            $query->bindParam(":id", $POST['id']);

            $query->bindParam(":archivo", $POST['archivo']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function cargar_archivo_vacaciones($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE vacaciones SET archivo = :archivo WHERE id = :id;");

            $query->bindParam(":id", $POST['id']);

            $query->bindParam(":archivo", $POST['archivo']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function cargar_archivo_capacitaciones($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE capacitaciones SET archivo = :archivo WHERE id = :id;");

            $query->bindParam(":id", $POST['id']);

            $query->bindParam(":archivo", $POST['archivo']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function cargar_archivo_certificado_medico($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE colaboradores SET archivo = :archivo WHERE id = :id;");

            $query->bindParam(":id", $POST['id']);

            $query->bindParam(":archivo", $POST['archivo']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function cargar_contrato($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE colaboradores SET contrato = :archivo WHERE id = :id;");

            $query->bindParam(":id", $POST['id']);

            $query->bindParam(":archivo", $POST['archivo']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function cargar_sst($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE colaboradores SET sst = :archivo WHERE id = :id;");

            $query->bindParam(":id", $POST['id']);

            $query->bindParam(":archivo", $POST['archivo']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function cargar_competencias($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE colaboradores SET competencias = :archivo WHERE id = :id;");

            $query->bindParam(":id", $POST['id']);

            $query->bindParam(":archivo", $POST['archivo']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function get_formacion($id)
    {
        include("env.php");

        $query = $mbd->prepare("SELECT * FROM formacion WHERE id_colaborador = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }

        return json_encode($values);
    }
    function guardar_formacion($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO formacion(formacion, lugar, id_colaborador) VALUES (:formacion, :lugar, :id_colaborador);");
            $query->bindParam(":formacion", $POST['formacion']);
            $query->bindParam(":lugar", $POST['lugar']);
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function get_all_areas()
    {
        include("env.php");

        $query = $mbd->prepare("SELECT * FROM areas");
        $query->execute();

        $values = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }

        return json_encode($values);
    }
    function get_puestos()
    {
        include("env.php");

        $query = $mbd->prepare("SELECT p.*, a.area FROM puestos as p, areas as a WHERE a.id = p.id_area");
        $query->execute();

        $values = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }

        return json_encode($values);
    }
    function guardar_puesto($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO puestos(id_area, puesto) VALUES (:id_area, :puesto);");
            $query->bindParam(":id_area", $POST['id_area']);
            $query->bindParam(":puesto", $POST['puesto']);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function guardar_area($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO areas(area) VALUES (:area);");
            $query->bindParam(":area", $POST['area']);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function eliminar_area($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM areas WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar_area($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE areas SET area = :area WHERE id = :id;");

            $query->bindParam(":id", $POST['id']);

            $query->bindParam(":area", $POST['area']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function editar_area($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM areas WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function get_perfil_puesto($id)
    {
        include("env.php");

        $query = $mbd->prepare("SELECT pp.*, p.puesto, a.area FROM perfil_puesto as pp, puestos as p, areas as a WHERE pp.id_puesto = :id AND pp.id_puesto = p.id AND a.id = p.id_area");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function guardar_perfil($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $contar = $mbd->prepare("SELECT count(*) as cant FROM perfil_puesto WHERE id_puesto = :id_puesto");
            $contar->bindParam(":id_puesto", $POST['id_puesto']);
            $contar->execute();

            $cant = $contar->fetch(PDO::FETCH_ASSOC);

            if ($cant['cant'] > 0) {
                $query = $mbd->prepare("UPDATE perfil_puesto SET reporta_a = :reporta_a, supervisa_a = :supervisa_a, interactua_con = :interactua_con, reemplazado_por = :reemplazado_por, objetivo = :objetivo, funciones = :funciones, responsabilidades = :responsabilidades, equipo_utilizado = :equipo_utilizado, lugar_trabajo = :lugar_trabajo, requerimientos_fisicos = :requerimientos_fisicos, formacion_basica = :formacion_basica, conocimientos_especificos = :conocimientos_especificos, experiencia_requerida = :experiencia_requerida, idioma = :idioma, competencia_especifica = :competencia_especifica, elaborado_por = :elaborado_por, aprobado_por = :aprobado_por, fecha_aprobacion = :fecha_aprobacion WHERE id_puesto = :id_puesto;");

                $query->bindParam(":id_puesto", $POST['id_puesto']);

                $query->bindParam(":reporta_a", $POST['reporta_a']);
                $query->bindParam(":reemplazado_por", $POST['reemplazado_por']);
                $query->bindParam(":objetivo", $POST['objetivo']);
                $query->bindParam(":lugar_trabajo", $POST['lugar_trabajo']);
                $query->bindParam(":requerimientos_fisicos", $POST['requerimientos_fisicos']);
                $query->bindParam(":formacion_basica", $POST['formacion_basica']);
                $query->bindParam(":experiencia_requerida", $POST['experiencia_requerida']);
                $query->bindParam(":idioma", $POST['idioma']);
                $query->bindParam(":competencia_especifica", $POST['competencia_especifica']);
                $query->bindParam(":elaborado_por", $POST['elaborado_por']);
                $query->bindParam(":aprobado_por", $POST['aprobado_por']);
                $query->bindParam(":fecha_aprobacion", $POST['fecha_aprobacion']);
                $query->bindParam(":supervisa_a", $POST['supervisa_a']);
                $query->bindParam(":interactua_con", $POST['interactua_con']);
                $query->bindParam(":funciones", $POST['funciones']);
                $query->bindParam(":responsabilidades", $POST['responsabilidades']);
                $query->bindParam(":equipo_utilizado", $POST['equipo_utilizado']);
                $query->bindParam(":conocimientos_especificos", $POST['conocimientos_especificos']);
                $query->bindParam(":competencia_especifica", $POST['competencia_especifica']);


                $query->execute();
            } else {
                $query = $mbd->prepare("INSERT INTO perfil_puesto(id_puesto, reporta_a, supervisa_a, interactua_con, reemplazado_por, objetivo, funciones, responsabilidades, equipo_utilizado, lugar_trabajo, requerimientos_fisicos, formacion_basica, conocimientos_especificos, experiencia_requerida, idioma, competencia_especifica, elaborado_por, aprobado_por, fecha_aprobacion) VALUES (:id_puesto, :reporta_a, :supervisa_a, :interactua_con, :reemplazado_por, :objetivo, :funciones, :responsabilidades, :equipo_utilizado, :lugar_trabajo, :requerimientos_fisicos, :formacion_basica, :conocimientos_especificos, :experiencia_requerida, :idioma, :competencia_especifica, :elaborado_por, :aprobado_por, :fecha_aprobacion);");

                $query->bindParam(":id_puesto", $POST['id_puesto']);

                $query->bindParam(":reporta_a", $POST['reporta_a']);
                $query->bindParam(":reemplazado_por", $POST['reemplazado_por']);
                $query->bindParam(":objetivo", $POST['objetivo']);
                $query->bindParam(":lugar_trabajo", $POST['lugar_trabajo']);
                $query->bindParam(":requerimientos_fisicos", $POST['requerimientos_fisicos']);
                $query->bindParam(":formacion_basica", $POST['formacion_basica']);
                $query->bindParam(":experiencia_requerida", $POST['experiencia_requerida']);
                $query->bindParam(":idioma", $POST['idioma']);
                $query->bindParam(":competencia_especifica", $POST['competencia_especifica']);
                $query->bindParam(":elaborado_por", $POST['elaborado_por']);
                $query->bindParam(":aprobado_por", $POST['aprobado_por']);
                $query->bindParam(":fecha_aprobacion", $POST['fecha_aprobacion']);
                $query->bindParam(":supervisa_a", $POST['supervisa_a']);
                $query->bindParam(":interactua_con", $POST['interactua_con']);
                $query->bindParam(":funciones", $POST['funciones']);
                $query->bindParam(":responsabilidades", $POST['responsabilidades']);
                $query->bindParam(":equipo_utilizado", $POST['equipo_utilizado']);
                $query->bindParam(":conocimientos_especificos", $POST['conocimientos_especificos']);
                $query->bindParam(":competencia_especifica", $POST['competencia_especifica']);


                $query->execute();
            }


            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function buscar_dni($POST)
    {
        include("env.php");
        if (!is_null($POST['dni']) && !empty($POST['dni'])) {
            $query = $mbd->prepare("SELECT * FROM colaboradores WHERE dni = :dni");
            $query->bindParam(":dni", $POST['dni']);
            $query->execute();
        } elseif (!is_null($POST['nombres']) && !empty($POST['nombres'])) {
            $query = $mbd->prepare("SELECT * FROM colaboradores WHERE nombres LIKE '%" . $POST['nombres'] . "%'");
            //$query->bindParam(":dni", $POST['dni']);
            $query->execute();
        } elseif (!is_null($POST['apellido']) && !empty($POST['apellido'])) {
            $query = $mbd->prepare("SELECT * FROM colaboradores WHERE apellido_paterno LIKE '%" . $POST['apellido'] . "%'");
            //$query->bindParam(":dni", $POST['dni']);
            $query->execute();
        }


        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function get_total()
    {
        include("env.php");

        $query = $mbd->prepare("SELECT COUNT(*) as cant FROM colaboradores");
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);



        return json_encode(array("total" => $row['cant']));
    }
    function siguiente($current)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM colaboradores LIMIT " . $current . ", 1 ");
        //$query->bindParam(":current", $current);
        $query->execute();
        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function siguiente_especifico($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM colaboradores WHERE id = " . $id);
        $query->execute();
        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function eliminar_puesto($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM puestos WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar_puesto($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE puestos SET puesto = :puesto, id_area = :id_area WHERE id = :id;");
            $query->bindParam(":id", $POST['id']);
            $query->bindParam(":id_area", $POST['id_area']);
            $query->bindParam(":puesto", $POST['puesto']);

            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function editar_puesto($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM puestos WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function eliminar_familiar($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM familiares WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar_familiar($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE familiares SET dni = :dni, nombre = :nombre, apellidos = :apellidos, fecha_nacimiento = :fecha_nacimiento, lugar_nacimiento = :lugar_nacimiento, telefono = :telefono, parentesco = :parentesco, id_colaborador = :id_colaborador WHERE id = :id;");
            $query->bindParam(":dni", $POST['dni']);
            $query->bindParam(":nombre", $POST['nombre']);
            $query->bindParam(":apellidos", $POST['apellidos']);
            $query->bindParam(":fecha_nacimiento", $POST['fecha_nacimiento']);
            $query->bindParam(":lugar_nacimiento", $POST['lugar_nacimiento']);
            $query->bindParam(":telefono", $POST['telefono']);
            $query->bindParam(":parentesco", $POST['parentesco']);
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function editar_familiar($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM familiares WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function guardar_capacitacion($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO capacitaciones(id_colaborador, curso, horas, fecha, capacitador, lugar) VALUES(:id_colaborador, :curso, :horas, :fecha, :capacitador, :lugar);");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":curso", $POST['curso']);
            $query->bindParam(":horas", $POST['horas']);
            $query->bindParam(":fecha", $POST['fecha']);
            $query->bindParam(":capacitador", $POST['capacitador']);
            $query->bindParam(":lugar", $POST['lugar']);
            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function guardar_vacaciones($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO vacaciones(id_colaborador, periodo, fecha_salida, fecha_retorno, dias, observaciones) VALUES (:id_colaborador, :periodo, :fecha_salida, :fecha_retorno, :dias, :observaciones);");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":periodo", $POST['periodo']);
            $query->bindParam(":fecha_salida", $POST['fecha_salida']);
            $query->bindParam(":fecha_retorno", $POST['fecha_retorno']);
            $query->bindParam(":dias", $POST['dias']);
            $query->bindParam(":observaciones", $POST['observaciones']);
            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function get_capacitacion($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM capacitaciones WHERE id_colaborador = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
    public function get_vacaciones($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM vacaciones WHERE id_colaborador = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
    public function editar_vacaciones($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM vacaciones WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function eliminar_capacitacion($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM capacitaciones WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function eliminar_vacaciones($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM vacaciones WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar_capacitacion($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE capacitaciones SET id_colaborador = :id_colaborador, curso = :curso, horas = :horas, fecha = :fecha, capacitador = :capacitador, lugar = :lugar WHERE id = :id;");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":curso", $POST['curso']);
            $query->bindParam(":horas", $POST['horas']);
            $query->bindParam(":fecha", $POST['fecha']);
            $query->bindParam(":capacitador", $POST['capacitador']);
            $query->bindParam(":lugar", $POST['lugar']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function actualizar_vacaciones($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE vacaciones SET id_colaborador = :id_colaborador, periodo = :periodo, fecha_salida = :fecha_salida, fecha_retorno = :fecha_retorno, dias = :dias, observaciones = :observaciones WHERE id = :id;");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":periodo", $POST['periodo']);
            $query->bindParam(":fecha_salida", $POST['fecha_salida']);
            $query->bindParam(":fecha_retorno", $POST['fecha_retorno']);
            $query->bindParam(":dias", $POST['dias']);
            $query->bindParam(":observaciones", $POST['observaciones']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function editar_capacitacion($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM capacitaciones WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function eliminar_habilidad($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM habilidades WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar_habilidad($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE habilidades SET elemento = :elemento, habilidad = :habilidad, id_colaborador = :id_colaborador, tipo = :tipo WHERE id = :id;");
            $query->bindParam(":elemento", $POST['elemento']);
            $query->bindParam(":habilidad", $POST['habilidad']);
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":tipo", $POST['tipo']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function editar_habilidad($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM habilidades WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function eliminar_experiencia($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM experiencia_laboral WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar_experiencia($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE experiencia_laboral SET empresa = :empresa, cargo = :cargo, responsabilidades = :responsabilidades, fecha_ingreso = :fecha_ingreso, fecha_termino = :fecha_termino, tiempo_servicio = :tiempo_servicio, id_colaborador = :id_colaborador WHERE id = :id;");
            $query->bindParam(":empresa", $POST['empresa']);
            $query->bindParam(":cargo", $POST['cargo']);
            $query->bindParam(":responsabilidades", $POST['responsabilidades']);
            $query->bindParam(":fecha_ingreso", $POST['fecha_ingreso']);
            $query->bindParam(":fecha_termino", $POST['fecha_termino']);
            $query->bindParam(":tiempo_servicio", $POST['tiempo_servicio']);
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function editar_experiencia($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM experiencia_laboral WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }


    function eliminar_formacion($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM formacion WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function actualizar_formacion($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE formacion SET formacion = :formacion, lugar = :lugar, id_colaborador = :id_colaborador WHERE id = :id;");
            $query->bindParam(":formacion", $POST['formacion']);
            $query->bindParam(":lugar", $POST['lugar']);
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function editar_formacion($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM formacion WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function hecho($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE cronograma_registro SET estado = 1 WHERE id = :id;");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    public function guardar_cambio_estado($id, $estado)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE cronograma_registro_fecha SET estado = :estado WHERE id = :id;");
            $query->bindParam(":id", $id);
            $query->bindParam(":estado", $estado);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
    function no_hecho($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE cronograma_registro SET estado = 0 WHERE id = :id;");
            $query->bindParam(":id", $id);
            $query->execute();
            $mbd->commit();
            $result = array(
                'Result' => 'OK',
                'Message' => 'OK'
            );
            return json_encode($result);
        } catch (Exception $e) {
            $mbd->rollBack();
            $result = array(
                'Result' => 'ERROR',
                'Message' => $e->getMessage()
            );
            return json_encode($result);
        }
    }
}
