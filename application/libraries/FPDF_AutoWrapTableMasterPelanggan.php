<?php

/**
 * @author Trias Bratakusuma
 * @copyright 2012
 */
require_once APPPATH.'/libraries/fpdf/fpdf.php';

class FPDF_AutoWrapTableMasterPelanggan extends FPDF {
private $data = array();

private $options = array(
'filename' => '',
'destinationfile' => '',
'paper_size'=>'A4',
'orientation'=>'P'
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
$this->SetX($left); $this->Cell(0, 14, 'DAFTAR MASTER PELANGGAN IPKL', 0, 1,'C');
$this->SetX($left); $this->Cell(0, 14, 'BULAN : ' . $this->tampil_bulan(date('n')) . ' TAHUN : ' . date('Y'), 0, 1,'C');
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
	$this->SetX($left += 120); $this->Cell(90, $h, 'HUNI/KOSONG', 1, 0, 'C',true);
	$this->SetX($left += 90); $this->Cell(70, $h, 'BLOK|KAV', 1, 0, 'C',true);
	$this->SetX($left += 70); $this->Cell(80, $h, 'TELPON/HP', 1, 0, 'C',true);
	$this->SetX($left += 80); $this->Cell(110, $h, 'SERAH TERIMA', 1, 0, 'C',true);
	$this->Ln(20);
 
	$this->SetFont('Arial','',9);
	$this->SetWidths(array(25,45,120,90,70,80,110));
	$this->SetAligns(array('C','C','L','C','C','C','C'));
	$no = 1; 
	$this->SetFillColor(255);
	
	foreach ($this->data as $baris)
	{
	$this->Row
	(
		array($no++,
		$baris->idipkl,
		$baris->namapelanggan,
		$baris->namahuni,
		$baris->blok .' No. '.
		$baris->nokav,				
		$baris->notelpon . ' ' . $baris->nohp,
		$baris->tglserahterima
			)
	); 
	}
        $this->SetX($left=10); $this->Cell(0, 12, ' ' , 0, 1,'C');
        $this->SetX($left=20); $this->Cell(0, 12, 'TANGGAL CETAK : ' .  date('d') . ' ' . $this->tampil_bulan(date('n')) . ' ' . date('Y') .'  '. date("H:i:s"), 0, 1,'L');
  
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
