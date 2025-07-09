<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_pelanggan" parsley-validate'); ?>               
<div class="panel panel-default">
    <div class="panel-heading"><i class="glyphicon glyphicon-signal"></i> </div>
     
      <div class="panel-body">
         
             <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">ID Cluster</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'idcluster',
                                 'id'           => 'idcluster',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'ID Cluster',
                                 'maxlength'=>'25'
                                 ),
                                 set_value('idcluster',$cluster['idcluster'])
                           );             
                  ?>
                 <?php echo form_error('idcluster');?>
                </div>
              </div> <!--/ No. Anggota -->         

			  <div class="form-group">
                   <label for="nama" class="col-sm-2 control-label">Nama Cluster</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'namacluster',
                                 'id'           => 'namacluster',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Nama Cluster',
                                 'maxlength'=>'25'
                                 ),
				set_value('namacluster',$cluster['namacluster'])
                           );             
                  ?>
                 <?php echo form_error('namacluster');?>
                </div>
              </div> <!--/ Nama -->
                     
               <div class="form-group">
                   <label for="alamat" class="col-sm-2 control-label">Tarif</label>
                <div class="col-sm-6">                                   
                  <?php                  
                   echo form_input(
                                array(
                                 'name'         => 'tarif',
                                 'id'           => 'tarif',                       
                                 'class'        => 'form-control input-sm ',
                                 'placeholder'  => 'Tarif',
                                 'maxlength'=>'12'
                                 ),
                                 set_value('tarif',$cluster['tarif'])
                           );             
                  ?>
                 <?php echo form_error('tarif');?>
                </div>
              </div> <!--/ Blok -->
              
            
      </div> <!--/ Panel Body -->
    <div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('master_cluster'); ?>" class="btn btn-default">
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