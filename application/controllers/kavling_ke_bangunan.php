<?php
/**
 * Infotag Class
 *
 * @author	Trias Bratakusuma <brata@pdamtkr.co.id>
 */
class Kavling_ke_bangunan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model', '', TRUE);
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('form');				
		$this->load->library('pagination');
		$this->load->library('table');
		$this->load->helper('datecbo');	
	}
	
	var $title = 'Tagihan';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menjalankan fungsi main()
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
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
			
			
			$data['action']  = 'kavling_ke_bangunan/querypelanggan';
			
			// set judul halaman
			$data['judulpage'] = "Mutasi Kavling Ke Bangunan ";
			$data['page'] = "kavling_ke_bangunan";
			
			$this->template->load('template','penagihan/transaksi_view',$data);
		}
	}
	
        function querypelanggan()
        {
            // load model 'usermodel'
			$this->load->model('usermodel');
                        
                        $id=$this->input->post('idipkl');
				
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			// set judul halaman
			$data['judulpage'] = "Mutasi Kavling Ke Bangunan";
			$data['action']       = 'kavling_ke_bangunan/save/' . $id;
			$data['main_view'] 		= 'master/kavlingkebangunan';


			$data['pelanggan']=$this->Master_model->get_oneplgkav_foredit($id);				
			
			// ambil data buat combo
			$data['isicluster'] = $this->Master_model->getClusterList();
			$data['isibork'] = $this->Master_model->getBorK();
			
                        if($data['pelanggan'])
                        {
                            $this->template->load('template','master/kavlingkebangunan',$data);
                        }
                        else 
                        {
                            $this->template->load('template','penagihan/transaksi_view',$data);
                        }			
        }
        
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
						'field' => 'tglmasuk',
						'label' => 'Tgl Masuk',
						'rules' => 'trim|xss_clean'
				),
		);
	
		// if id NULL then add new data
		if(!$id)
		{
                    echo "Simpan Data Data";
                    /*
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
                    */
		}
		else // Update data if Form Edit send Post and ID available
		{
                        //echo "Update Data";
                        
                        $this->form_validation->set_rules($config);
	
			if ($this->form_validation->run() == TRUE)
			{
                            $kavorbang=$this->input->post('cbobork');
                                                        
                            if($kavorbang=="B")
                            {
                                if ($this->input->post())
				{
					$this->Master_model->updatekavtobang($id);
					$this->Master_model->delpiutangkavtobang($id);
                                        
                                        $this->session->set_flashdata('notif', notify('Data berhasil di update','success'));
					redirect('kavling_ke_bangunan');
				}
                            }
                            else
                            {
                                $this->edit($id);
                            }
				
			}
			else // If validation incorrect
			{
				$this->edit($id);
			}
                        
		}
	}
        
}
// END Rekap Class

/* End of file rekap.php */
/* Location: ./system/application/controllers/rekap.php */