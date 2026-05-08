<?php
class InsumosData
{
	public static $tablename = "insumos_2";


	public function InsumosData()
	{
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->company = "";
		$this->created_at = "NOW()";
		$this->credit_limit = "NULL";
	}

	public function add_client()
	{
		$sql = "insert into person (no,name,lastname,address1,email1,phone1,is_active_access,password,kind,credit_limit,has_credit,created_at, wsp, banco, nro_cuenta, tipo_pago) ";
		$sql .= "value (\"$this->no\",\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",\"$this->is_active_access\",\"$this->password\",1,\"$this->credit_limit\",$this->has_credit,$this->created_at,\"$this->wsp\", \"$this->banco\", \"$this->nro_cuenta\", \"$this->tipo_pago\")";
		Executor::doit($sql);
	}

	public function add_provider()
	{
		$sql = "insert into person (no,name,lastname,address1,email1,phone1,kind,created_at, nro_cuenta, tipo_cuenta, tipo_moneda, forma_envio, banco, wsp, id_insumo) ";
		$sql .= "value (\"$this->no\",\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",2,$this->created_at, \"$this->nro_cuenta\", \"$this->tipo_cuenta\", \"$this->tipo_moneda\", \"$this->forma_envio\", \"$this->banco\",\"$this->wsp\", \"$this->id_insumo\")";
		Executor::doit($sql);
	}


	public function add_contact()
	{
		$sql = "insert into person (name,lastname,address1,email1,phone1,kind,created_at) ";
		$sql .= "value (\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",3,$this->created_at)";
		Executor::doit($sql);
	}

	public static function delById($id)
	{
		$sql = "delete from " . self::$tablename . " where id=$id";
		Executor::doit($sql);
	}
	public function del()
	{
		$sql = "delete from " . self::$tablename . " where id=$this->id";
		Executor::doit($sql);
	}

	// partiendo de que ya tenemos creado un objecto PersonData previamente utilizamos el contexto
	public function update()
	{
		$sql = "update " . self::$tablename . " set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\", where id=$this->id";
		Executor::doit($sql);
	}

	public function update_client()
	{
		$sql = "update " . self::$tablename . " set no=\"$this->no\",name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\",is_active_access=\"$this->is_active_access\",password=\"$this->password\",has_credit=\"$this->has_credit\",credit_limit=\"$this->credit_limit\", wsp = \"$this->wsp\", nro_cuenta = \"$this->nro_cuenta\" , banco = \"$this->banco\", tipo_pago = \"$this->tipo_pago\",company= \"$this->company\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_provider()
	{
		$sql = "update " . self::$tablename . " set no=\"$this->no\",name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\", nro_cuenta=\"$this->nro_cuenta\", tipo_cuenta=\"$this->tipo_cuenta\", tipo_moneda=\"$this->tipo_moneda\", forma_envio=\"$this->forma_envio\", banco=\"$this->banco\", wsp = \"$this->wsp\", id_insumo = \"$this->id_insumo\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_contact()
	{
		$sql = "update " . self::$tablename . " set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\" where id=$this->id";
		Executor::doit($sql);
	}


	public function update_passwd()
	{
		$sql = "update " . self::$tablename . " set password=\"$this->password\" where id=$this->id";
		Executor::doit($sql);
	}


	public static function getById($id)
	{
        if(empty($id) || is_null($id)){
            return null;
        }else{
            $sql = "select * from " . self::$tablename . " where id=$id";
            $query = Executor::doit($sql);
            return Model::one($query[0], new InsumosData());
        }
		
	}



	public static function getAll()
	{
		$sql = "select * from " . self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0], new PersonData());
	}

	public static function getClients()
	{
		$sql = "select * from " . self::$tablename . " where kind=1 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PersonData());
	}

	public static function getClientsWithCredit()
	{
		$sql = "select * from " . self::$tablename . " where kind=1 and has_credit=1 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PersonData());
	}

	public static function getContacts()
	{
		$sql = "select * from " . self::$tablename . " where kind = 3 order by name, lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PersonData());
	}

	public static function getProviders()
	{
		$sql = "select * from " . self::$tablename . " where kind=2 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PersonData());
	}

	public static function getLike($q)
	{
		$sql = "select * from " . self::$tablename . " where name like '%$q%' or no like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PersonData());
	}

	public static function getLikeNo($q)
	{
		$sql = "select * from " . self::$tablename . " where no like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PersonData());
	}
}
