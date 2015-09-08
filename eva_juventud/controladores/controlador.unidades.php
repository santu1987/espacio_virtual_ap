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
$cuantas_aulas=$unidad->consultar_unidad($id);
//die($cuantas_aulas);
$a='';
$opcion.='<option id="-1" value="-1">[Contenidos E.V.As]</option>';
for($i=1;$i<=$cuantas_aulas[0][0];$i++)
{
  if($i==$idm){
  	$a="selected";
  }else{
  	$a="";
  }	
  //$b=$i+1;
  $opcion.='<option id="'.$i.'" value="'.$i.'" '.$a.'>Contenido '.$i.'</option>';
}
die($opcion);
/////////////////////////////////////////////////////
?>