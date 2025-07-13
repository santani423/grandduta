<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_pelanggan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template','form_validation');
		$this->load->Model('Master_model'); 
		$this->load->helper(['form', 'url']);
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
			$data['judulpage'] = "Master Pelanggan";
			$data['page'] = "master_pelanggan";
			
			$config = array(
					'base_url'          => site_url('master_pelanggan/index'),
					'total_rows'        => $this->Master_model->count_all(),
					'per_page'          => $this->config->item('per_page'),
					'uri_segment'       => 3,
					'num_links'         => 9,
					'use_page_numbers'  => FALSE
			);
			
			$this->pagination->initialize($config);
			$data['total']          = $config['total_rows'];
                        $data['totalbangunan']  = $this->Master_model->count_bangunan();
                        $data['totalkavling']   = $this->Master_model->count_kavling();
			$data['pagination']     = $this->pagination->create_links();
			$data['number']         = (int)$this->uri->segment(3) +1;
			$data['pelanggans']       = $this->Master_model->get_all($config['per_page'], $this->uri->segment(3));
			
			$this->template->load('template','master/master_pelanggan_view',$data);
		}
	}
	
	public function show($id='')
	{
		if ($id != '')
		{
	
			// load model 'usermodel'
			$this->load->model('usermodel');
			
			// level untuk user ini
			$level = $this->session->userdata('level');
			
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
				
			// set judul halaman
			$data['judulpage'] = "Detail Master Pelanggan";
			
			$data['pelanggan'] = $this->Master_model->get_one($id);
			$data['tagihans'] = $this->Master_model->get_tagihan_fordetail($id);
			
			//$data['main_view'] 		= 'master/_pelangganshow';
			$this->template->load('template','master/_pelangganshow',$data);
	
		}
		else
		{
			//$this->session->set_flashdata('notif', notify('Data tidak ditemukan','info'));
			redirect(site_url('master_pelanggan'));
		}
	}
	
	/**
	 * Call Form to Add  New anggota
	 *
	 */
	public function add()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
			
		// level untuk user ini
		$level = $this->session->userdata('level');
			
		// ambil menu dari database sesuai dengan level
		$data['menu'] = $this->usermodel->get_menu_for_level($level);

		// set judul halaman
		$data['judulpage'] = "Menambahkan Master Pelanggan";
		$data['pelanggan'] = $this->Master_model->add();
		$data['action']  = 'master_pelanggan/save';
		
		// ambil data buat combo
		$data['isicluster'] = $this->Master_model->getClusterList();
		$data['isibork'] = $this->Master_model->getBorK();
		$data['isistatuspelanggan'] = $this->Master_model->getStatusPelanggan();
        $data['isihuni'] = $this->Master_model->getStatusHuni();
 		$data['isikab'] = $this->Master_model->getKabupatenList();
 		$data['isikec'] = $this->Master_model->getKecamatanList();
 		
	  	$this->template->load('template','master/pelangganform',$data);
// 		$this->template->render('master/pelangganform',$data);
		
	}
	
	/**
	 * Search anggota like ""
	 *
	 */

	 public function search2()
{
    $this->load->model('usermodel');
    $this->load->model('Master_model'); // pastikan ini ada

    $level = $this->session->userdata('level');
    $data['menu'] = $this->usermodel->get_menu_for_level($level);
    $data['judulpage'] = "Hasil Pencarian Master Pelanggan";

    // Ambil input
    $idipkl = $this->input->get_post('idipkl', TRUE);
    $blok = $this->input->get_post('blok', TRUE);
    $nokav = $this->input->get_post('nokav', TRUE);

    // Kirim ke model
    $pelanggan = $this->Master_model->search_pelanggan2($idipkl, $blok, $nokav);

    if ($pelanggan) {
        $data['pelanggans'] = [$pelanggan]; // bungkus array karena view pakai foreach
    } else {
        $data['pelanggans'] = []; // kosong
    }

    // Tidak pakai pagination
    $data['total'] = count($data['pelanggans']);
    $data['totalbangunan'] = "-";
    $data['totalkavling'] = "-";
    $data['pagination'] = "";

    $this->template->load('template', 'master/master_pelanggan_view', $data);
}


	public function search()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
			
		// level untuk user ini
		$level = $this->session->userdata('level');
			
		// ambil menu dari database sesuai dengan level
		$data['menu'] = $this->usermodel->get_menu_for_level($level);
		
		// set judul halaman
		$data['judulpage'] = "Hasil Pencarian Master Pelanggan";
		
		if($this->input->post('q'))
		{
			$keyword = $this->input->post('q');
	
			$this->session->set_userdata(
					array('keyword' => $this->input->post('q',TRUE))
			);
		}
	
		$config = array(
				'base_url'          => site_url('master_pelanggan/search/'),
				'total_rows'        => $this->Master_model->count_all_search(),
				'per_page'          => $this->config->item('per_page'),
				'uri_segment'       => 3,
				'num_links'         => 9,
				'use_page_numbers'  => FALSE
		);
	
		$this->pagination->initialize($config);
		$data['total']          = $config['total_rows'];
                $data['totalbangunan']  = "-";
                $data['totalkavling']   = "-";
		$data['number']         = (int)$this->uri->segment(3) +1;
		$data['pagination']     = $this->pagination->create_links();
		$data['pelanggans']       = $this->Master_model->get_search($config['per_page'], $this->uri->segment(3));
		$data['main_view']        = 'master/master_pelanggan_view';
		 
		$this->template->load('template','master/master_pelanggan_view',$data);
	}
	
	/**
	 * Call Form to Modify anggota
	 *
	 */
	public function edit($id='')
	{
		if ($id != '')
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
				
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			// set judul halaman
			$data['judulpage'] = "Update Master Pelanggan";
			$data['action']    = 'master_pelanggan/save/' . $id;
			$data['main_view'] = 'master/pelangganform';


			$data['pelanggan']=$this->Master_model->get_oneplg_foredit($id);				
			
			// ambil data buat combo
			$data['isicluster'] = $this->Master_model->getClusterList();
			$data['isibork'] = $this->Master_model->getBorK();
			$data['isistatuspelanggan'] = $this->Master_model->getStatusPelanggan();
			$data['isihuni'] = $this->Master_model->getStatusHuni();
                        $data['isikab'] = $this->Master_model->getKabupatenList();
			$data['isikec'] = $this->Master_model->getKecamatanList();
							
			$this->template->load('template','master/pelangganform',$data);
	
		}
		else
		{
			$this->session->set_flashdata('notif', notify('Data tidak ditemukan','info'));
			redirect(site_url('master_pelanggan'));
		}
	}
	
	
	
	/**
	 * Save & Update data  anggota
	 *
	 */
	public function save($id = NULL)
{
    // Validasi input
    $config = [
        ['field' => 'idipkl', 'label' => 'ID IPKL', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']],
        ['field' => 'namapelanggan', 'label' => 'Nama Pelanggan', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']],
        ['field' => 'cbocluster', 'label' => 'Cluster', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']],
        ['field' => 'blok', 'label' => 'Blok', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']],
        ['field' => 'nokav', 'label' => 'Nomor Kavling', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']],
        ['field' => 'cbobork', 'label' => 'Bangunan/Kavling', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']],
        ['field' => 'nohp', 'label' => 'Nomor HP', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']],
        ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi', 'valid_email' => 'Format email tidak valid']],
        ['field' => 'tglserahterima', 'label' => 'Tanggal Serah Terima', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']],
        ['field' => 'cbostatushuni', 'label' => 'Status Hunian', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']],
        ['field' => 'cbostatusplg', 'label' => 'Status Pelanggan', 'rules' => 'required|trim|xss_clean', 'errors' => ['required' => 'Data ini masih kosong, harap diisi']]
    ];

    $this->form_validation->set_rules($config);

    if ($this->form_validation->run() === FALSE) {
        if ($id) {
            $this->edit($id);
        } else {
            $this->add();
        }
    } else {
        if ($id) {
            $result = $this->Master_model->update($id);
            $msg = $result ? 'Data berhasil diperbarui' : 'Gagal memperbarui data';
        } else {
            $result = $this->Master_model->save();
            $msg = $result ? 'Data berhasil disimpan' : 'Gagal menyimpan data';
        }

        $this->session->set_flashdata('notif', notify($msg, $result ? 'Data Berhasil di simpan' : 'danger'));
        redirect('master_pelanggan');
    }
}


	
	public function destroy($id)
	{
		if ($id)
		{
			$this->Master_model->destroy($id);
			$this->session->set_flashdata('notif', notify('Data berhasil di hapus','success'));
			redirect('master_pelanggan');
		}
		else
		{
			$this->session->set_flashdata('notif', notify('Data tidak ditemukan','warning'));
			redirect('master_pelanggan');
		}
	}
	
	public function separator($num, $suffix = '')
	{
		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
	
		return $result ;
	}
	
	function select_kec()
	{
		if('IS_AJAX') {
			$data['isikec'] = $this->Master_model->getKecamatanList_byid();
			$this->load->view('master/kecamatanadd',$data);
		}
	}
	
}
