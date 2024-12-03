<?php

namespace App\Livewire\Filiais;

use App\Models\Filial;
use App\Models\Venda;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $filial;
    public $vendedores;

    public $vendedor_multi_ids = [];
    public function mount($id)
    {
        $this->filial = Filial::find($id);
        $this->vendedores = $this->getVendedores();
    }
    #[Layout('components.layouts.view')]
    public function render()
    {
        return view('livewire.filiais.show');
    }

    #[Computed]
    public function getData(): LengthAwarePaginator
    {
        return Venda::query()
            ->where('tipo_pedido', 'Venda')
            ->orderBy('data_pedido', 'desc')
            ->paginate();
    }

    public function getVendedores()
    {
        $vendedores = Venda::query()
            ->select('vendedor_id')
            ->where('filial_id', $this->filial->id)
            ->groupBy('vendedor_id')
            ->get();

        $select = [];

        foreach ($vendedores as $vendedor) {
            $select[] = [
                'id' => $vendedor->vendedor_id,
                'name' => $vendedor->vendedor->nome,
            ];
        }

        return $select;
    }

    public function headers()
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'gsm', 'label' => 'GSM'],
            ['key' => 'gsm_portado', 'label' => 'Portabilidade'],
            ['key' => 'data_pedido', 'label' => 'Data do Pedido'],
            ['key' => 'vendedor_id', 'label' => 'Vendedor'],
            ['key' => 'tipo_pedido', 'label' => 'Tipo'],
            ['key' => 'descricao', 'label' => 'Descrição'],
            ['key' => 'valor_caixa', 'label' => 'Valor'],

        ];
    }
}
