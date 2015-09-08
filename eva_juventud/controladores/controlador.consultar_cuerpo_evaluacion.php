<?php
session_start();
require("../modelos/modelo.registrar_evaluacion.php");
require("../modelos/modelo.paginacion_consultas.php");  
$mensaje=array();
$rs=array();
if(((ISSET($_POST["f_aula"]))&&($_POST["f_aula"]!=""))||
((ISSET($_POST["f_unidades"]))&&($_POST["f_unidades"]!=""))||
((ISSET($_POST["f_tipo_ev"]))&&($_POST["f_tipo_ev"]!="")))
{
	$faula=strtoupper($_POST["f_aula"]);
	$funidades=$_POST["f_unidades"];
	$ftipo=$_POST["f_tipo_ev"];
}else
{
	$faula='';
	$funidades='';
	$ftipo='';
}
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_tabla_evaluaciones";
///////////////////////////////////////////////
//declaro la clase
$obj_eva=new evaluacion();
$rs=$obj_eva->consultar_cuerpo_evaluaciones($faula,$funidades,$ftipo,$offset,$limit);
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else
{
	$cuerpo_evaluaciones="";
	for($i=0;$i<=count($rs)-1;$i++)
	{
		$k=$i+1;
		$cuerpo_evaluaciones.="<tr>
							<td width='35%'>".$rs[$i][1]."</td>
							<td width='15%'>".$rs[$i][2]."</td>
							<td width='25%'>".$rs[$i][3]."</td>
							<td width='15%'>".$rs[$i][5]."</td>
							<td width='25%'><button class='btn btn-danger' id='btn_selec".$k."' onmouseover='cambiar_color_btn(this);' onmouseout='cambiar_color_btn2(this);' onclick='evaluacion_select(".$rs[$i][4].",".$rs[$i][0].");' ><span class='glyphicon glyphicon-ok'></span></button></td>	
					 </tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_eva->num_rows,$nom_fun);
	$mensaje[0]=$cuerpo_evaluaciones;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	
///////////////////////////////////////////////
?>
