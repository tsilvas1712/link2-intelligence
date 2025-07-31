<?php

namespace App\Livewire\App\Components;

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

class GroupChart extends Component
{
    public $group_id;
    public $dt_start;
    public $dt_end;
    public $selectedFilial = null;
    public $selectedVendedor = null;
    public $data = [];
    public array $chart;


    public function render()
    {
        return view('livewire.app.components.group-chart');
    }

    public function mount($group_id)
    {
        $this->group_id = $group_id;
        $this->dt_start = Carbon::now()->subDay(1)->startOfMonth()->format('Y-m-d');
        $this->dt_end = Carbon::now()->subDay(1)->endOfMonth()->format('Y-m-d');
        $this->chart = $this->getValores();
    }

    #[Computed]
    public function getValores(): array
    {
        $grupo = Grupo::find($this->group_id);
        $grupo_estoque = null;
        $plano_habilitado = null;
        $modalidade_venda = null;

        $meta = MetaGroup::query()
            ->when($this->selectedFilial, function ($query) {
                return $query->whereIn('filial_id', $this->selectedFilial);
            })
            ->when($this->selectedVendedor, function ($query) {
                return $query->whereIn('vendedor_id', $this->selectedVendedor);
            })
            ->whereBetween('mes', [Carbon::parse($this->dt_start)->month, Carbon::parse($this->dt_end)->month])
            ->whereBetween('ano', [Carbon::parse($this->dt_start)->year, Carbon::parse($this->dt_end)->year])
            ->where('grupo_id', $this->group_id)
            ->sum('valor_meta');


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


        $vendas = Venda::query()
            ->when(!$this->selectedFilial, function ($query) use ($campo_valor) {
                return $query->selectRaw('SUM(' . $campo_valor . ') as total');
            })
            ->when($this->selectedFilial, function ($query) use ($campo_valor) {
                return $query->selectRaw('filial_id, SUM(' . $campo_valor . ') as total');
            })
            ->when($grupo_estoque, function ($query) use ($grupo_estoque) {
                return $query->whereIn('grupo_estoque', $grupo_estoque);
            })
            ->when($plano_habilitado, function ($query) use ($plano_habilitado) {
                return $query->whereIn('plano_habilitacao', $plano_habilitado);
            })
            ->when($modalidade_venda, function ($query) use ($modalidade_venda) {
                return $query->whereIn('modalidade_venda', $modalidade_venda);
            })
            ->when($this->selectedFilial, function ($query) {
                return $query->whereIn('filial_id', $this->selectedFilial);
            })
            ->when($this->selectedFilial, function ($query) {
                return $query->groupBy('filial_id');
            })
            ->when($this->selectedVendedor, function ($query) {
                return $query->whereIn('vendedor_id', $this->selectedVendedor);
            })
            ->whereBetween('data_pedido', [$this->dt_start, $this->dt_end])
            ->get();


        if (!$this->selectedFilial) {
            $total_dias = Carbon::parse($this->dt_start)->diffInDays(Carbon::parse($this->dt_end)) + 1;
            $tendencia = ($vendas[0]->total / $total_dias) * $total_dias;


            $projecao = $meta ? ($vendas[0]->total * 100) / $meta : 0;

            return [
                'type' => 'bar',
                'title' => $grupo->nome,
                'options' => [
                    'responsive' => true,
                    'legend' => [
                        'display' => false,
                    ],
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true,
                            'title' => [
                                'display' => true,
                                'text' => $grupo->nome,
                            ],
                            'grid' => [
                                'display' => false,
                            ],

                        ],
                        'x' => [
                            'title' => [
                                'display' => false,
                                'text' => 'Período',
                            ],
                            'grid' => [
                                'display' => false,
                            ],
                            'legends' => [
                                'display' => false,
                            ],
                        ],
                    ],
                ],
                'data' => [
                    'labels' => ['Total', 'Meta', 'Tendência'],
                    'datasets' => [
                        [
                            'label' => [''],
                            'legend' => false,
                            'backgroundColor' => ['#213152', '#26B7ED', '#077FF8'],
                            'data' => [$vendas[0]->total, $meta, $tendencia],
                        ],
                    ],
                ],
            ];
        }

        $dataVendas = [];
        //return ['total' => $total, 'meta_valor' => $meta, 'tendencia' => $tendencia, 'projecao' => $projecao];
        foreach ($vendas as $venda) {
            $total_dias = Carbon::parse($this->dt_start)->diffInDays(Carbon::parse($this->dt_end)) + 1;
            $tendencia = ($venda->total / $total_dias) * $total_dias;
            $meta = MetaGroup::query()
                ->where('filial_id', $venda->filial_id)
                ->where('grupo_id', $this->group_id)
                ->whereBetween('mes', [Carbon::parse($this->dt_start)->month, Carbon::parse($this->dt_end)->month])
                ->whereBetween('ano', [Carbon::parse($this->dt_start)->year, Carbon::parse($this->dt_end)->year])
                ->sum('valor_meta');

            $dataVendas[] = [
                'filial' => $venda->filial->filial,
                'total' => $venda->total,
                'meta' => $meta,
                'tendencia' => $tendencia,
            ];
        }

        $dataset = [];

        foreach ($dataVendas as $venda) {
            $dataset[] =
                [
                    'label' => $venda['filial'],
                    'legend' => false,
                    'backgroundColor' => ['#213152', '#26B7ED', '#077FF8'],
                    'data' => [$venda['total'], $venda['meta'], $venda['tendencia']],
                ];
        }

        return [
            'type' => 'bar',
            'title' => $grupo->nome,
            'options' => [
                'responsive' => true,
                'legend' => [
                    'display' => false,
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'title' => [
                            'display' => true,
                            'text' => $grupo->nome,
                        ],
                        'grid' => [
                            'display' => false,
                        ],

                    ],
                    'x' => [
                        'title' => [
                            'display' => false,
                            'text' => 'Período',
                        ],
                        'grid' => [
                            'display' => false,
                        ],
                        'legends' => [
                            'display' => false,
                        ],
                    ],
                ],
            ],
            'data' => [
                'labels' => ['Total', 'Meta', 'Tendência'],
                'datasets' => $dataset,
            ],
        ];
    }

    #[On('update-dash')]
    public function updatedDateStart($data)
    {
        $this->dt_start = $data['date_start'];
        $this->dt_end = $data['date_end'];
        $this->selectedFilial = $data['filiais_id'];
        $this->selectedVendedor = $data['vendedores_id'];
        $this->chart = $this->getValores();
    }
}
