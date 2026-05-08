<?php
class SellData {
	public static $tablename = "sell";

	public function SellData(){
		$this->created_at = "NOW()";
		$this->ref_id=NULL;
	}

	public function getPerson(){ return PersonData::getById($this->person_id);}
	public function getUser(){ return UserData::getById($this->user_id);}
	public function getP(){ return PData::getById($this->p_id);}
	public function getD(){ return DData::getById($this->d_id);}
	public function getStockFrom(){ return StockData::getById($this->stock_from_id);}
	public function getStockTo(){ return StockData::getById($this->stock_to_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (comment,ref_id,person_id,stock_to_id,iva,p_id,d_id,total,discount,cash,user_id,created_at,detraccion) ";
		$sql .= "value (\"$this->comment\",$this->ref_id,$this->person_id,$this->stock_to_id,$this->iva,$this->p_id,$this->d_id,$this->total,$this->discount,$this->cash,$this->user_id,$this->created_at,$this->detraccion)";
		return Executor::doit($sql);
	}
	public function add_traspase(){
		$sql = "insert into ".self::$tablename." (stock_to_id,stock_from_id,operation_type_id,iva,p_id,d_id,total,discount,user_id,created_at) ";
		$sql .= "value ($this->stock_to_id,$this->stock_from_id,6,$this->iva,$this->p_id,$this->d_id,$this->total,$this->discount,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}
	public function add_cotization(){
		$sql = "insert into ".self::$tablename." (is_draft,p_id,d_id,user_id,created_at) ";
		$sql .= "value (1,2,2,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}
	public function add_cotization_by_client(){
		$sql = "insert into ".self::$tablename." (is_draft,p_id,d_id,person_id,created_at) ";
		$sql .= "value (1,2,2,$this->person_id,$this->created_at)";
		return Executor::doit($sql);
	}
	public function add_de(){
		$sql = "insert into ".self::$tablename." (status,stock_to_id,sell_from_id,user_id,operation_type_id,created_at) ";
		$sql .= "value (0,$this->stock_to_id,$this->sell_from_id,$this->user_id,5,$this->created_at)";
		return Executor::doit($sql);
	}
	public function add_re(){
		$sql = "insert into ".self::$tablename."(invoice_code,f_id,ref_id,person_id,stock_to_id,total,p_id,d_id,user_id,operation_type_id,created_at) ";
		$sql .= "value (\"$this->invoice_code\",$this->f_id,$this->ref_id, $this->person_id,$this->stock_to_id,$this->total,$this->p_id,$this->d_id,$this->user_id,1,$this->created_at)";
		return Executor::doit($sql);
	}
	public function add_with_client(){	
		$sql = "insert into ".self::$tablename." (iva,p_id,d_id,total,discount,person_id,user_id,created_at) ";
		$sql .= "value ($this->iva,$this->p_id,$this->d_id,$this->total,$this->discount,$this->person_id,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}
	public function add_re_with_client(){
		$sql = "insert into ".self::$tablename." (p_id,d_id,person_id,operation_type_id,user_id,created_at) ";
		$sql .= "value ($this->p_id,$this->d_id,$this->person_id,1,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}
	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}
	public function process_cotization(){
		$sql = "update ".self::$tablename." set stock_to_id=$this->stock_to_id,p_id=$this->p_id,d_id=$this->d_id,iva=$this->iva,total=$this->total,discount=$this->discount,cash=$this->cash,is_draft=0 where id=$this->id";
		Executor::doit($sql);
	}
	public function update(){
		$sql = "update ".self::$tablename." set f_id=$this->f_id,person_id=$this->person_id,invoice_code=\"$this->invoice_code\",invoice_file=\"$this->invoice_file\",comment=\"$this->comment\" where id=$this->id";
		Executor::doit($sql);
	}
	public function update_box(){
		$sql = "update ".self::$tablename." set box_id=$this->box_id where id=$this->id";
		Executor::doit($sql);
	}
	public function update_d(){
		$sql = "update ".self::$tablename." set d_id=$this->d_id where id=$this->id";
		Executor::doit($sql);
	}
	public function update_status(){
		$sql = "update ".self::$tablename." set status=$this->status where id=$this->id";
		Executor::doit($sql);
	}

	public function update_p(){
		$sql = "update ".self::$tablename." set p_id=$this->p_id where id=$this->id";
		Executor::doit($sql);
	}
	public static function getById($id){
		 $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}
	public function cancel(){
		$sql = "update ".self::$tablename." set d_id=3,p_id=3 where id=$this->id";
		Executor::doit($sql);
	}
	public function uncancel(){
		$sql = "update ".self::$tablename." set d_id=1,p_id=1 where id=$this->id";
		Executor::doit($sql);
	}
	public static function getCotizations(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and d_id=2 and is_draft=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getCotizationsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and d_id=2 and is_draft=1 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSells(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 order by created_at desc limit 10";
		//echo($sql);
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getCredits(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getCreditsByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getCreditsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getCreditsByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToDeliver(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToDeliverByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToDeliverByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToDeliverByClient($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCob(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCobByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToCobByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToCobByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsUnBoxed(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id is NULL and p_id=1 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getByBoxId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id=$id and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getRes(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getResByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=1 and d_id=1 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getResToPay(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getResToPayByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getResToReceive(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and d_id=2  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getResToReceiveByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and d_id=2 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSQL($sql){
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getAllBySQL($sqlextra){
		$sql = "select * from ".self::$tablename." $sqlextra";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id<=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getAllByDateOp($start,$end,$op){
	  	$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getAllByDateOpByUserId($user,$start,$end,$op){
	  	$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getGroupByDateOp($start,$end,$op){
  		$sql = "select id,sum(total) as tot,discount,sum(total-discount) as t,count(*) as c from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and p_id!=4";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getAllByDateBCOp($clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getAllByDateBCOpByUserId($user,$clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getDetallePago($clientId,$ventaId){
 		$sql = "SELECT id, codigo_venta, id_person, total, pago, deuda, fecha_creacion as fc, DATE(fecha_creacion) as fecha_creacion FROM pagos 
 		WHERE codigo_venta = '$ventaId' AND id_person = '$clientId' ORDER BY fc ASC";
 		//echo($sql);
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getVentaSunatById($clientId, $ventaId){
		if($clientId == 0){
			$sql = "SELECT * FROM ventas_cabecera WHERE codigo_venta = '$ventaId'";
			//$sql = "SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name, p.name, d.name, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = '$clientId' AND vc.codigo_venta = '$ventaId' ORDER BY vc.fecha_creacion DESC";
		}else{
 			$sql = "SELECT vc.*, DATE(vc.fecha_emision) as fecha_creacion, pe.name, p.name, d.name, f.name as tipo_pago, k.tipo_documento FROM ventas_cabecera as vc, person as pe, p as p, d as d, f as f, kind_doc as k WHERE vc.id_estado_entrega = d.id AND vc.id_forma_pago = f.id AND vc.id_estado_pago = p.id AND vc.tipo_documento = k.id AND vc.id_person = pe.id AND pe.id = '$clientId' AND vc.codigo_venta = '$ventaId' ORDER BY vc.fecha_creacion DESC";
		}
 		//echo($sql);
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}
	/* ---- actualizar pago ---- */
	public function actualizarPagoVenta(){
		$sql = "update ".self::$tablename." set p_id=1,fecpago=\"$this->fecpago\",entidad=\"$this->entidad\",fecdetrac=\"$this->fecdet\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function delPagoById($id){
		$sql = "delete from pagos where id='$id'";
		Executor::doit($sql);
	}

	public function update_ventas_pedido($pedido,$id){
		$sql = "update ventas_cabecera set pedido_cod='$pedido' where codigo_venta='$id'";
		//echo($sql);
		Executor::doit($sql);
	}

	public function lista_rep_ventas_guia_pedido($desde,$hasta){
		$sql = "SELECT vc.fecha_emision as fecha,vc.codigo_venta as venta,vc.guia as guia,vc.pedido_cod as pedido FROM ventas_cabecera vc where vc.fecha_emision between '$desde' and '$hasta' order by vc.fecha_emision desc";
		//echo($sql);
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public function caja_lista_kardex($caja_id){
		$sql = "SELECT * FROM caja_kardex WHERE caja_id = $caja_id";
		//echo($sql);
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public function caja_lista_kardex_filtro(){

	}

}
?>