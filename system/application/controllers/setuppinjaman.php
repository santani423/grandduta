<?php
/**
 * Rekap Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Setuppinjaman extends Controller {
	/**
	 * Constructor
	 */
	function Setuppinjaman()
	{
		parent::Controller();
		$this->load->model('Simpanpinjam_model', '', TRUE);
		
		// Load to_excel_pi plugins
		$this->load->plugin('to_excel');
		$this->load->library('fungsi');
	}

	var $limit = 10;
	var $title = 'Setup Klasifikasi Pinjaman';
	
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
		$data['h2_title'] = 'Data Klasifikasi Pinjaman';
		$data['main_view'] = 'simpanpinjam/setuppinjaman';
		$data['left_view'] = 'menusimpanpinjam.php';
		
		// Load data
		$klaspinjaman = $this->Simpanpinjam_model->get_pinjaman();
		$klaspinjamanrow = $klaspinjaman->result();
		$num_rows = $this->Simpanpinjam_model->count_pinjaman();
		
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
				$this->table->set_heading('ID KLASIFIKASI PINJAMAN','JENIS PINJAMAN','PINJAMAN MAKSIMAL','JANGKA WAKTU','JASA KREDIT','AKSI');
								
				foreach ($klaspinjamanrow as $row)
				{
					$this->table->add_row($row->idklaspinjam, $row->JenisPinjaman,$row->MaxPinjaman, $row->JangkaWaktu,$row->Jasa,anchor('setuppinjaman/update/'.$row->idklaspinjam,'update',array('class' => 'update')).' '.anchor('setuppinjaman/delete/'.$row->idklaspinjam,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
					);
				}
									
				$data['table'] = $this->table->generate();
				$data['link'] = array('link_add' => anchor('setuppinjaman/add/','Tambah Data', array('class' => 'add')));
				$this->load->view('template', $data);
			}
		}
	
	function delete($idklaspinjam)
	{
		$this->Simpanpinjam_model->deletepinjaman($idklaspinjam);
		$this->session->set_flashdata('message', '1 data Jenis Pinjaman berhasil dihapus');
		
		redirect('setuppinjaman');
	}
	
	function add()
	{		
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Klasifikasi Pinjaman';
		$data['main_view'] = 'simpanpinjam/setuppinjaman_form';
		$data['left_view'] = 'menusimpanpinjam.php';
		$data['form_action'] = site_url('setuppinjaman/add_process');
		$data['link'] = array('link_back' => anchor('setuppinjaman','kembali', array('class' => 'back')));
		
		$this->load->view('template', $data);
	}
	
	function add_process()
	{		
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Klasifikasi Pinjaman';
		$data['main_view'] = 'simpanpinjam/setuppinjaman_form';
		$data['left_view'] = 'menusimpanpinjam.php';
		$data['form_action'] = site_url('setuppinjaman/add_process');
		$data['link'] = array('link_back' => anchor('setuppinjaman','kembali', array('class' => 'back')));
										
		// Set validation rules
		$this->form_validation->set_rules('idklaspinjam', 'ID Klasifikasi Pinjaman Kecamatan', 'required|exact_length[2]|numeric|callback_valid_idklaspinjam');
		$this->form_validation->set_rules('jenispinjaman', 'Jenis Pinjaman', 'required|max_length[30]');
		$this->form_validation->set_rules('maxpinjaman', 'Pinjaman Maksimal', 'required|max_length[9]');
		$this->form_validation->set_rules('jangkawaktu', 'Jangka Waktu', 'required|max_length[2]');
		$this->form_validation->set_rules('jasakredit', 'Jasa Kredit', 'required|max_length[2]');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$klaspinjam = array('idklaspinjam'	=> $this->input->post('idklaspinjam'),
							'JenisPinjaman' => $this->input->post('jenispinjaman'),
							'MaxPinjaman' => $this->input->post('maxpinjaman'),
							'JangkaWaktu' => $this->input->post('jangkawaktu'),
							'Jasa' => $this->input->post('jasakredit'),
							);
							
			$this->Simpanpinjam_model->addpinjaman($klaspinjam);
			
			$this->session->set_flashdata('message', 'Satu data  berhasil disimpan!');
			redirect('setuppinjaman/add');
		}
		else
		{	
			//$data['default']['id_kelas'] = $this->input->post('id_kelas');
			$this->load->view('template', $data);
		}	
		
		$this->load->view('template', $data);
	}

	function update($idklaspinjam)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Klasifikasi Pinjaman';
		$data['main_view'] = 'simpanpinjam/setuppinjaman_form';
		$data['left_view'] = 'menusimpanpinjam.php';
		$data['form_action'] = site_url('setuppinjaman/update_process');
		$data['link'] = array('link_back' => anchor('setuppinjaman','kembali', array('class' => 'back')));
		
		// cari data dari database
		$klaspinjam = $this->Simpanpinjam_model->get_pinjaman_by_id($idklaspinjam);
		
		// buat session untuk menyimpan data primary key (nis)
		$this->session->set_userdata('idklaspinjam', $klaspinjam->idklaspinjam);
		
		// Data untuk mengisi field2 form
		$data['default']['idklaspinjam'] 		= $klaspinjam->idklaspinjam;
		$data['default']['jenispinjaman']		= $klaspinjam->JenisPinjaman;	
		$data['default']['maxpinjaman'] 		= $klaspinjam->MaxPinjaman;
		$data['default']['jangkawaktu']		= $klaspinjam->JangkaWaktu;	
		$data['default']['jasakredit'] 		= $klaspinjam->Jasa;			
			
						
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data siswa
	 */
	function update_process()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Klasifikasi Pinjaman';
		$data['main_view'] = 'simpanpinjam/setuppinjaman_form';
		$data['left_view'] = 'menusimpanpinjam.php';
		$data['form_action'] = site_url('setuppinjaman/update_process');
		$data['link'] = array('link_back' => anchor('setuppinjaman','kembali', array('class' => 'back')));
										
		// Set validation rules
		$this->form_validation->set_rules('idklaspinjam', 'ID Klasifikasi Pinjaman Kecamatan', 'required|exact_length[2]|numeric|callback_valid_idklaspinjam');
		$this->form_validation->set_rules('jenispinjaman', 'Jenis Pinjaman', 'required|max_length[30]');
		$this->form_validation->set_rules('maxpinjaman', 'Pinjaman Maksimal', 'required|max_length[9]');
		$this->form_validation->set_rules('jangkawaktu', 'Jangka Waktu', 'required|max_length[2]');
		$this->form_validation->set_rules('jasakredit', 'Jasa Kredit', 'required|max_length[2]');
		
		if ($this->form_validation->run() == TRUE)
		{
			$klaspinjam = array('idklaspinjam'	=> $this->input->post('idklaspinjam'),
							'JenisPinjaman' => $this->input->post('jenispinjaman'),
							'MaxPinjaman' => $this->input->post('maxpinjaman'),
							'JangkaWaktu' => $this->input->post('jangkawaktu'),
							'Jasa' => $this->input->post('jasakredit'),
							);
							
			$this->Simpanpinjam_model->updatepinjaman($this->session->userdata('idklaspinjam'), $klaspinjam);
			
			$this->session->set_flashdata('message', 'Satu data berhasil disimpan!');
			redirect('setuppinjaman');
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