<?php 
//	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
	echo "<br>";
?>
<head>
	    <link rel="stylesheet" href="<?php echo base_url() . 'asset/jquery/jquery-ui.css';?>" type="text/css" media="all" />
		<link rel="stylesheet" href="<?php echo base_url() . 'asset/jquery/ui.theme.css';?>" type="text/	css" media="all" />
		<script src="<?php echo base_url() . 'asset/jquery/jquery.min.js';?>" type="text/javascript"></script>
		<script src="<?php echo base_url() . 'asset/jquery/jquery-ui.min.js'?>" type="text/javascript"></script>
		<style type="text/css">@import url("<?php echo base_url() . 'css/formulir.css'; ?>");</style>	
 </head>
<body>
<br>
<div id="stylized" class="myform">
<form name="rekapreg_form" method="post" action="<?php echo $form_action; ?>">

	  <p>
	  	  <label>Tanggal : </label>
    	<?php
    		echo buildDayDropdown("cbotgl");
    	?>
    	<?php
    		echo buildMonthDropdown("cbobulan");
    	?>
    	<?php
    		echo buildYearDropdown("cbotahun");
    	?>	  
	  </p>
		<div class="spacer"></div>
		<button type="submit">Cetak Data</button>
		<div class="spacer"></div>
</form>
</div>
</body>