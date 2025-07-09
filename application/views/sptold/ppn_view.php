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
<form name="ppn_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="nobps">No BPS :</label>
		<input type="text" class="form_field" name="nobps1" size="4" value="BPS-" />
		<input type="text" class="form_field" name="counterbps" size="4" value="<?php echo set_value('counterbps', isset($default['counterbps']) ? $default['counterbps'] : ''); ?>" />
		<input type="text" class="form_field" name="nobps2" size="16" value="/WPJ/32/PPK.05/" />
		<input type="text" class="form_field" name="nobpstahun" size="3" value="<?php echo date("Y") ;?>" />
	</p>
	<?php echo form_error('nobps', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="tglterima">Tanggal Terima :</label>
		<input type="text" id="datepicker" class="form_field" name="tglterima" size="14" value="" />
	</p>
	<?php echo form_error('tglterima', '<p class="field_error">', '</p>');?>  
		
	<p>
		<label for="npwp">NPWP :</label>
		<input type="text" class="form_field" name="npwp" size="15" value="<?php echo set_value('npwp', isset($default['npwp']) ? $default['npwp'] : ''); ?>" />
	</p>
	<?php echo form_error('npwp', '<p class="field_error">', '</p>');?> 
	
	<p>
	  <label for="jenisspt">Jenis SPT :</label>
    	<?php
    		echo form_dropdown("jenisspt",$option_jenisspt, isset($default['jenisspt']) ? $default['jenisspt'] : '',"id='jenisspt'");
    	?>
	</p>
		<?php echo form_error('jenisspt', '<p class="field_error">', '</p>');?>
	
	<p>
	  <label for="jenispajak">Jenis Pajak :</label>
    	<?php
    		echo form_dropdown("jenispajak",$option_jenispajak, isset($default['jenispajak']) ? $default['jenispajak'] : '',"id='jenispajak'");
    	?>
	</p>
		<?php echo form_error('jenispajak', '<p class="field_error">', '</p>');?>
	
	<p>
	  <label for="masatahunpajak">Masa Tahun Pajak :</label>
    	<?php
    		echo buildMonthDropdown("cbobulan");
    	?>
		<?php
    		echo buildYearDropdown("cbotahun");
    	?>
 	</p>
		<?php echo form_error('masatahunpajak', '<p class="field_error">', '</p>');?>
		
	<p>
	  <label for="stsbetul">Status Pembetulan :</label>
    	<?php
    		echo form_dropdown("stsbetul",$option_stspembetulan, isset($default['stsbetul']) ? $default['stsbetul'] : '',"id='stsbetul'");
    	?>
	</p>
		<?php echo form_error('stsbetul', '<p class="field_error">', '</p>');?>
	
	<p>
	  <label for="stskirim">Status Pengiriman :</label>
    	<?php
    		echo form_dropdown("stskirim",$option_stskirim, isset($default['stskirim']) ? $default['stskirim'] : '',"id='stskirim'");
    	?>
	</p>
		<?php echo form_error('stskirim', '<p class="field_error">', '</p>');?>
	

	<p>
	  <label for="kodenkl">Kode N/K/L | Nominal :</label>
    	<?php
    		echo form_dropdown("kodenkl",$option_nkl, isset($default['kodenkl']) ? $default['kodenkl'] : '',"id='kodenkl'");
    	?>
    	<input type="text" class="form_field" name="nominalnkl" size="15" value="" />
	</p>
		<?php echo form_error('stskirim', '<p class="field_error">', '</p>');?>
	
	<p>
	  <label for="restkompen">Restitusi/Kompensasi | Nominal :</label>
    	<?php
    		echo form_dropdown("restkompen",$option_restkompen, isset($default['restkompen']) ? $default['restkompen'] : '',"id='restkompen'");
    	?>
    	<input type="text" class="form_field" name="nominalrestkompen" size="15" value="" />
	</p>
		<?php echo form_error('stskirim', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="noketetapan">No. Ketetapan :</label>
		<input type="text" class="form_field" name="noketetapan" size="50" value="" />
	</p>
	<?php echo form_error('noketetapan', '<p class="field_error">', '</p>');?> 

	<p>
		<label for="tglbayar">Tanggal Bayar :</label>
		<input type="text" id="datepicker2" class="form_field" name="tglbayar" size="14" value="" />
	</p>
	<?php echo form_error('tglbayar', '<p class="field_error">', '</p>');?>  
	
		<input type="submit" name="submit" id="submit" value=" Simpan " />
	</p>
</form>
</body>
