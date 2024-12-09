<?php

namespace App\Livewire\Admin\Filiais;

use App\Models\Filial;
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
        return view('livewire.admin.filiais.main');
    }

    #[Computed]
    public function getFiliais(): LengthAwarePaginator
    {
        return Filial::query()
            ->when($this->search, function ($query) {
                return $query->where('filial', 'like', '%' . $this->search . '%');
            })
            ->orderBy('filial', 'asc')
            ->paginate(10);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'filial', 'label' => 'Filial']
        ];
    }
}
