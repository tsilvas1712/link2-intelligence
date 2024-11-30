<?php

namespace App\Livewire\Vendedores;

use App\Models\Venda;
use App\Models\Vendedor;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashbboard extends Component
{
    public $multi_ids = [];
    public $chartVendedores = [];
    public $chartAparelhos = [];
    public $chartAcessorios = [];
    public $chartFranquias = [];
    #[Layout('components.layouts.view')]
    public function mount()
    {
        $this->getTopVendedores();
        $this->getTopAparelhos();
        $this->getTopAcessorios();
        $this->getTopFranquias();
    }
    public function render()
    {
        return view('livewire.vendedores.dashbboard');
    }

    public function getVendedores()
    {
        $data = Vendedor::select('id', 'nome')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $vendedores = [];
        foreach ($data as $vendedor) {
            $vendedores[] = [
                'id' => $vendedor->id,
                'name' => $vendedor->nome,

            ];
        }
        return $vendedores;
    }

    public function getTopVendedores()
    {
        $vendedores = Vendedor::select('id', 'nome')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();


        $this->chartVendedores = [
            'type' => 'bar',
            'options' => [
                'indexAxis' => 'y',
                'maintainAspectRatio' => false,
                'responsive' => true,

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
                'labels' => $vendedores->pluck('nome'),
                'datasets' => [
                    [
                        'label' => 'Aparelhos',
                        'data' => $vendedores->map(function ($vendedor) {
                            //return Venda::where('vendedor_id', $vendedor->id)->count();
                            return rand(1000, 300000);
                        }),
                    ],
                    [
                        'label' => 'Acessórios',
                        'data' => $vendedores->map(function ($vendedor) {
                            //return Venda::where('vendedor_id', $vendedor->id)->count();
                            return rand(1000, 300000);
                        }),
                    ],
                    [
                        'label' => 'Franquia',
                        'data' => $vendedores->map(function ($vendedor) {
                            //return Venda::where('vendedor_id', $vendedor->id)->count();
                            return rand(1000, 300000);
                        }),
                    ],
                ],
            ],
        ];
    }

    public function getTopAparelhos()
    {
        $vendedores = Vendedor::select('id', 'nome')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();


        $this->chartAparelhos = [
            'type' => 'pie',
            'options' => [
                'label' => 'Aparelhos',
                'legend' => [
                    'display' => false,
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
                //'labels' => $vendedores->pluck('nome'),
                'legend' => [
                    'position' => 'right',
                    'display' => true,
                ],
                'datasets' => [
                    [
                        'label' => 'Aparelhos',
                        'data' => $vendedores->map(function ($vendedor) {
                            //return Venda::where('vendedor_id', $vendedor->id)->count();
                            return rand(1000, 300000);
                        }),
                    ],

                ],
            ],
        ];
    }

    public function getTopAcessorios()
    {
        $vendedores = Vendedor::select('id', 'nome')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();


        $this->chartAcessorios = [
            'type' => 'line',
            'options' => [
                'legend' => [
                    'display' => false,
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
                'labels' => 'Acessórios',
                'legend' => [

                    'display' => false,
                ],
                'datasets' => [
                    [
                        'label' => 'Acessórios',
                        'data' => [
                            3000,
                            2000,
                            1000,
                            4000,
                            5000,
                            6000,
                            7000,
                            8000,
                            9000,
                            10000,
                        ],
                    ],

                ],
            ],
        ];
    }

    public function getTopFranquias()
    {
        $vendedores = Vendedor::select('id', 'nome')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();


        $this->chartFranquias = [
            'type' => 'pie',
            'options' => [
                'indexAxis' => 'y',
                'maintainAspectRatio' => false,
                'responsive' => true,

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
                'labels' => $vendedores->pluck('nome'),
                'legend' => [
                    'position' => 'right',
                    'display' => true,
                ],
                'datasets' => [
                    [
                        'label' => 'Aparelhos',
                        'data' => $vendedores->map(function ($vendedor) {
                            //return Venda::where('vendedor_id', $vendedor->id)->count();
                            return rand(1000, 300000);
                        }),
                    ],

                ],
            ],
        ];
    }
}
