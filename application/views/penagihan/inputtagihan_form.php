<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>               
<div class="panel panel-default">

<div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">ID IPKL</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'idipkl',
                                 'id'           => 'idipkl',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'ID IPKL',
                                 'maxlength'=>'25',
                                 'readonly'=>'readonly'
                                 ),
                                 set_value('idipkl',isset($default['idipkl']) ? $default['idipkl'] : '') 
                           );             
                  ?>
                 <?php 
//                  echo form_error('idipkl');
                 ?>
                </div>
</div> <!--/ ID IPKL -->

<div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Nama Customer</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'nama',
                                 'id'           => 'nama',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Nama Customer',
                                 'maxlength'=>'25',
                                 'readonly'=>'readonly'
                                 ),
                                 set_value('nama',isset($default['nama']) ? $default['nama'] : '') 
                           );             
                  ?>
                 <?php 
//                  echo form_error('idipkl');
                 ?>
                </div>
</div> <!--/ Nama Pelanggan -->

<div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Blok</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'blok',
                                 'id'           => 'blok',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Blok',
                                 'maxlength'=>'25',
                                 'readonly'=>'readonly'
                                 ),
                                 set_value('blok',isset($default['blok']) ? $default['blok'] : '') 
                           );             
                  ?>
                 <?php 
//                  echo form_error('idipkl');
                 ?>
                </div>
</div> <!--/ Blok -->

<div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">No. Kavling</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'nokav',
                                 'id'           => 'nokav',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'No. Kavling',
                                 'maxlength'=>'25',
                                 'readonly'=>'readonly'
                                 ),
                                 set_value('nokav',isset($default['nokav']) ? $default['nokav'] : '') 
                           );             
                  ?>
                 <?php 
//                  echo form_error('idipkl');
                 ?>
                </div>
</div> <!--/ No. Kav -->

<div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Cluster</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'namacluster',
                                 'id'           => 'namacluster',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Nama Cluster',
                                 'maxlength'=>'25',
                                 'readonly'=>'readonly'
                                 ),
                                 set_value('namacluster',isset($default['namacluster']) ? $default['namacluster'] : '') 
                           );             
                  ?>
                 <?php 
//                  echo form_error('idipkl');
                 ?>
                </div>
</div> <!--/ Cluster -->

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

<div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Cluster</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'tagihan',
                                 'id'           => 'tagihan',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Tagihan',
                                 'maxlength'=>'25'
                                 ),
                                 set_value('tagihan',isset($default['tagihan']) ? $default['tagihan'] : '') 
                           );             
                  ?>
                 <?php 
//                  echo form_error('tagihan');
                 ?>
                </div>
</div> <!--/ Cluster -->

<div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('input_tagihan'); ?>" class="btn btn-default">
                       <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                   </a> 
                    <button type="submit" class="btn btn-primary" name="post">
                        <i class="glyphicon glyphicon-floppy-save"></i> Simpan Tagihan 
                    </button>                  
              </div>
          </div>
    </div><!--/ Panel Footer -->   

</div>
<?php echo form_close(); ?> 