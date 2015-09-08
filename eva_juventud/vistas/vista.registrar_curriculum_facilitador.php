<?php
session_start();
?>
<html>
<head>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#pie_pag").removeClass("contendor_pie_pagina").addClass("contendor_pie_pagina2");
//al cargarse el form
consultar_cur();
$("#btn_registrar_cur").click(function(){
	data=$("#form_cur").serialize();
	$.ajax({
				url:"./controladores/controlador.registrar_curriculum.php",
				data:data,
				type:"POST",
				cache:"false",
				error: function(request,error) 
                {
                      console.log(arguments);
                      mensajes(3);//error desconocido
                },
                success: function(html)
                {
                    var recordset=$.parseJSON(html);
                    //alert(recordset);
                	if(recordset=="error")
                	{
                		mensajes(7);//mensaje de error
                	}else if(recordset[0]=="registro_exitoso")
                	{
                		mensajes(6);//operacion realizada con exito
                		$("#id_cur").val(recordset[1]);
                	}	
                }

	});
});
//BLOQUE DE FUNCIONES
function consultar_cur()
{
	data=$("#form_cur").serialize();
	$.ajax({
				url:"./controladores/controlador.consultar_curriculum.php",
				data:data,
				type:"POST",
				cache:"false",
				error: function(request,error) 
                {
                      console.log(arguments);
                      mensajes(3);//error desconocido
                },
                success: function(html)
                {
                    //alert(html);
                    var recordset=$.parseJSON(html);
                   
                	if(recordset=="error")
                	{
                		mensajes(7);//mensaje de error
                	}else 
                	{
                		$("#resumen_perfil_profesional").val(recordset[0][1]);
                		$("#resumen_perfil_academico").val(recordset[0][2]);
                		$("#resumen_perfil_laboral").val(recordset[0][3]);
                		$("#resumen_perfil_cursos").val(recordset[0][4]);
                		$("#id_cur").val(recordset[0][0]);
                	}	
                }

	});
}
</script>
<head/>
<body>
<div id="form_1">	
	<form class="form-horizontal"  type="post" role="form" name="form_cur" id="form_cur">
			<fieldset>
        <legend>
            <h3>Registrar curriculum</h3>
        </legend>
      </fieldset>
			<div class="form-group">
      			<div class="col-sm-12">
      				<textarea id="resumen_perfil_profesional" name="resumen_perfil_profesional" class="form-control" rows="5" placeholder="Ingrese resumén de perfil profesional ejm: Profesional en el área de la informática, especializado en el desarrollo de software bajo herramientas libres, 5 años de experiencia en el área" onKeyPress="return valida(event,this,18,300)" onBlur="valida2(this,18,300);"></textarea>
      			</div>	
			</div>
			<div class="form-group">
					<div class="col-sm-12">
	  				<textarea id="resumen_perfil_academico" name="resumen_perfil_academico" class="form-control" rows="5" placeholder="Ingrese resumén de perfil acad&eacute;mico ejm: Egresado de la Universidad Bolivariana de Venezuela, con Expecializaci&oacute;n en Sistemas de informaci&oacute;n, maestr&iacute;a en marketing y publicidad" onKeyPress="return valida(event,this,18,300)" onBlur="valida2(this,18,300);"></textarea>

	  			</div>
			</div>
			<div class="form-group">
						<div class="col-sm-12">
	      			<textarea id="resumen_perfil_laboral" name="resumen_perfil_laboral" class="form-control" rows="5" placeholder="Ingrese resumén de perfil laboral ejm: 5 a&ntielde;os de experiencia como profesor de programaci&oacute;n en la Universidad Nueva Esparta, 3 a&ntielde;os de experiencia como desarrollador de software para el Ministerio del Poder Popular Para la educaci&ocute;n." onKeyPress="return valida(event,this,18,300)" onBlur="valida2(this,18,300);"></textarea>
	      		</div>
	    </div>		
			<div class="form-group">
      			<div class="col-sm-12">
      				<textarea id="resumen_perfil_cursos" name="resumen_perfil_cursos" class="form-control" rows="5" placeholder="Ingrese resumén de cursos realizados ejm: A&ntilde;o 2012 Curso de desarrollo een Php, Universidad Central de Venezuela " onKeyPress="return valida(event,this,18,300)" onBlur="valida2(this,18,300);"></textarea>
      			</div>
      		</div>		
      <div class="col-lg-12">    
			   <button type="button" class="btn btn-info btn-form" name="btn_registrar_cur" id="btn_registrar_cur">Guardar</button>
			</div>
      <input type="hidden" name="id_cur" id="id_cur" size="2">	
	</form>
</div>
</div>
</body>
</html>