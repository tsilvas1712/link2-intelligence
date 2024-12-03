<?php

namespace App\Livewire\Metas;

use App\Models\Filial;
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

    public $ano;

    public $tendenciaFaturamento;
    public $tendenciaFranquiaTotal;
    public $tendenciaAcessorioTotal;


    public function mount()
    {
        $this->ano = Carbon::now()->format('Y');

        $vendaModel = new VendaModel();
        $imagemTelecom = new ImagemTelecomService($vendaModel);



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
        $this->franquiaTotal = $imagemTelecom->totalFranquia();
        $this->acessoriosTotal = $imagemTelecom->totalAcessorios();
        $this->tendenciaFaturamento = $imagemTelecom->tendencia($this->faturamentoTotal);
        $this->tendenciaAcessorioTotal = $imagemTelecom->tendencia($this->acessoriosTotal);
        $this->tendenciaFranquiaTotal = $imagemTelecom->tendencia($this->franquiaTotal);

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
        }

        $this->chartMetas = [
            'type' => 'line',
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
                        'label' => 'Total em Vendas',
                        'data' => $chartMetasDatasets,
                    ],

                ]
            ]
        ];




        foreach ($imagemTelecom->vendasDiarias() as $vendaDiaria) {
            $chartMetasLabels[] = Carbon::parse($vendaDiaria->data_pedido)->format('d/m/Y');
            $chartMetasDatasets[] = $vendaDiaria->total;
            $chartMetasTendencia[] = $imagemTelecom->tendencia($vendaDiaria->data_pedido);
        }



        $filiais =  Filial::query()->get();

        $vendedores = Vendedor::query()->limit(10)->get();

        $filialData = [];
        $filialFaturamento = [];
        $filialTendencia = [];

        $vendedoresData = [];
        $vendedorFaturamento = [];
        $vendedorTendencia = [];
        $status = ['up', 'down', 'ok'];

        foreach ($filiais as $filial) {
            $this->filiais[] = [
                'filial' => $filial->filial,
                'status' => $status[array_rand($status)],
                'faturamento' => $imagemTelecom->faturamentoFilial($filial->id),
                'tendencia' => $imagemTelecom->tendenciaFilial($filial->id),
                'meta' => 300000
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
}
