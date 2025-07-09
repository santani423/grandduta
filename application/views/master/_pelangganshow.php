<?php 
    if($pelanggan) :
?> 
<div class="panel-body">
<table id="detail" class="table table-striped table-condensed">
	<tbody>
    <?php     
        foreach($pelanggan as $table => $value) :    
    ?>
    <tr>
        <td width="20%" align="right"><strong><?php echo $table ?></strong></td>
        <td><?php echo $value ?></td>
    </tr>
     <?php 
        endforeach;
     ?>
     </tbody>
</table>
	<div class="page-header">
		<h3><center><b>Riwayat Tagihan</b></center></h3>
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
                
                    <th><center>STATUS </center></th>
                    
                    <th><center>TGL BAYAR </center></th>      
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
               <td><?php echo $tagihan['namastatustagihan']; ?></td>
               <td><?php echo $tagihan['tglbayar']; ?></td>
              </tr>     
               <?php endforeach ; ?>
            </tbody>
          </table>
          <?php else : ?>
                <?php  echo notify('Data pinjaman tidak ada','Info');?>
          <?php endif ; ?>
    </div>

	<?php 
	
		echo anchor(site_url('master_pelanggan'), '<span class="fa fa-chevron-left"></span> Kembali', 'class="btn btn-sm btn-default"');
	
	?>


<br /><br />

<?php 
    endif;
?>