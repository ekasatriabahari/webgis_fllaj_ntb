<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-3"><?= $title ?></h1>
    </div>
</div>

<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <form id="formDetailFasilitas">
                        <input type="hidden" name="id" id="id">

                        <div class="form-group">
                            <label for="kategoriFasilitas">Kategori Fasilitas *</label>
                            <select name="kategori_fasilitas" id="kategoriFasilitas" class="form-control" required>
                                <option value="">Pilih Kategori Fasilitas</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jenisFasilitas">Jenis Fasilitas *</label>
                            <input type="hidden" name="kode_fasilitas" id="kode_fasilitas" />
                            <select name="jenis_fasilitas_id" id="jenisFasilitas" class="form-control" required>
                                <option value="">Pilih Jenis Fasilitas</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="namaFasilitas">Nama Fasilitas *</label>
                            <input type="text" name="nama_fasilitas" id="namaFasilitas" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="kondisi">Kondisi *</label>
                            <select name="kondisi" id="kondisi" class="form-control" required>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>

                        <!-- KOORDINAT -->
                        <div class="form-group">
                            <label for="koordinat">Koordinat</label>
                            <div class="input-group">
                                <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Latitude" readonly>
                                <input type="text" id="longitude" name="longitude" class="form-control" placeholder="Longitude" readonly>
                            </div>
                        </div>

                        <!-- FOTO -->
                        <div class="form-group">
                            <label for="foto">Foto Dokumentasi</label>
                            <input type="file" id="foto" name="foto[]" multiple accept="image/*" class="form-control">
                            <div id="fotoPreview" class="mt-2"></div>
                        </div>

                        <div class="form-group">
                            <label for="catatan">Catatan Tambahan</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" id="btnSubmit" class="btn btn-primary btn-block text-white mt-3">
                            <i class="fas fa-save"></i> Update Data
                        </button>
                    </form>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div id="map" style="height: 350px; margin-top: 10px; border: 1px solid #ccc;"></div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map, marker;
const fasilitasId = <?= $id ?>; // ambil id dari controller
const apiShow = "<?= site_url('api/fasilitas/') ?>" + fasilitasId;
const apiUpdate = "<?= site_url('api/fasilitas/update/') ?>" + fasilitasId;

let allJenis = [];
$(() => {
    $.ajax({
        url: '<?= site_url("api/jenis_fasilitas") ?>',
        type: 'GET',
        data: {
            'columns[1][search][value]': ''
        },
        dataType: 'json',
        success: (response) => {
            const allData = response.data;

            // --- isi dropdown kategori (unik) ---
            const kategoriUnik = [...new Set(allData.map(item => item.kategori))];
            let optKategori = '<option value="">Pilih Kategori Fasilitas</option>';
            kategoriUnik.forEach(kat => {
                optKategori += `<option value="${kat}">${kat}</option>`;
            });
            $('#kategoriFasilitas').html(optKategori);

            // --- event ketika kategori dipilih ---
            $('#kategoriFasilitas').on('change', function () {
                const kategori = $(this).val();

                // filter jenis sesuai kategori
                const jenisFiltered = allData.filter(item => item.kategori === kategori);

                // ✅ set hidden input kode_fasilitas (ambil dari item pertama)
                if (jenisFiltered.length > 0) {
                    $('#kode_fasilitas').val(jenisFiltered[0].kode_fasilitas);
                } else {
                    $('#kode_fasilitas').val('');
                }

                // isi dropdown jenis fasilitas
                let optJenis = '<option value="">Pilih Jenis Fasilitas</option>';
                jenisFiltered.forEach(j => {
                    optJenis += `<option value="${j.id}">${j.jenis}</option>`;
                });
                $('#jenisFasilitas').html(optJenis);
            });
        },
        error: (err) => {
            console.log(err);
        }
    });

    loadDetail();
});

function loadDetail() {
    fetch(apiShow)
      .then(res => res.json())
      .then(res => {
        if (res.success) {
            const d = res.data[0];
            // Isi form
            $('#id').val(d.id);
            $('#namaFasilitas').val(d.nama_fasilitas);
            $('#kondisi').val(d.kondisi);
            $('#catatan').val(d.catatan);

            // isi kategori + jenis
            $('#kategoriFasilitas').val(d.kategori).trigger('change');
            $('#jenisFasilitas').val(d.jenis_fasilitas_id);

            // isi koordinat
            $('#latitude').val(d.latitude);
            $('#longitude').val(d.longitude);

            // Peta
            map = L.map('map').setView([d.latitude, d.longitude], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            var icon = L.icon({
                iconUrl: '<?= base_url('uploads/icons/') ?>' + d.icon,
                iconSize: [41, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            marker = L.marker([d.latitude, d.longitude], {icon: icon, draggable:true} ).addTo(map);
            marker.on('dragend', e => {
                const pos = e.target.getLatLng();
                $('#latitude').val(pos.lat.toFixed(6));
                $('#longitude').val(pos.lng.toFixed(6));
            });

            // Foto preview
            if (d.foto && d.foto.length > 0) {
                try {
                    const fotos = JSON.parse(d.foto);
                    let html = '';
                    fotos.forEach(f => {
                        html += `<img src="<?= base_url('uploads/images/fasilitas/') ?>${f}" class="img-thumbnail m-1" style="max-width:100px;max-height:100px;" onclick="previewFoto('<?= base_url() ?>/uploads/images/fasilitas/${f}', '${d.nama_fasilitas}')">`;
                    });
                    $('#fotoPreview').html(html);
                } catch(e){}
            }
        }
    });
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

// Submit update
$('#formDetailFasilitas').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    $.ajax({
        url: apiUpdate,
        type: 'POST', // bisa ganti PUT kalau backend support
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: () => {
            $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        },
        success: res => {
            if (res.success) {
                Swal.fire({ icon:'success', title:'Updated', text: res.message })
                  .then(()=> window.location.href = '<?= site_url("fasilitas") ?>');
            } else {
                Swal.fire({ icon:'error', title:'Error', text: res.message });
            }
        },
        error: xhr => {
            Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
        },
        complete: () => {
            $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-save"></i> Update Data');
        }
    });
});
</script>
