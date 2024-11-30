<?php

namespace App\Livewire\Metas;

use App\Models\Filial;
use App\Models\Venda as VendaModel;
use App\Models\Vendedor;
use App\Services\ImagemTelecomService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public $chartMetas;
    public $chartFiliais;
    public $chartVendedores;
    public $filiais;
    public $vendedores;
    public $faturamentoTotal;
    public $recargasTotal;
    public $acessoriosTotal;
    public $franquiaTotal;

    public $tendenciaFaturamento;
    public $tendenciaFranquiaTotal;
    public $tendenciaAcessorioTotal;


    public function mount()
    {
        $vendaModel = new VendaModel();
        $imagemTelecom = new ImagemTelecomService($vendaModel);

        $chartMetasLabels = [];
        $chartMetasDatasets = [];
        $chartMetasTendencia = [];
        $this->faturamentoTotal = $imagemTelecom->totalFaturamento();
        $this->franquiaTotal = $imagemTelecom->totalFranquia();
        $this->acessoriosTotal = $imagemTelecom->totalAcessorios();
        $this->tendenciaFaturamento = $imagemTelecom->tendencia($this->faturamentoTotal);
        $this->tendenciaAcessorioTotal = $imagemTelecom->tendencia($this->acessoriosTotal);
        $this->tendenciaFranquiaTotal = $imagemTelecom->tendencia($this->franquiaTotal);




        foreach ($imagemTelecom->vendasDiarias() as $vendaDiaria) {
            $chartMetasLabels[] = Carbon::parse($vendaDiaria->data_pedido)->format('d/m/Y');
            $chartMetasDatasets[] = $vendaDiaria->total;
            $chartMetasTendencia[] = $imagemTelecom->tendencia($vendaDiaria->data_pedido);
        }


        $this->chartMetas = [
            'type' => 'line',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'display' => true,
                ],
                'scales' => [
                    'x' => [
                        'stacked' => true,
                    ],
                    'y' => [
                        'stacked' => true,
                    ],
                ],
            ],
            'data' => [
                'labels' => $chartMetasLabels,
                'datasets' => [
                    [
                        'label' => 'Faturamento',
                        'data' => $chartMetasDatasets,
                    ],
                    [
                        'label' => 'Tendencia',
                        'data' => $chartMetasTendencia,
                    ],
                    [
                        'label' => 'Meta',
                        'data' => [300000, 300000, 300000, 300000, 300000, 300000, 300000, 300000, 300000, 300000],
                    ]
                ]
            ]
        ];

        $filiais =  Filial::query()->limit(10)->get();

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

        foreach ($filiais as $filial) {
            $filialData[] = $filial->filial;
            $filialFaturamento[] = $imagemTelecom->faturamentoFilial($filial->id);
            $filialTendencia[] = $imagemTelecom->tendenciaFilial($filial->id);
        }


        foreach ($imagemTelecom->topVendedores() as $vendedor) {
            $vendedoresData[] = $imagemTelecom->getNomeVendedor($vendedor->vendedor_id);
            $vendedorFaturamento[] = $imagemTelecom->faturamentoVendedor($vendedor->vendedor_id);
            $vendedorTendencia[] = $imagemTelecom->tendenciaVendedor($vendedor->vendedor_id);
        }


        $this->chartFiliais = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,

                'legend' => [
                    'display' => true,
                ],

            ],
            'data' => [
                'labels' => $filialData,
                'datasets' => [
                    [
                        'label' => 'Faturamento',
                        'data' => $filialFaturamento,
                    ],
                    [
                        'label' => 'Tendencia',
                        'data' => $filialTendencia,
                    ],
                    [
                        'label' => 'Meta',
                        'data' => [300000, 300000, 300000, 300000, 300000, 300000, 300000, 300000, 300000, 300000],
                    ]
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
                        'label' => 'Faturamento',
                        'data' => $vendedorFaturamento,
                    ],
                    [
                        'label' => 'Tendencia',
                        'data' => $vendedorTendencia,
                    ],
                    [
                        'label' => 'Meta',
                        'data' => [300000, 300000, 300000, 300000, 300000, 300000, 300000, 300000, 300000, 300000],
                    ]
                ]
            ]
        ];
    }

    #[Layout('components.layouts.view')]
    public function render()
    {
        return view('livewire.metas.dashboard');
    }
}
