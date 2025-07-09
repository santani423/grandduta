<ul id="menu_tab">	
	<li id="tab_logout"><?php echo anchor('login/process_logout', 'Logout', array('onclick' => "return confirm('Anda yakin akan logout?')"));?></li>
	<li id="tab_absen"><?php echo anchor('tools', 'Tools');?></li>
	<li id="tab_rekap"><?php echo anchor('simpanpinjam', 'Simpan Pinjam');?></li>
   	<li id="tab_siswa"><?php echo anchor('master', 'Data Master');?></li>
  	<strong><?php echo "Username";?></strong>
</ul>
