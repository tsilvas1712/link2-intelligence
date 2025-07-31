<?php

namespace App\Livewire\App\Components\Detalhamento;

use App\Models\Grupo;
use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitacao;
use App\Models\Venda;
use App\Models\Vendedor;
use Livewire\Component;

class ChartPieVendedores extends Component
{
    public $vendedores;
    public $dt_inicio;
    public $dt_fim;
    public $grupo_id;
    public function render()
    {
        $series = [];
        $labels = [];
        if ($this->vendedores) {
            foreach ($this->vendedores as $vendedor) {
                $total = $this->totalVendedor($vendedor);
                $series[] = $total;
                $labels[] = $this->getVendedor($vendedor);
            }
        }



        $data = [
            'series' => $series,
            'labels' => $labels,

        ];

        return view('livewire.app.components.detalhamento.chart-pie-vendedores', [
            'data' => $data,
        ]);
    }

    public function totalVendedor($filial_id)
    {
        $vendas = Venda::query()
            ->whereBetween('data_pedido', [$this->dt_inicio, $this->dt_fim])
            ->get();



        $meta = 0;
        $grupo_estoque = [];
        $plano_habilitado = [];
        $modalidade_venda = [];

        $grupo = Grupo::find($this->grupo_id);
        if ($grupo->grupo_estoque) {
            $grupo_estoque = GrupoEstoque::query()
            ->whereIn('id', explode(';', $grupo->grupo_estoque) ?? [])
            ->pluck('nome')
            ->toArray();
        }

        if ($grupo->plano_habilitacao) {
            $plano_habilitado = PlanoHabilitacao::query()
                ->whereIn('id', explode(';', $grupo->plano_habilitacao) ?? [])
                ->pluck('nome')
                ->toArray();
        }

        if ($grupo->modalidade_venda) {
            $modalidade_venda = ModalidadeVenda::query()
                ->whereIn('id', explode(';', $grupo->modalidade_venda) ?? [])
                ->pluck('nome')
                ->toArray();
        }


        $totalVendas = $vendas
        ->when($grupo->grupo_estoque, function ($query) use ($grupo_estoque) {
            return $query->whereIn('grupo_estoque', $grupo_estoque);
        })
        ->when($grupo->plano_habilitacao, function ($query) use ($plano_habilitado) {
            return $query->whereIn('plano_habilitacao', $plano_habilitado);
        })
        ->when($grupo->modalidade_venda, function ($query) use ($modalidade_venda) {
            return $query->whereIn('modalidade_venda', $modalidade_venda);
        })
        ->where('filial_id', $filial_id)
        ->sum($grupo->campo_valor);

        return $totalVendas;
    }

    public function getVendedor($vendedor_id)
    {
        $vendedor = Vendedor::find($vendedor_id);
        if (!$vendedor) {
            return null;
        }

        return $vendedor->nome;
    }
}
