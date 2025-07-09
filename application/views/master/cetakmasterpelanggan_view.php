<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>               
<div class="panel panel-default">

 <div class="form-group">
      <label for="cbocluster" class="col-sm-2 control-label">Cluster</label>
 <div class="col-sm-6">                                   
      <?php                  
           echo form_dropdown(
               'cbocluster',
               $isicluster,  
               'class="form-control input-sm "  id="idcluster"'
                );             
      ?>
       <?php echo form_error('idcluster');?>
 </div>
 </div> <!--/ IdCluster -->
 
 				<div class="form-group">
                   <label for="cbobork" class="col-sm-2 control-label">Bangunan/Kavling </label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_dropdown(
                           'cbobork',
                           $isibork,  
                           '',
                           'class="form-control input-sm "  id="idbork"'
                           );             
                  ?>
                 <?php echo form_error('idbork');?>
                </div>
              </div> <!--/ cbobork -->

<div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('cetak_master_pelanggan'); ?>" class="btn btn-default">
                       <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                   </a> 
                    <button type="submit" class="btn btn-primary" name="post">
                        <i class="glyphicon glyphicon-floppy-save"></i> Cetak Data Master 
                    </button>                  
              </div>
          </div>
    </div><!--/ Panel Footer -->   

</div>
<?php echo form_close(); ?> 