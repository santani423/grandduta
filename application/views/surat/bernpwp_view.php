<?php 
//	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	//echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

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
<form name="bernpwp_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="nobps">No BPS :</label>
		<input type="text" class="form_field" name="nobps1" size="4" value="BPS-" />
		<input type="text" class="form_field" name="counterbps" size="4" value="<?php echo set_value('counterbps', isset($default['counterbps']) ? $default['counterbps'] : ''); ?>" />
		<input type="text" class="form_field" name="nobps2" size="16" value="/WPJ/32/PPK.05/" />
		<input type="text" class="form_field" name="nobpstahun" size="3" value="<?php echo date("Y") ;?>" />
		
	</p>
	
	
	<?php echo form_error('nobps', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="npwp">NPWP :</label>
		<input type="text" class="form_field" name="npwp" size="20" value="<?php echo set_value('npwp', isset($default['npwp']) ? $default['npwp'] : ''); ?>" />
	</p>
	<?php echo form_error('npwp', '<p class="field_error">', '</p>');?> 
	
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
