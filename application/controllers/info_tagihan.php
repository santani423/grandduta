<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_tagihan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
	}
	
	public function index()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else{

		//  Tambahkan di sini
		$this->load->model('Penagihan_model');

	 
	 
			$this->Penagihan_model->buat_tagihan_bulanan(); // Pastikan method ini aman dari duplikat
			$this->session->set_userdata('tagihan_checked', true); // Supaya tidak eksekusi berulang

			// $this->Penagihan_model->hapus_tagihan_bulanan(); // Pastikan method ini aman dari duplikat
			// $this->session->set_userdata('tagihan_checked', true); // Supaya tidak eksekusi berulang
	 

		
			// load model 'usermodel'
			$this->load->model('usermodel');
				
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			$data['action']  = 'info_tagihan/queryinfotagihan';
			
			// set judul halaman
			$data['judulpage'] = "Informasi Tagihan";
			$data['page'] = "info_tagihan";
			
			$this->template->load('template','info/transaksi_view',$data);
		}
	}



	
	// public function queryinfotagihan()
	// {
	// 	// load model 'usermodel'
	// 	$this->load->model('usermodel');
	// 	$this->load->model('Master_model');
	// 	$this->load->model('Penagihan_model');

	// 	$id=$this->input->post('idipkl');
		
	// 	// level untuk user ini
	// 	$level = $this->session->userdata('level');
			
	// 	// ambil menu dari database sesuai dengan level
	// 	$data['menu'] = $this->usermodel->get_menu_for_level($level);
		
	// 	// set judul halaman
	// 	$data['judulpage'] = "Detail Informasi Tagihan";
		
			
	// 	$data['pelanggan'] = $this->Master_model->get_one($id);
	// 	$data['totalnya'] = $this->Penagihan_model->get_tagihantotal_belumlunas($id);
	// 	$data['tagihans'] = $this->Master_model->get_tagihan_fordetail($id);

			
	// 	$this->template->load('template','penagihan/penagihanshow',$data);
	// }

	public function queryinfotagihan()
{
    $this->load->model('usermodel');
    $this->load->model('Master_model');
    $this->load->model('Penagihan_model');

    $idipkl = $this->input->post('idipkl');
    $blok   = $this->input->post('blok');
    $nokav = $this->input->post('nokav');

    $level = $this->session->userdata('level');
    $data['menu'] = $this->usermodel->get_menu_for_level($level);
    $data['judulpage'] = "Detail Informasi Tagihan";

    // Gunakan pencarian gabungan
    $data['pelanggan'] = $this->Master_model->search_pelanggan($idipkl, $blok, $nokav);

    if ($data['pelanggan']) {
        $id = $data['pelanggan']->idipkl; // atau sesuaikan nama kolomnya
        $data['totalnya'] = $this->Penagihan_model->get_tagihantotal_belumlunas($id);
        $data['tagihans'] = $this->Master_model->get_tagihan_fordetail($id);
    } else {
        $data['totalnya'] = null;
        $data['tagihans'] = null;
    }

    $this->template->load('template', 'penagihan/penagihanshow', $data);
}

	
}
