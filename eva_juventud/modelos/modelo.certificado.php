<?php
require_once("../controladores/conex.php");
class Certificado extends Conex
{
	function cargar_imagenes($ruta,$nombre_img,$tipo_archivo,$tamano_archivo,$tmp_name)
	{
		//return $ruta.",".$nombre_img.",".$tipo_archivo.",".$tamano_archivo.",".$tmp_name;
	/////////////////////////////////////////////////////////////
			if($nombre_img!="")
			{
				if (!((strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "jpg")||(strpos($tipo_archivo, "JPG"))) && ($tamano_archivo < 1000000000))) 
				{
				    
				    return "error_tipo_archivo";
				}else
				{
					//muevo la imagen de directorio
					
				    if (move_uploaded_file($tmp_name,$ruta))
				    {
					  	chmod($ruta,0777);//permisos al directorio
					  	return 1;
					}
				}	
			}else
			{
				return 0;
			}
	//////////////////////////////////////////////////////////
	}
	//
	function registrar_certificado($id_certificado,$nombre_logo,$nombre_fondo,$nombre_firma,$id_aula)
	{
		$this->conectar();
		$sql="SELECT registrar_certificado(
											".$id_certificado.",
											'".$nombre_logo."',
											'".$nombre_fondo."',
											'".$nombre_firma."',
											".$id_aula.",
											".$_SESSION['id_us'].")";
		//return $sql;
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar configuracion de un certificado por usuario
	function consultar_elementos2($id_aula,$id_us)
	{
		$id_us=$_SESSION["id_us"];
		$this->conectar();
		$where_us="WHERE tbl_inscripcion.id_usuario='".$id_us."'";
		$sql="SELECT
					upper(tbl_formacion.titulo_formacion),
					tbl_personas.nombres,
					(tbl_personas.nacionalidad||'-'||tbl_personas.ced_persona) AS cedula,
					tbl_firmas_certificados.imagen_firma,
					(SELECT tbl_personas.nombres FROM tbl_personas INNER JOIN tbl_usuarios ON tbl_personas.id_persona=tbl_usuarios.id_persona INNER JOIN tbl_usuario_perfil ON tbl_usuario_perfil.id_usuario=tbl_usuarios.id_usuarios where tbl_usuario_perfil.id_perfil=8)AS nombre_presidente,
					(SELECT tbl_personas.nacionalidad||'-'||tbl_personas.ced_persona FROM tbl_personas INNER JOIN tbl_usuarios ON tbl_personas.id_persona=tbl_usuarios.id_persona INNER JOIN tbl_usuario_perfil ON tbl_usuario_perfil.id_usuario=tbl_usuarios.id_usuarios where tbl_usuario_perfil.id_perfil=8)AS nombre_presidente,
					tbl_certificado.logo,
					tbl_certificado.fondo,
					(SELECT tbl_firmas_certificados.imagen_firma from tbl_firmas_certificados WHERE tbl_firmas_certificados.id_usuario='2') AS firma_presidente					
			  FROM 
			  		tbl_formacion
			 INNER JOIN
			 		tbl_inscripcion
			 ON 
			 		tbl_inscripcion.id_formacion=tbl_formacion.id_formacion		 		
			 INNER JOIN
			 		tbl_usuarios
			 ON
			 		tbl_formacion.id_us_creador=tbl_usuarios.id_usuarios
			 INNER JOIN
			  		tbl_personas
			  ON
			  		tbl_personas.id_persona=tbl_usuarios.id_persona
					 		
			 LEFT JOIN 
			  		tbl_firmas_certificados
			 ON 
			  		tbl_firmas_certificados.id_usuario=tbl_usuarios.id_usuarios
			INNER JOIN
			 		tbl_certificado
             ON 
             		tbl_certificado.id_formacion= tbl_formacion.id_formacion 
			  ".$where_us."			  		
			  AND 
			  		tbl_formacion.id_formacion=".$id_aula."
			  GROUP BY
					  tbl_formacion.titulo_formacion,
					  tbl_personas.nombres,
					  tbl_firmas_certificados.imagen_firma,
					  tbl_certificado.logo,
					  tbl_certificado.fondo,
					  (tbl_personas.nacionalidad||'-'||tbl_personas.ced_persona)
			  ;";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar la configuracion de un certificado
	function consultar_elementos_aula($id_aula)
	{
		$id_us=$_SESSION["id_us"];
		$this->conectar();
		if($_SESSION["id_perfil"]!=4)
		{
			$where_us="WHERE tbl_formacion.id_us_creador=".$id_us."";
		}else if($_SESSION["id_perfil"]==4)
		{
			$where_us="WHERE 1=1";
		}
		$where_us="WHERE 1=1";
		$sql="SELECT
					upper(tbl_formacion.titulo_formacion),
					tbl_personas.nombres,
					(tbl_personas.nacionalidad||'-'||tbl_personas.ced_persona) AS cedula,
					tbl_firmas_certificados.imagen_firma,
					(SELECT tbl_personas.nombres FROM tbl_personas INNER JOIN tbl_usuarios ON tbl_personas.id_persona=tbl_usuarios.id_persona INNER JOIN tbl_usuario_perfil ON tbl_usuario_perfil.id_usuario=tbl_usuarios.id_usuarios where tbl_usuario_perfil.id_perfil=8)AS nombre_presidente,
					(SELECT tbl_personas.nacionalidad||'-'||tbl_personas.ced_persona FROM tbl_personas INNER JOIN tbl_usuarios ON tbl_personas.id_persona=tbl_usuarios.id_persona INNER JOIN tbl_usuario_perfil ON tbl_usuario_perfil.id_usuario=tbl_usuarios.id_usuarios where tbl_usuario_perfil.id_perfil=8)AS nombre_presidente,
					tbl_certificado.logo,
					tbl_certificado.fondo,
					(SELECT tbl_firmas_certificados.imagen_firma from tbl_firmas_certificados WHERE tbl_firmas_certificados.id_usuario='2') AS firma_presidente					
			  FROM 
			  		tbl_formacion
			 INNER JOIN
			 		tbl_usuarios
			 ON
			 		tbl_formacion.id_us_creador=tbl_usuarios.id_usuarios
			 INNER JOIN
			  		tbl_personas
			  ON
			  		tbl_personas.id_persona=tbl_usuarios.id_persona
					 		
			 LEFT JOIN 
			  		tbl_firmas_certificados
			 ON 
			  		tbl_firmas_certificados.id_usuario=tbl_usuarios.id_usuarios
			INNER JOIN
			 		tbl_certificado
             ON 
             		tbl_certificado.id_formacion= tbl_formacion.id_formacion 
			  ".$where_us."			  		
			  AND 
			  		tbl_formacion.id_formacion=".$id_aula."
			  GROUP BY
					  tbl_formacion.titulo_formacion,
					  tbl_personas.nombres,
					  tbl_firmas_certificados.imagen_firma,
					  tbl_certificado.logo,
					  tbl_certificado.fondo,
					  (tbl_personas.nacionalidad||'-'||tbl_personas.ced_persona)
			  ;";
		$rs=$this->execute($sql);
		return $rs;	
	}
	//
	function consultar_elementos($id_aula,$id_certificado,$id_us)
	{
		if($_SESSION["id_perfil"]!=4)
		{
			$where_us="WHERE tbl_formacion.id_us_creador=".$id_us."";
		}else if($_SESSION["id_perfil"]==4)
		{
			$where_us="WHERE 1=1";
		}
		$this->conectar();
		$sql="SELECT
					upper(tbl_formacion.titulo_formacion),
					tbl_personas.nombres,
					(tbl_personas.nacionalidad||'-'||tbl_personas.ced_persona) AS cedula,
					tbl_firmas_certificados.imagen_firma,
					(SELECT tbl_personas.nombres FROM tbl_personas INNER JOIN tbl_usuarios ON tbl_personas.id_persona=tbl_usuarios.id_persona INNER JOIN tbl_usuario_perfil ON tbl_usuario_perfil.id_usuario=tbl_usuarios.id_usuarios where tbl_usuario_perfil.id_perfil=8)AS nombre_presidente,
					(SELECT tbl_personas.nacionalidad||'-'||tbl_personas.ced_persona FROM tbl_personas INNER JOIN tbl_usuarios ON tbl_personas.id_persona=tbl_usuarios.id_persona INNER JOIN tbl_usuario_perfil ON tbl_usuario_perfil.id_usuario=tbl_usuarios.id_usuarios where tbl_usuario_perfil.id_perfil=8)AS nombre_presidente,
					tbl_certificado.logo,
					tbl_certificado.fondo,
					(SELECT tbl_firmas_certificados.imagen_firma from tbl_firmas_certificados WHERE tbl_firmas_certificados.id_usuario='2') AS firma_presidente					
			  FROM 
			  		tbl_formacion
			 INNER JOIN
			 		tbl_usuarios
			 ON
			 		tbl_formacion.id_us_creador=tbl_usuarios.id_usuarios
			 INNER JOIN
			  		tbl_personas
			  ON
			  		tbl_personas.id_persona=tbl_usuarios.id_persona
					 		
			 LEFT JOIN 
			  		tbl_firmas_certificados
			 ON 
			  		tbl_firmas_certificados.id_usuario=tbl_usuarios.id_usuarios
			INNER JOIN
			 		tbl_certificado
             ON 
             		tbl_certificado.id_formacion= tbl_formacion.id_formacion 
			 ".$where_us."			  		
			 AND 
			  		tbl_formacion.id_formacion=".$id_aula.";";
		$rs=$this->execute($sql);
		return $rs;	  		
	}
}
?>