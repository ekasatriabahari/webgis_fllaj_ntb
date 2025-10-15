<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-3"><?= $title ?></h1>
    </div>
</div>
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <form id="filterForm">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label for="kategori">Kategori Fasilitas</label>
                            <select name="kategori" id="kategori" class="form-control">
                                <!-- load via ajax -->
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label for="tahun">Tahun Survey</label>
                            <select name="tahun" id="tahun" class="form-control">
                                <option value="2025" selected>2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <script>
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
                            $('#kategori').html(optKategori);
                        },
                        error: (err) => {
                            console.log(err);
                        }
                    });
                })
            </script>

            <!-- table fasilitas -->
             <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped" id="tableRencana">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Fasilitas</th>
                                <th>Nama Fasilitas</th>
                                <th>Lokasi Koordinat</th>
                                <th width="5%">Tahun Survey</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- load via ajax -->
                        </tbody>
                    </table>
                </div>
             </div>
        </div>
    </div>
</div>

<!-- modal detail -->
<div class="modal fade" id="modalDetailRencana" tabindex="-1" role="dialog" aria-labelledby="modalDetailRencanaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailRencanaLabel">Detail Rencana</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_fasilitas">Kode Fasilitas:</label>
                            <input type="text" name="kode_fasilitas" id="kode_fasilitas" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_fasilitas">Nama Fasilitas:</label>
                            <input type="text" name="nama_fasilitas" id="nama_fasilitas" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="latitude">Latitude:</label>
                            <input type="text" name="latitude" id="latitude" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="longitude">Longitude:</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="catatan">Catatan:</label>
                            <input type="text" name="catatan" id="catatan" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div id="map" class="mt-2" style="width: 100%; height: 300px;"></div>
                <!-- Leaflet CSS -->
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

                <!-- Leaflet JavaScript -->
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(() => {
        initData();
    });

    function initData()
    {
        $('#tableRencana').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url("api/rencana") ?>',
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
                    return row.tahun_survey;
                }},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail" onclick="viewDetail('${row.id}')"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove" onclick="removeData('${row.id}')"><i class="fas fa-trash-alt"></i></button>`;
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
    }

    let map = null; // buat variabel global agar bisa diakses ulang
    let marker = null;
    function viewDetail(id){
        $.ajax({
            url: '<?= site_url("api/fasilitas/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: (response) => {
                const data = response.data[0];

                // Isi data modal
                $('#kode_fasilitas').val(data.kode_fasilitas);
                $('#nama_fasilitas').val(data.nama_fasilitas);
                $('#latitude').val(data.latitude);
                $('#longitude').val(data.longitude);
                $('#catatan').val(data.catatan);

                // Cek apakah map sudah pernah dibuat
                if (map) {
                    map.remove(); // 🧹 hapus instance lama agar tidak error
                }

                // Inisialisasi ulang map
                map = L.map('map').setView([data.latitude, data.longitude], 18);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                    subdomains: ['a', 'b', 'c']
                }).addTo(map);

                marker = L.marker([data.latitude, data.longitude]).addTo(map);

                // Tampilkan modal
                $('#modalDetailRencana').modal('show');

                // 🕐 Setelah modal ditampilkan sepenuhnya, perbaiki ukuran map
                $('#modalDetailRencana').on('shown.bs.modal', function () {
                    map.invalidateSize();
                });
            },
            error: (err) => {
                console.log(err);
            }
        });
    }

    function removeData(id){
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
                    url: '<?= site_url("api/rencana/") ?>' + id,
                    type: 'DELETE',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: (response) => {
                        if(response.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Success', 
                                text: response.message
                            }).then(() => {
                                $('#tableRencana').DataTable().ajax.reload(null, false);
                            });                            
                        }
                    },
                    error: (err) => {
                        console.log(err);
                    }
                });
            }
        })
    }
</script>