<?php

namespace App\Livewire\Metas;

use App\Models\Filial;
use App\Models\Grupo;
use App\Models\Venda as VendaModel;
use App\Models\Vendedor;
use App\Services\ImagemTelecomService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\LaravelPdf\Facades\Pdf;

class Dashboard extends Component
{
    public $chartMetas;
    public $chartFiliais;
    public $chartVendedores;
    public $chartFabricante;
    public $filiais;
    public $vendedores;
    public $faturamentoTotal;

    public $aparelhosTotal;
    public $recargasTotal;
    public $acessoriosTotal;
    public $franquiaTotal;
    public $meses = [
        '01',
        '02',
        '03',
        '04',
        '05',
        '06',
        '07',
        '08',
        '09',
        '10',
        '11',
        '12'
    ];

    public $metas;

    public $ano;
    public $mes;

    public $tendenciaFaturamento;
    public $tendenciaFranquiaTotal;
    public $tendenciaAcessorioTotal;

    public $tendenciaAparelhosTotal;

    public $planos;

    public $chartPlanosValor;
    public $chartPlanosGross;


    public function mount()
    {
        $this->ano = Carbon::now()->format('Y');
        $this->mes = '05'; //Carbon::now()->format('m');

        $vendaModel = new VendaModel();
        $imagemTelecom = new ImagemTelecomService($vendaModel);

        $planos = $this->getGrupos();

        $chartPlanosLabel = [];
        $chartPlanosGross = [];
        $chartPlanosTotal = [];


        foreach ($planos as $plano) {
            $totalPlanos = $imagemTelecom->totalPlanos($plano);

            $chartPlanosLabel[] = $plano->nome;
            $chartPlanosTotal[] = $totalPlanos[0]['total'];
            $chartPlanosGross[] = $totalPlanos[0]['gross'];

            $this->planos[] = [
                'id' => $plano->id,
                'grupo' => $plano->nome,
                'gross' => $totalPlanos[0]['gross'],
                'total' => $totalPlanos[0]['total']
            ];
        }

        $this->chartPlanosValor = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,

                'legend' => [
                    'display' => true,

                ],


            ],
            'data' => [
                'labels' =>  $chartPlanosLabel,
                'datasets' => [
                    [
                        'label' => 'Total em Planos',
                        'data' => $chartPlanosTotal,
                        'borderColor' => '#2C5494',
                        'backgroundColor' => '#849CBC',
                    ],


                ],

            ]
        ];

        $this->chartPlanosGross = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,

                'legend' => [
                    'display' => true,

                ],


            ],
            'data' => [
                'labels' =>  $chartPlanosLabel,
                'datasets' => [
                    [
                        'label' => 'Total em Planos',
                        'data' => $chartPlanosGross,
                        'borderColor' => '#2C5494',
                        'backgroundColor' => '#849CBC',
                    ],


                ],

            ]
        ];




        $this->metas = $imagemTelecom->meta($this->mes, $this->ano);



        $rankingFabricantes = $imagemTelecom->rankingFabricantes();
        $fabricanteLabels = [];
        $fabricanteDatasets = [];

        foreach ($rankingFabricantes as $ranking) {
            $fabricanteLabels[] = $ranking->fabricante;
            $fabricanteDatasets[] = $ranking->total;
        }

        $this->chartFabricante = [
            'type' => 'pie',
            'data' => [
                'labels' => $fabricanteLabels,
                'datasets' => [
                    [
                        'label' => '# of Votes',
                        'data' => $fabricanteDatasets,
                    ]
                ]
            ]
        ];


        $this->faturamentoTotal = $imagemTelecom->totalFaturamento();
        $this->aparelhosTotal = $imagemTelecom->totalAparelhos();
        $this->franquiaTotal = $imagemTelecom->totalFranquia();
        $this->acessoriosTotal = $imagemTelecom->totalAcessorios();
        $this->tendenciaFaturamento = $imagemTelecom->tendencia($this->faturamentoTotal);
        $this->tendenciaAcessorioTotal = $imagemTelecom->tendencia($this->acessoriosTotal);
        $this->tendenciaFranquiaTotal = $imagemTelecom->tendencia($this->franquiaTotal);
        $this->tendenciaAparelhosTotal = $imagemTelecom->tendencia($this->aparelhosTotal);

        $meses = [
            '01' => 'Jan',
            '02' => 'Fev',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'Mai',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Set',
            '10' => 'Out',
            '11' => 'Nov',
            '12' => 'Dez'
        ];



        foreach ($this->meses as $mes) {

            $chartMetasLabels[] = $meses[$mes];
            $chartMetasDatasets[] = $imagemTelecom->faturamento($mes, $this->ano);
            $meta = $imagemTelecom->meta($mes, $this->ano);
            $chartMetasDatasetsMeta[] = $meta[0]['meta_faturamento'] ?? 0;
        }



        $this->chartMetas = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,

                'legend' => [
                    'display' => true,

                ],


            ],
            'data' => [
                'labels' =>  $chartMetasLabels,
                'datasets' => [
                    [
                        'label' => 'Tendência',
                        'data' => $chartMetasDatasets,
                        'borderColor' => '#2C5494',
                        'backgroundColor' => '#849CBC',
                    ],
                    [
                        'label' => 'Vendas',
                        'data' => $chartMetasDatasets,
                        'borderColor' => '#6FAD28',
                        'backgroundColor' => '#8CD4C4',
                    ],
                    [
                        'label' => 'Meta',
                        'data' => $chartMetasDatasetsMeta,
                        'borderColor' => '#FCA4A4',
                        'backgroundColor' => '#FCA4A4',
                    ],

                ],

            ]
        ];








        foreach ($imagemTelecom->vendasDiarias() as $vendaDiaria) {
            $chartMetasLabels[] = Carbon::parse($vendaDiaria->data_pedido)->format('d/m/Y');
            $chartMetasDatasets[] = $vendaDiaria->total;
        }



        $filiais =  VendaModel::query()
            ->select('filial_id')
            ->whereMonth('data_pedido', '=', $this->mes)
            ->whereYear('data_pedido', '=', $this->ano)
            ->groupBy('filial_id')
            ->get();

        $vendedores = Vendedor::query()->limit(10)->get();

        $filialData = [];
        $filialFaturamento = [];
        $filialTendencia = [];

        $vendedoresData = [];
        $vendedorFaturamento = [];
        $vendedorTendencia = [];
        $status = ['up', 'down', 'ok'];

        foreach ($filiais as $row) {
            $filial =  $row->filial;
            $meta = $imagemTelecom->metaFilial($filial->id, $this->mes, $this->ano)[0]['meta_faturamento'] ?? 0;
            $faturamento = $imagemTelecom->faturamentoFilial($filial->id);
            $perc = ($faturamento / $meta) * 100;
            $key = 1;

            if ($perc > 100) {
                $key = 0;
            }

            if ($key > 95 && $key < 100) {
                $key = 2;
            }

            $this->filiais[] = [
                'id' => $filial->id,
                'filial' => $filial->filial,
                'status' => $status[$key],
                'faturamento' => $faturamento,
                'tendencia' => $imagemTelecom->tendenciaFilial($filial->id),
                'meta' => $meta,
            ];
        }



        foreach ($vendedores as $vendedor) {
            $this->vendedores[] = [
                'nome' => $vendedor->nome,
                'status' => $status[array_rand($status)],
                'faturamento' => rand(1000, 300000),
                'tendencia' => rand(1000, 300000),
                'meta' => 300000
            ];
        }

        $rankingFiliais = $imagemTelecom->rankingFiliais();

        foreach ($rankingFiliais as $filial) {
            $filialData[] = $imagemTelecom->getNomeFilial($filial->filial_id);
            $filialFaturamento[] = $filial->total;
        }


        foreach ($imagemTelecom->rankingVendedores() as $vendedor) {
            $vendedoresData[] = $imagemTelecom->getNomeVendedor($vendedor->vendedor_id);
            $vendedorFaturamento[] = $vendedor->total;
        }


        $this->chartFiliais = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => true,
                'indexAxis' => 'y',

                'legend' => [
                    'display' => true,

                ],


            ],
            'data' => [
                'labels' => $filialData,
                'datasets' => [
                    [
                        'label' => 'Total em Vendas',
                        'data' => $filialFaturamento,
                    ],

                ]
            ]
        ];

        $this->chartVendedores = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'indexAxis' => 'y',

                'legend' => [
                    'display' => true,
                ],

            ],
            'data' => [
                'labels' => $vendedoresData,
                'datasets' => [
                    [
                        'label' => 'Total em Vendas',
                        'data' => $vendedorFaturamento,
                    ],

                ]
            ]
        ];
    }

    public function exportToPDF()
    {
        return Pdf::html(view('livewire.metas.dashboard'))
            ->format('a4')
            ->name('dashboard.pdf');
    }

    #[Layout('components.layouts.view')]
    public function render()
    {
        return view('livewire.metas.dashboard');
    }

    public function getGrupos()
    {
        return Grupo::query()
            ->get();
    }
}
