<?php

namespace App\Livewire\App\GrupoEstoque;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
use WithPagination;

    public $mes;
    public $ano;
    public $grupo_estoque;

    public function mount($grupo):void
    {
        $this->ano = date('Y');
        $this->grupo_estoque = $grupo;
        $this->mes = date('m');
    }

    #[Layout('components.layouts.view')]
    public function render()
    {
        return view('livewire.app.grupo-estoque.show');
    }

    public function headers(): array
    {
        return [
            //['key' => 'id', 'label' => '#'],
            ['key' => 'numero_pv', 'label' => 'Número PV','class' => 'text-sm'],
            ['key' => 'data_pedido', 'label' => 'Data Pedido','class' => 'text-sm'],
            ['key' => 'filial_id', 'label' => 'Filial','class' => 'text-sm'],
            ['key' => 'vendedor_id', 'label' => 'Vendedor','class' => 'text-sm'],
            ['key' => 'descricao_comercial', 'label' => 'Descrição Comercial','class' => 'text-sm'],
            ['key' => 'valor_caixa', 'label' => 'Valor Caixa','class' => 'text-sm'],
        ];
    }

    #[Computed]
    public function getData():LengthAwarePaginator
    {
        $data = \App\Models\Venda::query()
            ->select('id', 'numero_pv','data_pedido','filial_id','vendedor_id','vendas.descricao_comercial', 'valor_caixa')
            ->where('grupo_estoque', $this->grupo_estoque)
            ->whereMonth('created_at', $this->mes)
            ->whereYear('created_at', $this->ano)
            ->orderBy('data_pedido', 'desc')
            ->paginate();

        return $data;
    }
}
