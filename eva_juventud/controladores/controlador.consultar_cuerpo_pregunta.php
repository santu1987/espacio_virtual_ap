<?php
session_start();
require("../modelos/modelo.registrar_preguntas_eva.php");
require("../modelos/modelo.paginacion_consultas.php");  
$mensaje=array();
$rs=array();
if(((ISSET($_POST["f_aula"]))&&($_POST["f_aula"]!=""))||
((ISSET($_POST["f_ev"]))&&($_POST["f_ev"]!=""))||
((ISSET($_POST["f_pr"]))&&($_POST["f_pr"]!="")))
{
	$faula=strtoupper($_POST["f_aula"]);
	$fev=strtoupper($_POST["f_ev"]);
	$fpr=strtoupper($_POST["f_pr"]);
}else
{
	$faula='';
	$fev='';
	$fpr='';
}
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_tabla_preguntas";
///////////////////////////////////////////////
//declaro la clase
$obj_preguntas=new preguntas();
$rs=$obj_preguntas->consultar_cuerpo_preguntas($faula,$fev,$fpr,$offset,$limit);
//die(json_encode($rs));
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
		$cuerpo_preguntas.="<tr>
							<td width='25%'>".$rs[$i][0]."</td>
							<td width='25%'>".$rs[$i][1]."</td>
							<td width='25%'>".$rs[$i][2]."</td>
							<td width='25%'>".$rs[$i][3]."</td>
							<td width='25%'><button class='btn btn-danger' id='btn_selec".$k."' onmouseover='cambiar_color_btn(this);' onmouseout='cambiar_color_btn2(this);' onclick='pregunta_select(".$rs[$i][0].");' ><span class='glyphicon glyphicon-ok'></span></button></td>	
					 </tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_preguntas->num_rows,$nom_fun);
	$mensaje[0]=$cuerpo_preguntas;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	
///////////////////////////////////////////////
?>
