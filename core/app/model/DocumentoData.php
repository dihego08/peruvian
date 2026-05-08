<?php
class DocumentoData {
	public static $tablename = "kind_doc";



	public function DocumentoData(){
		$this->tipo_documento = "";
		$this->numero = "";
	}

	public function add(){
		$sql = "insert into category (name,created_at, image) ";
		$sql .= "value (\"$this->name\",$this->created_at, \"$this->image\")";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto CategoryData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\", image = \"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";

		$query = Executor::doit($sql);

		echo json_encode(Model::one($query[0],new DocumentoData()));

		//return Model::one($query[0],new DocumentoData());
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new DocumentoData());
	}


	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new DocumentoData());
	}


}

?>