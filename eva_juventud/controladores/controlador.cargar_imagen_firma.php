<?php
session_start();
require_once("../modelos/modelo.usuario.php");
/////////////////
$mensaje=array();
$rs=array();
//asignando valores del file...
//////////////////////////logo_inst
$firma=$_FILES['imagen_firma']['name'];
$tipo_archivo_firma= $_FILES['imagen_firma']['type'];
$tamano_firma=$_FILES['imagen_firma']['size'];
//valido los nombres de los archivos con el id del aula virtual
if(((ISSET($_POST["id_perfil_us"]))&&($_POST["id_perfil_us"]!=""))&&((ISSET($_POST["id_us_p"]))&&($_POST["id_us_p"]!="")))
{
	$id_perfil_us=$_POST["id_perfil_us"];
	$id_us=$_POST["id_us_p"];
	$nombre_firma="firma_us".$id_us.".jpg";
}else
{
	die("id_blancos");
}
//variable servidor donde se cargaran las imagenes
$serv = $_SERVER['DOCUMENT_ROOT']."/eva_juventud/img/firmas/";
//creo las rutas
$ruta_firma=$serv.$nombre_firma;
//valido que cada imagen sea solo tipo jpg
//creo mi clase certificado...
$obj_firma=new Usuario();
$datos[0]=$obj_firma->cargar_imagenes_firma($ruta_firma,$nombre_firma,$tipo_archivo_firma,$tamano_firma,$_FILES['imagen_firma']['tmp_name']);
/////////////////////////////////////
if($datos[0]=="error_tipo_archivo")
{
	$mensaje[0]="error_tipo_archivo";
	die(json_encode($mensaje));
}
if($datos[0]==1)
{
	$rs=$obj_firma->registrar_firma($id_us,$nombre_firma);
	if($rs=="error")
	{
		die("error_bd");
	}
	else
	{
		die("archivo_cargado");
	}	
}
else
{
	$mensaje[0]="error_inesperado";
	die(json_encode($mensaje));
}
/////////////////
?>