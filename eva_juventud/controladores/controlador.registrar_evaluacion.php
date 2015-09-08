<?php
session_start();
require_once("../modelos/modelo.registrar_evaluacion.php");
require_once("../modelos/modelo.registrar_auditoria.php"); 
//valido campos obligatorios
if(
(isset($_POST["aula_virtual_evaluacion"]))&&(isset($_POST["unidades_evaluacion"]))&&
(isset($_POST["desc_evaluacion"]))&&(isset($_POST["evaluacion_cuantas_preguntas"]))&&
(isset($_POST["fecha_activacion_evaluacion"]))&&
(isset($_POST["fecha_cierre_evaluacion"]))
)
{
	if(($_POST["aula_virtual_evaluacion"]!=0)&&($_POST["unidades_evaluacion"]!=0)&&($_POST["desc_evaluacion"]!="")
	&&($_POST["evaluacion_cuantas_preguntas"]!=0)
	&&($_POST["fecha_activacion_evaluacion"]!="")
	&&($_POST["fecha_cierre_evaluacion"]!=""))
	{
		$datos[0]=$_POST["aula_virtual_evaluacion"];
		$datos[1]=$_POST["unidades_evaluacion"];
		$datos[2]=$_POST["desc_evaluacion"];
		$datos[3]=$_POST["evaluacion_cuantas_preguntas"];
		$datos[4]=$_POST["id_evaluacion"];if($datos[4]==""){$datos[4]=0;}
		$datos[5]=$_POST["tipo_evaluacion"];
		$datos[6]=$_POST["fecha_activacion_evaluacion"];
		$datos[8]=$_POST["fecha_cierre_evaluacion"];
	}
	else
	{
		$mensaje="campos_blancos";
		die(json_encode($mensaje));
	}	
}else
	{
		$mensaje="campos_blancos2";
		die(json_encode($mensaje));
	}
//creo el objeto
$obj_evaluacion=new evaluacion();
$rs_ev=$obj_evaluacion->registrar_evaluacion($datos[0],$datos[1],$datos[2],$datos[3],$datos[4],$datos[5],$datos[6],$datos[7],$datos[8]);
//die(json_encode($rs_ev));
if($rs_ev=="error")
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}else
//--------------------------------------------------------------------------
if($rs_ev[0][0]=="-666")
{
	$mensaje[0]="activacion_menor_aula";
	die(json_encode($mensaje));
}else	
if($rs_ev[0][0]=="-665")
{
	$mensaje[0]="activacion_menor_unidad";
	die(json_encode($mensaje));
}else
if($rs_ev[0][0]=="-664")
{
	$mensaje[0]="cierre_menor_aula";
	die(json_encode($mensaje));
}else	
if($rs_ev[0][0]=="-663")
{
	$mensaje[0]="cierre_menor_unidad";
	die(json_encode($mensaje));
}	

//--------------------------------------------------------------------------
else
{
	$mensaje[0]="registro_exitoso";
	$mensaje[1]=$rs_ev[0][0];
	/////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
    $auditoria_evaluacion=new auditoria("Evaluacion","Actualizacion datos evaluacion(ID:".$mensaje[1]."AULA:".$datos[0].")");
    $auditoria=$auditoria_evaluacion->registrar_auditoria();
    if($auditoria==false)
    {
        $mensaje[0]='error_auditoria';
        die(json_encode($mensaje)); 
    }
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	die(json_encode($mensaje));
}	
?>