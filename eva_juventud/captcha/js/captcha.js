/////////////////////////////////////////////////////////////////
//BLOQUE DE FUNCIONES Y EVENTOS CAPTCHA
$("#n_cedula_is").focus();
$("#btn_is").click(function(){
/////////////////////////////////////////  
$.post("captcha/post.php?"+$("#form_is").serialize(), {
    },function(response){
    //alert(response);
    if(response==1)
    {
      //mensajes2("C&oacute;digo correcto");
        if(validar_campos_is()==true)
        {
          registrar_us_is();
        }
    }
    else
    {
      mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, el c&oacute;digo que ingres&oacute; no es valido");
    }
});
return false;
////////////////////////////////////////
});
// refresh captcha
$('img#refresh').click(function() {  
change_captcha();
});
$("#cerrar_modal_is").click(function(){
  $("#n_cedula_is,#correo_is,#clave_is,#code").val("");
  //foco en el campo usuarios....
  change_captcha();
});
//BLOQUE DE FUNCIONES
function change_captcha()
{
  document.getElementById('captcha').src="captcha/get_captcha.php?rnd=" + Math.random();
}
function validar_campos_is()
{
  if($("#n_cedula_is").val()=="")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, debe ingresar un n° c&eacute;dula");
    return false;
  }else  
  if($("#correo_is").val()=="")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, debe ingresar un correo electr&oacute;nico");
    return false;
  }else  
  if($("#clave_is").val()=="")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, debe ingresar una clave de 10 d&iacute;gitos, entre n&uacute;meros y letras");
    return false;
  }else  
  if($("#code").val()=="")
  {
    mensajes2("<i class='fa fa-exclamation-circle fa-2x' style='color:#bc2328'></i> Disculpe, debe ingresar c&oacute;digo de verificaci&oacute;n");
    return false;
  }else
    return true;  
}
function registrar_us_is()
{
  document.getElementById("btn_is").display="none"; 
 /////////////////
  barra_inicial();
 ///////////////// 
  var data=$("#form_is").serialize();
  $("#cerrar_modal_is").click();
  $.ajax({
            url:"./controladores/controlador.registrar_us_is.php",
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
              activar_botones_modalmensajes2();
              if(recordset=="error")
              {
                  mensajes(7);
              }else
              if(recordset=="existe")
              {
                  mensajes(16);
              }
              else
              if(recordset[0]=="campos_blancos")  
              {
                mensajes(5);
              }  
              else
              if(recordset[0]=="registro_exitoso") 
              {
                //$("#contenedor_barra").hide();//oculto la barra
                mensajes2("<i class='fa fa-check fa-2x' style='color:#16E91D'></i> Operaci&oacute;n realizada con exito, revise su correo en los pr&oacute;ximos diez minutos le llegar&aacute; un mensaje con la activaci&oacute;n de su cuenta");
                $("#cerrar_modal_is").click();
              }
              else
              {
                mensajes2(recordset);
              }  
            }
                 
  });
}