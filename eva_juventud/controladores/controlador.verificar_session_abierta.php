<?php
session_start();
if(isset($_SESSION["espacio_virtual"]))
{
  echo "<script type='text/javascript'>alert(".$_SESSION["espacio_virtual"].")</script>";
  if ($_SESSION["espacio_virtual"] == "SI")
  {
    echo "<script>location.href='http://".$_SERVER['HTTP_HOST']."/eva_juventud/inicio.php';</script>";
    exit(); 
  }
}
if (isset($_GET["cerrar"])){ $cerrar = base64_decode($_GET["cerrar"]); } else{ $cerrar = ""; }

?>
