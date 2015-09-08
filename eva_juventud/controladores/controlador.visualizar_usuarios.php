<?php
session_start();
require("../modelos/modelo.usuario.php");
require("../modelos/modelo.paginacion_consultas.php");
require("../modelos/modelo.registrar_eva.php");
///filtros complementarios
if(isset($_POST["nombre"])){ if($_POST["nombre"]!=""){ $nombre=strtoupper($_POST["nombre"]); }else{ $nombre="";} }else { $nombre="";}
if(isset($_POST["aula"])){ if($_POST["aula"]!=""){ $aula=$_POST["aula"]; }else{ $aula="";} }else { $aula="";}
///filtros 
$offset=$_POST["offset"];//offset
$j=$offset;//contador para el campo n°
$limit=$_POST["limit"]; //limit
$actual=$_POST["actual"];
$nom_fun="cargar_consulta_est";
//arreglos
$rs=array();
$mensaje=array();
$obj_est=new Usuario();
//--Creo el objeto que consulte que aulas tiene mi usuario si el tipo de usuario es=10
if($_SESSION["id_perfil"]==10)
{
	////////////////////////////////////////
	$obj_eva=new espacio_v();
	$rs_eva=$obj_eva->consultar_misaulas();
	////////////////////////////////////////
	//--Esta linea activa el metodo de filtro de aulas en el que se determina que aulas pertenecen al usuario...
	$obj_est->filtro_aulas($rs_eva);
	////////////////////////////////////////
}	
//----------------------------------------------------------------------------
$rs=$obj_est->consultar_estudiantes($offset,$limit,$nombre,$aula);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else
{
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_est->num_rows,$nom_fun);
	$mensaje[0]=$rs;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}
?>