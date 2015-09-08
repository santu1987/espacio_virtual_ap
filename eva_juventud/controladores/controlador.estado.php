<?php
session_start();
require_once("../modelos/modelo.sql_estado.php");
$objeto= new BuscarEstado();
$estado=$objeto->buscar_estados();
//die($estado[0][1]);
$option_estado =  '<option id="-1" value="-1">[Estado]</option>';
//echo '<option id=1>--'.$recordset[0][1].'</option>';
/////////////////////
for($i=0;$i<=count($estado)-1;$i++)
{
  $option_estado.= '<option id="'.$estado[$i][0].'" value="'.$estado[$i][0].'">'.strtoupper($estado[$i][1]).'</option>' ;
}
/////////////////////////////////////////////////////
?>