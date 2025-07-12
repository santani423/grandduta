<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<html>
<head>
<!-- header -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Trias Bratakusuma" >

    <title>IPKL Syss | Perumahan Grand Duta Tangeranga</title>

    <!-- Custom CSS -->
    <link href="<?php echo base_url() . 'css/sb-admin-2.css';?>" rel="stylesheet">
    
    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url() . 'bower_components/metisMenu/dist/metisMenu.min.css'; ?>" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo base_url() . 'css/error.css';?>" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url() . 'css/plugins/morris.css';?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url() . 'font-awesome/css/font-awesome.min.css';?>" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="<?php echo base_url() . 'bower_components/jquery/dist/jquery.min.js'; ?>"></script>
  	<script type="text/javascript" src="<?php echo base_url() . 'bower_components/moment/min/moment.min.js'; ?>"></script>
  	<script type="text/javascript" src="<?php echo base_url() . 'bower_components/bootstrap/dist/js/bootstrap.min.js'; ?>"></script>
  	<script type="text/javascript" src="<?php echo base_url() . 'bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'; ?>"></script>
  
  	<link rel="stylesheet" href="<?php echo base_url() . 'bower_components/bootstrap/dist/css/bootstrap.css'; ?>" /> 
  	<link rel="stylesheet" href="<?php echo base_url() . 'bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'; ?>" />


</head>

<body>

	<div id="wrapper">
	
	    <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="">IPKL Sys | Perumahan Grand Duta Tangerang</a>
            </div>
            <!-- batas akhir dari tampilan atas -->
            <!-- Top Menu Items -->

            <!-- akses user admin -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i><?php echo $this->session->userdata('nama');?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profil </a>
                        </li>
                        <li>
                            <a href="ubah_password"><i class="fa fa-fw fa-envelope"></i> Password</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <?php echo anchor('home/logout','Logout');?>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

			<!-- <div class="navbar-default sidebar" role="navigation">
                    <ul class="nav" id="side-menu">
 								<?php
									foreach($menu->result() as $row)
									{
										echo '<li>'.anchor($row->menu_uri,$row->menu_nama).'</li>';
									}
								?>                         -->
                    <!-- </ul> -->
                <!-- /.sidebar-collapse -->
            <!-- </div> -->
            <!-- /.navbar-static-side -->

            <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">

            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Cari...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </li>

            <!-- Dashboard side bar-->
            <li>
                <a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <!-- cara untuk memanggil satu persatu -->
            <?php
            // $row1 = $menu->row(0); // Ambil data pertama
            //$row2 = $menu->row(1); // Ambil data kedua
            //$row3 = $menu->row(2); // Ambil data ketiga
            ?>
            
            <!-- <li>
                <a href="<?php echo site_url($row1->menu_uri); ?>">
                    <i class="fa fa-folder-open-o fa-fw"></i> <?php echo $row1->menu_nama; ?>
                </a>
            </li> -->
            <!-- Dynamic Menu from DB -->
            <?php foreach ($menu->result() as $row): ?>
                <li>
                    <a href="<?php echo site_url($row->menu_uri); ?>">
                        <i class="fa fa-folder-open-o fa-fw"></i> <?php echo $row->menu_nama; ?>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Settings -->
            <!-- <li>
                <a href="#"><i class="fa fa-cogs fa-fw"></i> Pengaturan<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo site_url('profil'); ?>"><i class="fa fa-user"></i> Profil</a></li>
                    <li><a href="<?php echo site_url('ubah_password'); ?>"><i class="fa fa-lock"></i> Ubah Password</a></li>
                </ul>
            </li> -->

            <!-- Logout -->
            <!-- <li>
                <a href="<?php echo site_url('home/logout'); ?>"><i class="fa fa-sign-out"></i> Logout</a>
            </li> -->

        </ul>
    </div>
</div>


		<div id="page-wrapper">
               <div class="row">
     	
							<!-- Page Heading -->
							<div class="panel panel-default">
                  		<div class="col-lg-12">
                     		<h1 class="page-header">
                        		<?php echo $judulpage; ?> <small>IPKL Sys</small>
                        	</h1>
                   		</div>
							</div>
                		<!-- /.row -->               	
               	
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Workspace</h3>
                            </div>
                            <div class="panel-body">
                                <?php echo $contents ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                
	           </div>
            <!-- /.container-fluid -->

		</div>
</body>
</html>
