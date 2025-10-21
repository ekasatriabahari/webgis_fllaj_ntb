<div class="row mt-3" id="charts">
    <div class="col-md-3 col-sm-12">
        <div  id="kondisiChartContainer" style="height: 450px; width: 100%;"></div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div  id="perbandinganChartContainer" style="height: 450px; width: 100%;"></div>
    </div>
</div>
<script>
    $(() => {
        initKondisiChart();
        initPerbandinganChart();
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
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: false,
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
</script>