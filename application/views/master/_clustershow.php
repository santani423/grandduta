<?php 
    if($cluster) :
?> 
<div class="panel-body">
<table id="detail" class="table table-striped table-condensed">
	<tbody>
    <?php     
        foreach($cluster as $table => $value) :    
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

	<?php 
	
		echo anchor(site_url('master_cluster'), '<span class="fa fa-chevron-left"></span> Kembali', 'class="btn btn-sm btn-default"');
	
	?>


<br /><br />

<?php 
    endif;
?>