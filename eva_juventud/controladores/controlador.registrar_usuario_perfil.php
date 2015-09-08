<?php
session_start();
require("../modelos/modelo.usuario.php");
require("../modelos/modelo.registrar_auditoria.php"); 
$mensaje=array();
$rs=array();
if((isset($_POST["select_perfil"]))&&(isset($_POST["id_persona_p"])))
{
	if(($_POST["select_perfil"]!="")&&($_POST["id_persona_p"]!=""))
	{
		$usuario_perfil=new usuario();
		if($_POST["id_perfil_us"]=="")
		{
			$id_perfil_us=0;
		}
		else
		{
			$id_perfil_us=$_POST["id_perfil_us"];
		}	
		$rs=$usuario_perfil->registrar_perfil_us($_POST["id_us_p"],$_POST["select_perfil"],$id_perfil_us);
		if($rs[0][0]=="error")
		{
			$mensaje[0]="error_bd";
		}else
		{
			$mensaje[0]="registro_exitoso";
			$mensaje[1]=$rs[0][0];
			/////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
		    $auditoria_perfil=new auditoria("Perfil usuario","Actualizacion perfil usuario");
		    $auditoria=$auditoria_perfil->registrar_auditoria();
		    if($auditoria==false)
		    {
		        $mensaje[0]='error_auditoria';
		        die(json_encode($mensaje)); 
		    }
			/////////////////////////////////////////////////////////////////////////////////////////////////////

		}	
	}
	else
	{	
		$mensaje[0]="campos_blancos";
	}	
}else
{
	$mensaje[0]="campos_blancos2";
}
die(json_encode($mensaje));
?>