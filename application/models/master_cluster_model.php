<?php
class Master_cluster_model extends CI_Model {

   function Master_cluster_model()
	{
		parent::__Construct();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'tbl_anggota';
public function count_all_search($keyword = '')
{
    if (!empty($keyword)) {
        $this->db->like('namacluster', $keyword);
    }
    return $this->db->count_all_results('cluster');
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

public function get_search($keyword = '', $limit = 10, $offset = 0)
{
    if (!empty($keyword)) {
        $this->db->like('namacluster', $keyword);
    }
    $this->db->order_by('idcluster', 'ASC');
    $query = $this->db->get('cluster', $limit, $offset);
    return $query->result_array();
}



}
?>