<?php

if(count($_POST)>0){


  $sell = new SellData();
  $sell->fecpago = $_POST["fecpago"];
  $sell->entidad = $_POST["entidad"];
  $sell->fecdet = $_POST["fecdet"];
  $sell->id = $_POST["id"];

  $sell->actualizarPagoVenta();


print "<script>window.location='index.php?view=bycob';</script>";


}


?>