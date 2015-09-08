<?php
session_start();
require_once("../controladores/controlador.tipo_estudio.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#pie_pag").removeClass("contendor_pie_pagina").addClass("contendor_pie_pagina2");
consultar_cuerpo_tabla_aulas(0,5,0);
$("#f_fecha_aula").datetimepicker({ 
    lang:'es',
    timepicker:false,
    format:'d-m-Y',
    formatDate:'Y-m-d',
});
$("#f_nombre_eva,#f_fecha_aula,#f_selec_tipo,#f_opcion_eval").keypress(function(event){
    if(event.which==13)
    {
    	consultar_cuerpo_tabla_aulas(0,5,0);
    }
});	
//BLOQUE DE FUNCIONES
function cargar_botones_eva(numero,id_aula)
{
	$("#btn_selec_dest"+numero+",#btn_cons_eva"+numero).remove();
	var boton_ir_aula="<button class='btn btn-danger operaciones_be' id='btn_ir_aula_insc"+numero+"'  onclick='ir_aula_resumen("+id_aula+");' title='Ir al aula'><i class='fa fa-university'></i></i></button>";
	var boton_consultar_aula="<button class='btn btn-primary operaciones_be' style='margin-left:2%' title='Consultar aula' id='btn_cons_eva"+numero+"'onclick='consultar_resumen_eva("+id_aula+");' ><i class='fa fa-search'></i></button>";
	$("#botonera"+numero).append(boton_ir_aula+" "+boton_consultar_aula);
}
function ir_aula_resumen(id_aula)
{
	$("#program_body").load("./vistas/vista.espacio_virtual.php?id_aula="+id_aula);
}
function btn_el_inbox(id_eva,numero)
{
	bootbox.confirm("¿Realmente desea inscribirse en la siguiente aula?", function(result) 
	{
		if (result)
		{
		//
			var data={id_eva:id_eva};
			$.ajax({
						url:"./controladores/controlador.registrar_inscripcion.php",
						data:data,
						type:'POST',
						cache:false,
						error: function()
						{
							  console.log(arguments);
					          mensajes(3);
						},
						success: function(html)
						{
							var recordset=$.parseJSON(html);
							if(recordset=="error_bd")
							{
								mensajes(2);//error inesperado
							}
							else
							if(recordset[0]=="registro_exitoso")	
							{
								mensajes(6);//operacion realizada con exito
								cargar_botones_eva(numero,id_eva);
							}
							else
							if(recordset[0]=="existe")
							{
								mensajes(17);//el registro ya existe...
							}	
						}
			});

		//       
		}//fin de if
	});//fin de bootbox
//
}
function consultar_cuerpo_tabla_aulas(offset,limit,actual)
{
	var f_nombre_eva=$("#f_nombre_eva").val();
	var f_fecha_aula=$("#f_fecha_aula").val();
	var f_selec_tipo=$("#f_selec_tipo").val();
	var f_opcion_eval=$('input:radio[name=f_opcion_eval]:checked').val();
	var data={
				f_nombre_eva:f_nombre_eva,
				offset:offset,
				limit:limit,
				actual:actual,
				f_fecha_aula:f_fecha_aula,
				f_opcion_eval:f_opcion_eval,
				f_selec_tipo:f_selec_tipo
	};
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_eva.php",
				type:"POST",
				data:data,
				cache:false,
				error: function()
				{
					  console.log(arguments);
			          mensajes(3);
				},
				success: function(html)
				{
					recordset=$.parseJSON(html);
					if(recordset=="error")
					{
						mensajes(3);//error inesperado
					}
					else if(recordset=="campos_blancos")
					{
						mensajes(6);//error pasando campos vacios
					}
					else
					{
						$("#cuerpo_tabla").html(recordset[0]);//cuerpo de la tabla
                		$("#paginacion_tabla").html(recordset[1]);//paginacion
					}	
				}
	});
}
function consultar_resumen_eva(id_eva)
{
	$("#program_body").load("./vistas/vista.resumen_eva.php?id_eva="+id_eva);
}
//
</script>
<div class="tabla_body">
 <fieldset>
        <legend>
            <h3>Espacios Virtuales de Aprendizaje E.V.A's</h3>
        </legend>
 </fieldset>
 <div class="form-group">
 	<div class="col-lg-6">
 		<input type='text' name='f_nombre_eva' id='f_nombre_eva' placeholder='Filtro por nombre de E.V.A' class='form-control input-sg input-filtros' onchange="consultar_cuerpo_tabla_aulas(0,5,0);" onKeyPress="return valida(event,this,18,100)" onBlur="valida2(this,18,100);">
 	</div>
 	<div class="col-lg-2">
 		<input type='text' name='f_fecha_aula' id='f_fecha_aula' class='form-control input-sg input-filtros'onkeyup="this.value=formateafecha(this.value);" placeholder="Fecha activaci&oacute;n" onchange="consultar_cuerpo_tabla_aulas(0,5,0);">
 	</div>	
 	<div class="col-lg-2">
 		<select id="f_selec_tipo" name="f_selec_tipo" class="form-control" onchange="consultar_cuerpo_tabla_aulas(0,5,0);">
 			<?php echo $option_estudio; ?>
 		</select>
 	</div>	
 	<div style="display:none" class="col-lg-2">
 		Evaluados:<br>
 		<input type="radio" name="f_opcion_eval" id="f_opcion_eval_si" value="1" onchange="consultar_cuerpo_tabla_aulas(0,5,0);">Si
 		<input type="radio" name="f_opcion_eval" id="f_opcion_eval_no" value="0" onchange="consultar_cuerpo_tabla_aulas(0,5,0);">No
 	</div>	
</div>
 <table class="table table-hover" width="100%">
		<thead id="cabecera_tabla" name="cabecera_tabla">
		    <tr>
		    	<td width="10%"><label>EVA</label></td>
		    	<td width="15%"><label>T&iacute;tulo</label></td>
		    	<!--<td width="5%"><label>Evaluado</label></td>-->
		    	<td width="10%"><label>Tipo</label></td>
		    	<td width="30%"><label>Resum&eacute;n</label></td>
		    	<td width="10%" style="text-align:left"><label>Fecha de inicio</label></td>
		    	<td width="20%"><label>Acciones</label></td>
		    </tr>
		</thead>
		<tbody id="cuerpo_tabla" name="cuerpo_tabla">
			<!-- -->
		</tbody>
	</table>
	<div id="paginacion_consulta">        
      	<ul id="paginacion_tabla" class="pagination"></ul>
    </div>
</div>