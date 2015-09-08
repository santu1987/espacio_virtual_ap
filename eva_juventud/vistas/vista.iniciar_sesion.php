<script type="text/javascript" src="./captcha/js/captcha.js"></script>
<script type="text/javascript">
//funciones
function change_captcha2()
{
  document.getElementById('captcha2').src="captcha/get_captcha.php?rnd=" + Math.random();
}
function recuperar_clave_us()
{
////////////////////////////////////////////////////////////////////////  
  if(($("#code2").val()!="")&&($("#correo_rec").val()!=""))
  {  
  var correo=$("#correo_rec").val();
  ///
  barra_inicial();
  ///  
  var data={correo:correo};
  $.ajax({
            url:"./controladores/controlador.recuperar_contrasena.php",
            type:"POST",
            data:data,
            cache:false,
            error: function()
            {
                console.log(arguments);
                mensajes(3);
            },
            success: function(html)
            {
              //alert(html);
              activar_botones_modalmensajes2();
              var recordset=$.parseJSON(html);
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
              else if(recordset[0]="correo_enviado")
              {
                  mensajes(40);//correo enviado...
              } 
            }      
  });
}else
{
    //alert($("#code2").val());
    mensajes(5);//campos blancos
}
//////////////////////////////////////////////////////////////////////
}
//eventos

//foco en el campo usuarios....
$("#cerrar_modal_rec").click(function(){
  $("#correo_rec,#code2").val("");
  change_captcha2();
});
$("#usuario_nom" ).focus();
/*$("#usuario_nom, #usuario_contrasena").keypress(function(event){
    if(event.which==13){
      $("#iniciar_s").click();
    }
});*/
$("#usuario_nom,#usuario_contrasena").keypress(function(e) {
  if(e.which==13){
      $("#btn_iniciar").click();
    }
});   
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$("#recuperar_clave").click(function(){
  $("#cerrar_modal_rec").click();
});
$('img#refresh2').click(function() {  
  change_captcha2();
});
//------------------------------------------------------------------------------------------------------------------- 
////para recuperar la contraseña.-..
$("#btn_recuperar").click(function(){
//////////////////////////////////////////////////////////
$.post("captcha/post2.php?"+$("#form_rec").serialize(), {
    },function(response){
    //alert(response);
    if(response==1)
    {
      //mensajes2("C&oacute;digo correcto");
          recuperar_clave_us();
    }
    else
    {
      mensajes2("Disculpe, el c&oacute;digo que ingres&oacute; no es valido");
    }
});
return false;
//////////////////////////////////////////////////////////
});
///btn iniciar sesion.....
$("#btn_iniciar").click(function(){
   if((document.getElementById("usuario_nom").value!="")&&(document.getElementById("usuario_contrasena").value!=""))
   {
      var data=$("#form_inicio_usuario").serialize();
      $.ajax ({
                  url:"./controladores/controlador.iniciar_sesion.php",
                  data:data,
                  type:'POST',
                  cache: false,
                  //si ha ocurrido un error
                  error: function()
                  {
                      console.log(arguments);
                      mensajes(3);
                  },
                  success: function(html)
                  {
                          ///alert(html);
                          var recordset=$.parseJSON(html);
                          if(recordset=="inactivo")
                          {
                              mensajes2("Usuario inactivo, revise su correo electr&oacute;nico, presione el link de activación que se halla en el cuerpo del mensaje");
                          }else            
                          if(recordset[0]=="cargando_perfil")
                          {
                              document.forms.form_inicio_usuario.action='./inicio.php';
      						            document.forms.form_inicio_usuario.submit();
                              //alert(recordset[4]);
                              //$("#menu").load("modulos/administracion/db/vista.menu_admin.php");
                              //$("#titleh1").html('Bienvenid@ '+ucwords(recordset[4].toLowerCase()));
                           }
                          else if(recordset[0]=="clave_invalida")
                          {
                              mensajes(11);//clave invalida
                              $( "#usuario_nom" ).val( "" );
                              $( "#usuario_contrasena" ).val( "" );
                              //$( "#usuario_nom" ).focus();
                              //bloquear();
                          }
                          else if(recordset[0]=="error en bd")
                          {
                              mensajes(10);//error en base de datos
                              $( "#usuario_nom" ).val( "" );
                              $( "#usuario_contrasena" ).val( "" );
                              //$( "#usuario_nom" ).focus();
                              //bloquear();
                          }
                          else if(recordset[0]=="campos_blancos")
                          {
                              mensajes(5);//campos en blanco, debe llenar campos obligatorios...
                              $( "#usuario_nom" ).val( "" );
                              $( "#usuario_contrasena" ).val( "" );
                            
                          }
                          else if(recordset[0]=="campos_blancos2")
                          {
                              mensajes(3);//error inesoerado
                              $( "#usuario_nom" ).val( "" );
                              $( "#usuario_contrasena" ).val( "" );
                            
                          }  
                          else
                          {
                              mensajes(11);//usuario y/o contraseña incorrecto
                              $( "#usuario_nom" ).val( "" );
                              $( "#usuario_contrasena" ).val( "" );
                              //bloquear();
                          }

                  }
        });//CIERRE DEL $.ajax
    }//CIERRE DEL CONDICIONAL
  else
  {
        mensajes(5);//campos en blanco, debe llenar campos obligatorios...
  }
});//CIERRE DEL $("#iniciar_s").click
$("#usuario_nom, #usuario_contrasena").blur(function(){
    if( $(this).val() === '' ) { //<--SI EL CAMPO ESTA VACÍO
        //alert('empty');
        $(this).focus();  //<--MANTIENE EL FOCO ALLÍ
    }
});
</script>
<div id="agrupar_3" class="marco">
  <div>
      <form id="form_inicio_usuario" class="form-horizontal" name=="form_inicio_usuario" method="post" target="_self">
            <img src="./img/us1.png" alt="..." class="img-circle img_inicio" >
            <div class="input-group input-group-slg" style="margin-top:6%">
      				<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
      				<input type="email" maxlength="60" class="form-control input-sg" id="usuario_nom" name="usuario_nom" onpaste="alert('no puedes pegar');return false" onKeyPress="return valida(event,this,7,100)" onBlur="valida2(this,7,100);correo(this);"  placeholder="Ingrese nombre de usuario">
           	</div>
          	<div class="input-group input-group-slg  clave">
      			  <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
      			  <input type="password"   maxlength="15" class="form-control input-sg" id="usuario_contrasena" name="usuario_contrasena" onpaste="alert('no puedes pegar');return false" onKeyPress="return valida(event,this,2,15)" onBlur="valida2(this,2,15)" placeholder="Ingrese su clave">
      	    </div>       
            <button type="button" id="btn_iniciar" name="btn_iniciar" class="btn btn-primary btn-lg btn-block">Iniciar sesi&oacute;n</button>
      </form>	
  		<div>
          <a class="link_page" data-toggle="modal" data-target="#modal_is"><u>Registrate</u></a>
          <a class="rec_cont link_page" id="recuperar_clave" data-toggle="modal" data-target="#modal_rec_c"><u>Recuperar Contrase&ntilde;a</u></a>
      </div>
  </div>		
</div>