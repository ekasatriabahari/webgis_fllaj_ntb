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
                            <label for="kondisi">Kondisi</label>
                            <select name="kondisi" id="kondisi" class="form-control">
                                <option value="">--Semua Kondisi--</option>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
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
                    <table class="table table-bordered table-striped" id="tableFasilitas">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Fasilitas</th> <!-- tampilkan kode dan jenis fasilitas cth : RMB - Rambu Larangan -->
                                <th>Nama Fasilitas</th>
                                <th>Lokasi Koordinat</th>
                                <th width="10%">Kondisi</th>
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

<script>
    $(() => {
        initData();
    });

    function initData()
    {
        $('#tableFasilitas').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url("api/fasilitas") ?>',
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
                    url: '<?= site_url("api/fasilitas/") ?>' + id,
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
                                $('#tableFasilitas').DataTable().ajax.reload(null, false);
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