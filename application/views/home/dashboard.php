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
