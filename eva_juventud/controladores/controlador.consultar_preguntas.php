<?php
session_start();
require("../modelos/modelo.registrar_preguntas_eva.php");
$mensaje=array();
$rs=array();
if(isset($_POST["id_pregunta"])&&($_POST["id_pregunta"]!=""))
{
	$id_pregunta=$_POST["id_pregunta"];
}	
//declaro la clase
$obj_pregunta=new preguntas();
$rs=$obj_pregunta->consultar_preguntas($id_pregunta);
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