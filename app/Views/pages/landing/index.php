
<!DOCTYPE html>
<html lang="en">

<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Primary Meta Tags -->
<title>WEBGIS FLLAJ | DINAS PERHUBUNGAN PROVINSI NTB - <?= $title ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="title" content="WEBGIS FLLAJ DISHUB PROVINSI NTB">
<meta name="author" content="Dinas Perhubungan Provinsi NTB">
<meta name="description" content="Webgis Fasilitas Keselamatan Jalan Dinas Perhubungan Provinsi NTB 2025">
<meta name="keywords" content="dishub, fllaj, webgis, provinsi ntb" />
<link rel="manifest" href="">
<meta name="theme-color" content="#0d6efd">
<link rel="apple-touch-icon" href="">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">


<!-- Jquery -->
<script src="<?= base_url('assets/template') ?>/js/jquery.min.js"></script>

<!-- Sweet Alert -->
<link type="text/css" href="<?= base_url('assets/template') ?>/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

<!-- Notyf -->
<link type="text/css" href="<?= base_url('assets/template') ?>/notyf/notyf.min.css" rel="stylesheet">

<!-- Volt CSS -->
<link type="text/css" href="<?= base_url('assets/template') ?>/css/volt.css" rel="stylesheet">

<!-- FontAwesome -->
<link rel="text/css" href="<?= base_url('assets/template') ?>/fontawesome/css/all.min.css">
<script src="<?= base_url('assets/template') ?>/fontawesome/js/all.min.js"></script>

</head>

<body>
    <style>
        .custom-navbar {
            background-color: #1F2937 !important;
        }

        .custom-navbar .navbar-brand,
        .custom-navbar .nav-link,
        .custom-navbar .dropdown-toggle {
            color: #FFFFFF !important;
        }

        .custom-navbar .nav-link:hover,
        .custom-navbar .navbar-brand:hover {
            color: #d1d5db !important;
        }

        .custom-navbar .navbar-toggler {
            border-color: #FFFFFF;
        }

        .custom-navbar .navbar-toggler-icon {
            filter: invert(1);
        }

        .custom-navbar .dropdown-menu {
            background-color: #1F2937;
            border: 1px solid #374151;
        }

        .custom-navbar .dropdown-item {
            color: #FFFFFF;
        }

        .custom-navbar .dropdown-item:hover {
            background-color: #374151;
            color: #FFFFFF;
        }

        .custom-navbar .nav-link.active {
            color: #FFFFFF !important;
            font-weight: bold;
        }

        /* Pastikan navbar full width */
        .navbar {
            padding-left: 0;
            padding-right: 0;
        }

        section {
            width: 100%;
        }
    </style>
    <main>
        <section class="d-flex justify-content-center">
            <nav class="navbar custom-navbar navbar-expand-lg w-100">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Navbar</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Features</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Pricing</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Dropdown link
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </section>
        <section class="vh-lg-100 mt-lg-0 bg-soft mb-5 px-3">
            <div class="row mt-3">
                <div class="col-md-3 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-list"></i> Filter Data</h5>
                        </div>
                        <div class="card-body">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-map-marked-alt"></i> Peta Fasilitas Keselamatan Jalan</h5>
                        </div>
                        <div class="card-body">                            
                            <div id="map" style="height: 600px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
    #map {
        border-radius: 0.375rem;
        z-index: 1;
    }
    
    /* Pastikan peta responsive */
    .leaflet-container {
        height: 100%;
        width: 100%;
    }
</style>
<script>
    // Inisialisasi peta dengan view center di NTB
    var map = L.map('map').setView([-8.6529, 117.3616], 9); // Koordinat tengah NTB, zoom level 9

    // Tambahkan base layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Contoh polygon sederhana untuk wilayah NTB (simplified)
    var ntbBounds = [
        [-8.1, 116.5],  // Northwest
        [-8.1, 118.5],  // Northeast
        [-9.2, 118.5],  // Southeast
        [-9.2, 116.5]   // Southwest
    ];

    // Tambahkan polygon NTB
    var ntbPolygon = L.polygon(ntbBounds, {
        color: 'blue',
        fillColor: '#3388ff',
        fillOpacity: 0.1,
        weight: 2
    }).addTo(map);

    // Tambahkan marker untuk kota utama di NTB
    var cities = [
        {name: "Mataram", coords: [-8.5833, 116.1167]},
        {name: "Bima", coords: [-8.4600, 118.7267]},
        {name: "Sumbawa Besar", coords: [-8.4932, 117.4202]},
        {name: "Praya", coords: [-8.7050, 116.2700]}
    ];

    cities.forEach(function(city) {
        L.marker(city.coords)
            .addTo(map)
            .bindPopup("<b>" + city.name + "</b><br>Kota di NTB");
    });

    // Fit bounds untuk menampilkan seluruh NTB
    map.fitBounds([
        [-8.1, 116.5],  // Northwest
        [-9.2, 118.5]   // Southeast
    ]);

    // Tambahkan scale control
    L.control.scale().addTo(map);
</script>
<!-- Core -->
<script src="<?= base_url('assets/template') ?>/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="<?= base_url('assets/template') ?>/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Vendor JS -->
<script src="<?= base_url('assets/template') ?>/onscreen/dist/on-screen.umd.min.js"></script>

<!-- Slider -->
<script src="<?= base_url('assets/template') ?>/nouislider/dist/nouislider.min.js"></script>

<!-- Smooth scroll -->
<script src="<?= base_url('assets/template') ?>/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<!-- Charts -->
<script src="<?= base_url('assets/template') ?>/chartist/dist/chartist.min.js"></script>
<script src="<?= base_url('assets/template') ?>/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>

<!-- Datepicker -->
<script src="<?= base_url('assets/template') ?>/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Sweet Alerts 2 -->
<script src="<?= base_url('assets/template') ?>/sweetalert2/dist/sweetalert2.all.min.js"></script>

<!-- Moment JS -->
<script src="<?= base_url('assets/template') ?>/js/moment.min.js"></script>

<!-- Vanilla JS Datepicker -->
<script src="<?= base_url('assets/template') ?>/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Notyf -->
<script src="<?= base_url('assets/template') ?>/notyf/notyf.min.js"></script>

<!-- Simplebar -->
<script src="<?= base_url('assets/template') ?>/simplebar/dist/simplebar.min.js"></script>

<!-- Volt JS -->
<script src="<?= base_url('assets/template') ?>/assets/js/volt.js"></script>
    
</body>

</html>
