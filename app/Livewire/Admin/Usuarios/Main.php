<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
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
        return view('livewire.admin.usuarios.main');
    }

    #[Computed]
    public function getUsuarios(): LengthAwarePaginator
    {
        return User::query()
            ->when($this->search, function ($query) {
                return $query
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'email', 'label' => 'E-mail'],
            ['key' => 'cargo', 'label' => 'Cargo']
        ];
    }
}
