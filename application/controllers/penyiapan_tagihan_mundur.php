<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penyiapan_tagihan_mundur extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
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
			
			// load model 'master_model'
			$this->load->model('Master_model');
				
			// level untuk user ini
			$level = $this->session->userdata('level');
				
			// ambil menu dari database sesuai dengan level
			$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
			// ambil data buat combo
			$data['isicluster'] = $this->Master_model->getClusterList();
                        $data['isibork'] = $this->Master_model->getBorK();
			
			$data['action']  = 'penyiapan_tagihan_mundur/queryinfotagihan';
			
			// set judul halaman
			$data['judulpage'] = "Penyiapan Tagihan";
			
			$this->template->load('template','penagihan/penyiapantagihan_view',$data);
		}
	}
	
	function queryinfotagihan()
	{
		// load model 'usermodel'
		$this->load->model('usermodel');
		
		// load model 'usermodel'
		$this->load->model('Penagihan_model');
		
		// level untuk user ini
		$level = $this->session->userdata('level');
		

		// ambil menu dari database sesuai dengan level
		$data['menu'] = $this->usermodel->get_menu_for_level($level);
			
		// set judul halaman
		$data['judulpage'] = "Penyiapan Tagihan";
		
		$cluster = $this->input->post('cbocluster');
                $idbork = $this->input->post('cbobork');
		$tahun = $this->input->post('cbotahun');
		$bulan = $this->input->post('cbobulan');
		
		// Tentukan Data yang mau digenerate, Bangunan, Kavling Pelanggan atau Kavling Developer
                switch($idbork)
                {
                    case 'B':
                        //echo "Generate Bangunan";
                        
                        $jml_tagihan_bangunan = $this->Penagihan_model->count_tagihan_bangunan_percluster($cluster, $tahun, $bulan);
		
                        // Cek Apakah sudah Dibayar
                        $jml_pembayar_bangunan = $this->Penagihan_model->count_tagihan_bangunan_percluster_telahdibayar($cluster, $tahun, $bulan);
		
                        if($tahun < date('y'))
                        {
                            // Tampilkan Peringatan Tidak dapat Generate Tagihan
                            // echo "Tampilkan Peringatan";
                            $data['pesan'] = "Anda tidak dapat meng-generate data tahun sebelumnya";
                            $data['jml_terupdate'] = $jml_tagihan;
                            $data['cluster'] = $cluster;
                        }
                        else 
                        {
                            //if($bulan<=(date('m')-1))
                            //{
				//$data['pesan'] = "Anda tidak dapat meng-generate data bulan sebelumnya";
				//$data['jml_terupdate'] = $jml_tagihan;
				//$data['cluster'] = $cluster;
                           //}
                           //else 
                           //{
				if ($jml_tagihan_bangunan <> 0)
				{
                                    if ($jml_pembayar_bangunan <> 0)
                                    {
                                        // Generate Tagihan dengan memindahkan data deposit terlebih dahulu 
                                        // echo "Tampilkan Peringatan";
                                        
                                        //echo $cluster;
                                        //echo "<br>";
                                        //echo $tahun;
                                        //echo "<br>";
                                        //echo $bulan;
                                        
                                        $mttd = $this->Penagihan_model->move_tagihan_bangunan_to_tagihandeposit_perclusterperbulan($cluster, $tahun, $bulan);
                                        
                                        $deltag = $this->Penagihan_model->delete_tagihan_bangunan_perclusterperbulan($cluster, $tahun, $bulan);
					$gentag = $this->Penagihan_model->insert_tagihan_bangunan_perclusterperbulan($cluster, $tahun, $bulan);

                                        $idhasil=$this->Penagihan_model->get_idipkl_from_deposit($tahun,$bulan)->result();
                                        
                                        foreach ($idhasil as $row)
                                        {
                                            $btt = $this->Penagihan_model->back_tagihan_bangunan_from_tagihandeposit_perclusterperbulan($row->idipkl, $tahun, $bulan);
                                        }
                                                                                
                                        $data['pesan'] = "Terdapat data Deposito dan telah dibayar";
                                        
                                        $jml_tagihan_generated = $this->Penagihan_model->count_tagihan_bangunan_percluster($cluster, $tahun, $bulan);
                                        $data['jml_terupdate'] = $jml_tagihan_generated;
                                        $data['cluster'] = $cluster;
                                        
                                        $genpiutang = $this->Penagihan_model->insert_piutang_bangunan_perclusterpertahunperbulan($cluster, $tahun, $bulan);
                                    }
                                    else
                                    {
					// Generate Tagihan
					$deltag = $this->Penagihan_model->delete_tagihan_bangunan_perclusterperbulan($cluster, $tahun, $bulan);
					$gentag = $this->Penagihan_model->insert_tagihan_bangunan_perclusterperbulan($cluster, $tahun, $bulan);
                                        
					$jml_tagihan_generated = $this->Penagihan_model->count_tagihan_bangunan_percluster($cluster, $tahun, $bulan);
				
					// echo "Generate Tagihan";
					$data['pesan'] = "Data berhasil digenerate";
					$data['jml_terupdate'] = $jml_tagihan_generated;
					$data['cluster'] = $cluster;
                                                
                                        $genpiutang = $this->Penagihan_model->insert_piutang_bangunan_perclusterpertahunperbulan($cluster, $tahun, $bulan);
                                    }
				}
				else
				{
                                    // Generate Tagihan
						
                                    $gentag = $this->Penagihan_model->insert_tagihan_bangunan_perclusterperbulan($cluster, $tahun, $bulan);
						
                                    $jml_tagihan_generated = $this->Penagihan_model->count_tagihan_bangunan_percluster($cluster, $tahun, $bulan);
						
                                    //	echo "Generate Tagihan";
                                    $data['pesan'] = "Data berhasil digenerate";
                                    $data['jml_terupdate'] = $jml_pembayar;
                                    $data['cluster'] = $cluster;
                                        
                                    $genpiutang = $this->Penagihan_model->insert_piutang_bangunan_perclusterpertahunperbulan($cluster, $tahun, $bulan);
				}
                            //}
			}
                    $this->template->load('template','penagihan/konfirmasi_penyiapantagihan_view',$data);
                    
                    break;
		
                    case 'K';
                        //echo "Generate Kavling Developer";                        
                  
                        $jml_tagihan_kavdev = $this->Penagihan_model->count_tagihan_kavdev_percluster($cluster, $tahun, $bulan);
		
                        // Cek Apakah sudah Dibayar
                        $jml_pembayar_kavdev = $this->Penagihan_model->count_tagihan_kavdev_percluster_telahdibayar($cluster, $tahun, $bulan);
		
                        if($tahun < date('y'))
                        {
                            // Tampilkan Peringatan Tidak dapat Generate Tagihan
                            // echo "Tampilkan Peringatan";
                            $data['pesan'] = "Anda tidak dapat meng-generate data tahun sebelumnya";
                            $data['jml_terupdate'] = $jml_tagihan;
                            $data['cluster'] = $cluster;
                        }
                        else 
                        {
                            if($bulan<=(date('m')-1))
                            {
				$data['pesan'] = "Anda tidak dapat meng-generate data bulan sebelumnya";
				$data['jml_terupdate'] = $jml_tagihan;
				$data['cluster'] = $cluster;
                            }
                            else 
                            {
				if ($jml_tagihan_kavdev <> 0)
				{
                                    if ($jml_pembayar_kavdev <> 0)
                                    {
                                        // Generate Tagihan dengan memindahkan data deposit terlebih dahulu 
                                        // echo "Tampilkan Peringatan";
                                        
                                        //echo $cluster;
                                        //echo "<br>";
                                        //echo $tahun;
                                        //echo "<br>";
                                        //echo $bulan;
                                        
                                        $mttd = $this->Penagihan_model->move_tagihan_kavdev_to_tagihandeposit_perclusterperbulan($cluster, $tahun, $bulan);
                                        
                                        $deltag = $this->Penagihan_model->delete_tagihan_kavdev_perclusterperbulan($cluster, $tahun, $bulan);
					$gentag = $this->Penagihan_model->insert_tagihan_kavdev_perclusterperbulan($cluster, $tahun, $bulan);

                                        $idhasil=$this->Penagihan_model->get_idipkl_from_deposit($tahun,$bulan)->result();
                                        
                                        foreach ($idhasil as $row)
                                        {
                                            $btt = $this->Penagihan_model->back_tagihan_kavdev_from_tagihandeposit_perclusterperbulan($row->idipkl, $tahun, $bulan);
                                        }
                                                                                
                                        $data['pesan'] = "Terdapat data Deposit dan telah dibayar";
                                        
                                        $jml_tagihan_generated = $this->Penagihan_model->count_tagihan_kavdev_percluster($cluster, $tahun, $bulan);
                                        $data['jml_terupdate'] = $jml_tagihan_generated;
                                        $data['cluster'] = $cluster;
                                        
                                        $genpiutang = $this->Penagihan_model->insert_piutang_kavdev_perclusterpertahunperbulan($cluster, $tahun, $bulan);
                                    }
                                    else
                                    {
					// Generate Tagihan
					$deltag = $this->Penagihan_model->delete_tagihan_kavdev_perclusterperbulan($cluster, $tahun, $bulan);
					$gentag = $this->Penagihan_model->insert_tagihan_kavdev_perclusterperbulan($cluster, $tahun, $bulan);
                                        
					$jml_tagihan_generated = $this->Penagihan_model->count_tagihan_kavdev_percluster($cluster, $tahun, $bulan);
				
					// echo "Generate Tagihan";
					$data['pesan'] = "Data berhasil digenerate";
					$data['jml_terupdate'] = $jml_tagihan_generated;
					$data['cluster'] = $cluster;
                                                
                                        $genpiutang = $this->Penagihan_model->insert_piutang_kavdev_perclusterpertahunperbulan($cluster, $tahun, $bulan);
                                    }
				}
				else
				{
                                    // Generate Tagihan
						
                                    $gentag = $this->Penagihan_model->insert_tagihan_kavdev_perclusterperbulan($cluster, $tahun, $bulan);
						
                                    $jml_tagihan_generated = $this->Penagihan_model->count_tagihan_kavdev_percluster($cluster, $tahun, $bulan);
						
                                    //	echo "Generate Tagihan";
                                    $data['pesan'] = "Data berhasil digenerate";
                                    $data['jml_terupdate'] = $jml_tagihan_generated;
                                    $data['cluster'] = $cluster;
                                        
                                    $genpiutang = $this->Penagihan_model->insert_piutang_kavdev_perclusterpertahunperbulan($cluster, $tahun, $bulan);
				}
                            }
			}
                    $this->template->load('template','penagihan/konfirmasi_penyiapantagihan_view',$data);
                    
                    break;
		
                    case 'P';
                        //echo "Generate Kavling Pelanggan";
                        
                        $jml_tagihan_kavplg = $this->Penagihan_model->count_tagihan_kavplg_percluster($cluster, $tahun, $bulan);
		
                        // Cek Apakah sudah Dibayar
                        $jml_pembayar_kavplg = $this->Penagihan_model->count_tagihan_kavplg_percluster_telahdibayar($cluster, $tahun, $bulan);
		
                        if($tahun < date('y'))
                        {
                            // Tampilkan Peringatan Tidak dapat Generate Tagihan
                            // echo "Tampilkan Peringatan";
                            $data['pesan'] = "Anda tidak dapat meng-generate data tahun sebelumnya";
                            $data['jml_terupdate'] = $jml_tagihan_kavplg;
                            $data['cluster'] = $cluster;
                        }
                        else 
                        {
                            //if($bulan<=(date('m')-1))
                            //{
				//$data['pesan'] = "Anda tidak dapat meng-generate data bulan sebelumnya";
				//$data['jml_terupdate'] = $jml_tagihan_kavplg;
				//$data['cluster'] = $cluster;
                            //}
                            //else 
                            //{
				if ($jml_tagihan_kavplg <> 0)
				{
                                    if ($jml_pembayar_kavplg <> 0)
                                    {
                                        // Generate Tagihan dengan memindahkan data deposit terlebih dahulu 
                                        // echo "Tampilkan Peringatan";
                                        
                                        //echo $cluster;
                                        //echo "<br>";
                                        //echo $tahun;
                                        //echo "<br>";
                                        //echo $bulan;
                                        
                                        $mttd = $this->Penagihan_model->move_tagihan_kavplg_to_tagihandeposit_perclusterperbulan($cluster, $tahun, $bulan);
                                        
                                        $deltag = $this->Penagihan_model->delete_tagihan_kavplg_perclusterperbulan($cluster, $tahun, $bulan);
					$gentag = $this->Penagihan_model->insert_tagihan_kavplg_perclusterperbulan($cluster, $tahun, $bulan);

                                        $idhasil=$this->Penagihan_model->get_idipkl_from_deposit($tahun,$bulan)->result();
                                        
                                        foreach ($idhasil as $row)
                                        {
                                            $btt = $this->Penagihan_model->back_tagihan_kavplg_from_tagihandeposit_perclusterperbulan($row->idipkl, $tahun, $bulan);
                                        }
                                                                                
                                        $data['pesan'] = "Terdapat data Deposit dan telah dibayar";
                                        
                                        $jml_tagihan_generated = $this->Penagihan_model->count_tagihan_kavplg_percluster($cluster, $tahun, $bulan);
                                        $data['jml_terupdate'] = $jml_tagihan_generated;
                                        $data['cluster'] = $cluster;
                                        
                                        $genpiutang = $this->Penagihan_model->insert_piutang_kavplg_perclusterpertahunperbulan($cluster, $tahun, $bulan);
                                    }
                                    else
                                    {
					// Generate Tagihan
					$deltag = $this->Penagihan_model->delete_tagihan_kavplg_perclusterperbulan($cluster, $tahun, $bulan);
					$gentag = $this->Penagihan_model->insert_tagihan_kavplg_perclusterperbulan($cluster, $tahun, $bulan);
                                        
					$jml_tagihan_generated = $this->Penagihan_model->count_tagihan_kavplg_percluster($cluster, $tahun, $bulan);
				
					// echo "Generate Tagihan";
					$data['pesan'] = "Data berhasil digenerate";
					$data['jml_terupdate'] = $jml_tagihan_generated;
					$data['cluster'] = $cluster;
                                                
                                        $genpiutang = $this->Penagihan_model->insert_piutang_kavplg_perclusterpertahunperbulan($cluster, $tahun, $bulan);
                                    }
				}
				else
				{
                                    // Generate Tagihan
						
                                    $gentag = $this->Penagihan_model->insert_tagihan_kavplg_perclusterperbulan($cluster, $tahun, $bulan);
						
                                    $jml_tagihan_generated = $this->Penagihan_model->count_tagihan_kavplg_percluster($cluster, $tahun, $bulan);
						
                                    //	echo "Generate Tagihan";
                                    $data['pesan'] = "Data berhasil digenerate";
                                    $data['jml_terupdate'] = $jml_tagihan_generated;
                                    $data['cluster'] = $cluster;
                                        
                                    $genpiutang = $this->Penagihan_model->insert_piutang_kavplg_perclusterpertahunperbulan($cluster, $tahun, $bulan);
				}
                            //}
			}
                    $this->template->load('template','penagihan/konfirmasi_penyiapantagihan_view',$data);
                                         
                        
                }
                     
                /*
                $jml_tagihan = $this->Penagihan_model->count_tagihan_percluster($cluster, $tahun, $bulan);
		
		// Cek Apakah sudah Dibayar
		$jml_pembayar = $this->Penagihan_model->count_tagihan_percluster_telahdibayar($cluster, $tahun, $bulan);
		
// 		echo $cluster;
// 		echo "</br>";
// 		echo $tahun;
// 		echo "</br>";
// 		echo $bulan;
// 		echo "</br>";
// 		echo $jml_tagihan;
// 		echo "</br>";
// 		echo $jml_pembayar;
// 		echo "</br>";

		if($tahun < date('y'))
		{
			// Tampilkan Peringatan Tidak dapat Generate Tagihan
			// echo "Tampilkan Peringatan";
			$data['pesan'] = "Anda tidak dapat meng-generate data tahun sebelumnya";
			$data['jml_terupdate'] = $jml_tagihan;
			$data['cluster'] = $cluster;
		}
		else 
		{
		if($bulan<=(date('m')-1))
			{
				$data['pesan'] = "Anda tidak dapat meng-generate data bulan sebelumnya";
				$data['jml_terupdate'] = $jml_tagihan;
				$data['cluster'] = $cluster;
			}
			else 
			{
				if ($jml_tagihan <> 0)
				{
					if ($jml_pembayar <> 0)
					{
						// Tampilkan Peringatan Tidak dapat Generate Tagihan
						// echo "Tampilkan Peringatan";
						$data['pesan'] = "Data telah digenerate sebelumnya dan telah dibayar";
						$data['jml_terupdate'] = $jml_tagihan;
						$data['cluster'] = $cluster;
					}
					else
					{
						// Generate Tagihan
						$deltag = $this->Penagihan_model->delete_tagihan_perclusterperbulan($cluster, $tahun, $bulan);
				
						$gentag = $this->Penagihan_model->insert_tagihan_perclusterperbulan($cluster, $tahun, $bulan);
				
						$jml_tagihan_generated = $this->Penagihan_model->count_tagihan_percluster($cluster, $tahun, $bulan);
				
						// echo "Generate Tagihan";
						$data['pesan'] = "Data berhasil digenerate";
						$data['jml_terupdate'] = $jml_tagihan_generated;
						$data['cluster'] = $cluster;
                                                
                                                $genpiutang = $this->Penagihan_model->insert_piutang_perclusterpertahunperbulan($cluster, $tahun, $bulan);
					}
				}
				else
				{
					// Generate Tagihan
						
					$gentag = $this->Penagihan_model->insert_tagihan_perclusterperbulan($cluster, $tahun, $bulan);
						
					$jml_tagihan_generated = $this->Penagihan_model->count_tagihan_percluster($cluster, $tahun, $bulan);
						
					// 				echo "Generate Tagihan";
					$data['pesan'] = "Data berhasil digenerate";
					$data['jml_terupdate'] = $jml_pembayar;
					$data['cluster'] = $cluster;
                                        
                                        $genpiutang = $this->Penagihan_model->insert_piutang_perclusterpertahunperbulan($cluster, $tahun, $bulan);
				}
			}
			$this->template->load('template','penagihan/konfirmasi_penyiapantagihan_view',$data);
		}
                 * 
                 */
                
                //$this->template->load('template','penagihan/konfirmasi_penyiapantagihan_view',$data);
	}
	
	function separator($num, $suffix = '')
	{
		$ina_format_number = number_format($num, 3, ',','.');
		$result = str_replace(',000',$suffix,$ina_format_number) ;
	
		return $result ;
	}
	
}
