<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penagihan extends CI_Controller
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
			
			$data['action']  = 'penagihan/querytagihan';
			
			// set judul halaman
			$data['judulpage'] = "Penagihan";
			
			$this->template->load('template','penagihan/transaksi_view',$data);
		}
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
						
			$id=$this->input->post('idipkl');
	
			// level untuk user ini
			$level = $this->session->userdata('level');
	
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
				
 			$data['action']  = 'penagihan/bayartagihan';
				
			// set judul halaman
			$data['judulpage'] = "Rincian Penagihan";
			$data['isicarabayar'] = $this->Penagihan_model->getCaraBayarList();
			$data['isilewatbayar'] = $this->Penagihan_model->getLewatBayarList();
			
// 			$pelanggan = $this->Master_model->get_one($id);
// 			$pelangganbaris = $pelanggan->result();
			
			$pelangganbaris = $this->Penagihan_model->get_onepelanggan($id)->row();
			$getnomorkuitansi=$this->Penagihan_model->get_nomor_kwitansi();				
			$nomorkwitansibaris=$getnomorkuitansi->row();
			$nomorkwitansi='GD.'.$nomorkwitansibaris->tahun.'.'.$nomorkwitansibaris->bulan.'.'.$nomorkwitansibaris->counter;
				
				$data['default']['idipkl']=$pelangganbaris->ID_IPKL;
				$data['default']['nama']=$pelangganbaris->NAMA_PELANGGAN;
				$data['default']['blok']=$pelangganbaris->BLOK;
				$data['default']['nokav']=$pelangganbaris->NO_KAVLING;
				$data['default']['namacluster']=$pelangganbaris->NAMA_CLUSTER;
				$data['default']['nokwitansi']=$nomorkwitansi;
					
				$data['tagihans'] = $this->Penagihan_model->get_tagihan_belumlunas($id);
				$data['totalnya'] = $this->Penagihan_model->get_tagihantotal_belumlunas($id);
								
// 				$this->insert_kwitansi('11','1111','ddn','dd', 'd1','1',100,10,110,1,now(),'1', '1','121223');
				
			$this->template->load('template','penagihan/rinciantagihan_view',$data);
		}
	}
	
	public function separator($num, $suffix = '')
	{
		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
	
		return $result ;
	}
	
	public function update_lunas()
	{
		$pelunasan = $this->Penagihan_model->update_tagihan_lunas();
	}
	
	public function insert_kwitansi($nomorkwitansinya,$idipklnya,$tglbayar)
	{
		$kwit=$this->Penagihan_model->insert_kwit($nomorkwitansinya,$idipklnya,$tglbayar)->result();
	}
	
	public function bayartagihan()
	{

		$this->load->model('Penagihan_model');

// 		$idipklnya = $this->uri->segment(3);
//  		echo $idipkl;
		$idipklnya=$this->input->post('idipkl');
		$nomorkwitansinya=$this->input->post('nokwitansi');	
		$idcarabayarnya=$this->input->post('cbocarabayar');
		$idlewatbayarnya=$this->input->post('cbolewatbayar');
		//===============================================================================

		
		//================================= Mulai Cetak Pelanggan =======================
			
		//================================= Ambil Pelanggan Akan dicetak ================
		$tagihan = $this->Penagihan_model->get_tagihan($idipklnya);
		$tagihantotal = $this->Penagihan_model->get_tagihan_total($idipklnya);
		$tahunbulan = $this->Penagihan_model->get_tahun_bulan($idipklnya);
		
		$num_rows=$tagihan->num_rows();
		$tagihanbaris=$tagihan->row();
		
		$tagihantotalbaris=$tagihantotal->row();
		
		$tahunbulanhasil=$tahunbulan->result();
		
		$rincianbulantahunnya='';
		
		foreach ($tahunbulanhasil as $row)
		{
			$rincianbulantahunnya=$rincianbulantahunnya . '|' . '(' . $row->tahun . ')' . ' ' . $row->bulan;
		}
		
//		$nomorkwitansinya=$tagihanbaris-> ;
//		$idipklnya=$tagihanbaris-> ;
		$namanya=$tagihanbaris->namapelanggan ;
		$clusternya=$tagihanbaris->namacluster ;
		$bloknya=$tagihanbaris->blok ;
		$nokavlingnya=$tagihanbaris->nokav ;
		$totaltagihannya=$tagihantotalbaris->jmltagihan ;
		$totaldendanya=$tagihantotalbaris->jmldenda ;
		$jumlahtotalnya=$tagihantotalbaris->jmltotal ;
		$jumlahtagihannya=$tagihantotalbaris->jmlbulantagihan ;
		$loketnya= "Service Center ";
		$kasirnya= $this->session->userdata('nama');
//		$rincianbulannya= $tagihanbaris-> ;

// 		echo $num_rows;
// 		echo $tagihanbaris->idipkl;
		
		// ==============Header================
// 		$idipkl=$tagihanbaris->idipkl;
// 		$nama=$tagihanbaris->namapelanggan;
// 		$alamat=$tagihanbaris->namacluster + ' Blok ' + $tagihanbaris->blok + ' No. Kav ' + $tagihanbaris->nokav;
		// ==============Header================

		$pdf = new FPDF("L","cm", array(21.6, 9.32));
			
		// Get data dari tabel tagihan dengan caltagihan
		if ($num_rows > 0)
		{
			$pdf->AddPage();
			$pdf->SetMargins(1,1,1);
			$pdf->SetXY(0,0.8);
			$pdf->SetMargins(1,0.5,1);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(0  ,0.4,'No : '. $nomorkwitansinya,0,1, 'R');
			$pdf->Cell(0  ,0.4,'BUKTI PEMBAYARAN IPKL PERUMAHAN GRAND DUTA TANGERANG',0,1, 'C');
			$pdf->Cell(0  ,0.4,'' ,0,1, 'C');
			$pdf->Cell(2 ,0.2,' ',0,1,'C');
			
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(3.1 ,0.4,'ID IPKL',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(6 ,0.4, $tagihanbaris->idipkl ,0,0, 'L');
			
			$pdf->Cell(3.1 ,0.4,'NAMA',0,0, 'L');
			$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
			$pdf->Cell(6 ,0.4, $tagihanbaris->namapelanggan ,0,1, 'L');
			
			$pdf->Cell(3.1 ,0.4,'CLUSTER',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(6 ,0.4, $tagihanbaris->namacluster ,0,0, 'L');
				
			$pdf->Cell(3.1 ,0.4,'BLOK',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(3 ,0.4, $tagihanbaris->blok ,0,1, 'L');
				
			$pdf->Cell(3.1 ,0.4,'NO. KAVLING',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(3 ,0.4, $tagihanbaris->nokav ,0,1, 'L');
			
			$pdf->Cell(2 ,0.15,' ',0,1,'C');
			
			//$pdf->Ln();
			$pdf->Cell(0  ,0.4,'PERINCIAN TAGIHAN YANG HARUS DIBAYAR ',0,1, 'L');
			//$pdf->Ln();
			
			$pdf->Cell(2 ,0.15,' ',0,1,'C');
			
			$pdf->Cell(5.5 ,0.4,'Total Tagihan IPKL ',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(4 ,0.4, $this->separator($tagihantotalbaris->jmltagihan) ,0,0, 'R');
			
			$pdf->Cell(3.5 ,0.4,'Dibayar Tgl. ',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(3 ,0.4, date('j'.'/'.'m'.'/'.'Y') ,0,1, 'R');
				
			$pdf->Cell(5.5 ,0.4,'Total Denda ',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(4 ,0.4, $this->separator($tagihantotalbaris->jmldenda) ,0,0, 'R');
			
			$pdf->Cell(3.5 ,0.4,'Loket ',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(3 ,0.4, 'Service Center' ,0,1, 'R');
			
			$pdf->Cell(5.5 ,0.4,'Jumlah Total Yang Harus Dibayar ',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(4 ,0.4, $this->separator($tagihantotalbaris->jmltotal) ,0,0, 'R');
			
			$pdf->Cell(3.5 ,0.4,'Kasir ',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(3 ,0.4, $this->session->userdata('nama') ,0,1, 'R');
			
			$pdf->Cell(5.5 ,0.4,'Jumlah Bulan Tagihan ',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(4 ,0.4, $this->separator($tagihantotalbaris->jmlbulantagihan) ,0,1, 'R');
			
			$pdf->Cell(5.5 ,0.4,'Perincian Bulan ',0,0, 'L');
			$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
			$pdf->Cell(4 ,0.4, ' ' ,0,1, 'R');
			
 			$pdf->Cell(5.5 ,0.4, $rincianbulantahunnya ,0,0, 'L');			
			
 			$pdf->Cell(2 ,0.2,' ',0,1,'C');
			$pdf->Cell(2 ,0.1,' ',0,1,'C');
			$pdf->Cell(2 ,0.15,' ',0,1,'C');
			$pdf->Cell(0  ,0.3,'Grand Duta Menyatakan lembar transaksi ini sebagai bukti pembayaran yang sah.',0,1, 'L');
			$pdf->Cell(0  ,0.3,'Informasi : Telp. 55 73 0 888',0,1, 'L');
						
		}
		else
		{
		

		}
			
		//=========================== Akhir Cetak Pelanggan
		
  		$pdf->Output();
/* 		echo $nomorkwitansi;
		echo "<br>";
		echo $idipklnya; */
 		$usernya=$this->session->userdata('user_id');
 		

		$nokwit=$this->Penagihan_model->insert_kwitansi($nomorkwitansinya,
						$idipklnya,
						$namanya,
						$clusternya,
						$bloknya,
						$nokavlingnya,
						$totaltagihannya,
						$totaldendanya,
						$jumlahtotalnya,
						$jumlahtagihannya,
						$loketnya,
						$kasirnya,
						$rincianbulantahunnya,
						$idcarabayarnya,
						$idlewatbayarnya);
		
 		$upd_lunas=$this->Penagihan_model->update_tagihan_lunas($idipklnya,$usernya,$nomorkwitansinya)->result();
		
/* 		$kwit=$this->Penagihan_model->insert_kwit($nomorkwitansi,$tagihanbaris->idipkl,$tagihanbaris->namapelanggan,$tagihanbaris->namacluster, $tagihanbaris->blok,
				$tagihanbaris->nokav, $tagihantotalbaris->jmltagihan, $tagihantotalbaris->jmldenda, $tagihantotalbaris->jmltotal,
				$tagihantotalbaris->jmlbulantagihan, now(), $this->session->userdata('user_id'), $this->session->userdata('user_id'), $rincianbulantahun); */



	}
	
}
