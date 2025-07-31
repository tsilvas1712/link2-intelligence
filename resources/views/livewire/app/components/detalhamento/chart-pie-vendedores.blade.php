<div id="chartPieVendedores" class="w-full h-96">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
</div>
<script>
    
    var series = @json($data['series']);
    var labels = @json($data['labels']);
    var options = {
        series: series,
        labels: labels,
        chart: {
            width: '75%',
            type: 'donut',
        },
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270
            }
        },
        dataLabels: {
            enabled: false
        },
        fill: {
            type: 'gradient',
        },
        legend: {
           
               
   
        },
        title: {
            text: 'Comparativo de Vendas por Vendedor',
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

    var chart = new ApexCharts(document.querySelector("#chartPieVendedores"), options);
    chart.render();
</script>