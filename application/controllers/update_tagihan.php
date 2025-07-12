<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_tagihan extends CI_Controller
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
			
			$data['action']  = 'update_tagihan/querytagihan';
			
			// set judul halaman
			$data['judulpage'] = "Update Data Tagihan";
			$data['page'] = "update_tagihan";
			
			$this->template->load('template','penagihan/transaksi_view',$data);
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
		
			$data['action']  = 'penagihan/bayartagihan';
		
			// set judul halaman
			$data['judulpage'] = "Update Data Tagihan";
				
			// 			$pelanggan = $this->Master_model->get_one($id);
			// 			$pelangganbaris = $pelanggan->result();
				
			$pelangganbaris = $this->Penagihan_model->get_onepelanggan($id)->row();
				
			$data['default']['idipkl']=$pelangganbaris->ID_IPKL;
			$data['default']['nama']=$pelangganbaris->NAMA_PELANGGAN;
			$data['default']['blok']=$pelangganbaris->BLOK;
			$data['default']['nokav']=$pelangganbaris->NO_KAVLING;
			$data['default']['namacluster']=$pelangganbaris->NAMA_CLUSTER;
				
			$data['tagihans'] = $this->Penagihan_model->get_tagihan_belumlunas($id);
			$data['totalnya'] = $this->Penagihan_model->get_tagihantotal_belumlunas($id);
			// print_r($this->Penagihan_model->get_tagihan_belumlunas($id));
			// return false;
			$this->template->load('template','penagihan/rincianupdate_view',$data);
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

        $this->template->load('template','penagihan/rincianupdate_view', $data);
    }
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
			$data['judulpage'] = "Update Data Tagihan";
			$data['action']  = 'update_tagihan/updatetagihan';
				
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
			
			$data['isikenadiskon'] = $this->Master_model->get_kenadiskon();
			$data['isikenadenda'] = $this->Master_model->get_kenadenda();
								
			//$data['main_view'] 		= 'master/_pelangganshow';
			$this->template->load('template','penagihan/tagihanupdateform',$data);
	
		}
		else
		{
			//$this->session->set_flashdata('notif', notify('Data tidak ditemukan','info'));
			redirect(site_url('update_tagihan'));
		}
	}
	
	public function updatetagihandiskondenda($idtagihan,$nilaidiskon,$kenadiskon,$kenadenda,$user)
	{
		if($kenadiskon=='1' And $kenadenda=='1')
		{
			$this->Penagihan_model->update_tagihan_denda($idtagihan,$kenadiskon,$kenadenda,$user);
		}
		elseif ($kenadiskon=='1' And $kenadenda=='2')
		{
			$this->Penagihan_model->update_tagihan_denda($idtagihan,$kenadiskon,$kenadenda,$user);
		}
		elseif ($kenadiskon=='2' And $kenadenda=='1')
		{
			$this->Penagihan_model->update_tagihan_dendadiskon($idtagihan,$nilaidiskon,$kenadiskon,$kenadenda,$user);
		}
		elseif ($kenadiskon=='2' And $kenadenda=='2')
		{
			$this->Penagihan_model->update_tagihan_dendadiskon($idtagihan,$nilaidiskon,$kenadiskon,$kenadenda,$user);
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
			$idipkl=$this->input->post('idipkl');
			$tahun=$this->input->post('tahun');
			$bulan=$this->input->post('bulan');
			$tagihan=$this->input->post('tagihan');
			
/* 			echo $idtagihan;
			echo "<br>";
			echo $idipkl;
 */			
			$cektagihanbaris = $this->Penagihan_model->cek_tagihan_sama($idipkl,$tahun,$bulan)->row();
			$jmlcektagihanbaris=$cektagihanbaris->jml;
			
/* 			echo "<br>";
			echo $jmlcektagihanbaris; */
						
			if($jmlcektagihanbaris==1)
			{
// 				$this->session->set_flashdata('notif', notify('Terdapat data yang sama, Ulangi !','info'));
				$this->Penagihan_model->update_tagihan_saja($idtagihan, $tagihan);
/*				$this->session->set_flashdata('message', 'Data Tagihan Dobel, Update Gagal!');
				redirect(site_url('update_tagihan')); */
			}
 			else 
 			{
 				$this->Penagihan_model->update_tagihan($idtagihan, $tahun, $bulan, $tagihan);
 			}
			
 			redirect('update_tagihan');
	}
	
	public function destroy($id)
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
			
		// load model 'usermodel'
		$this->load->model('Master_model');
		$this->load->model('Penagihan_model');
		
		if ($id)
		{
			$this->Penagihan_model->destroytagihan($id);
			$this->session->set_flashdata('notif', notify('Data berhasil di hapus','success'));
			redirect(site_url('update_tagihan'));
		}
		else
		{
			$this->session->set_flashdata('notif', notify('Data tidak ditemukan','warning'));
			redirect(site_url('update_tagihan'));
		}
	}
}
