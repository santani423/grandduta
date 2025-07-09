<section class="panel panel-default">
    <header class="panel-heading">
        <div class="row">
            <div class="col-md-8 col-xs-3">                
                
            </div>
            <div class="col-md-4 col-xs-9">
            </div>
        </div>
    </header>
    
    
    <div class="panel-body">
         <?php 
		 //echo $anggotas['noanggota'];
		 if ($pelanggans) : 
		 ?>
          <table class="table table-hover table-condensed">
              
            <thead>
              <tr>
                <th class="header"><center>ID IPKL</th>
                
                    <th><center>NAMA PELANGGAN</center></th>   
                
                    <th><center>ALAMAT</center></th>   
                
  
                <th class="red header" align="right" width="120"><center>AKSI</center></th>
              </tr>
            </thead>
            
            
            <tbody>
             
               <?php foreach ($pelanggans as $pelanggan) : ?>
              <tr>
              	<td><?php echo $pelanggan['idipkl']; ?> </td>
			   <td><?php echo $pelanggan['namapelanggan']; ?></td>
               
               <td><?php echo $pelanggan['namacluster']; ?>
               <?php echo $pelanggan['blok']; ?>
               <?php echo $pelanggan['nokav']; ?></td>
               
            
                <td>    
                    
                    <?php
                                  echo anchor(
                                          site_url('master_pelanggan/show/' .$pelanggan['idipkl']),
                                            '<i class="glyphicon glyphicon-eye-open"></i>',
                                            'class="btn btn-sm btn-info" data-tooltip="tooltip" data-placement="top" title="Detail"'
                                          );
                   ?>
                             
                </td>
              </tr>     
               <?php endforeach ; ?>
            </tbody>
          </table>
          <?php else : ?>
                <?php  echo notify('Data Customer tidak ada','info');?>
          <?php endif ; ?>
    </div>
        </div>
    </div>
</section>
