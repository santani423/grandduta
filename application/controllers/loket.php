<?php
/**
 * Home Class
 *
 * @author	Trias Bratakusuma <bratatkr@gmail.com>
 */
class Loket extends CI_Controller {
	/**
	 * Constructor
	 */
	function loket()
	{
		parent::__Construct();
		$this->load->model('Loket_model', '', TRUE);
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('form');				
		$this->load->library('pagination');
		$this->load->library('table');
        $this->load->library('cfpdf');
	}
	
	var $title = 'Transaksi Loket';
	
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
		
		$data['main_view'] = 'loket/transaksi_view';
		$data['left_view'] = 'menuloket.php';
		$data['form_action'] = site_url('loket/get_transaksi');
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
		
		$data['main_view'] = 'loket/infotag_print';
		$data['left_view'] = 'menuloket.php';
		$data['form_action'] = site_url('loket/bayar');
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
		
		$nosl = $this->input->post('nosambungan');
		
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
			$num_rowstag=$tagihan->num_rows();
			$tagihanbaris=$tagihan->row();
			
			if($num_rowstag==0)
			{
				$tagihanallstate = $this->Loket_model->get_tagihan_allstate($nosl);
				$tagihanbarisallstate=$tagihanallstate->row();
				
			$this->session->set_flashdata('message', 'Tagihan Sudah Lunas Pada Tanggal : '. $tagihanbarisallstate->tanggalbayar. ' di Loket : ' . $tagihanbarisallstate->loket);
			redirect('loket');				
			}
			else
			{
		
			// ==============Header================
			$data['default']['nosl']=$tagihanbaris->idipkl;
			$data['default']['nama']=$tagihanbaris->nama;
			$data['default']['alamat']=$tagihanbaris->blok + ' No. Kav ' + $tagihanbaris->nokav;
			$data['default']['gol']=$tagihanbaris->kdgol;
			// ==============Header================	
			
			
			// Get data dari tabel tagihan dengan caltagihan
			if ($num_rowstag == 1)
			{
				// set table template for zebra row
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
						  'row_alt_start'=>'<tr class="zebra">',
						  'row_alt_end'=>'</tr>'
					  	);
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('BULAN','GOL','TAGIHAN','LOKET','TGL BAYAR');
				
/*				//========================= Get Denda ==============================
				if($tagihanbaris->rp_denda<=15000)
					{
					$denda=$tagihanbaris->rp_denda;
					}
					else
					{
					$denda=15000;
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
				//========================= Get Materai ==============================*/
											
				$this->table->add_row($tagihanbaris->bulan, $tagihanbaris->kdgol , $this->separator($tagihanbaris->tagihan),$tagihanbaris->loket,$tagihanbaris->tanggalbayar);
				
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
				$this->table->set_heading('BULAN','GOL','TAGIHAN','LOKET','TGL BAYAR');
								
				foreach ($tagihanhasil as $row)
					{

/*						//========================= Get Denda ==============================
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
						//========================= Get Materai ==============================*/
						
						$this->table->add_row($row->bulan, $row->kdgol , $this->separator($row->tagihan),$row->loket,$row->tanggalbayar);
					}
				
				$data['table'] = $this->table->generate();
			}
			}
		}
				
		$this->load->view('template', $data);
	}
	
	function bayar()
	{
		$nosl = $this->input->post('nosl');
		//echo $nosl;
		
			//===============================================================================
			$pdf = new FPDF("L","cm", array(21.6, 9.32));
						
			//================================= Mulai Cetak Pelanggan =======================
			
			//================================= Ambil Pelanggan Akan dicetak ================
			$tagihan = $this->Loket_model->get_tagihan($nosl);
			$tagihanhasil=$tagihan->result();
			$num_rows=$tagihan->num_rows();
			$tagihanbaris=$tagihan->row();
		
			// ==============Header================
			$nosl=$tagihanbaris->idipkl;
			$nama=$tagihanbaris->nama;
			$alamat=$tagihanbaris->$tagihanbaris->blok + ' No. Kav ' + $tagihanbaris->nokav;
			$gol=$tagihanbaris->kdgol;
			// ==============Header================	
			
			
			// Get data dari tabel tagihan dengan caltagihan
			if ($num_rows == 1)
			{
/*				//========================= Get Denda ==============================
				if($tagihanbaris->rp_denda<=15000)
					{
					$denda=$tagihanbaris->rp_denda;
					}
					else
					{
					$denda=15000;
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
				//========================= Get Materai ==============================*/
						//================= Ambil bulan tagihan for header =====================

							if($tagihanbaris->tagihanbulantagihan == 2 )
							{
								$bulanheader = "PEBRUARI";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;
							}
							elseif($tagihanbaris->tagihanbulantagihan == 3)
							{
								$bulanheader = "MARET";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 4)
							{
								$bulanheader = "APRIL";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 5)
							{
								$bulanheader = "MEI";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 6)
							{
								$bulanheader = "JUNI";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 7)
							{
								$bulanheader = "JULI";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 8)
							{
								$bulanheader = "AGUSTUS";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 9)
							{
								$bulanheader = "SEPTEMBER";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 10)
							{
								$bulanheader = "OKTOBER";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 11)
							{
								$bulanheader = "NOPEMBER";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 12)
							{
								$bulanheader = "DESEMBER";
								$tahunheader = $tagihanbaris->tagihantahuntagihan;								
							}
							elseif($tagihanbaris->tagihanbulantagihan == 13)
							{
								$bulanheader = "JANUARI";
								$tahunheader = $tagihanbaris->tahun + 1;								
							}

						//================= Ambil bulan tagihan for header =====================
				
						$pdf->AddPage();
						$pdf->SetMargins(1,1,1);
						$pdf->SetXY(0,0.8);
						$pdf->SetMargins(1,0.5,1);
    					$pdf->SetFont('Arial','B',10);
						$pdf->Cell(0  ,0.4,'BUKTI PEMBAYARAN IPKL PERUMAHAN GRAND DUTA TANGERANG',0,1, 'C');
						$pdf->Cell(0  ,0.4,'BULAN / TAHUN : ' . $bulanheader . ' ' .$tahunheader ,0,1, 'C');
						$pdf->Cell(2 ,0.2,' ',0,1,'C');							

    					$pdf->SetFont('Arial','',10);
						$pdf->Cell(3.1 ,0.4,'ID IPKL',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(6 ,0.4, $tagihanbaris->idipkl ,0,0, 'L');
			
/*						$pdf->Cell(3.1 ,0.4,'WILAYAH',0,0,'L');
						$pdf->Cell(0.3 ,0.4,':',0,0, 'L');
						$pdf->Cell(1 ,0.4, $tagihanbaris->tagihankdwilayah,0,0, 'L');
						
						$pdf->Cell(6 ,0.4,'ANGKA METER',0,1,'C');*/
						
						$pdf->Cell(3.1 ,0.4,'NAMA',0,0, 'L');
						$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
						$pdf->Cell(6 ,0.4, $tagihanbaris->nama ,0,0, 'L');
			
						$pdf->Cell(3.1 ,0.4,'TARIP',0,0,'L');
						$pdf->Cell(0.3 ,0.4,':',0,0, 'L');
						$pdf->Cell(1 ,0.4, $tagihanbaris->kdgol,0,0, 'L');
						
/*						$pdf->Cell(2 ,0.4,'AKHIR',0,0,'C');
						$pdf->Cell(2 ,0.4,'AWAL',0,0,'C');
						$pdf->Cell(2 ,0.4,'M3',0,1,'C');
						*/
						
						$pdf->Cell(3.1 ,0.4,'ALAMAT',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(6 ,0.4, $tagihanbaris->blok ,0,0, 'L');
			
/*						$pdf->Cell(3.1 ,0.4,'DIA.(mm)',0,0,'L');
						$pdf->Cell(0.3 ,0.4,':',0,0, 'L');
						$pdf->Cell(1 ,0.4, $tagihanbaris->tagihandiameter,0,0, 'L');
						
						$pdf->Cell(2 ,0.4,$tagihanbaris->tagihanangkamakhir,0,0,'C');
						$pdf->Cell(2 ,0.4,$tagihanbaris->tagihanangkamawal,0,0,'C');
						$pdf->Cell(2 ,0.4,$tagihanbaris->tagihanjumpakai,0,1,'C');	*/	
						
						$pdf->Cell(2 ,0.15,' ',0,1,'C');	
						
						//$pdf->Ln();	
						$pdf->Cell(0  ,0.4,'PERINCIAN TAGIHAN YANG HARUS DIBAYAR ',0,1, 'L');
						//$pdf->Ln();	
						
						$pdf->Cell(2 ,0.15,' ',0,1,'C');	
						
						$pdf->Cell(5.5 ,0.4,'Tagihan ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, $this->separator($tagihanbaris->tagihan) ,0,0, 'R');

						$pdf->Cell(3.5 ,0.4,'Dibayar Tgl. ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(3 ,0.4, date('j'.'/'.'m'.'/'.'Y') ,0,1, 'R');
											
/*						$pdf->Cell(5.5 ,0.4,'Biaya Beban Tetap ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, '0' ,0,0, 'R');*/
						
						$pdf->Cell(3.5 ,0.4,'Loket ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(3 ,0.4, $this->session->userdata('loket') ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Biaya Administrasi ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.4, $this->separator($tagihanbaris->tagihanbiayaadministrasi) ,0,0, 'R');
						
						$pdf->Cell(3.5 ,0.4,'Kasir ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(3 ,0.4, $this->session->userdata('username') ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Biaya Pemeliharaan ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.4, $this->separator($tagihanbaris->tagihanpemeliharaan) ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Biaya Denda ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.4, $this->separator($denda) ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Biaya Materai ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
//						$pdf->Cell(4 ,0.4, $this->separator($materai),0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Jumlah yang harus dibayar ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, $this->separator($tagihanbaris->tagihan),0,1, 'R');

						$pdf->Cell(2 ,0.1,' ',0,1,'C');
						$pdf->Cell(0  ,0.3,'Grand Duta Menyatakan lembar transaksi ini sebagai bukti pembayaran yang sah.',0,1, 'L');
						$pdf->Cell(0  ,0.3,'Pelayanan dan Gangguan : Telp. 555555555',0,1, 'L');														
//				$this->table->add_row($tagihanbaris->tagihanbulantagihan, $this->separator($tagihanbaris->tagihanangkamawal) , $this->separator($tagihanbaris->tagihanangkamakhir),$this->separator($tagihanbaris->tagihanjumpakai),$this->separator($tagihanbaris->tagihantagihanbulan),$tagihanbaris->rp_denda,$this->separator($materai),$this->separator($tagihanbaris->tagihantagihanbulan+$tagihanbaris->rp_denda+$materai));

			}
			else
			{
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
						
						//================= Ambil bulan tagihan for header =====================

							if($row->tagihanbulantagihan + 1 == 2 )
							{
								$bulanheader = "PEBRUARI";
								$tahunheader = $row->tagihantahuntagihan;
							}
							elseif($row->tagihanbulantagihan + 1 == 3)
							{
								$bulanheader = "MARET";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 4)
							{
								$bulanheader = "APRIL";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 5)
							{
								$bulanheader = "MEI";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 6)
							{
								$bulanheader = "JUNI";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 7)
							{
								$bulanheader = "JULI";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 8)
							{
								$bulanheader = "AGUSTUS";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 9)
							{
								$bulanheader = "SEPTEMBER";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 10)
							{
								$bulanheader = "OKTOBER";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 11)
							{
								$bulanheader = "NOPEMBER";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 12)
							{
								$bulanheader = "DESEMBER";
								$tahunheader = $row->tagihantahuntagihan;								
							}
							elseif($row->tagihanbulantagihan + 1 == 13)
							{
								$bulanheader = "JANUARI";
								$tahunheader = $row->tagihantahuntagihan + 1;								
							}

						//================= Ambil bulan tagihan for header =====================
						
						
						$pdf->AddPage();
						$pdf->SetMargins(1,1,1);
						$pdf->SetXY(0,0.8);
    					$pdf->SetFont('Arial','B',11);
						$pdf->Cell(0  ,0.4,'BUKTI PEMBAYARAN TAGIHAN AIR PDAM TKR KAB. TANGERANG',0,1, 'C');
						$pdf->Cell(0  ,0.4,'BULAN / TAHUN : ' . $bulanheader . ' ' .$tahunheader ,0,1, 'C');
						$pdf->Cell(2 ,0.2,' ',0,1,'C');							

    					$pdf->SetFont('Arial','',10);
						$pdf->Cell(3.1 ,0.4,'NO. SAMBUNGAN',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(6 ,0.4, $row->tagihannosambungan ,0,0, 'L');
			
						$pdf->Cell(3.1 ,0.4,'WILAYAH',0,0,'L');
						$pdf->Cell(0.3 ,0.4,':',0,0, 'L');
						$pdf->Cell(1 ,0.4, $row->tagihankdwilayah,0,0, 'L');
						
						$pdf->Cell(6 ,0.4,'ANGKA METER',0,1,'C');
						
						$pdf->Cell(3.1 ,0.4,'NAMA',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(6 ,0.4, $row->tagihannamapelanggan ,0,0, 'L');
			
						$pdf->Cell(3.1 ,0.4,'TARIP',0,0,'L');
						$pdf->Cell(0.3 ,0.4,':',0,0, 'L');
						$pdf->Cell(1 ,0.4, $row->tagihankdgolongan,0,0, 'L');
						
						$pdf->Cell(2 ,0.4,'AKHIR',0,0,'C');
						$pdf->Cell(2 ,0.4,'AWAL',0,0,'C');
						$pdf->Cell(2 ,0.4,'M3',0,1,'C');
						
						$pdf->Cell(3.1 ,0.4,'ALAMAT',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(6 ,0.4, $row->tagihanalamatpelanggan ,0,0, 'L');
			
						$pdf->Cell(3.1 ,0.4,'DIA.(mm)',0,0,'L');
						$pdf->Cell(0.3 ,0.4,':',0,0, 'L');
						$pdf->Cell(1 ,0.4, $row->tagihandiameter,0,0, 'L');
						
						$pdf->Cell(2 ,0.4,$row->tagihanangkamakhir,0,0,'C');
						$pdf->Cell(2 ,0.4,$row->tagihanangkamawal,0,0,'C');
						$pdf->Cell(2 ,0.4,$row->tagihanjumpakai,0,1,'C');		
						
						$pdf->Cell(2 ,0.15,' ',0,1,'C');	
						
						//$pdf->Ln();	
						$pdf->Cell(0  ,0.4,'PERINCIAN TAGIHAN YANG HARUS DIBAYAR ',0,1, 'L');
						//$pdf->Ln();	
						
						$pdf->Cell(2 ,0.15,' ',0,1,'C');	
						
						$pdf->Cell(5.5 ,0.4,'Biaya Pemakaian Air ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, $this->separator($row->tagihanjumbayar) ,0,0, 'R');

						$pdf->Cell(3.5 ,0.4,'Dibayar Tgl. ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(3 ,0.4, date('j'.'/'.'m'.'/'.'Y') ,0,1, 'R');
											
						$pdf->Cell(5.5 ,0.4,'Biaya Beban Tetap ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, '0' ,0,0, 'R');
						
						$pdf->Cell(3.5 ,0.4,'Loket ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(3 ,0.4, $this->session->userdata('loket') ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Biaya Administrasi ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, $this->separator($row->tagihanbiayaadministrasi) ,0,0, 'R');
						
						$pdf->Cell(3.5 ,0.4,'Kasir ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(3 ,0.4, $this->session->userdata('username') ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Biaya Pemeliharaan ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, $this->separator($row->tagihanpemeliharaan) ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Biaya Denda ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, $this->separator($denda) ,0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Biaya Materai ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, $this->separator($materai),0,1, 'R');
						
						$pdf->Cell(5.5 ,0.4,'Jumlah yang harus dibayar ',0,0, 'L');
						$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
						$pdf->Cell(4 ,0.4, $this->separator($row->tagihanjumbayar+$row->tagihanbiayaadministrasi+$row->tagihanpemeliharaan+$denda+$materai),0,1, 'R');

						$pdf->Cell(2 ,0.1,' ',0,1,'C');
						$pdf->Cell(0  ,0.3,'PDAM TKR Menyatakan lembar transaksi ini sebagai bukti pembayaran yang sah.',0,1, 'L');
						$pdf->Cell(0  ,0.3,'Pelayanan dan Gangguan : Telp. 55794863',0,1, 'L');
					}
			}
			
			//=========================== Akhir Cetak Pelanggan

	        $pdf->Output();
	}
		
}
