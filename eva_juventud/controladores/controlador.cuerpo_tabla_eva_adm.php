<?php
session_start();
ini_set("memory_limit","1024M");
require("../modelos/modelo.registrar_eva.php");
require("../modelos/modelo.paginacion_consultas.php");
//valido los post
//validar_post_paginacion();
//
$mensaje=array();
$cuerpo_contenido='';
$boton_ir_aula='';
$tipo_us=$_SESSION["id_perfil"];
if(isset($_POST["f_nombre_eva"])){ if($_POST["f_nombre_eva"]!=""){ $f_nombre_eva=strtoupper($_POST["f_nombre_eva"]); } else { $f_nombre_eva="";} } else { $f_nombre_eva="";}
if(isset($_POST["f_fecha_aula"])){ if($_POST["f_fecha_aula"]!=""){ $f_fecha_aula=strtoupper($_POST["f_fecha_aula"]); } else { $f_fecha_aula="";} } else { $f_fecha_aula="";}
if(isset($_POST["f_selec_tipo"])){ if($_POST["f_selec_tipo"]!=""){ $f_selec_tipo=strtoupper($_POST["f_selec_tipo"]); } else { $f_selec_tipo="";} } else { $f_selec_tipo="";}

$offset=$_POST["offset"];//offset
$j=$offset;//contador para el campo n°
$limit=$_POST["limit"]; //limit
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_tabla_aulas_adm";
$rs=array();
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_cuerpo_tabla_eva_adm($f_nombre_eva,$offset,$limit,$f_fecha_aula,$f_selec_tipo);
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
		$clase_btn_inac='';
		//valido: si tiene imagen el eva lo incluyo en una variable y se  muestra...
		if($rs[$i][1]!="")
		{
			$imagen_eva='<img src="./img/img_eva/'.$rs[$i][1].'"  alt="..." class="img-circle img_eva">';
		}else
		{
			$imagen_eva='';
		}	
		//validando activacion o inactivacion de boton
		if($rs[$i][8]==1)
		{
			$clase_btn_inac="btn-success";
			$titulo_aula="Inactivar E.V.A";
		}else
		if($rs[$i][8]==0)
		{
			$clase_btn_inac="btn-danger";
			$titulo_aula="Activar E.V.A";
		}	
		//////

		//////
		//BOTON PARA VER EL AULA
		$boton_ir_aula="<button class='btn btn-danger operaciones_be' id='btn_ir_aula_insc".$k."'  onclick='ir_aula_resumen(".$rs[$i][0].");' title='Ir al aula'><i class='fa fa-university'></i></i></button>";
		//BOTON PARA CONSULTAR PARA MODIFICAR EL AULA
		$boton_consultar_aula="<button class='btn btn-primary operaciones_be' style='margin-left:2%' title='Consultar aula' id='btn_cons_eva".$k."'onclick='consultar_conf_eva(".$rs[$i][0].");' ><i class='fa fa-search'></i></button>";
		//BOTON PARA MODIFICAR EVALUACIONES
		if($rs[$i][7]>0)
		{
			$boton_mod_evaluaciones="<button class='btn btn-warning' title='Ver evaluaciones de esta aula' id='btn_cons_mod_eval".$k."' name='btn_cons_mod_eval".$k."' onclick='consultar_evaluaciones_aula(".$rs[$i][0].")'><i class='fa fa-external-link'></i></button>";
		}
		else
		{
			$boton_mod_evaluaciones="";	
		}	
		if(($tipo_us=="4")&& ($rs[$i][6]>0))
		{
			//BOTON PARA APERTURAR O CERRAR UN AULA
			$boton_ap_cerrar="<button class='btn ".$clase_btn_inac."' title='".$titulo_aula."' id='btn_ap_cerrar".$k."' name='btn_ap_cerrar".$k."' onclick='habilitar_aula(".$rs[$i][0].",".$k.")'><i class='fa fa-power-off'></i></button>";
		}
		else
		{
			$boton_ap_cerrar="";
		}	
		//BOTON PARA MODIFICAR UNIDADES
		if($rs[$i][6]>0)
		{
			$boton_mod_unidades="<button class='btn btn-info' title='Consultar Contenidos de este EVA' id='btn_cons_mod_un".$k."' name='btn_cons_mod_un".$k."' onclick='consulta_un_eva(".$rs[$i][0].")'><i class='fa fa-book'></i></button>";
		}
		else
		{
			$boton_mod_unidades="";
		}
		//debido a que tiene demasiados registros
		set_time_limit(0);
		//---------------------------------------
		if($tipo_us==2)
		{
			$boton_consultar_aula="";
		}
		//---------------------------------------
		$j=$j+1;
		$tlf=$rs[$i][2];
		$resumen_aula=substr($rs[$i][3],0,1000)."...";
		$cuerpo_contenido.="<tr>
								<td width='10%'>".$imagen_eva."</td>
								<td width='20%'>".$rs[$i][2]."</td>
								<td width='30%'>".$resumen_aula."</td>
								<td width='10%'>".$rs[$i][4]."</td>
								<td width='10%'>".$rs[$i][5]."</td>
								<td width='30%' id='botonera".$k."' width='30%'>".$boton_ir_aula." ".$boton_consultar_aula." ".$boton_mod_unidades." ".$boton_mod_evaluaciones." ".$boton_ap_cerrar."</td>	
					 		</tr>";
		$imagen_eva='';
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_eva->num_rows4,$nom_fun);
	$mensaje[0]=$cuerpo_contenido;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}
?>