<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_cluster extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->Model('Master_model');
		 $this->load->model('Master_cluster_model');
        $this->load->library('pagination');
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
			$data['judulpage'] = "Master Cluster";
			
			$config = array(
					'base_url'          => site_url('master_cluster/index/'),
					'total_rows'        => $this->Master_model->count_all_cluster(),
					'per_page'          => $this->config->item('per_page'),
					'uri_segment'       => 3,
					'num_links'         => 9,
					'use_page_numbers'  => FALSE
			);
				
			$this->pagination->initialize($config);
			$data['total']          = $config['total_rows'];
			$data['pagination']     = $this->pagination->create_links();
			$data['number']         = (int)$this->uri->segment(3) +1;
			$data['clusters']       = $this->Master_cluster_model->get_all_cluster($config['per_page'], $this->uri->segment(3));
				
			$this->template->load('template','master/master_cluster_view',$data);
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
			$data['judulpage'] = "Detail Master Cluster";
			$data['page'] = "master_cluster";
			
			$data['cluster'] = $this->Master_model->get_onecluster($id);
						
			//$data['main_view'] 		= 'master/_pelangganshow';
			$this->template->load('template','master/_clustershow',$data);
	
		}
		else
		{
			//$this->session->set_flashdata('notif', notify('Data tidak ditemukan','info'));
			redirect(site_url('master_cluster'));
		}
	}
        
        public function add()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
			
		// level untuk user ini
		$level = $this->session->userdata('level');
			
		// ambil menu dari database sesuai dengan level
		$data['menu'] = $this->usermodel->get_menu_for_level($level);

		// set judul halaman
		$data['judulpage'] = "Menambahkan Master Cluster";
		$data['cluster'] = $this->Master_model->addcluster();
		$data['action']  = 'master_cluster/save';
		
	  	$this->template->load('template','master/clusterform',$data);
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
						'field' => 'idcluster',
						'label' => 'ID Cluster',
						'rules' => 'trim|xss_clean'
				),
	
				array(
						'field' => 'namacluster',
						'label' => 'Nama Cluster',
						'rules' => 'trim|xss_clean'
				),
	
				array(
						'field' => 'tarif',
						'label' => 'Tarif',
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
	
					$this->Master_model->savecluster();
					$this->session->set_flashdata('notif', notify('Data berhasil di simpan','success'));
					redirect('master_cluster');
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
					$this->Master_model->updatecluster($id);
					$this->session->set_flashdata('notif', notify('Data berhasil di update','success'));
					redirect('master_cluster');
				}
			}
			else // If validation incorrect
			{
				$this->edit($id);
			}
		}
	}

	public function search()
{
    $this->load->model('usermodel');
    $this->load->model('Master_cluster_model');

    // Ambil level user dan menu
    $level = $this->session->userdata('level');
    $data['menu'] = $this->usermodel->get_menu_for_level($level);

    // Set judul halaman
    $data['judulpage'] = "Hasil Pencarian Master Cluster";

    // Ambil keyword dari input
    $keyword = $this->input->post('q', TRUE);
    $this->session->set_userdata('keyword', $keyword);

    // Konfigurasi pagination
    $this->load->library('pagination');
    $config = array(
        'base_url'        => site_url('master_cluster/search/'),
        'total_rows'      => $this->Master_cluster_model->count_all_search($keyword),
        'per_page'        => $this->config->item('per_page'),
        'uri_segment'     => 3,
        'num_links'       => 5,
        'use_page_numbers'=> FALSE
    );
    $this->pagination->initialize($config);

    // Kirim data ke view
    $data['total']      = $config['total_rows'];
    $data['pagination'] = $this->pagination->create_links();
    $data['clusters']   = $this->Master_cluster_model->get_search($keyword, $config['per_page'], $this->uri->segment(3));
    $data['number']     = (int)$this->uri->segment(3) + 1;

    // Tampilkan view
    $this->template->load('template', 'master/master_cluster_view', $data);
}


	public function search2()
{
    $this->load->model('Master_cluster_model'); // pastikan model ini sudah dibuat

    // Ambil kata kunci dari input POST
    $keyword = $this->input->post('q', true);
    $this->session->set_userdata('keyword_cluster', $keyword);

    // Konfigurasi pagination
    $this->load->library('pagination');
    $config['base_url'] = site_url('master_cluster/search');
    $config['total_rows'] = $this->Master_cluster_model->count_all_search($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;

    // Styling pagination (opsional untuk Bootstrap)
    $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
    $config['full_tag_close'] = '</ul>';
    $config['cur_tag_open'] = '<li class="active"><a href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo;';
    $config['next_link'] = '&raquo;';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    // Ambil data berdasarkan keyword dan pagination
    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    $data['clusters'] = $this->Master_cluster_model->get_search($keyword, $config['per_page'], $page);
    $data['total'] = $config['total_rows'];
    $data['pagination'] = $this->pagination->create_links();
    $data['main_view'] = 'master/master_cluster_view';

    $data['h2_title'] = 'Data Cluster';
    $data['message'] = '';
    
    $this->load->view('template', $data); // atau $this->template->load() jika pakai template
}

        
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
			$data['judulpage'] = "Update Master Cluster";
			$data['action']    = 'master_cluster/save/' . $id;
			$data['main_view'] = 'master/clusterform';


			$data['cluster']=$this->Master_model->get_onecluster_foredit($id);				
													
			$this->template->load('template','master/clusterform',$data);
	
		}
		else
		{
			$this->session->set_flashdata('notif', notify('Data tidak ditemukan','info'));
			redirect(site_url('master_pelanggan'));
		}
	}
               
        public function destroy($id)
	{
		if ($id)
		{
			$this->Master_model->destroycluster($id);
			$this->session->set_flashdata('notif', notify('Data berhasil di hapus','success'));
			redirect('master_cluster');
		}
		else
		{
			$this->session->set_flashdata('notif', notify('Data tidak ditemukan','warning'));
			redirect('master_cluster');
		}
	}
        
}
