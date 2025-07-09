<?php
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>

<section class="panel panel-default">
    <header class="panel-heading">
        <div class="row">
            <div class="col-md-8 col-xs-3">                
                <?php
                                  echo anchor(
                                           site_url('master_cluster/add'),
                                            '<i class="glyphicon glyphicon-plus"></i>',
                                            'class="btn btn-success btn-sm" data-tooltip="tooltip" data-placement="top" title="Tambah Data"'
                                          );
                 ?>
                
            </div>
            <div class="col-md-4 col-xs-9">
                                           
                 <?php echo form_open(site_url('master_cluster/search'), 'role="search" class="form"') ;?>       
                           <div class="input-group pull-right">                      
                                 <input type="text" class="form-control input-sm" placeholder="Masukkan Nama Cluster" name="q" autocomplete="off"> 
                                 <span class="input-group-btn">
                                      <button class="btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-search"></i> Cari</button>
                                 </span>
                           </div>
                           
               </form> 
                <?php echo form_close(); ?>
            </div>
        </div>
    </header>
    
    
    <div class="panel-body">
         <?php 
		 //echo $anggotas['noanggota'];
		 if ($clusters) : 
		 ?>
          <table class="table table-hover table-condensed">
              
            <thead>
              <tr>
                <th class="header"><center>ID CLUSTER</th>
                
                    <th><center>NAMA CLUSTER</center></th>
                
                    <th><center> TARIF </center></th>
  
                <th class="red header" align="right" width="120"><center>AKSI</center></th>
              </tr>
            </thead>
            
            
            <tbody>
             
               <?php foreach ($clusters as $cluster) : ?>
              <tr>
              	<td><?php echo $cluster['idcluster']; ?> </td>
			   <td><?php echo $cluster['namacluster']; ?></td>
                           <td><?php echo $cluster['tarif']; ?></td>
               <td>    
                    
                    <?php
                                  echo anchor(
                                          site_url('master_cluster/show/' . $cluster['idcluster']),
                                            '<i class="glyphicon glyphicon-eye-open"></i>',
                                            'class="btn btn-sm btn-info" data-tooltip="tooltip" data-placement="top" title="Detail"'
                                          );
                   ?>
                    
                    <?php
                                  echo anchor(
                                          site_url('master_cluster/edit/' . $cluster['idcluster']),
                                            '<i class="glyphicon glyphicon-edit"></i>',
                                            'class="btn btn-sm btn-success" data-tooltip="tooltip" data-placement="top" title="Edit"'
                                          );
                   ?>
                   
                   <?php
                                  echo anchor(
                                          site_url('master_cluster/destroy/' . $cluster['idcluster']),
                                            '<i class="glyphicon glyphicon-trash"></i>',
                                            'onclick="return confirm(\'Anda yakin..???\');" class="btn btn-sm btn-danger" data-tooltip="tooltip" data-placement="top" title="Hapus"'
                                          );
                   ?>   
                                 
                </td>
              </tr>     
               <?php endforeach ; ?>
            </tbody>
          </table>
          <?php else : ?>
                <?php  //echo notify('Data Cluster belum tersedia','info');?>
          <?php endif ; ?>
    </div>
    
    
    <div class="panel-footer">
        <div class="row">
           <div class="col-md-3">
               Jumlah Cluster 
               <span class="label label-info">
                    <?php echo $total; ?>
               </span>
           </div>  
           <div class="col-md-9">
                 <?php echo $pagination; ?>
           </div>
        </div>
    </div>
</section>