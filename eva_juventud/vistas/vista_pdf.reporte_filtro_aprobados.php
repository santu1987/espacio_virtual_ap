<?php
session_start();
require("../modelos/modelo.registrar_evaluacion.php");
require("../modelos/modelo.registrar_auditoria.php"); 
require("../modelos/modelo.pdf_est_aprobados.php");
header('Content-type: application/pdf');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment");
//-- Validando los POST
if(isset($_POST["rep_nombre_detalle_eva"])){ if($_POST["rep_nombre_detalle_eva"]!=""){ $eva=strtoupper($_POST["rep_nombre_detalle_eva"]);}else{ $eva="";}  }else{ $eva="";}
/////////////////////////////////////////////////--AUDITORIA--/
$auditoria_contenido=new auditoria("Reporte de aprobados general ","Generar reporte aprobados/reprobados");
$auditoria=$auditoria_contenido->registrar_auditoria();
if($auditoria==false)
{
  $mensaje[0]='error_auditoria';
  die(json_encode($mensaje)); 
}
////////////////////////////////////////////////////////////////////////////////CREO OBJETO PARA LA CONSULTA
$obj_est=new evaluacion();	
$rs=$obj_est->consultar_cuerpo_estudiantes_aprobados($offset,$limit,$eva);
//CREO OBJETO PARA EL PDF
$pdf=new pdf_lista_est();
$pdf->Header();
$pdf->AliasNbPages();
$pdf->AddPage('P','Letter');
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(159,182,205);
$pdf->SetMargins(20, 5 , 10);
//$pdf->Cell(100,5,$eva,1,0,'L',0);
$total_aprobados=0;$total_reprobados=0;	
/////////////////////////////////////////
for($i=0;$i<=count($rs)-1;$i++)
{
	$pdf->Cell(100,5,$rs[$i][0],1,0,'L',0);
	$pdf->Cell(30,5,$rs[$i][1],1,0,'C',0);
	$pdf->Cell(30,5,$rs[$i][2],1,0,'C',0);
	$pdf->ln();
	$total_aprobados=$total_aprobados+$rs[$i][1];
	$total_reprobados=$total_reprobados+$rs[$i][2];
}	
$pdf->Cell(100,5,'Total General',1,0,'L',1);
$pdf->Cell(30,5,$total_aprobados,1,0,'C',1);
$pdf->Cell(30,5,$total_reprobados,1,0,'C',1);
////////////////////////////////////////
$pdf->Output('reporte_estudiantes_aprobados.pdf','D');     
?>