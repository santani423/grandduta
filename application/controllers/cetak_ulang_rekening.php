<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cetak_ulang_rekening extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->library('cfpdf');	
	}
	
	public function index()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
				
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			$data['action']  = 'cetak_ulang_rekening/querytagihan';
			
			// set judul halaman
			$data['judulpage'] = "Cetak Ulang Kuitansi";
			
			$this->template->load('template','penagihan/cetakulang_view',$data);
		}
	}
        
        public function separator($num, $suffix = '')
	{
		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
	
		return $result ;
	}
	
	public function querytagihan()
	{
            if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
				
			// load model 'usermodel'
			$this->load->model('Master_model');
			$this->load->model('Penagihan_model');
				
			$nomorkwitansi=$this->input->post('nokuitansi');
			//echo $nomorkwitansi;
                        
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
// 			echo $id;
// 			echo $blntag;
			
			//================================= Mulai Cetak Pelanggan =======================

			$pdf = new FPDF("L","cm", array(21.6, 9.32));
			
			//================================= Ambil Pelanggan Akan dicetak ================
			$tagihanulang = $this->Penagihan_model->get_kuitansi_ulang($nomorkwitansi);
			
			//$num_rows=$tagihanulang->num_rows();
			$tagihanbaris=$tagihanulang->row();
			
                        
/*			$rincianidtagihan = '';
		
			$namanya=$tagihanbaris->nama ;
			$clusternya=$tagihanbaris->namacluster ;
			$bloknya=$tagihanbaris->blok ;
			$nokavlingnya=$tagihanbaris->nokav ;
			$totaltagihannya=$totaltagihan ;
			$totaldendanya=$totaldenda ;
			$jumlahtotalnya=$totaltagihan+$totaldenda ;
			$jumlahtagihannya=$jmlbulan ;
			$loketnya= "Service Center ";
			$kasirnya= $this->session->userdata('nama');
			
                        echo $namanya;
*/				
			// 		echo $num_rows;
			// 		echo $tagihanbaris->idipkl;
			
			// ==============Header================
			// 		$idipkl=$tagihanbaris->idipkl;
			// 		$nama=$tagihanbaris->namapelanggan;
			// 		$alamat=$tagihanbaris->namacluster + ' Blok ' + $tagihanbaris->blok + ' No. Kav ' + $tagihanbaris->nokav;
			// ==============Header================
				
			//===============================================================================
			
			// Get data dari tabel tagihan dengan caltagihan
			//if ($num_rows > 0)
			//{
				$pdf->AddPage();
				$pdf->SetMargins(1,1,1);
				$pdf->SetXY(0,0.8);
				$pdf->SetMargins(1,0.5,1);
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(0  ,0.4,'No : '. $nomorkwitansi,0,1, 'R');
				$pdf->Cell(0  ,0.4,'BUKTI PEMBAYARAN IPKL PERUMAHAN GRAND DUTA TANGERANG',0,1, 'C');
				$pdf->Cell(0  ,0.4,'' ,0,1, 'C');
				$pdf->Cell(2 ,0.2,' ',0,1,'C');
					
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(3.1 ,0.4,'ID IPKL',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(6 ,0.4, $tagihanbaris->idipkl ,0,0, 'L');
					
				$pdf->Cell(3.1 ,0.4,'NAMA',0,0, 'L');
				$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
				$pdf->Cell(6 ,0.4, $tagihanbaris->nama ,0,1, 'L');
					
				$pdf->Cell(3.1 ,0.4,'CLUSTER',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(6 ,0.4, $tagihanbaris->cluster ,0,0, 'L');
			
				$pdf->Cell(3.1 ,0.4,'BLOK',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(3 ,0.4, $tagihanbaris->blok ,0,1, 'L');
			
				$pdf->Cell(3.1 ,0.4,'NO. KAVLING',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(3 ,0.4, $tagihanbaris->nokavling ,0,1, 'L');
					
				$pdf->Cell(2 ,0.15,' ',0,1,'C');
					
				//$pdf->Ln();
				$pdf->Cell(0  ,0.4,'PERINCIAN TAGIHAN YANG HARUS DIBAYAR ',0,1, 'L');
				//$pdf->Ln();
					
				$pdf->Cell(2 ,0.15,' ',0,1,'C');
					
				$pdf->Cell(5.5 ,0.4,'Total Tagihan IPKL ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, $this->separator($tagihanbaris->totaltagihan),0,0, 'R');
					
				$pdf->Cell(3.5 ,0.4,'Dibayar Tgl. ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(3 ,0.4, date('j'.'/'.'m'.'/'.'Y') ,0,1, 'R');
			
				$pdf->Cell(5.5 ,0.4,'Total Denda ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, $this->separator($tagihanbaris->totaldenda) ,0,0, 'R');
					
				$pdf->Cell(3.5 ,0.4,'Loket ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(3 ,0.4, 'Service Center' ,0,1, 'R');
					
				$pdf->Cell(5.5 ,0.4,'Jumlah Total Yang Harus Dibayar ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, $this->separator($tagihanbaris->jumlahtotal) ,0,0, 'R');
					
				$pdf->Cell(3.5 ,0.4,'Kasir ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(3 ,0.4, $tagihanbaris->kasir ,0,1, 'R');
					
				$pdf->Cell(5.5 ,0.4,'Jumlah Bulan Tagihan ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, $tagihanbaris->jumlahtagihan ,0,1, 'R');
					
				$pdf->Cell(5.5 ,0.4,'Perincian Bulan ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, ' ' ,0,1, 'R');
					
				if(strlen($tagihanbaris->rincianbulan)<121)
                                {
                                    $pdf->Cell(5.5 ,0.4, $tagihanbaris->rincianbulan ,0,1, 'L');
                                }
                                else
                                {
                                    $pdf->Cell(5.5 ,0.4, substr($tagihanbaris->rincianbulan,0,120) ,0,1, 'L');
                                    $pdf->Cell(5.5 ,0.4, substr($tagihanbaris->rincianbulan,(0-(strlen($tagihanbaris->rincianbulan)-120))),0,0, 'L');
                                }
                                
					
				$pdf->Cell(2 ,0.2,' ',0,1,'C');
				$pdf->Cell(2 ,0.1,' ',0,1,'C');
				$pdf->Cell(2 ,0.15,' ',0,1,'C');
				$pdf->Cell(0  ,0.3,'Grand Duta Menyatakan lembar transaksi ini sebagai bukti pembayaran yang sah. Informasi : Telp. 55 73 0 888',0,0, 'L');
				//$pdf->Cell(0  ,0.3,'Informasi : Telp. 55 73 0 888',0,1, 'L');
			
			//}
			//else
			//{			
			
			//}
				
			//=========================== Akhir Cetak Pelanggan
			
 			$pdf->Output();
			
			
		}
	}
	
}
