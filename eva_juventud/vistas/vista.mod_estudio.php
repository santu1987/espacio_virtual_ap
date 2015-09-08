<?php
session_start();
?>
<html>
<head>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#pie_pag").removeClass("contendor_pie_pagina2").addClass("contendor_pie_pagina");
//
$("#btn_tp_mod_estudio").click(function(){
	var cabecera="<b>Consulta emergente: Modalidad de estudio</b>";
	$("#myModalLabelconsulta").html(cabecera);
	//genero los campos de la tabla
	var cabacera_tabla="<tr><td><input type='text' name='f_mod' id='f_mod' placeholder='Filtro modalidad de estudio' class='form-control input-sg'  onblur='valida2(this,18,100);consultar_cuerpo_tabla_modalidad(0,5,0);' onKeyPress='filtrar_enter();return valida(event,this,18,100);'></td></tr>\
						<tr>\
							<td width='25%'><label>Modalidad de estudio</label></td>\
							<td width='25%'><label>Seleccione</label></td>\
						</tr>";
	$("#cabecera_consulta").html(cabacera_tabla);	
	//consultar cuerpo de la tabla
	consultar_cuerpo_tabla_modalidad(0,5,0);					
});
//
$("#btn_guardar_tipo").click(function(){
if($("#modalidad_tipo_estudio").val()!="")
{	
	var data=$("#form_tipo_mod").serialize();
	//////////////////////////////////////////////////////////////
		$.ajax ({
		              url:"./controladores/controlador.registrar_mod_estudio.php",
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
		                    	$("#id_modalidad").val("");
		                    }
		                    else if(recordset[0]=="registro_exitoso")
		                    {
		                    	mensajes(13);
		                    	$("#id_modalidad").val(recordset[1]);
		                    }
		              }
		});
}
else
{
	mensajes(5);
}
////////////////////////////////////////////////////////////////////////////
});
$("#btn_limpiar_per").click(function(){
	$("#id_modalidad,#modalidad_tipo_estudio").val("");
});
//BLOQUE DE FUNCIONES
function filtrar_enter()
{
	if(event.which==13){
        consultar_cuerpo_tabla_modalidad(0,5,0);
   	}  
}
function consultar_cuerpo_tabla_modalidad(offset,limit,actual)
{
	var f_mod=$("#f_mod").val();
	data={
			f_mod:f_mod,
			offset:offset,
			limit:limit,
			actual:actual,
		 }
	//////////////////////////////////////////////////////////////
	$.ajax ({
	          url:"./controladores/controlador.consultar_cuerpo_modalidad.php",
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
function modalidad_estc(id_modalidad)
{
	var data={
				id_modalidad:id_modalidad
			  }
	$.ajax({
			url:"./controladores/controlador.consultar_modalidad.php",
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
	          	//alert(recordset[0][0]);
	          	$('#myModal_consulta').modal('hide');//apago el modal
	          	$("#modalidad_tipo_estudio").val(recordset[0][1]);
	          	$("#id_modalidad").val(recordset[0][0]);
              }	
	});		  
}
/////////////////////////////////////////////////////////////////////////////////
</script>
</head>	
<body>
<div id="form_1">	
<form  class="form-horizontal" type="POST" name="form_tipo_mod" id="form_tipo_mod" role="form">	
	<fieldset>
        <legend>
            <h3>Modalidades de estudio</h3>
        </legend>
    </fieldset>
		<div class="form-group">
			<div class="col-sm-10">
				<input type="text" name="modalidad_tipo_estudio" id="modalidad_tipo_estudio" placeholder="Tipo de modalidad de estudio" class="form-control input-sg" onKeyPress='return valida(event,this,17,30)' onBlur='valida2(this,17,30);'>
			</div>
			<div class="col-lg-2">
				<button  id="btn_tp_mod_estudio" name="btn_tp_mod_estudio" title="Consultar modalidad de estudio" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_consulta"><span class="glyphicon glyphicon-search"></span>
				</button>
			</div>	
		</div>	
		<div class="col-lg-6">
					<button type="button" id="btn_guardar_tipo" name="btn_guardar_tipo" class="btn btn-info btn-form">Guardar</button>
		</div>
		<div class="col-lg-6">
			        <button type="reset" id="btn_limpiar_per" name="btn_limpiar_per" class="btn btn-warning btn-form">Limpiar</button>
		</div>	
		<input type="hidden" size="2" id="id_modalidad" name="id_modalidad">
</div>
<form>
</body>
</html>