<?php
/**
 * Rekap_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Info_model extends Model {
	/**
	 * Constructor
	 */
	function Info_model()
	{
		parent::Model();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'pelanggan';
	
	/**
	 * Proses rekap data absensi dengan kriteria semester dan kelas tertentu
	 */
	function get_info($namalkp)
	{
		$sql = "SELECT
				PelNoSambungan,
				PelNamaPelanggan,
				PelAlamatPelanggan
				FROM
				pelanggan
				WHERE
				pelnamapelanggan like '$namalkp'
				";
			
		return $this->db->query($sql);
	}
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */