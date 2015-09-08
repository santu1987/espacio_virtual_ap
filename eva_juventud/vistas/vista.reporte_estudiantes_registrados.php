<?php 
require_once("../controladores/controlador.estado.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#btn_ver_pdf_estudiantes").click(function(){
	$("#form_reporte_est").attr("action","./vistas/vista_pdf.reporte_filtro_est.php");
    $("#form_reporte_est").submit();
});
$("#rep_nacionalidad,#rep_cedula_est,#rep_nombre_est,#rep_estado,#rep_municipio,#rep_parroquia").keypress(function(event){
    if(event.which==13)
    {
    	consultar_cuerpo_estudiantes_reg(0,5,0);
    }
});	
consultar_cuerpo_estudiantes_reg(0,5,0);
//BLOQUE DE FUNCIONES
function consultar_cuerpo_estudiantes_reg(offset,limit,actual)
{
	var rep_nacionalidad=$("#rep_nacionalidad").val();
	var rep_cedula_est=$("#rep_cedula_est").val();
	var rep_nombre_est=$("#rep_nombre_est").val();
	var rep_estado=$("#rep_estado").val();
	var rep_municipio=$("#rep_municipio").val();
	var rep_parroquia=$("#rep_parroquia").val();
	var rep_sexo=$("#rep_sexo").val();
	var data={
				nacionalidad:rep_nacionalidad,
				cedula:rep_cedula_est,
				nombre:rep_nombre_est,
				estado:rep_estado,
				municipio:rep_municipio,
				parroquia:rep_parroquia,
				offset:offset,
				limit:limit,
				actual:actual,
				sexo:rep_sexo
			 };
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_rep_estudiantes.php",
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
					$("#cuerpo_tabla_aula_rep_estudiantes").html(recordset[0]);//cuerpo de la tabla
                	$("#paginacion_tabla_rep_estudiantes").html(recordset[1]);//paginacion					
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
<form id="form_reporte_est" name="form_reporte_est" method="POST">
<div class="tabla_body">
<fieldset>
        <legend>
            <h3 id='titulo_principal_un'>Estudiantes Registrados</h3>
        </legend>
 	</fieldset>
	<div class="form-horizontal">
		 	<div class="form-group">
		 		<div class="col-lg-2">
					<select id="rep_nacionalidad" name="rep_nacionalidad" class="form-control" onblur="consultar_cuerpo_estudiantes_reg(0,5,0);">
						<option id="-1" value="-1">[Nacionalidad]</option>
						<option id="V" value="V">Venezolano</option>
						<option id="E" value="E">Extranjero</option>
					</select>
				</div>
				<div class="col-lg-2">
					<input type='text' name='rep_cedula_est' id='rep_cedula_est' placeholder='Filtro por nº C&eacute;dula' class='form-control input-sg input-filtros' onblur='valida2(this,10,8);consultar_cuerpo_estudiantes_reg(0,5,0);' onKeyPress="return valida(event,this,10,8)">
				</div>
				<div class="col-lg-2">
					<input type='text' name='rep_nombre_est' id='rep_nombre_est' placeholder='Filtro por nombres y ape.' class='form-control input-sg input-filtros' onblur='valida2(this,19,20);consultar_cuerpo_estudiantes_reg(0,5,0);' onKeyPress="return valida(event,this,19,20)">
				</div>
				<div class="col-lg-2">
					<select id="rep_sexo" name="rep_sexo" class="form-control" onchange="cargar_municipio(this.value);consultar_cuerpo_estudiantes_reg(0,5,0);">
						 <option id='-1' value='-1'>[Sexo]</option>
 						 <option id='1' value='1'>Masculino</option>
						 <option id='2' value='0'>Femenino</option>
					</select>
				</div>
				<div class="col-lg-2">
					<select id="rep_estado" name="rep_estado" class="form-control" onchange="cargar_municipio(this.value);consultar_cuerpo_estudiantes_reg(0,5,0);">
						 <?php echo $option_estado; ?>
					</select>
				</div>
				<div class="col-lg-2">
					<select id="rep_municipio" name="rep_municipio" class="form-control" onchange="cargar_parroquia(this.value);consultar_cuerpo_estudiantes_reg(0,5,0);">
						<option id="-1" value="-1">[Municipio]</option>
					</select>
				</div>
			</div>
			<div class="form-group">	
				<div class="col-lg-2">
					<select id="rep_parroquia" name="rep_parroquia" class="form-control" onchange="consultar_cuerpo_estudiantes_reg(0,5,0);">
						<option id="-1" value="-1">[Parroquia]</option>
					</select>
				</div>
			</div>
			<table class="table table-hover" width="100%">
				<thead id="cabecera_tabla_est_apr" name="cabecera_tabla_est_apr">
				    <tr>
				    	<td width="5%"><label>Nacionalidad</label></td>
				    	<td width="10%"><label>C&eacute;dula</label></td>
				    	<td width="10%"><label>Nombres</label></td>
				    	<td width="5%"><label>Sexo</label></td>				    	
				    	<td width="10%"><label>Correo Electr&oacute;nico</label></td>
				    	<td width="10%"><label>Tel&eacute;fono</label></td>
				    	<td width="10%"><label>Estado</label></td>
				    	<td width="10%"><label>Municipio</label></td>
				    	<td width="10%"><label>Parroquia</label></td>
				    </tr>
				</thead>
				<tbody id="cuerpo_tabla_aula_rep_estudiantes" name="cuerpo_tabla_aula_rep_estudiantes">
					<!-- -->
				</tbody>
			</table>	
			<div class="alert alert-danger" role="alert">
				<label>Pulse el siguiente Bont&oacute;n para generar el PDF </label> <button type="button" id="btn_ver_pdf_estudiantes" name="btn_ver_pdf_estudiantes" class="btn btn-danger">Ver pdf</button>
			</div>
			<div id="paginacion_consulta">        
		      	<ul id="paginacion_tabla_rep_estudiantes" class="pagination"></ul>
		    </div>
		    <div id="botonera_vv" class="form-group">
		    	<div class="col-lg-10"></div>
		    	<div id="div_volver" class="col-lg-2"></div>
		    </div>	
		</div>    
	</div>
</div>
</form>