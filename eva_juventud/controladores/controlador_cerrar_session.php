<?php
 session_start();
 include_once '../modelos/modelo.usuario.php';
 $usuario = New Usuario();
 $resp = $usuario->cerrar_session();
 echo "$resp";
?>