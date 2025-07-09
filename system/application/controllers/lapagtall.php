<?php
/**
 * Rekap Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Lapagtall extends Controller {
	/**
	 * Constructor
	 */
	function Lapagtall()
	{
		parent::Controller();
		$this->load->model('Master_model', '', TRUE);
		
		// Load to_excel_pi plugins
		$this->load->plugin('to_excel');
		$this->load->helper('url');
	}
	
	var $title = 'master';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menjalankan fungsi main()
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->headers();
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
		$data['h2_title'] = 'Data Master';
		$data['main_view'] = 'master/master';
		$data['left_view'] = 'menumaster.php';
		$data['form_action'] = site_url('master/get_anggota');
				
		$this->load->view('template', $data);
	}
	
	function hello_world()
	{
		$this->load->library('cezpdf');

		$this->cezpdf->ezText('Hello World', 12, array('justification' => 'center'));
		$this->cezpdf->ezSetDy(-10);

		$content = 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog.
					Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz, vex nymphs.';

		$this->cezpdf->ezText($content, 10);

		$this->cezpdf->ezStream();
	}
	
	
	function tables()
	{
		$this->load->library('cezpdf');

		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		
		$col_names = array(
			'name' => 'Name',
			'phone' => 'Phone Number',
			'email' => 'E-mail Address'
		);
		
		$this->cezpdf->ezTable($db_data, $col_names, 'Contact List', array('width'=>550));
		$this->cezpdf->ezStream();
	}

	
	function headers()
	{
		$this->load->library('cezpdf');
		$this->load->helper('pdf');
		
		prep_pdf(); // creates the footer for the document we are creating.

		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
		$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
		$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		$col_names = array(
			'name' => 'Name',
			'phone' => 'Phone Number',
			'email' => 'E-mail Address'
		);
		
		$this->cezpdf->ezTable($db_data, $col_names, 'Daftar Kontak', array('width'=>550));
		$this->cezpdf->ezStream();
	}

	
//	/**
//	 * Mendapatkan data rekap absensi dari database, kemudian menampilkan di halaman
//	 */
//	function get_anggota($nosl = 0)
//	{		
//		$data['h2_title'] = 'Data Master';
//		$data['main_view'] = 'master/master';
//		$data['form_action'] = site_url('master/get_anggota');
//		
//		$nosl = $this->input->post('nosl');
//		
//		// Jumlah data tagihan
//		$query_anggota = $this->Master_model->get_anggota_all();
//		$anggota = $query_anggota->result();
//		$num_rows = $query_anggota->num_rows();
//		$baris = $query_anggota->row();		
//		// cek input parameter fungsi get_rekap()
//		//if ( ! ($nosl == 0))
//		//{
//			$data['nosl'] = $nosl;
//			$data['nama'] = $tagihanbaris->TagihanNamaPelanggan;
//			$data['alamat'] = $tagihanbaris->TagihanAlamatPelanggan;
//			$data['wilayah'] = $tagihanbaris->TagihanKDWilayah;
//			$data['tarif'] = $tagihanbaris->TagihanKDGolongan;
//			//$data['dia'] = $tagihan->;
//						
//			// jika query > 0
//			if ($num_rows > 0)
//			{
//				// set table template for zebra row
//				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
//							  'row_alt_start'=>'<tr class="zebra">',
//							  'row_alt_end'=>'</tr>'
//							  );
//				$this->table->set_template($tmpl);
//				
//				// set table header
//				$this->table->set_empty("&nbsp;");
//				$this->table->set_heading('Tahun','Bulan','Akhir','Awal','M3','Biaya Air','Bi. Adm','Bi. Pemel','Tagihan','Denda', 'Total');
//				//$i = 0;
//				$grandtotal = 0;
//				
//				foreach ($tagihan as $row)
//				{
//					$TagihanAir = $row->TagihanJumBayar+$row->TagihanPemeliharaan+$row->TagihanBiayaAdministrasi;
//					$Denda = 0;
//					$Total = $TagihanAir + $Denda;
//					
//					//print_r($Total);
//					
//					$this->table->add_row($row->TagihanTahunTagihan, $row->TagihanBulanTagihan, $row->TagihanAngkaMAwal, $row->TagihanAngkaMAkhir, $row->TagihanJumPakai, $row->TagihanJumBayar, $row->TagihanPemeliharaan, $row->TagihanBiayaAdministrasi,$TagihanAir,$Denda, $Total);
//					$grandtotal = $Total + $grandtotal;
//				}
//				$data['grandtotal'] = $grandtotal;
//				$bayar = 'bayar/'.$nosl;
//				//$bayar = site_url('transaksi/bayar/'.$nosl);
//				$data['bayar'] = $bayar;						
//				$data['table'] = $this->table->generate();
//				
//				//$data['link'] = array('link_add' => anchor("transaksi/download/$nosl",'download', array('class' => 'excel')));
//				//$data['link'] = array('link_add' => anchor("transaksi/cetak/$nosl",'Bayar', array('class' => 'excel')));
//			}
//			// jika query < 0
//			else
//			{
//				$query_tagihan = $this->Transaksi_model->get_transaksilunas($nosl);
//				$tagihan = $query_tagihan->result();
//				$num_rows = $query_tagihan->num_rows();
//				$tagihanbaris = $query_tagihan->row();	
//				
//				$tglbayar = $tagihanbaris->TagihanTanggalBayar;
//				print_r($tglbayar);
//								
//				if ($num_rows > 0)
//				{
//				$this->session->set_flashdata('message', 'Rekening telah dibayarkan tanggal ' . $tglbayar .'!');
//				redirect('transaksi');
//				}
//				else
//				{
//				$this->session->set_flashdata('message', 'Data tidak ditemukan!');
//				redirect('transaksi');				
//				}
//			}
//			
//			// Load view
//			$this->load->view('template', $data);
//			//}
//		//else
//		//{
//		//	redirect('transaksi');
//		//}
//	}
//				
//	// Download excel
//	function download($nosl)
//	{
//		$file_name = 'transaksi';
//		$query = $this->Transaksi_model->get_transaksi($nosl);
//		to_excel($query, $file_name);
//	}
//	
//	function bayar($nosl)
//	{
//		//Cetak Rekening Per Lembar
//		$query_tagihan = $this->Transaksi_model->get_transaksi($nosl);
//		$tagihan = $query_tagihan->result();
//		
//		foreach ($tagihan as $row)
//		{
//			$nosl = $row->TagihanNoSambungan;
//			$nama = $row->TagihanNamaPelanggan;
//			$alamat = $row->TagihanAlamatPelanggan;
//			$wilayah = $row->TagihanKDWilayah;
//			$gol = $row->TagihanKDGolongan;
//			$tahun = $row->TagihanTahunTagihan;
//			$bulan = $row->TagihanBulanTagihan;
//			$dia = $row->TagihanDiameter;
//			$mawal = $row->TagihanAngkaMAwal;
//			$makhir = $row->TagihanAngkaMAkhir;
//			$m3 = $row->TagihanJumPakai;
//			$biair = $row->TagihanJumBayar;
//			$bipemel = $row->TagihanPemeliharaan;
//			$biadmin = $row->TagihanBiayaAdministrasi;	
//			$bidenda = 0;
//			$total = $biair + $bidenda+$bipemel+$biadmin;
//					
//			$this->cetakrekening($bulan,$tahun,$nosl,$nama,$alamat,$wilayah,$gol,$dia,$mawal,$makhir,$m3,$biair,$biadmin,$bipemel,$bidenda,$total);
//		}
//		
//		print_r($nosl);	
//		print_r("<br>");	
//		$this->updatelunas($nosl);				
//	}
//	
//	function updatelunas($nosl)
//	{
//		$query_lunas = $this->Transaksi_model->upd_lunas($nosl);
//		$lunas = $query_lunas->result();
//	}		
//	
//	function cetakrekening($bulan,$tahun,$nosl,$nama,$alamat,$wilayah,$gol,$dia,$mawal,$makhir,$m3,$biair,$biadmin,$bipemel,$bidenda,$total)
//	{
//				print_r("=============");
//				print_r("<br>");
//				print_r($bulan);
//				print_r("<br>");
//				print_r($tahun);
//				print_r("<br>");
//				print_r("======");
//				print_r("<br>");				
//				print_r($nosl);
//				print_r("<br>");
//				print_r($nama);
//				print_r("<br>");
//				print_r("======");
//				print_r("<br>");	
//				print_r($alamat);
//				print_r("<br>");
//				print_r($wilayah);
//				print_r("<br>");
//				print_r("======");
//				print_r("<br>");	
//				print_r($gol);
//				print_r("<br>");
//				print_r($dia);
//				print_r("<br>");
//				print_r("======");
//				print_r("<br>");					
//				print_r($mawal);
//				print_r("<br>");
//				print_r($makhir);
//				print_r("<br>");
//				print_r("======");
//				print_r("<br>");	
//				print_r($biair);
//				print_r("<br>");
//				print_r($biadmin);
//				print_r("<br>");
//				print_r("======");
//				print_r("<br>");	
//				print_r($bipemel);
//				print_r("<br>");
//				print_r($bidenda);
//				print_r("<br>");
//				print_r("======");
//				print_r("<br>");	
//				print_r($total);
//				print_r("<br>");
//				print_r("=============");
//				print_r("<br>");
//	}
}
// END Rekap Class

/* End of file rekap.php */
/* Location: ./system/application/controllers/rekap.php */