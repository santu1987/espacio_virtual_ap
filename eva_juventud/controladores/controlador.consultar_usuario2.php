<?php
session_start();
require_once("../modelos/modelo.usuario.php");
if(isset($_POST["id_persona"])){ if($_POST["id_persona"]!=""){ $id_persona=$_POST["id_persona"];}else { $id_persona="";}  }else{ $id_persona="";}
//creado el objeto
$obj_us=new usuario();
$recordset=$obj_us->consultar_usuario($id_persona);
die(json_encode($recordset));
?>