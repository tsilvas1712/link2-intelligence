<?php

namespace App\Livewire\Admin\Group;

use App\Models\Grupo;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination;
    public $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'nome', 'label' => 'Grupo'],

    ];

    public function render()
    {
        return view('livewire.admin.group.main');
    }

    #[Computed]
    public function grupos(): LengthAwarePaginator
    {

        return Grupo::query()->paginate();
    }
}
