<?php

namespace App\Livewire\App;

use App\Imports\VendasImport;
use App\Models\Category;
use App\Models\Filial;
use App\Models\MetasFiliais;
use App\Models\Venda;
use App\Models\Venda as VendaModel;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
    use WithFileUploads;

    public $ano;
    public $mes;
    public $meses;
    public $anos;
    public $file = null;

    public $selectedTab = 'charts';

    public $mesSelecionado;
    public $anoSelecionado;
    public $filiais_id = [];

    public $chartFiliais;
    public $chartFiliaisDown;
    public $chartVendedores;
    public $chartVendedoresDown;
    public $selectedTabF = 'filial-up';
    public $selectedTabV = 'vendedores-up';
    public $isLoading = false;
    public $isProcessing = false;
    public $dt_start;
    public $dt_end;

    public $selectedFiliais = [];
    public $selectedVendedores = [];
    //public $filiais = [];
    public $vendedores = [];


    public $chartMetas;

    public function mount()
    {
        $connection = 'queue';
        $queueName = 'default';

        $size = Queue::size('import_vendas');
        $processing = Queue::size('default');

        $this->isLoading = $size;
        $this->isProcessing = $processing > 0;


        $this->ano = Carbon::now()->subDay(1)->format('Y');
        $this->mes = Carbon::now()->subDay(1)->format('m');
        $this->dt_start = Carbon::now()->subDay(1)->startOfMonth()->format('Y-m-d');
        $this->dt_end = Carbon::now()->subDay(1)->endOfMonth()->format('Y-m-d');
        //$this->meses = $this->getMeses();
        //$this->anos = $this->getAnos();
        //$this->chartMetas = $this->getChartMetas();
        //$this->chartFiliais = $this->rankingFiliais();
        //$this->chartFiliaisDown = $this->rankingFiliaisDown();
        //$this->chartVendedores = $this->rankingVendedores();
        //$this->chartVendedoresDown = $this->rankingVendedoresDown();

        //$this->filiais = Filial::all();


        //$this->vendedores = $this->getVendedores() ?? [];

        //dd($this->vendedores);
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
                'name' => $anoInicial + $i,
            ];
        }

        return $anos;
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
            '12' => 'Dez',
        ];
        foreach ($this->meses as $mes) {
            $vendas = VendaModel::query()
                ->whereIn('tipo_pedido', ['Venda', 'VENDA'])
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
                'labels' => $chartMetasLabels,
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

    public function rankingFiliais()
    {
        $rankingFiliais = VendaModel::query()
            ->select('filial_id', DB::raw('sum(valor_caixa) as Total'))
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

    #[Computed]
    public function getVendedores()
    {
        $vendas = Venda::query()
            ->select('vendedor_id')
            ->whereIn('filial_id', $this->selectedFiliais)
            ->whereBetween('data_pedido', [$this->dt_start, $this->dt_end])
            ->groupBy('vendedor_id')
            ->get();


        if ($vendas->count() > 0) {
            return Vendedor::query()
                ->when($vendas->count() > 0, function ($query) use ($vendas) {
                    $query->whereIn('id', $vendas->pluck('vendedor_id'));
                })
                ->get();
        }

        return Vendedor::all();
    }

    #[Layout('components.layouts.view')]
    public function render()
    {
        $categories = cache()->remember(
            'categories',
            60 * 60 * 24, // Cache for 24 hours
            function () {
                return Category::query()
                    ->where('active', 1)
                    ->orderBy('order', 'asc')
                    ->get();
            }
        );

        $filiais = cache()->remember(
            'filiais',
            60 * 60 * 24, // Cache for 24 hours
            function () {
                return Filial::all();
            }
        );




        return view('livewire.app.dashboard', [
            'categories' => $categories,
            'filiais' => $filiais,
        ]);
    }

    public function uploadFile()
    {
        Excel::import(new VendasImport(), $this->file->getRealPath())->allOnQueue('import_vendas');

        return redirect()->route('dashboard');
    }

    public function updateDash()
    {
        $this->getVendedores();
        $this->dispatch('update-dash', [
            'date_start' => $this->dt_start,
            'date_end' => $this->dt_end,
            'filiais_id' => $this->selectedFiliais,
            'vendedores_id' => $this->selectedVendedores,
        ]);
    }
}
