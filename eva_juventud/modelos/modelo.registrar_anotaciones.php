<?php
require_once("../controladores/conex.php");
class Anotaciones extends Conex
{
	//Metodo para cargar anotaciones a la unidad de un aula
	function cargar_anotaciones_unidades($id_unidad,$id_aula,$anotacion_texto)
	{
		$this->conectar();
		$sql="SELECT cargar_anotaciones(".$id_unidad.",".$id_aula.",'".$anotacion_texto."','".$_SESSION["id_us"]."');";
		$rs=$this->execute($sql);
		return $rs;	
	}
	//Metodo para consultar anotaciones a la unidad
	function consultar_anotaciones_unidades($id_unidad,$id_aula)
	{
		$this->conectar();
		$sql="SELECT texto_anotacion FROM tbl_anotaciones WHERE id_us='".$_SESSION["id_us"]."'  AND id_unidad=".$id_unidad." AND id_aula=".$id_aula;
		$rs=$this->execute($sql);
		return $rs;
	}
}
?>