<?php

namespace App\Livewire\Charts;

use Livewire\Component;

class GrupoEstoque extends Component
{
    public $mes;
    public $ano;

    public function mount(){
        $this->mes = date('m');
        $this->ano = date('Y');


    }
    public function render()
    {
        $data = $this->getGrupoEstoque();
        return view('livewire.charts.grupo-estoque', ['data' => $data]);
    }

    public function getGrupoEstoque()
    {
        $data = \App\Models\Venda::query()
            ->selectRaw('grupo_estoque, SUM(valor_caixa) as total_vendas')
            ->whereNot('grupo_estoque', 'APARELHO')
            ->whereMonth('created_at', $this->mes)
            ->whereYear('created_at', $this->ano)
            ->GroupBy('grupo_estoque')
            ->orderBy('total_vendas', 'desc')
            ->limit(5)
            ->get();

        return $data;
    }
}
