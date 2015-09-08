<?php
session_start();
require('../controladores/controlador.consultar_tipo_us.php');
if(isset($_GET["nacionalidad"])){ if($_GET["nacionalidad"]!=""){ $nacionalidad=$_GET["nacionalidad"];}else { $nacionalidad="";}  }else{ $nacionalidad="";}
if(isset($_GET["cedula"])){ if($_GET["cedula"]!=""){ $cedula=$_GET["cedula"];}else { $cedula="";}  }else{ $cedula="";}
?>
<head>
<script type="text/javascript">
/////BLOQUE DE FUNCIONES
function carga_inicial()
{
	var nacionalidad="<?php echo $nacionalidad;?>";
	var cedula="<?php echo $cedula; ?>";
	if(nacionalidad!=""){ $("#nacionalidad_perfil").val(nacionalidad);}
	if(cedula!=""){ $("#n_cedula_us").val(cedula);}
	if((nacionalidad)&&(cedula!=""))
	{
		$("#b_consulta_perfil").click();
	}	
}
function ver_img(valor)
{
	if((valor==1)||(valor==8))
	{
      document.getElementById("div_img").style.display="block";
	}
	else
	{
      document.getElementById("div_img").style.display="none";
	}	
}
function validar_consulta_perfil()
{
	
	if($("#nacionalidad_perfil").val()==-1)
	{
		mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar una nacionalidad");
		return false;	
	}
	else	
	if(document.getElementById("n_cedula_us").value=="")
	{
		mensajes(1);
		return false;
	}
	else
	{
		return true;
	}	
}
//
function validar_guardar()
{
	
	if($("#nacionalidad_perfil").val()==-1)
	{
		mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar una nacionalidad");
		return false;	
	}
	else	
	if(document.getElementById("n_cedula_us").value=="")
	{
		mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar n° c&eacute;dula");
		return false;
	}
	else
	if(document.getElementById("select_perfil").value=="0")
	{
		mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar un perfil");
		return false;
	}
	else
	if(document.getElementById("id_persona_p").value=="")
	{
		mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe consultar a un usuario");
		return false;
	}
	return true;	
}
function cargar_archivo_firma(valor)
{
	  var formData = new FormData($("#form_perfil_us")[0]);
      var message = "";   
      //hacemos la petición ajax  
      $.ajax({
                url: './controladores/controlador.cargar_imagen_firma.php',  
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
                    var recordset=data;
                    //alert(recordset);alert(valor);
                    if((valor=="guardo")&&(recordset=="archivo_cargado"))
                    {
                      mensajes2("Operaci&oacute;n realizada de manera exitosa");
                    }else
                    if(recordset=="error_tipo_archivo")
                    {
                        mensajes2("Error- Carga imag&eacute;n: Solo puede subir imagenes en formato jpg, de tama&ntilde;o menor a 2 megas");
                    }
                    if(recordset=="error_no_carga")
                    {
                        mensajes2("Error- Carga imag&eacute;n");
                    }  
                }
      });
}
////BLOQUE DE EVENTOS
carga_inicial();
$("#pie_pag").removeClass("contendor_pie_pagina2").addClass("contendor_pie_pagina");
//consulta de usuario segun cedula....
$("#b_consulta_perfil").click(function(){
if(validar_consulta_perfil()==true)
{	
///////////////////////////////////////////////////
var data=$("#form_perfil_us").serialize();
		//////////////////////////////////////////////////////////////
		$.ajax ({
		              url:"./controladores/controlador.consultar_usuario_perfil.php",
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
		                    else if(recordset=="")
		                    {
		                    	mensajes(12);//usuario no encontrado...
		                    	$("#id_persona_perfil").val("");
		                    	$("#nombres_perfil").text("");
		                    }
		                    else
		                    {
		                    	$("#id_persona_p").val(recordset[0][0]);
		                    	$("#id_us_p").val(recordset[0][1]);
		                    	$("#nombres_perfil").text("Usuario: "+recordset[0][2]);
		                    	$("#id_perfil_us").val(recordset[0][3]);
		                    	$("#select_perfil").val(recordset[0][4]);
		                    	ver_img(recordset[0][4]);
							}
		              }
		});
///////////////////////////////////////////////////
}
//
});
//////////////////////////////////////////////////
////registrar permisos...
$("#btn_guardar_per").click(function(){
if(validar_guardar()==true)
{
var data=$("#form_perfil_us").serialize();
		//////////////////////////////////////////////////////////////
		$.ajax ({
		              url:"./controladores/controlador.registrar_usuario_perfil.php",
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
		                    else if(recordset=="")
		                    {
		                    	mensajes(12);//usuario no encontrado...
		                    	$("#id_persona_perfil").val("");
		                    	$("#nombres_perfil").text("");
		                    }
		                    else
		                    {
		                    	//mensajes(13);
		                    	$("#id_perfil_us").val(recordset[1]);
		                    	if($("#imagen_firma").val()!="")
                                {
                                  cargar_archivo_firma("guardo");                            	
                                }else
                                {
                                  mensajes(6);//operacion realizada con exito
                                }
		                    }
		              }
		});
///////////////////////////////////////////////////
}
});
//////////////////////////////////////////////////
</script>
</head>
<body>
	<div id="form_1">
	<form class="form-horizontal" type="POST" name="form_perfil_us" id="form_perfil_us">
		<fieldset>
		    <legend>
		        <h3>Asignar perfil usuario</h3>
		    </legend>
    	</fieldset>
			<div class="form-group">
		      	<div class="col-lg-4">
					<select id="nacionalidad_perfil" name="nacionalidad_perfil" class="form-control">
								<option id='-1' value='-1'>[Nacionalidad]</option>
									<option id="0" value="V"  >V</option>
								<option id="1" value="E">E</option>	
					</select>
				</div>
				<div class="col-lg-6">
					<input type="text" name="n_cedula_us" id="n_cedula_us" placeholder="Ingrese cédula, ejm: 18765456" onKeyPress="return valida(event,this,10,8)" onBlur="valida2(this,10,8);" class="form-control input-sg">
				</div>
				<div class="col-lg-2">
					<button type="button" id="b_consulta_perfil" name="b_consulta_perfil" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
				</div>	
			</div>		
			<div class="form-group">
				<div id="nombres_perfil" class="col-lg-12">

				</div>
			</div>
			<div class="form-group">	
				<div class="col-lg-12">
					<select name="select_perfil" id="select_perfil" class="form-control" onchange="ver_img(this.value);">
					<?php echo $opcion_tp_us;?>
					</select>
				</div>
			</div>			
			<div class="form-group topit4" id="div_img" style="display:none">
			      <label for="imagen_firma" class="control-label col-lg-3">Cargar firma:</label>
			  <div class="col-lg-8">  
			      <input type="file" class="btn btn-primary" name="imagen_firma" id="imagen_firma"/>
			  </div>
			</div>
			<legend></legend>
			<div class="col-lg-6">
				<button type="button" id="btn_guardar_per" name="btn_guardar_per" class="btn btn-info  btn-form">Guardar</button>
		    </div>
		    <div class="col-lg-6">    
		        <button type="reset" id="btn_limpiar_per" name="btn_limpiar_per" class="btn btn-warning  btn-form">Limpiar</button>
			</div>
		<input type="hidden" size="2" id="id_persona_p" name="id_persona_p">
		<input type="hidden" size="2" id="id_us_p" name="id_us_p"> 
		<input type="hidden" size="2" id="id_perfil_us" name="id_perfil_us"> 
	</div>
	</form>
	</div>
</body>