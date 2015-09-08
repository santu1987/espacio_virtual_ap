<?php
include("../controladores/controlador.aula_virtual_us.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#btn_generar_grafica").click(function(){
if($("#aula_virtual_evaluacion").val()!="-1")
{
      var id_aula=$("#aula_virtual_evaluacion").val();
//---
    $("#cuadro_grafica").load("./vistas/vista.grafica_notas.php?id_aula="+id_aula);
      $('html, body').stop().animate({scrollTop: 300}, 2000);
//---  
}else
{
  mensajes(34);//debe selecciona un aula
  $("#cuadro_grafica").html("");
} 
 
});
//BLOQUE DE FUNCIONES
</script>
<div id="form_1" name="form_1">	
<form  class="form-horizontal" method="POST" name="form_generar_grafica" id="form_generar_grafica" role="form">	
	<fieldset>
        <legend>
            <h3>Generar gr&aacute;fico seg&uacute;n E.V.A</h3>
        </legend>
    </fieldset>
		<div class="form-group">
			<div class="col-sm-12">
				<select id="aula_virtual_evaluacion" name="aula_virtual_evaluacion" class="form-control">
					<?php echo $opcion_aula_virtual;?>
				</select>
			</div>
		</div>	
		<div class="col-lg-12">
			<button type="button" id="btn_generar_grafica" name="btn_generar_grafica" class="btn btn-info btn-form">Generar gr&aacute;fica</button>
		</div>

<form>
</div>
<div id="cuadro_grafica" name="cuadro_grafica">
</div>  