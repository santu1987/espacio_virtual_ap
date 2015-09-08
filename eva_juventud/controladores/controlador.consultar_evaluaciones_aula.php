<?php
session_start();
require_once("../modelos/modelo.registrar_evaluacion.php");
if(isset($_GET['id']))
{
	$id=$_GET['id'];
}
if(isset($_GET['idm']))
{
	$idm=$_GET['idm'];
}else{
	$idm='';
}
////creo el objeto
$evaluacion= new evaluacion();
$rs_evaluacion=$evaluacion->consultar_evaluacion_aula($id);
$a='';
$opcion.='<option id="0" value="0">--SELECCIONE--</option>';
for($i=0;$i<=count($rs_evaluacion)-1;$i++)
{
  if($rs_evaluacion[$i][0]==$idm){
  	$a="selected";
  }else{
  	$a="";
  }	
  //$b=$i+1;
  $opcion.='<option id="'.$rs_evaluacion[$i][0].'" value="'.$rs_evaluacion[$i][0].'" '.$a.'>'.$rs_evaluacion[$i][3].'</option>';
}
die($opcion);
/////////////////////////////////////////////////////
?>