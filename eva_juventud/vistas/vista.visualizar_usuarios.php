<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#filtro_aula_est").load("./controladores/controlador.misaula_virtual.php");
cargar_consulta_est(0,5,0);
$("#b_consulta_us").click(function(){
	cargar_consulta_est(0,5,0)	
});
//BLOQUE DE FUNCIONES
function ver_contacto(id_us,tipo)
{
	$("#program_body").load("./vistas/vista.usuario_consulta_perfil.php?id_us="+id_us+"&tipo_consulta="+tipo);
}
function agregar_contacto(id_us)
{
	var data={id_us:id_us};
	$.ajax({
				url:"./controladores/controlador.registrar_contacto_us.php",
				data:data,
				type:'POST',
				cache:'false',
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
						mensajes(3);//error
					}
					else
					if(recordset=="campos_blancos")
					{
						mensajes(5);//campos blancos
					}
					else
					if(recordset=='0')
					{
						mensajes(30);//contacto no agregado
					}
					else
					if(recordset!='0')
					{
						mensajes(31);//contacto agregado satisfactoriamente
						cargar_consulta_est(0,5,0);
					}	
				}	
	});
}
function cargar_consulta_est(offset,limit,actual)
{
	var nombre=$("#filtro_nombre_est").val();
	var aula=$("#filtro_aula_est").val();
	var data={
				offset:offset,
				limit:limit,
				actual:actual,
				nombre:nombre,
				aula:aula
	}
	$.ajax({
				url:"./controladores/controlador.visualizar_usuarios.php",
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
					vector=$.parseJSON(html);
					//alert(vector);
					recordset=vector[0];
					var cuerpo_usuarios='';
					var cuantos=recordset.length;
					cuerpo_usuarios="<div class='form-group cuerpo_est_pr'>";
					for(i=0; i<=cuantos-1;i++)
	            	{
		            	///////////////////////////////////////////////////////////////
						if (recordset[i][2]==null)
						{
							imagen_us="user.png";
						}	
						else
						{
							imagen_us=recordset[i][2];
						}
						//--Si el campo 6 >1 es xq esta como contacto
						n_us=recordset[i][3].split("@");
						nom_per="@"+n_us[0];
						if(recordset[i][6]>=1)
						{
							var btn_a="<a class='agregar_us' href='javascript:void(0)'' onclick='javascript:chatWith("+recordset[i][0]+",\""+nom_per+"\")'><i class='fa fa-weixin'></i> Chatear</a>";
						}
						else
						{
							var btn_a="<a class='agregar_us' onclick='agregar_contacto("+recordset[i][0]+")'><i class='fa fa-plus-circle'></i> Agregar</a>";
						}	
						//--
						cuerpo_usuarios+="<div class='col-lg-12'>\
												<div class='us_visualizar'>\
														<img id='imagen_usuario_est' src='./img/fotos_personas/"+imagen_us+"' class='img-circle img_fac_in pull-left' >\
													<div id='nombre_us_consulta' name='nombre_us_consulta'>@"+n_us[0]+"</div>\
													<div id='mensaje_us' name='mensaje_us' class='mensaje_uns_con_in'>"+recordset[i][4]+"</div>\
										  			<div id='botonera_mensajes_us' name='botonera_mensajes_us'>\
										  				<a class='ver_us' onclick='ver_contacto("+recordset[i][0]+",1)'><i class='fa fa-eye'></i> Visitar</a>\
										  				"+btn_a+"\
										  			</div>\
										  			<input type='hidden' size='2' name='id_us"+i+"' id='id_us"+i+"' value="+recordset[i][0]+">\
										 		</div>\
										   </div>";	
		            	///////////////////////////////////////////////////////////////
	            	}
	            	cuerpo_usuarios+="</div><div></div>";	
	            	$("#cuerpo_consulta_est").html(cuerpo_usuarios);
	            	$("#paginacion_est").html(vector[1]);
				}
	});
}
</script>
<div id="cuerpo_aula" class="cuerpo_aula">
		<div id="titulo_eva" name="titulo_eva">
			<h1 class="titulo_eva"><i class="fa fa-users" style="padding: 1%;"></i> Estudiantes</h1>
		</div>
		<div id="cuerpo_filtro_studiantes" class="form-group">
			<div class="col-lg-4">
				<input type="text" name="filtro_nombre_est" id="filtro_nombre_est" class="form-control input-sg" onKeyPress='return valida(event,this,19,50)' onBlur='valida2(this,19,50);' placeholder="Filtro por nombres y apellidos">
			</div>
			<div class="col-lg-6">
				<select name="filtro_aula_est" id="filtro_aula_est" class="form-control">
					<option id='-1' value='-1'>[E.V.A]</option>
				</select>	
			</div>
			<div class="col-lg-2">
				<button type="button" id="b_consulta_us" name="b_consulta_us" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
			</div>	
		</div>	
		<div id="cuerpo_consulta_est" name="cuerpo_consulta_est">
		</div>
		<div id="paginacion_consulta">        
      	<ul id="paginacion_est" class="pagination"></ul>
    </div>
</div>