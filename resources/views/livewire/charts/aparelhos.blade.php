<div  class="w-full h-full ">
    <div class="w-full flex justify-center">
        <a href="{{route('aparelhos.main')}}" class="flex items-center gap-2 link hover:bg-base-200 p-4 w-2/3 justify-center rounded-lg">
            <span class="font-bold italic text-lg text-primary ">Aparelhos</span>
        </a>
    </div>
    <div id="chart-aparelhos"></div>
    {{-- The Master doesn't talk, he acts. --}}
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
</div>


<script>
    var data = @json($data);
    var labels = data.map(function (item) {
        return item['fabricante'];
    });

    var datasets = data.map(function (item) {
        return parseFloat(item['total_vendas']);
    });
    console.log('DATA', data);
    console.log('LABELS', labels);
    console.log('DATASETS', datasets);

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
            click: function (event, chartContext, config) {
                console.log('Tooltip clicked:', event, chartContext, config);
                console.log('Clicked on:', label, 'with value:', value);
            },
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
            colors: ['#09B6ED', '#FAC077', '#E06457', '#F3831A', '#FF1493'],
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart-aparelhos"), options);
    chart.render();
</script>
