<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cetak_master_pelanggan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->helper('datecbo');
        $this->load->library('FPDF_AutoWrapTableMasterPelanggan');
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
			$data['isibork'] = $this->Master_model->getBorKList();
			
			
			$data['action']  = 'cetak_master_pelanggan/cetaklaporan';
			
			// set judul halaman
			$data['judulpage'] = "Cetak Data Master Pelanggan";
			
			$this->template->load('template','master/cetakmasterpelanggan_view',$data);
		}
	}
	
	function cetaklaporan()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
		$this->load->model('Master_model');
		
		$idclusternya=$this->input->post('cbocluster');
		$idborknya=$this->input->post('cbobork');
// 		echo $idclusternya;

		if($idborknya=='B')
		{
			$datapelanggan = $this->Master_model->get_master_pelanggan($idclusternya,$idborknya);
			$data = $datapelanggan->result();
			$datarow = $datapelanggan->row();
		}
		else 
		{
			$datapelanggan = $this->Master_model->get_master_kavling($idclusternya,$idborknya);
			$data = $datapelanggan->result();
			$datarow = $datapelanggan->row();			
		}
		
		//pilihan
		$options = array(
				'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
				'destinationfile' => '', //I=inline browser (default), F=local file, D=download
				'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
				'orientation'=>'P' //orientation: P=portrait, L=landscape
		);
		
		$tabel = new FPDF_AutoWrapTableMasterPelanggan($data,$options);
				
		$tabel->printPDF();
	}
	
}