<div class="row" id="cardRencana">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-table"></i> Tabel Rencana</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tableRencana">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Jenis Fasilitas</th>
                                <th>Nama Fasilitas</th>
                                <th>Koordinat</th>
                                <th>Tahun Survey</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
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
                            <label for="rencana_kode_fasilitas">Kode Fasilitas:</label>
                            <input type="text" name="rencana_kode_fasilitas" id="rencana_kode_fasilitas" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rencana_nama_fasilitas">Nama Fasilitas:</label>
                            <input type="text" name="rencana_nama_fasilitas" id="rencana_nama_fasilitas" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rencana_latitude">Latitude:</label>
                            <input type="text" name="rencana_latitude" id="rencana_latitude" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rencana_longitude">Longitude:</label>
                            <input type="text" name="rencana_longitude" id="rencana_longitude" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rencana_catatan">Catatan:</label>
                            <input type="text" name="rencana_catatan" id="rencana_catatan" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div id="mapRencana" class="mt-2" style="width: 100%; height: 300px;"></div>
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
                        return `<button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail" onclick="viewDetailRencana('${row.id}')"><i class="fas fa-eye"></i></button>`;
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

    let mapRencana = null; // buat variabel global agar bisa diakses ulang
    let markerRencana = null;
    function viewDetailRencana(id){
        $.ajax({
            url: '<?= site_url("api/rencana/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: (response) => {
                const data = response.data;

                // Isi data modal
                $('#rencana_kode_fasilitas').val(data.kode_fasilitas);
                $('#rencana_nama_fasilitas').val(data.nama_fasilitas);
                $('#rencana_latitude').val(data.latitude);
                $('#rencana_longitude').val(data.longitude);
                $('#rencana_catatan').val(data.catatan);

                // Cek apakah map sudah pernah dibuat
                if (map) {
                    map.remove(); // 🧹 hapus instance lama agar tidak error
                }

                // Inisialisasi ulang map
                map = L.map('mapRencana').setView([data.latitude, data.longitude], 18);

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
</script>