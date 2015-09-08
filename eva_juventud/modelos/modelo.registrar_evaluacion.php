<?php
require_once("../controladores/conex.php");
class evaluacion extends Conex
{
	public $num_rows;
	public $num_rows2;
	public $num_rows3;
	function registrar_evaluacion($id_aula,$unidad,$desc_eva,$preguntas,$id_evaluacion,$tipo_ev,$fecha_ac,$hora_ac,$fecha_cierre)
	{
		$this->conectar();
		$sql="SELECT registrar_evaluacion(
						".$id_aula.",
						".$unidad.",
						'".$desc_eva."',
						".$preguntas.",
						".$id_evaluacion.",
						".$tipo_ev.",
						'".$fecha_ac."',
						'".$fecha_cierre."');";
		$rs=$this->execute($sql);
		return $rs;
	}
	function consultar_evaluacion($id_aula,$unidad,$tipo_eva)
	{
		$this->conectar();
		$sql="SELECT 
					a.id_evaluacion, 
					a.id_contenido, 
					a.id_tipo_evaluacion, 
					upper(a.descripcion) AS descripcion, 
       				a.cantidad_preguntas, 
       				a.id_formacion,
					to_char(a.fecha_ac,'DD-MM-YYYY') AS fecha_ac,
       				a.hora_ac,
					to_char(a.fecha_cierre,'DD-MM-YYYY') AS fecha_cierre,
       				a.hora_cierre
       		FROM 
  					tbl_evaluacion a
  			WHERE 
  					a.id_formacion=".$id_aula."
  			AND 
  					a.id_contenido=".$unidad."
  			AND
  					a.id_tipo_evaluacion=".$tipo_eva.";";
  		$rs=$this->execute($sql);
  		return $rs;			
	}
	function consultar_evaluacion_aula($id_aula)
	{
		$this->conectar();
		$sql="SELECT 
					id_evaluacion, 
					id_contenido, 
					id_tipo_evaluacion, 
					upper(descripcion) AS descripcion, 
       				cantidad_preguntas, 
       				id_formacion
  			FROM 
  					tbl_evaluacion
  			WHERE 
  					id_formacion=".$id_aula;
  	  	$rs=$this->execute($sql);
  		return $rs;			
	}
	function consultar_preguntas($id_evaluacion)
	{
		$this->conectar();
		$sql="SELECT 
					cantidad_preguntas 
			  FROM 
			  		tbl_evaluacion 
			  WHERE 
			  		id_evaluacion='".$id_evaluacion."';";
		$rs=$this->execute($sql);
		return $rs;
	}
	//metodo para determinar la cantidad de evaluaciones que cargo un usuario...
	function cuantos_son($where)
	{
		//calculo cuantos son..
        $sql2="SELECT 
                  count(*)
 			FROM 
 			 		tbl_evaluacion
 			INNER JOIN 
 			 		tbl_formacion
 			ON 
 			 		tbl_evaluacion.id_formacion=tbl_formacion.id_formacion
 			INNER JOIN 
 			 		tbl_contenidos_formacion
 			ON
 			 		tbl_contenidos_formacion.id_contenido=tbl_evaluacion.id_contenido
 			INNER JOIN 
 			 		tbl_tipo_evaluacion
 			ON 
 			 		tbl_evaluacion.id_tipo_evaluacion=tbl_tipo_evaluacion.id_tipo_evaluacion								
	
  			  	".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//metodo que permite consultar el cuerpo del modal perteneciente a la evaluacion de ese usuario
	function consultar_cuerpo_evaluaciones($faula,$funidades,$ftipo,$offset,$limit)
	{
		$this->conectar();
		if($_SESSION["id_perfil"]==4)
		{
			$where=" WHERE 1=1 ";
		}
		else
		{
			$where="WHERE tbl_formacion.id_us_creador=".$_SESSION["id_us"];
		}
		if($faula!="")
		{
			$where.="  AND upper(tbl_formacion.titulo_formacion) like '%".$faula."%'";
		}
		if($funidades!="")
		{
			$where.="  AND tbl_contenidos_formacion.unidad='".$funidades."'";
		}
		if(($ftipo!="")&&($f_tipo!=0))
		{
			$where.="  AND tbl_evaluacion.id_tipo_evaluacion='".$ftipo."'";
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
					UPPER(tbl_tipo_evaluacion.tipo_evaluacion),
					tbl_evaluacion.id_evaluacion,
					(CASE WHEN tbl_evaluacion.estatus='0' THEN  'ABIERTA'
						 WHEN tbl_evaluacion.estatus='1' THEN  'CERRADA'
					END) AS estatus	 
			FROM 
 			 		tbl_evaluacion
 			INNER JOIN 
 			 		tbl_formacion
 			ON 
 			 		tbl_evaluacion.id_formacion=tbl_formacion.id_formacion
 			INNER JOIN 
 			 		tbl_contenidos_formacion
 			ON
 			 		tbl_contenidos_formacion.id_contenido=tbl_evaluacion.id_contenido
 			INNER JOIN 
 			 		tbl_tipo_evaluacion
 			ON 
 			 		tbl_evaluacion.id_tipo_evaluacion=tbl_tipo_evaluacion.id_tipo_evaluacion								
 			".$where."
 			GROUP BY
					tbl_contenidos_formacion.id_contenido, 
					tbl_formacion.titulo_formacion,
					tbl_contenidos_formacion.unidad,
					tbl_tipo_evaluacion.tipo_evaluacion,
					tbl_formacion.id_formacion,
					tbl_evaluacion.id_evaluacion
  			order by tbl_evaluacion.id_evaluacion DESC,tbl_formacion.id_formacion 
  			".$where2.";";
  		$rs=$this->execute($sql);
		///////////////////////////////////////////
		//calculo cuantos son...
		$this->cuantos_son($where);
		return $rs;
	}
	//metodo para cargar la consulta de contenido segun id
	function consultar_evalua($id_evaluacion,$unidad)
	{
		$this->conectar();
		$where="WHERE 1=1 ";
		if($id_evaluacion!="")
		{
			$where.=" AND 
  			  		    tbl_evaluacion.id_evaluacion=".$id_evaluacion;
		}	
		if($unidad!='')
		{
			$where.=" AND
						tbl_contenidos_formacion.id_contenido=".$unidad;
		}	
		$sql="SELECT 
					tbl_evaluacion.id_evaluacion, 
					tbl_formacion.id_formacion,
					tbl_contenidos_formacion.unidad,
					tbl_tipo_evaluacion.id_tipo_evaluacion,
					tbl_evaluacion.descripcion,
					tbl_evaluacion.cantidad_preguntas,
					to_char(tbl_evaluacion.fecha_ac,'DD-MM-YYYY') AS fecha_ac,
					tbl_evaluacion.hora_ac,
					to_char(tbl_evaluacion.fecha_cierre,'DD-MM-YYYY') AS fecha_ac,
					tbl_evaluacion.hora_cierre,
					tbl_evaluacion.id_contenido
  			 FROM 
 			 		tbl_evaluacion
 			 INNER JOIN 
 			 		tbl_formacion
 			 ON 
 			 		tbl_evaluacion.id_formacion=tbl_formacion.id_formacion
 			 INNER JOIN 
 			 		tbl_contenidos_formacion
 			 ON
 			 		tbl_contenidos_formacion.id_formacion=tbl_formacion.id_formacion
 			 INNER JOIN 
 			 		tbl_tipo_evaluacion
 			 ON 
 			 		tbl_evaluacion.id_tipo_evaluacion=tbl_tipo_evaluacion.id_tipo_evaluacion
  			 ".$where.";";
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
					tbl_contenidos_formacion.desc_contenido
			  FROM
			  		tbl_contenidos_formacion
			  WHERE 
			  		 id_formacion=".$aula."
			  AND
			  		 unidad=".$unidad.";";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar evaluacion
	function consulta_evaluacion_unidad($id_unidad,$id_evaluacion)
	{
		$this->conectar();
		$sql="SELECT
					upper(tbl_formacion.titulo_formacion) AS titulo_formacion,
					(upper(tbl_contenidos_formacion.titulo_contenido)||' '||tbl_contenidos_formacion.unidad) AS titulo_unidad,
					upper(tbl_evaluacion.descripcion)AS evaluacion,
					tbl_evaluacion_preguntas.n_pregunta AS n_pregunta,
					tbl_evaluacion_preguntas.pregunta AS pregunta,
					tbl_evaluacion_preguntas.opcion1,
					tbl_evaluacion_preguntas.opcion2,
					tbl_evaluacion_preguntas.opcion3,
					tbl_evaluacion_preguntas.opcion4,
					tbl_evaluacion_preguntas.r_correcta,
					tbl_evaluacion_preguntas.id_evaluacion,
					tbl_contenidos_formacion.id_formacion,
					tbl_contenidos_formacion.id_contenido,
					tbl_evaluacion_preguntas.id_evaluacion_preguntas,
					tbl_evaluacion.fecha_cierre
			  FROM
			  		tbl_contenidos_formacion
			  INNER JOIN 
			  		tbl_formacion
			  ON
			  		tbl_formacion.id_formacion=tbl_contenidos_formacion.id_formacion				
			  INNER JOIN
			  		tbl_evaluacion
			  ON
			  		tbl_evaluacion.id_contenido=tbl_contenidos_formacion.id_contenido		
			  INNER JOIN
			  		tbl_evaluacion_preguntas
			  ON 
			  		tbl_evaluacion_preguntas.id_evaluacion=tbl_evaluacion.id_evaluacion		
			  WHERE
			  		tbl_evaluacion.id_contenido=".$id_unidad."
			  AND
			  		tbl_evaluacion.id_evaluacion=".$id_evaluacion."		
			  ORDER BY tbl_evaluacion_preguntas.id_evaluacion_preguntas
			  ";
		//return $sql;	  	  
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para registrar resultados de prueba del estudiante
	function registrar_evaluacion_us($formacion,$unidades,$prueba,$cuantas_pr,$vector_respuestas,$vector_preguntas)
	{
		$this->conectar();
		$sql="SELECT registrar_respuestas_pruebas
					(
						".$formacion.",
						".$unidades.",
						".$prueba.",
						".$cuantas_pr.",
						'".$vector_respuestas."',
						".$_SESSION["id_us"].",
						'".$vector_preguntas."'
					)";
		$recordset=$this->execute($sql);
		return $recordset;
	}
	//Metodo para consultar opciones cargadas para ese usuario....
	function consultar_evaluacion_cargada($id_unidad,$id_evaluacion)
	{
		$this->conectar();
		$sql="SELECT 
					id_formacion,
					id_contenido,
					id_evaluacion, 
       				id_pregunta,
       				respuestas
  			 FROM 
  			 		tbl_evaluacion_respuestas
  			 WHERE
  			 		id_us=".$_SESSION["id_us"]."
  			 AND
  			 		id_contenido=".$id_unidad."
  			 AND
  			 		id_evaluacion=".$id_evaluacion.";";
  		//return $sql;
  		$rs=$this->execute($sql);
  		return $rs; 	 		
	}
	//Metodo para consultar respuestas correctas
	function consulta_evaluacion_respuestas_correctas($id_unidad,$id_evaluacion)
	{
		$this->conectar();
		$sql="SELECT 
					tbl_evaluacion_respuestas.id_pregunta,
					tbl_evaluacion_respuestas.respuestas,
					tbl_evaluacion_preguntas.r_correcta
  			  FROM 
  			  		tbl_evaluacion_respuestas
  			  INNER JOIN 
  			  		tbl_evaluacion_preguntas
  			  ON 
  			  		tbl_evaluacion_preguntas.id_evaluacion_preguntas=tbl_evaluacion_respuestas.id_pregunta				
  			  WHERE
  			  		id_us=".$_SESSION["id_us"]."
  			  AND
  			 		id_contenido=".$id_unidad."
  			  AND
  			  		tbl_evaluacion_preguntas.id_evaluacion=".$id_evaluacion.";";
  		$rs=$this->execute($sql);
  		return $rs;	  		
	}
	//Metodo que consulta solo las respuestas correctas
	function consulta_evaluacion_respuestas_correctas2($id_unidad,$id_evaluacion)
	{
		$this->conectar();
		$sql="SELECT
					tbl_evaluacion_preguntas.id_evaluacion_preguntas,
					tbl_evaluacion_preguntas.r_correcta
			  FROM 
			  		tbl_evaluacion_preguntas
			  INNER JOIN 
			  		tbl_evaluacion
			  ON 
			  		tbl_evaluacion_preguntas.id_evaluacion=tbl_evaluacion.id_evaluacion				
			  WHERE
			  		tbl_evaluacion.id_contenido=".$id_unidad."
			  AND
			  		tbl_evaluacion_preguntas.id_evaluacion=".$id_evaluacion.";";

		$rs=$this->execute($sql);
  		return $rs;	  	  		
	}
	//Metodo para cerrar aula
	function cerrar_evaluacion($evaluacion)
	{
		
		$this->conectar();
		$sql="SELECT cerrar_evaluacion(".$evaluacion.")";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para cargar notas
	function cargar_notas($id_evaluacion)
	{
		$this->conectar();
		$sql="SELECT cargar_notas('".$id_evaluacion."','".$_SESSION['id_us']."');";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar notas por aula
	function consultar_notas($id_aula)
	{
		$where.=" AND c.id_usuario='".$_SESSION['id_us']."'";
		if($id_aula!="")
		{
			$where.=" AND a.id_formacion='".$id_aula."'";
		}			$this->conectar();
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
			AND		
					f.id_contenido=b.id_contenido				
			AND 
					f.id_tipo_evaluacion='3'				
			AND
					estatus_aula=1
			".$where."
			order by b.unidad
			;";
		//return $sql;	
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar cuantos son notas_p
	function cuantos_son_notasp($where)
	{
		$sql2=" SELECT 
					COUNT(*)
				FROM 
  			  		notas_unidades a
				".$where.";";
		$rs=$this->execute($sql2);
		$this->num_rows2=$rs[0][0];		
	}
	//Metodo para consultar notas por aula para procesar nota final
	function consultar_notas_aula($id_aula)
	{
		$where="WHERE a.estatus_aula='1' ";
		if($id_aula!="")
		{
			$where.=" AND a.id_formacion='".$id_aula."'";
		}			
		$this->conectar();
		$sql="SELECT 
					a.id_usuarios, 
					a.nacionalidad, 
					a.ced_persona, 
					a.nombre_personas, 
					a.id_formacion, 
       				a.cuantas_unidades, 
       				a.id_us_creador,
       				a.eva, 
       				a.estatus_aula,
       				a.nota_unidad1,
       				a.nota_unidad2, 
       				a.nota_unidad3,
       				a.nota_unidad4,
       				a.nota_unidad5,
       				(SELECT b.calificacion FROM tbl_calificaciones_definitivas b WHERE a.id_usuarios=b.id_us AND a.id_formacion=b.id_formacion) AS calificacion_definitiva
  			  FROM 
  			  		notas_unidades a
			  ".$where."
			  ORDER BY 			
						a.id_usuarios
			  ".$where2.";";	
		//return $sql;	
		$rs=$this->execute($sql);
		//calculo cuantos son...
		$this->cuantos_son_notasp($where);
		return $rs;
	}
	//Metodo para determinar la cantidad de aprobados/reprobados segun count
	function cuantos_estudiantes_reporte($where)
	{
		$sql="SELECT
					COUNT(*)
			  FROM
			  		tbl_formacion a
			  ".$where."				
		";
		$rs=$this->execute($sql);
		$this->num_rows3=$rs[0][0];
	}
	//Metodo para determinar la cantidad de aprobados y reprobados
	function consultar_cuerpo_estudiantes_aprobados($offset,$limit,$nombre)
	{
		//--Filtros
		$where=' WHERE 1=1 ';
		if($nombre!="")
		{
			$where.=" AND upper(a.titulo_formacion) like '%".$nombre."%'";
		}
		//--Si el id_perfil es facilitador= 1
		if($_SESSION["id_perfil"]=='1')
		{
			$where.=" AND a.id_us_creador='".$_SESSION["id_us"]."'";			
		}
		//--Para $where 2: limit y offset
		if(($offset!="")&&($limit!=""))
		{
			$where2="   limit '".$limit."' 
                		offset '".$offset."' ";
		}	
		$this->conectar();
		//--
		$sql="SELECT 
				upper(a.titulo_formacion) AS eva,
				(SELECT COUNT(*) FROM  tbl_calificaciones_definitivas b WHERE b.id_formacion=a.id_formacion and calificacion>=50) AS aprobados,
				(SELECT COUNT(*) FROM  tbl_calificaciones_definitivas b WHERE b.id_formacion=a.id_formacion and (calificacion<50 or calificacion is null)) AS reprobados
			FROM
				tbl_formacion a
			".$where."
			ORDER BY
				a.id_formacion
			".$where2.";";
		//--
		$rs=$this->execute($sql);
		$this->cuantos_estudiantes_reporte($where);
		return $rs;
		//--
	}
	//Metodo para procesar evaluaciones y obtener los resultados finales
	function procesar_evaluacion($aula)
	{
		$this->conectar();
		$sql="SELECT cargar_notas_definitivas('".$aula."');";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para consultar cuantos estudiantess de detalle
	function cuantos_est_apr($where)
	{
		$sql="	SELECT 
					 COUNT(*)
				 FROM		 	
					 notas_unidades a
				 INNER JOIN
				  		 tbl_formacion b
				 ON 
				  		 a.id_formacion=b.id_formacion	
			  		 ".$where.";";
		$rs=$this->execute($sql);
		$this->num_rows3=$rs[0][0];	  		 
	}
	//Metodo para consultar detalle
	function consultar_cuerpo_est_apr($offset,$limit,$eva,$nacionalidad,$cedula,$nombre)
	{
		$this->conectar();
		//--Filtros
		$where="WHERE 1=1 ";
		if($nombre!="")
		{
			$where.=" AND upper(a.nombre_personas) like '%".$nombre."%'";
		}
		if($eva!="")
		{
			$where.=" AND upper(a.eva) like '%".$eva."%'";
		}	
		if(($nacionalidad!="")&&($nacionalidad!='-1'))
		{
			$where.=" AND a.nacionalidad='".$nacionalidad."'";
		}
		if($cedula!="")
		{
			$where.=" AND a.ced_persona='".$cedula."'";
		}
		//--Si el id_perfil es facilitador= 1
		if($_SESSION["id_perfil"]=='1')
		{
			$where.=" AND b.id_us_creador='".$_SESSION["id_us"]."'";			
		}	
		//--	
		//--Limites	
		if(($offset!="")&&($limit!=""))
		{
			$where2="   limit '".$limit."' 
                		offset '".$offset."' ";
		}
		//--
		$sql="SELECT
					a.nacionalidad,
					a.ced_persona,
					a.nombre_personas,
					a.eva,
					(CASE WHEN (SELECT calificacion FROM tbl_calificaciones_definitivas b WHERE a.id_usuarios=b.id_us AND a.id_formacion=b.id_formacion)>=50 THEN 'APROBADO'
						ELSE 'REPROBADO'
					 END) AS estatus
			  FROM		 	
					 notas_unidades a
			  INNER JOIN
			  		 tbl_formacion b
			  ON 
			  		 a.id_formacion=b.id_formacion			  		 
			  ".$where."
			  order by a.ced_persona
			  ".$where2.";";
		$rs=$this->execute($sql);
		$this->cuantos_est_apr($where);
		return $rs;
	}
	////////////////////////////////////////////////////////////////////7
}
?>