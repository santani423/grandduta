<ul id="sidebar">
	<li id="tab_absen"><?php echo anchor($this->session->userdata('username'), $this->session->userdata('username'));?></li>	
	<li id="tab_absen"><?php echo anchor('anggota', 'Data Anggota');?></li>
	<li id="tab_rekap"><?php echo anchor('kecamatan', 'Data Kecamatan');?></li>
	<li id="tab_siswa"><?php echo anchor('desa', 'Data Desa');?></li>
	<li id="tab_siswa"><?php echo anchor('lapagtall', 'Laporan Data Anggota');?></li>
	<li id="tab_siswa"><?php echo anchor('lapagtperdesa', 'Laporan Data Anggota Per Desa');?></li>
</ul>
