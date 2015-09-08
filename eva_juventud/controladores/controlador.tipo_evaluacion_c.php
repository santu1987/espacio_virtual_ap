<?php
session_start();
require_once("../modelos/modelo.tipo_evaluacion.php");

if(isset($_GET['id']))
{
	$id=$_GET['id'];
}
else
{
  $id="";
}
if(isset($_GET['idm']))
{
	$idm=$_GET['idm'];
}else{
	$idm='';
}
////creo el objeto
$tipo_evaluacion= new tipo_evaluacion();
$rs_tipo_evaluacion=$tipo_evaluacion->consultar_tipo_evaluacion($id);
$a='';
$opcion.='<option id="0" value="0">[Tipo de evaluaci&oacute;n]</option>';
for($i=0;$i<=count($rs_tipo_evaluacion)-1;$i++)
{
  if($rs_tipo_evaluacion[$i][0]==$idm){
  	$a="selected";
  }else{
  	$a="";
  }	
  //$b=$i+1;
  $opcion.='<option id="'.$rs_tipo_evaluacion[$i][0].'" value="'.$rs_tipo_evaluacion[$i][0].'" '.$a.'>'.$rs_tipo_evaluacion[$i][1].'</option>';
}
die($opcion);
/////////////////////////////////////////////////////
?>