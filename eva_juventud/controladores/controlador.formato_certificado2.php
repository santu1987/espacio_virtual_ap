<?php
session_start();
require_once("../modelos/modelo.certificado_pdf.php");
require_once("../modelos/modelo.certificado.php");
header('Content-type: application/pdf');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment");
//configurando pdf
$pdf=new PDF_CERTIFICADO();
$id_aula=$_POST["aula_virtual_evaluacion"];
//creo el segundo objeto que me taera los datos del certificado
$obj_cert=new Certificado();
$id_us=$_SESSION['id_us'];
$rs=$obj_cert->consultar_elementos2($id_aula,$id_us);
//aula virtual
$aula_virtual=$rs[0][0];
//creo el fondo
//$fondo="fondo-aula".$id_aula.".jpg";
$fondo=$rs[0][7];
//creo el logo
//$logo="logo-aula".$id_aula.".jpg";
$logo=$rs[0][6];
//creo la firma
$firma_presidente="firma_us58.jpg";
$firma_usuario=$rs[0][3];
if($firma_usuario==null)
{
	$firma_usuario="firma_usuario.jpg";
}
$pdf->AliasNbPages();
$pdf->AddPage('L','Letter');
$pdf->SetMargins(10, 5 , 10); 
//imagen de fondo
$pdf->Image("../img/img_certificados/".$fondo,0,0,300,300);
//cintillo
$pdf->Image("../img/gob_bolivariano.png",16,5,70,20);
$pdf->Image("../img/img_certificados/".$logo,250,5,20,20);
$pdf->SetFont('Arial','B',20);
$pdf->SetFillColor(159,182,205);
$pdf->ln(70);
$pdf->Cell(0,5,"Se le otorga el siguiente certificado a:",0,0,'C',0);
$pdf->ln(20);
$pdf->Cell(0,5,$_SESSION["nom_us"],0,0,'C',0);
$pdf->ln(20);
$pdf->Cell(0,5,"Por haber aprobado el curso de ".$aula_virtual,0,0,'C',0);
$pdf->SetMargins(50, 5 , 30); 
$pdf->ln(20);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(30,5,"Facilitador:",0,0,'C',0);$pdf->Cell(140,5,"Presidente INJ:",0,0,'R',0);$pdf->ln();
$pdf->Cell(30,5,$rs[0][1],0,0,'L',0);$pdf->cell(160,5,$rs[0][4],0,0,'R',0);$pdf->ln();
$pdf->Cell(30,5,$rs[0][2],0,0,'L',0);$pdf->cell(114,5,$rs[0][5],0,0,'R',0);$pdf->ln();
$pdf->Image("../img/firmas/".$firma_usuario,49,160,30,30);$pdf->Image("../img/firmas/".$firma_presidente,180,160,30,30);
//
$pdf->SetMargins(10, 5 , 10); 
//$pdf->Footer();
$pdf->Output();     
?>