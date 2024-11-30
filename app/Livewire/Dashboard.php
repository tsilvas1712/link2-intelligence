<?php

namespace App\Livewire;

use App\Models\Filial;
use App\Models\Venda;
use App\Models\Vendedor;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public $chartTotal = [];
    public $chartVendedores = [];
    public $chartFiliais = [];
    public $chartEvolucao = [];

    #[Layout('components.layouts.view')]
    public function render()
    {
        return view('livewire.dashboard');
    }

    public function mount()
    {
        $vendas = $this->getVendas();

        $this->chartTotal = [
            'type' => 'bar',
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
                'labels' => ['01-11', '02-11', '03-11', '04-11', '05-11', '06-11', '07-11', '08-11', '09-11', '10-11'],
                'datasets' => [
                    [
                        'label' => 'Aparelhos',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ],
                    [
                        'label' => 'Franquia',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ],
                    [
                        'label' => 'Acessórios',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ]
                ]
            ]
        ];

        $this->chartVendedores = [
            'type' => 'bar',
            'options' => [
                'indexAxis' => 'y',
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
                'labels' => ['ADELMO CARVALHO', 'BIANCA RIBEIRO', 'BRUNO FELIPE', 'SARAH ARAUJO', 'VITORIA SANTOS'],
                'datasets' => [
                    [
                        'label' => 'Aparelhos',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ],
                    [
                        'label' => 'Franquia',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ],
                    [
                        'label' => 'Acessórios',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ]
                ]
            ]
        ];

        $this->chartFiliais = [
            'type' => 'pie',
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
                'labels' => ['01-Diadema', '02-Itaquera', '09-Mocca', '15-Tatuapé', '19-Tucuruvi'],
                'datasets' => [
                    [
                        'label' => 'Aparelhos',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ],
                    [
                        'label' => 'Franquia',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ],
                    [
                        'label' => 'Acessórios',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ]
                ]
            ]
        ];

        $this->chartEvolucao = [
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
                'labels' => ['01-11', '02-11', '03-11', '04-11', '05-11', '06-11', '07-11', '08-11', '09-11', '10-11'],
                'datasets' => [
                    [
                        'label' => 'Vendas',
                        'data' => [rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000), rand(1000, 300000)],
                    ],

                ]
            ]
        ];
    }

    public function getFiliais()
    {
        $filiais = Filial::query()->orderBy('filial', 'asc')->get();
        $data = [];
        foreach ($filiais as $filial) {
            $data[] = [
                'id' => $filial->id,
                'name' => $filial->filial,
            ];
        }

        return $data;
    }

    public function getVendedores()
    {
        $vendedores = Vendedor::query()->orderBy('nome', 'asc')->get();
        $data = [];
        foreach ($vendedores as $vendedor) {
            $data[] = [
                'id' => $vendedor->id,
                'name' => $vendedor->nome,
            ];
        }

        return $data;
    }

    public function getVendas()
    {
        return Venda::query()
            ->select('id', 'data_pedido', 'filial_id', 'vendedor_id', 'valor_caixa')
            ->groupBy('data_pedido', 'filial_id', 'vendedor_id', 'valor_caixa')
            ->sum('valor_caixa');
    }

    public function filtrar() {}
}
