<?php

namespace App\Livewire\App;

use App\Imports\SyncMongoImport;
use App\Imports\VendasAtualImport;
use App\Imports\VendasImport;
use App\Models\Category;
use App\Models\Grupo;
use App\Models\GrupoEstoque;
use App\Models\MetasFiliais;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitacao;
use App\Models\Venda;
use App\Models\Venda as VendaModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
    public $file;

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


    public $chartMetas;

    public function mount()
    {
        $this->ano = Carbon::now()->subDay(1)->format('Y');
        $this->mes = Carbon::now()->subDay(1)->format('m');
        $this->meses = $this->getMeses();
        $this->anos = $this->getAnos();
        $this->chartMetas = $this->getChartMetas();
        $this->chartFiliais = $this->rankingFiliais();
        $this->chartFiliaisDown = $this->rankingFiliaisDown();
        $this->chartVendedores = $this->rankingVendedores();
        $this->chartVendedoresDown = $this->rankingVendedoresDown();
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
            '12' => 'Dez'
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

    #[Layout('components.layouts.view')]
    public function render()
    {


        $telas = Category::query()
            ->where('active', 1)
            ->orderBy('order', 'asc')
            ->get();
        return view('livewire.app.dashboard', [
            'telas' => $telas,
        ]);
    }

    public function getValores($id = null)
    {
        if (!$id) {
            return [];
        }

        $grupo = Grupo::find($id);
        $grupo_estoque = null;
        $plano_habilitado = null;
        $modalidade_venda = null;

        if ($grupo->grupo_estoque) {
            $grupo_estoque = GrupoEstoque::query()
                ->whereIn('id', explode(';', $grupo->grupo_estoque))
                ->pluck('nome')
                ->toArray();
        }

        if ($grupo->plano_habilitacao) {
            $plano_habilitado = PlanoHabilitacao::query()
                ->whereIn('id', explode(';', $grupo->plano_habilitacao))
                ->pluck('nome')
                ->toArray();
        }

        if ($grupo->modalidade_venda) {
            $modalidade_venda = ModalidadeVenda::query()
                ->whereIn('id', explode(';', $grupo->modalidade_venda))
                ->pluck('nome')
                ->toArray();
        }

        $campo_valor = $grupo->campo_valor;


        $total = Venda::query()
            ->when($grupo_estoque, function ($query) use ($grupo_estoque) {
                return $query->whereIn('grupo_estoque', $grupo_estoque);
            })
            ->when($plano_habilitado, function ($query) use ($plano_habilitado) {
                return $query->whereIn('plano_habilitacao', $plano_habilitado);
            })
            ->when($modalidade_venda, function ($query) use ($modalidade_venda) {
                return $query->whereIn('modalidade_venda', $modalidade_venda);
            })
            ->whereYear('data_pedido', '=', $this->ano)
            ->whereMonth('data_pedido', '=', $this->mes)
            ->sum($campo_valor);

        ds($total);

        return $total;


    }

    public function uploadFile()
    {

        Excel::import(new VendasImport(), $this->file->getRealPath());
        Excel::import(new VendasAtualImport(), $this->file->getRealPath());
    }
}