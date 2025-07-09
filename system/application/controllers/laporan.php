<?php
/**
 * Rekap Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Laporan extends Controller {
	/**
	 * Constructor
	 */
	function Laporan()
	{
		parent::Controller();
		$this->load->model('Laporan_model', '', TRUE);
		
		// Load to_excel_pi plugins
		$this->load->plugin('to_excel');
	}
	
	var $title = 'laporan';
	
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
		$data['h2_title'] = 'Laporan Penagihan';
		$data['main_view'] = 'laporan/laporan';
		$data['form_action'] = site_url('laporan/get_laporan');
		

		$this->load->view('template', $data);
	}
	
	/**
	 * Mendapatkan data rekap absensi dari database, kemudian menampilkan di halaman
	 */
	function get_laporan($tgl = '')
	{		
		$data['h2_title'] = 'Laporan Penagihan';
		$data['main_view'] = 'laporan/laporan';
		$data['form_action'] = site_url('laporan/get_laporan');
		
		$tgl = $this->input->post('tanggal');
		
		$tahun = substr($tgl,-4);
		$bulan = substr($tgl,0,2);
		$tanggal = substr($tgl,-7,2);
		
		//print_r($tgl);
		//print_r($tahun);
		//print_r($bulan);
		//print_r($tanggal);
		
		// Jumlah data tagihan
		$query_lpp = $this->Laporan_model->get_laporan($tahun,$bulan,$tanggal);
		$lpp = $query_lpp->result();
		$num_rows = $query_lpp->num_rows();
		
		//print_r($num_rows);
		
			//jika query > 0
			if ($num_rows > 0)
			{
				//set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
							  'row_alt_start'=>'<tr class="zebra">',
							  'row_alt_end'=>'</tr>'
							  );
				$this->table->set_template($tmpl);
				
			// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('No Sambung','Nama','Alamat','Total Tagihan');
				//$i = 0;
				$grandtotal = 0;
				
				foreach ($lpp as $row)
				{
					
					//print_r($Total);
					$grandtotal = $grandtotal +  $row->Total;
					$this->table->add_row($row->TagihanNoSambungan, $row->TagihanNamaPelanggan, $row->TagihanAlamatPelanggan, $row->Total);
				}
				$data['grandtotal'] = $grandtotal;
				$data['table'] = $this->table->generate();
				
				//$data['link'] = array('link_add' => anchor("transaksi/download/$nosl",'download', array('class' => 'excel')));
				//$data['link'] = array('link_add' => anchor("transaksi/cetak/$nosl",'Bayar', array('class' => 'excel')));
			}
			// jika query < 0
			else
			{
				$this->session->set_flashdata('message', 'Data tidak ditemukan!');
				redirect('laporan');
			}
			
			// Load view
			$this->load->view('template', $data);
			

	}

}
// END Rekap Class

/* End of file rekap.php */
/* Location: ./system/application/controllers/rekap.php */