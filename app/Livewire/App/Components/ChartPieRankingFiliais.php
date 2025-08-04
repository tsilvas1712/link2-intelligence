<?php

namespace App\Livewire\App\Components;

use App\Models\Filial;
use App\Models\Grupo;
use App\Models\GrupoEstoque;
use App\Models\MetaGroup;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitacao;
use App\Models\Venda;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ChartPieRankingFiliais extends Component
{
    public $dt_start;
    public $dt_end;
    public $selectedFilial = null;
    public $data = [];
    public array $chart;


    public function render()
    {
        return view('livewire.app.components.chart-pie-ranking-filiais');
    }

    #[Computed]
    public function getValores(): array
    {
        $filiais = Venda::query()
            ->selectRaw('filial_id, SUM(valor_caixa) as total')
             ->whereBetween('data_pedido', [$this->dt_start, $this->dt_end])
            ->groupBy('filial_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->pluck('filial_id')
            ->toArray();



        $dataset = [];
        $labels = [];

        ds($filiais);



        foreach ($filiais as $filial) {
            $grupos = Grupo::all();
            $grupo_estoque = null;
            $plano_habilitado = null;
            $modalidade_venda = null;
            $totalVendas = 0;


            foreach ($grupos as $grupo) {
                $meta = cache()->remember(
                    "meta_group_{$filial}_group_{$grupo->id}_{$this->dt_start}_{$this->dt_end}_" . implode('_', $this->selectedFilial ?? []) . '_' . implode('_', $this->selectedVendedor ?? []),
                    60 * 60 * 24, // Cache for 24 hours
                    function () use ($grupo) {
                        return MetaGroup::query()
                            ->when($this->selectedFilial, function ($query) {
                                return $query->whereIn('filial_id', $this->selectedFilial);
                            })
                            ->whereBetween('mes', [Carbon::parse($this->dt_start)->month, Carbon::parse($this->dt_end)->month])
                            ->whereBetween('ano', [Carbon::parse($this->dt_start)->year, Carbon::parse($this->dt_end)->year])
                            ->where('grupo_id', $grupo->id)
                            ->sum('valor_meta');
                    }
                );


                if ($grupo->grupo_estoque) {
                    $grupo_estoque = cache()->remember(
                        "grupo_estoque_{$filial}_group_{$grupo->id}_{$this->dt_start}_{$this->dt_end}_" . implode('_', $this->selectedFilial ?? []) . '_' . implode('_', $this->selectedVendedor ?? []),
                        60 * 60 * 24, // Cache for 24 hours
                        function () use ($grupo) {
                            return GrupoEstoque::query()
                                ->whereIn('id', explode(';', $grupo->grupo_estoque))
                                ->pluck('nome')
                                ->toArray();
                        }
                    );
                }

                if ($grupo->plano_habilitacao) {
                    $plano_habilitado = cache()->remember(
                        "plano_habilitacao_{$filial}_group_{$grupo->id}_{$this->dt_start}_{$this->dt_end}_" . implode('_', $this->selectedFilial ?? []) . '_' . implode('_', $this->selectedVendedor ?? []),
                        60 * 60 * 24, // Cache for 24 hours
                        function () use ($grupo) {
                            return PlanoHabilitacao::query()
                                ->whereIn('id', explode(';', $grupo->plano_habilitacao))
                                ->pluck('nome')
                                ->toArray();
                        }
                    );
                }

                if ($grupo->modalidade_venda) {
                    $modalidade_venda = cache()->remember(
                        "modalidade_venda_{$filial}_group_{$grupo->id}_{$this->dt_start}_{$this->dt_end}_" . implode('_', $this->selectedFilial ?? []) . '_' . implode('_', $this->selectedVendedor ?? []),
                        60 * 60 * 24, // Cache for 24 hours
                        function () use ($grupo) {
                            return ModalidadeVenda::query()
                                ->whereIn('id', explode(';', $grupo->modalidade_venda))
                                ->pluck('nome')
                                ->toArray();
                        }
                    );
                }

                $campo_valor = $grupo->campo_valor;


                $vendas = Venda::query()
                            ->selectRaw('filial_id, SUM(' . $campo_valor . ') as total')
                            ->when($grupo_estoque, function ($query) use ($grupo_estoque) {
                                return $query->whereIn('grupo_estoque', $grupo_estoque);
                            })
                            ->when($plano_habilitado, function ($query) use ($plano_habilitado) {
                                return $query->whereIn('plano_habilitacao', $plano_habilitado);
                            })
                            ->when($modalidade_venda, function ($query) use ($modalidade_venda) {
                                return $query->whereIn('modalidade_venda', $modalidade_venda);
                            })
                          ->where('filial_id', $filial)
                            ->groupBy('filial_id')
                            ->whereBetween('data_pedido', [$this->dt_start, $this->dt_end])
                            ->get();


                $totalVendas += floatval($vendas[0]->total) ?? 0;
            }




            $dataset[] = $totalVendas;
            $labels[] = Filial::find($filial)->filial ?? 'Filial ' . $filial;
        }



        return [
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
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Ranking de Filiais',
                        'data' => $dataset,
                    ],
                ],
            ],
        ];
    }

    public function mount()
    {
        $this->dt_start = Carbon::now()->subDay(1)->startOfMonth()->format('Y-m-d');
        $this->dt_end = Carbon::now()->subDay(1)->endOfMonth()->format('Y-m-d');
        $this->chart = $this->getValores();
    }

    #[On('update-dash')]
    public function updatedDateStart($data)
    {
        $this->dt_start = $data['date_start'];
        $this->dt_end = $data['date_end'];
        $this->selectedFilial = $data['filiais_id'];

        $this->chart = $this->getValores();
    }
}
