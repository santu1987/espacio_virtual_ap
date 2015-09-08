<?php
session_start();
require("../modelos/modelo.tipo_usuario.php");
$mensaje=array();
$rs=array();
if(isset($_POST["id_tp_us"])&&($_POST["id_tp_us"]!=""))
{
	$id_tp_us=$_POST["id_tp_us"];
}	
//declaro la clase
$tipo_usuario=new tipo_us();
$rs=$tipo_usuario->consultar_tp_usuario($id_tp_us);
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