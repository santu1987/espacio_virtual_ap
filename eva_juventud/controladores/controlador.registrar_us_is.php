<?php
session_start();
require_once  ('../modelos/modelo.usuario.php');
require_once("../modelos/modelo.registrar_auditoria.php"); 
require_once("../libs/class.phpmailer.php");
require_once("../libs/class.smtp.php");
require_once("../libs/fbasic.php");
$mensaje=array();
$recordset=array();
//validaciones en php
if((isset($_POST["select_nac_is"]))&&(isset($_POST["n_cedula_is"]))&&(isset($_POST["correo_is"]))&&(isset($_POST["clave_is"])))	
{
	if(($_POST["select_nac_is"]!="")&&($_POST["n_cedula_is"]!="")&&($_POST["correo_is"]!="")&&($_POST["clave_is"]!=""))
	{
		$nac=$_POST["select_nac_is"];
		$cedula_us=$_POST["n_cedula_is"];
		$correo=$_POST["correo_is"];
		$clave=$_POST["clave_is"];
	}	
}else
{
	$mensaje[0]="campos_blancos";
}
//creo el objeto de usuario para registrarlo
$obj_usuario=new Usuario();
$nombres=$obj_usuario->consultar_saime($nac,$cedula_us);
$rs=$obj_usuario->insertar_us_is($nac,$cedula_us,$correo,$clave,$nombres);
//die(json_encode($rs));
if($rs=="error")
{
	$mensaje[0]="error";
}else
if($rs[0][0]=="existe")
{
	$mensaje[0]="existe";
}
else
if($rs[0][0]=="registro_exitoso")
{
	$mensaje[0]="registro_exitoso";
//si el el registro fu exitoso encripto las variables que se enviaran por email...
//$enviar=encode_this("nacionalidad=".$nac."&cedula=".$cedula_us);	
///////////////////////////////////////////////////////////////////////////
//creo la clase phpmailer, envio el correo de verificación el usuario al pulsar el link activa su cuenta
/*$mail = new PHPMailer();
$body = "Bienvenido(a) al Espacio Virtual de Aprendizaje Juventud, Tu Usuario es: ".$correo." y Tu Clave: ".$clave." te invitamos a continuar con tu registro pulsando el siguiente enlace, para activar tu cuenta. http://" . $_SERVER['HTTP_HOST']
			                      . "/eva_juventud/activar.php?".$enviar;
$mail->IsSMTP(); 
//. rtrim(dirname($_SERVER['PHP_SELF']), '/\\')			                      
// la dirección del servidor, p. ej.: smtp.servidor.com
//$mail->Host = "correo.minjuventud.gob.ve";
$mail->Host = "smtp.gmail.com";
// dirección remitente, p. ej.: no-responder@miempresa.com
//$mail->From = "gsantucci@minjuventud.gob.ve";
$mail->From = "espacio.virtual.minjuventud@gmail.com";
// nombre remitente, p. ej.: "Servicio de envío automático"
$mail->FromName = "webmaster-EVA juventud";
// asunto y cuerpo alternativo del mensaje
$mail->Subject = "Envio de Usuario y Clave";
$mail->AltBody = "Cuerpo alternativo 
    para cuando el visor no puede leer HTML en el cuerpo"; 
// si el cuerpo del mensaje es HTML
$mail->MsgHTML($body);
// podemos hacer varios AddAdress
$mail->AddAddress($correo, $nombres);
// si el SMTP necesita autenticación
$mail->SMTPAuth = true;
// credenciales usuario
$mail->Username = "espacio.virtual.minjuventud@gmail.com";
$mail->Password = "machurucuto666"; 
if(!$mail->Send()) 
{
	die(json_encode("Error enviando: " . $mail->ErrorInfo));
}
///////////////////////////////////////////////////////////////////////////

}
else
{
	die(json_encode($mensaje));
}*/
//
die(json_encode($mensaje));	
}
?>