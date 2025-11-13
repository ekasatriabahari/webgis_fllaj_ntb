<div class="mb-3">
    <label>Kab / Kota:</label>
    <select id="filterKabKota" class="form-select form-select-sm" style="width:auto; display:inline-block;">
        <option value="">Semua</option>
    </select>

    <label class="ms-2">Kecamatan:</label>
    <select id="filterKecamatan" class="form-select form-select-sm" style="width:auto; display:inline-block;">
        <option value="">Semua</option>
    </select>

    <label class="ms-2">Kelurahan:</label>
    <select id="filterKelurahan" class="form-select form-select-sm" style="width:auto; display:inline-block;">
        <option value="">Semua</option>
    </select>
</div>

<div class="mb-3">
    <label>Kondisi:</label>
    <select id="filterKondisi" class="form-select form-select-sm" style="width:auto; display:inline-block;">
        <option value="">Semua</option>
        <option value="baik">Baik</option>
        <option value="rusak_ringan">Rusak Ringan</option>
        <option value="rusak_berat">Rusak Berat</option>
    </select>

    <label class="ms-2">Jenis Fasilitas:</label>
    <select id="filterJenis" class="form-select form-select-sm" style="width:auto; display:inline-block;">
        <!-- loaded via ajax -->
    </select>
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
                    let optKategori = '<option value="">Pilih Jenis Fasilitas</option>';
                    kategoriUnik.forEach(kat => {
                        optKategori += `<option value="${kat}">${kat}</option>`;
                    });
                    $('#filterJenis').html(optKategori);
                },
                error: (err) => {
                    console.log(err);
                }
            });
        })
    </script>
</div>

<div class="table-responsive">
    <table id="laporanTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Kab/Kota</th>
                <th rowspan="2">Kecamatan</th>
                <th rowspan="2">Kelurahan</th>
                <th rowspan="2">Nama Ruas</th>
                <th colspan="3" class="text-center">Kondisi</th>
                <th rowspan="2">Total Fasilitas</th>
            </tr>
            <tr>
                <th class="text-center">Baik</th>
                <th class="text-center">Rusak Ringan</th>
                <th class="text-center">Rusak Berat</th>
            </tr>
        </thead>
    </table>
</div>

<style>
    table.child-table {
        width: 100%;
        border-collapse: collapse;
    }

    table.child-table th,
    table.child-table td {
        padding: 6px 8px;
        vertical-align: top;
        border: 1px solid #ddd;
    }

    table.child-table td.nama-fasilitas {
        width: 150px;
        white-space: normal;
        word-wrap: break-word;
        word-break: break-word;
    }

    table.child-table thead {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    table.child-table tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>

<script>
$(document).ready(function() {

    // === Data untuk filter cascading ===
    let allData = []; 

    // === Inisialisasi DataTables ===
    const table = $('#laporanTable').DataTable({
        ajax: {
            url: "<?= site_url('api/laporan/fasilitas-report') ?>",
            data: function(d) {
                d.kab_kota = $('#filterKabKota').val();
                d.kecamatan = $('#filterKecamatan').val();
                d.kelurahan = $('#filterKelurahan').val();
                d.kondisi = $('#filterKondisi').val();
                d.jenis_fasilitas_id = $('#filterJenis').val();
            }
        },
        columns: [
            { className: 'dt-control', orderable: false, data: null, defaultContent: '' },
            { data: 'kab_kota' },
            { data: 'kecamatan' },
            { data: 'kelurahan' },
            { data: 'nama_ruas' },
            { data: 'total_baik' },
            { data: 'total_rusak_ringan' },
            { data: 'total_rusak_berat' },
            { data: 'total' }
        ],
        order: [[1, 'asc']],
        lengthMenu: [[25, 50, 100, 150 -1], [25, 50, 100, 150, "All"]],
        pageLength: 150,
        dom: 'Blfrtip',
        buttons: [
            { extend: 'excelHtml5', title: 'Laporan Fasilitas per Ruas' },
            { extend: 'pdfHtml5', title: 'Laporan Fasilitas per Ruas' },
            { extend: 'print', title: 'Laporan Fasilitas per Ruas' }
        ],
        initComplete: function(settings, json) {
            // Simpan semua data JSON untuk membangun filter chained
            allData = json.data || [];
            populateKabKota(allData);
        }
    });

    // === Populate Kab/Kota ===
    function populateKabKota(data) {
        const uniqueKab = [...new Set(data.map(d => d.kab_kota).filter(v => v && v !== '-'))];
        const kabSelect = $('#filterKabKota');
        kabSelect.empty().append(`<option value="">Semua</option>`);
        uniqueKab.forEach(k => kabSelect.append(`<option value="${k}">${k}</option>`));
    }

    // === Populate Kecamatan berdasarkan Kab/Kota ===
    function populateKecamatan(kab) {
        const uniqueKec = [...new Set(allData
            .filter(d => d.kab_kota === kab)
            .map(d => d.kecamatan)
            .filter(v => v && v !== '-'))];
        const kecSelect = $('#filterKecamatan');
        kecSelect.empty().append(`<option value="">Semua</option>`);
        uniqueKec.forEach(k => kecSelect.append(`<option value="${k}">${k}</option>`));
        $('#filterKelurahan').empty().append(`<option value="">Semua</option>`); // reset kelurahan
    }

    // === Populate Kelurahan berdasarkan Kecamatan ===
    function populateKelurahan(kab, kec) {
        const uniqueKel = [...new Set(allData
            .filter(d => d.kab_kota === kab && d.kecamatan === kec)
            .map(d => d.kelurahan)
            .filter(v => v && v !== '-'))];
        const kelSelect = $('#filterKelurahan');
        kelSelect.empty().append(`<option value="">Semua</option>`);
        uniqueKel.forEach(k => kelSelect.append(`<option value="${k}">${k}</option>`));
    }

    // === Event handler untuk chaining ===
    $('#filterKabKota').on('change', function() {
        const kab = $(this).val();
        populateKecamatan(kab);
        table.ajax.reload();
    });

    $('#filterKecamatan').on('change', function() {
        const kab = $('#filterKabKota').val();
        const kec = $(this).val();
        populateKelurahan(kab, kec);
        table.ajax.reload();
    });

    $('#filterKelurahan').on('change', function() {
        table.ajax.reload();
    });

    $('#filterKondisi, #filterJenis').on('change', function() {
        table.ajax.reload();
    });

    // === Fungsi format child rows ===
    function formatChildRow(rowData) {
        const safeId = btoa(rowData.nama_ruas).replace(/=/g, '');
        return `
            <table class="child-table table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nama Fasilitas</th>
                        <th>Kondisi</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <!-- <th>Foto</th> -->
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody id="child-${safeId}">
                    <tr><td colspan="6" class="text-center">Memuat data...</td></tr>
                </tbody>
            </table>`;
    }

    // === Expand child rows ===
    $('#laporanTable tbody').on('click', 'td.dt-control', function() {
        const tr = $(this).closest('tr');
        const row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            return;
        }

        row.child(formatChildRow(row.data())).show();
        tr.addClass('shown');

        const ruas = encodeURIComponent(row.data().nama_ruas);
        const safeId = btoa(row.data().nama_ruas).replace(/=/g, '');

        $.ajax({
            url: `<?= site_url('api/laporan/fasilitas-report/ruas/') ?>${ruas}`,
            data: {
                kondisi: $('#filterKondisi').val(),
                jenis_fasilitas_id: $('#filterJenis').val()
            },
            dataType: 'json',
            success: function(res) {
                const tbody = $(`#child-${safeId}`);
                tbody.empty();

                if (!res.data || res.data.length === 0) {
                    tbody.html(`<tr><td colspan="6" class="text-center">Tidak ada data fasilitas</td></tr>`);
                    return;
                }

                res.data.forEach(f => {
                    let fotoRaw = (f.foto || '').replace(/&quot;/g, '"').trim();
                    let fotoBtn = '-';
                    if (fotoRaw && fotoRaw !== '[]') {
                        fotoBtn = `
                            <button class="btn btn-sm btn-primary lihat-foto"
                                data-foto='${fotoRaw.replace(/'/g, "&apos;")}'
                                data-tahun='${f.tahun_survey}'>
                                <i class="fas fa-image"></i> Lihat Foto
                            </button>`;
                    }

                    tbody.append(`
                        <tr>
                            <td class="nama-fasilitas">${f.nama_fasilitas}</td>
                            <td>${f.kondisi}</td>
                            <td>${f.latitude}</td>
                            <td>${f.longitude}</td>
                            <!-- <td>${fotoBtn}</td> -->
                            <td>${f.catatan ?? '-'}</td>
                        </tr>
                    `);
                });
            },
            error: function() {
                $(`#child-${safeId}`).html(`<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>`);
            }
        });
    });

    // === SweetAlert2 preview foto ===
    $(document).on('click', '.lihat-foto', function() {
        let rawFoto = $(this).data('foto');
        let tahun = $(this).data('tahun');
        let pathBase = `<?= base_url('uploads/images/fasilitas/') ?>${tahun}/`;

        try {
            if (typeof rawFoto === 'string') rawFoto = rawFoto.replace(/'/g, '"');
            const files = JSON.parse(rawFoto);
            if (!Array.isArray(files) || files.length === 0) {
                Swal.fire('Tidak ada foto', 'Data foto kosong.', 'info');
                return;
            }

            let html = '<div style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;">';
            files.forEach(f => {
                html += `<img src="${pathBase + f}" 
                            style="max-width:200px;max-height:200px;object-fit:cover;border-radius:8px;cursor:zoom-in;" 
                            onclick="window.open('${pathBase + f}', '_blank')">`;
            });
            html += '</div>';

            Swal.fire({
                title: 'Foto Fasilitas',
                html: html,
                width: '80%',
                showConfirmButton: false,
                showCloseButton: true,
            });

        } catch (e) {
            Swal.fire('Error', 'Format data foto tidak valid.', 'error');
        }
    });
});
</script>
