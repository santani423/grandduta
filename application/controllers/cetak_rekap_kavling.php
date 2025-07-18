<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cetak_rekap_kavling extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->helper('datecbo');
        $this->load->library('FPDF_AutoWrapTableRekapKavling');
		$this->load->library('table');		
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
			$this->load->model('Master_model');
				
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			// ambil data buat combo
			$data['isicluster'] = $this->Master_model->getClusterList();
			
			$data['action']  = 'cetak_rekap_kavling/cetaklaporan';
			
			// set judul halaman
			$data['judulpage'] = "Cetak Rekap Tagihan Kavling";
			$data['page'] = "cetak_rekap_kavling";
			
			$this->template->load('template','penagihan/cetakrekapkavling_view',$data);
		}
	}
	
	function cetaklaporan()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
		$this->load->model('Master_model');
		$this->load->model('Penagihan_model');
		
		$idclusternya=$this->input->post('cbocluster');
// 		echo $idclusternya;
		
		$rekaptagihankavling = $this->Penagihan_model->get_tunggakan_kavling($idclusternya);
		$data = $rekaptagihankavling->result();
		$datarow = $rekaptagihankavling->row();
		
		//pilihan
		$options = array(
				'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
				'destinationfile' => '', //I=inline browser (default), F=local file, D=download
				'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
				'orientation'=>'L' //orientation: P=portrait, L=landscape
		);
			
		$tabel = new FPDF_AutoWrapTableRekapKavling($data,$options);
		$tabel->printPDF();
	}
	
}