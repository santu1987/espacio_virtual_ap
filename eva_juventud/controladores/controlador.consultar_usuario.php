<?php
session_start();
require_once("../modelos/modelo.usuario.php");
$id=$_SESSION["id"];
//creado el objeto
$obj_us=new usuario();
$recordset=$obj_us->consultar_usuario($id);
die(json_encode($recordset));
?>