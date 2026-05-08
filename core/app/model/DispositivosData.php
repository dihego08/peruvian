<?php
class DispositivosData
{
	public static $tablename = "dispositivos";

	public function DispositivosData()
	{
		$maquina->codigo = "";
		$maquina->descripcion = "";
		$maquina->cantidad = "";
		$maquina->observaciones = "";
		$maquina->imagen = "";
	}

	public function add()
	{
		$sql = "INSERT into dispositivos(descripcion, cantidad, imagen, observaciones, codigo, fecha, responsable) ";
		$sql .= "values('$this->descripcion', $this->cantidad, '$this->imagen', '$this->observaciones', '$this->codigo', '$this->fecha', '$this->responsable')";
		Executor::doit($sql);
	}

	public static function delById($id)
	{
		$sql = "delete from " . self::$tablename . " where maquina_id=$id";
		Executor::doit($sql);
	}
	public function del()
	{
		$sql = "delete from " . self::$tablename . " where maquina_id=$this->maquina_id";
		Executor::doit($sql);
	}

	// partiendo de que ya tenemos creado un objecto DispositivosData previamente utilizamos el contexto
	public function update()
	{
		$sql = "UPDATE " . self::$tablename . " SET 
		codigo = '$this->codigo',
		descripcion = '$this->descripcion',
		cantidad = $this->cantidad,
		observaciones = '$this->observaciones',
		fecha = '$this->fecha',
		responsable = '$this->responsable'

		where id=$this->id";

		Executor::doit($sql);
	}

	public function update_image()
	{
		$sql = "update " . self::$tablename . " set imagen = '$this->imagen' where id = $this->id";

		//echo($sql);
		Executor::doit($sql);
	}


	public function update_image_factura()
	{
		$sql = "update " . self::$tablename . " set factura_compra = '$this->factura_compra' where maquina_id=$this->maquina_id";

		//echo($sql);
		Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "select * from " . self::$tablename . " where id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new DispositivosData());
	}
	public static function get_max($campo, $id)
	{
		$sql = "SELECT $campo as campo from registro_dispositivo where id_dispositivo = $id order by id DESC LIMIT 1";
		$query = Executor::doit($sql);
		$query = Model::one($query[0], new DispositivosData());
		return $query->campo;
	}
	public static function getAll()
	{
		$sql = "select * from " . self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0], new DispositivosData());
	}

	public static function getBajas()
	{
		$sql = "select * from " . self::$tablename . " where maquina_estado = '0'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new DispositivosData());
	}


	public static function getLike($q)
	{
		$sql = "select * from " . self::$tablename . " where maquina_descripcion like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new DispositivosData());
	}
}
