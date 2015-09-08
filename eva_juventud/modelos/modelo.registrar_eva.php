<?php
require_once("../controladores/conex.php");
ini_set('upload_max_filesize', '2048M');
ini_set('post_max_size','2048M');
ini_set('memory_limit', '2048M'); 
class espacio_v extends Conex
{
	public $num_rows;
	public $num_rows2;
	public $num_rows3;
	public $num_rows4;
	public $num_rows5;
	public $num_rows6;
	public $num_rows7;
	public $num_rows8;
	function registrar_eva($id_eva,$titulo,$introduccion,$objetivos,$unidades,$resumen,$tipo_formacion,$opcion_evaluada,$fecha_activacion_eva)
	{
		$this->conectar();
		/////////////////////////////
		$sql="SELECT registrar_eva(
								    ".$id_eva.",
								    '".$titulo."',
								    '".$introduccion."',
								    '".$objetivos."',
								    ".$unidades.",
								    '".$resumen."',
								    '".$fecha_activacion_eva."',
								    ".$_SESSION['id_us'].",
								    ".$tipo_formacion.",
								    ".$opcion_evaluada."
								);";
	//	return($sql);
		$rs=$this->execute($sql);
		return $rs;
	}
	//metodo consultar aulas
	function consultar_aulas()
	{
		$id_us=$_SESSION["id_us"];
		//-- Si es administrador
		if($_SESSION["id_perfil"]==4)
		{
			$where=" WHERE 1=1 ";
		}
		else
		{
			$where=" WHERE id_us_creador=".$_SESSION["id_us"];
		}
		//--
		$sql="
				SELECT id_formacion,titulo_formacion
				FROM 
					tbl_formacion
				".$where."
				order by id_formacion DESC
				;";
		$this->conectar();
		$aula_virtual=$this->execute($sql);
		return $aula_virtual;
	}
	//Metodo consultar aulas aprobadas por el usuario
	function consultar_aulas_aprobadas()
	{
		$id_us=$_SESSION["id_us"];
		$where="	WHERE b.id_us='".$id_us."' AND b.calificacion>=50";
		$sql="
				SELECT 
						a.id_formacion,
						a.titulo_formacion
				FROM 
					tbl_formacion a
				INNER JOIN 
					tbl_calificaciones_definitivas b
				ON 
					a.id_formacion=b.id_formacion	 	
				".$where."
				order by id_formacion DESC
				;";
		$this->conectar();
		$aula_virtual=$this->execute($sql);
		return $aula_virtual;
	}
	//Metodo consultar aulas aprobadas por el usuario
	function consultar_aulas_us()
	{
		$id_us=$_SESSION["id_us"];
		$where="	WHERE b.id_usuario='".$id_us."'";
		$sql="
				SELECT 
						a.id_formacion,
						a.titulo_formacion
				FROM 
					tbl_formacion a
				INNER JOIN 
					tbl_inscripcion b
				ON 
					a.id_formacion=b.id_formacion	 	
				".$where."
				order by id_formacion DESC
				;";
		$this->conectar();
		$aula_virtual=$this->execute($sql);
		return $aula_virtual;
	}
	//Metodo que consulta las aulas de un usuario
	function consultar_misaulas()
	{
		$this->conectar();
		$where.=" AND c.id_usuario='".$_SESSION['id_us']."' ";
		$fecha_actual=date("d-m-Y");
		$sql="SELECT 
					a.id_formacion,
					upper(a.titulo_formacion)
			FROM
					tbl_formacion a
			INNER JOIN 
					tbl_inscripcion c
			ON 
					c.id_formacion=a.id_formacion						
			WHERE 
					1=1
			AND
					estatus_aula=1		
			AND 
					(SELECT COUNT(*) FROM tbl_contenidos_formacion WHERE tbl_contenidos_formacion.id_formacion=a.id_formacion)>0
			AND
					a.fecha <='".$fecha_actual."'
			".$where."
			ORDER BY a.fecha DESC;";
		//return $sql;
		$rs=$this->execute($sql);
		return $rs;
	}
	//metodo consultar unidades
	function consultar_unidad($aula)
	{
		$sql="
				SELECT cuantas_unidades
				FROM 
					tbl_formacion
				WHERE 
				id_formacion='".$aula."'";
		$this->conectar();
		$cuantas_unidades=$this->execute($sql);
		return $cuantas_unidades;
	}
	//metodo consultar unidades cargadas en contenidos
	function consultar_unidad2($aula)
	{
		$sql="
				SELECT id_contenido,unidad
				FROM 
					tbl_contenidos_formacion
				WHERE 
				id_formacion = '".$aula."'
				order by unidad
				";
		$this->conectar();
		$rs_unidades=$this->execute($sql);
		return $rs_unidades;
	}
	//metodo para cargar unidades a un espacio virtual
	function cargar_unidades($aula,$unidades,$material,$contenido,$id_contenido,$fecha_activacion_unidad,$titulo_aula)
	{
		//////////////////////////////////////////////////////////
			$sql="SELECT registrar_unidades(
		    ".$aula.",
		    ".$unidades.",
		    '".$material."',
		    '".$contenido."',
		    ".$id_contenido.",
		    '".$fecha_activacion_unidad."',
		    '".$titulo_aula."'
		    );";
			$this->conectar();
			$recordset=$this->execute($sql);
			return $recordset;
		/////////////////////////////////////////////////////////	
	}

	//metodo para determinar la cantidad de aulas para ese usuario...
	function cuantos_son($where)
	{
		//calculo cuantos son..
        $sql2="SELECT 
                  count(*)
                FROM 
               		tbl_formacion
  			  	".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows=$rs2[0][0];
        ////////////////////////////////////////////
	}

	//metodo que permite consultar el cuerpo del modal perteneciente a eva de ese usuario
	function consultar_cuerpo_ceva($fceva,$fceva2,$offset,$limit)
	{
		$this->conectar();
		//-- Si es administrador
		if($_SESSION["id_perfil"]==4)
		{
			$where=" WHERE 1=1";
		}
		else
		{
			$where="WHERE id_us_creador=".$_SESSION["id_us"];
		}	
		if($fceva!="")
		{
			$where.="  AND upper(tbl_formacion.titulo_formacion) like '%".$fceva."%'";
		}
		if($fceva2!="")
		{
			$where.="  AND upper(tbl_tipo_formacion.desc_tipo_formacion) like '%".$fceva2."%'";
		}
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		$sql="SELECT 
					tbl_formacion.id_formacion, 
					UPPER(tbl_formacion.titulo_formacion),
					UPPER(tbl_tipo_formacion.desc_tipo_formacion), 
       				tbl_formacion.cuantas_unidades
 			 FROM 
 			 		tbl_formacion
 			 INNER JOIN 
 			 		tbl_tipo_formacion
 			 ON 
 			 		tbl_tipo_formacion.id_tipo_formacion=tbl_formacion.id_tipo_formacion				
 			".$where."
  			order by tbl_formacion.id_formacion desc
  			".$where2.";";
  		//return $sql;	  
		$rs=$this->execute($sql);
		///////////////////////////////////////////
		//calculo cuantos son...
		$this->cuantos_son($where);
		return $rs;
	}
	//metodo usado para cargr consulta en el formulario
	function consultar_eva($id_eva)
	{
		$this->conectar();
		if($_SESSION["id_perfil"]==4)
		{
			$where="WHERE 1=1";
		}else
		{
			$where="WHERE id_us_creador=".$_SESSION["id_us"];
		}
		if($id_eva!="")
		{
			$where.=" AND 
  			  		    tbl_formacion.id_formacion=".$id_eva;
		}	
		$sql="SELECT 
					id_formacion,
					upper(titulo_formacion),
					id_tipo_formacion,
					substr(introduccion,0,600),
					objetivos,
					resumen_unidad,
					cuantas_unidades,
					evaluado,
					to_char(fecha,'DD-MM-YYYY'),
					hora_ac
  			  FROM 
  			        tbl_formacion
  			  ".$where.";";
  		//return $sql;	  
		$rs=$this->execute($sql);
		return $rs;
	}
	//metodo para determinar la cantidad de aulas para ese usuario...
	function cuantos_son_contenido($where)
	{
		//calculo cuantos son..
        $sql2="SELECT 
                  count(*)
                FROM 
               		tbl_contenidos_formacion
               	INNER JOIN 
               		tbl_formacion
               	ON 
               		tbl_contenidos_formacion.id_formacion=tbl_formacion.id_formacion		
  			  	".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows2=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//metodo para consultar cuerpo de contenido
	function consultar_cuerpo_contenido($faula,$funidades,$offset,$limit)
	{
		$this->conectar();
		//-- Si es administrador
		if($_SESSION["id_perfil"]==4)
		{
			$where=" WHERE 1=1 ";
		}
		else
		{
			$where=" WHERE id_us_creador=".$_SESSION["id_us"];
		}
		//--
		if($faula!="")
		{
			$where.="  AND upper(tbl_formacion.titulo_formacion) like '%".$faula."%'";
		}
		if($funidades!="")
		{
			$where.="  AND tbl_contenidos_formacion.unidad=".$funidades;
		}
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		$sql="SELECT 
					tbl_contenidos_formacion.id_contenido,
					upper(tbl_formacion.titulo_formacion), 
					tbl_contenidos_formacion.unidad,
					tbl_contenidos_formacion.ruta_video,
					tbl_contenidos_formacion.desc_contenido
 			 	FROM 
               		tbl_contenidos_formacion
               	INNER JOIN 
               		tbl_formacion
               	ON 
               		tbl_contenidos_formacion.id_formacion=tbl_formacion.id_formacion				
 			".$where."
  			order by tbl_formacion.id_formacion DESC,tbl_contenidos_formacion.unidad 
  			".$where2.";";
  		//return $sql;consultar_contenido	  
		$rs=$this->execute($sql);
		///////////////////////////////////////////
		//calculo cuantos son...
		$this->cuantos_son_contenido($where);
		return $rs;
	}

	//metodo para cargar la consulta de contenido segun id
	function consultar_contenido($id_contenido)
	{
		$this->conectar();
		$where="WHERE 1=1 ";
		if($id_contenido!="")
		{
			$where.=" AND 
  			  		    tbl_contenidos_formacion.id_contenido=".$id_contenido;
		}	
		$sql="SELECT 
					id_contenido,
					id_formacion,
					unidad,
					ruta_video,
					desc_contenido,
					upper(titulo_contenido) AS titulo_contenido,
					to_char(fecha_contenido,'DD-MM-YYYY'),
					hora
  			  FROM 
  			        tbl_contenidos_formacion
  			  ".$where.";";
  		//return $sql;	  
		$rs=$this->execute($sql);
		return $rs;	
	}
	//metodo para cargar los pdf o materiales con los que cuenta
	function consultar_archivos($id_contenido)
	{
		$this->conectar();
		$where="WHERE 1=1 ";
		if( $id_contenido!="")
		{
			$where.=" AND id_contenido_formacion='".$id_contenido."';";
		}
		$sql="SELECT
					 ruta_pdf
			  FROM
			  		tbl_contenidos_pdf
			  $where";
		//return $sql;	  
		$rs=$this->execute($sql);
		return $rs;	  	
	}
	//metodo para cargar archivos pdf en el directorio pdf...
	function cargar_archivos_unidad($ruta_pdf,$nombre_pdf,$tipo_archivo_pdf,$tamano_pdf,$tmp_name,$id_cont,$tipo_archivo)
	{
		/////////////////////////////////////////////////////////////
		if($ruta_pdf!="")
		{
			//--Para validar tipo archivo
			if (!((strpos($tipo_archivo_pdf, $tipo_archivo)))) 
			{
			    return "error_tipo_archivo";
			}
			else
			{
				//muevo la imagen de directorio
				if (move_uploaded_file($tmp_name,$ruta_pdf))
			    {
				  	chmod($ruta_pdf,0777);//permisos al directorio
				  	return 1;
				}
			}	
		}else
		{
			return 0;
		}
		//////////////////////////////////////////////////////////
	}
	//Metodo que registra los pdf que pertenece a una unidad
	function registrar_pdf($ruta_pdf,$id_cont)
	{
		$this->conectar();
		$sql="SELECT registrar_pdf(
  					  '".$ruta_pdf."',
    				  ".$id_cont.");";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo que registra los videos que pertenece a una unidad
	function registrar_video($ruta_video,$id_cont)
	{
		$this->conectar();
		$sql="SELECT registrar_video(
  					  '".$ruta_video."',
    				  ".$id_cont.");";
		$rs=$this->execute($sql);
		return $rs;
	}
	//metodo para consultarsi la unidad ya fue cargada
	function consultar_unidades($aula,$unidad)
	{
		$this->conectar();
		$sql="SELECT
					tbl_contenidos_formacion.id_contenido,
					tbl_contenidos_formacion.ruta_video,
					tbl_contenidos_formacion.desc_contenido,
					tbl_contenidos_formacion.unidad,
					tbl_contenidos_formacion.desc_contenido,
					upper(tbl_contenidos_formacion.titulo_contenido) AS titulo_contenido,
					to_char(tbl_contenidos_formacion.fecha_contenido,'DD-MM-YYYY'),
					tbl_contenidos_formacion.hora
			  FROM
			  		tbl_contenidos_formacion
			  WHERE 
			  		 id_formacion=".$aula."
			  AND
			  		 unidad=".$unidad.";";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para determinar cuantos_son los elementos de la tabla
	function cuantos_son_cuerpo_tabla($where)
	{
		$this->conectar();
		//calculo cuantos son..
        $sql2="SELECT 
                  count(*)
                FROM 
               			tbl_formacion a
                WHERE
               			1=1
               	AND 
    					estatus_aula=1			
               	AND	
        		(	SELECT COUNT(*) FROM tbl_contenidos_formacion WHERE tbl_contenidos_formacion.id_formacion=a.id_formacion)>0
				".$where."			
  			  	;";
        $rs2=$this->execute($sql2);
        $this->num_rows3=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//Metodo para consultar tabla eva que veran los usuarios estudiantes
	function consultar_cuerpo_tabla_eva($f_nombre_eva,$offset,$limit,$f_fecha_aula,$f_opcion,$f_selec_tipo)
	{
		$this->conectar();
		$where='';
		if($f_nombre_eva!="")
		{
			$where.="  AND upper(a.titulo_formacion) like '%".$f_nombre_eva."%'";
		}
		if($f_fecha_aula!="")
		{
			$where.="	AND a.fecha='".$f_fecha_aula."'";
		}
		if($f_selec_tipo!="")
		{
			$where.="	AND a.id_tipo_formacion='".$f_selec_tipo."'";
		}	
		if($f_opcion!="")
		{
			$where.=" 	AND a.evaluado='".$f_opcion."'";
		}	
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		$fecha_actual=date("d-m-Y");
		$sql="SELECT 
					a.id_formacion,
					upper(a.titulo_formacion),
					(CASE WHEN a.evaluado='1' THEN 'SI'
						 ELSE 'NO'
					END) AS evaluado,
					a.introduccion,
					a.objetivos,
					a.resumen_unidad,
					to_char(a.fecha,'DD-MM-YYYY') AS fecha_formacion,
					a.imagen_formacion,
					(SELECT COUNT(*) FROM tbl_contenidos_formacion WHERE tbl_contenidos_formacion.id_formacion=a.id_formacion)AS un,
					(SELECT COUNT(*) FROM tbl_evaluacion WHERE tbl_evaluacion.id_formacion=a.id_formacion) AS evaluacion,
					upper(b.desc_tipo_formacion)
			FROM
					tbl_formacion a
			INNER JOIN
					tbl_tipo_formacion b
			ON		
					a.id_tipo_formacion=b.id_tipo_formacion		
			WHERE 
					1=1
			AND
					estatus_aula=1		
			AND 
					(SELECT COUNT(*) FROM tbl_contenidos_formacion WHERE tbl_contenidos_formacion.id_formacion=a.id_formacion)>0
			AND
					a.fecha <='".$fecha_actual."'
			".$where."
			ORDER BY a.id_formacion DESC,a.fecha DESC
			".$where2."
			;";
		//return $sql;
		$rs=$this->execute($sql);
		$this->cuantos_son_cuerpo_tabla($where);
		return $rs;
	}
	//Metodo para consultar tabla eva que veran los usuarios estudiantes
	function consultar_cuerpo_tabla_miseva($f_nombre_eva,$offset,$limit,$f_fecha_aula,$f_opcion,$f_selec_tipo)
	{
		$this->conectar();
		$where='';
		if($f_nombre_eva!="")
		{
			$where.="  AND upper(a.titulo_formacion) like '%".$f_nombre_eva."%'";
		}
		if($f_fecha_aula!="")
		{
			$where.="	AND a.fecha='".$f_fecha_aula."'";
		}
		if($f_selec_tipo!="")
		{
			$where.="	AND a.id_tipo_formacion='".$f_selec_tipo."'";
		}	
		if($f_opcion!="")
		{
			$where.=" 	AND a.evaluado='".$f_opcion."'";
		}	
		$where.=" AND c.id_usuario='".$_SESSION['id_us']."'";
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}

		$fecha_actual=date("d-m-Y");
		$sql="SELECT 
					a.id_formacion,
					upper(a.titulo_formacion),
					(CASE WHEN a.evaluado='1' THEN 'SI'
						 ELSE 'NO'
					END) AS evaluado,
					a.introduccion,
					a.objetivos,
					a.resumen_unidad,
					to_char(a.fecha,'DD-MM-YYYY') AS fecha_formacion,
					a.imagen_formacion,
					(SELECT COUNT(*) FROM tbl_contenidos_formacion WHERE tbl_contenidos_formacion.id_formacion=a.id_formacion)AS un,
					(SELECT COUNT(*) FROM tbl_evaluacion WHERE tbl_evaluacion.id_formacion=a.id_formacion) AS evaluacion,
					upper(b.desc_tipo_formacion)
			FROM
					tbl_formacion a
			INNER JOIN
					tbl_tipo_formacion b
			ON		
					a.id_tipo_formacion=b.id_tipo_formacion
			INNER JOIN 
					tbl_inscripcion c
			ON 
					c.id_formacion=a.id_formacion						
			WHERE 
					1=1
			AND
					estatus_aula=1		
			AND 
					(SELECT COUNT(*) FROM tbl_contenidos_formacion WHERE tbl_contenidos_formacion.id_formacion=a.id_formacion)>0
			AND
					a.fecha <='".$fecha_actual."'
			".$where."
			ORDER BY a.fecha DESC
			".$where2."
			;";
		//return $sql;
		$rs=$this->execute($sql);
		$this->cuantos_son_cuerpo_tabla_miseva($where);
		return $rs;
	}
	//Metodo para cdeterminar cuantos registros son
	function cuantos_son_cuerpo_tabla_miseva($where)
	{
		$this->conectar();
		//calculo cuantos son..
        $sql2="SELECT 
                  count(*)
                FROM
					tbl_formacion a
				INNER JOIN
						tbl_tipo_formacion b
				ON		
						a.id_tipo_formacion=b.id_tipo_formacion
				INNER JOIN 
						tbl_inscripcion c
				ON 
						c.id_formacion=a.id_formacion
                WHERE
               			1=1
               	AND 
    					estatus_aula=1			
               	AND	
        		(	SELECT COUNT(*) FROM tbl_contenidos_formacion WHERE tbl_contenidos_formacion.id_formacion=a.id_formacion)>0
				".$where."			
  			  	;";
        $rs2=$this->execute($sql2);
        $this->num_rows3=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//Metodo para consultar resumen de notas
	function consultar_cuerpo_tabla_resumen_notas($offset,$limit,$f_nombre_eva,$f_nombre_contenido,$f_n_contenido,$id_aula)
	{
		$this->conectar();
		$where='';
		$where.=" AND c.id_usuario='".$_SESSION['id_us']."'";
		if($id_aula!="")
		{
			$where.=" AND a.id_formacion='".$id_aula."'";
		}	
		if($f_nombre_eva!="")
		{
			$where.="  AND upper(a.titulo_formacion) like '%".$f_nombre_eva."%'";
		}
		if($f_nombre_contenido!="")
		{
			$where.="  AND upper(b.titulo_contenido) like '%".$f_nombre_contenido."%'";
		}
		if($f_n_contenido!="")
		{
			$where.="  AND b.unidad='".$f_n_contenido."'";
		}	
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		$fecha_actual=date("d-m-Y");
		$sql="SELECT 
					a.id_formacion,
					b.id_contenido,
					upper(a.titulo_formacion),
					upper(b.titulo_contenido),
					b.unidad,
					(SELECT d.nota_evaluacion FROM tbl_calificaciones d inner join tbl_evaluacion f on d.id_evaluacion=f.id_evaluacion  WHERE d.id_us='".$_SESSION['id_us']."' and f.id_contenido=b.id_contenido AND f.id_tipo_evaluacion='3' ) as suma
			FROM
					tbl_formacion a
			INNER JOIN
					tbl_contenidos_formacion b
			ON		
					a.id_formacion=b.id_formacion		
			INNER JOIN 
					tbl_inscripcion c
			ON 
					c.id_formacion=a.id_formacion	
			INNER JOIN
					tbl_evaluacion f 
			ON 
					f.id_formacion=a.id_formacion									
			WHERE 
					1=1
			AND		f.id_contenido=b.id_contenido				
			AND 
					f.id_tipo_evaluacion='3'		
			AND
					estatus_aula=1		
			AND 
					a.evaluado='1'
			AND
					a.fecha <='".$fecha_actual."'
			".$where."
			ORDER BY b.unidad
			".$where2."
			;";
		//return $sql;
		$rs=$this->execute($sql);
		$this->cuantos_son_cuerpo_tabla_resumen_notas($where);
		return $rs;
	}
	//Metodo para consultar cuantos son resumen notas
	function cuantos_son_cuerpo_tabla_resumen_notas($where)
	{
		$fecha_actual=date("d-m-Y");
		$this->conectar();
		//calculo cuantos son..
        $sql2="	SELECT 
                  count(*)
               	FROM
					tbl_formacion a
				INNER JOIN
						tbl_contenidos_formacion b
				ON		
						a.id_formacion=b.id_formacion		
				INNER JOIN 
						tbl_inscripcion c
				ON 
						c.id_formacion=a.id_formacion
				WHERE 
						1=1
				AND
						estatus_aula=1		
				AND
						a.fecha <='".$fecha_actual."'
				".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows8=$rs2[0][0];
        ////////////////////////////////////////////	
	}
	//Metodo para consultar resumen de eva
	function consultar_eva_resumen($id_eva)
	{
		$this->conectar();
		if($id_eva!="")
		{
			$where.=" AND tbl_formacion.id_formacion=".$id_eva.";";
		}	
		$sql="SELECT 
					tbl_formacion.id_formacion,
					upper(tbl_formacion.titulo_formacion),
					substr(tbl_formacion.introduccion,0,600),
					tbl_formacion.resumen_unidad,
					substr(tbl_formacion.objetivos,0,1000),
					tbl_curriculum.resumen_profesional,
					tbl_usuarios.imagen_us,
					tbl_personas.nombres,
					tbl_formacion.imagen_formacion
			  FROM
			  		tbl_formacion
			  LEFT JOIN
			  		tbl_curriculum
			  ON 
			  		tbl_formacion.id_us_creador=tbl_curriculum.id_usuario
			  INNER JOIN 
			  		tbl_usuarios
			  ON 
			  		tbl_formacion.id_us_creador=tbl_usuarios.id_usuarios				
			  INNER JOIN 
			  		tbl_personas
			  ON 
			  		tbl_personas.id_persona=tbl_usuarios.id_persona		
			  WHERE 1=1
			  ".$where.";";
		$rs=$this->execute($sql);	  
		return $rs;
	}
	//
	//Metodo para consultar unidades segun id del aula
	function consultar_unidad_aula($aula)
	{
		$this->conectar();
		$sql="SELECT 
					tbl_contenidos_formacion.titulo_contenido
			  FROM
			  		tbl_contenidos_formacion
			  WHERE
			  		id_formacion=".$aula.";";
		$rs=$this->execute($sql);
		return $rs;	  		
	}
	//Metodo para consultar si el usuario esta inscrito
	function consultar_inscripcion($id_eva,$id_us)
	{
		$this->conectar();
		$sql="SELECT
					id_inscripcion
			  FROM		
					tbl_inscripcion
			  WHERE
			  		id_formacion=".$id_eva."
			  AND		
			  		id_usuario=".$id_us.";";		
		$rs=$this->execute($sql);
		return $rs;	
	}
	//Metodo para consultar contenidos del aula por el estudiante
	function consultar_contenidos_aula($aula)
	{
		$this->conectar();
		$fecha_actual=date("d-m-Y");
		$sql="SELECT
					a.id_formacion,
					upper(a.titulo_formacion),
					b.id_contenido,
					b.ruta_video,
					b.desc_contenido,
					b.unidad, 
       				upper(b.titulo_contenido),
       				(SELECT COUNT(*) FROM tbl_evaluacion WHERE b.id_contenido=tbl_evaluacion.id_contenido AND tbl_evaluacion.fecha_ac<='".$fecha_actual."' AND tbl_evaluacion.fecha_cierre>'".$fecha_actual."') AS prueba
       		  FROM
       		  		tbl_formacion a
       		  INNER JOIN 
       		  		tbl_contenidos_formacion b
       		  ON		
       		  		a.id_formacion=b.id_formacion
       		  WHERE
       		  		a.id_formacion=".$aula."
       		  AND
       		  		b.fecha_contenido<='".$fecha_actual."'		
       		  ORDER BY unidad;";
       	$rs=$this->execute($sql);
       	return $rs;	  		
	}
	//Metodo para consultar contenidos del aula por el administrador
	function consultar_contenidos_aula_adm($aula)
	{
		$this->conectar();
		$sql="SELECT
					a.id_formacion,
					upper(a.titulo_formacion),
					b.id_contenido,
					b.ruta_video,
					b.desc_contenido,
					b.unidad, 
       				upper(b.titulo_contenido),
       				(SELECT COUNT(*) FROM tbl_evaluacion WHERE b.id_contenido=tbl_evaluacion.id_contenido) AS prueba
       		  FROM
       		  		tbl_formacion a
       		  INNER JOIN 
       		  		tbl_contenidos_formacion b
       		  ON		
       		  		a.id_formacion=b.id_formacion
       		  WHERE
       		  		a.id_formacion=".$aula."
       		  ORDER BY unidad;";
       	$rs=$this->execute($sql);
       	return $rs;
	}
	//
	function consultar_evaluaciones_p($id_contenido)
	{
		$this->conectar();
		$sql="SELECT
					a.id_evaluacion,
					upper(b.tipo_evaluacion),
					(SELECT count(*) from tbl_evaluacion_preguntas where tbl_evaluacion_preguntas.id_evaluacion=a.id_evaluacion)
			  FROM
			  		tbl_evaluacion a
			  INNER JOIN
			  		tbl_tipo_evaluacion b
			  ON		
			  		a.id_tipo_evaluacion=b.id_tipo_evaluacion
			  WHERE
			  		a.id_contenido=".$id_contenido;
		$rs=$this->execute($sql);
		return $rs;	  		
	}
	////////////////////////////////////////////////////////////////////////
	//Metodo para actualizar imagen del eva
	function ac_imagen_eva($nombre_imagen,$id_eva)
	{
		$this->conectar();
		$sql="SELECT actualizar_imagen_eva('".$nombre_imagen."',".$id_eva.");";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo que permite capturar el id_formación de una unidad
	function consultar_formacion($id_contenido,$id_evaluacion)
	{
		$this->conectar();
		$sql="SELECT 
					tbl_evaluacion.id_formacion
			  FROM 
			  		tbl_evaluacion
			  WHERE
			  		
			  		tbl_evaluacion.id_contenido=".$id_contenido."
			  AND 	
			  		tbl_evaluacion.id_evaluacion=".$id_evaluacion."				
			  ";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo que permite consultar información de un aula/unidad/prueba
	function consultar_cuerpo_tabla_eva_adm($f_nombre_eva,$offset,$limit,$f_fecha_aula,$f_selec_tipo)
	{
		$this->conectar();
		$where='';
		if($_SESSION["id_perfil"]==1)
		{
			$where.=" AND a.id_us_creador='".$_SESSION["id_us"]."'";
		}	
		if($f_nombre_eva!="")
		{
			$where.="  AND upper(a.titulo_formacion) like '%".$f_nombre_eva."%'";
		}
		if($f_fecha_aula!="")
		{
			$where.="	AND a.fecha='".$f_fecha_aula."'";
		}
		if(($f_selec_tipo!="")&&($f_selec_tipo!="0"))
		{
			$where.="	AND a.id_tipo_formacion='".$f_selec_tipo."'";
		}		
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		$fecha_actual=date("d-m-Y");
		$sql="SELECT 
					a.id_formacion,
					a.imagen_formacion,
					upper(a.titulo_formacion),
					a.resumen_unidad,
					b.desc_tipo_formacion,
					to_char(a.fecha,'DD-MM-YYYY') AS fecha_formacion,
					(SELECT COUNT(*) FROM tbl_contenidos_formacion WHERE tbl_contenidos_formacion.id_formacion=a.id_formacion)AS un,
					(SELECT COUNT(*) FROM tbl_evaluacion WHERE tbl_evaluacion.id_formacion=a.id_formacion) AS evaluacion,
					a.estatus_aula 
			FROM
					tbl_formacion a,
					tbl_tipo_formacion b
			WHERE 
					a.id_tipo_formacion=b.id_tipo_formacion	
			".$where."
			ORDER BY a.fecha DESC,a.id_formacion DESC
			".$where2."
			;";
		//return $sql;	
		$rs=$this->execute($sql);
		$this->cuantos_son_cuerpo_tabla_adm($where);
		return $rs;
	} 
	//Metodo para determinar cuantos_son los elementos de la tabla
	function cuantos_son_cuerpo_tabla_adm($where)
	{
		$this->conectar();
		//calculo cuantos son..
        $sql2=" SELECT 
                  		count(*)
		        FROM
					tbl_formacion a,
					tbl_tipo_formacion b
               	WHERE
               		a.id_tipo_formacion=b.id_tipo_formacion	
               	".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows4=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//Metodo para habilitar/deshabilitar aulas
	function in_activar_aula($id_aula)
	{
		$this->conectar();
		$sql="SELECT in_activar_aula(".$id_aula.")";
		$rs2=$this->execute($sql);
		return $rs2;
	}
	//Metodo para cargar consulta tablas de unidades
	function consultar_cuerpo_tabla_un_adm($id_aula,$f_ti_un,$f_n_un,$offset,$limit,$f_ti_eva)
	{
		$this->conectar();
		$where='';
		if($_SESSION["id_perfil"]==1)
		{
			$where.=" AND tbl_formacion.id_us_creador='".$_SESSION["id_us"]."'";
		}	
		if($f_ti_un!="")
		{
			$where.="  AND upper(tbl_contenidos_formacion.titulo_contenido) like '%".$f_ti_un."%'";
		}
		if($f_n_un!="")
		{
			$where.="AND tbl_contenidos_formacion.unidad='".$f_n_un."'";
		}	
		if($id_aula!="")
		{
			$where.="  AND  tbl_contenidos_formacion.id_formacion='".$id_aula."'";
		}
		if($f_ti_eva!="")
		{
			$where.=" AND upper(tbl_formacion.titulo_formacion) like '%".$f_ti_eva."%'";
		}	
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		$sql="SELECT 
					tbl_contenidos_formacion.id_contenido,
					upper(tbl_contenidos_formacion.titulo_contenido),
					tbl_contenidos_formacion.unidad,
					(substr(tbl_contenidos_formacion.desc_contenido,0,300)||'...')AS desc_contenido,
			 		upper(tbl_formacion.titulo_formacion)
			 	FROM		
			  		tbl_contenidos_formacion
			  	INNER JOIN
			  		tbl_formacion
			  	ON
			  		tbl_formacion.id_formacion=tbl_contenidos_formacion.id_formacion		
			  WHERE 1=1		
			  ".$where."
			  ORDER BY tbl_formacion.fecha DESC,tbl_contenidos_formacion.id_contenido ASC
			  ".$where2.";";
		$rs2=$this->execute($sql);
		$this->cuantos_son_cuerpo_tabla_und($where);
		return $rs2;
	}
	//Metodo para determinar cuantas unidades hay de determinada aula
	function cuantos_son_cuerpo_tabla_und($where)
	{
		$this->conectar();
		//calculo cuantos son..
        $sql2=" SELECT 
                  		count(*)
		        FROM		
			  		tbl_contenidos_formacion
			  	INNER JOIN
			  		tbl_formacion
			  	ON
			  		tbl_formacion.id_formacion=tbl_contenidos_formacion.id_formacion
               	WHERE
               		1=1
               	".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows5=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//Metodo para determinar cuantas unidades hay en aula
	function cuantos_son_cuerpo_tabla_eval($where)
	{
		$this->conectar();
		//calculo cuantos son..
        $sql2="  SELECT 
                  		count(*)
				 FROM
				 		tbl_evaluacion a
				 INNER JOIN 
				 		tbl_contenidos_formacion b
				 ON
				 		b.id_contenido=a.id_contenido
				 INNER JOIN 
				 		tbl_tipo_evaluacion c
				 ON
				 		c.id_tipo_evaluacion=a.id_tipo_evaluacion						
				 INNER JOIN
				  		tbl_formacion d
				 ON
				  		d.id_formacion=a.id_formacion	
               	 WHERE
               			1=1
               	".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows6=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//Metodo para determinar cuantas unidades hay en aula de une estudiante..
	function cuantos_son_cuerpo_tabla_mieval($where)
	{
		$this->conectar();
		//calculo cuantos son..
        $sql2="SELECT 
                  		count(*)
				 FROM
						tbl_evaluacion a
				 INNER JOIN 
						tbl_contenidos_formacion b
				 ON
						b.id_contenido=a.id_contenido
				 INNER JOIN 
						tbl_tipo_evaluacion c
				 ON
						c.id_tipo_evaluacion=a.id_tipo_evaluacion						
				 INNER JOIN
						tbl_formacion d
				 ON
						d.id_formacion=a.id_formacion	
				 INNER JOIN 
				 		tbl_inscripcion e
				 ON  
				 		e.id_formacion=a.id_formacion					
				 WHERE
						1=1 
				 AND
				 		a.estatus='1'	
				 AND
				 		e.id_usuario='".$_SESSION['id_us']."'
               	".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows7=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//Metodo para consultar cuerpo de la evaluacion.
	function consultar_cuerpo_tabla_eval($id_aula,$f_ti_unidad,$f_fecha_eval,$f_tipo_eval,$offset,$limit,$f_ti_eva,$f_estatus)
	{
		$this->conectar();
		$where='';
		if($_SESSION["id_perfil"]==1)
		{
			$where.=" AND d.id_us_creador='".$_SESSION["id_us"]."'";
		}	
		if($id_aula!="")
		{
			$where.=" AND a.id_formacion='".$id_aula."'";
		}
		if($f_ti_eva!="")
		{
			$where.=" AND upper(d.titulo_formacion) like '%".$f_ti_eva."%'";
		}	
		if($f_ti_unidad!="")
		{
			$where.=" AND upper(b.titulo_contenido) like '%".$f_ti_unidad."%'";
		}
		if($f_fecha_eval!="")
		{
			$where.=" AND a.fecha_ac='".$f_fecha_eval."'";
		}
		if(($f_tipo_eval!="")&&($f_tipo_eval!="-1"))
		{
			$where.=" AND c.id_tipo_evaluacion='".$f_tipo_eval."'";
		}
		if(($f_estatus!="")&&($f_estatus!="-1"))
		{
			$where.=" AND a.estatus='".$f_estatus."'";
		}	
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                     offset '".$offset."' ";
		}
       	$sql="SELECT  
	       		  	a.id_evaluacion,
	       		  	UPPER(b.titulo_contenido)AS titulo_contenido,
	       		  	a.descripcion,
	       		  	UPPER(c.tipo_evaluacion) AS tipo_evaluacion,
					to_char(a.fecha_AC,'DD-MM-YYYY') AS fecha_ac,
					upper(d.titulo_formacion),
					(CASE WHEN a.estatus='0' THEN  'INACTIVA'
						 WHEN a.estatus='1' THEN  'ACTIVA'
					END) AS estatus,
					(SELECT COUNT(*) FROM tbl_evaluacion_respuestas WHERE id_evaluacion=a.id_evaluacion),						 
			 		a.cantidad_preguntas,
			 		(SELECT COUNT(*) FROM tbl_evaluacion_preguntas WHERE id_evaluacion=a.id_evaluacion),
			 		a.id_contenido
			 FROM
			 		tbl_evaluacion a
			 INNER JOIN 
			 		tbl_contenidos_formacion b
			 ON
			 		b.id_contenido=a.id_contenido
			 INNER JOIN 
			 		tbl_tipo_evaluacion c
			 ON
			 		c.id_tipo_evaluacion=a.id_tipo_evaluacion						
			 INNER JOIN
			  		tbl_formacion d
			 ON
			  		d.id_formacion=a.id_formacion		
			 WHERE
			 		1=1 
			   ".$where."
			  ORDER BY a.fecha_ac DESC,a.id_evaluacion ASC
			  ".$where2.";";
        //return $sql;
        $rs=$this->execute($sql);
        $this->cuantos_son_cuerpo_tabla_eval($where);
        return $rs;
	}
	//Metodo para consultar cuerpo de la evaluacion.
	function consultar_cuerpo_tabla_mieval($id_aula,$f_ti_unidad,$f_fecha_eval,$f_tipo_eval,$offset,$limit,$f_ti_eva,$f_estatus,$f_fecha_ac)
	{
		$this->conectar();
		$where='';
		if($id_aula!="")
		{
			$where.=" AND a.id_formacion='".$id_aula."'";
		}
		if($f_ti_eva!="")
		{
			$where.=" AND upper(d.titulo_formacion) like '%".$f_ti_eva."%'";
		}	
		if($f_ti_unidad!="")
		{
			$where.=" AND upper(b.titulo_contenido) like '%".$f_ti_unidad."%'";
		}
		if($f_fecha_ac!="")
		{
			$where.=" AND a.fecha_ac='".$f_fecha_ac."'";	
		}
		if($f_fecha_eval!="")
		{
			$where.=" AND a.fecha_cierre='".$f_fecha_eval."'";
		}
		if(($f_tipo_eval!="")&&($f_tipo_eval!="-1"))
		{
			$where.=" AND c.id_tipo_evaluacion='".$f_tipo_eval."'";
		}
		if(($f_estatus!="")&&($f_estatus!="-1"))
		{
			switch ($f_estatus)
			{
				case '1':
					$where.=" AND (
							SELECT 
								tbl_calificaciones.nota_evaluacion 
							FROM 
								tbl_calificaciones 
							WHERE 
								id_evaluacion=a.id_evaluacion AND  tbl_calificaciones.id_us='".$_SESSION['id_us']."'
						     )>50";
				break;
				case '2':
					$where.=" AND (
							SELECT 
								tbl_calificaciones.nota_evaluacion 
							FROM 
								tbl_calificaciones 
							WHERE 
								id_evaluacion=a.id_evaluacion AND  tbl_calificaciones.id_us='".$_SESSION['id_us']."'
						     )< 50";
				break;
				case '3':
					$where.=" AND (
							SELECT 
								tbl_calificaciones.nota_evaluacion 
							FROM 
								tbl_calificaciones 
							WHERE 
								id_evaluacion=a.id_evaluacion AND  tbl_calificaciones.id_us='".$_SESSION['id_us']."'
						     ) IS NULL";
				break;
			}
		}	
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                     offset '".$offset."' ";
		}
       	$sql="SELECT  
					a.id_evaluacion,
					UPPER(b.titulo_contenido)AS titulo_contenido,
					UPPER(c.tipo_evaluacion) AS tipo_evaluacion,
					to_char(a.fecha_AC,'DD-MM-YYYY') AS fecha_ac,
					upper(d.titulo_formacion),
					(CASE WHEN a.estatus='0' THEN  'INACTIVA'
						 WHEN a.estatus='1' THEN  'ACTIVA'
					END) AS estatus,
					(SELECT COUNT(*) FROM tbl_evaluacion_RESPUESTAS WHERE id_evaluacion=a.id_evaluacion),
					(SELECT tbl_calificaciones.nota_evaluacion FROM tbl_calificaciones WHERE id_evaluacion=a.id_evaluacion AND  tbl_calificaciones.id_us='".$_SESSION['id_us']."'),						 
					(CASE WHEN (
							SELECT 
								tbl_calificaciones.nota_evaluacion 
							FROM 
								tbl_calificaciones 
							WHERE 
								id_evaluacion=a.id_evaluacion AND  tbl_calificaciones.id_us='".$_SESSION['id_us']."'
						     )>=50 THEN 'APROBADO'
					      WHEN (
							SELECT 
								tbl_calificaciones.nota_evaluacion 
							FROM 
								tbl_calificaciones 
							WHERE 
								id_evaluacion=a.id_evaluacion AND  tbl_calificaciones.id_us='".$_SESSION['id_us']."'
						     )<50 THEN 'REPROBADO'     
					ELSE 'PENDIENTE'	     
					END),
					b.id_contenido,
					a.fecha_cierre,
					to_char(a.fecha_cierre,'DD-MM-YYYY') AS fecha_cierre,
					to_char(a.fecha_ac,'DD-MM-YYYY') AS fecha_ac
					
				 FROM
						tbl_evaluacion a
				 INNER JOIN 
						tbl_contenidos_formacion b
				 ON
						b.id_contenido=a.id_contenido
				 INNER JOIN 
						tbl_tipo_evaluacion c
				 ON
						c.id_tipo_evaluacion=a.id_tipo_evaluacion						
				 INNER JOIN
						tbl_formacion d
				 ON
						d.id_formacion=a.id_formacion	
				 INNER JOIN 
				 		tbl_inscripcion e
				 ON  
				 		e.id_formacion=a.id_formacion					
				 WHERE
						1=1 
				 AND
				 		a.estatus='1'	
				 AND
				 		e.id_usuario='".$_SESSION['id_us']."'			 
			  ".$where."
			  ORDER BY a.id_evaluacion DESC, a.fecha_ac DESC 
			  ".$where2.";";
        //return $where;
		$rs=$this->execute($sql);
        $this->cuantos_son_cuerpo_tabla_mieval($where);
        return $rs;
	}
	//Metodo para consultar aulas en la pantalla inicial
	function consultar_aulas_in()
	{
		$fecha_actual=date("d-m-Y");
		$this->conectar();
		$sql="SELECT 
					a.id_formacion,
					a.imagen_formacion,
					upper(a.titulo_formacion) AS titulo_formacion,
					(substr(a.resumen_unidad,0,200)||'...') AS resumen,
					to_char(a.fecha,'DD-MM-YYYY') AS fecha_formacion,
					a.estatus_aula,
					".$_SESSION["id_perfil"]."
			FROM
					tbl_formacion a
			WHERE 
					a.estatus_aula='1'
			AND
					a.fecha <='".$fecha_actual."'
			order by a.id_formacion DESC
			limit 4
			offset 0	
					";
		$rs=$this->execute($sql);
		return $rs; 
	}
	/////////////////////////////////////////////////////////////////////////
}
?>