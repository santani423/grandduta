<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cetak_rekap_cluster extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->helper('datecbo');
        $this->load->library('FPDF_AutoWrapTableRekapCluster');
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
				
			$data['action']  = 'cetak_rekap_cluster/cetaklaporan';
				
			// set judul halaman
			$data['judulpage'] = "Cetak Rekap Tagihan Semua Cluster";
			$data['page'] = "cetak_rekap_cluster";
				
			$this->template->load('template','penagihan/cetakrekaptagihanallcluster_view',$data);
				
		}
	}
	
	public function cetaklaporan()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
		$this->load->model('Master_model');
		$this->load->model('Penagihan_model');
		
		$rekaptagihanallcluster = $this->Penagihan_model->get_rekap_tunggakan_allcluster();
		$data = $rekaptagihanallcluster->result();
		$datarow = $rekaptagihanallcluster->row();
		
		//pilihan
		$options = array(
				'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
				'destinationfile' => '', //I=inline browser (default), F=local file, D=download
				'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
				'orientation'=>'L' //orientation: P=portrait, L=landscape
		);
			
		$tabel = new FPDF_AutoWrapTableRekapCluster($data,$options);
		$tabel->printPDF();
	}
}