<div  class="w-full h-full ">
    <div class="w-full flex justify-center">
        <a href="#" class="flex items-center gap-2 link hover:bg-base-200 p-4 w-2/3 justify-center rounded-lg">
            <span class="font-bold italic text-lg text-primary ">Planos</span>
        </a>
    </div>
    <div id="chart-planos"></div>
    {{-- The Master doesn't talk, he acts. --}}
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
</div>


<script>
    var data = @json($data);
    var labels = data.map(function (item) {
        return item['plano_habilitacao'];
    });

    var datasets = data.map(function (item) {
        return parseFloat(item['total_vendas']);
    });

    var total = 150;
    var options = {
        series: datasets,
        labels: labels,
        chart: {
            width: 380,
            type: 'pie',
        },
        legend: {
            show: true,
            showForSingleSeries: false,
            showForNullSeries: true,
            showForZeroSeries: true,
            position: 'bottom',
            horizontalAlign: 'center',
            floating: false,
            fontSize: '8px',
            fontFamily: 'Helvetica, Arial',
            fontWeight: 400,
            formatter: undefined,
            inverseOrder: false,
            width: undefined,
            height: undefined,
            tooltipHoverFormatter: undefined,
            customLegendItems: []
            ,
            clusterGroupedSeries: true,
            clusterGroupedSeriesOrientation: 'vertical',
            offsetX: 0,
            offsetY: 0,
            labels: {
                colors: undefined,
                useSeriesColors: false
            },
        },
        tooltip: {
            enabled: true,
            shared: true,
            intersect: false,
            followCursor: true,
            fillSeriesColor: false,
            theme: 'dark',
            style: {
                fontSize: '12px',
                fontFamily: 'Helvetica, Arial, sans-serif',
            },
            y: {
                formatter: function (val) {
                    return val.toLocaleString('pt-br', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                },
                title: {
                    formatter: function (seriesName) {
                        return seriesName + ': ';
                    }
                }
            },
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
        }],
        fill: {
            width: '100%',
            colors: ['#C4E4EC', '#ACC4CC', '#8CBDCD', '#BCE4FC', '#39B2E3']
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart-planos"), options);
    chart.render();
</script>

