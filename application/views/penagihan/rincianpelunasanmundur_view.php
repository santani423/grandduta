<?php echo form_open(site_url($action),'role="form" class="form-horizontal" id="form_penagihan" parsley-validate'); ?>

<?php 
	if ($default['idipkl']) : 
?>
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

<div class="panel-body">
	<div class="page-header">
		<h3><center><b>Tagihan</b></center></h3>
	</div>

    <div class="panel-body">
         <?php 
		 if ($tagihans) : 
		 ?>
          <table class="table table-hover table-condensed">
              
            <thead>
              <tr>
                <th class="header"><center>ID TAGIHAN</th>
                
                    <th><center>TAHUN</center></th>   
                
                    <th><center>BULAN</center></th>   
                
                    <th><center>JUMLAH</center></th>   
                
                    <th><center>DENDA</center></th>   
                    
                    <th><center>TOTAL</center></th> 
                    
					<th><center>STATUS TAGIHAN</center></th> 
					
					<th><center>PELUNASAN</center></th> 
              </tr>
            </thead>
            
            
            <tbody>
             
               <?php foreach ($tagihans as $tagihan) : ?>
              <tr>
            	<td><?php echo $tagihan['idtagihan']; ?> </td>               
               <td><?php echo $tagihan['tahun']; ?></td>               
               <td><?php echo $tagihan['bulan']; ?></td>          
               <td><?php echo $tagihan['tagihan']; ?></td>               
               <td><?php echo $tagihan['denda']; ?></td>                  
               <td><?php echo $tagihan['Jumlah']; ?></td>               
               <td><center><?php echo $tagihan['namastatustagihan']; ?></center></td>
               <td><center> 
                   <?php
               			 echo anchor(
                              site_url('pelunasan_mundur/tampillunasmundur/' .$tagihan['idtagihan']),
                                       '<i class="glyphicon glyphicon-eye-open"></i>',
                                       'class="btn btn-sm btn-info" data-tooltip="tooltip" data-placement="top" title="Pelunasan"'
                                      );
                   ?>
                   </center>
               </td> 
              </tr>     
               <?php endforeach ; ?>
            </tbody>
          </table>
          <?php if($totalnya) :
          
          		echo "Total Tagihan : ";
          		echo $totalnya['total']; 
          
          	endif;
          ?>
          <?php else : ?>
                <?php  echo notify('Data Tagihan tidak ada','Info');?>
          <?php endif ; ?>
    </div>

</div>

    <div class="panel-footer">   
          <div class="row"> 
              <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                   <a href="<?php echo site_url('pelunasan_mundur'); ?>" class="btn btn-default">
                       <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                   </a> 
                                      
              </div>
          </div>
    </div><!--/ Panel Footer -->  

<?php else : ?>
      <?php  echo notify('Data Customer tidak ada','Info');?>
<?php endif ; ?>

<?php 
	echo form_close();
?>

<script>
function cetak()
{
window.open('bayartagihan');
location.open();
}
</script>