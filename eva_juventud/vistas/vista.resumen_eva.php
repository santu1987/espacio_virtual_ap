<?php
	$aula=$_GET["id_eva"];
?>
<script type="text/javascript">

//BLOQUE DE EVENTOS
cargar_entorno_resumen_eva();
$("#btn_iraula").click(function(){
	var id_aula=$("#id_aula").val();
	$("#program_body").load("./vistas/vista.espacio_virtual.php?id_aula="+id_aula);
});
$("#btn_volver_consulta").click(function(){
	$("#program_body").load("./vistas/vista.consultar_aulas.php");
});
$("#btn_aula_insc").click(function(){
bootbox.confirm("¿Realmente desea inscribirse en la siguiente aula?", function(result) 
{
	if (result)
	{
	//
		var id_eva=$("#id_aula").val();
		var data={id_eva:id_eva};
		$.ajax({
					url:"./controladores/controlador.registrar_inscripcion.php",
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
						if(recordset=="error_bd")
						{
							mensajes(2);//error inesperado
						}
						else
						if(recordset[0]=="registro_exitoso")	
						{
							mensajes(6);//operacion realizada con exito
							$("#btn_iraula").css("display","block");
							$("#btn_aula_insc").css("display","none");
						}
						else
						if(recordset[0]=="existe")
						{
							mensajes(17);//el registro ya existe...
						}	
					}
		});

	//       
	}//fin de if
});//fin de bootbox
//
});

//BLOQUE DE FUNCIONES
function cargar_entorno_resumen_eva()
{
	var id_eva="<?php echo $aula; ?>";
	var data={id_eva:id_eva};
	$.ajax({
				url:"./controladores/controlador.resumen_eva.php",
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
					if(recordset=="error_bd")
					{
						mensajes(2);//error inesperado
					}
					else
					{
						var vector_aula=recordset[0];
						$("#id_aula").val(vector_aula[0][0]);
						$("#titulo_eva_resumen").html("<h1 class='titulo_eva'><i class='fa fa-university fa-circle'></i> "+vector_aula[0][1]+"</h1>");
						$("#introduccion_panel").html(vector_aula[0][2]);
						$("#resumen_panel").html(vector_aula[0][3]);
						$("#objetivos_panel").html(vector_aula[0][4]);
						$("#facilitador_panel").html(vector_aula[0][5]);
						$("#facilitador_h3").html("Facilitador: "+vector_aula[0][7]);
						$("#imagen_fa").attr('src','./img/fotos_personas/'+vector_aula[0][6]);
						$("#unidades_panel").html(recordset[1]); 
						$("#id_inscripcion").val(recordset[2]);
						$("#imagen_aula").attr('src','./img/img_eva/'+vector_aula[0][8]);   
						//cambio habilitacion de campos
						if($("#id_inscripcion").val()!="")
						{	
							document.getElementById("btn_aula_insc").style.display="none";
							document.getElementById("btn_iraula").style.display="";
						}
					}	
				}
	});
}
////
$("#pie_pag").removeClass("contendor_pie_pagina").addClass("contendor_pie_pagina2");
</script>
<div class="cuerpo_aula">
	<legend class="titles_table">	
		<div id="titulo_eva_resumen" name="titulo_eva_resumen">
			<h1>Aqui va el nombre del eva</h1>
		</div>
	</legend>
	<!-- Panel: Introduccion -->
	
	<!-- -->
	<div class="media">
		<a class="pull-left contenedor_img_aula" href="#">
			<img id="imagen_aula" class="media-object img_aula_x img-rounded">
		</a>
		<div class="media-body contenedor_int">
			<h1 class="media-heading">Introducci&oacute;n</h1>
			<div id="introduccion_panel" name="introduccion_panel">
			</div>
		</div>
	</div>	
	<!-- -->
	<!-- Panel: resumén -->
	<legend></legend>
	<div class="form-group">
	  	<div class="col-lg-12">
		    <h1>Resumen</h1>
		</div>
		<div class="col-lg-12" id="resumen_panel" name="resumen_panel">
		</div>
	</div>
	<!-- -->
	<legend></legend>
	<div class="form-group">
		<!-- Panel: resumén  -->
		<div class="col-lg-6 row row_resumen">
		  	<div>
			    <h1>Objetivos</h1>
			</div>
			<div id="objetivos_panel" name="objetivos_panel">
			
			</div>
		</div>
		<!-- --	>
		<!-- Panel: Unidades -->
		<div class="col-lg-6 row row_resumen">
		  	<div>
			    <h1>Unidades</h1>
			</div>
			<div id="unidades_panel" name="unidadess_panel">
			    
			</div>
		</div>
		<!-- -->
	</div>	
	<!-- --	>
	<!-- -->
	<legend></legend>
	<!-- Panel:  -->
	<div id="contenedor_facilitador" class="col-lg-12">
	<!--imagen:facilitador --> 
		<div class='col-lg-6'> 
			<div id="imagen_facilitador" name="imagen_facilitador" class="img_facilitador">
		        <img id="imagen_fa" name="imagen_fa" src="<?php echo './img/'.$_SESSION["img_us"];?>"  alt="..." class="img-circle img_fa pull-right">
			</div>
		</div>	
	  	<div class="col-lg-6">
		    <h3 id="facilitador_h3">Facilitador</h3>
		</div>
		<div class="col-lg-12" id="facilitador_panel" name="facilitador_panel">
		    
		</div>
	</div>

<legend></legend>
<div>
	<button id="btn_volver_consulta" name="btn_volver_consulta" class="btn btn-warning" style="float:left;margin-left:80%;margin-right:1%;">Volver</button>
	<button id="btn_aula_insc" name="btn_aula_insc" class="btn btn-primary">Inscribirme</button>
	<button id="btn_iraula" name="btn_iraula" class="btn btn-primary " style="display:none;">Ir aula</button>
</div>
<input type="hidden" name="id_aula" id="id_aula" size="2" >
<input type="hidden" name="id_inscripcion" id="id_inscripcion" size="2" >
