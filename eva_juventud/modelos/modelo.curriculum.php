<?php
require_once("../controladores/conex.php");
class Curriculum extends Conex
{
	function cargar_curriculum($id_curriculum,$r_profesional,$r_academico,$r_laboral,$r_cursos)
	{
		$this->conectar();
		$sql="SELECT registrar_curriculum(
											".$id_curriculum.",
											".$_SESSION['id_us'].",
											'".$r_profesional."',
											'".$r_academico."',
											'".$r_laboral."',
											'".$r_cursos."')";
		//return $sql;
		$rs=$this->execute($sql);
		return $rs;
	}
	function consultar_curriculum()
	{
		$this->conectar();
		$sql="SELECT 
					id_curriculum, 
					resumen_profesional,
					resumen_academico, 
       				resumen_laboral,
       				resumen_cursos
  				FROM 
  					tbl_curriculum
  				WHERE 
  					id_usuario=".$_SESSION['id_us'].";";
  		//return $sql;			
		$rs=$this->execute($sql);
		return $rs;
	}
}
?>