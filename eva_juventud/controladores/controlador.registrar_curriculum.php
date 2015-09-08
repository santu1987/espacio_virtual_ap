<?php
session_start();
require("../modelos/modelo.curriculum.php");
require("../modelos/modelo.registrar_auditoria.php"); 
$mensaje=array();
$datos=array();
$rs= array();
if(((ISSET($_POST["resumen_perfil_profesional"]))&&(ISSET($_POST["resumen_perfil_academico"]))&&
(ISSET($_POST["resumen_perfil_laboral"]))&&(ISSET($_POST["resumen_perfil_cursos"])))&&
(($_POST["resumen_perfil_profesional"]!="")&&($_POST["resumen_perfil_academico"]!="")&&($_POST["resumen_perfil_laboral"])))
{
	$datos[0]=$_POST["resumen_perfil_profesional"];
	$datos[1]=$_POST["resumen_perfil_academico"];
	$datos[2]=$_POST["resumen_perfil_laboral"];
	$datos[3]=$_POST["resumen_perfil_cursos"];
	$datos[4]=$_POST["id_cur"];if($datos[4]==''){$datos[4]=0;}
}
else
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}
$obj_curriculum=new Curriculum();
$rs=$obj_curriculum->cargar_curriculum($datos[4],$datos[0],$datos[1],$datos[2],$datos[3]);
//die(json_encode($rs));
if($rs=="error")
{
	die(json_encode("error_bd"));
}
else
{
	$mensaje[0]="registro_exitoso";
	$mensaje[1]=$rs[0][0];
	/////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
    $auditoria_cur=new auditoria("Curriculum","Actualizacion de curriculum");
    $auditoria=$auditoria_cur->registrar_auditoria();
    if($auditoria==false)
    {
        $mensaje[0]='error_auditoria';
        die(json_encode($mensaje)); 
    }
	/////////////////////////////////////////////////////////////////////////////////////////////////////

	die(json_encode($mensaje));
}
?>