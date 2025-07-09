<?php
/**
 * Rekap Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Info extends Controller {
	/**
	 * Constructor
	 */
	function Info()
	{
		parent::Controller();
		$this->load->model('Info_model', '', TRUE);
		
		// Load to_excel_pi plugins
		$this->load->plugin('to_excel');
	}
	
	var $title = 'info';
	
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
		$data['h2_title'] = 'Info Pelanggan';
		$data['main_view'] = 'info/info';
		$data['form_action'] = site_url('info/get_info');
				
		$this->load->view('template', $data);
	}
	
	/**
	 * Mendapatkan data rekap absensi dari database, kemudian menampilkan di halaman
	 */
	function get_info($namalkp = '')
	{		
		$data['h2_title'] = 'Info Pelanggan';
		$data['main_view'] = 'info/info';
		$data['form_action'] = site_url('info/get_info');
		
		$nama = $this->input->post('nama');
		$namalkp = "%". $nama . "%";
		//print_r($namalkp);
		
		// Jumlah data tagihan
		$query_nama = $this->Info_model->get_info($namalkp);
		$nama = $query_nama->result();
		$num_rows = $query_nama->num_rows();
		$namabaris = $query_nama->row();		
		// cek input parameter fungsi get_rekap()
		//if ( ! ($nosl == 0))
		//{
			//$data['nosl'] = $nosl;
			//$data['nama'] = $tagihanbaris->TagihanNamaPelanggan;
			//$data['alamat'] = $tagihanbaris->TagihanAlamatPelanggan;
			//$data['wilayah'] = $tagihanbaris->TagihanKDWilayah;
			//$data['tarif'] = $tagihanbaris->TagihanKDGolongan;
			//$data['dia'] = $tagihan->;
						
			// jika query > 0
			if ($num_rows > 0)
			{
				// set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
							  'row_alt_start'=>'<tr class="zebra">',
							  'row_alt_end'=>'</tr>'
							  );
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('No Sambung','Nama','Alamat');
				//$i = 0;
				//$grandtotal = 0;
				
				foreach ($nama as $row)
				{
					//$TagihanAir = $row->TagihanJumBayar+$row->TagihanPemeliharaan+$row->TagihanBiayaAdministrasi;
					//$Denda = 0;
					//$Total = $TagihanAir + $Denda;
					
					//print_r($Total);
					
					$this->table->add_row($row->PelNoSambungan, $row->PelNamaPelanggan, $row->PelAlamatPelanggan);
				}
				//$data['grandtotal'] = $grandtotal;
				//$bayar = 'bayar/'.$nosl;
				//$bayar = site_url('transaksi/bayar/'.$nosl);
				//$data['bayar'] = $bayar;						
				$data['table'] = $this->table->generate();
				
				//$data['link'] = array('link_add' => anchor("transaksi/download/$nosl",'download', array('class' => 'excel')));
				//$data['link'] = array('link_add' => anchor("transaksi/cetak/$nosl",'Bayar', array('class' => 'excel')));
			}
			// jika query < 0
			else
			{
				$this->session->set_flashdata('message', 'Data tidak ditemukan!');
				redirect('info');
			}
			
			// Load view
			$this->load->view('template', $data);
			//}
		//else
		//{
		//	redirect('transaksi');
		//}
	}

}
// END Rekap Class

/* End of file rekap.php */
/* Location: ./system/application/controllers/rekap.php */