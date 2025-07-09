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
?>
<form name="carisurat_form" method="post" action="<?php echo $form_action; ?>">

		
	<p>	
		<label for="nobps"><?php echo form_checkbox('nobps_check', '1', FALSE);?>No BPS :</label>
		<input type="text" class="form_field" name="nobps1" size="4" value="BPS-" />
		<input type="text" class="form_field" name="nobpscounter" size="4" value="<?php echo set_value('noanggota', isset($default['noanggota']) ? $default['noanggota'] : ''); ?>" />
		<input type="text" class="form_field" name="nobps2" size="20" value="/WPJ/32/PPK.05/2012" />
		
	</p>
	
	
	<?php echo form_error('nobps', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="npwp"><?php echo form_checkbox('npwp_check', '3', FALSE);?>NPWP :</label>
		<input type="text" class="form_field" name="npwp" size="15" value="<?php echo set_value('npwp', isset($default['npwp']) ? $default['npwp'] : ''); ?>" />
	</p>
	<?php echo form_error('npwp', '<p class="field_error">', '</p>');?> 
	
		<p>
		<label for="namawp"><?php echo form_checkbox('namawp_check', '5', FALSE);?>Nama Wajib Pajak :</label>
		<input type="text" class="form_field" name="namawp" size="30" value="<?php echo set_value('namawp', isset($default['namawp']) ? $default['namawp'] : ''); ?>" />
	</p>
	<?php echo form_error('namawp', '<p class="field_error">', '</p>');?> 
	
		<p>
		<label for="alamatwp"><?php echo form_checkbox('alamatwp_check', '7', FALSE);?>Alamat :</label>
		<input type="text" class="form_field" name="alamatwp" size="60" value="<?php echo set_value('alamatwp', isset($default['alamatwp']) ? $default['alamatwp'] : ''); ?>" />
	</p>
	<?php echo form_error('alamatwp', '<p class="field_error">', '</p>');?> 
	
	<p>
	  <label for="tglterima"><?php echo form_checkbox('tglterima_check', '9', FALSE);?>Tanggal Terima :</label>
    	<?php
    		echo buildDayDropdown("cbotgl");
    	?>
	</p>
		<?php echo form_error('tglterima', '<p class="field_error">', '</p>');?>
	
	<p>
	  <label for="bulanterima"><?php echo form_checkbox('bulanterima_check', '11', FALSE);?>Bulan Terima :</label>
    	<?php
    		echo buildMonthDropdown("cbobulan");
    	?>
	</p>
		<?php echo form_error('bulanterima', '<p class="field_error">', '</p>');?>
		
	<p>
	  <label for="tahunterima"><?php echo form_checkbox('tahunterima_check', '13', FALSE);?>Tahun Terima :</label>
    	<?php
    		echo buildYearDropdown("cbotahun");
    	?>
	</p>
		<?php echo form_error('tahunterima', '<p class="field_error">', '</p>');?>
		
	<p>
		<label for="perihal"><?php echo form_checkbox('perihal_check', '15', FALSE);?>Perihal :</label>
		<input type="text" class="form_field" name="perihal" size="50" value="" />
	</p>
	<?php echo form_error('perihal', '<p class="field_error">', '</p>');?> 
	
	<p>
		<label for="bernpwp"><?php echo form_checkbox('bernpwp_check', '17', FALSE);?>Ber NPWP :</label>
		<?php echo form_radio('bernpwp_radio', '0', FALSE);?>Ber NPWP
		<?php echo form_radio('bernpwp_radio', '1', FALSE);?>Tidak Ber NPWP
	</p>
	<?php echo form_error('npwp', '<p class="field_error">', '</p>');?> 


		<input type="submit" name="submit" id="submit" value=" Cari " />
	</p>
</form>
</body>
