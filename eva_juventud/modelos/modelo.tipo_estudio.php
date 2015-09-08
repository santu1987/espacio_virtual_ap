<?php
session_start();
//incluyo conexion...
require_once("../controladores/conex.php");
////////////////////
class tipo_estudio extends conex
{
	public $id_modelo;
	public $modalidad;
	public $num_rows;
	//metodo para registrar modalidades de estudio
	function registrar_tipo_estudio($id_modelo,$modalidad)
	{
		$this->id_modelo=$id_modelo;
		$this->modalidad=$modalidad;
		$this->conectar();
		$sql="SELECT registrar_tipo_formacion(
			    ".$this->id_modelo.",
    			'".$this->modalidad."'
			);";
		$this->rs=$this->execute($sql);
		return $this->rs;		
	}
	//metodo para consultar modalidades de estudio
	function buscar_estudio()
	{
		$this->conectar();
		$sql="SELECT id_tipo_formacion,desc_tipo_formacion from tbl_tipo_formacion order by id_tipo_formacion";
		$this->rs=$this->execute($sql);
		return $this->rs;
	}
	//metodo para consultar modalidades de estudio por id
	function consultar_modalidad($id)
	{
		$this->conectar();
		$sql="SELECT id_tipo_formacion,upper(desc_tipo_formacion) AS desc_tipo_formacion from tbl_tipo_formacion WHERE id_tipo_formacion=".$id.";";
		$this->rs=$this->execute($sql);
		return $this->rs;
	}
	//metodo que cuenta cuantos tipos de modalidades hay
	function cuantos_son($where)
	{
		//calculo cuantos son..
        $sql2="SELECT 
                  count(*)
                FROM 
               		tbl_tipo_formacion
  			  	".$where.";";

        $rs2=$this->execute($sql2);
        $this->num_rows=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//metodo que genera la tabla de consulta
	function consultar_cuerpo_modalidad($fmod,$offset,$limit)
	{
		$this->conectar();
		if($fmod!="")
		{
			$where="WHERE upper(desc_tipo_formacion) like '%".$fmod."%'";
			
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
					id_tipo_formacion, 
					UPPER(desc_tipo_formacion) AS desc_tipo_formacion
  			  FROM 
  			  		tbl_tipo_formacion
  			  ".$where."
  			  order by id_tipo_formacion
  			  ".$where2."
  			  ; ";
  		//return $sql;	  
		$rs=$this->execute($sql);
		///////////////////////////////////////////
		//calculo cuantos son...
		$this->cuantos_son($where);
		return $rs;	
	}
}
?>