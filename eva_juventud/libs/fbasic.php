<?php
//FUNCIONES CREADAS PARA SU UTILIZACION EN TODO EL ENTORNO DEL SISTEMA....
function encode_this($string) 
{
$string = utf8_encode($string);
$control = "qwerty"; //defino la llave para encriptar la cadena, cambiarla por la que deseamos usar
$string = $control.$string.$control; //concateno la llave para encriptar la cadena
$string = base64_encode($string);//codifico la cadena
return($string);
} 

function decode_get2($string)
{
	$cad = split("[?]",$string); //separo la url desde el ?
	$string = $cad[1]; //capturo la url desde el separador ? en adelante
	$string = base64_decode($string); //decodifico la cadena
	$control = "qwerty"; //defino la llave con la que fue encriptada la cadena,, cambiarla por la que deseamos usar
	$string = str_replace($control, "", "$string"); //quito la llave de la cadena

	//procedo a dejar cada variable en el $_GET
	$cad_get = split("[&]",$string); //separo la url por &
	foreach($cad_get as $value)
	{
		$val_get = split("[=]",$value); //asigno los valosres al GET
		$_GET[$val_get[0]]=utf8_decode($val_get[1]);
	}
}
//FUNCION QUE TRANSFORMA UN ARREGLO PHP A UNO EN PL/PGSQL
function to_pg_array($set) {
    settype($set, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($set as $t) {
        if (is_array($t)) {
            $result[] = to_pg_array($t);

        } else {
            $t = str_replace('"', '\\"', $t); // escape double quote
            /*if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' . $t . '"';*/
            $result[] = $t;
        }
    }
    return '{' . implode(",", $result) . '}'; // format
}
//FUNCION PARA VALIDAR LOS POST DE LA PAGINACIÓN
function validar_post_paginacion()
{
    if((!((isset($_POST["offset"]))||(isset($_POST["limit"]))))||(($_POST["limit"]=="")||($_POST["offset"]=="")))
    {
        
        $mensaje[0]="campos_blancos";
       //$mensaje[0]=$_POST["offset"]."-".$_POST["limit"];
        die(json_encode($mensaje));
    }
}
function curar_cadena($cadena)
{
    $valor=str_replace("'"," ",$cadena);
    $valor=trim(str_replace("/"," ",$valor));
    $valor=trim(str_replace("ñ","n",$valor));
    $valor=trim(str_replace("Ñ","N",$valor));
    $valor=trim(str_replace("-","",$valor));
    $valor=trim(str_replace("'","",$valor));
    $valor=trim(str_replace("'","",$valor));
    //$valor=str_replace("(","", $valor);
//    $valor=str_replace("");
    return $valor;
}
function curar_cadena_letras($cadena)
{
    $valor=str_replace("'","",$cadena);
    $valor=trim(str_replace("/"," ",$valor));
    $valor=trim(str_replace("ñ","n",$valor));
    $valor=trim(str_replace("Ñ","N",$valor));
    $valor=trim(str_replace("'","",$valor));
    $valor=trim(str_replace("'","",$valor));
    $valor=trim(str_replace(",","",$valor));
    $valor=trim(str_replace(".","",$valor));
    $valor=trim(str_replace("´","",$valor));
    $valor=trim(str_replace("`","",$valor));
    //$valor=valida($cadena,0);
    $valor=utf8_encode($cadena);
    //$valor=str_replace("(","", $valor);
//    $valor=str_replace("");
    return $valor;
}
function curar_tlf($cadena)
{
     $valor=trim(str_replace("(","",$cadena));
     $valor=trim(str_replace(")","",$valor));
     $valor=trim(str_replace("-","",$valor));
     $valor=trim(str_replace("/","",$valor)); 
     $valor=trim(str_replace("_","",$valor));
     $valor=trim(str_replace(".","",$valor)); 
     $valor=trim(str_replace(",","",$valor));
     $valor=trim(str_replace(".","",$valor));     
     return $valor;
}
?>
