<?php

if(count($_POST)>0){
	$product = ProductData::getById($_POST["product_id"]);

	$product->barcode = $_POST["barcode"];
	$product->name = $_POST["name"];
	$product->price_in = $_POST["price_in"];
	$product->price_in_2 = $_POST["price_in_2"];
	$product->price_out = $_POST["price_out"];
	$product->unit = $_POST["unit"];

	$product->description = $_POST["description"];
	$product->presentation = $_POST["presentation"];
	$product->inventary_min = $_POST["inventary_min"];
 	//$product->expire_at = $_POST["expire_at"];

	$product->code = $_POST["code"];
	$product->width = $_POST["width"];
	$product->height = $_POST["height"];
	$product->weight = $_POST["weight"];
	$product->large = $_POST['large'];

	$product->brand_id=$_POST["brand_id"]!=""?$_POST["brand_id"]:"NULL";
	$product->category_id=$_POST["category_id"]!=""?$_POST["category_id"]:"NULL";
	$product->inventary_min=$_POST["inventary_min"]!=""?$_POST["inventary_min"]:"10";

	$product->user_id = $_SESSION["user_id"];
	$product->is_active = isset($_POST["is_active"])?1:0;

	/* CAMPOS NUEVOS AGREGADOS */
	$product->cliente_id = $_POST["cliente_id"];
	$product->prebor_in = $_POST["pre_bor_in"];
	$product->prebor_out = $_POST["pre_bor_out"];
	$product->fecact = $_POST["fecact"];

	$product->update();

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$image->Process("storage/products/");
			if($image->processed){
				$product->image = $image->file_dst_name;
				$product->update_image();
			}
		}
	}

	if(isset($_FILES["imgBordado"])){
		$image2 = new Upload($_FILES["imgBordado"]);
		if($image2->uploaded){
			$image2->Process("storage/products/");
			if($image2->processed){
				$product->imgbordado = $image2->file_dst_name;
				$product->update_imageBordado();
			}
		}
	}


	if(isset($_FILES["secuencia"])){
		$secuencia = new Upload($_FILES["secuencia"]);
		if($secuencia->uploaded){
			$secuencia->Process("storage/secuencias/");
			if($secuencia->processed){
				$product->secuencia = $secuencia->file_dst_name;
				$product->update_secuencia();
			}
		}
	}

	setcookie("prdupd","true");
	print "<script>window.location='index.php?view=editproduct&id=$_POST[product_id]';</script>";


}


?>