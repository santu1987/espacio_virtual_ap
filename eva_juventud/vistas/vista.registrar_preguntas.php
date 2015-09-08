<?php
session_start();
include("../controladores/controlador.aula_virtual.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#btn_guardar_preguntas").click(function(){
	////////////////////////////////////////////////////
	var data=$("#form_preguntas_ev").serialize();
	/////////////////////////////////////////////////////////////
	$.ajax ({
	                  url:"./controladores/controlador.guardar_preguntas.php",
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
	                        if(recordset=="error_bd")
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
	                        else if(recordset[0]=="registro_exitoso")
	                        {
	                        	$("#id_pregunta").val(recordset[1]);
	                        	mensajes(6);
	                        }
	                  }
		});
	////////////////////////////////////////////////////
		
});
$("#btn_cpreguntas").click(function(){
	var cabecera="<b>Consulta emergente: Preguntas evaluaciones</b>";
	$("#myModalLabelconsulta").html(cabecera);
	//genero los campos de la tabla
	var cabacera_tabla="<tr><td><input type='text' name='f_aula' id='f_aula' placeholder='Filtro seg&uacute;n aula' class='form-control input-sg' onblur='consultar_cuerpo_tabla_preguntas(0,5,0);'></td>\
						<td><input type='text' name='f_ev' id='f_ev' placeholder='Filtro seg&uacute;n evaluacion' class='form-control input-sg' onblur='consultar_cuerpo_tabla_preguntas(0,5,0);'></td>\
						<td><input type='text' name='f_pr' id='f_pr' placeholder='Filtro seg&uacute;n preguntas' class='form-control input-sg' onblur='consultar_cuerpo_tabla_preguntas(0,5,0);'></td></tr>\
						<tr>\
							<td width='25%'><label>N°</label></td>\
							<td width='25%'><label>EVA</label></td>\
							<td width='25%'><label>Evaluaci&oacute;n</label></td>\
							<td width='25%'><label>Pregunta</label></td>\
							<td width='25%'><label>Seleccione</label></td>\
						</tr>";
	$("#cabecera_consulta").html(cabacera_tabla);
	//consultar cuerpo de la tabla
	consultar_cuerpo_tabla_preguntas(0,5,0);
});
//BLOQUE DE FUNCIONES
$("#pie_pag").removeClass("contendor_pie_pagina").addClass("contendor_pie_pagina2");
function cargar_evaluacion(valor,idm)
{
	$("#evaluacion_p").load("./controladores/controlador.consultar_evaluaciones_aula.php?id="+valor+"&idm="+idm);
}
function cargar_preguntas(valor,idm)
{
	//alert(valor);
	$("#n_pregunta").load("./controladores/controlador.consultar_n_preguntas.php?id="+valor+"&idm="+idm);
}
function consultar_cuerpo_tabla_preguntas(offset,limit,actual)
{
	var f_aula=$("#f_aula").val();
	var f_ev=$("#f_ev").val();
	var f_pr=$("#f_pr").val();
	data={
			f_aula:f_aula,
			f_ev:f_ev,
			f_pr:f_pr,
			offset:offset,
			limit:limit,
			actual:actual,
		 }
//////////////////////////////////////////////////////////////
	$.ajax ({
	          url:"./controladores/controlador.consultar_cuerpo_pregunta.php",
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
function pregunta_select(id_pregunta)
{
	var data={
				id_pregunta:id_pregunta
			  }
	$.ajax({
			url:"./controladores/controlador.consultar_preguntas.php",
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
	          	$("#aula_virtual_evaluacion").val(recordset[0][1]);
	          	cargar_evaluacion(recordset[0][1],recordset[0][2]);
	          	cargar_preguntas(recordset[0][2],recordset[0][3]);
	          	$("#pregunta_evaluacion").val(recordset[0][4]);
	          	$("#pregunta_respuesta1").val(recordset[0][5]);
	          	$("#pregunta_respuesta2").val(recordset[0][6]);
	          	$("#pregunta_respuesta3").val(recordset[0][7]);
	          	$("#pregunta_respuesta4").val(recordset[0][8]);
          		$("#r_op"+recordset[0][9]).prop("checked","checked");
	          	$("#id_pregunta").val(recordset[0][0]);
              }	
	});		  
}
/////////////////////////////////////////////////////////////////////////////////////
</script>
<div id="cambio_clave">	
<div id="agrupar_1">	
<form  class="form-horizontal" type="POST" name="form_preguntas_ev" id="form_preguntas_ev" role="form">	
		<legend class="title_form">	
			<h1>Cargar preguntas a evaluación</h1>
		</legend>
	<div class="form-group">
			<label class="col-sm-2 control-label conf_label">Seleccione aula virtual:</label>
			<div class="col-sm-10 texto2">
				<select name="aula_virtual_evaluacion" id="aula_virtual_evaluacion" class="form-control input-sg" onchange="cargar_evaluacion(this.value,0);">
					<?php echo $opcion_aula_virtual;?>
				</select>
			</div>	
			<button  id="btn_cpreguntas" name="btn_cpreguntas" title="Consultar preguntas" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_consulta"><span class="glyphicon glyphicon-search"></span>
			</button>
	</div>	
	<div class="form-group">
		<label class="col-sm-2 control-label conf_label">Evaluacion:</label>
		<div class="col-sm-10 texto2">
			<div>
				<select id="evaluacion_p" name="evaluacion_p" class="form-control input-sg" onchange="cargar_preguntas(this.value,0);">
					<option value="0">--SELECCIONE--</option>
				</select>			
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label conf_label">N&uacute;mero de Pregunta:</label>
		<div class="col-sm-10 texto2">
			<div>
				<select id="n_pregunta" name="n_pregunta" class="form-control input-sg">
					<option value="0">--SELECCIONE--</option>
				</select>			
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label conf_label">Pregunta:</label>
		<div class="col-sm-10 texto2">
			<textarea id="pregunta_evaluacion" name="pregunta_evaluacion" rows="5" class="form-control input-sg" placeholder="Ingrese el enunciado de la pregunta.. Ejm: Cual es el nombre de la distribuci&oacute;n GNU/LINUX desarrollada en la rep&uacute;blica Bolivariana de Venezuela"></textarea>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label conf_label">Opci&oacute;n Respuesta 1:</label>
		<div class="col-sm-10 texto2">
			<input type="text" name="pregunta_respuesta1" id="pregunta_respuesta1" placeholder="Ingrese respuesta 1 a la pregunta formulada" class="form-control input-sg">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label conf_label">Opci&oacute;n Respuesta 2:</label>
		<div class="col-sm-10 texto2">
			<input type="text" name="pregunta_respuesta2" id="pregunta_respuesta2" placeholder="Ingrese respuesta 2 a la pregunta formulada" class="form-control input-sg">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label conf_label">Opci&oacute;n Respuesta 3:</label>
		<div class="col-sm-10 texto2">
			<input type="text" name="pregunta_respuesta3" id="pregunta_respuesta3" placeholder="Ingrese respuesta 3 a la pregunta formulada" class="form-control input-sg">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label conf_label">Opci&oacute;n Respuesta 4:</label>
		<div class="col-sm-10 texto2">
			<input type="text" name="pregunta_respuesta4" id="pregunta_respuesta4" placeholder="Ingrese respuesta 4 a la pregunta formulada" class="form-control input-sg">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label conf_label">Respuesta correcta:</label>
		<div class="col-sm-10 texto2">
			<label>
	          <input type="radio" name="r_op" id="r_op1" checked  value="1"> <label>Opci&oacute;n1</label>
	        </label>
	        <label> 
	          <input type="radio" name="r_op" id="r_op2" value="2"><label>Opci&oacute;n2</label>
	        </label>
	        <label>  
	          <input type="radio" name="r_op" id="r_op3" value="3"><label>Opci&oacute;n3</label>
	        </label>
	        <label>  
	          <input type="radio" name="r_op" id="r_op4" value="4"><label>Opci&oacute;n4</label>
	        </label>
	    </div>    
	</div>
	<input type="text" name="id_pregunta" id="id_pregunta" size=2>
	<div id="botonera2">
			<button type="button" id="btn_guardar_preguntas" name="btn_guardar_preguntas" class="btn btn-info">Guardar</button>
	        <button type="reset" id="btn_limpiar_preguntas" name="btn_limpiar_preguntas" class="btn btn-warning">Limpiar</button>
	</div>	
</form>
</div>
</div>

