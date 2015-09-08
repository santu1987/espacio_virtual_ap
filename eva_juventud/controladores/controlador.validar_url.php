<?php
session_start();
require("../modelos/modelo.validar_url.php");
$mensaje=array();
//valido qque la url no este en blanco
if((isset($_POST["enlace"]))&&($_POST["enlace"]!=""))
{
	$enlace=$_POST["enlace"];
}
else
{
	die(json_encode("campos_blancos"));
}
//Creo un objeto
$obj_url=new validar_url($enlace);
$mensaje[0]=$obj_url->url_exists();
die(json_encode($mensaje));
?>