<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');                
		$this->load->Model('Master_model'); 
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
			
			// set judul halaman
			$data['judulpage'] = "Dashboard";
                        $data['total']  = $this->Master_model->count_all();
                        $data['totalbangunan']  = $this->Master_model->count_bangunan();
                        $data['totalkavling']   = $this->Master_model->count_kavling();

				 
			
			$this->template->load('template','home/dashboard',$data);
		}
	}
	
}
