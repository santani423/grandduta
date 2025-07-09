<?php
/**
 * Rekap_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Laporan_model extends Model {
	/**
	 * Constructor
	 */
	function Laporan_model()
	{
		parent::Model();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'tagihan';
	
	/**
	 * Proses rekap data absensi dengan kriteria semester dan kelas tertentu
	 */
	function get_laporan($tahun,$bulan,$tanggal)
	{
		$sql = "	SELECT
					tagihan.TagihanNoSambungan,
					tagihan.TagihanNamaPelanggan,
					tagihan.TagihanAlamatPelanggan,
					tagihan.TagihanJumBayar + tagihan.TagihanPemeliharaan + tagihan.TagihanBiayaAdministrasi As Total
					FROM 	tagihan
					WHERE 	tagihan.TagihanStatus = 'lunas'
					AND	year(tagihan.TagihanTanggalBayar) = '$tahun'
					AND	month(tagihan.TagihanTanggalBayar) = '$bulan'
					AND	day(tagihan.TagihanTanggalBayar) = '$tanggal'
				";
			
		return $this->db->query($sql);
	}
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */