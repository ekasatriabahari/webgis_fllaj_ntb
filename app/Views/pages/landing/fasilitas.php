<div class="row mt-3" id="cardFasilitas">
    <div class="col-md-12 col-sm-12">
        <div class="card mt-3 mb-5">
            <div class="card-header">
                <h5><i class="fas fa-table"></i> Tabel Fasilitas</h5>
            </div>
            <div class="card-body">
                <table id="tableKondisi" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode Fasilitas</th>
                            <th>Nama Fasilitas</th>
                            <th>Koordinat</th>
                            <th>
                                <select name="kondisi" id="kondisi" class="form-control">
                                    <option value="">Semua Kondisi</option>
                                    <option value="baik">Baik</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                </select>
                            </th>
                            <th>Tahun Survey</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- modal detail fasilitas -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <label for="kode_fasilitas">Kode Fasilitas:</label>
                        <input type="text" name="kode_fasilitas" id="kode_fasilitas" class="form-control" disabled>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <label for="nama_fasilitas">Nama Fasilitas:</label>
                        <input type="text" name="nama_fasilitas" id="nama_fasilitas" class="form-control" disabled>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <label for="" class="me-2">Koordinat:</label>
                        <div class=" d-flex align-items-center">
                            <input type="text" name="latitude" id="latitude" class="form-control form-control-inline" disabled>
                            <input type="text" name="longitude" id="longitude" class="form-control form-control-inline" disabled>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <label for="tahun_survey">Tahun Survey:</label>
                        <input type="text" name="tahun_survey" id="tahun_survey" class="form-control" disabled>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <label for="catatan">Catatan:</label>
                        <input type="text" name="catatan" id="catatan" class="form-control" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <label for="foto_container">Foto:</label>
                        <div id="foto_container"></div>
                    </div>
                </div>
                <div class="row mt-3 py-2 px-2">
                    <div id="mapDetail"  style="height: 300px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var tableKondisi = $('#tableKondisi').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url("api/kondisi-fasilitas") ?>',
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
                    return row.kondisi =='baik' ? `<span class="badge rounded-pill bg-success">${row.kondisi}</span>` : row.kondisi == 'rusak_ringan' ? `<span class="badge rounded-pill bg-warning">${row.kondisi}</span>` : `<span class="badge rounded-pill bg-danger">${row.kondisi}</span>`;
                }},
                { data: null, render: function(data, type, row) {
                    return row.tahun_survey;
                }},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail" onclick="viewDetail('${row.id}')"><i class="fas fa-pencil-alt"></i></button>`;
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

        $('#kondisi').on('change', function () {
            let val = $(this).val();
            $('#tableKondisi').DataTable().column(4).search(val).draw(); // Array Kolom ke-4 = "Kondisi"
        });
    });

    var mapDetail = L.map('mapDetail').setView([-8.6529, 117.3616], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapDetail);
    function viewDetail(id)
    {
        var url = "<?= site_url('api/fasilitas/') ?>" + id;
        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            beforeSend: () => {
                Swal.fire({
                    title: 'Loading..',
                    html: 'Menampilkan Data',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
            },
            success: (response) => {
                Swal.close();
                if (response.success) {
                    const data = response.data[0];
                    $('#kode_fasilitas').val(`${data.kode_fasilitas} - ${data.jenis}`);
                    $('#nama_fasilitas').val(data.nama_fasilitas);
                    $('#tahun_survey').val(data.tahun_survey);
                    $('#catatan').val(data.catatan);
                    $('#latitude').val(data.latitude);
                    $('#longitude').val(data.longitude);
                    let fotoPreviewHTML = '';
                    let fotos = JSON.parse(data.foto);
                    if (fotos) {
                        fotos.forEach(foto => {
                            fotoPreviewHTML += `<img onclick="previewFoto('<?= base_url('uploads/images/fasilitas/') ?>${data.tahun_survey}/${foto}', '${data.nama_fasilitas}')" src="<?= base_url('uploads/images/fasilitas/') ?>${data.tahun_survey}/${foto}" class="img-thumbnail" style="width: 100px; height: 100px; margin-right: 10px;">`;
                        });
                    }
                    $('#foto_container').html(fotoPreviewHTML);
                    
                    // Peta
                    var icon = L.divIcon({
                        className: "custom-marker",
                        html: `<div class="dot" style="background:${getColorByJenis(data.jenis)};"></div>`,
                        iconSize: [18, 18],
                        iconAnchor: [9, 9]
                    });

                    // hapus marker lama biar tidak numpuk
                    if (typeof marker !== 'undefined') {
                        mapDetail.removeLayer(marker);
                    }

                    marker = L.marker([data.latitude, data.longitude], {icon: icon} ).addTo(mapDetail);
                    mapDetail.flyTo([data.latitude, data.longitude], 14);
                    
                    $('#detailModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.data
                    });
                }
            },
            error: (err) => {
                console.log(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.responseText
                });
            }
        })
    }

    $('#detailModal').on('shown.bs.modal', function () {
        mapDetail.invalidateSize();
    });
</script>