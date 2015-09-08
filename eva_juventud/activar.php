<?php
require_once("./libs/fbasic.php");
require_once("./modelos/modelo.activacion.php");
if($_GET)
{	
	//recibo la url la decodifico y la dejo en la variable $_GET
	decode_get2($_SERVER["REQUEST_URI"]); 
	//echo "<br>";
	//ya puedo hacer uso de la variable $_GET
	// imprimo la variable $_GET
	//print_r($_GET);
	//accedo a un valor de la variable	
	//echo "siteurl = ". $_GET['nacionalidad'];
	//echo "<br>cof = ". $_GET['cedula'];
	$nac=$_GET['nacionalidad'];
	$cedula= $_GET['cedula'];
	$obj_us=new Activacion_Us();
	$rs=$obj_us->modificar_estatus($nac,$cedula);
	if($rs=="error")
	{
		$mensaje='error';		
	}
	else
	{
		$mensaje='cambio_efectuado';
	}
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
    <script type="text/javascript" src="js/valida.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
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
	$(document).ready(function(){
	    var mensaje="<?php echo $mensaje;  ?>";
		if(mensaje=='error')
		{
			mensajes2("Error: en activaci&oacute;n de cuenta de usuario");
		}
		else
		{
			//alert(mensaje);
			mensajes2("Cuenta activa: ya puedes iniciar sesi&oacute;n");
		}
		/////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////
	    $("#btn_ac_modal").click(function(){
	    	location.href="index.php";
	    });
	});
	</script>
<?php 
include("mensajes_emergentes.html");//modal de mensajes
?>