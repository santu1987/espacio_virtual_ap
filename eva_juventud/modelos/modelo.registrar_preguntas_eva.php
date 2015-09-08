<?php
session_start();
require_once("../controladores/conex.php");
class preguntas extends conex
{
	public $num_rows;
	//metodo para registrar preguntas
	function cargar_preguntas($aula,$evaluacion,$preguntas_evaluacion,$n_pregunta,$pregunta_respuesta1,$pregunta_respuesta2,$pregunta_respuesta3,$pregunta_respuesta4,$id_pregunta,$r_op)
	{
		$this->conectar();
		$sql="SELECT registrar_preguntas_evaluacion(
					    ".$aula.",
					    ".$evaluacion.",
   					    ".$n_pregunta.",
					    '".$preguntas_evaluacion."',
					    '".$pregunta_respuesta1."',
					    '".$pregunta_respuesta2."',
					    '".$pregunta_respuesta3."',
					    '".$pregunta_respuesta4."',
					   '".$id_pregunta."',
					   ".$r_op.");";
		$rs=$this->execute($sql);
		//return $sql;
		return $rs;	
	}
	//metodo para determinar la cantidad de preguntas de ese usuario...
	function cuantos_son($where)
	{
		//calculo cuantos son..
        $sql2="SELECT 
                  count(*)
                FROM 
 			 		tbl_evaluacion_preguntas
 			 INNER JOIN
 			 		tbl_evaluacion
 			 ON 
 			 		tbl_evaluacion.id_evaluacion=tbl_evaluacion_preguntas.id_evaluacion
 			 INNER JOIN
 			 		tbl_formacion 
 			 ON 
 			 		tbl_formacion.id_formacion=tbl_evaluacion.id_formacion								
 			 INNER JOIN 
 			 		tbl_tipo_formacion
 			 ON 
 			 		tbl_tipo_formacion.id_tipo_formacion=tbl_formacion.id_tipo_formacion
  			  	".$where.";";
        $rs2=$this->execute($sql2);
        $this->num_rows=$rs2[0][0];
        ////////////////////////////////////////////
	}

	//metodo que permite consultar el cuerpo del modal perteneciente a preguntas de ese usuario
	function consultar_cuerpo_preguntas($faula,$fev,$fpr,$offset,$limit)
	{
		$this->conectar();
		$where="WHERE id_us_creador=".$_SESSION["id_us"];
		if($faula!="")
		{
			$where.="  AND upper(tbl_formacion.titulo_formacion) like '%".$faula."%'";
		}
		if($fev!="")
		{
			$where.="  AND upper(tbl_evaluacion.descripcion) like '%".$fev."%'";
		}
		if($fpr!="")
		{
			$where.="  AND upper(tbl_evaluacion_preguntas.pregunta) like '%".$fpr."%'";
		}
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		$sql="SELECT 
					tbl_evaluacion_preguntas.id_evaluacion_preguntas, 
					UPPER(tbl_formacion.titulo_formacion),
					UPPER(tbl_evaluacion.descripcion), 
       				tbl_evaluacion_preguntas.pregunta
 			 FROM 
 			 		tbl_evaluacion_preguntas
 			 INNER JOIN
 			 		tbl_evaluacion
 			 ON 
 			 		tbl_evaluacion.id_evaluacion=tbl_evaluacion_preguntas.id_evaluacion
 			 INNER JOIN
 			 		tbl_formacion 
 			 ON 
 			 		tbl_formacion.id_formacion=tbl_evaluacion.id_formacion								
 			 INNER JOIN 
 			 		tbl_tipo_formacion
 			 ON 
 			 		tbl_tipo_formacion.id_tipo_formacion=tbl_formacion.id_tipo_formacion				
 			".$where."
  			order by tbl_formacion.id_formacion
  			".$where2.";";
  		//return $sql;	  
		$rs=$this->execute($sql);
		///////////////////////////////////////////
		//calculo cuantos son...
		$this->cuantos_son($where);
		return $rs;
	}
	//metodo para consultar preguntas
	function consultar_preguntas($id_pregunta)
	{
		$this->conectar();
		$where="WHERE 1=1 ";
		if($id_pregunta!="")
		{
			$where.=" AND 
  			  		    tbl_evaluacion_preguntas.id_evaluacion_preguntas=".$id_pregunta;
		}	
		$sql="SELECT 
					tbl_evaluacion_preguntas.id_evaluacion_preguntas,
					tbl_evaluacion.id_formacion,
					tbl_evaluacion.id_evaluacion,
					tbl_evaluacion_preguntas.n_pregunta,
					tbl_evaluacion_preguntas.pregunta,
					tbl_evaluacion_preguntas.opcion1,
					tbl_evaluacion_preguntas.opcion2,
					tbl_evaluacion_preguntas.opcion3,
					tbl_evaluacion_preguntas.opcion4,
					tbl_evaluacion_preguntas.r_correcta
  			 FROM 
 			 		tbl_evaluacion
 			 INNER JOIN 
 			 		tbl_evaluacion_preguntas
 			 ON 
 			 		tbl_evaluacion.id_evaluacion=tbl_evaluacion_preguntas.id_evaluacion
 			 ".$where.";";
  		//return $sql;	  
		$rs=$this->execute($sql);
		return $rs;	
	}
	//metodo para consultar preguntas por descripcion e id
	function consultar_preguntas_form($evaluacion)
	{
		$this->conectar();
		$sql="SELECT 
					   tbl_evaluacion_preguntas.pregunta,
					   tbl_evaluacion_preguntas.id_evaluacion_preguntas
			  FROM 
			  		   tbl_evaluacion_preguntas
			  WHERE
			  		   id_evaluacion=".$evaluacion.";";
		$rs=$this->execute($sql);
		return $rs;
	}
	//Metodo para determinar cuantas preguntas hay cargadas de la evaluacion
	function consultar_cuantas_preguntas($id_evaluacion)
	{
		$this->conectar();
		$sql="SELECT
					COUNT(*)
			  FROM
			  		tbl_evaluacion_preguntas
			  WHERE 
			  		id_evaluacion='".$id_evaluacion."'";
		$rs=$this->execute($sql);
		return $rs;	  		
	}
	//Metodo para determinar el estatus de el aula
	function consultar_estatus_aula($id_evaluacion)
	{
		$this->conectar();
		$sql="SELECT
					a.estatus,
	  				(SELECT COUNT(*) FROM tbl_evaluacion_respuestas WHERE id_evaluacion=a.id_evaluacion),
	  				(SELECT COUNT(*) FROM tbl_evaluacion_preguntas WHERE id_evaluacion=a.id_evaluacion)
			  FROM
			  		tbl_evaluacion a
			  WHERE 
			  		a.id_evaluacion='".$id_evaluacion."'";
		$rs=$this->execute($sql);
		return $rs;	  		
	}
}
?>