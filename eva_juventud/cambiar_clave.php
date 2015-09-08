<?php
require_once("./libs/fbasic.php");
if($_GET)
{	
	//recibo la url la decodifico y la dejo en la variable $_GET
	decode_get2($_SERVER["REQUEST_URI"]); 
	$correo=$_GET['correo'];
}
?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta author="gsantucci">
<!--librerias -->
<script type="text/javascript" src="js/fbasic.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/moment.js"></script>
<!-- CSS de la Tecnologia Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">
<!--<link href="css/bootsswatch.css" rel="stylesheet">-->
<!-- CSS DEL SISTEMA -->
<link href="css/index.css" rel="stylesheet">
<link href="css/bootstrap-3.1.1/fonts" rel="stylesheet">
<link href="css/simple-sidebar.css" rel="stylesheet">
<link href="font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href="css/datepicker" rel="stylesheet">
<script type="text/javascript">
//BLOQUE DE FUNCIONES
function validar_claves()
{
	if(($("#clave1").val())!=($("#clave2").val()))
	{
		mensajes2("Las claves nuevas deben coincidir");
		document.getElementById("clave2").focus();
		document.getElementById("clave2").value='';
		document.getElementById("clave1").value='';
	}	
	else
	{
		validar_longitud_claves(this);
	}
}//FIN DE FUNCION validar_claves
//funcion que valida los campos del formulario
function validacion_claves()
{
	if($("#clave1").val()=='')
	{
		mensajes2("Debe ingresar la nueva clave");
		return false;
	}
	else if($("#clave2").val()=='')
	{
		mensajes2("Debe ingresar la repetici&oacute;n de la nueva clave");
		return false;
	}
	else
	{
		return true;
	}	
}//FIN DE FUNCION validar_claves
//BLOQUE DE EVENTOS
$(document).ready(function(){
//////////////////////////////////////////////////////////////////////
$("#btn_limpiar_clave").click(function(){
	$("#clave1,#clave2,#clave3").val("");
});
$("#btn_guardar_clave").click(function(){
if(validacion_claves()==true)
{
	var correo="<?php  echo $correo; ?>";
	var clave1=$("#clave1").val();
	var clave2=$("#clave2").val();
	var data={
				correo:correo,
				clave1:clave1,
				clave2:clave2
	};
	$.ajax({
				url:"./controladores/controlador.cambiar_clave2.php",
				type:"POST",
				data:data,
				cache:false,
				//si ha ocurrido un error
		        error: function()
		        {
		            console.log(arguments);
		            mensajes(3);
		        },
		        success: function(html)
		        {
		          var recordset=$.parseJSON(html);
		          //alert(recordset);
		          if(recordset[0]=="error")
	              {
	                 mensajes(3);//error inesperado
	              }
	              else if(recordset[0]=="campos_blancos")
	              {
	                  mensajes(5);//error pasando campos vacios
	              }
	              else if(recordset[0]=="no_existe")
	              {
	                  mensajes(39);//no existe el correo
	              }
	              else if(recordset[0]=="modifico")
	              {
	                  mensajes(41);//correo enviado...

	              }
            	}
	});
}
});
$("#btn_ac_modal").click(function(){
	location.href="index.php";
});
/////////////////////////////////////////////////////////////////
});
</script>	
</head>
<body>
	<div id="form_1">	
	<form  class="form-horizontal" type="POST" name="form_cclave" id="form_cclave" role="form">	
		<fieldset>
	        <legend>
	            <h3>Recuperar Contrase&ntilde;a</h3>
	        </legend>
	    </fieldset>
			<div class="form-group">
				<div class="col-lg-12">
					<input type="password" name="clave1" id="clave1" placeholder="Ingrese su nueva contrase&ntilde;a" class="form-control" onKeyPress="return valida(event,this,17,30)" onBlur="valida2(this,17,30);" onchange="validar_longitud_claves(this);">
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					<input type="password" name="clave2" id="clave2" placeholder="Repita la nueva contrase&ntilde;a" class="form-control" onKeyPress="return valida(event,this,17,30)" onBlur="valida2(this,17,30);" onchange="validar_longitud_claves(this);">
				</div>
			</div>	
			<div class="col-lg-6">
				<button type="button" id="btn_guardar_clave" name="btn_guardar_clave" class="btn btn-info btn-form">Guardar</button>
			</div>
			<div class="col-lg-6">
				        <button type="reset" id="btn_limpiar_clave" name="btn_limpiar_clave" class="btn btn-warning btn-form">Limpiar</button>
			</div>	
			<input type="hidden" size="2" id="id_modalidad" name="id_modalidad">
	</form>
	</div>	
</body>
</html>
<?php 
include("mensajes_emergentes.html");//modal de mensajes
?>