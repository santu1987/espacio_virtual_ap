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
$("#f_fecha_ac").datetimepicker({ 
    lang:'es',
    timepicker:false,
    format:'d/m/Y',
    formatDate:'Y/m/d',
});
cargar_consulta_eval(0,5,0);
$("#f_tipo_eval").load('controladores/controlador.tipo_evaluacion_c.php');
$("#f_ti_eva,#f_ti_unidad,#f_fecha_eval,#f_tipo_eval,#f_estatus,#f_fecha_ac").keypress(function(){
   if(event.which==13){
        cargar_consulta_eval(0,5,0);
    }
});
//BLOQUE DE FUNCIONES
function ir_eval_est(id_evaluaciones,id_contenido)
{
	$("#program_body").load("./vistas/vista.desarrollar_prueba.php?id_evaluacion="+id_evaluaciones+"&id_contenido="+id_contenido);
}
function ir_eval_form(id_eval)
{
	$("#program_body").load("./vistas/vista.registrar_evaluacion.php?id_eval="+id_eval);
}
function cargar_consulta_eval(offset,limit,actual)
{
	var id_aula="<?php echo $id_aula;?>";
	var f_ti_unidad=$("#f_ti_unidad").val();
	var f_fecha_eval=$("#f_fecha_eval").val();
	var f_tipo_eval=$("#f_tipo_eval").val();
	var f_estatus=$("#f_estatus").val();
	var f_ti_eva=$("#f_ti_eva").val();
	var f_fecha_ac=$("#f_fecha_ac").val();
	var data={	id_aula:id_aula,
				f_ti_unidad:f_ti_unidad,
				f_fecha_eval:f_fecha_eval,
				f_tipo_eval:f_tipo_eval,
				offset:offset,
				limit:limit,
				actual:actual,
				f_estatus:f_estatus,
				f_ti_eva:f_ti_eva,
				f_fecha_ac:f_fecha_ac
			 };
	$.ajax({
				url:"./controladores/controlador.consultar_misevaluaciones_un_eva.php",
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
					//alert(recordset[3]);
					$("#cuerpo_tabla_aula_ev").html(recordset[0]);//cuerpo de la tabla
            		$("#paginacion_tabla_consulta_ev").html(recordset[1]);//paginacion
	                $("#mensaje_nota").html(recordset[3]);
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
            <h3 id='titulo_principal_eval'>Mis Evaluaciones de contenidos E.V.A</h3>
        </legend>
 	</fieldset>
 	<div class="form-horizontal">
	 	<div class="form-group">
	 		<div id="titulo_eva_tbl" class="col-lg-4">
				<input type='text' name='f_ti_eva' id='f_ti_eva' placeholder='Filtro por t&iacute;tulo de E.V.A' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);cargar_consulta_eval(0,5,0);' onKeyPress="return valida(event,this,18,100);">
			</div>
			<div id='ti_unidad' class="col-lg-4">
				<input type='text' name='f_ti_unidad' id='f_ti_unidad' placeholder='Filtro por t&iacute;tulo de unidad' class='form-control input-sg input-filtros' onblur='cargar_consulta_eval(0,5,0);' onKeyPress="return valida(event,this,18,100);">
			</div>
			<div id='t_fecha' class="col-lg-2">
				<input type='text' name='f_fecha_ac' id='f_fecha_ac' placeholder='Filtro por fecha inicio' class='form-control input-sg input-filtros' onblur='cargar_consulta_eval(0,5,0);' onkeyup="this.value=formateafecha(this.value);">
			</div>
			<div id='t_fecha' class="col-lg-2">
				<input type='text' name='f_fecha_eval' id='f_fecha_eval' placeholder='Filtro por fecha cierre' class='form-control input-sg input-filtros' onblur='cargar_consulta_eval(0,5,0);' onkeyup="this.value=formateafecha(this.value);">
			</div>				
		</div>
		<div class="form-group">
			<div class="col-lg-2">
				<select name="f_tipo_eval" id="f_tipo_eval" class="form-control" onblur="cargar_consulta_eval(0,5,0);">
					<?php echo $opcion_aula_virtual;?>
				</select>
			</div>
			<div class="col-lg-2">
				<select name='f_estatus' id='f_estatus' class="form-control" onblur='cargar_consulta_eval(0,5,0);'>
					<option id="0" value="0">[Estatus]</option>
					<option id="1" value="1">Aprobado</option>
					<option id="2" value="2">Reprobado</option>
					<option id="3" value="3">Pendiente</option>
				</select>
			</div>	
		</div>	
		<table class="table table-hover" width="100%">
			<thead id="cabecera_tabla_ev" name="cabecera_tabla_ev">
			    <tr>
			    	<td id="cab_titulo_eva" width="20%"><label>T&iacute;tulo E.V.A</label></td>
			    	<td style="text-align:left"id="cab_titulo_und" width="20%"><label>T&iacute;tulo de Contenido</label></td>
			    	<td width="10%"><label>Tipo</label></td>
			    	<td width="10%"><label>Estatus</label></td>
			    	<td width="10%"><label>Calificaci&oacute;n</label></td>
			    	<td width="10%" style="text-align:left"><label>Fecha Inicio</label></td>
			    	<td width="10%" style="text-align:left"><label>Fecha Cierre</label></td>
			    	<td width="10%"><label>Acciones</label></td>
			    </tr>
			</thead>
			<tbody id="cuerpo_tabla_aula_ev" name="cuerpo_tabla_ev">
				<!-- -->
			</tbody>
		</table>
		<div id="mensaje_nota">
		</div>	
		<div id="paginacion_consulta">        
	      	<ul id="paginacion_tabla_consulta_ev" class="pagination"></ul>
	    </div>
	    <div id="botonera_vv" class="form-group">
	    	<div class="col-lg-10"></div>
	    	<div id="div_volver" class="col-lg-2"></div>
	    </div>	
	</div>    
</div>