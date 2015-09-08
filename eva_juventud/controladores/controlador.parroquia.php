<?php
session_start();
require_once("../modelos/modelo.sql_parroquia.php");

if(isset($_GET['id']))
{
	$id=$_GET['id'];
}

if(isset($_GET['idp']))
{
	$idp=$_GET['idp'];
}else{
	$idp='';
}
////creo el objeto
$parroquia= new BuscarParroquia($id);
$recordset=$parroquia->buscar_parroquia();
$a='';
echo '<option id="-1" value="-1">[Parroquia]</option>';
for($i=0;$i<=count($recordset)-1;$i++)
{
	if($recordset[$i][2]==$idp){
  		$a="selected";
  	}else{
  		$a="";
  	}		
   echo '<option id="'.$recordset[$i][2].'" value="'.$recordset[$i][2].'" '.$a.'>'.strtoupper($recordset[$i][3]).'</option>';
}
/////////////////////////////////////////////////////
?>