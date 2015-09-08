<?php
session_start();
require("../modelos/modelo.registrar_evaluacion.php");
$mensaje=array();
///////////////////////////////////////////
//die(json_encode($_POST["id_unidad"]));
if((isset($_POST["id_unidad"]))&&(isset($_POST["id_evaluacion"])))
{
	$id_unidad=$_POST["id_unidad"];
	$id_evaluacion=$_POST["id_evaluacion"];
}	
else
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}
///////////////////////////////////////////
$obj_evaluacion=new evaluacion();
$rs=$obj_evaluacion->consulta_evaluacion_unidad($id_unidad,$id_evaluacion);
//die(json_encode($rs));
if($rs==null)
{
	$mensaje[0]="no_preguntas";
	die(json_encode($mensaje));
}
else
if($rs=="error")
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}
else
{
	//TITULO DE LA PAGINA
	$titulo_eva="<legend><h1 class='titulo_eva'>".$rs[0][0].": ".$rs[0][1]."</h1></legend>";
	//INICIO DE CUERPO PREGUNTAS
	$cuerpo_preguntas="<div id='cuerpo_preguntas'>";	
	//TITULO DE LA PREGUNTA
	$titulo_pregunta="<div id='titulo_pregunta' class='alert alert-info'>".$rs[0][2]."</div>";
	//PARA CONSULTAR SI LAS OPCIONES DE REPSUESTA YA ESTAN CHEQUEADAS...
	$rs2=$obj_evaluacion->consultar_evaluacion_cargada($id_unidad,$id_evaluacion);
	//fecha cierre
	$fecha_cierre=$rs[0][14];
	//fecha actual
	$fecha_actual=date("Y-m-d");
	//si la fecha actual es mayor a la fecha de la prueba debe bloquearse los radio
	if($fecha_actual>$fecha_cierre)
	{
		$a="disabled='disabled'";
		$b="si";
	}
	else
	{
		$a='';
		$b="no";
	}	
	//validando rs2
	if($rs2=="error")
	{
		$mensaje[0]="error_consulta";
		die(json_encode($mensaje));
	}	
	//

	for($i=0;$i<=count($rs)-1;$i++)
	{
		//CUERPO DE LAS PREGUNTAS
		$k=$i+1;
		$cuerpo_preguntas.="<div id='def_pregunta' name='def_pregunta' class='alert alert-success'><p><h1>Pregunta ".$rs[$i][3].": </h1>".$rs[$i][4]."</p></div>
		<div id='opciones_preguntas'".$k." class='alert alert-warning'><h2>Opciones respuesta:</h2>
			<div><input ".$a." type='radio' name='opcion".$k."[]' id='opcion1".$k."' value='".$rs[$i][13]."*1'>".$rs[$i][5]."  "."<i id='campo_respuesta".$rs[$i][13]."-1'></i><label id='label_respuesta".$rs[$i][13]."-1'></label></div>
			<div><input ".$a." type='radio' name='opcion".$k."[]' id='opcion2".$k."' value='".$rs[$i][13]."*2'>".$rs[$i][6]."  "."<i id='campo_respuesta".$rs[$i][13]."-2'></i><label id='label_respuesta".$rs[$i][13]."-2'></label></div>
			<div><input ".$a." type='radio' name='opcion".$k."[]' id='opcion3".$k."' value='".$rs[$i][13]."*3'>".$rs[$i][7]."  "."<i id='campo_respuesta".$rs[$i][13]."-3'></i><label id='label_respuesta".$rs[$i][13]."-3'></label></div>
			<div><input ".$a." type='radio' name='opcion".$k."[]' id='opcion4".$k."' value='".$rs[$i][13]."*4'>".$rs[$i][8]."  "."<i id='campo_respuesta".$rs[$i][13]."-4'></i><label id='label_respuesta".$rs[$i][13]."-4'></label></div>
		</div>";
	}
	$cuerpo_preguntas.="</div><div><input type='hidden' size='2' name='preguntas_xyz' id='preguntas_xyz' value='".$k."'>
		<input type='hidden' size='2' name='prueba_xyz' id='prueba_xyz' value='".$rs[0][10]."'>
		<input type='hidden' size='2' name='unidades_xyz' id='unidades_xyz' value='".$rs[0][12]."'>
		<input type='hidden' size='2' name='formacion_xyz' id='formacion_xyz' value='".$rs[0][11]."'>
	</div>";
	$mensaje[1]=$titulo_eva;
	$mensaje[2]=$titulo_pregunta;
	$mensaje[3]=$cuerpo_preguntas;
	$mensaje[4]=$rs2;
	$mensaje[5]=$b;
	die(json_encode($mensaje));
}
?>