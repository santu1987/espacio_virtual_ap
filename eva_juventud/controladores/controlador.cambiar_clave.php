<?php
session_start();
require_once("../modelos/modelo.usuario.php");
require_once("../modelos/modelo.registrar_auditoria.php"); 
$recordset=array();
$mensaje=array();
/////////////////////////////////////////////////////
if((isset($_POST['clave_anterior_us']))&&(isset($_POST['clave_nueva_us']))&&(isset($_POST['clave_nueva_us_rep'])))
{
	if(($_POST['clave_anterior_us']!='')&&($_POST['clave_nueva_us']==$_POST['clave_nueva_us_rep']))
	{
		$clave_ant=$_POST['clave_anterior_us'];
		$clave_nueva=$_POST['clave_nueva_us'];
		$id_us=$_SESSION["id_us"];
		//creando objetos
		$obj_usuario= new Usuario();
		$recordset=$obj_usuario->cambiar_clave($clave_nueva,$clave_ant,$id_us);
		//$mensaje[0]=$clave_nueva.",".$clave_ant.",".$id_us;
		//die(json_encode($recordset));
		if($recordset=='error')
		{
			$mensaje[0]="error_bd";
			die(json_encode($mensaje));
		}else
		if($recordset[0][0]=='no_existe')
		{
			$mensaje[0]="no_existe";
			die(json_encode($mensaje));
		}
		else
		if($recordset[0][0]=='modifico')
		{
    		/////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
		    $auditoria_mod=new auditoria("Cambio de Clave","Actualizacion de clave usuario");
		    $auditoria=$auditoria_mod->registrar_auditoria();
		    if($auditoria==false)
		    {
		        $mensaje[0]='error_auditoria';
		        die(json_encode($mensaje)); 
		    }
			/////////////////////////////////////////////////////////////////////////////////////////////////////

			$mensaje[0]="registro_exitoso";
			die(json_encode($mensaje));
		}	
	}else
	{
		$mensaje[0]='campos_blancos2';
		die(json_encode($mensaje));
	}	
}
else
{
	$mensaje[0]='campos_blancos';
	die(json_encode($mensaje));
}
?>