<div class="row mt-3" id="charts">
    <div class="col-md-4 col-sm-12">
        <div id="kondisiChartContainer" style="height: 550px; width: 100%;"></div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div id="perbandinganChartContainer" style="height: 550px; width: 100%;"></div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div id="perKotaChartContainer" style="height: 550px; width: 100%;"></div>
    </div>
</div>

<script>
    $(() => {
        initKondisiChart();
        initPerbandinganChart();
        initPerbandinganKabKotaChart();
    });

    function initKondisiChart() {
        $.ajax({
            url: "<?= site_url('api/charts/kondisi'); ?>",
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    Highcharts.chart('kondisiChartContainer', {
                        chart: {
                            type: 'pie',
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            borderRadius: 10
                        },
                        title: {
                            text: 'Kondisi Fasilitas',
                            style: {
                                fontSize: '12px',
                                fontWeight: 'bold',
                                color: '#333'
                            }
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: { valueSuffix: '%' }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: false,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.y}%',
                                    style: { fontSize: '12px' }
                                },
                                showInLegend: true
                            }
                        },
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom',
                            floating: false
                        },
                        series: [{
                            name: 'Kondisi',
                            colorByPoint: true,
                            data: response.data
                        }],
                        credits: { enabled: false }
                    });
                } else {
                    console.error("Gagal memuat data chart:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan AJAX:", error);
            }
        });
    }

    function initPerbandinganChart() {
        $.ajax({
            url: "<?= site_url('api/charts/eksisting-rencana'); ?>",
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    Highcharts.chart('perbandinganChartContainer', {
                        chart: {
                            type: 'pie',
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            borderRadius: 10
                        },
                        title: {
                            text: 'Perbandingan Data Fasilitas vs Rencana',
                            style: {
                                fontSize: '12px',
                                fontWeight: 'bold',
                                color: '#333'
                            }
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: { valueSuffix: '%' }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: false,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.y}%',
                                    style: { fontSize: '12px' }
                                },
                                showInLegend: true
                            }
                        },
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom',
                            floating: false
                        },
                        series: [{
                            name: 'Persentase',
                            colorByPoint: true,
                            data: response.data
                        }],
                        credits: { enabled: false }
                    });
                } else {
                    console.error("Gagal memuat data chart:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan AJAX:", error);
            }
        });
    }

    function initPerbandinganKabKotaChart() {
        $.ajax({
            url: "<?= site_url('api/charts/fasilitas-rencana-per-kab-kota'); ?>",
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    Highcharts.chart('perKotaChartContainer', {
                        chart: {
                            type: 'column',
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            borderRadius: 10
                        },
                        title: {
                            text: 'Perbandingan Jumlah Fasilitas dan Rencana per Kabupaten/Kota',
                            style: {
                                fontSize: '18px',
                                fontWeight: 'bold',
                                color: '#333'
                            }
                        },
                        xAxis: {
                            categories: response.categories,
                            crosshair: true,
                            title: { text: 'Kabupaten / Kota' }
                        },
                        yAxis: {
                            min: 0,
                            title: { text: 'Jumlah Data' }
                        },
                        tooltip: {
                            shared: true,
                            useHTML: true,
                            headerFormat: '<b>{point.key}</b><br/>',
                            pointFormat: '{series.name}: <b>{point.y}</b><br/>'
                        },
                        plotOptions: {
                            column: {
                                borderRadius: 4,
                                pointPadding: 0.2,
                                borderWidth: 0,
                                dataLabels: { enabled: true }
                            }
                        },
                        series: response.series,
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        },
                        credits: { enabled: false }
                    });
                } else {
                    console.error("Gagal memuat data chart:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }
</script>