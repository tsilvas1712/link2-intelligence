<?php

namespace App\Livewire\Charts;

use Livewire\Component;

class Planos extends Component
{
    public $mes;
    public $ano;

    public function mount(){
        $this->mes = date('m');
        $this->ano = date('Y');
    }
    public function render()
    {
        return view('livewire.charts.planos', ['data' => $this->getPlanos()]);
    }

    public function getPlanos()
    {
        $data = \App\Models\Venda::query()
            ->selectRaw('plano_habilitacao, SUM(valor_franquia) as total_vendas')
            ->where('grupo_estoque', 'CHIP')
            ->where('tipo_pedido','VENDA')
            ->whereMonth('created_at', $this->mes)
            ->whereYear('created_at', $this->ano)
            ->GroupBy('plano_habilitacao')
            ->orderBy('total_vendas', 'desc')
            ->limit(5)
            ->get();

        return $data;
    }
}
