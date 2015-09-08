<?php
session_start();
//incluyo al modelo
require("../modelos/modelo.registrar_eva.php");
//validando campos
if((isset($_POST["aula"]))&&(isset($_POST["unidad"]))&&($_POST["aula"]!="")&&($_POST["unidad"]!=""))
{
	$aula=$_POST["aula"];
	$unidad=$_POST["unidad"];
}
else
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}
$obj_unidades=new espacio_v();
$rs=$obj_unidades->consultar_unidades($aula,$unidad);
if($rs=="error")
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}
else
{
	//consultando los materiales
	$rs_pdf=$obj_unidades->consultar_archivos($rs[0][0]);
	//die(json_encode($rs_pdf));
	for($i=0;$i<=count($rs_pdf)-1;$i++)
	{
		$contenidos_pdf.="<i class='fa fa-file-pdf-o'></i> ".$rs_pdf[$i][0]."-";
	}

	//
	$mensaje[0]=$rs;
	$mensaje[1]=$contenidos_pdf;
	die(json_encode($mensaje));
}
?>