<?php
///incluir conexion
require_once("../controladores/conex.php");
//clase usuario....
class Usuario extends Conex
{
		private $login;
		private $clave;
		private $rs;
		public $mensaje=array();
		public $num_rows;
		public $num_rows2;
		public $num_rows3;
		public $num_rows4;
		public $num_rows_cnt;
		public $filtro_where;
		/////////////////////////////////////////////////////////7
		//metodo inciar session
		function iniciar_session($login,$clave)
		{
			$this->clave=$clave;
			$this->login=$login;
			$this->conectar();
			$sql="SELECT
						tbl_usuarios.id_usuarios,
						tbl_usuarios.usuario_login,
						tbl_usuarios.email,
						tbl_usuarios.clave,
						tbl_usuarios.id_persona,
						tbl_personas.nombres,
						tbl_usuarios.imagen_us,
						tbl_usuarios.estatus,
						tbl_usuario_perfil.id_perfil,
						upper(tbl_estado.nombre) AS estado
				FROM 
						tbl_usuarios
				RIGHT JOIN 
						tbl_personas
				ON 
						tbl_usuarios.id_persona=tbl_personas.id_persona
				LEFT JOIN
						tbl_usuario_perfil
				ON
						tbl_usuarios.id_usuarios=tbl_usuario_perfil.id_usuario
				LEFT JOIN 
						tbl_estado
				ON 
						tbl_estado.codigoestado=tbl_personas.codigoestado												
				WHERE
						tbl_usuarios.usuario_login='".$this->login."'
				AND
				  		tbl_usuarios.clave='".md5($this->clave)."';";
			//return $sql;
			$this->rs=$this->execute($sql);
			////////////////////////////////////
			if(isset($this->rs[0][0]))
			{
				//verifico primero si esta inactivo
				if($this->rs[0][7]=='I')
				{
					$this->mensaje[0]="inactivo";
					//die(json_encode($this->mensaje));
					return $this->mensaje[0];	
				}else	
				if($this->rs[0][0]=='')
				{
					$this->mensaje[0][0] ="clave_invalida";
					//die(json_encode($this->mensaje));
					return $this->mensaje[0];
				}else
				if($this->rs[0][0]!='error')
				{
					$_SESSION["id"]=$this->rs[0][4];//es el id_persona...
					$_SESSION["usuario"]=$this->rs[0][1];
					$_SESSION["espacio_virtual"]="SI";
					$_SESSION["nom_us"]=$this->rs[0][5];
					$_SESSION["correo"]=$this->rs[0][2];
					$_SESSION["id_us"]=$this->rs[0][0];//id de usuario
					$_SESSION["id_perfil"]=$this->rs[0][8];//id perfil
					///
					if($this->rs[0][6]=="")
					{
						$_SESSION["img_us"]="us1.png";
					}else
					{
						$_SESSION["img_us"]="/fotos_personas/".$this->rs[0][6];
					}	
					///
					$this->mensaje[0] ="cargando_perfil";
					return $this->mensaje[0];
				}
				else
				{
					$this->mensaje[0] ="error en bd";
					//die(json_encode($this->mensaje));
					return $this->mensaje[0];
				}	
			}else
			{
					$this->mensaje[0] ="clave invalida";
					//die(json_encode($this->mensaje));
					return $this->mensaje[0];
			}

		}
		/////////////////////////////////////////////////////////
		//metodo para registrar usuarios
		function registrar_usuario($cedula,$nacionalidad,$nombre_us,$fecha_nac_us,$select_estado,$select_municipio,$select_parroquia,$tlf_us,$sexo)
		{
			$this->conectar();
			$sql="
					SELECT registrar_usuario(
				    ".$cedula.",
				    '".$nacionalidad."',
				    '".$nombre_us."',
				    '".$fecha_nac_us."',
				    '".$select_estado."',
				    '".$select_municipio."',
				    '".$select_parroquia."',
				    '".$tlf_us."',
				    '".$sexo."'
			);";
			//return $sql;
			$this->rs=$this->execute($sql);
			return $this->rs;
		}
		/////////////////////////////////////////////////////////
		//metodo para consultar usuarios
		function consultar_usuario($id)
		{
			$this->conectar();
			$sql="SELECT 
						 tbl_personas.id_persona,
						 tbl_personas.nacionalidad,
						 tbl_personas.ced_persona,
						 tbl_personas.nombres,
						 tbl_personas.codigoestado,
						 tbl_personas.codigomunicipio,
						 tbl_personas.codigoparroquia,
						 to_char(fecha_nac,'DD-MM-YYYY'),
						 tbl_personas.tlf_movil,
						 tbl_personas.sexo 
				  FROM
				  		 tbl_personas
				  inner join
				  		 tbl_usuarios
				  on		 
				  		 tbl_usuarios.id_persona=tbl_personas.id_persona
				  WHERE 
				  		tbl_usuarios.id_persona='".$id."'";
			$rs=$this->execute($sql);
			return $rs;
		}
		/////////////////////////////////////////////////////////
		//metodo para consultar usuarios perfil
		function consultar_usuario_perfil($nacionaidad,$cedula)
		{
			$this->conectar();
			$sql="SELECT 
						 tbl_personas.id_persona,
						 tbl_usuarios.id_usuarios,
						 tbl_personas.nombres,
						 tbl_usuario_perfil.id_usuario_perfil,
						 tbl_usuario_perfil.id_perfil
				  FROM
				  		 tbl_personas
				  INNER JOIN
				  		tbl_usuarios
				  on
				  		tbl_personas.id_persona=tbl_usuarios.id_persona
				  LEFT JOIN 
				  		tbl_usuario_perfil
				  on 
				  		tbl_usuarios.id_usuarios=tbl_usuario_perfil.id_usuario							 
				  WHERE 
				  		tbl_personas.ced_persona='".$cedula."'
				  AND
				  		tbl_personas.nacionalidad='".$nacionaidad."'		 			 
						   	";
			///return $sql;			   	
			$rs=$this->execute($sql);
			return $rs;
		}
		/////////////////////////////////////////////////////////
		//metodo para asignar perfil
		function registrar_perfil_us($id_us,$select_perfil,$id_perfil_us)
		{
			$this->conectar();
			$sql="select registrar_prefil_us(".$id_us.",".$select_perfil.",".$id_perfil_us.")";
			$rs=$this->execute($sql);
			return $rs;
		}
		//metodo para cambio de clave de usurio
		function cambiar_clave($clave_nueva,$clave_ant,$id_us)
		{
			$this->conectar();
			$sql="SELECT cambiar_clave_us(
										    '".$clave_nueva."',
										    '".$clave_ant."',
										    ".$id_us."
						);";
			//return $sql;
			$rs=$this->execute($sql);
			return $rs;		
		}
		//metodo para actualizar la imagen del usuario
		function ac_imagen_us($nombre_imagen,$id_persona)
		{
			$this->conectar();
			$sql="SELECT actualizar_imagen('".$nombre_imagen."',".$id_persona.");";
			$rs=$this->execute($sql);
			//actualizo la variable session
			$_SESSION["img_us"]="/fotos_personas/".$nombre_imagen;
			return $rs;
		}
		//metodo que carga la imagen de la firma si el usuario es facilitador o el presidente del INJ
		function cargar_imagenes_firma($ruta,$nombre_img,$tipo_archivo,$tamano_archivo,$tmp_name)
		{
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
		//metodo para registrar las firmas de los facilitadores y del presidente del INJ
		function registrar_firma($id_us,$nombre_firma)
		{
			$this->conectar();
			$sql="SELECT registrar_firma(".$id_us.",'".$nombre_firma."')";
			$rs=$this->execute($sql);
		}
		////////////////////////////////////////////////////////
		//metodo para registrar usuarios en el inicio de session
		function insertar_us_is($nac,$cedula,$correo,$clave,$nombres)
		{
			$this->conectar();
			$sql="SELECT registrar_usuario_is(
												'".$nac."',
												".$cedula.",
												'".$correo."',
												'".$clave."',
												'".$nombres."')";
			//return $sql;
			$rs=$this->execute($sql);
			return $rs;
		}
		////////////////////////////////////////////////////////
		//metodo que consulta saime y trae nombres de una persona
		function  consultar_saime($nacionalidad,$cedula)
		{
			$this->conectar();
			$sql="SELECT saime.buscaci_nacionalidad(
			'".$nacionalidad."',
			".$cedula."
			);";
			//return $sql;
			$rs=$this->execute($sql);
			if($rs[0][0]!='')
			{
				$vector=explode(",",$rs[0][0]);
				if($nacionalidad=='V')
				{
					$nombres[0]=$vector[1]." ".$vector[2];
					$nombres[1]=$vector[3]." ".$vector[4];
					//
					$nombres[0]=str_replace("\"","",$nombres[0]);$nombres[0]=str_replace(")","",$nombres[0]);
					$nombres[1]=str_replace("\"","",$nombres[1]);$nombres[1]=str_replace(")","",$nombres[1]);
				}
				else
				{
					$nombres[0]=str_replace("\"","",$vector[1]);
					$apellidos_ex=str_replace("\"","",$vector[2]);
					$nombres[1]=str_replace(")","",$apellidos_ex);
				}	
				return $nombres[0]." ".$nombres[1];
			}
		}
		////////////////////////////////////////////////////////////////////
		//metodo para activar la cuenta de usuario....
		function modificar_estatus($nac,$cedula)
		{
			$this->conectar();
			$sql="SELECT modificar_estatus('".$nac."',".$cedula.")";
			$rs=$this->execute($sql);
			return $rs;
		}
		function cerrar_session()
		{
			session_start();
	        session_unset();
	        session_destroy();
	        $cs = base64_encode('1');
	        $envio = "http://" . $_SERVER['HTTP_HOST']."/eva_juventud/index.php?cerrar=".$cs; 
	        return $envio;
		}
		function consultar_usuarios_in()
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
				WHERE 
						1=1
				order by tbl_usuarios.id_persona DESC
				limit 6
				offset 0";
			$rs=$this->execute($sql);
			return $rs;
		}
		//Metodo para consultar cuantos estudiantes hay y hacer la paginacion
		function cuantos_estudiantes_son($where)
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
						c.id_usuario=a.id_usuarios								
				INNER JOIN 
						tbl_inscripcion d 
				ON 
						d.id_usuario=a.id_usuarios		
				INNER JOIN 
						tbl_formacion f
				ON 
						f.id_formacion=d.id_formacion		
				WHERE 
						1=1
				AND 
						a.id_usuarios!='".$_SESSION['id_us']."'		
				AND
						c.id_perfil=10
			    AND (SELECT COUNT(*) FROM tbl_inscripcion d WHERE  d.id_usuario=a.id_usuarios)>=1 
				".$where."
				".$this->filtro_where."
				GROUP BY 
					a.id_usuarios,
					a.id_persona,
					a.imagen_us,
					upper(a.usuario_login),
					upper(b.nombres),
					(SELECT COUNT(*) FROM tbl_inscripcion d WHERE  d.id_usuario=a.id_usuarios),
					(SELECT COUNT(*)FROM tbl_usuarios_contactos e WHERE e.id_us='".$_SESSION['id_us']."' and e.contacto=a.id_usuarios)";	

			$rs=$this->execute($sql);
			$this->num_rows=$rs[0][0];	  
		}
		//Metodo para crear los filtros....
		function filtro_aulas($rs_eva)
		{
			$where=" AND (f.id_formacion='".$rs_eva[0][0]."'";
			///////////////////////////////////////
			for($i=1;$i<=count($rs_eva)-1;$i++)
			{
				$where.=" OR f.id_formacion='".$rs_eva[$i][0]."'";
			}
			$where.=")";
			//////////////////////////////////////	
			$this->filtro_where=$where;
		}
		//Metodo para consultar los estudiantes inscritos
		function consultar_estudiantes($offset,$limit,$nombre,$aula)
		{
			//////////////////////////
			$where="";
			if($nombre!="")
			{
				$where.=" AND upper(b.nombres) like '%".$nombre."%'";
			}
			if(($aula!="")&&($aula!="-1"))
			{
				$where.=" AND (SELECT COUNT(*) FROM tbl_inscripcion d WHERE d.id_formacion='".$aula."' AND d.id_usuario=a.id_usuarios)>=1 ";
			}	
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
						(SELECT COUNT(*) FROM tbl_inscripcion d WHERE  d.id_usuario=a.id_usuarios),
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
						c.id_usuario=a.id_usuarios								
				INNER JOIN 
						tbl_inscripcion d 
				ON 
						d.id_usuario=a.id_usuarios		
				INNER JOIN 
						tbl_formacion f
				ON 
						f.id_formacion=d.id_formacion		
				WHERE 
						1=1
				AND 
						a.id_usuarios!='".$_SESSION['id_us']."'		
				AND
						c.id_perfil=10
			    AND (SELECT COUNT(*) FROM tbl_inscripcion d WHERE  d.id_usuario=a.id_usuarios)>=1 
				".$where."
				".$this->filtro_where."
				GROUP BY 
					a.id_usuarios,
					a.id_persona,
					a.imagen_us,
					upper(a.usuario_login),
					upper(b.nombres),
					(SELECT COUNT(*) FROM tbl_inscripcion d WHERE  d.id_usuario=a.id_usuarios),
					(SELECT COUNT(*)FROM tbl_usuarios_contactos e WHERE e.id_us='".$_SESSION['id_us']."' and e.contacto=a.id_usuarios)	
				order by a.id_persona DESC
				".$where2.";";
			//return $sql;	
			$rs=$this->execute($sql);
			$this->cuantos_estudiantes_son($where);
			return $rs;
		}
		//Metodo para registrar contactos
		function registrar_contacto($id_contacto)
		{
			$this->conectar();
			$sql="SELECT
						registrar_contacto('".$id_contacto."','".$_SESSION["id_us"]."');";
			$rs=$this->execute($sql);
			return $rs;
		}
		//Metodo para consultar perfil de usuarios
		function consultar_perfil_us($id_us)
		{
			$this->conectar();
			$sql="SELECT
						tbl_usuarios.id_usuarios,
						tbl_usuarios.usuario_login,
						tbl_usuarios.email,
						tbl_usuarios.clave,
						tbl_usuarios.id_persona,
						tbl_personas.nombres,
						tbl_usuarios.imagen_us,
						tbl_usuarios.estatus,
						upper(tbl_perfil.desc_perfil) AS perfil,
						upper(tbl_estado.nombre) AS estado,
						tbl_usuario_perfil.id_perfil
				FROM 
						tbl_usuarios
				RIGHT JOIN 
						tbl_personas
				ON 
						tbl_usuarios.id_persona=tbl_personas.id_persona
				LEFT JOIN
						tbl_usuario_perfil
				ON
						tbl_usuarios.id_usuarios=tbl_usuario_perfil.id_usuario
				LEFT JOIN
						tbl_perfil
				ON
						tbl_usuario_perfil.id_perfil=tbl_perfil.id_perfil		
				LEFT JOIN 
						tbl_estado
				ON 
						tbl_estado.codigoestado=tbl_personas.codigoestado
				where
						tbl_usuarios.id_usuarios='".$id_us."'		
						";
				$rs=$this->execute($sql);
				return $rs;		

		}
		//Metodo para consultar cursos segun usuarios
		function consultar_cursos($id_us,$perfil)
		{
			$this->conectar();
			if($perfil!=1)
			{
				/////////////////////////////////
				$sql2="SELECT 
						UPPER(tbl_formacion.titulo_formacion) AS titulo_eva
				  FROM 
				  		tbl_formacion
				  INNER JOIN 			
						tbl_inscripcion
				  ON
				  		tbl_inscripcion.id_formacion=tbl_formacion.id_formacion
				  WHERE 
				  		tbl_inscripcion.id_usuario='".$id_us."';";
				/////////////////////////////////	
			}	
			else
			if($perfil==1)
			{
				/////////////////////////////////
				$sql2="SELECT 
						UPPER(tbl_formacion.titulo_formacion) AS titulo_eva
				  FROM 
				  		tbl_formacion
				  INNER JOIN 			
						tbl_inscripcion
				  ON
				  		tbl_inscripcion.id_formacion=tbl_formacion.id_formacion
				  WHERE 
				  		tbl_formacion.id_us_creador='".$id_us."';";
				/////////////////////////////////
			}
			$rs=$this->execute($sql2);
			return $rs;
		}
		//Metodo para consultar contactos de un usuario
		function consultar_contactos($offset,$limit,$nombre,$aula)
		{
			//////////////////////////
			$where="";
			if($nombre!="")
			{
				$where.=" AND upper(b.nombres) like '%".$nombre."%'";
			}
			if(($aula!="")&&($aula!="-1"))
			{
				$where.=" AND (((SELECT COUNT(*) FROM tbl_formacion d WHERE d.id_formacion='".$aula."' AND d.id_us_creador=a.id_usuarios)>=1)
						  OR  ((SELECT COUNT(*) FROM tbl_inscripcion d WHERE d.id_formacion='".$aula."' AND d.id_usuario=a.id_usuarios)>=1))";
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
						upper(d.desc_perfil) AS perfil
				FROM
						tbl_usuarios a
				INNER join
						tbl_personas b
				ON 
						b.id_persona=a.id_persona
				INNER JOIN 
						tbl_usuario_perfil c
				ON 
						c.id_usuario=a.id_usuarios								
				INNER JOIN
						tbl_perfil  d
				ON 
						d.id_perfil=c.id_perfil	
				INNER JOIN 
						tbl_usuarios_contactos e
				ON 
						e.contacto=a.id_usuarios		
				WHERE 
						1=1
				AND 
						e.id_us='".$_SESSION["id_us"]."'
				".$where."		
				order by b.nombres ASC
				".$where2.";";
			$rs=$this->execute($sql);
			$this->cuantos_contactos_son($where);
			return $rs;
		}
		function cuantos_contactos_son($where)
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
						c.id_usuario=a.id_usuarios								
				INNER JOIN
						tbl_perfil  d
				ON 
						d.id_perfil=c.id_perfil	
				INNER JOIN 
						tbl_usuarios_contactos e
				ON 
						e.contacto=a.id_usuarios			
				WHERE 
						1=1
				AND 
						e.id_us='".$_SESSION["id_us"]."'
				".$where.";";
			$rs=$this->execute($sql);
			$this->num_rows_cnt=$rs[0][0];	  
		}
	function consultar_us_nombre($id)
	{
		$this->conectar();
		$sql="SELECT 
						lower(usuario_login)
			  FROM 
			 			tbl_usuarios
			  WHERE 
			  			id_usuarios='".$id."'";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar cuantos estudiantes hay y hacer la paginacion del reporte de estudiante
	function cuantos_estudiantes_reporte($where)
	{
		$sql2="SELECT
					COUNT(*)
			  FROM tbl_usuarios a
			  LEFT JOIN tbl_personas b
			  ON
			     a.id_persona=b.id_persona
			  LEFT JOIN tbl_estado c
			  ON
			     c.codigoestado=b.codigoestado
			  LEFT JOIN  tbl_municipio d
			  ON
			     d.codigomunicipio=b.codigomunicipio		
			  LEFT JOIN  tbl_parroquia e
			  ON
			     e.codigoparroquia=b.codigoparroquia
			  INNER JOIN tbl_usuario_perfil f
			  ON 
			  	f.id_usuario=a.id_usuarios
			  INNER JOIN
			  		tbl_inscripcion g
			  ON 
			  		g.id_usuario=a.id_usuarios
			  INNER JOIN 
			  		tbl_formacion h
			  ON 
			  		h.id_formacion=g.id_formacion					
			  WHERE 
			  		1=1 
			  AND 
			  		f.id_perfil=10			
			  ".$where."		
			  group by 
					a.id_usuarios;";
		$rs2=$this->execute($sql2);
		$this->num_rows2=$rs2[0][0];	  
	}

	function consultar_cuerpo_rep_estudiante($offset,$limit,$nacionalidad,$cedula,$nombre,$estado,$municipio,$parroquia,$sexo)
	{
		//--Filtros
		$where='';
		if(($nacionalidad!="")&&($nacionalidad!="-1"))
		{
			$where.=" AND b.nacionalidad='".$nacionalidad."'";
		}
		if($cedula!="")
		{
			$where.=" AND b.ced_persona='".$cedula."'";
		}
		if($nombre!="")
		{
			$where.=" AND upper(b.nombres) like '%".$nombre."%'";
		}
		if(($estado!="")&&($estado!="-1"))
		{
			$where.=" AND c.codigoestado='".$estado."'";
		}
		if(($municipio!="")&&($municipio!="-1"))
		{
			$where.=" AND d.codigomunicipio='".$municipio."'";
		}
		if(($parroquia!="")&&($parroquia!="-1"))
		{
			$where.=" AND e.codigoparroquia='".$parroquia."'";
		}	
		if(($sexo!='-1')&&($sexo!=''))
		{
			$where.=" AND b.sexo='".$sexo."'";
		}	
		//--
		//--Si el id_perfil es facilitador= 1
		if($_SESSION["id_perfil"]=='1')
		{
			$where.=" AND h.id_us_creador='".$_SESSION["id_us"]."'";			
		}	
		//--Para $where 2: limit y offset
		if(($offset!="")&&($limit!=""))
		{
			$where2="   limit '".$limit."' 
                		offset '".$offset."' ";
		}	
		//--
		$this->conectar();
		$sql="SELECT 	
					a.id_usuarios,
					b.id_persona,
					b.nacionalidad,
					b.ced_persona,
					upper(b.nombres),
					a.email,
					b.tlf_movil,
					upper(c.nombre) AS estado,
					upper(d.nombre) AS municipio,
					upper(e.nombre) AS parroquia,
					(CASE WHEN b.sexo='1' THEN 'Masculino'
						  WHEN b.sexo='0' THEN 'Femenino'
					END	  )AS sexo
			  FROM tbl_usuarios a
			  LEFT JOIN tbl_personas b
			  ON
			     a.id_persona=b.id_persona
			  LEFT JOIN tbl_estado c
			  ON
			     c.codigoestado=b.codigoestado
			  LEFT JOIN  tbl_municipio d
			  ON
			     d.codigomunicipio=b.codigomunicipio		
			  LEFT JOIN  tbl_parroquia e
			  ON
			     e.codigoparroquia=b.codigoparroquia
			  INNER JOIN tbl_usuario_perfil f
			  ON 
			  	f.id_usuario=a.id_usuarios
			  INNER JOIN
			  		tbl_inscripcion g
			  ON 
			  		g.id_usuario=a.id_usuarios
			  INNER JOIN 
			  		tbl_formacion h
			  ON 
			  		h.id_formacion=g.id_formacion					
			  WHERE 
			  		1=1 
			  AND 
			  		f.id_perfil=10			
			  ".$where."		
			  group by 
					a.id_usuarios,
					b.id_persona,
					b.nacionalidad,
					b.ced_persona,
					upper(b.nombres),
					a.email,
					b.tlf_movil,
					upper(c.nombre),
					upper(d.nombre),
					upper(e.nombre)					
			  order by b.id_persona DESC
			  ".$where2.";";
		//return $sql;	
		$rs=$this->execute($sql);
		$this->cuantos_estudiantes_reporte($where);
		return $rs;
	}
	//Metodo para consultar usuarios registrados
	function consultar_cuerpo_us($offset,$limit,$nacionalidad,$cedula,$nombre,$correo,$tipo_us)
	{
		//--Filtros
		$where="WHERE 1=1 and d.id_perfil!='4'";
		if(($nacionalidad!="")&&($nacionalidad!="-1"))
		{
			$where.=" AND b.nacionalidad='".$nacionalidad."'";
		}
		if($cedula!="")
		{
			$where.=" AND b.ced_persona='".$cedula."'";
		}
		if($nombre!="")
		{
			$where.=" AND upper(b.nombres) like '%".$nombre."%'";
		}
		if($correo!="")
		{
			$where.=" AND a.email like '%".$correo."%'";
		}
		if(($tipo_us!="")&&($tipo_us!="-1"))
		{
			$where.=" AND  c.id_perfil='".$tipo_us."'";
		}	
		//--FALTA TIPO_US
		//--Para $where 2: limit y offset
		if(($offset!="")&&($limit!=""))
		{
			$where2="   limit '".$limit."' 
                		offset '".$offset."' ";
		}	
		//--
		$this->conectar();
		$sql="SELECT 
					a.id_usuarios,
					b.id_persona,
					b.nacionalidad,
					b.ced_persona,
					upper(b.nombres) AS nombres,
					a.email,
					b.tlf_movil,
					upper(d.desc_perfil) AS perfil,
					a.estatus
			  FROM 
			  		tbl_usuarios a
			  INNER JOIN 
			  		tbl_personas b
			  ON 
			  		a.id_persona=b.id_persona
			  INNER JOIN 
			  		tbl_usuario_perfil c
			  ON
			  		c.id_usuario=a.id_usuarios
			  INNER JOIN 
			  		tbl_perfil d
			  ON 
			  		d.id_perfil=c.id_perfil
			  ".$where."
			  order by a.id_usuarios
			  ".$where2."		
			  ";
		//return $sql;	  
		$rs=$this->execute($sql);
		$this->cuantos_us_ad($where);
		return $rs;
	}	
	//Metodo para determinar cuantos usuarios son
	function cuantos_us_ad($where)
	{
		$sql2="SELECT 
							count(*)
				  FROM 
				  		tbl_usuarios a
				  INNER JOIN 
				  		tbl_personas b
				  ON 
				  		a.id_persona=b.id_persona
				  INNER JOIN 
				  		tbl_usuario_perfil c
				  ON
				  		c.id_usuario=a.id_usuarios
				  INNER JOIN 
				  		tbl_perfil d
				  ON 
				  		d.id_perfil=c.id_perfil
				  ".$where.";";
		//return $sql;	  
		$rs2=$this->execute($sql2);
		$this->num_rows4=$rs2[0][0];
	}
	//Metodo para determinar cuantos estados se muestran por pantalla
	function cuantos_beneficiados($where)
	{
		$sql="SELECT
						COUNT(*)
				FROM 
						tbl_estado a
			  	INNER JOIN	
						tbl_municipio x
			  	ON
						x.codigoestado=a.codigoestado	
			  	INNER JOIN 
			  			tbl_parroquia y
			  	ON
			        x.codigomunicipio=y.codigomunicipio
			  ".$where.";";
		$rs=$this->execute($sql);
		$this->num_rows3=$rs[0][0];	  
	}
	//Metodo para determinar la cantidad de beneficiarios por estado municipio y parroquia
	function consultar_cuerpo_beneficiados($offset,$limit,$estado,$municipio,$parroquia)
	{
		$this->conectar();
		$where="WHERE 1=1 ";
		if(($estado!="")&&($estado!="-1"))
		{
			$where.=" AND a.codigoestado='".$estado."'";
		}
		if(($municipio!="")&&($municipio!="-1"))
		{
			$where.=" AND x.codigomunicipio='".$municipio."'";
		}
		if(($parroquia!="")&&($parroquia!="-1"))
		{
			$where.=" AND y.codigoparroquia='".$parroquia."'";
		}	
		//--
		//--Para $where 2: limit y offset
		if(($offset!="")&&($limit!=""))
		{
			$where2="   limit '".$limit."' 
                		offset '".$offset."' ";
		}
		$sql=" SELECT 	
					upper(a.nombre) AS estado,
					upper(x.nombre) AS municipio,
					upper(y.nombre) AS parroquia,
					(SELECT 
						COUNT(*)
					 FROM 
						tbl_usuarios b 
					 INNER JOIN 
						tbl_personas c
					 ON 
						c.id_persona=b.id_persona
					 WHERE
						a.codigoestado=c.codigoestado
					 AND 
						x.codigomunicipio=c.codigomunicipio
					 AND 
						y.codigoparroquia=c.codigoparroquia						
					)
			  FROM 
				tbl_estado a
			  INNER JOIN	
				tbl_municipio x
			  ON
				x.codigoestado=a.codigoestado	
			  INNER JOIN 
			  	tbl_parroquia y
			  ON
			        x.codigomunicipio=y.codigomunicipio
			  ".$where."
  			  order by a.codigoestado
  			  ".$where2.";";
		$rs=$this->execute($sql);
		$this->cuantos_beneficiados($where);
		return $rs;	
		
	}
	////////////////////////////////////////////////////////////////////
	//--Metodo que permite inactivar a usuarios...
	function in_activar_us($id_persona)
	{
		$this->conectar();
		$sql="SELECT 
					in_activar_us('".$id_persona."');";
		//return $sql;
		$rs=$this->execute($sql);
		return $rs;
	}
	//--Metodo que verificar que existe el email
	function consultar_correo($correo)
	{
		$this->conectar();
		$sql="
				SELECT 
						COUNT(*)
				FROM 
						tbl_usuarios
				WHERE
						tbl_usuarios.email='".$correo."'";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para cambiar clave de correo
	function cambiar_clave_correo($clave1,$clave2,$correo)
	{
		$this->conectar();
		$sql=" SELECT cambiar_clave_correo('".$clave1."',
											'".$clave2."',
											'".$correo."'	
											)";
		//return $sql;
		$rs=$this->execute($sql);
		return $rs;
	}
	/////////////////////////////////////////////////////////////////////
}		
?>