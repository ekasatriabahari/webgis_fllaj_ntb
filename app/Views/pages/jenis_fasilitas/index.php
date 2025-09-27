<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-3"><?= $title ?></h1>
    </div>
</div>
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" onclick="tambahData()">Tambah Data</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="tableJenisFasilitas">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th>
                            <select id="filterKategori" class="form-control form-control-sm mt-1">
                                <option value="">Semua Kategori</option>
                                <option value="Rambu">Rambu</option>
                                <option value="Marka">Marka</option>
                                <option value="Pagar Pengaman">Pagar Pengaman</option>
                                <option value="Penanda Jalan">Penanda Jalan</option>
                                <option value="Penerangan">Penerangan</option>
                                <option value="Pemelandai">Pemelandai</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </th>
                        <th>Jenis</th>
                        <th width="35%">Deskripsi</th>
                        <th width="10%">#</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Jenis Fasilitas Modal -->
<div class="modal fade" id="jenisFasilitasModal" tabindex="-1" aria-labelledby="jenisFasilitasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jenisFasilitasModalLabel">Tambah Jenis Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formJenisFasilitas">
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="hidden" id="id" name="id">
                        <select class="form-select" aria-label="Default select example" name="kategori" id="kategori" required>
                            <option value="Rambu" selected>Rambu</option>
                            <option value="Marka">Marka</option>
                            <option value="Pagar Pengaman">Pagar Pengaman (Guardrail)</option>
                            <option value="Penerangan">Penerangan</option>
                            <option value="Pemelandai">Pemelandai</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis</label>
                        <input type="text" class="form-control" id="jenis" name="jenis" required/>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Opsional..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(() => {
        getData();
        // Saat dropdown filter status pengajuan berubah
        $('#filterKategori').on('change', function () {
            let val = $(this).val();
            $('#tableJenisFasilitas').DataTable().column(1).search(val).draw(); // Array Kolom ke-1 = "Kategori"
        });

        $('#formJenisFasilitas').submit(function(e) {
            e.preventDefault();
            saveData();
        });
    });

    function tambahData()
    {
        $('#jenisFasilitasModal .modal-title').text('Tambah Jenis Fasilitas');
        $('#jenisFasilitasModal').modal('show');
    }

    function getData()
    {
        $('#tableJenisFasilitas').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url("api/jenis_fasilitas") ?>',
                type: 'GET',
            },
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: null, render: function(data, type, row) {
                    return `${row.kategori} - <span class="badge rounded-pill bg-primary">${row.kode_fasilitas}</span>`;
                }},
                { data: 'jenis'},
                { data: 'deskripsi' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail" onclick="viewDetail('${row.id}')"><i class="fas fa-pencil-alt"></i></button>
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

    function saveData()
    {
        var id = $('#id').val();
        var method = id ? 'PUT' : 'POST';
        var data = $('#formJenisFasilitas').serialize();
        $.ajax({
            url: '<?= site_url("api/jenis_fasilitas") ?>',
            type: method,
            data: data,
            beforeSend: () => {
                Swal.fire({
                    title: 'Please Wait...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message
                }).then(() => {
                    $('#jenisFasilitasModal').modal('hide');
                    $('#formJenisFasilitas')[0].reset();
                    $('#tableJenisFasilitas').DataTable().destroy();
                    getData();
                });
            },
            error: (err) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.responseJSON.message  
                });
            }
        });
    }

    function viewDetail($id)
    {
        $('#jenisFasilitasModal .modal-title').text('Ubah Jenis Fasilitas');
        $.ajax({
            url: '<?= site_url("api/jenis_fasilitas") ?>/'+$id,
            type: 'GET',
            success: function(response) {
                $('#id').val(response.id);
                $('#kategori').val(response.kategori);
                $('#jenis').val(response.jenis);
                $('#deskripsi').val(response.deskripsi);
                $('#jenisFasilitasModal').modal('show');
            }
        });
    }

    function removeData(id)
    {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= site_url("api/jenis_fasilitas") ?>/'+id,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        }).then(() => {
                            $('#tableJenisFasilitas').DataTable().ajax.reload(null, false);
                        });
                    },
                    error: (err) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.responseJSON.message  
                        });
                    }
                });
            }
        });
    }

</script>