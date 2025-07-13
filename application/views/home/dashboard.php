<?php
$this->load->model('Master_model');

// === DATA CHART BULANAN === //
$tahun_ini = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$dataTagihan = $this->Master_model->get_tagihan_per_bulan($tahun_ini);

// Nama-nama bulan
$namaBulan = [
    '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
    '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu',
    '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
];

// Siapkan data chart bulanan
$chartDataBulan = [["Bulan", "Lunas", "Belum Lunas"]];
foreach ($dataTagihan as $row) {
    $bulan = str_pad($row['bulan'], 2, '0', STR_PAD_LEFT);
    $labelBulan = (isset($namaBulan[$bulan]) ? $namaBulan[$bulan] : $bulan) . ' ' . $tahun_ini;
    $chartDataBulan[] = [$labelBulan, (int)$row['jumlah_lunas'], (int)$row['jumlah_belum_lunas']];
}

// === DATA CHART TAHUNAN === //
$tahun_awal = isset($_GET['tahun_awal']) ? (int)$_GET['tahun_awal'] : date('Y') - 5;
$tahun_akhir = isset($_GET['tahun_akhir']) ? (int)$_GET['tahun_akhir'] : date('Y');

$dataTahunan = $this->Master_model->get_tagihan_per_tahun_range($tahun_awal, $tahun_akhir);

// Siapkan data chart tahunan
$chartDataTahun = [["Tahun", "Lunas", "Belum Lunas"]];
foreach ($dataTahunan as $row) {
    $chartDataTahun[] = [$row->tahun, (int)$row->jumlah_lunas, (int)$row->jumlah_belum_lunas];
}
?>

<!-- Load Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        // Chart per Bulan
        var dataBulan = google.visualization.arrayToDataTable(<?php echo json_encode($chartDataBulan); ?>);
        var optionsBulan = {
            chart: {
                title: 'Statistik Tagihan Tahun <?php echo $tahun_ini; ?>',
                subtitle: 'Lunas vs Belum Lunas per Bulan',
            },
            height: 400,
            colors: ['#1b9e77', '#d95f02']
        };
        var chartBulan = new google.charts.Bar(document.getElementById('chart_per_bulan'));
        chartBulan.draw(dataBulan, google.charts.Bar.convertOptions(optionsBulan));

        // Chart per Tahun
        var dataTahun = google.visualization.arrayToDataTable(<?php echo json_encode($chartDataTahun); ?>);
        var optionsTahun = {
            chart: {
                title: 'Statistik Tagihan Tahunan',
                subtitle: 'Dari Tahun <?php echo $tahun_awal; ?> sampai <?php echo $tahun_akhir; ?>',
            },
            height: 400,
            colors: ['#007bff', '#ffc107']
        };
        var chartTahun = new google.charts.Bar(document.getElementById('chart_per_tahun'));
        chartTahun.draw(dataTahun, google.charts.Bar.convertOptions(optionsTahun));
    }
</script>

<!-- Panel-panel lain tetap sama -->
<div class="row">
    <!-- Panel: Data Pelanggan -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3 text-center">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="small">Pelanggan Total</div>
                        <div class="huge"><?php echo $total; ?></div>
                        <div class="small">Bangunan: <?php echo $totalbangunan; ?></div>
                        <div class="small">Kavling: <?php echo $totalkavling; ?></div>
                    </div>
                </div>
            </div>
            <a href="<?php echo site_url('master_pelanggan'); ?>">
                <div class="panel-footer">
                    <span class="pull-left">Lihat Detail</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <!-- Panel: Piutang -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3 text-center">
                        <i class="fa fa-money fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="small">Total Piutang</div>
                        <div class="huge"><?php echo $total; ?></div>
                        <div class="small">Bangunan: <?php echo $totalbangunan; ?></div>
                        <div class="small">Kavling: <?php echo $totalkavling; ?></div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">Lihat Detail</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <!-- Panel: Efisiensi Penagihan -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3 text-center">
                        <i class="fa fa-line-chart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="small">Efisiensi Bulan Ini</div>
                        <div class="huge"><?php echo $total; ?></div>
                        <div class="small">Efektifitas Bulan Lalu</div>
                        <div class="huge"><?php echo $totalbangunan; ?></div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">Lihat Detail</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <!-- Panel: Pendapatan -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3 text-center">
                        <i class="fa fa-bar-chart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="small">Pendapatan Bulan Ini</div>
                        <div class="huge"><?php echo $total; ?></div>
                        <div class="small">Pendapatan Bulan Lalu</div>
                        <div class="huge"><?php echo $totalbangunan; ?></div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">Lihat Detail</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>


<!-- === FORM PILIH TAHUN PER BULAN === -->
<form method="GET" class="mb-4">
    <label for="tahun">Laporan Bulanan - Pilih Tahun:</label>
    <select name="tahun" id="tahun" class="form-control" style="width: 200px;" onchange="this.form.submit()">
        <?php
        $tahunSekarang = date('Y');
        for ($th = $tahunSekarang; $th >= $tahunSekarang - 5; $th--) {
            echo '<option value="' . $th . '"' . ($th == $tahun_ini ? ' selected' : '') . '>' . $th . '</option>';
        }
        ?>
    </select>
</form>
<div id="chart_per_bulan" style="width: 100%; height: 400px;"></div>

<!-- === FORM TAHUN AWAL-AKHIR UNTUK TAHUNAN === -->
<form method="GET" class="mb-4">
    <div class="form-inline">
        <label for="tahun_awal">Laporan Tahunan: </label>&nbsp;
        <input type="number" name="tahun_awal" value="<?php echo $tahun_awal; ?>" class="form-control" style="width: 100px;" required /> &nbsp;s/d&nbsp;
        <input type="number" name="tahun_akhir" value="<?php echo $tahun_akhir; ?>" class="form-control" style="width: 100px;" required />
        &nbsp;<button type="submit" class="btn btn-primary">Tampilkan</button>
    </div>
</form>
<div id="chart_per_tahun" style="width: 100%; height: 400px;"></div>>
</form>