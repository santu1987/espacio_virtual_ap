<?php
//incluyo al modelo
require_once("./modelos/conex.php");
$mensaje= array();
//valido que no esten campos en blanco
if(isset($_POST["usuario_nom"])&&$_POST["usuario_contrasena"]))
{
	////////////////////////////////////////////////////////////////////
	if(($_POST["usuario_nom"]!="")&&($_POST["usuario_contrasena"]!=""))
	{
		$datos[0]=$_POST["usuario_nom"];
		$datos[1]=$_POST["usuario_contrasena"];
		$usuario=new Usuario($datos[0],$datos[1]);
		$usuario->iniciar_sesion("
		SELECT 
				id_usuarios,
				usuario_login,
				email,
				clave,
				nombres,
				apellidos
		FROM 
				tbl_usuarios
	    WHERE
				tbl_usuarios.usuario_login='$datos[0]'
		  AND
		  		tbl_usuarios.clave='$datos[1]';");
	}
	else 
	{
		$mensaje[0] ="campos blancos";
		die(json_encode($mensaje));
	}	
	/////////////////////////////////////////////////////////////////////	
}
else
{
	$mensaje[0] ="campos blancos2";
	die(json_encode($mensaje));
}
?>