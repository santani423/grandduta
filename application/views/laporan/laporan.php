<html>
<?php	
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>	
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link type="text/css" href="http://localhost/webbilling/css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="http://localhost/webbilling/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="http://localhost/webbilling/js/jquery-ui-1.8.4.custom.min.js"></script>
		<script type="text/javascript">
						$(function() {
						$('#datepicker').datepicker({
							changeMonth: true,
							changeYear: true
							});
						});
						$(function() {
							$("button, input:submit, a", ".demo").button();		
							$("a", ".demo").click(function() { return false; });
						});
		</script>
	</head>
<body>		
<fieldset>
<legend>Isikan Tanggal Penagihan </legend>	
<form name="laporan_form" method="post" action="<?php echo $form_action; ?>">
	<p>
		<label for="nama">Tanggal Penagihan :</label>
		  <input id="datepicker" type="text" name="tanggal" size="14" class="form_field" value="<?php echo set_value('tanggal'); ?>"/>
	</p>
	<?php echo form_error('id_kelas', '<p class="field_error">', '</p>');?>
	<p>
		<input type="submit" name="submit" id="submit" value=" O K " />
	</p>
</form>
</fieldset>
<?php echo ! empty($table) ? $table : ''; ?>
  <?php echo '<br />';?>
  <?php echo ! empty($grandtotal) ? 'Total Penagihan : ' . $grandtotal . '<br />' : ''; ?>
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
</body>
</html>
