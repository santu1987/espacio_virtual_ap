<?php 
require_once("../controladores/controlador.estado.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#btn_ver_pdf_beneficiados").click(function(){
	$("#form_reporte_beneficiados").attr("action","./vistas/vista_pdf.reporte_beneficiados.php");
    $("#form_reporte_beneficiados").submit();
});
$("#rep_estado,#rep_municipio,#rep_parroquia").keypress(function(event){
    if(event.which==13)
    {
    	consultar_cuerpo_beneficiados(0,5,0);
    }
});	
consultar_cuerpo_beneficiados(0,5,0);
//BLOQUE DE FUNCIONES
function consultar_cuerpo_beneficiados(offset,limit,actual)
{
	var rep_estado=$("#rep_estado").val();
	var rep_municipio=$("#rep_municipio").val();
	var rep_parroquia=$("#rep_parroquia").val();
	var data={
				estado:rep_estado,
				municipio:rep_municipio,
				parroquia:rep_parroquia,
				offset:offset,
				limit:limit,
				actual:actual
			 };
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_rep_beneficiados.php",
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
					$("#cuerpo_tabla_beneficiados").html(recordset[0]);//cuerpo de la tabla
                	$("#paginacion_tabla_beneficiados").html(recordset[1]);//paginacion					
				}		
	});
}
function cargar_municipio(valor)
{
  $("#rep_municipio").load('controladores/controlador.municipio.php?id='+valor);
}
//funcion para cargar parroquia...
function cargar_parroquia(valor)
{
  $("#rep_parroquia").load('controladores/controlador.parroquia.php?id='+valor);
}
</script>
<form id="form_reporte_beneficiados" name="form_reporte_beneficiados" method="POST">
<div class="tabla_body">
<fieldset>
        <legend>
            <h3 id='titulo_principal_un'>Beneficiados por el sistema de ESPACIOS VIRTUALES DE APRENDIZAJE JUVENTUD</h3>
        </legend>
 	</fieldset>
	<div class="form-horizontal">
		 	<div class="form-group">
		 		<div class="col-lg-4">
					<select id="rep_estado" name="rep_estado" class="form-control" onchange="cargar_municipio(this.value);consultar_cuerpo_beneficiados(0,5,0);">
						 <?php echo $option_estado; ?>
					</select>
				</div>
				<div class="col-lg-4">
						<select id="rep_municipio" name="rep_municipio" class="form-control" onchange="cargar_parroquia(this.value);consultar_cuerpo_estudiantes_reg(0,5,0);">
							<option id="-1" value="-1">[Municipio]</option>
						</select>
				</div>
				<div class="col-lg-4">
						<select id="rep_parroquia" name="rep_parroquia" class="form-control" onchange="consultar_cuerpo_estudiantes_reg(0,5,0);">
							<option id="-1" value="-1">[Parroquia]</option>
						</select>
				</div>
			</div>	
			<table class="table table-hover" width="100%">
				<thead id="cabecera_tabla_beneficiados" name="cabecera_tabla_beneficiados">
				    <tr>
				    	<td width="25%"><label>Estado</label></td>
				    	<td width="25%"><label>Municipio</label></td>
				    	<td width="25%"><label>Parroquia</label></td>
				    	<td width="15%"><label>Beneficiados</label></td>
				    </tr>
				</thead>
				<tbody id="cuerpo_tabla_beneficiados" name="cuerpo_tabla_beneficiados">
					<!-- -->
				</tbody>
			</table>	
			<div class="alert alert-danger" role="alert">
				<label>Pulse el siguiente Bont&oacute;n para generar el PDF </label> <button type="button" id="btn_ver_pdf_beneficiados" name="btn_ver_pdf_beneficiados" class="btn btn-danger">Ver pdf</button>
			</div>
			<div id="paginacion_consulta">        
		      	<ul id="paginacion_tabla_beneficiados" class="pagination"></ul>
		    </div>
		    <div id="botonera_vv" class="form-group">
		    	<div class="col-lg-10"></div>
		    	<div id="div_volver" class="col-lg-2"></div>
		    </div>	
		</div>    
	</div>
</div>
</form>