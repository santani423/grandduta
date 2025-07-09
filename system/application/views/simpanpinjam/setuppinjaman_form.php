<?php 
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
<head>
</head>
<body>
<form name="setuppinjaman_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="idklaspinjam">ID Klas Pinjaman :</label>
		<input name="idklaspinjam" type="text" class="form_field" id="idklaspinjam" value="<?php echo set_value('idklaspinjam', isset($default['idklaspinjam']) ? $default['idklaspinjam'] : ''); ?>" size="4" />
	</p>
	<?php echo form_error('idklaspinjam', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="jenispinjaman">Jenis Pinjaman :</label>
		<input name="jenispinjaman" type="text" class="form_field" id="jenispinjaman" value="<?php echo set_value('jenispinjaman', isset($default['jenispinjaman']) ? $default['jenispinjaman'] : ''); ?>" size="30" />
	</p>
	<p><?php echo form_error('jenispinjaman', '<p class="field_error">', '</p>');?>  </p>
	<p>
	  <label for="maxpinjaman">Maksimal Pinjaman :</label>
      <input name="maxpinjaman" type="text" class="form_field" id="maxpinjaman" value="<?php echo set_value('maxpinjaman', isset($default['maxpinjaman']) ? $default['maxpinjaman'] : ''); ?>" size="20" />
</p>
	<p><?php echo form_error('maxpinjaman', '<p class="field_error">', '</p>');?>
	  <label for="id_kec"></label>
	</p>
<p>
	  <label for="jangkawaktu">Jangka Waktu :</label>
      <input id="datepicker" type="text" class="form_field" name="jangkawaktu" size="5" value="<?php echo set_value('jangkawaktu', isset($default['jangkawaktu']) ? $default['jangkawaktu'] : ''); ?>" /> 
      Bulan
</p>
	<?php echo form_error('jangkawaktu', '<p class="field_error">', '</p>');?>
    <label for="tanggal"></label>
<p>
    <label for="id_status"></label>
    <label for="jangkawaktu">Jasa Kredit :</label>
    <input id="datepicker2" type="text" class="form_field" name="jasakredit" size="5" value="<?php echo set_value('jasakredit', isset($default['jasakredit']) ? $default['jasakredit'] : ''); ?>" /> 
    %
</p>
<?php echo form_error('jasakredit', '<p class="field_error">', '</p>');?>
<label for="tanggal"></label>
<p>
		<input type="submit" name="submit" id="submit" value=" Simpan " />
	</p>
</form>
</body>
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