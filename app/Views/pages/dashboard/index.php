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
    var map = L.map('map').setView([-8.6529, 117.3616], 9);
    
    // init markers
    var markersLayer = L.layerGroup();
    var markers = [];

    // --- Base Layer ---
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // --- Shapefile: Jalan Provinsi NTB ---
    var shpJalan = new L.Shapefile("<?= base_url('assets/shp/jalan_provinsi_ntb.zip') ?>", {
        style: function (feature) {
            return {
                color: '#e53935',  // warna garis merah
                weight: 3,         // ketebalan garis
                opacity: 0.8       // transparansi garis
            };
        },
        onEachFeature: function (feature, layer) {
            if (feature.properties) {
                layer.bindPopup(Object.keys(feature.properties)
                    .map(function (k) { return k + ": " + feature.properties[k]; })
                    .join("<br />"), { maxHeight: 200 });
            }
        }
    });

    // --- Scale control ---
    L.control.scale().addTo(map);

    // Layer Control
    var baseMaps = {
        "OpenStreetMap": osm
    };

    var overlayMaps = {
        // "Wilayah NTB": shpMap,
        "Jalan Provinsi NTB": shpJalan,
        "Titik Fasilitas": markersLayer
    };

    // Tambahkan kontrol layer ke peta
    L.control.layers(baseMaps, overlayMaps, { collapsed: false }).addTo(map);

    $(() => {
        getMarkers();
    })
    
    function getMarkers()
    {
        $.ajax({
            url: "<?= site_url('api/dashboard/markers') ?>",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                markers = response.data;
                markersLayer.clearLayers();
                markers.forEach(marker => {
                    var icon = L.icon({
                        iconUrl: '<?= base_url('uploads/icons/') ?>' + marker.icon,
                        iconSize: [41, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });
                    L.marker([marker.latitude, marker.longitude], {icon: icon}).addTo(markersLayer)
                    .bindPopup(markerPopup(marker));
                });
                markersLayer.addTo(map);
            },
            error: (err) => {
                console.log(err);
            }
        });
    }

    function markerPopup(data) {
        const fotos = JSON.parse(data.foto || '[]');

        // Thumbnail untuk setiap foto dengan event onclick
        const fotoHTML = fotos.map((foto, i) => `
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