<?php 
//	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
<head>
	    <link rel="stylesheet" href="http://localhost/tpt/asset/jquery/jquery-ui.css" type="text/css" media="all" />
		<link rel="stylesheet" href="http://localhost/tpt/asset/jquery/ui.theme.css" type="text/	css" media="all" />
		<script src="http://localhost/tpt/asset/jquery/jquery.min.js" type="text/javascript"></script>
		<script src="http://localhost/tpt/asset/jquery/jquery-ui.min.js" type="text/javascript"></script>
	
    <script type="text/javascript">  
    $(function() {  
        $('#datepicker').datepicker({  
              changeMonth: true,  
              changeYear: true,
              dateFormat: "yy-mm-dd"
            });  
    });  
    
    $(function() {  
        $('#datepicker2').datepicker({  
              changeMonth: true,  
              changeYear: true,
              dateFormat: "yy-mm-dd"  
            });  
    }); 
    </script>  
</head>
<body>
<form name="tidakbernpwp_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="nobps">No BPS :</label>
		<input type="text" class="form_field" name="nobps1" size="4" value="BPS-" />
		<input type="text" class="form_field" name="counterbps" size="4" value="<?php echo set_value('counterbps', isset($default['counterbps']) ? $default['counterbps'] : ''); ?>" />
		<input type="text" class="form_field" name="nobps2" size="16" value="/WPJ/32/PPK.05/" />
		<input type="text" class="form_field" name="nobpstahun" size="3" value="<?php echo date("Y") ;?>" />
		
	</p>
	
	
	<?php echo form_error('nobps', '<p class="field_error">', '</p>');?>
	
		<p>
		<label for="mnpwp">MNPWP :</label>
		<input type="text" class="form_field" name="mnpwp" size="20" value="000000000-529.000" />
		<input type="text" class="form_field" name="mnpwpcounter" size="4" value="<?php echo set_value('mnpwpcounter', isset($default['mnpwpcounter']) ? $default['mnpwpcounter'] : ''); ?>" />
	</p>
	<?php echo form_error('mnpwp', '<p class="field_error">', '</p>');?> 
	
	<p>
		<label for="npwp">NPWP :</label>
		<input type="text" class="form_field" name="npwp" size="12" value="000000000" />
	</p>
	<?php echo form_error('npwp', '<p class="field_error">', '</p>');?> 
	
		<p>
		<label for="kpp">KPP :</label>
		<input type="text" class="form_field" name="kpp" size="4" value="-529" />
	</p>
	<?php echo form_error('kpp', '<p class="field_error">', '</p>');?> 
	
		<p>
		<label for="cabang">Cabang :</label>
		<input type="text" class="form_field" name="cabang" size="4" value=".000" />
	</p>
	<?php echo form_error('cabang', '<p class="field_error">', '</p>');?> 
	
		<p>
		<label for="namawp">Nama Wajib Pajak :</label>
		<input type="text" class="form_field" name="namawp" size="30" value="<?php echo set_value('namawp', isset($default['namawp']) ? $default['namawp'] : ''); ?>" />
	</p>
	<?php echo form_error('namawp', '<p class="field_error">', '</p>');?> 
	
		<p>
		<label for="alamatwp">Alamat :</label>
		<input type="text" class="form_field" name="alamatwp" size="60" value="<?php echo set_value('alamatwp', isset($default['alamatwp']) ? $default['alamatwp'] : ''); ?>" />
	</p>
	<?php echo form_error('alamatwp', '<p class="field_error">', '</p>');?> 
	
		<p>
		<label for="kelurahan">Kelurahan :</label>
		<input type="text" class="form_field" name="kelurahan" size="30" value="<?php echo set_value('kelurahan', isset($default['kelurahan']) ? $default['kelurahan'] : ''); ?>" />
	</p>
	<?php echo form_error('kelurahan', '<p class="field_error">', '</p>');?> 
	
		<p>
		<label for="kecamatan">Kecamatan :</label>
		<input type="text" class="form_field" name="kecamatan" size="30" value="<?php echo set_value('kecamatan', isset($default['kecamatan']) ? $default['kecamatan'] : ''); ?>" />
	</p>
	<?php echo form_error('kecamatan', '<p class="field_error">', '</p>');?> 
	
		<p>
		<label for="kota">Kota :</label>
		<input type="text" class="form_field" name="kota" size="30" value="<?php echo set_value('kota', isset($default['kota']) ? $default['kota'] : ''); ?>" />
	</p>
	<?php echo form_error('kota', '<p class="field_error">', '</p>');?> 
	
	<p>
	  <label for="stskirim">Status Pengiriman :</label>
    	<?php
    		echo form_dropdown("stskirim",$option_stskirim, isset($default['stskirim']) ? $default['stskirim'] : '',"id='stskirim'");
    	?>
	</p>
		<?php echo form_error('stskirim', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="tglterima">Tanggal Terima :</label>
		<input type="text" id="datepicker" class="form_field" name="tglterima" size="14" value="" />
	</p>
	<?php echo form_error('tglterima', '<p class="field_error">', '</p>');?>  
	
	<p>
		<label for="perihal">Perihal :</label>
		<input type="text" class="form_field" name="perihal" size="50" value="" />
	</p>
	<?php echo form_error('perihal', '<p class="field_error">', '</p>');?> 

	<p>
		<label for="tglsurat">Tanggal Surat :</label>
		<input type="text" id="datepicker2" class="form_field" name="tglsurat" size="14" value="" />
	</p>
	<?php echo form_error('tglsurat', '<p class="field_error">', '</p>');?>  
	
		<input type="submit" name="submit" id="submit" value=" Simpan " />
	</p>
</form>
</body>
