<?php echo form_open(site_url($action), 'class="form-horizontal" id="form_transaksi" data-parsley-validate'); ?>               

<div class="panel panel-default">
    <div class="panel-body">

        <!-- Per Tahun -->
        <div class="form-group">
            <label for="cbotahun" class="col-sm-2 control-label">Per Tahun</label>
            <div class="col-sm-6">
                <select name="cbotahun" id="cbotahun" class="form-control input-sm">
                    <?php
                    $tahun_sekarang = date('Y');
                    for ($i = $tahun_sekarang; $i >= $tahun_sekarang - 10; $i--) {
                        $selected = ($i == set_value('cbotahun', $tahun_sekarang)) ? 'selected' : '';
                        echo "<option value=\"$i\" $selected>$i</option>";
                    }
                    ?>
                </select>
                <?php echo form_error('cbotahun'); ?>
            </div>
        </div>

        <!-- Per Bulan -->
        <div class="form-group">
            <label for="cbobulan" class="col-sm-2 control-label">Per Bulan</label>
            <div class="col-sm-6">
                <select name="cbobulan" id="cbobulan" class="form-control input-sm">
                    <?php
                    $bulan = [
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember'
                    ];

                    $bulan_sekarang = date('m');
                    foreach ($bulan as $key => $val) {
                        $selected = ($key == set_value('cbobulan', $bulan_sekarang)) ? 'selected' : '';
                        echo "<option value=\"$key\" $selected>$val</option>";
                    }
                    ?>
                </select>
                <?php echo form_error('cbobulan'); ?>
            </div>
        </div>

        <!-- Pilihan Jenis Laporan -->
        <div class="form-group">
            <label class="col-sm-2 control-label">Pilih Laporan Bulanan</label>
            <div class="col-sm-6">
                <?php
                $laporanOptions = [
                    '1' => 'Bulan Berjalan',
                    '2' => 'Bulan Lalu',
                    '3' => 'Dua Bulan Lalu',
                    '4' => 'Lebih Dari Tiga Bulan',
                    '5' => 'Laporan Total Penerimaan',
                    '6' => 'Laporan Total Pendapatan',
                    '7' => 'Laporan Deposit Per Cluster',
                    '8' => 'Laporan Total Penerimaan Per Cluster',
                    '9' => 'Laporan Total Pendapatan Per Cluster'
                ];

                $selectedRadio = set_value('optionsRadios', '1');
                foreach ($laporanOptions as $value => $label) {
                    $checked = ($selectedRadio == $value) ? 'checked' : '';
                    echo '<div class="radio">
                            <label>
                                <input type="radio" name="optionsRadios" value="' . $value . '" ' . $checked . '> ' . $label . '
                            </label>
                          </div>';
                }
                ?>
            </div>
        </div>

        <!-- Dropdown Cluster -->
        <div class="form-group">
            <label for="cbocluster" class="col-sm-2 control-label">Cluster</label>
            <div class="col-sm-6">
                <?php
                echo form_dropdown(
                    'cbocluster',
                    $isicluster,
                    set_value('cbocluster'),
                    'class="form-control input-sm" id="cbocluster"'
                );
                ?>
                <?php echo form_error('cbocluster'); ?>
            </div>
        </div>

    </div> <!-- /panel-body -->

    <!-- Tombol Submit -->
    <div class="panel-footer">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <a href="<?php echo site_url('laporan_bulanan'); ?>" class="btn btn-default">
                    <i class="glyphicon glyphicon-chevron-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary" name="post">
                    <i class="glyphicon glyphicon-floppy-save"></i> Generate Laporan
                </button>
            </div>
        </div>
    </div>

</div> <!-- /panel -->

<?php echo form_close(); ?>
