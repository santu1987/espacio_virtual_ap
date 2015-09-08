<?php
session_start();
require("../modelos/modelo.registrar_evaluacion.php");
require_once("../libs/fbasic.php");
$mensaje=array();
//validando campos
if((isset($_POST["preguntas_xyz"]))&&(isset($_POST["unidades_xyz"]))&&(isset($_POST["formacion_xyz"])))
{
	$cuantas_pr=$_POST["preguntas_xyz"];
	$unidades=$_POST["unidades_xyz"];
	$formacion=$_POST["formacion_xyz"];
	$prueba=$_POST["prueba_xyz"];
}else
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}
for($i=0;$i<=$cuantas_pr;$i++)
{
	$k=$i+1;	
///////////////	
	if(isset($_POST["opcion".$k]))
	{
		$resp=$_POST["opcion".$k];
		$vector_r=$resp[0];
		$vector_r=explode("*",$vector_r);
		$vector_respuestas[$i]=$vector_r[1];
		$vector_pregunta[$i]=$vector_r[0];		
	}
/////////////		
}
$vector_respuestas=to_pg_array($vector_respuestas);
$vector_pregunta=to_pg_array($vector_pregunta);
$obj_evaluacion=new evaluacion();
$rs=$obj_evaluacion->registrar_evaluacion_us($formacion,$unidades,$prueba,$cuantas_pr,$vector_respuestas,$vector_pregunta);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error_bd";
}
else
{
	$rs2=$obj_evaluacion->cargar_notas($prueba);
	if($rs2=="error"){ $mensaje[0]="error_cargar_notas";die(json_encode($mensaje)); }
	$mensaje[0]=$rs[0][0];
	die(json_encode($mensaje));
}
?>