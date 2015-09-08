<?php
session_start();
ini_set("memory_limit","1024M");
require("../modelos/modelo.registrar_eva.php");
require("../modelos/modelo.paginacion_consultas.php");
//////////////////////////////////////////////////////////////////////////
if((isset($_POST["id_evaluacion"]))&&(isset($_POST["id_contenido"])))
{
	if(($_POST["id_evaluacion"]!="")&&($_POST["id_contenido"]!=""))
	{
		$id_evaluacion=$_POST["id_evaluacion"];
		$id_contenido=$_POST["id_contenido"];
	}else
	{
		$mensaje[0]="campos_blancos";
		die(json_encode($mensaje));
	}	
}
else
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}
//////////////////////////////////////////////////////////////////////////
//declaro mi objeto...
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_formacion($id_contenido,$id_evaluacion);
die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}else
{
	die(json_encode($rs));
}
///falta crear la funcion en el modelo....
?>