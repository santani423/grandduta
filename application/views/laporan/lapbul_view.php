<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>               
<div class="panel panel-default">

<div class="form-group">  
    <label for="cbostatus" class="col-sm-2 control-label">Per Tahun</label>
	<div class="col-sm-6">                                   
            <?php
    		echo buildYearDropdown("cbotahun",date('Y'));
            ?>
            <?php echo form_error('idtahun');?>
        </div>
</div>
    
<div class="form-group">           
    <label for="cbostatus" class="col-sm-2 control-label">Per Bulan</label>
	<div class="col-sm-6">                                   
            <?php
    		echo buildMonthDropdown("cbobulan",date('M'));
            ?>
            <?php echo form_error('idbulan');?>
        </div>
</div>
    
<div class="form-group">
	<label for="nama" class="col-sm-2 control-label">Pilih Laporan Bulanan</label>

    <div class="col-sm-6"> 
		<div class="radio">
          	<label>
          		<input type="radio" name="optionsRadios" id="optionsRadios1" value="1" checked>Bulan Berjalan
        	</label>
      	</div>
       	<div class="radio">
       		<label>
				<input type="radio" name="optionsRadios" id="optionsRadios2" value="2">Bulan Lalu
      		</label>
      	</div>
		<div class="radio">
         <label>
          		<input type="radio" name="optionsRadios" id="optionsRadios3" value="3">Dua Bulan Lalu
        	</label>
      	</div>
        
       	<div class="radio">
       		<label>
				<input type="radio" name="optionsRadios" id="optionsRadios4" value="4">Lebih Dari Tiga Bulan
      		</label>
      	</div>
        
      	<div class="radio">
       		<label>
				<input type="radio" name="optionsRadios" id="optionsRadios5" value="5">Laporan Total Penerimaan
      		</label>
      	</div>
        
      	<div class="radio">
       		<label>
				<input type="radio" name="optionsRadios" id="optionsRadios6" value="6">Laporan Total Pendapatan
      		</label>
      	</div>
      	
      	<div class="radio">
       		<label>
				<input type="radio" name="optionsRadios" id="optionsRadios7" value="7">Laporan Deposit Per Cluster
      		</label>
      	</div>
        
       	<div class="radio">
       		<label>
				<input type="radio" name="optionsRadios" id="optionsRadios8" value="8">Laporan Total Penerimaan Per Cluster
      		</label>
      	</div>
        
        <div class="radio">
       		<label>
				<input type="radio" name="optionsRadios" id="optionsRadios9" value="9">Laporan Total Pendapatan Per Cluster
      		</label>
      	</div>
        
            <div class="form-group">
                   <label for="cbocluster" class="col-sm-2 control-label">Cluster</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_dropdown(
                           'cbocluster',
                           $isicluster,  
                           '',
                           'class="form-control input-sm "  id="idcluster"'
                           );             
                  ?>
                 <?php echo form_error('idcluster');?>
                </div>
            </div> <!--/ IdCluster -->
   </div>                                     
</div> 

<div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('laporan_bulanan'); ?>" class="btn btn-default">
                       <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                   </a> 
                    <button type="submit" class="btn btn-primary" name="post">
                        <i class="glyphicon glyphicon-floppy-save"></i> Generate Laporan 
                    </button>                  
              </div>
          </div>
    </div><!--/ Panel Footer -->   

</div>
<?php echo form_close(); ?> 
