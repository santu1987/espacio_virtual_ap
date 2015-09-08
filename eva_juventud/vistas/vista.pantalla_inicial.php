<?php
session_start();
?>
<html>
<head>
<script type="text/javascript">
//BLOQUE DE EVENTOS
var perfil="<?php echo $_SESSION['id_perfil']; ?>";
$("#pie_pag").removeClass("contendor_pie_pagina2").addClass("contendor_pie_pagina");
//menui vertical solo para usuarios participantes
if(perfil!=1)
{
	$("#progra_body").load("./vistas/vista.registrar_usuario.php");
}
//para modificar el perfil de usuario
$("#perfil_us,#btn-modal").click(function(){
  $("#cuerpo_programa_est").load("./vistas/vista.registrar_usuario.php");
});
$("#contenedor_evas_rec").click(function(){
	//$('html, body').stop().animate({scrollTop: 700}, 2000);
	var perfil="<?php echo $_SESSION['id_perfil']; ?>";
	if((perfil==4)||(perfil==1))//en caso que sea facilitador o administrador
	{
		valor=1;
	}
	else
	{
		valor=0;
	}
    if(valor==1)
    {
   		$("#program_body").load("./vistas/vista.consultar_aulas_adm.php");
    }else
    {
	    $("#program_body").load("./vistas/vista.consultar_aulas.php");
    }	
}); 
$("#contenedor_facilitadores_rec").click(function(){
	//$('html, body').stop().animate({scrollTop: 1400}, 2000);
	$("#program_body").load("./vistas/vista.visualizar_facilitadores.php");
});
$("#contenedor_alumnos_rec").click(function(){
	//$('html, body').stop().animate({scrollTop: 1600}, 2000);
	$("#program_body").load("./vistas/vista.visualizar_usuarios.php");
});
$("#contenedor_rss_rec").click(function(){

	$("#program_body").load("./vistas/vista.consultar_miscontactos.php")
});
$("#contenedor_eval_rec").click(function(){
	var perfil="<?php echo $_SESSION['id_perfil']; ?>";
	if((perfil==4)||(perfil==1))//en caso que sea facilitador o administrador
	{
		$("#program_body").load("./vistas/vista.consultar_evaluaciones_aula.php");
	}else
	{
		$("#program_body").load("./vistas/vista.consultar_misevaluaciones.php");
	}	            	
});
$("#contenedor_noticias_rec").click(function(){
var perfil="<?php echo $_SESSION['id_perfil']?>";
if(perfil==10)//estudiante
{
	$("#form_pantalla_principal").attr("action","./manuales/manual_estudiante.pdf");	
}else
if(perfil==4)//administrador
{
	$("#form_pantalla_principal").attr("action","./manuales/manual_administrador.pdf");	
}else
if(perfil==1)//facilitador
{
	$("#form_pantalla_principal").attr("action","./manuales/manual_facilitador.pdf");	
}	
	$("#form_pantalla_principal").submit(); 
});
cargar_aulas_recientes();
cargar_facilitadores();
cargar_estudiantes();
//BLOQUE DE FUNCIONES	
function cargar_mas_evas(valor)
{
   if(valor==1)
   {
   		$("#program_body").load("./vistas/vista.consultar_aulas_adm.php");
   }else
   {
	    $("#program_body").load("./vistas/vista.consultar_aulas.php");
   }
}
function ver_contacto(id_us,tipo)
{
	$("#program_body").load("./vistas/vista.usuario_consulta_perfil.php?id_us="+id_us+"&tipo_consulta="+tipo);
}
function agregar_contacto(id_us)
{
	var data={id_us:id_us};
	$.ajax({
				url:"./controladores/controlador.registrar_contacto_us.php",
				data:data,
				type:'POST',
				cache:'false',
				error: function()
				{
					  console.log(arguments);
			          mensajes(3);
				},
				success: function(html)
				{
					recordset=$.parseJSON(html);
					alert(recordset);
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
					if(recordset=='0')
					{
						mensajes(30);//contacto no agregado
					}
					else
					if(recordset!='0')
					{
						mensajes(31);//contacto agregado satisfactoriamente
					}	
				}	
	});
}
function cargar_facilitadores()
{
	var data="{}";
	$.ajax({
				url:'./controladores/controlador.consultar_facilitadores_in.php',
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
                    var recordset=$.parseJSON(html);
	            	//alert(recordset);
	            	var cuantos=recordset.length;
	            	var cuerpo_facilitadores="<div class='form-group'>\
												<div class='col-lg-10'>\
													<input type='hidden'></input>\
												</div>\
												<div class='col-lg-2'>\
													<button class='btn btn-primary' type='button' id='ver_mas_fac' name='ver_mas_fac' onclick='cargar_mas_facilitadores();' title='Ir a consulta de Facilitadores'>Ver Mas</button>\
												</div>\
											</div>";
	            	for(i=0; i<=cuantos-1;i++)
	            	{
		            	if(recordset[i][2]==null)
	            		{
							imagen_fac="user.png";
						}	
						else
						{
							imagen_fac=recordset[i][2];
						}
		            	///////////////////////////////////////////////////////////////
						cuerpo_facilitadores+="<div class='col-lg-6'>\
													<div class='facilitador_consulta'>\
														<img id='imagen_usuario_foro' src='./img/fotos_personas/"+imagen_fac+"' class='img-circle img_fac_in pull-left' >\
													<div id='mensaje_us' name='mensaje_us' class='mensaje_fac_in'>"+recordset[i][4]+"</div>\
										  			<div id='botonera_mensajes_fac' name='botonera_mensajes_fac'>\
										  				<a class='ver_us_pi' onclick='ver_contacto("+recordset[i][0]+",1)'><i class='fa fa-eye'></i> Visitar</a>\
										  				<a class='agregar_us' onclick='agregar_contacto("+recordset[i][0]+")'><i class='fa fa-plus-circle'></i> Agregar</a>\
										  			</div>\
										  			</div>\
										 		</div>\
										 		";	
		            	///////////////////////////////////////////////////////////////
	            	}	
	            	$("#contenedor_facilitadores").html(cuerpo_facilitadores);
	            }
		});
}
function cargar_mas_us()
{
	$("#program_body").load("./vistas/vista.visualizar_usuarios.php");
}
function cargar_mas_facilitadores()
{
	$("#program_body").load("./vistas/vista.visualizar_facilitadores.php");
}
function cargar_estudiantes()
{
	var data="{}";
	$.ajax({
				url:"./controladores/controlador.consultar_usuarios_in.php",
				data:data,
				type:"POST",
				cache:false,
				error: function(request,error) 
	            {
	                  console.log(arguments);
	                  mensajes(3);//error desconocido
	            },
	            success: function(html)
	            {	
                    var recordset=$.parseJSON(html);
                	var cuantos=recordset.length;
                	var cuerpo_usuarios="<div class='form-group'>\
										<div class='col-lg-10'>\
											<input type='hidden'></input>\
										</div>\
										<div class='col-lg-2'>\
											<button class='btn btn-primary' type='button' id='ver_mas_evas' name='ver_mas_evas' onclick='cargar_mas_us();' title='Ir a consulta de usuarios'>Ver Mas</button>\
										</div>\
									</div>";
	            	for(i=0; i<=cuantos-1;i++)
	            	{
		            	///////////////////////////////////////////////////////////////
						if (recordset[i][2]==null)
						{
							imagen_us="user.png";
						}	
						else
						{
							imagen_us=recordset[i][2];
						}							
						cuerpo_usuarios+="<div class='col-lg-6'>\
											<div class='facilitador_consulta'>\
													<img id='imagen_usuario_foro' src='./img/fotos_personas/"+imagen_us+"' class='img-circle img_fac_in pull-left' >\
												<div id='mensaje_us' name='mensaje_us' class='mensaje_fac_in'>"+recordset[i][4]+"</div>\
									  			<div id='botonera_mensajes_fac' name='botonera_mensajes_fac'>\
										  				<a class='ver_us_pi' onclick='ver_contacto("+recordset[i][0]+",1)'><i class='fa fa-eye'></i> Visitar</a>\
										  				<a class='agregar_us' onclick='agregar_contacto("+recordset[i][0]+")'><i class='fa fa-plus-circle'></i> Agregar</a>\
										  		</div>\
									  			<input type='hidden' size='2' name='id_mensaje"+i+"' id='id_mensaje"+i+"' value="+recordset[i][0]+">\
									 		</div>\
									 	</div>";	
		            	///////////////////////////////////////////////////////////////
	            	}	
	            	$("#contenedor_usuarios_rec").html(cuerpo_usuarios);
	            }
	});

}
function cargar_eva(id_aula)
{
	$("#program_body").load("./vistas/vista.configurar_eva.php?id_aula="+id_aula);
}
function cargar_aulas_recientes()
{
	var data="{}";
	$.ajax({
				url:'./controladores/controlador_consultar_aulas_in.php',
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
                    var recordset=$.parseJSON(html);
	            	//alert(recordset);
	            	var vector=recordset;
	            	var j=vector.length;
	            	var re_cuadro='';
	            	var imagen_v='';
	            	var titulo_v='';
	            	var parrafo_v='';
	            	var btn_insc='';
	            	var btn_cons='';
	            	var valor='';
	            	var perfil="<?php echo $_SESSION['id_perfil']; ?>";
	            	if((perfil==4)||(perfil==1))//en caso que sea facilitador o administrador
	            	{
	            		valor=1;
	            	}
	            	else
	            	{
	            		valor=0;
	            	}	
	            	var re_cuadro="<div class='form-group'>\
										<div class='col-lg-10'>\
											<input type='hidden'></input>\
										</div>\
										<div class='col-lg-2'>\
											<button class='btn btn-primary' type='button' id='ver_mas_evas' name='ver_mas_evas' onclick='cargar_mas_evas("+valor+");' title='Ir a consulta de E.V.A's>Ver Mas</button>\
										</div>\
									</div>";
	            	for(i=0;i<=j-1;i++)
	            	{
	            		/////////////////////////////////////////
	            		imagen_v=vector[i][1];
	            		titulo_v=vector[i][2];
	            		parrafo_v=vector[i][3];
	            		if((vector[i][6]!='4')&&(vector[i][6]!='1'))
	            		{
	            			btn_insc="<div class='col-lg-6'><a href='#' class='btn btn-primary' role='button'>Inscribirme</a></div>";
	            			btn_cons="<div class='col-lg-6'><a href='#' class='btn btn-default' role='button'>Consultar</a></div>";	
	            		}else 
	            		{
	            			btn_insc="";
	            			btn_cons="<a href='#' onclick='cargar_eva("+vector[i][0]+");' class='btn btn-primary' role='button'>Consultar</a>";	
	            		}
	            		//////////////////////////////////////////
	            		re_cuadro=re_cuadro+"<div class='in_aulas col-lg-4'>\
												  <div id='contenedor_au' class='col-sm-6 col-md-4 re-cuadro thumbnail'>\
												      <img src='./img/img_eva/"+imagen_v+"' class='img_eva_us' alt='...'>\
												      <div id='c1_tabla' class='caption'>\
													        <h3>"+titulo_v+"</h3>\
													        <div id='parrafo_texto'>"+parrafo_v+"</div>\
													        </p>\
													        <div class='form-group'>\
													        <p>"+btn_insc+" "+btn_cons+"</p>\
													        </div>\
												  	  </div>\
												  </div>\
											  </div>";
	            	}
	            	$("#contenedor_aulas").html(re_cuadro);
	            }	
	});
}
</script>	
</head>
<body>
<!-- Contenedor carrousel-->
<div id="contenedor_carusel" class="carusel_in">	
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
			  <!-- Indicators -->
			  <ol class="carousel-indicators">
			    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
			    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
			    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
			  </ol>
			  <!-- Wrapper for slides -->
			  <div class="carousel-inner" role="listbox">
				     <div class="item active">
					      <img src="./img/carusel/carusel_cero.jpg"  class="img_carusel">
					      <!--<div class="carousel-caption">
					        ...
					      </div>-->
				    </div>
				    <div class="item">
					      <img src="./img/carusel/carusel_uno.jpg"  class="img_carusel">
					     <!-- <div class="carousel-caption">
					        ...
					      </div>-->
				    </div>
				     <div class="item">
					      <img src="./img/carusel/carusel_dos.jpg"  class="img_carusel">
					      <!--<div class="carousel-caption">
					        ...
					      </div>-->
				    </div>
				     <div class="item">
					      <img src="./img/carusel/carusel_tres.jpg"  class="img_carusel">
					      <!--<div class="carousel-caption">
					        ...
					      </div>-->
				    </div>
				    
			  </div>
			  <!-- Controls -->
			  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			  </a>
			</div>
</div>		
<!-- -->
<br>	
<!-- Titulo principal -->
<form id='form_pantalla_principal' name='form_pantalla_principal' method='POST'  target='_blank'>
<div id="contenedor_titulo_p" class="form-group">
	<div class="col-lg-12">
		<div class="titulo_eva">
			<h1><i class="fa fa-desktop" style="padding: 1%;"></i> ESPACIO VIRTUAL DE APRENDIZAJE </h1>
		</div>
	</div>	
</div>	
<!-- Sector de opciones-->
<div id='padre_articulos'>
	<div id="contenedor_rec" class="form-group">
		<div id="contenedor_evas_rec" class="col-lg-4 navegacion_rec" href="#noticias_title">
			<span class="fa-stack fa-lg">
			  <i class="fa fa-circle fa-stack-2x"></i>
			  <i class="fa fa-university fa-stack-1x fa-inverse"></i>
			</span>
			<b>Espacios Virtuales recientes</b>
		</div>
		<div id="contenedor_facilitadores_rec" class="col-lg-4 navegacion_rec">
			<span class="fa-stack fa-lg">
			  <i class="fa fa-circle fa-stack-2x"></i>
			  <i class="fa fa-user fa-stack-1x fa-inverse"></i>
			</span>
			<b>Contactar Facilitadores</b>
		</div>
		<div id="contenedor_alumnos_rec"  class="col-lg-4 navegacion_rec">
			<span class="fa-stack fa-lg">
			  <i class="fa fa-circle fa-stack-2x"></i>
			  <i class="fa fa-users fa-stack-1x fa-inverse"></i>
			</span>
		   <b>Contactar Estudiantes</b>
		</div>
	</div>		
</div>
<div id='padre_articulos_2da_fila'>
	<div id="contenedor_evaluaciones_rec" class="form-group">
		<div id="contenedor_eval_rec" class="col-lg-4 navegacion_rec">
			<span class="fa-stack fa-lg">
			  <i class="fa fa-circle fa-stack-2x"></i>
			  <i class="fa fa-graduation-cap fa-stack-1x fa-inverse"></i>
			</span>
			 <b>Ver evaluaciones recientes</b>
		</div>
		<div id="contenedor_rss_rec" class="col-lg-4 navegacion_rec">
			<span class="fa-stack fa-lg">
			  <i class="fa fa-circle fa-stack-2x"></i>
			  <i class="fa fa-envelope-o fa-stack-1x fa-inverse"></i>
			</span>
			 <b>Ver Contactos</b>
		</div>
		<div id="contenedor_noticias_rec"  class="col-lg-4 navegacion_rec">
			<span class="fa-stack fa-lg">
			  <i class="fa fa-circle fa-stack-2x"></i>
			  <i class="fa fa fa-question-circle fa-stack-1x fa-inverse"></i>
			</span>
			 <b>Ver Ayuda</b>
		</div>
	</div>		
</div>
</form>
<!--<div class='container form-group'>
	<div class='col-lg-12'>
		<div id='mensaje_ayuda' class="alert alert-success" role="alert">
			Presione el siguiente <a href="#" id='link_ayuda'><i class="fa fa-file-pdf-o"></i> Link</a> para acceder a la ayuda del sistema...
		</div>
	</div>
	<!--<div class='col-lg-2'>	
		<a class='btn btn-primary' id='baja_seccion2' style='width:50px;' title='Ir secci&oacute;n detalle EVA'  href='#marco_titulo_resumen".$k."' ><i class='fa fa-arrow-down'></i></a>
	</div>	
</div>-->
<!-- Contenedor de aulas recientes cargadas....-->
<!--<div id="contenedor_aulas1" class="form-group">
	<div class="col-lg-12">
		<div class="titulo_eva">
			<h1 id="noticias_title"><i class='fa fa-university fa-circle' style="padding: 1%;"></i> E.V.A's Recientes</h1>
		</div>
	</div>	
</div>
<!-- -->	
<!--<div id="contenedor_aulas" class="form-group">
</div>	
<div id="contenedor_aulas2" class="form-group">
	<div class="col-lg-12">
		<div class="titulo_eva">
			<h1 id="noticias_title"><i class="fa fa-user" style="padding: 1%;"></i> Facilitadores</h1>
		</div>
	</div>	
</div>
<div id="contenedor_facilitadores" class="form-group">
</div>
<div id="contenedor_aulas3" class="form-group">
	<div class="col-lg-12">
		<div class="titulo_eva">
			<h1 id="noticias_title"><i class="fa fa-users" style="padding: 1%;"></i>Estudiantes recientes</h1>
		</div>
	</div>	
</div>
<div id="contenedor_usuarios_rec" class="form-group" >
</div>
<div class='form-group'>
	<div id="contenedor_aulas4" class='col-lg-5'>
		<div class="titulo_eva">
			<h1 id="noticias_title"><i class="fa fa fa-newspaper-o" style="padding: 1%;"></i>Noticias recientes</h1>
		</div>
	</div>
	<div id="contenedor_aulas5" class='col-lg-5'>
		<div class="titulo_eva">
			<h1 id="noticias_title"><i class="fa fa-rss" style="padding:1%"></i> RSS</h1>
		</div>
	</div>
</div>
<div class="form-group">
	<div id='cuerpo_noticias' class='col-lg-5'>
		xxxxxx
	</div>
	<div id='cuerpo_rss' class='col-lg-5'>
		xxxxxx
	</div>	
</div>
<div id="contenedor_aulas3" class="form-group">
	<div class="col-lg-12">
		<div class="titulo_eva">
			<h1 id="" style="display:none"></h1>
		</div>
	</div>	
</div>-->
</body>
</html>