<?php

namespace App\Livewire\Charts;

use Livewire\Component;

class Aparelhos extends Component
{
    public $mes;
    public $ano;

    public function mount(){
        $this->mes = date('m');
        $this->ano = date('Y');
    }

    public function render()
    {
        $data = $this->getAparelhos();
        return view('livewire.charts.aparelhos', ['data' => $data]);
    }
    public function getAparelhos()
    {
        $data = \App\Models\Venda::query()
            ->selectRaw('fabricante, SUM(base_faturamento_compra) as total_vendas')
            ->where('grupo_estoque', 'APARELHO')
            ->where('tipo_pedido','VENDA')
            ->whereMonth('created_at', $this->mes)
            ->whereYear('created_at', $this->ano)
            ->GroupBy('fabricante')
            ->orderBy('total_vendas', 'desc')
            ->limit(5)
            ->get();

        return $data;
    }
}
