<?php
/**
 * Rekap Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Simpanpinjam extends Controller {
	/**
	 * Constructor
	 */
	function Simpanpinjam()
	{
		parent::Controller();
		$this->load->model('Simpanpinjam_model', '', TRUE);
		
		// Load to_excel_pi plugins
		$this->load->plugin('to_excel');
	}
	
	var $title = 'Simpan Pinjam';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menjalankan fungsi main()
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->main();
		}
		else
		{
			redirect('login');
		}
	}
	
	/**
	 * Menampilkan halaman utama rekap absen
	 */
	function main()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Simpan Pinjam';
		$data['main_view'] = 'simpanpinjam/simpanpinjam';
		$data['left_view'] = 'menusimpanpinjam.php';
				
		$this->load->view('template', $data);
	}
}
// END Rekap Class

/* End of file rekap.php */
/* Location: ./system/application/controllers/rekap.php */