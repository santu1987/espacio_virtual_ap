<?php
session_start();
require_once("../controladores/controlador.tipo_estudio.php");
$id_perfil=$_SESSION["id_perfil"];
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
consultar_cuerpo_tabla_aulas_adm(0,5,0);
$("#f_fecha_aula").datetimepicker({ 
    lang:'es',
    timepicker:false,
    format:'d-m-Y',
    formatDate:'Y-m-d',
});
$("#f_nombre_eva,#f_fecha_aula,#f_selec_tipo").keypress(function(event){
    if(event.which==13){
    	consultar_cuerpo_tabla_aulas_adm(0,5,0);
    }
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
function habilitar_aula(id_aula,num)
{
	var data={id_aula:id_aula};
	$.ajax({
				url:"./controladores/controlador.habilitar_aula.php",
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
					//alert(recordset[1]);
					if(recordset=="error")
					{
						mensajes(3);//error inesperado
					}
					else if(recordset=="campos_blancos")
					{
						mensajes(6);//error pasando campos vacios
					}
					else if(recordset[0]=="registro_exitoso")
					{
						if (recordset[1]=='1')
						{
							$("#btn_ap_cerrar"+num).removeClass("btn-danger").addClass("btn-success");
							$("#btn_ap_cerrar"+num).prop("title","Inactivar E.V.A");
							mensajes(23);
						}
						else
						{
							$("#btn_ap_cerrar"+num).removeClass("btn-success").addClass("btn-danger");
							$("#btn_ap_cerrar"+num).prop("title","Activar E.V.A");
							mensajes(24);
						}	
					}
				}
	});
}
function consultar_conf_eva(id_aula)
{
	$("#program_body").load("./vistas/vista.configurar_eva.php?id_aula="+id_aula);
}
function ir_aula_resumen(id_aula)
{
	$("#program_body").load("./vistas/vista.espacio_virtual.php?id_aula="+id_aula);
}
function consultar_cuerpo_tabla_aulas_adm(offset,limit,actual)
{
	//--Inahbilitar columna acciones para persona vicemin
	//--
	var f_nombre_eva=$("#f_nombre_eva").val();
	var f_fecha_aula=$("#f_fecha_aula").val();
	var f_selec_tipo=$("#f_selec_tipo").val();
	var data={
				f_nombre_eva:f_nombre_eva,
				offset:offset,
				limit:limit,
				actual:actual,
				f_fecha_aula:f_fecha_aula,
				f_selec_tipo:f_selec_tipo
	};
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_eva_adm.php",
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
						$("#cuerpo_tabla_aula_adm").html(recordset[0]);//cuerpo de la tabla
                		$("#paginacion_tabla_consulta_adm").html(recordset[1]);//paginacion
					}	
				}
	});
}
</script>
<div class="tabla_body">
	<fieldset>
        <legend>
            <h3>Espacios Virtuales de Aprendizaje E.V.A's</h3>
        </legend>
 	</fieldset>
 	<div class="form-group">
		<div class="col-lg-6">
			<input type='text' name='f_nombre_eva' id='f_nombre_eva' placeholder='Filtro por nombre de E.V.A' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_tabla_aulas_adm(0,5,0);' onKeyPress="return valida(event,this,18,100)">
		</div>
		<div id='t_fecha' class="col-lg-2">
				<input type='text' name='f_fecha_aula' id='f_fecha_aula' class='form-control input-sg input-filtros' onblur='consultar_cuerpo_tabla_aulas_adm(0,5,0);' onkeyup="this.value=formateafecha(this.value);" placeholder="Filtro Fecha :dd/mm/aaaa">
		</div>
		<div id='t_tipo_eva' class="col-lg-4">
				<SELECT id='f_selec_tipo' name='f_selec_tipo' class='form-control' onblur='consultar_cuerpo_tabla_aulas_adm(0,5,0);'>
				 <?php echo $option_estudio; ?>
				</SELECT>
		</div>	
	</div>
	<table class="table table-hover" width="100%">
		<thead id="cabecera_tabla_adm" name="cabecera_tabla_adm">
		    <tr>
		    	<td width="10%"><label>EVA</label></td>
		    	<td width="20%"><label>T&iacute;tulo</label></td>
		    	<td width="30%"><label>Resum&eacute;n</label></td>
		    	<td width="10%"><label>Tipo de EVA</label></td>
		    	<td width="10%" style="text-align:left;"><label>Fecha de inicio</label></td>
		    	<td width="30%"><label>Acciones</label></td>
		    </tr>
		</thead>
		<tbody id="cuerpo_tabla_aula_adm" name="cuerpo_tabla">
			<!-- -->
		</tbody>
	</table>	
	<div id="paginacion_consulta">        
      	<ul id="paginacion_tabla_consulta_adm" class="pagination"></ul>
    </div>
</div>