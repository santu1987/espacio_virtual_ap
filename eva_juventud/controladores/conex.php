<?php
//conexion de bd aplicando patron de diseño singleton...
class Conex
{
		
			private $conexion;
			private $servidor="localhost";
			private $clave="123456";
			private $usuario="postgres";
			private $bd="espacio_virtual_ap";
			//private $bd="espacio_v_blanco";
			private $query;
			public $arreglo = array();
			public  $sql="";
		/*Metodo constructor*/
		public function __construct()
		{
			/*$this->servidor="localhost";
			$this->clave="machurucuto666";
			$this->usuario="postgres";
			$this->bd="espacio_virtual_ap";*/
			$this->query="";
		}
		//
		//metodo que valida la sesion
		/*public function validar_sesion()
		{
			if(isset($_SESSION["espacio_virtual"]))
			{

				if($_SESSION["espacio_virtual"] != "SI")
				{
				    session_unset();
				    session_destroy();	
				    echo "<script>
				                location.href='http://".$_SERVER['HTTP_HOST']."/eva_juventud/';
				         </script>";
				    exit();
				}
			}else
			{
				 echo "<script>
				               location.href = 'http://".$_SERVER['HTTP_HOST']."/eva_juventud/';
				       </script>";
			}
			if (isset($_GET["cerrar"])){ $cerrar = base64_decode($_GET["cerrar"]);} else { $cerrar = ""; }
		}*/
		//metodo que permite conectarse a la bd
		public function conectar()
		{
			//valido la sesion antes de conectar
			$this->conexion=pg_connect('host='.$this->servidor. ' port=5432'. ' dbname='.$this->bd. ' user='.$this->usuario.' password='.$this->clave);
			if($this->conexion)
			{
				return 'SI';
			}
			else
			{
				return 'NO';
			}	
		}
		//
		//metodo que permite ejecutar un query
		//para select
		function enviarQuery($sql)
		{
			$this->query = pg_query($sql);
			return $this->query;
		}

		function vectorizar()
		{
			return pg_fetch_row($this->query);
		}

		//para insert, update, delete
		function execute($sql)
		{
			$result = $this->enviarQuery($sql);
			if($result){
				$arr = array();
				while($row = $this->vectorizar()){
					$arr[] = $row;
				}
			}else{
				$arr = "error";
			}
			return $arr;
		}
}
$bd= new Conex();
?>
