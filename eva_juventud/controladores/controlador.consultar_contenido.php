<?php
session_start();
require("../modelos/modelo.registrar_eva.php");
$mensaje=array();
$rs=array();
if(isset($_POST["id_contenido"])&&($_POST["id_contenido"]!=""))
{
	$id_contenido=$_POST["id_contenido"];
}	
//declaro la clase
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_contenido($id_contenido);
//die(json_encode($rs));
$rs_pdf=$obj_eva->consultar_archivos($id_contenido);
//die(json_encode($rs_pdf));
for($i=0;$i<=count($rs_pdf)-1;$i++)
{
	$contenidos_pdf.="<i class='fa fa-file-pdf-o'></i> ".$rs_pdf[$i][0]."-";
}
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else
{
	$mensaje[0]=$rs;
	$mensaje[1]=$contenidos_pdf;
	die(json_encode($mensaje));
}
?>