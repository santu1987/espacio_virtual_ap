<?php
 session_start();
 if(isset($_SESSION["espacio_virtual"])){

    if($_SESSION["espacio_virtual"] != "SI"){
        session_unset();
        session_destroy();	
        echo "<script>
                    location.href='http://".$_SERVER['HTTP_HOST']."/eva_juventud/';
             </script>";
        exit();
    }
    
 }else{
     echo "<script>
                   location.href = 'http://".$_SERVER['HTTP_HOST']."/eva_juventud/';
           </script>";
 }
 
 if (isset($_GET["cerrar"])){ $cerrar = base64_decode($_GET["cerrar"]);} else { $cerrar = ""; }
?> 