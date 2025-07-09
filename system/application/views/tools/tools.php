<?php
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>

<fieldset>
<legend>Tagihan Rekening Air   </legend>	
<form name="transaksi_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="nosl">No SL :</label>
        <input type="text" name="nosl" size="14" class="form_field" value="<?php echo set_value('nosl'); ?>"/>
	</p>
	<?php echo form_error('id_kelas', '<p class="field_error">', '</p>');?>
	
	<p>
		<input type="submit" name="submit" id="submit" value=" O K " />
	</p>
</form>
</fieldset>
<p><?php echo ! empty($nosl) ? 'No Sambung   : ' . $nosl . '<br />' : ''; ?>
  <?php echo ! empty($nama) ? 'Nama   : ' . $nama . '<br />' : ''; ?>
  <?php echo ! empty($alamat) ? 'Alamat : ' . $alamat . '<br />' : ''; ?>
  <?php echo ! empty($wilayah) ? 'Wilayah: ' . $wilayah . '<br />' : ''; ?>
  <?php echo ! empty($tarif) ? 'Tarif  : ' . $tarif . '<br />' : ''; ?> 
  <?php echo '<br />';?>
  <?php echo ! empty($table) ? $table : ''; ?>
  <?php echo '<br />';?>
  <?php echo ! empty($grandtotal) ? 'Total Tagihan : ' . $grandtotal . '<br />' : ''; ?>
  <?php echo ! empty($bayar) ? '<form name="transaksi_form" method="post" action="' . $bayar . '"><input type="submit" name="submit" id="submit" value="Bayar" />' : ''; ?> 
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
  </p>
</p>
  