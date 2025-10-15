<!-- Script Konversi -->
<script src="<?= base_url('assets/template/js/') ?>togeojson.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><?= $title; ?></h5>
            </div>
            <div class="card-body">
                <!-- Nav Pills -->
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="import-tab" data-bs-toggle="pill" data-bs-target="#importKMZ" type="button" role="tab" aria-controls="importKMZ" aria-selected="true">
                            <i class="fas fa-upload"></i> Import KMZ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                           <button class="nav-link" id="backup-tab" data-bs-toggle="pill" data-bs-target="#backupDB" type="button" role="tab" aria-controls="backupDB" aria-selected="false">
                            <i class="fas fa-download"></i> Backup Database
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="importKMZ" role="tabpanel" aria-labelledby="import-tab">
                        <?php include('import_kmz.php'); ?>
                    </div>
                    <div class="tab-pane fade" id="backupDB" role="tabpanel" aria-labelledby="backup-tab">
                        <?php include('backup_db.php'); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>

</script>