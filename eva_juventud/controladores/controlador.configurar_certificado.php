<?php
session_start();
require_once("../modelos/modelo.certificado.php");
/////////////////
$mensaje=array();
$rs=array();
//asignando valores del file...
//////////////////////////logo_inst
$logo=$_FILES['logo_inst']['name'];
$tipo_archivo_logo= $_FILES['logo_inst']['type'];
$tamano_logo=$_FILES['logo_inst']['size'];
//////////////////////////img_fondo
$fondo=$_FILES['img_fondo']['name'];
$tipo_archivo_fondo=$_FILES['img_fondo']['type'];
$tamano_fondo=$_FILES['img_fondo']['size'];
//////////////////////////img_firma
$firma=$_FILES['img_firma']['name'];
$tipo_archivo_firma=$_FILES['img_firma']['type'];
$tamano_firma=$_FILES['img_firma']['size'];
//valido los nombres de los archivos con el id del aula virtual
if((ISSET($_POST["aula_virtual_evaluacion"]))&&($_POST["aula_virtual_evaluacion"]!=""))
{
	$id_aula=$_POST["aula_virtual_evaluacion"];
	$id_certificado=$_POST["id_cert"];
	if($id_certificado==""){$id_certificado=0;}
	$nombre_logo="logo-aula".$id_aula.".jpg";
	$nombre_fondo="fondo-aula".$id_aula.".jpg";
	$nombre_firma="firma-aula".$id_aula.".jpg";	
}else
{
	die("id_blancos");
}
//variable servidor donde se cargaran las imagenes
$serv = $_SERVER['DOCUMENT_ROOT']."/eva_juventud/img/img_certificados/";
//creo las rutas
$ruta_logo=$serv.$nombre_logo;
$ruta_fondo=$serv.$nombre_fondo;
$ruta_firma=$serv.$nombre_firma;
//valido que cada imagen sea solo tipo jpg
//creo mi clase certificado...
$obj_certificado=new Certificado();
$datos[0]=$obj_certificado->cargar_imagenes($ruta_logo,$nombre_logo,$tipo_archivo_logo,$tamano_logo,$_FILES['logo_inst']['tmp_name']);
$datos[1]=$obj_certificado->cargar_imagenes($ruta_fondo,$nombre_fondo,$tipo_archivo_fondo,$tamano_fondo,$_FILES['img_fondo']['tmp_name']);
//$datos[2]=$obj_certificado->cargar_imagenes($ruta_firma,$nombre_firma,$tipo_archivo_firma,$tamano_firma,$_FILES['img_firma']['tmp_name']);
/////////////////////////////////////
if(($datos[0]=="error_tipo_archivo")||($datos[1]=="error_tipo_archivo")||($datos[2]=="error_tipo_archivo"))
{
	$mensaje[0]="error_tipo_archivo";
	die(json_encode($mensaje));
}
if(($datos[0]==1)&&($datos[1]==1))
{
	$rs=$obj_certificado->registrar_certificado($id_certificado,$nombre_logo,$nombre_fondo,$nombre_firma,$id_aula);
	if($rs=="error")
	{
		$mensaje[0]="error_bd";
		die(json_encode($mensaje));
	}
	else
	{
		$mensaje[0]="archivo_cargado";
		$mensaje[1]=$rs[0][0];
		die(json_encode($mensaje));
	}	
}
else
{
	$mensaje[0]="error_inesperado";
	die(json_encode($mensaje));
}
/////////////////
?>