<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>               
<div class="panel panel-default">
    <div class="panel-heading"><i class="glyphicon glyphicon-signal"></i> 
	</div>

	<?php 
	if($this->session->flashdata('message'))
	{
	?>
    	<div class="alert-message error">
	    <a class="close" href="#">×</a>
	    <p><strong>
    		<?php echo $this->session->flashdata('message'); ?>
		</strong></p>
		</div>
	<?php 	
	}
	?>
   
      <div class="panel-body">
             <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'username',
                                 'id'           => 'username',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Username',
                                 'maxlength'=>'10',
                                 'readonly'=>'readonly'
									),
									set_value('username',isset($default['username']) ? $default['username'] : '')
								);         
                  ?>
                 <?php echo form_error('idipkl');?>
                </div>
              </div> <!--/ Username -->         
      </div> <!--/ Panel Body -->

      <div class="panel-body">
             <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Password Lama</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'passwdlama',
                                 'id'           => 'passwdlama',
								 'type'         => 'password',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Password Lama',
                                 'maxlength'=>'10'
                                 )
                           );             
                  ?>
                 <?php echo form_error('idipkl');?>
                </div>
              </div> <!--/ Username -->         
      </div> <!--/ Panel Body -->
      
            <div class="panel-body">
             <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Password Baru</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'passwdbaru1',
                                 'id'           => 'passwdbaru1',
								 'type'         => 'password',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Password Baru',
                                 'maxlength'=>'10'
                                 )
                           );             
                  ?>
                 <?php echo form_error('idipkl');?>
                </div>
              </div> <!--/ Username -->         
      </div> <!--/ Panel Body -->
      
            <div class="panel-body">
             <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Password Baru Lagi</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'passwdbaru2',
                                 'id'           => 'passwdbaru2',
								 'type'         => 'password',                      
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Password Baru Lagi',
                                 'maxlength'=>'10'
                                 )
                           );             
                  ?>
                 <?php echo form_error('idipkl');?>
                </div>
              </div> <!--/ Username -->         
      </div> <!--/ Panel Body -->
      
      
    <div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('ubah_password'); ?>" class="btn btn-default">
                       <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                   </a> 
                    <button type="submit" class="btn btn-primary" name="post">
                        <i class="glyphicon glyphicon-floppy-save"></i> Ganti Password 
                    </button>                  
              </div>
          </div>
    </div><!--/ Panel Footer -->       
</div><!--/ Panel -->
<?php echo form_close(); ?> 
