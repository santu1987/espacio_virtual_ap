<?php
session_start();
require("../modelos/modelo.registrar_evaluacion.php");
//--Filtros
if(isset($_POST["eva"])){ if($_POST["eva"]!=""){ $eva=strtoupper($_POST["eva"]);}else{ $eva="";}  }else{ $eva="";}
if(isset($_POST["nombre"])){ if($_POST["nombre"]!=""){ $nombre=strtoupper($_POST["nombre"]);}else{ $nombre="";}  }else{ $nombre="";}
if(isset($_POST["nacionalidad"])){ if($_POST["nacionalidad"]!=""){ $nacionalidad=strtoupper($_POST["nacionalidad"]);}else{ $nacionalidad="";}  }else{ $nombre="";}
if(isset($_POST["cedula"])){ if($_POST["cedula"]!=""){ $cedula=strtoupper($_POST["cedula"]);}else{ $cedula="";}  }else{ $cedula="";}
//--Limites
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_est_apr";
///////////////////////////////////////////
//--declaro la clase
$obj_est=new evaluacion();
$rs=$obj_est->consultar_cuerpo_est_apr($offset,$limit,$eva,$nacionalidad,$cedula,$nombre);
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
									<td width='10%'><label>".$rs[$i][0]."</label></td>
							    	<td width='10%'><label>".$rs[$i][1]."</label></td>
							    	<td width='30%'><label>".$rs[$i][2]."</label></td>
							    	<td width='30%'><label>".$rs[$i][3]."</label></td>
							    	<td width='20%'><label>".$rs[$i][4]."</label></td>	
							</tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_est->num_rows3,$nom_fun);
	$mensaje[0]=$cuerpo_preguntas;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	
///////////////////////////////////////////////
?>