
<!DOCTYPE html>
<html lang="en">

<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Primary Meta Tags -->
<title>WEBGIS FLLAJ | DINAS PERHUBUNGAN PROVINSI NTB</title>
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

<!-- Highcharts -->
<script src="<?= base_url('assets/template') ?>/highcharts/highcharts.js"></script>

<!-- Datatables -->
<link rel="text/css" href="<?= base_url('assets/template') ?>/datatables/datatables.min.css">
<script src="<?= base_url('assets/template') ?>/datatables/datatables.min.js"></script>
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

        /* PERBAIKAN: CSS untuk mengatasi tumpang tindih */
        main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        section.bg-soft {
            flex: 1;
            padding-bottom: 2rem;
        }

        footer {
            margin-top: auto;
            position: relative;
            z-index: 10;
        }

        /* Perbaikan untuk DataTables */
        .dataTables_wrapper {
            position: relative;
            clear: both;
            margin-bottom: 2rem;
        }

        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_info {
            padding-top: 1rem;
        }

        .dataTables_paginate {
            padding-top: 1rem;
        }

        /* Pastikan card tabel memiliki margin bottom yang cukup */
        .card.mt-3.mb-5 {
            margin-bottom: 3rem !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            section.bg-soft {
                padding-bottom: 3rem;
            }
            
            .card.mt-3.mb-5 {
                margin-bottom: 2rem !important;
            }
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
                            <form id="filterForm">
                                <div class="form-group">
                                    <label for="kategoriFasilitas">Kategori Fasilitas Jalan</label>
                                    <select name="kategoriFasilitas" id="kategoriFasilitas" class="form-control">
                                        <!-- loaded via ajax -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kondisi">Kondisi</label>
                                    <select name="jenisFasilitas" id="jenisFasilitas" class="form-control">
                                        <option value="">Semua Kondisi</option>
                                        <option value="baik">Baik</option>
                                        <option value="sedang">Rusak Sedang</option>
                                        <option value="berat">Rusak Berat</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tahun">Tahun Survey</label>
                                    <select name="tahun" id="tahun" class="form-control">
                                        <option value="2025" selected>2025</option>
                                        <option value="2026">2026</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5><i class="fa-solid fa-chart-pie"></i> Presentase Kondisi Fasilitas</h5>
                        </div>
                        <div class="card-body">
                            <div>
                                <div class="form-group">
                                    <label for="chartFasilitas">Kategori Fasilitas</label>
                                    <select name="chartFasilitas" id="chartFasilitas" class="form-control">
                                        <!-- loaded via ajax -->
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3" id="kondisiChartContainer" style="height: 300px; width: 100%;"></div>
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
                    <div class="card mt-3 mb-5">
                        <div class="card-header">
                            <h5><i class="fas fa-table"></i> Tabel Fasilitas</h5>
                        </div>
                        <div class="card-body">
                            <table id="tableKondisi" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Kode Fasilitas</th>
                                        <th>Nama Fasilitas</th>
                                        <th>Koordinat</th>
                                        <th>
                                            <select name="kondisi" id="kondisi" class="form-control">
                                                <option value="">Semua Kondisi</option>
                                                <option value="baik">Baik</option>
                                                <option value="rusak_ringan">Rusak Ringan</option>
                                                <option value="rusak_berat">Rusak Berat</option>
                                            </select>
                                        </th>
                                        <th>Tahun Survey</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- footer -->
        <footer class="bg-white rounded shadow p-5 mb-4 mt-4 mx-3">
            <div class="row">
                <div class="col-12 col-md-4 col-xl-6 mb-4 mb-md-0">
                    <p class="mb-0 text-center text-lg-start">© <span class="current-year"></span> <span class="text-primary">Made with 💓 by </span> <a class="text-primary fw-normal" href="mailto:ekasatriabahari@outlook.com" target="_blank">Eka Satria Bahari</a></p>
                </div>
            </div>
        </footer>
    </main>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="<?= base_url('assets/template/') ?>leafletjs/leaflet.shpfile.js"></script>
    <script src="<?= base_url('assets/template/') ?>leafletjs/shp.js"></script>
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

        #kondisiChartContainer {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .highcharts-legend-item text {
            font-size: 14px !important;
            font-weight: 600;
        }

        .highcharts-data-label {
            font-weight: bold !important;
        }
    </style>

<script>
    $(document).ready(function() {
        var tableKondisi = $('#tableKondisi').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url("api/kondisi-fasilitas") ?>',
                type: 'GET',
            },
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: null, render: function(data, type, row) {
                    return `<span class="badge rounded-pill bg-primary">${row.kode_fasilitas} - ${row.jenis}</span>`;
                }},
                { data: 'nama_fasilitas'},
                { data: null, render: function(data, type, row) {
                    return `lat: ${row.latitude} <br> long: ${row.longitude}`;
                }},
                { data: null, render: function(data, type, row) {
                    return row.kondisi =='baik' ? `<span class="badge rounded-pill bg-success">${row.kondisi}</span>` : row.kondisi == 'rusak_ringan' ? `<span class="badge rounded-pill bg-warning">${row.kondisi}</span>` : `<span class="badge rounded-pill bg-danger">${row.kondisi}</span>`;
                }},
                { data: null, render: function(data, type, row) {
                    return row.tahun_survey;
                }},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail" onclick="viewDetail('${row.id}')"><i class="fas fa-pencil-alt"></i></button>`;
                    }
                }
            ],  
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + api.context[0]._iDisplayStart;
                });
                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            initComplete: function() {
                // Initialize tooltips setelah table loaded
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        $('#kondisi').on('change', function () {
            let val = $(this).val();
            $('#tableKondisi').DataTable().column(4).search(val).draw(); // Array Kolom ke-4 = "Kondisi"
        });
    });
    function initKondisiChart(data) {
        Highcharts.chart('kondisiChartContainer', {
            chart: {
                type: 'pie',
                backgroundColor: '#f8f9fa',
                borderRadius: 10
            },
            title: {
                text: 'Kondisi Rambu Lalu Lintas',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            },
            // subtitle: {
            //     text: 'Data kondisi rambu berdasarkan tingkat kerusakan',
            //     style: {
            //         color: '#666'
            //     }
            // },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false,
                        format: '<b>{point.name}</b>: {point.y}%',
                        style: {
                            fontSize: '12px'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Kondisi',
                colorByPoint: true,
                data: data
            }],
            credits: {
                enabled: false
            }
        });
    }

// Fungsi AJAX untuk mengambil data dummy
function loadKondisiData() {
    $.ajax({
        url: '/api/kondisi-rambu', // Ganti dengan endpoint API Anda
        method: 'GET',
        dataType: 'json',
        beforeSend: function() {
            // Tampilkan loading spinner
            $('#kondisiChartContainer').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        success: function(response) {
            // Jika menggunakan API real
            // initKondisiChart(response.data);
            
            // Untuk demo, gunakan data dummy
            var dummyData = [
                {
                    name: 'Baik',
                    y: 65,
                    color: '#28a745'
                },
                {
                    name: 'Sedang',
                    y: 25,
                    color: '#ffc107'
                },
                {
                    name: 'Rusak Berat',
                    y: 10,
                    color: '#dc3545'
                }
            ];
            initKondisiChart(dummyData);
        },
        error: function(xhr, status, error) {
            console.error('Error loading data:', error);
            
            // Fallback ke data dummy jika API error
            var fallbackData = [
                {
                    name: 'Baik',
                    y: 60,
                    color: '#28a745'
                },
                {
                    name: 'Sedang',
                    y: 30,
                    color: '#ffc107'
                },
                {
                    name: 'Rusak Berat',
                    y: 10,
                    color: '#dc3545'
                }
            ];
            initKondisiChart(fallbackData);
            
            // Tampilkan pesan error
            $('#kondisiChartContainer').append('<div class="alert alert-warning mt-2">Data menggunakan sample karena koneksi terputus</div>');
        }
    });
}
$(document).ready(function() {
    loadKondisiData();
});
</script>
<script>
    // Inisialisasi peta
    var map = L.map('map').setView([-8.6529, 117.3616], 8);

    // --- Base Layer ---
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // --- Shapefile: Jalan Provinsi NTB ---
    var shpJalan = new L.Shapefile("<?= base_url('assets/shp/jalan_provinsi_ntb.zip') ?>", {
        style: { color: '#e53935', weight: 3, opacity: 0.8 },
        onEachFeature: function (feature, layer) {
            if (feature.properties) {
                let props = Object.keys(feature.properties)
                    .map(k => k + ": " + feature.properties[k])
                    .join("<br />");
                layer.bindPopup(props, { maxHeight: 200 });
            }
        }
    });

    // --- Scale control ---
    L.control.scale().addTo(map);

    // === Custom Layer Control ===
    var layerControlDiv = L.DomUtil.create('div', 'leaflet-control-layers leaflet-control');
    layerControlDiv.style.background = '#fff';
    layerControlDiv.style.padding = '6px';
    layerControlDiv.style.fontSize = '14px';
    layerControlDiv.style.maxHeight = '500px';
    layerControlDiv.style.overflowY = 'auto';

    layerControlDiv.innerHTML = `
  <div class="accordion accordion-flush small" id="accordionLayers" style="font-size: 12px;">
    <!-- Base Maps -->
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed py-1 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBase">
          Base Maps
        </button>
      </h2>
      <div id="collapseBase" class="accordion-collapse collapse show">
        <div class="accordion-body py-2 px-2">
          <label class="d-block mb-1"><input type="radio" name="basemap" value="osm" checked> OpenStreetMap</label>
        </div>
      </div>
    </div>

    <!-- Shapefile Jalan -->
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed py-1 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJalan">
          Shapefile Jalan
        </button>
      </h2>
      <div id="collapseJalan" class="accordion-collapse collapse">
        <div class="accordion-body py-2 px-2">
          <label class="d-block mb-1"><input type="checkbox" class="overlay" value="jalan"> Jalan Provinsi NTB</label>
        </div>
      </div>
    </div>
    
    <!-- Wilayah Administrasi -->
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed py-1 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWilayah">
          Wilayah Administrasi
        </button>
      </h2>
      <div id="collapseWilayah" class="accordion-collapse collapse">
        <div class="accordion-body py-2 px-2" id="wilayah-groups"></div>
      </div>
    </div>

    <!-- Data Fasilitas -->
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed py-1 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFasilitas">
          Data Fasilitas
        </button>
      </h2>
      <div id="collapseFasilitas" class="accordion-collapse collapse">
        <div class="accordion-body py-2 px-2" id="fasilitas-groups"></div>
      </div>
    </div>
  </div>
  <style>
    /* Paksa icon collapse agar tampil */
    .accordion-button::after {
    flex-shrink: 0;
    width: 1rem;
    height: 1rem;
    margin-left: auto;
    content: "";
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23333'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-size: 1rem;
    transition: transform .2s ease-in-out;
    }

    .accordion-button:not(.collapsed)::after {
    transform: rotate(-180deg);
    }

  </style>
`;

    var customControl = L.control({ position: 'topright' });
    customControl.onAdd = function() { return layerControlDiv; };
    customControl.addTo(map);

    // === Event basemap ===
    layerControlDiv.querySelectorAll('input[name="basemap"]').forEach(input => {
        input.addEventListener('change', function() {
            map.eachLayer(function(layer) {
                if (layer !== shpJalan) map.removeLayer(layer);
            });
            if (this.value === 'osm') osm.addTo(map);
        });
    });

    // === Event overlay shapefile jalan ===
    layerControlDiv.querySelectorAll('.overlay').forEach(input => {
        input.addEventListener('change', function() {
            if (this.value === 'jalan') {
                if (this.checked) map.addLayer(shpJalan);
                else map.removeLayer(shpJalan);
            }
        });
    });

    // === Wilayah Administrasi ===
    const colors = [
        '#e41a1c','#377eb8','#4daf4a','#984ea3','#ff7f00',
        '#ffff33','#a65628','#f781bf','#999999','#66c2a5'
    ];
    const wilayahFiles = [
        { file: "1_kab_bima.zip", nama: "Kabupaten Bima" },
        { file: "2_kab_dompu.zip", nama: "Kabupaten Dompu" },
        { file: "3_kota_bima.zip", nama: "Kota Bima" },
        { file: "4_kota_mataram.zip", nama: "Kota Mataram" },
        { file: "5_kab_lombok_barat.zip", nama: "Kabupaten Lombok Barat" },
        { file: "6_kab_lombok_tengah.zip", nama: "Kabupaten Lombok Tengah" },
        { file: "7_kab_lombok_timur.zip", nama: "Kabupaten Lombok Timur" },
        { file: "8_kab_lombok_utara.zip", nama: "Kabupaten Lombok Utara" },
        { file: "9_kab_sumbawa_barat.zip", nama: "Kabupaten Sumbawa Barat" },
        { file: "10_kab_sumbawa.zip", nama: "Kabupaten Sumbawa" },
    ];
    var wilayahLayers = {};

    const wilayahContainer = layerControlDiv.querySelector('#wilayah-groups');

    wilayahFiles.forEach((item, idx) => {
        let color = colors[idx % colors.length];
        let shpLayer = new L.Shapefile("<?= base_url('assets/shp/') ?>" + item.file, {
            style: { color: color, weight: 2, fillOpacity: 0.3 },
            onEachFeature: function(feature, layer) {
                if (feature.properties) {
                    let props = Object.keys(feature.properties)
                        .map(k => `<b>${k}</b>: ${feature.properties[k]}`)
                        .join("<br>");
                    layer.bindPopup(`<b>${item.nama}</b><br>${props}`);
                }
            }
        });
        wilayahLayers[item.nama] = shpLayer;

        // Checkbox
        let checkbox = document.createElement('label');
        checkbox.innerHTML = `<input type="checkbox" data-nama="${item.nama}"> ${item.nama}<br>`;
        wilayahContainer.appendChild(checkbox);
    });

    // Event checkbox wilayah
    wilayahContainer.querySelectorAll('input[type=checkbox]').forEach(cb => {
        cb.addEventListener('change', function() {
            let nama = this.dataset.nama;
            if (this.checked) {
                map.addLayer(wilayahLayers[nama]);
            } else {
                map.removeLayer(wilayahLayers[nama]);
            }
        });
    });

    // === Ambil Data Marker dari API ===
    var fasilitasGroups = {};
    $(() => {
        getMarkers();
    });

    function getMarkers() {
        $.ajax({
            url: "<?= site_url('api/dashboard/markers') ?>",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.data;
                const container = document.getElementById('fasilitas-groups');
                container.innerHTML = ""; // reset

                data.forEach(group => {
                    // Buat container untuk setiap kode
                    let kodeContainer = document.createElement('div');
                    kodeContainer.innerHTML = `<b>${group.kode}</b> - ${group.nama_kode}<br>`;
                    container.appendChild(kodeContainer);

                    fasilitasGroups[group.kode] = {};

                    group.jenis.forEach(j => {
                        // Buat layerGroup untuk jenis ini
                        let jenisLayer = L.layerGroup();
                        fasilitasGroups[group.kode][j.nama_jenis] = jenisLayer;

                        // Tambah checkbox untuk kontrol
                        let checkbox = document.createElement('label');
                        checkbox.innerHTML = `<input type="checkbox" data-kode="${group.kode}" data-jenis="${j.nama_jenis}"> ${j.nama_jenis}<br>`;
                        kodeContainer.appendChild(checkbox);

                        // Isi data marker
                        j.data.forEach(item => {
                            var icon = L.icon({
                                iconUrl: '<?= base_url('uploads/icons/') ?>' + j.icon,
                                iconSize: [41, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            });
                            L.marker([item.latitude, item.longitude], {icon: icon})
                                .addTo(jenisLayer)
                                .bindPopup(markerPopup({...item, jenis: j.nama_jenis}));
                        });
                    });
                });

                // Bind event ke semua checkbox
                container.querySelectorAll('input[type=checkbox]').forEach(cb => {
                    cb.addEventListener('change', function() {
                        let kode = this.dataset.kode;
                        let jenis = this.dataset.jenis;
                        if (this.checked) {
                            map.addLayer(fasilitasGroups[kode][jenis]);
                        } else {
                            map.removeLayer(fasilitasGroups[kode][jenis]);
                        }
                    });
                });
            },
            error: (err) => {
                console.log(err);
            }
        });
    }

    function markerPopup(data) {
        const fotos = JSON.parse(data.foto || '[]');
        const fotoHTML = fotos.map((foto) => `
            <img 
                src="<?= base_url() ?>/uploads/images/fasilitas/${foto}"
                alt="${data.nama_fasilitas}"
                class="img-thumbnail m-1 preview-thumb"
                style="width:100px; height:100px; object-fit:cover; cursor:pointer;"
                onclick="previewFoto('<?= base_url() ?>/uploads/images/fasilitas/${foto}', '${data.nama_fasilitas}')"
            >
        `).join('');

        return `
            <div class="card shadow-sm border-0" style="width: 260px;">
                <div class="card-body p-2">
                    <h6 class="card-title text-primary mb-1">
                        ${data.kode_fasilitas} – ${data.jenis}
                    </h6>
                    <p class="mb-1"><b>Nama:</b> ${data.nama_fasilitas}</p>
                    <p class="mb-1"><b>Kondisi:</b> ${data.kondisi.replace('_',' ')}</p>
                    <p class="mb-1"><b>Lat:</b> ${data.latitude}<br>
                    <b>Lng:</b> ${data.longitude}</p>
                    <hr class="my-2">
                    <div class="d-flex flex-wrap justify-content-start">
                        ${fotoHTML || '<span class="text-muted">Tidak ada foto</span>'}
                    </div>
                </div>
            </div>
        `;
    }

    function previewFoto(url, title) {
        Swal.fire({
            title: title,
            imageUrl: url,
            imageAlt: title,
            width: 'auto',
            padding: '1em',
            background: '#fff',
            showConfirmButton: false,
            showCloseButton: true,
        });
    }
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
