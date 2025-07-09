<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>               
<div class="panel panel-default">
<div class="form-group">
    	<label for="cbostatus" class="col-sm-2 control-label">Tanggal</label>
	<div class="col-sm-6">                                   
    	<?php
    		echo buildDayDropdown("cbohari",date('D'));
    	?>
    	<?php echo form_error('idhari');?>
    </div>
</div> <!--/ Tanggal -->

<div class="form-group">
    	<label for="cbostatus" class="col-sm-2 control-label">Bulan</label>
	<div class="col-sm-6">                                   
    	<?php
    		echo buildMonthDropdown("cbobulan",date('M'));
    	?>
    	<?php echo form_error('idbulan');?>
    </div>
</div> <!--/ Bulan -->

<div class="form-group">
    	<label for="cbostatus" class="col-sm-2 control-label">Tahun</label>
	<div class="col-sm-6">                                   
        <?php
    		echo buildYearDropdown("cbotahun",date('Y'));
    	?>
    	<?php echo form_error('idtahun');?>
    </div>
</div> <!--/ Tahun -->

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

</div>
<?php echo form_close(); ?> 
