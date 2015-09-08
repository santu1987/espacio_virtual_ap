<?php
session_start();
require_once("../modelos/modelo.usuario.php");
$mensajes=array();
$rs=array();
$rs2=array();
$cursos="";
//validando el id_us
if(isset($_POST["id_us"])){ if($_POST["id_us"]){ $id_us=$_POST["id_us"];}else{ $id_us="";} }else{ $id_us="";}
//
$obj_us=new Usuario();
$rs=$obj_us->consultar_perfil_us($id_us);
if($rs=="error")
{
	$mensajes[0]="error";
	die(json_encode($mensajes));
}else
{
	$rs2=$obj_us->consultar_cursos($id_us,$rs[0][10]);
	//
	for($i=0;$i<=count($rs2)-1;$i++)
	{
		$cursos=$cursos."<i class='fa fa-check-circle-o' style='color:#16E91D'></i>".$rs2[$i][0].'</br>';
	}	
	//
	$mensajes[0]=$rs;
	$mensajes[1]=$cursos;
	die(json_encode($mensajes));
}	
?>