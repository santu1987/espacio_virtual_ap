<?php
session_start();
require("../modelos/modelo.usuario.php");
require("../modelos/modelo.paginacion_consultas.php");  
$rs=array();
$mensaje=array();
//-- Validando los POST
if(isset($_POST["nacionalidad"])){ if($_POST["nacionalidad"]!=""){ $nacionalidad=$_POST["nacionalidad"];}else{ $nacionalidad="";}  }else{ $nacionalidad="";}
if(isset($_POST["cedula"])){ if($_POST["cedula"]!=""){ $cedula=$_POST["cedula"];}else{ $cedula="";}  }else{ $cedula="";}
if(isset($_POST["nombre"])){ if($_POST["nombre"]!=""){ $nombre=$_POST["nombre"];}else{ $nombre="";}  }else{ $nombre="";}
if(isset($_POST["estado"])){ if($_POST["estado"]!=""){ $estado=$_POST["estado"];}else{ $estado="";}  }else{ $estado="";}
if(isset($_POST["municipio"])){ if($_POST["municipio"]!=""){ $municipio=$_POST["municipio"];}else{ $municipio="";}  }else{ $municipio="";}
if(isset($_POST["parroquia"])){ if($_POST["parroquia"]!=""){ $parroquia=$_POST["parroquia"];}else{ $parroquia="";}  }else{ $parroquia="";}
if(isset($_POST["sexo"])){ if($_POST["sexo"]!=""){ $sexo=$_POST["sexo"];}else{ $sexo="";}  }else{ $sexo="";}
//--filtros 2
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_estudiantes_reg";
///////////////////////////////////////////
//--declaro la clase
$obj_est=new Usuario();
$rs=$obj_est->consultar_cuerpo_rep_estudiante($offset,$limit,$nacionalidad,$cedula,$nombre,$estado,$municipio,$parroquia,$sexo);
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
									<td width='5%'>".$rs[$i][2]."</td>
									<td width='5%'>".$rs[$i][3]."</td>
									<td style='text-align:left' width='15%'>".$rs[$i][4]."</td>
									<td width='5%'>".$rs[$i][10]."</td>
									<td width='10%'>".$rs[$i][5]."</td>
									<td width='10%'>".$rs[$i][6]."</td>
									<td class='izquierda' width='10%'>".$rs[$i][7]."</td>
									<td class='izquierda' width='10%'>".$rs[$i][8]."</td>
									<td class='izquierda' width='10%'>".$rs[$i][9]."</td>
							 </tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_est->num_rows2,$nom_fun);
	$mensaje[0]=$cuerpo_preguntas;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	
///////////////////////////////////////////////
?>