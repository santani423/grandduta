<?php 
//	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	//echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
<head>
</head>
<body>
<form name="bernpwp_form" method="post" action="<?php echo $form_action; ?>"  target="_blank">
	<p>
		<label for="nobps">No BPS :</label>
		<input type="text" class="form_field" name="nobps" size="18" value="<?php echo set_value('nobps', isset($default['nobps']) ? $default['nobps'] : ''); ?>" />
	</p>	
	<p>
		<label for="npwp">NPWP :</label>
		<input type="text" class="form_field" name="npwp" size="12" value="<?php echo set_value('npwp', isset($default['npwp']) ? $default['npwp'] : ''); ?>" />
	</p>
	<p>
		<label for="nama">Nama :</label>
		<input type="text" class="form_field" name="nama" size="30" value="<?php echo set_value('nama', isset($default['nama']) ? $default['nama'] : ''); ?>" />
	</p>
	<p>
		<label for="alamat">Alamat :</label>
		<input type="text" class="form_field" name="alamat" size="40" value="<?php echo set_value('alamat', isset($default['alamat']) ? $default['alamat'] : ''); ?>" />
	</p>	
	<p>
		<label for="tglterima">Tanggal Terima :</label>
		<input type="text" id="datepicker" class="form_field" name="tglterima" size="10" value="<?php echo set_value('tglterima', isset($default['tglterima']) ? $default['tglterima'] : ''); ?>" />
	</p>
	<p>
		<label for="masa">Masa :</label>
		<input type="text" class="form_field" name="masa" size="10" value="<?php echo set_value('masa', isset($default['masa']) ? $default['masa'] : ''); ?>" />
	</p>
	<p>
		<label for="tahunpajak">Tahun Pajak :</label>
		<input type="text" class="form_field" name="tahun" size="5" value="<?php echo set_value('tahun', isset($default['tahun']) ? $default['tahun'] : ''); ?>" />
	</p>
	<p>
	  <label for="stskirim">Status Pengiriman :</label>
      <input type="text" class="form_field" name="status" size="20" value="<?php echo set_value('status', isset($default['status']) ? $default['status'] : ''); ?>" />
	</p>
	<p>
	  <label for="jumlah">Jumlah :</label>
      <input type="text" class="form_field" name="jumlah" size="12" value="<?php echo set_value('jumlah', isset($default['jumlah']) ? $default['jumlah'] : ''); ?>" />
	</p>
		<input type="submit" name="submit" id="submit"  value=" Cetak ">
	</p>
</form>
</body>
