<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>               
<div class="panel panel-default">
    <div class="panel-heading"><i class="glyphicon glyphicon-signal"></i> </div>
     
      <div class="panel-body">
         
             <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">No Kuitansi</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'nokuitansi',
                                 'id'           => 'nokuitansi',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Masukan No Kuitansi',
                                 'maxlength'=>'20'
                                 )
                           );             
                  ?>
                 <?php echo form_error('nokuitansi');?>
                </div>
              </div> <!--/ No. Kuitansi -->         

      </div> <!--/ Panel Body -->
    <div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('anggota'); ?>" class="btn btn-warning">
                       <i class="fa fa-chevron-left"></i> Kembali
                   </a> 
                    <button type="submit" class="btn btn-primary" name="post">
                        <i class="fa fa-search"></i> Cari 
                    </button>                  
              </div>
          </div>
    </div><!--/ Panel Footer -->       
</div><!--/ Panel -->
<?php echo form_close(); ?> 
