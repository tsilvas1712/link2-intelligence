<?php

namespace App\Livewire\Filial;

use App\Models\Filial;
use App\Models\MetasFiliais;
use App\Models\Venda;
use App\Services\ImagemTelecomService;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public $filial;
    public $mes;
    public $ano;

    public $mesSelecionado;
    public $anoSelecionado;

    public $meses;
    public $anos;

    public $metas;

    public $tendencias;

    public $vendedores;

    public $chartVendasDiarias;

    public $chartAparelhos;
    public $chartAcessorios;

    public $chartFranquia;

    public function mount($id)
    {
        $this->mes =  '11'; //Carbon::now()->format("m");
        $this->ano = Carbon::now()->format("Y");
        $this->meses = $this->getMeses();
        $this->anos = $this->getAnos();
        $this->filial = Filial::find($id);
        $this->metas = $this->getMetas();
        $this->chartVendasDiarias = $this->getChartVendasDiarias();
        $this->vendedores = $this->getvendedoresData();
        $this->chartAparelhos = $this->chartAparelho();
        $this->chartAcessorios = $this->chartAcessoriosData();
        $this->chartFranquia = $this->chartFranquiaData();
    }
    #[Layout("components.layouts.view")]
    public function render()
    {
        return view('livewire.filial.dashboard');
    }

    public function getMeses()
    {
        return [
            ['id' => '01', 'name' => 'Janeiro'],
            ['id' => '02', 'name' => 'Fevereiro'],
            ['id' => '03', 'name' => 'Março'],
            ['id' => '04', 'name' => 'Abril'],
            ['id' => '05', 'name' => 'Maio'],
            ['id' => '06', 'name' => 'Junho'],
            ['id' => '07', 'name' => 'Julho'],
            ['id' => '08', 'name' => 'Agosto'],
            ['id' => '09', 'name' => 'Setembro'],
            ['id' => '10', 'name' => 'Outubro'],
            ['id' => '11', 'name' => 'Novembro'],
            ['id' => '12', 'name' => 'Dezembro'],
        ];
    }

    public function getAnos()
    {
        $anos = [];
        $anoInicial = Carbon::now()->subYears(2)->format('Y');

        for ($i = 0; $i < 10; $i++) {
            $anos[] = [
                'id' => $anoInicial + $i,
                'name' =>  $anoInicial + $i,
            ];
        }

        return $anos;
    }

    public function filter()
    {
        $this->getVendas();
        $this->metas = $this->getMetas();
        $this->chartVendasDiarias = $this->getChartVendasDiarias();
        $this->vendedores = $this->getvendedoresData();
        $this->chartAparelhos = $this->chartAparelho();
        $this->chartAcessorios = $this->chartAcessoriosData();
        $this->chartFranquia = $this->chartFranquiaData();
    }

    public function getFaturamento()
    {
        $tAparelhos = $this->getTotalAparelhos();
        $tAcessorios = $this->getTotalAcessorios();
        $tChips = $this->getTotalChips();

        $total = $tAcessorios + $tAparelhos + $tChips;

        return 'R$ ' . number_format($total, 2, ',', '.');
    }


    #[Computed]
    public function getVendas()
    {
        return Venda::query()
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when($this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->anoSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->when(!$this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->ano);
            })
            ->where('filial_id', $this->filial->id)
            ->get();
    }

    public function getAparelhosDiarias()
    {
        return Venda::query()
            ->selectRaw('filial_id,data_pedido, sum(base_faturamento_compra) as total_caixa')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when($this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->anoSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->when(!$this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->ano);
            })
            ->where('grupo_estoque', 'APARELHOS')
            ->groupBy(['filial_id', 'data_pedido'])
            ->where('filial_id', $this->filial->id)
            ->get();
    }

    public function getAcessoriosDiarias()
    {
        return Venda::query()
            ->selectRaw('filial_id,data_pedido, sum(valor_caixa) as total_caixa')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when($this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->anoSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->when(!$this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->ano);
            })
            ->we
            ->groupBy(['filial_id', 'data_pedido'])
            ->where('filial_id', $this->filial->id)
            ->get();
    }

    public function getChipsDiarias()
    {
        return Venda::query()
            ->selectRaw('filial_id,data_pedido, sum(valor_caixa) as total_caixa')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when($this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->anoSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->when(!$this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->ano);
            })
            ->groupBy(['filial_id', 'data_pedido'])
            ->where('filial_id', $this->filial->id)
            ->get();
    }

    public function getVendasDiarias()
    {
        return Venda::query()
            ->selectRaw('filial_id,data_pedido, sum(valor_caixa) as total_caixa')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when($this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->anoSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->when(!$this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->ano);
            })
            ->groupBy(['filial_id', 'data_pedido'])
            ->where('filial_id', $this->filial->id)
            ->get();
    }

    public function getTotalAparelhos()
    {
        $vendas = $this->getVendas();
        return $vendas
            ->where('grupo_estoque', 'APARELHO')
            ->sum('base_faturamento_compra');
    }

    public function getTotalAcessorios()
    {
        $vendas = $this->getVendas();
        return $vendas->whereIn('grupo_estoque', ['ACESSORIOS', 'ACESSORIOS TIM'])->sum('valor_caixa');
    }

    public function getTotalChips()
    {
        $vendas = $this->getVendas();
        return $vendas->whereIn('grupo_estoque', ['CHIP'])->sum('valor_caixa');
    }

    public function getTotalFranquia()
    {
        $vendas = $this->getVendas();
        return $vendas->whereIn('grupo_estoque', ['CHIP'])->sum('valor_caixa');
    }

    public function getTendencias()
    {
        $vendas = $this->getVendas();
    }

    public function getMetas()
    {
        return MetasFiliais::query()
            ->when($this->mesSelecionado, function ($query) {
                $query->where('mes', $this->mesSelecionado);
            })
            ->when($this->anoSelecionado, function ($query) {
                $query->where('ano', $this->anoSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->where('mes', $this->mes);
            })
            ->when(!$this->anoSelecionado, function ($query) {
                $query->where('ano', $this->ano);
            })
            ->where('filial_id', $this->filial->id)
            ->first();
    }

    public function getChartAcessoriosDiarias()
    {
        $data = $this->getVendasDiarias();
        $imagemTelecom = new ImagemTelecomService(new Venda());


        $chartData = [];
        $label = [];
        $dataset = [];
        $datasetMeta = [];
        $datasetTendencia = [];

        foreach ($data as $row) {
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mes, $this->ano);

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));
            array_push($dataset, floatVal($row->total_caixa));
            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->filial_id, $row->data_pedido));
            array_push($datasetMeta, $meta[0]->meta_faturamento);
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['datasetTendencia'] = $datasetTendencia;
        $chartData['datasetMeta'] = $datasetMeta;

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
                        'label' => 'Vendas',
                        'data' => $chartData['dataset'],
                        'options' => [],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Tendencia',
                        'data' => $chartData['datasetTendencia'],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Meta',
                        'data' => $chartData['datasetMeta'],
                    ],


                ]
            ]
        ];



        return $chart;
    }

    public function getChartAparelhosDiarias()
    {
        $data = $this->getVendasDiarias();
        $imagemTelecom = new ImagemTelecomService(new Venda());


        $chartData = [];
        $label = [];
        $dataset = [];
        $datasetMeta = [];
        $datasetTendencia = [];

        foreach ($data as $row) {
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mes, $this->ano);

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));
            array_push($dataset, floatVal($row->total_caixa));
            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->filial_id, $row->data_pedido));
            array_push($datasetMeta, $meta[0]->meta_faturamento);
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['datasetTendencia'] = $datasetTendencia;
        $chartData['datasetMeta'] = $datasetMeta;

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
                        'label' => 'Vendas',
                        'data' => $chartData['dataset'],
                        'options' => [],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Tendencia',
                        'data' => $chartData['datasetTendencia'],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Meta',
                        'data' => $chartData['datasetMeta'],
                    ],


                ]
            ]
        ];



        return $chart;
    }

    public function getChartChipsDiarias()
    {
        $data = $this->getVendasDiarias();
        $imagemTelecom = new ImagemTelecomService(new Venda());


        $chartData = [];
        $label = [];
        $dataset = [];
        $datasetMeta = [];
        $datasetTendencia = [];

        foreach ($data as $row) {
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mes, $this->ano);

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));
            array_push($dataset, floatVal($row->total_caixa));
            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->filial_id, $row->data_pedido));
            array_push($datasetMeta, $meta[0]->meta_faturamento);
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['datasetTendencia'] = $datasetTendencia;
        $chartData['datasetMeta'] = $datasetMeta;

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
                        'label' => 'Vendas',
                        'data' => $chartData['dataset'],
                        'options' => [],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Tendencia',
                        'data' => $chartData['datasetTendencia'],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Meta',
                        'data' => $chartData['datasetMeta'],
                    ],


                ]
            ]
        ];



        return $chart;
    }

    public function getChartVendasDiarias()
    {
        $data = $this->getVendasDiarias();
        $imagemTelecom = new ImagemTelecomService(new Venda());


        $chartData = [];
        $label = [];
        $dataset = [];
        $datasetMeta = [];
        $datasetTendencia = [];

        foreach ($data as $row) {
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mes, $this->ano);

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));
            array_push($dataset, floatVal($row->total_caixa));
            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->filial_id, $row->data_pedido));
            array_push($datasetMeta, $meta->meta_faturamento);
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['datasetTendencia'] = $datasetTendencia;
        $chartData['datasetMeta'] = $datasetMeta;

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
                        'label' => 'Vendas',
                        'data' => $chartData['dataset'],
                        'options' => [],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Tendencia',
                        'data' => $chartData['datasetTendencia'],
                    ],
                    [
                        'type' => 'line',
                        'label' => 'Meta',
                        'data' => $chartData['datasetMeta'],
                    ],


                ]
            ]
        ];



        return $chart;
    }

    public function getVendedoresData()
    {
        return Venda::query()
            ->selectRaw('vendedor_id, sum(valor_caixa) as total_caixa')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when($this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->anoSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->when(!$this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->ano);
            })
            ->where('filial_id', $this->filial->id)
            ->groupBy('vendedor_id')
            ->orderBy('total_caixa', 'desc')
            ->get();
    }

    public function chartAparelho()
    {
        $data = $this->getAparelhos();

        $chartData = [];
        $label = [];
        $dataset = [];
        $dataCounter = [];
        $datasetMeta = [];

        foreach ($data as $row) {
            $imagemTelecom = new ImagemTelecomService(new Venda());
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mes, $this->ano);
            array_push($label, $row->filial->filial);
            array_push($dataset, floatVal($row->total));
            array_push($dataCounter, floatVal($row->aparelho));
            array_push($datasetMeta, $meta->meta_aparelhos);
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['contador'] = $dataCounter;
        $chartData['datasetMeta'] = $datasetMeta;



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
                    [
                        'label' => 'Meta Aparelhos',
                        'data' => $chartData['datasetMeta'],
                        'borderColor' => '##eab308',
                        'backgroundColor' => '#eab308',

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
        $datasetMeta = [];

        foreach ($data as $row) {
            $imagemTelecom = new ImagemTelecomService(new Venda());
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mes, $this->ano);
            array_push($label, $row->filial->filial);
            array_push($dataset, floatVal($row->total));
            array_push($datasetMeta, floatVal($meta->meta_acessorios));
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['contador'] = $dataCounter;
        $chartData['datasetMeta'] = $datasetMeta;



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
                        'label' => 'Total em Acessórios',
                        'data' => $chartData['dataset'],
                        'borderColor' => '#54B48C',
                        'backgroundColor' => '#54B48C',

                    ],
                    [
                        'label' => 'Meta Acessórios',
                        'data' => $chartData['datasetMeta'],
                        'borderColor' => '##eab308',
                        'backgroundColor' => '#eab308',

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
        $datasetMeta = [];

        foreach ($data as $row) {
            $imagemTelecom = new ImagemTelecomService(new Venda());
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mes, $this->ano);

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
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', '11');
            })
            ->when($this->filial->id, function ($query) {
                $query->where('filial_id', $this->filial->id);
            })
            ->where('grupo_estoque', 'APARELHO')
            ->groupBy('filial_id')
            ->get();
    }

    public function getAcessorios()
    {
        return Venda::query()
            ->selectRaw('filial_id,sum(valor_caixa) as total')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', '11');
            })
            ->when($this->filial->id, function ($query) {
                $query->where('filial_id', $this->filial->id);
            })
            ->whereIn('grupo_estoque', ['ACESSORIOS', 'ACESSORIOS TIM'])
            ->groupBy('filial_id')
            ->get();
    }

    public function getFranquia()
    {
        return Venda::query()
            ->selectRaw('filial_id,sum(valor_franquia) as total')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', '11');
            })
            ->when($this->filial->id, function ($query) {
                $query->where('filial_id', $this->filial->id);
            })
            ->where('grupo_estoque', 'RECARGA ELETRONICA')
            ->groupBy('filial_id')
            ->get();
    }
}
