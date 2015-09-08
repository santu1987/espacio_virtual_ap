<?php
session_start();
if(isset($_GET["id_aula"]))
{
	if($_GET["id_aula"]!="")
	{
		$aula=$_GET["id_aula"];
	}	
}
$id_us=$_SESSION["id_us"];
$id_perfil=$_SESSION["id_perfil"];
?>
<script type="text/javascript">
//VISTA DE ESPACIO VIRTUAL DE LOS USUARIOS ESTUDIANTES
//BLOQUE DE EVENTOS
var aula="<?php echo $aula; ?>";
cargar_aula_form(aula);
//BLOQUE DE FUNCIONES
function consultar_foro(offset,limit,actual)
{
	var aula="<?php echo $aula; ?>";
	var data={aula:aula,offset:offset,limit:limit,actual:actual};
	$.ajax({
				url:"./controladores/controladores.consultar_mensajes.php",
				data:data,
				type:"POST",
				cache:false,
				error: function()
				{
				  console.log(arguments);
		          mensajes(3);
				},
				success: function(html)
				{
					var vector=$.parseJSON(html);
					if(vector=="error")
					{
						mensajes(3);//error
					}else
					if(vector=="campos_blancos")
					{
						mensajes(5);//campos blancos
					}else
					{
						var recordset=vector[0];
						var paginador=vector[1];
						var cuantos_elementos=recordset.length;
						var cuerpo_foro="<div class='contenedor_principal_foro_us'>";
						var n_us='';
						$("#foro_principal").html("");
						for(i=0;i<=(cuantos_elementos)-1;i++)
						{
							n_us=recordset[i][0].split("@");
							cuerpo_foro+="<div class='contenedor_us'>\
													<img id='imagen_usuario_foro' src='./img/fotos_personas/"+recordset[i][1]+"' class='img-circle img_us_foro pull-left' >\
													<div id='nombre_us_foro' name='nombre_us_foro'>@"+n_us[0]+" dice:</div>\
													<div id='mensaje_us' name='mensaje_us' class='mensaje_foro'>"+recordset[i][2]+"</div>\
										  			<div id='botonera_mensaje_us' name='botonera_mensaje_us'>\
										  				<a onclick='cargar_cuerpo_responder_foro("+i+");' class='responder_mensaje' data-toggle='modal' data-target='#myModal_responder_foro'><i class='fa fa-comment-o'></i>"+' '+"<label id='cuantos_comentarios'>"+recordset[i][4]+"</label> Comentarios</a>\
										  				<a class='megusta_mensaje'><i class='fa fa-thumbs-o-up'></i> me gusta</a>\
										  			</div>\
										  			<input type='hidden' size='2' name='id_mensaje"+i+"' id='id_mensaje"+i+"' value="+recordset[i][3]+">\
										  </div>";	
						}	
						cuerpo_foro+="</div>\
											<div id='paginacion_consulta'>\
												<ul id='paginacion_foro' class='pagination'></ul>\
											</div>";
						if(cuantos_elementos>0)
						{
								$("#titulo_mr").html("<legend><h3>Mensajes recientes</h3></legend>");
							$("#foro_principal").append(cuerpo_foro);
							$("#paginacion_foro").html(paginador);//paginacion
						}					
					}	
				}
			});
}
function cargar_cuerpo_responder_foro(a)
{
	var cabecera="<b><h3>Responder comentario</h3></b>";
	$("#myModalLabelresponder").html(cabecera);
	$("#cuerpo_responder").html("");	
	var cuerpo_formulario_responder="<form name='form_foro_responder' id='form_foro_responder' class='form-horizontal' role='form'>\
									 <div class='form-horizontal'>\
										 <div class='form-group'>\
				      							<div class='col-lg-12'>\
				      								<textarea id='mensaje_foro_responder' name='mensaje_foro_responder' class='form-control' rows='5' placeholder='Ingresa tu comentario' onKeyPress='return valida(event,this,18,140);' onBlur='valida2(this,18,140);'></textarea>\
				   					   			</div>\
				   					   	  </div>\
				   					   	  <div class='form-group'>\
												<div class='col-lg-12'>\
													<button id='btn_publicar_mensaje_resp' name='btn_publicar_mensaje_resp' onclick='publicar_mensaje_responder("+a+");' class='btn btn-primary' type='button' title='Publicar Mensaje'><i class='fa fa-pencil'></i></button>\
												</div>\
										   </div>\
									 </div>\
										 <div id='foro_principal_respuestas'>\
										 </div>\
										 <input type='hidden' size='2' name='n_comentario' id='n_comentario' value='"+a+"'>\
									 </form>";
	$("#cuerpo_responder").html(cuerpo_formulario_responder);
	consultar_mensajes_respuestas_foro(0,5,0);
}
function publicar_mensaje_responder(a)
{
	var mensajes_respuesta=$("#mensaje_foro_responder").val();
	var id_foro=$("#id_mensaje"+a).val();
	var data={mensaje:mensajes_respuesta,id_foro:id_foro};
	$.ajax({
				url:"./controladores/controlador.registrar_respuesta_foro.php",
				data:data,
				type:"POST",
				cache:false,
				error: function()
				{
					  console.log(arguments);
			          mensajes(3);
				},
				success: function(html)
				{
					var recordset=$.parseJSON(html);
					//alert(recordset);
					if(recordset=="error")
					{	
						mensajes(3);//error
					}
					else
					if(recordset=="campos_blancos")
					{
						mensajes(5);//campos blancos
					}
					else	
					if(recordset=="registro_exitoso")
					{
						mensajes(21);//registro exitoso
						consultar_mensajes_respuestas_foro(0,5,0);
					}
				}
	});
}
function consultar_mensajes_respuestas_foro(offset,limit,actual)
{
	var a=$("#n_comentario").val();
	var id_foro=$("#id_mensaje"+a).val();
	var data={
				id_foro:id_foro,
				offset:offset,
				limit:limit,
				actual:actual
			 };	
	$.ajax({
				url:"./controladores/controladores.consultar_mensajes_respuestas.php",
				data:data,
				type:"POST",
				cache:false,
				error: function()
				{
					  console.log(arguments);
			          mensajes(3);
				},
				success: function(html)
				{
					var vector=$.parseJSON(html);
					if(vector=="error")
					{
						mensajes(3);//error
					}else
					if(vector=="campos_blancos")
					{
						mensajes(5);//campos blancos
					}else
					{
						var recordset=vector[0];
						var paginador=vector[1];
						var cuantos_elementos=recordset.length;
						var cuerpo_foro="<div class='contenedor_principal_foro_us'>";
						var n_us='';
						$("#foro_principal_respuestas").html("");
						for(i=0;i<=(cuantos_elementos)-1;i++)
						{
							n_us=recordset[i][0].split("@");
							cuerpo_foro+="<div class='contenedor_us'>\
													<img id='imagen_usuario_foro' src='./img/fotos_personas/"+recordset[i][1]+"' class='img-circle img_us_foro pull-left' >\
													<div id='nombre_us_foro' name='nombre_us_foro'>@"+n_us[0]+" dice:</div>\
													<div id='mensaje_us' name='mensaje_us' class='mensaje_foro'>"+recordset[i][2]+"</div>\
										  </div>";	
						}	
						cuerpo_foro+="</div>\
											<div id='paginacion_consulta'>\
												<ul id='paginacion_foro_respuestas' class='pagination'></ul>\
    										</div>";
    					if(cuantos_elementos>0)
    					{
    						$("#foro_principal_respuestas").append(cuerpo_foro);				
							$("#paginacion_foro_respuestas").html(paginador);//paginacion	
							consultar_foro(0,5,0);
    					}
					}	
				}
			});	
}
function publicar_mensaje()
{
	var aula="<?php echo $aula; ?>";
	var mensaje_foro=$("#mensaje_foro").val();
	var data={aula:aula,mensaje_foro:mensaje_foro};
	$.ajax({
				url:"./controladores/controlador.publicar_mensaje.php",
				data:data,
				type:"POST",
				cache:false,
				error: function()
				{
					  console.log(arguments);
			          mensajes(3);
				},
				success: function(html)
				{
					var recordset=$.parseJSON(html);
					//alert(recordset);
					if(recordset[0]=='error')
					{
						mensajes(3);//error
					}
					else
					if(recordset=="campos_blancos")
					{
						mensajes(5);//campos blancos
					}
					else
					if(recordset=="error_auditoria")
					{
						mensajes();//error en auditoria...
					}	
					if(recordset=="registro_exitoso")
					{
						mensajes(21);//registro exitoso
						$("#mensaje_foro").val("");
						consultar_foro(0,5,0);
					}	
				}
	});
}
function cargar_foro()
{
	var cuerpo_reg_mensaje="<form name='form_foro' id='form_foro' class='form-horizontal' role='form'>\
								<div class='form-group'>\
	      							<div class='col-lg-12'>\
	      								<textarea id='mensaje_foro' name='mensaje_foro' class='form-control' rows='5' placeholder='Ingresa tu comentario' onKeyPress='return valida(event,this,18,140);' onBlur='valida2(this,18,140);'></textarea>\
	   					   			</div>\
	   					   		</div>\
	   					   		<div class='form-group'>\
									<div class='col-lg-12'>\
										<button id='btn_publicar_mensaje' name='btn_publicar_mensaje' onclick='publicar_mensaje();' class='btn btn-primary' type='button' title='Publicar Mensaje'><i class='fa fa-pencil'></i></button>\
									</div>\
								</div>\
							</form>";
	var cuerpo_mensajes_foro="<div id='div_foro'><div id='titulo_mr' name='titulo_mr'></div><div id='foro_principal'></div></div>";
	$("#form_foro").remove();						
	$("#div_foro").remove();
	$("#cuerpo_reg_mensaje").append(cuerpo_reg_mensaje);
	$("#cuerpo_mensajes_foro").append(cuerpo_mensajes_foro);
	document.getElementById("btn_agregar_an").style.display="none";
}
function cargar_prueba(id_contenido,id_evaluacion)
{
	var opcion=1;
	$("#program_body").load("./vistas/vista.desarrollar_prueba.php?id_contenido="+id_contenido+"&id_evaluacion="+id_evaluacion+"&opcion="+opcion);
}
function consultar_anotaciones_unidad()
{
	var id_unidad=$("#id_unidad").val();
	var id_aula=$("#id_aula").val();
	var data={
				id_unidad:id_unidad,
				id_aula:id_aula,
			};
	$.ajax({
				url:'./controladores/controlador.consultar_anotaciones_unidad.php',
				type:'POST',
				data:data,
				cache:false,
				error: function()
				{
					  console.log(arguments);
			          mensajes(3);
				},
				success: function(html)
				{
					var recordset=$.parseJSON(html);
					//alert(recordset);
					if(recordset[0]=="error_bd")
					{
						mensajes(7);
					}
					$("#anotacion_texto").val(recordset[0]);
				}
	});		
	
}
function subir(i)
{
	$('html, body').stop().animate({scrollTop: $($("#ir_cabecera"+i).attr('href')).offset().top}, 1000);
}
function bajar(i)
{
	$('html, body').stop().animate({scrollTop: $($("#baja_texto"+i).attr('href')).offset().top}, 1000);
}
function bajar2(i)
{
	$('html, body').stop().animate({scrollTop: $($("#ir_archivos"+i).attr('href')).offset().top}, 1000);
}
function desactivar_pdf()
{
	$("#pestana_pdf,#menu_pdf_arc").remove();
	document.getElementById("btn_agregar_an").style.display="";
}
function cargar_pdf(archivo)
{
    //////////////////
    $('#menu_aula li').each(function(indice, elemento)
    {
        $(elemento).removeClass("active");
    });
    $('.cuadro_tab').each(function(indice, elemento)
    {
        $(elemento).removeClass("tab-pane active").addClass("tab-pane");
    }); 
    //////////////////
    var pestana_pdf="<div class='tab-pane active' id='pestana_pdf'>\
    					<iframe id='iframe_pdf' name='iframe_pdf'>\
            			</iframe>\
    				</div>";
    var menu_pdf="<li id='menu_pdf_arc' class='active'><a href='#pestana_pdf' data-toggle='tab'>Archivos adjuntos</a></li>";
	$("#contenido").append(pestana_pdf);			
	$("#menu_aula").append(menu_pdf);
    document.form_aulas.action='./pdf/'+archivo;
    document.forms.form_aulas.submit();
    subir(1);
}
function cargar_aula_form(aula)
{
	var id_us="<?php echo $id_us;?>";
	var id_perfil="<?php echo $id_perfil;?>";
	$("#titulo_eva").html(aula);
	var data={aula:aula};
	$.ajax({
				url:"./controladores/controlador.consultar_unidad_aula.php",
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
					var recordset=$.parseJSON(html);
					if(recordset[0]==null)
					{
						mensajes2("Disculpe pero no existen contenidos activos a esta aula");
						if((id_perfil=='1')||(id_perfil=='4'))
						{
							$("#program_body").load("./vistas/vista.consultar_aulas_adm.php");
						}
						else
							$("#program_body").load("./vistas/vista.consultar_aulas.php");
					}	
					$("#titulo_eva").html("<legend><h1 class='titulo_eva'>"+recordset[0]+"</h1></legend>");
					$("#menu_aula").html(recordset[1]);	
					$("#contenido").html(recordset[2]);
				}
	});
}
/////////////////////////////////////////////////////////////////
//BLOQUE DE EVENTOS
$("#btn_agregar_an").click(function(){
	var cabecera="<b><h3>Registro de anotaciones</h3></b>";
	$("#myModalLabelregistrar").html(cabecera);
	$("#cuerpo_registrar").html("");	
	var formulario_preguntas='<div class="form-group">\
									<div class="col-sm-12">\
										<textarea id="anotacion_texto" name="anotacion_texto" rows="5" class="form-control input-sg" placeholder="Ingrese anotaci&oacute;n en base al contenido de la unidad" onKeyPress="return valida(event,this,18,200)" onBlur="valida2(this,18,200);"></textarea>\
									</div>\
							  </div>';
	$("#cuerpo_registrar").html(formulario_preguntas);
	consultar_anotaciones_unidad();							
});
$("#registrar_modal").click(function(){
	var id_unidad=$("#id_unidad").val();
	var id_aula=$("#id_aula").val();
	var anotaciones=$("#anotacion_texto").val();
	var data={
				id_unidad:id_unidad,
				id_aula:id_aula,
				anotaciones:anotaciones
			};
	$.ajax({
				url:"./controladores/controlador.registrar_anotacion_unidad.php",
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
					var recordset=$.parseJSON(html);
					//alert(recordset);
					if(recordset[0]=="error_bd")
					{
						mensajes(10);
					}else
					if(recordset[0]=="registro_exitoso")
					{
						mensajes(6);
      		          	$('#myModal_registrar').modal('hide');//apago el modal
					}	
				}
	});		
});
</script>
<div id="cuerpo_aula" class="cuerpo_aula">
		<div id="titulo_eva" name="titulo_eva">
			<h1></h1>
		</div>
		<ul id="menu_aula" class='nav nav-tabs'>
		<!-- -->
		<!-- -->	
		</ul>
		<form id="form_aulas" class="form-horizontal" name="form_aulas" method="POST" target="iframe_pdf">	
       		 <div class="tab-content" id="contenido">
        	 </div>
        	 	<div id="evaluacion" name="evaluacion">
        	 	</div>
		 		<div class="form-group">
					<div class="col-lg-10">
		 				<input type="hidden" class="col-lg-8">
		 			</div>
			 		<div class="col-lg-2">
			 			<button  type="button" id="btn_agregar_an" name="btn_agregar_an" data-toggle="modal" data-target="#myModal_registrar" class="btn  btn-primary" title="Cargar anotaciones" >Anotaciones</button>
			 		</div>
		 		</div>		
<!-- ------------------------------ -->
		<!-- Modal: Responder mensaje foro -->
		<div class="modal fade bs-example-modal-lg modal_emergente" id="myModal_responder_foro" tabindex="-1" role="dialog" aria-labelledby="myModalLabelresponder" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		        <h4 class="modal-title" id="myModalLabelresponder"></h4>
		      </div>
		      <div class="modal-body">
		        <table class="table table-hover"  width="70%">
		          <thead id="cabecera_responder" name="cabecera_responder">  
		        <!-- -->
		        </thead>
		        <tbody id="cuerpo_responder" name="cuerpo_responder">    
		        <!-- -->
		        </tbody> 
		        </table>
		      </div>
		    </div>
		  </div>
		</div>
		<!-- FIN Modal -->
		<!-- Modal: Registro emergentes -->
		<div class="modal fade bs-example-modal-lg modal_emergente" id="myModal_registrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabelregistrar" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		        <h4 class="modal-title" id="myModalLabelregistrar"></h4>
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
<!-- ------------------------------ -->        	 	
        </form>
</div>        	 	
	