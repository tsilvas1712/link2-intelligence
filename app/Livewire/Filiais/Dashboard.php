<?php

namespace App\Livewire\Filiais;

use App\Models\Filial;
use App\Models\Venda;
use App\Models\Vendedor;
use App\Services\FilialService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use function Pest\Laravel\get;

class Dashboard extends Component
{
    public $chartFiliais;

    public $chartAparelhos;
    public $chartAcessorios;

    public $chartFranquia;

    public $selectedMonth;
    public $meses;

    public $chartFiliaisVendas;

    public $filiais_id = [];

    public $filiais;
    public function mount()
    {
        $meses = [
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro"
        ];

        $this->chartFiliaisVendas = $this->chartFiliais();
        $this->chartAparelhos = $this->chartAparelho();
        $this->chartAcessorios = $this->chartAcessoriosData();
        $this->chartFranquia = $this->chartFranquiaData();

        $this->filiais = Filial::query()
            ->orderBy('filial', 'asc')
            ->get();



        foreach ($meses as $key => $mes) {
            $this->meses[] = [
                'id' => $key,
                'name' => $mes,
            ];
        }
    }
    #[Layout('components.layouts.view')]
    public function render()
    {
        return view('livewire.filiais.dashboard');
    }

    public function filter()
    {
        $this->getData();

        $this->chartFiliaisVendas = $this->chartFiliais();
        $this->chartAparelhos = $this->chartAparelho();
        $this->chartAcessorios = $this->chartAcessoriosData();
        $this->chartFranquia = $this->chartFranquiaData();
    }

    public function getFiliais()
    {
        $data = Filial::query()
            ->orderBy('filial', 'desc')
            ->get();

        $filiais = [];
        foreach ($data as $filial) {
            $filiais[] = [
                'id' => $filial->id,
                'name' => $filial->filial,

            ];
        }


        return $filiais;
    }

    public function getData()
    {
        return Venda::query()
            ->selectRaw('filial_id, sum(valor_caixa) as total')
            ->when($this->selectedMonth, function ($query) {
                $query->whereMonth('data_pedido', $this->selectedMonth);
            })
            ->when(!$this->selectedMonth, function ($query) {
                $query->whereMonth('data_pedido', '11');
            })
            ->when($this->filiais_id, function ($query) {
                $query->whereIn('filial_id', $this->filiais_id);
            })
            ->groupBy('filial_id')
            ->get();
    }

    public function chartFiliais()
    {
        $data = $this->getData();

        $chartData = [];
        $label = [];
        $dataset = [];

        foreach ($data as $row) {
            array_push($label, $row->filial->filial);
            array_push($dataset, floatVal($row->total));
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;



        $chart = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,

                'legend' => [
                    'display' => false,

                ],


            ],
            'data' => [
                'labels' =>  $chartData['label'],
                'datasets' => [
                    [
                        'label' => 'Total em Vendas',
                        'data' => $chartData['dataset'],
                    ],

                ]
            ]
        ];



        return $chart;
    }

    public function chartAparelho()
    {
        $data = $this->getAparelhos();

        $chartData = [];
        $label = [];
        $dataset = [];
        $dataCounter = [];

        foreach ($data as $row) {
            array_push($label, $row->filial->filial);
            array_push($dataset, floatVal($row->total));
            array_push($dataCounter, floatVal($row->aparelho));
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['contador'] = $dataCounter;



        $chart = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,

                'legend' => [
                    'display' => false,

                ],


            ],
            'data' => [
                'labels' =>  $chartData['label'],
                'datasets' => [
                    [
                        'label' => 'Total em Aparelhos',
                        'data' => $chartData['dataset'],
                        'borderColor' => '#F5620F',
                        'backgroundColor' => '#F5620F',
                    ],


                ]
            ]
        ];



        return $chart;
    }

    public function chartAcessoriosData()
    {
        $data = $this->getAcessorios();

        $chartData = [];
        $label = [];
        $dataset = [];
        $dataCounter = [];

        foreach ($data as $row) {
            array_push($label, $row->filial->filial);
            array_push($dataset, floatVal($row->total));
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['contador'] = $dataCounter;



        $chart = [
            'type' => 'line',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,

                'legend' => [
                    'display' => false,

                ],


            ],
            'data' => [
                'labels' =>  $chartData['label'],

                'datasets' => [
                    [
                        'label' => 'Total em Acessórios',
                        'data' => $chartData['dataset'],
                        'borderColor' => '#54B48C',
                        'backgroundColor' => '#54B48C',

                    ],


                ]
            ]
        ];



        return $chart;
    }

    public function chartFranquiaData()
    {
        $data = $this->getFranquia();

        $chartData = [];
        $label = [];
        $dataset = [];
        $dataCounter = [];

        foreach ($data as $row) {
            array_push($label, $row->filial->filial);
            array_push($dataset, floatVal($row->total));
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['contador'] = $dataCounter;



        $chart = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,

                'legend' => [
                    'display' => false,

                ],


            ],
            'data' => [
                'labels' =>  $chartData['label'],
                'datasets' => [
                    [
                        'label' => 'Total em Franquia',
                        'data' => $chartData['dataset'],
                        'borderColor' => '#F9C408',
                        'backgroundColor' => '#F9C408',
                    ],


                ]
            ]
        ];



        return $chart;
    }

    public function getAparelhos()
    {
        return Venda::query()
            ->selectRaw('filial_id, count(*) as aparelho,sum(base_faturamento_compra) as total')
            ->when($this->selectedMonth, function ($query) {
                $query->whereMonth('data_pedido', $this->selectedMonth);
            })
            ->when(!$this->selectedMonth, function ($query) {
                $query->whereMonth('data_pedido', '11');
            })
            ->when($this->filiais_id, function ($query) {
                $query->whereIn('filial_id', $this->filiais_id);
            })
            ->where('grupo_estoque', 'APARELHO')
            ->groupBy('filial_id')
            ->get();
    }

    public function getAcessorios()
    {
        return Venda::query()
            ->selectRaw('filial_id,sum(valor_caixa) as total')
            ->when($this->selectedMonth, function ($query) {
                $query->whereMonth('data_pedido', $this->selectedMonth);
            })
            ->when(!$this->selectedMonth, function ($query) {
                $query->whereMonth('data_pedido', '11');
            })
            ->when($this->filiais_id, function ($query) {
                $query->whereIn('filial_id', $this->filiais_id);
            })
            ->whereIn('grupo_estoque', ['ACESSORIOS', 'ACESSORIOS TIM'])
            ->groupBy('filial_id')
            ->get();
    }

    public function getFranquia()
    {
        return Venda::query()
            ->selectRaw('filial_id,sum(valor_franquia) as total')
            ->when($this->selectedMonth, function ($query) {
                $query->whereMonth('data_pedido', $this->selectedMonth);
            })
            ->when(!$this->selectedMonth, function ($query) {
                $query->whereMonth('data_pedido', '11');
            })
            ->when($this->filiais_id, function ($query) {
                $query->whereIn('filial_id', $this->filiais_id);
            })
            ->where('grupo_estoque', 'RECARGA ELETRONICA')
            ->groupBy('filial_id')
            ->get();
    }
}
