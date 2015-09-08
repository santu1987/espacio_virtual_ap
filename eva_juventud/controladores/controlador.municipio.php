<?php
session_start();
require_once("../modelos/modelo.sql_municipio.php");

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
$municipio= new BuscarMunicipio($id);
$recordset=$municipio->buscar_municipio();
$a='';
echo '<option id="-1" value="-1">[Municipio]</option>';
for($i=0;$i<=count($recordset)-1;$i++)
{
  if($recordset[$i][1]==$idm){
  	$a="selected";
  }else{
  	$a="";
  }	
   echo '<option id="'.$recordset[$i][1].'" value="'.$recordset[$i][1].'" '.$a.'>'.strtoupper($recordset[$i][2]).'</option>';

}
/////////////////////////////////////////////////////
?>