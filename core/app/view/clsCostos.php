<?php
class clsCostos
{
    function data_producto($id_producto)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM product WHERE id = :id_producto");
        $query->bindParam(":id_producto", $id_producto);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function get_precio_insumo($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM insumo_stock WHERE id_insumo = :id LIMIT 1");
        $query->bindParam(":id", $id);
        $query->execute();

        echo json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function get_totales($id_producto)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT SUM(costo_total) as total_materiales FROM costos_materiales join insumos_2 on insumos_2.id = costos_materiales.id_insumo WHERE id_producto = :id_producto");
        $query->bindParam(":id_producto", $id_producto);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function reevaluar($id_producto)
    {
        include("env.php");

        $query = $mbd->prepare("SELECT SUM(costo_total) as costos_materiales FROM costos_materiales WHERE id_producto = :id_producto");
        $query->bindParam(":id_producto", $id_producto);
        $query->execute();

        $costos_materiales = $query->fetch(PDO::FETCH_ASSOC);


        $query_2 = $mbd->prepare("SELECT SUM(total) as costo_uso_taller FROM costo_uso_taller WHERE id_producto = :id_producto");
        $query_2->bindParam(":id_producto", $id_producto);
        $query_2->execute();

        $costo_uso_taller = $query_2->fetch(PDO::FETCH_ASSOC);

        $query_3 = $mbd->prepare("SELECT SUM(valor_prenda) as costo_mano_directa FROM costo_mano_directa WHERE id_producto = :id_producto");
        $query_3->bindParam(":id_producto", $id_producto);
        $query_3->execute();

        $costo_mano_directa = $query_3->fetch(PDO::FETCH_ASSOC);

        $query_4 = $mbd->prepare("SELECT SUM(bordado) as costo_servicio_externo FROM costo_servicio_externo WHERE id_producto = :id_producto");
        $query_4->bindParam(":id_producto", $id_producto);
        $query_4->execute();

        $costo_servicio_externo = $query_4->fetch(PDO::FETCH_ASSOC);

        $total_de_totales = $costos_materiales['costos_materiales'] + $costo_uso_taller['costo_uso_taller'] + $costo_mano_directa['costo_mano_directa'] + $costo_servicio_externo['costo_servicio_externo'];



        $query_traer = $mbd->prepare("SELECT * FROM costos WHERE id_producto = :id_producto");
        $query_traer->bindParam(":id_producto", $id_producto);
        $query_traer->execute();

        $traido = $query_traer->fetch(PDO::FETCH_ASSOC);

        $costo_prenda = $total_de_totales;
        $valor_venta = $traido['valor_venta'];

        $utilidad = $valor_venta - $costo_prenda;
        $igv = $valor_venta * 0.18;
        $renta = 0;
        $precio_venta = $valor_venta + $igv;

        $query_reevaluar = $mbd->prepare("UPDATE costos SET costo_prenda = :costo_prenda, utilidad = :utilidad, valor_venta = :valor_venta, igv = :igv, renta = :renta, precio_venta = :precio_venta WHERE id_producto = :id_producto;");
        $query_reevaluar->bindParam(":costo_prenda", $costo_prenda);
        $query_reevaluar->bindParam(":utilidad", $utilidad);
        $query_reevaluar->bindParam(":valor_venta", $valor_venta);
        $query_reevaluar->bindParam(":igv", $igv);
        $query_reevaluar->bindParam(":renta", $renta);
        $query_reevaluar->bindParam(":id_producto", $id_producto);
        $query_reevaluar->bindParam(":precio_venta", $precio_venta);
        $query_reevaluar->execute();
    }
    public function get_totales_2($id_producto, $status)
    {
        include("env.php");
        if ($status == 0) {

            $query_cuenta = $mbd->prepare("SELECT COUNT(*) as cant FROM costos WHERE id_producto = :id_producto");
            $query_cuenta->bindParam(":id_producto", $id_producto);
            $query_cuenta->execute();

            $cuenta = $query_cuenta->fetch(PDO::FETCH_ASSOC);

            if ($cuenta['cant'] > 0) {
                $query = $mbd->prepare("SELECT * FROM costos WHERE id_producto = :id_producto");
                $query->bindParam(":id_producto", $id_producto);
                $query->execute();

                return json_encode($query->fetch(PDO::FETCH_ASSOC));
            } else {
                $query = $mbd->prepare("SELECT SUM(costo_total) as total_materiales FROM costos_materiales join insumos_2 on insumos_2.id = costos_materiales.id_insumo WHERE id_producto = :id_producto");
                $query->bindParam(":id_producto", $id_producto);
                $query->execute();

                $costos_materiales = $query->fetch(PDO::FETCH_ASSOC);

                $query_2 = $mbd->prepare("SELECT di_total_confeccion, di_confeccion_margen FROM datos_ingreso where id_producto = :id_producto");
                $query_2->bindParam(":id_producto", $id_producto);
                $query_2->execute();

                $data_ingreso = $query_2->fetch(PDO::FETCH_ASSOC);

                /*$query_2 = $mbd->prepare("SELECT SUM(total) as costo_uso_taller FROM costo_uso_taller WHERE id_producto = :id_producto");
                $query_2->bindParam(":id_producto", $id_producto);
                $query_2->execute();

                $costo_uso_taller = $query_2->fetch(PDO::FETCH_ASSOC);

                $query_3 = $mbd->prepare("SELECT SUM(valor_prenda) as costo_mano_directa FROM costo_mano_directa WHERE id_producto = :id_producto");
                $query_3->bindParam(":id_producto", $id_producto);
                $query_3->execute();

                $costo_mano_directa = $query_3->fetch(PDO::FETCH_ASSOC);*/

                $query_4 = $mbd->prepare("SELECT SUM(bordado) as costo_servicio_externo FROM costo_servicio_externo WHERE id_producto = :id_producto");
                $query_4->bindParam(":id_producto", $id_producto);
                $query_4->execute();

                $costo_servicio_externo = $query_4->fetch(PDO::FETCH_ASSOC);

                $total_de_totales =
                    (isset($costos_materiales['costos_materiales']) ? $costos_materiales['costos_materiales'] : 0) +
                    (isset($costo_servicio_externo['costo_servicio_externo']) ? $costo_servicio_externo['costo_servicio_externo'] : 0) +
                    (isset($data_ingreso['di_total_confeccion']) ? $data_ingreso['di_total_confeccion'] : 0);
/*echo "AKIS";
                echo (isset($costos_materiales['costos_materiales']) ? $costos_materiales['costos_materiales'] : 0) ." + ".
                (isset($costo_servicio_externo['costo_servicio_externo']) ? $costo_servicio_externo['costo_servicio_externo'] : 0) ." + ".
                (isset($data_ingreso['di_total_confeccion']) ? $data_ingreso['di_total_confeccion'] : 0);*/

                $total_mas_margen =
                    (isset($costos_materiales['costos_materiales']) ? $costos_materiales['costos_materiales'] : 0) +
                    (isset($costo_servicio_externo['costo_servicio_externo']) ? $costo_servicio_externo['costo_servicio_externo'] : 0) +
                    (isset($data_ingreso['di_confeccion_margen']) ? $data_ingreso['di_confeccion_margen'] : 0);

                $igv = $total_mas_margen * 0.18;
                // echo $costos_materiales['costos_materiales'] . " + " . $costo_uso_taller['costo_uso_taller'] . " + " . $costo_mano_directa['costo_mano_directa'] . " + " . $costo_servicio_externo['costo_servicio_externo'];


                $los_totales = array(
                    "costo_prenda" => $total_de_totales,
                    "utilidad" => $total_mas_margen - $total_de_totales,
                    "valor_venta" => $total_mas_margen,
                    "igv" => $igv,
                    "renta" => 0,
                    "precio_venta" => $total_mas_margen + $igv
                );

                return json_encode($los_totales);
            }
        } else {
            $query = $mbd->prepare("SELECT * FROM costos WHERE id_producto = :id_producto");
            $query->bindParam(":id_producto", $id_producto);
            $query->execute();

            return json_encode($query->fetch(PDO::FETCH_ASSOC));
        }
    }
    public function set_costos($POST)
    {
        //costos
        include("env.php");
        $costo_prenda = $POST['costo_prenda'];
        $valor_venta = $POST['valor_venta'];

        $utilidad = $valor_venta - $costo_prenda;
        $igv = $valor_venta * 0.18;
        $renta = (1.5 / 100) * $valor_venta;
        $precio_venta = $valor_venta + $igv + $renta;

        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query_cuenta = $mbd->prepare("SELECT COUNT(*) as cant FROM costos WHERE id_producto = :id_producto");
            $query_cuenta->bindParam(":id_producto", $POST['id_producto']);
            $query_cuenta->execute();

            $cuenta = $query_cuenta->fetch(PDO::FETCH_ASSOC);

            if ($cuenta['cant'] > 0) {
                $query = $mbd->prepare("UPDATE costos SET costo_prenda = :costo_prenda, utilidad = :utilidad, valor_venta = :valor_venta, igv = :igv, renta = :renta, precio_venta = :precio_venta WHERE id_producto = :id_producto;");
                $query->bindParam(":costo_prenda", $costo_prenda);
                $query->bindParam(":utilidad", $utilidad);
                $query->bindParam(":valor_venta", $valor_venta);
                $query->bindParam(":igv", $igv);
                $query->bindParam(":renta", $renta);
                $query->bindParam(":id_producto", $POST['id_producto']);
                $query->bindParam(":precio_venta", $precio_venta);
                $query->execute();
            } else {
                $query = $mbd->prepare("INSERT INTO costos(costo_prenda, utilidad, valor_venta, igv, renta, precio_venta, id_producto) VALUES (:costo_prenda, :utilidad, :valor_venta, :igv, :renta, :precio_venta, :id_producto);");
                $query->bindParam(":costo_prenda", $costo_prenda);
                $query->bindParam(":utilidad", $utilidad);
                $query->bindParam(":valor_venta", $valor_venta);
                $query->bindParam(":igv", $igv);
                $query->bindParam(":renta", $renta);
                $query->bindParam(":id_producto", $POST['id_producto']);
                $query->bindParam(":precio_venta", $precio_venta);
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



        //echo json_encode(array("Result" => "OK"));
    }
    public function lista_directos($id_producto)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT c.*, i.insumo FROM costos_materiales as c, insumos_2 as i WHERE c.id_producto = :id_producto AND c.tipo_material = 0 AND c.id_insumo = i.id ORDER BY c.id");
        $query->bindParam(":id_producto", $id_producto);
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    public function lista_extras($id_producto)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT c.*, i.insumo FROM costos_materiales as c, insumos_2 as i WHERE c.id_producto = :id_producto AND c.tipo_material = 1 AND c.id_insumo = i.id");
        $query->bindParam(":id_producto", $id_producto);
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    public function lista_uso_taller($id_producto)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM costo_uso_taller WHERE id_producto = :id_producto");
        $query->bindParam(":id_producto", $id_producto);
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    public function lista_empaques($id_producto)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT c.*, i.insumo FROM costos_materiales as c, insumos_2 as i WHERE c.id_producto = :id_producto AND c.tipo_material = 2 AND c.id_insumo = i.id");
        $query->bindParam(":id_producto", $id_producto);
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    public function editar_mano_directa($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM costo_mano_directa  WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        $result = array();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function lista_mano_directa($id_producto)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM costo_mano_directa  WHERE id_producto = :id_producto");
        $query->bindParam(":id_producto", $id_producto);
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    public function lista_bordado($id_producto)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM costo_servicio_externo  WHERE id_producto = :id_producto");
        $query->bindParam(":id_producto", $id_producto);
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    public function guardar_directo($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO costos_materiales(id_insumo, unidad, consumo_teorico, merma, consumo_real, costo_unitario, costo_total, tipo_material, id_producto) VALUES (:id_insumo, :unidad, :consumo_teorico, 1, :consumo_real, :costo_unitario, :costo_total, 0, :id_producto)");

            $query->bindParam(":id_insumo", $POST['id_insumo_directo']);
            $query->bindParam(":unidad", $POST['unidad_directo']);
            $query->bindParam(":consumo_teorico", $POST['consumo_teorico_directo']);
            $query->bindParam(":consumo_real", $POST['consumo_real_directo']);
            $query->bindParam(":costo_unitario", $POST['costo_unitario_directo']);
            $costo_total = $POST['consumo_real_directo'] * $POST['costo_unitario_directo'];
            $query->bindParam(":costo_total", $costo_total);
            $query->bindParam(":id_producto", $POST['id_producto']);
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
    public function actualizar_directo($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("UPDATE costos_materiales SET id_insumo = :id_insumo, unidad = :unidad, consumo_teorico = :consumo_teorico, consumo_real = :consumo_real, costo_unitario = :costo_unitario, costo_total = :costo_total WHERE id = :id");

            $query->bindParam(":id_insumo", $POST['id_insumo_directo']);
            $query->bindParam(":unidad", $POST['unidad_directo']);
            $query->bindParam(":consumo_teorico", $POST['consumo_teorico_directo']);
            $query->bindParam(":consumo_real", $POST['consumo_real_directo']);
            $query->bindParam(":costo_unitario", $POST['costo_unitario_directo']);
            $costo_total = $POST['consumo_real_directo'] * $POST['costo_unitario_directo'];
            $query->bindParam(":costo_total", $costo_total);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $obj = new clsCostos();
            $obj->reevaluar($POST['id_producto']);

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
    public function guardar_extra($POST)
    {

        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO costos_materiales(id_insumo, unidad, consumo_teorico, merma, consumo_real, costo_unitario, costo_total, tipo_material, id_producto) VALUES (:id_insumo, :unidad, :consumo_teorico, 1, :consumo_real, :costo_unitario, :costo_total, 1, :id_producto)");

            $query->bindParam(":id_insumo", $POST['id_insumo_extra']);
            $query->bindParam(":unidad", $POST['unidad_extra']);
            $query->bindParam(":consumo_teorico", $POST['consumo_teorico_extra']);
            $query->bindParam(":consumo_real", $POST['consumo_real_extra']);
            $query->bindParam(":costo_unitario", $POST['costo_unitario_extra']);
            $costo_total = $POST['consumo_real_extra'] * $POST['costo_unitario_extra'];
            $query->bindParam(":costo_total", $costo_total);
            $query->bindParam(":id_producto", $POST['id_producto']);
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
    public function guardar_empaque($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO costos_materiales(id_insumo, unidad, consumo_teorico, merma, consumo_real, costo_unitario, costo_total, tipo_material, id_producto) VALUES (:id_insumo, :unidad, :consumo_teorico, 1, :consumo_real, :costo_unitario, :costo_total, 2, :id_producto)");

            $query->bindParam(":id_insumo", $POST['id_insumo_empaque']);
            $query->bindParam(":unidad", $POST['unidad_empaque']);
            $query->bindParam(":consumo_teorico", $POST['consumo_teorico_empaque']);
            $query->bindParam(":consumo_real", $POST['consumo_real_empaque']);
            $query->bindParam(":costo_unitario", $POST['costo_unitario_empaque']);
            $costo_total = $POST['consumo_real_empaque'] * $POST['costo_unitario_empaque'];
            $query->bindParam(":costo_total", $costo_total);
            $query->bindParam(":id_producto", $POST['id_producto']);
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
    public function guardar_bordado($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO costo_servicio_externo(bordado, id_producto, concepto) VALUES (:bordado, :id_producto, :concepto)");
            $query->bindParam(":bordado", $POST['bordado']);
            $query->bindParam(":id_producto", $POST['id_producto']);
            $query->bindParam(":concepto", $POST['concepto']);
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
    public function actualizar_bordado($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("UPDATE costo_servicio_externo SET bordado = :bordado, concepto = :concepto WHERE id = :id");
            $query->bindParam(":bordado", $POST['bordado']);
            $query->bindParam(":concepto", $POST['concepto']);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $obj = new clsCostos();
            $obj->reevaluar($POST['id_producto']);

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
    public function editar_bordado($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM costo_servicio_externo WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function actualizar_mano_directa($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("UPDATE costo_mano_directa SET proceso = :proceso, costo_minuto = :costo_minuto, valor_prenda = :valor_prenda, tiempo_produccion = :tiempo_produccion WHERE id = :id");

            $query->bindParam(":proceso", $POST['proceso']);
            $query->bindParam(":costo_minuto", $POST['costo_minuto']);
            $query->bindParam(":tiempo_produccion", $POST['tiempo_produccion']);

            $costo_total = $POST['costo_minuto'] * $POST['tiempo_produccion'];
            $query->bindParam(":valor_prenda", $costo_total);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $obj = new clsCostos();
            $obj->reevaluar($POST['id_producto']);

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
    public function guardar_mano_directa($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO costo_mano_directa(proceso, costo_minuto, valor_prenda, tiempo_produccion, id_producto) VALUES (:proceso, :costo_minuto, :valor_prenda, :tiempo_produccion, :id_producto)");

            $query->bindParam(":proceso", $POST['proceso']);
            $query->bindParam(":costo_minuto", $POST['costo_minuto']);
            $query->bindParam(":tiempo_produccion", $POST['tiempo_produccion']);

            $costo_total = $POST['costo_minuto'] * $POST['tiempo_produccion'];
            $query->bindParam(":valor_prenda", $costo_total);
            $query->bindParam(":id_producto", $POST['id_producto']);
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
    public function guardar_uso_taller($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO costo_uso_taller(costo_minuto, tiempo_produccion, id_producto, total) VALUES (:costo_minuto, :tiempo_produccion, :id_producto, :total)");
            $query->bindParam(":costo_minuto", $POST['costo_minuto_taller']);
            $query->bindParam(":tiempo_produccion", $POST['tiempo_produccion_taller']);

            $costo_total = $POST['costo_minuto_taller'] * $POST['tiempo_produccion_taller'];
            $query->bindParam(":total", $costo_total);
            $query->bindParam(":id_producto", $POST['id_producto']);
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
    public function actualizar_uso_taller($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("UPDATE costo_uso_taller SET costo_minuto = :costo_minuto, tiempo_produccion = :tiempo_produccion, total = :total WHERE id = :id");
            $query->bindParam(":costo_minuto", $POST['costo_minuto_taller']);
            $query->bindParam(":tiempo_produccion", $POST['tiempo_produccion_taller']);

            $costo_total = $POST['costo_minuto_taller'] * $POST['tiempo_produccion_taller'];
            $query->bindParam(":total", $costo_total);
            $query->bindParam(":id", $POST['id']);
            $query->execute();

            $obj = new clsCostos();
            $obj->reevaluar($POST['id_producto']);

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
    public function editar_uso_taller($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM costo_uso_taller WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function editar_directo($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM costos_materiales WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    public function eliminar_directo($id)
    {
        include("env.php");
        $query = $mbd->prepare("DELETE FROM costos_materiales WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode(
            array(
                "Result" => "OK"
            )
        );
    }
    function eliminar_bordado($id)
    {
        include("env.php");
        $query = $mbd->prepare("DELETE FROM costo_servicio_externo WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode(
            array(
                "Result" => "OK"
            )
        );
    }
    function eliminar_uso_taller($id)
    {
        include("env.php");
        $query = $mbd->prepare("DELETE FROM costo_uso_taller WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode(
            array(
                "Result" => "OK"
            )
        );
    }
    function guardar_MOD($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            /*$q = $mbd->prepare("SELECT COUNT(*) as cant FROM tbl_mod");
            $q->execute();
            $cant = $q->fetch(PDO::FETCH_ASSOC);*/

            /*if ($cant['cant'] > 0) {
                $query = $mbd->prepare("UPDATE tbl_mod SET mod_mod=:mod_mod,mod_sueldo_mes=:mod_sueldo_mes,mod_dia_mes=:mod_dia_mes,mod_horas_dia=:mod_horas_dia,mod_factor=:mod_factor,sueldo_mes=:sueldo_mes,sueldo_dia=:sueldo_dia,sueldo_hora=:sueldo_hora,sueldo_minuto=:sueldo_minuto WHERE id = 1");
            } else {*/
            $query = $mbd->prepare("INSERT INTO tbl_mod(mod_mod, mod_sueldo_mes, mod_dia_mes, mod_horas_dia, mod_factor, sueldo_mes, sueldo_dia, sueldo_hora, sueldo_minuto, id_producto) VALUES (:mod_mod, :mod_sueldo_mes, :mod_dia_mes, :mod_horas_dia, :mod_factor, :sueldo_mes, :sueldo_dia, :sueldo_hora, :sueldo_minuto, :id_producto)");
            //}

            $query->execute($POST);

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
    function guardar_gvm($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO tbl_gvm(concepto, monto, dias_mes, horas_dia, monto_mes, monto_dia) VALUES (:concepto, :monto, :dias_mes, :horas_dia, :monto_mes, :monto_dia)");
            $query->execute($POST);

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
    function guardar_gaf($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO tbl_gaf(concepto, monto, dias_mes, horas_dia, monto_mes, monto_dia) VALUES (:concepto, :monto, :dias_mes, :horas_dia, :monto_mes, :monto_dia)");
            $query->execute($POST);

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
    function guardar_costos_fijos($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO tbl_costos_fijos(concepto, monto, dias_mes, horas_dia, monto_mes, monto_dia) VALUES (:concepto, :monto, :dias_mes, :horas_dia, :monto_mes, :monto_dia)");
            $query->execute($POST);

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
    function guardar_MOI($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO tbl_moi(moi_concepto, moi_sueldo_mes, moi_n_trabajador, moi_dia_mes, moi_horas_dia, sueldo_mes, sueldo_dia) VALUES (:moi_concepto, :moi_sueldo_mes, :moi_n_trabajador, :moi_dia_mes, :moi_horas_dia, :sueldo_mes, :sueldo_dia)");
            $query->execute($POST);

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
    function extraer_gvm()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM tbl_gvm");
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    function extraer_gaf()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM tbl_gaf");
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    function extraer_costos_fijos()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM tbl_costos_fijos");
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    function extraer_MOI()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM tbl_moi");
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    function extraer_CIF()
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM tbl_cif");
        $query->execute();

        $result = array();

        while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $res;
        }

        return json_encode($result);
    }
    function get_MOD($id_producto)
    {
        include("env.php");
        $q1 = $mbd->prepare("SELECT * from tbl_mod WHERE id_producto = " . $id_producto . " ORDER BY id DESC LIMIT 1");
        $q1->execute();
        $mod = $q1->fetch((PDO::FETCH_ASSOC));
        return json_encode($mod);
    }
    function get_data_ingreso($id_producto)
    {
        include("env.php");
        $q1 = $mbd->prepare("SELECT sueldo_minuto, sueldo_dia from tbl_mod where id_producto = " . $id_producto. " ORDER BY id DESC");
        $q1->execute();
        $mod = $q1->fetch((PDO::FETCH_ASSOC));

        $q2 = $mbd->prepare("SELECT SUM(sueldo_dia) sueldo_dia FROM tbl_moi");
        $q2->execute();
        $moi = $q2->fetch((PDO::FETCH_ASSOC));

        $q3 = $mbd->prepare("SELECT SUM(consumo_dia) consumo_dia FROM tbl_cif");
        $q3->execute();
        $cif = $q3->fetch((PDO::FETCH_ASSOC));

        /************************ */
        $q_costos_fijos = $mbd->prepare("SELECT SUM(monto_dia) monto_dia FROM tbl_costos_fijos");
        $q_costos_fijos->execute();
        $q_costos_fijos = $q_costos_fijos->fetch((PDO::FETCH_ASSOC));

        $q_gaf = $mbd->prepare("SELECT SUM(monto_dia) monto_dia FROM tbl_gaf");
        $q_gaf->execute();
        $q_gaf = $q_gaf->fetch((PDO::FETCH_ASSOC));

        $q_gvm = $mbd->prepare("SELECT SUM(monto_dia) monto_dia FROM tbl_gvm");
        $q_gvm->execute();
        $q_gvm = $q_gvm->fetch((PDO::FETCH_ASSOC));
        /************** */

        $q4 = $mbd->prepare("SELECT * from datos_ingreso where id_producto = :id_producto");
        $q4->bindParam(":id_producto", $id_producto);
        $q4->execute();
        return json_encode(
            array(
                "mod" => $mod['sueldo_minuto'],
                "moi" => $moi['sueldo_dia'],
                "cif" => $cif['consumo_dia'],

                "costos_fijos" => $q_costos_fijos['monto_dia'],
                "gaf" => $q_gaf['monto_dia'],
                "gvm" => $q_gvm['monto_dia'],

                "sueldo_dia" => $mod['sueldo_dia'],
                "data_ingreso" => $q4->fetch(PDO::FETCH_ASSOC)
            )
        );
    }
    function guardar_CIF($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("INSERT INTO tbl_cif(cif_concepto, cif_mensual, cif_asignacion_planta, cif_dia_mes, cif_horas_dia, asignacion_planta_so, consumo_dia) VALUES (:cif_concepto, :cif_mensual, :cif_asignacion_planta, :cif_dia_mes, :cif_horas_dia, :asignacion_planta_so, :consumo_dia)");
            $query->execute($POST);

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
    function set_ingreso($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $q = $mbd->prepare("SELECT COUNT(*) as cant FROM datos_ingreso WHERE id_producto = :id_producto");
            $q->bindParam(":id_producto", $POST['id_producto']);
            $q->execute();
            $cant = $q->fetch(PDO::FETCH_ASSOC);
            if ($cant['cant'] > 0) {
                $query = $mbd->prepare("UPDATE datos_ingreso SET di_por_capacidad = :di_por_capacidad, di_nro_operarios = :di_nro_operarios, di_tie_confeccion = :di_tie_confeccion, di_hor_laboradas = :di_hor_laboradas, di_tal_estimar = :di_tal_estimar, tarifa_corte = :tarifa_corte, di_total_confeccion = :di_total_confeccion, di_confeccion_margen = :di_confeccion_margen, di_margen = :di_margen WHERE id_producto = :id_producto");
            } else {
                $query = $mbd->prepare("INSERT INTO datos_ingreso(di_por_capacidad, di_nro_operarios, di_tie_confeccion, di_hor_laboradas, di_tal_estimar, tarifa_corte, id_producto, di_total_confeccion, di_confeccion_margen, di_margen) VALUES (:di_por_capacidad, :di_nro_operarios, :di_tie_confeccion, :di_hor_laboradas, :di_tal_estimar, :tarifa_corte, :id_producto, :di_total_confeccion, :di_confeccion_margen, :di_margen);");
            }

            $query->execute($POST);

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
    function editar_MOI($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM tbl_moi where id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function editar_CIF($id)
    {
        include("env.php");
        $query = $mbd->prepare("SELECT * FROM tbl_cif where id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
    function actualizar_MOI($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("UPDATE tbl_moi SET moi_concepto = :moi_concepto, moi_sueldo_mes = :moi_sueldo_mes, moi_n_trabajador = :moi_n_trabajador, moi_dia_mes = :moi_dia_mes, moi_horas_dia = :moi_horas_dia, sueldo_mes = :sueldo_mes, sueldo_dia = :sueldo_dia  WHERE id = :id");
            $query->execute($POST);

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
    function actualizar_CIF($POST)
    {
        include("env.php");
        try {
            $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $mbd->beginTransaction();

            $query = $mbd->prepare("UPDATE tbl_cif SET id = :id, cif_concepto = :cif_concepto, cif_mensual = :cif_mensual, cif_asignacion_planta = :cif_asignacion_planta, cif_dia_mes = :cif_dia_mes, cif_horas_dia = :cif_horas_dia, asignacion_planta_so = :asignacion_planta_so, consumo_dia = :consumo_dia  WHERE id = :id");
            $query->execute($POST);

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
    function eliminar_MOI($id)
    {
        include("env.php");
        $query = $mbd->prepare("DELETE FROM tbl_moi WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode(
            array(
                "Result" => "OK"
            )
        );
    }
    function eliminar_CIF($id)
    {
        include("env.php");
        $query = $mbd->prepare("DELETE FROM tbl_cif WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        return json_encode(
            array(
                "Result" => "OK"
            )
        );
    }
}
