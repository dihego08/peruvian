<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
//`descripcion`, `cantidad`, `imagen`, `observaciones`, `codigo`
if(count($_POST)>0){
    $maquina = new DispositivosData();
    $maquina->codigo = $_POST["codigo"];
    $maquina->descripcion  = $_POST["descripcion"];
    $maquina->cantidad = $_POST["cantidad"];
    $maquina->observaciones = $_POST["observaciones"];
    $maquina->fecha = $_POST["fecha"];
    $maquina->responsable = $_POST["responsable"];

    if(isset($_FILES["image"])){
        $image = new Upload($_FILES["image"]);
        if($image->uploaded){
            $image->Process("storage/dispositivos/");
            if($image->processed){
                $maquina->imagen = $image->file_dst_name;
            }
        }
    }
    $maquina->add();
    print "<script>window.location='index.php?view=dispositivos';</script>";
}
?>