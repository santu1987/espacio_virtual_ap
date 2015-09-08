<?php
session_start();
require("../modelos/modelo.registrar_eva.php");
//validando el campo....
if(ISSET($_POST["id_eva"]))
{
	if($_POST["id_eva"]!="")
	{
		$id_eva=$_POST["id_eva"];
	}	
}
$cadena='';
//
//creo el objeto...
$obj_eva= new espacio_v();
$rs=$obj_eva->consultar_eva_resumen($id_eva);

//die(json_encode($rs);
if($rs=="error")
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}else
{
//Para recorrer unidades relacionadas a un aula
	$rs2=$obj_eva-> consultar_unidad_aula($id_eva);
	///recorro el vector de unidades
		for($j=0;$j<=count($rs2)-1;$j++)
		{
			$cadena.="<i class='fa fa-check'></i>".$rs2[$j][0]."<br>";
		}	
	//
//Para consultar si la persona esta inscrita...
	$rs3=$obj_eva->consultar_inscripcion($id_eva,$_SESSION['id_us']);		
	$mensaje[0]=$rs;
	$mensaje[1]=$cadena;
	if($rs3!=""){$mensaje[2]=$rs3[0][0];};
	die(json_encode($mensaje));

}
?>