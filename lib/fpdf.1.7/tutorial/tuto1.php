<?php
	require('../fpdf.php');

	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',16);
	$pdf->Cell(40,10,'Sirenga, my love. June, our baby.');
	$pdf->Output();
?>
