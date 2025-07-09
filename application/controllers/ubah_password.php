<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ubah_password extends CI_Controller
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
			$username=$this->session->userdata('username');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			$data['action']  = 'ubah_password/updatepassword';
			$data['info'] = "";
			
			// set judul halaman
			$data['judulpage'] = "Ubah Password";
			$data['default']['username']=$username;
			
			
			$this->template->load('template','master/password_view',$data);
		}
	}
	
	public function updatepassword()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
		$this->load->model('Login_model');

		$user=$this->input->post('username');
		$pwdlama=$this->input->post('passwdlama');
		$pwdbaru1=$this->input->post('passwdbaru1');
		$pwdbaru2=$this->input->post('passwdbaru2');
		
		// level untuk user ini
		$level = $this->session->userdata('level');
			
		// ambil menu dari database sesuai dengan level
		$data['menu'] = $this->usermodel->get_menu_for_level($level);
		
		// set judul halaman
		$data['judulpage'] = "Ubah Password";
		$data['info'] = "";
		
		if($pwdbaru1<>$pwdbaru2)
		{
			//Password baru nggak matching
			$this->session->set_flashdata('message', 'Password baru tidak sama !');
			redirect(site_url('ubah_password'));
		}	
		else 
		{
			//Password baru matching
			//Ambil data password lama dari database
			$data['passwd'] = $this->usermodel->get_one_passwd($user);
			
			echo $data['passwd']['user_password'];
			if(md5($pwdlama)==$data['passwd']['user_password'])
			{
				//Password benar lanjut perubahan
				//echo "Password Benar";
				$pwdsiap=md5($pwdbaru1);
								
				$proses = $this->usermodel->updateuser($user,$pwdsiap);
				$this->session->set_flashdata('message', 'Update Berhasil');
				redirect(site_url('ubah_password'));
				
/* 				if ($this->usermodel->updateuser($username,$pwdbaru1))
				{
					
					$this->session->set_flashdata('message', 'Update Berhasil');
					redirect(site_url('ubah_password'));
				}
				else
				{
					$this->session->set_flashdata('message', 'Update data Gagal');
					redirect(site_url('ubah_password'));
				} */
				
				//redirect(site_url('home'));
			}
			else 
			{
				//Password Salah batalkan perubahan
			$this->session->set_flashdata('message', 'Password anda salah !');
			redirect(site_url('ubah_password'));
			}
		}
	}
	
}
