<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-3"><?= $title ?></h1>
    </div>
</div>
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <!-- Leaflet CSS -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

            <!-- Leaflet JavaScript -->
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script src="<?= base_url('assets/template/') ?>leafletjs/leaflet.shpfile.js"></script>
            <script src="<?= base_url('assets/template/') ?>leafletjs/shp.js"></script>

            <div id="map" style="width: 100%; height: 600px;"></div>
        </div>
    </div>
</div>

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

<!-- untuk mendapatkan data ruas jalan dari layer jalan provinsi -->
<script src="<?= base_url('assets/template/js/') ?>turf.min.js"></script>
<script>
    // Inisialisasi peta
    var map = L.map('map').setView([-8.6529, 117.3616], 8);

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

    function addLegend(data) {
        // Hapus legend lama (jika ada)
        if (window.legendControl) map.removeControl(window.legendControl);

        window.legendControl = L.control({ position: 'bottomleft' });

        window.legendControl.onAdd = function () {
            const div = L.DomUtil.create('div', 'info legend');
            div.innerHTML = '<h6>📍 Jenis Fasilitas</h6>';

            // Gunakan Set agar tidak duplikat
            const jenisSet = new Set();
            data.forEach(group => {
                group.jenis.forEach(j => {
                    if (!jenisSet.has(j.nama_jenis)) {
                        jenisSet.add(j.nama_jenis);

                        const color = getColorByJenis(group.nama_kode);
                        const iconUrl = j.icon ? '<?= base_url('uploads/icons/') ?>' + j.icon : null;
                        const markerHTML = iconUrl
                            ? `<img src="${iconUrl}" style="width:20px;height:20px;vertical-align:middle;">`
                            : j.marker_color ? `<span class="legend-dot" style="background:${j.marker_color}"></span>`
                            : `<span class="legend-dot" style="background:${color}"></span>`;

                        div.innerHTML += `
                            <div class="legend-item">
                                ${markerHTML} ${j.nama_jenis}
                            </div>
                        `;
                    }
                });
            });

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
                    console.warn(`⚠️ Error menghitung segmen ${i} fitur ${idx}:`, err.message);
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
                    var icon;
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
                            if (j.icon && j.icon.trim() !== "") {
                                // Icon berbasis file (dari upload/icons/)
                                icon = L.icon({
                                    iconUrl: '<?= base_url('uploads/icons/') ?>' + j.icon,
                                    iconSize: [32, 32],
                                    iconAnchor: [16, 32],
                                    popupAnchor: [0, -30]
                                });
                            } else {
                            // Icon CSS fallback (divIcon)
                                icon = L.divIcon({
                                    className: "custom-marker",
                                    html: `<div class="dot" style="background:${getColorByJenis(group.nama_kode)};"></div>`,
                                    iconSize: [18, 18],
                                    iconAnchor: [9, 9]
                                });
                            }
                            L.marker([item.latitude, item.longitude], {icon: icon})
                                .addTo(jenisLayer)
                                .bindPopup(markerPopup({...item, jenis: j.nama_jenis, tahun: item.tahun_survey}));
                        });
                    });
                });
                
                addLegend(data); // tampil legend dalam peta

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
                src="<?= base_url() ?>/uploads/images/fasilitas/${data.tahun}/${foto}"
                alt="${data.nama_fasilitas}"
                class="img-thumbnail m-1 preview-thumb"
                style="width:100px; height:100px; object-fit:cover; cursor:pointer;"
                onclick="previewFoto('<?= base_url() ?>/uploads/images/fasilitas/${data.tahun}/${foto}', '${data.nama_fasilitas}')"
            >
        `).join('');

        // 🔍 Tambahkan pencarian ruas jalan
        const jalan = getNearestRoad(data.latitude, data.longitude);
        // const jalan =""

        return `
            <div class="card shadow-sm border-0" style="width: 260px;">
                <div class="card-body p-2">
                    <h6 class="card-title text-primary mb-1">
                        ${data.kode_fasilitas} – ${data.jenis}
                    </h6>
                    <p class="mb-1"><b>Nama:</b> ${data.nama_fasilitas}</p>
                    <p class="mb-1"><b>Kondisi:</b> ${data.kondisi.replace('_',' ')}</p>
                    <p class="mb-1"><b>Jalan:</b>${jalan}</p>
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
