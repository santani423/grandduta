<?php
/**
 * Loket_model Class
 *
 * @author	Trias Bratakusuma <bratatkr@gmail.com>
 */
class Loket_model extends CI_Model {
	/**
	 * Constructor
	 */
	function Loket_model()
	{
		parent::__Construct();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 'tagihan';
	
	/**
	 * Proses rekap data absensi dengan kriteria semester dan kelas tertentu
	 */
	function get_tagihan($nosl)
	{
		$sql = "select T.idtagihan, P.idipkl, P.nama, P.blok, P.nokav, P.kdgol, T.tahun, T.bulan, T.tagihan, T.loket, T.tanggalbayar from tbl_tagihan T Join tbl_pelanggan P on T.idipkl = P.idipkl where T.idipkl = '$nosl'";
			
		return $this->db->query($sql);
	}
	
	function get_tagihan_allstate($nosl)
	{
		$sql = "select * from tbl_pelanggan where idipkl = '$nosl'";
			
		return $this->db->query($sql);
	}
	
	function get_pelstatus($nosl)
	{
		$sql = "select * from tbl_pelanggan where idipkl = $nosl";
			
		return $this->db->query($sql);
	}
	
	function get_transaksilunas($nosl)
	{
		$sql = "SELECT
				tagihan.TagihanTanggalBayar
				FROM
				tagihan
				WHERE
				tagihan.TagihanNoSambungan = '$nosl'
				AND
				tagihan.TagihanStatus = 'lunas'
				order by tagihan.TagihanBulanTagihan asc";
			
		return $this->db->query($sql);
	}
	
	function upd_lunas($nosl)
	{
		$sql = "UPDATE tagihan
					SET tagihanstatus = 'lunas',
					TagihanTanggalBayar = now()
					WHERE 
					TagihanNoSambungan = '$nosl'";
			
		return $this->db->query($sql);
	}
}
// END Absen_model Class

/* End of file absen_model.php */
/* Location: ./system/application/models/absen_model.php */