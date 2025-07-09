<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
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
		else{ 

		

		//  Tambahkan di sini
		$this->load->model('Penagihan_model');

		// Cek apakah hari ini tanggal 1
		// if (date('d') == '01') {
		// 	$this->Penagihan_model->buat_tagihan_bulanan(); // Pastikan method ini aman dari duplikat
		// 	$this->session->set_userdata('tagihan_checked', true); // Supaya tidak eksekusi berulang
		
		// }

		
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
			
			// tampilkan halaman dashboard dengan data menu 
			$this->template->load('template','home/dashboard',$data);
		}
	}
	
	public function manajemen_menu()
	{
		// mencegah user yang belum login untuk mengakses halaman ini
		$this->auth->restrict();
		// mencegah user mengakses menu yang tidak boleh ia buka
		$this->auth->cek_menu(1);
		// tampilkan isi menu manajemen menu (mungkin tabel menu/input form menu)
		$this->template->set('title','Manajemen User | Auditas');
		$this->template->load('template','admin/manajemen_menu');
	}
	
	public function login()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$this->template->set('title','Login Form | Auditas');
			$this->template->load('templatelogin','login/login_form');
		}
		else
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$success = $this->auth->do_login($username,$password);
			if($success)
			{
				// lemparkan ke halaman index user
				redirect('home');
			}
			else
			{
				$this->template->set('title','Login Form | Auditas');
				$data['login_info'] = "Username dan password salah!";
				$this->template->load('templatelogin','login/login_form',$data);		
			}
		}
	}
	
	function logout()
	{
		if($this->auth->is_logged_in() == true)
		{
			// jika dia memang sudah login, destroy session
			$this->auth->do_logout();
		}
		// larikan ke halaman utama
		redirect('home');
	}
}