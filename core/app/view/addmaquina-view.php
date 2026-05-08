<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

if(count($_POST)>0){
    $maquina = new MaquinaData();
    $maquina->maquina_codigo = $_POST["codigo"];
    $maquina->maquina_descripcion  = $_POST["descripcion"];
    $maquina->maquina_marca = $_POST["marca"];
    $maquina->maquina_modelo = $_POST["modelo"];
    $maquina->maquina_serie  = $_POST["serie"];
    $maquina->maquina_marca_motor = $_POST["marca_motor"];
    $maquina->maquina_serie_motor = $_POST["serie_motor"];
    $maquina->maquina_exigencias = $_POST["exigencias"];
    $maquina->maquina_voltaje = $_POST["voltaje"];
    $maquina->maquina_tipo_corriente = $_POST["tipo_corriente"];

    $maquina->maquina_anio_compra = $_POST["anio_compra"];
    $maquina->maquina_vida_util = $_POST["vida_util"];
    $maquina->maquina_tipo = $_POST["tipo"];
    $maquina->maquina_ubicacion = $_POST["ubicacion"];
    $maquina->maquina_estado = $_POST["estado"];
    
    $maquina->precio_compra = $_POST["precio_compra"];
    $maquina->proveedor = $_POST["proveedor"];
  
    /*print_r($_POST);
echo "<br>";
    print_r($_FILES);*/

    if(isset($_FILES['image_factura'])){
        $image_f = new Upload($_FILES["image_factura"]);
        if($image_f->uploaded){
            $image_f->Process("storage/maquinas/");
            if($image_f->processed){
                $maquina->factura_compra = $image_f->file_dst_name;
            }
        }
    }

    if(isset($_FILES["image"])){
        $image = new Upload($_FILES["image"]);
        if($image->uploaded){
            $image->Process("storage/maquinas/");
            if($image->processed){
                $maquina->maquina_imagen = $image->file_dst_name;
            }
        }
    }
    $maquina->add();
    print "<script>window.location='index.php?view=maquinas';</script>";
}
?>