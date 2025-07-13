<?php
$this->load->model('Master_model');
$tahun_ini = date('Y'); // Mendapatkan tahun saat ini, misalnya 2025
$dataTagihan = $this->Master_model->get_tagihan_per_bulan($tahun_ini);

// Daftar nama bulan
$namaBulan = [
    '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
    '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu',
    '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
];

// Siapkan data chart untuk JavaScript
$chartData = [["Bulan", "Lunas", "Belum Lunas"]];
foreach ($dataTagihan as $row) {
    $bulan = str_pad($row['bulan'], 2, '0', STR_PAD_LEFT);
    $labelBulan = (isset($namaBulan[$bulan]) ? $namaBulan[$bulan] : $bulan) . ' ' . $tahun_ini;

    $chartData[] = [$labelBulan, (int)$row['jumlah_lunas'], (int)$row['jumlah_belum_lunas']];
}
?>

<!-- Load Google Chart -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    google.charts.load('current', {'packages': ['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartData); ?>);

        var options = {
            chart: {
                title: 'Statistik Tagihan Tahun <?php echo $tahun_ini; ?>',
                subtitle: 'Lunas vs Belum Lunas per Bulan',
            },
            bars: 'vertical',
            height: 500,
            colors: ['#1b9e77', '#d95f02']
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>

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

<!-- Tempat Chart -->
<div id="columnchart_material" style="width: 100%; height: 500px;"></div>
