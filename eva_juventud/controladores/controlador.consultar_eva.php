<?php
session_start();
require("../modelos/modelo.registrar_eva.php");
$mensaje=array();
$rs=array();
if(isset($_POST["id_eva"])&&($_POST["id_eva"]!=""))
{
	$id_eva=$_POST["id_eva"];
}	
//declaro la clase
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_eva($id_eva);
die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else
{
	die(json_encode($rs));
}
?>