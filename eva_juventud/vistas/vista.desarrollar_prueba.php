<?php
if(isset($_GET["id_contenido"]))
{
	$id_contenido=$_GET["id_contenido"];
}
if(isset($_GET["id_evaluacion"]))
{
	$id_evaluacion=$_GET["id_evaluacion"];
}
if(isset($_GET["opcion"]))
{
	$opcion=$_GET["opcion"];
}
else
{
	$opcion="";
}	
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
cargar_prueba();
$("#btn_volver_unidad").click(function(){
	var opcion="<?php echo $opcion; ?>";
	var id_contenido="<?php echo $id_contenido;?>";
	var id_evaluacion="<?php echo $id_evaluacion;?>";
	var data={id_contenido:id_contenido,id_evaluacion:id_evaluacion};
	if(opcion==1)
	{
		//////////////////////////////////////////////////////////////
		$.ajax({
				url:"./controladores/controlador.enviar_unidades.php",
				type:"POST",
				cache:false,
				data:data,
				error: function()
				{
					  console.log(arguments);
			          mensajes(3);
				},			
				success: function(html)
				{
					//alert(html);
					var recordset=$.parseJSON(html);
					$("#program_body").load("./vistas/vista.espacio_virtual.php?id_aula="+recordset[0][0]);
				}								
		});
		//////////////////////////////////////////////////////////////
	}else
	{
		$("#program_body").load("./vistas/vista.consultar_misevaluaciones.php");
	}	
});
$("#btn_cerrar_evaluacion").click(function(){
	bootbox.confirm("¿Realmente desea finalizar su evaluaci&oacute;n, recuerde que luego de haberla procesado no podr&aacute; modificar las respuestas ingresadas ?", function(result) 
	{
	    if (result)
	    {
			if(validar_check_respuestas()==true)
			{
				var data=$("#form_evaluacion").serialize();
				$.ajax({
							url:"./controladores/controlador_registrar_prueba.php",
							type:"POST",
							data:data,
							cache:false,
							error: function()
							{
								  console.log(arguments);
						          mensajes(3);
							},			
							success: function(html)
							{
								//alert(html);
								var recordset=$.parseJSON(html);
								if(recordset[0]=="error_bd")
								{
									mensajes(10);//error en bd
								}
								else if(recordset[0]=="campos_blancos")
								{
									mensajes(1);//campos obligatorios
								}
								else 
								{
									mensajes(18);//operacion realizada con exito...
									$("#btn_cerrar_evaluacion").remove();		
									$("#btn_volver_unidad").css("margin-left","60%");
									cargar_respuestas_correctas();
								}
							}
				});
			}	
		}//fin del if result	
	});//fin de bootbox;
});
//BLOQUE DE FUNCIONES
function cargar_respuestas_correctas()
{
	var id_contenido="<?php echo $id_contenido;?>";
	var id_evaluacion="<?php echo $id_evaluacion;?>";	
	var data={id_contenido:id_contenido,id_evaluacion:id_evaluacion};
	$.ajax({
	
			url:"./controladores/controlador.cargar_respuestas_correctas.php",
			data:data,
			cache:false,
			type:"POST",
			error: function()
			{
				console.log(arguments);
		        mensajes(3);
			},			
			success: function(html)
			{
				var recordset=$.parseJSON(html);
				//alert(recordset);
				if(recordset[0]=="error")
				{
					mensajes(28);//error en cargar de notas...
				}
				else
				{
					var cuantos=recordset.length;
					var nota=0;
					var correcta=0;
					var incorrecta=0;
					for(i=0;i<=cuantos-1;i++)
					{
						if(recordset[i][1]==recordset[i][2])
						{
							$("#campo_respuesta"+recordset[i][0]+"-"+recordset[i][1]).addClass("fa fa-check-circle color_respuesta_correcta");
							$("#label_respuesta"+recordset[i][0]+"-"+recordset[i][1]).addClass("color_respuesta_correcta");
							$("#label_respuesta"+recordset[i][0]+"-"+recordset[i][1]).html("RESPUESTA CORRECTA!");
							correcta=correcta+1;
						}
						else
						{
							$("#campo_respuesta"+recordset[i][0]+"-"+recordset[i][1]).addClass("fa fa-times color_respuesta_incorrecta");
							$("#label_respuesta"+recordset[i][0]+"-"+recordset[i][1]).html("RESPUESTA INCORRECTA!");
							$("#label_respuesta"+recordset[i][0]+"-"+recordset[i][1]).addClass("color_respuesta_incorrecta");
							$("#campo_respuesta"+recordset[i][0]+"-"+recordset[i][2]).addClass("fa fa-check-circle color_respuesta_correcta");
							$("#label_respuesta"+recordset[i][0]+"-"+recordset[i][2]).html("RESPUESTA CORRECTA!");
							$("#label_respuesta"+recordset[i][0]+"-"+recordset[i][2]).addClass("color_respuesta_correcta");
							incorrecta=incorrecta+1;
						}
					}	
					////////////////////////////////
					nota=(correcta*100)/cuantos;
					nota=Math.round(nota);
					var div_nota="<div class='alert alert-info' role='alert'><i class='fa fa-info-circle'></i> Informaci&oacute;n:La nota resultante en esta evaluaci&oacute;n es de : "+nota+"pts</div>";						
					$("#div_mensaje_nota").append(div_nota);
					////////////////////////////////
				}	
			}
	});			
}
function cargar_respuestas_correctas2()
{
	var id_contenido="<?php echo $id_contenido;?>";
	var id_evaluacion="<?php echo $id_evaluacion;?>";	
	var data={id_contenido:id_contenido,id_evaluacion:id_evaluacion};
	$.ajax({
	
			url:"./controladores/controlador.cargar_respuestas_correctas2.php",
			data:data,
			cache:false,
			type:"POST",
			error: function()
			{
				console.log(arguments);
		        mensajes(3);
			},			
			success: function(html)
			{
				var recordset=$.parseJSON(html);
				//alert(recordset);
				if(recordset[0]=="error")
				{
					mensajes(28);//error en cargar de notas...
				}
				else
				{
					var cuantos=recordset.length;
					var nota=0;
					var correcta=0;
					var incorrecta=0;
					for(i=0;i<=cuantos-1;i++)
					{
							$("#campo_respuesta"+recordset[i][0]+"-"+recordset[i][1]).addClass("fa fa-check-circle color_respuesta_correcta");
							$("#label_respuesta"+recordset[i][0]+"-"+recordset[i][1]).addClass("color_respuesta_correcta");
							$("#label_respuesta"+recordset[i][0]+"-"+recordset[i][1]).html("RESPUESTA CORRECTA!");
					}	
					////////////////////////////////
					nota=(correcta*100)/cuantos;
					nota=Math.round(nota);
					var div_nota="<div class='alert alert-info' role='alert'><i class='fa fa-info-circle'></i> Informaci&oacute;n:La nota resultante en esta evaluaci&oacute;n es de : "+nota+"pts</div>";						
					$("#div_mensaje_nota").append(div_nota);
					$("#btn_volver_unidad").css("margin-left","60%");
					////////////////////////////////
				}	
			}
	});			
}
function validar_check_respuestas()
{
	var cuantos_pr=$("#preguntas_xyz").val();
	var k=0;
	for(i=1;i<=cuantos_pr;i++)
	{
			var b = 0;
			var chk=document.getElementsByName('opcion'+i+'[]');
			//alert(chk.length);
				for(j=0;j<chk.length;j++) {
					//alert(chk.item(j).checked);
					if(chk.item(j).checked == false) {
						b++;
					}
			}
				if(b == chk.length) {
					chk='';
					mensajes2("Debe seleccionar al menos una opci&oacute;n de respuesta");
					return false;
				} 
				chk='';
				
	}
	return true;
}
function cargar_prueba()
{
	var id_contenido="<?php echo $id_contenido;?>";
	var id_evaluacion="<?php echo $id_evaluacion;?>";
	//var id_contenido=27;
	$("#id_unidad").val(id_contenido);
	$("#id_evaluacion").val(id_evaluacion);		
	var data={id_unidad:id_contenido,id_evaluacion:id_evaluacion};
	$.ajax({
				url:"./controladores/controlador.evaluacion_unidad.php",
				type:"POST",
				data:data,
				cache:false,
				error: function()
				{
					  console.log(arguments);
			          mensajes(3);
				},
				success: function(html)
				{
					//alert(html);
					var a="";
					var recordset=$.parseJSON(html);
					//alert(recordset[5]);
					if(recordset[0]=="no_preguntas")
					{
						mensajes2("Disculpe pero no existen preguntas cargadas a esta prueba");
						$("#program_body").load("./vistas/vista.consultar_aulas.php");
					}else	
					if(recordset[0]=="error_bd")
					{
						mensajes(10);//error en bd
					}
					else if(recordset[4]=="error_consulta")
					{
						mensajes(19);
					}	
					else if(recordset[0]=="campos_blancos")
					{
						mensajes(1);//campos obligatorios
					}
					else
					{
						$("#titulo_eva").html(recordset[1]);
						$("#titulo_preguntas").html(recordset[2]);
						$("#contenido_prueba").html(recordset[3]);
						///
						rs=recordset[4];
						//alert(rs);
						//////////
						if(rs!="")
						{
							/////////////////////////////////////
							$("#formacion_xyz").val(rs[0][0]);
							$("#unidades_xyz").val(rs[1][1]);
							$("#prueba_xyz").val(rs[1][2]);
							var cuantas_pr=$("#preguntas_xyz").val();
							//////recorre preguntas
							for(i=1;i<=(cuantas_pr);i++)
							{
								k=i-1;
								//recorre respuestas
								for(j=1;j<=4;j++)
								{
									//valido que el check este
									//alert(rs[k][3]+"*"+rs[k][4]);
									if($("#opcion"+j+i).val()==rs[k][3]+"*"+rs[k][4])
									{
										$("#opcion"+j+i).prop("checked","checked");		
									}	
								}	
							}
							////////////////////////////////////
							//elimino el boton para procesar evaluacion
							$("#btn_cerrar_evaluacion").remove();
							$("#btn_volver_unidad").css("margin-left","60%");
							cargar_respuestas_correctas();
						}else
						//
						if(recordset[5]=="si")
						{
							cargar_respuestas_correctas2(); 
							$("#btn_cerrar_evaluacion").remove();
						}
						//
						//////////
					}//fin del else...	
				}
	});
}
</script>
<div id="cuerpo_aula" name="cuerpo_aula"  class="cuerpo_aula">
		<div id="titulo_eva" name="titulo_eva">
			<h1></h1>
		</div>
		<form id="form_evaluacion" name="form_evaluacion" type="post">
			<div id="desc_preguntas"><legend><h3>Descripci&oacute;n de evaluaci&oacute;n:</h3></legend></div>
			<div id="titulo_preguntas"></div>
			<div id="bloque_pruebas"><legend><h3>Bloque de preguntas:</h3></legend></div>
			<div id="contenido_prueba">
			</div>
			<div id="div_mensaje_nota" name="div_mensaje_nota"></div>
			<legend></legend>
			<div class="botonera_eva">
				<button id="btn_volver_unidad" name="btn_volver_unidad" type="button" class="btn btn-danger">Volver</button>	
				<button id="btn_cerrar_evaluacion" name="btn_cerrar_evaluacion" type="button" class="btn btn-primary">Procesar evaluaci&oacute;n</button>
			</div>
		</form>	
</div>