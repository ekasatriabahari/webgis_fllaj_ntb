<div id="backupDB" class="card p-3 shadow-sm">
    <div class="row mb-3">
        <div class="col-md-6">
            <h5 class="fw-bold mb-2">Backup & Restore Database</h5>
            <button type="button" class="btn btn-primary" id="backupDBBtn">
                <i class="fas fa-gears"></i> Backup Database
            </button>
            <small class="text-muted d-block mt-2">
                Klik tombol di atas untuk membuat cadangan database. Riwayat backup dan import akan muncul di bawah.
            </small>
        </div>
        <div class="col-md-6">
            <form id="importForm" enctype="multipart/form-data">
                <label for="importFile" class="form-label fw-semibold mb-1">Import Database (.sql)</label>
                <div class="input-group">
                    <input type="file" name="importFile" id="importFile" accept=".sql" class="form-control" required>
                    <button class="btn btn-success" type="submit">
                        <i class="fas fa-upload"></i> Import
                    </button>
                </div>
                <small class="text-muted">Pastikan file SQL berasal dari backup sistem ini.</small>
            </form>
        </div>
    </div>

    <hr>

    <div id="backupDBLog">
        <h6 class="fw-bold mb-2">Log Aktivitas Database</h6>
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tipe</th>
                    <th>Nama File</th>
                    <th>Catatan</th>
                    <th>Dibuat Oleh</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="logTableBody">
                <tr><td colspan="7" class="text-center text-muted">Belum ada log</td></tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        loadLog();

        // Tombol backup
        $('#backupDBBtn').on('click', function() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Lanjutkan backup database sekarang?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('<?= site_url('api/database/backup') ?>', function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Backup berhasil dibuat!'
                        }).then(() => loadLog());
                    }).fail(() => Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal melakukan backup.'
                    }));
                }
            });
        });

        // Form import SQL
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Import database akan menimpa data lama. Lanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData(this);
                    $.ajax({
                        url: '<?= site_url('api/database/import') ?>',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Import berhasil!'
                            }).then(() => loadLog());
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal import database!'
                            });
                        }
                    });
                }
            });
        });

        // Muat log dari server
        function loadLog() {
            $.getJSON('<?= site_url('api/database/logs') ?>', function(data) {
                let rows = '';
                if (data.length === 0) {
                    rows = `<tr><td colspan="7" class="text-center text-muted">Belum ada log</td></tr>`;
                } else {
                    data.forEach((log, i) => {
                        rows += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${log.type}</td>
                                <td>${log.filename ?? '-'}</td>
                                <td>${log.notes ?? '-'}</td>
                                <td>${log.created_by ?? '-'}</td>
                                <td>${log.created_at}</td>
                                <td>
                                    ${log.type === 'backup' ? `<a href="<?= base_url('backups/') ?>${log.filename}" class="btn btn-sm btn-outline-primary">Download</a>` : '-'}
                                </td>
                            </tr>`;
                    });
                }
                $('#logTableBody').html(rows);
            });
        }
    });
</script>
