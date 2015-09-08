<?php
session_start();
require_once("../controladores/conex.php");
require("../modelos/modelo.registrar_auditoria.php");
require("../modelos/modelo.paginacion_consultas.php");  
$rs=array();
$mensaje=array();
//-- Filtros
if(isset($_POST["f_seccion"])){ if($_POST["f_seccion"]!=""){ $seccion=strtoupper($_POST["f_seccion"]);}else { $seccion=""; } }else{ $seccion="";}
if(isset($_POST["f_accion"])){ if($_POST["f_accion"]!=""){ $accion=strtoupper($_POST["f_accion"]);}else { $accion=""; } }else{ $accion="";}
if(isset($_POST["f_us"])){ if($_POST["f_us"]!=""){ $us=strtoupper($_POST["f_us"]);}else { $us=""; } }else{ $us="";}
if(isset($_POST["f_ip"])){ if($_POST["f_ip"]!=""){ $ip=$_POST["f_ip"];}else { $ip=""; } }else{ $ip="";}
if(isset($_POST["f_fecha"])){ if($_POST["f_fecha"]!=""){ $fecha=$_POST["f_fecha"];}else { $fecha=""; } }else{ $fecha="";}
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_tabla_auditoria";
//-- Creando el objeto
$x="";$y="";
$obj_auditoria=new Auditoria($x,$y);
$rs=$obj_auditoria->consultar_auditoria($offset,$limit,$actual,$seccion,$accion,$us,$ip,$fecha);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}else
{
	for($i=0;$i<=count($rs)-1;$i++)
	{
		$k=$i+1;
		$cuerpo_preguntas.="<tr>
									<td width='40%'>".$rs[$i][0]."</td>
							    	<td width='30%'>".$rs[$i][1]."</td>
							    	<td width='10%'>".$rs[$i][2]."</td>
							    	<td width='10%'>".$rs[$i][3]."</td>
							    	<td width='10%'>".$rs[$i][4]."</td>	
							</tr>";
	}
	if($actual=="")$actual=0;
	$obj_paginador=new paginacion($actual,$obj_auditoria->num_rows,$nom_fun);
	$mensaje[0]=$cuerpo_preguntas;
	$mensaje[1]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}	

?>