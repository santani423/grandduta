<?php
/**
 * LPP Class
 *
 * @author	Trias Bratakusuma <bratatkr@gmail.com>
 */
class LPP extends CI_Controller {
	/**
	 * Constructor
	 */
	function LPP()
	{
		parent::__Construct();
		$this->load->library('template');
		$this->load->helper('datecbo');
                $this->load->library('FPDF_AutoWrapTableLPP');
                $this->load->library('FPDF_AutoWrapTableLPPCash');
		$this->load->library('table');		
	
	}
	
	var $title = 'LPP';
	
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
				
			$data['action']  = 'lpp/cetaklaporan';
				
			// set judul halaman
			$data['judulpage'] = "Laporan Penagihan Harian";
				
			$this->template->load('template','laporan/lppform_view',$data);
		}
		
	}
	
	public function cetaklaporan()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
			$this->load->model('Master_model');
			$this->load->model('Penagihan_model');
					
			// level untuk user ini
			$level = $this->session->userdata('level');
			$user = $this->session->userdata('user_id');
			
			$tahun = $this->input->post('cbotahun');
			$bulan = $this->input->post('cbobulan');
			$hari = $this->input->post('cbohari');
			
			$tglbayar = $tahun.'-'.$bulan.'-'.$hari;
			
/*			$lpp = $this->Penagihan_model->get_lpp($tglbayar);
			$data = $lpp->result();
			$lppbaris = $lpp->row(); */
                        
			$lppCashLoket = $this->Penagihan_model->get_lppCashLoket($tglbayar);
			$dataCashLoket = $lppCashLoket->result();
			$lppbarisCashLoket = $lppCashLoket->row();
                        
                       	$lppCashKolektor = $this->Penagihan_model->get_lppCashKolektor($tglbayar);
			$dataCashKolektor = $lppCashKolektor->result();
			$lppbarisCashKolektor = $lppCashKolektor->row();
                        
			$lppDebet = $this->Penagihan_model->get_lppDebet($tglbayar);
			$dataDebet = $lppDebet->result();
			$lppbarisDebet = $lppDebet->row();
			
			//pilihan
			$options = array(
					'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
					'destinationfile' => '', //I=inline browser (default), F=local file, D=download
					'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
					'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
				
			$tabel = new FPDF_AutoWrapTableLPPCash($dataCashLoket,$dataCashKolektor,$dataDebet,$options);
			$tabel->printPDF();
                        
		}
	}

}
