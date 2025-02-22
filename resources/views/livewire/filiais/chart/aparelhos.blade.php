<div id="chartAparelhos" class="w-full h-24 bg-white">
    {{-- The Master doesn't talk, he acts. --}}
</div>
<script>
    var data = @json($data['data']);
    var labels = @json($data['data']['labels']);
    var datasets = @json($data['data']['datasets']);

    var horizontal = @json($data['horizontal'] ?? false);
    var type = @json($data['type'] ?? 'bar');
    var total = @json($data['total']);


    var options = {
        series: datasets,
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
            enabled: false,
            style: {
                fontSize: '24px',
                fontBold: 'bold',
                colors: ['#fff']
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

    var chart = new ApexCharts(document.querySelector("#chartAparelhos"), options);

    chart.render();
</script>
