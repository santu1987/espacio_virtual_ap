<?php
session_start();
require("../modelos/modelo.curriculum.php");
$mensaje=array();
$datos=array();
$rs= array();
$obj_curriculum=new Curriculum();
$rs=$obj_curriculum->consultar_curriculum();
die(json_encode($rs));
?>