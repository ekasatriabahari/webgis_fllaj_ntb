<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-3"><?= $title ?></h1>
    </div>
</div>
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <form id="formTambahFasilitas" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="jenisFasilitas">Jenis Fasilitas *</label>
                    <select name="jenis_fasilitas_id" id="jenisFasilitas" class="form-control" required>
                        <option value="">Pilih Jenis Fasilitas</option>
                        <!-- Options akan diisi via AJAX -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="namaFasilitas">Nama Fasilitas *</label>
                    <input type="text" name="nama_fasilitas" class="form-control" required
                        placeholder="Contoh: Rambu Stop, Marka Zebra Cross, dll">
                </div>

                <div class="form-group">
                    <label for="kondisi">Kondisi *</label>
                    <select name="kondisi" id="kondisi" class="form-control" required>
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                </div>

                <!-- KOORDINAT OTOMATIS DARI GPS -->
                <div class="form-group">
                    <label for="koordinat">Koordinat (Otomatis dari GPS)</label>
                    <div class="input-group">
                        <input type="text" id="latitude" name="latitude" class="form-control"
                            placeholder="Latitude" readonly>
                        <input type="text" id="longitude" name="longitude" class="form-control"
                            placeholder="Longitude" readonly>
                        <button type="button" id="btnGetLocation" class="btn btn-primary">
                            <i class="fas fa-location-arrow"></i> Get
                        </button>
                    </div>
                    <small class="form-text text-muted">
                        Tekan tombol untuk mendapatkan koordinat GPS saat ini
                    </small>
                    <div id="locationStatus" class="mt-2"></div>
                </div>

                <!-- FOTO MULTIPLE DARI KAMERA -->
                <div class="form-group">
                    <label for="foto">Foto Dokumentasi * (Minimal 2 foto)</label>
                    <input type="file" id="foto" name="foto[]" multiple
                        accept="image/*" capture="camera" class="form-control">
                    <small class="form-text text-muted">
                        Tekan tombol untuk mengambil foto langsung dari kamera
                    </small>
                    <div id="fotoPreview" class="mt-2"></div>
                </div>

                <div class="form-group">
                    <label for="catatan">Catatan Tambahan</label>
                    <textarea name="catatan" class="form-control" rows="3"
                        placeholder="Contoh: Tertutup pepohonan, posisi kurang strategis, dll"></textarea>
                </div>

                <button type="submit" class="btn btn-success btn-block text-white mt-3">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </form>
        </div>
    </div>
</div>
<script>
    // Geolocation API untuk mendapatkan koordinat
    document.getElementById('btnGetLocation').addEventListener('click', function() {
        const status = document.getElementById('locationStatus');
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        status.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Mencari lokasi...';
        
        if (!navigator.geolocation) {
            status.innerHTML = '<span class="text-danger">Geolocation tidak didukung browser ini</span>';
            return;
        }
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                
                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);
                
                status.innerHTML = `<span class="text-success">
                    <i class="fas fa-check-circle"></i> Lokasi berhasil didapatkan (Akurasi: ${accuracy}m)
                </span>`;
                
                // Tambahkan marker di peta
                if (window.map) {
                    // Hapus marker sebelumnya
                    if (window.currentMarker) {
                        window.map.removeLayer(window.currentMarker);
                    }
                    
                    // Tambahkan marker baru
                    window.currentMarker = L.marker([lat, lng])
                        .addTo(window.map)
                        .bindPopup('Lokasi saat ini<br>Lat: ' + lat.toFixed(6) + '<br>Lng: ' + lng.toFixed(6))
                        .openPopup();
                    
                    // Zoom ke lokasi
                    window.map.setView([lat, lng], 16);
                }
            },
            function(error) {
                let errorMessage = 'Error mendapatkan lokasi: ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'Izin lokasi ditolak';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'Informasi lokasi tidak tersedia';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'Permintaan lokasi timeout';
                        break;
                    default:
                        errorMessage += 'Error tidak diketahui';
                }
                status.innerHTML = `<span class="text-danger">${errorMessage}</span>`;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    });

    // Preview foto sebelum upload
    document.getElementById('foto').addEventListener('change', function(e) {
        const preview = document.getElementById('fotoPreview');
        preview.innerHTML = '';
        
        const files = e.target.files;
        if (files.length > 0) {
            preview.innerHTML = `<p>${files.length} foto dipilih:</p>`;
            
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.maxHeight = '100px';
                    img.style.margin = '5px';
                    img.className = 'img-thumbnail';
                    img.title = file.name;
                    
                    preview.appendChild(img);
                }
                
                reader.readAsDataURL(file);
            });
            
            // Validasi minimal 2 foto
            if (files.length < 2) {
                preview.innerHTML += '<div class="alert alert-warning mt-2">Minimal 2 foto diperlukan</div>';
            }
        }
    });

    // Form submission dengan AJAX
    document.getElementById('formTambahFasilitas').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const fotoFiles = document.getElementById('foto').files;
        
        // Validasi
        if (!formData.get('latitude') || !formData.get('longitude')) {
            alert('Silakan ambil koordinat lokasi terlebih dahulu');
            return;
        }
        
        if (fotoFiles.length < 2) {
            alert('Minimal 2 foto diperlukan untuk dokumentasi');
            return;
        }
        
        // Tambahkan tahun survey otomatis
        formData.append('tahun_survey', new Date().getFullYear());
        
        // Kirim data via AJAX
        $.ajax({
            url: '<?= base_url('api/fasilitas') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            },
            success: function(response) {
                if (response.success) {
                    alert('Data berhasil disimpan!');
                    document.getElementById('formTambahFasilitas').reset();
                    document.getElementById('fotoPreview').innerHTML = '';
                    document.getElementById('locationStatus').innerHTML = '';
                    
                    // Refresh peta dan tabel
                    refreshPeta();
                    refreshTabel();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error menyimpan data: ' + xhr.responseText);
            },
            complete: function() {
                $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Data');
            }
        });
    });
</script>