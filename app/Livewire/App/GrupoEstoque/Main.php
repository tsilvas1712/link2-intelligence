<?php

namespace App\Livewire\App\GrupoEstoque;

use App\Exports\GrupoEstoqueExport;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Main extends Component
{
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
        return view('livewire.app.grupo-estoque.main');
    }

    public function headers(): array
    {
        return [
            ['key' => 'grupo_estoque', 'label' => 'Grupo de Estoque'],
            ['key' => 'total_vendas', 'label' => 'Total em Vendas'],
        ];
    }
    #[Computed]
    public function getData():LengthAwarePaginator
    {
        $data = \App\Models\Venda::query()
            ->selectRaw('grupo_estoque, SUM(valor_caixa) as total_vendas')
            ->whereNot('grupo_estoque', 'APARELHO')
            ->whereMonth('created_at', $this->mes)
            ->whereYear('created_at', $this->ano)
            ->GroupBy('grupo_estoque')
            ->orderBy('total_vendas', 'desc')
            ->paginate();
        ds($data);

        return $data;

    }

    public function exportToExcel()
    {
        $grupo_estoque = 'ACESSÓRIOS';
        return (new GrupoEstoqueExport($this->mes, $this->ano,$grupo_estoque))->download('Grupo_Estoque_'.$this->mes.'_'.$this->ano.'.xlsx');
    }
}
