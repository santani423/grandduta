<div class="panel panel-default">

 <div class="form-group">
      <label for="cbocluster" class="col-sm-2 control-label">Hasil Penyiapan : </label>
 <div class="col-sm-6">                                   
      <?php echo $pesan;?>
 </div>
 </div> <!--/ IdCluster -->
 		<?php 
 			echo "</br>";
 		?>

<div class="form-group">
    	<label for="cbostatus" class="col-sm-2 control-label">Jumlah Data :</label>
	<div class="col-sm-6">                                   
      <?php echo $jml_terupdate; ?>
    </div>
</div> <!--/ Bulan -->
 		<?php 
 			echo "</br>";
 		?>

<div class="form-group">
    	<label for="cbostatus" class="col-sm-2 control-label">Cluster :</label>
	<div class="col-sm-6">                                   
      <?php echo $cluster; ?>
    </div>
</div> <!--/ Tahun -->
 		<?php 
 			echo "</br>";
 		?>

<div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('penyiapan_tagihan'); ?>" class="btn btn-default">
                       <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                   </a> 
            </div>
          </div>
    </div><!--/ Panel Footer -->   

</div>
