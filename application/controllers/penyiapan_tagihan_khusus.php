<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penyiapan_tagihan_khusus extends CI_Controller
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
				
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			// ambil data buat combo
			$data['isicluster'] = $this->Master_model->getClusterList();
			
			$data['action']  = 'penyiapan_tagihan_khusus/queryinfotagihan';
			
			// set judul halaman
			$data['judulpage'] = "Penyiapan Tagihan Khusus";
			
			$this->template->load('template','penagihan/penyiapantagihankhusus_view',$data);
		}
	}
	
	function queryinfotagihan()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
		
		// load model 'usermodel'
		$this->load->model('Penagihan_model');
		
		// level untuk user ini
		$level = $this->session->userdata('level');
		

		// ambil menu dari database sesuai dengan level
		$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
		// set judul halaman
		$data['judulpage'] = "Penyiapan Tagihan Khusus";
		
		$tahun = $this->input->post('cbotahun');
		$bulan = $this->input->post('cbobulan');
		
		if($tahun < date('y'))
		{
			// Tampilkan Peringatan Tidak dapat Generate Tagihan
			// echo "Tampilkan Peringatan";
			$data['pesan'] = "Anda tidak dapat meng-generate data tahun sebelumnya";
		}
		else 
		{
		if($bulan<=(date('m')-1))
			{
				$data['pesan'] = "Anda tidak dapat meng-generate data bulan sebelumnya";
				$data['jml_terupdate'] = 0;
			}
			else 
			{
				// Generate Tagihan
				$gentag = $this->Penagihan_model->update_tagihan_khusus($tahun, $bulan);
				
				$jml_tagihan_generated = $this->Penagihan_model->count_tagihan_khusus($tahun, $bulan)->row();

				// echo "Generate Tagihan";
				$data['pesan'] = "Data khusus berhasil digenerate";
				$data['jml_terupdate'] = $jml_tagihan_generated->jmltagkhusus;
			}
			$this->template->load('template','penagihan/konfirmasi_penyiapantagihankhusus_view',$data);
		}
	}
	
	function separator($num, $suffix = '')
	{
		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
	
		return $result ;
	}
	
}
