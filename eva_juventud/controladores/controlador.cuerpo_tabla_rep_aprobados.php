<?php
session_start();
require("../modelos/modelo.registrar_evaluacion.php");
require("../modelos/modelo.paginacion_consultas.php");  
$rs=array();
$mensaje=array();
//-- Validando los POST
if(isset($_POST["nombre"])){ if($_POST["nombre"]!=""){ $nombre=strtoupper($_POST["nombre"]);}else{ $nombre="";}  }else{ $nombre="";}
//--filtros 2
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_aprobados_rep";
///////////////////////////////////////////
//--Creo el objeto
$obj_est=new evaluacion();
$rs=$obj_est->consultar_cuerpo_estudiantes_aprobados($offset,$limit,$nombre);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else
{
	for($i=0;$i<=count($rs)-1;$i++)
	{
		$k=$i+1;
		$cuerpo_preguntas.="<tr>
									<td width='60%'>".$rs[$i][0]."</td>
									<td width='20%'>".$rs[$i][1]."</td>
									<td width='20%'>".$rs[$i][2]."</td>
							</tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_est->num_rows3,$nom_fun);
	$mensaje[0]=$cuerpo_preguntas;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	
///////////////////////////////////////////////
?>