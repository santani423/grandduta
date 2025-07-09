<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_pelanggan extends CI_Controller
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
			$data['judulpage'] = "Master Pelanggan";
			
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
	public function save($id =NULL)
	{
		// validation config
		$config = array(
	
				array(
						'field' => 'idipkl',
						'label' => 'ID IPKL',
						'rules' => 'trim|xss_clean'
				),
	
				array(
						'field' => 'namapelanggan',
						'label' => 'Nama Pelanggan',
						'rules' => 'trim|xss_clean'
				),
	
				array(
						'field' => 'alamat',
						'label' => 'Alamat',
						'rules' => 'trim|xss_clean'
				),
	
				array(
						'field' => 'cbodesa',
						'label' => 'Desa',
						'rules' => 'trim|xss_clean'
				),
	
				array(
						'field' => 'cbojk',
						'label' => 'Jenis Kelamin',
						'rules' => 'trim|xss_clean'
				),
	
				array(
						'field' => 'cboagama',
						'label' => 'Id Agama',
						'rules' => 'trim|xss_clean'
				),
	
				array(
						'field' => 'tglmasuk',
						'label' => 'Tgl Masuk',
						'rules' => 'trim|xss_clean'
				),
	
				array(
						'field' => 'cbostatus',
						'label' => 'Status Anggota',
						'rules' => 'trim|xss_clean'
				),
				 
		);
	
		// if id NULL then add new data
		if(!$id)
		{
			$this->form_validation->set_rules($config);
	
			if ($this->form_validation->run() == TRUE)
			{
				if($this->input->post())
				{
	
					$this->Master_model->save();
					$this->session->set_flashdata('notif', notify('Data berhasil di simpan','success'));
					redirect('master_pelanggan');
				}
			}
			else // If validation incorrect
			{
				$this->add();
			}
		}
		else // Update data if Form Edit send Post and ID available
		{
			$this->form_validation->set_rules($config);
	
			if ($this->form_validation->run() == TRUE)
			{
				if ($this->input->post())
				{
					$this->Master_model->update($id);
					$this->session->set_flashdata('notif', notify('Data berhasil di update','success'));
					redirect('master_pelanggan');
				}
			}
			else // If validation incorrect
			{
				$this->edit($id);
			}
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
