<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_bulanan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->helper('datecbo');
                $this->load->library('FPDF_AutoWrapTableLaporanBulananBerjalanan');
                $this->load->library('FPDF_AutoWrapTableLaporanBulananLalu');        
                $this->load->library('FPDF_AutoWrapTableLaporan2BulananLalu');
                $this->load->library('FPDF_AutoWrapTableLaporan3BulananLebih');
                $this->load->library('FPDF_AutoWrapTableLaporanTotal');
                $this->load->library('FPDF_AutoWrapTableLaporanTotalPendapatan');
                $this->load->library('FPDF_AutoWrapTableLaporanDepositPerCluster');
                $this->load->library('FPDF_AutoWrapTableLaporanTotalPerCluster');
                $this->load->library('FPDF_AutoWrapTableLaporanTotalPendapatanPerCluster');
        
                $this->load->library('table');	
	}
	
	public function index()
	{
		// return false;
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
			$this->load->model('Master_model');
                        
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
                        $data['isicluster'] = $this->Master_model->getClusterList();
			
			$data['action']  = 'laporan_bulanan/cetaklaporan';
			
			// set judul halaman
			$data['judulpage'] = "Laporan Bulanan";
			
			$this->template->load('template','laporan/lapbul_view',$data);
		}
	}
	
	public function cetaklaporan()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
		$this->load->model('Master_model');
		$this->load->model('Penagihan_model');
		
		$pilihanlapbul=$this->input->post('optionsRadios');
                $pertahun=$this->input->post('cbotahun');
                $perbulan=$this->input->post('cbobulan');

		switch($pilihanlapbul)
		{
			case 1:
/* 			========= Tagihan Bulan Berjalan - Start - ==========*/
		
			$rekaptagihan = $this->Penagihan_model->get_lapbul_berjalan($pertahun,$perbulan);
			$data = $rekaptagihan->result();
			$datarow = $rekaptagihan->row();
		
			//pilihan
			$options = array(
				'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
				'destinationfile' => '', //I=inline browser (default), F=local file, D=download
				'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
				'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
			
			$tabel = new FPDF_AutoWrapTableLaporanBulananBerjalanan($data,$options);

/* 			========= Tagihan Bulan Berjalan - End - ==========*/

			$tabel->printPDF();

			break;
		
			case 2;
				
/* 			========= Tagihan Bulan Lalu - Start - ==========*/
                        
                        if($perbulan=="01")
                        {
                            $perbulannya="12";
                            $pertahunnya=$pertahun-1;
                        }
                        else
                        {
                            $perbulannya=$perbulan-1;
                            $pertahunnya=$pertahun;
                        }

                        $rekaptagihan1 = $this->Penagihan_model->get_lapbul_bulanlalu($pertahunnya,$perbulannya);
			$data1 = $rekaptagihan1->result();
			$datarow1 = $rekaptagihan1->row();
		
			//pilihan
			$options = array(
				'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
				'destinationfile' => '', //I=inline browser (default), F=local file, D=download
				'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
				'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
			
			$tabel1 = new FPDF_AutoWrapTableLaporanBulananLalu($data1,$options);
		
/* 			========= Tagihan Bulan Lalu - End - ==========*/
		
			$tabel1->printPDF();

			break;
			
			case 3;
			
/* 			========= Tagihan Bulan Lalu - Start - ==========*/
			
                        if($perbulan=="01")
                        {
                            $perbulannya="11";
                            $pertahunnya=$pertahun-1;
                        }
                        elseif($perbulan=="02") 
                        {
                            $perbulannya="12";
                            $pertahunnya=$pertahun-1;
                        }
                        else 
                        {
                            $perbulannya=$perbulan-2;
                            $pertahunnya=$pertahun;
                        }                            
                            
			$rekaptagihan2 = $this->Penagihan_model->get_lapbul_2bulanlalu($pertahun,$perbulan);
			$data2 = $rekaptagihan2->result();
			$datarow2 = $rekaptagihan2->row();
			
			//pilihan
			$options = array(
					'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
					'destinationfile' => '', //I=inline browser (default), F=local file, D=download
					'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
					'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
				
			$tabel2 = new FPDF_AutoWrapTableLaporan2BulananLalu($data2,$options);
			
			/* 			========= Tagihan Bulan Lalu - End - ==========*/
			
			$tabel2->printPDF();
				
			break;
				
			case 4;
				
/* 			========= Tagihan Bulan Lalu - Start - ==========*/
				
			$rekaptagihan3 = $this->Penagihan_model->get_lapbul_3bulanlebih($pertahun,$perbulan);
			$data3 = $rekaptagihan3->result();
			$datarow3 = $rekaptagihan3->row();
				
			//pilihan
			$options = array(
					'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
					'destinationfile' => '', //I=inline browser (default), F=local file, D=download
					'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
					'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
			
			$tabel3 = new FPDF_AutoWrapTableLaporan3BulananLebih($data3,$options);
				
/* 			========= Tagihan Bulan Lalu - End - ==========*/
				
			$tabel3->printPDF();
			
			break;
				
			case 5;
				
/* 			========= Tagihan Total Penerimaan Per Bulan- ==========*/
				
			$rekaptagihan4 = $this->Penagihan_model->get_lapbul_Total($pertahun,$perbulan);
			$data4 = $rekaptagihan4->result();
			$datarow4 = $rekaptagihan4->row();
				
			//pilihan
			$options = array(
					'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
					'destinationfile' => '', //I=inline browser (default), F=local file, D=download
					'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
					'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
			
			$tabel4 = new FPDF_AutoWrapTableLaporanTotal($data4,$options);
				
/* 			========= Tagihan Bulan Lalu - End - ==========*/
				
			$tabel4->printPDF();
                        
			break;
				
			case 6;
				
/* 			========= Tagihan Total Pendapatan - ==========*/
				
			$rekaptagihan5 = $this->Penagihan_model->get_lapbul_Total_Pendapatan($pertahun,$perbulan);
			$data5 = $rekaptagihan5->result();
			$datarow5 = $rekaptagihan5->row();
				
			//pilihan
			$options = array(
					'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
					'destinationfile' => '', //I=inline browser (default), F=local file, D=download
					'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
					'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
			
			$tabel5 = new FPDF_AutoWrapTableLaporanTotalPendapatan($data5,$options);
				
/* 			========= Tagihan Bulan Lalu - End - ==========*/
				
			$tabel5->printPDF();
                        
			break;
				
			case 7;
                            
/* 			========= Laporan Deposit - ==========*/
			$percluster=$this->input->post('cbocluster');
                            
			$rekaptagihan6 = $this->Penagihan_model->get_lapbul_deposit_percluster($percluster,$pertahun,$perbulan);
			$data6 = $rekaptagihan6->result();
			$datarow6 = $rekaptagihan6->row();
				
			//pilihan
			$options = array(
					'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
					'destinationfile' => '', //I=inline browser (default), F=local file, D=download
					'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
					'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
			
			$tabel6 = new FPDF_AutoWrapTableLaporanDepositPerCluster($data6,$options);
				
/* 			========= Tagihan Bulan Lalu - End - ==========*/
				
			$tabel6->printPDF();
             
                        break;
                        
                        case 8;
                            
/* 			========= Tagihan Total Penerimaan Per  Cluster- ==========*/
			$percluster=$this->input->post('cbocluster');
                            
			$rekaptagihan7 = $this->Penagihan_model->get_lapbul_total_percluster($pertahun,$perbulan,$percluster);
			$data7 = $rekaptagihan7->result();
			$datarow7 = $rekaptagihan7->row();
				
			//pilihan
			$options = array(
					'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
					'destinationfile' => '', //I=inline browser (default), F=local file, D=download
					'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
					'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
			
			$tabel7 = new FPDF_AutoWrapTableLaporanTotal($data7,$options);
				
/* 			========= Tagihan Bulan Lalu - End - ==========*/
				
			$tabel7->printPDF();
				
                        break;
                        
                        case 9;
				
/* 			========= Tagihan Total Pendapatan - ==========*/
			$percluster=$this->input->post('cbocluster');
                        
			$rekaptagihan8 = $this->Penagihan_model->get_lapbul_Total_Pendapatan_percluster($pertahun,$perbulan,$percluster);
			$data8 = $rekaptagihan8->result();
			$datarow8 = $rekaptagihan8->row();
				
			//pilihan
			$options = array(
					'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
					'destinationfile' => '', //I=inline browser (default), F=local file, D=download
					'paper_size'=>'A4',	//paper size: F4, A3, A4, A5, Letter, Legal
					'orientation'=>'L' //orientation: P=portrait, L=landscape
			);
			
			$tabel8 = new FPDF_AutoWrapTableLaporanTotalPendapatanPerCluster($data8,$options);
				
/* 			========= Tagihan Bulan Lalu - End - ==========*/
				
			$tabel8->printPDF();
				
		}
	}
}