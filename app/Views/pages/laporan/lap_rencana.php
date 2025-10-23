<div class="row mb-3">
    <div class="col-md-3">
        <label for="filterKabKotaRencana" class="form-label">Kab/Kota</label>
        <select id="filterKabKotaRencana" class="form-select">
            <option value="">Semua</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterKecamatanRencana" class="form-label">Kecamatan</label>
        <select id="filterKecamatanRencana" class="form-select">
            <option value="">Semua</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterKelurahanRencana" class="form-label">Kelurahan</label>
        <select id="filterKelurahanRencana" class="form-select">
            <option value="">Semua</option>
        </select>
    </div>
</div>

<div class="table-responsive">
    <table id="laporanTableRencana" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>Kab/Kota</th>
                <th>Kecamatan</th>
                <th>Kelurahan</th>
                <th>Nama Ruas</th>
                <th>Total Fasilitas</th>
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
    let allDataRencana = [];

    // === Inisialisasi DataTable ===
    const tableRencana = $('#laporanTableRencana').DataTable({
        ajax: {
            url: "<?= site_url('api/laporan/rencana-report') ?>",
            data: function(d) {
                d.kab_kota = $('#filterKabKotaRencana').val();
                d.kecamatan = $('#filterKecamatanRencana').val();
                d.kelurahan = $('#filterKelurahanRencana').val();
            }
        },
        columns: [
            { className: 'dt-control', orderable: false, data: null, defaultContent: '' },
            { data: 'kab_kota' },
            { data: 'kecamatan' },
            { data: 'kelurahan' },
            { data: 'nama_ruas' },
            { data: 'total' }
        ],
        order: [[1, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', title: 'Laporan Rencana per Ruas' },
            { extend: 'pdfHtml5', title: 'Laporan Rencana per Ruas' },
            { extend: 'print', title: 'Laporan Rencana per Ruas' }
        ],
        initComplete: function(settings, json) {
            allDataRencana = json.data || [];
            populateKabKotaRencana(allDataRencana);
        }
    });

    // === Populate dropdown berantai ===
    function populateKabKotaRencana(data) {
        const uniqueKab = [...new Set(data.map(d => d.kab_kota).filter(v => v && v !== '-'))];
        const select = $('#filterKabKotaRencana');
        select.empty().append(`<option value="">Semua</option>`);
        uniqueKab.forEach(k => select.append(`<option value="${k}">${k}</option>`));
    }

    function populateKecamatanRencana(kab) {
        const uniqueKec = [...new Set(allDataRencana
            .filter(d => d.kab_kota === kab)
            .map(d => d.kecamatan)
            .filter(v => v && v !== '-'))];
        const select = $('#filterKecamatanRencana');
        select.empty().append(`<option value="">Semua</option>`);
        uniqueKec.forEach(k => select.append(`<option value="${k}">${k}</option>`));
        $('#filterKelurahanRencana').empty().append(`<option value="">Semua</option>`);
    }

    function populateKelurahanRencana(kab, kec) {
        const uniqueKel = [...new Set(allDataRencana
            .filter(d => d.kab_kota === kab && d.kecamatan === kec)
            .map(d => d.kelurahan)
            .filter(v => v && v !== '-'))];
        const select = $('#filterKelurahanRencana');
        select.empty().append(`<option value="">Semua</option>`);
        uniqueKel.forEach(k => select.append(`<option value="${k}">${k}</option>`));
    }

    $('#filterKabKotaRencana').on('change', function() {
        populateKecamatanRencana($(this).val());
        tableRencana.ajax.reload();
    });

    $('#filterKecamatanRencana').on('change', function() {
        populateKelurahanRencana($('#filterKabKotaRencana').val(), $(this).val());
        tableRencana.ajax.reload();
    });

    $('#filterKelurahanRencana').on('change', function() {
        tableRencana.ajax.reload();
    });

    // === Child Rows ===
    function formatChildRowRencana(rowData) {
        const safeId = btoa(rowData.nama_ruas).replace(/=/g, '');
        return `
            <table class="child-table table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nama Fasilitas</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Foto</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody id="child-${safeId}">
                    <tr><td colspan="6" class="text-center">Memuat data...</td></tr>
                </tbody>
            </table>`;
    }

    $('#laporanTableRencana tbody').on('click', 'td.dt-control', function() {
        const tr = $(this).closest('tr');
        const row = tableRencana.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            return;
        }

        row.child(formatChildRowRencana(row.data())).show();
        tr.addClass('shown');

        const ruas = encodeURIComponent(row.data().nama_ruas);
        const safeId = btoa(row.data().nama_ruas).replace(/=/g, '');

        $.ajax({
            url: `<?= site_url('api/laporan/rencana-report/ruas/') ?>${ruas}`,
            dataType: 'json',
            timeout: 10000,
            success: function(res) {
                const tbody = $(`#child-${safeId}`);
                tbody.empty();

                if (!res.data || res.data.length === 0) {
                    tbody.html(`<tr><td colspan="6" class="text-center">Tidak ada data Rencana</td></tr>`);
                    return;
                }

                res.data.forEach(f => {
                    let fotoRaw = (f.foto || '').replace(/&quot;/g, '"').trim();
                    let fotoBtn = '-';
                    if (fotoRaw && fotoRaw !== '[]') {
                        fotoBtn = `
                            <button class="btn btn-sm btn-primary lihat-foto-rencana"
                                data-foto='${fotoRaw.replace(/'/g, "&apos;")}'
                                data-tahun='${f.tahun_survey}'>
                                <i class="fas fa-image"></i> Lihat Foto
                            </button>`;
                    }

                    tbody.append(`
                        <tr>
                            <td class="nama-fasilitas">${f.nama_fasilitas}</td>
                            <td>${f.latitude}</td>
                            <td>${f.longitude}</td>
                            <td>${fotoBtn}</td>
                            <td>${f.catatan ?? '-'}</td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status) {
                const tbody = $(`#child-${safeId}`);
                tbody.html(`<tr><td colspan="6" class="text-center text-danger">Gagal memuat data (${status})</td></tr>`);
            }
        });
    });

    // === SweetAlert2 Preview Foto Rencana ===
    $(document).on('click', '.lihat-foto-rencana', function() {
        let rawFoto = $(this).data('foto');
        let tahun = $(this).data('tahun');
        let pathBase = `<?= base_url('uploads/images/rencana/') ?>${tahun}/`;

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
                title: 'Foto Fasilitas Rencana',
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
