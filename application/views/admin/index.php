<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class='title'>Dashboard</div>
<div> Selamat Datang, <?php echo $this->session->userdata('nama');?>, <?php echo anchor('home/logout','Logout');?></div>
<br /><br />
Menu yang tersedia untuk Anda :
<ul>
	<?php
	foreach($menu->result() as $row)
	{
		echo '<li>'.anchor($row->menu_uri,$row->menu_nama).'</li>';
	}
	?>
</ul>