<?php
/**
 * Rekap_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Rekap_model extends Model {
	/**
	 * Constructor
	 */
	function Rekap_model()
	{
		parent::Model();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'absen';
	
	/**
	 * Proses rekap data absensi dengan kriteria semester dan kelas tertentu
	 */
	function get_rekap($id_semester, $id_kelas)
	{
		$sql = "SELECT siswa.nis, siswa.nama,

				/* ----------- jumlah sakit ------------*/
				(SELECT COUNT(absen.absen)
				FROM absen
				WHERE absen.absen = 'S'
				AND absen.id_semester = '$id_semester'
				AND absen.nis = siswa.nis
				AND absen.nis IN (SELECT siswa.nis
								  FROM siswa
								  WHERE siswa.id_kelas = '$id_kelas'
								  ORDER BY siswa.nis ASC)
				GROUP BY absen.nis
				ORDER BY absen.nis ASC) AS Sakit,

				/* ----------- jumlah ijin ------------*/
				(SELECT COUNT(absen.absen)
				FROM absen
				WHERE absen.absen = 'I'
				AND absen.id_semester = '$id_semester'
				AND absen.nis = siswa.nis
				AND absen.nis IN (SELECT siswa.nis
								  FROM siswa
								  WHERE siswa.id_kelas = '$id_kelas'
								  ORDER BY siswa.nis ASC)
				GROUP BY absen.nis
				ORDER BY absen.nis ASC) AS Ijin,

				/* ----------- jumlah alpa ------------*/
				(SELECT COUNT(absen.absen)
				FROM absen
				WHERE absen.absen = 'A'
				AND absen.id_semester = '$id_semester'
				AND absen.nis = siswa.nis
				AND absen.nis IN (SELECT siswa.nis
								  FROM siswa
								  WHERE siswa.id_kelas = '$id_kelas'
								  ORDER BY siswa.nis ASC)
				GROUP BY absen.nis
				ORDER BY absen.nis ASC) AS Alpa,

				/* ----------- jumlah telat ------------*/
				(SELECT COUNT(absen.absen)
				FROM absen
				WHERE absen.absen = 'T'
				AND absen.id_semester = '$id_semester'
				AND absen.nis = siswa.nis
				AND absen.nis IN (SELECT siswa.nis
								  FROM siswa
								  WHERE siswa.id_kelas = '$id_kelas'
								  ORDER BY siswa.nis ASC)
				GROUP BY absen.nis
				ORDER BY absen.nis ASC) AS Telat

			FROM siswa
			WHERE siswa.id_kelas = '$id_kelas'
			GROUP BY siswa.nis
			ORDER BY siswa.nis ASC;";
			
		return $this->db->query($sql);
	}
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */