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
                        <button class="nav-link active" id="fasilitas-tab" data-bs-toggle="pill" data-bs-target="#lapFasilitas" type="button" role="tab" aria-controls="lapFasilitas" aria-selected="true">
                            <i class="far fa-folder"></i> Laporan Fasilitas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                           <button class="nav-link" id="rencana-tab" data-bs-toggle="pill" data-bs-target="#lapRencana" type="button" role="tab" aria-controls="lapRencana" aria-selected="false">
                            <i class="far fa-folder-open"></i> Laporan Rencana
                        </button>
                    </li>
                </ul>
                <hr>
                <!-- Tab Content -->
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="lapFasilitas" role="tabpanel" aria-labelledby="fasilitas-tab">
                        <?php include('lap_fasilitas.php'); ?>
                    </div>
                    <div class="tab-pane fade" id="lapRencana" role="tabpanel" aria-labelledby="rencana-tab">
                        <?php include('lap_rencana.php'); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>

</script>