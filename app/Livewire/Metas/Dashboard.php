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
use Illuminate\Support\Sleep;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\LaravelPdf\Facades\Pdf;

class Dashboard extends Component
{
    public $chartMetas;
    public $chartFiliais;
    public $chartFiliaisDown;
    public $chartVendedores;
    public $chartVendedoresDown;
    public $chartFabricante;
    public $filiais;
    public $vendedores;
    public $faturamentoTotal = 0;


    public $aparelhosTotal = 0;
    public $recargasTotal = 0;
    public $acessoriosTotal = 0;
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

    public $loading = true;

    public $selectedTab = 0;
    public $selectedTabV = 0;


    public function mount()
    {
        $this->ano = Carbon::now()->subDay(1)->format('Y');
        $this->mes = Carbon::now()->subDay(1)->format('m');
        $this->meses = $this->getMeses();
        $this->anos = $this->getAnos();
        $this->selectedTab = 'filial-up';
        $this->selectedTabV = 'vendedores-up';
        //Sleep::for(10)->seconds();

        //$this->init();
    }
    #[Layout('components.layouts.view')]
    public function render()
    {
        $this->init();
        $this->loading = true;
        return view('livewire.metas.dashboard');
    }

    public function init()
    {
        $this->ano = Carbon::now()->subDay(0)->format('Y');
        $this->mes = Carbon::now()->subDay(0)->format('m');
        $this->loading = true;

        $vendaModel = new VendaModel();
        $imagemTelecom = new ImagemTelecomService($vendaModel);


        $planos = $this->totalPlanos();
        $this->planos = $this->totalPlanos();

        $chartPlanosLabel = [];
        $chartPlanosGross = [];
        $chartPlanosTotal = [];
        $TotalVendas = 0;
        $totalGross = 0;

        foreach ($planos as $plano) {
            $chartPlanosLabel[] = $plano['grupo'];
            $chartPlanosTotal[] = floatval($plano['total']);
            $chartPlanosGross[] = $plano['gross'];
            $TotalVendas += $plano['total'];
            $totalGross += floatval($plano['gross']);
        }

        $this->chartPlanosValor = [
            'type' => 'bar',
            'data' => [
                'labels' =>  $chartPlanosLabel,
                'datasets' => $chartPlanosTotal
            ],
            'total' => floatval($TotalVendas),
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
                'datasets' => $chartPlanosGross,

            ],
            'total' => floatval($totalGross),
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
        $this->chartFiliaisDown = $this->rankingFiliaisDown();

        $this->chartVendedores = $this->rankingVendedores();
        $this->chartVendedoresDown = $this->rankingVendedoresDown();

        $this->loading = false;
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
        $this->chartFiliaisDown = $this->rankingFiliaisDown();
        $this->chartVendedores = $this->rankingVendedores();
        $this->chartVendedoresDown = $this->rankingVendedoresDown();
        $this->chartFabricante = $this->rankingFabricantes();
        $this->planos = $this->totalPlanos();

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
                        'label' => 'Total em Planos',
                        'data' => $chartPlanosGross,
                        'borderColor' => '#2C5494',
                        'backgroundColor' => '#849CBC',
                    ],


                ],

            ]
        ];

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

            $perc = $meta === 0 ? 0 : ($faturamento / $meta) * 100;

            $key = 1;

            if ($perc > 100) {
                $key = 0;
            }

            if ($key >= 80 && $key <= 100) {
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
            $totalVendas = $aparelhos + $acessorios + $chip + $recarga;
            $chartMetasLabels[] = $meses[$mes['id']];
            $chartMetasDatasets[] = $totalVendas;
            //$meta = $imagemTelecom->meta($mes, $this->ano);
            $chartMetasDatasetsMeta[] = $meta ?? 0;
        }

        return [
            'type' => 'bar',
            'data' => [
                'labels' =>  $chartMetasLabels,
                'datasets' => [
                    [
                        'name' => 'Tendência',
                        'data' => $chartMetasDatasets,

                    ],
                    [
                        'name' => 'Vendas',
                        'data' => $chartMetasDatasets,

                    ],
                    [
                        'name' => 'Meta',
                        'data' => $chartMetasDatasetsMeta,

                    ],

                ],

            ],
            'horizontal' => false,
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
        return $vendas->where('tipo_pedido', 'Venda')->whereIn('grupo_estoque', ['RECARGA ELETRONICA', 'RECARGA GWCEL'])->sum('valor_caixa');
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




    public function getGrupos()
    {
        return Grupo::query()
            ->orderBy('id', 'asc')
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
            if ($vendedor->total) {
                $vendedoresData[] = $vendedor->vendedor->nome;
                $vendedorFaturamento[] = $vendedor->total;
            }
        }

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $vendedoresData ?? null,
                'datasets' => $vendedorFaturamento ?? null,
            ],
            'horizontal' => true,
        ];
    }

    public function rankingVendedoresDown()
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
            ->orderBy('total', direction: 'asc')
            ->limit(10)
            ->get();


        foreach ($rankingVendedores as $vendedor) {
            if ($vendedor->total) {
                $vendedoresData[] = $vendedor->vendedor->nome;
                $vendedorFaturamento[] = $vendedor->total;
            }
        }

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $vendedoresData ?? null,
                'datasets' => $vendedorFaturamento ?? null,
            ],
            'horizontal' => true,
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
            'data' => [
                'labels' => $filialData ?? [],
                'datasets' => $filialFaturamento ?? [],
            ],
            'horizontal' => true,
        ];
    }
    public function rankingFiliaisDown()
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
            ->orderBy('total', direction: 'asc')
            ->limit(10)
            ->get();

        foreach ($rankingFiliais as $filial) {
            $filialData[] = $filial->filial->filial;
            $filialFaturamento[] = $filial->total;
        }


        return [
            'data' => [
                'labels' => $filialData ?? [],
                'datasets' => $filialFaturamento ?? [],
            ],
            'horizontal' => true,
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
            $fabricanteDatasets[] = floatval($ranking->total);
        }


        return [
            'type' => 'pie',
            'data' => [
                'labels' => $fabricanteLabels ?? null,
                'datasets' => $fabricanteDatasets ?? null
            ]
        ];
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

            $metas = MetasFiliais::query()
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
                ->when($this->filiais_id, function ($query) {
                    $query->whereIn('filial_id', $this->filiais_id);
                })
                ->get();


            $vendas = VendaModel::query()
                ->selectRaw('sum(' . $campo_valor . ') as total, count(gsm) as gross')
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
                ->when($this->filiais_id, function ($query) {
                    $query->whereIn('filial_id', $this->filiais_id);
                })
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

    public function tirarAcentos($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), strtolower($string));
    }
}
