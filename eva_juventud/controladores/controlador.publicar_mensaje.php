<?php
session_start();
require("../modelos/modelo.registrar_foro.php");
require("../modelos/modelo.registrar_auditoria.php"); 
$mensaje=array();
//valido el campo que viene por post
if((isset($_POST["aula"]))&&(isset($_POST["mensaje_foro"])))
{
	if(($_POST["aula"]!="")&&($_POST["mensaje_foro"]))
	{
		$aula=$_POST["aula"];
		$mensaje_foro=$_POST["mensaje_foro"];
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
//
$obj_foro=new foro();
$rs=$obj_foro->registrar_mensaje_foro($aula,$mensaje_foro);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
}
else
{
	$mensaje[0]="registro_exitoso";
	/////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
    $auditoria_cur=new auditoria("Discusion contenido","Publicacion mensaje(ID-AULA:".$aula.")");
    $auditoria=$auditoria_cur->registrar_auditoria();
    if($auditoria==false)
    {
        $mensaje[0]='error_auditoria';
        die(json_encode($mensaje)); 
    }
	/////////////////////////////////////////////////////////////////////////////////////////////////////
}	
	die(json_encode($mensaje));	
?>