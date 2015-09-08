<?php 
include("../controladores/controlador.aula_virtual.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#btn_consultar_notas").click(function(){
 if($("#select_eva").val()!="-1")
 {
 	consultar_cuerpo_notas(0,5,0);
 }else
 {
 	mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, debe seleecionar un E.V.A, para realizar la consulta");	
 }
});
$("#btn_procesar_notas").click(function(){
 if($("#select_eva").val()!="-1")
 {
	var aula=$("#select_eva").val();
	var data={aula:aula};
	$.ajax({
				url:'./controladores/controlador.procesar_notas.php',
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
					//alert(recordset);
					if(recordset[0]=="error_bd")
	                {
	                	mensajes(7);//error en base de datos
	                }
	                else if(recordset[0]=="campos_blancos")
	                {
	                	mensajes(5);//debe ingresar los campos
	                }
	                else if(recordset[0]=="error_auditoria")
	                {
	                	mensajes(22);//error auditorias
	                }	
	                else if(recordset[0]=="no_proceso")
	                {
	                	mensajes(35);//no proceso el calculo de las notas definitivas
	                }
	                else	
	                if(recordset[0]=="registro_exitoso")	
	                {
                    	mensajes(13);//registro exitoso
                    	consultar_cuerpo_notas(0,5,0);
	                }	
				}
	});
 }else
 {
 	mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, debe seleecionar un E.V.A, para procesar las notas definitivas");		
 }
});
//
//BLOQUE DE FUNCIONES
function consultar_cuerpo_notas(offset,limit,actual)
{
	var select_eva=$("#select_eva").val();
	var data={
			
				eva:select_eva,
				offset:offset,
				limit:limit,
				actual:actual
			 };
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_notas.php",
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
					$("#cabecera_tabla_notas").html(recordset[0]);//cabecera de la tabla
					$("#cuerpo_tabla_notas").html(recordset[1]);//cuerpo de la tabla
                	$("#paginacion_tabla_notas").html(recordset[2]);//paginacion tabla
				}		
	});
}
</script>
<form id="form_procesar_notas" name="form_procesar_notas" method="POST">
<div class="tabla_body">
<fieldset>
        <legend>
            <h3 id='titulo_principal_un'>Procesar Notas por Espacio Virtual de Aprendizaje </h3>
        </legend>
 	</fieldset>
	<div class="form-horizontal">
		 	<div class="form-group">
		 		<div class="col-lg-6">
		 			<select name="select_eva" id="select_eva" class="form-control">
		 				<?php echo $opcion_aula_virtual;?>
		 			</select>
		 		</div>
		 		<div class="col-lg-1">
		 			<button type="button" title="Consultar Notas" id="btn_consultar_notas" name="btn_consultar_notas" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
		 		</div>
		 		<div class="col-lg-1">
		 			<button type="button" title="Procesar Notas" id="btn_procesar_notas" name="btn_procesar_notas" class="btn btn-warning">Procesar Notas</button>
		 		</div>	
			</div>

			<table class="table table-hover" width="100%">
				<thead id="cabecera_tabla_notas" name="cabecera_tabla_notas">
				   <!-- -->
				</thead>
				<tbody id="cuerpo_tabla_notas" name="cuerpo_tabla_notas">
					<!-- -->
				</tbody>
			</table>	
			<div id="paginacion_consulta">        
		      	<ul id="paginacion_tabla_notas" class="pagination"></ul>
		    </div>
		    <div id="botonera_vv" class="form-group">
		    	<div class="col-lg-10"></div>
		    	<div id="div_volver" class="col-lg-2"></div>
		    </div>	
		</div>    
	</div>
</div>
</form>