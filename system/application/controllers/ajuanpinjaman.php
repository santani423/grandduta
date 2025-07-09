<?php
/**
 * Rekap Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Ajuanpinjaman extends Controller {
	/**
	 * Constructor
	 */
	function Ajuanpinjaman()
	{
		parent::Controller();
		$this->load->model('Simpanpinjam_model', '', TRUE);
		
		// Load to_excel_pi plugins
		$this->load->plugin('to_excel');
		$this->load->library('fungsi');
	}
	
	var $title = 'Pengajuan Pinjaman';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menjalankan fungsi main()
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->main();
		}
		else
		{
			redirect('login');
		}
	}
	
	/**
	 * Menampilkan halaman utama rekap absen
	 */
	function main()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Pengajuan Pinjaman';
		$data['main_view'] = 'simpanpinjam/ajuanpinjaman';
		$data['left_view'] = 'menusimpanpinjam.php';
		$data['form_action'] = site_url('ajuanpinjaman/get_boleh_pinjam');
		$data['link'] = array('link_back' => anchor('ajuanpinjaman','kembali', array('class' => 'back')));
				
		$data['kategori'] = '';
		$this->load->view('template', $data);
	}
	
	/**
	 * Mendapatkan data rekap absensi dari database, kemudian menampilkan di halaman
	 */
	function get_boleh_pinjam($noanggota = 0)
	{		
		$data['title'] = $this->title;
		$data['h2_title'] = 'Pengajuan Pinjaman';
		$data['main_view'] = 'simpanpinjam/ajuanpinjaman';
		$data['left_view'] = 'menusimpanpinjam.php';
		$data['form_action'] = site_url('ajuanpinjaman/get_boleh_pinjam');
		$data['link'] = array('link_back' => anchor('ajuanpinjaman','kembali', array('class' => 'back')));
		
		$noanggota = $this->input->post('noanggota');
		
		// Ambil data anggota dan pinjamannya
		$query_anggota = $this->Simpanpinjam_model->get_anggota_boleh_pinjam($noanggota);
		$num_rows = $query_anggota->num_rows();  
		
		$query_anggotadata = $this->Simpanpinjam_model->get_anggota_by_id($noanggota);
		$query_anggotadatabaris = $query_anggotadata->row();
		
					
			// jika query > 0
			if ($num_rows > 0)
			{
				if ($num_rows == 1)
				{
					$query_klaspinjam = $this->Simpanpinjam_model->get_klasifikasi_pinjam($noanggota);
					//$anggota = $query_anggota->result();
					$num_rows = $query_klaspinjam->num_rows();
					$klaspinjambaris = $query_klaspinjam->row();
					
					$klaspinjaman = $klaspinjambaris->idklaspinjam;
					
					if ($klaspinjaman == '01')
					{
						$data['hak'] = 'Anda boleh pinjam, dengan langsung dipotong pinjaman sebrangan Anda';
						
						$data['noanggota'] = $query_anggotadatabaris->NoAnggota;
						$data['nama'] = $query_anggotadatabaris->Nama;
						$data['alamat'] = $query_anggotadatabaris->Alamat;
						$data['desa'] = $query_anggotadatabaris->NamaDesa;
						$data['kec'] = $query_anggotadatabaris->NamaKec;
						
						$data['kategori'] = $this->db->get('vw_klaspinjam');
						
						$ajuan = 'pengajuan/'.$noanggota.$this->input->post('kategori');
						$data['ajuan'] = $ajuan;
					//	$this->load->view('template', $data);
					}
					else
					{
						$data['hak'] = 'Anda boleh pinjam sebrangan';
						
						$data['noanggota'] = $query_anggotadatabaris->NoAnggota;
						$data['nama'] = $query_anggotadatabaris->Nama;
						$data['alamat'] = $query_anggotadatabaris->Alamat;
						$data['desa'] = $query_anggotadatabaris->NamaDesa;
						$data['kec'] = $query_anggotadatabaris->NamaKec;
						
						$kategori = '01';
						$this->db->where('id',$kategori);
						$data['kategori'] = $this->db->get('vw_klaspinjam');
						
						$ajuan = 'pengajuan/'.$noanggota.$this->input->post('kategori');
						$data['ajuan'] = $ajuan;
						
					//	$this->load->view('template', $data);
					}
				}
				else
				{
					$data['hak'] = 'Anda belum boleh pinjam, karena masih memiliki pinjaman';
					
					$data['kategori'] = '';					
//					$data['noanggota'] = $query_anggotadatabaris->NoAnggota;
//					$data['nama'] = $query_anggotadatabaris->Nama;
//					$data['alamat'] = $query_anggotadatabaris->Alamat;
//					$data['desa'] = $query_anggotadatabaris->NamaDesa;
//					$data['kec'] = $query_anggotadatabaris->NamaKec;
//					
//					$kategori = '01';
//					$this->db->where('id',$kategori);
//					$data['kategori'] = $this->db->get('vw_klaspinjam');
						
				//	$this->load->view('template', $data);
				}

			}
			// jika query < 0
			else
			{
				$data['hak'] = 'Anda boleh pinjam';
				
				$data['noanggota'] = $query_anggotadatabaris->NoAnggota;
				$data['nama'] = $query_anggotadatabaris->Nama;
				$data['alamat'] = $query_anggotadatabaris->Alamat;
				$data['desa'] = $query_anggotadatabaris->NamaDesa;
				$data['kec'] = $query_anggotadatabaris->NamaKec;
				
				$data['kategori'] = $this->db->get('vw_klaspinjam');
				
				$ajuan = 'pengajuan/'.$noanggota.$this->input->post('kategori');
				$data['ajuan'] = $ajuan;
				//$this->load->view('template', $data);
			}
			
			// Load view
			$this->load->view('template', $data);
	}

	function tampilkan_subkategori()
	{
		$kategori = $this->uri->segment(3);
		echo $kategori;
		$this->db->where('id',$kategori);
		$subkategori = $this->db->get('vw_klaspinjam');
		echo $this->fungsi->create_combobox('subkategori',$subkategori,'id','nama');
	}
					
	function bayar($nosl)
	{
		//Cetak Rekening Per Lembar
		$query_tagihan = $this->Transaksi_model->get_transaksi($nosl);
		$tagihan = $query_tagihan->result();
		
		foreach ($tagihan as $row)
		{
			$nosl = $row->TagihanNoSambungan;
			$nama = $row->TagihanNamaPelanggan;
			$alamat = $row->TagihanAlamatPelanggan;
			$wilayah = $row->TagihanKDWilayah;
			$gol = $row->TagihanKDGolongan;
			$tahun = $row->TagihanTahunTagihan;
			$bulan = $row->TagihanBulanTagihan;
			$dia = $row->TagihanDiameter;
			$mawal = $row->TagihanAngkaMAwal;
			$makhir = $row->TagihanAngkaMAkhir;
			$m3 = $row->TagihanJumPakai;
			$biair = $row->TagihanJumBayar;
			$bipemel = $row->TagihanPemeliharaan;
			$biadmin = $row->TagihanBiayaAdministrasi;	
			$bidenda = 0;
			$total = $biair + $bidenda+$bipemel+$biadmin;
					
			$this->cetakrekening($bulan,$tahun,$nosl,$nama,$alamat,$wilayah,$gol,$dia,$mawal,$makhir,$m3,$biair,$biadmin,$bipemel,$bidenda,$total);
		}
		
		print_r($nosl);	
		print_r("<br>");	
		$this->updatelunas($nosl);				
	}
	
	function updatelunas($nosl)
	{
		$query_lunas = $this->Transaksi_model->upd_lunas($nosl);
		$lunas = $query_lunas->result();
	}		
}
// END Rekap Class

/* End of file rekap.php */
/* Location: ./system/application/controllers/rekap.php */