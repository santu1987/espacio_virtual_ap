<?php
session_start();
require("../modelos/modelo.registrar_evaluacion.php");
$mensaje=array();
$rs=array();
////////////////////////////////////////////////////////////////////
if((isset($_POST["id_evaluacion"]))&&($_POST["id_evaluacion"]!=""))
{
	$evaluacion=$_POST["id_evaluacion"];
}else
{
	$mensaje[0]="campos_blancos";
}
$obj_pregunta=new evaluacion();
$rs=$obj_pregunta->cerrar_evaluacion($evaluacion);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}else
{
	die(json_encode($rs));
}	
////////////////////////////////////////////////////////////////////

?>