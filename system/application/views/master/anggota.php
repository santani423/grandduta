<?php
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>

<fieldset>
<legend>Daftar Anggota Koperasi   </legend>	
<form name="transaksi_form" method="post" action="<?php echo $form_action; ?>">
  <table width="100%" border="0">
    <tr>
      <td width="47%" height="70"><p>Cari Anggota Koperasi Berdasarkan : </p>
        <p> Nama Atau No Anggota
          <input name="kriteria" type="text" class="form_field" id="kriteria" value="<?php echo set_value('nosl'); ?>" size="35"/>
          <input type="submit" name="submit" id="submit" value=" O K " />
        </p>
        </label>
        <div align="left"><br />
          <label></label></td>
    </tr>
  </table>
  <label> <?php echo form_error('id_kelas', '<p class="field_error">', '</p>');?><br />
  </label>
  <label for="kriteria"></label>
  
  
  <div align="center"></div>
</form>
</fieldset>
<p>
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
?></p>
