<?php
class auditoria extends conex
{
	public $seccion;
	public $accion;
	public $num_rows;
	function __construct($seccion,$accion)
	{
		$this->seccion=$seccion;
		$this->accion=$accion;
	}
	//////////////////////////////
	function registrar_auditoria()
	{
		$hora=date("H:i:s");
		$dia=date("Y-m-d");
		$sql="SELECT auditoria(
		    '".$this->seccion."',
		    '".$this->accion."',
		    '".$_SESSION["usuario"]."',
		    '".$_SESSION["id_us"]."',
		   '".$_SERVER['REMOTE_ADDR']."',
		   '".$dia."',
		   '".$hora."'
		);";
		///////////////////////////////////
		$this->conectar();
		$record=$this->execute($sql);
		if($record=="error")
		{
			return false;
		}
		else
		{
			return true;
		}
		/////////////////////////////////	
	}
	//Metodo para determinar cuantos reg hay ena auditoria...
	function cuantos_auditoria($where)
	{
		$sql=" SELECT COUNT(*) FROM tbl_auditoria a ".$where.";";
		$rs=$this->execute($sql);
		$this->num_rows=$rs[0][0];
	}
	//Metodo que consulta el contenido de la tabla de auditorioa...
	function consultar_auditoria($offset,$limit,$actual,$seccion,$accion,$us,$ip,$fecha)
	{
		$where="WHERE 1=1";
		//--Filtros
		if($seccion!="")
		{
			$where.=" AND  upper(a.seccion) like '%".$seccion."%'";
		}
		if($accion!="")
		{
			$where.=" AND  upper(a.accion) like '%".$accion."%'";
		}
		if($us!="")
		{
			$where.=" AND  upper(a.campoclave) like '%".$us."%'";
		}
		if($ip!="")
		{
			$where.=" AND  a.ip='".$ip."'";
		}
		if($fecha!="")
		{
			$where.=" AND  a.fecha='".$fecha."'";
		}
		//Filtros 2
		if(($offset!="")&&($limit!=""))	
		{
			$where2="limit '".$limit."' 
                    offset '".$offset."' ";
		}
		/////////////////////////////////////////////////////////
		$this->conectar();
		$sql="SELECT 
					upper(a.seccion),
					upper(a.accion),
					upper(a.campoclave),
					a.ip,
					to_char(a.fecha,'DD-MM-YYYY')
			  FROM 
			  		tbl_auditoria a
			  ".$where."
			  order by a.fecha desc
			  ".$where2.";";
		//return $sql;	  
		$rs=$this->execute($sql);
		$this->cuantos_auditoria($where);
		return $rs;
	}
	//////////////////////////
}
?>