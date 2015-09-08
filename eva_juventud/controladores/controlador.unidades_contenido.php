<?php
session_start();
require_once("../modelos/modelo.registrar_eva.php");

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
$unidad= new espacio_v();
$rs_contenido=$unidad->consultar_unidad2($id);
$a='';
$opcion.='<option id="-1" value="-1">[Contenidos E.V.A]</option>';
for($i=0;$i<=count($rs_contenido)-1;$i++)
{
  if($rs_contenido[$i][0]==$idm){
  	$a="selected";
  }else{
  	$a="";
  }	
  //$b=$i+1;
  $opcion.='<option id="'.$rs_contenido[$i][0].'" value="'.$rs_contenido[$i][0].'" '.$a.'> Contenido '.$rs_contenido[$i][1].'</option>';
}
die($opcion);
/////////////////////////////////////////////////////
?>