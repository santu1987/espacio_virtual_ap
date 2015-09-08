<?php
require_once  '../modelos/modelo.consultar_edo_muc_parr_comunidad.php';  
if( !empty($_POST)){

    //instanciacion
    $objeto= new cosultarComunidad();
    //acceso a la funcion consultar 
    $consulta=$objeto->cosultar();

    //Registros Obtenidos de la Base de Datos
    $codigoestado=$consulta[0][1];
    $codigomunicipio=$consulta[0][2];
    $codigoparroquia=$consulta[0][3];
    $estado=$consulta[0][4];
    $municipio=$consulta[0][5];
    $parroquia=$consulta[0][6];
   

    if($id_comunidad!=''){
        $encontado='datos_encontrados';
        echo '{ "encontrado" : "'.$encontado.'", "codigoestado" : "'.$codigoestado.'", "codigomunicipio" : "'.$codigomunicipio.'", "codigoparroquia" : "'.$codigoparroquia.'", "estado" : "'.$estado.'", "municipio" : "'.$municipio.'", "parroquia" : "'.$parroquia.'" }';
    }else{
        $vacio='datos_no_encontrados';
        echo '{ "vacio" : "'.$vacio.'" }';
    }
}
else
{
 echo 'no se pudo';
}