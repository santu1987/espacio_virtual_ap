<?php
require_once("../controladores/conex.php");
class Facilitador extends Conex
{
	public $num_rows;
	//Metodo para consultar facilitadores pantalla principal
	function consultar_facilitadores_in()
	{
		$this->conectar();
		$sql="SELECT 
					tbl_usuarios.id_usuarios,
					tbl_usuarios.id_persona,
					tbl_usuarios.imagen_us,
					upper(tbl_usuarios.usuario_login) AS 	login,
					upper(tbl_personas.nombres) AS nombres
			FROM
					tbl_usuarios
			INNER join
					tbl_personas
			ON 
					tbl_personas.id_persona=tbl_usuarios.id_persona				
			INNER JOIN 
					tbl_usuario_perfil
			ON
					tbl_usuarios.id_usuarios=tbl_usuario_perfil.id_usuario_perfil							
			WHERE 
					tbl_usuario_perfil.id_perfil='1'
			order by tbl_usuarios.id_persona DESC
			limit 6
			offset 0";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar cuantos estudiantes hay y hacer la paginacion
	function cuantos_facilitadores_son($where)
	{
		$this->conectar();
		$sql="SELECT
					COUNT(*)
			FROM
					tbl_usuarios a
			INNER join
					tbl_personas b
			ON 
					b.id_persona=a.id_persona				
			INNER JOIN 
				tbl_usuario_perfil c
			ON
				a.id_usuarios=c.id_usuario							
			WHERE 
				c.id_perfil='1'								
			".$where.";";
		$rs=$this->execute($sql);
		$this->num_rows=$rs[0][0];	  
	}
	//Metodo para consultar facilitadores de parte de est y admin
	function consultar_facilitador($offset,$limit,$nombre,$aula)
	{
			$where="";
			if($nombre!="")
			{
				$where.=" AND upper(b.nombres) like '%".$nombre."%'";
			}
			if(($aula!="")&&($aula!="-1"))
			{
				$where.=" AND (SELECT COUNT(*) FROM tbl_formacion d WHERE d.id_formacion='".$aula."' AND d.id_us_creador=a.id_usuarios)>=1";
			}
			//////////////////////////
			if(($offset!="")&&($limit!=""))
			{
				$where2="   limit '".$limit."' 
                    		offset '".$offset."' ";
			}
			//////////////////////////
			$this->conectar();
			$sql="SELECT 
						a.id_usuarios,
						a.id_persona,
						a.imagen_us,
						upper(a.usuario_login) AS 	login,
						upper(b.nombres) AS nombres,
						(SELECT COUNT(*)FROM tbl_usuarios_contactos e WHERE e.id_us='".$_SESSION['id_us']."' and e.contacto=a.id_usuarios)
				FROM
						tbl_usuarios a
				INNER join
						tbl_personas b
				ON 
						b.id_persona=a.id_persona				
				INNER JOIN 
					tbl_usuario_perfil c
				ON
					a.id_usuarios=c.id_usuario							
				WHERE 
					(c.id_perfil='1')
				".$where."	
				order by a.id_persona DESC
				".$where2.";";
			//return $sql;	
			$rs=$this->execute($sql);
			$this->cuantos_facilitadores_son($where);
			return $rs;
	}
}