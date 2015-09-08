<?php
  require('controladores/controlador.verificar_session_abierta.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta author="gsantucci">
    <title>E.V.A | Juventud</title>
    <!--librerias -->
    <script type="text/javascript" src="js/fbasic.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/bootstrap-dropdown.js"></script>
    <!-- CSS de la Tecnologia Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!--<link href="css/bootsswatch.css" rel="stylesheet">-->
    <!-- CSS DEL SISTEMA -->
    <link href="css/index.css" rel="stylesheet">
    <link href="css/bootstrap-3.1.1/fonts" rel="stylesheet">
    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link href="font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- -->
    <link rel="shortcut icon" href="./img/juventud.jpg" type="image/jpg" />
<script type="text/javascript">
    $(document).ready(function(){
       ////////////////// load
       $("#titleh1").html("Espacio virtual de aprendizaje");
       //$("#cuadro_principal").load("./vistas/vista.consultar_mision.php");
       $("#cuadro_principal").load("./vistas/vista.iniciar_sesion.php");
       /////////////////////////////////////////////////////////////////    
  });
  //fin document.ready
</script>
  </head>
  <body>
    <header>
      <div id="cabecera" ><img src="img/gobiernobolivariano-logo.png" ><img src="img/logo_juventud200.png" class="imagen_j2"></div>
    </header>
   <!-- cuerpo de la página-->
    <div id="container_titleh1" class="content-header cuprum">
          <h1 id="titleh1" class="caja_titulo">
            <a id="menu-toggle" href="#" class="btn btn-default"><i class="icon-reorder"></i></a>

          </h1>
    </div>      
   <!--Pestañas --> 
    <div id="cuadro_principal" class="cuprum" >
      <noscript><div class="bg-danger" id="no_js" name="no_js"><span class="glyphicon glyphicon-ban-circle"></span> Disculpe: Su navegador tiene inhabilitado complementos javascript, habilitelos o actualice su navegador (Recomendablemente use  Mozilla Firefox)!</div></noscript>
    </div>  
    <div id="pie_pag" class="contendor_pie_pagina">  
      <div style="background-color:#23ADEF;width:25%;height:5px;float:left" id=""></div>
      <div style="background-color:#EC2B8A;width:25%;height:5px;float:left" id=""></div>
      <div style="background-color:#F3702B;width:25%;height:5px;float:left" id=""></div>
      <div style="background-color:#ABCF38;width:25%;height:5px;float:left" id=""></div>
      <img src="./img/logo_t.png" class="img_foot" >
    </div>
  
  </body>
</html>
<?php 
include("modal_inicio_s.html");//modal inicio de sesion
include("modal_rec_c.html");//modal de recuperar contraseña
include("mensajes_emergentes.html");//modal de mensajes
?>