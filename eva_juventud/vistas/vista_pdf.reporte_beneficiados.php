<?php
session_start();
require("../modelos/modelo.usuario.php");
require("../modelos/modelo.registrar_auditoria.php"); 
require("../modelos/modelo.pdf_beneficiados.php");
header('Content-type: application/pdf');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment");
//-- Validando los POST
if(isset($_POST["rep_estado"])){ if($_POST["rep_estado"]!=""){ $estado=$_POST["rep_estado"];}else{ $estado="";}  }else{ $estado="";}
if(isset($_POST["rep_municipio"])){ if($_POST["rep_municipio"]!=""){ $municipio=$_POST["rep_municipio"];}else{ $municipio="";}  }else{ $municipio="";}
if(isset($_POST["rep_parroquia"])){ if($_POST["rep_parroquia"]!=""){ $parroquia=$_POST["rep_parroquia"];}else{ $parroquia="";}  }else{ $parroquia="";}
/////////////////////////////////////////////////--AUDITORIA--/
$auditoria_contenido=new auditoria("Reporte de Beneficiados ","Generar reporte beneficiados");
$auditoria=$auditoria_contenido->registrar_auditoria();
if($auditoria==false)
{
  $mensaje[0]='error_auditoria';
  die(json_encode($mensaje)); 
}
////////////////////////////////////////////////////////////////////////////////CREO OBJETO PARA LA CONSULTA
$obj_est=new Usuario();	
$rs=$obj_est->consultar_cuerpo_beneficiados($offset,$limit,$estado,$municipio,$parroquia);
//CREO OBJETO PARA EL PDF
$pdf=new pdf_lista_est();
$pdf->Header();
$pdf->AliasNbPages();
$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(159,182,205);
$pdf->SetMargins(45, 5 , 10);
$pdf->SetWidths(array(60,60,60,30));
$total=0;
srand(microtime()*1000000);
/////////////////////////////////////////
for($i=0;$i<=count($rs)-1;$i++)
{
    $pdf->Row(array(utf8_decode($rs[$i][0]),utf8_decode($rs[$i][1]),utf8_decode($rs[$i][2]),$rs[$i][3]));
    if($rs[$i][3]>0)
    {
        $total=$total+$rs[$i][3];
    }
}	
$pdf->Cell(180,5,'Total Beneficiados',1,0,'L',1);
$pdf->Cell(30,5,$total,1,0,'L',1);
////////////////////////////////////////
$pdf->Output('reporte_beneficiados.pdf','D');     
?>