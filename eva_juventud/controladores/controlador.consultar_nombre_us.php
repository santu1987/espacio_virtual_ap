<?php
require_once("../modelos/modelo.usuario.php");
if(isset($_POST["id"])){ if($_POST["id"]!=""){ $id=$_POST["id"]; }else{ $id=""; }  } else{ $id=""; }
if($id=="")
{
	$mensajes[0]="campos_blancos";
	die(json_encode($mensajes));
}
$obj_us= new Usuario();
$rs=$obj_us->consultar_us_nombre($id);
if($rs=="error")
{
	$mensajes[0]="error";
	die(json_encode($mensajes));
}
else
{
	$mensajes[0]=$rs[0][0];
	die(json_encode($mensajes));
}
?>