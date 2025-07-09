<?php
/**
 * Rekap Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Kecamatan extends Controller {
	/**
	 * Constructor
	 */
	function Kecamatan()
	{
		parent::Controller();
		$this->load->model('Master_model', '', TRUE);
		
		// Load to_excel_pi plugins
		$this->load->plugin('to_excel');
		$this->load->library('fungsi');
	}

	var $limit = 10;
	var $title = 'Kecamatan';
	
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
		$data['h2_title'] = 'Data Kecamatan';
		$data['main_view'] = 'master/kecamatan';
		$data['left_view'] = 'menumaster.php';
		
		// Load data
		$kecamatan = $this->Master_model->get_kec();
		$kecamatanrow = $kecamatan->result();
		$num_rows = $this->Master_model->count_kec();
		
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
				$this->table->set_heading('KODE KECAMATAN','KECAMATAN','AKSI');
								
				foreach ($kecamatanrow as $row)
				{
					$this->table->add_row($row->id, $row->nama,anchor('kecamatan/update/'.$row->id,'update',array('class' => 'update')).' '.anchor('kecamatan/delete/'.$row->id,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
					);
				}
									
				$data['table'] = $this->table->generate();
				$data['link'] = array('link_add' => anchor('kecamatan/add/','Tambah Data', array('class' => 'add')));
				$this->load->view('template', $data);
			}
		}
	
	function delete($idkec)
	{
		$this->Master_model->deletekec($idkec);
		$this->session->set_flashdata('message', '1 data kecamatan berhasil dihapus');
		
		redirect('kecamatan');
	}
	
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Kecamatan > Tambah Data';
		$data['main_view'] 		= 'master/kecamatan_form';
		$data['left_view']		= 'menumaster.php';
		$data['form_action']	= site_url('kecamatan/add_process');
		$data['link'] 			= array('link_back' => anchor('kecamatan','kembali', array('class' => 'back')));
		
		$this->load->view('template', $data);
	}
	
	function add_process()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Kecamatan > Tambah Data';
		$data['main_view'] 		= 'master/kecamatan_form';
		$data['left_view']		= 'menumaster.php';
		$data['form_action']	= site_url('kecamatan/add_process');
		$data['link'] 			= array('link_back' => anchor('kecamatan','kembali', array('class' => 'back')));
										
		// Set validation rules
		$this->form_validation->set_rules('idkec', 'Kode Kecamatan', 'required|exact_length[2]|numeric|callback_valid_idkec');
		$this->form_validation->set_rules('namakec', 'Nama Kecamatan', 'required|max_length[30]');

		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$kecamatan = array('id'	=> $this->input->post('idkec'),
							'nama' => $this->input->post('namakec'));
							
			$this->Master_model->addkec($kecamatan);
			
			$this->session->set_flashdata('message', 'Satu data siswa berhasil disimpan!');
			redirect('kecamatan/add');
		}
		else
		{	
			//$data['default']['id_kelas'] = $this->input->post('id_kelas');
			$this->load->view('template', $data);
		}	
		
		$this->load->view('template', $data);
	}

	function update($idkec)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Kecamatan > Update';
		$data['main_view'] 		= 'master/kecamatan_form';
		$data['left_view']		= 'menumaster.php';
		$data['form_action']	= site_url('kecamatan/update_process');
		$data['link'] 			= array('link_back' => anchor('kecamatan','kembali', array('class' => 'back'))
										);
		// cari data dari database
		$kecamatan = $this->Master_model->get_kecamatan_by_id($idkec);
		
		// buat session untuk menyimpan data primary key (nis)
		$this->session->set_userdata('id', $kecamatan->id);
		
		// Data untuk mengisi field2 form
		$data['default']['idkec'] 		= $kecamatan->id;
		$data['default']['namakec'] 		= $kecamatan->nama;		
						
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data siswa
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Kecamatan > Update';
		$data['main_view'] 		= 'master/kecamatan_form';
		$data['left_view']		= 'menumaster.php';
		$data['form_action']	= site_url('kecamatan/update_process');
		$data['link'] 			= array('link_back' => anchor('kecamatan','kembali', array('class' => 'back'))
										);
										
		// Set validation rules
		$this->form_validation->set_rules('idkec', 'Kode Kecamatan', 'required|exact_length[2]|numeric|callback_valid_idkec');
		$this->form_validation->set_rules('namakec', 'Nama Kecamatan', 'required|max_length[30]');
		
		if ($this->form_validation->run() == TRUE)
		{
			$kecamatan = array('id'	=> $this->input->post('idkec'),
							'nama' => $this->input->post('namakec'));
							
			$this->Master_model->updatekec($this->session->userdata('id'), $kecamatan);
			
			$this->session->set_flashdata('message', 'Satu data siswa berhasil disimpan!');
			redirect('kecamatan');
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