<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><?= $title; ?></h5>
            </div>
            <div class="card-body">

                <!-- Form Upload -->
                <div class="mb-3">
                    <label for="kmlFile" class="form-label fw-bold">Upload File KML</label>
                    <input type="file" id="kmlFile" accept=".kml" class="form-control" />
                </div>

                <button id="convertBtn" class="btn btn-primary">
                    <i class="bi bi-filetype-json"></i> Konversi & Tampilkan JSON
                </button>

                <hr>

                <!-- Hasil Konversi -->
                <h6 class="fw-bold">Hasil JSON Sederhana:</h6>
                <div id="jsonContainer"
                    style="background:#f8f9fa; border:1px solid #ddd; border-radius:6px; padding:10px; 
                           max-height:400px; overflow:auto; white-space: pre-wrap; font-family: monospace;">
                    <em>Belum ada data dikonversi.</em>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Script Konversi -->
<script src="<?= base_url('assets/template/js/') ?>togeojson.js"></script>
<script>
$(document).ready(function() {

    const rusakBerat = [
        "rusak berat", "rusak parah", "patah", "roboh", "tumbang", "hilang", "terbakar",
        "mati total", "putus", "tiang patah", "rambu hilang", "patah total",
        "lepas dari dudukan", "ambruk", "lampu tidak menyala", "lampu mati total",
        "kabel putus", "patah bagian bawah", "tiang roboh", "dahan tumbang menimpa",
        "tertabrak kendaraan", "rusak total", "marka hilang", "marka tidak terlihat",
        "guardrail copot", "delineator patah", "penyangga patah", "fondasi hancur",
        "tiang hilang", "kamera hilang", "sensor hilang", "alat tidak berfungsi"
    ];

    const rusakRingan = [
        "rusak ringan", "miring", "bengkok", "cat pudar", "terhalang pohon", "lampu redup",
        "lampu tidak hidup", "lampu padam sebagian", "retak", "berdebu", "kusam", "longgar",
        "patah kecil", "berkarat", "tergores", "dempul mengelupas", "cat mengelupas",
        "pudar", "tiang condong", "lampu rusak sebagian", "kabel longgar",
        "penyangga miring", "penyangga bengkok", "rambu terhalang", "marka pudar",
        "marka sebagian hilang", "guardrail miring", "delineator lepas", "tiang miring",
        "kamera buram", "lensa kotor", "cermin buram", "lampu berkedip", "lampu tidak stabil",
        "sensor tidak akurat", "penutup lepas", "kabel terbuka", "lampu goyang",
        "tiang tidak tegak", "penahan longgar", "cover retak", "tembok retak"
    ];

    $("#convertBtn").on("click", function() {
        const file = $("#kmlFile")[0].files[0];
        const output = $("#jsonContainer");

        if (!file) {
            output.html("<span style='color:red;'>⚠️ Silakan pilih file .kml terlebih dahulu.</span>");
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const text = e.target.result;
                const parser = new DOMParser();
                const kml = parser.parseFromString(text, "text/xml");
                const geojson = toGeoJSON.kml(kml);

                // Transformasi data hasil konversi
                const transformed = $.map(geojson.features, function(feature) {
                    const coords = feature.geometry.coordinates;
                    const props = feature.properties;

                    // Ambil nama file dari tag <img>
                    let photos = [];
                    if (props.pdfmaps_photos) {
                        const regex = /<img\s+src="([^"]+)"/g;
                        let match;
                        while ((match = regex.exec(props.pdfmaps_photos)) !== null) {
                            const filename = match[1].split('/').pop();
                            photos.push(filename);
                        }
                    }

                    // Format timestamp ke Y-m-d H:i:s
                    const createdAt = new Date(props.timestamp);
                    const formattedDate = createdAt.getFullYear() + "-" +
                        String(createdAt.getMonth() + 1).padStart(2, '0') + "-" +
                        String(createdAt.getDate()).padStart(2, '0') + " " +
                        String(createdAt.getHours()).padStart(2, '0') + ":" +
                        String(createdAt.getMinutes()).padStart(2, '0') + ":" +
                        String(createdAt.getSeconds()).padStart(2, '0');

                    // Tentukan kondisi berdasarkan deskripsi
                    const desc = (props.Description || "").toLowerCase();
                    let kondisi = "Baik";

                    if (rusakBerat.some(k => desc.includes(k))) {
                        kondisi = "Rusak Berat";
                    } else if (rusakRingan.some(k => desc.includes(k))) {
                        kondisi = "Rusak Ringan";
                    }

                    return {
                        nama_fasilitas: props.name || "",
                        catatan: props.Description || "",
                        longitude: coords[0],
                        latitude: coords[1],
                        foto: photos,
                        created_at: formattedDate,
                        kondisi: kondisi
                    };
                });

                // Tampilkan hasil JSON
                output.text(JSON.stringify(transformed, null, 2));

            } catch (err) {
                output.html("<span style='color:red;'>❌ Terjadi kesalahan saat konversi: " + err.message + "</span>");
            }
        };

        reader.readAsText(file);
    });
});
</script>

