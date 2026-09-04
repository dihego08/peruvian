<?php
class clsColaborador
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
    function editar_capacitacion_registro($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM capacitacion_registro WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $q = $mbd->prepare("SELECT * FROM capacitacion_registro_fecha WHERE id_capacitacion_registro = :id");
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
            $query = $mbd->prepare("DELETE FROM capacitacion_registro WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();

            $query = $mbd->prepare("DELETE FROM capacitacion_registro_fecha WHERE id_capacitacion_registro = :id");
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
    function get_cronograma($POST)
    {
        $mes = 0;
        $tipo = $POST['tipo'];

        include("env.php");
        $f_desde = $POST['fecha_desde'];
        $f_hasta = $POST['fecha_hasta'];
        if ($tipo == 0) {
            $query = $mbd->prepare("SELECT distinct cr.*, t.tipo_cronograma from capacitacion_registro as cr inner join capacitacion_registro_fecha crf on cr.id = crf.id_capacitacion_registro left join tipo_cronogramas as t on cr.id_tipo = t.id where crf.dia >= " . date("d", strtotime($f_desde)) . " and crf.dia <= " . date("d", strtotime($f_hasta)) . " and (crf.mes + 1) >= " . date("m", strtotime($f_desde)) . " and (crf.mes+1) <= " . date("m", strtotime($f_hasta)) . " and cr.anio = " . date("Y", strtotime($f_desde)) . " ORDER BY crf.dia ASC;");
            $query->execute();
        } else {
            $query = $mbd->prepare("SELECT distinct cr.*, t.tipo_cronograma from capacitacion_registro as cr inner join capacitacion_registro_fecha crf on cr.id = crf.id_capacitacion_registro left join tipo_cronogramas as t on cr.id_tipo = t.id where crf.dia >= " . date("d", strtotime($f_desde)) . " and crf.dia <= " . date("d", strtotime($f_hasta)) . " and (crf.mes + 1) >= " . date("m", strtotime($f_desde)) . " and (crf.mes+1) <= " . date("m", strtotime($f_hasta)) . " and cr.anio = " . date("Y", strtotime($f_desde)) . " AND cr.id_tipo = " . $tipo . " ORDER BY crf.dia ASC;");
            $query->execute();
        }

        $values = array();
        $mes = $mes - 1;

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

            $q = $mbd->prepare("SELECT * FROM capacitacion_registro_fecha WHERE id_capacitacion_registro = :id AND (mes + 1) >= " . date("m", strtotime($f_desde)) . " and (mes+1) <= " . date("m", strtotime($f_hasta)) . " ORDER BY dia ASC");
            $mes =  date("m", strtotime($f_hasta));

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
        $query = $mbd->prepare("SELECT a.*, c.curso FROM capacitacion_registro as c, asistencias_cursos as a WHERE c.id = a.id_curso");
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
            $query = $mbd->prepare("DELETE FROM capacitacion_registro_fecha WHERE id = :id");
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
            $query = $mbd->prepare("UPDATE capacitacion_registro SET responsable = :responsable, curso = :curso, eficacia = :eficacia, areas = :areas, anio = :anio, id_tipo = :id_tipo WHERE id = :id;");
            $POST['areas'] = nl2br($POST['areas']);
            $query->bindParam(":curso", $POST['curso']);
            $query->bindParam(":eficacia", $POST['eficacia']);
            $query->bindParam(":areas", $POST['areas']);
            $query->bindParam(":anio", $POST['anio']);
            $query->bindParam(":id_tipo", $POST['id_tipo']);
            $query->bindParam(":responsable", $POST['responsable']);
            $query->bindParam(":id", $POST['id']);

            $query->execute();

            $query = $mbd->prepare("DELETE FROM capacitacion_registro_fecha WHERE id_capacitacion_registro = :id");
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            for ($i = 0; $i < count($POST['meses']); $i++) {
                $q = $mbd->prepare("INSERT INTO capacitacion_registro_fecha(id_capacitacion_registro, dia, mes, estado) VALUES (:id_capacitacion_registro, :dia, :mes, 0)");
                $q->bindParam(":id_capacitacion_registro", $POST['id']);
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
            $query = $mbd->prepare("INSERT INTO capacitacion_registro(curso, areas, anio, responsable, eficacia, id_tipo) VALUES(:curso, :areas, :anio, :responsable, :eficacia, :id_tipo);");
            $POST['areas'] = nl2br($POST['areas']);
            $query->bindParam(":curso", $POST['curso']);
            $query->bindParam(":eficacia", $POST['eficacia']);
            $query->bindParam(":areas", $POST['areas']);
            $query->bindParam(":anio", $POST['anio']);
            $query->bindParam(":responsable", $POST['responsable']);
            $query->bindParam(":id_tipo", $POST['id_tipo']);
            $query->execute();

            $lid = $mbd->lastInsertId();

            for ($i = 0; $i < count($POST['meses']); $i++) {
                $q = $mbd->prepare("INSERT INTO capacitacion_registro_fecha(id_capacitacion_registro, dia, mes, estado) VALUES (:id_capacitacion_registro, :dia, :mes, 0)");
                $q->bindParam(":id_capacitacion_registro", $lid);
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
    function checkIsNull($value)
    {
        if (is_null($value) || empty($value) || $value == null || $value == 'null') {
            return null;
        } else {
            return $value;
        }
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
            $query->bindValue(":dni", $this->checkIsNull($POST['dni']));
            $query->bindValue(":nombres", $this->checkIsNull($POST['nombres']));
            $query->bindValue(":apellido_paterno", $this->checkIsNull($POST['apellido_paterno']));
            $query->bindValue(":apellido_materno", $this->checkIsNull($POST['apellido_materno']));
            $query->bindValue(":foto", $this->checkIsNull($POST['foto']));
            $fecha_nacimiento = date("Y-m-d", strtotime($this->checkIsNull($POST['fecha_nacimiento'])));
            $query->bindValue(":fecha_nacimiento", $fecha_nacimiento);
            $query->bindValue(":lugar_nacimiento", $this->checkIsNull($POST['lugar_nacimiento']));
            $query->bindValue(":id_estado_civil", $this->checkIsNull($POST['id_estado_civil']));
            $query->bindValue(":celular", $this->checkIsNull($POST['celular']));
            $query->bindValue(":correo", $this->checkIsNull($POST['correo']));
            $query->bindValue(":brevette", $this->checkIsNull($POST['brevette']));
            $query->bindValue(":direccion", $this->checkIsNull($POST['direccion']));
            $query->bindValue(":telefono_emergencia", $this->checkIsNull($POST['telefono_emergencia']));
            $query->bindValue(":id_sistema_pension", $this->checkIsNull($POST['id_sistema_pension']));
            $query->bindValue(":id_entidad_pension", $this->checkIsNull($POST['id_entidad_pension']));
            $query->bindValue(":codigo", $this->checkIsNull($POST['codigo']));
            $query->bindValue(":asegurado", $this->checkIsNull($POST['asegurado']));
            $query->bindValue(":proceso", $this->checkIsNull($POST['id_proceso']));
            $query->bindValue(":sueldo", $this->checkIsNull($POST['sueldo']));
            $query->bindValue(":genero", $this->checkIsNull($POST['genero']));
            $query->bindValue(":estado_laboral", $this->checkIsNull($POST['estado_laboral']));
            $fecha_ingreso = date("Y-m-d", strtotime($this->checkIsNull($POST['fecha_ingreso'])));
            $query->bindValue(":fecha_ingreso", $fecha_ingreso);
            $fecha_salida = date("Y-m-d", strtotime($this->checkIsNull($POST['fecha_salida'])));
            $query->bindValue(":fecha_salida", $fecha_salida);
            $query->bindValue(":id_cargo", $this->checkIsNull($POST['id_cargo']));
            $query->bindValue(":linea", $this->checkIsNull($POST['linea']));
            $query->bindValue(":estado", $this->checkIsNull($POST['estado']));
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
            if (is_null($POST['id_entidad_pension']) || empty($POST['id_entidad_pension']) || $POST['id_entidad_pension'] == 'null') {
                $POST['id_entidad_pension'] = 0;
            }
            $query->bindParam(":id_entidad_pension", $POST['id_entidad_pension']);
            $query->bindParam(":codigo", $POST['codigo']);
            $query->bindParam(":asegurado", $POST['asegurado']);
            $query->bindParam(":proceso", $POST['id_proceso']);
            if (empty($POST['sueldo']) || is_null($POST['sueldo'])) {
                $POST['sueldo'] = null;
            }
            $query->bindParam(":sueldo", $POST['sueldo']);
            $query->bindParam(":genero", $POST['genero']);
            $query->bindParam(":estado_laboral", $POST['estado_laboral']);
            if ($POST['fecha_ingreso'] == '00-00-0000' || is_null($POST['fecha_ingreso']) || empty($POST['fecha_ingreso'])) {
                $fecha_ingreso = null;
            } else {
                $fecha_ingreso = date("Y-m-d", strtotime($POST['fecha_ingreso']));
            }
            $query->bindParam(":fecha_ingreso", $fecha_ingreso);
            /*if ($POST['fecha_salida'] == '00-00-0000' || is_null($POST['fecha_salida']) || empty($POST['fecha_salida'])) {
                $fecha_salida = null;
            } else {
                $fecha_salida = date("Y-m-d", strtotime($POST['fecha_salida']));
            }*/
            $fecha_salida = $POST['fecha_salida'];

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
    public function cargar_archivo_recomendaciones($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE recomendaciones_sst SET archivo = :archivo WHERE id = :id;");
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
    public function cargar_archivo_competencias($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE verificacion_competencias SET archivo = :archivo WHERE id = :id;");
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
    public function cargar_archivo_contrato($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE contratos SET archivo = :archivo WHERE id = :id;");
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
    public function cargar_archivo_examen_medico($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE examenes_medicos SET archivo = :archivo WHERE id = :id;");
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
    public function cargar_archivo_capacitaciones2($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE capacitaciones2 SET archivo = :archivo WHERE id = :id;");

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
    public function cargar_archivo_dni($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE colaboradores SET dni_archivo = :archivo WHERE id = :id;");
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
                $query = $mbd->prepare("UPDATE perfil_puesto SET reporta_a = :reporta_a, supervisa_a = :supervisa_a, interactua_con = :interactua_con, reemplazado_por = :reemplazado_por, objetivo = :objetivo, funciones = :funciones, responsabilidades = :responsabilidades, equipo_utilizado = :equipo_utilizado, lugar_trabajo = :lugar_trabajo, requerimientos_fisicos = :requerimientos_fisicos, formacion_basica = :formacion_basica, conocimientos_especificos = :conocimientos_especificos, experiencia_requerida = :experiencia_requerida, idioma = :idioma, competencia_especifica = :competencia_especifica, elaborado_por = :elaborado_por, aprobado_por = :aprobado_por, fecha_aprobacion = :fecha_aprobacion, competencia_cardinal = :competencia_cardinal, formacion_basica_optima = :formacion_basica_optima, experiencia_requerida_optima = :experiencia_requerida_optima WHERE id_puesto = :id_puesto;");

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
                $query->bindParam(":competencia_cardinal", $POST['competencia_cardinal']);
                $query->bindParam(":formacion_basica_optima", $POST['formacion_basica_optima']);
                $query->bindParam(":experiencia_requerida_optima", $POST['experiencia_requerida_optima']);


                $query->execute();
            } else {
                $query = $mbd->prepare("INSERT INTO perfil_puesto(id_puesto, reporta_a, supervisa_a, interactua_con, reemplazado_por, objetivo, funciones, responsabilidades, equipo_utilizado, lugar_trabajo, requerimientos_fisicos, formacion_basica, conocimientos_especificos, experiencia_requerida, idioma, competencia_especifica, elaborado_por, aprobado_por, fecha_aprobacion, competencia_cardinal, formacion_basica_optima, experiencia_requerida_optima) VALUES (:id_puesto, :reporta_a, :supervisa_a, :interactua_con, :reemplazado_por, :objetivo, :funciones, :responsabilidades, :equipo_utilizado, :lugar_trabajo, :requerimientos_fisicos, :formacion_basica, :conocimientos_especificos, :experiencia_requerida, :idioma, :competencia_especifica, :elaborado_por, :aprobado_por, :fecha_aprobacion, :competencia_cardinal, :formacion_basica_optima, :experiencia_requerida_optima);");

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
                $query->bindParam(":competencia_cardinal", $POST['competencia_cardinal']);
                $query->bindParam(":formacion_basica_optima", $POST['formacion_basica_optima']);
                $query->bindParam(":experiencia_requerida_optima", $POST['experiencia_requerida_optima']);


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
        //$query = $mbd->prepare("SELECT * FROM colaboradores LIMIT " . $current . ", 1 ");
        $query = $mbd->prepare("SELECT c.*, (select archivo from examenes_medicos where id_colaborador = c.id ORDER BY id DESC LIMIT 1) certificado_medico, (select archivo from contratos where id_colaborador = c.id ORDER BY id DESC LIMIT 1) contrato, (select archivo from recomendaciones_sst where id_colaborador = c.id  ORDER BY id DESC LIMIT 1) recomendacion_sst, (select archivo from verificacion_competencias where id_colaborador = c.id ORDER BY id DESC LIMIT 1) verificacion_competencias FROM colaboradores c LIMIT " . $current . ", 1 ");
        //$query->bindParam(":current", $current);
        $query->execute();
        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function siguiente_especifico($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT c.*, (select archivo from examenes_medicos where id_colaborador = " . $id . " ORDER BY id DESC LIMIT 1) certificado_medico, (select archivo from contratos where id_colaborador = " . $id . " ORDER BY id DESC LIMIT 1) contrato, (select archivo from recomendaciones_sst where id_colaborador = " . $id . "  ORDER BY id DESC LIMIT 1) recomendacion_sst, (select archivo from verificacion_competencias where id_colaborador = " . $id . " ORDER BY id DESC LIMIT 1) verificacion_competencias FROM colaboradores c WHERE c.id = " . $id);
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
    function guardar_capacitacion_2($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO capacitaciones2(curso, horas, fecha, capacitador, lugar) VALUES(:curso, :horas, :fecha, :capacitador, :lugar);");
            $query->bindParam(":curso", $POST['curso']);
            $query->bindParam(":horas", $POST['horas']);
            $query->bindParam(":fecha", $POST['fecha']);
            $query->bindParam(":capacitador", $POST['capacitador']);
            $query->bindParam(":lugar", $POST['lugar']);
            $query->execute();

            $lid = $mbd->lastInsertId();

            for ($i = 0; $i < count($POST['asistentes']); $i++) {
                $query2 = $mbd->prepare("INSERT INTO asistentes_capacitacion(id_colaborador, id_capacitacion, estado) VALUES (:id_colaborador, :id_capacitacion, 1)");
                $query2->bindParam(":id_colaborador", $POST['asistentes'][$i]);
                $query2->bindParam(":id_capacitacion", $lid);
                $query2->execute();
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
    function actualizar_capacitacion2($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $q = $mbd->prepare("DELETE FROM asistentes_capacitacion WHERE id_capacitacion = :id");
            $q->bindParam(":id", $POST['id']);
            $q->execute();

            $query = $mbd->prepare("UPDATE capacitaciones2 SET curso = :curso, horas = :horas, fecha = :fecha, capacitador = :capacitador, lugar = :lugar WHERE id = :id;");
            $query->bindParam(":curso", $POST['curso']);
            $query->bindParam(":horas", $POST['horas']);
            $query->bindParam(":fecha", $POST['fecha']);
            $query->bindParam(":capacitador", $POST['capacitador']);
            $query->bindParam(":lugar", $POST['lugar']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $lid = $POST['id'];

            for ($i = 0; $i < count($POST['asistentes']); $i++) {
                $query2 = $mbd->prepare("INSERT INTO asistentes_capacitacion(id_colaborador, id_capacitacion, estado) VALUES (:id_colaborador, :id_capacitacion, 1)");
                $query2->bindParam(":id_colaborador", $POST['asistentes'][$i]);
                $query2->bindParam(":id_capacitacion", $lid);
                $query2->execute();
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
    public function guardar_recomendaciones($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO recomendaciones_sst(fecha_recomendacion, fecha_capacitacion, tipo_recomendacion, referencia_recomendacion, observaciones, id_colaborador) VALUES (:fecha_recomendacion, :fecha_capacitacion, :tipo_recomendacion, :referencia_recomendacion, :observaciones, :id_colaborador)");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":fecha_recomendacion", $POST['fecha_recomendacion']);
            $query->bindParam(":fecha_capacitacion", $POST['fecha_capacitacion']);
            $query->bindParam(":tipo_recomendacion", $POST['tipo_recomendacion']);
            $query->bindParam(":referencia_recomendacion", $POST['referencia_recomendacion']);
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
    public function guardar_competencias($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO verificacion_competencias(id_colaborador, periodo, fecha_inicio, observaciones) VALUES (:id_colaborador, :periodo, :fecha_inicio, :observaciones);");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":periodo", $POST['periodo']);
            $query->bindParam(":fecha_inicio", $POST['fecha_inicio']);
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
    public function guardar_contrato($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO contratos(id_colaborador, periodo, fecha_inicio, fecha_fin, id_tipo_contrato, observaciones) VALUES (:id_colaborador, :periodo, :fecha_inicio, :fecha_fin, :id_tipo_contrato, :observaciones);");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":periodo", $POST['periodo']);
            $query->bindParam(":fecha_inicio", $POST['fecha_inicio']);
            $query->bindParam(":fecha_fin", $POST['fecha_fin']);
            $query->bindParam(":id_tipo_contrato", $POST['id_tipo_contrato']);
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
    public function guardar_examen_medico($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO examenes_medicos(id_colaborador, periodo, fecha, id_tipo_examen, id_aptitud, observaciones) VALUES (:id_colaborador, :periodo, :fecha, :id_tipo_examen, :id_aptitud, :observaciones);");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":periodo", $POST['periodo']);
            $query->bindParam(":fecha", $POST['fecha']);
            $query->bindParam(":id_tipo_examen", $POST['id_tipo_examen']);
            $query->bindParam(":id_aptitud", $POST['id_aptitud']);
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
        $query = $mbd->prepare("SELECT id, curso, capacitador, fecha, horas, lugar, archivo, 'A' tipo FROM capacitaciones WHERE id_colaborador = :id UNION select c.id, curso, capacitador, fecha, horas, lugar, archivo, 'B' tipo from capacitaciones2  c join asistentes_capacitacion a on a.id_capacitacion = c.id AND a.id_colaborador = :id2");
        $query->bindParam(":id", $id);
        $query->bindParam(":id2", $id);
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
    function get_capacitacion2()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM capacitaciones2");
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

            $query2 = $mbd->prepare("SELECT c.nombres, c.apellido_paterno, c.apellido_materno FROM colaboradores c join asistentes_capacitacion a on a.id_colaborador = c.id AND a.id_capacitacion = :id");
            $query2->bindParam(":id", $res['id']);
            $query2->execute();
            $va = array();
            while ($r = $query2->fetch(PDO::FETCH_ASSOC)) {
                $va[] = $r['nombres'] . " " . $r["apellido_paterno"] . " " . $r["apellido_materno"];
            }
            $res['asistentes'] = $va;
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
    public function get_recomendaciones($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM recomendaciones_sst WHERE id_colaborador = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
    public function get_competencias($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM verificacion_competencias WHERE id_colaborador = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
    public function get_contratos($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT c.*, tc.tipo_contrato FROM contratos as c JOIN tipo_contrato tc on tc.id = c.id_tipo_contrato WHERE c.id_colaborador = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
    public function get_examenes_medicos($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT em.*, te.tipo_examen, a.aptitud FROM examenes_medicos as em JOIN tipo_examen te on te.id = em.id_tipo_examen JOIN aptitud a on a.id = em.id_aptitud WHERE em.id_colaborador = :id");
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
    public function editar_examen_medico($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM examenes_medicos WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function editar_recomendaciones($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM recomendaciones_sst WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function editar_competencias($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM verificacion_competencias WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function editar_contrato($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM contratos WHERE id = :id");
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
    function eliminar_capacitacion2($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM capacitaciones2 WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();

            $query = $mbd->prepare("DELETE FROM asistentes_capacitacion WHERE id_capacitacion = :id");
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
    public function eliminar_recomendaciones($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM recomendaciones_sst WHERE id = :id");
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
    public function eliminar_competencias($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM verificacion_competencias WHERE id = :id");
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
    public function eliminar_contrato($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM contratos WHERE id = :id");
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
    public function eliminar_examen_medico($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM examenes_medicos WHERE id = :id");
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
    public function actualizar_recomendaciones($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE recomendaciones_sst SET fecha_recomendacion = :fecha_recomendacion, fecha_capacitacion = :fecha_capacitacion, tipo_recomendacion = :tipo_recomendacion, referencia_recomendacion = :referencia_recomendacion, observaciones = :observaciones WHERE id = :id;");
            $query->bindParam(":fecha_recomendacion", $POST['fecha_recomendacion']);
            $query->bindParam(":fecha_capacitacion", $POST['fecha_capacitacion']);
            $query->bindParam(":tipo_recomendacion", $POST['tipo_recomendacion']);
            $query->bindParam(":referencia_recomendacion", $POST['referencia_recomendacion']);
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
    public function actualizar_competencias($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE verificacion_competencias SET id_colaborador = :id_colaborador, periodo = :periodo, fecha_inicio = :fecha_inicio, observaciones = :observaciones WHERE id = :id;");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":periodo", $POST['periodo']);
            $query->bindParam(":fecha_inicio", $POST['fecha_inicio']);
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
    public function actualizar_contrato($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE contratos SET id_colaborador = :id_colaborador, periodo = :periodo, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin, id_tipo_contrato = :id_tipo_contrato, observaciones = :observaciones WHERE id = :id;");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":periodo", $POST['periodo']);
            $query->bindParam(":fecha_inicio", $POST['fecha_inicio']);
            $query->bindParam(":fecha_fin", $POST['fecha_fin']);
            $query->bindParam(":id_tipo_contrato", $POST['id_tipo_contrato']);
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
    public function actualizar_examen_medico($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE examenes_medicos SET id_colaborador = :id_colaborador, periodo = :periodo, fecha = :fecha, id_tipo_examen = :id_tipo_examen, id_aptitud = :id_aptitud, observaciones = :observaciones WHERE id = :id;");
            $query->bindParam(":id_colaborador", $POST['id_colaborador']);
            $query->bindParam(":periodo", $POST['periodo']);
            $query->bindParam(":fecha", $POST['fecha']);
            $query->bindParam(":id_tipo_examen", $POST['id_tipo_examen']);
            $query->bindParam(":id_aptitud", $POST['id_aptitud']);
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
    function editar_capacitacion2($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM capacitaciones2 WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $query_asistencias = $mbd->prepare("SELECT * FROM asistentes_capacitacion WHERE id_capacitacion = :id");
        $query_asistencias->bindParam(":id", $id);
        $query_asistencias->execute();
        $asistentes = array();
        while ($res = $query_asistencias->fetch(PDO::FETCH_ASSOC)) {
            $asistentes[] = $res;
        }
        $values = $query->fetch(PDO::FETCH_ASSOC);
        $values['asistentes'] = $asistentes;

        return json_encode($values);
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
            $query = $mbd->prepare("UPDATE capacitacion_registro SET estado = 1 WHERE id = :id;");
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
            $query = $mbd->prepare("UPDATE capacitacion_registro_fecha SET estado = :estado WHERE id = :id;");
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
            $query = $mbd->prepare("UPDATE capacitacion_registro SET estado = 0 WHERE id = :id;");
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
    function get_tipos_cronograma($id = null)
    {
        include("env.php");
        if (empty($id) || is_null($id)) {
            $query = $mbd->prepare("SELECT * FROM tipo_cronogramas");
        } else {
            $query = $mbd->prepare("SELECT * FROM tipo_cronogramas WHERE id = " . $id);
        }

        $query->execute();
        $values = array();
        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }
        return json_encode($values);
    }
}
