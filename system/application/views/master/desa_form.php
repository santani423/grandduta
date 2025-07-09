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
					function tampilkan_subkategori()
					{
    					var selected_kategori = $('select[name=kategori]').val();
    					load('anggota/tampilkan_subkategori/'+selected_kategori,'#subkategori');
					}
		</script>

<form name="anggota_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="iddesa">Kode Kecamatan :</label>
		<input name="iddesa" type="text" class="form_field" id="iddesa" value="<?php echo set_value('iddesa', isset($default['iddesa']) ? $default['iddesa'] : ''); ?>" size="6" />
	</p>
	<p><?php echo form_error('iddesa', '<p class="field_error">', '</p>');?>  </p>
	<p>
	  <label for="id_kec">Kecamatan :</label>
      <?php echo $this->fungsi->create_combobox('kategori',$kategori,'id','nama','onchange="tampilkan_subkategori()"');?> </p>
    <p><?php echo form_error('kategori', '<p class="field_error">', '</p>');?></p>
	<p>
		<label for="namakec">Nama Desa :</label>
		<input name="namadesa" type="text" class="form_field" id="namadesa" value="<?php echo set_value('namadesa', isset($default['namadesa']) ? $default['namadesa'] : ''); ?>" size="30" />
	</p>
	<p><?php echo form_error('nama', '<p class="field_error">', '</p>');?><label for="alamat"></label>
  </p>
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