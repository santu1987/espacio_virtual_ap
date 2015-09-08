<?php
session_start();
require("../modelos/modelo.tipo_evaluacion.php");
$mensaje=array();
$rs=array();
if(isset($_POST["id_tp_eva"])&&($_POST["id_tp_eva"]!=""))
{
	$id_tp_eva=$_POST["id_tp_eva"];
}	
//declaro la clase
$tipo_evaluacion =new tipo_evaluacion();
$rs=$tipo_evaluacion->consultar_tipo_evaluacion_id($id_tp_eva);
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