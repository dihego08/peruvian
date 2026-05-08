<?php
class Database
{
	public static $db;
	public static $con;
	function Database()
	{
		//$this->user="sivecsol";$this->pass="sivecsol";$this->host="softluttioncom.ipagemysql.com";$this->ddbb="sivecsol";
		//$this->user="root";$this->pass="";$this->host="localhost";$this->ddbb="peruvian_sivecsol";
		//peruvian_usuario
		$this->user = "u622044135_peruvian";
		$this->pass = "2bGMm^n/4gZ:";
		$this->host = "193.203.175.216";
		$this->ddbb = "u622044135_peruvian";
	}

	function connect()
	{
		$con = new mysqli($this->host, $this->user, $this->pass, $this->ddbb);
		$con->query("set sql_mode='';");
		return $con;
	}

	public static function getCon()
	{
		if (self::$con == null && self::$db == null) {
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}

}
?>