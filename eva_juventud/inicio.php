<?php
session_start();
//require('controladores/controlador.verifica_sesion.php');
$user=split("@",$_SESSION["usuario"]);
$perfil=$_SESSION["id_perfil"];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta httpequiv="ContentType" content="text/html; charset=UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta author="gsantucci">
    <title>E.V.A | Juventud</title>
    <!--librerias -->
    <!--Archivos Js -->
   
    <script type="text/javascript" src="js/fbasic.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>
    <script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="js/bootbox.min.js"></script>
    <!--Archivos Js del chat -->
    <script type="text/javascript" src="js/chat.js"></script>
    <!-- Archivos Js para las graficas -->
    <script type="text/javascript" src="js/highstock.js"></script>
    <script type="text/javascript" src="js/exporting.js"></script>
    <!-- CSS de la Tecnologia Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/jquery-ui.css" rel="stylesheet">
    <!-- CSS del chat -->
    <link href="css/chat/chat.css" rel="stylesheet">
     <!--<link href="css/chat/screen.css" rel="stylesheet">
    <link href="css/chat/screen_ie.css" rel="stylesheet">
    <!--<link href="css/bootsswatch.css" rel="stylesheet">-->
    <!-- CSS DEL SISTEMA -->
    <link href="css/index.css" rel="stylesheet">
    <link href="css/jquery.datetimepicker.css" rel="stylesheet">
    <link href="css/bootstrap-3.1.1/fonts" rel="stylesheet">
    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link href="font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./img/juventud.jpg" type="image/jpg" />
    <!-- BLOQUE JS -->
    <script type="text/javascript">
    //programacion de boton de perfil us
    function cargar_perfil_us()
    {
      $("#program_body").load("./vistas/vista.registrar_usuario.php");
    }
    //programacion de boton de cerrar session
    function cerrar_session(){
        $.ajax({
                  url:'./controladores/controlador_cerrar_session.php',
                  type:'POST',
                  data:"",
                  cache:false,
                  error: function()
                  {
                      console.log(arguments);
                          mensajes(3);
                  },
                  success: function(resp)
                  {
                          window.location.href = resp;
                  }
        });
        /*document.form_principal.action='./controladores/controlador_cerrar_session.php';
        document.forms.form_principal.submit();*/
    }
    //////  
    $(document).ready(function(){
       ////////////////// load
       var logo ="<img src='img/logo_oficial2.png' class='img_juventud'>";
       $("#titleh1").html(logo);
       $("#program_body").load("./vistas/vista.pantalla_inicial.php");
      //para generar manual de ayuda
      $("#ayuda_us").click(function(){
        var perfil="<?php echo $_SESSION['id_perfil']?>";
        if(perfil==10)//estudiante
        {
          $("#form_principal").attr("action","./manuales/manual_estudiante.pdf");  
        }else
        if(perfil==4)//administrador
        {
          $("#form_principal").attr("action","./manuales/manual_administrador.pdf"); 
        }else
        if(perfil==1)//facilitador
        {
          $("#form_principal").attr("action","./manuales/manual_facilitador.pdf"); 
        } 
          $("#form_principal").submit(); 
      });
      //para ingresar al actualizar datos de usuarios
        $("#datos_perfil_us").click(function(){
          $("#program_body").load("./vistas/vista.registrar_usuario.php");
        });
      //para consultar las calificaciones según aula
      $("#puntuacion_us").click(function(){
         $("#program_body").load("./vistas/vista.graficas_evas.php"); 
      });  
      //para ingresar al asignar perfil
        $("#asignar_perfil_us").click(function(){
          $("#program_body").load("./vistas/vista.perfil_usuario.php");
        });
      //para adminsitrar usuarios
      $("#administrar_usuario").click(function(){
          $("#program_body").load("./vistas/vista.adminsitrar_usuarios.php");
      });  
      //para ingresar a modalidad de estudio
        $("#mod_estudio").click(function(){
          $("#program_body").load("./vistas/vista.mod_estudio.php");
        });
      //para ingresar el tipo de usuario 
      $("#tipos_usuarios").click(function(){
        $("#program_body").load("./vistas/vista.registrar_tipo_us.php");
      }); 
      //para ingresar la configuracion del eva-facilitadores
      $("#configurar_eva").click(function(){
        $("#program_body").load("./vistas/vista.configurar_eva.php");
      });
      //para ingresar la configuracion de una unidad del aula virtual
      $("#cargar_unidades").click(function(){
        $("#program_body").load("./vistas/vista.registrar_contenido.php");
      });
      //para llevar a caboel cambio de clave
      $("#cambiar_clave_us").click(function(){
        $("#program_body").load("./vistas/vista.cambiar_clave_us.php");
      });
      //para cargar tipo de evaluaciones
      $("#tipos_evaluacion").click(function(){
        $("#program_body").load("./vistas/vista.registrar_tipo_evaluacion.php");
      });
      //para ingresar la configuracion de las preguntas a las evaluaciones
      $("#configurar_pruebas").click(function(){
        $("#program_body").load("./vistas/vista.registrar_evaluacion.php");
      });
      //para configurar evaluaciones
      $("#cargar_preguntas").click(function(){
        $("#program_body").load("./vistas/vista.registrar_preguntas.php");
      });
      //para configurar curriculum
      $("#curriculum_facilitador").click(function(){
        $("#program_body").load("./vistas/vista.registrar_curriculum_facilitador.php");
      });
      //para configurar certificados
      $("#configurar_certificados").click(function(){
        $("#program_body").load("./vistas/vista.configurar_certificados.php");
      });
      //para procesar notas
      $("#procesar_notas").click(function(){
        $("#program_body").load("./vistas/vista.procesar_notas.php");
      });
      //para mas evas-> menu estudiantes
      $("#mas_evas").click(function(){
        $("#program_body").load("./vistas/vista.consultar_aulas.php");
      });
      //para consulta de aulas
      $("#consultar_aula_adm").click(function(){
        $("#program_body").load("./vistas/vista.consultar_aulas_adm.php");
      });
      //para consulta de unidades
      $("#consultar_un_adm").click(function(){
        $("#program_body").load("./vistas/vista.consultar_und_eva.php");
      });
      //para consulta de evaluaciones
      $("#consultar_eval_adm").click(function(){
        $("#program_body").load("./vistas/vista.consultar_evaluaciones_aula.php");
      });
      //Para consultar certificados
      $("#ver_certificados").click(function(){
        $("#program_body").load("./vistas/vista.generar_certificados.php");
      });
      //Para consultar contcatos de un facilitador o administrador
      $("#consultar_contactos").click(function(){
        $("#program_body").load("./vistas/vista.consultar_miscontactos.php");
      });
      //para consulta de Mis evas
      $("#mis_evas").click(function(){
        $("#program_body").load("./vistas/vista.consultar_misevas.php");
      });
      //Para la consulta de las evaluaciones
      $("#mis_evaluaciones").click(function(){
        $("#program_body").load("./vistas/vista.consultar_misevaluaciones.php");
      });
      //Para consultar mis contactos registrados
      $("#mis_contactos").click(function(){
        $("#program_body").load("./vistas/vista.consultar_miscontactos.php");
      });
      //Para consulta de usuarios
      $("#mas_estudiantes").click(function(){
        $("#program_body").load("./vistas/vista.visualizar_usuarios.php");
      });
      //Para consulta de facilitadores
      $("#mas_facilitadores").click(function(){
        $("#program_body").load("./vistas/vista.visualizar_facilitadores.php");
      });
      //Para consulta de usuarios de facilitadores
      $("#mas_estudiantes_fac").click(function(){
        $("#program_body").load("./vistas/vista.visualizar_usuarios.php");
      });
      //Para reporte de beneficiarios
      $("#listados_est_registrados").click(function(){
         $("#program_body").load("./vistas/vista.reporte_estudiantes_registrados.php");
      });
      //Para reporte de estudiantes detallados
      $("#listados_aprobados_detalle").click(function(){
        $("#program_body").load("./vistas/vista.reporte_est_aprobados_detalle.php");
      });
      $("#listados_beneficiados").click(function(){
        $("#program_body").load("./vistas/vista.reporte_beneficiados.php");
      });
      $("#listado_auditoria").click(function(){
        $("#program_body").load("./vistas/vista.reporte_auditoria.php");
      });
      $("#listados_aprobados_general").click(function(){
         $("#program_body").load("./vistas/vistas.reporte_aprobados_general.php");
      });
      //Para consulta de facilitadores perteneciente a facilitadores
      $("#mas_facilitadores_fac").click(function(){
        $("#program_body").load("./vistas/vista.visualizar_facilitadores.php");
      });
      $("#img_juventud").click(function(){
        $("#program_body").load("./vistas/vista.pantalla_inicial.php");
      });

      //para el pop over
      $('#nombre_us2').popover({
        title: $("#us_oc").val(),
        html:true,
        placement: 'bottom',
        content:$("#div_us_pop").html() 
      });

        
  });
  //fin document.ready
  //BLOQUE DE FUNCIONES
      function cambiar_color_btn(valor)
      {
        $(valor).removeClass("btn-danger").addClass("btn-success");
      }
      function cambiar_color_btn2(valor)
      {
        $(valor).removeClass("btn-success").addClass("btn-danger");
      }
</script>
  </head>
<body>
<!--contenedor del menu -->
<div id="contenedor">
 <header>
 <div>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- El logotipo y el icono que despliega el menú se agrupan para mostrarlos mejor en los dispositivos móviles -->
      <div class="navbar-header">
          <!-- -->
          <button id='btn_navegacion' type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
          </button>
          <!-- -->
          <img id='img_juventud' src='img/logo_oficial2.png' class='navbar-brand' style='height:50px'>
      </div>
      <div id="navbarCollapse" class="collapse navbar-collapse navbar-ex1-collapse">
        <!-- -->
          <?php if($perfil==4){?>
          <!-- Datos básicos:Usuarios-->
          <ul class="nav navbar-nav">
            <li class="dropdown">
               <a href="#" class="dropdown-toggle"  data-toggle="dropdown">
                Datos B&aacute;sicos<b class="caret"></b></a>
                 <ul class="dropdown-menu">
                    <li><a id="mod_estudio" name="mod_estudio" href="#">Modalidades de estudio</a></li>
                    <li><a id="tipos_usuarios" name="tipos_usuarios" href="#">Tipos de usuarios</a></li>
                    <li><a id="tipos_evaluacion" name="tipos_evaluacion" href="#">Tipos de evaluaciones</a></li>
                </ul>   
            </li>
          </ul>      
          <?php }else if($perfil==10){ 
          ?>
          <!--Consultas aulas: Estudiantes-->
          <ul class="nav navbar-nav">
            <li class="dropdown">
                 <a href="#" class="dropdown-toggle"  data-toggle="dropdown">
                  Mi E.V.A <b class="caret"></b></a>
                   <ul class="dropdown-menu">
                      <li><a id="mis_evas" name="mod_estudio" href="#">Mis aulas</a></li>
                      <li><a id="mis_evaluaciones" name="mis_evaluaciones" href="#">Mis Evaluaciones</a></li>
                  </ul>   
            </li>
          </ul>
          <!--Conectar: Navegacion estudiantes  -->
          <ul class="nav navbar-nav">
            <li class="dropdown">
                 <a  href="#" class="dropdown-toggle"  data-toggle="dropdown">
                  Conectar <b class="caret"></b></a>
                   <ul class="dropdown-menu">
                      <li><a id="mas_evas" name="mas_evas" href="#">Mas E.V.A'S</a></li>
                      <li><a id="mas_estudiantes" name="mas_estudiantes" href="#">Mas estudiantes</a></li>
                      <li><a id="mas_facilitadores" name="tipos_evaluacion" href="#">Facilitadores</a></li>
                  </ul>   
            </li>
          </ul>
        <!-- -->
        <?php
        } ?>
        <ul class="nav navbar-nav" >
        <!--Configuracion personal -->
        <?php if (($perfil==1)||($perfil==4)||($perfil==2)){?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              Configuraci&oacute;n<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <!-- solo el admin puede administrar usuarios -->
                <?php if($perfil==4){?>
                      <li><a id="administrar_usuario" name="administrar_usuario" href="#">Administrar usuarios</a></li>
                <?php } ?>
                <!-- --> 
                <li><a id="datos_perfil_us" name="datos_perfil_us" href="#">Actualizar datos perfil</a></li>
                <!-- solo si es admin puede asignar perfiles-->
                <?php if($perfil==4){?>
                      <li><a id="asignar_perfil_us" name="asignar_perfil_us" href="#">Asignar perfil de usuarios</a></li>
                <?php } ?>
                      <li><a id="cambiar_clave_us" name="cambiar_clave_us" href="#">Cambiar Clave</a></li>
                <?php if($perfil==1){?>
                      <li><a id="curriculum_facilitador" name="curriculum_facilitador" href="#">Curriculum Facilitador</a></li>
                <?php } ?>
            </ul>
          </li>
        <?php }  ?> 
          <!--Configuracion aulas-evaluaciones: Fac/admin -->
          <?php if (($perfil==1)||($perfil==4)){?>  
          <li class="dropdown">
             <a href="#" class="dropdown-toggle"  data-toggle="dropdown">
              Procesos<b class="caret"></b></a>
               <ul class="dropdown-menu">
                  <li><a id="configurar_eva" name="configurar_eva" href="#">Configurar espacio virtual de aprendizaje</a></li>
                  <li><a id="cargar_unidades" name="cargar_unidades" href="#">Configurar contenidos</a></li>
                  <li><a id="configurar_pruebas" name="configurar_pruebas" href="#">Configurar evaluaciones</a></li>
                  <!--<li><a id="cargar_preguntas" name="cargar_preguntas" href="#">Cargar preguntas a evaluaci&oacute;n</a></li>-->
                  <li><a id="configurar_certificados" name="configurar_certificados" href="#">Configurar certificados</a></li>
                  <li><a id="procesar_notas" name="procesar_notas" href="#">Procesar Notas</a></li>
                </ul>   
          </li>
          <?php } ?>  
        </ul> 
        <!-- Para conectar de adm o fac -->
        <?php if (($perfil==1)||($perfil==4)||($perfil==2)){?>  
        <ul class="nav navbar-nav">
            <li class="dropdown">
                 <a href="#" class="dropdown-toggle"  data-toggle="dropdown">
                  Conectar <b class="caret"></b></a>
                   <ul class="dropdown-menu">
                      <li><a id="mas_estudiantes_fac" name="mas_estudiantes_fac" href="#">M&aacute;s Estudiantes</a></li>
                      <li><a id="mas_facilitadores_fac" name="mas_facilitadores_fac" href="#">M&aacute;s Facilitadores</a></li>
                  </ul>   
            </li>
        </ul>
        <?php } ?>  
        <!-- -->
        <!--Consultas: Tanto para estudiantes como para facilitadores/administradores..-->
        <ul class="nav navbar-nav">
            <li class="dropdown">
                 <a  href="#" class="dropdown-toggle"  data-toggle="dropdown">
                  Consultas <b class="caret"></b></a>
                   <ul class="dropdown-menu">
                      <?php if (($perfil==1)||($perfil==4)||($perfil==2)){?> 
                         <li><a id="consultar_aula_adm" name="consultar_aula_adm" href="#">Aulas</a></li>
                         <li><a id="consultar_un_adm" name="consultar_un_adm" href="#">Contenidos</a></li>
                         <li><a id="consultar_eval_adm" name="consultar_eval_adm" href="#">Evaluaciones</a></li>
                         <li><a id="consultar_contactos" name="consultar_contactos" href="#">Contactos</a></li>
                      <?php }else{ ?>
                          <li><a id="mis_contactos" name="mis_contactos" href="#">Mis Contactos</a></li>
                          <li><a id="ver_certificados" name="ver_certificados" href="#">Mis Certificados</a></li>
                      <?php } ?>    
                   </ul>   
            </li>
          </ul>  
        <!-- --> 
         <!-- Menú para personal viceministerio-->
              <?php if(($perfil==2)||($perfil==4)||($perfil==1)){?>
              <ul class="nav navbar-nav">
              <li class="dropdown">
                 <a href="#" class="dropdown-toggle"  data-toggle="dropdown">
                 Reportes <b class="caret"></b></a>
                   <ul class="dropdown-menu">
                      <li><a id="listados_est_registrados" name="listados_est_registrados" href="#">Estudiantes registrados</a></li>
                      <li><a id="listados_aprobados_general" name="listados_aprobados_general" href="#">Listado cantidad de estudiantes aprobados/reprobados</a></li>
                      <li><a id="listados_aprobados_detalle" name="listados_aprobados_detalle" href="#">Listado aprobados/reprobados Detalle</a></li>
                      <?php if(($perfil==2)||($perfil==4)){?>
                        <li><a id="listados_beneficiados" name="listados_beneficiados" href="#">Listados Beneficiados</a></li>
                      <?php }?>
                      <?php if($perfil==4){ ?>
                        <li><a id="listado_auditoria" name="listado_auditoria" href="#">Auditoria</a></li>
                      <?php } ?>
                   </ul>   
              </li>
              </ul>
              <?php } ?>
        <!-- -->  
        <!-- -->
        <div class="navbar-right">
           <!-- <form class="navbar-form navbar-left" id="buscar_sis" role="search">
                  <div class="form-group">    
                      <input type="text" class="form-control texto4" placeholder="Buscar" id="text_buscar" name="text_buscar">
                  </div>    
                      <button class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
            </form>-->              
             <?php if (($perfil!=1)&&($perfil!=4)){?> 
              <!-- Menú para estudiantes,estos botones solo son visibles para estudiantes -->
              <ul class="nav navbar-nav">
                <div id="puntuacion_us" title="Puntuaciones" class="btn_menu_est"><i id="op_puntuaciones" name="op_puntuaciones" class="fa fa-bar-chart fa-2x img_menu"></i></div>
                <div id="perfil_us" title="Modificar Perfil" class="btn_menu_est" onclick="cargar_perfil_us();"><i id="op_perfil" name="op_perfil" class="fa fa-user fa-2x img_menu"></i></div>
                <div id="ayuda_us" title="Descargar ayuda" class="btn_menu_est"><i id="op_ayuda" name="op_ayuda" class="fa fa-question-circle fa-2x img_menu"></i></div>
              </ul>
              <!-- -->
             <?php } else { ?>
              <ul class="nav navbar-nav">
                <div id="perfil_us" title="Modificar Perfil" class="btn_menu_est" onclick="cargar_perfil_us();"><i id="op_perfil" name="op_perfil" class="fa fa-user fa-2x img_menu"></i></div>
                <div id="ayuda_us" title="Descargar ayuda" class="btn_menu_est" onclick="ir_ayuda();"><i id="op_ayuda" name="op_ayuda" class="fa fa-question-circle fa-2x img_menu"></i></div>
              </ul>
              <?php } ?>
              <ul class="nav navbar-nav navbar-left">
                  <div id="nombre_us2"><?php echo "@".substr($user[0],0,18);?></div>
              </ul>
              <ul class="nav navbar-nav navbar-left">
                 <img id="imagen_us" name="imagen_us" src="<?php echo './img/'.$_SESSION["img_us"];?>"  alt="..." class="img-circle img_us pull-right">
              </ul>
              <ul class="nav navbar-nav navbar-left">
                <div class="col-lg-2">
                  <input type="hidden">
                </div>
              </ul>
        </div>      
      </div>               
      <div style="background-color:#23ADEF;width:25%;height:5px;float:left" id=""></div>
      <div style="background-color:#EC2B8A;width:25%;height:5px;float:left" id=""></div>
      <div style="background-color:#F3702B;width:25%;height:5px;float:left" id=""></div>
      <div style="background-color:#ABCF38;width:25%;height:5px;float:left" id=""></div>
  </nav>
</div>
</header>
<!-- fin del contenedor del menu- -->
   <!--Pestañas --> 
    <div id="program_body" class="cuprum wrapper">
      <noscript><div class="bg-danger" id="no_js" name="no_js"><span class="glyphicon glyphicon-ban-circle"></span> Disculpe: Su navegador tiene inhabilitado complementos javascript, habilitelos o actualice su navegador (Recomendablemente use  Mozilla Firefox)!</div></noscript>
    </div>
</div>
<!-- Modal para los mensajes-->
<div class="modal fade" id="modal_mensaje" name="modal_mensaje" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <p id="cabecera_mensaje" name="cabecera_mensaje"></p>
        </div>
        <div class="modal-body" id="cuerpo_mensaje" name="cuerpo_mensaje">
        <!---  -->
        <!---  -->  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_ac" name="btn_ac">Aceptar</button>
          <!--<button type="button" class="btn btn-primary">Save changes</button>-->
        </div>
      </div>
    </div>
</div>
<!-- -->
<!-- Modal: Consultas emergentes -->
<div class="modal fade bs-example-modal-lg modal_emergente" id="myModal_consulta" tabindex="-1" role="dialog" aria-labelledby="myModalLabelconsulta" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabelconsulta"></h4>
      </div>
      <div class="modal-body">
        <table class="table table-hover"  width="70%">
          <thead id="cabecera_consulta" name="cabecera_consulta">  
           <!-- -->
        </thead>
        <tbody id="cuerpo_consulta" name="cuerpo_consulta">    
            <!-- -->
          </tbody> 
        </table>
        <input type="hidden" id="id_salud_miembro_familia" name="id_salud_miembro_familia" size="2">
        <input type="hidden" id="id_miembro_familia_salud" name="id_miembro_familia_salud" size="2">
        <input type="hidden" id="numero_fila_salud" name="numero_fila_salud" size="2">
        <input type="hidden" id="opcion_salud" name="opcion_salud" size="2">
      </div>
      <div id="paginacion_modal">        
        <ul id="paginacion_tabla" class="pagination"></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal -->  
      <input type="hidden" name="us_oc" id="us_oc" size="10" value="<?php  echo $_SESSION['usuario']?>">
      <!-- div que contiene los elementos del pop over al pulzar el boton de la imeg-->
        <div id="div_us_pop" class="hide">
          <div id="cuerpo_pop">
           <div id="nombre_popup" >
                    <?php echo $_SESSION["nom_us"]; ?>
           </div>
           <br>
           <div id='img_pop'>
                <img src="<?php echo './img/'.$_SESSION["img_us"];?>" class='img-thumbnail' style="width:100px;height:100px;">
           </div>       
                  <div id="correo_popup">
                    <?php //echo $_SESSION["correo"]; ?>
                  </div>  
          </div>
          <div id='btns_pop'> 
           <button id="btn-modal" class="btn btn-primary" onclick="cargar_perfil_us();" title="ir perfil"><span class="glyphicon glyphicon-user"></span></button>
           <button id="btn_cerrar_session" name="btn_cerrar_session" onclick="cerrar_session();" class="btn btn-danger" data-dismiss="clickover" ttitle="Cerrar Sesi&oacute;n"><span class="glyphicon glyphicon-off"></span></button>
          </div>
        </div>
      <!-- -->
      <form id="form_principal" name="form_principal" method="post" target="_blank"></form>
</body>
</html>
