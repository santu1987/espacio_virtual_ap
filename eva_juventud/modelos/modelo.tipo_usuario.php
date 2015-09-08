<?php
session_start();
//incluyo conexion...
require_once("../controladores/conex.php");
////////////////////
class tipo_us extends conex
{
	public $id_tipo_us;
	public $desc_modalidad;
	public $num_rows;
	function registrar_tipo_usuario($id_tipo_us,$desc_modalidad)
	{
		$this->id_tipo_us=$id_tipo_us;
		$this->desc_modalidad=$desc_modalidad;
		$this->conectar();
		$sql="SELECT registrar_tipo_us(
			    '".$this->id_tipo_us."',
    			'".$this->desc_modalidad."'
			);";
		//return $sql;
		$this->rs=$this->execute($sql);
		return $this->rs;		
	}
	//metodo patra determinar cuantos tp_us existen..
	function cuantos_son($where)
	{
		//calculo cuantos son..
        $sql2="SELECT 
                  count(*)
                FROM 
               		tbl_perfil
  			  	".$where.";";

        $rs2=$this->execute($sql2);
        $this->num_rows=$rs2[0][0];
        ////////////////////////////////////////////
	}
	//metodo para consultar el curpo de la consulta modal
	function consultar_cuerpo_tipo_usuario($fus,$offset,$limit)
	{
		$this->conectar();
		if($fus!="")
		{
			$where="WHERE upper(desc_perfil) like '%".$fus."%'";
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
					id_perfil, 
					upper(desc_perfil) AS desc_perfil
  			  FROM 
  			  		tbl_perfil
  			  ".$where."
  			  order by id_perfil
  			  ".$where2."
  			  ; ";
  		//return $sql;	  
		$rs=$this->execute($sql);
		///////////////////////////////////////////
		//calculo cuantos son...
		$this->cuantos_son($where);
		return $rs;	
	}
	//metodo para consultar el tipo de usuario
	function consultar_tp_usuario($id_tp_us)
	{
		$this->conectar();
		if($id_tp_us!="")
		{
			$where="WHERE 
  			  		id_perfil=".$id_tp_us.";";
		}else
		{
			$where="";
		}	
		$sql="SELECT 
					id_perfil,
					upper(desc_perfil) AS desc_perfil
  			  FROM 
  			        tbl_perfil
  			  ".$where.";";
		$rs=$this->execute($sql);
		return $rs;
	}
}
?>