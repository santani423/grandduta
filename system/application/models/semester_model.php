<?php
/**
 * Semester_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Semester_model extends Model {
	/**
	 * Constructor
	 */
	function Semester_model()
	{
		parent::Model();
	}
	
	// Inisialisasi nama tabel semester
	var $table = 'semester';
	
	/**
	 * Mendapatkan semester yang aktif
	 */
	function get_active_semester()
	{
		$this->db->select('id_semester');
		$this->db->where('status', 1);
		return $this->db->get($this->table);
	}
	
	/**
	 * Mendapatkan semua data semester
	 */
	function get_semester()
	{
		$this->db->order_by('id_semester');
		return $this->db->get($this->table);
	}
	
	/**
	 * Mengaktifkan sebuah semester dan menonaktifkan lainnya, menggunakan transaksi
	 */
	function aktif($id_semester)
	{
		$sql1 = "UPDATE semester
				SET semester.status = '1'
				WHERE semester.id_semester = '$id_semester';
				";
		$sql2 = "UPDATE semester
				SET semester.status = '0'
				WHERE semester.id_semester != '$id_semester';
				";
		$this->db->trans_start();
		$this->db->query($sql1);
		$this->db->query($sql2);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === TRUE)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Menonaktifkan sebuah semester dan mengaktifkan lainnya, menggunakan transaksi
	 */
	 
	 function nonaktif($id_semester)
	{
		$sql1 = "UPDATE semester
				SET semester.status = '0'
				WHERE semester.id_semester = '$id_semester';
				";
		$sql2 = "UPDATE semester
				SET semester.status = '1'
				WHERE semester.id_semester != '$id_semester';
				";
		$this->db->trans_start();
		$this->db->query($sql1);
		$this->db->query($sql2);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === TRUE)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
}
// END Semester_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/semester_model.php */