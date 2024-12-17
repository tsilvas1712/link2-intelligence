<?php

namespace App\Livewire\Metas;

use App\Models\Filial;
use App\Models\Grupo;
use App\Models\MetasFiliais;
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
    public $meses;
    public $anos;

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
    public $mesSelecionado;
    public $anoSelecionado;
    public $filiais_id = [];


    public function mount()
    {
        $this->ano = Carbon::now()->format('Y');
        $this->mes = '11'; //Carbon::now()->format('m');

        $vendaModel = new VendaModel();
        $imagemTelecom = new ImagemTelecomService($vendaModel);
        $this->meses = $this->getMeses();
        $this->anos = $this->getAnos();

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




        $this->metas = $this->getMetas();





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


        $this->faturamentoTotal = $this->getFaturamento();
        $this->aparelhosTotal = $this->getTotalAparelhos();
        $this->acessoriosTotal = $this->getTotalAcessorios();
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

            $chartMetasLabels[] = $meses[$mes['id']];
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





        $vendedores = Vendedor::query()->limit(10)->get();

        $filialData = [];
        $filialFaturamento = [];
        $filialTendencia = [];

        $vendedoresData = [];
        $vendedorFaturamento = [];
        $vendedorTendencia = [];

        $this->filiais = $this->getVendasFiliais();



        $status = ['up', 'down', 'ok'];


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

    public function filter()
    {
        $imagemTelecom = new ImagemTelecomService(new VendaModel());
        $this->getVendas();
        $this->metas = $this->getMetas();
        $this->filiais = $this->getVendasFiliais();
        //$this->chartVendasDiarias = $this->getChartVendasDiarias();
        //$this->vendedores = $this->getvendedoresData();
        //$this->chartAparelhos = $this->chartAparelho();
        //$this->chartAcessorios = $this->chartAcessoriosData();
        //$this->chartFranquia = $this->chartFranquiaData();
        $this->faturamentoTotal = $this->getFaturamento();
        $this->aparelhosTotal = $this->getTotalAparelhos();
        $this->acessoriosTotal = $this->getTotalAcessorios();
        $this->tendenciaFaturamento = $imagemTelecom->tendencia($this->faturamentoTotal);
        $this->tendenciaAcessorioTotal = $imagemTelecom->tendencia($this->acessoriosTotal);
        $this->tendenciaFranquiaTotal = $imagemTelecom->tendencia($this->franquiaTotal);
        $this->tendenciaAparelhosTotal = $imagemTelecom->tendencia($this->aparelhosTotal);
    }

    public function getVendas()
    {
        return VendaModel::query()
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
            ->when($this->filiais_id, function ($query) {
                $query->whereIn('filial_id', $this->filiais_id);
            })
            ->get();
    }

    public function getVendasFiliais()
    {
        $imagemTelecom = new ImagemTelecomService(new VendaModel());
        $filiais =  VendaModel::query()
            ->selectRaw('filial_id, sum(valor_caixa) as total')
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
            ->when($this->filiais_id, function ($query) {
                $query->whereIn('filial_id', $this->filiais_id);
            })
            ->groupBy('filial_id')
            ->get();

        $status = ['up', 'down', 'ok'];

        $response = [];

        foreach ($filiais as $row) {
            $filial =  $row->filial;
            $meta = $imagemTelecom->metaFilial($filial->id, $this->mesSelecionado ?? $this->mes, $this->anoSelecionado ?? $this->ano)['meta_faturamento'] ?? 0;
            $faturamento = $row->total;


            $perc = ($faturamento / $meta) * 100;

            $key = 1;

            if ($perc > 100) {
                $key = 0;
            }

            if ($key > 95 && $key < 100) {
                $key = 2;
            }

            $response[] = [
                'id' => $filial->id,
                'filial' => $filial->filial,
                'status' => $status[$key],
                'faturamento' => $faturamento,
                'tendencia' => $imagemTelecom->tendenciaFilial($filial->id),
                'meta' => $meta,
            ];
        }



        return $response;
    }

    public function getMetas()
    {
        $filiais_ids = VendaModel::query()
            ->select('filial_id')
            ->where('tipo_pedido', 'Venda')
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
            ->when($this->filiais_id, function ($query) {
                $query->whereIn('filial_id', $this->filiais_id);
            })
            ->groupBy('filial_id')
            ->get();

        $meta = MetasFiliais::query()
            ->selectRaw('sum(meta_faturamento) as meta_faturamento, sum(meta_acessorios) as meta_acessorios, sum(meta_aparelhos) as meta_aparelhos')
            ->whereIn('filial_id', $filiais_ids)
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
            ->when($this->filiais_id, function ($query) {
                $query->whereIn('filial_id', $this->filiais_id);
            })
            ->get();

        return $meta->toArray();
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

    public function getFaturamento()
    {
        $tAparelhos = $this->getTotalAparelhos();
        $tAcessorios = $this->getTotalAcessorios();
        $tChips = $this->getTotalChips();

        $total = $tAcessorios + $tAparelhos + $tChips;

        return $total;
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
