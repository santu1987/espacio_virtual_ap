<?php 
require_once("../controladores/controlador.estado.php");
if(isset($_GET["id_persona"])){ if($_GET["id_persona"]!=""){ $id_persona=$_GET["id_persona"];}else { $id_persona="";}  }else{ $id_persona="";}
?>
<html>
<head>
<script type="text/javascript">
var id_persona="<?php echo $id_persona;?>";
///////////////////////////////////
if(id_persona=="")
{
  consultar_us();
}else
{
  consultar_us2();
}
//////////////////////////////////
/////////BLOQUE DE EVENTOS
$("#pie_pag").removeClass("contendor_pie_pagina").addClass("contendor_pie_pagina2");
//btn guardar
$("#fecha_nac_us").datetimepicker({ 
    lang:'es',
    timepicker:false,
    format:'d-m-Y',
    formatDate:'Y-m-d',
});
$("#btn_guardar").click(function(){
////////validar
  if(validate_form()=="true")
  {  
       $('#select_nac_us').attr('disabled', false);
      var data=$("#form_reg_us").serialize();
      //////////////////////////////////////////////////////////////
      $.ajax ({
                      url:"./controladores/controlador.registrar_usuario.php",
                      data:data,
                      type:'POST',
                      cache: "false",
                      error: function(request,error) 
                      {
                          console.log(arguments);
                          mensajes(3);//error desconocido
                      },
                      success: function(html)
                      {
                            var recordset=$.parseJSON(html);
                            //alert(recordset);
                            if(recordset[0]=="guardo")
                            {
                                $("#id_persona").val(recordset[1]);
                                if($("#imagen_usuario").val()!="")
                                {
                                    cargar_archivo("guardo");                             
                                }else
                                {
                                    mensajes(6);//operacion realizada con exito
                                }  
                            }
                            else if(recordset[0]=="error")
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
                      }
              });
    //////////
  }
  //fin del if de validacion  
});
/////////BLOQUE DE FUNCIONES  
function validate_form()
{
  if(fecha_edad($("#fecha_nac_us").val())<16)
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> La persona debe ser mayor a 15 a&ntilde;os");
    return "false";
  }else  
  if($("#select_nac_us").val()=="-1")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar una Nacionalidad");
    return "false";
  }  
  if($("#nombre_us").val()=="")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar nombres y apellidos de usuarios");
    return "false";
  }else
  if($("#n_cedula_us").val()=="")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar n&uacute;mero de c&eacute;dula");
    return "false";
  }else
  if($("#select_estado").val()=="-1")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar un estado");
    return "false";
  }
  else
  if($("#select_municipio").val()=="-1")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar un municipio");
    return "false";
  }
  else
  if($("#select_parroquia").val()=="-1")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar una parroquia");
    return "false";
  }
  else
  if($("#fecha_nac_us").val()=="0")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar fecha de nacimiento");
    return "false";
  }    
  if($("#tlf_us").val()=="")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar n&uacute;mero de tel&eacute;fono");
    return "false";
  }  
  return "true";
}

//funcion para consultar los datos de usuarios
function consultar_us()
{
var data=$("#form_reg_us").serialize();
/////////////////////////////////////////////////////////////
  $.ajax ({
                  url:"./controladores/controlador.consultar_usuario.php",
                  data:data,
                  type:'POST',
                  cache: "false",
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
                        else
                        {
                          $("#id_persona").val(recordset[0][0]);
                          if(recordset[0][1]=='V')
                            $("#select_nac_us").val('V');
                          else if(recordset[0][1]=='E')
                            $("#select_nac_us").val('E');
                          $("#nombre_us").val(recordset[0][3]);
                          $("#n_cedula_us").val(recordset[0][2]);
                          $("#fecha_nac_us").val(recordset[0][7]);
                          $("#tlf_us").val(recordset[0][8]);
                          $("#select_estado").val(recordset[0][4]);
                          $("#sexo"+recordset[0][9]).prop("checked","checked");
                          cargar_municipio2(recordset[0][4],recordset[0][5]);
                          cargar_parroquia2(recordset[0][5],recordset[0][6]);
                        }
                        
                  }
    });
///////////////////////////////////////////////////////////////////////////////
}
//funcion para consultar los datos de usuarios
function consultar_us2()
{
var id_persona="<?php echo $id_persona;?>";
var data={id_persona:id_persona};
/////////////////////////////////////////////////////////////
  $.ajax ({
                  url:"./controladores/controlador.consultar_usuario2.php",
                  data:data,
                  type:'POST',
                  cache: "false",
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
                        else
                        {
                          $("#id_persona").val(recordset[0][0]);
                          if(recordset[0][1]=='V')
                            $("#select_nac_us").val('V');
                          else if(recordset[0][1]=='E')
                            $("#select_nac_us").val('E');
                          $("#nombre_us").val(recordset[0][3]);
                          $("#n_cedula_us").val(recordset[0][2]);
                          $("#fecha_nac_us").val(recordset[0][7]);
                          $("#tlf_us").val(recordset[0][8]);
                          $("#select_estado").val(recordset[0][4]);
                          cargar_municipio2(recordset[0][4],recordset[0][5]);
                          cargar_parroquia2(recordset[0][5],recordset[0][6]);
                          $("#sexo"+recordset[0][9]).prop("checked","checked");
                        }
                        
                  }
    });
///////////////////////////////////////////////////////////////////////////////
}
//funcion de para cargar el municipio dependiendo del estado...
function cargar_municipio(valor)
{
  $("#select_municipio").load('controladores/controlador.municipio.php?id='+valor);
}
//funcion de para cargar el municipio dependiendo del estado...
function cargar_municipio2(valor,valor2)
{ 
    $("#select_municipio").load('controladores/controlador.municipio.php?id='+valor+'&idm='+valor2);
}
//funcion para cargar parroquia...
function cargar_parroquia(valor)
{
  $("#select_parroquia").load('controladores/controlador.parroquia.php?id='+valor);
}
//funcion para cargar parroquia en consultas...
function cargar_parroquia2(valor,valor2) 
{
  $("#select_parroquia").load('controladores/controlador.parroquia.php?id='+valor+'&idp='+valor2);
}
//funcion para cargar imagenes
function cargar_archivo(valor)
{
      var formData = new FormData($("#form_reg_us")[0]);
      var message = "";   
      //hacemos la petición ajax  
      $.ajax({
                url: './controladores/controlador.cargar_imagen_us.php',  
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
                      mensajes2("<i class='fa fa-check fa-2x' style='color:#16E91D'></i> Operaci&oacute;n realizada de manera exitosa, debe esperar unos minutos para que su imagen de avatar sea visible");
                      setTimeout(function(){document.forms.form_reg_us.action='./inicio.php';document.forms.form_reg_us.submit();},4000);
                    }else
                    if(recordset=="error_tipo_archivo")
                    {
                        mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Error- Carga imag&eacute;n: Solo puede subir imagenes en formato jpg, de tama&ntilde;o menor a 2 megas");
                    }
                    if(recordset=="error_no_carga")
                    {
                        mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Error- Carga imag&eacute;n");
                    }  
                }
      });
}
/////////////////////////////////////////////////////////////////////////////////////////////////
</script>

</head>
<body>
<div id="form_1"> 
<form  class="form-horizontal" type="POST" name="form_reg_us" id="form_reg_us"> 
    <fieldset>
        <legend>
            <h3>Actualizar datos perfil usuario</h3>
        </legend>
    </fieldset>
    <div class="form-group">
      	<div class="col-lg-6">
          <select name="select_nac_us" id="select_nac_us" class="form-control" disabled="disabled">
            <option id="-1" value="-1">[Nacionalidad]</option>
            <option id="0" value="V">V</option>
            <option id="1" value="E">E</option> 
          </select>
        </div>
        <div class="col-lg-6">
           <input type="text" name="n_cedula_us" id="n_cedula_us" placeholder="C&eacute;dula" onKeyPress="return valida(event,this,10,11)" onBlur="valida2(this,10,11);" class="form-control" readonly>
        </div>
    </div>		
  <div class="form-group">
  	<div class="col-lg-12">
  		<input type="text" name="nombre_us" id="nombre_us" class="form-control" onKeyPress="return valida(event,this,19,50)" onBlur="valida2(this,19,50);" placeholder="Nombres y Apellidos">
    </div>
  </div>	
  <div class="form-group">
    <label style="margin-left: 5%;">Sexo:</label>
        <input type="radio" name="sexo_us" id="sexo2" value="2" checked>F
        <input type="radio" name="sexo_us" id="sexo1" value="1">M
  </div>	
	<div class="form-group">	
		<div class="col-lg-12">
			<select name="select_estado" id="select_estado" class="form-control" onchange="cargar_municipio(this.value);">
			 <?php echo $option_estado; ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-12">
			<select name="select_municipio" id="select_municipio" class="form-control" onchange="cargar_parroquia(this.value);">
				<option value="-1">--SELECCIONE--</option>
			</select>
		</div>
	</div>
	<div class="form-group">	
		<div class="col-lg-12">
			<select name="select_parroquia" id="select_parroquia" class="form-control">
				<option value="-1">--SELECCIONE--</option>
			</select>
		</div>	
	</div>
	<div class="form-group">
		<div class='col-lg-12'>	
				<input type="text"  name="fecha_nac_us" id="fecha_nac_us" class="form-control" placeholder="Fecha de nacimiento" onkeyup="this.value=formateafecha(this.value);">
    </div>	
	</div>	
	<div class="form-group">
		<div class="col-lg-12">
			<input type="text" name="tlf_us" id="tlf_us" class="form-control" placeholder="Tlf-Movil " onKeyPress="return valida(event,this,10,11)" onBlur="valida2(this,10,11);" onchange="longitud(this,12,11);formato_tlf(this);">
    </div>	
	</div>
  <div class="form-group">
          <label for="imagen_usuario" class="control-label col-lg-4">Cargar foto:</label>
      <div class="col-lg-8">  
          <input type="file" class="btn btn-primary" name="imagen_usuario" id="imagen_usuario"/>
      </div>
  </div>
      <legend></legend>
      <div class="col-lg-12">
        <button type="button" id="btn_guardar" name="btn_guardar" class="btn btn-info btn-form">Guardar</button>
      </div>
      <input type="hidden" size="2" id="id_persona" name="id_persona"> 
</form>
</div>
</body>
</html>