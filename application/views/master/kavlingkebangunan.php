<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_pelanggan" parsley-validate'); ?>               
<div class="panel panel-default">
    <div class="panel-heading"><i class="glyphicon glyphicon-signal"></i> </div>
     
      <div class="panel-body">
         
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
                                 set_value('idipkl',$pelanggan['idipkl'])
                           );             
                  ?>
                 <?php echo form_error('idipkl');?>
                </div>
              </div> <!--/ No. Anggota -->         

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
                                 'maxlength'=>'25'
                                 ),
								set_value('namapelanggan',$pelanggan['namapelanggan'])
                           );             
                  ?>
                 <?php echo form_error('namapelanggan');?>
                </div>
              </div> <!--/ Nama -->
              
              <div class="form-group">
                   <label for="cbocluster" class="col-sm-2 control-label">Cluster</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_dropdown(
                           'cbocluster',
                           $isicluster,  
                           set_value('idcluster',$pelanggan['idcluster']),
                           'class="form-control input-sm "  id="idcluster"                                  
                           readonly="eadonly"'
                           );             
                  ?>
                 <?php echo form_error('idcluster');?>
                </div>
              </div> <!--/ IdCluster -->
                          
               <div class="form-group">
                   <label for="alamat" class="col-sm-2 control-label">Blok</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'blok',
                                 'id'           => 'blok',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Blok',
                                 'maxlength'=>'5',                                 
                                 'readonly'=>'readonly'
                                 ),
                                 set_value('blok',$pelanggan['blok'])
                           );             
                  ?>
                 <?php echo form_error('blok');?>
                </div>
              </div> <!--/ Blok -->
              
              <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Nomor Kavling</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'nokav',
                                 'id'           => 'nokav',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Nomor Kavling',
                                 'maxlength'=>'25',                                 
                                 'readonly'=>'readonly'
                                 ),
                                 set_value('nokav',$pelanggan['nokav'])
                           );             
                  ?>
                 <?php echo form_error('nokav');?>
                </div>
              </div> <!--/ No. Kavling --> 
              
             <div class="form-group">
                   <label for="lb" class="col-sm-2 control-label">Luas Bangunan </label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'lb',
                                 'id'           => 'lb',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Luas Bangunan',
                                 'maxlength'=>'75'
                                 ),
                                 set_value('lb',$pelanggan['lb'])
                           );             
                  ?>
                 <?php echo form_error('lb');?>
                </div>
              </div> <!--/ Alamat KTP --> 
              
              <div class="form-group">
                   <label for="lt" class="col-sm-2 control-label">Luas Tanah </label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'lt',
                                 'id'           => 'lt',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Luas Tanah',
                                 'maxlength'=>'75'
                                 ),
                                 set_value('lt',$pelanggan['lt'])
                           );             
                  ?>
                 <?php echo form_error('lt');?>
                </div>
              </div> <!--/ Luas Tanah -->
              
			  
		<div class="form-group">
                   <label for="cbobork" class="col-sm-2 control-label">Bangunan/Kavling </label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_dropdown(
                           'cbobork',
                           $isibork,  
                           set_value('idbork',$pelanggan['idbork']),
                           'class="form-control input-sm "  id="idbork"'
                           );             
                  ?>
                 <?php echo form_error('idbork');?>
                </div>
              </div> <!--/ cbobork -->
              
               <div class="form-group">
                   <label for="tglserahterima" class="col-sm-2 control-label">Tgl Serah Terima</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'tglserahterima',
                                 'id'           => 'datetimepicker1',                       
                                 'class'        => 'form-control input-sm tanggal', 
                                 'placeholder'  => 'Tanggal Serah Terima',
                                 
                                 ),
                                 set_value('tglserahterima',$pelanggan['tglserahterima'])
                           );             
                  ?>
      </div> 
 
                 <?php echo form_error('tglserahterima');?>
                </div>
              </div> <!--/ Tglmasuk -->
                          
     </div> <!--/ Panel Body -->
    <div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('master_pelanggan'); ?>" class="btn btn-default">
                       <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                   </a> 
                    <button type="submit" class="btn btn-primary" name="post">
                        <i class="glyphicon glyphicon-floppy-save"></i> Simpan 
                    </button>                  
              </div>
          </div>
    </div><!--/ Panel Footer -->       
</div><!--/ Panel -->
<?php echo form_close(); ?> 

        <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
            });
        </script>