<?php

/**
 * @author Trias Bratakusuma
 * @copyright 2012
 */
require_once APPPATH.'/libraries/fpdf/fpdf.php';

class FPDF_AutoWrapTable extends FPDF {
private $data = array();
private $options = array(
'filename' => '',
'destinationfile' => '',
'paper_size'=>'A4',
'orientation'=>'P'
);
private $isihead  = array(
			'nosambungan' => '', 
			'nama' => '', 
			'alamat'=>'',	
			'tahun'=>'',	
			'proses'=>'' 
			);
 
	function __construct($data = array(), $options = array(),$isihead = array()) {
		parent::__construct();
		$this->data = $data;
		$this->options = $options;
		$this->isihead = $isihead;
	}
 
public function rptDetailData () {
//
$border = 0;
$this->AddPage();
$this->SetAutoPageBreak(true,60);
$this->AliasNbPages();
$left = 5;
 
//header
$this->SetFont("", "B", 15);
$this->MultiCell(0, 12, 'PENGEMBANG PERUMAHAN GRAND DUTA TANGERANG');
$this->Cell(0, 1, " ", "B");
$this->Ln(10);
$this->SetFont("", "B", 12);
$this->Ln(10);
$this->SetX($left); $this->Cell(0, 10, 'INFORMASI TAGIHAN IPKL', 0, 1,'C');
$this->Ln(10);

$this->Ln(10);
$this->SetFont("Arial", "", 11);

$this->Cell(120, 10, 'ID. IPKL', 0, 0,'L');
$this->Cell(4, 10, ' : ', 0, 0,'C');
$this->Cell(4, 10, $this->isihead['nosambungan'] , 0, 1,'L');
$this->Ln(10);
$this->Cell(120, 10, 'Nama Pelanggan', 0, 0,'L');
$this->Cell(4, 10, ' : ', 0, 0,'C');
$this->Cell(4, 10, $this->isihead['nama'] , 0, 1,'L');
$this->Ln(10);
$this->Cell(120, 10, 'Alamat Pelanggan', 0, 0,'L');
$this->Cell(4, 10, ' : ', 0, 0,'C');
$this->Cell(4, 10, $this->isihead['alamat'] , 0, 1,'L');
$this->Ln(10);
$this->Cell(120, 10, 'Tahun Tagihan', 0, 0,'L');
$this->Cell(4, 10, ' : ', 0, 0,'C');
$this->Cell(4, 10, $this->isihead['tahun'] , 0, 1,'L');
$this->Ln(10);
$this->Cell(120, 10, 'Diproses Tanggal', 0, 0,'L');
$this->Cell(4, 10, ' : ', 0, 0,'C');
$this->Cell(4, 10, $this->isihead['proses'] , 0, 1,'L');
$this->Ln(10);

	//====================================================================
 	
$h = 26;
$left = 0;
$top = 80;	
#tableheader
$this->SetFillColor(200,200,200);	
$left = $this->GetX();
$this->Cell(0,$h,'BULAN',1,0,'C',true);
$this->SetX($left += 60);$this->Cell(50, $h, 'GOL', 1, 0, 'C',true);
$this->SetX($left += 60); $this->Cell(75, $h, 'TAGIHAN', 1, 0, 'C',true);
$this->SetX($left += 100); $this->Cell(100, $h, 'LOKET', 1, 0, 'C',true);
$this->SetX($left += 10); $this->Cell(100, $h, 'TGL BAYAR', 1, 0, 'C',true);
$this->Ln(26);
 
$this->SetFont('Arial','',10);
$this->SetWidths(array(60,60,100,100,100));
$this->SetAligns(array('C','C','R','R','C'));
$no = 1; $this->SetFillColor(255);
foreach ($this->data as $baris) {
$this->Row(
array(
$baris->bulan,
$baris->kdgol,
$this->separator($baris->tagihan),
$baris->loket,
$baris->tanggalbayar
));
}

//FOOTER
$this->Ln(26);
$this->Cell(0, 1, " ", "B");
$this->Ln(10);
$this->SetFont("Arial", "", 12);
$this->MultiCell(0, 12, 'Keterangan :');
$this->Ln(10);
$this->SetFont("Arial", "", 11);
//$this->SetX($left);
$this->Cell(0, 10, '* Lembar Informasi ini bukan merupakan bukti pembayaran ', 0, 1,'L');
$this->Ln(10);
$this->Cell(0, 10, '* Informasi tagihan yang tercantum belum termasuk denda dan materai ', 0, 1,'L');
$this->Ln(10);
$this->Cell(0, 10, '* Setiap 1(satu) bulan keterlambatan berikutnya dikenakan denda kelipatan sesuai dengan jenis ', 0, 1,'L');
$this->Ln(10);
$this->Cell(0, 10, 'golongan tarif, berlaku mulai rekening bulan Peruari 2003', 0, 1,'L');
$this->Ln(10);
}

	function separator($num, $suffix = '')
	{
 		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
 
		return $result ;
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
$h=10*$nb;
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
$this->MultiCell($w,10,$data[$i],0,$a);
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