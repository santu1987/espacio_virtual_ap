<?php
session_start();
require_once("../modelos/modelo.registrar_eva.php");
/////////////////
$mensaje=array();
$rs=array();
//asignando valores del file...
$p=$_POST["cuantos_pdf"];
//valido los nombres de los archivos con el id del aula virtual
if(ISSET($_POST["cont_ev"])&&($_POST["cont_ev"]!=""))
{
	$id_cont=$_POST["cont_ev"];
}else
{
	die("campos_blancdos");
}
for($i=1;$i<=$p;$i++)
{
	$pdf=$_FILES['pdf_unidad'.$i]['name'];
	$tipo_archivo_pdf= $_FILES['pdf_unidad'.$i]['type'];
	$tamano_pdf=$_FILES['pdf_unidad'.$i]['size'];
	//-- Para validar tamaño
	if($tamano_pdf>2000000)
	{
		$mensaje[0]="error_tamano";
		die(json_encode($mensaje));
	}
	//variable servidor donde se cargaran las imagenes
	$serv = $_SERVER['DOCUMENT_ROOT']."/eva_juventud/pdf/";
	//creo las rutas
	$nombre_pdf="pdf".$i."_unidad".$id_cont.".pdf";
	$ruta_pdf=$serv.$nombre_pdf;
	//valido que cada imagen sea solo tipo jpg
	//creo mi clase certificado...
	$tipo_archivo='pdf';
	$obj_unidad=new espacio_v();
	$datos[0]=$obj_unidad->cargar_archivos_unidad($ruta_pdf,$nombre_pdf,$tipo_archivo_pdf,$tamano_pdf,$_FILES['pdf_unidad'.$i]['tmp_name'],$id_cont,$tipo_archivo);
	/////////////////////////////////////
	if($datos[0]=="error_tipo_archivo")
	{
		$mensaje[0]="error_tipo_archivo";
		die(json_encode($mensaje));
	}
	if($datos[0]==1)
	{
		$rs=$obj_unidad->registrar_pdf($nombre_pdf,$id_cont);
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
}
	$mensaje[0]="archivo_cargado";	
	die(json_encode($mensaje));
?>