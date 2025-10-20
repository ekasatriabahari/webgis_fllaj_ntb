
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

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?= base_url('assets/template/') ?>leafletjs/leaflet.shpfile.js"></script>
<script src="<?= base_url('assets/template/') ?>leafletjs/shp.js"></script>

<!-- untuk mendapatkan data ruas jalan dari layer jalan provinsi -->
<script src="<?= base_url('assets/template/js/') ?>turf.min.js"></script>
</head>

<body style="padding: 0px;">
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
        <?php include('navbar.php'); ?>

        <section class="vh-lg-100 mt-lg-0 bg-soft mb-5 px-3" id="home">

            <!-- Map Section -->
            <div class="row mt-3">
                <div class="col-md-12 col-sm-12">
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
            
            <?php include('charts.php'); ?>
            
            <?php include('fasilitas.php'); ?>
            
            <?php include('rencana.php'); ?>

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

<!-- points marker css -->
<style>
    /* --- Marker CSS fallback --- */
    .custom-marker .dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 3px rgba(0,0,0,0.3);
    }

    /* --- Legend styling --- */
    .leaflet-control.legend {
    background: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 13px;
    line-height: 18px;
    color: #333;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    .leaflet-control.legend h6 {
    margin: 0 0 6px;
    font-weight: bold;
    font-size: 13px;
    }

    .legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 4px;
    }

    .legend-dot {
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-right: 6px;
    border-radius: 50%;
    border: 1px solid #ccc;
    }

</style>

<script>
    // Inisialisasi peta
    var map = L.map('map').setView([-8.6529, 117.3616], 9);

    // --- Base Layer ---
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let jalanProvinsiGeoJSON = null;
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

    // simpan GeoJSON setelah selesai dimuat
    shpJalan.once("data:loaded", function() {
        jalanProvinsiGeoJSON = shpJalan.toGeoJSON();
        console.log( jalanProvinsiGeoJSON );
    });

    shpJalan.addTo(map);

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
        <div id="collapseJalan" class="accordion-collapse collapse show">
            <div class="accordion-body py-2 px-2">
            <label class="d-block mb-1"><input type="checkbox" class="overlay" value="jalan" checked> Jalan Provinsi NTB</label>
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
        <div id="collapseFasilitas" class="accordion-collapse collapse show">
            <div class="accordion-body py-2 px-2" id="fasilitas-groups"></div>
        </div>
        </div>
        <!-- Data Rencana -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed py-1 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRencana">
                Data Rencana
                </button>
            </h2>
            <div id="collapseRencana" class="accordion-collapse collapse show">
                <div class="accordion-body py-2 px-2" id="rencana-groups"></div>
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
    var rencanaGroups = {};
    $(() => {
        getMarkers();
    });

    function getColorByJenis(jenis) {
        const colors = {
            "Rambu": "#ff0000ff",
            "Marka": "#07fff3ff",
            "Pagar Pengaman": "#dcd935ff",
            "Penanda Jalan": "#a72828ff",
            "Penerangan": "#9fc142ff",
            "Pemelandai": "#fd7e14",
            "Lainnya": "#6c757d"
        };
        return colors[jenis] || "#999";
    }

    function addLegend(fasilitasData, rencanaData) {
        if (window.legendControl) map.removeControl(window.legendControl);
        window.legendControl = L.control({ position: 'bottomleft' });

        window.legendControl.onAdd = function () {
            const div = L.DomUtil.create('div', 'info legend');
            div.innerHTML = '<h6>📍 Fasilitas</h6>';

            // --- Fasilitas ---
            const jenisSet = new Set();
            fasilitasData.forEach(group => {
                group.jenis.forEach(j => {
                    if (!jenisSet.has(j.nama_jenis)) {
                        jenisSet.add(j.nama_jenis);
                        const iconUrl = j.icon ? '<?= base_url('uploads/icons/') ?>' + j.icon : null;
                        const markerHTML = iconUrl
                            ? `<img src="${iconUrl}" style="width:20px;height:20px;vertical-align:middle;">`
                            : group.nama_kode ? `<span class="legend-dot" style="background:${getColorByJenis(group.nama_kode)}"></span>` 
                            : `<span class="legend-dot" style="background:${j.marker_color || '#999'}"></span>`;
                        div.innerHTML += `<div class="legend-item">${markerHTML} ${j.nama_jenis}</div>`;
                    }
                });
            });

            // --- Rencana ---
            if (rencanaData && rencanaData.length > 0) {
                div.innerHTML += `<hr><h6>🗺️ Rencana</h6>`;
                const rencanaSet = new Set();
                rencanaData.forEach(group => {
                    group.jenis.forEach(j => {
                        if (!rencanaSet.has(j.nama_jenis)) {
                            rencanaSet.add(j.nama_jenis);
                            const iconUrl = j.icon ? '<?= base_url('uploads/icons/') ?>' + j.icon : null;
                            const markerHTML = iconUrl
                                ? `<img src="${iconUrl}" style="width:20px;height:20px;vertical-align:middle;">`
                                : group.nama_kode ? `<span class="legend-dot" style="background:${getColorByJenis(group.nama_kode)}"></span>` 
                                : `<span class="legend-dot" style="background:${j.marker_color || '#999'}"></span>`;
                            div.innerHTML += `<div class="legend-item">${markerHTML} ${j.nama_jenis}</div>`;
                        }
                    });
                });
            }

            return div;
        };
        window.legendControl.addTo(map);
    }

    function getNearestRoad(lat, lng) {
        if (!jalanProvinsiGeoJSON || !jalanProvinsiGeoJSON.features) {
            return "Tidak ada data jalan";
        }

        // Pastikan koordinat valid
        lat = parseFloat(lat);
        lng = parseFloat(lng);
        if (isNaN(lat) || isNaN(lng)) {
            console.warn("⚠️ Koordinat tidak valid:", lat, lng);
            return "Koordinat tidak valid";
        }

        const point = turf.point([lng, lat]);
        let nearestRoadName = "Tidak diketahui";
        let wilayah = "Tidak diketahui";
        let minDistance = Infinity;

        jalanProvinsiGeoJSON.features.forEach((f, idx) => {
            if (!f.geometry || !f.geometry.coordinates) return;

            let segments = [];

            // 🔹 Deteksi tipe geometry
            if (f.geometry.type === "LineString") {
                segments = [f.geometry.coordinates];
            } else if (f.geometry.type === "MultiLineString") {
                segments = f.geometry.coordinates;
            } else {
                console.warn(`⚠️ Geometry tipe ${f.geometry.type} di-skip (fitur ke-${idx})`);
                return;
            }

            // 🔹 Loop setiap ruas garis
            segments.forEach((coords, i) => {
                try {
                    // Validasi: pastikan semua elemen koordinat numerik
                    if (
                        !Array.isArray(coords) ||
                        coords.length === 0 ||
                        !coords.every(c => Array.isArray(c) && c.length === 2 && 
                                        typeof c[0] === "number" && typeof c[1] === "number")
                    ) {
                        console.warn(`⚠️ Koordinat tidak valid pada segmen ke-${i} fitur ${idx}`);
                        return;
                    }

                    const line = turf.lineString(coords);
                    const snapped = turf.nearestPointOnLine(line, point, { units: "meters" });
                    const dist = snapped.properties.dist;

                    if (dist < minDistance) {
                        minDistance = dist;
                        wilayah = `${f.properties.Desa_Kel || '-'}, ${f.properties.Kecamatan || '-'}, ${f.properties.Kab_Kot || '-'}`;
                        nearestRoadName = f.properties.Nm_Ruas || "Tanpa nama";
                    }
                } catch (err) {
                    // console.warn(`⚠️ Error menghitung segmen ${i} fitur ${idx}:`, err.message);
                }
            });
        });

        return `${nearestRoadName} (${minDistance.toFixed(1)} m - ${wilayah})`;
    }

    function getMarkers() {
        $.ajax({
            url: "<?= site_url('api/dashboard/markers') ?>",
            type: 'GET',
            dataType: 'json',
            beforeSend: () => {
                Swal.fire({
                    title: 'Memuat Data Peta...',
                    html: 'Mohon tunggu, sedang memuat fasilitas dan shapefile jalan',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(response) {
                Swal.close();
                const fasilitasData = response.data.fasilitas || [];
                const rencanaData = response.data.rencana || [];

                const fasilitasContainer = document.getElementById('fasilitas-groups');
                const rencanaContainer = document.getElementById('rencana-groups');
                fasilitasContainer.innerHTML = "";
                rencanaContainer.innerHTML = "";

                fasilitasGroups = {};
                rencanaGroups = {};

                // ======================
                // === Data Fasilitas ===
                // ======================
                fasilitasData.forEach(group => {
                    let kodeContainer = document.createElement('div');
                    kodeContainer.innerHTML = `<b>${group.kode}</b> - ${group.nama_kode}<br>`;
                    fasilitasContainer.appendChild(kodeContainer);

                    fasilitasGroups[group.kode] = {};
                    group.jenis.forEach(j => {
                        let jenisLayer = L.layerGroup();
                        fasilitasGroups[group.kode][j.nama_jenis] = jenisLayer;

                        // Checkbox per jenis fasilitas
                        let checkbox = document.createElement('label');
                        checkbox.innerHTML = `<input type="checkbox" data-kode="${group.kode}" data-jenis="${j.nama_jenis}" class="cb-fasilitas" checked> ${j.nama_jenis}<br>`;
                        kodeContainer.appendChild(checkbox);

                        // Marker per data
                        j.data.forEach(item => {
                            let icon;
                            if (j.icon && j.icon.trim() !== "") {
                                icon = L.icon({
                                    iconUrl: '<?= base_url('uploads/icons/') ?>' + j.icon,
                                    iconSize: [32, 32],
                                    iconAnchor: [16, 32],
                                    popupAnchor: [0, -30]
                                });
                            } else {
                                icon = L.divIcon({
                                    className: "custom-marker",
                                    html: `<div class="dot" style="background:${j.marker_color || getColorByJenis(group.nama_kode)};"></div>`,
                                    iconSize: [18, 18],
                                    iconAnchor: [9, 9]
                                });
                            }

                            L.marker([item.latitude, item.longitude], { icon: icon })
                                .addTo(jenisLayer)
                                .bindPopup(markerPopup({ ...item, jenis: j.nama_jenis, tahun: item.tahun_survey, catatan: item.catatan }));
                        });
                    });
                });

                $('#fasilitas-groups .cb-fasilitas').each(function() {
                    $(this).prop('checked', true);
                    let kode = this.dataset.kode;
                    let jenis = this.dataset.jenis;
                    if (fasilitasGroups[kode] && fasilitasGroups[kode][jenis]) {
                        map.addLayer(fasilitasGroups[kode][jenis]);
                    }
                });

                // Checkbox event fasilitas
                fasilitasContainer.querySelectorAll('.cb-fasilitas').forEach(cb => {
                    cb.addEventListener('change', function() {
                        let kode = this.dataset.kode;
                        let jenis = this.dataset.jenis;
                        if (this.checked) map.addLayer(fasilitasGroups[kode][jenis]);
                        else map.removeLayer(fasilitasGroups[kode][jenis]);
                    });
                });


                // ======================
                // === Data Rencana ===
                // ======================
                if (rencanaData.length > 0) {
                    rencanaData.forEach(group => {
                        let kodeContainer = document.createElement('div');
                        kodeContainer.innerHTML = `<b>${group.kode}</b> - ${group.nama_kode}<br>`;
                        rencanaContainer.appendChild(kodeContainer);

                        rencanaGroups[group.kode] = {};
                        group.jenis.forEach(j => {
                            let jenisLayer = L.layerGroup();
                            rencanaGroups[group.kode][j.nama_jenis] = jenisLayer;

                            // Checkbox per jenis rencana
                            let checkbox = document.createElement('label');
                            checkbox.innerHTML = `<input type="checkbox" data-kode="${group.kode}" data-jenis="${j.nama_jenis}" class="cb-rencana" checked> ${j.nama_jenis}<br>`;
                            kodeContainer.appendChild(checkbox);

                            // Marker per data
                            j.data.forEach(item => {
                                let icon;
                                if (j.icon && j.icon.trim() !== "") {
                                    icon = L.icon({
                                        iconUrl: '<?= base_url('uploads/icons/') ?>' + j.icon,
                                        iconSize: [32, 32],
                                        iconAnchor: [16, 32],
                                        popupAnchor: [0, -30]
                                    });
                                } else {
                                    icon = L.divIcon({
                                        className: "custom-marker",
                                        html: `<div class="dot" style="background:${j.marker_color || getColorByJenis(group.nama_kode)};"></div>`,
                                        iconSize: [18, 18],
                                        iconAnchor: [9, 9]
                                    });
                                }

                                L.marker([item.latitude, item.longitude], { icon: icon })
                                    .addTo(jenisLayer)
                                    .bindPopup(markerPopup({ ...item, jenis: j.nama_jenis, tahun: item.tahun_survey, catatan: item.catatan }));
                            });
                        });
                    });

                    $('#rencana-groups .cb-rencana').each(function() {
                        $(this).prop('checked', true);
                        let kode = this.dataset.kode;
                        let jenis = this.dataset.jenis;
                        if (rencanaGroups[kode] && rencanaGroups[kode][jenis]) {
                            map.addLayer(rencanaGroups[kode][jenis]);
                        }
                    });

                    // Checkbox event rencana
                    rencanaContainer.querySelectorAll('.cb-rencana').forEach(cb => {
                        cb.addEventListener('change', function() {
                            let kode = this.dataset.kode;
                            let jenis = this.dataset.jenis;
                            if (this.checked) map.addLayer(rencanaGroups[kode][jenis]);
                            else map.removeLayer(rencanaGroups[kode][jenis]);
                        });
                    });
                } else {
                    rencanaContainer.innerHTML = `<span class="text-muted">Tidak ada data rencana.</span>`;
                }

                // Gabungkan legend
                addLegend(fasilitasData, rencanaData);
            },
            error: (err) => {
                console.error(err);
            }
        });
    }


    function markerPopup(data) {
        const fotos = JSON.parse(data.foto || '[]');
        const fotoHTML = fotos.map((foto) => `
            <img 
                src="<?= base_url() ?>/uploads/images/${data.kode_fasilitas.includes('RNC') ? 'rencana' : 'fasilitas'}/${data.tahun}/${foto}"
                alt="${data.nama_fasilitas}"
                class="img-thumbnail m-1 preview-thumb"
                style="width:100px; height:100px; object-fit:cover; cursor:pointer;"
                onclick="previewFoto('<?= base_url() ?>/uploads/images/${data.kode_fasilitas.includes('RNC') ? 'rencana' : 'fasilitas'}/${data.tahun}/${foto}', '${data.nama_fasilitas}')"
            >
        `).join('');

        // 🔍 Tambahkan pencarian ruas jalan
        // const jalan = getNearestRoad(data.latitude, data.longitude);
        // const jalan =""

        return `
            <div class="card shadow-sm border-0" style="width: 260px;">
                <div class="card-body p-2">
                    <h6 class="card-title text-primary mb-1">
                        ${data.kode_fasilitas} – ${data.jenis}
                    </h6>
                    <p class="mb-1"><b>Nama:</b> ${data.nama_fasilitas}</p>
                    <p class="mb-1"><b>Kondisi:</b> ${data.kondisi ? data.kondisi.replace('_',' ') : 'Rencana'}</p>
                    <p class="mb-1"><b>Jalan:</b>${data.lokasi}</p>
                    <p class="mb-1"><b>Lat:</b> ${data.latitude}<br>
                    <b>Lng:</b> ${data.longitude}</p>
                    <p><b>Catatan:</b> ${data.catatan}</p>
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
