<?php
///////////////////////////////////////////////////////////
require_once('../modelos/modelo.usuario.php');
require_once("../libs/class.phpmailer.php");
require_once("../libs/class.smtp.php");
require_once("../libs/fbasic.php");
//-- Arreglos
$mensaje=array();
$rs=array();
if(isset($_POST["correo"])){ if($_POST["correo"]!=""){ $correo=$_POST["correo"];} else{ $correo=""; } }else{ $correo="";}
if($correo=="")
{
	$mensaje[0]="campos_blancos";
	die(json_encode($mensaje));
}
$obj_usuario=new Usuario();
$rs=$obj_usuario->consultar_correo($correo);
if($rs[0][0]==0)
{
	$mensaje[0]="no_existe";
	die(json_encode($mensaje));
}
///////////////////////////////////////////////////////////
$enviar=encode_this("correo=".$correo);	
///////////////////////////////////////////////////////////////////////////
//creo la clase phpmailer, envio el correo de verificación el usuario al pulsar el link activa su cuenta
$mail = new PHPMailer();
$body = "Espacio Virtual de Aprendizaje Juventud: Para modificar su clave, pulse el siguiente enlace (puede copiar y pegarlo en la barra de cualquier navegador). http://" . $_SERVER['HTTP_HOST']
			                      . "/eva_juventud/cambiar_clave.php?".$enviar;
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
$mail->Subject = utf8_decode("Recuperar contraseña");
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
$mail->Password = "asdfghmmmmm666"; 
if(!$mail->Send()) 
{
	$mensaje[0]="Error enviando: " . $mail->ErrorInfo;
	die(json_encode($mensaje));
}else
{
	$mensaje[0]="correo_enviado";
	die(json_encode($mensaje));
}
///////////////////////////////////////////////////////////////////////////
?>