<?php
include("../controladores/controlador.aula_virtual_aprobadas.php");
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
$("#btn_generar_certificado").click(function(){
	 if($("#aula_virtual_evaluacion").val()!="-1")
{  
 var aula=$("#aula_virtual_evaluacion").val();
 var data={aula:aula};
 $.ajax({
          url:"./controladores/controlador.consultar_certificado.php",
          data:data,
          type:"POST",
          cache:false,
          error: function()
          {
              console.log(arguments);
              mensajes(3);
          },
          success: function(data)
          {
              var recordset=$.parseJSON(data);
              alert(recordset);
              if(recordset[0]=="error")
              {
                mensajes(7);//error en base de datos
              }else if(recordset=="")
              {
                mensajes(33);//no se ha configurado el certificado
              }
              else
              {
                $("#form_generar_certificados").attr("action","./controladores/controlador.formato_certificado2.php");
                $("#form_generar_certificados").submit(); 
              }
          }      
 });
}else
{
  mensajes(34);//debe selecciona un aula
} 
 
});
//BLOQUE DE FUNCIONES
</script>
<div id="form_1" name="form_1">	
<form  class="form-horizontal" method="POST" name="form_generar_certificados" id="form_generar_certificados" target="_blank" role="form">	
	<fieldset>
        <legend>
            <h3>Generar certificado</h3>
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
			<button type="button" id="btn_generar_certificado" name="btn_generar_certificado" class="btn btn-info btn-form">Generar certificado</button>
		</div>
<form>
</div>