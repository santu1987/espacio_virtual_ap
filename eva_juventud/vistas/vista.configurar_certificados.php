<?php
session_start();
include("../controladores/controlador.aula_virtual.php");
?>
<html>
<head>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#pie_pag").removeClass("contendor_pie_pagina2").addClass("contendor_pie_pagina");
$("#btn_registrar_cert").click(function(){
if(validar_campos()==true)
{ 
///////////////////////////////////////////////////////////
	  var formData = new FormData($("#form_certificados")[0]);
    var message = "";   
      //hacemos la petición ajax  
      $.ajax({
                url: './controladores/controlador.configurar_certificado.php',  
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
                    //alert(recordset);
                    if(recordset[0]=="archivo_cargado")
                    {
                       mensajes2("Operaci&oacute;n realizada de manera exitosa");
                       $("#id_cert").val(recordset[1]);
                    }else
                    if(recordset[0]=="error_tipo_archivo")
                    {
                        mensajes2("Error- Carga imag&eacute;n: Solo puede subir imagenes en formato jpg, de tama&ntilde;o menor a 2 megas");
                    }
                    else
                    {
                        mensajes2(recordset);
                    }  
                }
      });
////////////////////////////////////////////////////////////////////////////
}
//////////////////////////////////////////////////////////////////////////
});
$("#btn_ver_pdf").click(function(){
if($("#aula_virtual_evaluacion").val()!="-1")
{  
 var aula=$("#aula_virtual_evaluacion").val();
 var data={aula:aula};
 $.ajax({
          url:"./controladores/controlador.consultar_certificado.php",
          data:data,
          type:"POST",
          cache:false,
          error: function()
          {
              console.log(arguments);
              mensajes(3);
          },
          success: function(data)
          {
              var recordset=$.parseJSON(data);
              //alert(recordset);
              if(recordset[0]=="error")
              {
                mensajes(7);//error en base de datos
              }else if(recordset=="")
              {
                  mensajes(33);//no se ha configurado el certificado
              }
              else
              {
                 $("#form_certificados").attr("action","./controladores/controlador.formato_certificado.php");
                 $("#form_certificados").submit(); 
              }
              
          }      
 });
}else
{
  mensajes(34);//debe selecciona un aula
} 
});
//BLOQUE DE FUNCIONES
function validar_campos()
{
  if($("#aula_virtual_evaluacion").val()=="-1")
  {
      mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar un espacio virtual ya creado");
      return false;
  }else
  if($("#logo_inst").val()=="")
  {
      mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar imagen de logo");
      return false;
  }else
  if($("#img_fondo").val()=="")
  {
      mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar imagen de fondo");
      return false;
  }else
  return true;  
}
function ver_certificado()
{
   var id=$("#id_cert").val();
   var id_aula=$("#aula_virtual_evaluacion").val();
    $("#form_certificados").attr("action","./controladores/controlador.formato_certificado.php");
    $("#form_certificados").submit();
}
</script>
</head>
<body>
<div id="form_1">	
	<form class="form-horizontal"  method="POST" name="form_certificados" id="form_certificados" target="_blank">
		<fieldset>
        <legend>
            <h3>Configurar certificado</h3>
        </legend>
    </fieldset>
		<div class="form-group">
			<div class="col-sm-12">
				<select name="aula_virtual_evaluacion" id="aula_virtual_evaluacion" class="form-control input-sg">
					<?php echo $opcion_aula_virtual;?>
				</select>
			</div>	
		</div>
		<div class="form-group">
	          <label for="logo_inst" class="control-label col-sm-2">Logo institucional:</label>
		        <div class="col-sm-10 texto2">  
		          <input type="file" class="btn btn-warning" name="logo_inst" id="logo_inst"/>
		        </div>
  		</div>
  		<div class="form-group">
	          <label for="img_fondo" class="control-label col-sm-2">Fondo certificado:</label>
		        <div class="col-sm-10 texto2">  
		          <input type="file" class="btn btn-primary" name="img_fondo" id="img_fondo"/>
		        </div>
  		</div>
      <div class="col-lg-6">	
        <button type="button" class="btn btn-info btn-form" name="btn_registrar_cert" id="btn_registrar_cert">Guardar</button>
			</div>
      <div class="col-lg-6">	
        <button type="button" class="btn btn-warning btn-form" name="btn_ver_pdf" id="btn_ver_pdf">Generar PDF</button>
    	</div>
      <div id="botonera2" class="col-lg-12">
        <!-- -->
      </div> 
		<input type="hidden" name="id_cert" id="id_cert" size="2">
	</form>
</div>
</body>
</html>