<div id="chart" class="w-full h-24 bg-white">
    {{-- The Master doesn't talk, he acts. --}}
</div>
<script>
    var labels = @json($data['data']['labels']);
    var datasets = @json($data['data']['datasets']);
    var horizontal = @json($data['horizontal'] ?? false);
    var type = @json($data['type'] ?? 'bar');
    var total = 150;
    var options = {
        chart: {
            type: type,
            height: 250,
            with: '100%',
        },
        plotOptions: {
            bar: {
                horizontal: horizontal
            }
        },
        fill: {
            colors: ['#849CBC', '#8CD4C4', '#FCA4A4'],
        },
        series: datasets,
        dataLabels: {
            enabled: false,
            style: {
                fontSize: '10px',
                fontBold: 'bold',
                colors: ['#fff']
            },
            formatter: function(val) {
                const l = (val * 100) / total
                return l.toFixed(2) + '%';
            }
        },
        xaxis: {
            lines: {
                show: false
            },
            categories: labels,
        },
        yaxis: {
            show: true,
            lines: {
                show: false
            },
            labels: {
                formatter: function(val) {
                    return val.toLocaleString('pt-br', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            }
        },
        grid: {
            show: true,
            borderColor: '#90A4AE',
            strokeDashArray: 0,
            position: 'back',
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            row: {
                colors: undefined,
                opacity: 0.5
            },
            column: {
                colors: undefined,
                opacity: 0.5
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 8
            },
        },
        responsive: [{
            breakpoint: 500,
            options: {
                chart: {
                    width: '100%',
                },
                fill: {
                    width: '100%',
                    colors: ['#849CBC', '#8CD4C4', '#FCA4A4'],
                },
                grid: {
                    show: false,
                    legend: {
                        position: 'top'
                    }
                },
                yaxis: {
                    show: false,
                    lines: {
                        show: false
                    },
                }
            }
        }]
    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
</script>
