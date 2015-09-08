<?php
require_once("../controladores/conex.php");
class inscripcion_eva extends Conex
{
	function registrar_inscripcion_eva($id_eva)
	{
		$this->conectar();
		$sql="SELECT registrar_inscripcion(".$id_eva.",".$_SESSION['id_us'].")";
		$rs=$this->execute($sql);
		return $rs;
	}
}
?>