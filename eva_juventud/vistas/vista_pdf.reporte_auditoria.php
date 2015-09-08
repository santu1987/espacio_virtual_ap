<?php
session_start();
require_once("../controladores/conex.php");
require("../modelos/modelo.pdf_filtro_auditoria.php");
require("../modelos/modelo.registrar_auditoria.php"); 
header('Content-type: application/pdf');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment");
//-- Validando los POST
//-- Filtros
if(isset($_POST["f_seccion"])){ if($_POST["f_seccion"]!=""){ $seccion=strtoupper($_POST["f_seccion"]);}else { $seccion=""; } }else{ $seccion="";}
if(isset($_POST["f_accion"])){ if($_POST["f_accion"]!=""){ $accion=strtoupper($_POST["f_accion"]);}else { $accion=""; } }else{ $accion="";}
if(isset($_POST["f_us"])){ if($_POST["f_us"]!=""){ $us=strtoupper($_POST["f_us"]);}else { $us=""; } }else{ $us="";}
if(isset($_POST["f_ip"])){ if($_POST["f_ip"]!=""){ $ip=$_POST["f_ip"];}else { $ip=""; } }else{ $ip="";}
if(isset($_POST["f_fecha"])){ if($_POST["f_fecha"]!=""){ $fecha=$_POST["f_fecha"];}else { $fecha=""; } }else{ $fecha="";}
/////////////////////////////////////////////////--AUDITORIA--/
$auditoria_contenido=new auditoria("Reporte de auditoria ","Generar reporte auditoria");
$auditoria=$auditoria_contenido->registrar_auditoria();
if($auditoria==false)
{
  $mensaje[0]='error_auditoria';
  die(json_encode($mensaje)); 
}
////////////////////////////////////////////////////////////////////////////////CREO OBJETO PARA LA CONSULTA
$rs=$auditoria_contenido->consultar_auditoria($offset,$limit,$actual,$seccion,$accion,$us,$ip,$fecha);
//CREO OBJETO PARA EL PDF
$pdf=new pdf_lista_auditoria();
$pdf->Header();
$pdf->AliasNbPages();
$pdf->AddPage('P','Letter');
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(159,182,205);
$pdf->SetMargins(20, 5 , 10);
$pdf->SetWidths(array(40,40,40,30,30));
srand(microtime()*1000000);
/////////////////////////////////////////
for($i=0;$i<=count($rs)-1;$i++)
{
    $pdf->Row(array($rs[$i][0],$rs[$i][1],$rs[$i][2],$rs[$i][3],$rs[$i][4]));
}	
////////////////////////////////////////
$pdf->Output('reporte_auditoria.pdf','D');     
?>