<?php
session_start();
require_once("../modelos/modelo.registrar_eva.php");
/////////////////
//valido que el id no este en blanco
if(isset($_POST["id_eva"])&&($_POST["id_eva"]))
{
	$id_eva=$_POST["id_eva"];
}
else
{
	die("id_blanco");
}
//creo mi clase...
$obj_eva=new espacio_v();
/////////////////
//asignando valores del file...
$nombre_archivo = $_FILES['imagen_eva']['name'];
$tipo_archivo = $_FILES['imagen_eva']['type'];
$tamano_archivo = $_FILES['imagen_eva']['size'];
$serv = $_SERVER['DOCUMENT_ROOT']."/eva_juventud/img/img_eva/";
//valido el nombre del archivo segun el id
$nombre_archivo2="imagen_eva".$id_eva.".jpg";
//creo la ruta
$ruta=$serv.$nombre_archivo2;
//valido que la imagen solo sea tipo jpg...
if (!((strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "jpg")||(strpos($tipo_archivo, "JPG"))) && ($tamano_archivo < 1000000000))) 
{
    die("error_tipo_archivo");
}else
{
    //muevo la imagen de directorio
    if (move_uploaded_file($_FILES['imagen_eva']['tmp_name'],$ruta))
    {
	  	chmod($ruta,0777);//permisos al directorio
	  	$rs=$obj_eva->ac_imagen_eva($nombre_archivo2,$id_eva);
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