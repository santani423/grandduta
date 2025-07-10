<?php
/**
 * Penagihan_model Class
 *
 * @author	Trias Bratakusuma <brata@pdamtkr.co.id>
 */
class Penagihan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function Penagihan_model()
	{
		parent::__Construct();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'tagihan';
	
	// =====================================================================================
	
	public function count_tagihan_percluster($cluster, $tahun, $bulan)
	{
		$this->db->from('tagihan');
	
		$this->db->where('left(idipkl,2)', $cluster);
		$this->db->where('tahun', $tahun);
		$this->db->where('bulan', $bulan);
	
		return $this->db->count_all_results();
	}

public function hapus_tagihan_bulanan()
{
	
}

	//otomatis tagihan bulanan
public function buat_tagihan_bulanan()
{
    $this->load->helper('date');

    $tahun_awal = 2020;
    $tahun_sekarang = date('Y');
    $bulan_sekarang = date('n'); // tanpa leading zero

    // Ambil semua pelanggan dan tarif dari cluster
    $this->db->select('pelanggan.idipkl, cluster.tarif, pelanggan.tglserahterima');
    $this->db->from('pelanggan');
    $this->db->join('cluster', 'pelanggan.idcluster = cluster.idcluster');
	//  $tahun_awal = 'tglserahterima';
	
    $pelanggan = $this->db->get()->result();

    if (empty($pelanggan)) {
        return; // Tidak ada pelanggan
    }

    // Ambil semua tagihan yang sudah ada sejak 2024
    $this->db->select('idipkl, tahun, bulan');
    $this->db->from('tagihan');
    $this->db->where('tahun >=', $tahun_awal);
    $existing_tagihan = $this->db->get()->result();

    // Buat map [idipkl][tahun][bulan] = true
    $existing_map = [];
    foreach ($existing_tagihan as $et) {
        $existing_map[$et->idipkl][$et->tahun][intval($et->bulan)] = true;
    }

    $data_to_insert = [];

    for ($tahun = $tahun_awal; $tahun <= $tahun_sekarang; $tahun++) {
    $bulan_mulai = ($tahun == $tahun_awal) ? 1 : 1;
    $bulan_akhir = ($tahun == $tahun_sekarang) ? $bulan_sekarang : 12;

    for ($bulan = $bulan_mulai; $bulan <= $bulan_akhir; $bulan++) {
        foreach ($pelanggan as $p) {
            // Konversi tglserahterima ke tahun dan bulan
            $tgl_serah_terima = date_create($p->tglserahterima);
            $tahun_serah = (int)date_format($tgl_serah_terima, 'Y');
            $bulan_serah = (int)date_format($tgl_serah_terima, 'm');

            // Lewati jika tahun dan bulan lebih kecil dari tglserahterima
            if ($tahun < $tahun_serah || ($tahun == $tahun_serah && $bulan < $bulan_serah) || $p->tglserahterima == '0000-00-00') {
                continue;
            }

            if (empty($existing_map[$p->idipkl][$tahun][$bulan])) {
                $data_to_insert[] = [
                    'idipkl' => $p->idipkl,
                    'tahun' => $tahun,
                    'bulan' => str_pad($bulan, 2, '0', STR_PAD_LEFT),
                    'tagihan' => $p->tarif,
                    'denda' => 0,
                    'idstatustagihan' => 1,
                    'tglbayar' => '',
                    'nokuitansi' => '',
                    'ketaproval' => ''
                ];
            }
        }
    }
}


    // Insert batch untuk efisiensi
    if (!empty($data_to_insert)) {
        $this->db->insert_batch('tagihan', $data_to_insert);
    }
}




	
	public function count_tagihan_percluster_telahdibayar($cluster, $tahun, $bulan)
	{
		$this->db->from('tagihan');
	
		$this->db->where('left(idipkl,2)', $cluster);
		$this->db->where('tahun', $tahun);
		$this->db->where('bulan', $bulan);	
		$this->db->where('idstatustagihan', '2');
		
		return $this->db->count_all_results();
	}
        
        //===================== awal of generate tagihan bangunan =======================
        
        public function count_tagihan_bangunan_percluster($cluster, $tahun, $bulan)
	{
		$this->db->from('tagihan');
                $this->db->join('pelanggan','tagihan.idipkl=pelanggan.idipkl');    
		$this->db->where('left(tagihan.idipkl,2)', $cluster);
		$this->db->where('tagihan.tahun', $tahun);
		$this->db->where('tagihan.bulan', $bulan);
                $this->db->where('pelanggan.idbork','B');
	
		return $this->db->count_all_results();
	}
	
	public function count_tagihan_bangunan_percluster_telahdibayar($cluster, $tahun, $bulan)
	{
		$this->db->from('tagihan');
                $this->db->join('pelanggan','tagihan.idipkl=pelanggan.idipkl');    
		$this->db->where('left(tagihan.idipkl,2)', $cluster);
		$this->db->where('tagihan.tahun', $tahun);
		$this->db->where('tagihan.bulan', $bulan);
                $this->db->where('pelanggan.idbork','B');	
		$this->db->where('idstatustagihan', '2');
		
		return $this->db->count_all_results();
	}
        
        public function move_tagihan_bangunan_to_tagihandeposit_perclusterperbulan($cluster, $tahun, $bulan)
        {
            $sql = "insert tagihandeposit(SELECT T.idtagihan,
T.idipkl,
T.tahun,
T.bulan,
T.tagihan,
T.tglbayar,
T.idloket,
T.user_id,
T.idstatustagihan,
T.cetakspt,
T.denda,
T.diskon,
T.user_id_aprover,
T.kenadiskon,
T.kenadenda,
T.nokuitansi,
T.ketaproval
FROM tagihan T Join pelanggan P on T.idipkl=P.idipkl where left(T.idipkl,2) = '" . $cluster . "' and T.tahun = '" . $tahun . "' and T.bulan='" .$bulan . "' and P.idbork='B')";
        
            return $this->db->query($sql);
        }
        
        public function delete_tagihan_bangunan_perclusterperbulan($cluster, $tahun, $bulan)
	{
		$sql = " DELETE tagihan From tagihan Join pelanggan on tagihan.idipkl=pelanggan.idipkl  
                    Where left(tagihan.idipkl,2) = '" . $cluster . "' and tagihan.tahun = '" . $tahun . "' and tagihan.bulan='" .$bulan . "' 
                    and pelanggan.idbork='B'
                    ";
			
		return $this->db->query($sql);
	}
        
	public function insert_tagihan_bangunan_perclusterperbulan($cluster, $tahun, $bulan)
	{
		$sql = "insert into tagihan(idipkl, tahun, bulan, tagihan, idstatustagihan,kenadenda,kenadiskon) select P.idipkl, '" . $tahun . "','" .$bulan . "'  ,C.tarif,'1','1','1'  From pelanggan P join cluster C on P.idcluster = C.idcluster where P.idcluster='" . $cluster . "' and P.idstatuspelanggan<>'3' and P.idbork='B'";

		return $this->db->query($sql);
	}	
        
        public function insert_piutang_bangunan_perclusterpertahunperbulan($cluster, $tahun, $bulan)
	{
		$sql = "INSERT piutang
SELECT CONCAT(" . $tahun . "," . $bulan . ",T.idtagihan) as idpiutang, " . $tahun ." as pertahun, " . $bulan . " as perbulan,
T.idtagihan, T.tagihan FROM tagihan T join pelanggan P on T.idipkl=P.idipkl
WHERE T.idstatustagihan='1' and left(T.idipkl,2)= '" . $cluster . "' and P.idbork='B'";

		return $this->db->query($sql);
	}        

        public function get_idipkl_from_deposit($tahun, $bulan)
	{
		$sql = "select idipkl from tagihandeposit where tahun = '" . $tahun . "' and bulan='" .$bulan . "'";
			
		return $this->db->query($sql);
	}
        
        public function back_tagihan_bangunan_from_tagihandeposit_perclusterperbulan($idipkl, $tahun, $bulan)
	{
		$sql = " UPDATE tagihan T, tagihandeposit D SET
T.tagihan = D.tagihan,
T.tglbayar = D.tglbayar,
T.idloket = D.idloket,
T.user_id = D.user_id,
T.idstatustagihan =D.idstatustagihan,
T.cetakspt = D.cetakspt,
T.denda = D.denda,
T.diskon = D.diskon,
T.user_id_aprover = D.user_id_aprover,
T.kenadiskon = D.kenadiskon,
T.kenadenda = D.kenadenda,
T.nokuitansi = D.nokuitansi,
T.ketaproval = D.ketaproval
WHERE T.idipkl='" . $idipkl . "' AND T.tahun='" . $tahun . "' AND T.bulan='" .$bulan . "'";
			
		return $this->db->query($sql);
	}
        
        //===================== end of generate tagihan bangunan =======================
        
        //===================== awal of generate tagihan kavling developer =======================
        
        public function count_tagihan_kavdev_percluster($cluster, $tahun, $bulan)
	{
		$this->db->from('tagihan');
                $this->db->join('pelanggan','tagihan.idipkl=pelanggan.idipkl');    
		$this->db->where('left(tagihan.idipkl,2)', $cluster);
		$this->db->where('tagihan.tahun', $tahun);
		$this->db->where('tagihan.bulan', $bulan);
                $this->db->where('pelanggan.idbork','K');
	
		return $this->db->count_all_results();
	}


	
	public function count_tagihan_kavdev_percluster_telahdibayar($cluster, $tahun, $bulan)
	{
		$this->db->from('tagihan');
                $this->db->join('pelanggan','tagihan.idipkl=pelanggan.idipkl');    
		$this->db->where('left(tagihan.idipkl,2)', $cluster);
		$this->db->where('tagihan.tahun', $tahun);
		$this->db->where('tagihan.bulan', $bulan);
                $this->db->where('pelanggan.idbork','K');	
		$this->db->where('idstatustagihan', '2');
		
		return $this->db->count_all_results();
	}
        
        public function move_tagihan_kavdev_to_tagihandeposit_perclusterperbulan($cluster, $tahun, $bulan)
        {
            $sql = "insert tagihandeposit(SELECT T.idtagihan,
T.idipkl,
T.tahun,
T.bulan,
T.tagihan,
T.tglbayar,
T.idloket,
T.user_id,
T.idstatustagihan,
T.cetakspt,
T.denda,
T.diskon,
T.user_id_aprover,
T.kenadiskon,
T.kenadenda,
T.nokuitansi,
T.ketaproval
FROM tagihan T Join pelanggan P on T.idipkl=P.idipkl where left(T.idipkl,2) = '" . $cluster . "' and T.tahun = '" . $tahun . "' and T.bulan='" .$bulan . "' and P.idbork='K')";
        
            return $this->db->query($sql);
        }
        
        public function delete_tagihan_kavdev_perclusterperbulan($cluster, $tahun, $bulan)
	{
		$sql = " DELETE tagihan From tagihan Join pelanggan on tagihan.idipkl=pelanggan.idipkl  
                    Where left(tagihan.idipkl,2) = '" . $cluster . "' and tagihan.tahun = '" . $tahun . "' and tagihan.bulan='" .$bulan . "' 
                    and pelanggan.idbork='K'
                    ";
			
		return $this->db->query($sql);
	}
        
	public function insert_tagihan_kavdev_perclusterperbulan($cluster, $tahun, $bulan)
	{
		$sql = "insert into tagihan(idipkl, tahun, bulan, tagihan, idstatustagihan,kenadenda,kenadiskon) select P.idipkl, '" . $tahun . "','" .$bulan . "'  ,C.tarif/2,'1','1','1'  From pelanggan P join cluster C on P.idcluster = C.idcluster where P.idcluster='" . $cluster . "' and P.idstatuspelanggan<>'3' and P.idbork='K'";

		return $this->db->query($sql);
	}	
        
        public function insert_piutang_kavdev_perclusterpertahunperbulan($cluster, $tahun, $bulan)
	{
		$sql = "INSERT piutang
SELECT CONCAT(" . $tahun . "," . $bulan . ",T.idtagihan) as idpiutang, " . $tahun ." as pertahun, " . $bulan . " as perbulan,
T.idtagihan, T.tagihan FROM tagihan T join pelanggan P on T.idipkl=P.idipkl
WHERE T.idstatustagihan='1' and left(T.idipkl,2)= '" . $cluster . "' and P.idbork='K'";

		return $this->db->query($sql);
	}        

   
        public function back_tagihan_kavdev_from_tagihandeposit_perclusterperbulan($idipkl, $tahun, $bulan)
	{
		$sql = " UPDATE tagihan T, tagihandeposit D SET
T.idtagihan = D.idtagihan,
T.idipkl = D.idipkl,
T.tahun = D.tahun,
T.bulan = D.bulan,
T.tagihan = D.tagihan,
T.tglbayar = D.tglbayar,
T.idloket = D.idloket,
T.user_id = D.user_id,
T.idstatustagihan =D.idstatustagihan,
T.cetakspt = D.cetakspt,
T.denda = D.denda,
T.diskon = D.diskon,
T.user_id_aprover = D.user_id_aprover,
T.kenadiskon = D.kenadiskon,
T.kenadenda = D.kenadenda,
T.nokuitansi = D.nokuitansi,
T.ketaproval = D.ketaproval
WHERE T.idipkl='" . $idipkl . "' AND T.tahun='" . $tahun . "' AND T.bulan='" .$bulan . "'";
			
		return $this->db->query($sql);
	}
        
        //===================== end of generate tagihan Kavling Developer =======================
        
        //===================== awal of generate tagihan Kavling Pelanggan =======================
        
        public function count_tagihan_kavplg_percluster($cluster, $tahun, $bulan)
	{
		$this->db->from('tagihan');
                $this->db->join('pelanggan','tagihan.idipkl=pelanggan.idipkl');    
		$this->db->where('left(tagihan.idipkl,2)', $cluster);
		$this->db->where('tagihan.tahun', $tahun);
		$this->db->where('tagihan.bulan', $bulan);
                $this->db->where('pelanggan.idbork','P');
	
		return $this->db->count_all_results();
	}
	
	public function count_tagihan_kavplg_percluster_telahdibayar($cluster, $tahun, $bulan)
	{
		$this->db->from('tagihan');
                $this->db->join('pelanggan','tagihan.idipkl=pelanggan.idipkl');    
		$this->db->where('left(tagihan.idipkl,2)', $cluster);
		$this->db->where('tagihan.tahun', $tahun);
		$this->db->where('tagihan.bulan', $bulan);
                $this->db->where('pelanggan.idbork','P');	
		$this->db->where('idstatustagihan', '2');
		
		return $this->db->count_all_results();
	}
        
        public function move_tagihan_kavplg_to_tagihandeposit_perclusterperbulan($cluster, $tahun, $bulan)
        {
            $sql = "insert tagihandeposit(SELECT T.idtagihan,
T.idipkl,
T.tahun,
T.bulan,
T.tagihan,
T.tglbayar,
T.idloket,
T.user_id,
T.idstatustagihan,
T.cetakspt,
T.denda,
T.diskon,
T.user_id_aprover,
T.kenadiskon,
T.kenadenda,
T.nokuitansi,
T.ketaproval
FROM tagihan T Join pelanggan P on T.idipkl=P.idipkl where left(T.idipkl,2) = '" . $cluster . "' and T.tahun = '" . $tahun . "' and T.bulan='" .$bulan . "' and P.idbork='P')";
        
            return $this->db->query($sql);
        }
        
        public function delete_tagihan_kavplg_perclusterperbulan($cluster, $tahun, $bulan)
	{
		$sql = " DELETE tagihan From tagihan Join pelanggan on tagihan.idipkl=pelanggan.idipkl  
                    Where left(tagihan.idipkl,2) = '" . $cluster . "' and tagihan.tahun = '" . $tahun . "' and tagihan.bulan='" .$bulan . "' 
                    and pelanggan.idbork='P'
                    ";
			
		return $this->db->query($sql);
	}
        
	public function insert_tagihan_kavplg_perclusterperbulan($cluster, $tahun, $bulan)
	{
		$sql = "insert into tagihan(idipkl, tahun, bulan, tagihan, idstatustagihan,kenadenda,kenadiskon) select P.idipkl, '" . $tahun . "','" .$bulan . "'  ,C.tarif/2 ,'1','1','1'  From pelanggan P join cluster C on P.idcluster = C.idcluster where P.idcluster='" . $cluster . "' and P.idstatuspelanggan<>'3' and P.idbork='P'";

		return $this->db->query($sql);
	}	
        
        public function insert_piutang_kavplg_perclusterpertahunperbulan($cluster, $tahun, $bulan)
	{
		$sql = "INSERT piutang
SELECT CONCAT(" . $tahun . "," . $bulan . ",T.idtagihan) as idpiutang, " . $tahun ." as pertahun, " . $bulan . " as perbulan,
T.idtagihan, T.tagihan FROM tagihan T join pelanggan P on T.idipkl=P.idipkl
WHERE T.idstatustagihan='1' and left(T.idipkl,2)= '" . $cluster . "' and P.idbork='P'";

		return $this->db->query($sql);
	}        

   
        public function back_tagihan_kavplg_from_tagihandeposit_perclusterperbulan($idipkl, $tahun, $bulan)
	{
		$sql = " UPDATE tagihan T, tagihandeposit D SET
T.idtagihan = D.idtagihan,
T.idipkl = D.idipkl,
T.tahun = D.tahun,
T.bulan = D.bulan,
T.tagihan = D.tagihan,
T.tglbayar = D.tglbayar,
T.idloket = D.idloket,
T.user_id = D.user_id,
T.idstatustagihan =D.idstatustagihan,
T.cetakspt = D.cetakspt,
T.denda = D.denda,
T.diskon = D.diskon,
T.user_id_aprover = D.user_id_aprover,
T.kenadiskon = D.kenadiskon,
T.kenadenda = D.kenadenda,
T.nokuitansi = D.nokuitansi,
T.ketaproval = D.ketaproval
WHERE T.idipkl='" . $idipkl . "' AND T.tahun='" . $tahun . "' AND T.bulan='" .$bulan . "'";
			
		return $this->db->query($sql);
	}
        
        //===================== end of generate tagihan Kavling Pelanggan =======================

	public function count_tagihan_khusus($tahunnya,$bulannya)
	{
		$sql = "select count(*) as jmltagkhusus from tagihan T ,nilaitagihankhusus K
where T.idipkl=K.idipkl and tahun = '" . $tahunnya . "' and bulan = '" . $bulannya . "'";
	
		return $this->db->query($sql);
	}
	
	
	public function insert_kwitansi($nomorkwitansinya,$idipklnya,$namanya,
						$clusternya,
						$bloknya,
						$nokavlingnya,
						$totaltagihannya,
						$totaldendanya,
						$jumlahtotalnya,
						$jumlahtagihannya,
						$loketnya,
						$kasirnya,
						$rincianbulannya,
						$idcarabayarnya,
						$idlewatbayarnya)
	{
		$sql = "insert into kuitansi(nokuitansi,idipkl,
										tglbayar,
										nama,
										cluster,
										blok,
										nokavling,
										totaltagihan,
										totaldenda,
										jumlahtotal,
										jumlahtagihan,
										loket,
										kasir,
										rincianbulan,
										idcarabayar,
										idlewatbayar) 	
				values ('" .$nomorkwitansinya . "',
						'" . $idipklnya. "',
						now(),
						'" .$namanya. "',
						'" .$clusternya. "',
						'" .$bloknya. "',
						'" .$nokavlingnya. "',
						'" .$totaltagihannya. "',
						'" .$totaldendanya. "',
						'" .$jumlahtotalnya. "',
						'" .$jumlahtagihannya. "',
						'" .$loketnya. "',
						'" .$kasirnya. "',
						'" .$rincianbulannya. "',
						'" .$idcarabayarnya. "',
						'" .$idlewatbayarnya. "'
						)";
	
		return $this->db->query($sql);
	}
	
	public function insert_tagihan_perclusterperbulan($cluster, $tahun, $bulan)
	{
		$sql = "insert into tagihan(idipkl, tahun, bulan, tagihan, idstatustagihan,kenadenda,kenadiskon) select P.idipkl, '" . $tahun . "','" .$bulan . "'  ,C.tarif,'1','1','1'  From pelanggan P join cluster C on P.idcluster = C.idcluster where P.idcluster='" . $cluster . "' and P.idstatuspelanggan<>'3'";

		return $this->db->query($sql);
	}	
        
        public function insert_piutang_perclusterpertahunperbulan($cluster, $tahun, $bulan)
	{
		$sql = "INSERT piutang
SELECT CONCAT(" . $tahun . "," . $bulan . ",idtagihan) as idpiutang, " . $tahun ." as pertahun, " . $bulan . " as perbulan,
idtagihan,tagihan FROM tagihan
WHERE idstatustagihan='1' and left(idipkl,2)= '" . $cluster . "'";

		return $this->db->query($sql);
	}
	
	public function insert_tagihan_perid($idipklnya,$tahunnya,$bulannya,$tagihannya)
	{
		$sql = "insert into tagihan(idipkl,tahun,bulan,tagihan,idstatustagihan,kenadiskon,kenadenda) values('" . $idipklnya . "','" . $tahunnya . "','" . $bulannya . "','" . $tagihannya. "','1','1','1')";
	
		return $this->db->query($sql);
	}
	
	public function delete_tagihan_perclusterperbulan($cluster, $tahun, $bulan)
	{
		$sql = "delete from tagihan where left(idipkl,2) = '" . $cluster . "' and tahun = '" . $tahun . "' and bulan = '" .$bulan . "'";
			
		return $this->db->query($sql);
	}
	
	public function destroytagihan($id)
	{
		$this->db->where('idtagihan', $id);
		$this->db->delete('tagihan');		
	}
	
	// public function get_tagihan_belumlunas($id)
	// {
	//     $this->db->where('vw_tagihan_blmlunas.idipkl', $id);
    //     $result = $this->db->get('vw_tagihan_blmlunas');
	// 	// print_r($result->result_array());
    //     if ($result->num_rows() > 0) 
    //     {
    //         return $result->result_array();
    //     } 
    //     else 
    //     {
    //         return array();
    //     }
	// }

	public function get_tagihan_belumlunas($id)
{
    $this->db->where('vw_tagihan_blmlunas.idipkl', $id);
    $result = $this->db->get('vw_tagihan_blmlunas');

    if ($result->num_rows() > 0) {
        return $result->result_array();
    } else {
        return array();
    }
}

public function get_onepelanggan_multi1($idipkl = null, $blok = null, $nokav = null)
{
    $this->db->from('vw_pelanggan_fordetail');

    if (!empty($idipkl)) {
        $this->db->where('ID_IPKL', $idipkl);
    }
    if (!empty($blok)) {
        $this->db->where('BLOK', $blok);
    }
    if (!empty($nokav)) {
        $this->db->where('NO_KAVLING', $nokav);
    }

    return $this->db->get();
}




	public function get_onepelanggan_multi($keyword)
{
    $this->db->select('pelanggan.idipkl,pelanggan.namapelanggan, pelanggan.blok, tagihan.idipkl AS tagihan_idipkl, 
	pelanggan.nokav, pelanggan.namapelanggan');
		$this->db->from('pelanggan');
		$this->db->join('tagihan', 'pelanggan.idipkl = tagihan.idipkl');
    $this->db->where("(
			pelanggan.idipkl LIKE '%$keyword%' 
			OR pelanggan.blok LIKE '%$keyword%' 
			OR pelanggan.nokav LIKE '%$keyword%'
    )", null, false);
    $this->db->limit(1);
    return $this->db->get();
}



	public function get_tagihan_belumlunas_multi($keyword)
	{
		$this->db->select('pelanggan.idipkl,pelanggan.namapelanggan, pelanggan.blok, tagihan.idipkl AS tagihan_idipkl, 
	pelanggan.nokav, pelanggan.namapelanggan');
		$this->db->from('pelanggan');
		$this->db->join('tagihan', 'pelanggan.idipkl = tagihan.idipkl');
		// $this->db->from('tagihan');
        // $this->db->join('pelanggan','tagihan.idipkl=pelanggan.idipkl');
		// $this->db->from('vw_tagihan_blmlunas');
		$this->db->where("(
			pelanggan.idipkl LIKE '%$keyword%' 
			OR pelanggan.blok LIKE '%$keyword%' 
			OR pelanggan.nokav LIKE '%$keyword%'
		)", null, false);


		$result = $this->db->get();
		return $result->num_rows() > 0 ? $result->result_array() : array();
	}

	
        public function get_jml_tagihan_belumlunas($id)
	{
	    $this->db->where('tagihan.idipkl', $id);
	    $this->db->where('tagihan.idstatustagihan','1');            
            $result = $this->db->get('tagihan');

            return $result->num_rows();
	}
        
	// public function get_tagihantotal_belumlunas($id)
	// {
	// 	$this->db->select('sum(Jumlah) AS total');
	// 	$this->db->where('vw_tagihan_blmlunas.idipkl', $id);
	// 	$result = $this->db->get('vw_tagihan_blmlunas');
	
	// 	if ($result->num_rows() == 1) 
    //     {
    //         return $result->row_array();
    //     } 
    //     else 
    //     {
    //         return array();
    //     }	
	// }

	public function get_tagihantotal_belumlunas($id)
{
    $this->db->select('sum(Jumlah) AS total');
    $this->db->where('vw_tagihan_blmlunas.idipkl', $id);
    $result = $this->db->get('vw_tagihan_blmlunas');

    if ($result->num_rows() == 1) {
        return $result->row_array();
    } else {
        return array();
    }
}


	public function get_tagihantotal_belumlunas_multi($keyword)
{
		$this->db->select('pelanggan.idipkl,pelanggan.namapelanggan, pelanggan.blok, tagihan.idipkl AS tagihan_idipkl, 
	pelanggan.nokav, pelanggan.namapelanggan');	
		$this->db->from('pelanggan');
		$this->db->join('tagihan', 'pelanggan.idipkl = tagihan.idipkl');
		// $this->db->from('tagihan');
        // $this->db->join('pelanggan','tagihan.idipkl=pelanggan.idipkl');
		// $this->db->from('vw_tagihan_blmlunas');
		$this->db->where("(
			pelanggan.idipkl LIKE '%$keyword%' 
			OR pelanggan.blok LIKE '%$keyword%' 
			OR pelanggan.nokav LIKE '%$keyword%'
		)", null, false);

	$result = $this->db->get();

	return $result->num_rows() > 0 ? $result->row_array() : array();
}

		
	// public function get_onepelanggan($id)
	// {
	// 	$this->db->where('vw_pelanggan_fordetail.ID_IPKL', $id);
	// 	$result = $this->db->get('vw_pelanggan_fordetail');
		
	// 	return $result;
	// }

	public function get_onepelanggan_multiup($idipkl = null, $blok = null, $nokav = null)
{
    if (!empty($idipkl)) {
        $this->db->where('ID_IPKL', $idipkl);
    }

    if (!empty($blok)) {
        $this->db->where('BLOK', $blok);
    }

    if (!empty($nokav)) {
        $this->db->where('NO_KAVLING', $nokav);
    }

    return $this->db->get('vw_pelanggan_fordetail');
}

	public function get_onepelanggan($id)
{
    if (empty($id)) {
        // Hindari query tanpa filter
        $this->db->limit(0);
return $this->db->get('vw_pelanggan_fordetail');

    }

    $this->db->where('vw_pelanggan_fordetail.ID_IPKL', $id);
    return $this->db->get('vw_pelanggan_fordetail');
}

public function get_onepelanggan_by_kriteria($idipkl, $blok, $nokav)
{
    $this->db->from('vw_pelanggan_fordetail');
    if (!empty($idipkl)) $this->db->where('ID_IPKL', $idipkl);
    if (!empty($blok))   $this->db->where('BLOK', $blok);
    if (!empty($nokav))  $this->db->where('NO_KAVLING', $nokav);
    return $this->db->get()->row();
}




	public function get_onepelanggan1($id)
{
    $this->db->select('pelanggan.idipkl, pelanggan.blok, pelanggan.nokav, pelanggan.namapelanggan, cluster.namacluster, cluster.tarif');
    $this->db->from('pelanggan');
    $this->db->join('tagihan', 'pelanggan.idipkl = tagihan.idipkl');
    $this->db->join('cluster', 'pelanggan.idcluster = cluster.idcluster'); // JOIN ke tabel cluster

    if (!empty($id)) {
        $this->db->where('pelanggan.idipkl', $id);
    }

    return $this->db->get()->row();
}


	
	public function get_tagihan($idipkl)
	{
		$sql = "select T.idtagihan, P.idipkl, P.namapelanggan, C.namacluster, P.blok, P.nokav, T.tahun, T.bulan, T.tagihan,f_denda(T.tagihan,T.bulan,T.tahun,T.kenadenda) AS denda,T.tagihan + f_denda(T.tagihan,T.bulan,T.tahun,T.kenadenda) AS total from tagihan T Join pelanggan P on T.idipkl = P.idipkl Join cluster C on P.idcluster=C.idcluster where T.idipkl = '$idipkl' and P.idbork='B'";
			
		return $this->db->query($sql);
	}
        
        public function get_kuitansi_ulang($nomorkwitansi)
        {
            $this->db->where('kuitansi.nokuitansi', $nomorkwitansi);
            $result = $this->db->get('kuitansi');
		
            return $result;
        }
	
	public function get_tagihan_terbatas($idipkl,$limits)
	{
		$sql = "select T.idtagihan, P.idipkl, P.namapelanggan, C.namacluster, B.namabork, P.blok, P.nokav, T.tahun, T.bulan, T.tagihan,f_denda(T.tagihan,T.bulan,T.tahun,T.kenadenda) AS denda,T.tagihan + f_denda(T.tagihan,T.bulan,T.tahun,T.kenadenda) AS total from tagihan T Join pelanggan P on T.idipkl = P.idipkl Join cluster C on P.idcluster=C.idcluster Join bork B on P.idbork=B.idbork where T.idipkl = '$idipkl' and T.idstatustagihan='1'
 order by tahun asc, bulan asc limit $limits";
			
		return $this->db->query($sql);
	}
	
	public function get_tagihan_bylimit($idipkl,$limits)
	{
		$sql="select T.idtagihan, T.tahun, T.bulan, T.tagihan,f_denda(T.tagihan,T.bulan,T.tahun,T.kenadenda) AS denda,
T.tagihan + f_denda(T.tagihan,T.bulan,T.tahun,T.kenadenda) AS Jumlah, S.namastatustagihan
from tagihan T Join statustagihan S on T.idstatustagihan=S.idstatustagihan Join pelanggan P on T.idipkl=P.idipkl
where T.idipkl = '$idipkl' and T.idstatustagihan='1' 
order by tahun asc, bulan asc limit $limits";
		
		$result=$this->db->query($sql);
		
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}
	}
	
	public function get_tagihan_total($idipkl)
	{
		$sql ="select count(idipkl) AS jmlbulantagihan,sum(tagihan) AS jmltagihan,sum(f_denda(tagihan,bulan,tahun,kenadenda)) AS jmldenda,sum(tagihan + f_denda(tagihan,bulan,tahun,kenadenda)) AS jmltotal from tagihan where idipkl = '$idipkl' and idstatustagihan = '1'";

		return $this->db->query($sql);
	}
	
	public function get_nomor_kwitansi()
	{
		$sql ="select year(current_date()) as tahun, month(current_date()) as bulan,count(*)+1 as counter from kuitansi";
	
		return $this->db->query($sql);
	}
	
	public function cek_tagihan_sama($idipklnya,$tahunnya,$bulannya)
	{
		$sql ="select count(*) AS jml from tagihan where idipkl = '$idipklnya' and tahun = '$tahunnya' and bulan = '$bulannya'";
	
		return $this->db->query($sql);
	}

	public function get_tagihan_totalbylimit($idipkl,$limits)
	{
		$sql ="select count(idipkl) AS jmlbulantagihan,sum(tagihan) AS jmltagihan,sum(f_denda(tagihan,bulan,tahun)) AS jmldenda,sum(tagihan + f_denda(tagihan,bulan,tahun)) AS jmltotal from tagihan where idipkl = '$idipkl' and P.idbork='B' order by tahun asc, bulan asc
limit $limits";
	
		$result=$this->db->query($sql);
		
		if ($result->num_rows() == 1)
		{
			return $result->row_array();
		}
		else
		{
			return array();
		}		
	}
		
	public function get_tahun_bulan($idipkl)
	{
		$sql = "select tahun,bulan from tagihan where idipkl = '$idipkl' and idstatustagihan='1'";
				
		return $this->db->query($sql);
	}
	
	public function get_tahun_bulan_terbatas($idipkl,$limits)
	{
		$sql = "select tahun,bulan from tagihan where idipkl = '$idipkl' and idstatustagihan='1'
order by tahun asc, bulan asc limit $limits";
	
		return $this->db->query($sql);
	}
	
	public function updates_tagihan_lunas($idipklnya,$usernya)
	{
		$data = array(
				'tglbayar' => now(),
				'idloket' => '1',
				'idstatustagihan'=>'2', 
				'denda'=> 3000,
				'user_id'=> $usernya
		);
		
		$this->db->where('idipkl', $idipklnya);
		$this->db->update('tagihan', $data);
	
	}

	public function update_tagihan_lunas($idipklnya,$usernya,$nokwitnya)
	{
		$sql = "update tagihan set tglbayar=now(), user_id = '$usernya',idloket='1', idstatustagihan='2', denda = 0, nokuitansi='$nokwitnya' where idipkl = '$idipklnya'";
	
		return $this->db->query($sql);
	}
	
	public function update_tagihan_lunas_cicilan($idtagihanya,$dendanya,$usernya,$nokwitnya)
	{
		$sql = "update tagihan set tglbayar=now(), user_id = '$usernya',idloket='1', idstatustagihan='2', denda = $dendanya, nokuitansi='$nokwitnya' where idtagihan in ('$idtagihanya')";
	
		return $this->db->query($sql);
	}
	
	public function update_tagihan_lunas_mundur($idtagihanya,$usernya,$tgllunasnya)
	{
		$sql = "update tagihan set tglbayar='$tgllunasnya', user_id = '$usernya',idloket='1', idstatustagihan='2' where idtagihan in ('$idtagihanya')";
	
		return $this->db->query($sql);
	}
	
	public function update_tagihan_khusus($tahunnya,$bulannya)
	{
		$sql = "UPDATE tagihan T ,nilaitagihankhusus K SET T.tagihan=K.nilaitagihan
where T.idipkl=K.idipkl and tahun = '" . $tahunnya . "' and bulan = '" . $bulannya . "'";
	
		return $this->db->query($sql);
	}

	public function get_tunggakan_kavling($idclusternya)
	{
		$sql="select T.idipkl,P.namapelanggan,C.namacluster, P.blok,P.nokav,T.tahun,T.bulan,T.tagihan as tagihan
from tagihan T Right Join pelanggan P on T.idipkl= P.idipkl Join cluster C on P.idcluster=C.idcluster
where idstatustagihan = '1' and left(T.idipkl,2)='$idclusternya' and P.idbork='K' and tahun=year(now()) and bulan=month(now())";
		
		return $this->db->query($sql);
	}
	
	public function get_rekap_tunggakan($idclusternya)
	{
		$sql = "select T.idipkl,P.namapelanggan,H.namahuni, P.blok,P.nokav,sum(if(tahun=year(now()),if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())),T.tagihan,0) ,if(T.bulan=month(now()),T.tagihan,0)),0))tagihanbulanberjalan,
sum(if(tahun=year(now()),if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-1),T.tagihan,0) ,if(T.bulan=month(now())-1,T.tagihan,0)),
if(tahun=year(now())-1,if(month(now())-1=0,if(T.bulan=12,T.tagihan,0),0),if(tahun=year(now())-2,if(month(now())-1=0,
if(T.bulan=12,0,0),0),0))))tagihanbulanlalu,sum(if(tahun=year(now()),if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-2),T.tagihan,0) ,if(T.bulan=month(now())-2,T.tagihan,0)),
if(tahun=year(now())-1,if(month(now())-2=0,if(T.bulan=12,T.tagihan,0),if(T.bulan=11,T.tagihan,0)),if(tahun=year(now())-2,
if(month(now())-2=0,if(T.bulan=12,0,0),0),0))))tagihan2bulanlalu,sum(tagihan)-(sum(if(tahun=year(now()),if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())),T.tagihan,0) ,if(T.bulan=month(now()),T.tagihan,0)),
0))+sum(if(tahun=year(now()),if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-1),T.tagihan,0) ,if(T.bulan=month(now())-1,T.tagihan,0)),
if(tahun=year(now())-1,if(month(now())-1=0,if(T.bulan=12,T.tagihan,0),0),if(tahun=year(now())-2,if(month(now())-1=0,
if(T.bulan=12,0,0),0),0))))+sum(if(tahun=year(now()),if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-2),T.tagihan,0) ,if(T.bulan=month(now())-2,T.tagihan,0)),
if(tahun=year(now())-1,if(month(now())-2=0,if(T.bulan=12,T.tagihan,0),if(T.bulan=11,T.tagihan,0)),if(tahun=year(now())-2,
if(month(now())-2=0,if(T.bulan=12,0,0),0),0)))))as tagihan3bulanlebih,sum(T.tagihan) as Total
from tagihan T Right Join pelanggan P on T.idipkl= P.idipkl Join huni H on P.idhuni=H.idhuni
where T.idstatustagihan = '1' and left(T.idipkl,2)='".$idclusternya."' and P.idbork='B' group by T.idipkl";
				
		return $this->db->query($sql);
		
	}

	
	public function get_rekap_tunggakan_allcluster()
	{
		$sql = "select C.namacluster,sum(if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())),T.tagihan,0) ,if(T.bulan=month(now()),T.tagihan,0))
,0))tagihanbulanberjalan,sum(if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-1),T.tagihan,0) ,if(T.bulan=month(now())-1,T.tagihan,0))
,0))tagihanbulanlalu,sum(if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-2),T.tagihan,0) ,if(T.bulan=month(now())-2,T.tagihan,0))
,0))tagihan2bulanlalu,sum(tagihan)-(sum(if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())),T.tagihan,0) ,if(T.bulan=month(now()),T.tagihan,0))
,0))+sum(if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-1),T.tagihan,0) ,if(T.bulan=month(now())-1,T.tagihan,0))
,0))+sum(if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-2),T.tagihan,0) ,if(T.bulan=month(now())-2,T.tagihan,0))
,0))) as tagihan3bulanlebih,sum(T.tagihan) as Total
from tagihan T Join pelanggan P on T.idipkl= P.idipkl Join cluster C on P.idcluster=C.idcluster
where T.idstatustagihan='1' and P.idbork='B' group by left(T.idipkl,2)";
	
		return $this->db->query($sql);
	
	}
	
	public function get_lapbul()
	{
		$sql = "select C.namacluster,sum(if(T.idstatustagihan=1,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())),T.tagihan,0) ,if(T.bulan=month(now()),T.tagihan,0))
,0),0))tagihanbulanberjalan,sum(if(T.idstatustagihan=2,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())),T.tagihan,0) ,if(T.bulan=month(now()),T.tagihan,0))
,0),0))penerimaanbulanberjalan,sum(if(T.idstatustagihan=1,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-1),T.tagihan,0) ,if(T.bulan=month(now())-1,T.tagihan,0))
,0),0))tagihanbulanlalu,sum(if(T.idstatustagihan=2,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-1),T.tagihan,0) ,if(T.bulan=month(now())-1,T.tagihan,0))
,0),0))penerimaanbulanlalu,sum(if(T.idstatustagihan=1,
if(tahun=year(now()),if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-2),T.tagihan,0) ,if(T.bulan=month(now())-2,T.tagihan,0))
,0),0))tagihan2bulanlalu,sum(if(T.idstatustagihan=2,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-2),T.tagihan,0) ,if(T.bulan=month(now())-2,T.tagihan,0))
,0),0))penerimaan2bulanlalu,sum(if(T.idstatustagihan=1,T.tagihan,0))-(sum(if(T.idstatustagihan=1,
if(tahun=year(now()),if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())),T.tagihan,0) ,if(T.bulan=month(now()),T.tagihan,0))
,0),0))+ sum(if(T.idstatustagihan=1,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-1),T.tagihan,0) ,if(T.bulan=month(now())-1,T.tagihan,0))
,0),0))+sum(if(T.idstatustagihan=1,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-2),T.tagihan,0) ,if(T.bulan=month(now())-2,T.tagihan,0))
,0),0))) as tagihan3bulanlebih,sum(if(T.idstatustagihan=2,T.tagihan,0))-
(sum(if(T.idstatustagihan=2,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())),T.tagihan,0) ,if(T.bulan=month(now()),T.tagihan,0))
,0),0))+ sum(if(T.idstatustagihan=2,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-1),T.tagihan,0) ,if(T.bulan=month(now())-1,T.tagihan,0))
,0),0))+ sum(if(T.idstatustagihan=2,if(tahun=year(now()),
if(CHAR_LENGTH(month(now()))=1,if(T.bulan=concat('0',month(now())-2),T.tagihan,0) ,if(T.bulan=month(now())-2,T.tagihan,0))
,0),0))) as penerimaan3bulanlebih,sum(if(T.idstatustagihan=1,T.tagihan,0)) as totaltagihan,sum(
if(T.idstatustagihan=2,T.tagihan,0)) as totalpenerimaan,sum(if(T.idstatustagihan=2,T.denda,0)) as denda
from tagihan T Join pelanggan P on T.idipkl= P.idipkl Join cluster C on P.idcluster=C.idcluster
group by left(T.idipkl,2)";
				
		return $this->db->query($sql);
	}
	
	public function get_lapbul_berjalan($pertahun,$perbulan)
	{
		$sql = "SELECT left(T.idipkl,2),C.namacluster, P.pertahun,P.perbulan,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0)) AS Tagihan,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0)) as Penerimaan,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0) -
IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0)) as Piutang,
(SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0))/
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0)))*100 AS Efisiensi
FROM piutang P join tagihan T on P.idtagihan=T.idtagihan
join pelanggan L on T.idipkl=L.idipkl join cluster C on L.idcluster=C.idcluster
where P.pertahun='". $pertahun ."' and P.perbulan='" . $perbulan ."' 
Group by left(T.idipkl,2)
order by left(T.idipkl,2) asc";
	
		return $this->db->query($sql);
	}
	
	public function get_lapbul_bulanlalu($pertahunnya,$perbulannya)
	{
		$sql = "SELECT left(T.idipkl,2),C.namacluster, P.pertahun,P.perbulan,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0)) AS tagihanbulanlalu,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0)) as penerimaanbulanlalu,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0) -
IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0)) as piutangbulanlalu,
(SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0))/
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0))) * 100 AS efisiensibulanlalu
FROM piutang P join tagihan T on P.idtagihan=T.idtagihan
join pelanggan L on T.idipkl=L.idipkl join cluster C on L.idcluster=C.idcluster
where P.pertahun='". $pertahunnya ."' and P.perbulan='" . $perbulannya ."' 
Group by left(T.idipkl,2)
order by left(T.idipkl,2) asc";

		return $this->db->query($sql);
	}
	
	public function get_lapbul_2bulanlalu($pertahunnya,$perbulannya)
	{
		$sql = "SELECT left(T.idipkl,2),C.namacluster, P.pertahun,P.perbulan,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0)) AS tagihan2bulanlalu,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0)) as penerimaan2bulanlalu,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0) -
IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0)) as piutang2bulanlalu,
(SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0))/
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0))) * 100 AS efisiensi2bulanlalu
FROM piutang P join tagihan T on P.idtagihan=T.idtagihan
join pelanggan L on T.idipkl=L.idipkl join cluster C on L.idcluster=C.idcluster
where P.pertahun='". $pertahunnya ."' and P.perbulan='" . $perbulannya ."' 
Group by left(T.idipkl,2)
order by left(T.idipkl,2) asc";

		return $this->db->query($sql);
	}
	
	public function get_lapbul_3bulanlebih($pertahunnya,$perbulannya)
	{
		$sql = "SELECT left(T.idipkl,2),C.namacluster, P.pertahun,P.perbulan,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0)) AS tagihan3bulanlebih,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0)) as penerimaan3bulanlebih,
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0) -
IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0)) as piutang3bulanlebih,
(SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,
IF((T.tagihan+T.denda) is null,0,(T.tagihan+T.denda))
,0),0))/
SUM(IF(T.tahun=P.pertahun,IF(T.bulan=P.perbulan,P.tagihan,0),0))) * 100 AS efisiensi3bulanlebih
FROM piutang P join tagihan T on P.idtagihan=T.idtagihan
join pelanggan L on T.idipkl=L.idipkl join cluster C on L.idcluster=C.idcluster
where P.pertahun='". $pertahunnya ."' and P.perbulan='" . $perbulannya ."' 
Group by left(T.idipkl,2)
order by left(T.idipkl,2) asc";
		
		return $this->db->query($sql);	
	}
	
        public function get_lapbul_total($pertahun,$perbulan)
	{
		$sql = "select K.idipkl,K.nama,K.blok,K.nokavling,K.totaltagihan,K.tglbayar,
K.rincianbulan,K.nokuitansi,B.namacarabayar,L.namalewatbayar from kuitansi K join carabayar B on K.idcarabayar=B.idcarabayar
join lewatbayar L on K.idlewatbayar=L.idlewatbayar where year(K.tglbayar)='" . $pertahun . "'
and month(K.tglbayar)='" . $perbulan . "' order by tglbayar asc, namacarabayar asc ,namalewatbayar asc";
		
		return $this->db->query($sql);	
	}
        
        public function get_lapbul_total_pendapatan($pertahun,$perbulan)
	{
		$sql = "select distinct K.idipkl,K.nama,K.blok,K.nokavling,T.tagihan,K.tglbayar,
T.tahun,T.bulan,K.nokuitansi,B.namacarabayar,L.namalewatbayar from kuitansi K 
join tagihan T on T.nokuitansi = K.nokuitansi join carabayar B on K.idcarabayar=B.idcarabayar
join lewatbayar L on K.idlewatbayar=L.idlewatbayar where T.tahun='". $pertahun ."' and T.bulan='".$perbulan."' 
order by K.tglbayar asc, B.namacarabayar asc ,L.namalewatbayar asc";
		
		return $this->db->query($sql);	
	}
        
        public function get_lapbul_total_percluster($pertahun,$perbulan,$percluster)
	{
		$sql = "select K.idipkl,K.nama,K.blok,K.nokavling,K.totaltagihan,K.tglbayar,
K.rincianbulan,K.nokuitansi,B.namacarabayar,L.namalewatbayar from kuitansi K join carabayar B on K.idcarabayar=B.idcarabayar
join lewatbayar L on K.idlewatbayar=L.idlewatbayar where year(K.tglbayar)='" . $pertahun . "'
and month(K.tglbayar)='" . $perbulan . "' and left(K.idipkl,2)='" . $percluster . "' order by tglbayar asc, namacarabayar asc ,namalewatbayar asc";
		
		return $this->db->query($sql);	
	}
        
        public function get_lapbul_total_pendapatan_percluster($pertahun,$perbulan,$percluster)
	{
		$sql = "select distinct K.idipkl,K.nama,K.blok,K.nokavling,T.tagihan,K.tglbayar,
T.tahun,T.bulan,K.nokuitansi,B.namacarabayar,L.namalewatbayar from kuitansi K 
join tagihan T on T.nokuitansi = K.nokuitansi join carabayar B on K.idcarabayar=B.idcarabayar
join lewatbayar L on K.idlewatbayar=L.idlewatbayar where T.tahun='". $pertahun ."' and T.bulan='".$perbulan."' and left(T.idipkl,2)='" . $percluster . "' 
order by K.tglbayar asc, B.namacarabayar asc ,L.namalewatbayar asc";
		
		return $this->db->query($sql);	
	}
        
        public function get_lapbul_deposit_percluster($percluster,$pertahun,$perbulan)
	{
	/*	
            $sql = "select K.idipkl,K.nama,K.blok,K.nokavling,K.totaltagihan,K.tglbayar,
K.rincianbulan,K.nokuitansi,B.namacarabayar,L.namalewatbayar from kuitansi K join carabayar B on K.idcarabayar=B.idcarabayar
join lewatbayar L on K.idlewatbayar=L.idlewatbayar where year(K.tglbayar)='" . $pertahun . "'
and month(K.tglbayar)='" . $perbulan . "' and left(K.idipkl,2)='" . $percluster . "' order by tglbayar asc, namacarabayar asc ,namalewatbayar asc";
	*/
            $sql ="select T.idipkl, P.namapelanggan ,P.blok,P.nokav, T.tglbayar,
                T.tagihan, T.nokuitansi,T.tahun,T.bulan,B.namacarabayar,L.namalewatbayar
                    from tagihan T join kuitansi K on T.nokuitansi=K.nokuitansi
                    join pelanggan P on T.idipkl=P.idipkl
                    join carabayar B on K.idcarabayar=B.idcarabayar
                    join lewatbayar L on K.idlewatbayar=L.idlewatbayar
                    where T.idstatustagihan = '2'
                    and T.tahun = '" . $pertahun . "'
                    and T.bulan = '" . $perbulan . "'
                    and left(T.idipkl,2)='" . $percluster . "'
                    and T.tahun > year(K.tglbayar)
                    or
                    T.idstatustagihan = '2'
                    and T.tahun = '" . $pertahun . "'
                    and T.bulan = '" . $perbulan . "'
                    and left(T.idipkl,2)='" . $percluster . "'
                    and T.bulan > month(K.tglbayar)                    
                    order by tahun,bulan";
       
            /*
            $sql ="select T.idipkl, P.namapelanggan ,P.blok,P.nokav, T.tglbayar,
                T.tagihan, T.nokuitansi,T.tahun,T.bulan,B.namacarabayar,L.namalewatbayar
                    from tagihan T join kuitansi K on T.nokuitansi=K.nokuitansi
                    join pelanggan P on T.idipkl=P.idipkl
                    join carabayar B on K.idcarabayar=B.idcarabayar
                    join lewatbayar L on K.idlewatbayar=L.idlewatbayar
                    where T.idstatustagihan = '2'
                    and T.tahun > '" . $pertahun . "'
                    and left(T.idipkl,2)='" . $percluster . "'
                    and T.tahun > year(K.tglbayar)
                    or
                    T.idstatustagihan = '2'
                    and T.tahun = '" . $pertahun . "'
                    and T.bulan >= '" . $perbulan . "'
                    and left(T.idipkl,2)='" . $percluster . "'
                    and T.bulan > month(K.tglbayar)
                    or
                    T.idstatustagihan = '2'
                    and T.tahun = '" . $pertahun . "'
                    and left(T.idipkl,2)='" . $percluster . "'
                    and T.tahun > year(K.tglbayar)
                    order by tahun,bulan";
             * 
             */
            
            return $this->db->query($sql);	
	}
        
	public function get_tidak_diangkut($idclusternya)
	{
		$sql = "select T.idipkl,P.namapelanggan,H.namahuni, P.blok, P.nokav,
count(T.tagihan) as jmltunggakan, sum(T.tagihan) as jmltagihan from tagihan T join pelanggan P
on T.idipkl=P.idipkl join huni H on P.idhuni=H.idhuni where left(T.idipkl,2)='$idclusternya'
and T.idstatustagihan='1' and P.idbork='B' group by idipkl having count(jmltunggakan)>=2";
	
		return $this->db->query($sql);
	}
	
	public function get_pelanggan_percluster($idclusternya,$idhuninya)
	{
		$sql="select distinct(P.idipkl),P.namapelanggan,C.namacluster,P.blok,P.nokav,H.namahuni,C.tarif,P.idcluster
from pelanggan P join cluster C on P.idcluster=C.idcluster 
join tagihan T on P.idipkl=T.idipkl Join huni H on P.idhuni=H.idhuni where T.idstatustagihan='1'and P.idcluster= '$idclusternya' and P.idhuni='$idhuninya' and P.idbork='B'";
		
		return $this->db->query($sql);
	}

        public function get_pelanggan_perclusterpiutang1($idclusternya,$idhuninya) 
	{
		$sql="SELECT T.idipkl, P.namapelanggan, C.namacluster, P.blok, P.nokav, H.namahuni, C.tarif, count(T.idipkl) FROM tagihan T Join pelanggan P on T.idipkl = P.idipkl join cluster C on P.idcluster = C.idcluster Join huni H on P.idhuni=H.idhuni where T.idstatustagihan='1' and P.idbork='B' and left(T.idipkl,2)='$idclusternya' and P.idhuni='$idhuninya' group by T.idipkl having count(T.idipkl) > 1";
		
		return $this->db->query($sql);
	}
        
        public function get_pelanggan_perclusterpiutang2($idclusternya,$idhuninya) 
	{
		$sql="SELECT T.idipkl, P.namapelanggan, C.namacluster, P.blok, P.nokav, H.namahuni, C.tarif, count(T.idipkl) FROM tagihan T Join pelanggan P on T.idipkl = P.idipkl join cluster C on P.idcluster = C.idcluster Join huni H on P.idhuni=H.idhuni where T.idstatustagihan='1' and P.idbork='B' and left(T.idipkl,2)='$idclusternya' and P.idhuni='$idhuninya' group by T.idipkl having count(T.idipkl) > 2";
		
		return $this->db->query($sql);
	}
        
        public function get_pelanggan_perclusterpiutang3($idclusternya,$idhuninya) 
	{
		$sql="SELECT T.idipkl, P.namapelanggan, C.namacluster, P.blok, P.nokav, H.namahuni, C.tarif, count(T.idipkl) FROM tagihan T Join pelanggan P on T.idipkl = P.idipkl join cluster C on P.idcluster = C.idcluster Join huni H on P.idhuni=H.idhuni where T.idstatustagihan='1' and P.idbork='B' and left(T.idipkl,2)='$idclusternya' and P.idhuni='$idhuninya' group by T.idipkl having count(T.idipkl) > 3";
		
		return $this->db->query($sql);
	}

	public function get_pelanggan_by_blok_kavling($blok, $nokav)
{
    $sql = "SELECT DISTINCT(P.idipkl), 
                   P.namapelanggan, 
                   C.namacluster, 
                   P.blok, 
                   P.nokav, 
                   C.tarif, 
                   P.idcluster, 
                   H.namahuni
            FROM pelanggan P
            JOIN cluster C ON P.idcluster = C.idcluster
            JOIN huni H ON P.idhuni = H.idhuni
            JOIN tagihan T ON P.idipkl = T.idipkl
            WHERE T.idstatustagihan = '1'
              AND P.blok = ?
              AND P.nokav = ?
              AND P.idbork IN ('B','P')
            LIMIT 1"; // Optional: hanya ambil satu pelanggan saja

    return $this->db->query($sql, array($blok, $nokav));
}


        
	public function get_pelanggan_perpelanggan($idipklnya)
	{
		$sql="select distinct(P.idipkl),P.namapelanggan,C.namacluster,P.blok,P.nokav,C.tarif,P.idcluster, H.namahuni
		from pelanggan P join cluster C on P.idcluster=C.idcluster Join huni H on P.idhuni=H.idhuni 
		join tagihan T on P.idipkl=T.idipkl where T.idstatustagihan='1' and P.idipkl= '$idipklnya' and P.idbork in ('B','P')";
	
		return $this->db->query($sql);
	}
	
	public function get_tagihan_perid($idipkl)
	{
		$sql="select tahun,bulan,tagihan from tagihan where idipkl='$idipkl' and idstatustagihan='1'
order by tahun desc, bulan desc";
	
		return $this->db->query($sql);
	}

	public function get_tagihan_foraproval($idtagihan)
	{
		$sql="select idtagihan,idipkl,tahun,bulan,tagihan,denda,diskon,kenadiskon,kenadenda,ketaproval from tagihan
where idtagihan='$idtagihan'";
	
		return $this->db->query($sql);
	}
	
	public function get_lapcollector($idcluster)
	{
		$sql="SELECT T.idipkl,P.namapelanggan,P.blok,P.nokav,P.notelpon,P.nohp,H.namahuni,YEAR(DATE_SUB(CURDATE(), INTERVAL (COUNT(T.idipkl)-1) MONTH)) as tahun,
IF(LENGTH(MONTH(DATE_SUB(CURDATE(), INTERVAL (COUNT(T.idipkl)-1) MONTH)))=2, MONTH(DATE_SUB(CURDATE(), INTERVAL (COUNT(T.idipkl)-1) MONTH)), CONCAT('0',MONTH(DATE_SUB(CURDATE(), INTERVAL (COUNT(T.idipkl)-1) MONTH)))) as bulan,
COUNT(T.idipkl) as jmlbulan, SUM(T.tagihan) as tagihan FROM tagihan T
JOIN pelanggan P ON T.idipkl=P.idipkl JOIN huni H on P.idhuni=H.idhuni
WHERE T.idstatustagihan = '1' and P.idcluster='$idcluster' and P.idbork='B' 
group by T.idipkl
order by T.idipkl asc, T.tahun asc, T.bulan asc";
		
		return $this->db->query($sql);
	}
		
	public function get_totaltagihan_perid($idipkl)
	{
		$sql="select sum(tagihan) as total from tagihan where idipkl='$idipkl' and idstatustagihan='1'";
	
		return $this->db->query($sql);
	}
	
	public function get_alamat_perid($idipkl)
	{
		$sql="select P.idipkl, P.alamatktp,P.nohp,P.notelpon,C.namakecamatan,K.namakabupaten 
from pelanggan P left join kecamatan C on P.idkecamatan=C.idkecamatan left join kabupaten K
on C.idkabupaten=K.idkabupaten where P.idipkl='$idipkl'";
	
		return $this->db->query($sql);
	}
	
	public function update_tagihan_dendadiskon($idtagihannya,$nilaidiskonnya,$kenadiskonnya,$kenadendanya,$usernya,$ket)
	{
		$data=array(
				'tagihan'=>$nilaidiskonnya,
				'kenadiskon'=>$kenadiskonnya,
				'kenadenda'=>$kenadendanya,
				'user_id_aprover'=>$usernya,
				'ketaproval'=>$ket
		);
		
		$this->db->where('idtagihan',$idtagihannya);
		$this->db->update('tagihan',$data);
	}
	
	public function update_tagihan($idtagihannya, $tahunnya, $bulannya, $tagihannya)
	{
		$data=array(
				'tahun'=>$tahunnya,
				'bulan'=>$bulannya,
				'tagihan'=>$tagihannya
		);
	
		$this->db->where('idtagihan',$idtagihannya);
		$this->db->update('tagihan',$data);
	}

	public function update_tagihan_saja($idtagihannya, $tagihannya)
	{
		$data=array(
				'tagihan'=>$tagihannya
		);
	
		$this->db->where('idtagihan',$idtagihannya);
		$this->db->update('tagihan',$data);
	}
	
	public function update_tagihan_denda($idtagihannya,$kenadiskonnya,$kenadendanya,$usernya,$ket)
	{
		$data=array(
				'kenadiskon'=>$kenadiskonnya,
				'kenadenda'=>$kenadendanya,
				'user_id_aprover'=>$usernya,
				'ketaproval'=>$ket
		);
		
		$this->db->where('idtagihan',$idtagihannya);
		$this->db->update('tagihan',$data);
	}
	
	public function get_lpp($tglnya)
	{
		$sql="select nokuitansi,idipkl,nama,blok,nokavling,jumlahtotal,jumlahtagihan,date(tglbayar) as tglbayar,rincianbulan from kuitansi
where date(tglbayar) = '$tglnya' order by nokuitansi asc";

		return $this->db->query($sql);
	}
        
	public function get_lppCashLoket($tglnya)
	{
		$sql="select nokuitansi,idipkl,nama,blok,nokavling,jumlahtotal,jumlahtagihan,date(tglbayar) as tglbayar,rincianbulan from kuitansi
where date(tglbayar) = '$tglnya' and idcarabayar='c'  and idlewatbayar='l' order by nokuitansi asc";

		return $this->db->query($sql);
	}
        
        public function get_lppCashKolektor($tglnya)
	{
		$sql="select nokuitansi,idipkl,nama,blok,nokavling,jumlahtotal,jumlahtagihan,date(tglbayar) as tglbayar,rincianbulan from kuitansi
where date(tglbayar) = '$tglnya' and idcarabayar='c'  and idlewatbayar='k' order by nokuitansi asc";

		return $this->db->query($sql);
	}
        
	public function get_lppDebet($tglnya)
	{
		$sql="select nokuitansi,idipkl,nama,blok,nokavling,jumlahtotal,jumlahtagihan,date(tglbayar) as tglbayar,rincianbulan from kuitansi
where date(tglbayar) = '$tglnya' and idcarabayar='d' order by nokuitansi asc";

		return $this->db->query($sql);
	}
	
	function getCaraBayarList(){
		$result = array();
		$this->db->select('idcarabayar,namacarabayar');
		$this->db->from('carabayar');
		$array_keys_values = $this->db->get();
		foreach ($array_keys_values->result() as $row)
		{
			$result[0]= '-Pilih Cara Pembayaran-';
			$result[$row->idcarabayar]= $row->namacarabayar;
		}
	
		return $result;
	}
	
	function getLewatBayarList(){
		$result = array();
		$this->db->select('idlewatbayar,namalewatbayar');
		$this->db->from('lewatbayar');
		$array_keys_values = $this->db->get();
		foreach ($array_keys_values->result() as $row)
		{
			$result[0]= '-Pilih Pembayaran Melalui-';
			$result[$row->idlewatbayar]= $row->namalewatbayar;
		}
	
		return $result;
	}
	
	// =====================================================================================
	
	/**
	 * Proses rekap data absensi dengan kriteria semester dan kelas tertentu
	 */
	 
	public function get_all($limit, $offset) 
    {
        $result = $this->db->get('vw_pelanggan',$limit, $offset);
        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        } 
        else 
        {
            return array();
        }
    } 
	
    public function get_all_cluster($limit, $offset)
    {
    	$result = $this->db->get('cluster',$limit, $offset);
    	if ($result->num_rows() > 0)
    	{
    		return $result->result_array();
    	}
    	else
    	{
    		return array();
    	}
    }
    
	public function get_search($limit, $offset) 
    {
        $keyword = $this->session->userdata('keyword');
                
		$this->db->like('nama', $keyword);  
                
		$this->db->limit($limit, $offset);
        
		$result = $this->db->get('vw_anggota');

        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        } 
        else 
        {
            return array();
        }
    }
    
    public function getdesa_search($limit, $offset)
    {
    	$keyword = $this->session->userdata('keyword');
    
    	$this->db->like('namadesa', $keyword);
    
    	$this->db->limit($limit, $offset);
    
    	$result = $this->db->get('vw_desa_all');
    
    	if ($result->num_rows() > 0)
    	{
    		return $result->result_array();
    	}
    	else
    	{
    		return array();
    	}
    }
    
    /**
    * Search All anggota
    * @param keyword : mixed
    *
    * @return Integer
    *
    */
    public function count_all_search()
    {
        $keyword = $this->session->userdata('keyword');
		//echo $keyword;
		
        $this->db->from('vw_anggota');   
                
		$this->db->like('nama', $keyword);  
        
        return $this->db->count_all_results();
    }
    
    /**
     * Search All anggota
     * @param keyword : mixed
     *
     * @return Integer
     *
     */
    public function countdesa_all_search()
    {
    	$keyword = $this->session->userdata('keyword');
    	//echo $keyword;
    
    	$this->db->from('vw_desa_all');
    
    	$this->db->like('namadesa', $keyword);
    
    	return $this->db->count_all_results();
    }
	
	
	public function get_one($id) 
    {
        $this->db->where('vw_pelanggan_fordetail.ID_IPKL', $id);
        $result = $this->db->get('vw_pelanggan_fordetail');

        if ($result->num_rows() == 1) 
        {
            return $result->row_array();
        } 
        else 
        {
            return array();
        }
    }
    
    public function get_one_desa($id)
    {
    	$this->db->where('vw_desa_fordetail.ID_DESA', $id);
    	$result = $this->db->get('vw_desa_fordetail');
    
    	if ($result->num_rows() == 1)
    	{
    		return $result->row_array();
    	}
    	else
    	{
    		return array();
    	}
    }
    
    public function get_one_kec($id)
    {
    	$this->db->where('vw_kec_fordetail.ID_KECAMATAN', $id);
    	$result = $this->db->get('vw_kec_fordetail');
    
    	if ($result->num_rows() == 1)
    	{
    		return $result->row_array();
    	}
    	else
    	{
    		return array();
    	}
    }
	
	public function get_tagihan_fordetail($id) 
    {
        $this->db->where('vw_tagihan_fordetail.idipkl', $id);
        $result = $this->db->get('vw_tagihan_fordetail');
		
        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        } 
        else 
        {
            return array();
        }
    }
	
	public function get_one_for_edit($id) 
    {
        $this->db->where('idipkl', $id);
        $result = $this->db->get('pelanggan');

        if ($result->num_rows() == 1) 
        {
            return $result->row_array();
        } 
        else 
        {
            return array();
        }
    }
    
    public function get_onedesa_for_edit($id)
    {
    	$this->db->where('iddesa', $id);
    	$result = $this->db->get('tbl_desa');
    
    	if ($result->num_rows() == 1)
    	{
    		return $result->row_array();
    	}
    	else
    	{
    		return array();
    	}
    }
    
    
    public function get_onekec_for_edit($id)
    {
    	$this->db->where('idkec', $id);
    	$result = $this->db->get('tbl_kecamatan');
    
    	if ($result->num_rows() == 1)
    	{
    		return $result->row_array();
    	}
    	else
    	{
    		return array();
    	}
    }
	
	public function add()
    {
        $data = array(
		
				'idipkl' => '',
                
				'namapelanggan' => '',
            
                'idcluster' => '',
            
                'blok' => '',
			
				'nokav' => '',
            
                'bork' => '',
            
                'nohp' => '',
            
                'notelpon' => '',
            
                'alamatktp' => '',
        		
        		'idkecamatan' => '',
        		
        		'lb' => '',
        			
        		'lt' => '',
        		
        		'email' => '',
        		
        		'tglserahterima' => '',
        		
        		'idstatuspelanggan' => '',
        		
        		'user_id' => '',
            
        );

        return $data;
    }
    
    public function adddesa()
    {
    	$data = array(
    
    			'iddesa' => '',
    
    			'idkec' => '',
    
    			'namadesa' => '',
    	);
    
    	return $data;
    }
    
    public function addkec()
    {
    	$data = array(
    
    			'idkec' => '',

    			'namakec' => '',
    	);
    
    	return $data;
    }
    
    /**
    *  Save data Post
    *
    *  @return void
    *
    */
    public function save() 
    {
        $data = array(
		
			'idanggota' => strip_tags($this->input->post('noanggota', TRUE)),
        
            'nama' => strip_tags($this->input->post('nama', TRUE)),
        
            'alamat' => strip_tags($this->input->post('alamat', TRUE)),
        
            'iddesa' => strip_tags($this->input->post('cbodesa', TRUE)),
        
            'idjk' => strip_tags($this->input->post('cbojk', TRUE)),
        
            'idagama' => strip_tags($this->input->post('cboagama', TRUE)),
        
            'tglmasuk' => strip_tags($this->input->post('tglmasuk', TRUE)),
        
            'idstatus' => strip_tags($this->input->post('cbostatus', TRUE)),
        
        );
                
        $this->db->insert('tbl_anggota', $data);
    }
    
    function getClusterList(){
    	$result = array();
    	$this->db->select('idcluster,namacluster');
    	$this->db->from('cluster');
    	$array_keys_values = $this->db->get();
    	foreach ($array_keys_values->result() as $row)
    	{
    		$result[0]= '-Pilih Cluster-';
    		$result[$row->idcluster]= $row->namacluster;
    	}
    
    	return $result;
    }
    
    function getBorK(){
    	$result = array();
    	$this->db->select('*');
    	$this->db->from('bork');
    	$array_keys_values = $this->db->get();
    	foreach ($array_keys_values->result() as $row)
    	{
    		$result[0]= '-Pilih Jenis Bangunan-';
    		$result[$row->idbork]= $row->namabork;
    	}
    
    	return $result;
    }
    
    /**
     *  Save data Post
     *
     *  @return void
     *
     */
    public function savedesa()
    {
    	$data = array(
    
    			'iddesa' => strip_tags($this->input->post('iddesa', TRUE)),
    
    			'idkec' => strip_tags($this->input->post('cbokec', TRUE)),
    
    			'namadesa' => strip_tags($this->input->post('namadesa', TRUE)),
    	);
    
    	$this->db->insert('tbl_desa', $data);
    }
    
    public function savekec()
    {
    	$data = array(
    
    			'idkec' => strip_tags($this->input->post('idkec', TRUE)),
    
    			'namakec' => strip_tags($this->input->post('namakec', TRUE)),
    	);
    
    	$this->db->insert('tbl_kecamatan', $data);
    }
    
    /**
    *  Update modify data
    *
    *  @param id : Integer
    *
    *  @return void
    *
    */
    public function update($id)
    {
        $data = array(
        
                'nama' => strip_tags($this->input->post('nama', TRUE)),
        
                'alamat' => strip_tags($this->input->post('alamat', TRUE)),
        
                'iddesa' => strip_tags($this->input->post('cbodesa', TRUE)),
        
                'idjk' => strip_tags($this->input->post('cbojk', TRUE)),
        
                'idagama' => strip_tags($this->input->post('cboagama', TRUE)),
        
                'tglmasuk' => strip_tags($this->input->post('tglmasuk', TRUE)),
        
                'idstatus' => strip_tags($this->input->post('cbostatus', TRUE)),
        
        );        
        
        $this->db->where('idanggota', $id);
        $this->db->update('tbl_anggota', $data);
    }
    
    /**
     *  Update modify data
     *
     *  @param id : Integer
     *
     *  @return void
     *
     */
    public function updatedesa($id)
    {
    	$data = array(
    
    			'iddesa' => strip_tags($this->input->post('iddesa', TRUE)),
    
    			'idkec' => strip_tags($this->input->post('cbokec', TRUE)),
    
    			'namadesa' => strip_tags($this->input->post('namadesa', TRUE)),
    
    	);
    
    	$this->db->where('iddesa', $id);
    	$this->db->update('tbl_desa', $data);
    }
    
    public function updatekec($id)
    {
    	$data = array(
    
	   			'idkec' => strip_tags($this->input->post('idkec', TRUE)),
    
    			'namakec' => strip_tags($this->input->post('namakec', TRUE)),
    
    	);
    
    	$this->db->where('idkec', $id);
    	$this->db->update('tbl_kecamatan', $data);
    }
       
    
    /**
    *  Delete data by id
    *
    *  @param id : Integer
    *
    *  @return void
    *
    */
    public function destroy($id)
    {       
        $this->db->where('idanggota', $id);
        $this->db->delete('tbl_anggota');        
    }
	
    /**
     *  Delete data by id
     *
     *  @param id : Integer
     *
     *  @return void
     *
     */
    public function destroydesa($id)
    {
    	$this->db->where('iddesa', $id);
    	$this->db->delete('tbl_desa');
    
    }   

    public function destroykec($id)
    {
    	$this->db->where('idkec', $id);
    	$this->db->delete('tbl_kecamatan');
    
    } 
    
    public function get_status() 
    {
      
        $result = $this->db->get('tbl_status')
                           ->result();

        $ret ['']= 'Pilih Status :';
        if($result)
        {
            foreach ($result as $key => $row)
            {
                $ret [$row->idstatus] = $row->namastatus;
            }
        }
        
        return $ret;
    }
	
	function count_all()
	{
		return $this->db->count_all('pelanggan');
	}
	
	function count_all_cluster()
	{
		return $this->db->count_all('cluster');
	}
	
	function count_desa_all()
	{
		return $this->db->count_all('tbl_desa');
	}	
	
	function count_kec_all()
	{
		return $this->db->count_all('tbl_kecamatan');
	}
	
/* 	function get_kec()
	{
		$this->db->order_by('idkec');
		return $this->db->get('kecamatan');
	} */
	
	function get_kecamatan_by_id($idkec)
	{
		return $this->db->get_where('kecamatan', array('idkec' => $idkec))->row();
	}
	
	function count_kec()
	{
		return $this->db->count_all('kecamatan');
	}
	
	function deletekec($idkec)
	{
		$this->db->delete('kecamatan', array('idkec' => $idkec));
	}
	
	function get_desa_all($limit, $offset)
	{
	    $result = $this->db->get('vw_desa_all',$limit, $offset);
        if ($result->num_rows() > 0) 
        {
            return $result->result_array();
        } 
        else 
        {
            return array();
        }	
	}
	
	function get_kec_all($limit, $offset)
	{
		$result = $this->db->get('tbl_kecamatan',$limit, $offset);
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}
	}
	
	function get_desa_by_idkec($idkec)
	{
		return $this->db->get_where('tbl_desa', array('idkec' => $idkec))->row();
	}
	
	function count_desa()
	{
		return $this->db->count_all('tbl_desa');
	}
	
	function adddesaold($desa)
	{
		$this->db->insert('tbl_desa', $desa);
	}
	
	function get_desa_by_iddesa($iddesa)
	{
		return $this->db->get_where('tbl_desa', array('iddesa' => $iddesa))->row();
	}
	
	function updatedesaold($iddesa, $desa)
	{
		$this->db->where('iddesa', $iddesa);
		$this->db->update('tbl_desa', $desa);
	}
	
	function deletedesaold($iddesa)
	{
		$this->db->delete('tbl_desa', array('iddesa' => $iddesa));
	}
	
	function getKecamatanList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('tbl_kecamatan');
		$this->db->order_by('namakec','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result[0]= '-Pilih Kecamatan-';
            $result[$row->idkec]= $row->namakec;
        }
        
        return $result;
	}

	function getDesaList(){
		$idkec = $this->input->post('idkec');
		$result = array();
		$this->db->select('*');
		$this->db->from('tbl_desa');
		$this->db->where('idkec',$idkec);
		$this->db->order_by('namadesa','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result[0]= '-Pilih Desa-';
            $result[$row->iddesa]= $row->namadesa;
        }
        
        return $result;
	}
	
	
	function getDesaListEdit($idkec){
		//$idkec = $anggota->id;
		$result = array();
		$this->db->select('*');
		$this->db->from('tbl_desa');
		$this->db->where('idkec',$idkec);
		$this->db->order_by('namadesa','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result[0]= '-Pilih Desa-';
            $result[$row->iddesa]= $row->namadesa;
        }
        
        return $result;
	}
	
/* 	function add($anggota)
	{
		$this->db->insert($this->table, $anggota);
	} */
	
	function get_anggota_by_id($anggota)
	{
		return $this->db->get_where('vw_anggota_all', array('noanggota' => $anggota))->row();
	}
	

	
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */
