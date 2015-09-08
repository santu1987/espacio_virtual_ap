<?php
session_start();
require("../modelos/modelo.registrar_inscripcion_eva.php");
require_once("../modelos/modelo.registrar_auditoria.php"); 
//validando el campo....
if(ISSET($_POST["id_eva"]))
{
	if($_POST["id_eva"]!="")
	{
		$id_eva=$_POST["id_eva"];
	}	
}
//
//creo el objeto...
$obj_eva= new inscripcion_eva();
$rs=$obj_eva->registrar_inscripcion_eva($id_eva);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}else
if($rs[0][0]=="registro_exitoso")
{
	/////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
    $auditoria_cur=new auditoria("Resumen E.V.A","registrar_inscripcion_eva-(ID:".$id_eva."-ID-US:".$_SESSION['id_us'].")");
    $auditoria=$auditoria_cur->registrar_auditoria();
    if($auditoria==false)
    {
        $mensaje[0]='error_auditoria';
        die(json_encode($mensaje)); 
    }
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	$mensaje[0]="registro_exitoso";
}
else
if($rs[0][0]=="existe")
{
	$mensaje[0]="existe";
}
die(json_encode($mensaje));
?>