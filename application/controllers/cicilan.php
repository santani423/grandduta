<?php
/**
 * Cicilan Class
 *
 * @author	Trias Bratakusuma <bratatkr@gmail.com>
 */
class Cicilan extends CI_Controller {
	/**
	 * Constructor
	 */
	function cicilan()
	{
		parent::__Construct();
		$this->load->model('Cicilan_model', '', TRUE);
		$this->load->model('Loket_model', '', TRUE);
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('form');				
		$this->load->library('pagination');
		$this->load->library('table');
        $this->load->library('cfpdf');
	}
	
	var $title = 'Transaksi Cicilan';
	
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
		$data['h2_title'] = 'Transaksi Cicilan';
		
		$posisi = $this->session->userdata('posisi');

			Switch($posisi){
				case "Operator Loket":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaloket.php';
					break;
				case "Administrator":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamaadmin.php';
					break;
				case "Cicilan":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamacicilan.php';
					break;
				case "Operator Stand Meter":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaopsm.php';
					break;
				case "Pembatalan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamapembatalan.php';
					break;
				case "Supervisor":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamaspv.php';
					break;
				case "Services":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaservices.php';
					break;
				case "Yan-Gan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamayg.php';
					break;
			}
		
		$data['main_view'] = 'cicilan/cicilan_view';
		$data['left_view'] = 'menucicilan.php';
		$data['form_action'] = 'cicilan/get_transaksi';
		$data['user'] = $this->session->userdata('username');
		$data['posisi'] = $this->session->userdata('posisi');
		$data['tgl'] = date('j'.'/'.'m'.'/'.'Y');
		IF($this->session->userdata('posisi') == "Operator Loket"){
			$data['loket'] = $this->session->userdata('loket');
		} 
		else
		{
			$data['loket'] = "Aplikasi Non Loket";	
		}
				
		$this->load->view('template', $data);
	}
	
	function separator($num, $suffix = '')
	{
 		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
 
		return $result ;
	}
	
	function get_transaksi()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Transaksi Loket';
		
		$posisi = $this->session->userdata('posisi');

			Switch($posisi){
				case "Operator Loket":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaloket.php';
					break;
				case "Administrator":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamaadmin.php';
					break;
				case "Cicilan":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamacicilan.php';
					break;
				case "Operator Stand Meter":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaopsm.php';
					break;
				case "Pembatalan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamapembatalan.php';
					break;
				case "Supervisor":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamaspv.php';
					break;
				case "Services":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaservices.php';
					break;
				case "Yan-Gan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamayg.php';
					break;
			}
		
		$data['main_view'] = 'cicilan/cicilan_print';
		$data['left_view'] = 'menucicilan.php';
		$data['form_action'] = site_url('cicilan/bayar');
		$data['user'] = $this->session->userdata('username');
		$data['posisi'] = $this->session->userdata('posisi');
		$data['tgl'] = date('j'.'/'.'m'.'/'.'Y');
		IF($this->session->userdata('posisi') == "Operator Loket"){
			$data['loket'] = $this->session->userdata('loket');
		} 
		else
		{
			$data['loket'] = "Aplikasi Non Loket";	
		}
		
		$nosl = $this->input->post('nosl');
		
		$pelstatus = $this->Loket_model->get_pelstatus($nosl);
		$num_rows=$pelstatus->num_rows();
		
		if($num_rows==0)
		{
			$this->session->set_flashdata('message', 'Pelanggan Tidak Aktif!');
			redirect('loket');				
		}
		else
		{
			//echo "Ada pelanggan Aktif";
			$tagihan = $this->Loket_model->get_tagihan($nosl);
			$tagihanhasil=$tagihan->result();
			$num_rows=$tagihan->num_rows();
			$tagihanbaris=$tagihan->row();
		
			// ==============Header================
			$data['default']['nosl']=$tagihanbaris->tagihannosambungan;
			$data['default']['nama']=$tagihanbaris->tagihannamapelanggan;
			$data['default']['alamat']=$tagihanbaris->tagihanalamatpelanggan;
			$data['default']['gol']=$tagihanbaris->tagihankdgolongan;
			// ==============Header================	
			
			
			// Get data dari tabel tagihan dengan caltagihan
			if ($num_rows == 1)
			{
				// set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
						  'row_alt_start'=>'<tr class="zebra">',
						  'row_alt_end'=>'</tr>'
					  	);
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('BULAN','M. AWAL','M. AKHIR','M3','TAGIHAN','DENDA','MATERAI','TOTAL');
				
				//========================= Get Denda ==============================
				if($tagihanbaris->rp_denda<=15000)
					{
					$denda=$tagihanbaris->rp_denda;
					}
					else
					{
					$=15000;
					}						
				//========================= Get Denda ==============================
				//========================= Get Materai ==============================
				if($tagihanbaris->tagihantagihanbulan+$denda<=250000)
				{
					$materai=0;
				}
				else
				{
					if($tagihanbaris->tagihantagihanbulan+$denda<=1000000)
						{
							$materai=3000;
						}
						else
						{
							$materai=6000;
						}
				}
				//========================= Get Materai ==============================
											
				$this->table->add_row($tagihanbaris->tagihanbulantagihan, $this->separator($tagihanbaris->tagihanangkamawal) , $this->separator($tagihanbaris->tagihanangkamakhir),$this->separator($tagihanbaris->tagihanjumpakai),$this->separator($tagihanbaris->tagihantagihanbulan),$this->separator($denda),$this->separator($materai),$this->separator($tagihanbaris->tagihantagihanbulan+$denda+$materai));
				
				$data['table'] = $this->table->generate();
			}
			else
			{
				// set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
					  'row_alt_start'=>'<tr class="zebra">',
					  'row_alt_end'=>'</tr>');
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('BULAN','M. AWAL','M. AKHIR','M3','TAGIHAN','DENDA','MATERAI','TOTAL');
								
				foreach ($tagihanhasil as $row)
					{

						//========================= Get Denda ==============================
						if($row->rp_denda<=15000)
						{
							$denda=$row->rp_denda;
						}
						else
						{
							$denda=15000;
						}						
						//========================= Get Denda ==============================
						//========================= Get Materai ==============================
						if($row->tagihantagihanbulan+$denda<=250000)
						{
								$materai=0;
						}
						else
						{
								if($row->tagihantagihanbulan+$denda<=1000000)
								{
									$materai=3000;
								}	
								else
								{
									$materai=6000;
								}
						}
						//========================= Get Materai ==============================
						
						$this->table->add_row($row->tagihanbulantagihan, $this->separator($row->tagihanangkamawal) , $this->separator($row->tagihanangkamakhir),$this->separator($row->tagihanjumpakai),$this->separator($row->tagihantagihanbulan),$this->separator($denda),$this->separator($materai),$this->separator($row->tagihantagihanbulan+$denda+$materai));
					}
				
				$data['table'] = $this->table->generate();
			}
		}
				//================ Ada pelanggan Aktif Buat Cicilan ====================;
				$tagihancicil = $this->Cicilan_model->get_tagihan_pertama_buat_cicil($nosl);
				$tagihanbariscicil = $tagihancicil->row();
				
				//========================= Get Denda ==============================
				if($tagihanbariscicil->rp_denda<=15000)
					{
					$denda=$tagihanbariscicil->rp_denda;
					}
					else
					{
					$denda=15000;
					}						
				//========================= Get Denda ==============================
				//========================= Get Materai ==============================
				if($tagihanbariscicil->tagihantagihanbulan+$denda<=250000)
				{
					$materai=0;
				}
				else
				{
					if($tagihanbariscicil->tagihantagihanbulan+$denda<=1000000)
						{
							$materai=3000;
						}
						else
						{
							$materai=6000;
						}
				}
				//========================= Get Materai ==============================
											
				$this->table->add_row($tagihanbariscicil->tagihanbulantagihan, $this->separator($tagihanbariscicil->tagihanangkamawal) , $this->separator($tagihanbariscicil->tagihanangkamakhir),$this->separator($tagihanbariscicil->tagihanjumpakai),$this->separator($tagihanbariscicil->tagihantagihanbulan),$this->separator($denda),$this->separator($materai),$this->separator($tagihanbariscicil->tagihantagihanbulan+$denda+$materai),anchor('cicilan/bayar/'.$row->tagihannosambungan, img(array('src'=>'/asset/images/btnbayar.jpg','Text'=>'Bayar'))));				
						
				//========================= Tabel dengan Tombol Cicilan ===========================	
				// set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
					  'row_alt_start'=>'<tr class="zebra">',
					  'row_alt_end'=>'</tr>');
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('BULAN','M. AWAL','M. AKHIR','M3','TAGIHAN','DENDA','MATERAI','TOTAL','AKSI');			
				//========================= Tabel dengan Tombol Cicilan ===========================	
				
				$data['table2'] = $this->table->generate();	
				//================ Ada pelanggan Aktif Buat Cicilan ====================;
				
				
		$this->load->view('template', $data);
	}
	
	function bayar()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Transaksi Loket';
		
		$posisi = $this->session->userdata('posisi');

			Switch($posisi){
				case "Operator Loket":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaloket.php';
					break;
				case "Administrator":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamaadmin.php';
					break;
				case "Cicilan":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamacicilan.php';
					break;
				case "Operator Stand Meter":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaopsm.php';
					break;
				case "Pembatalan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamapembatalan.php';
					break;
				case "Supervisor":
					//echo "No BPS dan Tahun";
					$data['menu_utama'] = 'menuutamaspv.php';
					break;
				case "Services":
					//echo "No BPS";
					$data['menu_utama'] = 'menuutamaservices.php';
					break;
				case "Yan-Gan":	
					//echo "Tahun";
					$data['menu_utama'] = 'menuutamayg.php';
					break;
			}
		
		$data['main_view'] = 'cicilan/cicilan_printlunas';
		$data['left_view'] = 'menucicilan.php';
		$data['form_action'] = site_url('cicilan/cetakrek');
		$data['user'] = $this->session->userdata('username');
		$data['posisi'] = $this->session->userdata('posisi');
		$data['tgl'] = date('j'.'/'.'m'.'/'.'Y');
		IF($this->session->userdata('posisi') == "Operator Loket"){
			$data['loket'] = $this->session->userdata('loket');
		} 
		else
		{
			$data['loket'] = "Aplikasi Non Loket";	
		}
				
		$nosl = $this->uri->segment(3);
		//echo $nosl;
		
				//================ Ada pelanggan Aktif Buat Cicilan ====================;
				$tagihancicil = $this->Cicilan_model->get_tagihan_pertama_buat_cicil($nosl);
				$tagihanbariscicil = $tagihancicil->row();
				
				
				// ==============Header================
				$data['default']['nosl']=$tagihanbariscicil->tagihannosambungan;
				$data['default']['nama']=$tagihanbariscicil->tagihannamapelanggan;
				$data['default']['alamat']=$tagihanbariscicil->tagihanalamatpelanggan;
				$data['default']['gol']=$tagihanbariscicil->tagihankdgolongan;
				// ==============Header================	
				
				//========================= Get Denda ==============================
				if($tagihanbariscicil->rp_denda<=15000)
					{
					$denda=$tagihanbariscicil->rp_denda;
					}
					else
					{
					$denda=15000;
					}						
				//========================= Get Denda ==============================
				//========================= Get Materai ==============================
				if($tagihanbariscicil->tagihantagihanbulan+$denda<=250000)
				{
					$materai=0;
				}
				else
				{
					if($tagihanbariscicil->tagihantagihanbulan+$denda<=1000000)
						{
							$materai=3000;
						}
						else
						{
							$materai=6000;
						}
				}
				//========================= Get Materai ==============================
											
				$this->table->add_row($tagihanbariscicil->tagihanbulantagihan, $this->separator($tagihanbariscicil->tagihanangkamawal) , $this->separator($tagihanbariscicil->tagihanangkamakhir),$this->separator($tagihanbariscicil->tagihanjumpakai),$this->separator($tagihanbariscicil->tagihantagihanbulan),$this->separator($denda),$this->separator($materai),$this->separator($tagihanbariscicil->tagihantagihanbulan+$denda+$materai));				
						
				//========================= Tabel dengan Tombol Cicilan ===========================	
				// set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
					  'row_alt_start'=>'<tr class="zebra">',
					  'row_alt_end'=>'</tr>');
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('BULAN','M. AWAL','M. AKHIR','M3','TAGIHAN','DENDA','MATERAI','TOTAL');			
				//========================= Tabel dengan Tombol Cicilan ===========================	
//				$data['links'] = "anchor('cicilan/bayar/'.$tagihanbariscicil->tagihannosambungan, img(array('src'=>'/asset/images/btnbayar.jpg','Text'=>'Bayar')))";
				
				$data['table'] = $this->table->generate();	
				//================ Ada pelanggan Aktif Buat Cicilan ====================;
				
				
		$this->load->view('template', $data);
	}
	
	
	function cetakrek()
	{	
		
			$nosl = $this->input->post('nosl');
			//echo $nosl;
			
			//================================= Ambil Pelanggan Akan dicetak ================
			$tagihannya = $this->Cicilan_model->get_tagihan_pertama_buat_cetakcicil($nosl);
			//$tagihanhasil=$tagihan->result();
			$num_rowsnya=$tagihannya->num_rows();
			$tagihanbarisnya =$tagihannya->row();
		
			// ==============Header================
			$nosl=$tagihanbarisnya->tagihannosambungan;
			$nama=$tagihanbarisnya->tagihannamapelanggan;
			$alamat=$tagihanbarisnya->tagihanalamatpelanggan;
			$gol=$tagihanbarisnya->tagihankdgolongan;
			// ==============Header================	
					
			//===============================================================================
			$pdf = new FPDF("L","cm", array(21.6, 9.32));
			
			//================================= Mulai Cetak Pelanggan =======================
	
//			echo $nama;
//			echo $alamat;
//			echo $gol;
			// Get data dari tabel tagihan dengan caltagihan
			if ($num_rowsnya == 1)
			{
				//========================= Get Denda ==============================
				if($tagihanbarisnya->rp_denda<=15000)
					{
					$denda=$tagihanbarisnya->rp_denda;
					}
					else
					{
					$denda=15000;
					}						
				//========================= Get Denda ==============================
				//========================= Get Materai ==============================
				if($tagihanbarisnya->tagihantagihanbulan+$denda<=250000)
				{
					$materai=0;
				}
				else
				{
					if($tagihanbarisnya->tagihantagihanbulan+$denda<=1000000)
						{
							$materai=3000;
						}
						else
						{
							$materai=6000;
						}
				}
				//========================= Get Materai ==============================
						//================= Ambil bulan tagihan for header =====================

							if($tagihanbarisnya->tagihanbulantagihan + 1 == 2 )
							{
								$bulanheader = "PEBRUARI";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 3)
							{
								$bulanheader = "MARET";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 4)
							{
								$bulanheader = "APRIL";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 5)
							{
								$bulanheader = "MEI";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 6)
							{
								$bulanheader = "JUNI";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 7)
							{
								$bulanheader = "JULI";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 8)
							{
								$bulanheader = "AGUSTUS";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 9)
							{
								$bulanheader = "SEPTEMBER";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 10)
							{
								$bulanheader = "OKTOBER";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 11)
							{
								$bulanheader = "NOPEMBER";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 12)
							{
								$bulanheader = "DESEMBER";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan;								
							}
							elseif($tagihanbarisnya->tagihanbulantagihan + 1 == 13)
							{
								$bulanheader = "JANUARI";
								$tahunheader = $tagihanbarisnya->tagihantahuntagihan + 1;								
							}

						//================= Ambil bulan tagihan for header =====================
				
						$pdf->AddPage();
    					$pdf->SetFont('Courier','B',10);
						$pdf->Cell(0  ,0.4,'BUKTI PEMBAYARAN TAGIHAN AIR PDAM TKR KAB. TANGERANG',0,1, 'C');
						$pdf->Cell(0  ,0.4,'BULAN / TAHUN : ' . $bulanheader . ' ' .$tahunheader ,0,1, 'C');
						$pdf->Cell(2 ,0.2,' ',0,1,'C');							

    					$pdf->SetFont('Courier','',10);
						$pdf->Cell(3.1 ,0.35,'NO. SAMBUNGAN',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(6 ,0.35, $tagihanbarisnya->tagihannosambungan ,0,0, 'L');
			
						$pdf->Cell(3.1 ,0.35,'WILAYAH',0,0,'L');
						$pdf->Cell(0.3 ,0.35,':',0,0, 'L');
						$pdf->Cell(1 ,0.35, $tagihanbarisnya->tagihankdwilayah,0,0, 'L');
						
						$pdf->Cell(6 ,0.35,'ANGKA METER',0,1,'C');
						
						$pdf->Cell(3.1 ,0.35,'NAMA',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(6 ,0.35, $tagihanbarisnya->tagihannamapelanggan ,0,0, 'L');
			
						$pdf->Cell(3.1 ,0.35,'TARIP',0,0,'L');
						$pdf->Cell(0.3 ,0.35,':',0,0, 'L');
						$pdf->Cell(1 ,0.35, $tagihanbarisnya->tagihankdgolongan,0,0, 'L');
						
						$pdf->Cell(2 ,0.35,'AKHIR',0,0,'C');
						$pdf->Cell(2 ,0.35,'AWAL',0,0,'C');
						$pdf->Cell(2 ,0.35,'M3',0,1,'C');
						
						$pdf->Cell(3.1 ,0.35,'ALAMAT',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(6 ,0.35, $tagihanbarisnya->tagihanalamatpelanggan ,0,0, 'L');
			
						$pdf->Cell(3.1 ,0.35,'DIA.(mm)',0,0,'L');
						$pdf->Cell(0.3 ,0.35,':',0,0, 'L');
						$pdf->Cell(1 ,0.35, $tagihanbarisnya->tagihandiameter,0,0, 'L');
						
						$pdf->Cell(2 ,0.35,$tagihanbarisnya->tagihanangkamakhir,0,0,'C');
						$pdf->Cell(2 ,0.35,$tagihanbarisnya->tagihanangkamawal,0,0,'C');
						$pdf->Cell(2 ,0.35,$tagihanbarisnya->tagihanjumpakai,0,1,'C');		
						
						$pdf->Cell(2 ,0.15,' ',0,1,'C');	
						
						//$pdf->Ln();	
						$pdf->Cell(0  ,0.35,'PERINCIAN TAGIHAN YANG HARUS DIBAYAR ',0,1, 'L');
						//$pdf->Ln();	
						
						$pdf->Cell(2 ,0.15,' ',0,1,'C');	
						
						$pdf->Cell(5.5 ,0.35,'Biaya Pemakaian Air ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.35, $this->separator($tagihanbarisnya->tagihanjumbayar) ,0,0, 'R');

						$pdf->Cell(3.5 ,0.35,'Dibayar Tgl. ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(3 ,0.35, date('j'.'/'.'m'.'/'.'Y') ,0,1, 'R');
											
						$pdf->Cell(5.5 ,0.35,'Biaya Beban Tetap ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.35, '0' ,0,0, 'R');
						
						$pdf->Cell(3.5 ,0.35,'Loket ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(3 ,0.35, $this->session->userdata('loket') ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.35,'Biaya Administrasi ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.35, $this->separator($tagihanbarisnya->tagihanbiayaadministrasi) ,0,0, 'R');
						
						$pdf->Cell(3.5 ,0.35,'Kasir ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(3 ,0.35, $this->session->userdata('username') ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.35,'Biaya Pemeliharaan ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.35, $this->separator($tagihanbarisnya->tagihanpemeliharaan) ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.35,'Biaya Denda ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.35, $this->separator($denda) ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.35,'Biaya Materai ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.35, $this->separator($materai),0,1, 'R');
						
						$pdf->Cell(5.5 ,0.35,'Jumlah yang harus dibayar ',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.35, $this->separator($tagihanbarisnya->tagihanjumbayar+$tagihanbarisnya->tagihanbiayaadministrasi+$tagihanbarisnya->tagihanpemeliharaan+$denda+$materai),0,1, 'R');

						$pdf->Cell(2 ,0.1,' ',0,1,'C');
						$pdf->Cell(0  ,0.35,'PDAM TKR Menyatakan lembar transaksi ini sebagai bukti pembayaran yang sah.',0,1, 'L');
						$pdf->Cell(0  ,0.35,'Pelayanan dan Gangguan : Telp. 55794863',0,1, 'L');
			}
			else
			{
//				foreach ($tagihanhasil as $row)
//					{
//						
//				//========================= Get Denda ==============================
//				if($row->rp_denda<=15000)
//					{
//					$denda=$row->rp_denda;
//					}
//					else
//					{
//					$denda=15000;
//					}						
//				//========================= Get Denda ==============================
//				//========================= Get Materai ==============================
//				if($row->tagihantagihanbulan+$denda<=250000)
//				{
//					$materai=0;
//				}
//				else
//				{
//					if($row->tagihantagihanbulan+$denda<=1000000)
//						{
//							$materai=3000;
//						}
//						else
//						{
//							$materai=6000;
//						}
//				}
//				//========================= Get Materai ==============================
//						
//						//================= Ambil bulan tagihan for header =====================
//
//							if($row->tagihanbulantagihan + 1 == 2 )
//							{
//								$bulanheader = "PEBRUARI";
//								$tahunheader = $row->tagihantahuntagihan;
//							}
//							elseif($row->tagihanbulantagihan + 1 == 3)
//							{
//								$bulanheader = "MARET";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 4)
//							{
//								$bulanheader = "APRIL";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 5)
//							{
//								$bulanheader = "MEI";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 6)
//							{
//								$bulanheader = "JUNI";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 7)
//							{
//								$bulanheader = "JULI";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 8)
//							{
//								$bulanheader = "AGUSTUS";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 9)
//							{
//								$bulanheader = "SEPTEMBER";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 10)
//							{
//								$bulanheader = "OKTOBER";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 11)
//							{
//								$bulanheader = "NOPEMBER";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 12)
//							{
//								$bulanheader = "DESEMBER";
//								$tahunheader = $row->tagihantahuntagihan;								
//							}
//							elseif($row->tagihanbulantagihan + 1 == 13)
//							{
//								$bulanheader = "JANUARI";
//								$tahunheader = $row->tagihantahuntagihan + 1;								
//							}
//
//						//================= Ambil bulan tagihan for header =====================
//						
//						
//						$pdf->AddPage();
//    					$pdf->SetFont('Courier','B',10);
//						$pdf->Cell(0  ,0.4,'BUKTI PEMBAYARAN TAGIHAN AIR PDAM TKR KAB. TANGERANG',0,1, 'C');
//						$pdf->Cell(0  ,0.4,'BULAN / TAHUN : ' . $bulanheader . ' ' .$tahunheader ,0,1, 'C');
//						$pdf->Cell(2 ,0.2,' ',0,1,'C');							
//
//    					$pdf->SetFont('Courier','',10);
//						$pdf->Cell(3.1 ,0.35,'NO. SAMBUNGAN',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(6 ,0.35, $row->tagihannosambungan ,0,0, 'L');
//			
//						$pdf->Cell(3.1 ,0.35,'WILAYAH',0,0,'L');
//						$pdf->Cell(0.3 ,0.35,':',0,0, 'L');
//						$pdf->Cell(1 ,0.35, $row->tagihankdwilayah,0,0, 'L');
//						
//						$pdf->Cell(6 ,0.35,'ANGKA METER',0,1,'C');
//						
//						$pdf->Cell(3.1 ,0.35,'NAMA',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(6 ,0.35, $row->tagihannamapelanggan ,0,0, 'L');
//			
//						$pdf->Cell(3.1 ,0.35,'TARIP',0,0,'L');
//						$pdf->Cell(0.3 ,0.35,':',0,0, 'L');
//						$pdf->Cell(1 ,0.35, $row->tagihankdgolongan,0,0, 'L');
//						
//						$pdf->Cell(2 ,0.35,'AKHIR',0,0,'C');
//						$pdf->Cell(2 ,0.35,'AWAL',0,0,'C');
//						$pdf->Cell(2 ,0.35,'M3',0,1,'C');
//						
//						$pdf->Cell(3.1 ,0.35,'ALAMAT',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(6 ,0.35, $row->tagihanalamatpelanggan ,0,0, 'L');
//			
//						$pdf->Cell(3.1 ,0.35,'DIA.(mm)',0,0,'L');
//						$pdf->Cell(0.3 ,0.35,':',0,0, 'L');
//						$pdf->Cell(1 ,0.35, $row->tagihandiameter,0,0, 'L');
//						
//						$pdf->Cell(2 ,0.35,$row->tagihanangkamakhir,0,0,'C');
//						$pdf->Cell(2 ,0.35,$row->tagihanangkamawal,0,0,'C');
//						$pdf->Cell(2 ,0.35,$row->tagihanjumpakai,0,1,'C');		
//						
//						$pdf->Cell(2 ,0.15,' ',0,1,'C');	
//						
//						//$pdf->Ln();	
//						$pdf->Cell(0  ,0.35,'PERINCIAN TAGIHAN YANG HARUS DIBAYAR ',0,1, 'L');
//						//$pdf->Ln();	
//						
//						$pdf->Cell(2 ,0.15,' ',0,1,'C');	
//						
//						$pdf->Cell(5.5 ,0.35,'Biaya Pemakaian Air ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.35, $this->separator($row->tagihanjumbayar) ,0,0, 'R');
//
//						$pdf->Cell(3.5 ,0.35,'Dibayar Tgl. ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(3 ,0.35, date('j'.'/'.'m'.'/'.'Y') ,0,1, 'R');
//											
//						$pdf->Cell(5.5 ,0.35,'Biaya Beban Tetap ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.35, '0' ,0,0, 'R');
//						
//						$pdf->Cell(3.5 ,0.35,'Loket ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(3 ,0.35, $this->session->userdata('loket') ,0,1, 'R');
//						
//						$pdf->Cell(5.5 ,0.35,'Biaya Administrasi ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.35, $this->separator($row->tagihanbiayaadministrasi) ,0,0, 'R');
//						
//						$pdf->Cell(3.5 ,0.35,'Kasir ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(3 ,0.35, $this->session->userdata('username') ,0,1, 'R');
//						
//						$pdf->Cell(5.5 ,0.35,'Biaya Pemeliharaan ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.35, $this->separator($row->tagihanpemeliharaan) ,0,1, 'R');
//						
//						$pdf->Cell(5.5 ,0.35,'Biaya Denda ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.35, $this->separator($denda) ,0,1, 'R');
//						
//						$pdf->Cell(5.5 ,0.35,'Biaya Materai ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.35, $this->separator($materai),0,1, 'R');
//						
//						$pdf->Cell(5.5 ,0.35,'Jumlah yang harus dibayar ',0,0, 'L');
//						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.35, $this->separator($row->tagihanjumbayar+$row->tagihanbiayaadministrasi+$row->tagihanpemeliharaan+$denda+$materai),0,1, 'R');
//
//						$pdf->Cell(2 ,0.1,' ',0,1,'C');
//						$pdf->Cell(0  ,0.35,'PDAM TKR Menyatakan lembar transaksi ini sebagai bukti pembayaran yang sah.',0,1, 'L');
//						$pdf->Cell(0  ,0.35,'Pelayanan dan Gangguan : Telp. 55794863',0,1, 'L');
//					}
			}
			
	        $pdf->Output();
	}
	

}
