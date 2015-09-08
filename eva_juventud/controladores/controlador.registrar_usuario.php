<?php
session_start();
require_once  ('../modelos/modelo.usuario.php');
require_once("../modelos/modelo.registrar_auditoria.php"); 
$mensaje=array();
$recordset=array();
//validaciones en php
if((isset($_POST["n_cedula_us"]))&&(isset($_POST["nombre_us"]))&&(isset($_POST["fecha_nac_us"]))&&(isset($_POST["tlf_us"])))
{
	if(($_POST["n_cedula_us"]!="")&&($_POST["nombre_us"]!="")&&($_POST["fecha_nac_us"])&&($_POST["select_estado"]!="-1")&&($_POST["select_municipio"]!="-1")&&($_POST["select_parroquia"]!="-1")&&($_POST["tlf_us"]!=""))
	{
///////////////////////////////////////////////////////////////////////////////
		$obj_usuario=new usuario();
		//ejecuto el registrar
		$recordset=$obj_usuario->registrar_usuario($_POST["n_cedula_us"],$_POST["select_nac_us"],$_POST["nombre_us"],$_POST["fecha_nac_us"],$_POST["select_estado"],$_POST["select_municipio"],$_POST["select_parroquia"],$_POST["tlf_us"],$_POST["sexo_us"]);
		//die(json_encode($recordset));
		if($recordset[0][0]!='')
		{
	        $mensaje[0]='guardo';
	        $mensaje[1]=$recordset;
	        /////////////////////////////////////////////////--AUDITORIA--///////////////////////////////////////
		    $auditoria_mod=new auditoria("Usuario","Actualizacion datos usuarios");
		    $auditoria=$auditoria_mod->registrar_auditoria();
		    if($auditoria==false)
		    {
		        $mensaje[0]='error_auditoria';
		        die(json_encode($mensaje)); 
		    }
			/////////////////////////////////////////////////////////////////////////////////////////////////////
        die(json_encode($mensaje));     
	    }else 
	    if($vector[0]=='error')
	    {
	        $mensaje[0]='error';
	        die(json_encode($mensaje));  
	    }
///////////////////////////////////////////////////////////////////////////////
	}
	else
	{
		$mensaje[0]="campos_blancos";
		die(json_encode($mensaje));
	}
}
else
{
	$mensaje[0]="campos_blancos2";
	die(json_encode($mensaje));
}
///////////////////////////////////////////

?>