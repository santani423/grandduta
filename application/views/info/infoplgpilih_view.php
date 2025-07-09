<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>               
<div class="panel panel-default">
    <div class="panel-heading"><i class="glyphicon glyphicon-signal"></i> </div>
     
      <div class="panel-body">
      
     <div class="form-group">
 		<label for="nama" class="col-sm-2 control-label">Cari Berdasarkan</label>
      
   <div class="col-sm-6"> 
       <div class="radio">
           <label>
           <input type="radio" name="optionsRadios" id="optionsRadios1" value="1" checked>Nama Pelanggan
           </label>
       </div>
      <div class="radio">
      <label>
           <input type="radio" name="optionsRadios" id="optionsRadios2" value="2">Alamat Pelanggan
      </label>
      </div>
       </div>                                     
    </div> 
         
             <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Nama Pelanggan</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'namapelanggan',
                                 'id'           => 'namapelanggan',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Nama Pelanggan',
                                 'maxlength'=>'10'
                                 )
                           );             
                  ?>
                 <?php echo form_error('namapelanggan');?>
                </div>
              </div> <!--/ No. Anggota -->  
              
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
                   <label for="nama" class="col-sm-2 control-label">Blok</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'blok',
                                 'id'           => 'blok',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Blok'
                                 )
                           );             
                  ?>
                 <?php echo form_error('blok');?>
                </div>
              </div> <!--/ No. Anggota -->  
              
                           <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Nomor Kavling</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'nokav',
                                 'id'           => 'nokav',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Nomor Kavling'
                                 )
                           );             
                  ?>
                 <?php echo form_error('nokav');?>
                </div>
              </div> <!--/ No. Anggota -->  

      </div> <!--/ Panel Body -->
    <div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('anggota'); ?>" class="btn btn-default">
                       <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                   </a> 
                    <button type="submit" class="btn btn-primary" name="post">
                        <i class="glyphicon glyphicon-floppy-save"></i> Cari 
                    </button>                  
              </div>
          </div>
    </div><!--/ Panel Footer -->       
</div><!--/ Panel -->
<?php echo form_close(); ?> 