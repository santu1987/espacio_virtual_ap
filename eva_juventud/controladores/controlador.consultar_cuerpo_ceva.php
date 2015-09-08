<?php
session_start();
require("../modelos/modelo.registrar_eva.php");
require("../modelos/modelo.paginacion_consultas.php");  
$mensaje=array();
$rs=array();
if(((ISSET($_POST["f_ceva"]))&&($_POST["f_ceva"]!=""))||
((ISSET($_POST["f_ceva2"]))&&($_POST["f_ceva2"]!="")))
{
	$fceva=strtoupper($_POST["f_ceva"]);
	$fceva2=strtoupper($_POST["f_ceva2"]);
}else
{
	$fceva='';
	$fceva2='';
}
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_tabla_ceva";
///////////////////////////////////////////////
//declaro la clase
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_cuerpo_ceva($fceva,$fceva2,$offset,$limit);
//die(json_encode($fceva."-".$fceva2));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else
{
	for($i=0;$i<=count($rs)-1;$i++)
	{
		$k=$i+1;
		$cuerpo_ceva.="<tr>
							<td width='50%'>".$rs[$i][1]."</td>
							<td width='50%'>".$rs[$i][2]."</td>
							<td width='50%'>".$rs[$i][3]."</td>
							<td width='25%'><button class='btn btn-danger' id='btn_selec".$k."' onmouseover='cambiar_color_btn(this);' onmouseout='cambiar_color_btn2(this);' onclick='conf_eva_selec(".$rs[$i][0].");' ><span class='glyphicon glyphicon-ok'></span></button></td>	
					 </tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_eva->num_rows,$nom_fun);
	$mensaje[0]=$cuerpo_ceva;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	
///////////////////////////////////////////////
?>