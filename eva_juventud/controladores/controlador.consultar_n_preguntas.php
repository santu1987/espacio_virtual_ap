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
$preguntas= new evaluacion();
$cuantas_preguntas=$preguntas->consultar_preguntas($id);
//die($cuantas_preguntas[0][0]);
$a='';
$opcion.='<option id="-1" value="-1">--SELECCIONE--</option>';
for($i=1;$i<=$cuantas_preguntas[0][0];$i++)
{
  if($i==$idm){ $a="selected"; }else {$a="";}
  $opcion.='<option id="'.$i.'" value="'.$i.'"'.$a.'>Pregunta '.$i.'</option>';
}
die($opcion);
/////////////////////////////////////////////////////
?>