<?php
session_start();
?>
<html>
<head>
<script type="text/javascript">
//eventos
$("#pie_pag").removeClass("contendor_pie_pagina2").addClass("contendor_pie_pagina");
$("#btn_tp_ev").click(function(){
	var cabecera="<b>Consulta emergente: Tipo de evaluaciones</b>";
	$("#myModalLabelconsulta").html(cabecera);
	//genero los campos de la tabla
	var cabacera_tabla="<tr><td><input type='text' name='f_eva' id='f_eva' placeholder='Filtro Tipo de evaluaciones' class='form-control input-sg' onblur='valida2(this,18,100);consultar_cuerpo_tabla_teva(0,5,0);' onKeyPress='filtrar_enter();return valida(event,this,18,100);'></td></tr>\
						<tr>\
							<td width='25%'><label>Tipo de evaluaciones</label></td>\
							<td width='25%'><label>Seleccione</label></td>\
						</tr>";
	$("#cabecera_consulta").html(cabacera_tabla);	
	//consultar cuerpo de la tabla
	consultar_cuerpo_tabla_teva(0,5,0);
});
$("#btn_guardar_tipo_evaluacion").click(function(){
if($("#desc_tipo_evaluacion").val!="")
{
///////////////////////////////////////////////////////////////////	
	var data=$("#form_tipo_evaluacion").serialize();
//////////////////////////////////////////////////////////////
		$.ajax ({
		              url:"./controladores/controlador.tipo_evaluacion.php",
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
		                    	$("#id_tipo_evaluacion").val("");
		                    }
		                    else if(recordset[0]=="registro_exitoso")
		                    {
		                    	mensajes(13);
		                    	$("#id_tipo_evaluacion").val(recordset[1]);
		                    }
		              }
		});
////////////////////////////////////////////////////////////////////////////
}else
{
	mensajes(5);//debe ingresar los campos
}
///////////////////////////////////////////////////////////////////////////
});
$("#btn_limpiar_tipo_us").click(function(){
	$("#id_tipo_evaluacion,#desc_tipo_evaluacion").val("");
});
//BLOQUE DE FUNCIONES
function filtrar_enter()
{
	if(event.which==13){
        consultar_cuerpo_tabla_teva(0,5,0);
   	}  
}
function consultar_cuerpo_tabla_teva(offset,limit,actual)
{
	var f_eva=$("#f_eva").val();
	data={
			f_eva:f_eva,
			offset:offset,
			limit:limit,
			actual:actual,
		 }
//////////////////////////////////////////////////////////////
	$.ajax ({
	          url:"./controladores/controlador.consultar_cuerpo_tipo_evaluacion.php",
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
////////////////////////////////////////////////////////////////////////
function consultar_tp_eva(id_tp_eva)
{
	var data={
				id_tp_eva:id_tp_eva
			  }
	$.ajax({
			url:"./controladores/controlador.consultar_tipo_evaluacion.php",
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
	          	$("#desc_tipo_evaluacion").val(recordset[0][1]);
	          	$("#id_tipo_evaluacion").val(recordset[0][0]);
              }	
	});		  
}
///////////////////////////////////////////////////////////////////////////
</script>
</head>	
<body>
<div id="form_1">	
	<form  class="form-horizontal" type="POST" name="form_tipo_evaluacion" id="form_tipo_evaluacion" role="form">	
		<fieldset>
		        <legend>
		            <h3>Tipo evaluaci&oacute;n</h3>
		        </legend>
		</fieldset>		
		<div class="form-group">	
			<div class="col-lg-10">
				<input type="text" name="desc_tipo_evaluacion" id="desc_tipo_evaluacion" placeholder="Tipo de evaluaci&oacute;n" class="form-control input-sg" onKeyPress='return valida(event,this,17,30)' onBlur='valida2(this,17,30);'>
			</div>
			<div class="col-lg-2">
				<button  id="btn_tp_ev" name="btn_tp_ev" title="Consultar tipo de evaluaciones" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_consulta"><span class="glyphicon glyphicon-search"></span>
				</button>
			</div>	
		</div>	
		<div class="col-lg-6">
			<button type="button" id="btn_guardar_tipo_evaluacion" name="btn_guardar_tipo_evaluacion" class="btn btn-info btn-form">Guardar</button>
		</div>
		<div class="col-lg-6">
			<button type="reset" id="btn_limpiar_tipo_evaluacion" name="btn_limpiar_tipo_evaluacion" class="btn btn-warning btn-form">Limpiar</button>
		</div>
		<input type="hidden" size="2" id="id_tipo_evaluacion" name="id_tipo_evaluacion">
	<form>
</div>
</body>
</html>