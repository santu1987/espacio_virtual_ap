<?php
session_start();
require("../modelos/modelo.usuario.php");
require("../modelos/modelo.paginacion_consultas.php");  
$rs=array();
$mensaje=array();
$cuerpo_us="";
//-- Validando los POST
if(isset($_POST["nacionalidad"])){ if($_POST["nacionalidad"]!=""){ $nacionalidad=$_POST["nacionalidad"];}else{ $nacionalidad="";}  }else{ $nacionalidad="";}
if(isset($_POST["cedula"])){ if($_POST["cedula"]!=""){ $cedula=$_POST["cedula"];}else{ $cedula="";}  }else{ $cedula="";}
if(isset($_POST["nombre"])){ if($_POST["nombre"]!=""){ $nombre=strtoupper($_POST["nombre"]);}else{ $nombre="";}  }else{ $nombre="";}
if(isset($_POST["correo"])){ if($_POST["correo"]!=""){ $correo=$_POST["correo"];}else{ $correo="";}  }else{ $correo="";}
if(isset($_POST["tipo_us"])){ if($_POST["tipo_us"]!=""){ $tipo_us=$_POST["tipo_us"];}else{ $tipo_us="";}  }else{ $tipo_us="";}
//--filtros 2
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_us";
///////////////////////////////////////////
//--declaro la clase
$obj_us=new Usuario();
$rs=$obj_us->consultar_cuerpo_us($offset,$limit,$nacionalidad,$cedula,$nombre,$correo,$tipo_us);
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
		//--
		//--Creando los botones para adminsitrar usuarios...
		//validando activacion o inactivacion de boton
		//--------------------------------------------------------
		if($rs[$i][8]=="A")
		{
			$clase_btn_inac="btn-success";
			$titulo_aula="Inactivar Usuario";
		}else
		if($rs[$i][8]=="I")
		{
			$clase_btn_inac="btn-danger";
			$titulo_aula="Activar Usuario";
		}
		//----------------------------------------------------------
		$boton_ir_us="<button style='display:none' class='btn btn-warning operaciones_be' id='btn_ir_us".$k."'  onclick='ir_us(".$rs[$i][1].");' title='Ver datos usuario'><i class='fa fa-search'></i></i></button>";
		$boton_perfil="<button class='btn btn-primary operaciones_be' id='btn_perfil_us".$k."'  onclick='ir_perfil(\"".$rs[$i][2]."\",".$rs[$i][3].");' title='Asignar Perfil usuario'><i class='fa fa-user'></i></i></button>";
		$boton_activar="<button class='btn ".$clase_btn_inac."' id='btn_activar".$k."'  onclick='ir_activar_us(".$rs[$i][1].",".$k.");' title='".$titulo_aula."'><i class='fa fa-power-off'></i></i></button>";
		//--
		$cuerpo_us.="<tr>
									<td width='5%'>".$rs[$i][2]."</td>
									<td width='5%'>".$rs[$i][3]."</td>
									<td style='text-align:left' width='20%'>".$rs[$i][4]."</td>
									<td style='text-align:left' width='10%'>".$rs[$i][5]."</td>
									<td width='10%'>".$rs[$i][6]."</td>
									<td style='text-align:left' width='15%'>".$rs[$i][7]."</td>
									<td width='20%'>".$boton_ir_us." ".$boton_perfil." ".$boton_activar."</td>
							 </tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_us->num_rows4,$nom_fun);
	$mensaje[0]=$cuerpo_us;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	
///////////////////////////////////////////////
?>