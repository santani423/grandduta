<?php
/**
 * Infotag Class
 *
 * @author	Trias Bratakusuma <brata@pdamtkr.co.id>
 */
class Infotag extends CI_Controller {
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
		$this->load->helper('form');				
		$this->load->library('pagination');
		$this->load->library('table');
		$this->load->helper('datecbo');
		$this->load->library('FPDF_AutoWrapTable');		
	}
	
	var $title = 'Tagihan';
	
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
		$data['h2_title'] = 'Informasi Tagihan';
		
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
		
		$data['main_view'] = 'info/infotag_view';
		$data['left_view'] = 'menuinfo.php';
		$data['form_action'] = 'infotag/caritagihan';
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
	
	function separator($num, $suffix = '')
	{
 		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
 
		return $result ;
	}
	
	function caritagihan($nosl = '')
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Informasi Tagihan';
		
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
		$data['form_action'] = 'cetak_infotag';
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
		
		$nosl = $this->input->post('idipkl');
		$tahun = $this->input->post('cbotahun');
		
//		echo $nosl;
//		echo $tahun;		
		
		$tagihan = $this->Info_model->get_tagihan_info($nosl,$tahun);
		$tagihanhasil=$tagihan->result();
		$num_rows=$tagihan->num_rows();
		$tagihanbaris=$tagihan->row();
		
		if($num_rows==0)
		{
			$this->session->set_flashdata('message', 'Tidak ada Data Tagihan, Coba Pilih Tahun Lain!');
			redirect('infotag');				
		}
		else
		{
		
		$data['default']['idipkl']=$tagihanbaris->idipkl;
		$data['default']['nama']=$tagihanbaris->nama;
		$data['default']['alamat']='Blok ' . $tagihanbaris->blok . ' No. Kav. ' . $tagihanbaris->nokav;
		$data['default']['tahun']=$tagihanbaris->tahun;
		$data['default']['tglproses']=date('j'.'/'.'m'.'/'.'Y');
		
		// set table template for zebra row
		$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
					  'row_alt_start'=>'<tr class="zebra">',
					  'row_alt_end'=>'</tr>');
		$this->table->set_template($tmpl);
				
		// set table header
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('BULAN','GOL','TAGIHAN','LOKET','TGL BAYAR');
								
		foreach ($tagihanhasil as $row)
			{
			$this->table->add_row($row->bulan, $row->kdgol, $this->separator($row->tagihan),$row->loket ,$row->tanggalbayar);
			}
				
		$data['table'] = $this->table->generate();
		}
				
		$this->load->view('template', $data);		
	}
	
	function cetak_infotag()
	{
		$nosl = $this->input->post('idipkl');
		$tahun = $this->input->post('tahun');
		
		$tagihan = $this->Info_model->get_tagihan_info($nosl,$tahun);
		$data = $tagihan->result();
		$datarow = $tagihan->row();	

		//pilihan
		$options = array(
			'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
			'destinationfile' => '', //I=inline browser (default), F=local file, D=download
			'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
			'orientation'=>'P' //orientation: P=portrait, L=landscape
			);
			
		//head
		$isihead  = array(
			'nosambungan' => $datarow->idipkl, 
			'nama' => $datarow->nama, 
			'alamat'=>$datarow->blok + ' ' + $datarow->nokav,	
			'tahun'=>$datarow->tahun,	
			'proses'=> date('j'.'/'.'m'.'/'.'Y') 
			);

		$tabel = new FPDF_AutoWrapTable($data, $options,$isihead);
		$tabel->printPDF();
	}
}
// END Rekap Class

/* End of file rekap.php */
/* Location: ./system/application/controllers/rekap.php */