<?php
session_start();
require_once("../modelos/modelo.tipo_usuario.php");
////creo el objeto
$tipo_us= new tipo_us();
$rs=$tipo_us->consultar_tp_usuario();
$opcion_tp_us.='<option id="-1" value="-1">[Tipo Usuario]</option>';
for($i=0;$i<=count($rs)-1;$i++)
{
  if($rs[$i][0]!='4')
  {
	  $opcion_tp_us.='<option id="'.$rs[$i][0].'" value="'.$rs[$i][0].'" >'.$rs[$i][1].'</option>';
  }
}
/////////////////////////////////////////////////////
?>