<?php
session_start();
ini_set("memory_limit","1024M");
require("../modelos/modelo.registrar_eva.php");
require("../modelos/modelo.paginacion_consultas.php");
//valido los post
//validar_post_paginacion();
$mensaje=array();
$cuerpo_contenido='';
//validando los filtros 
if(isset($_POST["f_nombre_eva"])){ if($_POST["f_nombre_eva"]){ $f_nombre_eva=strtoupper($_POST["f_nombre_eva"]);  } else { $f_nombre_eva="";} } else { $f_nombre_eva="";}
if(isset($_POST["f_nombre_contenido"])){ if($_POST["f_nombre_contenido"]){ $f_nombre_contenido=strtoupper($_POST["f_nombre_contenido"]);  } else { $f_nombre_contenido="";} } else { $f_nombre_contenido="";}
if(isset($_POST["f_n_contenido"])){ if(($_POST["f_n_contenido"])){ $f_n_contenido=$_POST["f_n_contenido"];  } else { $f_n_contenido="";} } else { $f_n_contenido="";}
if(isset($_POST["id_aula"])){ if(($_POST["id_aula"])){ $id_aula=$_POST["id_aula"];  } else { $id_aula="";} } else { $id_aula="";}
//
$offset=$_POST["offset"];//offset
$j=$offset;//contador para el campo n°
$limit=$_POST["limit"]; //limit
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_tabla_resumen_notas";
$rs=array();
$obj_eva=new espacio_v();
$rs=$obj_eva->consultar_cuerpo_tabla_resumen_notas($offset,$limit,$f_nombre_eva,$f_nombre_contenido,$f_n_contenido,$id_aula);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_decode($mensaje));
}else
{
	for($i=0;$i<=count($rs)-1;$i++)
	{
		//--Validando las notas
		if($rs[$i][5]=="")
		{
			$calificaciones="0/100";
		}else
		{
			$calificaciones=$rs[$i][5]."/100";
		}	
		//-- botones de graficos y certificados por eva y unidad
		$boton_certificado="<button class='btn btn-primary operaciones_be' id='boton_certificado".$k."'  onclick='ir_certificado(".$rs[$i][0].",".$rs[$i][1].");' title='Ir al certificado'><i class='fa fa-university'></i></i></button>";
		$boton_graficos="<button class='btn btn-danger operaciones_be' id='boton_graficos".$k."'  onclick='ir_graficos(".$rs[$i][0].",".$rs[$i][1].");' title='Ir graficos resumen'><i class='fa fa-university'></i></i></button>";
		set_time_limit(0);
		$cuerpo_contenido.="<tr>
								<td width='30%'>".$rs[$i][2]."</td>
								<td width='30%'>".$rs[$i][3]."</td>
								<td width='20%'>".$rs[$i][4]."</td>
								<td width='20%'>".$calificaciones."</td>
					 		</tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_eva->num_rows8,$nom_fun);
	$mensaje[0]=$cuerpo_contenido;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}
?>