<?php
session_start();
require("../modelos/modelo.tipo_usuario.php");
require("../modelos/modelo.paginacion_consultas.php");  
$mensaje=array();
$rs=array();
if((ISSET($_POST["f_us"]))&&($_POST["f_us"]!=""))
{
	$f_us=strtoupper($_POST["f_us"]);
}else
$f_us='';
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_tabla_tpus";
///////////////////////////////////////////////
//declaro la clase
$tipo_usuario=new tipo_us();
$rs=$tipo_usuario->consultar_cuerpo_tipo_usuario($f_us,$offset,$limit);
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
		$cuerpo_mod.="<tr>
							<td width='50%'>".$rs[$i][1]."</td>2
							<td width='25%'><button class='btn btn-danger' id='btn_selec".$k."' onmouseover='cambiar_color_btn(this);' onmouseout='cambiar_color_btn2(this);' onclick='car_tp_us(".$rs[$i][0].");' ><span class='glyphicon glyphicon-ok'></span></button></td>	
					 </tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$tipo_usuario->num_rows,$nom_fun);
	$mensaje[0]=$cuerpo_mod;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	
///////////////////////////////////////////////
?>