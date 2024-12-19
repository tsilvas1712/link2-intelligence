<?php

namespace App\Livewire\Metas;

use App\Models\Filial;
use App\Models\Grupo;
use App\Models\MetasFiliais;
use App\Models\Venda as VendaModel;
use App\Models\Vendedor;
use App\Services\ImagemTelecomService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

    public $recargaTotal;
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



        $this->chartFabricante = $this->rankingFabricantes();


        $this->faturamentoTotal = $this->getFaturamento();
        $this->aparelhosTotal = $this->getTotalAparelhos();
        $this->acessoriosTotal = $this->getTotalAcessorios();
        $this->tendenciaFaturamento = $imagemTelecom->tendencia($this->faturamentoTotal);
        $this->tendenciaAcessorioTotal = $imagemTelecom->tendencia($this->acessoriosTotal);
        $this->tendenciaFranquiaTotal = $imagemTelecom->tendencia($this->franquiaTotal);
        $this->tendenciaAparelhosTotal = $imagemTelecom->tendencia($this->aparelhosTotal);








        $this->chartMetas = $this->getChartMetas();

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


        $this->chartFiliais = $this->rankingFiliais();

        $this->chartVendedores = $this->rankingVendedores();
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
        $this->chartMetas = $this->getChartMetas();
        $this->chartFiliais = $this->rankingFiliais();
        $this->chartVendedores = $this->rankingVendedores();
        $this->chartFabricante = $this->rankingFabricantes();

        //$this->chartVendasDiarias = $this->getChartVendasDiarias();
        //$this->vendedores = $this->getvendedoresData();
        //$this->chartAparelhos = $this->chartAparelho();
        //$this->chartAcessorios = $this->chartAcessoriosData();
        //$this->chartFranquia = $this->chartFranquiaData();
        $this->faturamentoTotal = $this->getFaturamento();
        $this->aparelhosTotal = $this->getTotalAparelhos();
        $this->acessoriosTotal = $this->getTotalAcessorios();
        $this->recargaTotal = $this->getTotalRecarga();
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
            ->select('filial_id')
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

            $mes = $this->mesSelecionado ?? $this->mes;
            $ano = $this->anoSelecionado ?? $this->ano;
            $aparelhos = $imagemTelecom->aparelhosFilial($row->filial_id, $mes, $ano);

            $acessorios = $imagemTelecom->acessoriosFilial($row->filial_id, $mes, $ano);
            $chips = $imagemTelecom->chipsFilial($row->filial_id, $mes, $ano);
            $recarga = $imagemTelecom->recargaFilial($row->filial_id, $mes, $ano);
            //$filial =  $row->filial;
            $meta = $imagemTelecom->metaFilial($row->filial_id, $this->mesSelecionado ?? $this->mes, $this->anoSelecionado ?? $this->ano)['meta_faturamento'] ?? 0;
            $faturamento = $aparelhos + $acessorios + $chips + $recarga;

            $perc = ($faturamento / $meta) * 100;

            $key = 1;

            if ($perc > 100) {
                $key = 0;
            }

            if ($key > 95 && $key < 100) {
                $key = 2;
            }

            $response[] = [
                'id' => $row->filial->id,
                'filial' => $row->filial->filial,
                'status' => $status[$key],
                'faturamento' => $faturamento,
                //'aparelhos' => $aparelhos,
                //'acessorios' => $aparelhos_filial,
                //'chips' => $chips,
                //'recarga' => $recarga,
                'tendencia' => $imagemTelecom->tendenciaFilial($row->filial_id, $mes, $ano, $faturamento),
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
            ->where('tipo_pedido', 'Venda')
            ->where('grupo_estoque', 'APARELHO')
            ->sum('base_faturamento_compra');
    }

    public function getTotalAcessorios()
    {
        $vendas = $this->getVendas();
        return $vendas->where('tipo_pedido', 'Venda')->whereIn('grupo_estoque', ['ACESSORIOS', 'ACESSORIOS TIM'])->sum('valor_caixa');
    }

    public function getChartMetas()
    {
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
            $vendas =  VendaModel::query()
                ->where('tipo_pedido', 'Venda')
                ->whereMonth('data_pedido', $mes)
                ->when($this->anoSelecionado, function ($query) {
                    $query->whereYear('data_pedido', $this->anoSelecionado);
                })
                ->when(!$this->anoSelecionado, function ($query) {
                    $query->whereYear('data_pedido', $this->ano);
                })
                ->when(
                    $this->filiais_id,
                    function ($query) {
                        $query->whereIn('filial_id', $this->filiais_id);
                    }
                )

                ->get();



            $meta = MetasFiliais::query()
                ->where('mes', $mes['id'])
                ->when($this->anoSelecionado, function ($query) {
                    $query->where('ano', $this->anoSelecionado);
                })
                ->when(!$this->anoSelecionado, function ($query) {
                    $query->where('ano', $this->ano);
                })
                ->when(
                    $this->filiais_id,
                    function ($query) {
                        $query->whereIn('filial_id', $this->filiais_id);
                    }
                )
                ->sum('meta_faturamento');





            $aparelhos = $vendas->where('grupo_estoque', 'APARELHO')->sum('base_faturamento_compra');
            $acessorios = $vendas->whereIn('grupo_estoque', ['ACESSORIOS', 'ACESSORIOS TIM'])->sum('valor_caixa');
            $chip = $vendas->whereIn('grupo_estoque', ['CHIP'])->sum('valor_caixa');
            $recarga = $vendas->whereIn('grupo_estoque', ['RECARGA', 'RECARGA GWCEL'])->sum('valor_caixa');

            $chartMetasLabels[] = $meses[$mes['id']];
            $chartMetasDatasets[] = $aparelhos + $acessorios + $chip + $recarga;
            //$meta = $imagemTelecom->meta($mes, $this->ano);
            $chartMetasDatasetsMeta[] = $meta ?? 0;
        }

        return [
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
    }

    public function getTotalChips()
    {
        $vendas = $this->getVendas();
        return $vendas->where('tipo_pedido', 'Venda')->whereIn('grupo_estoque', ['CHIP'])->sum('valor_caixa');
    }

    public function getTotalRecarga()
    {
        $vendas = $this->getVendas();
        return $vendas->where('tipo_pedido', 'Venda')->whereIn('grupo_estoque', ['RECARGA', 'RECARGA GWCEL'])->sum('valor_caixa');
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
        $tRecarga = $this->getTotalRecarga();

        $total = $tAcessorios + $tAparelhos + $tChips + $tRecarga;

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

    public function rankingVendedores()
    {

        $rankingVendedores = VendaModel::query()
            ->select('vendedor_id', DB::raw('sum(valor_caixa) as Total'))
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
            ->groupBy('vendedor_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        foreach ($rankingVendedores as $vendedor) {
            $vendedoresData[] = $vendedor->vendedor->nome;
            $vendedorFaturamento[] = $vendedor->total;
        }

        return [
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

    public function rankingFiliais()
    {
        $rankingFiliais = VendaModel::query()
            ->select('filial_id', DB::raw('sum(valor_caixa) as Total'))
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
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        foreach ($rankingFiliais as $filial) {
            $filialData[] = $filial->filial->filial;
            $filialFaturamento[] = $filial->total;
        }


        return [
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
    }

    public function rankingFabricantes()
    {

        $rankingFabricantes = VendaModel::query()
            ->select('fabricante', DB::raw('sum(valor_caixa) as Total'))
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
                'labels' => $fabricanteLabels,
                'datasets' => [
                    [
                        'label' => '# of Votes',
                        'data' => $fabricanteDatasets,
                    ]
                ]
            ]
        ];
    }
}
