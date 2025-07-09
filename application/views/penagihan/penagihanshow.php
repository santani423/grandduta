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
                    
                    <th><center>NO. KUITANSI </center></th>
                     
                    <th><center>KETERANGAN </center></th>  
              </tr>
            </thead>
            
            
            <tbody>
               <?php
               		$jmltagihan=0;
               		$jmltotal=0;
               	?>
               <?php foreach ($tagihans as $tagihan) : ?>
               	<?php
               		$jmltagihan=$jmltagihan+$tagihan['tagihan'];
               		$jmltotal=$jmltotal+$tagihan['Jumlah'];
               	?>
              <tr>
            	<td><?php echo $tagihan['idtagihan']; ?> </td>               
               <td><?php echo $tagihan['tahun']; ?></td>               
               <td><?php echo $tagihan['bulan']; ?></td>          
               <td><?php echo $tagihan['tagihan']; ?></td>               
               <td><?php echo $tagihan['denda']; ?></td>                  
               <td><?php echo $tagihan['Jumlah']; ?></td>               
               <td><?php echo $tagihan['namastatustagihan']; ?></td>
               <td><?php echo $tagihan['tglbayar']; ?></td>
               <td><?php echo $tagihan['nokuitansi']; ?></td>
               <td><?php echo $tagihan['ketaproval']; ?></td>
              </tr>     
               <?php endforeach ; ?>
              <tr>
            	<td><?php echo ''; ?> </td>               
               <td><?php echo ''; ?></td>               
               <td><?php echo 'Total '; ?></td>          
               <td><?php echo $jmltagihan; ?></td>               
               <td><?php ''; ?></td>                  
               <td><?php echo ''; ?></td>               
               <td><?php echo ''; ?></td>
              </tr> 
            </tbody>
            
          </table>
         <?php else : ?>
                <?php  echo notify('Data Tagihan tidak ada','Info');?>
          <?php endif ; ?>
    </div>

	<?php 
	
		echo anchor(site_url('info_tagihan'), '<span class="fa fa-chevron-left"></span> Kembali', 'class="btn btn-sm btn-default"');
	
	?>

<br /><br />

<?php else : ?>
    <?php  echo notify('Data Customer tidak ada','Info');?>
<?php endif ; ?>