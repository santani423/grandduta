<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cetak_tagihan_perplg extends CI_Controller
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
			
			$data['action']  = 'cetak_tagihan_perplg/querytagihan';
			
			// set judul halaman
			$data['judulpage'] = "Cetak Tagihan Per Pelanggan";
			
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
			$idipkl = $this->input->post('idipkl');
      		$blok   = $this->input->post('blok');
        	$nokav  = $this->input->post('nokav');
	
			// level untuk user ini
			$level = $this->session->userdata('level');
	
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
				
 			// $data['action']  = 'cetak_tagihan_perplg/cetak_surattagihan_perplg';
 			 // $data['action']  = 'penagihan/querytagihan';
			$data['action']  = 'cetak_tagihan_perplg/cetak_surattagihan_perplg';
				
			// set judul halaman
			$data['judulpage'] = "Cetak Tagihan Per Pelanggan";
			
// 			$pelanggan = $this->Master_model->get_one($id);
// 			$pelangganbaris = $pelanggan->result();
			
			// $pelangganbaris = $this->Penagihan_model->get_onepelanggan($id)->row();
		$pelangganbaris = $this->Penagihan_model->get_onepelanggan_multiup($idipkl, $blok, $nokav)->row();
			
				 if ($pelangganbaris) {
            $data['default']['idipkl'] = $pelangganbaris->ID_IPKL;
            $data['default']['nama'] = $pelangganbaris->NAMA_PELANGGAN;
            $data['default']['blok'] = $pelangganbaris->BLOK;
            $data['default']['nokav'] = $pelangganbaris->NO_KAVLING;
            $data['default']['namacluster'] = $pelangganbaris->NAMA_CLUSTER;

            $data['tagihans'] = $this->Penagihan_model->get_tagihan_belumlunas($pelangganbaris->ID_IPKL);
            $data['totalnya'] = $this->Penagihan_model->get_tagihantotal_belumlunas($pelangganbaris->ID_IPKL);
        } else {
            $this->session->set_flashdata('error', 'Data pelanggan tidak ditemukan');
            redirect('penagihan/formcari'); // ganti dengan halaman formulir pencarian
            return;
        }
			$this->template->load('template','penagihan/rinciancetaktagihan_view',$data);
		}
	}
	
	public function separator($num, $suffix = '')
	{
		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
	
		return $result ;
	}
	
	public function cetak_surattagihan_perplg()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
		$this->load->model('Penagihan_model');
		
		$idipkl=$this->input->post('idipkl');
                
		// Ambil Data Pelanggan Per Cluster
		$pelanggan=$this->Penagihan_model->get_pelanggan_perpelanggan($idipkl);
		$pelangganbaris=$pelanggan->row();
		
		$pdf = new FPDF("P","cm", array(21,29,7));
		
		$pdf->AddPage();
		$pdf->SetMargins(1,1,1);
		// 		$pdf->SetXY(1,0.8);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(20,0.4,'Perumahan Grand Duta Tangerang',0,0,'L');
		$pdf->Line(1,1.5, 20, 1.5);
			
		$pdf->SetXY(1, 2);
		$pdf->SetFont('Arial','B',13);
		$pdf->Cell(20,0.4,'SURAT TAGIHAN IURAN PEMELIHARAAN KEBERSIHAN LINGKUNGAN (IPKL)',0,1,'C');
		$pdf->Cell(20,0.4,'PERUMAHAN GRAND DUTA TANGERANG',0,1,'C');
			
		$pdf->SetXY(1, 3.5);
		$pdf->SetFont('Arial','',12);
			
		$pdf->Cell(3,0.4,'IDIPKL',0,0,'L');
		$pdf->Cell(0.5,0.4,' : ',0,0,'C');
		$pdf->Cell(2,0.4,$pelangganbaris->idipkl,0,0,'L');
		
		$pdf->Cell(2,0.4,'Nama',0,0,'L');
		$pdf->Cell(0.5,0.4,' : ',0,0,'C');
		$pdf->Cell(6.5,0.4,$pelangganbaris->namapelanggan,0,1,'L');
			
		$pdf->Cell(3,0.4,'Alamat ',0,0,'L');
		$pdf->Cell(0.5,0.4,' : ',0,0,'C');
		$pdf->Cell(6.5,0.4,'Cluster ' . $pelangganbaris->namacluster . ' Blok : ' . $pelangganbaris->blok . ' No : '. $pelangganbaris->nokav ,0,1,'L');
			
		$pdf->Cell(3,0.4,'Status Huni',0,0,'L');
		$pdf->Cell(0.5,0.4,' : ',0,0,'C');
		$pdf->Cell(3.5,0.4,$pelangganbaris->namahuni,0,1,'L');
                
                $pdf->Cell(3,0.4,'Iuran Per Bulan',0,0,'L');
		$pdf->Cell(0.5,0.4,' : ',0,0,'C');
		$pdf->Cell(3.5,0.4,$this->separator($pelangganbaris->tarif),0,1,'L');
			
		$pdf->Cell(20,0.4,'',0,1,'L');
		$pdf->Cell(20,0.4,'Berdasarkan catatan Kami, Bapak/Ibu/Sdr. mempunyai tagihan IPKL sebagai berikut :',0,1,'L');
		$pdf->Cell(20,0.2,'',0,1,'L');
			
		// 			=============== Tabel tagihan ==================
		$tagihan=$this->Penagihan_model->get_tagihan_perid($idipkl);
		$tagihantotal=$this->Penagihan_model->get_totaltagihan_perid($idipkl)->row();
		$alamat=$this->Penagihan_model->get_alamat_perid($idipkl)->row();
			
		$data=$tagihan->result();
			
		$pdf->SetFont('Arial','','11');
		$pdf->SetFillColor(255,0,0);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(128,0,0);
		
			
		$header = array(
				array("label"=>"TAHUN", "length"=>4, "align"=>"L"),
				array("label"=>"BULAN", "length"=>4, "align"=>"L"),
				array("label"=>"JUMLAH TAGIHAN", "length"=>6, "align"=>"R")
		);
			
		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
			
		foreach ($header as $kolom)
		{
			$pdf->Cell($kolom['length'], 0.5, $kolom['label'], 1, '0', $kolom['align'], true);
		}
		$pdf->Ln();
			
		$fill=FALSE;
			
		foreach ($data as $baris)
		{
			$i = 0;
			foreach ($baris as $cell)
			{
				$pdf->Cell($header[$i]['length'], 0.5, $cell, 1, '0', $kolom['align'], $fill);
				$i++;
			}
			$fill = !$fill;
			$pdf->Ln();
		}
		
		$pdf->SetFont('Arial','B','13');
		$pdf->Cell(20,0.2,'',0,1,'L');
		$pdf->Cell(3,0.4,'GRAND TOTAL ',0,0,'L');
		$pdf->Cell(1.5,0.4,' : Rp. ',0,0,'C');
		$pdf->Cell(3.5,0.4,$this->separator($tagihantotal->total),0,1,'L');
			
		$pdf->SetFont('Arial','','11');
		$pdf->Cell(20,0.2,'',0,1,'L');
		$pdf->Cell(5,0.4,'# CATATAN # ',0,1,'L');
		$pdf->Cell(20,0.4,'1. Pembayaran Tagihan di atas, membantu kami dalam upaya peningkatan keamanan,kenyamanan',0,1,'L');
		$pdf->Cell(20,0.4,'    dan kebersihan lingkungan. Tagihan jatuh tempo tgl 10 setiap bulannya.',0,1,'L');
		
		$pdf->Cell(20,0.4,'2. Kami berterimakasih apabila dapat diselesaikan selambat-lambatnya 7 (tujuh) hari setelah tanggal',0,1,'L');
		$pdf->Cell(20,0.4,'    surat pemberitahuan ini ',0,1,'L');
		
		$pdf->Cell(20,0.4,'3. Bapak/Ibu/Sdr. dapat melakukan pembayaran ke Kantor Pemasaran Grand Duta Loket Customer Service ',0,1,'L');
		$pdf->Cell(20,0.4,'    (Estate Management) atau menghubungi Kami di (021) 55 73 0 888.',0,1,'L');
			
                        $pdf->Cell(20,0.4,'4. Untuk Tagihan yang tidak di bayarkan atau melewati jatuh tempo kami akan melakukan pemasangan ',0,1,'L');
                        $pdf->Cell(20,0.4,'    sticker "IPKL Menunggak" di jendela kaca rumah untuk memberikan identitas kepada warga',0,1,'L');
						$pdf->Cell(20,0.4,'    agar petugas di lapangan tidak berkewajiban untuk melayani baik pengamanan dalam hal',0,1,'L');
						$pdf->Cell(20,0.4,'    pembukaan pintu pagar masuk dan keluar maupun kebersihan khususnya pengambilan sampah',0,1,'L');
                        $pdf->Cell(20,0.4,'    harian rumah tangga.',0,1,'L');
		//$pdf->Cell(20,0.4,'',0,1,'L');

			$pdf->Cell(20,0.4,'5. Surat tagihan iuran pemeliharaan kebersihan lingkungan di cetak secara sistem dan komputerisasi ',0,1,'L');
			$pdf->Cell(20,0.4,'    sehingga tidak membutuhkan tanda tangan',0,1,'L');
			//$pdf->Cell(20,0.4,'',0,1,'L');

			$pdf->Cell(20,0.4,'6. Apabila ada perbedaan catatan pembayaran yang telah di lakukan oleh warga kepada Estate ',0,1,'L');
			$pdf->Cell(20,0.4,'    Management mohon menunjukan /melampirkan bukti pembayaran',0,1,'L');
			$pdf->Cell(20,0.4,'',0,1,'L');
			
			$pdf->Cell(6,0.4,'Dicetak Tanggal : ',0,0,'C');
			$pdf->Cell(6,0.4,date('d-M-Y'),0,1,'C');
			
			/*
			$pdf->Cell(6,0.4,'Mengetahui',0,0,'C');								
			$pdf->Cell(6,0.4,' ',0,0,'C');
			$pdf->Cell(6,0.4,'Menyetujui',0,1,'C');
			
			$pdf->Cell(6,0.4,'Penghuni/Pemilik',0,0,'C');
			$pdf->Cell(6,0.4,'Pelaksana Tugas',0,0,'C');
			$pdf->Cell(6,0.4,'Manager',0,1,'C');
			
			$pdf->Cell(6,1.2,'',0,0,'C');
			$pdf->Cell(6,1.2,'',0,0,'C');
			$pdf->Cell(6,1.2,'',0,1,'C');

			$pdf->Cell(6,0.4,'__________________',0,0,'C');
			$pdf->Cell(6,0.4,'__________________',0,0,'C');
			$pdf->Cell(6,0.4,'Rini Inthalla',0,1,'C');
			*/

			$pdf->Cell(20,0.4,'________________________________________________________________________________',0,1,'L');
			$pdf->Cell(20,0.4,'Mohon di konfirmasi jika alamat alternatif tidak sesuai  dengan data saat ini mohon dapat menghubungi ke',0,1,'L');
			$pdf->Cell(20,0.4,'kantor kami Estate Management atau di nomor  telp.021 55730888',0,1,'L');
			$pdf->Cell(20,0.4,'',0,1,'L');

		$pdf->Cell(5,0.4,'# ALAMAT ALTERNATIF : ',0,1,'L');
		
		$pdf->SetFont('Arial','','10');
		$pdf->Cell(2,0.4,'Alamat',0,0,'L');
		$pdf->Cell(0.5,0.4,':',0,0,'C');
		$pdf->Cell(14.5,0.4,$alamat->alamatktp,0,1,'L');
		
		$pdf->Cell(2,0.4,'Kecamatan',0,0,'L');
		$pdf->Cell(0.5,0.4,':',0,0,'C');
		$pdf->Cell(14.5,0.4,$alamat->namakecamatan,0,1,'L');
			
		$pdf->Cell(2,0.4,'Kabupaten',0,0,'L');
		$pdf->Cell(0.5,0.4,':',0,0,'C');
		$pdf->Cell(14.5,0.4,$alamat->namakabupaten,0,1,'L');
			
		$pdf->Cell(2,0.4,'No. Telpon',0,0,'L');
		$pdf->Cell(0.5,0.4,':',0,0,'C');
		$pdf->Cell(14.5,0.4,$alamat->nohp . ' dan ' . $alamat->notelpon ,0,1,'L');
		
		$pdf->Output();
		
	}
	
}
