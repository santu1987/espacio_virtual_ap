<?php
session_start();
require_once("../controladores/controlador.tipo_estudio.php");
if(isset($_GET["id_aula"]))
{
  $id_aula=$_GET["id_aula"];
}else
{
  $id_aula='';
}
$fecha=date('Y-m-d');
?>
<html>
<head>
<script type="text/javascript">
//eventos
var fecha="<?php echo $fecha; ?>";
$("#pie_pag").removeClass("contendor_pie_pagina").addClass("contendor_pie_pagina2");
$("#fecha_activacion_eva").datetimepicker({ 
    lang:'es',
    timepicker:false,
    format:'d-m-Y',
    formatDate:'Y-m-d',
    minDate:fecha
});
$("#hora_activacion_eva").datetimepicker({
  datepicker:false,
  format:'H:i',
});
var id_aula_c="<?php echo $id_aula;?>";
cargar_consulta(id_aula_c);
//guardar eva
$("#btn_guardar_eva").click(function(){
//alert(validar_formulario_eva());
//////////////////////////////////////////////////////////////////////////////////////////////
if(validar_formulario_eva()==true)
{
var data=$("#form_conf_eva").serialize();
/////////////////////////////////////////////////////////////
	$.ajax ({
                  url:"./controladores/controlador.registrar_eva.php",
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
                        else if(recordset[0]="registro_exitoso")
                        {
                        	$("#id_eva").val(recordset[1]);
                          //mensajes(6);
                          if($("#imagen_eva").val()!="")
                          {
                            cargar_archivo("registro_exitoso");                             
                          }else
                          {
                            mensaje_ir_unidades();//funcion que verifica si el usuario desea ir a la carga de unidades
                          }  
                        }
						              
                  }
	});
///////////////////////////////////////////////////////////////////////////////
}
///////////////////////////////////////////////////////////////////////////////
});
$("#btn_con_eva").click(function(){
///////////////////////////////////////
  var cabecera="<b>Consulta emergente: Configuraci&oacute;n de E.V.A</b>";
  $("#myModalLabelconsulta").html(cabecera);
  //genero los campos de la tabla
  var cabacera_tabla="<tr><td><input type='text' name='f_ceva' id='f_ceva'  placeholder='Filtro por T&iacute;tulo' class='form-control input-sg' onblur='valida2(this,18,100);consultar_cuerpo_tabla_ceva(0,5,0);' onKeyPress='filtrar_enter();return valida(event,this,18,100);' style='width:300px'></td>\
                      <td><input type='text' name='f_ceva2' id='f_ceva2' placeholder='Filtro por Tipo' class='form-control input-sg' onblur='valida2(this,18,100);consultar_cuerpo_tabla_ceva(0,5,0);' onKeyPress='filtrar_enter();return valida(event,this,18,100);'></td></tr>\
            <tr>\
              <td width='25%'><label>T&iacute;tulo</label></td>\
              <td width='25%'><label>Tipo</label></td>\
              <td width='25%'><label>Contenidos</label></td>\
              <td width='25%'><label>Seleccione</label></td>\
            </tr>";
  $("#cabecera_consulta").html(cabacera_tabla); 
  //consultar cuerpo de la tabla
  consultar_cuerpo_tabla_ceva(0,5,0);
///////////////////////////////////////  
});
//funciones
function mensaje_ir_unidades()
{
  bootbox.confirm("<i class='fa fa-check fa-2x' style='color:#16E91D'></i> Operación realizada de manera exitosa, ¿Desea registrar las unidades de contenido a esta aula ?", function(result) 
  { if(result)
    {
      var id_eva=$("#id_eva").val();
      $("#program_body").load("./vistas/vista.registrar_contenido.php");
      setTimeout(function(){$("#aula_virtual_facilitador").val(id_eva);},1000);
      setTimeout(function(){cargar_unidades(id_eva,0);},1000);
    }
    else
    {
      btn_limpiar_eva.click();//limpio los campos 
    } 
    subir_titulo();
  });
}
function filtrar_enter(){
   if(event.which==13){
        consultar_cuerpo_tabla_ceva(0,5,0);
   }     
}
function cargar_consulta(id_aula_c)
{
  if(id_aula_c!="")
  {
    conf_eva_selec(id_aula_c);
  }
}
function cargar_archivo(valor)
{
      var formData = new FormData($("#form_conf_eva")[0]);
      var message = "";   
      //hacemos la petición ajax  
      $.ajax({
                url: './controladores/controlador.cargar_imagen_eva.php',  
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
                    if((valor=="registro_exitoso")&&(recordset=="archivo_cargado"))
                    {
                        mensaje_ir_unidades();//funcion que verifica si el usuario desea ir a la carga de unidades
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

//para el modal de consulta
function consultar_cuerpo_tabla_ceva(offset,limit,actual)
{
  var f_ceva=$("#f_ceva").val();
  var f_ceva2=$("#f_ceva2").val();
  data={
      f_ceva:f_ceva,
      f_ceva2:f_ceva2,
      offset:offset,
      limit:limit,
      actual:actual,
     }
  //////////////////////////////////////////////////////////////
  $.ajax ({
            url:"./controladores/controlador.consultar_cuerpo_ceva.php",
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
function conf_eva_selec(id_eva)
{
  var data={
              id_eva:id_eva
         }
  $.ajax({
      url:"./controladores/controlador.consultar_eva.php",
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
              $("#id_eva").val(recordset[0][0]);
              $("#titulo_eva").val(recordset[0][1]);
              $("#tipo_formacion").val(recordset[0][2]);
              $("#introduccion_eva").val(recordset[0][3]);
              $("#objetivos_eva").val(recordset[0][4]);
              $("#resumen_eva").val(recordset[0][5]);
              $("#unidades_eva").val(recordset[0][6]);
              if(recordset[0][7]==1){$("#opcion_evaluada1").prop("checked","checked");}else{$("#opcion_evaluada2").prop("checked","checked");}
              $("#fecha_activacion_eva").val(recordset[0][8]);
              //alert(recordset[0][9]);
              document.getElementById("hora_activacion_eva").value=recordset[0][9];
            } 
  });     
}

function validar_formulario_eva()
{
  var f = new Date();
  var fecha_actual;
  fecha_actual=f.getDate() + "-" + (f.getMonth() +1) + "-" + f.getFullYear();
  //alert(fecha_actual+"-"+$("#fecha_activacion_eva").val());
  if(compararFecha($("#fecha_activacion_eva").val(),fecha_actual)==false)
  {
       mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> La fecha no puede ser menor a la actual");
       return false;    
  }  
  if($("#titulo_eva").val()=="")
  {
       mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar t&iacute;tulo del aula virtual");
       return false; 
  }  
  if($("#tipo_formacion").val()==0)
  {
       mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe seleccionar tipo de formación");
       return false;  
  }
  else
  if($("#introduccion_eva").val()=="")
  {
       mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar introducci&oacute;n de aula virtual a crear");
       return false;  
  }
  else
  if($("#objetivos_eva").val()=="")
  {
       mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar objetivos del aula virtual a crear");
       return false;  
  }
  else
  if($("#resumen_eva").val()=="")
  {
       mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar resumen del aula virtual a crear");
       return false;  
  }
  else
  if($("#unidades_eva").val()==0)  
  {
       mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar unidades del aula virtual a crear");
       return false;  
  }
  else
  if($("#fecha_activacion_eva").val()=="")
  {
       mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Debe ingresar la fecha de activaci&oacute;n");
       return false;  
  }else
  {
    return true;
  }  
}
</script>
</head>
<body>
	<div id="form_2">
  <form  class="form-horizontal" type="POST" name="form_conf_eva" id="form_conf_eva" role="form">
      <fieldset>
        <legend>
            <h3>Configuraci&oacute;n: Espacio Virtual de Aprendizaje E.V.A</h3>
        </legend>
      </fieldset>
			<div class="form-group">
					<div class="col-lg-10">
            <input type="text" name="titulo_eva" id="titulo_eva" class="form-control" onKeyPress="return valida(event,this,18,100)" onBlur="valida2(this,18,100);" placeholder="T&iacute;tulo del E.V.A">
          </div>
          <div class="col-lg-2">
              <button  id="btn_con_eva" name="btn_con_eva" title="Consultar configuraci&oacute;n de E.V.A" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_consulta"><span class="glyphicon glyphicon-search"></span>
              </button>
           </div>
      </div>
     <div class="form-group">
          <div class="col-lg-12">
            <select id="tipo_formacion" name="tipo_formacion" class="form-control">
              <?php echo $option_estudio; ?>
            </select>
          </div> 
      </div>
          <div class="form-group">
            <div class="col-lg-12">
                <span class="help-block">1- Introducci&oacute;n : </span>
                <textarea id="introduccion_eva" name="introduccion_eva" class="form-control" rows="5" onKeyPress='return valida(event,this,18,600)' onBlur='valida2(this,18,600);' placeholder="Ingrese introducci&oacute;n, ejm: El siguiente espacio de aprendizaje consiste en...(M&aacute;x-600 Caracteres)"></textarea>
            </div>
          </div>  
         	<div class="form-group ">
            <div class="col-lg-12">
                <span class="help-block">2- Objetivos : </span>
                <textarea id="objetivos_eva" name="objetivos_eva"  class="form-control" rows="5" onKeyPress='return valida(event,this,18,1000)' onBlur='valida2(this,18,1000);' placeholder="Ingrese objetivos, ejem: Entre los objetivos del EVA resaltan ..."></textarea>
            </div>
          </div>  
          <div class="form-group ">
            <div class="col-lg-12">
              <span class="help-block">3- Resum&eacute;n : </span>
              <textarea id="resumen_eva" name="resumen_eva" class="form-control" rows="5" onKeyPress='return valida(event,this,18,10000)' onBlur='valida2(this,18,10000);' placeholder="Ingrese el resum&eacute;n del espacio virtual, ejm: El EVA permitirá lograr un mayor aprendizaje en cuanto a.."></textarea>
            </div>
          </div>
					<div class="form-group">
						<div class="col-sm-12">
              <select id="unidades_eva" name="unidades_eva" class="form-control">
                  <option value="0" id="0">[Cantidad de Contenidos]</option>
                  <option value="1" id="1">1 Contenido</option>
                  <option value="2" id="2">2 Contenido</option>
                  <option value="3" id="3">3 Contenido</option>
                  <option value="4" id="4">4 Contenido</option>
                  <option value="5" id="5">5 Contenido</option>
              </select>
            </div>
				  </div>
          <div class="form-group">
            <div class="col-sm-6">
                <input type="text" name="fecha_activacion_eva" id="fecha_activacion_eva" class="form-control input-sg"  onkeyup="this.value=formateafecha(this.value);" placeholder="Fecha activaci&oacute;n: dd/mm/aaaa">
            </div>
            <div class="col-sm-6">
                <input type="hidden" readonly id="hora_activacion_eva" name="hora_activacion_eva"  class="form-control input-sg" placeholder="Hora activaci&oacute;n: 00:00">
            </div>
          </div>
          <div class="form-group">
              <label for="imagen_eva" class="control-label col-sm-4">Cargar Imagen:</label>
              <div class="col-sm-10 texto2">  
                  <input type="file" class="btn btn-primary" name="imagen_eva" id="imagen_eva"/>
              </div>
          </div>
					<div class="form-group" style="display:none">
            <label class="col-sm-4 control-label conf_label">E.V.A Evaluada:</label>
                <div id="form_group">
                  <input type="radio" name="opcion_evaluada" id="opcion_evaluada1'" value="1" checked>SI
                  <input type="radio" name="opcion_evaluada" id="opcion_evaluada2'" value="0">NO
                </div>
          </div>
        <legend></legend>  
 				<div class="col-lg-6">
						 <button type="button" id="btn_guardar_eva" name="btn_guardar_eva" class="btn btn-info btn-form">Guardar</button>
	      </div>
        <div class="col-lg-6">    
             <button type="reset" id="btn_limpiar_eva" name="btn_limpiar_eva" class="btn btn-warning btn-form">Limpiar</button>
				</div>
		 		<input type="hidden" size="2" id="id_eva" name="id_eva"> 
  </form>	
  </div>
  <div id="contenedor_pie" class="form-group" style="position:relative;top:170%;">
      <div class="col-lg-12">
        <div>
          <h1 id="" style="display:none"></h1>
        </div>
      </div>  
  </div>    
</body>
</html>