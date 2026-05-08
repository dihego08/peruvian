<?php
class MaquinaData {
	public static $tablename = "tbl_maquina";



	public function MaquinaData(){
		$this->maquina_id = "";
		$this->maquina_codigo = "";
		$this->maquina_descripcion = "";
		$this->maquina_marca = "";
		$this->maquina_modelo = "";
		$this->maquina_serie = "";
		$this->maquina_marca_motor = "";
		$this->maquina_serie_motor  = "";
		$this->maquina_exigencias  = "";
		$this->maquina_voltaje  = "";
		$this->maquina_tipo_corriente = "";
		$this->maquina_anio_compra = "";
		$this->maquina_vida_util  = "";
		$this->maquina_imagen = "";
		$this->maquina_ubicacion = "";
		$this->maquina_tipo = "";
		$this->maquina_estado = "1";
		$this->maquina_fecha_registro = "NOW()";
		$this->precio_compra = "";
		$this->proveedor = "";
	}

	public function add(){
		$sql = "INSERT into tbl_maquina(maquina_codigo,maquina_descripcion,maquina_marca,maquina_modelo,maquina_serie,maquina_marca_motor,maquina_serie_motor,maquina_exigencias,maquina_voltaje,maquina_tipo_corriente,maquina_anio_compra,maquina_vida_util,maquina_imagen,maquina_fecha_registro,maquina_tipo,maquina_ubicacion,maquina_estado, precio_compra, proveedor) ";
		$sql .= "value ('$this->maquina_codigo','$this->maquina_descripcion','$this->maquina_marca','$this->maquina_modelo','$this->maquina_serie','$this->maquina_marca_motor','$this->maquina_serie_motor','$this->maquina_exigencias','$this->maquina_voltaje','$this->maquina_tipo_corriente','$this->maquina_anio_compra','$this->maquina_vida_util','$this->maquina_imagen','$this->maquina_fecha_registro','$this->maquina_tipo','$this->maquina_ubicacion','$this->maquina_estado', $this->precio_compra, '$this->proveedor')";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where maquina_id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where maquina_id=$this->maquina_id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto MaquinaData previamente utilizamos el contexto
	public function update(){
		$sql = "UPDATE ".self::$tablename." SET maquina_codigo = '$this->maquina_codigo',
		maquina_descripcion = '$this->maquina_descripcion',
		maquina_marca= '$this->maquina_marca',
		maquina_modelo= '$this->maquina_modelo',
		maquina_serie= '$this->maquina_serie',
		maquina_marca_motor= '$this->maquina_marca_motor',
		maquina_serie_motor= '$this->maquina_serie_motor',
		maquina_exigencias= '$this->maquina_exigencias',
		maquina_voltaje= '$this->maquina_voltaje',
		maquina_tipo_corriente= '$this->maquina_tipo_corriente',
		maquina_anio_compra= '$this->maquina_anio_compra',
		maquina_vida_util= '$this->maquina_vida_util',
		maquina_tipo= '$this->maquina_tipo',
		maquina_ubicacion= '$this->maquina_ubicacion',
		maquina_estado= '$this->maquina_estado',

		precio_compra = $this->precio_compra,
		proveedor= '$this->proveedor'

		where maquina_id=$this->maquina_id";

		//echo($sql);
		Executor::doit($sql);
	}

	public function update_image(){
		$sql = "update ".self::$tablename." set maquina_imagen = '$this->maquina_imagen' where maquina_id=$this->maquina_id";

		//echo($sql);
		Executor::doit($sql);
	}


	public function update_image_factura(){
		$sql = "update ".self::$tablename." set factura_compra = '$this->factura_compra' where maquina_id=$this->maquina_id";

		//echo($sql);
		Executor::doit($sql);
	}
	
	public static function getById($id){
		$sql = "select * from ".self::$tablename." where maquina_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new MaquinaData());
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename." where maquina_estado = '1'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MaquinaData());
	}

	public static function getBajas(){
		$sql = "select * from ".self::$tablename." where maquina_estado = '0'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MaquinaData());
	}


	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where maquina_descripcion like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MaquinaData());
	}


}

?>