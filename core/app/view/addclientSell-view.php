<?php

if(count($_POST)>0){
	$user = new PersonData();
	$user->no = $_POST["no"];
	$user->name = $_POST["name"];
	$user->lastname = "";
	$user->address1 = "";
	$user->email1 = $_POST["email1"];
	$user->phone1 = $_POST["phone1"];
	$user->credit_limit = 0;

	$user->is_active_access = 1;
	$user->has_credit = 0;
	$user->add_client();

print "<script>window.location='index.php?view=sell&cli=".$_POST["name"]."';</script>";


}


?>