<?php
session_start();
require_once("../modelos/modelo.registrar_evaluacion.php");
if((isset($_POST["aula_virtual_evaluacion"]))&&(isset($_POST["unidades_evaluacion"]))
&&(isset($_POST["tipo_evaluacion"])))
{
	if(($_POST["aula_virtual_evaluacion"]!='0')&&($_POST["unidades_evaluacion"]!='0')&&
	($_POST["tipo_evaluacion"]!='0'))
	{
		$datos[0]=$_POST["aula_virtual_evaluacion"];
		$datos[1]=$_POST["unidades_evaluacion"];
		$datos[2]=$_POST["tipo_evaluacion"];
	}else
	{
		$mensaje[0]="campos_blancos";
		die(json_encode($mensaje));
	}	
}
else
{
	$mensaje[0]="campos_blancos2";
	die(json_encode($mensaje));
}
////////////////////////////////////////////
//creo el objeto
$obj_evaluacion=new evaluacion();
$rs_ev=$obj_evaluacion->consultar_evaluacion($datos[0],$datos[1],$datos[2]);
//die(json_encode($rs_ev));
if($rs_ev=="error")
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}
else
{
	die(json_encode($rs_ev));
}	
/////////////////////////////////////////
?>