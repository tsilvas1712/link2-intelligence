<?php

namespace App\Livewire\App\Components;

use App\Models\Grupo;
use App\Models\GrupoEstoque;
use App\Models\MetaGroup;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitacao;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class GroupTotal extends Component
{
    public $group_id;
    public $dt_start;
    public $dt_end;
    public $selectedFilial = null;
    public $selectedVendedor = null;

    public function mount($group_id)
    {
        $this->group_id = $group_id;
        $this->dt_start = Carbon::now()->subDay(1)->startOfMonth()->format('Y-m-d');
        $this->dt_end = Carbon::now()->subDay(1)->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $group = Grupo::find($this->group_id);

        return view(
            'livewire.app.components.group-total',
            [
                'grupo' => $group,

            ]
        );
    }

    #[On('update-dash')]
    public function updatedDateStart($data)
    {
        $this->dt_start = $data['date_start'];
        $this->dt_end = $data['date_end'];
        $this->selectedFilial = $data['filiais_id'];
        $this->selectedVendedor = $data['vendedores_id'];
    }

    #[Computed]
    public function getValores(): array
    {
        $grupo = Grupo::find($this->group_id);
        $grupo_estoque = null;
        $plano_habilitado = null;
        $modalidade_venda = null;

        $meta = cache()->remember(
            "meta_group_{$this->group_id}_{$this->dt_start}_{$this->dt_end}_" . implode('_', $this->selectedFilial ?? []) . '_' . implode('_', $this->selectedVendedor ?? []),
            60 * 60 * 24, // Cache for 24 hours
            function () {
                return MetaGroup::query()
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
            }
        );



        if ($grupo->grupo_estoque) {
            $grupo_estoque = cache()->remember(
                "grupo_estoque_{$this->group_id}_{$this->dt_start}_{$this->dt_end}_" . implode('_', $this->selectedFilial ?? []) . '_' . implode('_', $this->selectedVendedor ?? []),
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
                "plano_habilitacao_{$this->group_id}_{$this->dt_start}_{$this->dt_end}_" . implode('_', $this->selectedFilial ?? []) . '_' . implode('_', $this->selectedVendedor ?? []),
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
                "modalidade_venda_{$this->group_id}_{$this->dt_start}_{$this->dt_end}_" . implode('_', $this->selectedFilial ?? []) . '_' . implode('_', $this->selectedVendedor ?? []),
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


        $total = cache()->remember(
            "total_group_{$this->group_id}_{$this->dt_start}_{$this->dt_end}_" . implode('_', $this->selectedFilial ?? []) . '_' . implode('_', $this->selectedVendedor ?? []),
            60 * 60 * 24, // Cache for 24 hours
            function () use ($grupo_estoque, $plano_habilitado, $modalidade_venda, $campo_valor) {
                return Venda::query()
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
            ->when($this->selectedVendedor, function ($query) {
                return $query->whereIn('vendedor_id', $this->selectedVendedor);
            })
            ->whereBetween('data_pedido', [$this->dt_start, $this->dt_end])
            ->sum($campo_valor);
            }
        );


        $total_dias = Carbon::parse($this->dt_start)->diffInDays(Carbon::parse($this->dt_end)) + 1;
        $tendencia = ($total / $total_dias) * $total_dias;

        $projecao = $meta ? ($total * 100) / $meta : 0;


        return ['total' => $total, 'meta_valor' => $meta, 'tendencia' => $tendencia, 'projecao' => $projecao];
    }
}
