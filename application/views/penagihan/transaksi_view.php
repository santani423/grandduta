




<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<?php echo form_open(site_url($action), 'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>               
<div class="panel panel-default">
    <div class="panel-heading"><i class="glyphicon glyphicon-signal"></i> </div>
    <div class="panel-body">

        <!-- Input IDIPKL -->
        <div class="form-group">
            <label for="idipkl" class="col-sm-2 control-label">ID IPKL</label>
            <div class="col-sm-6">                                   
                <?php echo form_input([
                    'name'        => 'idipkl',
                    'id'          => 'idipkl',                       
                    'class'       => 'form-control input-sm',
                    'placeholder' => 'ID IPKL',
                    'maxlength'   => '10'
                ]); ?>
                <?php echo form_error('idipkl'); ?>
            </div>
        </div>

        <!-- Input Blok -->
        <div class="form-group">
            <label for="blok" class="col-sm-2 control-label">Blok</label>
            <div class="col-sm-6">                                   
                <?php echo form_input([
                    'name'        => 'blok',
                    'id'          => 'blok',                       
                    'class'       => 'form-control input-sm',
                    'placeholder' => 'Blok',
                    'maxlength'   => '10'
                ]); ?>
                <?php echo form_error('blok'); ?>
            </div>
        </div>

        <!-- Input No Kavling -->
        <div class="form-group">
            <label for="nokav" class="col-sm-2 control-label">No Kavling</label>
            <div class="col-sm-6">                                   
                <?php echo form_input([
                    'name'        => 'nokav',
                    'id'          => 'nokav',                       
                    'class'       => 'form-control input-sm',
                    'placeholder' => 'No Kavling',
                    'maxlength'   => '10'
                ]); ?>
                <?php echo form_error('nokav'); ?>
            </div>
        </div>

    </div>
    <div class="panel-footer">   
        <div class="row"> 
            <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
                <a href="<?php echo site_url('anggota'); ?>" class="btn btn-warning">
                    <i class="fa fa-chevron-left"></i> Kembali
                </a> 
                <button type="submit" class="btn btn-primary" name="post">
                    <i class="fa fa-search"></i> Cari 
                </button>                  
            </div>
        </div>
    </div>     
</div>
<?php echo form_close(); ?>
