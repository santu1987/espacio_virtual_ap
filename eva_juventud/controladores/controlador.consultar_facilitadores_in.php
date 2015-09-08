<?php
session_start();
require("../modelos/modelo.facilitador.php");
$obj_fac=new Facilitador();
$rs=$obj_fac->consultar_facilitadores_in();
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