<div class="flex flex-col items-center justify-between w-full h-full p-4 bg-white rounded shadow">
    <span class="text-3xl italic font-bold">Fabricantes</span>
    <div id="chartFab" class="w-full h-24 bg-white">
        {{-- The Master doesn't talk, he acts. --}}
    </div>
</div>

<script>
    var labels = @json($data['data']['labels']);
    var datasets = @json($data['data']['datasets']);
    var horizontal = @json($data['horizontal'] ?? false);
    var type = @json($data['type'] ?? 'bar');

    var total = 150;
    var options = {
        series: datasets,
        labels: labels,
        chart: {
            type: 'donut',
        },
        legend: {
            show: true,
            showForSingleSeries: false,
            showForNullSeries: true,
            showForZeroSeries: true,
            position: 'top',
            horizontalAlign: 'center',
            floating: false,
            fontSize: '14px',
            fontFamily: 'Helvetica, Arial',
            fontWeight: 400,
            formatter: undefined,
            inverseOrder: false,
            width: undefined,
            height: undefined,
            tooltipHoverFormatter: undefined,
            customLegendItems: [],
            clusterGroupedSeries: true,
            clusterGroupedSeriesOrientation: 'vertical',
            offsetX: 0,
            offsetY: 0,
            labels: {
                colors: undefined,
                useSeriesColors: false
            },
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '32px',
                            fontBold: true,
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            color: undefined,
                            offsetY: -10,
                        },
                        value: {
                            show: true,
                            fontSize: '24px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            color: undefined,
                            offsetY: 16,
                            formatter: function(val) {
                                const fValor = parseFloat(val).toLocaleString('pt-br', {
                                    style: 'currency',
                                    currency: 'BRL'
                                })
                                console.log('VALOR', fValor)
                                return fValor;
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            color: '#373d3f',
                            formatter: function(w) {
                                const valor = w.globals.seriesTotals.reduce((a, b) => {
                                    return a + b
                                }, 0)

                                return valor.toLocaleString('pt-br', {
                                    style: 'currency',
                                    currency: 'BRL'
                                })
                            }
                        }
                    }
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#chartFab"), options);
    chart.render();
</script>
