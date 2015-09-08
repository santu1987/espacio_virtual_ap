<?php
session_start();
require_once("../modelos/modelo.usuario.php");
/////////////////
//valido que el id no este en blanco
if(isset($_POST["id_persona"])&&($_POST["id_persona"]))
{
	$id_persona=$_POST["id_persona"];
}
else
{
	die("id_blanco");
}
//creo mi clase...
$obj_us=new Usuario();
/////////////////
//asignando valores del file...
$nombre_archivo = $_FILES['imagen_usuario']['name'];
$tipo_archivo = $_FILES['imagen_usuario']['type'];
$tamano_archivo = $_FILES['imagen_usuario']['size'];
$serv = $_SERVER['DOCUMENT_ROOT']."/eva_juventud/img/fotos_personas/";
//valido el nombre del archivo segun la cedula de identidad
if(isset($_POST["n_cedula_us"]))
{
	$nombre_archivo2=$_POST["select_nac_us"].$_POST["n_cedula_us"].".jpg";
}
//creo la ruta
$ruta=$serv.$nombre_archivo2;
//valido que la imagen solo sea tipo jpg...
if (!((strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "jpg")||(strpos($tipo_archivo, "JPG"))) && ($tamano_archivo < 1000000000))) 
{
    die("error_tipo_archivo");
}else
{
    //muevo la imagen de directorio
    if (move_uploaded_file($_FILES['imagen_usuario']['tmp_name'],$ruta))
    {
	  	chmod($ruta,0777);//permisos al directorio
	  	$rs=$obj_us->ac_imagen_us($nombre_archivo2,$id_persona);
	  	if($rs=="error")
	  	{
	  		die("error_bd");
	  	}
	  	else
	  	if($rs[0][0]=="modifico")
	  	{
		    die("archivo_cargado");
	  	}
	  	else
	  	if($rs[0][0]=="no_existe")
	  	{
		    die("no_existe_usuario");
	  	}	
    }else
    {
       die("error_no_carga");
    }
}
/////////////////
?>