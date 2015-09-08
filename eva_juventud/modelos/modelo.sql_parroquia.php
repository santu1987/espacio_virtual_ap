<?php
include("../controladores/conex.php");
class BuscarParroquia extends conex{ 
    
    public $id;
    
    public function __construct($id){

        $this->id=$id;
    }
    
    function buscar_parroquia(){
    	if ($this->id!=''){
    		$where= "WHERE codigomunicipio='".$this->id."'";
    	}else{
    		$where= "";
    	}
        $sql="SELECT * FROM tbl_parroquia $where";
        $this->conectar();
        $row_parroquia=$this->execute($sql);
        return $row_parroquia;
   }  
}
