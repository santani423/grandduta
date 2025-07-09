<?php

/**
 * @author Trias Bratakusuma
 * @copyright 2012
 */
require_once APPPATH.'/libraries/fpdf/fpdf.php';

class FPDF_AutoWrapTableLaporan3BulananLebih extends FPDF {
private $data3 = array();

private $options = array(
'filename' => '',
'destinationfile' => '',
'paper_size'=>'A4',
'orientation'=>'L'
);

 
	function __construct($data3 = array(), $options = array()) {
		parent::__construct();
		$this->data3 = $data3;
		$this->options = $options;	
	}

	function separator($num, $suffix = '')
	{
 		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
 
		return $result ;
	}
	
	function tampil_bulan($x) {
		$bulan = array (1=>'JANUARI',2=>'FEBRUARI',3=>'MARET',4=>'APRIL',
				5=>'MEI',6=>'JUNI',7=>'JULI',8=>'AGUSTUS',
				9=>'SEPTEMBER',10=>'OKTOBER',11=>'NOVEMBER',12=>'DESEMBER');
		return $bulan[$x];
	}
	 
public function rptDetailData () {
//
$border = 0;
$this->AddPage();
$this->SetAutoPageBreak(true,60);
$this->AliasNbPages();
$left = 25;
 
//header
$this->SetFont("", "B", 12);
$this->MultiCell(0, 12, 'PERUMAHAN GRAND DUTA');
$this->Cell(0, 1, " ", "B");
$this->Ln(10);
$this->SetFont("", "B", 15);
$this->SetX($left); $this->Cell(0, 14, 'LAPORAN BULANAN TAGIHAN DAN PENERIMAAN TIGA BULAN LEBIH', 0, 1,'C');
$this->SetX($left); $this->Cell(0, 14, 'BULAN : ' . $this->tampil_bulan(date('n')) . ' TAHUN : ' . date('Y'), 0, 1,'C');
$this->Ln(10);
 
$h = 40;
$left = 40;
$top = 80;	
#tableheader
$this->SetFillColor(200,200,200);	
$left = $this->GetX();
$this->SetFont("", "B", 12);
$this->SetX($left += 0); $this->Cell(25, $h, 'NO', 1, 0, 'C',true);
$this->SetX($left += 25); $this->Cell(110, $h, 'CLUSTER', 1, 0, 'C',true);

$this->SetX($left += 110); $this->Cell(150, $h, 'TAGIHAN', 1,0, 'C',true);
$this->SetX($left += 150); $this->Cell(150, $h, 'PENERIMAAN', 1, 0, 'C',true);

$this->SetX($left += 150); $this->Cell(150, $h, 'PIUTANG', 1, 0, 'C',true);
$this->SetX($left += 150); $this->Cell(150, $h, 'EFISIENSI PENAGIHAN', 1, 0, 'C',true);
$this->Ln(40);
 
$this->SetFont('Arial','',9);
$this->SetWidths(array(25,110,150,150,150,150));
$this->SetAligns(array('C','C','R','R','R','R'));
$no = 1; $this->SetFillColor(255);


$tagihanbulanlalu=0;
$penerimaanbulanlalu=0;
$piutang=0;

foreach ($this->data3 as $baris)
{
	$tagihanbulanlalu=$tagihanbulanlalu+$baris->tagihan3bulanlebih;
	$penerimaanbulanlalu=$penerimaanbulanlalu+$baris->penerimaan3bulanlebih;
	$piutang=$piutang+$baris->piutang3bulanlebih;
	
	$this->Row(
	array($no++,
	$baris->namacluster,
	$this->separator($baris->tagihan3bulanlebih), 
	$this->separator($baris->penerimaan3bulanlebih),
	$this->separator($baris->piutang3bulanlebih),
	$this->separator($baris->efisiensi3bulanlebih)
	));

}

$this->Row
(
		array
		(
				'','Grand Total',
				$this->separator($tagihanbulanlalu),
				$this->separator($penerimaanbulanlalu),
				$this->separator($piutang)
		)
);

 
}
 
public function printPDF () {
 
if ($this->options['paper_size'] == "F4") {
$a = 8.3 * 72; //1 inch = 72 pt
$b = 13.0 * 72;
$this->FPDF($this->options['orientation'], "pt", array($a,$b));
} else {
$this->FPDF($this->options['orientation'], "pt", $this->options['paper_size']);
}
 
$this->SetAutoPageBreak(false);
$this->AliasNbPages();
$this->SetFont("helvetica", "B", 10);
//$this->AddPage();
 
$this->rptDetailData();
 
$this->Output($this->options['filename'],$this->options['destinationfile']);
}
 
private $widths;
private $aligns;
 
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
 
function Row($data1)
{
//Calculate the height of the row
$nb=0;
for($i=0;$i<count($data1);$i++)
$nb=max($nb,$this->NbLines($this->widths[$i],$data1[$i]));
$h=12*$nb;
//Issue a page break first if needed
$this->CheckPageBreak($h);
//Draw the cells of the row
for($i=0;$i<count($data1);$i++)
{
$w=$this->widths[$i];
$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
//Save the current position
$x=$this->GetX();
$y=$this->GetY();
//Draw the border
$this->Rect($x,$y,$w,$h);
//Print the text
$this->MultiCell($w,9,$data1[$i],0,$a);
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
}

?>
