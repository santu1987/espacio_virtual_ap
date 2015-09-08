<?php
session_start();
require_once("../modelos/modelo.registrar_eva.php");
require_once("../modelos/modelo.registrar_auditoria.php"); 
$recordset=array();
$mensaje=array();
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//valido  los post
//&&($_POST["material_multimedia1"]!="")
//&&($_POST["hora_activacion_unidad"]!="")
if(($_POST["aula_virtual_facilitador"]!="")&&($_POST["unidades_aula_virtual"]!="")&&($_POST["contenido_material"]!="")&&($_POST["fecha_activacion_unidad"]!="")&&($_POST["titulo_aula"]!=""))
{
	//cargar valores en variables
	$aula=$_POST["aula_virtual_facilitador"];
	$unidades=$_POST["unidades_aula_virtual"];
	$material_multimedia=$_POST["material_multimedia1"];
	$contenido=strtoupper($_POST["contenido_material"]);
	$id_contenido=$_POST["cont_ev"];
	$fecha_activacion_unidad=$_POST["fecha_activacion_unidad"];
	$titulo_aula=strtoupper($_POST["titulo_aula"]);
	$hora_ac=$_POST["hora_activacion_unidad"];
	//valido si el id es blanco
	if($id_contenido==""){$id_contenido=0;}
	//creo el objeto
	$objeto_unidades=new espacio_v();
	$recordset=$objeto_unidades->cargar_unidades($aula,$unidades,$material_multimedia,$contenido,$id_contenido,$fecha_activacion_unidad,$titulo_aula,$hora_ac);
	//die(json_encode($recordset));
	if($recordset=="error")
	{
		$mensaje[0]="error_bd";
	}else
	if($recordset[0][0]==-666)
	{
		$mensaje[0]="fecha_menor";
		die(json_encode($mensaje));
	}	
	else
	{
		$mensaje[0]="registro_exitoso";
		$mensaje[1]=$recordset[0][0];
		/////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
		$auditoria_contenido=new auditoria("Contenido EVA","Actualizacion contenido EVA");
		$auditoria=$auditoria_contenido->registrar_auditoria();
		if($auditoria==false)
		{
		    $mensaje[0]='error_auditoria';
		    die(json_encode($mensaje)); 
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////

	}
	die(json_encode($mensaje));
}else
{
	die(json_encode("campos_blancos"));
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
?>