<?php
///incluir conexion
require_once("./controladores/conex.php");
class Activacion_Us extends conex
{
		//metodo para activar la cuenta de usuario....
		function modificar_estatus($nac,$cedula)
		{
			$this->conectar();
			$sql="SELECT modificar_estatus('".$nac."',".$cedula.")";
			$rs=$this->execute($sql);
			return $rs;
		}
		////////////////////////////////////////////////////////////////////

}
?>