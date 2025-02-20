<div id="chartProdutos" class="w-full h-24 bg-white">
    {{-- The Master doesn't talk, he acts. --}}
</div>
<script>
    var labels = @json($data['data']['labels']);
    var datasets = @json($data['data']['datasets']);

    var horizontal = @json($data['horizontal'] ?? false);
    var type = @json($data['type'] ?? 'bar');
    var total = @json($data['total']);

    var options = {
        series: [{
            name: ' Vendas',
            data: datasets
        }],
        annotations: {
            points: [{
                x: 'Bananas',
                seriesIndex: 0,
                label: {
                    borderColor: '#775DD0',
                    offsetY: 0,
                    style: {
                        color: '#fff',
                        background: '#775DD0',
                    },
                    text: 'Bananas are good',
                }
            }]
        },
        chart: {
            height: 350,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                columnWidth: '30%',
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '24px',
                fontBold: 'bold',
                colors: ['#fff']
            },
            formatter: function(val) {
                const l = (val * 100) / total
                return l.toFixed(2) + '%';
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
        },
        stroke: {
            width: 0
        },
        grid: {
            row: {
                colors: ['#fff', '#f2f2f2']
            }
        },
        xaxis: {
            labels: {
                rotate: -45
            },
            categories: labels,
            tickPlacement: 'on'
        },
        yaxis: {
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
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.25,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 0.85,
                stops: [50, 0, 100]
            },
        }
    };

    var chart = new ApexCharts(document.querySelector("#chartProdutos"), options);

    chart.render();
</script>
