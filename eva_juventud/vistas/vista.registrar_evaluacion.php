<?php
session_start();
include("../controladores/controlador.aula_virtual.php");
if(isset($_GET["id_eval"]))
{
	if($_GET["id_eval"]!="")
	{
		$id_eval=$_GET["id_eval"];
	}else
	{
		$id_eval='';	
	}	
}
else
{
	$id_eval='';	
}
////
if(isset($_GET["id_unidad"]))
{
	if($_GET["id_unidad"]!="")
	{
		$id_unidad=$_GET["id_unidad"];
	}else
	{
		$id_unidad='';	
	}	
}
else
{
	$id_unidad='';	
}
?>
<script type="text/javascript">
//BLOQUE DE FUNCIONES
function verificar_cuantas_preguntas(cuantos,estatus)
{
	if(cuantos==estatus[0][2])
	{
		var id_evaluacion=$("#id_evaluacion").val();
		var btn_cerrar='';
		//si alguien respondio la pregunta no se puede abrir
		if(estatus[0][1]==0)
		{
			if(estatus[0][0]==0)
			{
				btn_cerrar="<button type='button' class='btn btn-danger' id='btn_cerrar_eval' name='btn_cerrar_eval' onclick='cerrar_eva("+id_evaluacion+");' style='width:100%;'>Cerrar evaluaci&oacute;n</button> ";
			}else
			{
				btn_cerrar="<button type='button' class='btn btn-success' id='btn_cerrar_eval' name='btn_cerrar_eval' onclick='cerrar_eva("+id_evaluacion+");' style='width:100%;'>Abrir evaluaci&oacute;n</button> ";
			}	
			$("#caja_estatus").val(estatus[0][0]);
			$("#div_cerrar_sesion").html(btn_cerrar);	
		}
		//
	}
}
function cerrar_eva(id_evaluacion)
{
	var data={id_evaluacion:id_evaluacion};
	$.ajax ({
	          url:"./controladores/controlador.cerrar_eva.php",
			  data:data,
	          type:'POST',
	          cache: false,
	          error: function(request,error) 
	          {
	              console.log(arguments);
	              mensajes(3);//error desconocido
	          },
	          success: function(html)
	          {
				var recordset=$.parseJSON(html);	          	
	          	//alert(recordset);
	          	if(recordset[0]=="error_bd")
                {
                	mensajes(7);//error en base de datos
                }
                else if(recordset[0]=="campos_blancos")
                {
                	mensajes(5);//debe ingresar los campos
                }
                  else if(recordset[0]=="campos_blancos2")
                {
                	mensajes(8);//error inesperado
                }
                else if(recordset[0]=="examen_resuelto_alumno")
                {
                	mensajes(27);//no puede aperturar la prueba...
                }	
                else if(recordset[0]=='1')
                {
                	mensajes(25);//evaluación activa
       				btn_cerrar="<button type='button' class='btn btn-success' id='btn_cerrar_eval' name='btn_cerrar_eval' onclick='cerrar_eva("+id_evaluacion+");' style='width:100%;'>Abrir evaluaci&oacute;n</button> ";
                	$("#div_cerrar_sesion").html(btn_cerrar);
                	$("#caja_estatus").val("1");
                }
                else if (recordset[0]=='0') 
                {
                	mensajes(26);//evaluacion inactiva...
                	btn_cerrar="<button type='button' class='btn btn-danger' id='btn_cerrar_eval' name='btn_cerrar_eval' onclick='cerrar_eva("+id_evaluacion+");' style='width:100%;'>Cerrar evaluaci&oacute;n</button> ";
                	$("#div_cerrar_sesion").html(btn_cerrar);
                	$("#caja_estatus").val("0");
                }
	          }
	});
}
function cargar_consulta_eval()
{
	var id_eval="<?php echo $id_eval;?>";
	var unidad="<?php echo $id_unidad;?>";
	if((id_eval!="")&&(unidad!=""))
	{
		evaluacion_select2(id_eval,unidad);	
	}	
}
function limpiar_consulta_bl()
{
	$("#desc_evaluacion,#fecha_activacion_evaluacion,#fecha_cierre_evaluacion,#id_evaluacion").val("");
	$("#marco_preguntas").html("");
	 document.getElementById("ir_marco").style.display="none";
	$("#evaluacion_cuantas_preguntas").val("0"); 
	$("#caja_estatus").val("0");
}
function hab_reg(valor,id_pregunta)
{
	if($("#caja_estatus").val()==0)
	{
		//para validar si la pregunta anterior
		var n_pregunta=valor-1;
		var campo_id=$("#id_pre"+n_pregunta).val();
		var cabecera="<b><h3>Registro de pregunta #"+valor+"</h3></b>";
		$("#myModalLabelregistrar").html(cabecera);
		//genero los campos de la tabla
		var cabacera_tabla="";
		//////////////////////////////////////
		//en caso de que no deba registrar una pregunta
		if(campo_id=="")
		{
			document.getElementById("registrar_modal").style.display="none";
			var formulario='<div class="form-group">\
								<center><h4><i class="fa fa-exclamation-circle fa-2x" style="color:#bc2328"></i> Informaci&oacute;n: No puede registrar una pregunta sin haber completado la anterior</h4></center>\
							</div>';
			$("#cuerpo_registrar").html(formulario);
			$("#myModalLabelregistrar").html("Informaci&oacute;n");				
			//$('#myModal_registrar').modal('hide');//apago el modal
		}
		//en caso de que deba registrar.
		else
		{
			//armo el formualrio de carga de preguntas
			$("#cuerpo_registrar").html("");	
			var formulario_preguntas='<div>\
			<div class="form-group">\
				<div class="col-sm-12">\
					<textarea id="pregunta_evaluacion" name="pregunta_evaluacion" rows="5" class="form-control input-sg" placeholder="Enunciado de la pregunta.. Ejm: Cual es el nombre de la distribuci&oacute;n GNU/LINUX desarrollada en la rep&uacute;blica Bolivariana de Venezuela"></textarea>\
				</div>\
			</div>\
			<div class="form-group">\
				<div class="col-sm-12">\
					<input type="text" name="pregunta_respuesta1" id="pregunta_respuesta1" placeholder="Opci&oacute;n respuesta 1 a la pregunta formulada" class="form-control input-sg" >\
				</div>\
			</div>\
			<div class="form-group">\
				<div class="col-sm-12">\
					<input type="text" name="pregunta_respuesta2" id="pregunta_respuesta2" placeholder="Opci&oacute;n respuesta 2 a la pregunta formulada" class="form-control input-sg" onKeyPress="return valida(event,this,18,100)" onBlur="valida2(this,18,100);">\
				</div>\
			</div>\
			<div class="form-group ">\
				<div class="col-sm-12">\
					<input type="text" name="pregunta_respuesta3" id="pregunta_respuesta3" placeholder="Opci&oacute;n respuesta 3 a la pregunta formulada" class="form-control input-sg" onKeyPress="return valida(event,this,18,100)" onBlur="valida2(this,18,100);">\
				</div>\
			</div>\
			<div class="form-group">\
				<div class="col-sm-12">\
					<input type="text" name="pregunta_respuesta4" id="pregunta_respuesta4" placeholder="Opci&oacute;n respuesta 4 a la pregunta formulada" class="form-control input-sg" onKeyPress="return valida(event,this,18,100)" onBlur="valida2(this,18,100);">\
				</div>\
			</div>\
			<div class="form-group ">\
				<label class="col-sm-4 control-label conf_label">Respuesta correcta:</label>\
				<div class="col-sm-10 texto2">\
					<label>\
			          <input type="radio" name="r_op" id="r_op1" checked  value="1"> <label>Opci&oacute;n1</label>\
			        </label>\
			        <label>\
			        	<input type="radio" name="r_op" id="r_op2" value="2"><label>Opci&oacute;n2</label>\
			        </label>\
			        <label>\
			        	<input type="radio" name="r_op" id="r_op3" value="3"><label>Opci&oacute;n3</label>\
			        </label>\
			        <label>\
			        	<input type="radio" name="r_op" id="r_op4" value="4"><label>Opci&oacute;n4</label>\
			        </label>\
			    </div>\
			</div>\
			<input type="hidden" name="id_pregunta" id="id_pregunta" size=2></div>';
			$("#cabecera_registrar").html(cabacera_tabla);
			$("#cuerpo_registrar").html(formulario_preguntas);
			$("#pr_n").val(valor);
			consultar_contenido_pregunta(id_pregunta);
		}
		///////////////////////////////////////////////	
	}else
	{
		document.getElementById("registrar_modal").style.display="none";
		var formulario='<div class="form-group">\
							<center><h4><i class="fa fa-exclamation-circle fa-2x" style="color:#bc2328"></i> Informaci&oacute;n: Disculpe, no puede modificar preguntas si el aula se encuentra cerrada</h4></center>\
						</div>';
		$("#cuerpo_registrar").html(formulario);	
		$("#myModalLabelregistrar").html("Informaci&oacute;n");			
	}	
}
//funcion que permite consultar el contenido de la evaluacion
function consultar_contenido_pregunta(id_pregunta)
{
	var data={
				id_pregunta:id_pregunta
			  }
	$.ajax({
			url:"./controladores/controlador.consultar_preguntas.php",
			data:data,
			type:"POST",
			cache: false,
	          error: function(request,error) 
	          {
	              console.log(arguments);
	              mensajes(3);//error desconocido
	          },
	          success: function(html)
	          {
	          	
	          	var recordset=$.parseJSON(html);
	          	//alert(recordset);
	          	$("#pregunta_evaluacion").val(recordset[0][4]);
	          	$("#pregunta_respuesta1").val(recordset[0][5]);
	          	$("#pregunta_respuesta2").val(recordset[0][6]);
	          	$("#pregunta_respuesta3").val(recordset[0][7]);
	          	$("#pregunta_respuesta4").val(recordset[0][8]);
          		$("#r_op"+recordset[0][9]).prop("checked","checked");
	          	$("#id_pregunta").val(recordset[0][0]);
              }	
	});
}
//funcion que cargar en formulario las preguntas referentes a la evaluacion
function cargar_preguntas_ev()
{
	var data=$("#form_reg_evaluacion").serialize();
	var id_evaluacion=$("#id_evaluacion").val();
	$.ajax({
			 url:"./controladores/controlador_cargar_preguntas_form.php",
			 type:"POST",
			 data:data,
			 cache:false,
			 error: function(request,error) 
		     {
	              console.log(arguments);
	              mensajes(3);//error desconocido
	         },
	         success: function(html)
	         {
	          	var vector=$.parseJSON(html);
	          	var recordset=vector[0];
	          	var estatus=vector[1];
	          	 if(recordset[0]=="error_bd")
		         {
		         	mensajes2("Error en base de datos");
		         }else
		         if(recordset[0]=="campos_blancos")
		         {
		         	mensajes2("Debe crear una pregunta primero");
		         }
		         else
		         {
		     		var cuantas_preguntas=$("#evaluacion_cuantas_preguntas").val();
					var grupos_preguntas="";
					$("#marco_preguntas").html('<br><div class="form-group"><div class="col-lg-10"><legend class="title_form"><h3>Bloque de preguntas:</h3></legend></div><div class="col-lg-2" style="margin-top:5%;"> <a class="btn btn-primary btn-form" id="ir_pregunta" name="ir_pregunta" style="width:50px;" title="Ir al bloque principal"  href="#form_reg_evaluacion" ><i class="fa fa-arrow-up"></i></a></div></div>');
					var k=1;
					grupos_preguntas="<div class='alert alert-warning tam_alert_div'><i class='fa fa-exclamation-circle'></i>Informaci&oacute;n: Debe cargar las preguntas pulsando el bot&oacute;n + en las filas siguientes...</div><br>";
					for(i=0;i<=(recordset.length)-1;i++)
					{
						k=i+1;
						grupos_preguntas=grupos_preguntas+'<div id="grupos_preguntas"><div id="campo_pregunta"'+k+'" name="campo_pregunta"'+k+'" class="alert alert-success tam_alert_div" role="alert"><button type="button" id="btn_pr_'+k+'" name="btn_pr_'+k+'" data-toggle="modal" data-target="#myModal_registrar" class="btn  btn-success btn-agr-pr" title="Cargar pregunta" onclick="hab_reg('+k+','+recordset[i][1]+');"><span class="glyphicon glyphicon-plus"></span></button>  Pregunta-'+k+': '+recordset[i][0]+'  <i class="fa fa-check-circle-o fa-2x"></i><input type="hidden" id="id_pre'+k+'" name="id_pre'+k+'" size="2" value="'+recordset[i][1]+'"></div>';
					}
					//segundo ciclo con lo restante
					if(recordset.length<cuantas_preguntas)
					{
						for(j=i+1;j<=(cuantas_preguntas);j++)
						{
							grupos_preguntas=grupos_preguntas+'<div id="grupos_preguntas"><div id="campo_pregunta"'+j+'" name="campo_pregunta"'+j+'" class="alert alert-danger tam_alert_div" role="alert"><button type="button" id="btn_pr_'+j+'" name="btn_pr_'+j+'" data-toggle="modal" data-target="#myModal_registrar" class="btn  btn-danger btn-agr-pr" title="Cargar pregunta" onclick="hab_reg('+j+',0);"><span class="glyphicon glyphicon-plus"></span></button>  Pregunta-'+j+': No cargada <i class="fa fa-times-circle-o fa-2x"></i><input type="hidden" id="id_pre'+j+'" name="id_pre'+j+'" size="2"></div>';
						}	
					}
					if(cuantas_preguntas>0)
					{
						grupos_preguntas=grupos_preguntas+"<div class='form-group'>\
										<div id='div_cerrar_sesion' class='col-lg-12'>\
										</div>\
									  </div>";	
					}					
					$("#marco_preguntas").append(grupos_preguntas);
					$("#marco_preguntas").append("</div>");
					document.getElementById("ir_marco").style.display="inline-block";
					//
					$("#ir_pregunta").off('click');
				    $("#ir_pregunta").on('click', function() {
				     subir(); 
				    });
				    ///////////////////////////////////////////////////////////
				    verificar_cuantas_preguntas(cuantas_preguntas,estatus);
				    ////////////////////////////////////////////////////////////

		        }
	        }	
		});
}
function cargar_unidades_evaluacion(valor,idm)
{
	if(valor!="")
	{
		$("#unidades_evaluacion").load('controladores/controlador.unidades_contenido.php?id='+valor+"&idm="+idm);
	}
	//alert('controladores/controlador.unidades_contenido.php?id='+valor);
}//FIN DE LA FUNCION
function cargar_tipo_evaluacion(idm)
{
	$("#tipo_evaluacion").load('controladores/controlador.tipo_evaluacion_c.php?idm='+idm);
}//FIN DE LA FUNCION
function consultar_evaluaciones_cam()
{
	if(($("#aula_virtual_evaluacion").val()!='0')&&($("#unidades_evaluacion").val()!='0')
		&&($("#tipo_evaluacion").val()!='0'))
	{
	///////////////////////////////////////////////////////////////////	
	var data=$("#form_reg_evaluacion").serialize();
	//////////////////////////////////////////////////////////////
	$.ajax ({
	          url:"./controladores/controlador.consultar_evaluacion.php",
	          data:data,
	          type:'POST',
	          cache: false,
	          error: function(request,error) 
	          {
	              console.log(arguments);
	              mensajes(3);//error desconocido
	          },
	          success: function(html)
	          {
	                var recordset=$.parseJSON(html);
	                //alert(recordset);
	                 if(recordset[0]=="error")
	                {
	                	mensajes(7);//error en base de datos
	                }
	                else
	                if(recordset=="")
	                {
	                	limpiar_consulta_bl();
	                }	
	                else 
	                {
	                	///////////////////////////////////
	                	$("#desc_evaluacion").val(recordset[0][3]);
	                	$("#evaluacion_cuantas_preguntas").val(recordset[0][4]);
	                	$("#id_evaluacion").val(recordset[0][0]);
	                	$("#fecha_activacion_evaluacion").val(recordset[0][6]);
	                	$("#fecha_cierre_evaluacion").val(recordset[0][8]);
	                	$("#caja_estatus").val("");
	                	///////////////////////////////////
		             	cargar_preguntas_ev();
	                }
	         }
	});

	}	
}
function validar_evaluacion()
{
	var f = new Date();
	var fecha_actual;
	fecha_actual=f.getDate() + "-" + (f.getMonth() +1) + "-" + f.getFullYear();
	//alert(fecha_actual+"-"+$("#fecha_activacion_eva").val());
	//--Para fecha de activacion
	////---Menor a la fecha actual
	if(compararFecha($("#fecha_activacion_evaluacion").val(),fecha_actual)==false)
	{
	   mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> La fecha de activaci&oacute;n no puede ser menor a la actual");
	   return false;    
	}
	//--Para fecha de cierre
	////---Menor a la fecha actual
	if(compararFecha($("#fecha_cierre_evaluacion").val(),fecha_actual)==false)
	{
	   mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> La fecha de cierre no puede ser menor a la actual");
	   return false;    
	}
	//--Fecha desde no puede ser mayor a a fecha hasta
	if(compararFecha($("#fecha_cierre_evaluacion").val(),$("#fecha_activacion_evaluacion").val())==false)
	{
	   mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> La fecha de cierre no puede ser menor a la fecha de activaci&oacute;n");
	   return false;    
	}
	if(($("#fecha_cierre_evaluacion").val())==($("#fecha_activacion_evaluacion").val()))
	{
		mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> La fecha de activaci&oacute;n no puede ser igual a la fecha de cierre");
	    return false;	
	}	
	//----------------------------------------------------------------------------------------------------------------------------------
	if($("#aula_virtual_evaluacion").val()==0)
	{
		mensajes2("Debe seleccionar un aula virtual");
		return false;
	}else 
	if($("#unidades_evaluacion").val()==0)
	{
		mensajes2("Debe seleccionar una unidad de evaluaci&oacute;n");
		return false;
	}
	else
	if($("#evaluacion_cuantas_preguntas").val()==0)
	{
		mensajes2("Debe seleccionar cuantas preguntas tendr&aacute; la evaluaci&oacute;n");
		return false;
	}else
	if($("#desc_evaluacion").val()=="")
	{
		mensajes2("Debe seleccionar cuantas preguntas tendr&acute;a la evaluaci&oacute;n");
		return false;
	}	
	else
	{
		return true;
	}
}
function consultar_cuerpo_tabla_evaluaciones(offset,limit,actual)
{
	var f_aula=$("#f_aula").val();
	var f_unidades=$("#f_unidades").val();
	var f_tipo_ev=$("#f_tipo_ev").val();
	data={
			f_aula:f_aula,
			f_unidades:f_unidades,
			f_tipo_ev:f_tipo_ev,
			offset:offset,
			limit:limit,
			actual:actual,
		 }
//////////////////////////////////////////////////////////////
	$.ajax ({
	          url:"./controladores/controlador.consultar_cuerpo_evaluacion.php",
	          data:data,
	          type:'POST',
	          cache: false,
	          error: function(request,error) 
	          {
	              console.log(arguments);
	              mensajes(3);//error desconocido
	          },
	          success: function(html)
	          {
                var recordset=$.parseJSON(html);
                //en caso de que no traiga nada la consulta...
                if(recordset[0]=="error")//si da error
                {
                	mensajes(12);
                }else
                	$("#cuerpo_consulta").html(recordset[0]);//cuerpo de la tabla
                	$("#paginacion_tabla").html(recordset[1]);//paginacion

               }
	});
}
////////////////////////////////////////////////////////////////////////
function evaluacion_select(id_evaluacion,unidad)
{
	var data={
				id_evaluacion:id_evaluacion,
				unidad:unidad
			 }
	$.ajax({
			url:"./controladores/controlador.consultar_evalua.php",
			data:data,
			type:"POST",
			cache: false,
	          error: function(request,error) 
	          {
	              console.log(arguments);
	              mensajes(3);//error desconocido
	          },
	          success: function(html)
	          {
	          	
	          	var recordset=$.parseJSON(html);
	          	//alert(recordset);
	          	$('#myModal_consulta').modal('hide');//apago el modal
	          	$("#aula_virtual_evaluacion").val(recordset[0][1]);
	          	$("#id_evaluacion").val(recordset[0][0]);
	          	cargar_unidades_evaluacion(recordset[0][1],recordset[0][10]);
	          	$("#tipo_evaluacion").val(recordset[0][3]);
	          	$("#desc_evaluacion").val(recordset[0][4]);
	          	$("#evaluacion_cuantas_preguntas").val(recordset[0][5]);
             	$("#fecha_activacion_evaluacion").val(recordset[0][6]);
				$("#fecha_cierre_evaluacion").val(recordset[0][8]);
             	 cargar_preguntas_ev();
	          	///
              }	
	});		  //
}
function evaluacion_select2(id_evaluacion,unidad)
{
	var data={
				id_evaluacion:id_evaluacion,
				unidad:unidad
			 }
	$.ajax({
			url:"./controladores/controlador.consultar_evalua.php",
			data:data,
			type:"POST",
			cache: false,
	          error: function(request,error) 
	          {
	              console.log(arguments);
	              mensajes(3);//error desconocido
	          },
	          success: function(html)
	          {
	          	
	          	var recordset=$.parseJSON(html);
	          	//alert(recordset);
	          	$('#myModal_consulta').modal('hide');//apago el modal
	          	$("#aula_virtual_evaluacion").val(recordset[0][1]);
	          	$("#id_evaluacion").val(recordset[0][0]);
	          	cargar_unidades_evaluacion(recordset[0][1],unidad);
	          	cargar_tipo_evaluacion(recordset[0][3]);
	          	$("#tipo_evaluacion").val(recordset[0][3]);
	          	$("#desc_evaluacion").val(recordset[0][4]);
	          	$("#evaluacion_cuantas_preguntas").val(recordset[0][5]);
             	$("#fecha_activacion_evaluacion").val(recordset[0][6]);
				$("#fecha_cierre_evaluacion").val(recordset[0][8]);
             	 cargar_preguntas_ev();
	          	///
              }	
	});		  //
}
function bajar()
{
	$('html, body').stop().animate({scrollTop: $($("#ir_marco").attr('href')).offset().top}, 1000);
}
function subir()
{
	$('html, body').stop().animate({scrollTop: $($("#ir_pregunta").attr('href')).offset().top}, 1000);
}
function validar_preguntas()
{
	if($("#pregunta_evaluacion").val()=="")
	{
		mensajes2("Debe ingresar el texto de la pregunta");
		return false;
	}else
	if($("#pregunta_respuesta1").val()=="")
	{
		mensajes2("Debe ingresar la respuesta 1");
		return false;
	}
	else
	if($("#pregunta_respuesta1").val()=="")
	{
		mensajes2("Debe ingresar la respuesta 1");
		return false;
	}
	else
	if($("#pregunta_respuesta2").val()=="")
	{
		mensajes2("Debe ingresar la respuesta 2");
		return false;
	}
	else
	if($("#pregunta_respuesta3").val()=="")
	{
		mensajes2("Debe ingresar la respuesta 3");
		return false;
	}
	else
	if($("#pregunta_respuesta4").val()=="")
	{
		mensajes2("Debe ingresar la respuesta 4");
		return false;
	}else
	{
		return true;
	}	
}
function filtrar_enter()
{
	if(event.which==13){
        consultar_cuerpo_tabla_evaluaciones(0,5,0);
   	}  
}
///////////////////////////////////////////////////////////////////////////
//BLOQUE DE EVENTOS
cargar_consulta_eval();
$("#fecha_activacion_evaluacion,#fecha_cierre_evaluacion").datetimepicker({ 
    lang:'es',
    minDate:0,
    timepicker:false,
    format:'d-m-Y',
    formatDate:'Y-m-d',
});
$("#salir_modal").click(function(){
	document.getElementById("registrar_modal").style.display="block";
});
$("#btn_ac").click(function(){
	//alert("xyz");
	//$('#ir_marco').click();
});
$("#pie_pag").removeClass("contendor_pie_pagina").addClass("contendor_pie_pagina2");
var idm='';
cargar_tipo_evaluacion(idm);
$("#btn_guardar_evaluacion").click(function(){
	if($("#caja_estatus").val()==0)
	{
	/////////////////////////////////
		if(validar_evaluacion()==true)
		{
		///////////////////////////////////////////////////////////////////	
			var data=$("#form_reg_evaluacion").serialize();
		//////////////////////////////////////////////////////////////
				$.ajax ({
				          url:"./controladores/controlador.registrar_evaluacion.php",
				          data:data,
				          type:'POST',
				          cache: false,
				          error: function(request,error) 
				          {
				              console.log(arguments);
				              mensajes(3);//error desconocido
				          },
				          success: function(html)
				          {
				                var recordset=$.parseJSON(html);
				                //alert(recordset);
				                 if(recordset[0]=="error")
				                {
				                	mensajes(7);//error en base de datos
				                }
				                else if(recordset[0]=="campos_blancos")
				                {
				                	mensajes(5);//debe ingresar los campos
				                }
				                  else if(recordset[0]=="campos_blancos2")
				                {
				                	mensajes(8);//error inesperado
				                }
				                //----------------------------------------------------------------------------------------------------------------------------------------
				                //--fecha de activacion
				                else if(recordset[0]=="activacion_menor_aula")//factivacion<f_aula
				                {
										mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, la fecha de activaci&oacute;n de la evaluaci&oacute;n no puede ser menor a la fecha de activaci&oacute;n del EVA");
				                }
				                else if(recordset[0]=="activacion_menor_unidad")//factivacion<f_unidad
				                {
										mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, la fecha de activaci&oacute;n de la evaluaci&oacute;n no puede ser menor a la fecha de activaci&oacute;n de la unidad");
				                }	
				                //--------------------------------------------------------------------------------------------------------------------------------------------------
				                //--fecha de cierre
				                else if(recordset[0]=="cierre_menor_aula")//fcierre<f_aula
				                {
										mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, la fecha de cierre de la de la evaluaci&oacute;n no puede ser menor a la fecha de activaci&oacute;n del EVA");
				                }
				                else if(recordset[0]=="cierre_menor_unidad")//fcierre<f_unidad
				                {
										mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, la fecha de cierre de la evaluaci&oacute;n no puede ser menor a la fecha de activaci&oacute;n de la unidad");
				                }	
				                //--------------------------------------------------------------------------------------------------------------------------------------------------
				                
				                else if(recordset[0]=="registro_exitoso")
				                {
				                	mensajes(13);
				                	$("#id_evaluacion").val(recordset[1]);
				                	cargar_preguntas_ev();
				                	$('#ir_marco').click();
				                }
				                else
				                {
				                	mensajes2(recordset);
				                }	
				          }
				});
		//////////////////////////////////////////////////
		}
	/////////////////////////////////////////////////////
	}
	else
	{
		mensajes(29);//en caso de que  este la evaluacion cerrada no podrá guardar/actualizar datos
	}	
});
///////////////////////////////////////////
$("#btn_cevaluacion").click(function(){
	var cabecera="<b>Consulta emergente: Evaluaciones</b>";
	$("#myModalLabelconsulta").html(cabecera);
	//genero los campos de la tabla
	var cabacera_tabla="<tr>\
							<td><input type='text' name='f_aula' id='f_aula' placeholder='Filtro seg&uacute;n aula' class='form-control input-sg' onblur='valida2(this,18,100);consultar_cuerpo_tabla_evaluaciones(0,5,0);' onKeyPress='filtrar_enter();return valida(event,this,18,100);'></td>\
							<td><input type='text' name='f_unidades' id='f_unidades' placeholder='Contenidos' class='form-control input-sg' onblur='valida2(this,10,100);consultar_cuerpo_tabla_evaluaciones(0,5,0);' onKeyPress='filtrar_enter();return valida(event,this,10,100);'></td>\
							<td><select id='f_tipo_ev' name='f_tipo_ev'  class='form-control input-sg' onKeyPress='filtrar_enter();' >\
								</select>\
							<td>\
						</tr>\
						<tr>\
							<td width='35%'><label>EVA</label></td>\
							<td width='15%'><label>Contenidos</label></td>\
							<td width='25%'><label>Tipo de evaluaciones</label></td>\
							<td width='15%'><label>Estatus</label></td>\
							<td width='25%'><label>Seleccione</label></td>\
						</tr>";
	$("#cabecera_consulta").html(cabacera_tabla);
	$("#f_tipo_ev").load('controladores/controlador.tipo_evaluacion_c.php');
	//consultar cuerpo de la tabla
	consultar_cuerpo_tabla_evaluaciones(0,5,0);
});
$("#btn_limpiar_evaluacion").click(function(){
$(':input','#form_reg_evaluacion')
 .not(':button, :submit, :reset')
 .val('')
 .removeAttr('checked')
 .removeAttr('selected');
 $("#marco_preguntas").html("");
 document.getElementById("ir_marco").style.display="none";
});
$("#registrar_modal").click(function(){
	////////////////////////////////////////////////////
	if(validar_preguntas()==true)
	{	
		var data=$("#form_reg_evaluacion").serialize();
		/////////////////////////////////////////////////////////////
				$.ajax ({
				          url:"./controladores/controlador.guardar_preguntas.php",
				          data:data,
				          type:'POST',
				          cache: false,
				          error: function(request,error) 
				          {
				              console.log(arguments);
				              mensajes(3);//error desconocido
				          },
				          success: function(html)
				          {
				                var recordset=$.parseJSON(html);
				                //alert(recordset[2]+"="+$("#evaluacion_cuantas_preguntas").val());
				                if(recordset=="error_bd")
				                {
				                	mensajes(7);//error en base de datos
				                }
				                else if(recordset[0]=="campos_blancos")
				                {
				                	mensajes(5);//debe ingresar los campos
				                }
				                  else if(recordset[0]=="campos_blancos2")
				                {
				                	mensajes(8);//error inesperado
				                }
				                else if(recordset[0]=="registro_exitoso")
				                {
				                	$("#id_pregunta").val(recordset[1]);
				                	mensajes(6);
				                	//actualizo el sector de bloque de preguntas
				                	cargar_preguntas_ev();
				                	//apago el modal
	               		          	$('#myModal_registrar').modal('hide');//apago el modal
				              	}	
				          }
				});
		////////////////////////////////////////////////////
	}
});
$('#ir_marco').click(function(){
	bajar();		
});
//////////////////////////////////////////
</script>
<!-- -->
<div id="form_2">
	<form  class="form-horizontal" type="POST" name="form_reg_evaluacion" id="form_reg_evaluacion" role="form">	
		<fieldset>
		    <legend>
		        <h3>Configurar Evaluaciones</h3>
		    </legend>
    	</fieldset>
		<div class="form-group">
			<div class="col-sm-10">
				<select name="aula_virtual_evaluacion" id="aula_virtual_evaluacion" class="form-control" onchange="cargar_unidades_evaluacion(this.value,0);consultar_evaluaciones_cam();">
					<?php echo $opcion_aula_virtual;?>
				</select>
			</div>
			<div class="col-lg-2">
				<button  id="btn_cevaluacion" name="btn_cevaluacion" title="Consultar evaluacion" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_consulta"><span class="glyphicon glyphicon-search"></span>
				</button>
			</div>		
		</div>
		<div class="form-group ">
			<div class="col-sm-12">
				<select name="unidades_evaluacion" id="unidades_evaluacion" class="form-control" onchange="consultar_evaluaciones_cam();">
					<option value="0">[Contenidos E.V.A]</option>
				</select>
			</div>	
		</div>
		<div class="form-group ">
			<div class="col-sm-12">
				<select name="tipo_evaluacion" id="tipo_evaluacion" class="form-control" onchange="consultar_evaluaciones_cam();">
					<option value="0">[Tipo de evaluaci&oacute;n]</option>
				</select>
			</div>	
		</div>	
		<div class="form-group">
			<div class="col-sm-12">
				<textarea id="desc_evaluacion" name="desc_evaluacion" class="form-control" placeholder="Ingrese la descripción de la evaluací&oacute;n,  Ejm:Determinar conocimientos generales en software libre" onKeyPress="return valida(event,this,18,300)" onBlur="valida2(this,18,300);"></textarea>
			</div>
		</div>
		<div class="form-group">
            <div class="col-sm-6">
                <input type="text" name="fecha_activacion_evaluacion" id="fecha_activacion_evaluacion" class="form-control input-sg"  onkeyup="this.value=formateafecha(this.value);" placeholder="Fecha activaci&oacute;n :dd/mm/aaaa">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <input type="text" name="fecha_cierre_evaluacion" id="fecha_cierre_evaluacion" onblur="verificar_fechas();" class="form-control input-sg"  onkeyup="this.value=formateafecha(this.value);" placeholder="Fecha de cierre: dd/mm/aaaa">
            </div>
        </div>
		<div class="form-group">
			<div class="col-sm-10">
				<select name="evaluacion_cuantas_preguntas" id="evaluacion_cuantas_preguntas" class="form-control">
					<option value='0'>[Cantidad de preguntas]</option>
					<option value='4'>4 Preguntas</option>
					<option value='6'>6 Preguntas</option>
					<option value='10'>10 Preguntas</option>
				</select>
			</div>
			<div class="col-sm-2">
		      	<a class="btn btn-primary" id="ir_marco" style="width:50px;display:none;" title="Ir al bloque de preguntas"  href="#marco_preguntas" ><i class="fa fa-arrow-down"></i></a>
			</div>	
		</div>
	<div class="col-lg-6">
		<button type="button" id="btn_guardar_evaluacion" name="btn_guardar_evaluacion" class="btn btn-info btn-form">Guardar</button>
	</div>
	<div class="col-lg-6">
	    <button type="button" id="btn_limpiar_evaluacion" name="btn_limpiar_evaluacion" class="btn btn-warning btn-form">Limpiar</button>
    </div>
	<div id="marco_preguntas" name="marco_preguntas">
	</div>
	<input type="hidden" size="2" id="id_evaluacion" name="id_evaluacion">
	<input type="hidden" size="2" id="caja_estatus" name="caja_estatus">		
	<!-- Modal: Registro emergentes -->
		<div class="modal fade bs-example-modal-lg modal_emergente" id="myModal_registrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabelregistrar" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		        <h3 class="modal-title" id="myModalLabelregistrar"></h3>
		      </div>
		      <div class="modal-body">
		        <table class="table table-hover"  width="70%">
		          <thead id="cabecera_registrar" name="cabecera_registrar">  
		        <!-- -->
		        </thead>
		        <tbody id="cuerpo_registrar" name="cuerpo_registrar">    
		        <!-- -->
		        </tbody> 
		        </table>
		        <input type="hidden" size="2" name="pr_n" id="pr_n">
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal" id="salir_modal" name="salir_modal">Salir</button>
		        <button type="button" class="btn btn-primary" style="display:block;float:right; margin-right:2px"  name="registrar_modal" id="registrar_modal">Registrar</button>
		      </div>
		    </div>
		  </div>
		</div>
		<!-- FIN Modal -->  
	</form>
</div>	
<!-- -->