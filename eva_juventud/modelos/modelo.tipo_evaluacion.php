<?php
session_start();
//incluyo conexion...
require_once("../controladores/conex.php");
////////////////////
class tipo_evaluacion extends conex
{
	public $id_tipo_evaluacion;
	public $desc_evaluacion;
	public $num_rows;
	//metodo para registrar tipo de evaluacion
	function registrar_tipo_evaluacion($id_tipo_evaluacion,$desc_evaluacion)
	{
		$this->id_tipo_evaluacion=$id_tipo_evaluacion;
		$this->desc_evaluacion=$desc_evaluacion;
		$this->conectar();
		$sql="SELECT registrar_tipo_evaluacion(
			    '".$this->id_tipo_evaluacion."',
    			'".$this->desc_evaluacion."'
			);";
		//return $sql;
		$this->rs=$this->execute($sql);
		return $this->rs;		
	}
	//metodo para consultar el tipo de evaluacion
	function consultar_tipo_evaluacion()
	{
		$this->conectar();
		$sql="SELECT 
					id_tipo_evaluacion,
					upper(tipo_evaluacion) AS tipo_evaluacion
  			  FROM 
  			  		tbl_tipo_evaluacion ";
		$rs=$this->execute($sql);
		return $rs; 
	}
	//metodo que cuenta cuantos tipos de evaluaciones hay
	function cuantos_son($where)
	{
		//calculo cuantos son..
        $sql2="SELECT 
                  count(*)
                FROM 
               		tbl_tipo_evaluacion
  			  	".$where.";";

        $rs2=$this->execute($sql2);
        $this->num_rows=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//metodo que genera la tabla de consulta
	function consultar_cuerpo_evaluacion($feva,$offset,$limit)
	{
		$this->conectar();
		if($feva!="")
		{
			$where="WHERE upper(tipo_evaluacion) like '%".$feva."%'";
			
		}else
		{
			$where='';
		}
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		$sql="SELECT 
					id_tipo_evaluacion, 
					UPPER(tipo_evaluacion) AS tipo_evaluacion
  			  FROM 
  			  		tbl_tipo_evaluacion
  			  ".$where."
  			  order by id_tipo_evaluacion
  			  ".$where2."
  			  ; ";
  		//return $sql;	  
		$rs=$this->execute($sql);
		///////////////////////////////////////////
		//calculo cuantos son...
		$this->cuantos_son($where);
		return $rs;	
	}
	//metodo para consultar tipo de evaluacion segun un id
	function consultar_tipo_evaluacion_id($id)
	{
		$this->conectar();
		$sql="SELECT id_tipo_evaluacion,upper(tipo_evaluacion) AS tipo_evaluacion from tbl_tipo_evaluacion WHERE id_tipo_evaluacion=".$id.";";
		$this->rs=$this->execute($sql);
		return $this->rs;
	}
}
?>