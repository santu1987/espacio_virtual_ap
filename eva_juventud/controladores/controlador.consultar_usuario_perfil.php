<?php
session_start();
require_once("../modelos/modelo.usuario.php");
//verificando que los  campos no esten blancos
if((isset($_POST["nacionalidad_perfil"]))&&(isset($_POST["n_cedula_us"])))
{
	if(($_POST["nacionalidad_perfil"]!="")&&($_POST["n_cedula_us"]!=""))
	{
/////////////////////////////////////////////////////////////////////////////
		$nacionalidad_perfil=$_POST["nacionalidad_perfil"];
		$cedula_perfil=$_POST["n_cedula_us"];
		//creando el objeto
		$obj_us=new usuario();
		$recordset=$obj_us->consultar_usuario_perfil($nacionalidad_perfil,$cedula_perfil);
		if($recordset=="error")
		{
			$mensaje[0]="error";
			die(json_encode($mensaje));
		}
		else
		{
			die(json_encode($recordset));	
		}
/////////////////////////////////////////////////////////////////////////////		
	}
	else
	{	
		$mensaje[0]="campos_blancos";
		die(json_encode($mensaje));
	}	
}else
{
	$mensaje[0]="campos_blancos2";
	die(json_encode($mensaje));
}
?>