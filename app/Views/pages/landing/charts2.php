<div class="row mt-3" id="chartRow">
</div>

<script>
$(document).ready(function() {
    // Ambil data dari API
    $.ajax({
        url: "<?= site_url('api/charts/kondisi-pie-per-kota') ?>",
        dataType: 'json',
        success: function(res) {
            if (res.status !== 'success' || !res.data) {
                $('#kondisiPerKotaContainer').html('<div class="text-center text-muted">Tidak ada data untuk ditampilkan.</div>');
                return;
            }

            // Loop per kabupaten untuk menampilkan pie chart dinamis
            res.data.forEach((item, index) => {
                const containerId = `pieContainer-${index}`;
                $('#chartRow').append(`
                    <div class="col-md-4 col-sm-12 mb-2">
                        <div id="${containerId}" style="height: 400px; width: 100%;"></div>
                    </div>
                `);

                Highcharts.chart(containerId, {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: `Persentase Kondisi<br>${item.kab_kota}`,
                        align: 'center'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Total: {point.y}'
                    },
                    accessibility: {
                        point: { valueSuffix: '%' }
                    },
                    exporting: {
                        chartOptions: {
                            chart: {
                                style: {
                                    fontFamily: 'monospace'
                                }
                            }
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                style: { fontSize: '12px' }
                            }
                        }
                    },
                    series: [{
                        name: 'Persentase',
                        colorByPoint: true,
                        data: item.data
                    }],
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    },
                    credits: { enabled: false }
                    
                });
            });
        },
        error: function() {
            $('#kondisiPerKotaContainer').html('<div class="text-center text-danger">Gagal memuat data grafik.</div>');
        }
    });
});
</script>
