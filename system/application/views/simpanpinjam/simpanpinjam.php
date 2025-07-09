<?php
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
<ul>
  <li>Setup Data Pinjaman</li>
  <li>Pengajuan Pinjaman</li>
  <li>Cetak Rekap Ajuan</li>
  <li>Cetak Daftar Pinjaman</li>
  <li>Pemberian Pinjaman</li>
  <li>Cetak Daftar Calon Penerima Pinjaman</li>
  <li>Pencairan Pinjaman</li>
  <li>Tutup Buku Pinjaman</li>
  <li>Transaksi Bulanan</li>
  <li>Penyelesaian Pinjaman</li>
  <li>Laporan Pinjaman</li>
  <li>Laporan Simpanan Anggota</li>
  <li>Laporan Sisa Pinjaman</li>
  <li>Laporan Transaksi Harian</li>
  <li>Laporan Transaksi Bulanan</li>
</ul>
<p>&nbsp;</p>
