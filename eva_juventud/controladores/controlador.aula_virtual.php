<?php
session_start();
require_once("../modelos/modelo.registrar_eva.php");
$objeto= new espacio_v();
$aulas=$objeto->consultar_aulas();
//die($aulas);
$opcion_aula_virtual =  '<option id="-1" value="-1">[Espacio Virtual de Ap. E.V.A]</option>';
//echo '<option id=1>--'.$recordset[0][1].'</option>';
/////////////////////
for($i=0;$i<=count($aulas)-1;$i++)
{
  $opcion_aula_virtual.= '<option id="'.$aulas[$i][0].'" value="'.$aulas[$i][0].'">'.strtoupper($aulas[$i][1]).'</option>' ;
}
/////////////////////////////////////////////////////
?>