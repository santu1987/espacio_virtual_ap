<?php
session_start();
ini_set("memory_limit","1024M");
require("../modelos/modelo.registrar_eva.php");
require("../modelos/modelo.paginacion_consultas.php");
//valido los post
//validar_post_paginacion();
//
$fecha_actual=date("Y-m-d");
$mensaje=array();
$cuerpo_contenido='';
$boton_ir_aula='';
////////////////////////////////////////////////////////
if(isset($_POST["f_ti_eva"])){ if($_POST["f_ti_eva"]!=""){ $f_ti_eva=strtoupper($_POST["f_ti_eva"]); } }	
if(isset($_POST["id_aula"])){ if($_POST["id_aula"]){ $id_aula=$_POST["id_aula"]; }else{ $id_aula=""; }  } else { $id_aula=""; }
if(isset($_POST["f_ti_unidad"])){ if($_POST["f_ti_unidad"]){ $f_ti_unidad=strtoupper($_POST["f_ti_unidad"]); }else{ $f_ti_unidad=""; }  } else { $f_ti_unidad=""; }
if(isset($_POST["f_fecha_eval"])){ if($_POST["f_fecha_eval"]){ $f_fecha_eval=$_POST["f_fecha_eval"]; }else{ $f_fecha_eval=""; }  } else { $f_fecha_eval=""; }
if(isset($_POST["f_fecha_ac"])){ if($_POST["f_fecha_ac"]){ $f_fecha_ac=$_POST["f_fecha_ac"]; }else{ $f_fecha_ac=""; }  } else { $f_fecha_ac=""; }
if(isset($_POST["f_tipo_eval"])){ if($_POST["f_tipo_eval"]){ $f_tipo_eval=strtoupper($_POST["f_tipo_eval"]); }else{ $f_tipo_eval=""; }  } else { $f_tipo_eval=""; }
if(isset($_POST["offset"])){ if($_POST["offset"]){ $offset=$_POST["offset"]; }else{ $offset=""; }  } else { $offset=""; }
if(isset($_POST["limit"])){ if($_POST["limit"]){ $limit=$_POST["limit"]; }else{ $limit=""; }  } else { $limit=""; }
if(isset($_POST["actual"])){ if($_POST["actual"]){ $actual=$_POST["actual"]; }else{ $actual=""; }  } else { $actual=""; }
if(isset($_POST["f_estatus"])){ if($_POST["f_estatus"]!=""){ $f_estatus=$_POST["f_estatus"]; }else{ $f_estatus=""; }  } else { $f_estatus=""; }
$offset=$_POST["offset"];//offset
$limit=$_POST["limit"]; //limit
$actual=$_POST["actual"];
////////////////////////////////////////////////////////
$nom_fun="cargar_consulta_eval";
$rs=array();
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_cuerpo_tabla_mieval($id_aula,$f_ti_unidad,$f_fecha_eval,$f_tipo_eval,$offset,$limit,$f_ti_eva,$f_estatus,$f_fecha_ac);
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
		//
		if($rs[$i][7]==null)
		{
			$calificacion='0';
		}else
		{
			$calificacion=$rs[$i][7];
		}	
		if(($rs[$i][10]<$fecha_actual)&&($calificacion==0))
		{
			$rs[$i][8]="EVALUACI&Oacute;N NO PRESENTADA ";
		}
		//
		///////
		$btn_mod_un="<button class='btn btn-primary' id='btn_mod_eval".$k."' name='btn_mod_eval".$k."'  onclick='ir_eval_est(".$rs[$i][0].",".$rs[$i][9].");' title='Ir a evaluacion'><i class='fa fa-pencil-square-o'></i></i></button>";
		///////
		$x='';
		$y='';
		/**/
		//Validando si el tipo de aula es curso, calificacion es=--
		$estatus=$rs[$i][8];
		if($rs[$i][2]=="QUIZ")
		{
			$estatus="N/A";
			$calificacion="N/A";
			$nota_quiz='<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle"></i>Nota: El tipo de evaluaci&oacute;n QUIZ no cuentan con estatus ni calificación con ponderaci&oacute;n, las siglas "N/A" hacen referencia a la expresi&oacute;n no aplica</div>';
		}	
		/**/
		$resumen_eval=substr($rs[$i][2],0,1000)."...";
		$cuerpo_contenido.="<tr>
								<td id='titulo_eva_tbl' style='text-align:left' width='15%'>".$rs[$i][4]."</td>
								<td style='text-align:left' width='15%'>".$rs[$i][1]."</td>
								<td width='10%' style='text-align: initial'>".$rs[$i][2]."</td>
								<td width='10%' style='text-align:left'>".$estatus."</td>
								<td width='10%' style='text-align:center;'>".$calificacion."</td>
								<td width='10%'>".$rs[$i][12]."</td>
								<td width='10%'>".$rs[$i][11]."</td>
								<td width='20%'>".$btn_mod_un." ".$btn_cerrar."</td>
							</tr>";
		$imagen_eva='';
		$btn_cerrar='';
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_eva->num_rows7,$nom_fun);
	$mensaje[0]=$cuerpo_contenido;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	$mensaje[2]="Evaluaciones de contenidos E.V.A: ".$titulo_eva;
	$mensaje[3]=$nota_quiz;
	die(json_encode($mensaje));
}
?>