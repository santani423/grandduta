<?php

/**
 * @author Trias Bratakusuma
 * @copyright 2012
 */
require_once APPPATH.'/libraries/fpdf/fpdf.php';

class FPDF_AutoWrapTableRekapTagihan extends FPDF {
private $data = array();

private $options = array(
'filename' => '',
'destinationfile' => '',
'paper_size'=>'A4',
'orientation'=>'L'
);

 
	function __construct($data = array(), $options = array()) {
		parent::__construct();
		$this->data = $data;
		$this->options = $options;	
	}

	function separator($num, $suffix = '')
	{
 		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
 
		return $result ;
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
$this->SetX($left); $this->Cell(0, 10, 'REKAP TAGIHAN', 0, 1,'C');
$this->Ln(10);
 
$h = 20;
$left = 40;
$top = 80;	
#tableheader
$this->SetFillColor(200,200,200);	
$left = $this->GetX();
$this->SetFont("", "B", 12);
$this->SetX($left += 0); $this->Cell(25, $h, 'NO', 1, 0, 'C',true);
$this->SetX($left += 25); $this->Cell(45, $h, 'ID IPKL', 1, 0, 'C',true);
$this->SetX($left += 45); $this->Cell(120, $h, 'NAMA', 1, 0, 'C',true);
$this->SetX($left += 120); $this->Cell(90, $h, 'CLUSTER', 1, 0, 'C',true);

$this->SetX($left += 90); $this->Cell(70, $h, 'BLOK|KAV', 1, 0, 'C',true);
$this->SetX($left += 70); $this->Cell(70, $h, 'BULAN INI', 1, 0, 'C',true);
$this->SetX($left += 70); $this->Cell(80, $h, 'BULAN LALU', 1, 0, 'C',true);

$this->SetX($left += 80); $this->Cell(90, $h, '2 BULAN LALU', 1, 0, 'C',true);
$this->SetX($left += 90); $this->Cell(90, $h, '3 BULAN LEBIH', 1, 0, 'C',true);
$this->SetX($left += 90); $this->Cell(100, $h, 'TOTAL TAGIHAN', 1, 0, 'C',true);
$this->Ln(20);
 
$this->SetFont('Arial','',9);
$this->SetWidths(array(25,45,120,90,70,70,80,90,90,100));
$this->SetAligns(array('C','C','L','L','L','R','R','R','R','R'));
$no = 1; $this->SetFillColor(255);
foreach ($this->data as $baris) {
$this->Row(
array($no++,
$baris->idipkl,
$baris->namapelanggan,
$baris->namacluster,
$baris->blok .' '.
$baris->nokav,
$this->separator($baris->tagihanbulanberjalan), 
$this->separator($baris->tagihanbulanlalu),
$this->separator($baris->tagihan2bulanlalu),
$this->separator($baris->tagihan3bulanlebih),
$this->separator($baris->Total)
));
}
 
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
 
function Row($data)
{
//Calculate the height of the row
$nb=0;
for($i=0;$i<count($data);$i++)
$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
$h=12*$nb;
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
$this->MultiCell($w,9,$data[$i],0,$a);
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