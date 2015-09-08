<?php
session_start();
require("../modelos/modelo.usuario.php");
$rs=array();
$mensaje=array();
//Validar campos filtros...
if(isset($_POST["id_persona"])){ if($_POST["id_persona"]!=""){ $id_persona=$_POST["id_persona"]; } else { $id_persona="";}  } else{ $id_persona="";}
/////////////
if($id_persona=="")
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}
////////////
//--Creo el objeto
$obj_us=new Usuario();
$rs=$obj_us->in_activar_us($id_persona);
//die(json_encode($rs));
////////////////////////////////////////
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else if($rs[0][0]=="no_existe")
{
	$mensaje[0]="no_existe";
	die(json_encode($mensaje));
}
else if($rs[0][0]=="I")
{
	$mensaje[0]="inactivo";
	die(json_encode($mensaje));
}
else if($rs[0][0]=="A")
{
	$mensaje[0]="activo";
	die(json_encode($mensaje));
}
////////////////////////////////////////
?>