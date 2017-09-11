<?php

namespace Gist\ReportBundle\Model;

use FPDF\FPDF;
use DateTime;
use DateTimeZone;

class ReportPDF 
{
	public function generate()
    {

		$pdf = new FPDF();
		
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);		
		$pdf->Cell(40,10,'Hello World!');		
		$pdf->Output();
    }
}