<?php
/**
 * Rekap_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Simpanpinjam_model extends Model {
	/**
	 * Constructor
	 */
	function Simpanpinjam_model()
	{
		parent::Model();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'klasifikasipinjaman';
	
	/**
	 * Proses rekap data absensi dengan kriteria semester dan kelas tertentu
	 */
	function get_pinjaman()
	{
		$this->db->order_by('idklaspinjam');
		return $this->db->get($this->table);
	}
	
	function get_pinjaman_by_id($idklaspinjam)
	{
		return $this->db->get_where($this->table, array('idklaspinjam' => $idklaspinjam))->row();
	}
	
	function count_pinjaman()
	{
		return $this->db->count_all($this->table);
	}
	
	function addpinjaman($klaspinjam)
	{
		$this->db->insert($this->table, $klaspinjam);
	}
	
	function updatepinjaman($idklaspinjam, $klaspinjam)
	{
		$this->db->where('idklaspinjam', $idklaspinjam);
		$this->db->update($this->table, $klaspinjam);
	}
	
	function deletepinjaman($idklaspinjam)
	{
		$this->db->delete($this->table, array('idklaspinjam' => $idklaspinjam));
	}
	
	function get_anggota_boleh_pinjam($noanggota)
	{
		return $this->db->get_where('pinjaman', array('noanggota' => $noanggota));
	}
	
	function get_klasifikasi_pinjam($noanggota)
	{
		return $this->db->get_where('pinjaman', array('noanggota' => $noanggota));
	}
	
	function get_anggota_by_id($noanggota)
	{
		$sql = "select vw_anggota_all.NoAnggota AS NoAnggota,vw_anggota_all.Nama AS Nama,vw_anggota_all.Alamat AS Alamat,vw_anggota_all.id AS KodeDesa,vw_anggota_all.JenisKelamin AS JenisKelamin,vw_anggota_all.kdagama AS kdagama,vw_anggota_all.TglMasuk AS TglMasuk,vw_anggota_all.StatusAnggota AS StatusAnggota,vw_anggota_all.NamaDesa AS NamaDesa,vw_anggota_all.NamaJK AS NamaJK,vw_anggota_all.NamaKec AS NamaKec,vw_anggota_all.agama AS deskripsi from vw_anggota_all WHERE NoAnggota = '$noanggota'" ;
		
		return $this->db->query($sql);
	}
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */