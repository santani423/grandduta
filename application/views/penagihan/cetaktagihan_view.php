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
 </div>
    
<div class="form-group">
    <label for="cbostatus" class="col-sm-2 control-label">Huni / Kosong</label>
    <div class="col-sm-6">                                   
        <?php                  
            echo form_dropdown(
               'cbostatushuni',
               $isihuni,  
               '',
               'class="form-control input-sm "  id="idhuni"'
            );             
        ?>
        <?php echo form_error('idhuni');?>
    </div>
</div> <!--/ Status Huni -->    
    
 <div class="form-group">     
	<label for="nama" class="col-sm-2 control-label">Pilih Laporan Bulanan</label>
 
    <div class="col-sm-6"> 
 
		<div class="radio">
          	<label>
          		<input type="radio" name="optionsRadios" id="optionsRadios1" value="1" checked>Berikut Bulan Berjalan
        	</label>
                </div>
                <div class="radio">
       		<label>
			<input type="radio" name="optionsRadios" id="optionsRadios2" value="2">Lebih dari 1 Bulan 
      		</label>
                </div>
		<div class="radio">
                <label>
          		<input type="radio" name="optionsRadios" id="optionsRadios3" value="3">Lebih dari 2 Bulan
        	</label>
                </div>
                <div class="radio">
       		<label>
			<input type="radio" name="optionsRadios" id="optionsRadios4" value="4">Lebih Dari Tiga Bulan
      		</label>
                </div>
    </div>
 </div>

 <div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('anggota'); ?>" class="btn btn-default">
                       <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                   </a> 
                    <button type="submit" class="btn btn-primary" name="post">
                        <i class="glyphicon glyphicon-floppy-save"></i> Cetak Tagihan 
                    </button>                  
              </div>
          </div>
    </div><!--/ Panel Footer -->   

</div>
<?php echo form_close(); ?> 