<?php
/**
 * Rekap_model Class
 *
 * @author	Trias Bratakusuma <brata@pdamtkr.co.id>
 */
class Master_model extends CI_Model {
	/**
	 * Constructor
	 */
	function Master_model()
	{
		parent::__Construct();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'tbl_anggota';
	
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
    
    public function get_master_pelanggan($idclusternya,$idborknya)
    {
    	$sql = "select distinct P.idipkl,P.namapelanggan,H.namahuni,P.blok,P.nokav,P.notelpon,P.nohp,P.tglserahterima
from pelanggan P join bork B on P.idbork=B.idbork join huni H on P.idhuni=H.idhuni 
Where P.idcluster='$idclusternya' and P.idbork='$idborknya' order by P.idipkl asc";
    		
    	return $this->db->query($sql);
    }
    
    public function get_master_kavling($idclusternya,$idborknya)
    {
    	$sql = "select distinct P.idipkl,P.namapelanggan,H.namahuni,P.blok,P.nokav,P.notelpon,P.nohp,P.tglserahterima
    	from pelanggan P join bork B on P.idbork=B.idbork join huni H on P.idhuni=H.idhuni
    	Where P.idcluster='$idclusternya' and P.idbork='$idborknya' order by  P.idipkl asc";
    
    	return $this->db->query($sql);
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
                
		$this->db->like('namapelanggan', $keyword);  
                
                $this->db->or_like('blok', $keyword);  
                
		$this->db->limit($limit, $offset);
        
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
		
        $this->db->from('vw_pelanggan');   
                
		$this->db->like('namapelanggan', $keyword);  
                
                $this->db->or_like('blok', $keyword); 
        
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

	public function search_pelanggan2($idipkl = '', $blok = '', $nokav = '')
{
    $this->db->select('pelanggan.idipkl, pelanggan.namapelanggan, pelanggan.blok, pelanggan.nokav, cluster.namacluster');
    $this->db->from('pelanggan');
    $this->db->join('tagihan', 'pelanggan.idipkl = tagihan.idipkl', 'left');
	$this->db->join('cluster', 'pelanggan.idcluster = cluster.idcluster', 'left'); // JOIN ke cluster


    if (!empty($idipkl)) {
        $this->db->where('pelanggan.idipkl', $idipkl);
    }
    if (!empty($blok)) {
        $this->db->where('pelanggan.blok', $blok);
    }
    if (!empty($nokav)) {
        $this->db->where('pelanggan.nokav', $nokav);
    }

    $query = $this->db->get();
    return $query->row_array(); // array agar cocok dengan foreach view
}


	public function search_pelanggan($idipkl = '', $blok = '', $nokav = '')
{
    $this->db->select('pelanggan.idipkl, pelanggan.blok, tagihan.idipkl AS tagihan_idipkl, pelanggan.nokav');
    $this->db->from('pelanggan');
    $this->db->join('tagihan', 'pelanggan.idipkl = tagihan.idipkl');

    if (!empty($idipkl)) {
        $this->db->where('pelanggan.idipkl', $idipkl);
    }
    if (!empty($blok)) {
        $this->db->where('pelanggan.blok', $blok);
    }
    if (!empty($nokav)) {
        $this->db->where('pelanggan.nokav', $nokav);
    }

    $query = $this->db->get();
    return $query->row(); // Mengembalikan satu baris sebagai object
}


    
    public function get_onecluster($id) 
    {
        $this->db->where('idcluster', $id);
        $result = $this->db->get('cluster');

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
    
    public function get_oneplg_foredit($id)
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
    
        public function get_onecluster_foredit($id)
    {
    	$this->db->where('idcluster', $id);
    	$result = $this->db->get('cluster');

        if ($result->num_rows() == 1)
    	{
    		return $result->row_array();
    	}
    	else
    	{
    		return array();
    	}    	
    }
    
    public function get_oneplgkav_foredit($id)
    {
    	$this->db->where('idipkl', $id);
        $this->db->where('idbork', 'K');
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
            
                'idbork' => '',
            
                'nohp' => '',
            
                'notelpon' => '',
            
                'alamatktp' => '',
        		
        		'idkecamatan' => '',
        		
        		'lb' => '',
        			
        		'lt' => '',
        		
        		'email' => '',
        		
        		'tglserahterima' => '',
        		
        		'idstatuspelanggan' => '',
            
                        'idhuni' => '',
        		
        		'user_id' => '',
            
        );

        return $data;
    }
    
    public function addcluster()
    {
        $data = array(
		
		'idcluster' => '',
                
		'namacluster' => '',
            
                'tarif' => '',
            
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
		$data = [
			'idipkl'              => $this->input->post('idipkl', TRUE),
			'namapelanggan'       => $this->input->post('namapelanggan', TRUE),
			'idcluster'           => $this->input->post('cbocluster', TRUE),
			'blok'                => $this->input->post('blok', TRUE),
			'nokav'               => $this->input->post('nokav', TRUE),
			'idbork'              => $this->input->post('cbobork', TRUE),
			'nohp'                => $this->input->post('nohp', TRUE),
			'notelpon'            => $this->input->post('notelpon', TRUE),
			'alamatktp'           => $this->input->post('alamatktp', TRUE),
			'idkecamatan'         => $this->input->post('cbokec', TRUE),
			'lb'                  => $this->input->post('lb', TRUE),
			'lt'                  => $this->input->post('lt', TRUE),
			'email'               => $this->input->post('email', TRUE),
			'tglserahterima'      => $this->input->post('tglserahterima', TRUE),
			'idstatuspelanggan'   => $this->input->post('cbostatusplg', TRUE),
			'user_id'             => $this->session->userdata('user_id')
		];

		return $this->db->insert('pelanggan', $data);
	}

    
    public function savecluster() 
    {
        $data = array(
        		
        		'idcluster' => strip_tags($this->input->post('idcluster', TRUE)),
        		
        		'namacluster' => strip_tags($this->input->post('namacluster', TRUE)),
        		
        		'tarif' => strip_tags($this->input->post('tarif', TRUE)),
     );
                
        $this->db->insert('cluster', $data);
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
    
    function getBorKList(){
    	$result = array();
    	$this->db->select('idbork,namabork');
    	$this->db->from('bork');
    	$array_keys_values = $this->db->get();
    	foreach ($array_keys_values->result() as $row)
    	{
    		$result[0]= '-Pilih Bangunan/Kavling-';
    		$result[$row->idbork]= $row->namabork;
    	}
    
    	return $result;
    }
    
    function getStatusPelanggan(){
    	$result = array();
    	$this->db->select('idstatuspelanggan,namastatuspelanggan');
    	$this->db->from('statuspelanggan');
    	$array_keys_values = $this->db->get();
    	foreach ($array_keys_values->result() as $row)
    	{
    		$result[0]= '-Pilih Status Pelanggan-';
    		$result[$row->idstatuspelanggan]= $row->namastatuspelanggan;
    	}
    
    	return $result;
    }
    
    function getStatusHuni(){
    	$result = array();
    	$this->db->select('idhuni,namahuni');
    	$this->db->from('huni');
    	$array_keys_values = $this->db->get();
    	foreach ($array_keys_values->result() as $row)
    	{
    		$result[0]= '-Pilih Status Hunian-';
    		$result[$row->idhuni]= $row->namahuni;
    	}
    
    	return $result;
    }
    
    function get_kenadenda(){
    	$result = array();
    	$this->db->select('kenadenda,keterangan');
    	$this->db->from('kenadenda');
    	$array_keys_values = $this->db->get();
    	foreach ($array_keys_values->result() as $row)
    	{
    		$result[0]= '-Pilih Keterangan-';
    		$result[$row->kenadenda]= $row->keterangan;
    	}
    
    	return $result;
    }
    
    function get_kenadiskon(){
    	$result = array();
    	$this->db->select('kenadiskon,keterangan');
    	$this->db->from('kenadiskon');
    	$array_keys_values = $this->db->get();
    	foreach ($array_keys_values->result() as $row)
    	{
    		$result[0]= '-Pilih Keterangan-';
    		$result[$row->kenadiskon]= $row->keterangan;
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
		$data = [
			'namapelanggan'       => $this->input->post('namapelanggan', TRUE),
			'idcluster'           => $this->input->post('cbocluster', TRUE),
			'blok'                => $this->input->post('blok', TRUE),
			'nokav'               => $this->input->post('nokav', TRUE),
			'idbork'              => $this->input->post('cbobork', TRUE),
			'nohp'                => $this->input->post('nohp', TRUE),
			'notelpon'            => $this->input->post('notelpon', TRUE),
			'alamatktp'           => $this->input->post('alamatktp', TRUE),
			'idkecamatan'         => $this->input->post('cbokec', TRUE),
			'lb'                  => $this->input->post('lb', TRUE),
			'lt'                  => $this->input->post('lt', TRUE),
			'email'               => $this->input->post('email', TRUE),
			'tglserahterima'      => $this->input->post('tglserahterima', TRUE),
			'idstatuspelanggan'   => $this->input->post('cbostatusplg', TRUE),
			'user_id'             => $this->session->userdata('user_id')
		];

		$this->db->where('idipkl', $id);
		return $this->db->update('pelanggan', $data);
	}

    
    public function updatecluster($id)
    {
        $data = array(
                		
        		'namacluster' => strip_tags($this->input->post('namacluster', TRUE)),
        		
        		'tarif' => strip_tags($this->input->post('tarif', TRUE)),
        		        		
        );        
        
        $this->db->where('idcluster', $id);
        $this->db->update('cluster', $data);
    }
    
    public function updatekavtobang($id)
    {
        $data = array(
                		
        		'namapelanggan' => strip_tags($this->input->post('namapelanggan', TRUE)),
        		
        		'idbork' => strip_tags($this->input->post('cbobork', TRUE)),
        		
                        'lb' => strip_tags($this->input->post('lb', TRUE)),
            
                        'lt' => strip_tags($this->input->post('lt', TRUE)),
            
                        'tglserahterima' => strip_tags($this->input->post('tglserahterima', TRUE)),
        		
        		'user_id' => strip_tags($this->session->userdata('user_id')),
        		
        );        
        
        $this->db->where('idipkl', $id);
        $this->db->update('pelanggan', $data);
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
        $this->db->where('idipkl', $id);
        $this->db->delete('pelanggan');
        
    }
    
    public function destroycluster($id)
    {       
        $this->db->where('idcluster', $id);
        $this->db->delete('cluster');
        
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
        
        function count_kavling()
	{
		return $this->db->count_all('vw_pelanggan_kavling');
	}
        
        function count_bangunan()
	{
		return $this->db->count_all('vw_pelanggan_bangunan');
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
        
        function delpiutangkavtobang($id)
        {
            $sql = "delete from tagihan where idipkl='$id' and tahun = year(now()) and bulan <> month(now()) OR idipkl='$id' and tahun <> year(now())";
    
            return $this->db->query($sql);
        }
                
	function getKabupatenList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('kabupaten');
		$this->db->order_by('namakabupaten','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result[0]= '-Pilih Kabupaten-';
            $result[$row->idkabupaten]= $row->namakabupaten;
        }
        
        return $result;
	}

	function getKecamatanList_byid(){
		$idkab = $this->input->post('cbokab');
		$result = array();
		$this->db->select('*');
		$this->db->from('kecamatan');
		$this->db->where('idkabupaten',$idkab);
		$this->db->order_by('namakecamatan','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result[0]= '-Pilih Kecamatan-';
            $result[$row->idkecamatan]= $row->namakecamatan;
        }
        
        return $result;
	}
	
	function getKecamatanList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('kecamatan');
		$this->db->order_by('namakecamatan','ASC');
		$array_keys_values = $this->db->get();
		foreach ($array_keys_values->result() as $row)
		{
			$result[0]= '-Pilih Kecamatan-';
			$result[$row->idkecamatan]= $row->namakecamatan;
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
	
/* 	function update($noanggota, $anggota)
	{
		$this->db->where('noanggota', $noanggota);
		$this->db->update($this->table, $anggota);
	} */
	
	
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */