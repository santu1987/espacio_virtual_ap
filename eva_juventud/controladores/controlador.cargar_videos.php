<?php
session_start();
require_once("../modelos/modelo.registrar_eva.php");
/////////////////
$mensaje=array();
$rs=array();
//valido los nombres de los archivos con el id del aula virtual
if(ISSET($_POST["cont_ev"])&&($_POST["cont_ev"]!=""))
{
	$id_cont=$_POST["cont_ev"];
}else
{
	$mensaje[0]="campos_blancos";	
	die(json_encode($mensaje));
}
///////////////////////////////////////////////////////////////
//Realizando la carga del video...
//////////////////////////////////////////////////////////////
	$video=$_FILES['material_video']['name'];
	$tipo_archivo_video= $_FILES['material_video']['type'];
	$tamano_video=$_FILES['material_video']['size'];
	//-- Para validar tamaño
	if($tamano_video>100000000)
	{
		$mensaje[0]="error_tamano";
		die(json_encode($mensaje));
	}
	//variable servidor donde se cargaran los videos de las unidades
	$serv = $_SERVER['DOCUMENT_ROOT']."/eva_juventud/material_video/";
	//creo las rutas
	$nombre_video="video".$i."_unidad".$id_cont.".ogg";
	$nombre_video2="video".$i."_unidad".$id_cont.".ogg";
	$ruta_video=$serv.$nombre_video;
	//valido que cada imagen sea solo tipo jpg
	//creo mi clase certificado...
	$tipo_archivo='ogg';
	$obj_unidad=new espacio_v();
	$datos[0]=$obj_unidad->cargar_archivos_unidad($ruta_video,$nombre_video,$tipo_archivo_video,$tamano_video,$_FILES['material_video']['tmp_name'],$id_cont,$tipo_archivo);
	/////////////////////////////////////
	if($datos[0]=="error_tipo_archivo")
	{
		$mensaje[0]="error_tipo_archivo";
		die(json_encode($mensaje));
	}
	if($datos[0]=="error_tamano")
	{
		$mensaje[0]="error_tamano";
		die(json_encode($mensaje));
	}
	if($datos[0]==1)
	{
		$rs=$obj_unidad->registrar_video($nombre_video,$id_cont);
		//die(json_encode($rs));
		if($rs=="error")
		{
			die("error_bd");
		}
	}
	else
	{
		$mensaje[0]="error_no_carga";
		die(json_encode($mensaje));
	}
	/////////////////
	$mensaje[0]="archivo_cargado";
	$mensaje[1]=$nombre_video2;	
	die(json_encode($mensaje));
///////////////////////////////////////////////////////////////////////
/////
///////////////////////////////////////////////////////////////////////	
?>