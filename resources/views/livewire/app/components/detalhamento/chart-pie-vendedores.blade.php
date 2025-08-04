<div id="chartPieVendedores" class="w-full h-96">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
</div>
<script>
    var series = @json($data['series']);
    var labels = @json($data['labels']);
    console.log('SERIES', series);
    console.log('LABELS', labels);
    var options = {
        series: series,
        labels: labels,
        chart: {
            width: '75%',
            type: 'donut',
        },
        legend: {
            show: true,
            showForSingleSeries: false,
            showForNullSeries: true,
            showForZeroSeries: true,
            position: 'right',
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
        fill: {
            type: 'gradient',
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
            breakpoint: 500,
            options: {
                chart: {
                    width: '100%',
                },
                grid: {
                    show: false,
                    legend: {
                        position: 'top'
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '16px',
                                    fontBold: true,
                                    fontFamily: 'Helvetica, Arial, sans-serif',
                                    color: undefined,
                                    offsetY: -10,
                                },
                                value: {
                                    show: true,
                                    fontSize: '16px',
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
                                    label: 'TOTAL',
                                    fontBold: true,
                                    fontSize: '16px',
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
            }
        }],
        tooltip: {
            y: {
                formatter: function(val) {
                    return val.toLocaleString('pt-br', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            }

        },
    };

    var chart = new ApexCharts(document.querySelector("#chartPieVendedores"), options);
    chart.render();
</script>
