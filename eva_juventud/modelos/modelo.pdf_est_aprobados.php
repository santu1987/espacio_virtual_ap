<?php
require("../libs/fpdf17/fpdf.php");
class pdf_lista_est extends FPDF
{
	//Metodo para la cabecera del pdf
	function Header()
	{
            $this->Image("../img/cintillo.jpg",16,6);
            $this->Image("../img/logo_Juventud_Bicentenaria.jpg",250,6,15,15);
            $this->Ln(28);
            $this->SetFont('Arial','B',12);
            $this->Cell(0,5,'LISTADO DE CANTIDAD DE ESTUDIANTES APROBADOS/REPROBADOS',0,0,'C');
            $this->Ln(10);
			$this->SetFillColor(169,169,169) ;
            $this->SetTextColor(1);            
            $this->SetMargins(20, 5 , 10);
            $this->Cell(100,5,"Espacio virtual de Aprendizaje",1,0,'C',1);
            $this->Cell(30,5,"Aprobados",1,0,'C',1);
            $this->Cell(30,5,"Reprobados",1,0,'C',1);
            $this->Ln();
    }
    //Metodo para pie de página
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
		$this->Cell(40,3,'eva_juventud ',0,0,'C');
		$this->Cell(80,3,date("d/m/Y h:m:s"),0,0,'R');					
		$this->Ln();
		$this->SetFillColor(0);
		//$this->Code128(88,285,strtoupper($_SESSION['cedula']),40,6);
	} 
	//-----------------------------------------------------------------------------
}
?>