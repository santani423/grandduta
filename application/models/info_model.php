<?php
/**
 * Info_model Class
 *
 * @author	Trias Bratakusuma <bratatkr@gmail.com>
 */
class Info_model extends CI_Model {
	/**
	 * Constructor
	 */
	function Info_model()
	{
		parent::__Construct();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'tagihan';
	
	public function get_plg_bynama($keyword)
	{
// 		$this->db->from('vw_pelanggan');
	
		$this->db->like('namapelanggan', $keyword);
		
// 		$this->db->where('vw_desa_fordetail.ID_DESA', $id);
		$result = $this->db->get('vw_pelanggan');
		
	    if ($result->num_rows() > 0)
    	{
    		return $result->result_array();
    	}
    	else
    	{
    		return array();
    	}
	}
	
	public function get_plg_byalamat($idcluster,$blok,$nokav)
	{
		$this->db->where('idcluster', $idcluster);
		$this->db->or_where('blok', $blok);
		$this->db->or_where('nokav', $nokav);
		
		$result = $this->db->get('vw_pelanggan2');
	
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}
	}
	
	public function get_plg_by_clusterbloknokav($idcluster,$blok,$nokav)
	{
		$this->db->where('idcluster', $idcluster);
		$this->db->where('blok', $blok);
		$this->db->where('nokav', $nokav);
		
		$result = $this->db->get('vw_pelanggan2');
		
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}		
	}

	public function get_plg_by_clusterblok($idcluster,$blok)
	{
		$this->db->where('idcluster', $idcluster);
		$this->db->where('blok', $blok);
		
		$result = $this->db->get('vw_pelanggan2');
		
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}		
	}
	
	public function get_plg_by_clusternokav($idcluster,$nokav)
	{
		$this->db->where('idcluster', $idcluster);
		$this->db->where('nokav', $nokav);
		
		$result = $this->db->get('vw_pelanggan2');
		
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}		
	}
	
	public function get_plg_by_cluster($idcluster)
	{
		$this->db->where('idcluster', $idcluster);
		
		$result = $this->db->get('vw_pelanggan2');
		
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}		
	}
	
	public function get_plg_by_bloknokav($blok,$nokav)
	{
		$this->db->where('blok', $blok);
		$this->db->where('nokav', $nokav);
		
		$result = $this->db->get('vw_pelanggan2');
		
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}		
	}
	
	public function get_plg_by_blok($blok)
	{
		$this->db->where('blok', $blok);
		
		$result = $this->db->get('vw_pelanggan2');
		
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}		
	}
	
	public function get_plg_by_nokav($nokav)
	{
		$this->db->where('nokav', $nokav);
		
		$result = $this->db->get('vw_pelanggan2');
		
		if ($result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return array();
		}		
	}
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */