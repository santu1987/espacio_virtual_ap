<?php
//session_start();
require_once("../modelos/modelo.registrar_eva.php");
$objeto= new espacio_v();
$aulas=$objeto->consultar_aulas();
//die($estado[0][1]);
$opcion_aula_virtual =  '<option id=0 value="">--SELECCIONE--</option>';
//echo '<option id=1>--'.$recordset[0][1].'</option>';
/////////////////////
for($i=0;$i<=count($estado)-1;$i++)
{
  $opcion_aula_virtual.= '<option id="'.$estado[$i][0].'" value="'.$estado[$i][0].'">'.strtoupper($estado[$i][1]).'</option>' ;
}
/////////////////////////////////////////////////////
?>