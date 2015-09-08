<?php
session_start();
?>
<!--Bloque javascript -->
<script type="text/javascript">
	//cargas de div
	/*$("#menu_app").load("./vistas/vista.menu_app.php");*/
	//BLOQUE DE EVENTOS
	$("#program_body").load("./vistas/vista.registrar_usuario.php");
	$("#pie_pag").removeClass("contendor_pie_pagina2").addClass("contendor_pie_pagina");
	//BLOQUE DE FUNCIONES

</script>
<!-- -->
<!--Bloque HTML -->
     <div id="program_body" name="program_body">
     </div>
     <!-- -->
<!-- -->