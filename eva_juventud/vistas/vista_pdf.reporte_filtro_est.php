<?php
session_start();
require("../modelos/modelo.usuario.php");
require("../modelos/modelo.pdf_filtro_est.php");
require("../modelos/modelo.registrar_auditoria.php"); 
header('Content-type: application/pdf');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment");
//-- Validando los POST
if(isset($_POST["rep_nacionalidad"])){ if($_POST["rep_nacionalidad"]!=""){ $nacionalidad=$_POST["rep_nacionalidad"];}else{ $nacionalidad="";}  }else{ $nacionalidad="";}
if(isset($_POST["rep_cedula_est"])){ if($_POST["rep_cedula_est"]!=""){ $cedula=$_POST["rep_cedula_est"];}else{ $cedula="";}  }else{ $cedula="";}
if(isset($_POST["rep_nombre_est"])){ if($_POST["rep_nombre_est"]!=""){ $nombre=$_POST["rep_nombre_est"];}else{ $nombre="";}  }else{ $nombre="";}
if(isset($_POST["rep_estado"])){ if($_POST["rep_estado"]!=""){ $estado=$_POST["rep_estado"];}else{ $estado="";}  }else{ $estado="";}
if(isset($_POST["rep_municipio"])){ if($_POST["rep_municipio"]!=""){ $municipio=$_POST["rep_municipio"];}else{ $municipio="";}  }else{ $municipio="";}
if(isset($_POST["rep_parroquia"])){ if($_POST["rep_parroquia"]!=""){ $parroquia=$_POST["rep_parroquia"];}else{ $parroquia="";}  }else{ $parroquia="";}
/////////////////////////////////////////////////--AUDITORIA--/
$auditoria_contenido=new auditoria("Reporte de estudiantes ","Generar reporte estudiante");
$auditoria=$auditoria_contenido->registrar_auditoria();
if($auditoria==false)
{
  $mensaje[0]='error_auditoria';
  die(json_encode($mensaje)); 
}
////////////////////////////////////////////////////////////////////////////////CREO OBJETO PARA LA CONSULTA
$obj_est=new Usuario();	
$rs=$obj_est->consultar_cuerpo_rep_estudiante($offset,$limit,$nacionalidad,$cedula,$nombre,$estado,$municipio,$parroquia);
//CREO OBJETO PARA EL PDF
$pdf=new pdf_lista_est();
$pdf->Header();
$pdf->AliasNbPages();
$pdf->AddPage('L','Letter');
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(159,182,205);
$pdf->SetMargins(10, 5 , 10);
$pdf->SetWidths(array(10,30,50,55,30,30,30,30));
srand(microtime()*1000000);
/////////////////////////////////////////
for($i=0;$i<=count($rs)-1;$i++)
{
    $pdf->Row(array($rs[$i][2],$rs[$i][3],$rs[$i][4],$rs[$i][5],$rs[$i][6],$rs[$i][7],$rs[$i][8],$rs[$i][9]));
}	
////////////////////////////////////////
$pdf->Output('reporte_estudiantes.pdf','D');     
?>