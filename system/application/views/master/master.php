<?php
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
<ul>
  <li>Data Anggota  </li>
  <li>Data Desa</li>
  <li>Data Kecamatan</li>
  <li>Laporan Anggota Keseluruhan</li>
  <li>Laporan Anggota Per Desa</li>
</ul>
<p>&nbsp;</p>
