<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?= base_url('assets/template/js/') ?>turf.min.js"></script>
<script src="<?= base_url('assets/template/js/') ?>leaflet.geometryutil.js"></script>
<script src="<?= base_url('assets/template/') ?>leafletjs/leaflet.shpfile.js"></script>
<script src="<?= base_url('assets/template/') ?>leafletjs/shp.js"></script>
<!-- Form Upload -->
<div class="mb-3" id="importKMZ">
    <label for="kmlFile" class="form-label fw-bold">Pilih Folder KMZ</label>
    <!-- <input type="file" id="kmlFile" accept=".kml" class="form-control"/> -->
        <input type="file" id="kmlFile" webkitdirectory directory multiple class="form-control" />
    <small class="text-muted">Pilih folder hasil extract KMZ (berisi doc.kml dan folder images/)</small>
</div>
<div id="progressContainer" style="margin-top:10px; display:none;">
    <div style="width:100%; background:#eee; height:20px; border-radius:5px;">
        <div id="progressBar" style="height:20px; width:0%; background:#0d6efd; border-radius:5px;"></div>
    </div>
    <p id="progressText" style="margin-top:5px; font-size:13px;">0%</p>
</div>

<button id="convertBtn" class="btn btn-primary">
    <i class="bi bi-filetype-json"></i> Konversi & Import
    <span id="loader"></span>
</button>

<hr>

<!-- Hasil Konversi -->
<h6 class="fw-bold">Log hasil import:</h6>
<div id="jsonContainer"
    style="background:#f8f9fa; border:1px solid #ddd; border-radius:6px; padding:10px; 
            max-height:400px; overflow:auto; white-space: pre-wrap; font-family: monospace;">
    <em>Belum ada data diimport.</em>
</div>

<h6 class="fw-bold mt-3">History import:</h6>
<div class="mt-3">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Direktori KMZ</th>
                <th>Diupload oleh</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tbodyLogImportTable"></tbody>
    </table>
</div>
<div id="map" hidden></div>
<script>
    $(document).ready(function () {
        // ========================
        // 1️⃣ KATA KUNCI KONDISI
        // ========================
        const rusakBerat = [
            "rusak berat","rusak parah","patah","roboh","tumbang","hilang","terbakar",
            "mati total","putus","tiang patah","rambu hilang","ambruk","lampu tidak menyala",
            "lampu mati total","kabel putus","dahan tumbang","tertutup pohon","tertabrak",
            "guardrail copot","patah total","fondasi hancur","sensor hilang"
        ];

        const rusakRingan = [
            "rusak ringan","miring","bengkok","cat pudar","lampu redup","lampu tidak hidup",
            "lampu padam sebagian","retak","berdebu","kusam","longgar","pudar","berkarat",
            "rambu terhalang","marka pudar","guardrail miring","tiang miring","kamera buram",
            "lensa kotor","lampu goyang","cover retak","cat mengelupas","tiang condong", 
            "rusak", "tidak ada", "buram"
        ];

        const rencanaKeywords = ["usulan", "rencana", "pengadaan", "perlu", "penambahan", "perbaikan"];

        function deteksiKondisi(desc) {
            const text = (desc || "").toLowerCase();
            if (rusakBerat.some(k => text.includes(k))) return "rusak_berat";
            if (rusakRingan.some(k => text.includes(k))) return "rusak_ringan";
            return "baik";
        }

        function isRencana(desc) {
            const text = (desc || "").toLowerCase();
            if (rencanaKeywords.some(k => text.includes(k))) return true;
            return false;
        }

        // ============================
        // 2️⃣ DETEKSI JENIS FASILITAS
        // ============================
        const jenisFasilitasKeywords = [
            { kategori: "Rambu", jenis: "Rambu Larangan", keys: ["rambu larangan","larangan","dilarang","no entry","stop","no parkir","no parking","no u-turn","no right turn","no left turn","rambu stop","rambu dilarang", "plang dilarang", "plang larangan", "larang"] },
            { kategori: "Rambu", jenis: "Rambu Perintah", keys: ["rambu perintah","wajib","belok kanan wajib","belok kiri wajib","gunakan helm","gunakan lajur kiri","nyalakan lampu","wajib belok","perintah", "plang perintah", "terus", "lurus", "harus", "gunakan"] },
            { kategori: "Rambu", jenis: "Rambu Peringatan", keys: ["rambu peringatan","hati-hati","waspada","tanjakan","turunan","rawan","rambu kuning","penyempitan","licin","bergelombang","anak sekolah","hewan","rambu tikungan","menanjak","menurun","jalan rusak", "plang peringatan", "hati", "plang pejalan", "jembatan", "merge", "penyebrangan", "longsor", "rawan", "kecelakaan", "hewan"] },
            { kategori: "Rambu", jenis: "Rambu Petunjuk", keys: ["rambu petunjuk","petunjuk","arah","tujuan","nama jalan","belok kiri","belok kanan","jarak","km","terminal","bandara","pelabuhan","hotel","wisata", "orang", "plang jalan", "plang jl", "plang petunjuk", "jam", "kilometer", "belok", "putar", "bundaran", "rambu tafficlight", "plang traffic", "rambu lampu", "plang rambu", "tikungan", "informasi", "plang zebra", "simpang", "perempatan", "pertigaan", "masjid", "spbu", "pombensin", "pejalan", "berkelok", "puskesmas", "ibadah", "rumah"] },
            { kategori: "Rambu", jenis: "Rambu Tambahan", keys: ["rambu tambahan","tambahan","angka jarak","keterangan","plang tambahan","keterangan waktu"] },
            { kategori: "Marka", jenis: "Marka Membujur", keys: ["marka membujur","garis tengah","as jalan","garis as","garis putus","garis kuning","pembatas jalan","lajur","marka tengah"] },
            { kategori: "Marka", jenis: "Marka Melintang", keys: ["marka melintang","stop line","garis berhenti","marka berhenti","henti","melintang"] },
            { kategori: "Marka", jenis: "Marka Serong", keys: ["marka serong","chevron","zigzag","larangan lintas","area silang","serong"] },
            { kategori: "Marka", jenis: "Marka Lambang", keys: ["marka lambang","panah","anak sekolah","lambang panah","tulisan jalan","arah panah"] },
            { kategori: "Marka", jenis: "Zebra Cross", keys: ["zebra","zebra cross","penyeberangan","crosswalk","penyeberang","garis zebra", "zoss"] },
            { kategori: "Pagar Pengaman", jenis: "Guardrail", keys: ["guardrail","pagar besi","pagar pengaman","pagar baja","rail","crash barrier","besi guard", "pagar jalan"] },
            { kategori: "Pagar Pengaman", jenis: "Pembatas Jalan", keys: ["pembatas","median","separator","beton barrier","barrier","pembagi jalan","pagar beton"] },
            { kategori: "Penanda Jalan", jenis: "Delinator", keys: ["delineator","reflektor","patok reflektor","tongkat reflektor","stick reflektor","tiang reflektor", "delinator"] },
            { kategori: "Penanda Jalan", jenis: "Patok Kilometer", keys: ["patok km","km","kilometer","penanda km","patok jarak","batu km","tugu km"] },
            { kategori: "Penanda Jalan", jenis: "Patok Pengarah", keys: ["patok pengarah","patok batas","patok tikungan","patok jalan"] },
            { kategori: "Penanda Jalan", jenis: "Pita Penggaduh", keys: ["pita penggaduh","rumble strip","pita getar","marka getar","pita jalan"] },
            { kategori: "Penerangan", jenis: "Lampu PJU", keys: ["pju","lampu jalan","lampu pju","lampu umum","lampu penerangan","lampu tiang", "lampu"] },
            { kategori: "Penerangan", jenis: "Lampu PJU Tenaga Surya", keys: ["pju solar","solar","tenaga surya","solar cell","lampu surya","pju tenaga surya","lampu solar"] },
            { kategori: "Penerangan", jenis: "Lampu Traffic Light", keys: ["traffic light","lampu merah","lampu simpang","lampu lalu lintas","lampu pengatur", "trafficlight"] },
            { kategori: "Pemelandai", jenis: "Speed Bump", keys: ["bump","speed bump","polisi tidur","gundukan","gundukan kecil"] },
            { kategori: "Pemelandai", jenis: "Speed Hump", keys: ["hump","speed hump","polisi tidur tinggi","polisi tidur panjang"] },
            { kategori: "Pemelandai", jenis: "Speed Table", keys: ["table","speed table","perataan","rata","speed platform","hamparan"] },
            { kategori: "Lainnya", jenis: "Cermin Tikungan", keys: ["cermin","cermin tikungan","cermin cembung","cermin tikungan kanan","cermin lalu lintas"] },
            { kategori: "Lainnya", jenis: "JPO (Jembatan Penyeberangan Orang)", keys: ["jpo","penyeberangan orang","jembatan penyeberangan","jembatan pejalan kaki"] },
            { kategori: "Lainnya", jenis: "Pulau Jalan", keys: ["pulau jalan","median jalan","pembagi jalan","pulau lalu lintas","pulau"] },
            { kategori: "Lainnya", jenis: "Papan Reklame", keys: ["reklame","papan iklan","billboard","spanduk","baliho","iklan","promosi"] },
            { kategori: "Lainnya", jenis: "CCTV", keys: ["cctv","kamera"] }
        ];

        function deteksiJenisFasilitas(nama, deskripsi = "") {
            const text = `${nama} ${deskripsi}`.toLowerCase();

            for (const rule of jenisFasilitasKeywords) {
                for (const keyword of rule.keys) {
                    if (text.includes(keyword)) {
                        return {
                            kategori: rule.kategori,
                            jenis_fasilitas: rule.jenis,
                            keyword: keyword
                        };
                    }
                }
            }
            return { kategori: "Lainnya", jenis_fasilitas: "LNN", keyword: null };
        }

        // ============================
        // 3️⃣ EVENT KONVERSI DAN IMPORT
        // ============================
        $("#convertBtn").on("click", async function () {
            $("#tbodyLogImportTable").html(`<tr><td colspan="3"><em>Memuat...</em></td></tr>`);
            const files = $("#kmlFile")[0].files;
            if (!files.length) {
                alert("⚠️ Pilih folder hasil extract KMZ (berisi doc.kml dan folder images/)!");
                return;
            }

            const kmlFile = [...files].find(f => f.name.endsWith(".kml"));
            const imageFiles = [...files].filter(f => f.webkitRelativePath.includes("images/"));
            const output = $("#jsonContainer");
            const progressContainer = $("#progressContainer");
            const progressBar = $("#progressBar");
            const progressText = $("#progressText");
            // folder path for log data
            const folderPath = files[0].webkitRelativePath.split("/")[0];

            if (!kmlFile) {
                output.html("<span style='color:red;'>❌ File .kml tidak ditemukan di folder!</span>");
                return;
            }

            // process log
            let importBatchID = null;
            await $.post("<?= site_url('api/log-import-kmz'); ?>", { filepath: folderPath })
            .then((res) => importBatchID = res.id) 
            .then(() => console.log("✅ Log import disimpan:", folderPath))
            .catch(err => console.error("❌ Gagal menyimpan log import:", err));

            // 🔹 Load file GeoJSON ruas jalan provinsi 
            const jalanProvinsi = await fetch("<?= base_url('assets/others/JALAN_PROVINSI.geojson'); ?>") .then(res => res.json()) .catch(() => { alert("❌ Gagal memuat data JALAN_PROVINSI.geojson"); return null; }); 
            
            if (!jalanProvinsi) return;

            const reader = new FileReader();
            reader.onload = async function (e) {
                try {
                    const text = e.target.result;
                    const parser = new DOMParser();
                    const kml = parser.parseFromString(text, "text/xml");
                    const geojson = toGeoJSON.kml(kml);

                    // Konversi fitur ke array hasil JSON
                    const hasil = geojson.features.map((feature, idx) => {
                        const props = feature.properties;
                        const coords = feature.geometry.coordinates;
                        const tgl = new Date(props.timestamp || Date.now());
                        const createdAt = `${tgl.getFullYear()}-${String(tgl.getMonth()+1).padStart(2,'0')}-${String(tgl.getDate()).padStart(2,'0')} ${String(tgl.getHours()).padStart(2,'0')}:${String(tgl.getMinutes()).padStart(2,'0')}:${String(tgl.getSeconds()).padStart(2,'0')}`;
                        const tahunSurvey = tgl.getFullYear();

                        const lat = parseFloat(coords[1]); 
                        const lng = parseFloat(coords[0]); 
                        if (isNaN(lat) || isNaN(lng)) return null;
                        
                        // 🌍 Cari ruas jalan terdekat 
                        let nearest = getNearestRoad(lat, lng);

                        const photos = [];
                        if (props.pdfmaps_photos) {
                            const regex = /<img\s+src="([^"]+)"/g;
                            let match;
                            while ((match = regex.exec(props.pdfmaps_photos)) !== null) {
                                photos.push(match[1].split('/').pop());
                            }
                        }

                        const kondisi = deteksiKondisi(props.Description);
                        const jenis = deteksiJenisFasilitas(props.name, props.Description);
                        const rencana = isRencana(props.Description);

                        return {
                            kode_fasilitas: `AUTO-${idx+1}`,
                            nama_fasilitas: props.name || "",
                            jenis_fasilitas: jenis,
                            kondisi: !Boolean(rencana) && kondisi,
                            rencana: Boolean(rencana),
                            latitude: coords[1],
                            longitude: coords[0],
                            tahun_survey: tahunSurvey,
                            foto: photos,
                            catatan: props.Description || "",
                            import_batch_kmz: importBatchID,
                            nama_ruas: nearest?.Nm_Ruas || null, 
                            kelurahan: nearest?.Desa_kel || null, 
                            kecamatan: nearest?.Kecamatan || null, 
                            kab_kota: nearest?.Kab_Kot || null,
                            created_at: createdAt
                        };
                    });

                    // Reset tampilan
                    output.html(`<b>Log Proses Import:</b><br><ul id="importLog"></ul>`);
                    progressContainer.show();
                    progressBar.css("width", "0%");
                    progressText.text("0%");

                    // Proses upload satu per satu (sequential)
                    const total = hasil.length;
                    let current = 0;

                    for (const item of hasil) {
                        current++;

                        const formData = new FormData();
                        formData.append("data", JSON.stringify([item])); // kirim satu item

                        // Tambahkan foto yang cocok
                        for (const name of item.foto) {
                            const file = imageFiles.find(f => f.name.endsWith(name));
                            if (file) formData.append("images[]", file);
                        }

                        try {
                            const res = await $.ajax({
                                url: "<?= site_url('api/fasilitas/import-kml'); ?>",
                                method: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                            });

                            // set loader on button
                            $('#loader').html('<i class="fas fa-spinner fa-spin"></i>');

                            const percent = Math.round((current / total) * 100);
                            progressBar.css("width", percent + "%");
                            progressText.text(`${percent}%`);

                            const logEl = $("#importLog");
                            if (res.log && res.log[0]) {
                                const itemLog = res.log[0];
                                const color = itemLog.status === "success" ? "green" : "red";
                                logEl.append(`<li style="color:${color}">${itemLog.kode_fasilitas} - ${itemLog.message}</li>`);
                            }
                        } catch (err) {
                            console.error(err);
                            $("#importLog").append(`<li style="color:red;">❌ Gagal upload ${item.kode_fasilitas}</li>`);
                        }
                    }

                    progressBar.css("background", "#28a745");
                    progressText.text("✅ Selesai semua");
                    $('#loader').empty();
                    getLogImportData();
                } catch (err) {
                    output.html(`<span style='color:red;'>❌ Terjadi kesalahan: ${err.message}</span>`);
                }
            };
            reader.readAsText(kmlFile);
        });

        var map = L.map('map').setView([-8.6529, 117.3616], 9);
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
        var layerJalanProvinsi = [];
        shpJalan.once("data:loaded", function() {
            jalanProvinsiGeoJSON = shpJalan.toGeoJSON();
            shpJalan.eachLayer(function (lyr) {
                layerJalanProvinsi.push(lyr);
            });
            console.log("✅ Data jalan provinsi siap:", layerJalanProvinsi.length, "fitur");
        });
        
        function getNearestRoad(lat, lng, radiusMeter = 15) {
            let nearestRoad = {
                Nm_Ruas: "Tidak diketahui",
                Desa_kel: "-",
                Kecamatan: "-",
                Kab_Kot: "-",
                minDistance: Infinity
            };
            const point = L.latLng(lat, lng);
            let nearest = { dist: Infinity, props: null };

            layerJalanProvinsi.forEach(lyr => {
                if (!lyr.feature || !lyr.getLatLngs) return;

                const coords = lyr.getLatLngs().flat();
                for (let i = 0; i < coords.length - 1; i++) {
                    const segA = coords[i];
                    const segB = coords[i + 1];
                    const jarak = L.GeometryUtil.distanceSegment(map, point, segA, segB);
                    if (jarak < nearest.dist) {
                        nearest.dist = jarak;
                        nearest.props = lyr.feature.properties;
                    }
                }
            });

            if (nearest.dist <= radiusMeter && nearest.props) {
                return nearestRoad = {
                    Nm_Ruas: nearest.props.Nm_Ruas || "Tanpa nama",
                    Desa_kel: nearest.props.Desa_Kel || "-",
                    Kecamatan: nearest.props.Kecamatan || "-",
                    Kab_Kot: nearest.props.Kab_Kot || "-",
                    minDistance: nearest.dist
                };
            } else {
                return "Tidak ada jalan dalam radius " + radiusMeter + " m";
            }
        }

        // load log import kmz data
        getLogImportData();
    });

    function getLogImportData() {
        $.ajax({
            url: "<?= base_url('api/log-import-kmz') ?>",
            method: "GET",
            dataType: "json",
            success: function(res) {
                let rows = '';
                const logTable = $("#tbodyLogImportTable");
                if (res.success && res.data.length > 0) {                    
                    res.data.forEach(item => {
                        rows += `
                            <tr>
                                <td>${item.filepath}</td>
                                <td>${item.created_by}</td>
                                <td>${item.created_at}</td>
                                <td>
                                    <button onclick="removeBatch('${item.id}')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt" data-tooltip="Hapus Data Import ini"></i></button>
                                </td>
                            </tr>`;
                    });
                }else{
                    rows = `<tr><td colspan="4" class="text-center text-muted">Belum ada log</td></tr>`;
                }
                
                logTable.html(rows);
            }
        });
    }

    function removeBatch(id){
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak akan bisa dipulihkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= site_url('api/database/remove-by-log-batch/') ?>" + id,
                    type: 'POST',
                    dataType: 'json',
                    success: (res) => {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success', 
                                text: res.message
                            }).then(() => {
                                getLogImportData();
                            });
                        }
                    },
                    error: (err) => {
                        console.log(err);
                    }
                });
            }
        });
    }
</script>