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
////////////////////////////////////////////////////////
if(isset($_POST["f_ti_eva"])){ if($_POST["f_ti_eva"]!=""){ $f_ti_eva=strtoupper($_POST["f_ti_eva"]); } }	
if(isset($_POST["id_aula"])){ if($_POST["id_aula"]){ $id_aula=$_POST["id_aula"]; }else{ $id_aula=""; }  } else { $id_aula=""; }
if(isset($_POST["f_ti_unidad"])){ if($_POST["f_ti_unidad"]){ $f_ti_unidad=strtoupper($_POST["f_ti_unidad"]); }else{ $f_ti_unidad=""; }  } else { $f_ti_unidad=""; }
if(isset($_POST["f_fecha_eval"])){ if($_POST["f_fecha_eval"]){ $f_fecha_eval=$_POST["f_fecha_eval"]; }else{ $f_fecha_eval=""; }  } else { $f_fecha_eval=""; }
if(isset($_POST["f_tipo_eval"])){ if($_POST["f_tipo_eval"]){ $f_tipo_eval=strtoupper($_POST["f_tipo_eval"]); }else{ $f_tipo_eval=""; }  } else { $f_tipo_eval=""; }
if(isset($_POST["offset"])){ if($_POST["offset"]){ $offset=$_POST["offset"]; }else{ $offset=""; }  } else { $offset=""; }
if(isset($_POST["limit"])){ if($_POST["limit"]){ $limit=$_POST["limit"]; }else{ $limit=""; }  } else { $limit=""; }
if(isset($_POST["actual"])){ if($_POST["actual"]){ $actual=$_POST["actual"]; }else{ $actual=""; }  } else { $actual=""; }
if(isset($_POST["f_estatus"])){ if($_POST["f_estatus"]!=""){ $f_estatus=$_POST["f_estatus"]; }else{ $f_estatus=""; }  } else { $f_estatus=""; }
////////////////////////////////////////////////////////
$nom_fun="cargar_consulta_eval";
$rs=array();
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_cuerpo_tabla_eval($id_aula,$f_ti_unidad,$f_fecha_eval,$f_tipo_eval,$offset,$limit,$f_ti_eva,$f_estatus);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else
{
	$titulo_eva=$rs[0][5];
	for($i=0;$i<=count($rs)-1;$i++)
	{
		
		$k=$i+1;
		///////
		$btn_mod_un="<button class='btn btn-primary' id='btn_mod_eval".$k."' name='btn_mod_eval".$k."'  onclick='ir_eval_form(".$rs[$i][0].",".$rs[$i][10].");' title='Consultar evaluacion'><i class='fa fa-search'></i></i></button>";
		///////
		if($rs[$i][7]==0)
		{	
			if($rs[$i][6]=='ACTIVA')
			{
				$btn_cerrar="<button type='button' class='btn btn-success' id='btn_cerrar_eval_consulta' name='btn_cerrar_eval_consulta' onclick='cerrar_eva(".$rs[$i][0].");' title='Aperturar evaluaci&oacute;n'><i class='fa fa-power-off'></i></i></button> ";
			}else
			if($rs[$i][6]=='INACTIVA')
			{
				//si el rs[8] cantidad preguntas a registrar  == cantidad preguntas registradas rs[9]
				if($rs[$i][8]==$rs[$i][9])
				{
					$btn_cerrar="<button type='button' class='btn btn-danger' id='btn_cerrar_eval_consulta' name='btn_cerrar_eval_consulta' onclick='cerrar_eva(".$rs[$i][0].");' title='Cerrar evaluaci&oacute;n'><i class='fa fa-power-off'></i></i></button> ";
				}
				//	
			}
		}
		//---
		if($tipo_us==2)
		{
			$btn_mod_un='';
			$btn_cerrar='';
		}	
		//---
		$resumen_eval=substr($rs[$i][2],0,1000)."...";
		$cuerpo_contenido.="<tr>
								<td id='titulo_eva_tbl' width='20%'>".$rs[$i][5]."</td>
								<td width='20%'>".$rs[$i][1]."</td>
								<td width='20%'>".$resumen_eval."</td>
								<td width='10%'>".$rs[$i][3]."</td>
								<td width='10%'>".$rs[$i][6]."</td>
								<td width='10%'>".$rs[$i][4]."</td>
								<td width='20%'>".$btn_mod_un." ".$btn_cerrar."</td>
							</tr>";
		$imagen_eva='';
		$btn_cerrar='';
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_eva->num_rows6,$nom_fun);
	$mensaje[0]=$cuerpo_contenido;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	$mensaje[2]="Evaluaciones de contenidos E.V.A: ".$titulo_eva;
	die(json_encode($mensaje));
}
?>