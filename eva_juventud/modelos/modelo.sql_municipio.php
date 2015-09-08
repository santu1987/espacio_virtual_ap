<?php
include("../controladores/conex.php");
class BuscarMunicipio extends conex{ 
    
    public $id;
    public function __construct($id){

        $this->id=$id;
    }
    
    function buscar_municipio(){
    	if ($this->id!=''){
    		$where= "WHERE codigoestado='".$this->id."'";
    	}else{
    		$where= "";
    	}
        $sql="SELECT * FROM tbl_municipio $where";
        $this->conectar();
        $row_municipio=$this->execute($sql);
        return $row_municipio;
   }  
}
