<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<?php echo form_open(site_url('aproval_penagihan/querytagihan'), 'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>
<div class="panel panel-default">
    <div class="panel-heading"><i class="glyphicon glyphicon-signal"></i> Pencarian</div>
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
    <div class="panel-footer text-center">   
        <button type="submit" class="btn btn-primary" name="post">
            <i class="fa fa-search"></i> Cari 
        </button>                  
    </div>     
</div>
<?php echo form_close(); ?>
<br>


<!-- Form Hasil Pencarian -->
<?php if (!empty($default['idipkl'])): ?>
<?php echo form_open(site_url($action), 'role="form" class="form-horizontal" id="form_penagihan" parsley-validate'); ?>

<!-- Informasi Pelanggan -->
<?php
$fields = [
    'idipkl' => 'ID IPKL',
    'nama' => 'Nama Customer',
    'blok' => 'Blok',
    'nokav' => 'No. Kavling',
    'namacluster' => 'Cluster'
];
foreach ($fields as $field => $label): ?>
    <div class="form-group">
        <label for="<?= $field ?>" class="col-sm-2 control-label"><?= $label ?></label>
        <div class="col-sm-6">
            <?php echo form_input([
                'name' => $field,
                'id' => $field,
                'class' => 'form-control input-sm',
                'placeholder' => $label,
                'readonly' => 'readonly'
            ], set_value($field, isset($default[$field]) ? $default[$field] : '')); ?>
        </div>
    </div>
<?php endforeach; ?>

<!-- Daftar Tagihan -->
<div class="panel-body">
    <div class="page-header">
        <h3 class="text-center"><b>Tagihan</b></h3>
    </div>

    <?php if (!empty($tagihans)): ?>
        <table class="table table-bordered table-hover table-condensed">
            <thead class="text-center">
                <tr>
                    <th>ID TAGIHAN</th>
                    <th>TAHUN</th>
                    <th>BULAN</th>
                    <th>JUMLAH</th>
                    <th>DENDA</th>
                    <th>TOTAL</th>
                    <th>STATUS TAGIHAN</th>
                    <th>APPROVE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tagihans as $tagihan): ?>
                    <tr>
                        <td><?php echo $tagihan['idtagihan']; ?></td>
                        <td><?php echo $tagihan['tahun']; ?></td>
                        <td><?php echo $tagihan['bulan']; ?></td>
                        <td><?php echo $tagihan['tagihan']; ?></td>
                        <td><?php echo $tagihan['denda']; ?></td>
                        <td><?php echo $tagihan['Jumlah']; ?></td>
                        <td class="text-center"><?php echo $tagihan['namastatustagihan']; ?></td>
                        <td class="text-center">
                            <?php echo anchor(
                                site_url('aproval_penagihan/tampilaprove/' . $tagihan['idtagihan']),
                                '<i class="fa fa-eye"></i>',
                                'class="btn btn-sm btn-info" title="Detail"'
                            ); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (!empty($totalnya)): ?>
            <div><strong>Total Tagihan:</strong> <?php echo $totalnya['total']; ?></div>
        <?php endif; ?>
    <?php else: ?>
        <?php echo notify('Data Tagihan tidak ada', 'Info'); ?>
    <?php endif; ?>
</div>

<!-- Tombol Kembali -->
<div class="panel-footer">
    <div class="row">
        <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">
            <a href="<?php echo site_url('aproval_penagihan'); ?>" class="btn btn-warning">
                <i class="fa fa-chevron-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<?php echo form_close(); ?>
<?php else: ?>
    <?php echo notify('Data Customer tidak ada', 'Info'); ?>
<?php endif; ?>

<!-- Optional Cetak Script -->
<script>
function cetak() {
    window.open('bayartagihan', '_blank');
}
</script>
