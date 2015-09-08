<?php
session_start();
require_once("../modelos/modelo.certificado_pdf.php");
header('Content-type: application/pdf');
//configurando pdf
$pdf=new PDF_CERTIFICADO();
$pdf->AliasNbPages();
$pdf->AddPage('P','Letter');
$pdf->SetMargins(10, 5 , 10); 
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(159,182,205);
//cintillo
$pdf->Image("../img/gobiernobolivariano-logo.png",16,5,160,20);
$pdf->Output();     
?>