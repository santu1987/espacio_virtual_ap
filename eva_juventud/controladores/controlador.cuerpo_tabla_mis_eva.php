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
//validando los filtros 
if(isset($_POST["f_nombre_eva"])){ if($_POST["f_nombre_eva"]){ $f_nombre_eva=strtoupper($_POST["f_nombre_eva"]);  } else { $f_nombre_eva="";} } else { $f_nombre_eva="";}
if(isset($_POST["f_fecha_aula"])){ if($_POST["f_fecha_aula"]){ $f_fecha_aula=strtoupper($_POST["f_fecha_aula"]);  } else { $f_fecha_aula="";} } else { $f_fecha_aula="";}
if(isset($_POST["f_opcion_eval"])){ if(($_POST["f_opcion_eval"])||($_POST["f_opcion_eval"]=='0')){ $f_opcion_eval=$_POST["f_opcion_eval"];  } else { $f_opcion_eval="";} } else { $f_opcion_eval="";}
if(isset($_POST["f_selec_tipo"])){ if($_POST["f_selec_tipo"]){ $f_selec_tipo=strtoupper($_POST["f_selec_tipo"]);  } else { $f_selec_tipo="";} } else { $f_selec_tipo="";}
//
$offset=$_POST["offset"];//offset
$j=$offset;//contador para el campo n°
$limit=$_POST["limit"]; //limit
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_tabla_aulas";
$rs=array();
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_cuerpo_tabla_miseva($f_nombre_eva,$offset,$limit,$f_fecha_aula,$f_opcion_eval,$f_selec_tipo);
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
		//valido: si tiene imagen el eva lo incluyo en una variable y se  muestra...
		if($rs[$i][7]!="")
		{
			$imagen_eva='<img src="./img/img_eva/'.$rs[$i][7].'"  alt="..." class="img-circle img_eva">';
		}else
		{
			$imagen_eva='';
		}	
		//
		$rs_aula_inscrito=$obj_eva->consultar_inscripcion($rs[$i][0],$_SESSION["id_us"]);
		if($rs_aula_inscrito=="error")
		{
			$mensaje[0]="error_bd";
			die(json_encode($mensaje));
		}else
		{
			if(is_numeric($rs_aula_inscrito[0][0]))
			{
				$boton_ir_aula="<button class='btn btn-danger operaciones_be' id='btn_ir_aula_insc".$k."'  onclick='ir_aula_resumen(".$rs[$i][0].");' title='Ir al aula'><i class='fa fa-university'></i></i></button>";
				$boton_insc_aula="";
			}
			else
			{
				$boton_ir_aula="";
			}	
		}
		//
		$boton_evaluacion="<button class='btn btn-warning operaciones_be' id='btn_ver_notas".$k."' name='btn_ver_notas".$k."'  onclick='ir_resumen_notas(".$rs[$i][0].");' title='Ir resum&eacute;n calificaciones' style='margin-left:1%;'><i class='fa fa-pencil-square-o'></i></button>";
		//debido a que tiene demasiados registros
		set_time_limit(0);
		$j=$j+1;
		$tlf=$rs[$i][2];
		$resumen_aula=substr($rs[$i][5],0,1000)."...";
		$cuerpo_contenido.="<tr>
								<td width='10%'>".$imagen_eva."</td>
								<td width='15%' style='text-align:left'>".$rs[$i][1]."</td>
								<td width='5%'>".$rs[$i][2]."</td>
								<td width='10%'>".$rs[$i][10]."</td>
								<td width='30%'>".$resumen_aula."</td>
								<td width='10%'>".$rs[$i][6]."</td>
								<td  id='botonera".$k."' width='30%'>".$boton_ir_aula."<button class='btn btn-primary operaciones_be' style='margin-left:2%' title='Consultar aula' id='btn_cons_eva".$k."'onclick='consultar_resumen_eva(".$rs[$i][0].");' ><i class='fa fa-search'></i></button>".$boton_evaluacion."</td>	
					 		</tr>";
		$imagen_eva='';
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_eva->num_rows3,$nom_fun);
	$mensaje[0]=$cuerpo_contenido;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}
?>