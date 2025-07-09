<?php
/**
 * Rekap_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Tools_model extends Model {
	/**
	 * Constructor
	 */
	function Tools_model()
	{
		parent::Model();
	}
	
	// Inisialisasi nama tabel absen
	var $table = 's_user';
	
	/**
	 * Proses rekap data absensi dengan kriteria semester dan kelas tertentu
	 */
	function get_transaksi($nosl)
	{
		$sql = "SELECT
				tagihan.TagihanKDWilayah,
				tagihan.TagihanKDGolongan,
				tagihan.TagihanNamaPelanggan,
				tagihan.TagihanAlamatPelanggan,
				tagihan.TagihanBulanTagihan,
				tagihan.TagihanNoSambungan,
				tagihan.TagihanIDTagihan,
				tagihan.TagihanTahunTagihan,
				tagihan.TagihanDiameter,
				tagihan.TagihanAngkaMAwal,
				tagihan.TagihanAngkaMAkhir,
				tagihan.TagihanJumPakai,
				tagihan.TagihanJumBayar,
				tagihan.TagihanBiayaPipa,
				tagihan.TagihanBiayaMeteran,
				tagihan.TagihanPemeliharaan,
				tagihan.TagihanBiayaAdministrasi,
				tagihan.TagihanTagihanBulan,
				tagihan.TagihanTagihanTotal
				FROM
				tagihan
				WHERE
				tagihan.TagihanNoSambungan = '$nosl'
				AND
				tagihan.TagihanStatus = '0'
				order by tagihan.TagihanBulanTagihan asc";
			
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