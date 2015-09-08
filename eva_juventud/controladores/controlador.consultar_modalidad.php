<?php
session_start();
require("../modelos/modelo.tipo_estudio.php");
$mensaje=array();
$rs=array();
if(isset($_POST["id_modalidad"])&&($_POST["id_modalidad"]!=""))
{
	$id_modalidad=$_POST["id_modalidad"];
}	
//declaro la clase
$tipo_estudio=new tipo_estudio();
$rs=$tipo_estudio->consultar_modalidad($id_modalidad);
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