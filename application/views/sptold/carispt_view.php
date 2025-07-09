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
<form name="carispt_form" method="post" action="<?php echo $form_action; ?>">

	<p>
		<label for="tglterima">Tanggal Terima Surat :</label>
		<input type="text" id="datepicker" class="form_field" name="tglterima" size="14" value="" />
	</p>
	<?php echo form_error('tglterima', '<p class="field_error">', '</p>');?>  
		
	<p>
		<label for="jenisspt">Jenis SPT :</label>
		<? echo form_checkbox('semua_check','1');?> Semua
		<? echo form_checkbox('masa_check','3');?> Masa
		<? echo form_checkbox('tahunan_check','5');?> Tahunan
		<? echo form_checkbox('ppn_check','7');?> PPN
	</p>
	
	<p>
		<input type="submit" name="submit" id="submit" value=" Cari " />
	</p>
</form>
</body>
