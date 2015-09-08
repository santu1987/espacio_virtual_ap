<?
session_start();
require("../modelos/modelo.usuario.php");
require("../modelos/modelo.paginacion_consultas.php");  
$rs=array();
$mensaje=array();
//-- Validando los POST
if(isset($_POST["estado"])){ if($_POST["estado"]!=""){ $estado=$_POST["estado"];}else{ $estado="";}  }else{ $estado="";}
if(isset($_POST["municipio"])){ if($_POST["municipio"]!=""){ $municipio=$_POST["municipio"];}else{ $municipio="";}  }else{ $municipio="";}
if(isset($_POST["parroquia"])){ if($_POST["parroquia"]!=""){ $parroquia=$_POST["parroquia"];}else{ $parroquia="";}  }else{ $parroquia="";}
//--filtros 2
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_beneficiados";
///////////////////////////////////////////
//--declaro la clase
$obj_est=new Usuario();
$rs=$obj_est->consultar_cuerpo_beneficiados($offset,$limit,$estado,$municipio,$parroquia);
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
		$cuerpo_beneficiados.="<tr>
									<td width='5%'>".$rs[$i][0]."</td>
									<td width='5%'>".$rs[$i][1]."</td>
									<td width='10%'>".$rs[$i][2]."</td>
									<td width='10%'>".$rs[$i][3]."</td>
							   </tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_est->num_rows3,$nom_fun);
	$mensaje[0]=$cuerpo_beneficiados;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	
///////////////////////////////////////////////
?>