<?php
session_start();
ini_set("memory_limit","1024M");
require("../modelos/modelo.registrar_eva.php");
require("../modelos/modelo.paginacion_consultas.php");
//valido los post
//validar_post_paginacion();
$mensaje=array();
$cuerpo_contenido='';
$boton_ir_aula='';
$f_ti_eva='';
$tipo_us=$_SESSION["id_perfil"];
//////////////////////////////
if(isset($_POST["id_aula"]))
{
	if($_POST["id_aula"]!="")
	{
		$id_aula=$_POST["id_aula"];
	}
	else
	{
		$id_aula='';
	}	
}
else
{
	$id_aula='';
}
if(isset($_POST["f_ti_eva"])){ if($_POST["f_ti_eva"]!=""){ $f_ti_eva=strtoupper($_POST["f_ti_eva"]); } }	
$f_ti_un=strtoupper($_POST["f_ti_unidad"]);//nombre del eva
//$mensaje[0]=$f_ti_un;
$f_n_un=$_POST["f_n_unidad"];
//////////////////////////////
$offset=$_POST["offset"];//offset
$j=$offset;//contador para el campo n°
$limit=$_POST["limit"]; //limit
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_un";
$rs=array();
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_cuerpo_tabla_un_adm($id_aula,$f_ti_un,$f_n_un,$offset,$limit,$f_ti_eva);
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
		//////
		//BOTON PARA VER la unidad
		$btn_mod_un="<button class='btn btn-primary' id='btn_mod_unidad".$k."' name='btn_mod_unidad".$k."'  onclick='ir_un_form(".$rs[$i][0].");' title='Consultar unidad'><i class='fa fa-search'></i></i></button>";
		if($tipo_us==2)
		{
			$btn_mod_un="";
		}	
		set_time_limit(0);
		$j=$j+1;
		$title=strtoupper($rs[$i][4]);		
		$resumen=substr($rs[$i][3],0,1000)."...";
		$cuerpo_contenido.="<tr>
								<td id='titulo_eva_tbl' width='20%'>".$rs[$i][4]."</td>
								<td width='20%'>".$rs[$i][1]."</td>
								<td width='15%'>".$rs[$i][2]."</td>
								<td width='30%'>".$resumen."</td>
								<td width='20%'>".$btn_mod_un."</td>
					 		</tr>";
		//////
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_eva->num_rows5,$nom_fun);
	$mensaje[0]=$cuerpo_contenido;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	$mensaje[2]="Contenidos E.V.A:  ".$title;
	die(json_encode($mensaje));
}
?>