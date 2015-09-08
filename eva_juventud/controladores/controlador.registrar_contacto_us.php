<?php
session_start();
require("../modelos/modelo.usuario.php");
require("../modelos/modelo.registrar_auditoria.php"); 
$mensaje=array();
$recordset=array();
//valido los POST
if(isset($_POST["id_us"])){ if($_POST["id_us"]!=""){  $id_contacto=$_POST["id_us"]; } else { $id_contacto=""; } }else{ $id_contacto="" ;}
if($id_contacto==""){ $mensaje[0]="campos_blancos"; die(json_encode($mensaje));}
/////////////////
$obj_us=new Usuario();
$rs=$obj_us->registrar_contacto($id_contacto);
//die(json_encode($rs));
/////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
	    $auditoria_mod=new auditoria("Contactos- Estudiantes","agregar contacto");
	    $auditoria=$auditoria_mod->registrar_auditoria();
	    if($auditoria==false)
	    {
	        $mensaje[0]='error_auditoria';
	        die(json_encode($mensaje)); 
	    }
/////////////////////////////////////////////////////////////////////////////////////////////////////
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