<?php
/**
 * Infoplg Class
 *
 * @author	Trias Bratakusuma <brata@pdamtkr.co.id>
 */
class Infoplg extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Info_model', '', TRUE);
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('uri');
		$this->load->helper('form');
		$this->load->helper('html');		
		$this->load->library('pagination');
		$this->load->library('table');
		$this->load->helper('datecbo');			
	}
	
	//var $limit = 10;
	var $title = 'Pelanggan';
	
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
		$data['h2_title'] = 'Informasi Pelanggan';
		
		$posisi = $this->session->userdata('posisi');

			Switch($posisi){
				case "Operator Loket":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaloket.php';
					break;
				case "Administrator":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamaadmin.php';
					break;
				case "Cicilan":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamacicilan.php';
					break;
				case "Operator Stand Meter":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaopsm.php';
					break;
				case "Pembatalan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamapembatalan.php';
					break;
				case "Supervisor":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamaspv.php';
					break;
				case "Services":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaservices.php';
					break;
				case "Yan-Gan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamayg.php';
					break;
			}
		
		$data['main_view'] = 'info/infoplg_view';
		$data['left_view'] = 'menuinfo.php';
		$data['form_action'] = 'infoplg/caripelanggan';
		$data['user'] = $this->session->userdata('username');
		$data['posisi'] = $this->session->userdata('posisi');
		$data['tgl'] = date('j'.'/'.'m'.'/'.'Y');
		IF($this->session->userdata('posisi') == "Operator Loket"){
			$data['loket'] = $this->session->userdata('loket');
		} 
		else
		{
			$data['loket'] = "Aplikasi Non Loket";	
		}
		
		$this->load->view('template', $data);
		
	}
	
	function caripelanggan($kriteria='')
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Informasi Pelanggan';
		
		$posisi = $this->session->userdata('posisi');

			Switch($posisi){
				case "Operator Loket":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaloket.php';
					break;
				case "Administrator":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamaadmin.php';
					break;
				case "Cicilan":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamacicilan.php';
					break;
				case "Operator Stand Meter":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaopsm.php';
					break;
				case "Pembatalan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamapembatalan.php';
					break;
				case "Supervisor":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamaspv.php';
					break;
				case "Services":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaservices.php';
					break;
				case "Yan-Gan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamayg.php';
					break;
			}
		
		$data['main_view'] = 'info/infoplghasil_view';
		$data['left_view'] = 'menuinfo.php';
		$data['form_action'] = 'infoplg/caripelanggan';
		$data['user'] = $this->session->userdata('username');
		$data['posisi'] = $this->session->userdata('posisi');
		$data['tgl'] = date('j'.'/'.'m'.'/'.'Y');
		IF($this->session->userdata('posisi') == "Operator Loket"){
			$data['loket'] = $this->session->userdata('loket');
		} 
		else
		{
			$data['loket'] = "Aplikasi Non Loket";	
		}
		
		$kriteria = $this->input->post('kriteria');
		$parameter = $this->input->post('parameter');
		
		if ($parameter=="N")
			{
			$pelanggan = $this->Info_model->get_pelanggan_by_nama($kriteria);
			$pelangganhasil=$pelanggan->result();
			$num_rows=$pelanggan->num_rows();
			$pelangganbaris=$pelanggan->row();	
			}
		else
			{
			$pelanggan = $this->Info_model->get_pelanggan_by_alamat($kriteria);
			$pelangganhasil=$pelanggan->result();
			$num_rows=$pelanggan->num_rows();
			$pelangganbaris=$pelanggan->row();				
			}
		
	
		if ($num_rows > 0)
		{

			if ($num_rows == 1)
			{
				// set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
						  'row_alt_start'=>'<tr class="zebra">',
						  'row_alt_end'=>'</tr>'
					  	);
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('ID IPKL','NAMA','ALAMAT','GOL','STATUS','AKSI');
								
				$this->table->add_row($pelangganbaris->idipkl, $pelangganbaris->nama, $pelangganbaris->blok + ' ' + $pelangganbaris->nokav, $pelangganbaris->kdgol,$pelangganbaris->status,anchor('infoplg/detail/'.$pelangganbaris->idipkl, img(array('src'=>'/asset/images/btndetil.jpg','Text'=>'Detil'))));
				
				$data['table'] = $this->table->generate();
			}
			else
			{
				// set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
						  'row_alt_start'=>'<tr class="zebra">',
						  'row_alt_end'=>'</tr>'
					  	);
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('ID IPKL','NAMA','ALAMAT','GOL','STATUS','AKSI');
								
				foreach ($pelangganhasil as $row)
				{
					$this->table->add_row($row->idipkl, $row->nama, $row->blok + ' ' +  $row->nokav , $row->kdgol, $row->status,anchor('infoplg/detail/'.$row->idipkl, img(array('src'=>'/asset/images/btndetil.jpg','Text'=>'Detil'))) .' '.anchor('infoplg/tagihan/'.$row->idipkl, img(array('src'=>'/asset/images/btntagihan.jpg','Text'=>'Tagihan'))));
				}
				
				$data['table'] = $this->table->generate();
			}
		}
		else
		{
			$this->session->set_flashdata('message', 'Data tidak ditemukan!');
			redirect('info');	
		}
		
		$this->load->view('template', $data);
		
	}
	
	function detail()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Informasi Detil Pelanggan';
		
		$posisi = $this->session->userdata('posisi');

			Switch($posisi){
				case "Operator Loket":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaloket.php';
					break;
				case "Administrator":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamaadmin.php';
					break;
				case "Cicilan":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamacicilan.php';
					break;
				case "Operator Stand Meter":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaopsm.php';
					break;
				case "Pembatalan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamapembatalan.php';
					break;
				case "Supervisor":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamaspv.php';
					break;
				case "Services":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaservices.php';
					break;
				case "Yan-Gan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamayg.php';
					break;
			}
		
		$data['main_view'] = 'info/detilplg_view';
		$data['left_view'] = 'menuinfo.php';
//		$data['form_action'] = 'infoplg/caripelanggan';
		$data['user'] = $this->session->userdata('username');
		$data['posisi'] = $this->session->userdata('posisi');
		$data['tgl'] = date('j'.'/'.'m'.'/'.'Y');
		IF($this->session->userdata('posisi') == "Operator Loket"){
			$data['loket'] = $this->session->userdata('loket');
		} 
		else
		{
			$data['loket'] = "Aplikasi Non Loket";	
		}
		
		$pelnosambungan = $this->uri->segment(3);
		//echo $pelnosambungan;
		
		$pelanggan = $this->Info_model->get_detilplg($pelnosambungan);
		//$pelangganhasil=$pelanggan->result();
		$num_rows=$pelanggan->num_rows();
		$pelangganbaris=$pelanggan->row();
		
//		echo $pelangganbaris->PelNoSambungan;
//		echo $pelangganbaris->PelNamaPelanggan;
//		echo $pelangganbaris->PelKDGolongan;
		
		
		$data['default']['nosl']=$pelangganbaris->idipkl;
		$data['default']['nama']=$pelangganbaris->nama;
		$data['default']['alamat']=$pelangganbaris->blok +' No. Kav. ' + $pelangganbaris->nokav;
		$data['default']['wil']=$pelangganbaris->nohp;
		$data['default']['kdgol']=$pelangganbaris->kdgol;
		$data['default']['tglpasang']=$pelangganbaris->email;
		$data['default']['diapipa']=$pelangganbaris->status;
		
		$this->load->view('template', $data);
	}
	
	function tagihan()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Informasi Detil Pelanggan';
		
		$posisi = $this->session->userdata('posisi');

			Switch($posisi){
				case "Operator Loket":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaloket.php';
					break;
				case "Administrator":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamaadmin.php';
					break;
				case "Cicilan":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamacicilan.php';
					break;
				case "Operator Stand Meter":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaopsm.php';
					break;
				case "Pembatalan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamapembatalan.php';
					break;
				case "Supervisor":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamaspv.php';
					break;
				case "Services":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaservices.php';
					break;
				case "Yan-Gan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamayg.php';
					break;
			}
		
		$data['main_view'] = 'info/infotag_print';
		$data['left_view'] = 'menuinfo.php';
		$data['form_action'] = base_url() .'index.php/infotag/cetak_infotag';
		$data['user'] = $this->session->userdata('username');
		$data['posisi'] = $this->session->userdata('posisi');
		$data['tgl'] = date('j'.'/'.'m'.'/'.'Y');
		IF($this->session->userdata('posisi') == "Operator Loket"){
			$data['loket'] = $this->session->userdata('loket');
		} 
		else
		{
			$data['loket'] = "Aplikasi Non Loket";	
		}
		
		$nosl = $this->uri->segment(3);
		$tahun = date('Y');
		
				$tagihan = $this->Info_model->get_tagihan_info($nosl,$tahun);
		$tagihanhasil=$tagihan->result();
		$num_rows=$tagihan->num_rows();
		$tagihanbaris=$tagihan->row();
		
		$data['default']['nosl']=$tagihanbaris->TagihanNoSambungan;
		$data['default']['nama']=$tagihanbaris->TagihanNamaPelanggan;
		$data['default']['alamat']=$tagihanbaris->TagihanAlamatPelanggan;
		$data['default']['tahun']=$tagihanbaris->TagihanTahunTagihan;
		$data['default']['tglproses']=date('j'.'/'.'m'.'/'.'Y');;
		
		// set table template for zebra row
		$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
					  'row_alt_start'=>'<tr class="zebra">',
					  'row_alt_end'=>'</tr>');
		$this->table->set_template($tmpl);
				
		// set table header
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('BULAN','TAHUN','M. AWAL','M. AKHIR','M3','TAGIHAN','TGL BAYAR','LOKET');
								
		foreach ($tagihanhasil as $row)
			{
			$this->table->add_row($row->TagihanBulanTagihan, $row->TagihanTahunTagihan, $this->separator($row->TagihanAngkaMAwal) , $this->separator($row->TagihanAngkaMAkhir),$this->separator($row->TagihanJumPakai),$this->separator($row->TagihanTagihanBulan),$row->TagihanTanggalBayar,$row->Tagihankodeloket);
			}
				
		$data['table'] = $this->table->generate();
				
		$this->load->view('template', $data);	
		
	}
	
	function separator($num, $suffix = '')
	{
 		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
 
		return $result ;
	}
		
		
		
}
// END Rekap Class

/* End of file rekap.php */
/* Location: ./system/application/controllers/rekap.php */