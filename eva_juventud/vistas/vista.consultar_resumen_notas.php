<?php
session_start();
require_once("../controladores/controlador.tipo_estudio.php");
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
}
else
{
	$id_aula="";
}
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
consultar_cuerpo_tabla_resumen_notas(0,5,0);
$("#f_nombre_eva,#f_nombre_contenido,#f_n_contenido").keypress(function(event){
    if(event.which==13){
    	consultar_cuerpo_tabla_aulas_adm(0,5,0);
    }
});	
$("#btn_ver_grafica").click(function(){
	var id_aula="<?php  echo $id_aula; ?>";
	$("#program_body").load("./vistas/vista.grafica_notas.php?id_aula="+id_aula);
});
//BLOQUE DE FUNCIONES
function consultar_evaluaciones_aula(id_aula)
{
	$("#program_body").load("./vistas/vista.consultar_evaluaciones_aula.php?id_aula="+id_aula);
}
function consulta_un_eva(id_aula)
{
	$("#program_body").load("./vistas/vista.consultar_und_eva.php?id_aula="+id_aula);
}
function consultar_conf_eva(id_aula)
{
	$("#program_body").load("./vistas/vista.configurar_eva.php?id_aula="+id_aula);
}
function ir_aula_resumen(id_aula)
{
	$("#program_body").load("./vistas/vista.espacio_virtual.php?id_aula="+id_aula);
}
function consultar_cuerpo_tabla_resumen_notas(offset,limit,actual)
{
	var id_aula="<?php  echo $id_aula; ?>";
	var f_nombre_eva=$("#f_nombre_eva").val();
	var f_nombre_contenido=$("#f_nombre_contenido").val();
	var f_n_contenido=$("#f_n_contenido").val();
	var data={
				id_aula:id_aula,
				f_nombre_eva:f_nombre_eva,
				f_nombre_contenido:f_nombre_contenido,
				f_n_contenido:f_n_contenido,
				offset:offset,
				limit:limit,
				actual:actual,
			};
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_resumen_notas.php",
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
					//alert(recordset);
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
						$("#cuerpo_tabla_resumen_notas").html(recordset[0]);//cuerpo de la tabla
                		$("#paginacion_tabla_resumen_notas").html(recordset[1]);//paginacion
					}	
				}
	});
}
</script>
<div class="tabla_body">
	<fieldset>
        <legend>
            <h3>Resum&eacute;n de calificaciones</h3>
        </legend>
 	</fieldset>
 	<div class="form-group">
		<div class="col-lg-6">
			<input type='text' name='f_nombre_eva' id='f_nombre_eva' placeholder='Filtro por nombre de E.V.A' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_tabla_resumen_notas(0,5,0);' onKeyPress="return valida(event,this,18,100)">
		</div>
		<div class="col-lg-4">
			<input type='text' name='f_nombre_contenido' id='f_nombre_contenido' placeholder='Filtro por nombre de Contenido' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_tabla_resumen_notas(0,5,0);' onKeyPress="return valida(event,this,18,100)">
		</div>
		<div class="col-lg-2">
			<input type="text" name="f_n_contenido" id="f_n_contenido" placeholder="Contenido" class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_tabla_resumen_notas(0,5,0);' onKeyPress="return valida(event,this,10,10)">
		</div>
	</div>
	<table class="table table-hover" width="100%">
		<thead id="cabecera_tabla_adm" name="cabecera_tabla_adm">
		    <tr>
		    	<td width="30%"><label>T&iacute;tulo E.V.A</label></td>
		    	<td width="30%"><label>T&iacute;tulo contenido</label></td>
		    	<td width="20%"><label>Contenido</label></td>
		    	<td width="20%"><label>Calificaci&oacute;n</label></td>
		    </tr>
		</thead>
		<tbody id="cuerpo_tabla_resumen_notas" name="cuerpo_tabla_resumen_notas">
			<!-- -->
		</tbody>
	</table>	
	<div class="alert alert-danger" role="alert">
		<label>Pulse el siguiente Bont&oacute;n para generar la gr&aacute;fica  </label> <button type="button" id="btn_ver_grafica" name="btn_ver_grafica" class="btn btn-danger">Ver Gr&aacute;fica</button>
	</div>
	<div id="paginacion_consulta">        
      	<ul id="paginacion_tabla_resumen_notas" class="pagination"></ul>
    </div>
</div>