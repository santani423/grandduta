<?php 
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
<head>
		<script src="<?php echo base_url();?>asset/javascript/jquery.js" type="text/javascript"></script>
</head>
<body>
<script language='javascript' type='text/javascript'>
					function load(page,div)
					{
    					var site = "<?php echo site_url();?>";
    					$.ajax({
        				url: site+"/"+page,
        				success: function(response){
            			$(div).html(response);
        				},
    					dataType:"html"
    					});
    					return false;
					}
					function tampilkan_subkategorin()
					{
    					var selected_kategori = $('select[name=kategori]').val();
    					load('anggota/tampilkan_subkategori/'+selected_kategori,'#subkategori');
					}
		</script>

<form name="anggota_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="noanggota">No Anggota :</label>
		<input type="text" class="form_field" name="noanggota" size="6" value="<?php echo set_value('noanggota', isset($default['noanggota']) ? $default['noanggota'] : ''); ?>" />
	</p>
	<?php echo form_error('noanggota', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="nama">Nama :</label>
		<input type="text" class="form_field" name="nama" size="30" value="<?php echo set_value('nama', isset($default['nama']) ? $default['nama'] : ''); ?>" />
	</p>
	<p><?php echo form_error('nama', '<p class="field_error">', '</p>');?>  </p>
	<p>
	  <label for="alamat">Alamat :</label>
      <input type="text" class="form_field" name="alamat" size="30" value="<?php echo set_value('alamat', isset($default['alamat']) ? $default['alamat'] : ''); ?>" />
</p>
	<p><?php echo form_error('alamat', '<p class="field_error">', '</p>');?></p>
	<p>
	  <label for="id_kec">Kecamatan :</label>
      <?php echo $this->fungsi->create_combobox('kategori',$kategori,'id','nama','onchange="tampilkan_subkategorin()"');?> </p>
	<p><?php echo form_error('id_kec', '<p class="field_error">', '</p>');?></p>
	<p>
	  <label for="id_desa">Desa :</label>
	  <span id='subkategori'></span>
    </p>
    <br>
    <p><?php echo form_error('id_desa', '<p class="field_error">', '</p>');?></p>
	  <label for="id_kec"></label>
	  <p>
	  <label for="id_jk">Jenis Kelamin:</label>
      <?php echo form_dropdown('id_jk', $options_jk, isset($default['id_jk']) ? $default['id_jk'] : ''); ?> </p>
	<p><?php echo form_error('id_jk', '<p class="field_error">', '</p>');?></p>
	<p>
	  <label for="id_agama">Agama:</label>
      <?php echo form_dropdown('id_agama', $options_agama, isset($default['id_agama']) ? $default['id_agama'] : ''); ?> </p>
	<?php echo form_error('id_agama', '<p class="field_error">', '</p>');?>
	<p>
	  <label for="tglmasuk">Tanggal Masuk :</label>
      <input id="datepicker" type="text" class="form_field" name="tglmasuk" size="10" value="<?php echo set_value('tglmasuk', isset($default['tglmasuk']) ? $default['tglmasuk'] : ''); ?>" />
</p>
	<?php echo form_error('nis', '<p class="field_error">', '</p>');?>
    <label for="tanggal"></label>
<p>
    <label for="id_status">Status Anggota :</label>
        <?php echo form_dropdown('id_status', $options_status, isset($default['id_status']) ? $default['id_status'] : ''); ?> </p>
<?php echo form_error('id_status', '<p class="field_error">', '</p>');?>
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