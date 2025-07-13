<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aproval_penagihan extends CI_Controller
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
			
			$data['action']  = 'aproval_penagihan/querytagihan';
			
			// set judul halaman
			$data['judulpage'] = "Aproval Penagihan";
			$data['page'] = "aproval_penagihan";
			
			$this->template->load('template','penagihan/transaksi_view',$data);
		}
	}
	
	// public function querytagihan()
	// {
	// 	if($this->auth->is_logged_in() == false)
	// 	{
	// 		$this->login();
	// 	}
	// 	else
	// 	{
	// 		// load model 'usermodel'
	// 		$this->load->model('usermodel');
				
	// 		// load model 'usermodel'
	// 		$this->load->model('Master_model');
	// 		$this->load->model('Penagihan_model');
		
	// 		$id=$this->input->post('idipkl');
		
	// 		// level untuk user ini
	// 		$level = $this->session->userdata('level');
		
	// 		// ambil menu dari database sesuai dengan level
	// 		$data['menu'] = $this->usermodel->get_menu_for_level($level);
		
	// 		$data['action']  = 'penagihan/bayartagihan';
		
	// 		// set judul halaman
	// 		$data['judulpage'] = "Approval Tagihan";
				
	// 		// 			$pelanggan = $this->Master_model->get_one($id);
	// 		// 			$pelangganbaris = $pelanggan->result();
				
	// 		// $pelangganbaris = $this->Penagihan_model->get_onepelanggan($id)->row();
	// 		$pelangganbaris = $this->Penagihan_model->get_onepelanggan($id)->row();

	// 			if (!$pelangganbaris) {
	// 						$this->session->set_flashdata('error', 'Data pelanggan tidak ditemukan.');
	// 						redirect('aproval_penagihan'); // Kembali ke form pencarian
	// 						return;
	// 					}
	

	// 		$data['default']['idipkl']=$pelangganbaris->ID_IPKL;
	// 		$data['default']['nama']=$pelangganbaris->NAMA_PELANGGAN;
	// 		$data['default']['blok']=$pelangganbaris->BLOK;
	// 		$data['default']['nokav']=$pelangganbaris->NO_KAVLING;
	// 		$data['default']['namacluster']=$pelangganbaris->NAMA_CLUSTER;
				
	// 		$data['tagihans'] = $this->Penagihan_model->get_tagihan_belumlunas($id);
	// 		$data['totalnya'] = $this->Penagihan_model->get_tagihantotal_belumlunas($id);
		
	// 		$this->template->load('template','penagihan/rincianaproval_view',$data);
	// 	}
	// }
public function querytagihan()
{
    // Cek login
    if (!$this->auth->is_logged_in()) {
        return $this->login();
    }

    // Load model yang dibutuhkan
    $this->load->model('usermodel');
    $this->load->model('Penagihan_model');

    // Ambil input dari form POST
    $idipkl = $this->input->post('idipkl');
    $blok   = $this->input->post('blok');
    $nokav  = $this->input->post('nokav');

    // Ambil menu berdasarkan level user
    $level = $this->session->userdata('level');
    $data['menu'] = $this->usermodel->get_menu_for_level($level);

    // Konfigurasi halaman
    $data['action']     = 'approval/querytagihan'; // Arahkan kembali ke form ini jika ingin pencarian ulang
    $data['judulpage']  = "Approval Tagihan";

    // Cari data pelanggan berdasarkan input
    $pelangganbaris = $this->Penagihan_model->get_onepelanggan_multi1($idipkl, $blok, $nokav)->row();

    if (!$pelangganbaris) {
        $this->session->set_flashdata('error', 'Data pelanggan tidak ditemukan.');
        redirect('aproval_penagihan');
        return;
    }

    // Set data default untuk ditampilkan ke view
    $data['default'] = [
        'idipkl'      => $pelangganbaris->ID_IPKL,
        'nama'        => $pelangganbaris->NAMA_PELANGGAN,
        'blok'        => $pelangganbaris->BLOK,
        'nokav'       => $pelangganbaris->NO_KAVLING,
        'namacluster' => $pelangganbaris->NAMA_CLUSTER
    ];

    // Ambil tagihan belum lunas
    $data['tagihans']  = $this->Penagihan_model->get_tagihan_belumlunas($pelangganbaris->ID_IPKL);
    $data['totalnya']  = $this->Penagihan_model->get_tagihantotal_belumlunas($pelangganbaris->ID_IPKL);

    // Load view
    $this->template->load('template', 'penagihan/rincianaproval_view', $data);
}

public function get_onepelanggan1($id)
	{
		$this->db->select('pelanggan.idipkl, pelanggan.blok, tagihan.idipkl AS tagihan_idipkl, pelanggan.nokav');
    $this->db->from('pelanggan');
    $this->db->join('tagihan', 'pelanggan.idipkl = tagihan.idipkl');

    if (!empty($idipkl)) {
        $this->db->where('pelanggan.idipkl', $idipkl);
    }
    if (!empty($blok)) {
        $this->db->where('pelanggan.blok', $blok);
    }
    if (!empty($nokav)) {
        $this->db->where('pelanggan.nokav', $nokav);
    }

    $query = $this->db->get();
    return $query->row(); // Mengembalikan satu baris sebagai object
		
		return $result;
	}
	
	
	public function tampilaprove($id='')
	{
		if ($id != '')
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
				
			// load model 'usermodel'
			$this->load->model('Master_model');
			$this->load->model('Penagihan_model');
							
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
	
			// set judul halaman
			$data['judulpage'] = "Approval Tagihan";
			$data['action']  = 'aproval_penagihan/updatetagihan';
				
			$tagihanbaris = $this->Penagihan_model->get_tagihan_foraproval($id)->row();
				
			$data['default']['idtagihan']=$tagihanbaris->idtagihan;
			$data['default']['idipkl']=$tagihanbaris->idipkl;
			$data['default']['tahun']=$tagihanbaris->tahun;
			$data['default']['bulan']=$tagihanbaris->bulan;
			$data['default']['tagihan']=$tagihanbaris->tagihan;
			$data['default']['denda']=$tagihanbaris->denda;
			$data['default']['diskon']=$tagihanbaris->diskon;
			$data['default']['kenadiskon']=$tagihanbaris->kenadiskon;
			$data['default']['kenadenda']=$tagihanbaris->kenadenda;
			$data['default']['keterangan']=$tagihanbaris->ketaproval;
			
			$data['isikenadiskon'] = $this->Master_model->get_kenadiskon();
			$data['isikenadenda'] = $this->Master_model->get_kenadenda();
								
			//$data['main_view'] 		= 'master/_pelangganshow';
			$this->template->load('template','penagihan/tagihanaprovalform',$data);
	
		}
		else
		{
			//$this->session->set_flashdata('notif', notify('Data tidak ditemukan','info'));
			redirect(site_url('master_pelanggan'));
		}
	}
	
	public function updatetagihandiskondenda($idtagihan,$nilaidiskon,$kenadiskon,$kenadenda,$user,$ket)
	{
		if($kenadiskon=='1' And $kenadenda=='1')
		{
			$this->Penagihan_model->update_tagihan_denda($idtagihan,$kenadiskon,$kenadenda,$user,$ket);
		}
		elseif ($kenadiskon=='1' And $kenadenda=='2')
		{
			$this->Penagihan_model->update_tagihan_denda($idtagihan,$kenadiskon,$kenadenda,$user,$ket);
		}
		elseif ($kenadiskon=='2' And $kenadenda=='1')
		{
			$this->Penagihan_model->update_tagihan_dendadiskon($idtagihan,$nilaidiskon,$kenadiskon,$kenadenda,$user,$ket);
		}
		elseif ($kenadiskon=='2' And $kenadenda=='2')
		{
			$this->Penagihan_model->update_tagihan_dendadiskon($idtagihan,$nilaidiskon,$kenadiskon,$kenadenda,$user,$ket);
		}
	}
	
	
	public function updatetagihan()
	{
			// load model 'usermodel'
			$this->load->model('usermodel');
			
			// load model 'usermodel'
			$this->load->model('Master_model');
			$this->load->model('Penagihan_model');
				
			// level untuk user ini
			$level = $this->session->userdata('level');
			
			// data user 
			$user = $this->session->userdata('user_id');
			
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			$idtagihan=$this->input->post('idtagihan');
			$id=$this->input->post('idipkl');
			$nilaidiskon=$this->input->post('diskon');
			$kenadiskon=$this->input->post('cbokenadiskon');
			$kenadenda=$this->input->post('cbokenadenda');
			$ket=$this->input->post('keterangan');

			$this->updatetagihandiskondenda($idtagihan, $nilaidiskon, $kenadiskon, $kenadenda, $user,$ket);
			
 			redirect('aproval_penagihan');
	}
	
}
