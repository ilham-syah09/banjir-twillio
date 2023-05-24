<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Monitoring Air</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= base_url(); ?>assets/frontend/img/favicon.png" rel="icon">
    <link href="<?= base_url(); ?>assets/frontend/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Poppins:300,400,500,700" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= base_url(); ?>assets/frontend/vendor/aos/aos.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/frontend/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/frontend/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/frontend/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/frontend/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= base_url(); ?>assets/frontend/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Regna
  * Updated: Mar 10 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/regna-bootstrap-onepage-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top d-flex align-items-center header-transparent">
        <div class="container d-flex justify-content-between align-items-center">

            <div id="logo">
                <!-- <a href="index.html"><img src="<?= base_url(); ?>assets/frontend/img/logo.png" alt=""></a> -->
                <!-- Uncomment below if you prefer to use a text logo -->
                <h1><a href="index.html"></a></h1>
            </div>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                    <li><a class="nav-link scrollto" href="#about">Informasi</a></li>
                    <li><a class="nav-link scrollto" href="<?= base_url('auth'); ?>">Login</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
        </div>
    </header><!-- End Header -->

    <!-- ======= Hero Section ======= -->
    <section id="hero">
        <div class="hero-container" data-aos="zoom-in" data-aos-delay="100">
            <h1>Sistem Monitoring</h1>
            <h2>Ketinggian Air Sungai</h2>
            <a href="#about" class="btn-get-started">Informasi</a>
        </div>
    </section><!-- End Hero Section -->

    <main id="main">

        <!-- ======= Services Section ======= -->
        <section id="about">
            <div class="container" data-aos="fade-up">
                <div class="section-header mb-5">
                    <h3 class="section-title">Informasi Ketinggian</h3>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6" data-aos="zoom-in">
                        <div class="box text-center">
                            <h4 class="title"><a href="">Ketinggian Air</a></h4>
                            <h1 id="ketinggian"></h1>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="zoom-in">
                        <div class="box text-center">
                            <h4 class="title"><a href="">Status</a></h4>
                            <h1 id="status"></h1>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="zoom-in">
                        <div class="box text-center">
                            <h4 class="title"><a href="">Kecepatan Debit Air</a></h4>
                            <h1 id="debit"></h1>
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

            </div>
        </section><!-- End Services Section -->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">

            </div>
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong>Sistem Monitoring Ketinggian Air Sungai</strong>
            </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <!-- hightchart -->
    <script src="<?= base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/highcharts/highcharts.js"></script>
    <script src="<?php echo base_url() ?>assets/highcharts/exporting.js"></script>
    <script src="<?php echo base_url() ?>assets/highcharts/export-data.js"></script>
    <script src="<?php echo base_url() ?>assets/highcharts/accessibility.js"></script>

    <script src="<?= base_url(); ?>assets/frontend/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="<?= base_url(); ?>assets/frontend/vendor/aos/aos.js"></script>
    <script src="<?= base_url(); ?>assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets/frontend/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="<?= base_url(); ?>assets/frontend/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="<?= base_url(); ?>assets/frontend/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets/frontend/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="<?= base_url(); ?>assets/frontend/js/main.js"></script>

    <script>
        var chartKetinggian;
        var total = 0;

        function getGrafik() {
            $.ajax({
                url: '<?php echo base_url('home/get_grafik') ?>',
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
                url: "<?= base_url('home/get_realtime'); ?>",
                dataType: "json",
                success: function(response) {
                    $('#ketinggian').text(response.data.ketinggian + ' cm');
                    $('#status').text(response.data.status);
                    $('#debit').text(response.data.debit + ' m3/s');

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

</body>

</html>