<?php
class clsMaquinas
{
    function llenar_maquinas()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM tbl_maquina");
        $query->execute();

        $result = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    function get_cronograma($anio, $mes)
    {
        include("env.php");

        $query = $mbd->prepare("SELECT mm.*, CONCAT(m.maquina_descripcion, ' ', m.maquina_tipo, '-', m.maquina_codigo) as maquina, m.maquina_imagen FROM mantenimiento_maquinas as mm, tbl_maquina as m WHERE mm.anio = :anio AND mm.id_maquina = m.maquina_id ORDER BY mm.fecha ASC");

        $query->bindParam(":anio", $anio);
        $query->execute();

        $values = array();
        $mes = $mes - 1;

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {

            if ($mes < 0) {
                $q = $mbd->prepare("SELECT * FROM mantenimiento_maquinas_fechas WHERE id_mantenimiento = :id");
            } else {
                //echo "SELECT * FROM mantenimiento_maquinas_fecha WHERE id_mantenimiento_maquinas = ".$res['id']." AND mes = ".$mes."||";
                $q = $mbd->prepare("SELECT * FROM mantenimiento_maquinas_fechas WHERE id_mantenimiento = :id AND mes = :mes");
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
    function editar_capacitacion_registro($id)
    {
        include("env.php");
        /*$query = $mbd->prepare("SELECT * FROM mantenimiento_maquinas WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
				
			$result = $query->fetch(PDO::FETCH_ASSOC);
			
            echo json_encode($result);*/

        $query = $mbd->prepare("SELECT * FROM mantenimiento_maquinas WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $q = $mbd->prepare("SELECT * FROM mantenimiento_maquinas_fechas WHERE id_mantenimiento = :id");
        $q->bindParam(":id", $result['id']);
        $q->execute();
        $fechas = array();
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
            $fechas[] = $r;
        }
        $result['fechas'] = $fechas;

        echo json_encode($result);
    }
    public function delete_elemento_from_form($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM mantenimiento_maquinas_fechas WHERE id = :id");
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
    public function eliminar_maquina($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM tbl_maquina WHERE maquina_id = :id");
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
    function eliminar_dispositivo($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM dispositivos WHERE id = :id");
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
    function eliminar_registro_capacitacion($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("DELETE FROM mantenimiento_maquinas WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();

            $query = $mbd->prepare("DELETE FROM mantenimiento_maquinas_fechas WHERE id_mantenimiento = :id");
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
            $query = $mbd->prepare("UPDATE mantenimiento_maquinas_fechas SET estado = :estado WHERE id = :id;");
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
    function actualizar_registro_capacitacion($POST)
    {
        include("env.php");
        /*try {
                $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $mbd->beginTransaction();
                $query = $mbd->prepare("UPDATE mantenimiento_maquinas SET responsable = :responsable, id_maquina = :id_maquina, descripcion = :descripcion, mes = :mes, anio = :anio, fecha = :fecha, dia = :dia WHERE id = :id;");
                $POST['descripcion'] = nl2br($POST['descripcion']);

                $la_fecha = explode("-", $POST['fecha']);
                
                $POST['anio'] = $la_fecha[0];
                $POST['mes'] = ($la_fecha[1] - 1);
                $POST['dia'] = $la_fecha[2];

                $query->execute($POST);

                $lid = $mbd->lastInsertId();

                $mbd->commit();
                $result = array(
                    'Result' => 'OK',
                    'Message' => 'OK',
                    'LID' => $lid
                );
                return json_encode($result);
            }catch (Exception $e) {
                $mbd->rollBack();
                $result = array(
                    'Result' => 'ERROR',
                    'Message' => $e->getMessage()
                );
                return json_encode($result);
            }*/
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE mantenimiento_maquinas SET responsable = :responsable, id_maquina = :id_maquina, descripcion = :descripcion, anio = :anio WHERE id = :id;");
            $POST['descripcion'] = nl2br($POST['descripcion']);
            $query->bindParam(":id_maquina", $POST['id_maquina']);
            $query->bindParam(":descripcion", $POST['descripcion']);
            $query->bindParam(":anio", $POST['anio']);
            $query->bindParam(":responsable", $POST['responsable']);
            $query->bindParam(":id", $POST['id']);

            $query->execute();

            $query = $mbd->prepare("DELETE FROM mantenimiento_maquinas_fechas WHERE id_mantenimiento = :id");
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            for ($i = 0; $i < count($POST['meses']); $i++) {
                $q = $mbd->prepare("INSERT INTO mantenimiento_maquinas_fechas(id_mantenimiento, dia, mes, estado) VALUES (:id_mantenimiento, :dia, :mes, 0)");
                $q->bindParam(":id_mantenimiento", $POST['id']);
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
            $query = $mbd->prepare("INSERT INTO mantenimiento_maquinas(id_maquina, descripcion, anio, responsable) VALUES(:id_maquina, :descripcion, :anio, :responsable);");
            $POST['descripcion'] = nl2br($POST['descripcion']);

            //$la_fecha = explode("-", $POST['fecha']);
            /*$mes = "";
                for ($i = 0; $i < count($POST['mes']); $i++) { 
                    if ($i == 0) {
                        $mes = $POST['mes'][$i];
                    }else{
                        $mes = $mes.",". $POST['mes'][$i];
                    }
                }*/
            //$POST['anio'] = $la_fecha[0];
            //$POST['mes'] = ($la_fecha[1] - 1);
            //$POST['dia'] = $la_fecha[2];

            //$query->execute($POST);
            $query->bindParam(":id_maquina", $POST['id_maquina']);
            $query->bindParam(":descripcion", $POST['descripcion']);
            $query->bindParam(":anio", $POST['anio']);
            $query->bindParam(":responsable", $POST['responsable']);
            $query->execute();

            $lid = $mbd->lastInsertId();

            for ($i = 0; $i < count($POST['meses']); $i++) {
                $q = $mbd->prepare("INSERT INTO mantenimiento_maquinas_fechas(id_mantenimiento, dia, mes, estado) VALUES (:id_mantenimiento, :dia, :mes, 0)");
                $q->bindParam(":id_mantenimiento", $lid);
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
    function hecho($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE mantenimiento_maquinas SET estado = 1 WHERE id = :id;");
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
    function no_hecho($id)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("UPDATE mantenimiento_maquinas SET estado = 0 WHERE id = :id;");
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
    public function get_tipos_maquinas()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM tipos_maquinas");
        $query->execute();

        $values = array();
        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $values[] = $res;
        }

        echo json_encode($values);
    }
    public function guardar_tipos_maquinas($tipo_maquina)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();
            $query = $mbd->prepare("INSERT INTO tipos_maquinas(tipo_maquina) VALUES(:tipo_maquina);");
            $query->bindParam(":tipo_maquina", $tipo_maquina);
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
