<?php
session_start();
require_once("../controladores/controlador.tipo_estudio.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
consultar_cuerpo_tabla_auditoria(0,5,0);
$("#f_fecha").datetimepicker({ 
    lang:'es',
    timepicker:false,
    format:'d-m-Y',
    formatDate:'Y-m-d',
});
$("#f_seccion,#f_accion,#f_us,#f_ip,#f_fecha").keypress(function(event){
    if(event.which==13)
    {
    	consultar_cuerpo_tabla_auditoria(0,5,0);
    }
});    
$("#btn_ver_pdf_auditoria").click(function(){
	$("#form_auditoria").attr("action","./vistas/vista_pdf.reporte_auditoria.php");
    $("#form_auditoria").submit();
});
//BLOQUE DE FUNCIONES
function consultar_cuerpo_tabla_auditoria(offset,limit,actual)
{
	var f_seccion=$("#f_seccion").val();
	var f_accion=$("#f_accion").val();
	var f_us=$("#f_us").val();
	var f_ip=$("#f_ip").val();
	var f_fecha=$("#f_fecha").val();
	var data={
				f_seccion:f_seccion,
				f_accion:f_accion,
				f_us:f_us,
				f_ip:f_ip,
				f_fecha:f_fecha,
				offset:offset,
				limit:limit,
				actual:actual
	};
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_auditoria.php",
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
						$("#cuerpo_tabla_auditoria").html(recordset[0]);//cuerpo de la tabla
                		$("#paginacion_tabla_auditoria").html(recordset[1]);//paginacion
					}	
				}
	});
}
</script>
<div class="tabla_body">
	<fieldset>
        <legend>
            <h3>Auditoria del sistema</h3>
        </legend>
 	</fieldset>
 	<form id="form_auditoria" name="form_auditoria" method="POST" target="_blank">	
 	<div class="form-horizontal">
	 	<div class="form-group">
			<div class="col-lg-4">
				<input type='text' name='f_seccion' id='f_seccion' placeholder='Filtro por secci&oacute;n' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_tabla_auditoria(0,5,0);' onKeyPress="return valida(event,this,18,100)">
			</div>
			<div class="col-lg-4">
				<input type='text' name='f_accion' id='f_accion' placeholder='Filtro por acci&oacute;n' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_tabla_auditoria(0,5,0);' onKeyPress="return valida(event,this,18,100)">
			</div>
			<div class="col-lg-4">
				<input type='text' name='f_us' id='f_us' placeholder='Filtro por Usuario' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_tabla_auditoria(0,5,0);' onKeyPress="return valida(event,this,18,100)">
			</div>
		</div>
		<div class="form-group">	
			<div class="col-lg-4">
				<input type='text' name='f_ip' id='f_ip' placeholder='Filtro por IP' class='form-control input-sg input-filtros' onblur='valida2(this,18,100);consultar_cuerpo_tabla_auditoria(0,5,0);' onKeyPress="return valida(event,this,18,100)">
			</div>
			<div id='t_fecha' class="col-lg-4">
					<input type='text' name='f_fecha' id='f_fecha' class='form-control input-sg input-filtros' onblur='consultar_cuerpo_tabla_auditoria(0,5,0);' onkeyup="this.value=formateafecha(this.value);" placeholder="Filtro Fecha">
			</div>
		</div>
	<table class="table table-hover" width="100%">
		<thead id="cabecera_tabla_auditoria" name="cabecera_tabla_auditoria">
		    <tr>
		    	<td width="40%"><label>Secci&oacute;n</label></td>
		    	<td width="30%"><label>Acci&oacute;n</label></td>
		    	<td width="10%"><label>Usuario</label></td>
		    	<td width="10%"><label>IP</label></td>
		    	<td width="10%" style="text-align:left;"><label>Fecha</label></td>
		    </tr>
		</thead>
		<tbody id="cuerpo_tabla_auditoria" name="cuerpo_tabla_auditoria">
			<!-- -->
		</tbody>
	</table>
	<div class="alert alert-danger" role="alert">
				<label>Pulse el siguiente Bont&oacute;n para generar el PDF </label> <button type="button" id="btn_ver_pdf_auditoria" name="btn_ver_pdf_auditoria" class="btn btn-danger">Ver pdf</button>
	</div>
	<div id="paginacion_consulta">        
      	<ul id="paginacion_tabla_auditoria" class="pagination"></ul>
    </div>
	</div>
	</form>	
</div>