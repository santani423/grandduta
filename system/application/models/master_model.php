<?php
/**
 * Rekap_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Master_model extends Model {
	/**
	 * Constructor
	 */
	function Master_model()
	{
		parent::Model();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'anggota';
	
	/**
	 * Proses rekap data absensi dengan kriteria semester dan kelas tertentu
	 */
	function get_anggota_all()
	{
		$sql = "select 
		anggota.NoAnggota AS NoAnggota,
		anggota.Nama AS Nama,
		anggota.Alamat AS Alamat,
		anggota.KodeDesa AS KodeDesa,
		anggota.JenisKelamin AS JenisKelamin,
		anggota.kdagama AS kdagama,
		anggota.TglMasuk AS TglMasuk,
		anggota.StatusAnggota AS StatusAnggota,
		desa.NamaDesa AS NamaDesa,
		jk.NamaJK AS NamaJK,
		kecamatan.NamaKec AS NamaKec,
		agama.deskripsi AS deskripsi 
		from anggota join desa join jk join kecamatan join agama 
		where anggota.KodeDesa = desa.KodeDesa and desa.KodeKec = kecamatan.KodeKec and 
		anggota.JenisKelamin = jk.Kodejk and anggota.kdagama = agama.kodeagama";
			
		return $this->db->query($sql);
	}
	
	function get_anggota_by_idnama($kriteria)
	{
		$kriterianama = '%' . $kriteria . '%';
		$sql = "select vw_anggota_all.NoAnggota AS NoAnggota,vw_anggota_all.Nama AS Nama,vw_anggota_all.Alamat AS Alamat,vw_anggota_all.id AS KodeDesa,vw_anggota_all.JenisKelamin AS JenisKelamin,vw_anggota_all.kdagama AS kdagama,vw_anggota_all.TglMasuk AS TglMasuk,vw_anggota_all.StatusAnggota AS StatusAnggota,vw_anggota_all.NamaDesa AS NamaDesa,vw_anggota_all.NamaJK AS NamaJK,vw_anggota_all.NamaKec AS NamaKec,vw_anggota_all.agama AS deskripsi from vw_anggota_all WHERE NoAnggota = '$kriteria' OR Nama LIKE '$kriterianama'" ;
		
		return $this->db->query($sql);
	}
	
	function get_all($limit, $offset)
	{
		$this->db->select('vw_anggota_all.NoAnggota AS NoAnggota,vw_anggota_all.Nama AS Nama,vw_anggota_all.Alamat AS Alamat,vw_anggota_all.id AS KodeDesa,vw_anggota_all.JenisKelamin AS JenisKelamin,vw_anggota_all.kdagama AS kdagama,vw_anggota_all.TglMasuk AS TglMasuk,vw_anggota_all.StatusAnggota AS StatusAnggota,vw_anggota_all.NamaDesa AS NamaDesa,vw_anggota_all.NamaJK AS NamaJK,vw_anggota_all.NamaKec AS NamaKec,vw_anggota_all.agama AS deskripsi ');
		$this->db->from('vw_anggota_all');
		$this->db->limit($limit, $offset);
		$this->db->order_by('NoAnggota', 'asc');
		return $this->db->get()->result();
	}
	
	function count_all()
	{
		return $this->db->count_all('vw_anggota_all');
	}
	
	function delete($noanggota)
	{
		$this->db->delete($this->table, array('noanggota' => $noanggota));
	}
	
	function get_jk()
	{
		$this->db->order_by('Kodejk');
		return $this->db->get('jk');
	}
	
	function get_sts()
	{
		$this->db->order_by('kodestatus');
		return $this->db->get('status');
	}
	
	function get_agama()
	{
		$this->db->order_by('kodeagama');
		return $this->db->get('agama');
	}
	
	function get_kec()
	{
		$this->db->order_by('id');
		return $this->db->get('kecamatan');
	}
	
	function get_kecamatan_by_id($idkec)
	{
		return $this->db->get_where('kecamatan', array('id' => $idkec))->row();
	}
	
	function count_kec()
	{
		return $this->db->count_all('kecamatan');
	}
	
	function addkec($kecamatan)
	{
		$this->db->insert('kecamatan', $kecamatan);
	}
	
	function updatekec($idkec, $kecamatan)
	{
		$this->db->where('id', $idkec);
		$this->db->update('kecamatan', $kecamatan);
	}
	
	function deletekec($idkec)
	{
		$this->db->delete('kecamatan', array('id' => $idkec));
	}
	
	function get_desa()
	{
		$this->db->order_by('id');
		return $this->db->get('desa');		
	}
	
	function get_desa_all()
	{
		$this->db->order_by('kecamatan');
		return $this->db->get('vw_desa_all');		
	}
	
	function get_desa_by_idkec($idkec)
	{
		return $this->db->get_where('desa', array('kodekec' => $idkec))->row();
	}
	
	function count_desa()
	{
		return $this->db->count_all('desa');
	}
	
	function adddesa($desa)
	{
		$this->db->insert('desa', $desa);
	}
	
	function get_desa_by_iddesa($iddesa)
	{
		return $this->db->get_where('desa', array('id' => $iddesa))->row();
	}
	
	function updatedesa($iddesa, $desa)
	{
		$this->db->where('id', $iddesa);
		$this->db->update('desa', $desa);
	}
	
	function deletedesa($iddesa)
	{
		$this->db->delete('desa', array('id' => $iddesa));
	}
	
	function add($anggota)
	{
		$this->db->insert($this->table, $anggota);
	}
	
	function get_anggota_by_id($anggota)
	{
		return $this->db->get_where('vw_anggota_all', array('noanggota' => $anggota))->row();
	}
	
	function update($noanggota, $anggota)
	{
		$this->db->where('noanggota', $noanggota);
		$this->db->update($this->table, $anggota);
	}
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */