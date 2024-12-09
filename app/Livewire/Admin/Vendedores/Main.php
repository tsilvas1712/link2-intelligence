<?php

namespace App\Livewire\Admin\Vendedores;

use App\Models\Vendedor;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination;

    public $search;
    public function render()
    {
        return view('livewire.admin.vendedores.main');
    }

    #[Computed]
    public function getVendedores(): LengthAwarePaginator
    {
        return Vendedor::query()
            ->when($this->search, function ($query) {
                return $query->where('nome', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nome', 'asc')
            ->paginate(10);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'nome', 'label' => 'Vendedor'],
            ['key' => 'cpf', 'label' => 'CPF']
        ];
    }
}
