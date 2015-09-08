<?php
session_start();
?>
<script type="text/javascript">
//FUNCIONES
function validar_claves()
{
	if(($("#clave_nueva_us").val())!=($("#clave_nueva_us_rep").val()))
	{
		mensajes2("Las claves nuevas deben coincidir");
		document.getElementById("clave_nueva_us").focus();
		document.getElementById("clave_nueva_us").value='';
		document.getElementById("clave_nueva_us_rep").value='';
	}	
	else
	{
		validar_longitud_claves(this);
	}
}//FIN DE FUNCION validar_claves
//funcion que valida los campos del formulario
function validacion_claves()
{
	if(($("#clave_anterior_us").val()==$("#clave_nueva_us").val())||
		($("#clave_anterior_us").val()==$("#clave_nueva_us_rep").val()))
	{
		mensajes2("Debe ingresar en el campo clave nueva una diferente a la clave anterior");
		return false;
	}	
	if($("#clave_anterior_us").val()=='')
	{
		mensajes2("Debe ingresar la clave anterior");
		return false;
	}
	else if($("#clave_nueva_us").val()=='')
	{
		mensajes2("Debe ingresar la clave nueva");
		return false;
	}
	else if($("#clave_nueva_us_rep").val()=='')
	{
		mensajes2("Debe repetir la clave nueva");
		return false;
	}
	else
	{
		return true;
	}	
}//FIN DE FUNCION validar_claves
//EVENTOS
$("#pie_pag").removeClass("contendor_pie_pagina2").addClass("contendor_pie_pagina");
$("#btn_guardar_tipo_us").click(function(){
	if(validacion_claves()==true)
	{
	////////////////////////////////////////////////////
	var data=$("#form_cambiar_clave").serialize();
	/////////////////////////////////////////////////////////////
	$.ajax ({
	                  url:"./controladores/controlador.cambiar_clave.php",
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
	                       ///alert(recordset);
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
	                        else if(recordset[0]=="no_existe")
	                        {
	                        	mensajes(20);//no se encontro al usuario con esa clave
	                        }
	                        else if(recordset[0]=="registro_exitoso")
	                        {
                           		mensajes(6);//operacion realizada con exito
	                        }
	                        else
	                        {
	                        	mensajes2(recordset);
	                        }	
							              
	                  }
		});
	////////////////////////////////////////////////////
	}	
});
</script>
<div id="form_1">	
	<form  class="form-horizontal" type="POST" name="form_cambiar_clave" id="form_cambiar_clave" role="form">	
		<fieldset>
		    <legend>
		        <h3>Cambiar clave</h3>
		    </legend>
    	</fieldset>
		<div class="form-group">
	    	<div class="col-lg-12">
        		<input type="password" name="clave_anterior_us" id="clave_anterior_us" placeholder="Clave actual" class="form-control" onKeyPress="return valida(event,this,17,10)" onBlur="valida2(this,17,10);validar_longitud_claves(this);">
        	</div>
		</div>	
		<div class="form-group">
        	<div class="col-lg-12">
        		<input type="password" name="clave_nueva_us" id="clave_nueva_us" placeholder="Clave nueva" class="form-control" onKeyPress="return valida(event,this,17,30)" onBlur="valida2(this,17,30);" onchange="validar_longitud_claves(this);">
        	</div>	
		</div>
		<div class="form-group">
			<div class="col-lg-12">
				<input type="password" name="clave_nueva_us_rep" id="clave_nueva_us_rep" placeholder="Repita la nueva clave" class="form-control" onKeyPress="return valida(event,this,17,30)" onBlur="valida2(this,17,30);validar_claves();">
			</div>
		</div>	
		<div class="col-lg-6">
			<button type="button" id="btn_guardar_tipo_us" name="btn_guardar_tipo_us" class="btn btn-info btn-form">Guardar</button>
		</div>
		<div class="col-lg-6">
			<button type="reset" id="btn_limpiar_tipo_us" name="btn_limpiar_tipo_us" class="btn btn-warning btn-form">Limpiar</button>
		</div>
	</form>
</div>
