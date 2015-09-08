<?php

include("../controladores/conex.php");

class BuscarEstado extends conex{ 
    
    public $estados;
    
    public function __construct(){

        $this->estados=array();
    }
    
    function buscar_estados(){
        $sql="SELECT * FROM tbl_estado";
        $this->conectar();
        $row_estados=$this->execute($sql);
        return $row_estados;
   }  
}
