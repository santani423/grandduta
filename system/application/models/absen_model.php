<?php
/**
 * Absen_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Absen_model extends Model {
	/**
	 * Constructor
	 */
	function Absen_model()
	{
		parent::Model();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'absen';
	
	/**
	 * Menghitung jumlah baris dalam sebuah tabel, ada kaitannya dengan pagination
	 */
	function count_all_num_rows()
	{
		return $this->db->count_all($this->table);
	}
	
	/**
	 * Tampilkan 10 baris absen terkini, diurutkan berdasarkan tanggal (Descending)
	 */
	function get_last_ten_absen($limit, $offset)
	{
		$this->db->select('absen.id_absen, absen.tanggal, absen.nis, siswa.nama, kelas.kelas, absen.absen');
		$this->db->from('absen, siswa, kelas, semester');
		$this->db->where('siswa.id_kelas = kelas.id_kelas');
		$this->db->where('absen.nis = siswa.nis');
		$this->db->where('semester.id_semester = absen.id_semester');
		$this->db->order_by('absen.tanggal', 'desc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}
	
	/**
	 * Menghapus sebuah entry data absen
	 */
	function delete($id_absen)
	{
		$this->db->where('id_absen', $id_absen);
		$this->db->delete($this->table);
	}
	
	/**
	 * Menambahkan sebuah data ke tabel absen
	 */
	function add($absen)
	{
		$this->db->insert($this->table, $absen);
	}
	
	/**
	 * Dapatkan data absen dengan id_absen tertentu, untuk proses update
	 */
	function get_absen_by_id($id_absen)
	{
		$this->db->select('id_absen, nis, id_semester, tanggal, absen');
		$this->db->where('id_absen', $id_absen);
		return $this->db->get($this->table);
	}
	
	/**
	 * Update data absensi
	 */
	function update($id_absen, $absen)
	{
		$this->db->where('id_absen', $id_absen);
		$this->db->update($this->table, $absen);
	}
	
	/**
	 * Cek apakah ada entry data yang sama pada tanggal tertentu untuk siswa dengan NIS tertentu pula
	 */
	function valid_entry($nis, $tanggal)
	{
		$this->db->where('nis', $nis);
		$this->db->where('tanggal', $tanggal);
		$query = $this->db->get($this->table)->num_rows();
						
		if($query > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}	
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */