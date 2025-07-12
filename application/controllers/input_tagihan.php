<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Input_tagihan extends CI_Controller
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
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
				
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			
			$data['action']  = 'input_tagihan/querytagihan';
			
			// set judul halaman
			$data['judulpage'] = "Input Tagihan";
			$data['page'] = "input_tagihan";
			$this->template->load('template','penagihan/transaksi_view',$data);
		}
	}

	public function querytagihan()
{
    if($this->auth->is_logged_in() == false)
    {
        $this->login();
    }
    else
    {
        $this->load->model('usermodel');
        $this->load->model('Master_model');
        $this->load->model('Penagihan_model');

        $idipkl = $this->input->post('idipkl');
        $blok   = $this->input->post('blok');
        $nokav  = $this->input->post('nokav');

        $level = $this->session->userdata('level');
        $data['menu'] = $this->usermodel->get_menu_for_level($level);
        $data['action']  = 'penagihan/querytagihan';
        $data['judulpage'] = "Update Data Tagihan";

        // Ambil data pelanggan berdasarkan input
        $pelangganbaris = $this->Penagihan_model->get_onepelanggan_multiup($idipkl, $blok, $nokav)->row();

        if ($pelangganbaris) {
            $data['default']['idipkl'] = $pelangganbaris->ID_IPKL;
            $data['default']['nama'] = $pelangganbaris->NAMA_PELANGGAN;
            $data['default']['blok'] = $pelangganbaris->BLOK;
            $data['default']['nokav'] = $pelangganbaris->NO_KAVLING;
            $data['default']['namacluster'] = $pelangganbaris->NAMA_CLUSTER;

            $data['tagihans'] = $this->Penagihan_model->get_tagihan_belumlunas($pelangganbaris->ID_IPKL);
            $data['totalnya'] = $this->Penagihan_model->get_tagihantotal_belumlunas($pelangganbaris->ID_IPKL);
        } else {
            $this->session->set_flashdata('error', 'Data pelanggan tidak ditemukan');
            redirect('penagihan/formcari'); // ganti dengan halaman formulir pencarian
            return;
        }
		// $this->template->load('template','penagihan/rinciancetaktagihan_view',$data);
		$this->template->load('template','penagihan/inputtagihan_form',$data);

        
    }
}
	
	public function querytagihan2()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
				
			// load model 'usermodel'
			$this->load->model('Master_model');
			$this->load->model('Penagihan_model');
		
			$id=$this->input->post('idipkl');
		
			// level untuk user ini
			$level = $this->session->userdata('level');
		
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
		
			$data['action']  = 'input_tagihan/simpantagihan';
		
			// set judul halaman
			$data['judulpage'] = "Input Tagihan";
				
			$pelangganbaris = $this->Penagihan_model->get_onepelanggan($id)->row();
				
			$data['default']['idipkl']=$pelangganbaris->ID_IPKL;
			$data['default']['nama']=$pelangganbaris->NAMA_PELANGGAN;
			$data['default']['blok']=$pelangganbaris->BLOK;
			$data['default']['nokav']=$pelangganbaris->NO_KAVLING;
			$data['default']['namacluster']=$pelangganbaris->NAMA_CLUSTER;
			$data['default']['tagihan']=$pelangganbaris->TARIF;
				
			$this->template->load('template','penagihan/inputtagihan_form',$data);
		}
	}

	public function simpantagihan()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
			
			// load model 'usermodel'
			$this->load->model('Master_model');
			$this->load->model('Penagihan_model');
			
			$idipkl=$this->input->post('idipkl');
			$tahun=$this->input->post('cbotahun');
			$bulan=$this->input->post('cbobulan');	
			$tagihan=$this->input->post('tagihan');
			
			// level untuk user ini
			$level = $this->session->userdata('level');
			
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			$data['action']  = 'input_tagihan/querytagihan';
			
			// set judul halaman
			$data['judulpage'] = "Input Tagihan";
			
			$this->Penagihan_model->insert_tagihan_perid($idipkl,$tahun,$bulan,$tagihan);
			
			redirect(site_url('update_tagihan'));
// 			$this->template->load('template','penagihan/transaksi_view',$data);
		}
	}

}
