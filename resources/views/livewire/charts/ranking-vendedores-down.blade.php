<div id="chartVendedorDown" class="w-full h-24 bg-white">
    {{-- The Master doesn't talk, he acts. --}}
</div>
<script>
    var labels = @json($data['data']['labels']);
    var datasets = @json($data['data']['datasets']);

    var horizontal = @json($data['horizontal'] ?? false);
    var type = @json($data['type'] ?? 'bar');
    var total = 150;
    var options = {
        series: [{
            name: 'Vendas',
            data: datasets
        }],
        chart: {
            type: 'bar',
            height: 350,

        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                borderRadiusApplication: 'end',
                horizontal: true,
            }
        },
        fill: {
            colors: ['#FCA4A4'],
        },
        dataLabels: {
            enabled: true,
            style: {
                fontBold: 'bold',
                colors: ['#fff']
            },
            formatter: function(val) {
                const l = parseFloat(val)
                return l.toLocaleString('pt-br', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }
        },
        xaxis: {
            categories: labels,
        }
    };

    var chart = new ApexCharts(document.querySelector("#chartVendedorDown"), options);

    chart.render();
</script>
