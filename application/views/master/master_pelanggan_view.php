<?php
    echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>' : '';
    echo ! empty($message) ? '<p class="message">' . $message . '</p>' : '';
    
    $flashmessage = $this->session->flashdata('message');
    echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>' : '';
?>

<section class="panel panel-default">
    <header class="panel-heading">
    <div class="row">
        <!-- Tombol Tambah dan Cetak -->
        <div class="col-md-12 mb-2">
            <?php
                echo anchor(
                    site_url('master_pelanggan/add'),
                    '<i class="fa fa-plus"></i> Tambah',
                    'class="btn btn-success btn-sm" data-tooltip="tooltip" data-placement="top" title="Tambah Data"'
                );
                echo ' ';
                echo anchor(
                    site_url('cetak_master_pelanggan'),
                    '<i class="fa fa-print"></i> Cetak',
                    'class="btn btn-success btn-sm" data-tooltip="tooltip" data-placement="top" title="Cetak Data Master"'
                );
            ?>
        </div>

        <!-- Form Pencarian -->
        <div class="col-md-6 col-sm-12">
            <?php echo form_open(site_url('master_pelanggan/search2'), 'role="search" class="form-inline"'); ?>
                <div class="form-group mb-2 mr-2">
                    <input type="text" class="form-control input-sm" name="idipkl" placeholder="ID IPKL" value="<?php echo set_value('idipkl'); ?>">
                </div>
                <div class="form-group mb-2 mr-2">
                    <input type="text" class="form-control input-sm" name="blok" placeholder="Blok" value="<?php echo set_value('blok'); ?>">
                </div>
                <div class="form-group mb-2 mr-2">
                    <input type="text" class="form-control input-sm" name="nokav" placeholder="No Kavling" value="<?php echo set_value('nokav'); ?>">
                </div>
                <button class="btn btn-primary btn-sm mb-2" type="submit">
                    <i class="fa fa-search"></i> Cari
                </button>
                <a href="<?php echo site_url('master_pelanggan'); ?>" class="btn btn-warning btn-sm mb-2">Reset</a>
            <?php echo form_close(); ?>
        </div>
    </div>
</header>


    <div class="panel-body">
        <?php if (!empty($pelanggans)) : ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th class="header text-center">ID IPKL</th>
                        <th class="text-center">NAMA PELANGGAN</th>
                        <th class="text-center">ALAMAT</th>
                        <th class="red header text-center" width="120">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pelanggans as $pelanggan) : ?>
                        <tr>
                            <td><?php echo $pelanggan['idipkl']; ?></td>
                            <td><?php echo $pelanggan['namapelanggan']; ?></td>
                            <td>
                                <?php echo $pelanggan['namacluster']; ?>
                                <?php echo $pelanggan['blok']; ?>
                                <?php echo $pelanggan['nokav']; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    echo anchor(
                                        site_url('master_pelanggan/show/' . $pelanggan['idipkl']),
                                        '<i class="fa fa-eye"></i>',
                                        'class="btn btn-sm btn-info" data-tooltip="tooltip" data-placement="top" title="Detail"'
                                    );
                                    echo anchor(
                                        site_url('master_pelanggan/edit/' . $pelanggan['idipkl']),
                                        '<i class="fa fa-edit"></i>',
                                        'class="btn btn-sm btn-success" data-tooltip="tooltip" data-placement="top" title="Edit"'
                                    );
                                    echo anchor(
                                        site_url('master_pelanggan/destroy/' . $pelanggan['idipkl']),
                                        '<i class="fa fa-trash"></i>',
                                        'onclick="return confirm(\'Anda yakin..???\');" class="btn btn-sm btn-danger" data-tooltip="tooltip" data-placement="top" title="Hapus"'
                                    );
                                ?>
                            </td>
                        </tr>
                    <?php endforeach ; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-info">Data pelanggan tidak ditemukan.</div>
        <?php endif; ?>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-md-9">
                <?php echo $pagination; ?>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-4">
                Pelanggan 
                <span class="label label-info"><?php echo $total; ?></span>
                Bangunan 
                <span class="label label-info"><?php echo $totalbangunan; ?></span>
                Kavling 
                <span class="label label-info"><?php echo $totalkavling; ?></span>
            </div> 
        </div>
    </div>
</section>
