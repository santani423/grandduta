<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo base_url().'images/fav_icon.png';?>" />
<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/gaya.css'; ?>");</style>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">

<div id="header">
	<div id="logo">
		<h1><a href="#">Sisinfokop </a></h1>
	</div>
	<div id="menu">
		<?php $this->load->view('navigation'); ?>
	</div>
</div>
<hr />
<!-- end header -->
<!-- start page -->
<div id="wrapper">
	<div id="page">
		<!-- start content -->
		<div id="content">			
			<div class="box">
			<?php $this->load->view($main_view); ?>
			<img src="images/splitter.gif" class="splitter" alt="" />
			</div>
		</div>
		<!-- start sidebar -->
		<div id="sidebar">
		
			<ul>
				<li id="list">
					<h2>Sub Menu</h2>
					<ul>
						<?php $this->load->view($left_view); ?>
					</ul>
				</li>
			</li>
				
		</div>
				<br style="clear: both;" />
	<div id="footer">
		<?php $this->load->view('footer'); ?>
	</div>
	</div>
</div>

</body>
</html>