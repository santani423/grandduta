<?php
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
	echo "<br>";
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">@import url("<?php echo base_url() . 'css/formulir.css'; ?>");</style>
</head>
<br>

<div id="stylized" class="myform">
<form id="form" name="transaksi_form" method="post" action="">
<label>ID IPKL : 
</label>
<input type="text" name="idipkl" class="inputnosl" value="<?php echo set_value('nosl',isset($default['nosl']) ? $default['nosl'] : ''); ?>" size="12" READONLY />
<br>
<label>Nama Pelanggan : 
</label>
<input type="text" name="nama" class="inputnama" value="<?php echo set_value('nama',isset($default['nama']) ? $default['nama'] : ''); ?>" size="25" READONLY />
<br>
<label>Blok :
</label>
<input type="text" name="nosl" class="inputalamat" value="<?php echo set_value('alamat',isset($default['alamat']) ? $default['alamat'] : ''); ?>" size="40" READONLY />
<br>
<label>No. Kav :
</label>
<input type="text" name="nama" class="inputwil" value="<?php echo set_value('wil',isset($default['wil']) ? $default['wil'] : ''); ?>" size="10" READONLY />
<br>
<label>No. HP :
</label>
<input type="text" name="nama" class="inputpasang" value="<?php echo set_value('tglpasang',isset($default['tglpasang']) ? $default['tglpasang'] : ''); ?>" size="8" READONLY />
<br>
<label>Email :
</label>
<input type="text" name="nama" class="inputpasang" value="<?php echo set_value('tglpasang',isset($default['tglpasang']) ? $default['tglpasang'] : ''); ?>" size="8" READONLY />
<br>
<label>Kode Golongan :
</label>
<input type="text" name="nosl" class="inputgol" value="<?php echo set_value('kdgol',isset($default['kdgol']) ? $default['kdgol'] : ''); ?>" size="4" READONLY />
<br>
<label>Status :
</label>
<input type="text" name="nama" class="inputstatus" value="<?php echo set_value('status',isset($default['status']) ? $default['status'] : ''); ?>" size="10" READONLY />
<br>
<div class="spacer"></div>
</form>
</div>
