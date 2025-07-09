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
    					load('ajuanpinjaman/tampilkan_subkategori/'+selected_kategori,'#subkategori');
					}
		</script>
<fieldset>
<legend>Pengajuan Pinjaman</legend>	
<form name="ajuanpinjaman_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="noanggota">No Anggota :</label>
        <input type="text" name="noanggota" size="6" class="form_field" value="<?php echo set_value('noanggota'); ?>"/>
	</p>
	<?php echo form_error('id_kelas', '<p class="field_error">', '</p>');?>
	
	<p>
		<input type="submit" name="submit" id="submit" value=" O K " />
	</p>
</form>
</fieldset>


<div align="center">
  <?php echo ! empty($hak) ? $hak : ''; ?>
</div>
<p><?php echo ! empty($noanggota) ? 'No Anggota   : ' . $noanggota . '<br />' : ''; ?>
  <?php echo ! empty($nama) ? 'Nama Anggota   : ' . $nama . '<br />' : ''; ?>
  <?php echo ! empty($alamat) ? 'Alamat : ' . $alamat . '<br />' : ''; ?>
  <?php echo ! empty($desa) ? 'Desa : ' . $desa . '<br />' : ''; ?>
  <?php echo ! empty($kec) ? 'Kecamatan  : ' . $kec . '<br />' : ''; ?> 
  <?php echo '<br/>';?>
	<?php echo $this->fungsi->create_combobox('kategori',$kategori,'id','nama','onchange="tampilkan_subkategoris()"');?> 
   <?php echo ! empty($maxpinjam) ? 'Pinjaman Maksimal : ' . $maxpinjam . '<br />' : ''; ?>
  <?php echo ! empty($maxwaktu) ? 'Lama Pinjaman Maksimal  : ' . $maxwaktu . '<br />' : ''; ?>    
    <?php echo '<br/>';?>
  <?php echo ! empty($ajuan) ? '<form name="transaksi_form" method="post" action="' . $ajuan . '"><input type="submit" name="submit" id="submit" value="Ajukan Pinjaman" />' : ''; ?>
  
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
  </p>
</p>
