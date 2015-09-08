<?php 
require_once("../controladores/controlador.estado.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#btn_ver_pdf_estudiantes").click(function(){
	$("#form_reporte_aprobados_det").attr("action","./vistas/vista_pdf.reporte_filtro_aprobados_detalle.php");
    $("#form_reporte_aprobados_det").submit();
});
$("#rep_nacionalidad,#rep_cedula_est,#rep_nombre_est,#rep_nombre_eva").keypress(function(event){
    if(event.which==13)
    {
    	consultar_cuerpo_est_apr(0,5,0);
    }
});
consultar_cuerpo_est_apr(0,5,0);
//BLOQUE DE FUNCIONES
function consultar_cuerpo_est_apr(offset,limit,actual)
{
	var rep_nombre_eva=$("#rep_nombre_eva").val();
	var rep_nacionalidad=$("#rep_nacionalidad").val();
	var rep_cedula_est=$("#rep_cedula_est").val();
	var rep_nombre_est=$("#rep_nombre_est").val();
	var data={
				eva:rep_nombre_eva,
				nacionalidad:rep_nacionalidad,
				cedula:rep_cedula_est,
				nombre:rep_nombre_est,
				offset:offset,
				limit:limit,
				actual:actual
			 };
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_est_apr.php",
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
					$("#cuerpo_tabla_aprobados_det").html(recordset[0]);//cuerpo de la tabla
                	$("#paginacion_tabla_est_det").html(recordset[1]);//paginacion					
				}		
	});
}
</script>
<form id="form_reporte_aprobados_det" name="form_reporte_aprobados_det" method="POST">
<div class="tabla_body">
<fieldset>
        <legend>
            <h3 id='titulo_principal_un'>Listado de estudiantes aprobados/reprobados (Detalle)</h3>
        </legend>
 	</fieldset>
	<div class="form-horizontal">
		 	<div class="form-group">
		 		<div class="col-lg-2">
					<select id="rep_nacionalidad" name="rep_nacionalidad" class="form-control" onblur="consultar_cuerpo_est_apr(0,5,0);">
						<option id="-1" value="-1">[Nacionalidad]</option>
						<option id="V" value="V">Venezolano</option>
						<option id="E" value="E">Extranjero</option>
					</select>
				</div>
				<div class="col-lg-2">
					<input type='text' name='rep_cedula_est' id='rep_cedula_est' placeholder='Filtro por nº C&eacute;dula' class='form-control input-sg input-filtros' onblur='valida2(this,10,100);consultar_cuerpo_est_apr(0,5,0);' onKeyPress="return valida(event,this,10,100)">
				</div>
				<div class="col-lg-4">
					<input type='text' name='rep_nombre_est' id='rep_nombre_est' placeholder='Filtro por nombres y apellidos' class='form-control input-sg input-filtros' onblur='valida2(this,19,100);consultar_cuerpo_est_apr(0,5,0);' onKeyPress="return valida(event,this,19,50)">
				</div>
				<div class="col-lg-4">
					<input type='text' name='rep_nombre_eva' id='rep_nombre_eva' placeholder='Filtro por E.V.A' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_est_apr(0,5,0);' onKeyPress="return valida(event,this,19,50)">
				</div>
			</div>
			<table class="table table-hover" width="100%">
				<thead id="cabecera_tabla_est_det" name="cabecera_tabla_est_det">
				    <tr>
				    	<td width="10%"><label>Nacionalidad</label></td>
				    	<td width="10%"><label>C&eacute;dula</label></td>
				    	<td width="30%"><label>Nombres</label></td>
				    	<td width="30%"><label>EVA</label></td>
				    	<td width="20%"><label>Estatus</label></td>				    	
				    </tr>
				</thead>
				<tbody id="cuerpo_tabla_aprobados_det" name="cuerpo_tabla">
					<!-- -->
					<!-- -->
				</tbody>
			</table>	
			<div class="alert alert-danger" role="alert">
				<label>Pulse el siguiente Bont&oacute;n para generar el PDF </label> <button type="button" id="btn_ver_pdf_estudiantes" name="btn_ver_pdf_estudiantes" class="btn btn-danger">Ver pdf</button>
			</div>
			<div id="paginacion_consulta">        
		      	<ul id="paginacion_tabla_est_det" class="pagination"></ul>
		    </div>
		    <div id="botonera_vv" class="form-group">
		    	<div class="col-lg-10"></div>
		    	<div id="div_volver" class="col-lg-2"></div>
		    </div>	
		</div>    
	</div>
</div>
</form>