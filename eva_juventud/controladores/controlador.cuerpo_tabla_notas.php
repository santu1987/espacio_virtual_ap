<?php
session_start();
require("../modelos/modelo.registrar_evaluacion.php");
require("../modelos/modelo.paginacion_consultas.php");
//-- Filtros
if(isset($_POST["eva"])){ if($_POST["eva"]!=""){ $eva=$_POST["eva"];}else{ $eva="";}  }else{ $eva="";}
//-Limites
$offset=$_POST["offset"];
$limit=$_POST["limit"]; 
$actual=$_POST["actual"];
$nom_fun="consultar_cuerpo_aprobados_rep";
//--
$obj_notas=new evaluacion();
$rs=$obj_notas->consultar_notas_aula($eva);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
	die(json_encode($mensaje));
}
else
{
	//--Generando cabecera
	$cuantos_son_cabecera=$rs[0][5];
	//--
	$cabecera="<tr>
			    	<td width='10%''><label>Nacionalidad</label></td>
			    	<td width='10%''><label>Cédula</label></td>
			    	<td width='10%'><label>Nombre</label></td>
					<td width='10%'><label>Proyecto</label></td>";
	//--
	for($i=0;$i<=$cuantos_son_cabecera-1;$i++)
	{
		$x=$i+1;
		$cabecera.="<td width='5%;'>Nota#".$x."</td>";
	}
	$cabecera.="<td width='5%;'>Nota Def</td></tr>";
	//--Generando Cuerpo
	$j=9;
	$cuerpo="<tr>";
	for($i=0;$i<=count($rs)-1;$i++)
	{
		$cuerpo.="<td width='10%'>".$rs[$i][1]."</td>
				  <td width='10%'>".$rs[$i][2]."</td>
				  <td style='text-align:left;' width='10%'>".$rs[$i][3]."</td>
				  <td style='text-align:left;' width='10%'>".$rs[$i][7]."</td>";
		for($k=0;$k<$rs[0][5];$k++)
		{
			$nota_un=$rs[$i][$j];
			if($nota_un==""){ $nota_un='0';}
			$cuerpo.="<td width='5%'>".$nota_un."</td>";
			$j++;
		}
		//--
		$nota_def=$rs[$i][14];
		if($nota_def==""){ $nota_def='0';}
		//--
		$j=9;
		$nota_un='';
		$cuerpo.="<td width='5%;'>".$nota_def."</td></tr>";
	}
	//--Paginacion
	$obj_paginador=new paginacion($actual,$obj_notas->num_rows2,$nom_fun);
	//--
	$mensaje[0]=$cabecera;
	$mensaje[1]=$cuerpo;	
	$mensaje[2]=$obj_paginador-> crear_paginacion();
	die(json_encode($mensaje));
}
?>