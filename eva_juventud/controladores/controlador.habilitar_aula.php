<?php
session_start();
require("../modelos/modelo.registrar_eva.php");
require("../modelos/modelo.registrar_auditoria.php"); 
$mensaje=array();
if(isset($_POST["id_aula"]))
{
	if($_POST["id_aula"]!="")
	{
		$id_aula=$_POST["id_aula"];
	}
	else
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
/////////////////////////////////
$obj_eva=new espacio_v();
$rs=$obj_eva->in_activar_aula($id_aula);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}else
{
	$mensaje[0]="registro_exitoso";
	$mensaje[1]=$rs[0][0];
	die(json_encode($mensaje));
}	
?>