<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Ketinggian Air</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="ketinggian"></div>
                    </div>
                    <div class="col-auto">
                        <!-- <i class="fas fa-user fa-2x text-gray-300"></i> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Status</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="status"></div>
                    </div>
                    <div class="col-auto">
                        <!-- <i class="fas fa-user fa-2x text-gray-300"></i> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Debit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="debit"></div>
                    </div>
                    <div class="col-auto">
                        <!-- <i class="fas fa-user fa-2x text-gray-300"></i> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Waktu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="waktu"></div>
                    </div>
                    <div class="col-auto">
                        <!-- <i class="fas fa-user fa-2x text-gray-300"></i> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-body">
                <div id="chart-Ketinggian"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url() ?>assets/highcharts/highcharts.js"></script>
<script src="<?php echo base_url() ?>assets/highcharts/exporting.js"></script>
<script src="<?php echo base_url() ?>assets/highcharts/export-data.js"></script>
<script src="<?php echo base_url() ?>assets/highcharts/accessibility.js"></script>

<script>
    var chartKetinggian;
    var total = 0;

    function getGrafik() {
        $.ajax({
            url: '<?php echo base_url('dashboard/get_grafik') ?>',
            dataType: 'json',
            success: function(result) {
                if (result.length > total) {
                    total = result.length;
                    var i;
                    var ketinggian = [];
                    var date = [];

                    chartKetinggian.series[0].update({
                        color: "#ff0000"
                    });

                    for (i = 0; i < result.length; i++) {
                        ketinggian[i] = Number(result[i].ketinggian);

                        date[i] = result[i].date;

                        chartKetinggian.series[0].setData(ketinggian);

                        chartKetinggian.xAxis[0].setCategories(date);
                    }
                } else if (result.length <= total) {
                    var i;
                    var ketinggian = [];
                    var date = [];

                    for (i = 0; i < result.length; i++) {
                        ketinggian[i] = Number(result[i].ketinggian);

                        date[i] = result[i].date;

                        chartKetinggian.series[0].setData(ketinggian);

                        chartKetinggian.xAxis[0].setCategories(date);
                    }

                }

                setTimeout(getGrafik, 30000);
            }
        });
    }

    function realtime() {
        $.ajax({
            url: "<?= base_url('dashboard/get_realtime'); ?>",
            dataType: "json",
            success: function(response) {
                $('#ketinggian').text(response.data.ketinggian + ' cm');
                $('#status').text(response.data.status);
                $('#debit').text(response.data.debit + ' m3/s');
                $('#waktu').text(response.data.date);

                setTimeout(realtime, 2000)
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {

        realtime();

        chartKetinggian = Highcharts.chart('chart-Ketinggian', {
            chart: {
                type: 'line',
                events: {
                    load: getGrafik
                }
            },
            title: {
                text: 'Grafik Ketinggian Air'
            },
            yAxis: {
                title: {
                    text: 'Ketinggian'
                }
            },
            xAxis: {

            },
            series: [{
                name: "Ketinggian Air"
            }]
        });
    })
</script>