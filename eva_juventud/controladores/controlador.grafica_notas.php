<?php
require("../modelos/modelo.registrar_evaluacion.php");
session_start();
//Declarando arreglos
$mensaje=array();
$rs=array();
//validando los POST
if(isset($_POST["id_aula"])){ if($_POST["id_aula"]){ $id_aula=strtoupper($_POST["id_aula"]);  } else { $id_aula="";} } else { $id_aula="";}
//
$obj_notas=new evaluacion();
$rs=$obj_notas->consultar_notas($id_aula);
die(json_encode($rs));
if($rs==error)
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}else
{
	for($i=0;$i<=count($rs);$i++)
	{
		$unidad[i]=$rs[$i][3];
		$notas[i]=$rs[$i][5];
	}
	$vector[0]=$unidad;
	$vector[1]=$notas;
	die($vector);
}
?>