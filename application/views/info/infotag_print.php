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
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
</head>
<br>

<div id="stylized" class="myform">
<form id="form" name="transaksi_form" method="post" action="<?php echo $form_action; ?>" target="_blank">
<label>ID IPKL : 
</label>
<input type="text" name="idipkl" class="inputnosl" value="<?php echo set_value('idipkl',isset($default['idipkl']) ? $default['idipkl'] : ''); ?>" size="12" READONLY />
<br>
<label>Nama Pelanggan : 
</label>
<input type="text" name="nama" class="inputnama" value="<?php echo set_value('nama',isset($default['nama']) ? $default['nama'] : ''); ?>" size="25" READONLY />
<br>
<label>Alamat Pelanggan :
</label>
<input type="text" name="alamat" class="inputalamat" value="<?php echo set_value('alamat',isset($default['alamat']) ? $default['alamat'] : ''); ?>" size="40" READONLY />
<br>
<label>Tahun Tagihan :
</label>
<input type="text" name="tahun" class="inputwil" value="<?php echo set_value('tahun',isset($default['tahun']) ? $default['tahun'] : ''); ?>" size="10" READONLY />
<br>
<label>Tanggal Proses :
</label>
<input type="text" name="tglproses" class="inputpasang" value="<?php echo set_value('tglproses',isset($default['tglproses']) ? $default['tglproses'] : ''); ?>" size="4" READONLY />
  <?php echo ! empty($pagination) ? '<p id="pagination">' . $pagination . '</p>' : ''; ?>
  <?php echo '<br />';?>
  <?php echo ! empty($table) ? $table : ''; ?>
  <?php echo '<br />';?>
  <?php
if ( ! empty($link))
{
	echo '<p id="bottom_link">';
	foreach($link as $links)
	{
		echo $links . ' ';
	}
	echo '</p>';
}
?>
<button type="submit">Cetak Info</button>
</form>
</div>

