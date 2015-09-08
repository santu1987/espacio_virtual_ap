<?php 
require_once("../controladores/controlador.estado.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#btn_ver_pdf_estudiantes").click(function(){

	$("#form_reporte_aprobados_detalle").attr("action","./vistas/vista_pdf.reporte_filtro_aprobados.php");
    $("#form_reporte_aprobados_detalle").submit();
});
$("#rep_nombre_detalle_eva").keypress(function(event){
    if(event.which==13)
    {
    	consultar_cuerpo_aprobados_rep(0,5,0);
    }
});		
consultar_cuerpo_aprobados_rep(0,5,0);
//BLOQUE DE FUNCIONES
function consultar_cuerpo_aprobados_rep(offset,limit,actual)
{
	var rep_nombre_eva=$("#rep_nombre_detalle_eva").val();
	var data={
			
				nombre:rep_nombre_eva,
				offset:offset,
				limit:limit,
				actual:actual
			 };
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_rep_aprobados.php",
				data:data,
				type:"POST",
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
					$("#cuerpo_tabla_aprobados_rep").html(recordset[0]);//cuerpo de la tabla
                	$("#paginacion_tabla_aprobados_rep").html(recordset[1]);//paginacion					
				}		
	});
}
</script>
<form id="form_reporte_aprobados_detalle" name="form_reporte_aprobados_detalle" method="POST">
<div class="tabla_body">
<fieldset>
        <legend>
            <h3 id='titulo_principal_un'>Listado cantidad de estudiantes aprobados/reprobados</h3>
        </legend>
 	</fieldset>
	<div class="form-horizontal">
		 	<div class="form-group">
		 		<div class="col-lg-6" style="display:none;">
					<input type='text' name='rep_nombre_detalle_eva2' id='rep_nombre_detalle_eva2' placeholder='Filtro por E.V.A' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_aprobados_rep(0,5,0);' onKeyPress="return valida(event,this,18,100)">
				</div>
				<div class="col-lg-6">
					<input type='text' name='rep_nombre_detalle_eva' id='rep_nombre_detalle_eva' placeholder='Filtro por nombre E.V.A' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_aprobados_rep(0,5,0);' onKeyPress="return valida(event,this,18,100)">
				</div>
			</div>
			<table class="table table-hover" width="100%">
				<thead id="cabecera_tabla_aprobados_rep" name="cabecera_tabla_aprobados_rep">
				    <tr>
				    	<td width="60%"><label>E.V.A</label></td>
				    	<td width="20%"><label>Aprobados</label></td>
				    	<td width="20%"><label>Reprobados</label></td>
				    </tr>
				</thead>
				<tbody id="cuerpo_tabla_aprobados_rep" name="cuerpo_tabla">
					<!-- -->
				</tbody>
			</table>	
			<div class="alert alert-danger" role="alert">
				<label>Pulse el siguiente Bont&oacute;n para generar el PDF </label> <button type="button" id="btn_ver_pdf_estudiantes" name="btn_ver_pdf_estudiantes" class="btn btn-danger">Ver pdf</button>
			</div>
			<div id="paginacion_consulta">        
		      	<ul id="paginacion_tabla_aprobados_rep" class="pagination"></ul>
		    </div>
		    <div id="botonera_vv" class="form-group">
		    	<div class="col-lg-10"></div>
		    	<div id="div_volver" class="col-lg-2"></div>
		    </div>	
		</div>    
	</div>
</div>
</form>