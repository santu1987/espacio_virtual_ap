<?php
session_start();
require("../modelos/modelo.usuario.php");
require("../modelos/modelo.paginacion_consultas.php");
///filtros complementarios
if(isset($_POST["nombre"])){ if($_POST["nombre"]!=""){ $nombre=strtoupper($_POST["nombre"]); }else{ $nombre="";} }else { $nombre="";}
if(isset($_POST["aula"])){ if($_POST["aula"]!=""){ $aula=$_POST["aula"]; }else{ $aula="";} }else { $aula="";}
///filtros 
$offset=$_POST["offset"];//offset
$j=$offset;//contador para el campo n°
$limit=$_POST["limit"]; //limit
$actual=$_POST["actual"];
$nom_fun="cargar_consulta_contactos";
//arreglos
$rs=array();
$mensaje=array();
$obj_us=new Usuario();
$rs=$obj_us->consultar_contactos($offset,$limit,$nombre,$aula);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else
{
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_us->num_rows_cnt,$nom_fun);
	$mensaje[0]=$rs;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}
?>