<?php
session_start();
if(isset($_GET['id_us'])){ if($_GET['id_us']!=""){ $id_us=$_GET['id_us']; }else{ $id_us='';} }else{ $id_us='';}
if(isset($_GET['tipo_consulta'])){ if($_GET['tipo_consulta']!=""){ $tipo_consulta=$_GET['tipo_consulta']; }else{ $tipo_consulta='';} }else{ $tipo_consulta='';}
?>
<script type="text/javascript">
//BLOQUE DE FUNCIONES
function consulta_principal()
{
	var id_us="<?php echo $id_us; ?>";
	var data={id_us:id_us};
	$.ajax({
				url:"./controladores/controlador.consulta_perfil_us.php",
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
					var recordset=vector[0];
					var cursos_par=vector[1];
					var login=recordset[0][1].split("@");
					var login2="@"+login[0];
					$("#login_us").html(login2);
					var imagen_us="";
					if((recordset[0][6]!="")&&(recordset[0][6]!=null))
					{
						imagen_us=recordset[0][6];
					}
					else
					{
						imagen_us="user.png";
					}	
					var img_us='<img id="img_uss" name="img_uss" src="./img/fotos_personas/'+imagen_us+'"  alt="..." class="img-circle img_fa pull-right">';
					$("#img_us_perfil").html(img_us);
					$("#perfil_h3").html(recordset[0][8]);
					$("#nombre_us_h4").html(recordset[0][5]);
					$("#nombre_estado_h4").html(recordset[0][9]);
					//
					if(cursos_par!="")
					{
						///////////////////////////////////////////
						if(recordset[0][10]==1)
						{
							var cursos="Cursos impartidos:";
						}else
						{
							var cursos="Cursos en los que est&aacute; inscrito:";
						}	
						////////////////////////////////////////////
					}	
					$("#cursos_perfil").html(cursos);
					$("#cursos_participa").html(cursos_par);
					$("#perfil_usuario").val(recordset[0][10]);
					$("#id_usuario").val(recordset[0][0]);
					//	
				}	
	});
}
//BLOQUE DE EVENTOS
consulta_principal();
$("#btn_aula_insc").click(function()
{
	var id_us=$("#id_usuario").val();
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
					}	
				}	
	});
});
$("#btn_volver_consulta").click(function(){
var tipo_consulta="<?php echo $tipo_consulta;?>";
	if(tipo_consulta==1)
	{
		////////////////////////////////////
		if($("#perfil_usuario").val()=="1")
		{
			$("#program_body").load("./vistas/vista.visualizar_facilitadores.php");
		}
		else
		{
			$("#program_body").load("./vistas/vista.visualizar_usuarios.php");
		}
		////////////////////////////////////
	}else
	if(tipo_consulta==2)
	{
			$("#program_body").load("./vistas/vista.consultar_miscontactos.php");
	}	
});
</script>
<div class="cuerpo_aula">
	<div id="titulo_eva" name="titulo_eva">
			<h1 class="titulo_eva"><i class="fa fa-laptop" style="padding:1%;"></i><label id="login_us"> </label></h1>
	</div>
	<!-- Panel:  -->
	<div id="contenedor_us_consulta" class="col-lg-12">
		<!--imagen:facilitador --> 
		<div class="col-lg-4">
			<div id="img_us_perfil" name="img_us_perfil" class="img_us_perfil">
			</div>
		</div>	
	  	<div class="col-lg-4">
		    <h3 id="perfil_h3">Facilitador</h3>
			<h4 id="nombre_us_h4">@aqui va el nombre</h4>
			<h4 id="nombre_estado_h4">aqui va el Estado</h4>
		</div>
		<div class="col-lg-4">
			<h3 id="cursos_perfil"></h3>
			<h4 id="cursos_participa"></h4>
		</div>	
	</div>

<legend></legend>
<div>
	<button id="btn_volver_consulta" name="btn_volver_consulta" class="btn btn-warning btn-izq">Volver</button>
	<button id="btn_aula_insc" name="btn_aula_insc" class="btn btn-primary ">Agregar contacto</button>
</div>
<input type="hidden" size="2" id="perfil_usuario" name="perfil_usuario">
<input type="hidden" size="2" id="id_usuario" name="id_usuario">
