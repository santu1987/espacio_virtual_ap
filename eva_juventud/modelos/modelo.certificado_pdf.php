<?php
require_once("../libs/fpdf17/fpdf.php");
class PDF_CERTIFICADO extends FPDF
{
//Pie de pagina
	function Footer()
	{
			$this->SetY(-25);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//N�mero de p�gina
			$this->Ln();
			$this->Ln(10);
			$this->SetFont('Arial','B',8);
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			//$this->Cell(62,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(180,3,date("d/m/Y h:m:s"),0,0,'R');					
		}    
}
?>