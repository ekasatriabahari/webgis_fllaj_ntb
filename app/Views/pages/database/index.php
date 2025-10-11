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
                    <input type="file" id="kmlFile" accept=".kml" class="form-control"/>
                </div>
                <div id="progressContainer" style="margin-top:10px; display:none;">
                    <div style="width:100%; background:#eee; height:20px; border-radius:5px;">
                        <div id="progressBar" style="height:20px; width:0%; background:#0d6efd; border-radius:5px;"></div>
                    </div>
                    <p id="progressText" style="margin-top:5px; font-size:13px;">0%</p>
                </div>

                <button id="convertBtn" class="btn btn-primary">
                    <i class="bi bi-filetype-json"></i> Konversi & Import
                </button>

                <hr>

                <!-- Hasil Konversi -->
                <h6 class="fw-bold">Log hasil import:</h6>
                <div id="jsonContainer"
                    style="background:#f8f9fa; border:1px solid #ddd; border-radius:6px; padding:10px; 
                           max-height:400px; overflow:auto; white-space: pre-wrap; font-family: monospace;">
                    <em>Belum ada data diimport.</em>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Script Konversi -->
<script src="<?= base_url('assets/template/js/') ?>togeojson.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
        "rusak", "tidak ada"
    ];

    function deteksiKondisi(desc) {
        const text = (desc || "").toLowerCase();
        if (rusakBerat.some(k => text.includes(k))) return "rusak_berat";
        if (rusakRingan.some(k => text.includes(k))) return "rusak_ringan";
        return "baik";
    }

    // ============================
    // 2️⃣ DETEKSI JENIS FASILITAS
    // ============================
    const jenisFasilitasKeywords = [
        { kategori: "Rambu", jenis: "Rambu Larangan", keys: ["rambu larangan","larangan","dilarang","no entry","stop","no parkir","no parking","no u-turn","no right turn","no left turn","rambu stop","rambu dilarang"] },
        { kategori: "Rambu", jenis: "Rambu Perintah", keys: ["rambu perintah","wajib","belok kanan wajib","belok kiri wajib","gunakan helm","gunakan lajur kiri","nyalakan lampu","wajib belok","perintah"] },
        { kategori: "Rambu", jenis: "Rambu Peringatan", keys: ["rambu peringatan","tikungan","hati-hati","waspada","tanjakan","turunan","rawan","rambu kuning","penyempitan","licin","bergelombang","anak sekolah","hewan","rambu tikungan","menanjak","menurun","jalan rusak"] },
        { kategori: "Rambu", jenis: "Rambu Petunjuk", keys: ["rambu petunjuk","petunjuk","arah","tujuan","nama jalan","belok kiri","belok kanan","jarak","km","terminal","bandara","pelabuhan","hotel","wisata"] },
        { kategori: "Rambu", jenis: "Rambu Tambahan", keys: ["rambu tambahan","tambahan","angka jarak","keterangan","plang tambahan","keterangan waktu"] },
        { kategori: "Marka", jenis: "Marka Membujur", keys: ["marka membujur","garis tengah","as jalan","garis as","garis putus","garis kuning","pembatas jalan","lajur","marka tengah"] },
        { kategori: "Marka", jenis: "Marka Melintang", keys: ["marka melintang","stop line","garis berhenti","marka berhenti","henti","melintang"] },
        { kategori: "Marka", jenis: "Marka Serong", keys: ["marka serong","chevron","zigzag","larangan lintas","area silang","serong"] },
        { kategori: "Marka", jenis: "Marka Lambang", keys: ["marka lambang","panah","anak sekolah","lambang panah","tulisan jalan","arah panah"] },
        { kategori: "Marka", jenis: "Zebra Cross", keys: ["zebra","zebra cross","penyeberangan","crosswalk","penyeberang","garis zebra"] },
        { kategori: "Pagar Pengaman", jenis: "Guardrail", keys: ["guardrail","pagar besi","pagar pengaman","pagar baja","rail","crash barrier","besi guard"] },
        { kategori: "Pagar Pengaman", jenis: "Pembatas Jalan", keys: ["pembatas","median","separator","beton barrier","barrier","pembagi jalan","pagar beton"] },
        { kategori: "Penanda Jalan", jenis: "Delinator", keys: ["delineator","reflektor","patok reflektor","tongkat reflektor","stick reflektor","tiang reflektor"] },
        { kategori: "Penanda Jalan", jenis: "Patok Kilometer", keys: ["patok km","km","kilometer","penanda km","patok jarak","batu km","tugu km"] },
        { kategori: "Penanda Jalan", jenis: "Patok Pengarah", keys: ["patok pengarah","patok batas","patok tikungan","patok jalan"] },
        { kategori: "Penanda Jalan", jenis: "Pita Penggaduh", keys: ["pita penggaduh","rumble strip","pita getar","marka getar","pita jalan"] },
        { kategori: "Penerangan", jenis: "Lampu PJU", keys: ["pju","lampu jalan","lampu pju","lampu umum","lampu penerangan","lampu tiang"] },
        { kategori: "Penerangan", jenis: "Lampu PJU Tenaga Surya", keys: ["pju solar","solar","tenaga surya","solar cell","lampu surya","pju tenaga surya","lampu solar"] },
        { kategori: "Penerangan", jenis: "Lampu Traffic Light", keys: ["traffic light","lampu merah","lampu simpang","lampu lalu lintas","lampu pengatur"] },
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
    $("#convertBtn").on("click", function () {
        $("#kmlFile").attr("multiple", true);
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

        if (!kmlFile) {
            output.html("<span style='color:red;'>❌ File .kml tidak ditemukan di folder!</span>");
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            try {
                const text = e.target.result;
                const parser = new DOMParser();
                const kml = parser.parseFromString(text, "text/xml");
                const geojson = toGeoJSON.kml(kml);

                const hasil = geojson.features.map((feature, idx) => {
                    const props = feature.properties;
                    const coords = feature.geometry.coordinates;

                    const tgl = new Date(props.timestamp || Date.now());
                    const createdAt = `${tgl.getFullYear()}-${String(tgl.getMonth()+1).padStart(2,'0')}-${String(tgl.getDate()).padStart(2,'0')} ${String(tgl.getHours()).padStart(2,'0')}:${String(tgl.getMinutes()).padStart(2,'0')}:${String(tgl.getSeconds()).padStart(2,'0')}`;
                    const tahunSurvey = tgl.getFullYear();

                    // Ambil nama file foto
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

                    return {
                        kode_fasilitas: `AUTO-${idx+1}`,
                        nama_fasilitas: props.name || "",
                        jenis_fasilitas: jenis,
                        kondisi: kondisi,
                        latitude: coords[1],
                        longitude: coords[0],
                        tahun_survey: tahunSurvey,
                        foto: photos,
                        catatan: props.Description || "",
                        created_at: createdAt
                    };
                });

                output.text(JSON.stringify(hasil, null, 2));

                // --- Kirim JSON dan foto ke server ---
                const formData = new FormData();
                formData.append("data", JSON.stringify(hasil));
                imageFiles.forEach(f => formData.append("images[]", f));

                progressContainer.show();
                progressBar.css("width", "0%");
                progressText.text("0%");

                $.ajax({
                    url: "<?= site_url('api/fasilitas/import-kml'); ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                const percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                progressBar.css("width", percentComplete + "%");
                                progressText.text(percentComplete + "%");
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(res) {
                        let html = `<b>Log Proses Import:</b><br><ul>`;
                        res.log.forEach(item => {
                            const color = item.status === 'success' ? 'green' : 'red';
                            html += `<li style="color:${color};">${item.kode_fasilitas} - ${item.message || item.status}</li>`;
                        });
                        html += `</ul>`;
                        output.html(html);
                    },
                    error: function(err) {
                        output.html(`<span style='color:red;'>❌ Gagal import data ke server.</span>`);
                        console.error(err);
                    }
                });

            } catch (err) {
                output.html(`<span style='color:red;'>❌ Terjadi kesalahan: ${err.message}</span>`);
            }
        };
        reader.readAsText(kmlFile);
    });
});
</script>



