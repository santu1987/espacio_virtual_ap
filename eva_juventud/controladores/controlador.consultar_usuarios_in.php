<?php
session_start();
require("../modelos/modelo.usuario.php");
$obj_fac=new Usuario();
$rs=$obj_fac->consultar_usuarios_in();
//die(json_encode($rs));
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