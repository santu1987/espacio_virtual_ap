<?php
session_start();
require_once("../modelos/modelo.registrar_preguntas_eva.php");
$mensaje=array();
$rs=array();
if((isset($_POST["id_evaluacion"]))&&($_POST["id_evaluacion"]!=""))
{
	$evaluacion=$_POST["id_evaluacion"];
}else
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}
//////////////////////////////////////////////////
$obj_pregunta=new preguntas();
$rs=$obj_pregunta->consultar_preguntas_form($evaluacion);
if($rs=="error")
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}else
{
	$mensaje[0]=$rs;
	$mensaje[1]=$obj_pregunta->consultar_estatus_aula($evaluacion);
	die(json_encode($mensaje));
}	
//////////////////////////////////////////////////
?>