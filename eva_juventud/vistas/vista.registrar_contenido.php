<?php
session_start();
include("../controladores/controlador.aula_virtual.php");
$id_un='';
if(isset($_GET["id_un"]))
{
	if($_GET["id_un"]!="")
	{
		$id_un=$_GET["id_un"];
	}
	else
	{
		$id_un='';
	}	
}
else
{
	$id_un='';
}
?>
<html>
<head>
<script type="text/javascript">
////BLOQUE DE EVENTOS	
cargar_consulta_contenidos();
$("#btn_limpiar_cont").click(function(){
	limpiar_cont();
	valor=0;
	$("#unidades_aula_virtual").load('controladores/controlador.unidades.php?id='+valor);
});
$("#fecha_activacion_unidad").datetimepicker({ 
    lang:'es',
    minDate:0,
    timepicker:false,
    format:'d-m-Y',
    formatDate:'Y-m-d',
});
$("#hora_activacion_unidad").datetimepicker({
  datepicker:false,
  format:'H:i'
});
$("#pie_pag").removeClass("contendor_pie_pagina").addClass("contendor_pie_pagina2");
$("#btn_guardar_cont").click(function(){
if(validar_campos_contenidos()==true)
{		
		var data=$("#form_registrar_contenido").serialize();
		$.ajax ({
                  url:"./controladores/controlador.registrar_unidad.php",
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
                        var carga_video='';
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
                        }else if(recordset[0]=="fecha_menor")
                        {
                        	mensajes(42);//la fecha no debe ser menor que la fecha actual...
                        }
                        else if(recordset[0]=="registro_exitoso")
                        {
                        	$("#cont_ev").val(recordset[1]);
                        	////
                        	//alert($("#op_pdf").is(":checked"));
                        	if($("#op_pdf").is(":checked"))
                        	{
                        		cargar_archivos_pdf("registro_exitoso");
                        	}
                        	else
                        	if($("#material_video").val()!="")
                        	{
                        		carga_archivos_video();	
                        	}
                        	else
                        	{
                        		////
                        		mensaje_ir_evaluaciones();//funcion que verifica si el usuario desea ir a la carga de evaluaciones	
                        	}	
                        	
	                    }  
                	}
				});
	}
});
	$("#btn_conte").click(function(){
	//////////////////////////////////
	var cabecera="<b>Consulta emergente: Contenido E.V.A</b>";
	$("#myModalLabelconsulta").html(cabecera);
	//genero los campos de la tabla
	var cabacera_tabla="<tr><td><input type='text' name='f_aula' id='f_aula' placeholder='Filtro seg&uacute;n aula' class='form-control input-sg' onblur='valida2(this,18,100);consultar_cuerpo_tabla_cont(0,5,0);' onKeyPress='filtrar_enter();return valida(event,this,18,100);'></td>\
						<td><input type='text' name='f_unidades' id='f_unidades' placeholder='Filtro seg&uacute;n unidades' class='form-control input-sg' onblur='valida2(this,18,100);consultar_cuerpo_tabla_cont(0,5,0);' onKeyPress='filtrar_enter();return valida(event,this,18,100);'></td></tr>\
						<tr>\
							<td width='45%'><label>Espacio Virtual de Aprendizaje</label></td>\
							<td width='25%'><label>Unidades</label></td>\
							<td width='25%'><label>Seleccione</label></td>\
						</tr>";
	$("#cabecera_consulta").html(cabacera_tabla);	
	//consultar cuerpo de la tabla
	consultar_cuerpo_tabla_cont(0,5,0);		
	//////////////////////////////////	
	});
	$("#op_pdf").click(function(){
		if($("#op_pdf").is(":checked"))
		{
			limpiar_campos_pdf();
			//$("#cuantos_pdf").val("1");
			document.getElementById("cargar_pdf").style.display="block";
			$("#asdfads").html("");
			$("#informacion_material").html("");
		}
		else
		{
			document.getElementById("cargar_pdf").style.display="none";
		}	
	});
	$("#btn_agregarpdf").click(function(){

		var cuantos_pdf=parseInt($("#cuantos_pdf").val());
		if(cuantos_pdf<3)
		{	
			cuantos_pdf=cuantos_pdf+1;
			var cargado_lineas='<div class="form-group" id="div_pdf_unidad'+cuantos_pdf+'">\
									<label class="col-sm-2 control-label conf_label">Cargar PDF '+cuantos_pdf+'</label>\
						   			<div class="col-sm-10 texto7">\
						   			<input type="file" class="btn btn-primary" name="pdf_unidad'+cuantos_pdf+'" id="pdf_unidad'+cuantos_pdf+'"/>\
									</div>\
								</div>\
								';	
			$("#cuantos_pdf").val(cuantos_pdf);
			$("#segmento_pdf").append(cargado_lineas);
		}
		else
		{
			mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, no puede incluir m&aacute;s de 3 archivos pdf");
		}	
	});
	$("#btn_quitarpdf").click(function(){
		var cuantos_pdf=parseInt($("#cuantos_pdf").val());
		if(cuantos_pdf>=2)
		{
			$("#div_pdf_unidad"+cuantos_pdf).remove();
			cuantos_pdf=cuantos_pdf-1;
			$("#cuantos_pdf").val(cuantos_pdf);
		}
	});
	///BLOQUE DE FUNCIONES
	function mensaje_ir_evaluaciones()
	{
		bootbox.confirm("<i class='fa fa-check fa-2x' style='color:#16E91D'></i> Operación realizada de manera exitosa, ¿Desea registrar la evaluaci&oacute;n a esta unidad de contenido?", function(result) 
		{ if(result)
		    {
		      var id_contenido=$("#cont_ev").val();
		      var id_aula=$("#aula_virtual_facilitador").val();
		      $("#program_body").load("./vistas/vista.registrar_evaluacion.php");
		      setTimeout(function(){$("#aula_virtual_evaluacion").val(id_aula);},1000);
		      setTimeout(function(){cargar_unidades_evaluacion(id_aula,id_contenido);},1000);
			}
		    else
		    {
		      limpiar_cont();//limpio el formulario...
		      $("#unidades_aula_virtual").val('-1'); 
		    } 
		    subir_titulo();
		});
	}	
	function limpiar_campos_pdf()
	{
		var cuantos_pdf=parseInt($("#cuantos_pdf").val());
		while(cuantos_pdf>1)
		{
			$("#div_pdf_unidad"+cuantos_pdf).remove();
			cuantos_pdf=cuantos_pdf-1;
			$("#cuantos_pdf").val(cuantos_pdf);
			//alert(cuantos_pdf);
		}
	}
	function filtrar_enter()
	{
	   if(event.which==13){
	        consultar_cuerpo_tabla_cont(0,5,0);
	   }     
	}
	function cargar_consulta_contenidos()
	{
		var id_un="<?php echo $id_un;?>";
		if(id_un)
		{
			contenido_selec(id_un);
		}
	}
	function validar_campos_contenidos()
	{
		var f = new Date();
  		var fecha_actual;
		fecha_actual=f.getDate() + "-" + (f.getMonth() +1) + "-" + f.getFullYear();
		//alert(fecha_actual+"-"+$("#fecha_activacion_eva").val());
		if(compararFecha($("#fecha_activacion_unidad").val(),fecha_actual)==false)
		{
		   mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> La fecha no puede ser menor a la actual");
		   return false;    
		}  
		if($("#aula_virtual_facilitador").val()=="-1")
		{
			mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar un espacio virtual ya creado");
			return false;
		}else
		if($("#unidades_aula_virtual").val()=="-1")
		{
			mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar una unidad de contenido");
			return false;
		}else
		if($("#titulo_aula").val()=="")
		{
			mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar un t&iacute;tulo de  unidad de contenido");
			return false;
		}
		else
		if($("#contenido_material").val()=="")
		{
			mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe señalar el contenido documental(textual) de la unidad");
			return false;
		}else
		if($("#fecha_activacion_unidad").val()=="")
		{
			mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe señalar la fecha de activaci&oacute;n del contenido");
		}/*else
		if($("#hora_activacion_unidad").val()=="")
		{
			mensajes2("Debe señalar la hora de activaci&oacute;n del contenido");
			return false;
		}*/
		else
		{
			return true;
		}	

	}
	function cargar_archivos_pdf(valor)
	{
	  var formData = new FormData($("#form_registrar_contenido")[0]);
      var message = "";   
      //hacemos la petición ajax  
      $.ajax({
                url: './controladores/controlador.cargar_pdf_unidades.php',  
                type: 'POST',
                // Form data
                //datos del formulario
                data: formData,
                //necesario para subir archivos via ajax
                cache: false,
                contentType: false,
                processData: false,
                //mientras enviamos el archivo
                beforeSend: function(){
                    //message = $("<span class='before'>Subiendo la imagen, por favor espere...</span>");
                   // showMessage(message)         
                },
                //si ha ocurrido un error
                error: function()
                {
                    console.log(arguments);
                    mensajes(3);
                },
                //una vez finalizado correctamente
                success: function(data)
                {
                    var recordset=$.parseJSON(data);
                    //alert(recordset);//alert(recordset[0]);//alert(valor);
                    if((valor=="registro_exitoso")&&(recordset[0]=="archivo_cargado"))
                    {
                      	//si registro satisfactoriamente consulto si desea cargar video
                      		if($("#material_video").val()!="")
                        	{
                        		carga_archivos_video();	
                        	}
                        	else//sino le pregunta si desea ir a la otra pantalla de carga de evaluaciones
                        	{
                        		setTimeout(function(){mensaje_ir_evaluaciones();},1000);
                        	}	
                        ////////////////////////////////////////////////////	
                    }else
                    if(recordset[0]=="error_tipo_archivo")
                    {
                        mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i>Error- Carga pdf: Solo puede subir archivos en formato .pdf");
                    	return false;
                    }
                    else
                    if(recordset[0]=="error_tamano")
                    {
                    	mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i>Error- Carga pdf: Solo puede subir archivos en formato .pdf, de tamaño menor a 2 megas");
                    	return false;	
                    }	
                    else
                    if(recordset[0]=="error_no_carga")
                    {
                        mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Error-Proceso de carga pdf");
                        return false;
                    }  
                }
      		});
	}
	function carga_archivos_video()
	{
		  var formData = new FormData($("#form_registrar_contenido")[0]);
	      var message = "";   
	      //hacemos la petición ajax  
	      $.ajax({
	                url: './controladores/controlador.cargar_videos.php',  
	                type: 'POST',
	                // Form data
	                //datos del formulario
	                data: formData,
	                //necesario para subir archivos via ajax
	                cache: false,
	                contentType: false,
	                processData: false,
	                //mientras enviamos el archivo
	                beforeSend: function(){
	                    //message = $("<span class='before'>Subiendo la imagen, por favor espere...</span>");
	                   // showMessage(message)         
	                },
	                //si ha ocurrido un error
	                error: function()
	                {
	                    console.log(arguments);
	                    mensajes(3);
	                },
	                //una vez finalizado correctamente
	                success: function(data)
	                {
	                    var recordset=$.parseJSON(data);
	                   // alert(recordset);
	                    if(recordset[0]=="campos_blancos")
	                    {
                        	mensajes(5);//debe ingresar los campos
	                    }	
	                    if(recordset[0]=="error_tipo_archivo")
	                    {
	                        mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Error- Carga video: Solo puede subir archivos en formato .mp4");
	                    }else
	                    if(recordset[0]=="error_tamano")
	                    {
	                    	mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Error- Carga video: Solo puede subir archivos en formato .mp4, de tamaño menor a 100Mg");
	                    }else
	                    if(recordset[0]=="error_no_carga")
	                    {
	                        mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Error-Proceso de carga de video");
	                    }else	
	                    {
	                    	mostrar_video(recordset[1]);
                    	    setTimeout(function(){mensaje_ir_evaluaciones();},2000);
	                    }
	                }
	      		});
	}
	function consultar_cuerpo_tabla_cont(offset,limit,actual)
	{
		var f_aula=$("#f_aula").val();
		var f_unidades=$("#f_unidades").val();
		data={
				f_aula:f_aula,
				f_unidades:f_unidades,
				offset:offset,
				limit:limit,
				actual:actual,
			 }
		//////////////////////////////////////////////////////////////
		$.ajax ({
		          url:"./controladores/controlador.consultar_cuerpo_contenido.php",
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
	                //alert(recordset);
	                if(recordset[0]=="error")//si da error
	                {
	                	mensajes(12);
	                }else
	                	$("#cuerpo_consulta").html(recordset[0]);//cuerpo de la tabla
	                	$("#paginacion_tabla").html(recordset[1]);//paginacion
	               }
		});
	}
	function contenido_selec(id_contenido)
	{

		var data={
        			id_contenido:id_contenido
        		  }
		$.ajax({
				  url:"./controladores/controlador.consultar_contenido.php",
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
				          vectores=$.parseJSON(html);
   			              //alert(vectores);
   			              if(vectores[0]=="error")
   			              {
   			              		mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Ha ocurrido un error en consulta");
   			              }
   			              else
   			              {	  limpiar_cont();//limpio el formulario...
					          var recordset=vectores[0];
					          var contenidos_pdf=vectores[1];
					          var video_cont=recordset[0][3];
					          $('#myModal_consulta').modal('hide');//apago el modal
					          $("#cont_ev").val(recordset[0][0]);
					          $("#aula_virtual_facilitador").val(recordset[0][1]);
					          cargar_unidades(recordset[0][1],recordset[0][2]);
					          $("#contenido_material").val(recordset[0][4]);
					          $("#titulo_aula").val(recordset[0][5]);
					          $("#fecha_activacion_unidad").val(recordset[0][6]);
					          $("#hora_activacion_unidad").val(recordset[0][7]);
					          if(vectores[1]!=null)
					          {
					          	var v_informacion_material='<div class="alert alert-success" role="alert"><i class="fa fa-asterisk"></i>Material cargado: '+contenidos_pdf+'</div>';
					          	$("#informacion_material").html(v_informacion_material);	
					          }
					          //esto se sustituyte por la visualizacn del video
					          mostrar_video(video_cont)
					      }
					      //    					          
				        } 
			});     
	}
	function cargar_unidades(valor,idm)
	{
		$("#contenido_material,#material_video").val("");
		if(valor!="")
		{
			$("#unidades_aula_virtual").load('controladores/controlador.unidades.php?id='+valor+"&idm="+idm);
		}
		//alert('controladores/controlador.unidades.php?id='+valor+"&idm="+idm);
	}	
	function cargar_contenido_ev()
	{
		var aula=$("#aula_virtual_facilitador").val();
		var unidad=$("#unidades_aula_virtual").val();
		var data={
					aula:aula,
					unidad:unidad	
					}
		$.ajax({
					url:'./controladores/controlador.cargar_contenido_unidad.php',
					data:data,
					type:'POST',
					cache:'false',
					error: function(request,error) 
			        {
			            console.log(arguments);
			            mensajes(3);//error desconocido
			        },
			        success: function(html)
			        {
			        	//alert(html);
			        	vectores=$.parseJSON(html);
			        	if(vectores[0]=="error_bd")
			        	{
			        		mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Error en base de datos");
			        	}
			        	else if(vectores[0]!="")
			        	{
			        		recordset=vectores[0];
			        		var video='';
			        		video=recordset[0][1];
			        		mostrar_video(video);
			        		$("#contenido_material").val(recordset[0][2]);
			        		$("#cont_ev").val(recordset[0][0]);
			        		//////////////////////////////////////////////
					        $("#titulo_aula").val(recordset[0][5]);
					        $("#fecha_activacion_unidad").val(recordset[0][6]);
					        $("#hora_activacion_unidad").val(recordset[0][7]);
					        if(vectores[1]!=null)
					        {
					          	var contenidos_pdf=vectores[1];
					           	var v_informacion_material='<div class="alert alert-success" role="alert"><i class="fa fa-asterisk"></i>Material cargado: '+contenidos_pdf+'</div>';
					           	$("#informacion_material").html(v_informacion_material);	
					        }
					        //esto se sustituyte por la visualizacn del video
					        //cargar_enlace();
			        		/////////////////////////////////////////////
			        	}
			        	else if(vectores[0]=="")
			        	{
			        		limpiar_cont();
			        	}	
			        }
		});			
	}
	function limpiar_cont() 
	{ 
		$("#contenido_material,#material_video,#cont_ev,#cont_ev,#titulo_aula,#fecha_activacion_unidad,#hora_activacion_unidad").val("");
		$("#op_pdf").prop("checked","");
		$("#preview_material_multimedia").html("");
		document.getElementById("preview_material_multimedia").style.display="none";
		$("#informacion_material").html("");
		document.getElementById("cargar_pdf").style.display="none";
		$("#cuantos_pdf").val("1");
	}
	function cargar_div_video(material_contenido)
	{
		document.getElementById("preview_material_multimedia").style.display="block";
		var previa='<object height="100" width="200">\
					<param name="movie" value="'+material+'">\
					<param name="wmode" value="transparent">\
					<embed src="'+material_contenido+'" type="application/x-shockwave-flash" wmode="transparent" height="100" width="200">\
					</object>';
		$("#preview_material_multimedia").html(previa);
	}
	function cargar_enlace() 
	{
		var enlace_url=$("#material_multimedia1").val();
		var material2=enlace_url.split("=");
		var material="https://www.youtube.com/v/"+material2[1];
		var enlace=ytVidId(material);
		if(enlace==false)
		{
			mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Ingres&oacute; una url  no v&aacute;lida");
			$("#material_multimedia1").val("");
			$("#preview_material_multimedia").html("");
			document.getElementById("preview_material_multimedia").style.display="none";
		}
		else 
		{
			var data={
   			            enlace:material
   			          }
   			$.ajax({
   						url:'./controladores/controlador.validar_url.php',
   						data:data,
   						type:'POST',
   						cache:false,
						error: function(request,error) 
				        {
				            console.log(arguments);
				            mensajes(3);//error desconocido
				        },
				        success: function(html)
				        {
				        		//alert(html);
				        		var recordset=$.parseJSON(html);
				        		if(recordset[0]==true)
				        		{
				        			cargar_div_video(material);
				        		}else
				        		if(recordset[0]==false)
				        		{
				        			mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Error:El enlace no es  v&aacute;lido");
				        			$("#material_multimedia1").val("");
									$("#preview_material_multimedia").html("");
									document.getElementById("preview_material_multimedia").style.display="none";
				        		}	
				        }
	  			});          
		}
	}
	function mostrar_video(video_contenido)
	{
		var entorno_video_contenido='<video width="80%" height="40%" style="margin-left:12%;margin-bottom:5%;" controls autoplay preload>\
								<source src="./material_video/'+video_contenido+'" type="video/ogg">\
								Your browser does not support the video tag.\
							</video>';
		///$material_un="./material_video/".$rs[$i][3];

		$("#preview_material_multimedia").css("display","block");					
		$("#preview_material_multimedia").html(entorno_video_contenido);					
	}
</script>
</head>
<body>
	<div id="form_2">
	<form class="form-horizontal"  type="post" role="form" name="form_registrar_contenido" id="form_registrar_contenido">
		<fieldset>
		    <legend>
		        <h3>Configurar Contenidos de E.V.A's</h3>
		    </legend>
    	</fieldset>
		<div class="form-group ">
			<div class="col-sm-10">
				<select name="aula_virtual_facilitador" id="aula_virtual_facilitador" class="form-control" onchange="cargar_unidades(this.value,0);">
					<?php echo $opcion_aula_virtual;?>
				</select>
			</div>
			<div class="col-sm-2">	
				<button  id="btn_conte" name="btn_conte" title="Consultar contendio EVA" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_consulta"><span class="glyphicon glyphicon-search"></span>
				</button>
			</div>	
        </div>
		<div class="form-group ">
			<div class="col-sm-12">
				<select name="unidades_aula_virtual" id="unidades_aula_virtual" class="form-control" onchange="cargar_contenido_ev();">
					<option value="-1">[Contenidos E.V.A]</option>
				</select>
			</div>
     	</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="text" name="titulo_aula" id="titulo_aula" placeholder="T&iacute;tulo del contenido" class="form-control input-sg" onKeyPress="return valida(event,this,18,100)" onBlur="valida2(this,18,100);">
			</div>
		</div>
		<div class="form-group margen_video">
			<div class="col-sm-12">
				<input type="hidden" name="material_multimedia" id="material_multimedia" class="form-control input-sg">
			   <label class="col-sm-2 control-label conf_label">Cargar Video:</label>
			   <div class="col-sm-6">	
				   <input type="file" class="btn btn-primary" name="material_video" id="material_video"> 
			   </div>
			</div>
		</div>
		<div id="preview_material_multimedia" name="preview_material_multimedia" class="col-lg-12" style="display:none">
		</div>
		<div class="form-group ">
			<div class="col-sm-12">	
				<textarea id="contenido_material" name="contenido_material" class="form-control" rows="5" placeholder="Resumen documental del contenido" onKeyPress='return valida(event,this,18,10000)' onBlur='valida2(this,18,10000);'></textarea>
			</div>
		</div>
  		<div class="form-group">
            <div class="col-sm-6">
                <input type="text" name="fecha_activacion_unidad" id="fecha_activacion_unidad" class="form-control"  onkeyup="this.value=formateafecha(this.value);" placeholder="Fecha activaci&oacute;n: dd/mm/aaaa">
            </div>
            <div class="col-sm-6" style='display:none'>
                <input type="hidden" readonly id="hora_activacion_unidad" name="hora_activacion_unidad"  class="form-control" placeholder="Hora activaci&oacute;n: 00:00">
            </div>
  		</div>
		<div id="informacion_material" name="informacion_material">
		</div>
		<legend></legend>
		<div class="form-group">
			<label class="col-sm-4 control-label conf_label">Incluir material documental en PDF:</label>
			   <div class="col-sm-10 texto2">	
				    <input type="checkbox" name="op_pdf" id="op_pdf"> 
			   </div>
		</div>
		<div id="cargar_pdf" name="cargar_pdf" style="display:none;">
			<input type="hidden" name="cuantos_pdf" id="cuantos_pdf" size="2" value="1">	
			<div class="form-group">
			   <label class="col-sm-2 control-label conf_label">Cargar PDF 1:</label>
			   <div class="col-sm-6">	
				   <input type="file" class="btn btn-primary" name="pdf_unidad1" id="pdf_unidad1"/> 
			   </div>
			<div class="col-lg-1">   
			   <button  id="btn_agregarpdf" name="btn_agregarpdf" title="Agregar otro PDF" type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button>
			</div>
			<di class="col-lg-1">   
			   <button  id="btn_quitarpdf" name="btn_quitarpdf" title="Quitar PDF de la lista" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-minus"></span></button>
			</div>
				<div id="segmento_pdf" name="segmento_pdf">
				<!-- Aqui se van a cargar los input para ue cargue un pdf... -->	
				</div>
		</div>	
		<legend></legend>
		<div class="col-lg-6">
			<button type="button" id="btn_guardar_cont" name="btn_guardar_cont" class="btn btn-info btn-form">Guardar</button>
	    </div>
	    <div class="col-lg-6">	
	    	<button type="reset" id="btn_limpiar_cont" name="btn_limpiar_cont" class="btn btn-warning btn-form">Limpiar</button>
		</div>
			<input type="hidden" id="cont_ev" name="cont_ev" size="5">
	</form>
	</div>
	<div id="contenedor_pie" class="form-group" style="position:relative;top:115%;">
      <div class="col-lg-12">
        <div>
          <h1 id="" style="display:none"></h1>
        </div>
      </div>  
  	</div>    
</body>
</html>