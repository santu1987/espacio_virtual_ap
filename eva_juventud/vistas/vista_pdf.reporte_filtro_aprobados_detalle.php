<?php
session_start();
require("../modelos/modelo.registrar_evaluacion.php");
require("../modelos/modelo.pdf_filtro_est_detalle.php");
require("../modelos/modelo.registrar_auditoria.php"); 
header('Content-type: application/pdf');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment");
//-- Validando los POST
if(isset($_POST["rep_nacionalidad"])){ if($_POST["rep_nacionalidad"]!=""){ $nacionalidad=$_POST["rep_nacionalidad"];}else{ $nacionalidad="";}  }else{ $nacionalidad="";}
if(isset($_POST["rep_cedula_est"])){ if($_POST["rep_cedula_est"]!=""){ $cedula=$_POST["rep_cedula_est"];}else{ $cedula="";}  }else{ $cedula="";}
if(isset($_POST["rep_nombre_est"])){ if($_POST["rep_nombre_est"]!=""){ $nombre=strtoupper($_POST["rep_nombre_est"]);}else{ $nombre="";}  }else{ $nombre="";}
if(isset($_POST["rep_nombre_eva"])){ if($_POST["rep_nombre_eva"]!=""){ $nombre_eva=strtoupper($_POST["rep_nombre_eva"]);}else{ $nombre_eva="";}  }else{ $nombre_eva="";}
/////////////////////////////////////////////////--AUDITORIA--/
$auditoria_contenido=new auditoria("Reporte de estudiantes ","Generar reporte estudiante");
$auditoria=$auditoria_contenido->registrar_auditoria();
if($auditoria==false)
{
  $mensaje[0]='error_auditoria';
  die(json_encode($mensaje)); 
}
////////////////////////////////////////////////////////////////////////////////CREO OBJETO PARA LA CONSULTA
$obj_est=new evaluacion();	
$rs=$obj_est->consultar_cuerpo_est_apr($offset,$limit,$nombre_eva,$nacionalidad,$cedula,$nombre);
//CREO OBJETO PARA EL PDF
$pdf=new pdf_lista_est();
$pdf->Header();
$pdf->AliasNbPages();
$pdf->AddPage('L','Letter');
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(159,182,205);
$pdf->SetMargins(10, 5 , 10);
$pdf->SetWidths(array(20,30,80,80,40));
srand(microtime()*1000000);
/////////////////////////////////////////
for($i=0;$i<=count($rs)-1;$i++)
{
    $pdf->Row(array($rs[$i][0],$rs[$i][1],$rs[$i][2],$rs[$i][3],$rs[$i][4]));
}	
////////////////////////////////////////
$pdf->Output('reporte_estudiantes.pdf','D');     
?>