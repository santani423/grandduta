<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penagihan_cicilan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->load->library('cfpdf');
		//$this->load->library('form_validation');                
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
			
			$data['action']  = 'penagihan_cicilan/querytagihan';
			
			// set judul halaman
			$data['judulpage'] = "Penagihan Cicilan";
			
			$this->template->load('template','penagihan/transaksi_view',$data);
		}
	}
	
	public function querytagihan()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
				
			// load model 'usermodel'
			$this->load->model('Master_model');
			$this->load->model('Penagihan_model');
	
			$id=$this->input->post('idipkl');
	
			// level untuk user ini
			$level = $this->session->userdata('level');
	
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
	
			$data['action']  = 'penagihan_cicilan/querytagihanterbatas';
	
			// set judul halaman
			$data['judulpage'] = "Rincian Penagihan";
				
			// 			$pelanggan = $this->Master_model->get_one($id);
			// 			$pelangganbaris = $pelanggan->result();
                        
                        $adatagihan = $this->Penagihan_model->get_jml_tagihan_belumlunas($id);	
			$pelangganbaris = $this->Penagihan_model->get_onepelanggan($id)->row();
			
                        //echo $adatagihan;
                        //echo $pelangganbaris;
                        if($adatagihan==0)
                        {
                            $this->template->load('template','penagihan/transaksi_view',$data);
                        }
                        else
                        {
                            if($pelangganbaris)
                            {
                                //echo $pelangganbaris->ID_BORK;
                                //if($pelangganbaris->ID_BORK == 'K')
                                //    {
                                //        $this->template->load('template','penagihan/transaksi_view',$data);
                                //    }
                                //else
                                //    {
                                    $data['default']['idipkl']=$pelangganbaris->ID_IPKL;
                                    $data['default']['nama']=$pelangganbaris->NAMA_PELANGGAN;
                                    $data['default']['blok']=$pelangganbaris->BLOK;
                                    $data['default']['nokav']=$pelangganbaris->NO_KAVLING;
                                    $data['default']['namacluster']=$pelangganbaris->NAMA_CLUSTER;
				
                                    $data['tagihans'] = $this->Penagihan_model->get_tagihan_belumlunas($id);
                                    $data['totalnya'] = $this->Penagihan_model->get_tagihantotal_belumlunas($id);
    
                                    $this->template->load('template','penagihan/rinciantagihancicilan1_view',$data);
                                //    }
                            }
                            else
                            {
                                $this->template->load('template','penagihan/transaksi_view',$data);
                            }
                        }
		}
	}
	
	public function separator($num, $suffix = '')
	{
		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
	
		return $result ;
	}
	
	public function querytagihanterbatas()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
			
			// load model 'usermodel'
			$this->load->model('Master_model');
			$this->load->model('Penagihan_model');
			
			$id=$this->input->post('idipkl');
			$blntag=$this->input->post('blntagihan');
			
			// level untuk user ini
			$level = $this->session->userdata('level');
			
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);

// 			echo $id;
// 			echo $blntag;
			
			$data['action']  = 'penagihan_cicilan/bayartagihanterbatas';
			
			// set judul halaman
			$data['judulpage'] = "Rincian Penagihan";
			$data['isicarabayar'] = $this->Penagihan_model->getCaraBayarList();
			$data['isilewatbayar'] = $this->Penagihan_model->getLewatBayarList();
			
			// 			$pelanggan = $this->Master_model->get_one($id);
			// 			$pelangganbaris = $pelanggan->result();
			
			$pelangganbaris = $this->Penagihan_model->get_onepelanggan($id)->row();
			$getnomorkuitansi=$this->Penagihan_model->get_nomor_kwitansi();
			$nomorkwitansibaris=$getnomorkuitansi->row();
			$nomorkwitansi='GD.'.$nomorkwitansibaris->tahun.'.'.$nomorkwitansibaris->bulan.'.'.$nomorkwitansibaris->counter;
							
			$data['default']['idipkl']=$pelangganbaris->ID_IPKL;
			$data['default']['nama']=$pelangganbaris->NAMA_PELANGGAN;
			$data['default']['blok']=$pelangganbaris->BLOK;
			$data['default']['nokav']=$pelangganbaris->NO_KAVLING;
			$data['default']['namacluster']=$pelangganbaris->NAMA_CLUSTER;
			$data['default']['blntagihan']=$blntag;
			$data['default']['nokwitansi']=$nomorkwitansi;
			
 			$data['tagihans'] = $this->Penagihan_model->get_tagihan_bylimit($id,$blntag);
//  		$data['totalnya'] = $this->Penagihan_model->get_tagihan_totalbylimit($id,$blntag);
			
                        $this->form_validation->set_rules('cbocarabayar','cbocarabayar','required|callback_select_validate');
			$this->form_validation->set_rules('cbolewatbayar','cbolewatbayar','required|callback_select_validate');
                        
                        $this->template->load('template','penagihan/rinciantagihancicilan2_view',$data);
		                       
            }
	}
	
	public function bayartagihanterbatas()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->login();
		}
		else
		{
			// load model 'usermodel'
			$this->load->model('usermodel');
				
			// load model 'usermodel'
			$this->load->model('Master_model');
			$this->load->model('Penagihan_model');
                        
            		// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
                        $id=$this->input->post('idipkl');
                        $blntag=$this->input->post('blntagihan');
                        $nomorkwitansi=$this->input->post('nokwitansi');
                        $idcarabayarnya=$this->input->post('cbocarabayar');
                        $idlewatbayarnya=$this->input->post('cbolewatbayar');
                        
                        //echo $idcarabayarnya;
                        //echo "<br>";
                        //echo $idlewatbayarnya;
                        //echo "<br>";
                                                
                        //Cek Validasi Form
                        
                        if($idcarabayarnya == "0")
                        {
                            if($idlewatbayarnya == "0")
                            {
                                //echo "Cara Bayar dan Lewat Bayar Harus Diisi.";
                                $this->session->set_flashdata('message', 'Cara Bayar dan Bayar Melalui Harus Dipilih !');
                                redirect(site_url('penagihan_cicilan'));
                            }
                            else 
                            {
                                //echo "Cara Bayar Harus Diisi.";
                                $this->session->set_flashdata('message', 'Cara Bayar Harus Dipilih !');
                                redirect(site_url('penagihan_cicilan'));
                            }
                        }
                        else 
                        {
                            if($idlewatbayarnya == "0")
                            {
                                //echo "Lewat Bayar Harus Diisi.";
                                $this->session->set_flashdata('message', 'Bayar Melalui Harus Dipilih !');
                                redirect(site_url('penagihan_cicilan'));
                            }
                            else 
                            {
                                //echo "Oke";
                            //}
            
                
 			//echo $id;
 			//echo $blntag;
			
			//================================= Mulai Cetak Pelanggan =======================

			$pdf = new FPDF("L","cm", array(21.6, 9.32));
			
			//================================= Ambil Pelanggan Akan dicetak ================
			$tagihan = $this->Penagihan_model->get_tagihan_terbatas($id,$blntag);
// 			$tagihantotal = $this->Penagihan_model->get_tagihan_total($idipklnya);
			$tahunbulan = $this->Penagihan_model->get_tahun_bulan_terbatas($id,$blntag);
			
			$num_rows=$tagihan->num_rows();
			$tagihanbaris=$tagihan->row();
			
// 			$tagihantotalbaris=$tagihantotal->row();
			
			$tahunbulanhasil=$tahunbulan->result();
			$tagihanhasil=$tagihan->result();
			
			$rincianbulantahun='';
			
			foreach ($tahunbulanhasil as $row)
			{
				$rincianbulantahun=$rincianbulantahun . '|' . '(' . $row->tahun . ')' . ' ' . $row->bulan;
			}
			
			$totaltagihan=0;
			$totaldenda=0;
			$jmlbulan=0;
			
			$rincianidtagihan = '';
			
			foreach ($tagihanhasil as $row)
			{
				$totaltagihan=$totaltagihan+$row->tagihan;
				$totaldenda=$totaldenda+$row->denda;
				$jmlbulan=$jmlbulan+1;
			}
			
			$namanya=$tagihanbaris->namapelanggan ;
			$clusternya=$tagihanbaris->namacluster ;
			$bloknya=$tagihanbaris->blok ;
			$nokavlingnya=$tagihanbaris->nokav ;
                        $namabork=$tagihanbaris->namabork ;
			$totaltagihannya=$totaltagihan ;
			$totaldendanya=$totaldenda ;
			$jumlahtotalnya=$totaltagihan+$totaldenda ;
			$jumlahtagihannya=$jmlbulan ;
			$loketnya= "Service Center ";
			$kasirnya= $this->session->userdata('nama');
			
				
			// 		echo $num_rows;
			// 		echo $tagihanbaris->idipkl;
			
			// ==============Header================
			// 		$idipkl=$tagihanbaris->idipkl;
			// 		$nama=$tagihanbaris->namapelanggan;
			// 		$alamat=$tagihanbaris->namacluster + ' Blok ' + $tagihanbaris->blok + ' No. Kav ' + $tagihanbaris->nokav;
			// ==============Header================
				
			//===============================================================================
			
			// Get data dari tabel tagihan dengan caltagihan
			if ($num_rows > 0)
			{
				$pdf->AddPage();
				$pdf->SetMargins(1,1,1);
				$pdf->SetXY(0,0.8);
				$pdf->SetMargins(1,0.5,1);
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(0  ,0.4,'No : '. $nomorkwitansi,0,1, 'R');
				$pdf->Cell(0  ,0.4,'BUKTI PEMBAYARAN IPKL PERUMAHAN GRAND DUTA TANGERANG',0,1, 'C');
				$pdf->Cell(0  ,0.4,'' ,0,1, 'C');
				$pdf->Cell(2 ,0.2,' ',0,1,'C');
					
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(3.1 ,0.4,'ID IPKL',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(6 ,0.4, $tagihanbaris->idipkl ,0,0, 'L');
					
				$pdf->Cell(3.1 ,0.4,'NAMA',0,0, 'L');
				$pdf->Cell(0.3 ,0.35,' : ',0,0, 'C');
				$pdf->Cell(6 ,0.4, $tagihanbaris->namapelanggan ,0,1, 'L');
					
				$pdf->Cell(3.1 ,0.4,'CLUSTER',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(6 ,0.4, $tagihanbaris->namacluster ,0,0, 'L');
			
				$pdf->Cell(3.1 ,0.4,'BLOK',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(3 ,0.4, $tagihanbaris->blok ,0,1, 'L');
			
				$pdf->Cell(3.1 ,0.4,'NO. KAVLING',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(6 ,0.4, $tagihanbaris->nokav ,0,0, 'L');
                                
				$pdf->Cell(3.1 ,0.4,'JENIS ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(6 ,0.4, $tagihanbaris->namabork ,0,1, 'L');
					
				$pdf->Cell(2 ,0.15,' ',0,1,'C');
					
				//$pdf->Ln();
				$pdf->Cell(0  ,0.4,'PERINCIAN TAGIHAN YANG HARUS DIBAYAR ',0,1, 'L');
				//$pdf->Ln();
					
				$pdf->Cell(2 ,0.15,' ',0,1,'C');
					
				$pdf->Cell(5.5 ,0.4,'Total Tagihan IPKL ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, $this->separator($totaltagihan),0,0, 'R');
					
				$pdf->Cell(3.5 ,0.4,'Dibayar Tgl. ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(3 ,0.4, date('j'.'/'.'m'.'/'.'Y') ,0,1, 'R');
			
				$pdf->Cell(5.5 ,0.4,'Total Denda ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, $this->separator($totaldenda) ,0,0, 'R');
					
				$pdf->Cell(3.5 ,0.4,'Loket ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(3 ,0.4, 'Service Center' ,0,1, 'R');
					
				$pdf->Cell(5.5 ,0.4,'Jumlah Total Yang Harus Dibayar ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, $this->separator($totaltagihan+$totaldenda) ,0,0, 'R');
					
				$pdf->Cell(3.5 ,0.4,'Kasir ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(3 ,0.4, $this->session->userdata('nama') ,0,1, 'R');
					
				$pdf->Cell(5.5 ,0.4,'Jumlah Bulan Tagihan ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, $jmlbulan ,0,1, 'R');
					
				$pdf->Cell(5.5 ,0.4,'Perincian Bulan ',0,0, 'L');
				$pdf->Cell(0.3 ,0.4,' : ',0,0, 'C');
				$pdf->Cell(4 ,0.4, ' ' ,0,1, 'R');
					
				if(strlen($rincianbulantahun)<121)
                                {
                                    $pdf->Cell(5.5 ,0.4, $rincianbulantahun ,0,1, 'L');
                                }
                                else
                                {
                                    $pdf->Cell(5.5 ,0.4, substr($rincianbulantahun,0,120) ,0,1, 'L');
                                    $pdf->Cell(5.5 ,0.4, substr($rincianbulantahun,(0-(strlen($rincianbulantahun)-120))),0,0, 'L');
                                }
                                
					
				$pdf->Cell(2 ,0.2,' ',0,1,'C');
				$pdf->Cell(2 ,0.1,' ',0,1,'C');
				$pdf->Cell(2 ,0.15,' ',0,1,'C');
				$pdf->Cell(0  ,0.3,'Grand Duta Menyatakan lembar transaksi ini sebagai bukti pembayaran yang sah. Informasi : Telp. 55 73 0 888',0,0, 'L');

			
			}
			else
			{
			
			
			}
				
			//=========================== Akhir Cetak Pelanggan
			
 			$pdf->Output();
			
                        }
                    }
                        
			$tagihan = $this->Penagihan_model->get_tagihan_terbatas($id,$blntag);
			$tagihanhasil=$tagihan->result();
			
			$rincianidtagihan='';
			$denda=0;
			
			foreach ($tagihanhasil as $row)
			{
// 				$pelunasan = $this->Penagihan_model->update_tagihan_lunas_cicilan($row->idtagihan, $row->denda, '1')->result();
								
				$rincianidtagihan= $rincianidtagihan . '\',\' ' . $row->idtagihan;
				$denda=$row->denda;				
			}

			$usernya=$this->session->userdata('user_id');
			
			$nokwit=$this->Penagihan_model->insert_kwitansi($nomorkwitansi,
						$id,
						$namanya,
						$clusternya,
						$bloknya,
						$nokavlingnya,
						$totaltagihannya,
						$totaldendanya,
						$jumlahtotalnya,
						$jumlahtagihannya,
						$loketnya,
						$kasirnya,
						$rincianbulantahun,
						$idcarabayarnya,
						$idlewatbayarnya);
			
			$this->update_lunas_cicilan($rincianidtagihan, $row->denda, $usernya,$nomorkwitansi);
		
                            
                        }
         
	}
	
	public function update_lunas_cicilan($idtagihannya,$dendanya,$usernya,$nomorkwitansinya)
	{
            $pelunasan = $this->Penagihan_model->update_tagihan_lunas_cicilan($idtagihannya,$dendanya,$usernya,$nomorkwitansinya)->result();
	}
       
}