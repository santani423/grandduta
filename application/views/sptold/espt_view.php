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
</head>
<body>
<form name="bernpwp_form" method="post" enctype="multipart/form-data" action="<?php echo $form_action; ?>">
	<p>
		<label for="nobps">No BPS :</label>
		<input type="text" class="form_field" name="nobps1" size="4" value="BPS-" />
		<input type="text" class="form_field" name="counterbps" size="4" value="<?php echo set_value('counterbps', isset($default['counterbps']) ? $default['counterbps'] : ''); ?>" />
		<input type="text" class="form_field" name="nobps2" size="20" value="/WPJ/32/PPK.05/" />
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
		<input type="text" id="npwp" class="form_field" name="npwp" size="15" value="<?php echo set_value('npwp', isset($default['npwp']) ? $default['npwp'] : ''); ?>" />
	</p>
	<?php echo form_error('npwp', '<p class="field_error">', '</p>');?> 
	
	<p>
	  <label for="idjenisdokumen">Jenis SPT :</label>
    	<?php
    		echo form_dropdown("idjenisdokumen",$option_jenisspt, isset($default['idjenisdokumen']) ? $default['idjenisdokumen'] : '',"id='idjenisdokumen'");
    	?>
	</p>
		<?php echo form_error('idjenisdokumen', '<p class="field_error">', '</p>');?>
	
	<p>
	  <label for="idjenispajak">Jenis Pajak :</label>
		<div id="dokumendd">
			<?php
    			echo form_dropdown("idjenispajak",$option_jenispajak,isset($default['idjenispajak']) ? $default['idjenispajak'] : ''); 		?>
	  	</div>	
	</p>
		<?php echo form_error('idjenispajak', '<p class="field_error">', '</p>');?>
	
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
		<?php echo form_error('restkompen', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="tglbayar">Tanggal Bayar :</label>
		<input type="text" id="datepicker2" class="form_field" name="tglbayar" size="14" value="" />
	</p>
	<?php echo form_error('tglbayar', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="uploadcsv">Upload File CSV :</label>
		<?php
			echo form_open_multipart('$form_action');
    		echo form_upload('dokumen');
    	?>
	</p>
	<?php echo form_error('uploadcsv', '<p class="field_error">', '</p>');?>   
	
		<input type="submit" name="submit" id="submit" value=" Simpan " />
	</p>
</form>
    <script type="text/javascript">          
    $('#idjenisdokumen').change(function(){
	    		var selectValues = $("#idjenisdokumen").val();
	    		if (selectValues == 0){
	    			var msg = "<select name=\"idjenispajak\" disabled><option value=\"Pilih Jenis Pajak\">Pilih Jenis Dokumen Dulu</option></select>";
	    			$('#dokumendd').html(msg);
	    		}else{
	    			var idjenisdokumen = {idjenisdokumen:$("#idjenisdokumen").val()};
	    			$('#idjenispajak').attr("disabled",true);
	    			$.ajax({
							type: "POST",
							url : "<?php echo site_url('espt/select_jenispajak')?>",
							data: idjenisdokumen,
							success: function(msg){
								$('#dokumendd').html(msg);
							}
				  	});
	    		}
	    });
    
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
    
    function coba(){
    	var npwp = {npwp:$("#npwp").val()};
    	$.ajax({
        type : "POST",
        url : "<?php echo site_url('espt/get_npwp')?>",
        data: npwp,
        success : function(msg) {
			$('#npwpadd').html(msg);
		}
    });
	
}
    </script>  
</body>
