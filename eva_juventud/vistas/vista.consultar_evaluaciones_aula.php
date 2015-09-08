<?php
session_start();
if(isset($_GET["id_aula"]))
{
	if($_GET["id_aula"]!="")
	{
		$id_aula=$_GET["id_aula"];	
	}
	else
	{
		$id_aula="";
	}	
}else
{
	$id_aula="";
}
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#f_fecha_eval").datetimepicker({ 
    lang:'es',
    timepicker:false,
    format:'d-m-Y',
    formatDate:'Y-m-d',
});
cargar_consulta_eval(0,5,0);
$("#f_tipo_eval").load('controladores/controlador.tipo_evaluacion_c.php');
$("#f_ti_eva,#f_ti_unidad,#f_fecha_eval,#f_tipo_eval,#f_estatus").keypress(function(){
   if(event.which==13){
        cargar_consulta_eval(0,5,0);
    }
});
//BLOQUE DE FUNCIONES
function cerrar_eva(id_evaluacion)
{
	
	var data={id_evaluacion:id_evaluacion};
	$.ajax ({
	          url:"./controladores/controlador.cerrar_eva.php",
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
                else if(recordset[0]=="examen_resuelto_alumno")
                {
                	mensajes(27);//no puede aperturar la prueba...
                }	
                else if(recordset[0]=='1')
                {
                	mensajes(25);//evaluación activa
                	cargar_consulta_eval(0,5,0);
                }
                else if (recordset[0]=='0') 
                {
                	mensajes(26);//evaluacion inactiva...
                    cargar_consulta_eval(0,5,0);
                }
	          }
	});
}
function ir_eval_form(id_eval,id_unidad)
{
	$("#program_body").load("./vistas/vista.registrar_evaluacion.php?id_eval="+id_eval+"&id_unidad="+id_unidad);
}
function cargar_consulta_eval(offset,limit,actual)
{
	var id_aula="<?php echo $id_aula;?>";
	var f_ti_unidad=$("#f_ti_unidad").val();
	var f_fecha_eval=$("#f_fecha_eval").val();
	var f_tipo_eval=$("#f_tipo_eval").val();
	var f_estatus=$("#f_estatus").val();
	var f_ti_eva=$("#f_ti_eva").val();
	//alert(f_estatus);
	var data={	id_aula:id_aula,
				f_ti_unidad:f_ti_unidad,
				f_fecha_eval:f_fecha_eval,
				f_tipo_eval:f_tipo_eval,
				offset:offset,
				limit:limit,
				actual:actual,
				f_estatus:f_estatus,
				f_ti_eva:f_ti_eva
			};
	$.ajax({
				url:"./controladores/controlador.consultar_evaluaciones_un_eva.php",
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
					var recordset=$.parseJSON(html);
					//alert(recordset);
					$("#cuerpo_tabla_aula_ev").html(recordset[0]);//cuerpo de la tabla
            		$("#paginacion_tabla_consulta_ev").html(recordset[1]);//paginacion
	                if(id_aula!="")
	                {
   		                $("#titulo_principal_eval").html(recordset[2]);
	                	$("#titulo_eva_tbl,#cab_titulo_eva").addClass("desaparecer");
	                	$("#ti_unidad").removeClass("col-lg-4").addClass("col-lg-6");
	                	$("#t_fecha").removeClass("col-lg-2").addClass("col-lg-4");
	                	cargar_btn_volver();
	                }		
				}
	});			
}
</script>
<div class="tabla_body">
	<fieldset>
        <legend>
            <h3 id='titulo_principal_eval'>Evaluaciones de contenidos E.V.A</h3>
        </legend>
 	</fieldset>
 	<div class="form-horizontal">
	 	<div class="form-group">
	 		<div id="titulo_eva_tbl" class="col-lg-4">
				<input type='text' name='f_ti_eva' id='f_ti_eva' placeholder='Filtro por t&iacute;tulo de E.V.A' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);cargar_consulta_eval(0,5,0);' onKeyPress="return valida(event,this,18,100);">
			</div>
			<div id='ti_unidad' class="col-lg-4">
		
				<input type='text' name='f_ti_unidad' id='f_ti_unidad' placeholder='Filtro por t&iacute;tulo de contenido' class='form-control input-sg input-filtros' onblur='cargar_consulta_eval(0,5,0);' onKeyPress="return valida(event,this,18,100);">
			</div>
			<div id='t_fecha' class="col-lg-2">
				<input type='text' name='f_fecha_eval' id='f_fecha_eval' placeholder='Filtro por fecha evaluaci&oacute;n' class='form-control input-sg input-filtros' onblur='cargar_consulta_eval(0,5,0);' onkeyup="this.value=formateafecha(this.value);" placeholder="Fecha activaci&oacute;n :dd/mm/aaaa">
			</div>
			<div class="col-lg-2">
				<select name="f_tipo_eval" id="f_tipo_eval" class="form-control" onblur="cargar_consulta_eval(0,5,0);">
					<?php echo $opcion_aula_virtual;?>
				</select>
			</div>	
		</div>
		<div class="form-group">
			<div class="col-lg-2">
				<select name='f_estatus' id='f_estatus' class="form-control" onblur='cargar_consulta_eval(0,5,0);'>
					<option value='-1'>[Estatus]</option>
					<option value='0'>Inactivas</option>
					<option value='1'>Activas</option>
				</select>
			</div>	
		</div>	
		<table class="table table-hover" width="100%">
			<thead id="cabecera_tabla_ev" name="cabecera_tabla_ev">
			    <tr>
			    	<td id="cab_titulo_eva" width="20%"><label>T&iacute;tulo E.V.A</label></td>
			    	<td id="cab_titulo_und" width="20%"><label>T&iacute;tulo contenido</label></td>
			    	<td width="20%"><label>Descripci&oacute;n contenido</label></td>
			    	<td width="10%"><label>Tipo</label></td>
			    	<td width="10%"><label>Estatus</label></td>
			    	<td width="10%"><label>Fecha</label></td>
			    	<td width="20%"><label>Acciones</label></td>
			    </tr>
			</thead>
			<tbody id="cuerpo_tabla_aula_ev" name="cuerpo_tabla_ev">
				<!-- -->
			</tbody>
		</table>	
		<div id="paginacion_consulta">        
	      	<ul id="paginacion_tabla_consulta_ev" class="pagination"></ul>
	    </div>
	    <div id="botonera_vv" class="form-group">
	    	<div class="col-lg-10"></div>
	    	<div id="div_volver" class="col-lg-2"></div>
	    </div>	
	</div>    
</div>