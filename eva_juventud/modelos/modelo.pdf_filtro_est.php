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
            $this->Cell(0,5,'LISTADO DE ESTUDIANTES REGISTRADOS',0,0,'C');
            $this->Ln(10);
			$this->SetFillColor(169,169,169) ;
            $this->SetTextColor(1);            
            $this->Cell(10,5,"Nac.",1,0,'C',1);
            $this->Cell(30,5,utf8_decode("Cédula"),1,0,'C',1);
            $this->Cell(50,5,"Nombres",1,0,'C',1);
            $this->Cell(55,5,utf8_decode("Correo electrónico"),1,0,'C',1);
            $this->Cell(30,5,utf8_decode("Teléfono"),1,0,'C',1);
            $this->Cell(30,5,"Estado",1,0,'C',1);
            $this->Cell(30,5,"Municipio",1,0,'C',1);
            $this->Cell(30,5,"Parroquia",1,0,'C',1);
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
		$this->Cell(120,3,'eva_juventud ',0,0,'C');
		$this->Cell(80,3,date("d/m/Y h:m:s"),0,0,'R');					
		$this->Ln();
		$this->SetFillColor(0);
		//$this->Code128(88,285,strtoupper($_SESSION['cedula']),40,6);
	} 
	//-----------------------------------------------------------------------------
	//--Para manejar el multicell sin novedad
	var $widths;
	var $aligns;
	function SetWidths($w)
	{
	    //Set the array of column widths
	    $this->widths=$w;
	}
	function SetAligns($a)
	{
	    //Set the array of column alignments
	    $this->aligns=$a;
	}
	function Row($data)
	{
	    //Calculate the height of the row
	    $nb=0;
	    for($i=0;$i<count($data);$i++)
	        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	    $h=5*$nb;
	    //Issue a page break first if needed
	    $this->CheckPageBreak($h);
	    //Draw the cells of the row
	    for($i=0;$i<count($data);$i++)
	    {
	        $w=$this->widths[$i];
	        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
	        //Save the current position
	        $x=$this->GetX();
	        $y=$this->GetY();
	        //Draw the border
	        $this->Rect($x,$y,$w,$h);
	        //Print the text
	        $this->MultiCell($w,5,$data[$i],0,$a);
	        //Put the position to the right of the cell
	        $this->SetXY($x+$w,$y);
	    }
	    //Go to the next line
	    $this->Ln($h);
	}
	function CheckPageBreak($h)
	{
	    //If the height h would cause an overflow, add a new page immediately
	    if($this->GetY()+$h>$this->PageBreakTrigger)
	        $this->AddPage($this->CurOrientation);
	}
	function NbLines($w,$txt)
	{
	    //Computes the number of lines a MultiCell of width w will take
	    $cw=&$this->CurrentFont['cw'];
	    if($w==0)
	        $w=$this->w-$this->rMargin-$this->x;
	    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
	    if($nb>0 and $s[$nb-1]=="\n")
	        $nb--;
	    $sep=-1;
	    $i=0;
	    $j=0;
	    $l=0;
	    $nl=1;
	    while($i<$nb)
	    {
	        $c=$s[$i];
	        if($c=="\n")
	        {
	            $i++;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	            continue;
	        }
	        if($c==' ')
	            $sep=$i;
	        $l+=$cw[$c];
	        if($l>$wmax)
	        {
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	            }
	            else
	                $i=$sep+1;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	        }
	        else
	            $i++;
	    }
	    return $nl;
	}
	//-----------------------------------------------------------------------------   
}
?>