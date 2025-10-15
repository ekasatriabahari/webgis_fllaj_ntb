<div class="row mt-3" id="charts">
    <div class="col-md-3 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-list"></i> Filter Data</h5>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    <div class="form-group">
                        <label for="kategoriFasilitas">Kategori Fasilitas Jalan</label>
                        <select name="kategoriFasilitas" id="kategoriFasilitas" class="form-control">
                            <!-- loaded via ajax -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kondisi">Kondisi</label>
                        <select name="jenisFasilitas" id="jenisFasilitas" class="form-control">
                            <option value="">Semua Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="sedang">Rusak Sedang</option>
                            <option value="berat">Rusak Berat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun Survey</label>
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="2025" selected>2025</option>
                            <option value="2026">2026</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fa-solid fa-chart-pie"></i> Presentase Kondisi Fasilitas</h5>
            </div>
            <div class="card-body">
                <div>
                    <div class="form-group">
                        <label for="chartFasilitas">Kategori Fasilitas</label>
                        <select name="chartFasilitas" id="chartFasilitas" class="form-control">
                            <!-- loaded via ajax -->
                        </select>
                    </div>
                </div>
                <div class="mt-3" id="kondisiChartContainer" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>
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
                $('#kategoriFasilitas, #chartFasilitas').html(optKategori);
            },
            error: (err) => {
                console.log(err);
            }
        });
    });

    function initKondisiChart(data) {
        Highcharts.chart('kondisiChartContainer', {
            chart: {
                type: 'pie',
                backgroundColor: '#f8f9fa',
                borderRadius: 10
            },
            title: {
                text: 'Kondisi Rambu Lalu Lintas',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            },
            // subtitle: {
            //     text: 'Data kondisi rambu berdasarkan tingkat kerusakan',
            //     style: {
            //         color: '#666'
            //     }
            // },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false,
                        format: '<b>{point.name}</b>: {point.y}%',
                        style: {
                            fontSize: '12px'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Kondisi',
                colorByPoint: true,
                data: data
            }],
            credits: {
                enabled: false
            }
        });
    }
</script>