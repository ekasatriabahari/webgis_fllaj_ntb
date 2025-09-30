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
