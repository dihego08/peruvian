<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);
class clsOrder
{
	function solo_ruc()
	{
		include("env.php");
		$query = $mbd->prepare("SELECT DISTINCT ruc_add FROM ventas_cabecera WHERE id_person = 0");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}

		return json_encode($values);
	}
	function lista_ventas_ruc($ruc_add, $fecha)
	{
		include("env.php");
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("SELECT * FROM ventas_cabecera WHERE ruc_add = :ruc_add ORDER BY fecha_creacion DESC");
			$query->bindParam(":ruc_add", $ruc_add);
			$query->execute();
			$values = array();
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$values[] = $res;
			}

			$mbd->commit();
			return json_encode($values);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function get_cliente($k)
	{
		include("env.php");
		$query = $mbd->prepare("SELECT id_referencia FROM cargos WHERE id = :id");
		$query->bindParam(":id", $k);
		$query->execute();
		$values = $query->fetch(PDO::FETCH_ASSOC);

		return json_encode($values);
	}
	function producto_autocomplete($arg)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT *  FROM product WHERE code LIKE '%" . $arg . "%'");
		$query->execute();
		$values = array();
		$tipo = "";
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			if ($res['kind'] == 1) {
				$tipo = 'Servicio';
			} else {
				$tipo = 'Producto';
			}
			$values[] = array(
				'id' => $res['id'],
				'value' => $res['name'],
				'unidad' => $res['unit'],
				'tipo' => $tipo,
				'precio_unitario' => $res['price_in']
			);
		}
		return json_encode($values);
	}
	function enviar_correo($cliente, $POST, $cant, $tiempo_entrega, $codigo, $comentario, $usuario, $mbd)
	{
		//include('env.php');

		$query_c = $mbd->prepare("SELECT * FROM order_detalle_2 WHERE codigo_cabecera = :codigo");
		$query_c->bindParam(':codigo', $codigo);
		$query_c->execute();

		$query = $mbd->prepare("SELECT * FROM person WHERE id = :cliente");
		$query->bindParam(':cliente', $cliente);
		$query->execute();
		$cli = $query->fetch(PDO::FETCH_ASSOC);

		//$to = 'informes@peruviandress.com';
		$to = 'omendoza@peruviandress.com';

		$subject = 'Orden de Pedido Código: ' . $codigo;

		$headers = "From: " . strip_tags("informes@peruviandress.com") . "\r\n";
		//$headers .= "Reply-To: ". strip_tags("aranibar.08diego@gmail.com") . "\r\n";
		//$headers .= "CC: aranibar.08diego@gmail.com\r\n";
		//$headers .= "CC: cesarmayta@solinsoft.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$message = '<html><style type="text/css">
				th{
					padding: 5px;
					background-color: wheat;
				}
				td{
					padding: 5px;
				}
			</style><body>';
		$message .= '<h2>Orden de Pedido Código ' . $codigo . '</h2>';
		$message .= '<h2>Usuario: ' . $usuario . '</h2>';
		$message .= '<h2>Cliente: ' . $cli['name'] . '</h2>';
		$message .= '<table border="1">
								<thead>
									<tr>
										<th rowspan="2" style="vertical-align: middle; text-align: center;">Modelo</th>
										<th rowspan="2" style="vertical-align: middle; text-align: center;">Color</th>
										<th colspan="13" style="text-align: center;">Cantidades por Talla</th>
									</tr>
									';

		$tabla_cabecera = "";
		$tabla_cuerpo = "";
		$total = 0;
		while ($res = $query_c->fetch(PDO::FETCH_ASSOC)) {
			$tabla_cabecera = '<tr>
										<th>' . $res['n1'] . '</th>
										<th>' . $res['n2'] . '</th>
										<th>' . $res['n3'] . '</th>
										<th>' . $res['n4'] . '</th>
										<th>' . $res['n5'] . '</th>
										<th>' . $res['n6'] . '</th>
										<th>' . $res['n7'] . '</th>
										<th>' . $res['n8'] . '</th>
										<th>' . $res['n9'] . '</th>
										<th>' . $res['n10'] . '</th>
										<th>' . $res['n11'] . '</th>
										<th>' . $res['n12'] . '</th>
										<th>' . $res['n13'] . '</th>
										<th>Total</th>
									</tr>';

			$subtotal = $res['_2'] + $res['_4'] + $res['_6'] + $res['_8'] + $res['_10'] + $res['_12'] + $res['_14'] + $res['_16'] + $res['s']
				+ $res['m'] + $res['l'] + $res['xl'] + $res['xxl'];
			$total += $subtotal;

			$tabla_cuerpo .= '<tr>' .
				'<td>' . $res['modelo'] . '</td>' .
				'<td>' . $res['color'] . '</td>' .
				'<td>' . $res['_2'] . '</td>' .
				'<td>' . $res['_4'] . '</td>' .
				'<td>' . $res['_6'] . '</td>' .
				'<td>' . $res['_8'] . '</td>' .
				'<td>' . $res['_10'] . '</td>' .
				'<td>' . $res['_12'] . '</td>' .
				'<td>' . $res['_14'] . '</td>' .
				'<td>' . $res['_16'] . '</td>' .
				'<td>' . $res['s'] . '</td>' .
				'<td>' . $res['m'] . '</td>' .
				'<td>' . $res['l'] . '</td>' .
				'<td>' . $res['xl'] . '</td>' .
				'<td>' . $res['xxl'] . '</td>' .
				'<td>' . $subtotal . '</td>' .
				'</tr>';
		}
		$message .= $tabla_cabecera .
			'</thead>
								<tbody>' . $tabla_cuerpo;
		$message .= '<tr><td colspan="15" align="right"></td><td>' . $total . '</td></tr>';
		$message .= '</tbody>
			</table>';
		$message .= "<h4>" . $comentario . "</h4></body></html>";
		mail($to, $subject, $message, $headers);
	}
	function guardar_order($cliente, $productos, $cantidad, $tiempo_entrega)
	{
		include('env.php');
		$productos = explode(',', $productos);
		$cantidad = explode(',', $cantidad);

		$aux = new clsOrder();
		$id = json_decode($aux->id_order());
		$codigo = date("y") . str_pad($id->id, 2, "0", STR_PAD_LEFT);
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();
			$query = $mbd->prepare("INSERT INTO order_cabecera(codigo, tiempo_entrega, person_id, estado, fecha_entrega) VALUES(:codigo, :tiempo_entrega, :cliente, 0, DATE_ADD(CURDATE(), INTERVAL :tiempo_entrega DAY))");
			$query->bindParam(':codigo', $codigo);
			$query->bindParam(':tiempo_entrega', $tiempo_entrega);
			$query->bindParam(':cliente', $cliente);
			$query->execute();

			for ($i = 1; $i < count($productos); $i++) {
				$query2 = $mbd->prepare("INSERT INTO order_detalle(codigo_cabecera, id_producto, cantidad) VALUES(:codigo, :producto, :cantidad)");
				$query2->bindParam(':codigo', $codigo);
				$query2->bindParam(':producto', $productos[$i]);
				$query2->bindParam(':cantidad', $cantidad[$i]);
				$query2->execute();
			}
			$m = new clsOrder;
			$m->enviar_correo($cliente, $productos, $cantidad, $tiempo_entrega, $codigo, $usuario, $mbd);

			$query_upd = $mbd->prepare("UPDATE aux SET id = id + 1 WHERE tabla = 'order'");
			$query_upd->execute();

			$mbd->commit();
			$result = array(
				'Result' => $codigo
			);
			//$aux->update_id_order();
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	/*function update_id_order()
	{
		include('env.php');
		$query = $mbd->prepare("UPDATE aux SET id = id + 1 WHERE tabla = 'order'");
		$query->execute();
	}*/
	function id_order()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT id FROM aux WHERE tabla = 'order'");
		$query->execute();
		$id = $query->fetch(PDO::FETCH_ASSOC);
		return json_encode($id);
	}
	function lista_clientes()
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM person WHERE kind = 1");
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
	}
	function lista_ordenes($id_cliente)
	{
		include('env.php');

		$where = [];
		$params = [];

		if ($id_cliente != 0) {
			$where[] = "p.id = :id_cliente";
			$params[':id_cliente'] = $id_cliente;
		}

		$sqlWhere = count($where) ? ("WHERE " . implode(" AND ", $where)) : "";

		$sql = "SELECT 
            oc.nombre_modelo,
            oc.fecha_entrega_real,
            oc.imagen_alt,
            IFNULL(GROUP_CONCAT(DISTINCT g.num_guia SEPARATOR ' - '), oc.guia_remision) AS guia_remision,
            oc.num_contrato,
            oc.comentario,
            oc.codigo,
            DATE_FORMAT(oc.fecha_creacion, '%d-%m-%Y') AS fecha_creacion,
            oc.tiempo_entrega,
            oc.fecha_entrega,
            oc.estado,
            p.name,
            DATEDIFF(oc.fecha_entrega, CURDATE()) AS trans,
            oc.total,

            -- Datos del primer detalle
            det.image AS imagen,
            det.modelo AS codigo_unitario,
            det.producto,
            det.totalp,

            -- Códigos de venta relacionados
            IFNULL(GROUP_CONCAT(DISTINCT v.codigo_venta SEPARATOR ' - '), '') AS codigo_venta

        FROM order_cabecera oc
        INNER JOIN person p ON p.id = oc.person_id

        -- Subconsulta optimizada del detalle
        LEFT JOIN (
            SELECT 
                d.codigo_cabecera,
                SUM(d.ptotal) AS totalp,
                SUBSTRING_INDEX(GROUP_CONCAT(d.modelo ORDER BY d.id ASC), ',', 1) AS modelo,
                SUBSTRING_INDEX(GROUP_CONCAT(prod.image ORDER BY d.id ASC), ',', 1) AS image,
                SUBSTRING_INDEX(GROUP_CONCAT(prod.name ORDER BY d.id ASC), ',', 1) AS producto
            FROM order_detalle_2 d
            INNER JOIN product prod ON prod.code = d.modelo
            GROUP BY d.codigo_cabecera
        ) AS det ON det.codigo_cabecera = oc.codigo

        -- Ventas relacionadas
        LEFT JOIN ventas_cabecera v 
            ON v.pedido_cod LIKE CONCAT('%', oc.codigo, '%')

        -- Guias remision relacionadas
        LEFT JOIN guia_detalle gd 
            ON gd.pedido LIKE CONCAT('%', oc.codigo, '%')
            LEFT JOIN guia_cabecera g ON g.id = gd.id_guia

        $sqlWhere

        GROUP BY oc.codigo
        ORDER BY CAST(oc.codigo AS UNSIGNED) DESC";

		$query = $mbd->prepare($sql);

		foreach ($params as $key => $value) {
			$query->bindValue($key, $value);
		}

		$query->execute();

		$values = $query->fetchAll(PDO::FETCH_ASSOC);

		return json_encode([
			'Result' => 'OK',
			'Records' => $values
		]);
	}

	function lista_ordenes_fecha($GET)
	{
		include('env.php');

		$where = [];
		$params = [];

		// Filtro por fecha (obligatorio)
		$where[] = "oc.fecha_creacion BETWEEN :fecha_desde AND :fecha_hasta";
		$params[':fecha_desde'] = $GET['fecha_desde'];
		$params[':fecha_hasta'] = $GET['fecha_hasta'];

		// Filtro por cliente
		if ($GET['id_cliente'] != 0) {
			$where[] = "p.id = :id_cliente";
			$params[':id_cliente'] = $GET['id_cliente'];
		}

		// Filtro por modelo
		if (!empty($GET['num_modelo'])) {
			$where[] = "EXISTS (
                        SELECT 1 
                        FROM order_detalle_2 d 
                        WHERE d.codigo_cabecera = oc.codigo 
                        AND d.modelo = :num_modelo
                    )";
			$params[':num_modelo'] = $GET['num_modelo'];
		}

		// Armado dinámico del WHERE
		$sqlWhere = "WHERE " . implode(" AND ", $where);

		// Consulta optimizada
		$sql = "SELECT 
            oc.nombre_modelo,
            oc.fecha_entrega_real,
            oc.imagen_alt,
            IFNULL(GROUP_CONCAT(DISTINCT g.num_guia SEPARATOR ' - '), oc.guia_remision) AS guia_remision,
            oc.num_contrato,
            oc.comentario,
            oc.codigo,
            DATE_FORMAT(oc.fecha_creacion, '%d-%m-%Y') AS fecha_creacion,
            oc.tiempo_entrega,
            oc.fecha_entrega,
            oc.estado,
            p.name,
            DATEDIFF(oc.fecha_entrega, CURDATE()) AS trans,
            oc.total,

            -- Subconsulta agrupada de los detalles
            det.image AS imagen,
            det.modelo AS codigo_unitario,
            det.producto,
            det.totalp,

            -- Códigos de venta relacionados
            IFNULL(GROUP_CONCAT(DISTINCT v.codigo_venta SEPARATOR ' - '), '') AS codigo_venta

        FROM order_cabecera oc
        INNER JOIN person p ON p.id = oc.person_id

        -- JOIN optimizado del detalle
        LEFT JOIN (
            SELECT 
                d.codigo_cabecera,
                SUM(d.ptotal) AS totalp,
                SUBSTRING_INDEX(GROUP_CONCAT(d.modelo ORDER BY d.id ASC), ',', 1) AS modelo,
                SUBSTRING_INDEX(GROUP_CONCAT(prod.image ORDER BY d.id ASC), ',', 1) AS image,
                SUBSTRING_INDEX(GROUP_CONCAT(prod.name  ORDER BY d.id ASC), ',', 1) AS producto
            FROM order_detalle_2 d
            INNER JOIN product prod ON prod.code = d.modelo
            GROUP BY d.codigo_cabecera
        ) det ON det.codigo_cabecera = oc.codigo

        -- Ventas relacionadas
        LEFT JOIN ventas_cabecera v 
            ON v.pedido_cod LIKE CONCAT('%', oc.codigo, '%')
        
        -- Guias remision relacionadas
        LEFT JOIN guia_detalle gd 
            ON gd.pedido LIKE CONCAT('%', oc.codigo, '%')
            LEFT JOIN guia_cabecera g ON g.id = gd.id_guia

        $sqlWhere

        GROUP BY oc.codigo
        ORDER BY CAST(oc.codigo AS UNSIGNED) DESC";

		$query = $mbd->prepare($sql);

		// Bind dinámico
		foreach ($params as $key => $value) {
			$query->bindValue($key, $value);
		}

		$query->execute();

		$values = $query->fetchAll(PDO::FETCH_ASSOC);

		return json_encode([
			'Result' => 'OK',
			'Records' => $values
		]);
	}

	function lista_ordenes_2($id_cliente, $codigo, $modelo, $contrato)
	{
		include('env.php');

		// Filtros SQL dinámicos
		$where = [];
		$params = [];

		if (!empty($id_cliente) && $id_cliente != 0) {
			$where[] = "p.id = :id_cliente";
			$params[':id_cliente'] = $id_cliente;
		}

		if (!empty($codigo)) {
			$where[] = "oc.codigo = :codigo";
			$params[':codigo'] = $codigo;
		}

		if (!empty($modelo)) {
			$where[] = "d1.modelo = :modelo";
			$params[':modelo'] = $modelo;
		}

		if (!empty($contrato) && $contrato != -1) {
			$where[] = "oc.num_contrato = :contrato";
			$params[':contrato'] = $contrato;
		}

		$sqlWhere = count($where) ? ("WHERE " . implode(" AND ", $where)) : "";

		// ==========================
		// CONSULTA COMPLETAMENTE OPTIMIZADA
		// ==========================

		$sql = "SELECT 
            oc.num_contrato,
            oc.comentario,
            oc.codigo,
            oc.fecha_entrega_real,
			IFNULL(GROUP_CONCAT(DISTINCT g.num_guia SEPARATOR ' - '), oc.guia_remision) AS guia_remision,
            DATE_FORMAT(oc.fecha_creacion, '%d-%m-%Y') AS fecha_creacion,
            oc.tiempo_entrega,
            oc.fecha_entrega,
            oc.estado,
			oc.imagen_alt,
            p.name,
            DATEDIFF(oc.fecha_entrega, CURDATE()) AS trans,
            oc.total,

            -- Información del primer detalle
            det.imagen,
            det.modelo AS codigo_unitario,
            det.producto,
            det.totalp,

            -- Códigos de venta relacionados
            IFNULL(GROUP_CONCAT(DISTINCT v.codigo_venta SEPARATOR ' - '), '') AS codigo_venta

        FROM order_cabecera oc
        INNER JOIN person p ON p.id = oc.person_id

        -- Subconsulta con el primer detalle
        LEFT JOIN (
            SELECT 
                d.codigo_cabecera,
                SUM(d.ptotal) AS totalp,
                MIN(d.id) AS primer_detalle,
                SUBSTRING_INDEX(GROUP_CONCAT(d.modelo ORDER BY d.id ASC), ',', 1) AS modelo,
                SUBSTRING_INDEX(GROUP_CONCAT(prod.image ORDER BY d.id ASC), ',', 1) AS imagen,
                SUBSTRING_INDEX(GROUP_CONCAT(prod.name ORDER BY d.id ASC), ',', 1) AS producto
            FROM order_detalle_2 d
            INNER JOIN product prod ON prod.code = d.modelo
            GROUP BY d.codigo_cabecera
        ) det ON det.codigo_cabecera = oc.codigo

        -- Para filtro por modelo
        LEFT JOIN order_detalle_2 d1 ON d1.codigo_cabecera = oc.codigo

        -- Ventas relacionadas
        LEFT JOIN ventas_cabecera v ON v.pedido_cod LIKE CONCAT('%', oc.codigo, '%')
        -- Guias remision relacionadas
        LEFT JOIN guia_detalle gd 
            ON gd.pedido LIKE CONCAT('%', oc.codigo, '%')
            LEFT JOIN guia_cabecera g ON g.id = gd.id_guia

        $sqlWhere

        GROUP BY oc.codigo
        ORDER BY oc.fecha_creacion DESC;";
		//echo $sql;
		$query = $mbd->prepare($sql);

		foreach ($params as $key => $value) {
			$query->bindValue($key, $value);
		}

		$query->execute();

		$values = [];
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}

		return json_encode([
			'Result' => 'OK',
			'Records' => $values
		]);
	}


	function lista_detalle_2($codigo)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT `id`, `codigo_cabecera`, `modelo`, `color`, COALESCE(`_2`, ' ') _2, COALESCE(`_4`, ' ') _4, COALESCE(`_6`, ' ') _6, COALESCE(`_8`, ' ') _8, COALESCE(`_10`, ' ') _10, COALESCE(`_12`, ' ') _12, COALESCE(`_14`, ' ') _14, COALESCE(`_16`, ' ') _16, COALESCE(`s`, ' ') s, COALESCE(`m`, ' ') m, COALESCE(`l`, ' ') l, COALESCE(`xl`, ' ') xl, COALESCE(`xxl`, ' ') xxl, total,p2,p4,p6,p8,p10,p12,p14,p16,ps,pm,pl,pxl,pxxl,ptotal, n1, n2, n3, n4, n5, n6, n7, n8, n9, n10, n11, n12, n13 FROM order_detalle_2 WHERE codigo_cabecera = :codigo");
		$query->bindParam(':codigo', $codigo);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
	}

	function lista_detalle_produccion($codigo)
	{
		include('env.php');

		$values = array();

		$cabe = $mbd->prepare("SELECT * FROM order_cabecera WHERE codigo = :codigo");
		$cabe->bindParam(":codigo", $codigo);
		$cabe->execute();

		$cabecera = $cabe->fetch(PDO::FETCH_ASSOC);



		$query = $mbd->prepare("SELECT `id`, `codigo_cabecera`, `modelo`, `color`, COALESCE(`_2`, ' ') _2, COALESCE(`_4`, ' ') _4, COALESCE(`_6`, ' ') _6, COALESCE(`_8`, ' ') _8, COALESCE(`_10`, ' ') _10, COALESCE(`_12`, ' ') _12, COALESCE(`_14`, ' ') _14, COALESCE(`_16`, ' ') _16, COALESCE(`s`, ' ') s, COALESCE(`m`, ' ') m, COALESCE(`l`, ' ') l, COALESCE(`xl`, ' ') xl, COALESCE(`xxl`, ' ') xxl, total,p2,p4,p6,p8,p10,p12,p14,p16,ps,pm,pl,pxl,pxxl,ptotal, n1, n2, n3, n4, n5, n6, n7, n8, n9, n10, n11, n12, n13 FROM order_detalle_2 WHERE codigo_cabecera = :codigo");
		$query->bindParam(':codigo', $codigo);
		$query->execute();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values,
			'fecha_entrega' => $cabecera['fecha_entrega'],
			'fecha_entrega_real' => $cabecera['fecha_entrega_real'],
			'guia_remision' => $cabecera['guia_remision'],
			'num_contrato' => $cabecera['num_contrato'],
			"nombre_modelo" => $cabecera['nombre_modelo'],
			"fecha_creacion" => $cabecera['fecha_creacion'],
		);
		return json_encode($result);
	}
	function lista_detalle($codigo)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT p.id, p.unit, ROUND(p.price_in, 2) as price_in, p.name, od.cantidad, ROUND((od.cantidad * p.price_in), 2) as total FROM product as p, order_detalle as od WHERE p.id = od.id_producto AND od.codigo_cabecera = :codigo");
		$query->bindParam(':codigo', $codigo);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
	}
	function sumar_fechas($fecha, $dias)
	{
		$f = new clsOrder;

		$fecha_re = "";
		for ($i = 1; $i <= $dias; $i++) {
			$fecha = date("Y-m-d", strtotime($fecha . "+ 1 days"));
			if (date("N", strtotime($fecha)) == 7) {
				//echo date("N", strtotime($fecha))."<br>";
				$dias++;
			} else {
				$cc = $f->es_feriado($fecha);
				if ($cc > 0) {
					$dias++;
				} else {
					$fecha_re = $fecha;
				}
			}
		}
		//echo $fecha_re;
		return $fecha_re;
	}
	function es_feriado($fecha)
	{
		$anio = date("Y");
		$feriados = array(
			'1' => $anio . '-01-01',
			'2' => $anio . '-05-01',
			'3' => $anio . '-06-24',
			'4' => $anio . '-06-29',
			'5' => $anio . '-06-30',
			'6' => $anio . '-07-28',
			'7' => $anio . '-07-29',
			'8' => $anio . '-08-15',
			'9' => $anio . '-08-30',
			'10' => $anio . '-10-08',
			'11' => $anio . '-11-01',
			'12' => $anio . '-12-25'
		);
		$r = 0;
		foreach ($feriados as $key => $value) {
			//echo $fecha . " == " . $value."<br>";
			if ($fecha == $value) {
				$r++;
			} else {
			}
		}
		return $r;
	}
	function eliminar_order($codigo)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();
			$query = $mbd->prepare("DELETE FROM order_detalle_2 WHERE codigo_cabecera = :codigo");
			$query->bindParam(':codigo', $codigo);
			$query->execute();

			$query2 = $mbd->prepare("DELETE FROM order_cabecera WHERE codigo = :codigo");
			$query2->bindParam(':codigo', $codigo);
			$query2->execute();


			$_query_ = $mbd->prepare("UPDATE aux SET id = id - 1 WHERE tabla = 'order'");
			$_query_->execute();

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function detalle_producto($codigo)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM product WHERE id = :codigo");
		$query->bindParam(':codigo', $codigo);
		$query->execute();
		$values = $query->fetch(PDO::FETCH_ASSOC);
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
	}
	function actualizar_estado($codigo, $estado)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();
			$query = $mbd->prepare("UPDATE order_cabecera SET estado = :estado WHERE codigo = :codigo");
			$query->bindParam(':codigo', $codigo);
			$query->bindParam(':estado', $estado);
			$query->execute();

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function buscar_por_orden($codigo_order)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM order_detalle WHERE codigo_cabecera = :codigo_order");
		$query->bindParam(':codigo_order', $codigo_order);
		$query->execute();
		$values = array();
		while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
			//$values[] = $res;
			$p = $mbd->prepare("SELECT * FROM product WHERE id = :id_producto");
			$p->bindParam(':id_producto', $res['id_producto']);
			$p->execute();
			$pr = $p->fetch(PDO::FETCH_ASSOC);
			$values[] = array(
				'id' => $pr['id'],
				'codigo' => $pr['barcode'],
				'nombre' => $pr['name'],
				'unidad' => $pr['unit'],
				'tipo' => $pr['kind'],
				'precio' => $pr['price_in'],
				'cantidad' => $res['cantidad']
			);
		}
		$result = array(
			'Result' => 'OK',
			'Records' => $values
		);
		return json_encode($result);
	}
	function actualizar_order_produccion($POST, $cant, $codigo)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			//echo $POST['fecha_entrega'];

			$cabecera = $mbd->prepare("UPDATE order_cabecera SET fecha_creacion = :fecha_desde, num_contrato = :num_contrato, fecha_entrega_real = :fecha_entrega_real, fecha_entrega = :fecha_entrega, guia_remision = :guia_remision, nombre_modelo = :nombre_modelo WHERE codigo = :codigo");
			$cabecera->bindParam(":codigo", $codigo);
			$cabecera->bindParam(":fecha_entrega_real", $POST['fecha_entrega']);
			$cabecera->bindParam(":fecha_entrega", $POST['fecha_estimada']);
			$cabecera->bindParam(":guia_remision", $POST['guia']);
			$cabecera->bindParam(":num_contrato", $POST['n_contrato']);
			$cabecera->bindParam(":nombre_modelo", $POST['nombre_modelo']);
			$cabecera->bindParam(":fecha_desde", $POST['fecha_desde']);
			$cabecera->execute();

			$acc = 0;

			for ($i = 1; $i <= $cant; $i++) {
				//echo "p2_ " + $POST['p2_'.$i];
				$total = $POST['p2_' . $i] + $POST['p4_' . $i] + $POST['p6_' . $i] + $POST['p8_' . $i] + $POST['p10_' . $i] + $POST['p12_' . $i] + $POST['p14_' . $i] + $POST['p16_' . $i] + $POST['ps_' . $i] + $POST['pm_' . $i] + $POST['pl_' . $i] + $POST['pxl_' . $i] + $POST['pxxl_' . $i];
				$query = $mbd->prepare("UPDATE order_detalle_2 set p2=:p2,p4=:p4,p6=:p6,p8=:p8,p10=:p10,p12=:p12,p14=:p14,p16=:p16,ps=:ps,pm=:pm,pl=:pl,pxl=:pxl,pxxl=:pxxl,ptotal=:ptotal where id = :pid");
				$query->bindParam(':p2', $POST['p2_' . $i]);
				$query->bindParam(':p4', $POST['p4_' . $i]);
				$query->bindParam(':p6', $POST['p6_' . $i]);
				$query->bindParam(':p8', $POST['p8_' . $i]);
				$query->bindParam(':p10', $POST['p10_' . $i]);
				$query->bindParam(':p12', $POST['p12_' . $i]);
				$query->bindParam(':p14', $POST['p14_' . $i]);
				$query->bindParam(':p16', $POST['p16_' . $i]);
				$query->bindParam(':ps', $POST['ps_' . $i]);
				$query->bindParam(':pm', $POST['pm_' . $i]);
				$query->bindParam(':pl', $POST['pl_' . $i]);
				$query->bindParam(':pxl', $POST['pxl_' . $i]);
				$query->bindParam(':pxxl', $POST['pxxl_' . $i]);
				$query->bindParam(':ptotal', $total);
				$query->bindParam(':pid', $POST['pid_' . $i]);
				$query->execute();
				//echo $total."<br>";
				$acc = $acc + $total;
			}

			/*$query_u = $mbd->prepare("UPDATE order_cabecera SET total = :acc WHERE codigo = :codigo");
			$query_u->bindParam(':acc', $acc);
			$query_u->bindParam(':codigo', $codigo);
			$query_u->execute();*/
			//echo "acc ".$acc;
			//echo "codigo ".$codigo;

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function update_contrato($POST)
	{
		include("env.php");


		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE order_cabecera SET num_contrato = :num_contrato WHERE codigo = :codigo");
			$query->execute($POST);

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function update_guia($POST)
	{
		include("env.php");


		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE order_cabecera SET guia_remision = :num_contrato WHERE codigo = :codigo");
			$query->execute($POST);

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function nuevo_order($POST, $cant)
	{
		foreach ($POST as $clave => $valor) {
			if (empty($valor)) {
				$POST[$clave] = NULL;
			} else {
			}
		}
		include('env.php');

		$aux = new clsOrder();
		$id = json_decode($aux->id_order());
		$codigo = date("y") . str_pad($id->id, 2, "0", STR_PAD_LEFT);

		$fecha_re = $aux->sumar_fechas($POST['fecha_desde'], $POST['tiempo_entrega']);

		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();
			$query = $mbd->prepare("INSERT INTO order_cabecera(num_contrato, fecha_creacion, codigo, tiempo_entrega, person_id, estado, fecha_entrega, comentario, imagen_alt, nombre_modelo) VALUES(:num_contrato, :fecha_creacion, :codigo, :tiempo_entrega, :cliente, 0, :fecha_entrega, :comentario, :imagen_alt, :nombre_modelo)");
			$query->bindParam(':fecha_creacion', $POST['fecha_desde']);
			$query->bindParam(':fecha_entrega', $fecha_re);
			$query->bindParam(':codigo', $codigo);
			$query->bindParam(':tiempo_entrega', $POST['tiempo_entrega']);
			$query->bindParam(':cliente', $POST['s_cliente']);
			$query->bindParam(':comentario', $POST['comentario']);
			$query->bindParam(':num_contrato', $POST['num_contrato']);
			$query->bindParam(':imagen_alt', $POST['imagen_alt']);
			$query->bindParam(':nombre_modelo', $POST['nombre_producto']);
			$query->execute();

			$acc = 0;

			for ($i = 1; $i <= $cant; $i++) {
				$q = $mbd->prepare("INSERT INTO order_detalle_2(codigo_cabecera, modelo, _2, _4, _6, _8, _10, _12, _14, _16, s, m, l, xl, xxl, color, total, n1, n2, n3, n4, n5, n6, n7, n8, n9, n10, n11, n12, n13) VALUES (:codigo_cabecera, :modelo, :2, :4, :6, :8, :10, :12, :14, :16, :s, :m, :l, :xl, :xxl, :color, :total, :n1, :n2, :n3, :n4, :n5, :n6, :n7, :n8, :n9, :n10, :n11, :n12, :n13)");
				$q->bindParam(':codigo_cabecera', $codigo);
				$q->bindParam(':modelo', $POST['nn_0_' . $i]);
				$q->bindParam(':2', $POST['nn_2_' . $i]);
				$q->bindParam(':4', $POST['nn_3_' . $i]);
				$q->bindParam(':6', $POST['nn_4_' . $i]);
				$q->bindParam(':8', $POST['nn_5_' . $i]);
				$q->bindParam(':10', $POST['nn_6_' . $i]);
				$q->bindParam(':12', $POST['nn_7_' . $i]);
				$q->bindParam(':14', $POST['nn_8_' . $i]);
				$q->bindParam(':16', $POST['nn_9_' . $i]);
				$q->bindParam(':s', $POST['nn_10_' . $i]);
				$q->bindParam(':m', $POST['nn_11_' . $i]);
				$q->bindParam(':l', $POST['nn_12_' . $i]);
				$q->bindParam(':xl', $POST['nn_13_' . $i]);
				$q->bindParam(':xxl', $POST['nn_14_' . $i]);
				$q->bindParam(':color', $POST['nn_1_' . $i]);
				$q->bindParam(':total', $POST['tot_' . $i]);

				$q->bindParam(':n1', $POST['celda_1_' . $i]);
				$q->bindParam(':n2', $POST['celda_2_' . $i]);
				$q->bindParam(':n3', $POST['celda_3_' . $i]);
				$q->bindParam(':n4', $POST['celda_4_' . $i]);
				$q->bindParam(':n5', $POST['celda_5_' . $i]);
				$q->bindParam(':n6', $POST['celda_6_' . $i]);
				$q->bindParam(':n7', $POST['celda_7_' . $i]);
				$q->bindParam(':n8', $POST['celda_8_' . $i]);
				$q->bindParam(':n9', $POST['celda_9_' . $i]);
				$q->bindParam(':n10', $POST['celda_10_' . $i]);
				$q->bindParam(':n11', $POST['celda_11_' . $i]);
				$q->bindParam(':n12', $POST['celda_12_' . $i]);
				$q->bindParam(':n13', $POST['celda_13_' . $i]);
				$q->execute();

				$acc = $acc + $POST['tot_' . $i];
			}

			$query_u = $mbd->prepare("UPDATE order_cabecera SET total = :acc WHERE codigo = :codigo");
			$query_u->bindParam(':acc', $acc);
			$query_u->bindParam(':codigo', $codigo);
			$query_u->execute();

			/*$m = new clsOrder;
			$m->eliminar_null();*/
			$query_null = $mbd->prepare("DELETE FROM order_detalle_2 WHERE modelo IS NULL");
			$query_null->execute();
			//$m->enviar_correo($POST['s_cliente'], $POST, $cant, $tiempo_entrega, $codigo, $POST['comentario'], $POST['usuario']);
			//$m->enviar_correo($POST['s_cliente'], $POST, $cant, $POST['tiempo_entrega'], $codigo, $POST['comentario'], $POST['usuario']);


			/*$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			$aux->update_id_order();*/
			// AFTER (correct order):
			$query_upd = $mbd->prepare("UPDATE aux SET id = id + 1 WHERE tabla = 'order'");
			$query_upd->execute();
			$mbd->commit();
			//$aux->update_id_order();

			// Send email AFTER committing — outside the transaction
			$m = new clsOrder;
			/*$m->eliminar_null();*/
			$query_null = $mbd->prepare("DELETE FROM order_detalle_2 WHERE modelo IS NULL");
			$query_null->execute();
			$m->enviar_correo($POST['s_cliente'], $POST, $cant, $POST['tiempo_entrega'], $codigo, $POST['comentario'], $POST['usuario'], $mbd);

			$result = array('Result' => 'OK');
			return json_encode($result);
			//return json_encode($result);
		} catch (Exception $e) {
			/*$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);*/
			try {
				$mbd->rollBack();
			} catch (Exception $rollbackEx) {
				// Connection already gone, nothing to roll back
			}
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	public function editar_order_detalle($POST)
	{
		include('env.php');

		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$q = $mbd->prepare("UPDATE order_detalle_2 SET 
			_2 = :_2, 
			_4 = :_4, 
			_6 = :_6, 
			_8 = :_8, 
			_10 = :_10, 
			_12 = :_12, 
			_14 = :_14, 
			_16 = :_16, 
			s = :s, 
			m = :m, 
			l = :l, 
			xl = :xl, 
			xxl = :xxl,
			total = :total, 
			n1 = :n1, 
			n2 = :n2, 
			n3 = :n3, 
			n4 = :n4, 
			n5 = :n5, 
			n6 = :n6, 
			n7 = :n7, 
			n8 = :n8, 
			n9 = :n9, 
			n10 = :n10, 
			n11 = :n11, 
			n12 = :n12, 
			n13 = :n13 WHERE id = :id");

			if (empty($POST['_2']) || is_null($POST['_2'])) {
				$POST['_2'] = null;
			}
			if (empty($POST['_4']) || is_null($POST['_4'])) {
				$POST['_4'] = null;
			}
			if (empty($POST['_6']) || is_null($POST['_6'])) {
				$POST['_6'] = null;
			}
			if (empty($POST['_8']) || is_null($POST['_8'])) {
				$POST['_8'] = null;
			}
			if (empty($POST['_10']) || is_null($POST['_10'])) {
				$POST['_10'] = null;
			}
			if (empty($POST['_12']) || is_null($POST['_12'])) {
				$POST['_12'] = null;
			}
			if (empty($POST['_14']) || is_null($POST['_14'])) {
				$POST['_14'] = null;
			}
			if (empty($POST['_16']) || is_null($POST['_16'])) {
				$POST['_16'] = null;
			}
			if (empty($POST['_s']) || is_null($POST['_s'])) {
				$POST['_s'] = null;
			}
			if (empty($POST['_m']) || is_null($POST['_m'])) {
				$POST['_m'] = null;
			}
			if (empty($POST['_l']) || is_null($POST['_l'])) {
				$POST['_l'] = null;
			}
			if (empty($POST['_xl']) || is_null($POST['_xl'])) {
				$POST['_xl'] = null;
			}
			if (empty($POST['_xxl']) || is_null($POST['_xxl'])) {
				$POST['_xxl'] = null;
			}
			if (empty($POST['_total']) || is_null($POST['_total'])) {
				$POST['_total'] = null;
			}

			$q->bindParam(':id', $POST['id']);
			$q->bindParam(':_2', $POST['_2']);
			$q->bindParam(':_4', $POST['_4']);
			$q->bindParam(':_6', $POST['_6']);
			$q->bindParam(':_8', $POST['_8']);
			$q->bindParam(':_10', $POST['_10']);
			$q->bindParam(':_12', $POST['_12']);
			$q->bindParam(':_14', $POST['_14']);
			$q->bindParam(':_16', $POST['_16']);
			$q->bindParam(':s', $POST['_s']);
			$q->bindParam(':m', $POST['_m']);
			$q->bindParam(':l', $POST['_l']);
			$q->bindParam(':xl', $POST['_xl']);
			$q->bindParam(':xxl', $POST['_xxl']);
			$q->bindParam(':total', $POST['_total']);

			$q->bindParam(':n1', $POST['n1']);
			$q->bindParam(':n2', $POST['n2']);
			$q->bindParam(':n3', $POST['n3']);
			$q->bindParam(':n4', $POST['n4']);
			$q->bindParam(':n5', $POST['n5']);
			$q->bindParam(':n6', $POST['n6']);
			$q->bindParam(':n7', $POST['n7']);
			$q->bindParam(':n8', $POST['n8']);
			$q->bindParam(':n9', $POST['n9']);
			$q->bindParam(':n10', $POST['n10']);
			$q->bindParam(':n11', $POST['n11']);
			$q->bindParam(':n12', $POST['n12']);
			$q->bindParam(':n13', $POST['n13']);
			$q->execute();

			$acc = $POST['_total'];


			$query_u = $mbd->prepare("UPDATE order_cabecera SET total = (total - :anterior) + :acc WHERE codigo = (select codigo_cabecera from order_detalle_2 where id = :id)");
			$query_u->bindParam(':anterior', $POST['total_anterior']);
			$query_u->bindParam(':acc', $acc);
			$query_u->bindParam(':id', $POST['id']);
			$query_u->execute();

			// $m = new clsOrder;
			// $m->eliminar_null();

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	public function edit_order($POST, $cant)
	{
		foreach ($POST as $clave => $valor) {
			if (empty($valor)) {
				$POST[$clave] = NULL;
			} else {
			}
		}
		include('env.php');

		$aux = new clsOrder();
		$id = json_decode($aux->id_order());
		$codigo = $_POST['codigo'];

		$fecha_re = $aux->sumar_fechas($POST['fecha_desde'], $POST['tiempo_entrega']);

		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();
			$query = $mbd->prepare("UPDATE order_cabecera SET num_contrato = :num_contrato, tiempo_entrega = :tiempo_entrega, person_id = :cliente, fecha_entrega = :fecha_entrega, comentario = :comentario, imagen_alt = :imagen_alt, nombre_modelo = :nombre_modelo WHERE codigo = :codigo");
			$query->bindParam(':fecha_entrega', $fecha_re);
			$query->bindParam(':codigo', $codigo);
			$query->bindParam(':tiempo_entrega', $POST['tiempo_entrega']);
			$query->bindParam(':cliente', $POST['s_cliente']);
			$query->bindParam(':comentario', $POST['comentario']);
			$query->bindParam(':num_contrato', $POST['num_contrato']);
			$query->bindParam(':imagen_alt', $POST['imagen_alt']);
			$query->bindParam(':nombre_modelo', $POST['nombre_producto']);
			$query->execute();

			$acc = 0;
			$qd = $mbd->prepare("DELETE FROM order_detalle_2 where codigo_cabecera = :codigo");
			$qd->bindParam(":codigo", $_POST['codigo']);
			$qd->execute();

			for ($i = 1; $i <= $cant; $i++) {
				$q = $mbd->prepare("INSERT INTO order_detalle_2(codigo_cabecera, modelo, _2, _4, _6, _8, _10, _12, _14, _16, s, m, l, xl, xxl, color, total, n1, n2, n3, n4, n5, n6, n7, n8, n9, n10, n11, n12, n13) VALUES (:codigo_cabecera, :modelo, :2, :4, :6, :8, :10, :12, :14, :16, :s, :m, :l, :xl, :xxl, :color, :total, :n1, :n2, :n3, :n4, :n5, :n6, :n7, :n8, :n9, :n10, :n11, :n12, :n13)");
				$q->bindParam(':codigo_cabecera', $codigo);
				$q->bindParam(':modelo', $POST['nn_0_' . $i]);
				$q->bindParam(':2', $POST['nn_2_' . $i]);
				$q->bindParam(':4', $POST['nn_3_' . $i]);
				$q->bindParam(':6', $POST['nn_4_' . $i]);
				$q->bindParam(':8', $POST['nn_5_' . $i]);
				$q->bindParam(':10', $POST['nn_6_' . $i]);
				$q->bindParam(':12', $POST['nn_7_' . $i]);
				$q->bindParam(':14', $POST['nn_8_' . $i]);
				$q->bindParam(':16', $POST['nn_9_' . $i]);
				$q->bindParam(':s', $POST['nn_10_' . $i]);
				$q->bindParam(':m', $POST['nn_11_' . $i]);
				$q->bindParam(':l', $POST['nn_12_' . $i]);
				$q->bindParam(':xl', $POST['nn_13_' . $i]);
				$q->bindParam(':xxl', $POST['nn_14_' . $i]);
				$q->bindParam(':color', $POST['nn_1_' . $i]);
				$q->bindParam(':total', $POST['tot_' . $i]);

				$q->bindParam(':n1', $POST['celda_1_' . $i]);
				$q->bindParam(':n2', $POST['celda_2_' . $i]);
				$q->bindParam(':n3', $POST['celda_3_' . $i]);
				$q->bindParam(':n4', $POST['celda_4_' . $i]);
				$q->bindParam(':n5', $POST['celda_5_' . $i]);
				$q->bindParam(':n6', $POST['celda_6_' . $i]);
				$q->bindParam(':n7', $POST['celda_7_' . $i]);
				$q->bindParam(':n8', $POST['celda_8_' . $i]);
				$q->bindParam(':n9', $POST['celda_9_' . $i]);
				$q->bindParam(':n10', $POST['celda_10_' . $i]);
				$q->bindParam(':n11', $POST['celda_11_' . $i]);
				$q->bindParam(':n12', $POST['celda_12_' . $i]);
				$q->bindParam(':n13', $POST['celda_13_' . $i]);
				$q->execute();

				$acc = $acc + $POST['tot_' . $i];
			}

			$query_u = $mbd->prepare("UPDATE order_cabecera SET total = :acc WHERE codigo = :codigo");
			$query_u->bindParam(':acc', $acc);
			$query_u->bindParam(':codigo', $codigo);
			$query_u->execute();

			/*$m = new clsOrder;
			$m->eliminar_null();*/
			$query_null = $mbd->prepare("DELETE FROM order_detalle_2 WHERE modelo IS NULL");
			$query_null->execute();
			//$m->enviar_correo($POST['s_cliente'], $POST, $cant, $tiempo_entrega, $codigo, $POST['comentario'], $POST['usuario']);


			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			//$aux->update_id_order();
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	public function para_cabecera($codigo_cabecera)
	{
		include('env.php');
		$query = $mbd->prepare("DELETE FROM order_detalle_2 WHERE modelo IS NULL");
		$query->execute();
	}
	/*function eliminar_null()
	{
		include('env.php');
		$query = $mbd->prepare("DELETE FROM order_detalle_2 WHERE modelo IS NULL");
		$query->execute();
	}*/
	function eliminar_imagen($id)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE product SET image = null WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->execute();

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function eliminar_bordado($id)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("UPDATE product SET imgbordado = null WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->execute();

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	function buscar_modelo($product_name)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();

			$query = $mbd->prepare("SELECT * FROM product WHERE code = :product_name");
			$query->bindParam(':product_name', $product_name);
			$query->execute();

			$mbd->commit();
			/*$result = array(
					'Result' => 'OK'
				);*/
			return json_encode($query->fetch(PDO::FETCH_ASSOC));
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
	public function get_orden($codigo)
	{
		include('env.php');
		$query = $mbd->prepare("SELECT * FROM order_cabecera WHERE codigo = :codigo");
		$query->bindParam(":codigo", $codigo);
		$query->execute();

		$query_2 = $mbd->prepare("SELECT * FROM order_detalle_2 where codigo_cabecera = :codigo");
		$query_2->bindParam(":codigo", $codigo);
		$query_2->execute();

		$values = array();
		while ($res = $query_2->fetch(PDO::FETCH_ASSOC)) {
			$values[] = $res;
		}

		echo json_encode(array(
			"cabecera" => $query->fetch(PDO::FETCH_ASSOC),
			"detalle" => $values
		));
	}
	public function eliminar_order_detalle($id)
	{
		include('env.php');
		try {
			$mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$mbd->beginTransaction();
			$q = $mbd->prepare("SELECT * FROM order_detalle_2 WHERE id = :id");
			$q->bindParam(":id", $id);
			$q->execute();
			$d = $q->fetch(PDO::FETCH_ASSOC);

			$query = $mbd->prepare("DELETE FROM order_detalle_2 WHERE id = :id");
			$query->bindParam(':id', $id);
			$query->execute();

			$query_u = $mbd->prepare("UPDATE order_cabecera SET total = total - :acc WHERE codigo = :codigo");
			$query_u->bindParam(':acc', $d['total']);
			$query_u->bindParam(':codigo', $d['codigo_cabecera']);
			$query_u->execute();

			$mbd->commit();
			$result = array(
				'Result' => 'OK'
			);
			return json_encode($result);
		} catch (Exception $e) {
			$mbd->rollBack();
			$result = array(
				'Result' => $e->getMessage()
			);
			return json_encode($result);
		}
	}
}

