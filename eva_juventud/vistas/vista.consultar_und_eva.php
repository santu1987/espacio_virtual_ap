<?php
session_start();
$id_aula='';
if(isset($_GET["id_aula"]))
{
	if($_GET["id_aula"]!="")
	{
		$id_aula=$_GET["id_aula"];
	}	
}
?>
<script type='text/javascript'>
//BLOQUE DE EVENTOS
consultar_cuerpo_un(0,5,0);
$("#f_ti_eva,#f_ti_unidad,#f_n_unidad").keypress(function(event){
    if(event.which==13){
		consultar_cuerpo_un(0,5,0);
    }
});	
//BLOQUE DE FUNCIONES
function ir_un_form(id_un)
{
	$("#program_body").load("./vistas/vista.registrar_contenido.php?id_un="+id_un);
}
function consultar_cuerpo_un(offset,limit,actual)
{
	var id_aula="<?php echo $id_aula; ?>";
	var f_ti_unidad=$("#f_ti_unidad").val();
	var f_n_unidad=$("#f_n_unidad").val();
	var f_ti_eva=$("#f_ti_eva").val();
	var data={
				id_aula:id_aula,
				f_ti_unidad:f_ti_unidad,
				f_n_unidad:f_n_unidad,
				f_ti_eva:f_ti_eva,
				offset:offset,
				limit:limit,
				actual:actual
			 };
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_unidades_adm.php",
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
					$("#cuerpo_tabla_aula_un").html(recordset[0]);//cuerpo de la tabla
                	$("#paginacion_tabla_consulta_un").html(recordset[1]);//paginacion					
					if(id_aula!="")
					{
						//oculto campos innecesarios si existe el id_formacion
						$("#titulo_eva_tbl1,#titulo_eva_tbl,#f_ti_eva").addClass("desaparecer");	
						$("#ti_unidad").removeClass("col-lg-5").addClass("col-lg-10");	
						$("#titulo_principal_un").html(recordset[2]);
	                	cargar_btn_volver();
					}	
				}		
	});
}
</script>
<div class="tabla_body">
	<fieldset>
        <legend>
            <h3 id='titulo_principal_un'>Contenidos de  E.V.A</h3>
        </legend>
 	</fieldset>
 	<div class="form-horizontal">
	 	<div class="form-group">
	 		<div class="col-lg-5">
				<input type='text' name='f_ti_eva' id='f_ti_eva' placeholder='Filtro por t&iacute;tulo de E.V.A' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_un(0,5,0);' onKeyPress="return valida(event,this,18,100)">
			</div>
			<div id='ti_unidad' class="col-lg-5">
				<input type='text' name='f_ti_unidad' id='f_ti_unidad' placeholder='Filtro por t&iacute;tulo de contenido' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_un(0,5,0);' onKeyPress="return valida(event,this,18,100)">
			</div>
			<div class="col-lg-2">
				<input type='text' name='f_n_unidad' id='f_n_unidad' placeholder='Filtro por nº contenido' class='form-control input-sg input-filtros' onblur='valida2(this,10,100);consultar_cuerpo_un(0,5,0);' onKeyPress="return valida(event,this,10,100)">
			</div>
		</div>	
		<table class="table table-hover" width="100%">
			<thead id="cabecera_tabla_un" name="cabecera_tabla_adm">
			    <tr>
			    	<td id='titulo_eva_tbl1' width="20%"><label>T&iacute;tulo E.V.A</label></td>
			    	<td width="20%"><label>T&iacute;tulo Contenido</label></td>
			    	<td width="15%"><label>N° Contenido</label></td>
			    	<td width="30%"><label>Descripci&oacute;n</label></td>
			    	<td width="20%"><label>Acciones</label></td>
			    </tr>
			</thead>
			<tbody id="cuerpo_tabla_aula_un" name="cuerpo_tabla">
				<!-- -->
			</tbody>
		</table>	
		<div id="paginacion_consulta">        
	      	<ul id="paginacion_tabla_consulta_un" class="pagination"></ul>
	    </div>
	    <div id="botonera_vv" class="form-group">
	    	<div class="col-lg-10"></div>
	    	<div id="div_volver" class="col-lg-2"></div>
	    </div>	

	</div>    
</div>