<?php
session_start();
require_once("../modelos/modelo.usuario.php");
$recordset=array();
$mensaje=array();
//--Filtros obligatorios
if(isset($_POST["correo"])){ if($_POST["correo"]!=""){ $correo=$_POST["correo"];} else{ $correo=""; } }else{ $correo="";}
if(isset($_POST["clave1"])){ if($_POST["clave1"]!=""){ $clave1=$_POST["clave1"];} else{ $clave1=""; } }else{ $clave1="";}
if(isset($_POST["clave2"])){ if($_POST["clave2"]!=""){ $clave2=$_POST["clave2"];} else{ $clave2=""; } }else{ $clave2="";}
//------------------------------------------------------------------------------------------------------------------------
if(($correo=="")||($clave1=="")||($clave2==""))
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}	
//------------------------------------------------------------------------------------------------------------------------
//creando objetos
$obj_usuario= new Usuario();
$recordset=$obj_usuario->cambiar_clave_correo($clave1,$clave2,$correo);
//$mensaje[0]=$clave_nueva.",".$clave_ant.",".$id_us;
//die(json_encode($recordset));
if($recordset=='error')
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}else
if($recordset[0][0]=='no_existe')
{
	$mensaje[0]="no_existe";
	die(json_encode($mensaje));
}
else
if($recordset[0][0]=='modifico')
{
	$mensaje[0]="modifico";
	die(json_encode($mensaje));
}	
	
?>