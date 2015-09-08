<?php
require_once("../controladores/conex.php");
class foro extends conex
{
	public $num_rows;
	public $num_rows2;
	//Metodo para registrar mensaje en el foro
	function registrar_mensaje_foro($aula,$mensaje_foro)
	{
		$this->conectar();
		$sql="	SELECT registrar_foro(".$aula.",".$_SESSION['id_us'].",'".$mensaje_foro."');";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar un foro de un aula en especifico
	function consultar_foro($aula,$offset,$limit)
	{	
		////
		$where=" WHERE 
			  		 a.id_aula='".$aula."'";
		if(($offset!="")&&($limit!=""))	
		{
			$where2=" limit '".$limit."' 
                    offset '".$offset."' ";
		}
		////
		$this->conectar();
		$sql="SELECT
					b.usuario_login AS nombre_us,
					b.imagen_us, 
					a.mensajes_foro,
					a.id_mensaje_contenido,
					(SELECT count(*) FROM tbl_respuestas_mensajes WHERE tbl_respuestas_mensajes.id_mensaje=a.id_mensaje_contenido)AS cuantos_mensajes
			  FROM 
			  		tbl_mensajes_contenido a
			  INNER join
			  		tbl_usuarios b
			  ON		
			  		b.id_usuarios=a.id_usuario
			 ".$where."
			 order by a.id_mensaje_contenido DESC 
			 ".$where2.";";
		$rs=$this->execute($sql);
		$this->cuantos_son_foro($where);
		return $rs;
	}
	//Metodo para registrar mensajes de respuesta a un comentario
	function registrar_mensaje_foro_respuesta($id_foro,$mensaje_foro)
	{
		$this->conectar();
		$sql="	SELECT registrar_foro_respuesta(".$id_foro.",".$_SESSION['id_us'].",'".$mensaje_foro."');";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar todos los mensajes de un aula
	function consultar_foro_respuestas($id_foro,$offset,$limit)
	{
		$this->conectar();
		//////
		$where= "WHERE 
			  		tbl_respuestas_mensajes.id_mensaje='".$id_foro."'";
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		//////
		$sql="SELECT
					tbl_usuarios.usuario_login AS nombre_us,
					tbl_usuarios.imagen_us, 
					tbl_respuestas_mensajes.mensaje_respuesta,
					tbl_respuestas_mensajes.id_respuestas
			  FROM 
			  		tbl_respuestas_mensajes
			  INNER join
			  		tbl_usuarios
			  ON		
			  		tbl_usuarios.id_usuarios=tbl_respuestas_mensajes.id_us_responde
			  ".$where."
			  order by tbl_respuestas_mensajes.id_respuestas DESC
			  ".$where2.";";
		//return $sql;	  
		$rs=$this->execute($sql);
		$this->cuantos_son_respuestas_foro($where);
		return $rs;	
	}
	//Metodo para detrminar cuantos mensajes estan registrados
	function cuantos_son_foro($where)
	{
		//calculo cuantos son..
        $sql2="SELECT
					count(*)
			  FROM 
			  		tbl_mensajes_contenido a
			  INNER join
			  		tbl_usuarios b
			  ON		
			  		b.id_usuarios=a.id_usuario	
			  ".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//Metodo para determinar cuantos mensajes de respuestas estan registrados
	function cuantos_son_respuestas_foro($where)
	{
		//calculo cuantos son..
        $sql2="SELECT
					count(*)
			  FROM 
			  		tbl_respuestas_mensajes
			  INNER join
			  		tbl_usuarios
			  ON		
			  		tbl_usuarios.id_usuarios=tbl_respuestas_mensajes.id_us_responde	
			  ".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows2=$rs2[0][0];
        ////////////////////////////////////////////
	}
}
?>
