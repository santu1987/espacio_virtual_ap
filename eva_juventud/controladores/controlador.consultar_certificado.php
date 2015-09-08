<?php
session_start();
require_once("../modelos/modelo.certificado.php");
$aula=$_POST["aula"];
$mensaje=array();
$rs=array();
//creo el segundo objeto que me taera los datos del certificado
$obj_cert=new Certificado();
$rs=$obj_cert->consultar_elementos_aula($aula);
die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}else
{
	die(json_encode($rs));
}
?>