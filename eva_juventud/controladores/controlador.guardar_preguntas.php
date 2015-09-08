<?php
session_start();
require_once("../modelos/modelo.registrar_preguntas_eva.php");
require_once("../modelos/modelo.registrar_auditoria.php"); 
$recordset=array();
$mensaje=array();
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//valido  los post
	//cargar valores en variables
	$aula=$_POST["aula_virtual_evaluacion"];
	//$evaluacion=$_POST["evaluacion_p"];
	$evaluacion=$_POST["id_evaluacion"];
	$preguntas_evaluacion=$_POST["pregunta_evaluacion"];
	$n_pregunta=$_POST["pr_n"];
	$pregunta_respuesta1=$_POST["pregunta_respuesta1"];
	$pregunta_respuesta2=$_POST["pregunta_respuesta2"];
	$pregunta_respuesta3=$_POST["pregunta_respuesta3"];
	$pregunta_respuesta4=$_POST["pregunta_respuesta4"];
	$id_pregunta=$_POST["id_pregunta"];
	$r_op=$_POST["r_op"];
	//valido si el id es blanco
	if($id_pregunta==""){$id_pregunta=0;}
	//creo el objeto
	$objeto_preguntas=new preguntas();
	$recordset=$objeto_preguntas->cargar_preguntas($aula,$evaluacion,$preguntas_evaluacion,$n_pregunta,$pregunta_respuesta1,$pregunta_respuesta2,$pregunta_respuesta3,$pregunta_respuesta4,$id_pregunta,$r_op);
	//die(json_encode($recordset));
	if($recordset=="error")
	{
		$mensaje[0]="error_bd";
	}else
	{
		$mensaje[0]="registro_exitoso";
		$mensaje[1]=$recordset[0][0];
		//$mensaje[2]=$objeto_preguntas->consultar_cuantas_preguntas($evaluacion);
		/////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
	    $auditoria_preguntas=new auditoria("Evaluacion preguntas","Carga de preguntas(ID:".$mensaje[1]."-EVALUACION:".$evaluacion.")");
	    $auditoria=$auditoria_preguntas->registrar_auditoria();
	    if($auditoria==false)
	    {
	        $mensaje[0]='error_auditoria';
	        die(json_encode($mensaje)); 
	    }
		/////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	die(json_encode($mensaje));
///////////////////////////////////////////////////////////////////////////////////////////////////////////
?>