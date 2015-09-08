<?php
//session_start();
require_once("../modelos/modelo.tipo_estudio.php");
$objeto= new tipo_estudio();
$tipo_estudio=$objeto->buscar_estudio();
//die($estado[0][1]);
$option_estudio =  '<option id=0 value="">[Tipo E.V.A]</option>';
//echo '<option id=1>--'.$recordset[0][1].'</option>';
/////////////////////
for($i=0;$i<=count($tipo_estudio)-1;$i++)
{
  $option_estudio.= '<option id="'.$tipo_estudio[$i][0].'" value="'.$tipo_estudio[$i][0].'">'.strtoupper($tipo_estudio[$i][1]).'</option>';
}
/////////////////////////////////////////////////////
?>