<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<?php echo form_open(site_url('pelunasan_mundur/querytagihan'), 'role="form" class="form-horizontal" id="form_transaksi" parsley-validate'); ?>
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

<?php echo form_open(site_url($action), 'role="form" class="form-horizontal" id="form_penagihan" parsley-validate'); ?>

<?php if (!empty($default['idipkl'])) : ?>
    <!-- Customer Detail -->
    <?php 
    $fields = [
        'idipkl'      => 'ID IPKL',
        'nama'        => 'Nama Customer',
        'blok'        => 'Blok',
        'nokav'       => 'No. Kavling',
        'namacluster' => 'Cluster'
    ];

    foreach ($fields as $field => $label) :
    ?>
    <div class="form-group">
        <label for="<?= $field ?>" class="col-sm-2 control-label"><?= $label ?></label>
        <div class="col-sm-6">
            <?php echo form_input([
                'name'        => $field,
                'id'          => $field,
                'class'       => 'form-control input-sm',
                'placeholder' => $label,
                'readonly'    => 'readonly',
                'maxlength'   => '25'
            ], set_value($field, isset($default[$field]) ? $default[$field] : '')); ?>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Tagihan -->
    <div class="panel panel-default">
        <div class="panel-heading text-center"><b>Tagihan</b></div>
        <div class="panel-body">
            <?php if (!empty($tagihans)) : ?>
            <table class="table table-bordered table-condensed table-hover">
                <thead class="text-center">
                    <tr>
                        <th>ID TAGIHAN</th>
                        <th>TAHUN</th>
                        <th>BULAN</th>
                        <th>JUMLAH</th>
                        <th>DENDA</th>
                        <th>TOTAL</th>
                        <th>STATUS TAGIHAN</th>
                        <th>PELUNASAN</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($tagihans as $tagihan): ?>
                    <tr>
                        <td><?= $tagihan['idtagihan']; ?></td>
                        <td><?= $tagihan['tahun']; ?></td>
                        <td><?= $tagihan['bulan']; ?></td>
                        <td><?= $tagihan['tagihan']; ?></td>
                        <td><?= $tagihan['denda']; ?></td>
                        <td><?= $tagihan['Jumlah']; ?></td>
                        <td class="text-center"><?= $tagihan['namastatustagihan']; ?></td>
                        <td class="text-center">
                            <?= anchor(
                                site_url('pelunasan_mundur/tampillunasmundur/' . $tagihan['idtagihan']),
                                '<i class="fa fa-eye"></i>',
                                'class="btn btn-sm btn-info" title="Pelunasan"'
                            ); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (!empty($totalnya)) : ?>
                <strong>Total Tagihan: <?= $totalnya['total']; ?></strong>
            <?php endif; ?>
            <?php else : ?>
                <?= notify('Data Tagihan tidak ada', 'Info'); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="panel-footer text-right">
        <a href="<?= site_url('pelunasan_mundur'); ?>" class="btn btn-warning">
            <i class="fa fa-chevron-left"></i> Kembali
        </a>
    </div>
<?php else : ?>
    <?= notify('Data Customer tidak ada', 'Info'); ?>
<?php endif; ?>

<?php echo form_close(); ?>

<script>
function cetak() {
    window.open('bayartagihan', '_blank');
}
</script>
