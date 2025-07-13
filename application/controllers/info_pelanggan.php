<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_pelanggan extends CI_Controller
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
			
			// load model 'master_model'
			$this->load->model('Master_model');
			$this->load->model('Info_model');
				
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			$data['isicluster'] = $this->Master_model->getClusterList();
			
			$data['action']  = 'info_pelanggan/querypelanggan';
			
			// set judul halaman
			$data['judulpage'] = "Informasi Pelanggan";
			$data['page'] = "info_pelanggan";
			
			$this->template->load('template','info/infoplgpilih_view',$data);
		}
	}
	
	public function querypelanggan()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
			
		// load model 'master_model'
		$this->load->model('Master_model');
		$this->load->model('Info_model');
		
		// level untuk user ini
		$level = $this->session->userdata('level');
		
		// ambil menu dari database sesuai dengan level
		$data['menu'] = $this->usermodel->get_menu_for_level($level);
		
		$optradio = $this->input->post('optionsRadios');
		$nama = $this->input->post('namapelanggan');
		$idcluster = $this->input->post('cbocluster');
		$blok = $this->input->post('blok');
		$nokav = $this->input->post('nokav');
		
		if ($optradio=='1')
		{
// 			echo "Berdasar nama";
			if($nama)
			{
				$data['pelanggans']       = $this->Info_model->get_plg_bynama($nama);
			}
			else 
			{
				redirect(site_url('info_pelanggan'));
			}
		}
		elseif ($optradio=='2')
		{
			if($idcluster)
			{
				if($blok)
				{
					if($nokav)
					{
						// **** cluster,blok dan nokav diisi
						$data['pelanggans']= $this->Info_model->get_plg_by_clusterbloknokav($idcluster,$blok,$nokav);
					}
					else 
					{
						// **** cluster dan blok diisi						
						$data['pelanggans']= $this->Info_model->get_plg_by_clusterblok($idcluster,$blok);
					}
				}
				else 
				{
					if($nokav)
					{
						// **** cluster dan nokav diisi
						$data['pelanggans']= $this->Info_model->get_plg_by_clusternokav($idcluster,$nokav);
					}
					else 
					{
						// **** cluster saja diisi						
						$data['pelanggans']= $this->Info_model->get_plg_by_cluster($idcluster);
					}
				}
			}
			else 
			{
				if($blok)
				{
					if($nokav)
					{
						// **** blok dan nokav diisi
						$data['pelanggans']= $this->Info_model->get_plg_by_bloknokav($blok,$nokav);
					}
					else
					{
						// **** blok diisi
						$data['pelanggans']= $this->Info_model->get_plg_by_blok($blok);
					}
				}
				else
				{
					if($nokav)
					{
						// **** nokav saja diisi
						$data['pelanggans']= $this->Info_model->get_plg_by_nokav($nokav);
					}
					else
					{
						// **** ngga ada yang diisi
						redirect(site_url('info_pelanggan'));
					}
				}				
			}
		}
		
		// set judul halaman
		$data['judulpage'] = "Informasi Pelanggan";

		$this->template->load('template','info/infoplghasil_view',$data);
	}
	
}
