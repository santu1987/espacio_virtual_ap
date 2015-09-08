<?php
session_start();
require("../modelos/modelo.registrar_eva.php");
$mensaje=array();
$contenidos_pdf='';
//valido el campo que viene por post
if(isset($_POST["aula"]))
{
	if($_POST["aula"]!="")
	{
		$aula=$_POST["aula"];
	}
	else
	{
		$mensaje[0]="campos_blancos";
		die(json_encode($mensaje));
	}	
}	
else
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}	
//creo el objeto...
$obj_aula=new espacio_v();
if(($_SESSION["id_perfil"]==1)||($_SESSION["id_perfil"]==4))
{
	$rs=$obj_aula->consultar_contenidos_aula_adm($aula);
}else
{
	$rs=$obj_aula->consultar_contenidos_aula($aula);
}						
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error_bd";
	die(json_encode($mensaje));
}
else
{
	$mensaje[0]=$rs[0][1];
	$menu_unidades="";
	$cuerpo_tab="";
	for($i=0;$i<=count($rs)-1;$i++)
	{
		
		$k=$i+1;
		if($i==0)
		{
			$activo="class='active'";
			$activador="active";
		}else
		{
			$activo='';
			$activador='';
		}
		//Para validar si desea realizar una prueba...
		if($rs[$i][7]>0)
		{
			$cuantas_evaluaciones=$rs[$i][7];
			$id_contenido=$rs[$i][2];
			//consulto cuales son esas evaluaciones
			$btn_prueba="";
			$rs_evaluaciones=$obj_aula->consultar_evaluaciones_p($id_contenido);
			for($d=0;$d<=count($rs_evaluaciones)-1;$d++)
			{
				////
				if(($_SESSION["id_perfil"]!=1)&&($_SESSION["id_perfil"]!=4))
				{
					if($rs_evaluaciones[$d][2]>0)
					{	
						$btn_prueba.="<div class='alert alert-danger div_inf_prueba' role='alert'><i class='fa fa-info-circle'></i> Informaci&oacute;n: Esta unidad ya cuenta con una prueba cargada de tipo ".$rs_evaluaciones[$d][1]."<br> Si desea presentarla puede acceder a ella presionando el siguiente boton <i class='fa fa-arrow-right'></i>"." "."
						<button type='button' class='btn btn-danger' onclick='cargar_prueba(".$rs[$i][2].",".$rs_evaluaciones[$d][0].")'><i class='fa fa-file-text-o'></i></button>
						</div>";	
					}	
				}	
				////	
			}
		}
		else
		{
			$btn_prueba='';
		}	
		//Para traer el material en pdf
		$rs_pdf=$obj_aula->consultar_archivos($rs[$i][2]);
		//die(json_encode($rs_pdf));
		for($j=0;$j<=count($rs_pdf)-1;$j++)
		{
			$archi=$rs_pdf[$j][0];
			$contenidos_pdf.="<i onclick='cargar_pdf(\"$archi\")' class='fa fa-file-pdf-o'></i> ".$rs_pdf[$j][0]."-";
		}
		if($contenidos_pdf!="")
		{
			$div_archivos="<div id='marco_archivo".$k."'></div>
							<div class='form-group'>
								<div class='col-lg-10'>
									<input type='hidden'>
								</div>
								<div class='col-lg-2'>
									<a onclick='subir(".$k.");' class='btn btn-primary boton_ar' id='ir_cabecera".$k."' name='ir_cabecera".$k."' style='width:50px;' title='Ir al bloque principal'  href='#titulo_eva' ><i class='fa fa-arrow-up'></i></a>
								</div>
							</div>
							<div id='archivos_pdf'>
									<legend><h1>Archivos pdf adjuntos:</h1></legend>
									".$contenidos_pdf."
							</div>";
			$bajar_ar="<a onclick='bajar2(".$k.");' class='btn btn-primary boton_ar' id='ir_archivos".$k."' name='ir_archivos".$k."' style='width:50px;' title='Ir al bloque de archivos adjuntos'  href='#marco_archivo".$k."' ><i class='fa fa-arrow-down'></i></a>";
		}else
		{
			$div_archivos='';
			$bajar_ar='';
		}	
		//menu de las unidades
		$menu_unidades.="<li id='unidad".$k."' ".$activo."><a onclick='desactivar_pdf();' href='#".$rs[$i][5]."' data-toggle='tab'>".$rs[$i][6]."</a></li>";
		//configuracion del cuerpo del tab
		//											<source src='".$material_un."' type='video/mp4'>
		$material_un="";
		$material_un="./material_video/".$rs[$i][3];
		$boton_baja="<a class='btn btn-primary boton_ar' onclick='bajar(".$k.");' id='baja_texto".$k."' style='width:50px;' title='Ir contenido documental'  href='#marco_titulo_resumen".$k."' ><i class='fa fa-arrow-down'></i></a>";
		$cuerpo_tab.="<div class='cuadro_tab tab-pane ".$activador."' id='".$rs[$i][5]."'>
					 ".$btn_prueba."
							<div id='titulo_material_multimedia'><legend><h1>Video-Clase</h3></legend></div>
							<div class='form-group'>
								<div class='col-lg-10'>
									<div id='material_m_aula' name='material_m_aula'>
										<video width='70%' style='margin-left:20%;margin-bottom:5%;' controls preload>
											<source src='".$material_un."' type='video/ogg'>
											Your browser does not support the video tag.
										</video>
									</div>
								</div>
								<div class='col-lg-2'>".$boton_baja."</div>
							</div>	
							<div id='marco_titulo_resumen".$k."'></div>
							<div>
								<a onclick='subir(".$k.");' class='btn btn-primary boton_ar' id='ir_cabecera".$k."' name='ir_cabecera".$k."' style='width:50px;' title='Ir al bloque principal'  href='#titulo_eva' ><i class='fa fa-arrow-up'></i></a>
							".$bajar_ar."
							</div>
							<div id='titulo_resumen'><legend><h1>Contenido Documental</h3></legend></div>
							<div id='resumen_contenido' class='col-lg-11'>
								<p>".$rs[$i][4]."</p>
							".$div_archivos."
							<input type='hidden' name='id_unidad' id='id_unidad' size='2' value=".$rs[$i][2].">
							<input type='hidden' name='id_aula' id='id_aula' size='2' value=".$rs[$i][0].">
							</div>
						</div>
						";
		$contenidos_pdf='';		
	}
	//tab perteneciente a foro
	$menu_unidades.="<li id='foro' ><a onclick='consultar_foro(0,5,0);desactivar_pdf();cargar_foro();' href='#foro_aula' data-toggle='tab'>Discusi&oacute;n Contenido</a></li>";
	$cuerpo_tab.="<div class='cuadro_tab tab-pane' id='foro_aula'><div id='cuerpo_reg_mensaje' name='cuerpo_reg_mensaje'></div><div id='cuerpo_mensajes_foro' name='cuerpo_mensajes_foro'></div></div>";
	$mensaje[1]=$menu_unidades;
	$mensaje[2]=$cuerpo_tab;	
	die(json_encode($mensaje));
}
?>