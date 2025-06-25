<?php

namespace App\Livewire\App\Aparelhos;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination;

    public $mes;
    public $ano;

    public function mount()
    {
        $this->mes = date('m');
        $this->ano = date('Y');
    }

    #[Layout('components.layouts.view')]
    public function render()
    {
        return view('livewire.app.aparelhos.main');
    }

    public function headers(): array
    {
        return [
            ['key' => 'descricao_comercial', 'label' => 'Produto'],
            ['key' => 'total_vendas', 'label' => 'Total em Vendas'],
        ];
    }
    #[Computed]
    public function getData():LengthAwarePaginator
    {
        $data = \App\Models\Venda::query()
            ->selectRaw('descricao_comercial, SUM(base_faturamento_compra) as total_vendas')
            ->where('grupo_estoque', 'APARELHO')
            ->where('tipo_pedido','VENDA')
            ->whereMonth('created_at', $this->mes)
            ->whereYear('created_at', $this->ano)
            ->GroupBy('descricao_comercial')
            ->orderBy('total_vendas', 'desc')
            ->paginate();


        return $data;

    }
}
