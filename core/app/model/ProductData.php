<?php
class ProductData
{
	public static $tablename = "product";

	public function ProductData()
	{
		$this->name = "";
		$this->price_in = "";
		$this->price_in_2 = "";
		$this->price_out = "";
		$this->unit = "";
		$this->user_id = "";
		$this->image = "";
		$this->presentation = "0";
		$this->created_at = "NOW()";

		$this->imgbordado = "";
		$this->cliente_id = "";
		$this->prebor_in = "";
		$this->prebor_out = "";
		$this->fecact = "";
		$this->secuencia = "";
	}

	public function getCategory()
	{
		return CategoryData::getById($this->category_id);
	}

	public function getCliente()
	{
		return PersonData::getById($this->cliente_id);
	}

	public function add()
	{

		$sql = "INSERT into " . self::$tablename . " (image,kind,code,brand_id,width,height,weight,barcode,name,description,price_in, price_in_2,price_out,user_id,presentation,unit,category_id,inventary_min,created_at,imgbordado,cliente_id,prebor_in,prebor_out,fecact, large, secuencia) ";
		$sql .= "value (\"$this->image\",\"$this->kind\",\"$this->code\",$this->brand_id,\"$this->width\",\"$this->height\",\"$this->weight\",\"$this->barcode\",\"$this->name\",\"$this->description\",\"$this->price_in\", \"$this->price_in_2\",\"$this->price_out\",$this->user_id,\"$this->presentation\",\"$this->unit\",$this->category_id,$this->inventary_min,NOW(),\"$this->imgbordado\",\"$this->cliente_id\",\"$this->prebor_in\",\"$this->prebor_out\",\"$this->fecact\",\"$this->large\", \"$this->secuencia\")";


		return Executor::doit($sql);
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


	// partiendo de que ya tenemos creado un objecto ProductData previamente utilizamos el contexto
	public function update()
	{
		$sql = "update " . self::$tablename . " set is_active=\"$this->is_active\",barcode=\"$this->barcode\",name=\"$this->name\",price_in=\"$this->price_in\",price_in_2=\"$this->price_in_2\",price_out=\"$this->price_out\",unit=\"$this->unit\",presentation=\"$this->presentation\",category_id=$this->category_id,inventary_min=\"$this->inventary_min\",description=\"$this->description\",is_active=\"$this->is_active\",code=\"$this->code\",width=\"$this->width\",large=\"$this->large\",height=\"$this->height\",weight=\"$this->weight\",imgbordado=\"$this->imgbordado\",cliente_id=\"$this->cliente_id\",prebor_in=\"$this->prebor_in\",prebor_out=\"$this->prebor_out\",fecact=\"$this->fecact\",brand_id=$this->brand_id where id=$this->id";
		Executor::doit($sql);
	}

	public function del_category()
	{
		$sql = "update " . self::$tablename . " set category_id=NULL where id=$this->id";
		Executor::doit($sql);
	}

	public function del_brand()
	{
		$sql = "update " . self::$tablename . " set brand_id=NULL where id=$this->id";
		Executor::doit($sql);
	}


	public function update_image()
	{
		$sql = "update " . self::$tablename . " set image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_imageBordado()
	{
		$sql = "update " . self::$tablename . " set imgbordado=\"$this->imgbordado\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_secuencia()
	{
		$sql = "update " . self::$tablename . " set secuencia=\"$this->secuencia\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_prices()
	{
		$sql = "update " . self::$tablename . " set price_in=\"$this->price_in\",price_out=\"$this->price_out\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "SELECT * from " . self::$tablename . " where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ProductData());
	}

	public static function getAll()
	{
		$sql = "SELECT product.*, person.name as cliente from " . self::$tablename . ", person WHERE product.is_active = '1' AND product.cliente_id = person.id order by product.fecact desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}

	public static function getAllByCategoryId($id)
	{
		$sql = "SELECT * from " . self::$tablename . " where is_active = '1' and category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}

	public static function getAllByPage($start_from, $limit)
	{
		$sql = "SELECT * from " . self::$tablename . " where id>=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}


	public static function getLike($p)
	{
		$sql = "SELECT * from " . self::$tablename . " where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%') and is_active=1";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}

	public static function getLikeCat($p, $cat)
	{
		$sql = "SELECT * from " . self::$tablename . " where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%') and category_id=$cat and is_active=1";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}

	public static function getLike2($p)
	{
		$sql = "SELECT * from " . self::$tablename . " where (code like '%$p%' or barcode like '%$p%' or name like '%$p%' or id like '%$p%') and kind=1 and is_active=1";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}


	public static function getAllByUserId($user_id)
	{
		$sql = "SELECT * from " . self::$tablename . " where user_id=$user_id and is_active = '1' order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}

	public static function getAllByClienteId($cli_id)
	{
		$sql = "SELECT * from " . self::$tablename . " where cliente_id IN ($cli_id) and is_active = '1' order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}

	public static function OnlyProducts()
	{
		$sql = "SELECT * from " . self::$tablename . " WHERE kind = 1 order by fecact desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}

	public static function getAllByFilter($cliente, $modelo, $nombre, $estado)
	{
		$sql = "SELECT * from " . self::$tablename . " WHERE is_active = '$estado'";
		if ($cliente != "") {
			$sql .= " and cliente_id = '$cliente'";
		}
		if ($modelo != "") {
			$sql .= " and code = '$modelo'";
		}
		if ($nombre != "") {
			$sql .= " and name like '%$nombre%'";
		}
		$sql .= " order by created_at desc";

		$query = Executor::doit($sql);
		return Model::many($query[0], new ProductData());
	}
}
