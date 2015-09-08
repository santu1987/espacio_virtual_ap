<?php
session_start();
//incluyo al modelo
require_once("../modelos/modelo.usuario.php");
//valido que no esten campos en blanco
if(isset($_POST["usuario_nom"])&&($_POST["usuario_contrasena"]))
{
	////////////////////////////////////////////////////////////////////
	if(($_POST["usuario_nom"]!="")&&($_POST["usuario_contrasena"]!=""))
	{
		$datos[0]=$_POST["usuario_nom"];
		$datos[1]=$_POST["usuario_contrasena"];
		$usuario=new Usuario();
		$rs=$usuario->iniciar_session($datos[0],$datos[1]);
		die(json_encode($usuario->mensaje));
	}
	else 
	{
		$usuario->mensaje[0] ="campos blancos";
		die(json_encode($usuario->mensaje));
	}	
	/////////////////////////////////////////////////////////////////////	
}
else
{
	$usuario->mensaje[0] ="campos blancos2";
	die(json_encode($usuario->mensaje));
}
?>