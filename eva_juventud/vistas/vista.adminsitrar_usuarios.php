<?php
session_start();
require('../controladores/controlador.consultar_tipo_us.php');
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#rep_nacionalidad_us,#rep_cedula_us,#rep_nombre_us,#rep_correo_us,#rep_tipo_us").keypress(function(event){
    if(event.which==13)
    {
    	consultar_cuerpo_us(0,5,0);
    }
});
consultar_cuerpo_us(0,5,0);
//BLOQUE DE FUNCIONES
function ir_us(id_persona)
{
	$("#program_body").load("./vistas/vista.registrar_usuario.php?id_persona="+id_persona);
}
function ir_activar_us(id_persona,num)
{
	var data={id_persona:id_persona};
	$.ajax({
				url:"./controladores/controlador.activar_usuarios.php",
				type:"POST",
				data:data,
				cache:false,
				error: function(request,error) 
	            {
	                  console.log(arguments);
	                  mensajes(3);//error desconocido
	            },
	            success: function(html)
	            {
	            /////////////////////////////////////////////////////////	
	                    var recordset=$.parseJSON(html);
	                   // alert(recordset);
	                    if(recordset[0]=="error")
	                    {
	                    	mensajes(7);//error en base de datos
		                }else
		                if(recordset[0]=="campos_blancos")
		                {
		                	mensajes(5);//campos en blanco
		                }
		                else
		                if(recordset[0]=="no_existe")
		                {
		                	mensajes(38);//no existe
		                }
		                else
		                if(recordset[0]=="activo")
		                {
		                	$("#btn_activar"+num).removeClass("btn-danger").addClass("btn-success");
							$("#btn_activar"+num).prop("title","Inactivar usuario");
		                	mensajes(36);//usuario activo
		                }
		                else
		                if(recordset[0]=="inactivo")
		                {
		                	$("#btn_activar"+num).removeClass("btn-success").addClass("btn-danger");
							$("#btn_activar"+num).prop("title","Activar usuario");
		                	mensajes(37);//usuario inactivo
		                }
		        /////////////////////////////////////////////////////////
		        }  
	});
}
function ir_perfil(nac,cedula)
{
	$("#program_body").load("./vistas/vista.perfil_usuario.php?nacionalidad="+nac+"&cedula="+cedula);
}
function consultar_cuerpo_us(offset,limit,actual)
{
	var rep_nacionalidad_us=$("#rep_nacionalidad_us").val();
	var rep_cedula_us=$("#rep_cedula_us").val();
	var rep_nombre_us=$("#rep_nombre_us").val();
	var rep_correo_us=$("#rep_correo_us").val();
	var rep_tipo_us=$("#rep_tipo_us").val();
	var data={
				nacionalidad:rep_nacionalidad_us,
				cedula:rep_cedula_us,
				nombre:rep_nombre_us,
				correo:rep_correo_us,
				tipo_us:rep_tipo_us,
				offset:offset,
				limit:limit,
				actual:actual
			 };
	$.ajax({
				url:"./controladores/controlador.cuerpo_tabla_us.php",
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
					$("#cuerpo_tabla_us").html(recordset[0]);//cuerpo de la tabla
                	$("#paginacion_tabla_us").html(recordset[1]);//paginacion					
				}		
	});
}
</script>
<div class="tabla_body">
<fieldset>
        <legend>
            <h3 id='titulo_principal_un'>Adminsitrar Usuarios</h3>
        </legend>
 	</fieldset>
	<div class="form-horizontal">
		 	<div class="form-group">
		 		<div class="col-lg-2">
					<select id="rep_nacionalidad_us" name="rep_nacionalidad_us" class="form-control" onblur="consultar_cuerpo_us(0,5,0);">
						<option id="-1" value="-1">[Nacionalidad]</option>
						<option id="V" value="V">Venezolano</option>
						<option id="E" value="E">Extranjero</option>
					</select>
				</div>
				<div class="col-lg-2">
					<input type="text" id="rep_cedula_us" name="rep_cedula_us"  placeholder="Filtro por nº Cédula" class="form-control input-sg input-filtros" onblur="valida2(this,10,8);consultar_cuerpo_us(0,5,0);" onkeypress="return valida(event,this,10,8)">
				</div>
				<div class="col-lg-2">
					<input type='text' name='rep_nombre_us' id='rep_nombre_us' placeholder='Filtro nombres y apellidos' class='form-control input-sg input-filtros' onblur='valida2(this,19,50);consultar_cuerpo_us(0,5,0);' onKeyPress="return valida(event,this,19,50)">
				</div>
				<div class="col-lg-4">
					<input type='text' name='rep_correo_us' id='rep_correo_us' placeholder='Filtro por Correo electr&oacute;nico' class='form-control input-sg input-filtros' onblur='correo(this);valida2(this,7,100);consultar_cuerpo_us(0,5,0);' onKeyPress="return valida(event,this,7,100)">
				</div>
				<div class="col-lg-2">
					<select id="rep_tipo_us" name="rep_tipo_us" class="form-control" onchange="consultar_cuerpo_us(0,5,0);">
					<?php echo $opcion_tp_us;?>
					</select>
				</div>
			</div>
			<table class="table table-hover" width="100%">
				<thead id="cabecera_tabla_us" name="cabecera_tabla_us">
				    <tr>
				    	<td width="5%"><label>Nacionalidad</label></td>
				    	<td width="5%"><label>C&eacute;dula</label></td>
				    	<td width="20%"><label>Nombres y apellidos</label></td>
				    	<td width="10%"><label>Correo Electr&oacute;nico</label></td>
				    	<td width="10%"><label>Tel&eacute;fono</label></td>
				    	<td width="15%"><label>Tipo</label></td>
				    	<td width="20%"><label>Acciones</label></td>	
				    </tr>
				</thead>
				<tbody id="cuerpo_tabla_us" name="cuerpo_tabla_us">
					<!-- -->
				</tbody>
			</table>	
			<div id="paginacion_consulta">        
		      	<ul id="paginacion_tabla_us" class="pagination"></ul>
		    </div>
		    <div id="botonera_vv" class="form-group">
		    	<div class="col-lg-10"></div>
		    	<div id="div_volver" class="col-lg-2"></div>
		    </div>	
		</div>    
	</div>
</div>
