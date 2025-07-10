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
    $this->load->model('usermodel');
    $this->load->model('Penagihan_model');

    $idipkl = $this->input->post('idipkl');
    $blok   = $this->input->post('blok');
    $nokav  = $this->input->post('nokav');

    // Ambil data pelanggan
    if (empty($idipkl)) {
        $pelanggan = $this->Penagihan_model->get_pelanggan_by_blok_kavling($blok, $nokav);
    } else {
        $pelanggan = $this->Penagihan_model->get_pelanggan_perpelanggan($idipkl);
    }

    $pelangganbaris = $pelanggan->row();

    if (!$pelangganbaris) {
        show_error('Data pelanggan tidak ditemukan berdasarkan input yang diberikan.');
    }

    $idipkl = $pelangganbaris->idipkl; // overwrite untuk ambil data tagihan

    // Ambil tagihan dan alamat
    $tagihan      = $this->Penagihan_model->get_tagihan_perid($idipkl);
    $tagihantotal = $this->Penagihan_model->get_totaltagihan_perid($idipkl)->row();
    $alamatdata   = $this->Penagihan_model->get_alamat_perid($idipkl)->row();

    // Jika data alamat kosong, buat dummy object kosong
    if (!$alamatdata) {
        $alamatdata = (object)[];
    }

    // Fungsi safe get untuk PHP 5.6
    function safe_get($obj, $prop, $default = '-') {
        return isset($obj->$prop) && trim($obj->$prop) !== '' ? $obj->$prop : $default;
    }

    // Gunakan safe data
    $alamat = (object)[
        'alamatktp'     => safe_get($alamatdata, 'alamatktp'),
        'namakecamatan' => safe_get($alamatdata, 'namakecamatan'),
        'namakabupaten' => safe_get($alamatdata, 'namakabupaten'),
        'nohp'          => safe_get($alamatdata, 'nohp'),
        'notelpon'      => safe_get($alamatdata, 'notelpon'),
    ];

    $data = $tagihan->result();

    // Mulai cetak PDF
    $pdf = new FPDF("P", "cm", array(21, 29.7));
    $pdf->AddPage();
    $pdf->SetMargins(1, 1, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(20, 0.4, 'Perumahan Grand Duta Tangerang', 0, 0, 'L');
    $pdf->Line(1, 1.5, 20, 1.5);

    $pdf->SetXY(1, 2);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(20, 0.4, 'SURAT TAGIHAN IURAN PEMELIHARAAN KEBERSIHAN LINGKUNGAN (IPKL)', 0, 1, 'C');
    $pdf->Cell(20, 0.4, 'PERUMAHAN GRAND DUTA TANGERANG', 0, 1, 'C');

    $pdf->SetXY(1, 3.5);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(3, 0.4, 'IDIPKL', 0, 0, 'L');
    $pdf->Cell(0.5, 0.4, ' : ', 0, 0, 'C');
    $pdf->Cell(2, 0.4, $pelangganbaris->idipkl, 0, 0, 'L');
    $pdf->Cell(2, 0.4, 'Nama', 0, 0, 'L');
    $pdf->Cell(0.5, 0.4, ' : ', 0, 0, 'C');
    $pdf->Cell(6.5, 0.4, $pelangganbaris->namapelanggan, 0, 1, 'L');

    $pdf->Cell(3, 0.4, 'Alamat ', 0, 0, 'L');
    $pdf->Cell(0.5, 0.4, ' : ', 0, 0, 'C');
    $pdf->Cell(6.5, 0.4, 'Cluster ' . $pelangganbaris->namacluster . ' Blok : ' . $pelangganbaris->blok . ' No : ' . $pelangganbaris->nokav, 0, 1, 'L');

    $pdf->Cell(3, 0.4, 'Status Huni', 0, 0, 'L');
    $pdf->Cell(0.5, 0.4, ' : ', 0, 0, 'C');
    $pdf->Cell(3.5, 0.4, $pelangganbaris->namahuni, 0, 1, 'L');

    $pdf->Cell(3, 0.4, 'Iuran Per Bulan', 0, 0, 'L');
    $pdf->Cell(0.5, 0.4, ' : ', 0, 0, 'C');
    $pdf->Cell(3.5, 0.4, $this->separator($pelangganbaris->tarif), 0, 1, 'L');

    $pdf->Ln();
    $pdf->Cell(20, 0.4, 'Berdasarkan catatan Kami, Bapak/Ibu/Sdr. mempunyai tagihan IPKL sebagai berikut :', 0, 1, 'L');
    $pdf->Ln(0.2);

    // Header tabel
    $header = [
        ["label" => "TAHUN", "length" => 4, "align" => "L"],
        ["label" => "BULAN", "length" => 4, "align" => "L"],
        ["label" => "JUMLAH TAGIHAN", "length" => 6, "align" => "R"]
    ];

    $pdf->SetFillColor(224, 235, 255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('');

    foreach ($header as $kolom) {
        $pdf->Cell($kolom['length'], 0.5, $kolom['label'], 1, 0, $kolom['align'], true);
    }
    $pdf->Ln();

    $fill = false;
    foreach ($data as $baris) {
        $pdf->Cell($header[0]['length'], 0.5, $baris->tahun, 1, 0, $header[0]['align'], $fill);
        $pdf->Cell($header[1]['length'], 0.5, $baris->bulan, 1, 0, $header[1]['align'], $fill);
        $pdf->Cell($header[2]['length'], 0.5, $this->separator($baris->tagihan), 1, 1, $header[2]['align'], $fill);
        $fill = !$fill;
    }

    // Grand Total
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Ln(0.2);
    $pdf->Cell(3, 0.4, 'GRAND TOTAL ', 0, 0, 'L');
    $pdf->Cell(1.5, 0.4, ' : Rp. ', 0, 0, 'C');
    $pdf->Cell(3.5, 0.4, $this->separator($tagihantotal->total), 0, 1, 'L');

    // Catatan
    $pdf->SetFont('Arial', '', 11);
    $pdf->Ln(0.2);
    $pdf->MultiCell(20, 0.4, 
        "1. Pembayaran Tagihan di atas membantu kami dalam upaya peningkatan keamanan, kenyamanan dan kebersihan lingkungan. Tagihan jatuh tempo tgl 10 setiap bulannya.\n" .
        "2. Mohon diselesaikan selambat-lambatnya 7 (tujuh) hari setelah tanggal surat pemberitahuan ini.\n" .
        "3. Pembayaran dapat dilakukan ke Kantor Estate Management atau hubungi (021) 55730888.\n" .
        "4. Jika tagihan tidak dibayar tepat waktu, akan ditempelkan stiker 'IPKL Menunggak' di rumah.\n" .
        "5. Surat ini dicetak sistem, tidak memerlukan tanda tangan.\n" .
        "6. Bila ada perbedaan catatan, mohon lampirkan bukti pembayaran."
    );

    $pdf->Ln(0.5);
    $pdf->Cell(6, 0.4, 'Dicetak Tanggal : ', 0, 0, 'C');
    $pdf->Cell(6, 0.4, date('d-M-Y'), 0, 1, 'C');

    $pdf->Ln(0.3);
    $pdf->Cell(20, 0.4, str_repeat('_', 90), 0, 1, 'L');
    $pdf->Cell(20, 0.4, 'Mohon konfirmasi jika alamat alternatif tidak sesuai.', 0, 1, 'L');
    $pdf->Cell(20, 0.4, 'Hubungi kantor Estate Management atau Telp. (021) 55730888', 0, 1, 'L');

    $pdf->Ln(0.5);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(2, 0.4, 'Alamat', 0, 0, 'L');
    $pdf->Cell(0.5, 0.4, ':', 0, 0, 'C');
    $pdf->Cell(14.5, 0.4, $alamat->alamatktp, 0, 1, 'L');

    $pdf->Cell(2, 0.4, 'Kecamatan', 0, 0, 'L');
    $pdf->Cell(0.5, 0.4, ':', 0, 0, 'C');
    $pdf->Cell(14.5, 0.4, $alamat->namakecamatan, 0, 1, 'L');

    $pdf->Cell(2, 0.4, 'Kabupaten', 0, 0, 'L');
    $pdf->Cell(0.5, 0.4, ':', 0, 0, 'C');
    $pdf->Cell(14.5, 0.4, $alamat->namakabupaten, 0, 1, 'L');

    $pdf->Cell(2, 0.4, 'No. Telpon', 0, 0, 'L');
    $pdf->Cell(0.5, 0.4, ':', 0, 0, 'C');
    $pdf->Cell(14.5, 0.4, $alamat->nohp . ' / ' . $alamat->notelpon, 0, 1, 'L');

    $pdf->Output();
}

	
}
