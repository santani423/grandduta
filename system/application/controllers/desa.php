<?php
/**
 * Rekap Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Desa extends Controller {
	/**
	 * Constructor
	 */
	function Desa()
	{
		parent::Controller();
		$this->load->model('Master_model', '', TRUE);
		
		// Load to_excel_pi plugins
		$this->load->plugin('to_excel');
		$this->load->library('fungsi');
	}

	var $limit = 10;
	var $title = 'Desa';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menjalankan fungsi main()
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->get_all();
		}
		else
		{
			redirect('login');
		}
	}
	
	/**
	 * Menampilkan halaman utama rekap absen
	 */
	function get_all()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Desa';
		$data['main_view'] = 'master/desa';
		$data['left_view'] = 'menumaster.php';
		
		// Load data
		$desa = $this->Master_model->get_desa_all();
		$desarow = $desa->result();
		$num_rows = $this->Master_model->count_desa();
		
		if ($num_rows > 0)
		{
		
			// set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
							  'row_alt_start'=>'<tr class="zebra">',
							  'row_alt_end'=>'</tr>'
							  );
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('KODE DESA','KECAMATAN','DESA','AKSI');
								
				foreach ($desarow as $row)
				{
					$this->table->add_row($row->id, $row->kecamatan ,$row->desa,anchor('desa/update/'.$row->id,'update',array('class' => 'update')).' '.anchor('desa/delete/'.$row->id,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
					);
				}
									
				$data['table'] = $this->table->generate();
				$data['link'] = array('link_add' => anchor('desa/add/','Tambah Data', array('class' => 'add')));
				$this->load->view('template', $data);
			}
		}
	
	function delete($iddesa)
	{
		$this->Master_model->deletedesa($iddesa);
		$this->session->set_flashdata('message', '1 data kecamatan berhasil dihapus');
		
		redirect('desa');
	}
	
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Desa > Tambah Data';
		$data['main_view'] 		= 'master/desa_form';
		$data['left_view']		= 'menumaster.php';
		$data['form_action']	= site_url('desa/add_process');
		$data['link'] 			= array('link_back' => anchor('desa','kembali', array('class' => 'back')));
		
		$data['kategori'] = $this->db->get('kecamatan');
		$this->load->view('template', $data);
	}
	
	function add_process()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Desa > Tambah Data';
		$data['main_view'] 		= 'master/desa_form';
		$data['left_view']		= 'menumaster.php';
		$data['form_action']	= site_url('desa/add_process');
		$data['link'] 			= array('link_back' => anchor('desa','kembali', array('class' => 'back')));
		
		$data['kategori'] = $this->db->get('kecamatan');
		
		// Set validation rules
		$this->form_validation->set_rules('iddesa', 'Kode Desa', 'required|exact_length[2]|numeric|callback_valid_iddesa');
		$this->form_validation->set_rules('kategori', 'Nama Kecamatan', 'required|max_length[30]');
		$this->form_validation->set_rules('namadesa', 'Nama Desa', 'required|max_length[30]');

		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$desa = array('id'	=> $this->input->post('iddesa'),
							'kategori'	=> $this->input->post('kategori'),
							'nama' => $this->input->post('namadesa'));
			
			echo $this->input->post('iddesa');
			echo $this->input->post('kategori');
			echo $this->input->post('namadesa');
							
			$this->Master_model->adddesa($desa);
			
			$this->session->set_flashdata('message', 'Satu data siswa berhasil disimpan!');
			redirect('desa/add');
					

		}
		else
		{	
			//$data['default']['id_kelas'] = $this->input->post('id_kelas');
			$this->load->view('template', $data);
		}	
		$this->load->view('template', $data);
	}

	function update($iddesa)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Desa > Update Data';
		$data['main_view'] 		= 'master/desa_form';
		$data['left_view']		= 'menumaster.php';
		$data['form_action']	= site_url('desa/update_process');
		$data['link'] 			= array('link_back' => anchor('desa','kembali', array('class' => 'back')));
		
		$data['kategori'] = $this->db->get('kecamatan');

		$desa = $this->Master_model->get_desa_by_iddesa($iddesa);
				
		// buat session untuk menyimpan data primary key (nis)
		$this->session->set_userdata('id', $desa->id);
		
		// Data untuk mengisi field2 form
		$data['default']['iddesa'] 		= $desa->id;
		$data['default']['kategori'] 		= $desa->kategori;	
		$data['default']['namadesa'] 		= $desa->nama;		
						
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data siswa
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Desa > Update Data';
		$data['main_view'] 		= 'master/desa_form';
		$data['left_view']		= 'menumaster.php';
		$data['form_action']	= site_url('desa/update_process');
		$data['link'] 			= array('link_back' => anchor('desa','kembali', array('class' => 'back')));
										
		$data['kategori'] = $this->db->get('kecamatan');
		
		// Set validation rules
		$this->form_validation->set_rules('iddesa', 'Kode Desa', 'required|exact_length[2]|numeric|callback_valid_iddesa');
		$this->form_validation->set_rules('kategori', 'Nama Kecamatan', 'required|max_length[30]');
		$this->form_validation->set_rules('namadesa', 'Nama Desa', 'required|max_length[30]');
		
		if ($this->form_validation->run() == TRUE)
		{
		$desa = array('id'	=> $this->input->post('iddesa'),
							'kategori'	=> $this->input->post('kategori'),
							'nama' => $this->input->post('namadesa'));
							
			$this->Master_model->updatedesa($this->session->userdata('id'), $desa);
			
			$this->session->set_flashdata('message', 'Satu data berhasil disimpan!');
			redirect('desa');
		}
		else
		{
			$this->load->view('template', $data);
		}
	}
}				
// END Rekap Class

/* End of file rekap.php */
/* Location: ./system/application/controllers/rekap.php */