<?php

namespace App\Livewire\Vendedor;

use App\Models\Grupo;
use App\Models\MetasVendedores;
use App\Models\Venda;
use App\Models\Vendedor;
use App\Services\ImagemTelecomService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public $vendedor;
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
    public $chartDiario;

    public $chartAparelhos;
    public $chartAcessorios;

    public $chartFranquia;

    public $meta;

    public $tendenciaFaturamento;

    public $aparelhosTotal;

    public $tendenciaAparelhosTotal;
    public $acessoriosTotal;

    public $tendenciaAcessorioTotal;

    public $chartProgressao;

    public $planos;

    public $faturamentoTotal;

    public $chartFabricante;

    public $chartPlanosValor;

    public $chartPlanosGross;

    public function mount($id)
    {
        $imagemTelecom = new ImagemTelecomService(new Venda());
        $this->mes =  Carbon::now()->format('m');
        $this->ano = Carbon::now()->format("Y");
        $this->meses = $this->getMeses();
        $this->anos = $this->getAnos();
        $this->vendedor = Vendedor::find($id);
        $this->metas = $this->getMetas();
        $this->chartVendasDiarias = $this->getChartVendasDiarias();
        $this->chartDiario = $this->getChartDiario();
        $this->vendedores = $this->getvendedoresData();
        $this->chartAparelhos = $this->chartAparelho();
        $this->chartAcessorios = $this->chartAcessoriosData();
        $this->chartFranquia = $this->chartFranquiaData();
        $this->chartFabricante = $this->rankingFabricantes();

        $this->meta = $this->getMetas();

        $this->faturamentoTotal = $this->getFaturamento();
        $this->aparelhosTotal = $this->getTotalAparelhos();
        $this->acessoriosTotal = $this->getTotalAcessorios();
        $this->tendenciaFaturamento = $imagemTelecom->tendencia($this->faturamentoTotal);
        $this->tendenciaAcessorioTotal = $imagemTelecom->tendencia($this->acessoriosTotal);
        //$this->tendenciaFranquiaTotal = $imagemTelecom->tendencia($this->franquiaTotal);
        $this->tendenciaAparelhosTotal = $imagemTelecom->tendencia($this->aparelhosTotal);

        $planos = $this->totalPlanos();
        $this->planos = $this->totalPlanos();


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
            $meta = $imagemTelecom->metaVendedor($this->vendedor->id, $mes['id'], $this->ano);
            $chartProgressaoLabels[] = $meses[$mes['id']];
            $chartProgressaoDatasets[] = $imagemTelecom->faturamentoVendedorMensal($this->vendedor->id, $mes['id'], $this->ano);
            $chartProgressaoDatasetsMeta[] = $meta->meta_faturamento ?? 0;
            $chartProgressaoDatasetsTendencia[] = $imagemTelecom->tendenciaVendedorMensal($this->vendedor->id, $mes['id'], $this->ano);
        }


        $this->chartProgressao = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,

                'legend' => [
                    'display' => true,

                ],


            ],
            'data' => [
                'labels' =>  $chartProgressaoLabels,
                'datasets' => [
                    [
                        'label' => 'Tendência',
                        'data' => $chartProgressaoDatasetsTendencia,
                        'borderColor' => '#2C5494',
                        'backgroundColor' => '#849CBC',
                    ],
                    [
                        'label' => 'Vendas',
                        'data' => $chartProgressaoDatasets,
                        'borderColor' => '#6FAD28',
                        'backgroundColor' => '#8CD4C4',
                    ],
                    [
                        'label' => 'Meta',
                        'data' => $chartProgressaoDatasetsMeta,
                        'borderColor' => '#FCA4A4',
                        'backgroundColor' => '#FCA4A4',
                    ],

                ],

            ]
        ];

        foreach ($planos as $plano) {
            $chartPlanosLabel[] = $plano['grupo'];
            $chartPlanosTotal[] = $plano['total'];
            $chartPlanosGross[] = $plano['gross'];
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
                        'label' => 'Gross Total',
                        'data' => $chartPlanosGross,
                        'borderColor' => '#2C5494',
                        'backgroundColor' => '#849CBC',
                    ],


                ],

            ]
        ];
    }

    #[Layout("components.layouts.view")]
    public function render()
    {
        return view('livewire.vendedor.dashboard');
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
        $imagemTelecom = new ImagemTelecomService(new Venda());
        $this->getVendas();
        $this->metas = $this->getMetas();
        $this->chartVendasDiarias = $this->getChartVendasDiarias();
        $this->chartDiario = $this->getChartDiario();
        $this->vendedores = $this->getvendedoresData();
        $this->chartAparelhos = $this->chartAparelho();
        $this->chartAcessorios = $this->chartAcessoriosData();
        $this->chartFranquia = $this->chartFranquiaData();
        $this->faturamentoTotal = $this->getFaturamento();
        $this->aparelhosTotal = $this->getTotalAparelhos();
        $this->acessoriosTotal = $this->getTotalAcessorios();
        $this->chartFabricante = $this->rankingFabricantes();
        $this->planos = $this->totalPlanos();
        $this->tendenciaFaturamento = $imagemTelecom->tendencia($this->faturamentoTotal);
        $this->tendenciaAcessorioTotal = $imagemTelecom->tendencia($this->acessoriosTotal);
        //$this->tendenciaFranquiaTotal = $imagemTelecom->tendencia($this->franquiaTotal);
        $this->tendenciaAparelhosTotal = $imagemTelecom->tendencia($this->aparelhosTotal);

        foreach ($this->totalPlanos() as $plano) {
            $chartPlanosLabel[] = $plano['grupo'];
            $chartPlanosTotal[] = $plano['total'];
            $chartPlanosGross[] = $plano['gross'];
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
                        'label' => 'Gross Total',
                        'data' => $chartPlanosGross,
                        'borderColor' => '#2C5494',
                        'backgroundColor' => '#849CBC',
                    ],


                ],

            ]
        ];
    }

    public function getFaturamento()
    {
        $tAparelhos = $this->getTotalAparelhos();
        $tAcessorios = $this->getTotalAcessorios();
        $tChips = $this->getTotalChips();
        $tFranquia = $this->getTotalFranquia();


        $total = $tAparelhos + $tChips + $tFranquia;

        return $total;
    }


    #[Computed]
    public function getVendas()
    {
        return Venda::query()
            ->whereIn('tipo_pedido', ['Venda', 'VENDA'])
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
            ->where('vendedor_id', $this->vendedor->id)
            ->get();
    }

    public function getAparelhosDiarias()
    {
        return Venda::query()
            ->selectRaw('vendedor_id,data_pedido, sum(base_faturamento_compra) as total_caixa')
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
            ->groupBy(['vendedor_id', 'data_pedido'])
            ->where('vendedor_id', $this->vendedor->id)
            ->get();
    }

    public function getAcessoriosDiarias()
    {
        return Venda::query()
            ->selectRaw('vendedor_id,data_pedido, sum(valor_caixa) as total_caixa')
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
            ->groupBy(['vendedor_id', 'data_pedido'])
            ->where('vendedor_id', $this->vendedor->id)
            ->get();
    }

    public function getChipsDiarias()
    {
        return Venda::query()
            ->selectRaw('vendedor_id,data_pedido, sum(valor_caixa) as total_caixa')
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
            ->groupBy(['vendedor_id', 'data_pedido'])
            ->where('vendedor_id', $this->vendedor->id)
            ->get();
    }

    public function getVendasDiarias()
    {
        return Venda::query()
            ->selectRaw('vendedor_id,data_pedido, sum(valor_caixa) as total_caixa')
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
            ->groupBy(['vendedor_id', 'data_pedido'])
            ->where('vendedor_id', $this->vendedor->id)
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
        return $vendas->whereIn('grupo_estoque', ['RECARGA', 'RECARGA GWCEL', 'RECARGA ELETRONICA'])->sum('valor_caixa');
    }

    public function getTendencias()
    {
        $vendas = $this->getVendas();
    }

    public function getMetas()
    {
        return MetasVendedores::query()
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
            ->where('vendedor_id', $this->vendedor->id)
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
            $meta = $imagemTelecom->metaVendedor($row->vendedor_id, $this->mes, $this->ano);

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));
            array_push($dataset, floatVal($row->total_caixa));
            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->vendedor_id, $row->data_pedido));
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
            $meta = $imagemTelecom->metaVendedor($row->vendedor_id, $this->mes, $this->ano);

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));
            array_push($dataset, floatVal($row->total_caixa));
            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->vendedor_id, $row->data_pedido));
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
            $meta = $imagemTelecom->metaVendedor($row->vendedor_id, $this->mes, $this->ano);

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));
            array_push($dataset, floatVal($row->total_caixa));
            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->vendedor_id, $row->data_pedido));
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
            $meta = $imagemTelecom->metaVendedor($row->vendedor_id, $this->mes, $this->ano);

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));

            array_push($datasetTendencia, $imagemTelecom->tendenciaDiaria($row->vendedor_id, $row->data_pedido));
            array_push($datasetMeta, $meta === null ? 0 : $meta->meta_faturamento);
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
    public function getChartDiario()
    {
        $data = $this->getVendasDiarias();
        $imagemTelecom = new ImagemTelecomService(new Venda());


        $chartData = [];
        $label = [];
        $dataset = [];
        $datasetMeta = [];
        $datasetTendencia = [];

        foreach ($data as $row) {
            $meta = $imagemTelecom->metaVendedor($row->vendedor_id, $this->mes, $this->ano);

            array_push($label, Carbon::parse($row->data_pedido)->format('d/m'));
            array_push($dataset, floatVal($row->total_caixa));
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
            ->where('vendedor_id', $this->vendedor->id)
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
            $meta = $imagemTelecom->metaVendedor($row->vendedor_id, $this->mes, $this->ano);
            array_push($label, $row->vendedor->nome);
            array_push($dataset, floatVal($row->total));
            array_push($dataCounter, floatVal($row->aparelho));
            array_push($datasetMeta, $meta === null ? 0 : $meta->meta_aparelhos);
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['contador'] = $dataCounter;
        $chartData['datasetMeta'] = $datasetMeta;



        $chart = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => true,

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
            $meta = $imagemTelecom->metaVendedor($row->vendedor_id, $this->mes, $this->ano);
            array_push($label, $row->vendedor->nome);
            array_push($dataset, floatVal($row->total));
            array_push($datasetMeta, floatVal($meta === null ? 0 : $meta->meta_acessorios));
        }
        $chartData['label'] = $label;
        $chartData['dataset'] = $dataset;
        $chartData['contador'] = $dataCounter;
        $chartData['datasetMeta'] = $datasetMeta;



        $chart = [
            'type' => 'bar',
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => true,

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
            $meta = $imagemTelecom->metaVendedor($row->vendedor_id, $this->mes, $this->ano);

            array_push($label, $row->vendedor->nome);
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
            ->selectRaw('vendedor_id, count(*) as aparelho,sum(base_faturamento_compra) as total')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->when($this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->anoSelecionado);
            })
            ->when(!$this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->ano);
            })
            ->where('vendedor_id', $this->vendedor->id)
            ->where('grupo_estoque', 'APARELHO')
            ->groupBy('vendedor_id')
            ->get();
    }

    public function getAcessorios()
    {
        return Venda::query()
            ->selectRaw('vendedor_id,sum(valor_caixa) as total')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mes);
            })
            ->when($this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->anoSelecionado);
            })
            ->when(!$this->anoSelecionado, function ($query) {
                $query->whereYear('data_pedido', $this->ano);
            })
            ->where('vendedor_id', $this->vendedor->id)
            ->whereIn('grupo_estoque', ['ACESSORIOS', 'ACESSORIOS TIM'])
            ->groupBy('vendedor_id')
            ->get();
    }

    public function getFranquia()
    {
        return Venda::query()
            ->selectRaw('vendedor_id,sum(valor_franquia) as total')
            ->when($this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', $this->mesSelecionado);
            })
            ->when(!$this->mesSelecionado, function ($query) {
                $query->whereMonth('data_pedido', '11');
            })
            ->when($this->vendedor->id, function ($query) {
                $query->where('vendedor_id', $this->vendedor->id);
            })
            ->where('grupo_estoque', 'RECARGA ELETRONICA')
            ->groupBy('vendedor_id')
            ->get();
    }

    public function getGrupos()
    {
        return Grupo::query()
            ->get();
    }

    public function totalPlanos()
    {
        $planos = $this->getGrupos();

        $grupos = [];

        foreach ($planos as $plano) {
            $modalidade = explode(';', $plano->modalidade_venda);
            $plano_habilitacao = explode(';', $plano->plano_habilitacao);
            $grupo_estoque = null;
            $campo_valor = $plano->campo_valor;

            $metas = MetasVendedores::query()
                ->selectRaw('sum(meta_pos) as total_meta_pos,sum(meta_pre) as total_meta_pre,sum(meta_controle) as total_meta_controle')
                ->selectRaw('sum(meta_gross_pos) as total_meta_gross_pos,sum(meta_gross_pre) as total_meta_gross_pre,sum(meta_gross_controle) as total_meta_gross_controle')
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
                ->where('vendedor_id', $this->vendedor->id)
                ->get();


            $vendas = Venda::query()
                ->selectRaw('count(*) as gross,sum(' . $campo_valor . ') as total')
                ->whereIn('modalidade_venda', $modalidade)
                ->whereIn('plano_habilitacao', $plano_habilitacao)
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
                ->where('vendedor_id', $this->vendedor->id)
                ->get();

            $nome_campo = explode(' ', $this->tirarAcentos($plano->nome));


            $grupos[] = [
                'id' => $plano->id,
                'grupo' => $plano->nome,
                'gross' => $vendas[0]->gross,
                'meta_gross' => $metas[0]['total_meta_gross_' . $nome_campo[1]] ?? 0,
                'total' => $vendas[0]->total,
                'meta_plano' => $metas[0]['total_meta_' . $nome_campo[1]],
            ];
        }

        return $grupos;
    }

    public function rankingFabricantes()
    {

        $rankingFabricantes = Venda::query()
            ->select('fabricante', DB::raw('sum(valor_caixa) as Total'))
            ->whereIn('tipo_pedido', ['Venda', 'VENDA'])
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
            ->where('vendedor_id', $this->vendedor->id)
            ->where('grupo_estoque', 'APARELHO')
            ->where('fabricante', '<>', '')
            ->groupBy('fabricante')
            ->get();


        $fabricanteLabels = [];
        $fabricanteDatasets = [];

        foreach ($rankingFabricantes as $ranking) {
            $fabricanteLabels[] = $ranking->fabricante;
            $fabricanteDatasets[] = $ranking->total;
        }


        return [
            'type' => 'pie',
            'data' => [
                'labels' => $fabricanteLabels ?? null,
                'datasets' => [
                    [
                        'label' => 'R$',
                        'data' => $fabricanteDatasets ?? null,
                    ]
                ]
            ]
        ];
    }

    public function tirarAcentos($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), strtolower($string));
    }
}
